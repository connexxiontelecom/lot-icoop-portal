<?php
namespace App\Controllers;

class AccountStatement extends BaseController {

	function index() {
		if ($this->session->active) {
			$page_data['page_title'] = 'Account Statement';
			$page_data['savings_types'] = $this->_get_savings_types();
			return view('account-statement/index', $page_data);
		}
		return redirect('auth/login');
	}

	function view_account_statement() {
		if ($this->session->active) {
			extract($_POST);
			$staff_id = $this->session->get('staff_id');
			if (\DateTime::createFromFormat('m/d/Y', $start_date)) {
				$start_date = $this->_convert_date_to_Ymd($start_date);
			}
			if (\DateTime::createFromFormat('m/d/Y', $end_date)) {
				$end_date = $this->_convert_date_to_Ymd($end_date);
			}
			if ($savings_type && $start_date && $end_date) {
				if ($savings_type != 'default') {
					$payment_details = $this->paymentDetailModel->get_payment_details_by_date_range($staff_id, $savings_type, $start_date, $end_date);
					if (!empty($payment_details)) {
						$page_data['page_title'] = 'View Account Statement';
						$page_data['payment_details'] = $payment_details;
						$page_data['savings_type_ledger'] = $this->contributionTypeModel->where('contribution_type_id', $savings_type)->first();
						$page_data['start_date'] = $start_date;
						$page_data['end_date'] = $end_date;
						$page_data['brought_forward'] = $this->_get_brought_forward($savings_type, $start_date);
						return view('account-statement/account-statement-ledger', $page_data);
					} else {
						$this->session->setFlashdata('no_payment_details', 'We could not find an activity for your criteria!');
					}
				} else {
					$this->session->setFlashdata('no_payment_details', 'Please select a valid Savings Type!');
				}
			} else {
				$this->session->setFlashdata('no_payment_details', 'Please select a valid Savings Type or Transaction Date Range!');
			}
			return redirect('account-statement');
		}
		return redirect('auth/login');
	}

	private function _get_savings_types(): array {
		$staff_id = $this->session->get('staff_id');
		$payment_details = $this->paymentDetailModel->get_payment_details_by_staff_id($staff_id);
		$savings_types = array();
		foreach($payment_details as $payment_detail) {
			$savings_type = $this->contributionTypeModel->where('contribution_type_id', $payment_detail->pd_ct_id)->first();
			array_push($savings_types, $savings_type);
		}
		return $savings_types;
	}

	private function _get_brought_forward($savings_type, $start_date): int {
	  $staff_id = $this->session->get('staff_id');
	  $payment_details = $this->paymentDetailModel->get_payment_details_before_date($staff_id, $savings_type, $start_date);
	  $total_dr = 0;
	  $total_cr = 0;
	  foreach ($payment_details as $payment_detail) {
      if ($payment_detail->pd_drcrtype == 1) $total_cr += $payment_detail->pd_amount;
      if ($payment_detail->pd_drcrtype == 2) $total_dr += $payment_detail->pd_amount;
    }
	  return $total_cr - $total_dr;
  }

	private function _convert_date_to_Ymd($date): string {
		return \DateTime::createFromFormat('m/d/Y', $date)->format('Y-m-d');
	}
}