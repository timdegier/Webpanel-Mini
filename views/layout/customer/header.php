<?php

$tickets = new Tickets;

if($_SESSION['type'] !== 1){
  session_unset();
  header('location:' . PANEL_URL . '/login');
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta name="robots" content="noindex">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" href="<?= PANEL_URL ?>/assets/img/icon.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= PANEL_URL ?>/assets/css/style.css">

    <title>WebPanel</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark">
      <a class="navbar-brand" href="<?= PANEL_URL ?>">
        <img src="<?= PANEL_URL ?>/assets/img/webpanel_50x50.png" width="40" class="mr-2">
        <div class="logo-text">
          Web Hosting
        </div>
      </a>
    </nav>

    <div class="panel">
      <div class="panel-menu">
        <div class="user-container">
          <i class="fas fa-user"></i> <?= $_SESSION['username'] ?>
        </div>

        <div class="panel-nav">
          <div class="panel-nav-title">
            General
          </div>
          <a href="<?= PANEL_URL ?>/customer/home" class="panel-nav-item <?php if($this->url == 'customer/home') echo 'active' ?>"><i class="fas fa-home"></i> Home</a>

          <div class="panel-nav-title">
            Website management
          </div>
          <a href="<?= PANEL_URL ?>/phpmyadmin" class="panel-nav-item"><i class="fas fa-database"></i> Database</a>
          <a href="<?= PANEL_URL ?>/customer/edit-website" class="panel-nav-item <?php if($this->url == 'customer/edit-website') echo 'active' ?>"><i class="fas fa-cog"></i> Edit website</a>
          <div class="panel-nav-title">
            Services
          </div>
          <a href="<?= PANEL_URL ?>/customer/tickets" class="panel-nav-item <?php if($this->url == 'customer/tickets') echo 'active' ?>"><i class="fas fa-ticket-alt"></i> Tickets <span class="badge badge-danger"><?= $tickets->getResponses() ?></span></a>
          <div class="panel-nav-title">
            Logout
          </div>
          <a href="<?= PANEL_URL ?>/logout" class="panel-nav-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <div class="panel-footer">
          &copy; Webpanel 2020 - <?= date('Y') ?>
        </div>

        <div class="panel-menu-toggle" onclick="toggleMenu()">
          <i class="fas fa-bars"></i>
        </div>

        <script type="text/javascript">
          var open = false;

          function toggleMenu(){

            var menu = document.querySelector('.panel-menu');
            var content = document.querySelector('.panel-content');

            if(open){

              menu.style.left = "-250px";
              content.style.width = "100%";

              open = false;

            } else {

              menu.style.left = "0px";
              content.style.width = "calc(100% - 250px)";

              open = true;

            }

          }
        </script>
      </div>

      <div class="panel-content">
