<?php
namespace App\Controllers;

class FinishedLoans extends BaseController {

  function index() {
    if ($this->session->active) {
      $page_data['page_title'] = 'Finished Loans';
      $page_data['finished_loans'] = $this->_get_user_loans(1);
      return view('finished-loans/index', $page_data);
    }
    return redirect('auth/login');
  }

  function view_finished_loan($loan_id) {
    if ($this->session->active) {
      $loan_exists = $this->loanModel->where('loan_id', $loan_id)->first();
      if ($loan_id && $loan_exists) {
        $page_data['page_title'] = 'View Finished Loan';
        $page_data['finished_loan_details'] = $this->_get_user_loan_details($loan_id);
        return view('finished-loans/finished-loans-ledger', $page_data);
      }
      return redirect('finished-loans');
    }
    return redirect('auth/login');
  }
}