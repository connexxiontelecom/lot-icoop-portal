<?php
namespace App\Controllers;

class WithdrawalApplication extends BaseController {

  function index() {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $page_data['page_title'] = 'Withdrawal Application';
      $page_data['savings_types'] = $this->_get_savings_types($staff_id);
      return view('service-forms/withdrawal-application', $page_data);
    }
    return redirect('auth/login');
  }
}