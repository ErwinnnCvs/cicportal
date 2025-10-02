<?php
// //ERROR REPORTING
ini_set('memory_limit','85M');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');

$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";

$hour = 10;
$year = "2024";
$month = "02";
$day = "06";
$date = $year."-".$month."-".$day;
// $date = "2023-11-10";
// $hour = $_GET['hour'];
$pages = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");



$url = 'https://creditinfoph.freshdesk.com/api/v2/tickets/493380"';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($server_output, 0, $header_size);
$response = substr($server_output, $header_size);
if($info['http_code'] == 200) {

	$obj = json_decode($response, true);
	// print_r($obj);

	print_r($obj);

} else {
	if($info['http_code'] == 404) {
	echo "Error, Please check the end point \n";
	} else {
	echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";
	echo "Headers are ".$headers;
	echo "Response are ".$response;
	}
}
curl_close($ch);



?>