<?php

class Websites {

  private $screenshot;

  public function __construct(){

    $this->screenshot = new Screenshot;

  }

  public function getAll(){

    global $db;
    $return = [];

    $query = 'SELECT * FROM websites';

    $prepare = $db->prepare($query);
    $prepare->execute();
    $result = $prepare->get_result();

    $i = 0;

    while($row = $result->fetch_assoc()){

      $return[$i] = $row;
      $return[$i]['screenshot'] = $this->screenshot->getScreenshot($row['url']);

      $url = str_replace('https://','',$row['name']);

      // Check if online
      if($socket =@ fsockopen($url, 80, $errno, $errstr, 3)) {
        $return[$i]['status'] = 'Online';
        fclose($socket);
      } else {
        $return[$i]['status'] = 'Offline';
      }

      $i++;
    }

    $prepare->close();

    return $return;

  }

  public function getSiteHome(){

    global $db;
    $return = [];

    $userid = $_SESSION['userid'];

    $query = 'SELECT * FROM websites WHERE id=?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$userid);
    $prepare->execute();

    $result = $prepare->get_result();

    $return = $result->fetch_assoc();
    $return['screenshot'] = $this->screenshot->getScreenshot($return['url']);

    $url = str_replace('https://','',$return['name']);

    // Check if online
    if($socket =@ fsockopen($url, 80, $errno, $errstr, 3)) {
      $return['status'] = 'Online';
      fclose($socket);
    } else {
      $return['status'] = 'Offline';
    }

    $prepare->close();

    return $return;

  }

  public function getById($id){

    global $db;
    $return = [];

    $query = 'SELECT * FROM websites WHERE id=?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);
    $prepare->execute();

    $result = $prepare->get_result();

    $return = $result->fetch_assoc();
    $return['screenshot'] = $this->screenshot->getScreenshot($return['url']);

    $url = str_replace('https://','',$return['name']);

    // Check if online
    if($socket =@ fsockopen($url, 80, $errno, $errstr, 3)) {
      $return['status'] = 'Online';
      fclose($socket);
    } else {
      $return['status'] = 'Offline';
    }

    $prepare->close();

    return $return;

  }

  public function add(){

    if(!isset($_POST['add'])){
      return;
    }

    global $db;

    // Site info
    $name = filter_input(INPUT_POST, 'name');
    $url = filter_input(INPUT_POST, 'url');
    $dir = filter_input(INPUT_POST, 'dir');

    // Credentials
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    $password = hash('sha512', $password);
    $email = filter_input(INPUT_POST, 'email');

    // Config file
    $conf_file = '/etc/apache2/sites-available/' . $name . '.conf';

    if(empty($name) || empty($url) || empty($dir) || empty($username) || empty($password)){
      throw new Exception('Please fill in all fields');
    }

    $query = 'SELECT * FROM websites WHERE name = ?';

    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$name);
    $prepare->execute();

    $result = $prepare->get_result();

    if($result->num_rows > 0){
      throw new Exception(sprintf('Website %s already exists', $name));
    }

    $prepare->close();

    $query = 'INSERT INTO `websites`(`name`, `url`, `username`, `password`, `email`, `conf_file`, `directory`) VALUES (?,?,?,?,?,?,?)';

    $prepare = $db->prepare($query);
    $prepare->bind_param('sssssss',$name,$url,$username,$password,$email,$conf_file,$dir);
    $prepare->execute();

    $prepare->close();

    if(!TESTING){

      $vhost = '
      <VirtualHost *:80>
          DocumentRoot "'.$dir.'"
          ServerName '.str_replace('https://','',$url).'
          ServerAlias www.'.str_replace('https://','',$url).'

          Redirect /webpanel https://webpanel.webend.nl
      </VirtualHost>';

      $indexphp = '<h1>If you see this, that means it works!</h1>';

      if(!is_dir($dir)){
        mkdir($dir);
        file_put_contents($dir. '/index.php', $indexphp);
      }

      file_put_contents('/etc/apache2/sites-available/' . $name . '.conf', $vhost);

      $commands = 'cd /etc/apache2/sites-available ;';
      $commands .= 'a2ensite ' . $name . '.conf ;';
      $commands .= 'systemctl reload apache2 ;';

      exec($commands);

    }

