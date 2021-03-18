<?php
namespace App\Models;
use CodeIgniter\Model;

class LoanModel extends Model {
	protected $table = 'loans';
	protected $primaryKey = 'loan_id';
	protected $allowedFields = [
		'loan_app_id', 'staff_id', 'schedule_master_id', 'amount', 'interest_rate', 'loan_type',
		'interest', 'disburse', 'disburse_date', 'cart', 'created_at', 'scheduled', 'paid_back'
	];
}