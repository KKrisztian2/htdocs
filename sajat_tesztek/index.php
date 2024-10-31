<?php 
	$oldal = "sajat_tesztek";
	session_start();
	$_SESSION["title"] = "Saját feladatsorok";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
	if (isset($_SESSION["rang"]) && $_SESSION["rang"] == "tanar"){ ?>
		<section id = "lehetosegek">
			<p>Új teszt összeállítása:</p>
			<button class = "ujteszt_gomb"><a href = "../sajat_teszt/index.php?sajat=1">Véletlenszerű feladatsor</a></button>
			<button class = "ujteszt_gomb"><a href = "#">Feladatsor létrehozása</a></button>
			<button class = "ujteszt_gomb"><a href = "#">Feladatok kiválasztása</a></button><br>
			<p>Már elkészített tesztek: </p>
		</section>
	<?php 
	}

	if (isset($_POST["mentes"])){
		$nev = $_POST["nev"];
		megtisztit($_POST["nev"]);
		if ($_SESSION["sajat"] == 1)
			$_POST["nev"] = sajat_nev($_POST["nev"]);
		if (ellenoriz($_POST["nev"]) != ""){
			$_SESSION["hiba"] = ellenoriz($_POST["nev"]);
			//visszairanyitas, hibajelzes
			if ($_SESSION["sajat"] == 1){
				//vissza a generált teszthez
				header ("Location: ../feladatok/diak.php?sajat_teszt=sajat_teszt");
				exit();
			}
			if ($_SESSION["sajat"] == 2){
				header ("Location: ../feladatok/diak.php?feladatsor=".$_SESSION["feladatsor"]);
				exit();
			}
			
		}else {
			//feladatsor mentese
			if ($_SESSION["sajat"] == 2){
				$stmt = $conn->prepare("INSERT INTO feladatsorok_sajat (nev,tabla_neve,szerkesztette,allapot) VALUES (?, ?, ?, ?)");
				$f = "feltöltve";
				$stmt->bind_param("ssis",$_POST["nev"],$_SESSION["feladatsor"],$_SESSION["felhasznalo"], $f);
				$stmt->execute();
				$stmt->close();
			}else if ($_SESSION["sajat"] == 1){
				$stmt = $conn->prepare("INSERT INTO feladatsorok_sajat (nev,tabla_neve,szerkesztette,allapot) VALUES (?, ?, ?, ?)");
				$f = "feltöltve";
				$stmt->bind_param("ssis",$nev, $_POST["nev"], $_SESSION["felhasznalo"], $f);
				$stmt->execute();
				$stmt->close();
				$sql = "CREATE TABLE ".$_POST["nev"]."(id int AUTO_INCREMENT, sorszam int, feladat text(30000), feladat_tipusa int , valaszok_szama int, megoldas varchar(100), szint int, temakor int, PRIMARY KEY(id), INDEX(feladat_tipusa),  INDEX(temakor), FOREIGN KEY (feladat_tipusa) REFERENCES feladat_tipus(id), FOREIGN KEY (temakor) REFERENCES temakorok(id) );";
				$conn->query($sql);
				foreach($_SESSION["beilleszt"] as $sor){
					$stmt = $conn->prepare("INSERT INTO ".$_POST["nev"]." (sorszam, feladat, feladat_tipusa, valaszok_szama, megoldas, szint, temakor) VALUES (?, ?, ?, ?, ?, ?, ?)");
					echo $conn->error;
					$stmt->bind_param("sssssss", $sor["sorszam"], $sor["feladat"], $sor["feladat_tipusa"] ,$sor["valaszok_szama"], $sor["megoldas"], $sor["szint"], $sor["temakor"]);
					$stmt->execute();
					$stmt->close();
					if ($conn->error){
						echo $conn->error;
					}
				}
				unset($_SESSION["beilleszt"]);
				
			}
		}
	}
		
		unset($_SESSION["sajat"]);
		unset($_SESSION["hiba"]);
	
?>

