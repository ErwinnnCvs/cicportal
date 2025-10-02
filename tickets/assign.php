<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tickets_arr = [];

// $se_provcode = $sess_provider_code;
$no_submission = 0;

$api_key = "hIf9nbsln6nsn0B2B0yu";
$password = "password123123";
$yourdomain = "creditinfoph";

$url = 'https://$yourdomain.freshdesk.com/api/v2/search/tickets?query="agent_id:null"';
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
  echo "Response Body \n";
  echo "$response \n";

  $obj = json_decode($response, true);

} else {
  if($info['http_code'] == 404) {
    echo "Error, Please check the end point \n";
  } else {
    echo $response;
  }
}

curl_close($ch);



?>