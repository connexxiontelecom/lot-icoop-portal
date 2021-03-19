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
				$cooperator_loan_details = $this->loanModel->get_outstanding_loans_by_staff_id_loan_id($staff_id, $cooperator_loan['loan_id']);
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