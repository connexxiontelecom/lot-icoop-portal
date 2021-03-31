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
                  <h2 class="nk-block-title fw-normal">Withdrawal Application</h2>
                  <div class="nk-block-des">
                    <p>Submit a new withdrawal application here.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-blood-head-content">
                    <h4 class="nk-block-title">New Withdrawal Application Form</h4>
                  </div>
                </div>
                <div class="row gy-4">
                  <div class="col-lg-7">
                    <div class="card card-preview">
                      <div class="card-inner">
                        <form action="" class="form-validate" id="withdrawal-application">
                          <div class="preview-block">
                            <span class="preview-title-lg overline-title">Withdrawal Details</span>
                          </div>
                          <div class="row gy-3">
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <label for="savings-type" class="form-label font-weight-bolder">Savings Type</label>
                                <div class="form-control-wrap">
                                  <select name="savings_type" id="savings-type" class="form-select form-control">
                                    <option value="default">Default Value</option>
                                    <?php if (!empty($savings_types)): foreach ($savings_types as $savings_type):?>
                                      <option value="<?=$savings_type['contribution_type_id']?>">
                                        <?=$savings_type['contribution_type_name']?>
                                      </option>
                                    <?php endforeach; endif;?>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
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
