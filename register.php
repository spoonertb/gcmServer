<?php
 
// response json
//$json = array();
//Public public_msg;


 
/**
 * Registering a user device
 * Store reg id in users table
 */
if (isset($_POST["last_name"]) && isset($_POST["first_name"]) && isset($_POST["reg_id"]) && isset($_POST["property_id"])) {
    $last_name = $_POST["last_name"];
    $first_name = $_POST["first_name"];
    $reg_id = $_POST["reg_id"]; // GCM Registration ID
    $property_id = $_POST["property_id"];
    // Store user details in db
    //echo $reg_id;
    include_once './db_functions.php';
    include_once './GCM.php';
 
    $db = new DB_Functions();
 
    $res = $db->storeUser($reg_id, $first_name, $last_name);

    if($res != false){
        $ids = explode(",", $property_id);
        foreach ($ids as $id) {
            $res = $db->storePropertyId($id, $reg_id);
        }
        echo "Success";
    }
 
    $reg_ids = array($reg_id);
    $message = array("GcmService" => "Device Registered with Push notification server");
 
    $gcm = new GCM();
    $result = $gcm->send_notification($reg_ids, $message);
 
    //echo $reg_id;
} else {
    // user details missing
    echo "Failed";
}
?>
