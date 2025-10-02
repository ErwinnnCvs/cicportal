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
$date = date("Y-m-d");
$hour = date("H");

// $date = "2023-11-08";
// $hour = "11";
$pages = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");

echo "CWD: ".__DIR__;
if (file_exists(__DIR__."/files/".$date)) {
    foreach ($pages as $num) {
        $url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$num.'&query="tag:%27Batch%20Console%27%20AND%20created_at:%27'.$date.'%27"';
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
                preg_match('/[a-zA-Z]{2}\d{6}/', $item['subject'], $matches);
                // echo $matches[0];

                $txt .= $item['id'] . "|" .$matches[0]. "|" .$item['subject']. "|" .$item['created_at']." \r\n";

            }

            $myfile = fopen(__DIR__."/files/".$date."/".$num."_".$date."_".$hour.".txt", "w") or die("Unable to open file!");
            fwrite($myfile, $txt);
            fclose($myfile);

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
    }

} else {
	mkdir(__DIR__."/files/".$date);

	foreach ($pages as $num) {
		$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$num.'&query="tag:%27Batch%20Console%27%20AND%20created_at:%27'.$date.'%27"';
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
			$txt = "";
			foreach($obj['results'] as $item) {
				$c++;
				preg_match('/[a-zA-Z]{2}\d{6}/', $item['subject'], $matches);
				// echo $matches[0];

				$txt .= $item['id'] . "|" .$matches[0]. "|" .$item['subject']. "|" .$item['created_at']." \r\n";

		 	}

		 	// echo $txt;

		 	$myfile = fopen(__DIR__."/files/".$date."/".$num."_".$date."_".$hour.".txt", "w") or die("Unable to open file!");
			fwrite($myfile, $txt);
			fclose($myfile);

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
	}
}



?>