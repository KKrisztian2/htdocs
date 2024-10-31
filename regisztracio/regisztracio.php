<?php
	session_start();
	if(!isset($_SESSION["elkuldve"])){
		header("Location: send_email.php");
		exit();
	}
?>