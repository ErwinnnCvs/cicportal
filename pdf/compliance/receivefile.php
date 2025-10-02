<?php
if($_SERVER['REMOTE_ADDR'] == "10.250.106.28"){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	if(isset($_FILES["file"])) {
		$foldername = 'test';
		mkdir($foldername);
		$target_dir = $foldername."/";
		$target_file = $target_dir . basename($_FILES["file"]["name"]);

		if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
		echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
		} else {
		echo "Sorry, there was an error uploading your file.";
		}
	}
}
?>