<?php 
	session_start();
	include('../includes/overall/db_connect.php');
	$feladatsor = $_GET["feladatsor"];
	echo $feladatsor;
	$sql = "UPDATE feladatsorok SET allapot = 'feltöltve' WHERE tabla_neve = '".$feladatsor."'";
	$result = $conn->query($sql);
	include('../includes/overall/db_disconnect.php');
	header('Location: index.php');
	exit();
?>