<?php $session = session();?>
<script>
  $(document).ready(function () {
    function loadUnseenNotifications () {
      $.ajax({
        type: 'GET',
        url: '/get-user-notifications',
        dataType: 'json',
        success: function (data) {
          if (data) {
            $('#notification-icon').addClass('icon-status-success')
            $('.nk-notification').empty()
            $.each(data, function (index, notification) {
              let notificationTime = new Date(notification.created_at).toLocaleString()
              $('.nk-notification').append(`
                <a href="/notifications/view-notification/${notification.notification_id}" class="nk-notification-item dropdown-inner">
                  <div class="nk-notification-icon">
                    <em class="icon icon-circle bg-warning-dim ni ni-clipboad-check"></em>
                  </div>
                  <div class="nk-notification-content">
                    <div class="nk-notification-text">${notification.topic}</div>
                    <div class="nk-notification-time">${notificationTime}</div>
                  </div>
                </a>
              `)
            })

          } else {
            $('#notification-icon').removeClass('icon-status-success')
          }
        }
      })
    }
    loadUnseenNotifications()
    setInterval(function () {
      loadUnseenNotifications()
    }, 5000)
  })
</script>
