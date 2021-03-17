<?php
namespace App\Controllers;

class Home extends BaseController {
	public function index() {
		if ($this->session->active) {
			$page_data['page_title'] = 'Dashboard';
			$page_data['contribution_types'] = $this->contributionTypeModel->findAll();
			return view('index', $page_data);
		}
	  return redirect('auth/login');
	}
}
