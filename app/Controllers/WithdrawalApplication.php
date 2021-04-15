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

  /**
   * Calculate balances for the chosen savings type when a cooperator 
   * wishes to make a withdrawal.
   * 
   * Get details on the savings type and check if it is the regular savings type.
   * If it is, calculate the encumbered amount from all active loans. Next, calculate
   * the total savings amount for the savings type for the cooperator and remove the 
   * encumbered amount from it to get the actual savings amount that can be withdrawn from.
   * Then, calculate the maximum percentage of the actual savings amount that can be
   * withdrawn and remove the withdrawal charge from it to calculate the withdrawable amount.
   * 
   * @see private function _get_savings_type_amount
   * @link withdrawal-application/compute-balance/${savingsType}
   * 
   * @param $savings_type ID of the contribution type to compute balance for.
   * @return AJAX response data with computed balances or error message.
   */
  function compute_balance($savings_type) {
    $staff_id = $this->session->get('staff_id');
    $status = $this->session->get('status');
    if ($status == 2) {
      $savings_type_detail = $this->contributionTypeModel->find($savings_type);
      $encumbered_amount = 0;
      if ($savings_type_detail['contribution_type_regular'] == 1) {
        $active_loans = $this->loanModel->where([
          'staff_id' => $staff_id,
          'paid_back' => 0,
          'disburse' => 1
        ])->findAll();
        foreach ($active_loans as $active_loan) {
          $encumbered_amount += $active_loan['encumbrance_amount'];
        }
      }
      $savings_amount = $this->_get_savings_type_amount($staff_id, $savings_type);
      $actual_savings_amount = $savings_amount - $encumbered_amount;
      $withdrawal_amount = 0;
      if ($savings_amount) {
        $policy_config = $this->policyConfigModel->first();
        $max_withdrawal = $policy_config['max_withdrawal_amount'];
        $withdrawal_charges = $policy_config['savings_withdrawal_charge'];
        $withdrawal_amount = ($max_withdrawal / 100) * $actual_savings_amount;
        $withdrawable_amount = $withdrawal_amount * (1 - ($withdrawal_charges / 100));
      }
      $response_data = [
        'success' => true,
        'savings_amount' => number_format($savings_amount, 2),
        'withdrawable_amount' => number_format($withdrawable_amount, 2),
        'encumbered_amount' => number_format($encumbered_amount, 2)
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