    if($db->error){
      throw new Exception($db->error);
    } else {
      return 'Website added succesfully';
    }

  }

  public function addScreenshot(){

    if(!isset($_POST['addScreenshot'])){
      return;
    }

    $id = filter_input(INPUT_POST, 'id');
    $filename = filter_input(INPUT_POST, 'name');
    $filename = str_replace('.', '_', $filename);

    $dir = __DIR__ . '/../../assets/img/screenshots/';

    $file_name = $dir . $filename . '.jpg';
    $file_tmp = $_FILES['screenshot']['tmp_name'];

    if(move_uploaded_file($file_tmp, $file_name)){
      return 'Screenshot uploaded';
    } else {
      throw new \Exception('Screenshot not uploaded');
    }

  }

  public function delete(){

    if(!isset($_POST['delete'])){
      return;
    }

    global $db;
    $id = filter_input(INPUT_POST, 'id');
    $name = filter_input(INPUT_POST, 'name');

    $query = 'SELECT * FROM websites WHERE id = ?';
    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);

    $prepare->execute();

    $result = $prepare->get_result();

    $website = $result->fetch_assoc();

    $prepare->close();

    $query = 'DELETE FROM websites WHERE id = ?';
    $prepare = $db->prepare($query);
    $prepare->bind_param('s',$id);
    $prepare->execute();

    $prepare->close();

    deleteDirectory($website['directory']);

    if(!TESTING){

      $commands = 'cd /etc/apache2/sites-enabled ;';
      $commands .= 'a2dissite ' . $name . '.conf ;';
      $commands .= 'systemctl reload apache2 ;';

      exec($commands);

    }

  }


  // Admin version
  public function edit(){

    if(!isset($_POST['editConfig'])){
      return;
    }

    global $db;

    $query = 'UPDATE websites SET email = ?';

    $id = filter_input(INPUT_POST, 'id');
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');

    if(!empty($password)){
      $password = hash('sha512', $password);
      $query .= ', password =?';
      $query .= ' WHERE id = ?';

      $prepare = $db->prepare($query);
      $prepare->bind_param('sss',$email, $password, $id);
    } else {
      $query .= ' WHERE id = ?';
      $prepare = $db->prepare($query);
      $prepare->bind_param('ss',$email, $id);
    }

    $prepare->execute();

    $prepare->close();

    $conf_file = $_POST['conf_file'];
    $conf_file_content = $_POST['conf_file_content'];

    file_put_contents($conf_file, $conf_file_content);

    $commands = 'systemctl reload apache2 ;';

    exec($commands);

    return 'Website edited succesfully';

  }

  // Customer version
  public function editWebsite(){

    if(!isset($_POST['editConfig'])){
      return;
    }

    global $db;

    $query = 'UPDATE websites SET email = ?';

    $id = $_SESSION['userid'];
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');

    if(!empty($password)){
      $password = hash('sha512', $password);
      $query .= ', password =?';
      $query .= ' WHERE id = ?';

      $prepare = $db->prepare($query);
      $prepare->bind_param('sss',$email, $password, $id);
    } else {
      $query .= ' WHERE id = ?';
      $prepare = $db->prepare($query);
      $prepare->bind_param('ss',$email, $id);
    }

    $prepare->execute();

    $prepare->close();

    $conf_file = $_POST['conf_file'];
    $conf_file_content = $_POST['conf_file_content'];

    file_put_contents($conf_file, $conf_file_content);

    $commands = 'systemctl reload apache2 ;';

    exec($commands);

    return 'Website edited succesfully';

  }

  public function reloadApache(){

    if(isset($_POST['reloadApache'])){

      $commands = 'systemctl reload apache2 ;';

      exec($commands);

    }

  }

}



?>
