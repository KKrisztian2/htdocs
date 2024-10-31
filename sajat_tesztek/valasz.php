<?php 
	if (!$_POST["teszt_nev"]){
		header ("Location: index.php");
		exit();
	}else {
		include('../includes/overall/db_connect.php');
		$sql = "SELECT id from feladatsorok_sajat WHERE nev = '".$_POST["teszt_nev"]."'";
		$result = $conn->query($sql);
		$sor = $result->fetch_assoc();
		$id = $sor["id"];
		foreach($_POST as $key=>$data){
			if ($key != "teszt_nev"){
				$sql = "SELECT id FROM csoportok WHERE csoport_nev = '".$_POST[$key]."'";
				$result = $conn->query($sql);
				$sor2 = $result->fetch_assoc();
				$stmt = $conn->prepare("INSERT INTO kiadott VALUES (?, ?)");; 
				$stmt->bind_param("ss",$id,$sor2["id"]);
				$stmt->execute();
				$stmt->close();
			}
		}
		echo "A feladatsor kiadása megtörtént!";
		include('../includes/overall/db_disconnect.php');
		
	}
?>