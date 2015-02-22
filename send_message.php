<?php
if (isset($_GET["reg_id"]) && isset($_GET["message"])) {
    $reg_id = $_GET["reg_id"];
    $message = $_GET["message"];
     
    include_once './GCM.php';
     
    $gcm = new GCM();
 
    $registatoin_ids = array($reg_id);
    $message = array("GcmServer Notification" => $message);
 
    $result = $gcm->send_notification($registatoin_ids, $message);
 
    echo $result;
}
?>