<section id = "tesztek">
	<?php
		if (isset($_SESSION["rang"]) && $_SESSION["rang"] == "tanar"){
			$sql="SELECT id, nev, tabla_neve FROM feladatsorok_sajat WHERE szerkesztette=".$_SESSION["felhasznalo"];
			$result = $conn->query($sql);
			while($row = $result->fetch_assoc()){
				echo '<div class = "teszt">';
				echo '<h4><a href = "../feladatok/feladatok.php?feladatsor='.$row["tabla_neve"].'">'.$row["nev"].'</a></h4>';
					echo '<ul class = "megjeleno">';
						$sql="SELECT csoport_id FROM kiadott WHERE feladatsor_id=".$row["id"];
						$csoportok = $conn->query($sql);
						while($nev = $csoportok->fetch_assoc()){
							$sql="SELECT csoport_nev FROM csoportok WHERE id=".$nev["csoport_id"];
							$result1=$conn->query($sql);
							$nev=$result1->fetch_assoc();
							echo '<li>'.$nev["csoport_nev"].'</li>';
						}
					echo '</ul>';
					echo '<button class="alap_gomb kiad_gomb">Kiad</button>';
					echo '</div>';
			}
		}
		else if(isset($_SESSION["rang"]) && $_SESSION["rang"] == "diak"){
			$sql="SELECT id, tabla_nev FROM csoportok";
			$result = $conn->query($sql);
			$csoportok=[];
			$csoportok_index=0;
			while($row = $result->fetch_assoc()){
				$sql="SELECT id FROM ".$row["tabla_nev"];
				$result1 = $conn->query($sql);
				while($id = $result1->fetch_assoc()){
					if($id["id"]==$_SESSION["felhasznalo"]){
						$csoportok[$csoportok_index]=$row["id"];
						$csoportok_index=$csoportok_index+1;
					}
				}
			}
			for($i=0;$i<$csoportok_index;$i++){
				$sql="SELECT * FROM kiadott WHERE csoport_id=".$csoportok[$i];
				$result = $conn->query($sql);
				while($row = $result->fetch_assoc()){
					$sql="SELECT nev, tabla_neve FROM feladatsorok_sajat WHERE id=".$row["feladatsor_id"];
					$result1 = $conn->query($sql);
					$f_nev=$result1->fetch_assoc();
					echo '<div class = "teszt">';
					echo '<h4><a href = "../feladatok/diak.php?feladatsor='.$f_nev["tabla_neve"].'">'.$f_nev["nev"].'</a></h4>';
					echo '</div>';
				}
			}
		}
		?>
		<div id="ki">
			<span id = "bezar_gomb"><i class="fa fa-close"></i></span>
			<div id = "flex">
				<div id="nevsor">
					<label for = "kereses">Keresendő csoport:</label><br>
					<input type = "text" name = "kereses" id = "kereses">
						<ul>
						<?php
							$sql="SELECT csoport_nev FROM csoportok";
							$result=$conn->query($sql);
							while($nev=$result->fetch_assoc()){
								echo '<li>'.$nev["csoport_nev"].'</li>';
							}
						?>
						</ul>
				</div>
				<div id="gombok">
					<button id="bedob"><i class="fa fa-arrow-right"></i></button><br>
					<button id="kidob"><i class="fa fa-arrow-left"></i></button>
				</div>
				<div id="csoport">
					<ul>
					</ul>
				</div>
				<div>
					<button class="alap_gomb" id="kiad">Kiad</button>
				</div>
				</form>
			</div>
		</div>
		
</section>
<section id = "uzenet">
	
</section>
<?php 
	function megtisztit($adat){
		$adat = trim($adat);
		$adat=stripslashes($adat);
		$adat=htmlspecialchars($adat);
		for ($i = 0; $i < strlen($adat); $i++){
			if (substr($adat, $i, 1) == " "){
				$adat[$i] = '_';
			}
		}
		return $adat;
	}
	function ellenoriz($adat){
		global $conn;
		$hiba = "";
		if ($_SESSION["sajat"] == 2){
			if (substr($adat,0,3) == "zno"){
				$hiba = "rossz nev";
			}
			$result = $conn->query("SELECT * FROM feladatsorok_sajat WHERE nev = '".$adat."'");
			if ($result->num_rows > 0){
				$hiba = "mar letezik";
			}
			return $hiba;
		}
		if ($_SESSION["sajat"] == 1){
			$result = $conn->query("SELECT * FROM feladatsorok_sajat WHERE tabla_neve = '".$adat."'");
				if ($result->num_rows > 0){
					$hiba = "mar letezik";
				}
				return $hiba;
		}
	}
	
	function sajat_nev($adat){
		$adat = "sajat_".$_SESSION["felhasznalo"]."_".$_POST["nev"];
		return $adat;
	}
	include('../includes/overall/footer.php'); 
	include('../includes/overall/db_disconnect.php');
?>