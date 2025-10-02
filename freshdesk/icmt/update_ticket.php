<?php
// //ERROR REPORTING
ini_set('memory_limit','85M');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');

include('../../config.php');
include('../../views/cicP14922.php');

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

$user_id = 33047927537;



// $getTickets = $dbh4->query("SELECT * from tbict_freshdesk");
// while ($gt = $getTickets->fetch_array()) {
//     echo "Ticket ID: ".$gt['fld_ticket_id']."<br>";

$update_ticket = json_encode(array(
    'status' => $statusCode
	//'body' => "TEST REPLY FROM CIC APPSDEV",
	//'user_id' => $user_id,
	//'attachments[]' => curl_file_create("file/SAMPLE.docx", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "SAMPLE.docx")
));

$url = 'https://creditinfoph.freshdesk.com/api/v2/tickets/415261"';
//$url = "https://creditinfoph.freshdesk.com/api/v2/tickets/".$ticket_id."";

//foreach($pages as $page) {
	//$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$page.'&query="(created_at:%272024-08-28%27%20AND%20updated_at:%272024-08-28%27)"';

	//echo $url;
	//echo "<br>";

$ch = curl_init($url);
$header[] = "Content-type: application/json";
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $update_ticket);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($server_output, 0, $header_size);
$response = substr($server_output, $header_size);

if($info['http_code'] == 200) {

	$obj = json_decode($response, true);

	if ($dbh4->query("UPDATE tbict_freshdesk SET fld_status = '".$statusCode."' WHERE fld_ticket_id = '".$ticket_id."'")) {
		//echo working;
	}

    //echo "working";

    // foreach ($obj as $key) {

	//print_r($obj);

    // }

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

//}

?>