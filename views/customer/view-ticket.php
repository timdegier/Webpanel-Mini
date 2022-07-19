<?php

$tickets = new Tickets;
$ticket = $tickets->get($_POST['id']);

require 'views/layout/customer/header.php';

?>

<div class="panel-title border-bottom">
  <h2><?= $ticket['title'] ?></h2>
</div>

<section>
  <div class="p-3">
    <div class="mb-2">
      <b>Response:</b>
    </div>

    <div class="border p-3">
      <?= $ticket['response'] ?>
    </div>
  </div>
</section>

<?php require 'views/layout/admin/footer.php'; ?>
