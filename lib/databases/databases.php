<?php

class Databases {

  public function list(){

    global $db;

    $query = 'SHOW DATABASES';

    $prepare = $db->prepare($query);
    $prepare->execute();

    $result = $prepare->get_result();

    $return = array();

    while($row = $result->fetch_assoc()) {
      $return[] = $row;
    }

    $prepare->close();

    return $return;

  }

  public function delete(){

    if(!isset($_POST['delete'])){
      return;
    }

    global $db;

    $name = filter_input(INPUT_POST, 'name');

    $query = "DROP DATABASE $name";

    $result = $db->query($query);

  }

}

?>
