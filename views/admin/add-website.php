<?php

$website = new Websites;

try {
  $success = $website->add();
} catch (\Exception $e) {
  $error = $e->getMessage();
}

require 'views/layout/admin/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Add website</h2>
</div>

<div class="mb-0 m-3">
  <div class="row">
    <div class="col">
      <?php if(isset($success)): ?>
        <div class="alert alert-success mb-0">
          <?php echo $success; ?>
        </div>
      <?php endif; ?>

      <?php if(isset($error)): ?>
        <div class="alert alert-danger mb-0">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="p-4 border m-3">
  <form method="post">
    <div class="row">
      <div class="col-6">
          <h3 class="mb-3">Website info</h3>
          <label>Name</label>
          <input type="text" name="name" placeholder="Website name" class="form-control mb-3">
          <label>Url</label>
          <input type="text" name="url" placeholder="Website url" class="form-control mb-3">
          <label>Directory</label>
          <input type="text" name="dir" placeholder="Directory" class="form-control mb-3">
          <input type="submit" name="add" value="Add" class="button">
      </div>

      <div class="col-6">
        <h3 class="mb-3">Credentials</h3>
        <label>Username</label>
        <input type="text" name="username" placeholder="Username" class="form-control mb-3">
        <label>Password</label>
        <input type="text" name="password" placeholder="Password" class="form-control mb-3">
        <label>Suggested password</label>
        <input type="text" placeholder="Password" class="form-control mb-3" value="<?= generatePassword() ?>" disabled>
        <label>Email</label>
        <input type="email" name="email" placeholder="Email" class="form-control mb-3">
      </div>
    </div>
  </form>
</div>

<?php require 'views/layout/admin/footer.php'; ?>
