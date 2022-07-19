<?php

$users = new Users;
$users->delete();

require 'views/layout/admin/header.php';

?>

<div class="panel-title border-bottom">
  <h2>Users</h2>
</div>

<div class="px-3 pt-3 pb-1">
  <a href="<?= PANEL_URL ?>/admin/add-user" class="button">Add user</a>
</div>

<div class="p-3">
  <ul class="list-group rounded-0">
    <?php $i = 0; ?>
    <li class="list-group-item header">
      <div class="row">
        <div class="col-4">
          <b>Username</b>
        </div>

        <div class="col-4">
          <b>Email</b>
        </div>

        <div class="col-4">
          <b>Action</b>
        </div>
      </div>
    </li>
    <?php foreach($users->getAll() as $key => $value): ?>
      <li class="list-group-item">
        <div class="row">
          <div class="col-4">
            <?= $value['username'] ?>
          </div>

          <div class="col-4">
            <?= $value['email'] ?>
          </div>
          <div class="col-4">
            <form method="post" action="edit-user" class="d-inline">
              <input type="hidden" name="id" value="<?= $value['id'] ?>">
              <button type="submit" name="submit" class="button-none" title="Edit"><i class="fas fa-cog"></i></button>
            </form>

            <form method="post" class="d-inline">
              <input type="hidden" name="id" value="<?= $value['id'] ?>">
              <button type="submit" name="delete" class="button-none" title="Delete"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php require 'views/layout/admin/footer.php'; ?>
