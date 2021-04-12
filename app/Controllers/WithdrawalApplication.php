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

  function compute_balance($savings_type) {
    $staff_id = $this->session->get('staff_id');
    $status = $this->session->get('status');
    if ($status == 2) {
      $savings_type_detail = $this->contributionTypeModel->find($savings_type);
      $encumbrance_amount = 0;
      if ($savings_type_detail['contribution_type_regular'] == 1) {
        $active_loans = $this->loanModel->where([
          'staff_id' => $staff_id,
          'paid_back' => 0,
          'disburse' => 1
        ])->findAll();
        foreach ($active_loans as $active_loan) {
          $encumbrance_amount += $active_loan['encumbrance_amount'];
        }
      }
      $savings_amount = $this->_get_savings_type_amount($staff_id, $savings_type);
      $withdrawal_amount = 0;
      if ($savings_amount) {
        $policy_config = $this->policyConfigModel->first();
        $max_withdrawal = $policy_config['max_withdrawal_amount'];
        $withdrawal_amount = ($max_withdrawal / 100) * $savings_amount;
      }
      $response_data = [
        'success' => true,
        'savings_balance' => $savings_amount,
        'withdrawal_balance' => $withdrawal_amount - $encumbrance_amount,
        'encumbered_amount' => $encumbrance_amount
      ];
      return $this->response->setJSON($response_data);
    }
    $response_data = [
      'success' => false,
      'msg' => 'User account must be active to withdraw'
    ];
    return $this->response->setJSON($response_data);
  }

  private function _get_savings_type_amount($staff_id, $savings_type) {
    $savings_type_payments = $this->paymentDetailModel->where(['pd_staff_id' => $staff_id, 'pd_ct_id' => $savings_type])->findAll();
    $total_dr = 0;
    $total_cr = 0;
    foreach ($savings_type_payments as $savings_type_payment) {
      if ($savings_type_payment['pd_drcrtype'] == 1) $total_cr += $savings_type_payment['pd_amount'];
      if ($savings_type_payment['pd_drcrtype'] == 2) $total_dr += $savings_type_payment['pd_amount'];
    }
    return $total_cr - $total_dr;
  }
}