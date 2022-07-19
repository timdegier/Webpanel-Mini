<?php

$users = new Users;

try {
  $success = $users->edit();
} catch (\Exception $e) {
  $error = $e->getMessage();
}

$user = $users->getAdminById($_POST['id']);

if(!$user['id']){
  header('location:' . PANEL_URL . '/admin/users');
}

require 'views/layout/admin/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Edit user</h2>
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

        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <label>Username</label>
        <input type="text" name="username" placeholder="Username" class="form-control mb-3" value="<?= $user['username'] ?>">
        <label>Email</label>
        <input type="email" name="email" placeholder="Email" class="form-control mb-3" value="<?= $user['email'] ?>">
        <label>New password</label>
        <input type="password" name="password" placeholder="Enter new password" class="form-control mb-3" value="">
        <label>Suggested password</label>
        <input type="text" placeholder="Password" class="form-control mb-3" value="<?= generatePassword() ?>" disabled>

        <input type="submit" name="edit" value="Edit" class="button">
      </form>
    </div>
  </div>
</div>

<?php require 'views/layout/admin/footer.php'; ?>
