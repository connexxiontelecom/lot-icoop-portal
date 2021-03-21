<?php
namespace App\Controllers;

class OutstandingLoans extends BaseController {

	function index() {
		if ($this->session->active) {
			$page_data['page_title'] = 'Outstanding Loans';
			$page_data['outstanding_loans'] = $this->_get_outstanding_loans();
			return view('outstanding-loans/index', $page_data);
		}
		return redirect('auth/login');
	}

	function view_outstanding_loan($loan_id) {
		if ($this->session->active) {
			$loan_exists = $this->loanModel->where('loan_id', $loan_id)->first();
			if ($loan_id && $loan_exists) {
				$page_data['page_title'] = 'View Outstanding Loan';
				$page_data['outstanding_loan_details'] = $this->_get_outstanding_loan_details($loan_id);
				return view('outstanding-loans/outstanding-loans-ledger', $page_data);
			}
			return redirect('outstanding-loans');
		}
		return redirect('auth/login');
	}

	private function _get_outstanding_loan_details($loan_id): array {
		$staff_id = $this->session->get('staff_id');
		$outstanding_loan_details = array();
		$cooperator_loan_details = $this->loanModel->get_cooperator_loans_by_staff_id_loan_id($staff_id, $loan_id);
		if (empty($cooperator_loan_details)) {
			// If the query returns empty then the loan has been disbursed but no activity has taken place
			// (i.e no interest or repayment has been made).
			// So we query for loan details but skip repayment details.
			$outstanding_loan_details['loan_details'] = $this->loanModel->get_cooperator_loans_no_repayment_by_staff_id_loan_id($staff_id, $loan_id);
			$outstanding_loan_details['no_activity'] = true;
		} else {
			$outstanding_loan_details['loan_details'] = $cooperator_loan_details;
			$outstanding_loan_details['no_activity'] = false;
		}
		return $outstanding_loan_details;
	}

	private function _get_outstanding_loans(): array {
		$staff_id = $this->session->get('staff_id');
		$cooperator_loans = $this->loanModel->where('staff_id', $staff_id)->findAll();
		$outstanding_loans = array();
		$i = 0;
		foreach ($cooperator_loans as $cooperator_loan) {
			if ($cooperator_loan['disburse'] == 1 && $cooperator_loan['paid_back'] == 0) {
				$total_dr = 0;
				$total_cr = 0;
				$total_interest = 0;
				$cooperator_loan_details = $this->loanModel->get_cooperator_loans_by_staff_id_loan_id($staff_id, $cooperator_loan['loan_id']);
				if (!empty($cooperator_loan_details)) {
					foreach ($cooperator_loan_details as $cooperator_loan_detail) {
						$cooperator_loan_detail->lr_dctype == 1 ? $total_cr += $cooperator_loan_detail->lr_amount : false;
						$cooperator_loan_detail->lr_dctype == 2 ? $total_dr += $cooperator_loan_detail->lr_amount : false;
						$cooperator_loan_detail->lr_interest == 1 ? $total_interest += $cooperator_loan_detail->lr_amount: false;
					}
				}
				$outstanding_loans[$i] = array(
					'loan_id' => $cooperator_loan_details[0]->loan_id,
					'loan_type' => $cooperator_loan_details[0]->loan_description,
					'loan_principal' => $cooperator_loan_details[0]->amount,
					'total_interest' =>$total_interest,
					'total_dr' => $total_dr,
					'total_cr' => $total_cr,
					'loan_balance' => $cooperator_loan_details[0]->amount + ($total_dr - $total_cr),
				);
				$i++;
			}
		}
		return $outstanding_loans;
	}
}