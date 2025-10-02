<?php 
date_default_timezone_set('Asia/Manila');
require_once("config.php");
// $dbh = new mysqli("10.250.106.33", "myuser1", "mypassword", "cicportal");

$check_ticket = $dbh->query("SELECT fld_id FROM tbprodtickets ORDER BY fld_id DESC LIMIT 1");


if($ct = $check_ticket->fetch_array()){

    if (file_exists($filename)) {

        // echo "asdadasdas";
        $fh = fopen($filename,'r');

        while ($line = fgets($fh)) {
            $explodeWords = explode("|", $line);

            $subject = preg_match("/\[CIC PROD]/", $explodeWords[2]);
            if($subject){
                $check_ticket_exist = $dbh->query("SELECT * FROM tbprodtickets WHERE fld_id = ".$explodeWords[0]."");
                $cte=$check_ticket_exist->fetch_array();
                
                if($cte['fld_id']){
                    echo "Ticket: ".$explodeWords[0]." already exists<br>";
                } else {
                    $dbh->query("INSERT INTO tbprodtickets (fld_id, fld_provcode, fld_subject, fld_created_time) VALUES ('".$explodeWords[0]."', '".$explodeWords[1]."', '".$explodeWords[2]."', '".$explodeWords[3]."')");

                    echo "SUCCESSFULLY SAVED!!!!!!!!!!!";
                }
            }
        }
        // fclose($fh);
    } else {
        echo "NO FILE<br/>";
        // $nofile = fopen($filename, "w") or die("Unable to open file!");
        // $txt = "";
        // fwrite($nofile, $txt);
        // fclose($nofile);
    }
}


?>