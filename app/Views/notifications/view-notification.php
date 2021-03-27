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
                  <div class="nk-block-head-sub"><a class="back-to" href="#"><em class="icon ni ni-arrow-left"></em><span>Notifications</span></a></div>
                  <h2 class="nk-block-title fw-normal">View Notification</h2>
                  <div class="nk-block-des"></div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-block-head-content">
                    <h4 class="nk-block-title"><?=$notification['topic']?></h4>
                    <div class="nk-block-des">
                      <p>
                        Notification received
                        <em class="font-weight-bolder">
                          <?php
                          $date = date_create($notification['created_at']);
                          echo date_format($date, 'd M Y H:i:s a');
                          ?>
                        </em>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-8">
                    <div class="card card-bordered card-full">
                      <div class="card-inner">
                        <?php if ($notification['type'] == 'guarantor_notification'):?>
                          <div class="card-title-group align-start mb-0">
                            <div class="card-subtitle">
                              <p>
                                <span class="font-weight-bolder"><?=$loan_application['name']?></span> selected you as a guarantor for their loan application.
                                You can review the details of the loan application below and either <code>confirm</code> or <code>reject</code> as guarantor.
                              </p>
                            </div>
                          </div>
                          <div class="nk-block mt-2">
                          <ul class="list list-sm list-checked">
                            <li><?=$loan_application['duration']?> Month(s) Loan Duration</li>
                            <li><em class="icon ni ni-sign-kobo"></em> <?=number_format($loan_application['amount'], 2)?> Loan Amount</li>
                            <li><?=$loan_details['loan_description']?></li>
                            <li><?=$loan_details['ls_interest_rate']?>% Interest Rate</li>
                            <li>
                              <?php
                                if ($loan_details['interest_method'] == 1) echo 'Upfront Interest Method';
                                elseif ($loan_details['interest_method'] == 2) echo 'Reducing Balance Interest Method';
                                elseif ($loan_details['interest_method'] == 3) echo 'Targeted Interest Method';
                              ?>
                            </li>
                            <li>
                              <?php
                                if ($loan_details['interest_charge_type'] == 1) echo 'Flat Interest Charge';
                                elseif ($loan_details['interest_charge_type'] == 2) echo 'Monthly Interest Charge';
                                elseif ($loan_details['interest_charge_type'] == 3) echo 'Yearly Interest Charge';
                              ?>
                            </li>
                            <li>
                              Applied on
                              <?php
                                $date = date_create($loan_application['applied_date']);
                                echo date_format($date, 'd M Y');
                              ?>
                            </li>
                          </ul>
                          <button class="btn btn-primary" type="button">Confirm</button>
                          <button class="btn btn-outline-danger" type="button">Reject</button>
                        </div>
                        <?php endif;?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
