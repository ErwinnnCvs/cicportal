<?php
// //ERROR REPORTING
ini_set('memory_limit','85M');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');

//$dbh4 = new mysqli("localhost", "root", "", "dev_80_cicseis");
include('../../config.php');

$api_key = "xVfqn6csPjVKD0QQkR";
$password = "password123123";
$yourdomain = "creditinfoph";

$hour = 10;
$year = "2024";
$month = "02";
$day = "06";
$date = $year."-".$month."-".$day;
// $date = "2023-11-10";
// $hour = $_GET['hour'];
$pages = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10");

$getTickets = $dbh4->query("SELECT * from tbict_freshdesk WHERE fld_ctrlno IS NOT NULL");
while ($gt = $getTickets->fetch_array()) {
    //echo "Ticket ID: ".$gt['fld_ticket_id']."<br>";



//$url = 'https://creditinfoph.freshdesk.com/api/v2/tickets/392879"';
$url = "https://creditinfoph.freshdesk.com/api/v2/tickets/".$gt['fld_ticket_id']."/conversations";
//$url = "https://creditinfoph.freshdesk.com/api/v2/tickets/440146/conversations";

//foreach($pages as $page) {
	//$url = 'https://creditinfoph.freshdesk.com/api/v2/search/tickets?page='.$page.'&query="(created_at:%272024-08-28%27%20AND%20updated_at:%272024-08-28%27)"';

	//echo $url;
	//echo "<br>";

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
        $createdDateFromFreshDesk = str_replace("T", " ",$key['created_at']);
        $createdDateUTC = str_replace("Z", "",$createdDateFromFreshDesk);
        $createdDateConvert = strtotime($createdDateUTC. ' UTC');
        $createdDate = date("Y-m-d H:i:s", $createdDateConvert);
    
        $updatedDateFromFreshDesk = str_replace("T", " ",$key['updated_at']);
        $updatedDateUTC = str_replace("Z", "",$updatedDateFromFreshDesk);
        $updatedDateConvert = strtotime($updatedDateUTC. ' UTC');
        $updatedDate = date("Y-m-d H:i:s", $updatedDateConvert);

        $lastEditedDateFromFreshDesk = str_replace("T", " ",$key['last_edited_at']);
        $lastEditedDateUTC = str_replace("Z", "",$lastEditedDateFromFreshDesk);
        $lastEditedDateConvert = strtotime($lastEditedDateUTC. ' UTC');
        $lastEditedDate = date("Y-m-d H:i:s", $lastEditedDateConvert);
    
        $description = addslashes($key['body_text']);
        $body = addslashes($key['body']);
        //$attach = addslashes($key['attachments']);
        $to_emails = implode(" | ",$key['to_emails']);
        $sent_to = $dbh4->real_escape_string($to_emails);

        if ($key['cc_emails'] == NULL) {
            $cc_emails = "";
        }
        else {
            $cc_emails = implode(" | ",$key['cc_emails']);
        }

        if ($key['bcc_emails'] == NULL) {
            $bcc_emails = "";
        }
        else {
            $bcc_emails = implode(" | ",$key['bcc_emails']);
        }

        // if ($key['attachments'] == NULL) {
        //     $attachments = "";
        // }
        //else {
            //$attachments = implode(" | ",$key['attachments']);
            // $result = [];
            // array_walk_recursive($key['attachments'], function($i) use (&$result) {
            //     $result[] = $i;
            // });
            // $attachments = implode(" | ",$result);

            foreach ($key['attachments'] as $file) {
                $fileDateCreatedFromFreshdesk = str_replace("T", " ", $file['created_at']);
                $fileDateCreatedUTC = str_replace("Z", "", $fileDateCreatedFromFreshdesk);
                $fileDateCreatedConvert = strtotime($fileDateCreatedUTC. ' UTC');
                $fileDateCreated = date("Y-m-d H:i:s", $fileDateCreatedConvert);
                
                $fileDateUpdatedFromFreshdesk = str_replace("T", " ", $file['updated_at']);
                $fileDateUpdatedUTC = str_replace("Z", "", $fileDateUpdatedFromFreshdesk);
                $fileDateUpdatedConvert = strtotime($fileDateUpdatedUTC. ' UTC');
                $fileDateUpdated = date("Y-m-d H:i:s", $fileDateUpdatedConvert);

                $file_name = $dbh4->real_escape_string($file['name']);
                
                
                // echo "file ID: " .$file['id'];
                // echo "<br>";
                // echo "File Name: " .$file['name'];
                // echo "<br>";


                $checkFileID = $dbh4->query("SELECT * FROM tbict_attachments WHERE fld_file_id = '".$file['id']."'");
                // echo "SELECT * FROM tbict_attachments WHERE fld_file_id = '".$file['id']."'";
                // echo "<br>";
                // echo "<br>";

                if (!$chkFile = $checkFileID->fetch_array()) {
                    $insertAttachment = $dbh4->query("INSERT INTO tbict_attachments (fld_ticket_id, fld_convo_id, fld_file_id, fld_name, fld_content_type, fld_size, fld_created_at, fld_updated_at, fld_attachment_url) VALUES ('".$key['ticket_id']."', '".$key['id']."', '".$file['id']."', '".$file_name."', '".$file['content_type']."', '".$file['size']."', '".$fileDateCreated."', '".$fileDateUpdated."', '".$file['attachment_url']."')");
                    // echo "INSERT INTO tbict_attachments (fld_ticket_id, fld_convo_id, fld_file_id, fld_name, fld_content_type, fld_size, fld_created_at, fld_updated_at, fld_attachment_url) VALUES ('".$key['ticket_id']."', '".$key['id']."', '".$file['id']."', '".$file['name']."', '".$file['content_type']."', '".$file['size']."', '".$fileDateCreated."', '".$fileDateUpdated."', '".$file['attachment_url']."')";
                    // echo "SAVED FILE";
                    // echo "<br>";
                }
                else {
                    // echo "FILE ALREADY SAVED";
                    // echo "<br>";
                }
            }
        //}

        $convoID = $key['id'];

        //echo "fetched";

    
        // echo "Convo ID: " . $convoID;
        // echo "<br>";
        // echo "To Email: " .$to_emails;
        // echo "<br>";
        // echo $attachments;
        // echo "<br>";
        // echo "Attachment: " .$key['attachments'];
        // echo "<br>";
        // echo "Ticket ID: " .$key['ticket_id'];
        // echo "<br>";
        // echo "<br>";
        // echo "<br>";
        //echo "<br>";
        //echo $attach;

        //echo "INSERT INTO tbict_conversations (fld_ticket_id, fld_description, fld_body, fld_freshdesk_id, fld_incoming, fld_private, fld_userid, fld_support_email, fld_source, fld_category, fld_to_emails, fld_from_email, fld_cc_emails, fld_bcc_emails, fld_email_failure_count, fld_outgoing_failures, fld_thread_id, fld_thread_message_id, fld_created_at, fld_updated_at, fld_last_edited_at, fld_last_edited_user_id, fld_attachments, fld_automation_id, fld_automation_type_id, fld_auto_response, fld_additional_info) VALUES ('".$key['ticket_id']."', '".$description."', '".$body."', '".$key['id']."', '".$key['incoming']."', '".$key['private']."', '".$key['user_id']."', '".$key['support_email']."', '".$key['source']."', '".$key['category']."', '".$key['to_emails']."', '".$key['from_email']."', '".$cc_emails."', '".$bcc_emails."', '".$key['email_failure_count']."', '".$key['outgoing_failures']."', '".$key['thread_id']."', '".$key['thread_message_id']."', '".$createdDate."', '".$updatedDate."', '".$lastEditedDate."', '".$key['last_edited_user_id']."', '".$attachments."', '".$key['automation_id']."', '".$key['automation_type_id']."', '".$key['auto_response']."', '".$key['additional_info']."')";
        //echo "SELECT * FROM tbict_conversations WHERE fld_ticket_id = '".$convoID."'";
        //echo "INSERT INTO tbict_conversations (fld_ticket_id, fld_description, fld_body, fld_freshdesk_id, fld_incoming, fld_private, fld_userid, fld_support_email, fld_source, fld_category, fld_to_emails, fld_from_email, fld_cc_emails, fld_bcc_emails, fld_email_failure_count, fld_outgoing_failures, fld_thread_id, fld_thread_message_id, fld_created_at, fld_updated_at, fld_last_edited_at, fld_last_edited_user_id, fld_attachments, fld_automation_id, fld_automation_type_id, fld_auto_response, fld_additional_info) VALUES ('".$key['ticket_id']."', '".$description."', '".$body."', '".$key['id']."', '".$key['incoming']."', '".$key['private']."', '".$key['user_id']."', '".$key['support_email']."', '".$key['source']."', '".$key['category']."', '".$to_emails."', '".$key['from_email']."', '".$cc_emails."', '".$bcc_emails."', '".$key['email_failure_count']."', '".$key['outgoing_failures']."', '".$key['thread_id']."', '".$key['thread_message_id']."', '".$createdDate."', '".$updatedDate."', '".$lastEditedDate."', '".$key['last_edited_user_id']."', '".$attachments."', '".$key['automation_id']."', '".$key['automation_type_id']."', '".$key['auto_response']."', '".$key['additional_info']."')";

        $checkConvoID = $dbh4->query("SELECT * FROM tbict_conversations WHERE fld_freshdesk_id = '".$convoID."'");

        if (!$chkID = $checkConvoID->fetch_array()) {
            $insertConversation = $dbh4->query("INSERT INTO tbict_conversations (fld_ticket_id, fld_description, fld_body, fld_freshdesk_id, fld_incoming, fld_private, fld_userid, fld_support_email, fld_source, fld_category, fld_to_emails, fld_from_email, fld_cc_emails, fld_bcc_emails, fld_email_failure_count, fld_outgoing_failures, fld_thread_id, fld_thread_message_id, fld_created_at, fld_updated_at, fld_last_edited_at, fld_last_edited_user_id, fld_attachments, fld_automation_id, fld_automation_type_id, fld_auto_response, fld_additional_info) VALUES ('".$key['ticket_id']."', '".$description."', '".$body."', '".$key['id']."', '".$key['incoming']."', '".$key['private']."', '".$key['user_id']."', '".$key['support_email']."', '".$key['source']."', '".$key['category']."', '".$sent_to."', '".$key['from_email']."', '".$cc_emails."', '".$bcc_emails."', '".$key['email_failure_count']."', '".$key['outgoing_failures']."', '".$key['thread_id']."', '".$key['thread_message_id']."', '".$createdDate."', '".$updatedDate."', '".$lastEditedDate."', '".$key['last_edited_user_id']."', '".$attachments."', '".$key['automation_id']."', '".$key['automation_type_id']."', '".$key['auto_response']."', '".$key['additional_info']."')");
            // echo "SAVED CONVERSATION FOR TICKET #". $key['ticket_id'];
            // echo "<br>";
        }
        else {
            // echo "EXISTING ALREADY";
            // echo "<br>";
        }

        // echo "---------------------------------------------------";
        // echo "<br>";


		//print_r($obj);

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

}

?>