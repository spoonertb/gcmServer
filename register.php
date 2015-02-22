<?php
 
// response json
$json = array();
#Public public_msg;

 
/**
 * Registering a user device
 * Store reg id in users table
 */
if (isset($_POST["last_name"]) && isset($_POST["first_name"]) && isset($_POST["reg_id"])) {
    $last_name = $_POST["last_name"];
    $first_name = $_POST["first_name"];
    $reg_id = $_POST["reg_id"]; // GCM Registration ID
    // Store user details in db
    include_once './db_functions.php';
    include_once './GCM.php';
 
    $db = new DB_Functions();
 
    $res = $db->storeUser($reg_id,$first_name, $last_name);
 
    $reg_ids = array($reg_id);
    $message = array("product" => "shirt");
 
#    $result = $gcm->send_notification($reg_ids, $message);
 
    echo $reg_id;
} else {
    // user details missing
}
?>
