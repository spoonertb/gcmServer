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
        $this->db = new DB_Connect();
        $this->db->connect();
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

    public function storePropertyId($property_id, $reg_id){
        $result = mysql_query("INSERT IGNORE INTO user_hotels(reg_id, location_id) VALUES('$reg_id', '$property_id')");
        mysql_query("INSERT IGNORE INTO hotels(location_id) VALUES('$property_id')");

        if ($result) {
            // get user details
            //$id = mysql_insert_id(); // last inserted id
            //$result = mysql_query("SELECT * FROM user_hotels WHERE reg_id = $reg_id & location_id = $property_id") or die(mysql_error());

            // return user details
            if (mysql_num_rows($result) > 0) {
               // mysql_query("INSERT IGNORE INTO hotels(location_id) VALUES('$property_id')");
                return mysql_fetch_array($result);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getRevLocation(){
        $result = mysql_query("SELECT * FROM hotels");
        return $result;
    }
 
    /**
     * Getting all users
     */
    public function getAllUsers() {
        $result = mysql_query("SELECT * FROM users");
        return $result;
    }
 
}
 
?>
