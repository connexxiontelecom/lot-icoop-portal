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
                        <p>View activity on any savings type here.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="nk-block nk-block-lg mt-3">
                  <div class="row g-gs">
                    <div class="col-lg-8">
                      <div class="card card-bordered h-100">
                        <div class="card-inner">
                          <div class="card-title-group align-start mb-3">
                            <div class="card-title">
                              <h6 class="title">Savings Types</h6>
                              <p class="mt-2">
                                Select a Savings Type and a date range to view a comprehensive account activity for this account during this date range.
                              </p>
                            </div>
                          </div><!-- .card-title-group -->
                          <div class="nk-order-ovwg">
                            <div class="row g-4 align-end">
                              <div class="col-xxl-8">
                                <form action="#">
                                  <div class="form-group mt-2">
                                    <div class="form-control-wrap">
                                      <select class="form-select form-control form-control-xl" data-ui="xl" id="savings-type" name="savings_type">
                                        <option value="default_option">Default Option</option>
                                      </select>
                                      <label class="form-label-outlined" for="savings-type">Select Savings Type</label>
                                    </div>
                                  </div>
                                  <div class="form-group mt-2">
                                    <div class="form-control-wrap">
                                      <div class="form-icon form-icon-right">
                                        <em class="icon ni ni-calendar-alt"></em>
                                      </div>
                                      <input type="text" class="form-control form-control-xl form-control-outlined date-picker" id="start-date" name="start_date">
                                      <label class="form-label-outlined" for="start-date">Start Date</label>
                                    </div>
                                  </div>
                                  <div class="form-group mb-3">
                                    <div class="form-control-wrap">
                                      <div class="form-icon form-icon-right">
                                        <em class="icon ni ni-calendar-alt"></em>
                                      </div>
                                      <input type="text" class="form-control form-control-xl form-control-outlined date-picker" id="end-date" name="end_date">
                                      <label class="form-label-outlined" for="end-date">End Date</label>
                                    </div>
                                  </div>
                                </form>
                              </div><!-- .col -->
                            </div>
                          </div><!-- .nk-order-ovwg -->
                        </div><!-- .card-inner -->
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
    <div class="modal fade zoom" tabindex="-1" id="savings-modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Savings Types</h5>
            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
              <em class="icon ni ni-cross"></em>
            </a>
          </div>
          <div class="modal-body">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem similique earum necessitatibus nesciunt! Quia id expedita asperiores voluptatem odit quis fugit sapiente assumenda sunt voluptatibus atque facere autem, omnis explicabo.</p>
          </div>
          <div class="modal-footer bg-light">
            <span class="sub-text">Modal Footer Text</span>
          </div>
        </div>
      </div>
    </div>
    <?php include(APPPATH.'/Views/_scripts.php'); ?>
  </body>
</html>
