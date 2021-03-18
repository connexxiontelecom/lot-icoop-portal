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
}