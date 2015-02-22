<?php

$url = "http://api.tripadvisor.com/api/partner/2.0/location/258705/reviews/?key=F421BF121238453AB5E56EFCC11AE1CA";

$ch = curl_init($url);
$json = curl_exec($ch);

$jsonIterator = new RecursiveIteratorIterator(
	

#$json = json_decode($fp);
#echo $json[0]->{'data'};
?>
