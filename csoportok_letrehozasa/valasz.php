<?php 
	if (!$_POST["csoport_nev"]){
		header ("Location: index.php");
		exit();
	}else {
		session_start();
		include('../includes/overall/db_connect.php');
		$stmt = $conn->prepare("INSERT INTO csoportok (tanar_id, csoport_nev, tabla_nev) VALUES (?, ?, ?)");
		$stmt->bind_param("sss",$_SESSION["felhasznalo"], $_POST["csoport_nev"], $_POST["csoport_nev"]);
		$stmt->execute();
		$stmt->close();
		
		$sql = "CREATE TABLE ".$_POST["csoport_nev"]." (id int PRIMARY KEY, FOREIGN KEY (id) REFERENCES felhasznalok(id))";
		$result = $conn->query($sql);
		foreach($_POST as $key=>$data){
			if ($key != "csoport_nev"){
				$sql = "SELECT id FROM felhasznalok WHERE nev = '".$_POST[$key]."'";
				$result = $conn->query($sql);
				$sor2 = $result->fetch_assoc();
				$stmt = $conn->prepare("INSERT INTO ".$_POST["csoport_nev"]." VALUES (?)");
				$stmt->bind_param("s",$sor2["id"]);
				$stmt->execute();
				$stmt->close();
			}
			echo "A csoport létrehozása megtörtént!";
		}
		
		include('../includes/overall/db_disconnect.php');
		
	}
?>