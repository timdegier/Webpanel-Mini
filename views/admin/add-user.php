<?php

$user = new Users;

try {
  $success = $user->add();
} catch (\Exception $e) {
  $error = $e->getMessage();
}

require 'views/layout/admin/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Add user</h2>
</div>

<div class="p-3">
  <div class="row">
    <div class="col-12">
      <form method="post">
        <?php if(isset($success)): ?>
          <div class="alert alert-success">
            <?php echo $success; ?>
          </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
          <div class="alert alert-danger">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <label>Username</label>
        <input type="text" name="username" placeholder="Username" class="form-control mb-3">
        <label>Email</label>
        <input type="email" name="email" placeholder="Email" class="form-control mb-3">
        
        <div class="row">
          <div class="col-md-6">
            <label>Password</label>
            <input type="password" name="password" placeholder="Password" class="form-control mb-3">
          </div>

          <div class="col-md-6">
            <label>Suggested password</label>
            <input type="text" placeholder="Password" class="form-control mb-3" value="<?= generatePassword() ?>" disabled>
          </div>
        </div>

        <input type="submit" name="add" value="Add" class="button">
      </form>
    </div>
  </div>
</div>

<?php require 'views/layout/admin/footer.php'; ?>
