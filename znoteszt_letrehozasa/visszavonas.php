<?php 
	session_start();
	include('../includes/overall/db_connect.php');
	$feladatsor = $_GET["feladatsor"];
	$sql = "UPDATE feladatsorok SET allapot = 'szerkesztés alatt' WHERE tabla_neve = '".$feladatsor."'";
	$result = $conn->query($sql);
	include('../includes/overall/db_disconnect.php');
	header('Location: index.php');
	exit();
?>