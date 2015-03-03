<?php
 
class GCM {
 
    //put your code here
    // constructor
    public static $public_msg="initial";
    function __construct() {
         
    }
 
    /**
     * Sending Push Notification
     */
    public function send_notification($reg_ids, $message) {
        // include config
        include_once './config.php';
        include_once 'db_functions.php';
        $db=new DB_Functions();
 
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
        


        $json = array( 
            'data' => $message, 
            'registration_ids' => $reg_ids
        );

        $data = json_encode( $json );
        $context = array( 
            'http' => array(
                'method' => 'POST',
                'header' => 'Authorization: key=' . GOOGLE_API_KEY . "\r\n" . 'Content-Type: application/json' . "\r\n",
                'content' => $data,
                'alert' => 'This is a test'
            )
        );
        $context = @stream_context_create($context);
        $result = @file_get_contents("https://android.googleapis.com/gcm/send", false, $context);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            //self::$public_msg=$curl_error($ch);
            //$db->storeUser("error", "last", "first");
            echo "Failed";
        }
        else{
            //$db->storeUser("success", "last", "first");
        }
    }

    public static function get_public_msg(){
        return self::$public_msg;
    }
 
}
 
?>
