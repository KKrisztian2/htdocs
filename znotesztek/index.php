<?php 
	session_start();
	$_SESSION["title"] = "Nyilvános teszt";
	$oldal = "znotesztek";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
?>

	<div class = "leiras">
		<p>Alább találja a mindenki számára nyilvános feladatsorokat, így gyakorolhat adott témakörökben.</p>
		<p>A teszt indításához csak kattintson a kívánt feladatsorra: </p>
	</div>
	<?php
		if (isset($_GET["sajat"]))
			$_SESSION["sajat"] = $_GET["sajat"];
		else {
			if (isset($_SESSION["sajat"]))
				unset($_SESSION["sajat"]);
		}
		$sql="SELECT * FROM feladatsorok WHERE SUBSTR(tabla_neve, 1, 3)=".'"nlv"'." AND allapot='feltöltve' ORDER BY tabla_neve DESC";
		$result=$conn->query($sql);
		$elozo="";
		$most="";
		echo "<table id = 'kesz_feladatsorok' border = '1'>";
		for($i=0;$i<$result->num_rows;$i++){
			if($i>0)
				$elozo=substr($row["tabla_neve"], 4, 4);
			$row=$result->fetch_array();
			$most=substr($row["tabla_neve"], 4, 4);
			if($elozo!=$most && $i>0){
				echo "</tr>";
			}
			if($elozo!=$most){
				echo "<tr>";
					echo "<td>$most</td>";
			}
			echo "<td><a href = '../feladatok/diak.php?feladatsor=".$row["tabla_neve"]."'>";
			echo substr($row["tabla_neve"], 9, 50);
			echo "</a></td>";
		}
		echo "</table>";
	?>
	
	<div class = "leiras">
		<p>A gyakorlást elősegítendő véletlenszerű feladatsor generálására is van lehetősége (akár adott témakörök kiválasztásával), ehhez válassza a 2. menüpontot. </p>
	</div>


<?php 
	include('../includes/overall/footer.php');
	include('../includes/overall/db_disconnect.php');
?>