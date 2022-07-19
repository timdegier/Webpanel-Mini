<?php

$users = new Users;
$users->delete();

$tickets = new Tickets;
$tickets->delete();

require 'views/layout/admin/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Tickets</h2>
</div>

<div class="p-3">
  <ul class="list-group rounded-0">
    <?php $i = 0; ?>
    <li class="list-group-item header">
      <div class="row">
        <div class="col-3">
          <b>Ticket name</b>
        </div>

        <div class="col-3">
          <b>User</b>
        </div>

        <div class="col-3">
          <b>Status</b>
        </div>

        <div class="col-3">
          <b>Actions</b>
        </div>
      </div>
    </li>
    <?php foreach($tickets->getAll() as $key => $value): ?>
      <li class="list-group-item">
        <div class="row">
          <div class="col-3">
            <?= $value['title'] ?>
          </div>

          <div class="col-3">
            <?= $users->getById($value['userid'])['username'] ?>
          </div>
          <div class="col-3">
            <?php if ($value['status'] == 0): ?>
              <div class="badge badge-danger">
                unread
              </div>
            <?php endif; ?>

            <?php if ($value['status'] == 1): ?>
              <div class="badge badge-success">
                read
              </div>
            <?php endif; ?>

            <?php if ($value['status'] == 2): ?>
              <div class="badge badge-dark">
                completed
              </div>
            <?php endif; ?>
          </div>

          <div class="col-3">
            <form action="add-response" method="post" class="d-inline">
              <input type="hidden" name="id" value="<?= $value['id'] ?>">
              <button type="submit" name="view" class="button-none"><i class="fas fa-eye"></i></button>
            </form>

            <form method="post" class="d-inline">
              <input type="hidden" name="id" value="<?= $value['id'] ?>">
              <button type="submit" name="delete" class="button-none"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php require 'views/layout/admin/footer.php'; ?>
