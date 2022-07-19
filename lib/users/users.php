<?php

class Users extends Events {

  public function login(){

    if(!isset($_POST['login'])){
      return;
    }

    global $db;

    // Security feature for ip ban/block
    if(!isset($_SESSION['tries'])){
      $_SESSION['tries'] = 1;
    }

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $query = 'SELECT * FROM blocked_ips WHERE ip = ?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$ip);
    $prepare->execute();

    $result = $prepare->get_result();

    $ipRows = $result->num_rows;

    $prepare->close();

    $_SESSION['tries'] = $_SESSION['tries'] + 1;

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

    if($ipRows > 0){

      $this->event(sprintf('Blocked login attempt from ip %s on user %s', $ip, $username));

      $_SESSION['tries'] = 10;

      throw new \Exception('Login blocked. Please contact the admin.');

    }

    if($_SESSION['tries'] > 5){
      if($ipRows == 0){

        $query = 'INSERT INTO blocked_ips(ip) VALUES (?)';

        $prepare = $db->prepare($query);
        $prepare->bind_param('s',$ip);
        $prepare->execute();

        $prepare->close();

      }

      $this->event(sprintf('Blocked login attempt from ip %s on user %s', $ip, $username));

      throw new \Exception('Login blocked. Please contact the admin.');
    }

    // Actual login part

    $password = filter_input(INPUT_POST, 'password');
    $password = hash('sha512', $password);

    if(empty($username) || empty($password)){
      $this->event(sprintf('Invalid login attempt from ip %s on user %s', $ip, $username));

      throw new \Exception('Invalid credentials.');
    }

    // Check if user is admin
    $query = 'SELECT * FROM users WHERE username=? AND password=?';
    $prepare = $db->prepare($query);
    $prepare->bind_param('ss',$username,$password);
    $prepare->execute();

    $result = $prepare->get_result();

    if($result->num_rows > 0){

      $user = $result->fetch_assoc();

      $_SESSION['userid'] = $user['id'];
      $_SESSION['username'] = $username;
      $_SESSION['type'] = 0;

      $prepare->close();

      header('location:' . PANEL_URL .'/admin/home');

      $this->event(sprintf('Succesful login from ip %s on user %s', $ip, $username));

    } else {

      $prepare->close();

    }

    // Check if user is a website user
    $query = 'SELECT * FROM websites WHERE username=? AND password=?';
    $prepare = $db->prepare($query);
    $prepare->bind_param('ss',$username,$password);
    $prepare->execute();

    $result = $prepare->get_result();

    if($result->num_rows > 0){

      $user = $result->fetch_assoc();

      $_SESSION['userid'] = $user['id'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['username'] = $username;
      $_SESSION['type'] = 1;

      $prepare->close();

      header('location:' . PANEL_URL .'/customer/home');

      $this->event(sprintf('Succesful login from ip %s on website %s', $ip, $username));

      return;

    } else {

      $prepare->close();

      $e = sprintf('User %s not found.', $username);

      throw new \Exception($e);

    }

    $this->event(sprintf('Blocked login attempt from ip %s on user %s', $ip, $username));

  }

  public function getAll(){
    global $db;

    $return = [];

    $query = 'SELECT * FROM users';

    $prepare = $db->prepare($query);
    $prepare->execute();
    $result = $prepare->get_result();

    while($row = $result->fetch_assoc()){
      $return[] = $row;
    }

    $prepare->close();

    return $return;

  }

  public function getAdminById($id){

    global $db;

    $query = 'SELECT * FROM users WHERE id = ?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);
    $prepare->execute();
    $result = $prepare->get_result();

    if($result->num_rows > 0){
      $return = $result->fetch_assoc();

      $prepare->close();
    } else {
      $prepare->close();
    }

    return $return;

  }

  public function getById($id){

    global $db;

    $query = 'SELECT * FROM websites WHERE id = ?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);
    $prepare->execute();
    $result = $prepare->get_result();

    if($result->num_rows > 0){
      $return = $result->fetch_assoc();

      $prepare->close();
    } else {
      $prepare->close();
    }

    return $return;

  }

  // Add new user
  public function add(){

    if(!isset($_POST['add'])){
      return;
    }

    global $db;

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password');
    $password = hash('sha512', $password);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if(empty($username) || empty($password) || empty($email)){
      return;
    }

    $query = 'INSERT INTO users(username, password, email) VALUES (?, ?, ?)';

    $prepare = $db->prepare($query);
    $prepare->bind_param('sss',$username,$password,$email);

    if($prepare->execute()){

      $this->event(sprintf('New user %s created', $username));

      return 'Succesfully added user: ' . $username;

      $prepare->close();

    } else {

      $this->event(sprintf('Failed to add user %s', $username));

      $prepare->close();

      throw new \Exception('Could not add user');

    }

  }

  // Add new user
  public function edit(){

    if(!isset($_POST['edit'])){
      return;
    }

    global $db;

    $id = filter_input(INPUT_POST, 'id');
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password');
    $password = hash('sha512', $password);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if(empty($username) || empty($email)){
      return;
    }

    if($_SESSION['userid'] == $id){

      $_SESSION['username'] = $username;

    }

    if(!empty($password)){

      $query = 'UPDATE users SET username = ?, password = ?, email = ? WHERE id = ?';

      $prepare = $db->prepare($query);
      $prepare->bind_param('ssss',$username,$password,$email,$id);

    } else {

      $query = 'UPDATE users SET username = ?, email = ? WHERE id = ?';

      $prepare = $db->prepare($query);
      $prepare->bind_param('sss',$username,$email,$id);

    }

    if($prepare->execute()){

      $this->event(sprintf('Edited user %s', $username));

      $prepare->close();
      return 'Succesfully edited user: ' . $username;

    } else {

      $this->event(sprintf('Failed to edit user %s', $username));

      $prepare->close();

      throw new \Exception('Could not add user');

    }

  }

  public function delete(){

    if(!isset($_POST['delete'])){
      return;
    }

    global $db;

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = 'DELETE FROM users WHERE id = ?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);

    if($prepare->execute()){

      $this->event(sprintf('Deleted user with id %s', $id));

      $prepare->close();
      return 'Succesfully deleted user with id: ' . $id;

    } else {

      $prepare->close();
      throw new \Exception('Could not delete user');

    }

  }

}

?>
