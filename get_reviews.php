<?php
/**
* 
*/

include_once './db_functions.php';
$db = new DB_Functions();
$hotel_rows = db->getRevLocation();
while($row = mysql_fetch_row($hotel_rows)){
	get_latest_reviews($row["review_id"], $row["location_id"]);
	$db->storeUser($row["review_id"], $row["location_id"], "last name");
}
	
public function get_latest_reviews($latest_id, $location_id){
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
		foreach($json_result["reviews"] as $review){
			printf($review["id"] . "\n");
			printf($review["text"] . "\n");
		}
	}
	else{
		foreach($json_result["reviews"] as $review){
			printf($review["id"] . "\n");
			printf($review["text"] . "\n");
			if($review["id"] == $$latest_id){
				break;
			}
		}
	}
}
?>