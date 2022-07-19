<?php

$websites = new Websites;
$website = $websites->getSiteHome();

require 'views/layout/customer/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Home</h2>
</div>

<div class="p-3">
  <div class="row">
    <div class="col">
      <?php if (!isset($_COOKIE['welcome'])): ?>
        <div class="alert alert-light">
          Welcome to WebPanel. A small and lightweight hosting management panel. We hope you enjoy this service and thank you for using us. <a href="#" onclick="document.cookie = 'welcome=true; expires=Sun, 1 Jan 2030 00:00:00 UTC; path=/;'; location.reload();">Dismiss</a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4 mb-3 mb-md-0">
      <div class="border">
        <div class="info-title">
          <?= $website['name'] ?>
        </div>

        <img src="<?= $website['screenshot'] ?>" class="w-100">
      </div>
    </div>

    <div class="col-md-4 mb-3 mb-md-0">
      <div class="border mb-3">
        <div class="info-title">
          Info
        </div>

        <div class="p-3 border-bottom">
          Great news. Your website is <?= $website['status'] ?>

          <?php if ($website['status'] == 'Online'): ?>
            <div class="active-ball"></div>
          <?php endif; ?>

          <?php if ($website['status'] == 'Offline'): ?>
            <div class="not-active-ball"></div>
          <?php endif; ?>
        </div>

        <div class="p-3">
          This means your website is currently

          <?php if ($website['status'] == 'Online'): ?>
            not
          <?php endif; ?>

          experiencing trouble.
        </div>
      </div>

      <div class="border">
        <div class="info-title">
          Actions
        </div>

        <a href="<?= PANEL_URL ?>/phpmyadmin" class="p-3 d-block border-bottom">Go to PHPMyAdmin</a>
        <a href="<?= PANEL_URL ?>/customer/edit-website" class="p-3 d-block">Edit your website</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="mb-3 border">
        <div class="info-title">
          User info
        </div>

        <div class="p-3 border-bottom">
          <b>Username</b><br>
          <?= $_SESSION['username'] ?>
        </div>

        <div class="p-3 border-bottom">
          <b>Url</b><br>
          <a href="<?= $website['url'] ?>"><?= $website['url'] ?></a>
        </div>

        <div class="p-3">
          <b>Website size:</b><br>
          <?php echo formatBytes(folderSize($website['directory'])); ?>
        </div>
      </div>

      <div class="mb-3 border">
        <div class="info-title">
          Tickets
        </div>

        <div class="p-3 border-bottom">
          Having trouble? Errors? Hosting not working? Please contact us using our ticket system with your problem and we'll help you out.
        </div>

        <a href="<?= PANEL_URL ?>/customer/create-ticket" class="p-3 d-block">Create a ticket</a>
      </div>
    </div>
  </div>
</div>

<?php require 'views/layout/customer/footer.php'; ?>
