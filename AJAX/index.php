<?php
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["action"] == "szintek"){
		$servername = "localhost";
		$username = "root";
		$password = "";
		$db_name = "zno";
		$conn = new mysqli($servername, $username, $password, $db_name);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		
		$szintek = [false,false,false,false];
		$db = 0;
		$sql = "SELECT * FROM temakorok"; 
		$result = $conn->query($sql);
		while($sor = $result->fetch_assoc()){
			$kulcs = "temakor_".$sor["id"];
			if(array_key_exists($kulcs,$_POST)){
				for($i = 1; $i <= 4; $i++){
					$sql = "SELECT * FROM ".$sor["temakor_tabla"]." WHERE feladat_szint = ".$i;
					$sor2 = $conn->query($sql);
					if(!$szintek[$i-1] && $sor2->num_rows > 0){
						$szintek[$i-1] = true;
						$db++;
					}
				}
			}
			if($db == 4){
				break;
			}
		}
		$conn->close();
		$json = "";
		for($i = 0; $i < 4; $i++){
			if($szintek[$i]){
				$json .= ($i+1).",";
			}
		}
		$json = rtrim($json,",");
		echo "[".$json."]";
	}
	else
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["action"] == "feladatokSzama"){
		$servername = "localhost";
		$username = "root";
		$password = "";
		$db_name = "zno";
		$conn = new mysqli($servername, $username, $password, $db_name);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		
		
		$szintKriterium = "";
		for($i = 1; $i < 5; $i++){  //$i < 4 - 4 szint van
			if(isset($_POST["szint_".$i])){
				$szintKriterium .= "feladat_szint = ".$i." OR "; //feladat_szint - egy adott témakör táblában egy mező
			}
		}
		$szintKriterium = rtrim($szintKriterium," OR ");
		
		$db = 0;
		$sql = "SELECT * FROM temakorok"; 
		$result = $conn->query($sql);
		while($sor = $result->fetch_assoc()){
			$kulcs = "temakor_".$sor["id"];
			if(array_key_exists($kulcs,$_POST)){
				$sql = "SELECT COUNT(*) FROM ".$sor["temakor_tabla"]." WHERE ".$szintKriterium;
				$result2 = $conn->query($sql);
				$db += $result2->fetch_array(MYSQLI_NUM)[0];
			}
		}
		$conn->close();
		echo $db;
		//echo $szintKriterium;
	}
?>