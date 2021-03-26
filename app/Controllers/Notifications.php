<?php
namespace App\Controllers;

class Notifications extends BaseController {

  function get_user_notifications() {
    if ($this->session->active) {
      $staff_id = $this->session->get('staff_id');
      $user_notifications = $this->notificationModel->where('receiver_id', $staff_id)->findAll();
      return $this->response->setJSON($user_notifications);
    }
    return redirect('auth/login');
  }
}