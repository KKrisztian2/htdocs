<?php
	session_start();
	include('../includes/overall/db_connect.php');

	$id = $_GET["id"];
	$feladatsor = $_GET["feladatsor"];
	$sql = "SELECT * FROM ".$feladatsor." WHERE id = '".$id."'";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
		$row = $result->fetch_assoc();
		$_SESSION["edit_task"] = $row;
		$_SESSION["edit_task_id"] = $id;
	}
	include('../includes/overall/db_disconnect.php');
	header("Location: index.php?feladatsor=".$feladatsor);
	exit();
?>