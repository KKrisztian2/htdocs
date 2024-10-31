<?php 
	session_start();
	include('../includes/overall/db_connect.php');
	$znonev = $_GET["znonev"];
	if($znonev == "ZNO"){
		$znonev = "a";
	}else if($znonev == "Utóvizsga ZNO"){
		$znonev = "o";
	}else if($znonev == "Próba ZNO"){
		$znonev = "p";
	}
	$ev = $_GET["ev"];
	$tabla_nev = "zno_".$ev."_".$znonev;
	$sql = "SELECT temakor_tabla FROM temakorok";
	$result = $conn->query($sql);
	while($row=$result->fetch_assoc()){
		$sql="DELETE FROM ".$row["temakor_tabla"]." WHERE tabla_neve='".$tabla_nev."'";
		echo $sql;
		$result1 = $conn->query($sql);
	}
	$sql = "DROP TABLE ".$tabla_nev;
	$result = $conn->query($sql);
	$sql = "DELETE FROM feladatsorok WHERE nev = '".$tabla_nev. "'" ;
	$result = $conn->query($sql);
	include('../includes/overall/db_disconnect.php');
	header('Location: index.php');
	exit();

?>