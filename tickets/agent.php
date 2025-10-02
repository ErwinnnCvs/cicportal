<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// require_once("../config.php");
$ticket_date = "2024-03-11";



$tickets_arr = [];

// $se_provcode = $sess_provider_code;
$no_submission = 0;

$api_key = "hIf9nbsln6nsn0B2B0yu";
$password = "password123123";
$yourdomain = "creditinfoph";

$ticket_date = "2024-05-11";

$created = 'created_at:%27'.$ticket_date.'%27';

// curl -v -u yourapikey:X -X GET 'https://domain.freshdesk.com/api/v2/search/tickets?query="agent_id:2%20AND%20priority:4"'

// $url = "https://$yourdomain.freshdesk.com/api/v2/agents";
$url = "https://$yourdomain.freshdesk.com/api/v2/agents/".$agent_id_dropdown;
echo $url;
echo "<br>";
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
  // echo "Tickets fetched successfully, the response is given below \n";
  // echo "Response Headers are \n";
  // echo $headers."\n";
  // echo "Response Body \n";
  // echo "$response \n";

  $obj = json_decode($response, true);

  // echo "<br><br><br>-------<br>";
  // echo "ID: ".$obj['id']."<br>";
  // echo "CREATED AT: ".$obj['created_at']."<br>";
  // echo "UPDATED AT: ".$obj['updated_at']."<br>";

  // // echo "signature: ".$obj['signature']."<br>";
  // echo "ACTIVE: ".$obj['contact']['active']."<br>";
  // echo "EMAIL: ".$obj['contact']['email']."<br>";
  $agent_name = $obj['contact']['name'];
} else {
  if($info['http_code'] == 404) {
    // echo "Error, Please check the end point \n";
  } else {
    // echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";
    // echo "Headers are ".$headers;
    // echo $response;
  }
}

curl_close($ch);



?>