<?php
namespace App\Controllers;

class OutstandingLoans extends BaseController {

	function index() {
		if ($this->session->active) {
			$page_data['page_title'] = 'Outstanding Loans';
			return view('outstanding-loans/index', $page_data);
		}
		return redirect('auth/login');
	}
}