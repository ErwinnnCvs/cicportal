<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// require_once("../config.php");

$no_submission = 0;

$api_key = "hIf9nbsln6nsn0B2B0yu";
$password = "password123123";
$yourdomain = "creditinfoph";

$url = "https://creditinfoph.freshdesk.com/api/v2/groups";

// echo $url;
// echo "<br>";
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($server_output, 0, $header_size);
$response = substr($server_output, $header_size);

$groups_arr = array();

if($info['http_code'] == 200) {
  $obj = json_decode($response, true);
  foreach ($obj as $key => $value) {
    $groups_arr[$value['id']] = $value['name'];
  }

  echo json_encode($groups_arr);
} else {
  if($info['http_code'] == 404) {
    $msg = "Error, Please check the end point of Groups";
    $msgclr = "danger";
  } else {
    // echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";
    // echo "Headers are ".$headers;
    // echo $response;
  }
}

curl_close($ch);



?>