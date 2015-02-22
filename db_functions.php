<?php
 
class DB_Functions {
 
    private $con;
    private $msg;
    private $db;
    public static $public_msg="woo";
 
    //put your code here
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
        $result = $this->db->query("INSERT IGNORE INTO gcm_users(reg_id, first_name, last_name) VALUES('$reg_id', '$first_name', '$last_name')");
        // check for successful store
        $public_msg=$result;
        if ($result) {
            // get user details
            $id = mysql_insert_id(); // last inserted id
            $result = $this->db->query("SELECT * FROM gcm_users WHERE reg_id = $reg_id") or die(mysql_error());
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
        $result = mysql_query("select * FROM gcm_users");
        return $result;
    }

 
}
 
?>
