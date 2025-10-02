<?php 

$api_key = "hIf9nbsln6nsn0B2B0yu";
$password = "password123123";
$yourdomain = "creditinfoph";


$ticket_data = json_encode(array(
  "priority" => $priority,
  "status" => $status
));

// Id of the ticket to be updated
$ticket_id = $ticket;

$url = "https://$yourdomain.freshdesk.com/api/v2/tickets/$ticket_id";

$ch = curl_init($url);

$header[] = "Content-type: application/json";
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
curl_setopt($ch, CURLOPT_POSTFIELDS, $ticket_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($server_output, 0, $header_size);
$response = substr($server_output, $header_size);


if($info['http_code'] == 200) {
  $msg = "Successfully updated the ticket.";
  $msgclr = "success";
} else {
  if($info['http_code'] == 404) {
    $msg = "Error, Please check the end point.";
    $msgclr = "warning";
  } else {
    $msg = "Error updating ".$ticket_id.", HTTP Status Code : " . $info['http_code'];
    $msgclr = "danger";
  }
}

curl_close($ch);

?>