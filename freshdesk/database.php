<?php 
date_default_timezone_set('Asia/Manila');
include("config.php");
// $dbh = new mysqli("10.250.106.33", "myuser1", "mypassword", "cicportal");

// if($dbh){
// 	echo 'asdasd';
// }
// $date = date("Y-m-d");
// $hour = date("H");

$date = "2022-03-21";
$hour = 22;
$pages = array("10", "9", "8", "7", "6", "5", "4", "3", "2", "1");

$check_ticket = $dbh->query("SELECT fld_id FROM tbprodtickets ORDER BY fld_id DESC LIMIT 1");


if($ct = $check_ticket->fetch_array()){
	// echo "test";
	echo "Current Directory: ".getcwd()."<br>";
	foreach ($pages as $num) {

	$file = "files/".$date."/".$num."_".$date."_".$hour.".txt";
	// echo $file;
		if (file_exists($file)) {


			$fh = fopen($file,'r');

			while ($line = fgets($fh)) {
				$explodeWords = explode("|", $line);
				
				$subject = preg_match("/\[CIC PROD]/", $explodeWords[2]);
				if($subject){
					echo $explodeWords[0].", ".$explodeWords[1].", ".$explodeWords[2].", ".$explodeWords[3]."<br>";
					if ($explodeWords[0] > $check_ticket['fld_id']) {
						if ($dbh->query("INSERT INTO tbprodtickets (fld_id, fld_provcode, fld_subject, fld_created_time) VALUES ('".$explodeWords[0]."', '".$explodeWords[1]."', '".$explodeWords[2]."', '".$explodeWords[3]."')")) {
							echo "SUCCESS<br/>";
						} else {
							echo "FAILED<br/>";
						}
	
					}
				}
			}
			fclose($fh);
		} else {
			echo "NO FILE<br/>";
			$nofile = fopen("files/".$date."/"."NO_FILE_".$num."_".$date."_".$hour.".txt", "w") or die("Unable to open file!");
			$txt = "";
			fwrite($nofile, $txt);
			fclose($nofile);
		}
	}
}
// $ct = $check_ticket->fetch_array();


?>