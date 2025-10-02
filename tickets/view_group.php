<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$no_submission = 0;

$api_key = "hIf9nbsln6nsn0B2B0yu";
$password = "password123123";
$yourdomain = "creditinfoph";

$url = "https://creditinfoph.freshdesk.com/api/v2/groups/".$group_id;

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($server_output, 0, $header_size);
$response = substr($server_output, $header_size);

$group_view = array();

$agents = array();
if($info['http_code'] == 200) {

  $group_view = json_decode($response, true);

  // $id = $obj['id'];
  // $name = $obj['name'];
  // foreach ($obj['agent_ids'] as $key => $value) {
  //   array_push($agents, $value);
  // }

  // $group_view[$id] = array("name"=>$name, "agent_ids"=>$)
  


  

} else {
  if($info['http_code'] == 404) {
    $msg = "Error, Please check the end point View Group";
    $msgclr = "danger";
  } else {
    $msg = "Error, HTTP Status Code : " . $info['http_code'];
    $msgclr = "danger";
  }
}

curl_close($ch);



?>