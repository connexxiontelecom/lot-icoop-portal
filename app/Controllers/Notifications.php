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

  function view_notification($notification_id) {
    if ($this->session->active) {
      $notification = $this->notificationModel->find($notification_id);
      if ($notification) {
        $page_data['page_title'] = 'View Notification';
        $page_data['notification'] = $notification;
        switch ($notification['type']) {
          case 'guarantor_notification':
            $loan_guarantor = $this->loanGuarantorModel->find($notification['details']);
            $loan_application = $this->loanApplicationModel->find($loan_guarantor['loan_application_id']);
            $loan_details = $this->loanSetupModel->find($loan_application['loan_type']);
            $page_data['loan_guarantor'] = $loan_guarantor;
            $page_data['loan_application'] = $loan_application;
            $page_data['loan_details'] = $loan_details;
            break;
        }
        return view('notifications/view-notification', $page_data);
      }
      return redirect('dashboard');
    }
    return redirect('auth/login');
  }
}