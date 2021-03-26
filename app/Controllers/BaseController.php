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
    $this->stateModel = new StateModel();
    $this->withdrawModel = new WithdrawModel();
	}

	// calculate the difference between total credits and total debits for all payments in the regular savings type
  // to determine the total regular savings amount
  protected function _get_regular_savings_amount($staff_id): int {
    $regular_savings_contribution_type = $this->contributionTypeModel->where('contribution_type_regular', 1)->first();
    $regular_savings_payment_details = $this->paymentDetailModel->get_savings_payment_details_by_id($staff_id, $regular_savings_contribution_type['contribution_type_id']);
    $total_dr = 0;
    $total_cr = 0;
    foreach ($regular_savings_payment_details as $regular_savings_payment_detail) {
      if ($regular_savings_payment_detail->pd_drcrtype == 1) $total_cr += $regular_savings_payment_detail->pd_amount;
      if ($regular_savings_payment_detail->pd_drcrtype == 2) $total_dr += $regular_savings_payment_detail->pd_amount;
    }
    return $total_cr - $total_dr;
  }

  protected function _create_new_notification($type, $topic, $receiver_id, $details) {
	  $notification_data = array();
    $notification_data['type'] = $type;
    $notification_data['topic'] = $topic;
    $notification_data['receiver_id'] = $receiver_id;
    $notification_data['details'] = $details;
    $this->notificationModel->save($notification_data);
  }
}
