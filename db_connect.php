<?php
  
class DB_Connect {
  
    // constructor
    function __construct() {
  
    }
  
    // destructor
    function __destruct() {
        // $this->close();
    }
  
    // Connecting to database
    public function connect() {
        require_once 'config.php';
        // connecting to mysql
        //$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
       $con = new mysqli(DB_IP, DB_USER, DB_PASSWORD, DB_DATABASE);
        // selecting database
        //mysql_select_db(DB_DATABASE);
        //$sql = new mysqli(null, $user, $pass, null, null, "/cloudsql/sandylizapp:userdata");
       // $con = new pdo('mysql:unix_socket=')
       // $db = new pdo('mysql:host=173.194.250.45:3306;dbname=gcm', 'root', '');
  
        // return database handler
        return $con;
    }
  
    // Closing database connection
    public function close() {
        mysql_close();
    }
  
}
?>
