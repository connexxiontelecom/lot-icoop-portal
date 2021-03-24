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
                                <label class="form-label font-weight-bolder" for="loan-type">Loan Type</label>
                                <div class="form-control-wrap">
                                  <select class="form-select form-control" data-ui="lg" data-search="on" id="loan-type" name="loan_type" required>
                                    <option value="default">Default Option</option>
                                    <?php if (!empty($loan_types)): foreach ($loan_types as $loan_type): ?>
                                      <option value="<?=$loan_type['loan_setup_id']?>">
                                        <?=$loan_type['loan_description']?>
                                      </option>
                                    <?php endforeach; endif;?>
                                  </select>
                                  <div id="loan-type-note" class="form-note"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label class="form-label font-weight-bolder" for="loan-duration">Loan Duration (Months)</label>
                                <div class="form-control-wrap">
                                  <input type="number" class="form-control form-control-lg" id="loan-duration" name="loan_duration" required disabled>
                                  <div id="loan-duration-note" class="form-note"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label class="form-label font-weight-bolder" for="loan-amount">Loan Amount</label>
                                <div class="form-control-wrap">
                                  <input type="number" class="form-control form-control-lg" id="loan-amount" name="loan_amount" required disabled>
                                  <div id="loan-amount-note" class="form-note"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label class="form-label font-weight-bolder">File Attachment (PDF)</label>
                                <div class="form-control-wrap">
                                  <div class="custom-file">
                                    <input type="file" class="custom-file-input" data-ui="lg" id="loan-attachment" name="loan_attachment" accept="application/pdf" disabled>
                                    <label class="custom-file-label" for="loan-attachment">Choose file</label>
                                  </div>
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
                        <div class="alert alert-icon alert-info mt-1 mb-1" role="alert" id="get-started">
                          <em class="icon ni ni-alert-circle"></em> Please select a loan type to get started.
                        </div>
                        <!--qualification age-->
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="qualification-age-passed" hidden>
                          <em class="icon ni ni-check-circle"></em> You have been a member long enough to qualify for this loan.
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="qualification-age-failed" hidden>
                          <em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">We're Sorry</span>. You have not been a member long enough to qualify for this loan
                        </div>
                        <!--loan duration-->
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="loan-duration-passed" hidden>
                          <em class="icon ni ni-check-circle"></em> Your selected loan duration is valid.
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="loan-duration-failed" hidden>
                          <em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">We're Sorry</span>. Your loan duration exceeds the repayment period for this loan.
                        </div>
                        <!--loan amount-->
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="loan-amount-passed" hidden>
                          <em class="icon ni ni-check-circle"></em> Your selected loan amount is valid.
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="loan-amount-failed" hidden>
                          <em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">We're Sorry</span>. Your loan amount does not fall within the credit limit range.
                        </div>
                        <!--psr amount-->
                        <div class="alert alert-icon alert-success mt-1 mb-1" role="alert" id="loan-psr-passed" hidden>
                          <em class="icon ni ni-check-circle"></em> Your PSR loan amount is valid.
                        </div>
                        <div class="alert alert-icon alert-warning mt-1 mb-1" role="alert" id="loan-psr-failed" hidden>
                          <em class="icon ni ni-alert-circle"></em><span class="font-weight-bolder">We're Sorry</span>. Your PSR loan amount should not exceed your Regular Savings.
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
  let loanDuration = 0
  let loanMaxCreditLimit = 0
  let loanMinCreditLimit = 0
  let loanAgeQualification = 0
  let loanPSR = 0
  let loanPSRValue = 0
  let userApprovedDate = moment('<?= $session->get('approved_date')?>')
  let savingsAmount = '<?= $session->get('regular_savings')?>'
  let today = moment()
  let monthsDifference = today.diff(userApprovedDate, 'months', true)

  $(document).ready(function () {
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
            loanMaxCreditLimit = loanSetupDetails.max_credit_limit
            loanMinCreditLimit = loanSetupDetails.min_credit_limit
            loanAgeQualification = loanSetupDetails.age_qualification
            loanPSR = loanSetupDetails.psr
            loanPSRValue = loanSetupDetails.psr_value
            $('#loan-type-note').html(`Loan Qualification Period <span class="text-primary">${loanAgeQualification} month(s)</span>`)
            $('#loan-duration-note').html(`Maximum Repayment Period <span class="text-primary">${loanDuration} month(s)</span>`)
            $('#loan-amount-note').html(`
              Minimum Credit Limit <span class="text-primary">${loanMinCreditLimit.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")} </span>
              ---
              Maximum Credit Limit <span class="text-primary">${loanMaxCreditLimit.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")} </span>
              ---
              Loan PSR <span class="text-primary">${loanPSRValue} % </span>
            `)
            $('#get-started').attr('hidden', true)
            if (monthsDifference > loanAgeQualification) {
              // The user has been approved long enough to qualify for the loan type
              $('#qualification-age-passed').attr('hidden', false)
              $('#qualification-age-failed').attr('hidden', true)
              $('#loan-duration').attr('disabled', false)
              $('#loan-amount').attr('disabled', false)
            } else {
              $('#qualification-age-failed').attr('hidden', false)
              $('#qualification-age-passed').attr('hidden', true)
              $('#loan-duration').val('')
              $('#loan-duration-passed').attr('hidden', true)
              $('#loan-duration-failed').attr('hidden', true)
              $('#loan-duration').attr('disabled', true)
              $('#loan-amount').attr('disabled', true)
              $('#loan-amount-passed').attr('hidden', true)
              $('#loan-amount-failed').attr('hidden', true)
              $('#loan-psr-passed').attr('hidden', true)
              $('#loan-psr-failed').attr('hidden', true)
            }
          }
        })
      } else if (loanType === 'default') {
        $('#loan-type-note').html(``)
        $('#loan-duration-note').html(``)
        $('#loan-amount-note').html(``)
        $('#get-started').attr('hidden', false)
        $('#loan-duration').attr('disabled', true)
        $('#loan-amount').attr('disabled', true)
        $('#qualification-age-passed').attr('hidden', true)
        $('#qualification-age-failed').attr('hidden', true)
        $('#loan-duration').val('')
        $('#loan-duration-passed').attr('hidden', true)
        $('#loan-duration-failed').attr('hidden', true)
        $('#loan-amount').val('')
        $('#loan-amount-passed').attr('hidden', true)
        $('#loan-amount-failed').attr('hidden', true)
        $('#loan-psr-passed').attr('hidden', true)
        $('#loan-psr-failed').attr('hidden', true)
      }
    })

    // perform these when user enters loan duration
    $(document).on('blur', '#loan-duration', function(e) {
      e.preventDefault()
      let selectedLoanDuration = $(this).val()
      if (selectedLoanDuration) {
        if (parseInt(selectedLoanDuration) <= loanDuration) {
          $('#loan-duration-passed').attr('hidden', false)
          $('#loan-duration-failed').attr('hidden', true)
        } else {
          $('#loan-duration-failed').attr('hidden', false)
          $('#loan-duration-passed').attr('hidden', true)
        }
      }
    })

    // perform these when user enters an amount
    $(document).on('blur', '#loan-amount', function(e) {
      e.preventDefault()
      let selectedLoanAmount = $(this).val()
      if (selectedLoanAmount) {
        if (parseInt(selectedLoanAmount) >= loanMinCreditLimit && parseInt(selectedLoanAmount) <= loanMaxCreditLimit) {
          $('#loan-amount-passed').attr('hidden', false)
          $('#loan-amount-failed').attr('hidden', true)
        } else {
          $('#loan-amount-failed').attr('hidden', false)
          $('#loan-amount-passed').attr('hidden', true)
        }
        if (parseInt(loanPSR) > 0) {
          let loanPSRAmount = (parseInt(loanPSRValue) / 100) * selectedLoanAmount
          if (loanPSRAmount <= savingsAmount) {
            $('#loan-psr-passed').attr('hidden', false)
            $('#loan-psr-failed').attr('hidden', true)
          } else {
            $('#loan-psr-passed').attr('hidden', true)
            $('#loan-psr-failed').attr('hidden', false)
          }
        }
      }
    })
  })
</script>
</body>
</html>
