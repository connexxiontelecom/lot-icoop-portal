<?php
namespace App\Controllers;

class SavingsVariation extends BaseController {

  function index() {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $page_data['page_title'] = 'Savings Variation';
      $page_data['savings_types'] = $this->_get_savings_types($staff_id);
      return view('service-forms/savings-variation', $page_data);
    }
    return redirect('auth/login');
  }
}