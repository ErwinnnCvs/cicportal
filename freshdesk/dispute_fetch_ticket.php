<?php
// //ERROR REPORTING
ini_set('memory_limit','85M');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');

$api_key = "KQgMm1Qkj4bIGAgSnI9v";
$password = "password123123";
$yourdomain = "creditinfoph";

$ticket = $_GET['ticket'];

$pages = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");

$date = "2023-12-18";
echo "CWD: ".__DIR__."<br><br>";

    $url = 'https://creditinfohelp.freshdesk.com/api/v2/search/tickets?page=1&query="created_at:%27'.$date.'%27"';
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
        // echo "string";
        $txt = "";
        foreach($obj['results'] as $item) {
            $c++;
            $tags_arr = "";
            foreach($item['tags'] as $tags){
            	echo "for tags: ".$tags;
            	// $tags_arr .= $tags;
            }
            echo "TICKET NUMBER: ".$item['id']. " <br> SUBJECT: " . $item['subject'] . "; <br>CONTENT: " . $item['description'] ."<br><br>";
            // preg_match('/[a-zA-Z]{2}\d{6}/', $item['subject'], $matches);
            // echo $matches[0];

            // $txt .= $item['id'] . "|" .$matches[0]. "|" .$item['subject']. "|" .$item['created_at']." \r\n";

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



?>