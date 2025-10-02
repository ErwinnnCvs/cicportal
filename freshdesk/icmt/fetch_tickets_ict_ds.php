<?php
// //ERROR REPORTING
ini_set('memory_limit','85M');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');

//$dbh4 = new mysqli("localhost", "root", "", "dev_80_cicseis");
//include('config.php');
include('../../config.php');

$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";

$hour = 10;
$year = "2024";
$month = "02";
$day = "06";
$date = $year."-".$month."-".$day;
$currDate = Date('Y-m-d H:i:s');
// $date = "2023-11-10";
// $hour = $_GET['hour'];
$pages = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");

$getLastFetchedTimestamp = $dbh4->query("SELECT fld_last_fetched_ts FROM `tbict_freshdesk` WHERE fld_last_fetched_ts IS NOT NULL AND fld_group_id = 33000148658 ORDER BY fld_last_fetched_ts DESC LIMIT 1");
$glft = $getLastFetchedTimestamp->fetch_array();

$lastFetchedDate = new DateTime($glft['fld_last_fetched_ts']);
$lfd = $lastFetchedDate->format('Y-m-d');

$today = Date('Y-m-d');

if (!$glft) {
	$filter = "";
}
else {
	if ($lfd == $today) {
		$filter = '%20AND%20(created_at:%27'.$lfd.'%27)';
	}
	elseif ($lfd < $today) {
		$filter = '%20AND%20(created_at:>%27'.$lfd.'%27%20AND%20created_at:<%27'.$today.'%27)';
	}
}

//$url = "https://creditinfoph.freshdesk.com/api/v2/tickets/394037";
//$url = "https://creditinfoph.freshdesk.com/api/v2/tickets/395359/conversations";

foreach($pages as $page) {

	//$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$page.'&query="(group_id:33000148658)%20AND%20(created_at:>%27'.$lfd.'%27%20AND%20created_at:<%27'.$today.'%27)"';

	$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$page.'&query="(group_id:33000148658)'.$filter.'"';
	
	//$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$page.'&query="(group_id:33000148658%20AND%20created_at:%27'.$lfd.'%27)"';

	//$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page=1&query="(group_id:33000148658)"';

	//echo $url;
	//echo "<br>";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($server_output, 0, $header_size);
$response = substr($server_output, $header_size);

$ctr = 1;

if($info['http_code'] == 200) {

	$obj = json_decode($response, true);
	// print_r($obj);

	//foreach($obj['results'] as $o) {
	foreach ($obj as $key => $value) {
		foreach ($value as $o) {
			$createdDateFromFreshDesk = str_replace("T", " ",$o['created_at']);
			$createdDateUTC = str_replace("Z", "",$createdDateFromFreshDesk);
			$createdDateConvert = strtotime($createdDateUTC. ' UTC');
			$createdDate = date("Y-m-d H:i:s", $createdDateConvert);
  
			$updatedDateFromFreshDesk = str_replace("T", " ",$o['updated_at']);
			$updatedDateUTC = str_replace("Z", "",$updatedDateFromFreshDesk);
			$updatedDateConvert = strtotime($updatedDateUTC. ' UTC');
			$updatedDate = date("Y-m-d H:i:s", $updatedDateConvert);

			$dueDateFromFreshdesk = str_replace("T", " ",$o['due_by']);
			$dueDateUTC = str_replace("Z", "",$dueDateFromFreshdesk);
			$dueDateConvert = strtotime($dueDateUTC. ' UTC');
			$dueDate = date("Y-m-d H:i:s", $dueDateConvert);
  
			$descriptionText = addslashes($o['description_text']);
			$description =  addslashes($o['description']);
			$subject = addslashes($o['subject']);

			//$to_emails = implode(" | ",$o['to_emails']);

			// 	if ($o['id'] >= 385900 && $o['id'] <= 385905) { 
			//echo "INSERT INTO tbict_freshdesk (fld_ticket_id, fld_subject, fld_description, fld_created_at, fld_updated_at, fld_status, fld_due_date, fld_date_insert, fld_group_id) VALUES ('".$o['id']."', '".$subject."', '".$description."', '".$createdDate."', '".$updatedDate."', '".$o['status']."', '".$dueDate."', '".$currDate."', '".$o['group_id']."') ";
			// echo $url;
			// echo "<br>";
			$checkTicket = $dbh4->query("SELECT * FROM tbict_freshdesk WHERE fld_ticket_id = '".$o['id']."'");
				if (!$chkTkt = $checkTicket->fetch_array()) {
					$saveTicket = $dbh4->query("INSERT INTO tbict_freshdesk (fld_ticket_id, fld_subject, fld_description_text, fld_created_at, fld_updated_at, fld_status, fld_due_date, fld_date_insert, fld_group_id, fld_description, fld_last_fetched_ts) VALUES ('".$o['id']."', '".$subject."', '".$descriptionText."', '".$createdDate."', '".$updatedDate."', '".$o['status']."', '".$dueDate."', '".$currDate."', '".$o['group_id']."', '".$description."', '".$currDate."')");
					// echo "TICKET SAVED";
					// echo "<br>";
				}
				else {
					// echo "THIS TICKET ALREADY EXISTS";
					// echo "<br>";
				}

				//print_r($obj);

			// echo $ctr++." ticket ID: " . $o['id'];
			// echo "<br>";
			// echo "Ticket Subject: " . $o['subject'];
			// echo "<br>";
			// echo "Ticket Date: " . $o['created_at'];
			// echo "<br>";
			// echo "Converted Date: " . $createdDate;
			// echo "<br>";
			// echo "Group: " . $o['group_id'];
			// echo "<br>";
			// echo "Status: " . $o['status'];
			// echo "<br>";
			// echo "Updated: " . $o['updated_at'];
			// echo "<br>";
			// echo "Description: " . $o['description_text'];
			// echo "<br>";
			// echo "Due by: " . $o['due_by'];
			// echo "<br>";
			// echo "------------------------------------<br>";
		}
		//print_r($obj);
	}


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

}

?>