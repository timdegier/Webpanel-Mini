<?php

$websites = new Websites;

try {
  $success = $websites->addScreenshot();
} catch (\Exception $e) {
  $error = $e->getMessage();
}

$website = $websites->getById($_POST['id']);

require 'views/layout/admin/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Add screenshot to website</h2>
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
  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-6">
        <h3 class="mb-3">Add screenshot</h3>
        <input type="hidden" name="id" value="<?= $website['id'] ?>">
        <input type="hidden" name="name" value="<?= $website['name'] ?>">
        <input type="file" name="screenshot" class="d-block mb-3">
        <input type="submit" name="addScreenshot" value="Add" class="button">
      </div>
    </div>
  </form>
</div>

<?php require 'views/layout/admin/footer.php'; ?>
