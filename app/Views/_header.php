<?php
  $session = session();
?>
<div class="nk-header nk-header-fluid nk-header-fixed is-light">
  <div class="container-fluid">
    <div class="nk-header-wrap">
      <div class="nk-menu-trigger d-xl-none ml-n1">
        <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
      </div>
      <div class="nk-sidebar-brand d-xl-none">
        <a href="/dashboard" class="logo-link text-center">
          <img class="logo-light logo-img" src="/assets/images/logo-01-2.png" srcset="./images/logo2x.png 2x" alt="logo">
          <img class="logo-dark logo-img" src="/assets/images/logo-01-2.png" srcset="./images/logo-dark2x.png 2x" alt="logo-dark">
          <span class="nio-version text-dark">Portal</span>
        </a>
      </div>
      <!--			<div class="nk-header-brand d-xl-none">-->
      <!--				<a href="html/crypto/index.html" class="logo-link">-->
      <!--					<img class="logo-light logo-img" src="./images/logo.png" srcset="./images/logo2x.png 2x" alt="logo">-->
      <!--					<img class="logo-dark logo-img" src="./images/logo-dark.png" srcset="./images/logo-dark2x.png 2x" alt="logo-dark">-->
      <!--					<span class="nio-version">Crypto</span>-->
      <!--				</a>-->
      <!--			</div>-->
      <div class="nk-header-news d-none d-xl-block">
        <div class="nk-news-list">
          <a class="nk-news-item" href="javascript:void(0)" target="_blank">
            <div class="nk-news-icon">
              <em class="icon ni ni-card-view"></em>
            </div>
            <div class="nk-news-text">
              <p>Do you know the latest update of 2021? <span> Review all the changes we've made here</span></p>
              <em class="icon ni ni-external"></em>
            </div>
          </a>
        </div>
      </div>
      <div class="nk-header-tools">
        <ul class="nk-quick-nav">
          <li class="dropdown user-dropdown">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
              <div class="user-toggle">
                <div class="user-avatar sm">
                  <em class="icon ni ni-user-alt"></em>
                </div>
                <div class="user-info d-none d-md-block">
                  <?php if($session->get('status') == 2):?>
                    <div class="user-status user-status-verified">Active</div>
                  <?php endif;?>
                  <div class="user-name dropdown-indicator">
                    <?= $session->get('firstname').' '.$session->get('lastname') ?>
                  </div>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
              <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                <div class="user-card">
                  <div class="user-avatar">
                    <span>
                      <?= ucfirst($session->get('firstname'))[0].''.ucfirst($session->get('lastname'))[0] ?>
                    </span>
                  </div>
                  <div class="user-info">
                    <span class="lead-text"><?= $session->get('firstname').' '.$session->get('lastname') ?></span>
                    <span class="sub-text"><?= $session->get('email') ?></span>
                  </div>
                </div>
              </div>
              <div class="dropdown-inner user-account-info">
                <h6 class="overline-title-alt">Regular Savings</h6>
                <div class="user-balance"><em class="icon ni ni-sign-kobo"></em> <?=number_format($session->get('regular_savings'), 2)?></div>
                <a href="#" class="link mt-2"><span>Withdraw Funds</span> <em class="icon ni ni-wallet-out"></em></a>
              </div>
              <div class="dropdown-inner">
                <ul class="link-list">
                  <li><a href="#"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                  <li><a href="#"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
                  <li><a href="#"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li>
                  <li><a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li>
                </ul>
              </div>
              <div class="dropdown-inner">
                <ul class="link-list">
                  <li><a href="<?= site_url('logout')?>"><em class="icon ni ni-signout"></em><span>Logout</span></a></li>
                </ul>
              </div>
            </div>
          </li>
          <li class="dropdown notification-dropdown mr-n1">
            <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
              <div id="notification-icon" class="icon-status"><em class="icon ni ni-bell"></em></div>
<!--              <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>-->
            </a>
            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right dropdown-menu-s1">
              <div class="dropdown-head">
                <span class="sub-title nk-dropdown-title">Notifications</span>
                <a href="#">Mark All as Read</a>
              </div>
              <div class="dropdown-body">
                <div class="nk-notification">
                  <a href="#" class="nk-notification-item dropdown-inner">
                    <div class="nk-notification-icon">
                      <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                    </div>
                    <div class="nk-notification-content">
                      <div class="nk-notification-text">You have requested to <span>Widthdrawl</span></div>
                      <div class="nk-notification-time">2 hrs ago</div>
                    </div>
                  </a>
                </div>
              </div>
              <div class="dropdown-foot center">
                <a href="#">View All</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
