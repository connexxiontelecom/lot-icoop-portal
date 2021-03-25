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

  function get_guarantor_cooperators() {
    $post_data = $this->request->getPost();
    $staff_id = $this->session->get('staff_id');
    $response_data = array();
    if ($post_data) {
      $cooperators = $this->cooperatorModel->search_cooperators($post_data['search']);
      foreach ($cooperators as $cooperator) {
        if ($cooperator->cooperator_staff_id != $staff_id)
          $response_data[] = $cooperator->cooperator_staff_id . ', ' . $cooperator->cooperator_first_name . ' ' . $cooperator->cooperator_last_name;
      }
    }
    return $this->response->setJSON($response_data);
  }
}