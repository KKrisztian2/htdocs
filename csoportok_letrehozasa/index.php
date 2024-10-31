<?php 
	session_start();
	$oldal = "csoport_letrehoz";
	$_SESSION["title"] = "Csoportok";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php'); 
?>
	<div id = "letezo_csoportok">
	<?php
		if(isset($_SESSION["rang"]) && $_SESSION["rang"]=="tanar"){
			$sql="SELECT csoport_nev, tabla_nev FROM csoportok WHERE tanar_id=".$_SESSION["felhasznalo"];
			$result=$conn->query($sql);
			while($csoport=$result->fetch_assoc()){
				echo '<div class="csoport">';
				echo '<h3>'.$csoport["csoport_nev"].'</h3>';
				echo '<ul class="megjeleno">';
				$sql="SELECT id FROM ".$csoport["tabla_nev"];
				$result1= $conn->query($sql);
					while($nevek=$result1->fetch_assoc()){
						$sql="SELECT nev FROM felhasznalok WHERE id=".$nevek["id"];
						$result2= $conn->query($sql);
						$nev=$result2->fetch_assoc();
						echo '<li>'.$nev["nev"].'</li>';
					}
				echo '</ul>';
				//echo '<button class="alap_gomb szerk">Szerkesztés</button>';
				echo '</div>';
			}
		}
		else if(isset($_SESSION["rang"]) && $_SESSION["rang"]=="diak"){
			$sql="SELECT csoport_nev, tabla_nev FROM csoportok";
			$result=$conn->query($sql);
			while($csoport=$result->fetch_assoc()){
				$sql="SELECT id FROM ".$csoport["tabla_nev"]." WHERE id=".$_SESSION["felhasznalo"];
				$result2=$conn->query($sql);
				if($result2->num_rows>0){
					echo '<div class="csoport">';
			echo '<h3>'.$csoport["csoport_nev"].'</h3>';
			echo '<ul class="megjeleno">';
			$sql="SELECT id FROM ".$csoport["tabla_nev"];
			$result1= $conn->query($sql);
				while($nevek=$result1->fetch_assoc()){
					$sql="SELECT nev FROM felhasznalok WHERE id=".$nevek["id"];
					$result2= $conn->query($sql);
					$nev=$result2->fetch_assoc();
					echo '<li>'.$nev["nev"].'</li>';
				}
			echo '</ul>';
			echo '</div>';
				}
			}
		}
	?>	
	</div>
	<?php
		if(isset($_SESSION["rang"]) && $_SESSION["rang"]=="tanar"){
			echo '<button id = "alap_gomb">Csoport létrehozása</button>';
		}
	?>
	<div>
		<span id = "bezar_gomb"><i class="fa fa-close"></i></span>
		<div id = "flex">
			<div id="nevsor">
				<label for = "kereses">Keresendő diák:</label><br>
				<input type = "text" name = "kereses" id = "kereses">
					<ul>
					<?php
						$sql="SELECT nev FROM felhasznalok WHERE rang='diak'";
						$result=$conn->query($sql);
						while($diak=$result->fetch_assoc()){
							echo '<li>'.$diak["nev"].'</li>';
						}
					?>
					</ul>
			</div>
			<div id="gombok">
				<button id="bedob"><i class="fa fa-arrow-right"></i></button><br>
				<button id="kidob"><i class="fa fa-arrow-left"></i></button>
			</div>
			<div id="csoport">
				<label for = "csoportnev">Csoport neve:</label><br>
				<input type = "text" name = "csoportnev" id = "csoportnev">
				<ul>
				</ul>
			</div>
			<div>
				<button class="alap_gomb" id="letrehoz">Létrehoz</button>
			</div>
			</form>
		</div>
	</div>
	<div id = "uzenet">
	</div>
			
<?php 
	include('../includes/overall/footer.php'); 
	include('../includes/overall/db_disconnect.php');
?>