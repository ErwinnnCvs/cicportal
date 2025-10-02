<?php 
date_default_timezone_set("Asia/Manila");
$timestamp = date("Y-m-d_H:i:s");
$file = basename($_GET['file']);
$file = 'nocusers/seis/'.$file;

if(!file_exists($file)){ // file does not exist
    die('file not found');
} else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=Users_'".$timestamp."'.csv");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");

    // read the file from disk
    readfile($file);
}

?>