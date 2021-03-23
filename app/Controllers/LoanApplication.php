<?php
namespace App\Controllers;

class LoanApplication extends BaseController {

  function index() {
    if ($this->session->active) {
      $page_data['page_title'] = 'Loan Application';
      $page_data['loan_types'] = $this->loanSetupModel->where('status', 1)->findAll();
      return view('service-forms/loan-application', $page_data);
    }
    return redirect('auth/login');
  }

  function get_loan_setup_details($loan_setup_id) {
    $loan_setup_details = $this->loanSetupModel->find($loan_setup_id);
    return json_encode($loan_setup_details);
  }
}