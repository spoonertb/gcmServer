<!DOCTYPE html>

<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
            });
            function sendPushNotification(id){
                var data = $('form#'+id).serialize();
                $('form#'+id).unbind('submit');               
                $.ajax({
                    url: "send_message.php",
                    type: 'GET',
                    data: data,
                    beforeSend: function() {
                         
                    },
                    success: function(data, textStatus, xhr) {
                        $('.txt_message').val("");
                    },
                    error: function(xhr, textStatus, errorThrown) {
                         
                    }
                });
                return false;
            }
        </script>
        <style type="text/css">
            .container{
                width: 950px;
                margin: 0 auto;
                padding: 0;
            }
            h1{
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 24px;
                color: #777;
            }
            div.clear{
                clear: both;
            }
            ul.devices{
                margin: 0;
                padding: 0;
            }
            ul.devices li{
                float: left;
                list-style: none;
                border: 1px solid #dedede;
                padding: 10px;
                margin: 0 15px 25px 0;
                border-radius: 3px;
                -webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                -moz-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                color: #555;
            }
            ul.devices li label, ul.devices li span{
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 12px;
                font-style: normal;
                font-variant: normal;
                font-weight: bold;
                color: #393939;
                display: block;
                float: left;
            }
            ul.devices li label{
                height: 25px;
                width: 50px;               
            }
            ul.devices li textarea{
                float: left;
                resize: none;
            }
            ul.devices li .send_btn{
                background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -webkit-linear-gradient(0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -moz-linear-gradient(center top, #0096FF, #005DFF);
                background: linear-gradient(#0096FF, #005DFF);
                text-shadow: 0 1px 0 rgba(0, 0, 0, 0.3);
                border-radius: 3px;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <?php
        include_once 'db_functions.php';
        include_once 'register.php';

        $db = new DB_Functions();
        //$db->storeUser("point", "last", "first");
        $users = $db->getAllUsers();
        include_once 'GCM.php';
        $gcm = new GCM();
        //echo GCM::get_public_msg();
       /* $db->storeProertyId("123", "123");
        $db->storeProertyId("123", "123");*/
        if ($users)
            $no_of_users = mysql_num_rows($users);
        else
            $no_of_users = 0;
        ?>
        <div class="container">
            <h1>No of Devices Registered: <?php echo $no_of_users; ?></h1>
            <hr/>
            <ul class="devices">
                <?php
                if ($no_of_users > 0) {
                    ?>
                    <?php
                    while ($row = mysql_fetch_array($users)) {
                        ?>
                        <li>
                            <form id="<?php echo $row["reg_id"] ?>" name="" method="post" onsubmit="return sendPushNotification('<?php echo $row["reg_id"] ?>')">
                                <label>First Name: </label> <span><?php echo $row["first_name"] ?></span>
                                <div class="clear"></div>
                                <label>Last Name:</label> <span><?php echo $row["last_name"] ?></span>
                                <div class="clear"></div>
                                <div class="send_container">                               
                                    <textarea rows="3" name="message" cols="25" class="txt_message" placeholder="Type message here"></textarea>
                                    <input type="hidden" name="reg_id" value="<?php echo $row["reg_id"] ?>"/>
                                    <input type="submit" class="send_btn" value="Send" onclick=""/>
                                </div>
                            </form>
                        </li>
                        <?php
                       // include_once 'GCM.php';
                        $gcm = new GCM();
                        echo $row["reg_id"];
                        $registration_ids = array($row["reg_id"]);
                        $message = array("GcmServer Notification" => "index");
                       // $result = $gcm->send_notification($registration, $message);
                        //echo $result;
                        ?>
                    <?php }
                } else { ?>

                    <li>
                        No Users Registered Yet!
                    </li>
                   
                    
                <?php } ?>
                 <?php
                    include_once 'db_functions.php';
                    include_once 'GCM.php';
                    $db = new 
                    $hotel_rows = $db->getRevLocation();
                    //echo "under get \n";
                    while($row = mysql_fetch_assoc($hotel_rows)){
                        $ids = $db->getAllRegIds($row["location_id"]);
                        while($row2 = mysql_fetch_assoc($ids)) {
                            get_latest_reviews($row["review_id"], $row["location_id"], $row2["reg_id"]);
                        }
                       // $db->storeUser($row["review_id"], $row["location_id"], "last name");
                       // print_r($row[0] . "ROW\n");
                        //echo $row["location_id"] . "location_id\n";
                    }

                    function get_latest_reviews($latest_id, $location_id, $reg_id) {
                        include_once './config.php';

                        $url="http://api.tripadvisor.com/api/partner/2.0/location/" . $location_id . "?key=" . TRIPADVISOR_PARTNER_API_KEY;
                        $context=array(
                            'http' => array(
                                'method' => 'GET')
                        );
                        $context = @stream_context_create($context);
                        $result = @file_get_contents($url, false, $context);
                        $json_result = json_decode($result, true);
                        if($latest_id == null){
                            $db->updateMostRecent($location_id, $json_result["reviews"][0]["id"]);
                            foreach($json_result["reviews"] as $review){
                                printf($review["id"] . "\n");
                                printf($review["text"] . "\n");
                                $gcm->send_notification($reg_id, $review);
                            }
                        }
                        else if ($location_id != $json_result["reviews"][0]["id"]) {
                            foreach($json_result["reviews"] as $review){                                
                                printf($review["id"] . "\n");
                                printf($review["text"] . "\n");                                
                                if($review["id"] == $latest_id){
                                    break;
                                }
                                $gcm->send_notification($reg_id, $review);                                
                            }
                            $db->updateMostRecent($location_id, $json_result["reviews"][0]["id"]);                            
                        }
                    }
                    ?>
            </ul>
        </div>
    </body>
</html>
