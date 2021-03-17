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
    extract($_POST);
    if ($staff_id && $password) {
      $cooperator = $this->cooperatorModel->where('cooperator_staff_id', $staff_id)->first();
      if ($cooperator) {
      	if (password_verify($password, $cooperator['cooperator_password'])) {
      		$user_data = array(
      			'firstname' => $cooperator['cooperator_first_name'],
			      'lastname' => $cooperator['cooperator_last_name'],
			      'othername' => $cooperator['cooperator_other_name'],
			      'staff_id' => $cooperator['cooperator_staff_id'],
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
			      'savings' => $cooperator['cooperator_savings'],
			      'status' => $cooperator['cooperator_status'],
			      'active' => true
		      );
      		$this->session->set($user_data);
		      $this->session->setFlashdata('login_success', 'You have logged in successfully!');
		      return redirect('/');
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
}