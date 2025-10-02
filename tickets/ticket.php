<?php 

$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";

// Return the tickets that are new or opend & assigned to you
// If you want to fetch all tickets remove the filter query param
$url = "https://$yourdomain.freshdesk.com/api/v2/tickets/367026";#?include=conversations

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


  foreach($obj['attachments'] as $attachments){
    echo $attachments['name']."<br>";
    echo $attachments['attachment_url']."<br>";
  }
  echo "<br><br><br>";
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