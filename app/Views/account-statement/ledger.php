<?php
$session = session();
?>
<!DOCTYPE html>
<html lang="en" class="js">
<?php include(APPPATH.'/Views/_head.php'); ?>
<body class="nk-body npc-crypto bg-white has-sidebar">
<div class="nk-app-root">
	<div class="nk-main">
		<?php include(APPPATH.'/Views/_sidebar.php'); ?>
		<div class="nk-wrap">
			<?php include(APPPATH.'/Views/_header.php'); ?>
			<div class="nk-content nk-content-fluid">
				<div class="container-xl wide-lg">
					<div class="nk-content-body">
            <div class="components-preview">
              <div class="nk-block-head nk-block-head-lg wide-sm">
                <div class="nk-block-head-content">
                  <div class="nk-block-head-sub"><a class="back-to" href="<?=site_url('account-statement')?>"><em class="icon ni ni-arrow-left"></em><span>Account Statement</span></a></div>
                  <h2 class="nk-block-title fw-normal">View Account Statement</h2>
                  <div class="nk-block-des">
                    <p class="lead">Using <a href="https://datatables.net/" target="_blank">DataTables</a>, add advanced interaction controls to your HTML tables. It is a highly flexible tool and all advanced features allow you to display table instantly and nice way.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-block-head-content">
                    <h4 class="nk-block-title"><?= $savings_type['contribution_type_name']?> Ledger</h4>
                    <div class="nk-block-des">
                      <p>Using the most basic table markup, here’s how <code class="code-class">.table</code> based tables look by default.</p>
                    </div>
                  </div>
                </div>
                <div class="card card-preview">
                  <div class="card-inner">
                    <table class="datatable-init table">
                      <thead>
                      <tr>
                        <th>Date</th>
                        <th>Narration</th>
                        <th class="text-right">DR</th>
                        <th class="text-right">CR</th>
                        <th class="text-right">Balance</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($payment_details)): $balance = 0; $total_dr = 0; $total_cr = 0; foreach ($payment_details as $payment_detail): ?>
                          <tr>
                            <td>
                              <?php
                                $transaction_date = DateTime::createFromFormat('Y-m-d', $payment_detail->pd_transaction_date);
                                $transaction_date = $transaction_date->format('d M Y');
                                echo $transaction_date;
                              ?>
                            </td>
                            <td>
                              <?=ucfirst($payment_detail->pd_narration)?>
                            </td>
                            <td class="text-right text-danger">
                              <?php
                                if ($payment_detail->pd_drcrtype == 2){
                                  echo number_format($payment_detail->pd_amount, 2, '.', ',');
                                  $balance -= $payment_detail->pd_amount;
                                  $total_dr += $payment_detail->pd_amount;
                                }
                              ?>
                            </td>
                            <td class="text-right text-success">
	                            <?php
	                            if ($payment_detail->pd_drcrtype == 1){
		                            echo number_format($payment_detail->pd_amount, 2, '.', ',');
		                            $balance += $payment_detail->pd_amount;
		                            $total_cr += $payment_detail->pd_amount;
	                            }
	                            ?>
                            </td>
                            <td class="text-right">
                              <?=number_format($balance, 2, '.', ',');?>
                            </td>
                          </tr>
                        <?php endforeach; endif;?>
                        <tr class="border-primary">
                          <td class="font-weight-bolder">
                            Total
                          </td>
                          <td></td>
                          <td class="text-right text-danger">
                            <?= number_format($total_dr, 2, '.', ',');?>
                          </td>
                          <td class="text-right text-success">
                            <?= number_format($total_cr, 2, '.', ',');?>
                          </td>
                          <td class="text-right">
                            <?= number_format($balance, 2, '.', ',');?>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div><!-- .card-preview -->
              </div> <!-- nk-block -->
            </div><!-- .components-preview -->
					</div>
				</div>
			</div>
			<?php include(APPPATH.'/Views/_footer.php'); ?>
		</div>
	</div>
</div>
<?php include(APPPATH.'/Views/_scripts.php'); ?>
</body>
</html>
