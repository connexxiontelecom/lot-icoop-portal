<?php
namespace App\Controllers;

class Home extends BaseController {

	function index() {
		if ($this->session->active) {
			$page_data['page_title'] = 'Dashboard';
			$page_data['pending_withdrawals'] = $this->_count_withdrawal_types(0);
			$page_data['approved_withdrawals'] = $this->_count_withdrawal_types(2);
			$page_data['disbursed_withdrawals'] = $this->_count_withdrawal_types(5);
			$page_data['pending_loans'] = $this->_count_pending_approved_loans(0);
			$page_data['approved_loans'] = $this->_count_pending_approved_loans(1);
			$page_data['disbursed_loans'] = $this->_count_disbursed_loans();
			return view('index', $page_data);
		}
	  return redirect('auth/login');
	}

	function account_statement() {
		if ($this->session->active) {
			$page_data['page_title'] = 'Account Statement';
			return view('account-statement/index', $page_data);
		}
		return redirect('auth/login');
	}

	private function _count_withdrawal_types($type): int {
		$withdrawals = $this->withdrawModel->where('withdraw_staff_id', $this->session->get('staff_id'))->findAll();
		$result = 0;
		foreach ($withdrawals as $withdrawal) {
			if ($withdrawal['withdraw_status'] == $type) {
				$result++;
			}
		}
		return $result;
	}

	private function _count_pending_approved_loans($type): int {
		$loanApplications = $this->loanApplicationModel->where('staff_id', $this->session->get('staff_id'))->findAll();
		$result = 0;
		foreach ($loanApplications as $loanApplication) {
			if ($loanApplication['approve'] == $type) {
				$result++;
			}
		}
		return $result;
	}

	private function _count_disbursed_loans(): int {
		$loans = $this->loanModel->where('staff_id', $this->session->get('staff_id'))->findAll();
		$result = 0;
		foreach ($loans as $loan) {
			if ($loan['disburse'] == 1) {
				$result++;
			}
		}
		return $result;
	}
}
