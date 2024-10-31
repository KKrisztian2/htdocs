<?php
	session_start();
	$_SESSION["error"] = false;
	include('../includes/overall/db_connect.php');
	if(isset($_POST["submit"])){
		
		if(!empty($_POST["szoveg"])){
			$adatok_megfeleloek = adatokMegtisztitasaEllenorzese();
			if($adatok_megfeleloek){
				$helyes_valasz = "";
				$valaszok_szama = 0;
				valaszKodolasa($helyes_valasz, $valaszok_szama);
				if(isset($_SESSION["edit_task_id"])){              //feladat módosítása/szerkesztése
					$sql = "UPDATE ".$_GET["feladatsor"]." SET 
							feladat = ?, feladat_tipusa = ?, valaszok_szama = ?,
							megoldas = ?, szint = ?, temakor = ?
							WHERE id = ".$_SESSION["edit_task_id"];
					$stmt = $conn->prepare($sql);
					$stmt->bind_param("ssssss", $_POST["szoveg"], $_POST["tipus"],$valaszok_szama,$helyes_valasz,$_POST["szint"],$_POST["temakor"]);
					$stmt->execute();
					$stmt->close();
					unset($_SESSION["edit_task_id"]);
				}else{
					//adatok mentése
					$sql = "INSERT INTO ".$_GET["feladatsor"]." (sorszam, feladat, feladat_tipusa, valaszok_szama, megoldas, szint, temakor) VALUES (?, ?, ?, ?, ?, ?, ?)";
					$stmt = $conn->prepare($sql);
					$stmt->bind_param("sssssss", $_SESSION["sorszam"], $_POST["szoveg"], $_POST["tipus"],$valaszok_szama,$helyes_valasz,$_POST["szint"],$_POST["temakor"]);
					if($stmt->execute()){
						//az új feladat id értékének lekérése
						$sql = "SELECT id FROM ".$_GET["feladatsor"]." WHERE sorszam = ".$_SESSION["sorszam"];
						$result = $conn->query($sql);
						$id = $result->fetch_all(MYSQLI_NUM);
						$id = $id[0][0];
						//témakör tábla lekérése
						$sql = "SELECT temakor_tabla FROM temakorok WHERE id = ".$_POST["temakor"];
						$temakor_tabla = $conn->query($sql);
						$temakor_tabla = $temakor_tabla->fetch_all(MYSQLI_NUM);
						$temakor_tabla = $temakor_tabla[0][0];
						
						//megkísérlem beszúrni az új feladat szükséges adatait a témakör táblába
						$sql = "INSERT INTO ".$temakor_tabla." (tabla_neve, feladat_id, feladat_szint) VALUES ('".$_GET["feladatsor"]."', ".$id.", ".$_POST["szint"].");";
						$result = $conn->query($sql);
						if($conn->error && $conn->errno == 1146){ //ha hibát kaptam és a hiba: Table doesn't exist
							$sql = "CREATE TABLE ".$temakor_tabla." (
									tabla_neve varchar(50) NOT NULL,
									feladat_id int(11) NOT NULL,
									feladat_szint int(11) NOT NULL,
									PRIMARY KEY (tabla_neve, feladat_id),
									FOREIGN KEY (tabla_neve) REFERENCES feladatsorok(tabla_neve))";
							$result = $conn->query($sql);
							
							if($result != false){//ha létrejött a tábla
								//beszúrni az új feladat szükséges adatait a most létrejött témakör táblába
								$sql = "INSERT INTO ".$temakor_tabla." (tabla_neve, feladat_id, feladat_szint) VALUES ('".$_GET["feladatsor"]."', ".$id.", ".$_POST["szint"].");";
								$result = $conn->query($sql);
							}else{
								$_SESSION["error"] = true;
								$_SESSION["error_message"] = "Témakörtábla létrehozása sikertelen!<br>".$conn->error;
							}
						}
					}else{
						$_SESSION["error"] = true;
						$_SESSION["error_message"] = "Hiba a feladat mentésekor!<br>".$conn->error;
					}
					$stmt->close();
				}
				if(!$_SESSION["error"]){
					$_SESSION["mesage"] = "Mentés sikeres!";
					unset($_SESSION["sorszam"]);
				}
			}else{
				$_SESSION["error"] = true;
				$_SESSION["error_message"] = "Hiba a tartalom mentésekor!<br>".$conn->error;
			}
		}else{
			$_SESSION["error"] = true;
			$_SESSION["error_message"] = "Nem hozott létre feladatot!";
		}
		header("Location: index.php?feladatsor=".$_GET["feladatsor"]);
		exit();
	}
	
	function adatokMegtisztitasaEllenorzese(){
		function megtisztit($adat){
			$adat=trim($adat);
			$adat=stripslashes($adat);
			$adat=htmlspecialchars($adat);
			return $adat;
		}
		$sql="SELECT id from feladat_tipus";
		$result = $conn->query($sql);
		$tipus_ok=false;
		while($row=$result->fetch_assoc()){
			if($row["id"]==$_POST["tipus"])
				$tipus_ok=true;
			
		}
		if(!$tipus_ok)
			return false;
		
		$sql="SELECT id from temakorok";
		$result = $conn->query($sql);
		$temakor_ok=false;
		while($row=$result->fetch_assoc()){
			if($row["id"]==$_POST["temakor"])
				$temakor_ok=true;
		}
		if(!$temakor_ok)
			return false;
		
		$szint_ok=false;
		for($i=1;$i<=4;$i++){
			if($i==$_POST["szint"])
				$szint_ok=true;
		}
		if(!$szint_ok)
			return false;
		
		$sql="SELECT * FROM ".$_GET["feladatsor"];
		$result = $conn->query($sql);
		if($conn->errno==1146)
			return false;
		
		if($_POST["tipus"] == "1"){
			$teszt_ok=false;
			$valaszok = ["A","B","C","D","E"];
			for($i = 0; $i < 5; $i++){
				if($_POST["helyes_teszt"]==$valaszok[$i])
					$teszt_ok=true;
			}
			if(!$teszt_ok)
				return false;
		}
		else if($_POST["tipus"] == "2"){
			$nevek = ["elso","masodik","harmadik","negyedik"];
			$valaszok = ["A","B","C","D","E"];
			$parositos_ok=[false,false,false,false];
			for($i = 0; $i < 4; $i++){
				for($j=0;$j<5;$j++){
					if($_POST[$nevek[$i]]==$valaszok[$j]){
						$parositos_ok[$i]=true;
						break;
					}
				}
				if(!$parositos_ok[$i])
					return false;
			}
		}
		else if($_POST["tipus"] == "3"){
			$_POST["helyes1"]=megtisztit($_POST["helyes1"]);
			if($_POST["db"] > 1){
				$_POST["helyes2"]=megtisztit($_POST["helyes2"]);
			}
			if($_POST["db"] > 2){
				$_POST["helyes3"]=megtisztit($_POST["helyes3"]);
			}
		}
		return true;
	}
	
	function valaszKodolasa(&$helyes_valasz, &$valaszok_szama){
		if($_POST["tipus"] == "1"){ //helyes válasz(ok) kódolása, válaszok számának meghatározása/beállítása
			$helyes_valasz = $_POST["helyes_teszt"];
			$valaszok_szama = 1;
		}else if($_POST["tipus"] == "2"){
			$helyes_valasz = $_POST["elso"]."\t".$_POST["masodik"]."\t".$_POST["harmadik"]."\t".$_POST["negyedik"];
			$valaszok_szama = 4;
		}else if($_POST["tipus"] == "3"){
			$helyes_valasz = $_POST["helyes1"];
			$valaszok_szama++;
			if($_POST["db"] > 1){
				$helyes_valasz .="\t".$_POST["helyes2"];
				$valaszok_szama++;
			}
			if($_POST["db"] > 2){
				$helyes_valasz .="\t".$_POST["helyes3"];
				$valaszok_szama++;
			}
		}
	}
	include('../includes/overall/db_disconnect.php');
?>