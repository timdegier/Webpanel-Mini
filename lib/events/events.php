<?php

class Events {

  public function getAll(){

    global $db;
    $return = [];

    $query = 'SELECT * FROM events ORDER BY id DESC LIMIT 30';

    $prepare = $db->prepare($query);
    $prepare->execute();

    $result = $prepare->get_result();

    while($row = $result->fetch_assoc()) {
      $return[] = $row;
    }

    $prepare->close();

    return $return;

  }

  public function event(string $event){

    global $db;

    $date = date('d-m-Y H:i');

    $query = 'INSERT INTO events(event, date) VALUES (?,?)';

    $prepare = $db->prepare($query);
    $prepare->bind_param('ss', $event, $date);
    $prepare->execute();

    $prepare->close();

  }

}

?>
