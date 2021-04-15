<?php $session = session(); ?>

<script>
  $(document).ready(function () {
    // perform all these actions when a user selects a withdrawal type
    $(document).on('change', '#savings-type', function (e) {
      e.preventDefault()
      let savingsType = $(this).val()
      if (savingsType !== '' && savingsType !== 'default') {
        $.ajax({
          type: 'get',
          url: `withdrawal-application/compute-balance/${savingsType}`,
          success: function (response) {
            if (response.success) {
              console.log(response)
              let encumberedAmount = response.encumbered_amount
              let savingsAmount = response.savings_amount
              let withdrawableAmount = response.withdrawable_amount

              $('#savings-details-list').html(`
                <li>Withdrawal Balance ${withdrawableAmount}</li>
                <li>Encumbered Amount ${encumberedAmount}</li>
                <li>Savings Amount ${savingsAmount}</li>
              `)
            }
            $('#get-started').attr('hidden', true)
            $('#withdraw-details').attr('hidden', false)
          }
        })
      }
    })
  })
</script>
