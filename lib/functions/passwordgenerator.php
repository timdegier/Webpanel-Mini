<?php

function generatePassword(){

  $pwd = bin2hex(random_bytes(8));

  return $pwd;

}

?>
