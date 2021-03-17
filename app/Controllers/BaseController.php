<?php

namespace App\Controllers;

use App\Models\BankModel;
use App\Models\ContributionTypeModel;
use App\Models\CooperatorModel;
use App\Models\DepartmentModel;
use App\Models\LoanApplicationModel;
use App\Models\LoanModel;
use App\Models\LocationModel;
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
	protected $helpers = ['form', 'url'];
	protected $session;

	protected $bankModel;
	protected $contributionTypeModel;
	protected $cooperatorModel;
	protected $departmentModel;
	protected $loanApplicationModel;
	protected $loanModel;
	protected $locationModel;
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
		$this->bankModel = new BankModel();
		$this->contributionTypeModel = new ContributionTypeModel();
    $this->cooperatorModel = new CooperatorModel();
    $this->departmentModel = new DepartmentModel();
    $this->loanApplicationModel = new LoanApplicationModel();
    $this->loanModel = new LoanModel();
    $this->locationModel = new LocationModel();
    $this->payrollGroupModel = new PayrollGroupModel();
    $this->stateModel = new StateModel();
    $this->withdrawModel = new WithdrawModel();
	}
}
