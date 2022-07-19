<?php

class Tickets {

  public function getAll(){
    global $db;
    $return = [];

    $query = 'SELECT * FROM tickets ORDER BY id DESC';

    $prepare = $db->prepare($query);
    $prepare->execute();

    $result = $prepare->get_result();

    while($row = $result->fetch_assoc()) {
      $return[] = $row;
    }

    $prepare->close();

    return $return;

  }

  public function get($id){

    global $db;

    $query = 'SELECT * FROM tickets WHERE id = ?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);
    $prepare->execute();

    $result = $prepare->get_result();

    $return = $result->fetch_assoc();

    $prepare->close();

    return $return;

  }

  public function getUnreadLabel(){
    global $db;

    $query = 'SELECT * FROM tickets WHERE status = 0';

    $prepare = $db->prepare($query);

    if($prepare->execute()){
      $result = $prepare->get_result();

      $num = $result->num_rows;

      $prepare->close();
    } else {
      $num = 0;

      $prepare->close();
    }

    return $num;
  }

  public function view($id){

    global $db;

    $query = 'SELECT * FROM tickets WHERE id = ?';
    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);
    $prepare->execute();

    $result = $prepare->get_result();

    $row = $result->fetch_assoc();

    if($row['status'] == '0'){
      $prepare->close();

      $query = 'UPDATE tickets SET status = 1 WHERE id = ?';
      $prepare = $db->prepare($query);
      $prepare->bind_param('s',$id);
      $prepare->execute();

      $prepare->close();
    } else {
      $prepare->close();
    }

  }

  public function reply(){

    if(!isset($_POST['reply'])){
      return;
    }

    global $db;

    $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_NUMBER_INT);

    $users = new Users;
    $user = $users->getById($userid);

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $response = filter_input(INPUT_POST, 'response', FILTER_SANITIZE_STRING);
    $response = nl2br($response);

    $query = 'UPDATE tickets SET response = ?, status = 2 WHERE id = ?';
    $prepare = $db->prepare($query);
    $prepare->bind_param('ss',$response,$id);

    if($prepare->execute()){

      $prepare->close();

      $email = mailTemplate('email/ticket-response', $data = ['user' => $user['username'], 'solution' => $response]);

      mailer($user['email'], 'Webpanel - Ticket reply', $email);

      return 'Your reply has been sent.';

    } else {

      $prepare->close();

      throw new \Exception('Something went wrong.');

    }

  }

  public function getResponses(){

    global $db;

    $id = $_SESSION['userid'];

    $query = 'SELECT * FROM tickets WHERE status = 2 AND userid = ?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);
    $prepare->execute();

    $result = $prepare->get_result();

    $num = $result->num_rows;

    $prepare->close();

    return $num;

  }

  public function send(){

    if(!isset($_POST['send'])){
      return;
    }

    global $db;

    $id = $_SESSION['userid'];
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $description = nl2br($description);
    $response = '';
    $status = 0;
    $date = date('d-m-Y H:i');

    $users = new Users;
    $user = $users->getById($id);

    $query = 'INSERT INTO tickets(userid,title,description,response,status,date) VALUES (?,?,?,?,?,?)';

    $prepare = $db->prepare($query);
    $prepare->bind_param('ssssss',$id,$title,$description,$response,$status,$date);
    if($prepare->execute()){

      $prepare->close();

      $email = mailTemplate('email/ticket-sent', $data = ['user' => $user['username'], 'problem' => $description]);

      $mailer = mailer($user['email'], 'Webpanel - Ticket submission', $email);

      $email = mailTemplate('email/admin-ticket', $data = ['user' => $user['username'], 'problem' => $description]);

      $mailer = mailer(SMTP_USER, 'Webpanel - New ticket', $email);

      return 'Your ticket has been sent.';

    } else {

      $prepare->close();

      throw new \Exception('Something went wrong.');

    }

  }

  public function getAllByUser($id){

    global $db;

    $return = array();

    $query = 'SELECT * FROM tickets WHERE userid = ? ORDER BY id DESC';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);
    $prepare->execute();

    $result = $prepare->get_result();

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

    $id = filter_input(INPUT_POST, 'id');

    $query = 'DELETE FROM tickets WHERE id = ?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);
    $prepare->execute();

    $prepare->close();

  }

}

?>
