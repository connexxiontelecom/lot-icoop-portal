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
              //
            }
          }
        })

      }
    })
  })
</script>
