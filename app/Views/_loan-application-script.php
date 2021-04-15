<?php $session = session()?>
<!-- jQuery UI CSS -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<!-- jQuery UI JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
  let loanDuration = 0
  let loanMaxCreditLimit = 0
  let loanMinCreditLimit = 0
  let loanAgeQualification = 0
  let loanPSR = 0
  let loanPSRValue = 0
  let loanInterestRate = 0
  let loanInterestMethod = ''
  let loanInterestChargeType = ''
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
            loanInterestRate = loanSetupDetails.ls_interest_rate
            if (loanSetupDetails.interest_method == 1) loanInterestMethod = 'Upfront'
            else if (loanSetupDetails.interest_method == 2) loanInterestMethod = 'Reducing Balance'
            else if (loanSetupDetails.interest_method == 3) loanInterestMethod = 'Targeted'

            if (loanSetupDetails.interest_charge_type == 1) loanInterestChargeType = 'Flat'
            else if (loanSetupDetails.interest_charge_type == 2) loanInterestChargeType = 'Monthly'
            else if (loanSetupDetails.interest_charge_type == 3) loanInterestChargeType = 'Yearly'


            $('#loan-details-list').html(`
              <li>${loanInterestRate}% Interest Rate</li>
              <li>${loanInterestMethod} Interest Method</li>
              <li>${loanInterestChargeType} Interest Charge Type</li>
            `)
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
            $('#loan-details').attr('hidden', false)
            if (monthsDifference > loanAgeQualification) {
              // The user has been approved long enough to qualify for the loan type
              $('#qualification-age-passed').attr('hidden', false)
              $('#qualification-age-failed').attr('hidden', true)
              $('#loan-duration').attr('disabled', false)
              $('#loan-amount').attr('disabled', false)
              $('#loan-attachment').attr('disabled', false)
              $('#guarantor-1').attr('disabled', false)
              $('#guarantor-2').attr('disabled', false)
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
              $('#loan-attachment').attr('disabled', true)
              $('#guarantor-1').attr('disabled', true)
              $('#guarantor-2').attr('disabled', true)
            }
          }
        })
      } else if (loanType === 'default') {
        $('#loan-type-note').html(``)
        $('#loan-duration-note').html(``)
        $('#loan-amount-note').html(``)
        $('#loan-details-list').html(``)
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
        $('#loan-attachment').attr('disabled', true)
        $('#guarantor-1').val('')
        $('#guarantor-2').val('')
        $('#guarantor-1').attr('disabled', true)
        $('#guarantor-2').attr('disabled', true)
        $('#loan-details').attr('hidden', true)
      }
    })

    // perform these when user enters loan duration
    $(document).on('keyup', '#loan-duration', function(e) {
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
      } else {
        $('#loan-duration-failed').attr('hidden', true)
        $('#loan-duration-passed').attr('hidden', true)
      }
    })

    // perform these when user enters an amount
    $(document).on('keyup', '#loan-amount', function(e) {
      e.preventDefault()
      let selectedLoanAmount = $(this).val()
      selectedLoanAmount = +(selectedLoanAmount.replace(/,/g, ''))
      if (selectedLoanAmount) {
        if (selectedLoanAmount >= loanMinCreditLimit && selectedLoanAmount <= loanMaxCreditLimit) {
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
      } else {
        $('#loan-amount-failed').attr('hidden', true)
        $('#loan-amount-passed').attr('hidden', true)
        $('#loan-psr-passed').attr('hidden', true)
        $('#loan-psr-failed').attr('hidden', true)
      }
    })

    // autocomplete for guarantor one
    $('#guarantor-1').autocomplete({
      source: function (req, res) {
        $.ajax({
          url: '<?= site_url('loan-application/get-guarantors')?>',
          type: 'post',
          dataType: 'json',
          data: {
            search: req.term,
            guarantor1: ''
          },
          success: function (data) {
            console.log(data)
            res(data)
          }
        })
      },
      select: function (event, ui) {
        $('#guarantor-1').val(ui.item.label)
        return false
      },
      focus: function (event, ui) {
        $('#guarantor-1').val(ui.item.label)
        return false
      }
    })

    // autocomplete for guarantor two
    $('#guarantor-2').autocomplete({
      source: function (req, res) {
        $.ajax({
          url: '<?= site_url('loan-application/get-guarantors')?>',
          type: 'post',
          dataType: 'json',
          data: {
            search: req.term,
            guarantor1: $('#guarantor-1').val()
          },
          success: function (data) {
            console.log(data)
            res(data)
          }
        })
      },
      select: function (event, ui) {
        $('#guarantor-2').val(ui.item.label)
        return false
      },
      focus: function (event, ui) {
        $('#guarantor-2').val(ui.item.label)
        return false
      }
    })

    // loan application form submission
    $('form#loan-application').submit(function (e) {
      e.preventDefault()
      let loanType = $('#loan-type').val()
      if (!loanType || loanType === 'default') {
        Swal.fire("Invalid Submission", "Please select a valid loan type!", "error");
      } else {
        const formData = new FormData(this)
        formData.set('loan_amount', formData.get('loan_amount').replace(/,/g, ''))
        Swal.fire({
          title: 'Are you sure?',
          text: 'Applying for a loan is irreversible',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Confirm Loan'
        }).then(function (result) {
          if (result.value) {
            $.ajax({
              url: '<?=site_url('loan-application/submit-application')?>',
              type: 'post',
              data: formData,
              success: function (data) {
                if (data.success) {
                  Swal.fire('Confirmed!', data.msg, 'success').then(() => location.reload())
                } else {
                  Swal.fire('Sorry!', data.msg, 'error')
                }
              },
              cache: false,
              contentType: false,
              processData: false
            })
          }
        });
      }
    })
  })
</script>
