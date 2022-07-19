<?php

$tickets = new Tickets;
try {
  $success = $tickets->send();
} catch (\Exception $e) {
  $error = $e->getMessage();
}

require 'views/layout/customer/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Tickets</h2>
</div>

<div class="px-3">
  <?php if(isset($success)): ?>
    <div class="alert alert-success mt-3 mb-0">
      <?php echo $success; ?>
    </div>
  <?php endif; ?>

  <?php if(isset($error)): ?>
    <div class="alert alert-danger mt-3 mb-0">
      <?php echo $error; ?>
    </div>
  <?php endif; ?>
</div>

<section>
  <div class="p-3">
    <div class="mb-2">
      <b>Report a problem:</b>
    </div>

    <div>
      <form method="post">
        <input type="text" name="title" placeholder="Subject" class="form-control mb-3">
        <textarea name="description" rows="8" cols="80" class="form-control mb-3" placeholder="Your message"></textarea>
        <input type="submit" name="send" value="Send" class="button">
      </form>
    </div>
  </div>
</section>

<?php require 'views/layout/admin/footer.php'; ?>
