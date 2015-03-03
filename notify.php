<?php
    echo "Succeeded2";

    include_once 'db_functions.php';
    include_once 'GCM.php';
    $db = new DB_FUNCTIONS();
    $sm = new GCM();
    $users = $db->getAllUsers();
    $message2 = array("GcmServer Notification" => "notify");

    while($row = mysql_fetch_assoc($users)) {
    	$sm->send_notification($row["reg_id"], $message2);
    }
?>