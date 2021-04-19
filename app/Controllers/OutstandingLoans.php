<?php
namespace App\Controllers;

class OutstandingLoans extends BaseController {

	function index() {
		if ($this->session->active) {
			$page_data['page_title'] = 'Outstanding Loans';
			$page_data['outstanding_loans'] = $this->_get_user_loans(0);
			return view('outstanding-loans/index', $page_data);
		}
		return redirect('auth/login');
	}

	function view_outstanding_loan($loan_id) {
		if ($this->session->active) {
			$loan_exists = $this->loanModel->where('loan_id', $loan_id)->first();
			if ($loan_id && $loan_exists) {
				$page_data['page_title'] = 'View Outstanding Loan';
				$page_data['outstanding_loan_details'] = $this->_get_user_loan_details($loan_id);
				return view('outstanding-loans/outstanding-loans-ledger', $page_data);
			}
			return redirect('outstanding-loans');
		}
		return redirect('auth/login');
	}
}