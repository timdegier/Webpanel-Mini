<?php

// Server info

class ServerInfo {

  /**
   * Total Storage Var
   * @var float
   */
  public $totalStorage;

  /**
   * Free Storage Var
   * @var float
   */
  public $freeStorage;

  public function getServername(){

    return $_SERVER['SERVER_NAME'];

  }

  public function getServeraddress(){

    return $_SERVER['SERVER_ADDR'];

  }

  /**
   * Makes a percentage value
   * @return float $percentage
   */
  public function getStoragePercentage(){

    // Get free and total and make a percentage

    $percentage = $this->freeStorage / $this->totalStorage * 100;

    // Return percentage

    return $percentage;
  }

  /**
   * Get the total amount of storage on the device
   * @return string total amount of storage
   */
  function getTotalStorage(){

    // Get amount of bytes

    $bytes = disk_total_space(".");

    // Prefixes e.g. 10MB or 15GB

    $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );

    // The base to divide from (1MB = 1024KB)

    $base = 1024;

    // Create a class

    $class = min((int)log($bytes , $base) , count($si_prefix) - 1);

    // Setup a string with the amount of bytes in it

    $this->totalStorage = sprintf('%1.2f' , $bytes / pow($base,$class));

    // Return the total amount of storage

    return $this->totalStorage . ' ' . $si_prefix[$class];

  }

  function getFreeStorage(){

    // Get amount of bytes

    $bytes = disk_free_space(".");

    // Prefixes e.g. 10MB or 15GB

    $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );

    // The base to divide from (1MB = 1024KB)

    $base = 1024;

    // Create a class

    $class = min((int)log($bytes , $base) , count($si_prefix) - 1);

    // Setup a string with the amount of bytes in it

    $this->freeStorage = sprintf('%1.2f' , $bytes / pow($base,$class));

    // Return the total amount of free storage

    return $this->freeStorage . ' ' . $si_prefix[$class];

  }

}

$ServerInfo = new ServerInfo;

?>
