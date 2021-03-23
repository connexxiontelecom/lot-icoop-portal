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
                  <h2 class="nk-block-title fw-normal">Loan Application</h2>
                  <div class="nk-block-des">
                    <p>Submit a new loan application here.</p>
                  </div>
                </div>
              </div><!-- .nk-block-head -->
              <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                  <div class="nk-blood-head-content">
                    <h4 class="nk-block-title">New Loan Application Form</h4>
                  </div>
                </div>
                <div class="row gy-4">
                  <div class="col-lg-7">
                    <div class="card card-preview">
                      <div class="card-inner">
                        <form action="" method="post" class="form-validate">
                          <div class="preview-block">
                            <span class="preview-title-lg overline-title">Loan Details</span>
                          </div>
                          <div class="row gy-4">
                            <div class="col-12">
                              <div class="form-group mt-3">
                                <div class="form-control-wrap">
                                  <select class="form-select form-control form-control-xl" data-ui="xl" id="loan-type" name="loan_type" required>
                                    <option value="default" class="bold">Default Option</option>
                                    <?php if (!empty($loan_types)): foreach ($loan_types as $loan_type): ?>
                                      <option value="<?=$loan_type['loan_setup_id']?>">
                                        <?=$loan_type['loan_description']?>
                                      </option>
                                    <?php endforeach; endif;?>
                                  </select>
                                  <label class="form-label-outlined" for="loan-type">Loan Type</label>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <div class="form-control-wrap">
                                  <input type="number" class="form-control form-control-xl form-control-outlined" id="loan-duration" name="loan_duration" required>
                                  <label class="form-label-outlined" for="loan-duration">Loan Duration (Months)</label>
                                </div>
                                <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="loan-alert" hidden>
                                  <em class="icon ni ni-alert-circle"></em> <strong>Order has been placed</strong>. Your will be redirect for make your payment.
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-5">
                    <div class="card card-preview">
                      <div class="card-inner">
                        <div class="preview-block">
                          <span class="preview-title-lg overline-title">Loan Terms</span>
                        </div>
                        <div class="alert alert-icon alert-secondary mt-1 mb-1" role="alert" id="get-started">
                          <em class="icon ni ni-alert-circle"></em> Please select a loan type to get started.
                        </div>
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="qualification-age-passed" hidden>
                          <em class="icon ni ni-check-circle"></em>You have been a member long enough to qualify for this loan
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="qualification-age-failed" hidden>
                          <em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">We're Sorry</span>. You have not been a member long enough to qualify for this loan
                        </div>
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
<script>
  $(document).ready(function () {
    let loanDuration = 0
    let loanCreditLimit = 0
    let loanAgeQualification = 0
    let userApprovedDate = moment('<?= $session->get('approved_date')?>')
    let today = moment()
    let monthsDifference = today.diff(userApprovedDate, 'months', true)

    // Perform all these actions when user selects the loan type
    $(document).on('change', '#loan-type', function(e) {
      e.preventDefault()
      let loanType = $(this).val()
      if (loanType !== '' && loanType !== 'default') {
        $.ajax({
          type: 'GET',
          url: 'loan-application/get-loan-setup-details/'+loanType,
          success: function(response) {
            let loanSetupDetails = JSON.parse(response)
            loanDuration = loanSetupDetails.max_repayment_periods
            loanCreditLimit = loanSetupDetails.max_credit_limit
            loanAgeQualification = loanSetupDetails.age_qualification
            $('#get-started').attr('hidden', true)
            if (monthsDifference > loanAgeQualification) {
              // The user has been approved long enough to qualify for the loan type
              $('#qualification-age-passed').attr('hidden', false)
              $('#qualification-age-failed').attr('hidden', true)
            } else {
              $('#qualification-age-failed').attr('hidden', false)
              $('#qualification-age-passed').attr('hidden', true)
            }
          }
        })
      }
    })

    $(document).on('blur', '#loan-duration', function(e) {
      e.preventDefault()
      let selectedLoanDuration = $(this).val()
      if (parseInt(selectedLoanDuration) > loanDuration) {
        $('#loan-alert').attr('hidden', false)
      } else {
        $('#loan-alert').attr('hidden', true)
      }
    })
  })
</script>
</body>
</html>
