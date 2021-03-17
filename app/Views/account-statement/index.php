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
                <div class="nk-content-head">
<!--                  <div class="nk-block-head-sub"><span>Account Statement</span></div>-->
                  <div class="nk-block-between-md g-4">
                    <div class="nk-block-head-content">
                      <h2 class="nk-block-title fw-normal">Account Statement</h2>
                      <div class="nk-block-des">
                        <p>A list of accounts you can generate a statement from.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="nk-block nk-block-lg mt-5">
                  <div class="nk-block-head-sm">
                    <div class="nk-block-head-content">
                      <h5 class="nk-block-title title mb-1">Accounts</h5>
                    </div>
                  </div>
                  <div class="row g-gs">
                    <div class="col-md-6 col-lg-4">
                      <div class="card card-bordered">
                        <div class="nk-wgw">
                          <div class="nk-wgw-inner">
                            <a class="nk-wgw-name" href="#">
                              <div class="nk-wgw-icon">
                                <em class="icon ni ni-wallet-saving"></em>
                              </div>
                              <h5 class="nk-wgw-title title">Savings</h5>
                            </a>
                          </div>
                          <div class="nk-wgw-actions">
                            <div class="amount-sm">Here you can select a Savings Type and a year and generate a statement for this account.</div>
                          </div>
                        </div>
                      </div><!-- .card -->
                    </div><!-- .col -->
                    <div class="col-md-6 col-lg-4">
                      <div class="card card-bordered">
                        <div class="nk-wgw">
                          <div class="nk-wgw-inner">
                            <a class="nk-wgw-name" href="#">
                              <div class="nk-wgw-icon">
                                <em class="icon ni ni-wallet-saving"></em>
                              </div>
                              <h5 class="nk-wgw-title title">Outstanding Loans</h5>
                            </a>
                          </div>
                          <div class="nk-wgw-actions">
                            <div class="amount-sm">Here you can select an Outstanding Loan and generate a complete statement of activity for the loan.</div>
                          </div>
                        </div>
                      </div><!-- .card -->
                    </div><!-- .col -->
                    <div class="col-md-6 col-lg-4">
                      <div class="card card-bordered">
                        <div class="nk-wgw">
                          <div class="nk-wgw-inner">
                            <a class="nk-wgw-name" href="#">
                              <div class="nk-wgw-icon">
                                <em class="icon ni ni-wallet-saving"></em>
                              </div>
                              <h5 class="nk-wgw-title title">Finished Loans</h5>
                            </a>
                          </div>
                          <div class="nk-wgw-actions">
                            <div class="amount-sm">Here you can select a Finished Loan and generate a complete statement of activity for the loan.</div>
                          </div>
                        </div>
                      </div><!-- .card -->
                    </div><!-- .col -->
                  </div><!-- .row -->
                </div><!-- .nk-block -->
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
