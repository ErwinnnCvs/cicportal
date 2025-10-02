<?php
include 'classes/Auth.class.php';

session_start();

$auth = new Auth();

if (!isset($_SESSION['user_id'])) {
	//Not logged in, send to login page.
	header("Location: login.php");
} else {
	//Check we have the right user
	$logged_in = $auth->checkSession();

	if(empty($logged_in)){
		//Bad session, ask to login
		$auth->logout();
		header("Location: login.php");

	} else {
		//User is logged in, show the page
		if ($_SESSION['success_otp'] == 1) {
			header('Location: otp.php');
		} else {
			switch ($_SESSION['usertype']) {
				case 99:
					header("Location: main.php?nid=3&sid=0&rid=0");
					break;
				default:
					header("Location: main.php?nid=1&sid=0&rid=0");
					break;
			}
	    }
	}
}

?>
