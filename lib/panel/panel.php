<?php

class Panel {

  public $url;

  public function __construct(){

    $this->url = $_GET['url'];

    if($_GET['url'] === ''){
      $this->url = 'login';
    }

    $this->check();
    $this->page();

  }

  public function check(){
    if($this->url !== 'login'){
      if(!isset($_SESSION['username'])){
        header('location:' . PANEL_ROOT . 'login');
      }
    }
  }

  public function page(){

    $url = $this->url;

    $page = 'views/' . $url . '.php';

    if(file_exists($page)){
      require $page;
    } else {
      throw new \Exception(sprintf('Could not load page: %s', $page));
    }

  }

}

?>
