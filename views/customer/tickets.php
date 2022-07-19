<?php

$tickets = new Tickets;
$tickets->send();
$tickets->delete();

require 'views/layout/customer/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Tickets</h2>
</div>

<section>
  <div class="p-3">

    <div class="mb-3">
      <a href="<?= PANEL_URL ?>/customer/create-ticket" class="button">Report a problem/ticket</a>
    </div>

    <ul class="list-group rounded-0">
      <?php $i = 0; ?>
      <li class="list-group-item">
        <div class="row">
          <div class="col-4">
            <b>Ticket name</b>
          </div>

          <div class="col-4">
            <b>Status</b>
          </div>

          <div class="col-4">
            <b>Actions</b>
          </div>
        </div>
      </li>
      <?php foreach ($tickets->getAllByUser($_SESSION['userid']) as $ticket): ?>
        <li class="list-group-item">
          <div class="row">
            <div class="col-4">
              <?= $ticket['title'] ?>
            </div>

            <div class="col-4">
              <?php if ($ticket['status'] == 0): ?>
                <div class="badge badge-danger">
                  unread
                </div>
              <?php endif; ?>

              <?php if ($ticket['status'] == 1): ?>
                <div class="badge badge-success">
                  read
                </div>
              <?php endif; ?>

              <?php if ($ticket['status'] == 2): ?>
                <div class="badge badge-dark">
                  completed
                </div>
              <?php endif; ?>
            </div>

            <div class="col-4">

              <?php if (!empty($ticket['response'])): ?>
                <form action="view-ticket" method="post" class="d-inline">
                  <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                  <button type="submit" name="view" class="button-none"><i class="fas fa-eye"></i></button>
                </form>
              <?php endif; ?>

              <form method="post" class="d-inline">
                <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                <button type="submit" name="delete" class="button-none"><i class="fas fa-trash"></i></button>
              </form>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php require 'views/layout/admin/footer.php'; ?>
