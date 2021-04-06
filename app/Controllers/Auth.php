<?php
namespace App\Controllers;

class Auth extends BaseController {

  function login() {
    if ($this->session->active) {
      return redirect('/');
    }
    $page_data['page_title'] = 'Login';
    return view('auth/login', $page_data);
  }

  function auth_login() {
    // @TODO refactor method to use proper Request pattern
    extract($_POST);
    if ($staff_id && $password) {
      $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();
      if ($cooperator) {
      	if (password_verify($password, $cooperator['cooperator_password'])) {
      		$user_data = array(
            'staff_id' => $cooperator['cooperator_staff_id'],
            'firstname' => $cooperator['cooperator_first_name'],
            'lastname' => $cooperator['cooperator_last_name'],
            'othername' => $cooperator['cooperator_other_name'],
			      'dob' => $cooperator['cooperator_dob'],
			      'email' => $cooperator['cooperator_email'],
			      'address' => $cooperator['cooperator_address'],
			      'city' => $cooperator['cooperator_city'],
			      'state' => $this->stateModel->where('state_id', $cooperator['cooperator_state_id'])->first(),
			      'department' => $this->departmentModel->where('department_id', $cooperator['cooperator_department_id'])->first(),
			      'location' => $this->locationModel->where('location_id', $cooperator['cooperator_location_id'])->first(),
			      'payroll_group' => $this->payrollGroupModel->where('pg_id', $cooperator['cooperator_payroll_group_id'])->first(),
			      'bank' => $this->bankModel->where('bank_id', $cooperator['cooperator_bank_id'])->first(),
			      'account_number' => $cooperator['cooperator_account_number'],
			      'bank_branch' => $cooperator['cooperator_bank_branch'],
			      'sort_code' => $cooperator['cooperator_sort_code'],
			      'date' => $cooperator['cooperator_date'],
			      'approved_date' => $cooperator['cooperator_approved_date'],
			      'savings' => $cooperator['cooperator_savings'],
			      'status' => $cooperator['cooperator_status'],
			      'regular_savings' => $this->_get_regular_savings_amount($cooperator['cooperator_staff_id']),
			      'savings_types_amounts_list' => $this->_get_savings_types_amounts($cooperator['cooperator_staff_id']),
			      'active' => true
		      );
      		$this->session->set($user_data);
		      $this->session->setFlashdata('login_success', 'You have logged in successfully!');
//		      print_r($this->_get_savings_types_amounts($user_data['staff_id']));
		      return redirect('dashboard');
	      }
      } else {
      	print_r('Not Found');
      }
    }
  }

  function logout() {
  	if ($this->session->active) {
  		$this->session->stop();
  		$this->session->destroy();
	  }
  	return redirect('auth/login');
  }

  private function _get_savings_types_amounts($staff_id): array {
    $savings_types = $this->_get_savings_types($staff_id);
    $savings_types_amounts = array();
    foreach ($savings_types as $savings_type) {
      $total_dr = 0;
      $total_cr = 0;
      $savings_payment_amounts = $this->paymentDetailModel->get_all_payment_details_by_id($staff_id, $savings_type['contribution_type_id']);
      foreach ($savings_payment_amounts as $savings_payment_amount) {
        if ($savings_payment_amount->pd_drcrtype == 1) $total_cr += $savings_payment_amount->pd_amount;
        if ($savings_payment_amount->pd_drcrtype == 2) $total_dr += $savings_payment_amount->pd_amount;
      }
      $savings_types_amounts[$savings_type['contribution_type_name']] = $total_cr - $total_dr;
    }
    return $savings_types_amounts;
  }
}