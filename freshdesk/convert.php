<?php

require_once("../config.php");

$file = "arrival2.txt";

$fh = fopen($file,'r');


while ($line = fgets($fh)) {
	$explodeWords = explode("|", $line);

	$subject = preg_match("/\[CIC PROD]/", $explodeWords[1]);
	if($subject){
		preg_match('/[a-zA-Z]{2}\d{6}/', $explodeWords[1], $matches);
		// echo $explodeWords[3]. " - " .date("Y-m-d H:i:s", strtotime($explodeWords[3]))."<br>";
		// echo $explodeWords[0].", ".$explodeWords[1].", ".$explodeWords[2].", ".$explodeWords[3]."<br>";
		// if ($explodeWords[0] > $check_ticket['fld_id']) {
		$check_id = $dbh->query("SELECT * FROM tbprodtickets WHERE fld_id = ".$explodeWords[0]);
		$ci =$check_id->fetch_array();

		if($ci['fld_id']){
			echo $ci['fld_id']."<br>";
		} else {
			if ($dbh->query("INSERT INTO tbprodtickets (fld_id, fld_provcode, fld_subject, fld_created_time) VALUES ('".$explodeWords[0]."', '".$matches[0]."' ,'".$explodeWords[1]."', '".date("Y-m-d H:i:s", strtotime($explodeWords[3]))."')")) {
				echo "SUCCESS<br/>";
			} else {
				echo "FAILED<br/>";
			}
		}

		// }
	} else {
		echo "NO MATCH";
	}
}
fclose($fh);

?>