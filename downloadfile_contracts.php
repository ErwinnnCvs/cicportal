<?php 
date_default_timezone_set("Asia/Manila");
$timestamp = date("Y-m-d_H-i-s");
// if(isset($_REQUEST))
// {
// echo $files;
$file = basename($_GET['file']);
$file = 'files/csv/contracts/'.$file;

if(!file_exists($file)){ // file does not exist
    die('file not found');
} else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=Extract_Contracts_".$timestamp.".csv");
    header("Content-Type: application/csv");
    header("Content-Transfer-Encoding: binary");

    // read the file from disk
    readfile($file);
}

// }
?>
