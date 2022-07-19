<?php

$user = new Users;

try {
  $success = $user->login();
} catch (\Exception $e) {
  $message = $e->getMessage();
}

require 'views/layout/login/login-header.php';

?>

<section class="login">
    <div class="row justify-content-center">
      <div class="col-lg-3 col-md-6 col-sm-12 col-sx-12 my-auto mx-4 mx-md-0">
        <?php if (isset($message)): ?>
          <div class="alert alert-danger">
            <?= $message ?>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['logout'])): ?>
          <div class="alert alert-success">
            <?= $_SESSION['logout'] ?>.
          </div>
        <?php endif; ?>

        <div class="alert alert-primary">
          Welcome to WebPanel, please login.
        </div>

        <div class="login-top px-4 py-3">
          <img src="<?= PANEL_URL ?>/assets/img/webpanel_50x50.png" width="45" class="mr-2" alt="">
          <div class="login-top-text">
            Web Hosting Manager
          </div>
        </div>

        <div class="login-form p-4">
          <form method="post">
            <label>Username</label>
            <input type="text" name="username" placeholder="Username" class="form-control mb-3">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password" class="form-control mb-3">
            <button type="submit" name="login" class="button">
              Login
            </button>
          </form>
        </div>

        <div class="my-3 text-center">
          &copy; Webpanel 2020 - <?= date('Y') ?> Webpanel
        </div>
      </div>
    </div>
</section>

<?php

$_SESSION['logout'] = null;

require 'views/layout/login/login-footer.php';

?>
