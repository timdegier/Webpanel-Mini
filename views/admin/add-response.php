<?php

$tickets = new Tickets;
$tickets->view($_POST['id']);

try {
  $success = $tickets->reply();
} catch (\Exception $e) {
  $error = $e->getMessage();
}

$ticket = $tickets->get($_POST['id']);

$users = new Users;

require 'views/layout/admin/header.php';

?>

<div class="panel-title border-bottom">
  <h2><?= $ticket['title'] ?></h2>
  <div>By: <?= $users->getById($ticket['userid'])['username'] ?></div>
  <small>Date: <?= $ticket['date'] ?></small>
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
      <b>Ticket:</b>
    </div>

    <div class="border p-3">
      <?= $ticket['description'] ?>
    </div>

    <div class="mt-4 mb-2">
      <b>Reply:</b>
    </div>

    <div>
      <form method="post">
        <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
        <input type="hidden" name="userid" value="<?= $ticket['userid'] ?>">
        <textarea name="response" rows="8" cols="80" class="form-control mb-3"></textarea>
        <input type="submit" name="reply" value="Reply" class="button">
      </form>
    </div>
  </div>
</section>

<?php require 'views/layout/admin/footer.php'; ?>
