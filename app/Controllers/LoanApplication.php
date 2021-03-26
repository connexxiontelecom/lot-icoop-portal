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
    if ($this->session->active) {
      // @TODO refactor method to use Request pattern
      $loan_setup_details = $this->loanSetupModel->find($loan_setup_id);
      return json_encode($loan_setup_details);
    }
    return redirect('auth/login');
  }

  function get_guarantor_cooperators() {
    if ($this->session->active) {
      $post_data = $this->request->getPost();
      $staff_id = $this->session->get('staff_id');
      $response_data = array();
      if ($post_data) {
        $cooperators = $this->cooperatorModel->search_cooperators($post_data['search']);
        $guarantor_1 = $post_data['guarantor1'];
        foreach ($cooperators as $cooperator) {
          $guarantor_id = explode(',', $guarantor_1)[0];
          if ($cooperator->cooperator_staff_id != $staff_id && $cooperator->cooperator_staff_id != $guarantor_id) {
            $response_data[] = $cooperator->cooperator_staff_id . ', ' . $cooperator->cooperator_first_name . ' ' . $cooperator->cooperator_last_name;
          }
        }
      }
      return $this->response->setJSON($response_data);
    }
    return redirect('auth/login');
  }

  function submit_loan_application() {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $staff_status = $this->session->get('status');
      $response_data = array();
      $account_closure = $this->accountClosureModel->check_account_closure($staff_id);
      if (empty($account_closure)) {
        if ($staff_status == 2) {
          $post_data = $this->request->getPost();
          if ($post_data) {
            $loan_setup_id = $post_data['loan_type'];
            $loan_duration = $post_data['loan_duration'];
            $loan_amount = $post_data['loan_amount'];
            $guarantor_1 = $post_data['guarantor_1'];
            $guarantor_2 = $post_data['guarantor_2'];
            $filename = null;
            $file = $this->request->getFile('loan_attachment');
            if (!empty($file)) {
              if ($file->isValid() && !$file->hasMoved()) {
                $extension = $file->guessExtension();
                $extension = strtolower($extension);
                if($extension == 'pdf'){
                  $filename = $file->getRandomName();
                  $file->move('uploads/loan-attachments', $filename);
                } else {
                  $response_data['success'] = false;
                  $response_data['msg'] = 'Please submit your loan attachment as a PDF';
                  return $this->response->setJSON($response_data);
                }
              }
            }
            $response_data = $this->_submit_loan_application($loan_setup_id, $loan_amount, $loan_duration, $guarantor_1, $guarantor_2, $filename);
            return $this->response->setJSON($response_data);
          }
        } else {
          $response_data['success'] = false;
          $response_data['msg'] = 'Your account is currently frozen';
          return $this->response->setJSON($response_data);
        }
      } else {
        $response_data['success'] = false;
        $response_data['msg'] = 'Your account is currently undergoing closure';
        return $this->response->setJSON($response_data);
      }
      return $this->response->setJSON($response_data);
    }
    return redirect('auth/login');
  }

  private function _submit_loan_application($loan_setup_id, $loan_amount, $loan_duration, $guarantor_1, $guarantor_2, $filename): array {
    $staff_id = $this->session->get('staff_id');
    $firstname = $this->session->get('firstname');
    $lastname = $this->session->get('lastname');
    $othername = $this->session->get('othername');
    $response_data = array();
    $loan_setup_details = $this->loanSetupModel->find($loan_setup_id);
    // @TODO do all checks in here and submit loan application
    if ($loan_setup_details) {
      // check if user has been approved longer than the loan age qualification
      $today = date_create(date('Y-m-d'));
      $user_approved_date = date_create($this->session->get('approved_date'));
      $months_difference = date_diff($today, $user_approved_date)->format('%m');
      if ($months_difference <= $loan_setup_details['age_qualification']) {
        $response_data['success'] = false;
        $response_data['msg'] = 'You have not been a member long enough to qualify for this loan';
        return $response_data;
      }
      // check if loan duration is valid
      if (!$loan_duration) {
        $response_data['success'] = false;
        $response_data['msg'] = 'The loan duration is required';
        return $response_data;
      }
      if ($loan_duration > $loan_setup_details['max_repayment_periods']) {
        $response_data['success'] = false;
        $response_data['msg'] = 'The loan duration should not exceed the maximum repayment periods';
        return $response_data;
      }
      // check if loan amount is valid
      if (!$loan_amount) {
        $response_data['success'] = false;
        $response_data['msg'] = 'The loan amount is required';
        return $response_data;
      }
      if ($loan_amount < $loan_setup_details['min_credit_limit'] || $loan_amount > $loan_setup_details['max_credit_limit']) {
        $response_data['success'] = false;
        $response_data['msg'] = 'The loan amount should fall within the credit limit range';
        return $response_data;
      }
      // check loan PSR
      if ($loan_setup_details['psr'] == 1) {
        $regular_savings_amount = $this->_get_regular_savings_amount($staff_id);
        $psr_amount = ($loan_setup_details['psr_value'] / 100) * $loan_amount;
        if ($psr_amount > $regular_savings_amount) {
          $response_data['success'] = false;
          $response_data['msg'] = 'The PSR amount should not exceed your Regular Savings amount';
          return $response_data;
        }
      }
      $loan_application_data = array(
        'staff_id' => $staff_id,
        'name' => $firstname.' '.$othername.' '.$lastname,
        'guarantor' => $guarantor_1,
        'guarantor_2' => $guarantor_2,
        'loan_type' => $loan_setup_id,
        'duration' => $loan_duration,
        'amount' => $loan_amount,
        'applied_date' => date('Y-m-d H:i:s'),
        'attachment' => $filename
      );
      $loan_application_id = $this->loanApplicationModel->insert($loan_application_data);
      $this->_update_loan_guarantors($loan_application_id, $guarantor_1, $staff_id);
      $this->_update_loan_guarantors($loan_application_id, $guarantor_2, $staff_id);
      $response_data['success'] = true;
      $response_data['msg'] = 'You have successfully applied for a '. number_format($loan_amount, 2).'
       amount loan for '. $loan_duration.' months. Your chosen guarantors have been notified for their approval.';
      return $response_data;
    }
    $response_data['success'] = false;
    $response_data['msg'] = 'We did not find any details on this loan type';
    return $response_data;
  }

  private function _update_loan_guarantors($loan_application_id, $guarantor_id, $staff_id) {
    $guarantor_data = array(
      'loan_application_id' => $loan_application_id,
      'guarantor_id' => $guarantor_id,
      'staff_id' => $staff_id,
      'confirm' => 0
    );
    $this->loanGuarantorModel->save($guarantor_data);
    // notify guarantor of the loan application
    $notification_type = 'guarantor_notification';
    $notification_topic = 'You have been selected as a guarantor for a loan';
    $notification_receiver_id = $guarantor_id;
    $notification_details = $loan_application_id;
    $this->_create_new_notification($notification_type, $notification_topic, $notification_receiver_id, $notification_details);
  }
}