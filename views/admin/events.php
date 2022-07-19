<?php

$events = new Events;

require 'views/layout/admin/header.php';
?>

<div class="panel-title border-bottom">
  <h2>Events</h2>
</div>

<div class="p-3">
  <ul class="list-group rounded-0">
    <li class="list-group-item header">
      <div class="row">
        <div class="col-9">
          <b>Event</b>
        </div>

        <div class="col-3">
          <b>Date</b>
        </div>
      </div>
    </li>
    <?php foreach($events->getAll() as $value): ?>
      <li class="list-group-item">
        <div class="row">
          <div class="col-9">
            <?= $value['event'] ?>
          </div>

          <div class="col-3">
            <?= $value['date'] ?>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<?php require 'views/layout/admin/footer.php'; ?>
