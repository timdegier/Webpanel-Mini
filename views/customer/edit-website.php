<?php

$websites = new Websites;

try {
  $success = $websites->editWebsite();
} catch (\Exception $e) {
  $error = $e->getMessage();
}

$website = $websites->getById($_SESSION['userid']);

require 'views/layout/customer/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Edit website</h2>
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

<div class="m-3 p-4 border">
  <form method="post">
    <div class="row">
      <div class="col-6">
          <h3 class="mb-3">Config file</h3>
          <input type="hidden" name="conf_file" value="<?= $website['conf_file'] ?>">

          <textarea name="conf_file_content" class="form-control mb-3" rows="8" cols="80"><?= file_get_contents($website['conf_file']) ?></textarea>

          <input type="submit" name="editConfig" value="Edit" class="button">
      </div>

      <div class="col-6">
          <h3 class="mb-3">Edit credentials</h3>
          <label>Email</label>
          <input type="email" name="email" placeholder="Email" class="form-control mb-3" value="<?= $website['email'] ?>">
          <label>New password</label>
          <input type="text" name="password" placeholder="Password (leave empty if none)" class="form-control mb-3">
          <label>Suggested password</label>
          <input type="text" placeholder="Password" class="form-control mb-3" value="<?= generatePassword() ?>" disabled>
      </div>
    </div>
  </form>
</div>

<?php require 'views/layout/customer/footer.php'; ?>
