<?php
	require_once 'classes/Auth.class.php';
	
	session_start();
	
	$auth = new Auth();
	$auth->logout();
	
	if (!$auth->checkSession()) {
		header("Location: login.php");
	}
?>
