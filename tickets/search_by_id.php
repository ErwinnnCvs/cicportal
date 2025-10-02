
<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// require_once("../../var/www/html/mycic/config.php");
$ticket_date = 33003914491;



$tickets_arr = [];

// $se_provcode = $sess_provider_code;
$no_submission = 0;

$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";


// echo date("F d, Y", strtotime($ticket_date)). "<br>";

// $pages = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");

for ($i=1; $i < 11; $i++) {
// foreach ($pages as $num) {
    echo $i."<b>--------------START--------------</b><br>";
    // $url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$i.'&query="'.$created.'"';
    $url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$i.'&query="agent_id:33003914491"';

    echo $url."<br> ";
    
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

        foreach ($obj['results'] as $item) {
            // echo $item['to_emails'];
            echo "<b>TICKET PID</b>: ".$item['id']. "; <br> <b>CREATED_AT</b>: ".$item['created_at']." <b>SUBJECT</b>: " .$item['subject']. ";<br> <b>DESCRIPTION</b>: " .$item['description_text'].";<br> <b>TAGS</b>: ";
            foreach ($item['tags'] as $tag) {
                echo $tag."|";
            }

            echo "<br>";
            echo "<b>TO EMAILS</b>: ";
            foreach ($item['to_emails'] as $to_email) {
                echo $to_email."|";
            }

            echo "<br><br>";
        }

        

    }

    echo $i."<b>--------------END--------------</b><br>";
    curl_close($ch);

}


?>