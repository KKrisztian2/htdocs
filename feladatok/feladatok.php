<?php 
	$oldal = "szerkesztofeladatok";
	session_start();
	$_SESSION["title"] = "Feladatok";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
 ?>
	<div class = "feladatok">
	<?php 
	if(!isset($_GET["feladat"]))
		echo '<a href = "../szerkeszto/index.php?feladatsor='.$_GET["feladatsor"].'" class = "alap_gomb">Vissza a szerkesztőhöz</a>';

	$sql = "SELECT * FROM ".$_GET["feladatsor"];
	$result = $conn->query($sql);
	$db = 1;
	
	if($result != false && $result->num_rows > 0){
		while($row = $result->fetch_assoc()) {
			echo "<div class = 'feladat'>";
			echo "<span class = 'feladatSorszama'>".$db.". feladat</span> <a href = '../szerkeszto/loadTaskEdit.php?feladatsor=".$_GET["feladatsor"]."&id=".$row["id"]."' class = 'szerkeszt_gomb inline displayHover'>Szerkesztés</a>";
			echo "<br>".$row["feladat"]."<br>";
			echo "<div class='adatok'>";
				echo "<p>Megoldás: ".$row["megoldas"]."</p>";
				echo "<p>Szint: ".$row["szint"]."</p>";
				$sql = "SELECT temakor_neve FROM temakorok WHERE id=".$row["temakor"];
				$temakor = $conn->query($sql);
				$temakor = $temakor->fetch_assoc();
				echo "<p>Témakör: ".$temakor["temakor_neve"]."</p>";
				$sql = "SELECT tipus FROM feladat_tipus WHERE id=".$row["feladat_tipusa"];
				$tipus = $conn->query($sql);
				$tipus = $tipus->fetch_assoc();
				echo "<p>Típusa: ".$tipus["tipus"]."</p>";
				if($row["feladat_tipusa"]==3)
					echo "<p>Válaszok száma: ".$row["valaszok_szama"]."</p>";
			echo "</div>";
			echo "</div>";
			$db++;
		}
	}else{
		echo " <p>Nincs adat a táblában!</p>";
	}
	
	
	$conn->close();
	?>
	</div>

<?php 
	include('../includes/overall/footer.php'); 
	include('../includes/overall/db_disconnect.php');
?>