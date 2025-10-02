;<?php
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

// echo $_SESSION['user_id'] . "<br>";

$getFreshdeskId = $dbh5->query("SELECT * from tbictpersonnel WHERE fld_userid = '".$_SESSION['user_id']."' LIMIT 1");
$user = $getFreshdeskId->fetch_array();

// $getTickets = $dbh4->query("SELECT * from tbict_freshdesk");
// while ($gt = $getTickets->fetch_array()) {
//     echo "Ticket ID: ".$gt['fld_ticket_id']."<br>";

//echo $user['fld_freshdesk_id'];

$reply_payload = array(
	'body' => $_POST['compose-textarea'],
	//'body' => "TEST REPLY",
	//'user_id' => $user['fld_freshdesk_id'],
	'attachments[]' => curl_file_create($fileTmpName, $fileType, $fileName)
	//'attachments[]' => curl_file_create("files/test file again.docx", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "test file again.docx")
);

$url = "https://creditinfoph.freshdesk.com/api/v2/tickets/415261/reply";
//$url = "https://creditinfoph.freshdesk.com/api/v2/tickets/".$ticket_id."/reply";

//foreach($pages as $page) {
	//$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$page.'&query="(created_at:%272024-08-28%27%20AND%20updated_at:%272024-08-28%27)"';

	//echo $url;
	//echo "<br>";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $reply_payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($server_output, 0, $header_size);
$response = substr($server_output, $header_size);

if($info['http_code'] == 200 || $info['http_code'] == 201) {

	$obj = json_decode($response, true);
	$msg = "Reply Sent!";
    $msgclr = "success";

    //echo "working";

    // foreach ($obj as $key) {

	//print_r($obj);

    // }

} else {
	if($info['http_code'] == 404) {
	$msg = "Error, Please check the end point \n";
	$msgclr = "danger";
	//echo "Error, Please check the end point \n";
	} else {
	$msg = "Error, HTTP Status Code : " . $info['http_code'] . "\n" . "Headers are ".$headers . "\n" . "Response are ".$response . "\n";
    $msgclr = "danger";
	// echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";
	// echo "Headers are ".$headers;
	// echo "Response are ".$response;
	}
}
curl_close($ch);

//}

?>