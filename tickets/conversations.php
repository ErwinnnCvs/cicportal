<?php


$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";

// Return the tickets that are new or opend & assigned to you
// If you want to fetch all tickets remove the filter query param
$url = "https://$yourdomain.freshdesk.com/api/v2/tickets/358054/conversations";

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


  foreach ($obj as $key) {
    echo "<b>ID</b>: ".$key['id']."<br>"; 
    echo "<b>CREATED_AT</b>: ".$key['created_at']."<br>";
    echo "<b>FROM</b>: ".$key['from_email']."<br>";

    foreach ($key['cc_emails'] as $cc_email) {
      echo $cc_email."|";
    }
    echo "<br> <b>TO</b>: ";
    echo $key['incoming'];
    foreach ($key['to_emails'] as $to_email) {
      echo $to_email."|";
    }

    echo "<br>";
    echo "<b>BODY</b>: ".$key['body_text'];
    echo "|<br><br><br>";
  }
  echo "<br>";
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