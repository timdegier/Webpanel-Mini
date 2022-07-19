<?php

$databases = new Databases;
$databases->delete();

$backups = new Backups;
$backups->backup();

require 'views/layout/admin/header.php';
?>

<div class="panel-title border-bottom">
  <h2>Databases</h2>
</div>

<div class="px-3 pt-3 pb-1">
  <a href="<?= PANEL_URL ?>/phpmyadmin" class="button">Go to PHPMyAdmin</a>
</div>

<div class="p-3">
  <ul class="list-group rounded-0">
    <li class="list-group-item header">
      <div class="row">
        <div class="col-9">
          <b>Database</b>
        </div>

        <div class="col-3">
          <b>Action</b>
        </div>
      </div>
    </li>
    <?php foreach($databases->list() as $value): ?>
      <li class="list-group-item">
        <div class="row">
          <div class="col-9">
            <?= $value['Database'] ?>
          </div>

          <div class="col-3">
            <form method="post" class="d-inline">
              <input type="hidden" name="name" value="<?= $value['Database'] ?>">
              <button type="submit" name="delete" class="button-none" title="Delete"><i class="fas fa-trash"></i></button>
            </form>

            <form method="post" class="d-inline">
              <input type="hidden" name="name" value="<?= $value['Database'] ?>">
              <button type="submit" name="backup" class="button-none" title="Backup"><i class="fas fa-server"></i></button>
            </form>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php require 'views/layout/admin/footer.php'; ?>
