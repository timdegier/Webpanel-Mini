<?php

session_unset();

$_SESSION['logout'] = 'Succesfully logged out';

header('location:' . PANEL_URL . '/login');

?>
