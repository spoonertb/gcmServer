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
                    <?php }
                } else { ?>

                    <li>
                        No Users Registered Yet!
                    </li>
                   
                    
                <?php } ?>
                 <?php
                    //include_once 'db_functions.php';
                    //include_once 'GCM.php';

                    $hotel_rows = $db->getRevLocation();

                    // Iterate through a list of properties
                    while($hotel = mysql_fetch_assoc($hotel_rows)){
                        //$ids = $db->getAllRegIds($hotel["location_id"]);
                        //Iterate through list of devices subscribed to a $row
                        //while($device = mysql_fetch_assoc($ids)) {   
                        get_latest_reviews($hotel["review_id"], $hotel["location_id"]);
                           // get_latest_reviews($hotel["review_id"], $hotel["location_id"], $device["reg_id"]);
                        //}
                        // $db->storeUser($row["review_id"], $row["location_id"], "last name");
                        // print_r($row[0] . "ROW\n");
                    }

                    // Get newest reviews up until $latest_id and send to devices where appropriate
                    function get_latest_reviews($latest_id, $location_id) {
                        include_once './config.php';
                        //nclude_once 'GCM.php';
                        $db = new DB_Functions();
                        $gcm = new GCM();
                        //$db = new DB_Functions();
                        echo "location id: " . $location_id . "\n";
                        //echo "latest_id: ". $latest_id . "\n";

                        $ids = $db->getAllRegIds($location_id);
                        $devs=array();
                        while($row=mysql_fetch_row($ids)) $devs[]=$row[0];
                        mysql_free_result($ids);
                        //$devs = mysql_fetch_assoc($ids);

                        echo "Regs: ";
                        $url="http://api.tripadvisor.com/api/partner/2.0/location/" . $location_id . "?key=" . TRIPADVISOR_PARTNER_API_KEY;
                        $context=array(
                            'http' => array(
                                'method' => 'GET')
                        );
                        $context = @stream_context_create($context);
                        $result = @file_get_contents($url, false, $context);
                        $json_result = json_decode($result, true);

                        /* The Property has just been added to the db for the first time. Set first review as recent, don't send push
                        * No latest review means this system has not been run on this hotel before
                        * Non-Null $json_result means new reviews. Don't iterate because this could be in the thousands...
                        */
                        if(($latest_id == null) && ($json_result["reviews"] != null)){
                            printf("New Property \n");
                            //printf("first value" . current($json_result["reviews"]). "\n");
                            //$first_review;                            
                            foreach ($json_result["reviews"] as $review) {
                                //Set the most recent review to the first id from the API and break
                                $first_review=$review;
                                break;
                            }
                            //printf("first review" . $first_review["id"]);
                            $db= new DB_Functions;
                            //Set most recent review in hotels db to this first encountered review
                            $db->updateMostRecent($location_id, $first_review["id"]);
                            /*
                            foreach($json_result["reviews"] as $review){
                                printf("Check For Non Null First Review\n");                                    
                                //$gcm->send_notification($reg_id, $review);
                                $gcm->send_notification(array($reg_id), array($review["id"] => $review["text"]));
                            }
                            */
                           
                            //$gcm->send_notification($devs, array("GCM Server" => "This property is new, you may have several unread reviews."));
                            $gcm->send_notification($devs, array("GCM Server" => "This property is new, you may have several unread reviews."));
                    
                        }

                        /* The system has found new reviews from the TA API. Send each one to the devices
                        * Trying to multicast each review (set of reviews) to each property. Saves on calls to GCM                    
                        */
                        else if ($location_id != $json_result["reviews"][0]["id"] && $json_result["reviews"][0]["id"] != null) {
                            //printf("inside if not null \n");
                            foreach($json_result["reviews"] as $review){                                
                                //printf($review["id"] . "\n");
                                //printf($review["text"] . "\n"); 
                                //Iterate until the previous 'most recent review' is reached. This set is sent to the device
                                if($review["id"] == $latest_id){
                                    break;
                                }
                                /*Send unseen reviews to devices as a multicast*/
                                $gcm->send_notification($devs["reg_id"], array($review["id"] => $review["text"]));

                            }
                            //$gcm->send_notification(array($devs["reg_id"]), array($review));

                            $db->updateMostRecent($location_id, $json_result["reviews"][0]["id"]);                            
                        }
                        echo "End \n";
                    }
                    ?>
            </ul>
        </div>
    </body>
</html>
