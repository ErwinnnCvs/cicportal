<?php
// //ERROR REPORTING
ini_set('memory_limit','85M');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');

$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";

$ticket = $_GET['ticket'];

$pages = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");
$date = "2024-02-26";

//Fetch single ticket using ticket number
// $url = 'https://creditinfoph.freshdesk.com/api/v2/tickets/318014"';
// $ch = curl_init($url);
// curl_setopt($ch, CURLOPT_HEADER, true);
// curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $server_output = curl_exec($ch);
// $info = curl_getinfo($ch);
// $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
// $headers = substr($server_output, 0, $header_size)
// ;$response = substr($server_output, $header_size);
// if($info['http_code'] == 200) {

// 	$obj = json_decode($response, true);
// 	print_r($obj);

// }
// curl_close($ch);

foreach ($pages as $num) {
echo "PAGE: ".$num;
echo "<br>";
// Fetch tickets using search and query
$tags = '(tag:%27Batch%20Console%27%20OR%20tag:%27CIS%27)%20';

$tomorrow = date("Y-m-d", strtotime("+1 day"));

$created = '(created_at:%27'.$date.'%27%20OR%20updated_at:%27'.$date.'%27)';

$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$num.'&query="'.$tags.'AND%20'.$created.'"';
echo $url."<br>";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($server_output, 0, $header_size)
;$response = substr($server_output, $header_size);
if($info['http_code'] == 200) {

	$obj = json_decode($response, true);
	// print_r($obj);

	foreach($obj['results'] as $item) {
		$c++;
		preg_match('/[a-zA-Z]{2}\d{6}/', $item['subject'], $matches);
		$provider_code = $matches[0];
		// echo $matches[0];

		
		$created_at = $item['created_at'];
		$updated_at = $item['updated_at'];
		$subject = preg_match("/\[CIC PROD]/", $item['subject']);

		if($subject){
			echo $provider_code."<br>";
			echo $item['subject']."<br>";

			$subjfile = explode(":", $item['subject']);
            $file = str_split($subjfile[1], 36);

            $filename = $file[1].".TXT";
            echo $filename."<br>";
            echo $created_at."<br>";
            echo $updated_at."<br>";
            echo "------------------<br>";
		}
		
		// $txt .= $item['id'] . "|" .$matches[0]. "|" .$item['subject']. "|" .$item['created_at']." \r\n";

	}

}
curl_close($ch);
echo "<br>";
}

?>