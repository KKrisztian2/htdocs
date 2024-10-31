<?php 
	$oldal = "znotesztletrehoz";
	session_start();
	$_SESSION["title"] = "Nyilvános tesztek kezelése";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
?>

<?php
	if(isset($_POST["kuldes"])){
		$_SESSION["tabla_nev"]="nlv_";
		$_SESSION["tabla_nev"]=$_SESSION["tabla_nev"].$_POST["evszam"];
		$_SESSION["tabla_nev"]=$_SESSION["tabla_nev"]."_";
		$_SESSION["tabla_nev"]=$_SESSION["tabla_nev"].$_POST["nev"];
		$hiba="";
		$sql="SELECT tabla_neve FROM feladatsorok WHERE SUBSTR(tabla_neve,1,3)=".'"nlv"'." ORDER BY tabla_neve DESC";
		$result=$conn->query($sql);
		for($i=0;$i<$result->num_rows;$i++){
			$nev=$result->fetch_array();
			if($nev[0]==$_SESSION["tabla_nev"]){
				$hiba="Ez a feladatsor már létezik!";
			}
		}
		if($hiba==""){
			$sql="INSERT INTO feladatsorok (nev, tabla_neve, szerkesztette, allapot) VALUES ('".$_SESSION["tabla_nev"]."','".$_SESSION["tabla_nev"]."',".$_SESSION["felhasznalo"].", 'szerkesztés alatt')";
			$result=$conn->query($sql);
			$sql = "CREATE TABLE ".$_SESSION["tabla_nev"]." (
					id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					sorszam int(11) NOT NULL UNIQUE,
					feladat TEXT NOT NULL,
					feladat_tipusa int(11) NOT NULL,
					valaszok_szama int(11) NOT NULL,
					megoldas varchar(100) NOT NULL,
					szint int(11) NOT NULL,
					temakor int(11) NOT NULL,
					FOREIGN KEY (feladat_tipusa) REFERENCES feladat_tipus(id),
					FOREIGN KEY (temakor) REFERENCES temakorok(id))";
			$result=$conn->query($sql);
			include('../includes/overall/db_disconnect.php');
			header('Location: ../szerkeszto/index.php?feladatsor='.$_SESSION["tabla_nev"]);
			exit();
		}
		else{
			echo "<div id = 'hibajelzes'>
					<p>$hiba</p>
				</div>";
		}
	}
	$hiba="";
	echo "<div id = 'hibajelzes'>
		<p>$hiba</p>
		</div>";

?>

	<p>Alább találja az ön által szerkesztett feladatsorokat. Lehetősége van ezeket szerkeszteni, nyilvánossá/zártá tenni, törölni. <br> Illetve itt tud feltölteni új feladatot az adatbázisba. <br> 
		<button class = "pelda_gomb"><i class = 'far fa-edit'></i></button> - feladatsor szerkesztése <br>
		<button class = "pelda_gomb"><i class = 'fas fa-upload'></i></button> - feladatsor feltöltése <br>
		<button class = "pelda_gomb"><i class = 'fas fa-trash'></i></button> - feladatsor végleges törlése <br>
		<button class = "pelda_gomb"><i class = 'far fa-arrow-alt-circle-down'></i></button> - feladatsor feltöltésének visszavonása
	</p>
	<button id = "alap_gomb">Új feladatsor feltöltése</button><br>
	<?php
		echo "<ul id = 'kesz_feladatsorok'>";

		$sql="SELECT * FROM feladatsorok WHERE SUBSTR(tabla_neve,1,3)=".'"nlv"'." ORDER BY tabla_neve DESC" ;
		$result=$conn->query($sql);
		$elozo="";
		$most="";
		for($i=0;$i<$result->num_rows;$i++){
			if($i>0)
				$elozo=substr($row["tabla_neve"], 4, 4);
			$row=$result->fetch_array();
			$most=substr($row["tabla_neve"], 4, 4);
			if($elozo!=$most && $i>0){
				echo "</ul>";
				echo "</li>";
			}
			if($elozo!=$most){
				echo "<li>".substr($row["tabla_neve"], 4, 4)."";
					echo "<ul>";
			}
					echo "<li>";
					echo "<p class='nev'>";
					echo substr($row["tabla_neve"], 9, 50);
					echo "</p>";
						$sql="SELECT nev FROM felhasznalok WHERE id=".$row["szerkesztette"].";";
						$result1=$conn->query($sql);
						$nev=$result1->fetch_array();
						echo "<p>".$row["allapot"]."</p>";
						echo "<div class = 'gombok_listaja'>";
							if ($row["allapot"] != "feltöltve")
								echo "<a class = 'szerk' href = '../feladatok/feladatok.php?feladatsor=".$row["tabla_neve"]."'><i class = 'far fa-edit'></i></a>";
							if ($row["allapot"] == "szerkesztés alatt")
								echo "<a class = 'kozzetetel' href = 'feltoltes.php?feladatsor=".$row["tabla_neve"]."'><i class = 'fas fa-upload'></i></a>";
							else if($row["allapot"] == "feltöltve")
								echo "<a class = 'visszavonas' href = 'visszavonas.php?feladatsor=".$row["tabla_neve"]."'><i class = 'far fa-arrow-alt-circle-down'></i></a>";
							echo "<a class = 'torles'><i class = 'fas fa-trash'></i></a>";
						echo "</div>";
					echo "</li>";
		}
		echo "</ul>";
		$date=date("Y");
	?>
	
	<div id = "ujznoteszt_div">
		<form method = "post" action = "">
			<span id = "bezar_gomb"><i class="fa fa-close"></i></span>
			<label for = "nev">Adja meg a feladatsor nevét</label><br>
			<input type="text" name="nev"><br>
			<label for = "evszam">Év</label><br>
			<input type = "number" min = "1950" max = <?php echo "$date" ?> name = "evszam" id = "evszam" value = <?php echo "$date" ?>><br>
			<input type = "submit" value = "Küldés" id = "alap_gomb" name = "kuldes">
		</form>
	</div>
	<div id="torles_main">
		<div id="torles_uzenet">
			<span id = "torles_uzenet_bezar"><i class="fa fa-close"></i></span>
		</div>
	</div>
<?php 
	include('../includes/overall/footer.php'); 
	include('../includes/overall/db_disconnect.php');
?>