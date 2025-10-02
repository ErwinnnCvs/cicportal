<?php 
date_default_timezone_set('Asia/Manila');
// echo getcwd();exit();
require_once("../../var/www/html/mycic/config.php");

// $dbh = new mysqli("10.250.106.33", "myuser1", "mypassword", "cicportal");

// if($dbh){
// 	echo 'asdasd';
// }

$date = date("Y-m-d");
$hour = date("H");
// $date = "2023-11-08";
// $hour = "11";
$pages = array("10", "9", "8", "7", "6", "5", "4", "3", "2", "1");

$check_ticket = $dbh->query("SELECT fld_id FROM tbprodtickets ORDER BY fld_id DESC LIMIT 1");


if($ct = $check_ticket->fetch_array()){
	// echo "test";
	echo "Current Directory: ".getcwd()."<br>";
	foreach ($pages as $num) {

	$file = __DIR__."/files/".$date."/".$num."_".$date."_".$hour.".txt";
	// echo $file;
		if (file_exists($file)) {
			$fh = fopen($file,'r');

			while ($line = fgets($fh)) {
				$explodeWords = explode("|", $line);
				
				$subject = preg_match("/\[CIC PROD]/", $explodeWords[2]);
				if($subject){
					// echo $explodeWords[0].", ".$explodeWords[1].", ".$explodeWords[2].", ".$explodeWords[3]."<br>";
                    // echo $explodeWords[0]." != ".$ct['fld_id'];
					echo $file."<br>";
					if ($explodeWords[0] == $ct['fld_id']) {
                        // echo "INSERT INTO tbprodtickets (fld_id, fld_provcode, fld_subject, fld_created_time) VALUES ('".$explodeWords[0]."', '".$explodeWords[1]."', '".$explodeWords[2]."', '".$explodeWords[3]."')";
						continue;
	
					} else {
						// '2023-10-16T02:07:54Z '
						$date_created1 = str_replace("T", "-",$explodeWords[3]);
						$date_created2 = str_replace("Z", ":00",$explodeWords[3]);
                        if ($dbh->query("INSERT INTO tbprodtickets (fld_id, fld_provcode, fld_subject, fld_created_time, fld_transmittal_status) VALUES ('".$explodeWords[0]."', '".$explodeWords[1]."', '".$explodeWords[2]."', '".$date_created2."', 0)")) {
							echo "SUCCESS<br/>";
						} else {
							echo "FAILED: ".$explodeWords[0]."<br/>";
							if($dbh->query("DELETE FROM tbprodtickets WHERE fld_id = ".$explodeWords[0])){
								if($dbh->query("INSERT INTO tbprodtickets (fld_id, fld_provcode, fld_subject, fld_created_time, fld_transmittal_status) VALUES ('".$explodeWords[0]."', '".$explodeWords[1]."', '".$explodeWords[2]."', '".$date_created2."', 0)")){
									echo "UPDATED:".$explodeWords[0]."<br>";
								}	
							}
							
						}
                    }
				}
			}
			fclose($fh);
		} else {
			echo "NO FILE<br/>";
			$nofile = fopen(__DIR__."/files/".$date."/"."NO_FILE_".$num."_".$date."_".$hour.".txt", "w") or die("Unable to open file!");
			$txt = "";
			fwrite($nofile, $txt);
			fclose($nofile);
		}
	}
}
// $ct = $check_ticket->fetch_array();


?>