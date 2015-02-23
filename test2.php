<?php
    include_once 'db_functions.php';
    include_once 'GCM.php';
    $db = new DB_FUNCTIONS();
    $sm = new GCM();
    $users = $db->getAllUsers();

    while($row = mysql_fetch_array($users)) {
    	$sm->send_notification($row["reg_id"], "This is a test");
    }
?>
