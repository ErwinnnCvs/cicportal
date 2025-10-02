<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

require_once("../../var/www/html/mycic/config.php");
$start = microtime(true);


$api_key = "hIf9nbsln6nsn0B2B0yu";
$password = "password123123";
$yourdomain = "creditinfoph";

// $date = date("Y-m-d");
// $date = "2024-01-07";

$begin = new DateTime('2024-10-01');
$end = new DateTime('2024-10-17');

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);

foreach ($period as $dt) {
    $date = $dt->format("Y-m-d");

$pages = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");

foreach ($pages as $num) {
$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$num.'&query="group_id:33000215540%20AND%20(created_at:%27'.$date.'%27%20OR%20updated_at:%27'.$date.'%27)%20AND%20(status:2%20OR%20status:3%20OR%20status:9)"';

echo $url."\n\n";
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

if($info['http_code'] == 200) {
  $obj = json_decode($response, true);
  
  foreach ($obj['results'] as $key => $value) {
    $ticket_id = $value['id'];
    $subject = $value['subject'];
    $description = $value['description'];
    $description_text = $value['description_text'];
    $priority = $value['priority'];
    $status = $value['status'];
    $created_at = $value['created_at'];
    $updated_at = $value['updated_at'];
    $requester_id = $value['requester_id'];
    $group_id = $value['group_id'];
    $responder_id = $value['responder_id'];
    $due_by = $value['due_by'];
    $tags = '';
    foreach ($value['tags'] as $tag) {
      $tags = $tag."|";
    }

    // echo $ticket_id."<br>";

    // echo $subject."<br>";
    // echo $responder_id."<br>";


    $check_existing = $dbh->query("SELECT fld_id FROM tbfreshdesk WHERE fld_ticket_id = ".$ticket_id);
    if (mysqli_num_rows($check_existing) > 0) {
      echo  $ticket_id. " EXISTED\n";
    } else {
      if($dbh->query("INSERT INTO tbfreshdesk (fld_ticket_id, fld_subject, fld_description, fld_description_text, fld_priority, fld_status, fld_created_at, fld_updated_at, fld_tags, fld_group_id, fld_requester_id, fld_responder_id, fld_due_by, fld_type) VALUES (".$ticket_id.", '".addslashes($subject)."', '".addslashes(htmlspecialchars($description))."', '".addslashes(htmlspecialchars($description_text))."', ".$priority.", ".$status.", '".$created_at."', '".$updated_at."', '".$tags."', '".$group_id."', '".$requester_id."', '".$responder_id."', '".$due_by."', 1)")){
        foreach($obj['attachments'] as $attachments){
          $dbh->query("INSERT INTO tbfreshdesk_attachments (fld_ticket_id, fld_attachment_name, fld_attachment_url, fld_attachment_size) VALUES (".$ticket_id.", '".$attachments['name']."', '".$attachments['attachment_url']."', '".$attachments['size']."')");
        }

        $url = "https://$yourdomain.freshdesk.com/api/v2/tickets/".$ticket_id."/conversations";

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
          $objconvo = json_decode($response, true);


          foreach ($objconvo as $keyconvo) {
            $convo_id = $keyconvo['id'];
            $created_at_convo = $keyconvo['created_at'];
            $updated_at_convo = $keyconvo['updated_at'];
            $from_email_convo = $keyconvo['from_email'];

            foreach ($keyconvo['cc_emails'] as $cc_email) {
              $cc_emails_convo = $cc_email."|";
            }

            $incoming_convo = $keyconvo['incoming'];
            
            foreach ($keyconvo['to_emails'] as $to_email) {
              $to_email_convo = $to_email."|";
            }

            $body = $keyconvo['body'];
            $body_text = $keyconvo['body_text'];

            

            $dbh->query("INSERT INTO tbfreshdesk_conversations (fld_ticket_id, fld_description, fld_description_text, fld_created_at, fld_updated_at, fld_provcode, fld_from_email, fld_to_email, fld_cc_emails, fld_incoming, fld_convo_id) VALUES (".$ticket_id.", '".addslashes(htmlspecialchars($body))."', '".addslashes(htmlspecialchars($body_text))."', '".$created_at_convo."', '".$updated_at_convo."', '".$provcode."', '".$from_email_convo."', '".$to_email_convo."', '".$cc_emails_convo."', '".$incoming_convo."', '".$convo_id."')");

            foreach($keyconvo['attachments'] as $cattachments){
              $dbh->query("INSERT INTO tbfreshdesk_attachments (fld_ticket_id, fld_attachment_name, fld_attachment_url, fld_convo_id, fld_attachment_size) VALUES (".$ticket_id.", '".$cattachments['name']."', '".addslashes($cattachments['attachment_url'])."', '".$convo_id."', '".$cattachments['size']."')");
            }

            $msg = "Successfully fetched ticket.";
            $msgclr = "success";
          }
          // echo "<br>";
          // print_r($obj);
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

      } else {
        echo "ERROR INSERTING DATA: ".$ticket_id."\n";
      }
    }

    echo "----------------------\n";

  }
  



  // $provcode = $_POST['assignse'];


  
  
  
} else {
  if($info['http_code'] == 404) {
    echo "Error, Please check the end point of Groups";
      
  } else {
    echo "Error, HTTP Status Code : " . $info['http_code'] . "\n";
    echo "Headers are ".$headers;
    echo $response;
  }
}

curl_close($ch);
}

}

$time_elapsed_secs = microtime(true) - $start;

echo $time_elapsed_secs."\n";

?>