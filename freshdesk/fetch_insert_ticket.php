<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../var/www/html/mycic/config.php");
$ticket_date = date("Y-m-d");



$tickets_arr = [];

// $se_provcode = $sess_provider_code;
$no_submission = 0;

$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";


echo $ticket_date. "\n";

$pages = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");

foreach ($pages as $num) {
    echo $num."\n";

    // Fetch tickets using search and query
    $tags = '(tag:%27Batch%20Console%27%20OR%20tag:%27CIS%27)%20';

    $created = '(created_at:%27'.$ticket_date.'%27)';

    $url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$num.'&query="'.$tags.'AND%20'.$created.'"';
 
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

        foreach($obj['results'] as $item) {
            preg_match('/[a-zA-Z]{2}\d{6}/', $item['subject'], $matches);
            
            $subject = preg_match("/\[CIC PROD]/", $item['subject']);

            if($subject){

                // if($se_provcode == $provider_code){

                    $created_at = $item['created_at'];
                    $updated_at = $item['updated_at'];
                    // $subjfile = explode(":", $item['subject']);
                    // $file = str_split($subjfile[1], 36);

                    $file_name = $item['subject'];

                    // echo $item['id']. " - - ";

                    $subjfile = explode(":", $file_name);

                    // IF CC REQUIRES TO REMOVE File Discarded
                    // if($subjfile[0] != "[CIC PROD] File Discarded"){

                    // }
                    $file = str_split($subjfile[1], 36);

                    $filename = $file[1].".TXT";

                    $check_if_filed = $dbh->query("SELECT * FROM tbprodtickets WHERE fld_subject LIKE '%".$item['subject']."%' AND fld_id = '".$item['id']."'");
                    $cii=$check_if_filed->fetch_array();

                    if($cii['fld_id']){

                        echo $filename." IN TBPRODTICKETS - CIC DATABASE;\n";


                    } else {

                        preg_match('/[a-zA-Z]{2}\d{6}/', $item['subject'], $matches);

                        $date_created1 = str_replace("T", "-",$created_at);
                        $date_created2 = str_replace("Z", ":00",$created_at);

                        $provcode = $matches[0];

                        echo $item['id']. " - - ". $matches[0]." - " .$file_name. "  " . $date_created2 . " NOT IN DATABASE; <br>";

                        
                        // echo "INSERT INTO tbprodtickets (fld_id, fld_provcode, fld_subject, fld_created_time, fld_transmittal_status) VALUES ('".$item['id']."', '".$provcode."', '".$file_name."', '".$date_created2."', 0)";
                        $dbh->query("INSERT INTO tbprodtickets (fld_id, fld_provcode, fld_subject, fld_created_time, fld_transmittal_status) VALUES ('".$item['id']."', '".$provcode."', '".$file_name."', '".$date_created2."', 0)");

                        // $data = $created_at.",".$filename;
                        // $val = explode(",", $data);
                        // fputcsv($fp, $val);
                        // $tickets_arr[$created_at] = $filename;
                    }
                    
                                                               
                // }

            }

        }

        

    }
    curl_close($ch);

}

// fclose($fp);
// echo $tickets_arr;
// file_put_contents(__DIR__."/tickets_arrival/".$se_provcode."_".$selectedFTPDate.".txt", print_r($tickets_arr, true));



?>