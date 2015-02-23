<?php
 
class DB_Functions {
 
    private $con;
    private $msg;
    private $db;
    public static $public_msg="woo";
 
    // constructor
    function __construct() {
        include_once './db_connect.php';
        // connecting to database
        $this->con = new DB_Connect();
        $this->db = $this->con->connect();
    }
 
    // destructor
    function __destruct() {
         
    }
 
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($reg_id, $first_name, $last_name) {
        // insert user into database
        //$result = $this->db->query("INSERT IGNORE INTO users(reg_id, first_name, last_name) VALUES('$reg_id', '$first_name', '$last_name')");
        $result = mysql_query("INSERT IGNORE INTO users(reg_id, first_name, last_name) VALUES('$reg_id', '$first_name', '$last_name')");

        // check for successful store
        $public_msg=$result;
        if ($result) {
            // get user details
            $id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM users WHERE reg_id = $id") or die(mysql_error());

            // return user details
            if (mysql_num_rows($result) > 0) {
                return mysql_fetch_array($result);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
 
    /**
     * Getting all users
     */
    public function getAllUsers() {
        $result = mysql_query("select * FROM users");
        return $result;
    }

<<<<<<< HEAD
=======
    public function getMsg(){
        if(!($this->msg)){
            return "fail";
        }
        else{
            return "successful";
        }
    }

    public function getPubMsg(){
        return self::$public_msg;
    }
>>>>>>> df206c28864890679b0a8a4913ceb8b57f597025
 
}
 
?>
