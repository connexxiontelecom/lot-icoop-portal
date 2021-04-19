<?php
namespace App\Controllers;

use App\Models\AccountClosureModel;
use App\Models\BankModel;
use App\Models\ContributionTypeModel;
use App\Models\CooperatorModel;
use App\Models\DepartmentModel;
use App\Models\LoanApplicationModel;
use App\Models\LoanGuarantorModel;
use App\Models\LoanModel;
use App\Models\LoanSetupModel;
use App\Models\LocationModel;
use App\Models\NotificationModel;
use App\Models\PaymentDetailModel;
use App\Models\PayrollGroupModel;
use App\Models\PolicyConfigModel;
use App\Models\StateModel;
use App\Models\WithdrawModel;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class BaseController extends Controller
{
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['form', 'url', 'date'];
	protected $session;

	protected $accountClosureModel;
	protected $bankModel;
	protected $contributionTypeModel;
	protected $cooperatorModel;
	protected $departmentModel;
	protected $loanApplicationModel;
	protected $loanGuarantorModel;
	protected $loanModel;
	protected $loanSetupModel;
	protected $locationModel;
	protected $notificationModel;
	protected $paymentDetailModel;
	protected $payrollGroupModel;
	protected $policyConfigModel;
	protected $stateModel;
	protected $withdrawModel;

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
    // libraries
    $this->session = \CodeIgniter\Config\Services::session();
    // models
    $this->accountClosureModel = new AccountClosureModel();
		$this->bankModel = new BankModel();
		$this->contributionTypeModel = new ContributionTypeModel();
    $this->cooperatorModel = new CooperatorModel();
    $this->departmentModel = new DepartmentModel();
    $this->loanApplicationModel = new LoanApplicationModel();
    $this->loanGuarantorModel = new LoanGuarantorModel();
    $this->loanModel = new LoanModel();
    $this->loanSetupModel = new LoanSetupModel();
    $this->locationModel = new LocationModel();
    $this->notificationModel = new NotificationModel();
    $this->paymentDetailModel = new PaymentDetailModel();
    $this->payrollGroupModel = new PayrollGroupModel();
    $this->policyConfigModel =new PolicyConfigModel();
    $this->stateModel = new StateModel();
    $this->withdrawModel = new WithdrawModel();
	}

	// calculate the difference between total credits and total debits for all payments in the regular savings type
  // to determine the total regular savings amount
  protected function _get_regular_savings_amount($staff_id): int {
    $regular_savings_contribution_type = $this->contributionTypeModel->where('contribution_type_regular', 1)->first();
    $regular_savings_payment_details = $this->paymentDetailModel->get_all_payment_details_by_id($staff_id, $regular_savings_contribution_type['contribution_type_id']);
    $total_dr = 0;
    $total_cr = 0;
    foreach ($regular_savings_payment_details as $regular_savings_payment_detail) {
      if ($regular_savings_payment_detail->pd_drcrtype == 1) $total_cr += $regular_savings_payment_detail->pd_amount;
      if ($regular_savings_payment_detail->pd_drcrtype == 2) $total_dr += $regular_savings_payment_detail->pd_amount;
    }
    return $total_cr - $total_dr;
  }

  protected function _get_savings_types($staff_id): array {
    $payment_details = $this->paymentDetailModel->get_payment_details_by_staff_id($staff_id);
    $savings_types = array();
    foreach($payment_details as $payment_detail) {
      $savings_type = $this->contributionTypeModel->where('contribution_type_id', $payment_detail->pd_ct_id)->first();
      array_push($savings_types, $savings_type);
    }
    return $savings_types;
  }

  protected function _create_new_notification($type, $topic, $receiver_id, $details) {
	  $notification_data = array();
    $notification_data['type'] = $type;
    $notification_data['topic'] = $topic;
    $notification_data['receiver_id'] = $receiver_id;
    $notification_data['details'] = $details;
    $this->notificationModel->save($notification_data);
  }

  protected function _get_user_loans($paid_back): array {
	  $staff_id = $this->session->get('staff_id');
    $cooperator_loans = $this->loanModel->where('staff_id', $staff_id)->findAll();
    $loans = array();
    $i = 0;
    foreach ($cooperator_loans as $cooperator_loan) {
      if ($cooperator_loan['disburse'] == 1 && $cooperator_loan['paid_back'] == $paid_back) {
        $total_dr = 0;
        $total_cr = 0;
        $total_interest = 0;
        $cooperator_loan_details = $this->loanModel->get_cooperator_loans_by_staff_id_loan_id($staff_id, $cooperator_loan['loan_id']);
        if (!empty($cooperator_loan_details)) {
          foreach ($cooperator_loan_details as $cooperator_loan_detail) {
            $cooperator_loan_detail->lr_dctype == 1 ? $total_cr += $cooperator_loan_detail->lr_amount : 0;
            $cooperator_loan_detail->lr_dctype == 2 ? $total_dr += $cooperator_loan_detail->lr_amount : 0;
            $cooperator_loan_detail->lr_interest == 1 ? $total_interest += $cooperator_loan_detail->lr_amount: 0;
          }
        }
        $loans[$i] = array(
          'loan_id' => $cooperator_loan_details[0]->loan_id,
          'loan_type' => $cooperator_loan_details[0]->loan_description,
          'loan_principal' => $cooperator_loan_details[0]->amount,
          'total_interest' =>$total_interest,
          'total_dr' => $total_dr,
          'total_cr' => $total_cr,
          'loan_balance' => $cooperator_loan_details[0]->amount + ($total_dr - $total_cr),
        );
        $i++;
      }
    }
    return $loans;
  }

  protected function _get_user_loan_details($loan_id) {
    $staff_id = $this->session->get('staff_id');
    $loan_details = array();
    $cooperator_loan_details = $this->loanModel->get_cooperator_loans_by_staff_id_loan_id($staff_id, $loan_id);
    if (empty($cooperator_loan_details)) {
      // If the query returns empty then the loan has been disbursed but no activity has taken place
      // (i.e no interest or repayment has been made).
      // So we query for loan details but skip repayment details.
      $loan_details['loan_details'] = $this->loanModel->get_cooperator_loans_no_repayment_by_staff_id_loan_id($staff_id, $loan_id);
      $loan_details['no_activity'] = true;
    } else {
      $loan_details['loan_details'] = $cooperator_loan_details;
      $loan_details['no_activity'] = false;
    }
    return $loan_details;
  }
}
