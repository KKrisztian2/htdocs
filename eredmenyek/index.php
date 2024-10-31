<?php
	session_start();
	$_SESSION["title"] = "Eredmények";
	$oldal = "eredmenyek";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
?>
	<div>
		<div id="eredmenyek">
			<?php
				if(isset($_SESSION["rang"]) && $_SESSION["rang"]=="diak"){
					$sql="SELECT * FROM eredmenyek WHERE diak_id=".$_SESSION["felhasznalo"];
					$result=$conn->query($sql);
					for($i=0;$i<$result->num_rows;$i++){
						$row=$result->fetch_array();
						echo "<div class='eredmeny'> <div class='fejlec'><h3>".$row["feladat_nev"]."</h3></div><hr><p>Kitöltés időpontja: ".$row["datum"]."</p><p>Elért pontszám: ".$row["pontszam"]."</p><p>Százalékban: ".$row["szazalek"]."%</p></div>";
					}
				}
				else if(isset($_SESSION["rang"]) && $_SESSION["rang"]=="tanar"){
					$sql="SELECT tabla_nev FROM csoportok WHERE tanar_id=".$_SESSION["felhasznalo"];
					$result=$conn->query($sql);
					$diakok=[];
					$db=0;
					for($i=0;$i<$result->num_rows;$i++){
						$row=$result->fetch_array();
						$sql="SELECT id FROM ".$row["tabla_nev"];
						$result1=$conn->query($sql);
						while($diak_id=$result1->fetch_assoc()){
							$volt=false;
							for($i=0;$i<$db;$i++){
								if($diak_id["id"]==$diakok[$i]){
									$volt=true;
								}
							}
							if($volt==false){
								$diakok[$db]=$diak_id["id"];
								$db++;
								$sql="SELECT * FROM eredmenyek WHERE diak_id=".$diak_id["id"];
								$result2=$conn->query($sql);
								while($eredmeny=$result2->fetch_assoc()){
									$sql="SELECT nev FROM felhasznalok WHERE id=".$diak_id["id"];
									$result3=$conn->query($sql);
									$nev=$result3->fetch_assoc();
									echo "<div class='eredmeny'> <div class='fejlec'><h3>".$eredmeny["feladat_nev"]."</h3></div><hr><p>Név: ".$nev["nev"]."</p><p>Kitöltés időpontja: ".$eredmeny["datum"]."</p><p>Elért pontszám: ".$eredmeny["pontszam"]."</p><p>Százalékban: ".$eredmeny["szazalek"]."%</p></div>";
								}
							}
						}
					}
				}
			?>
		</div>
		<aside>
			<h2>Toplista</h2>
			<ol>
				<?php
					$sql="SELECT * FROM eredmenyek ORDER BY szazalek DESC LIMIT 10";
					$result=$conn->query($sql);
					for($i=0;$i<$result->num_rows;$i++){
						$row=$result->fetch_array();
						$sql="SELECT nev FROM felhasznalok WHERE id=".$row["diak_id"].";";
						$result1=$conn->query($sql);
						$nev=$result1->fetch_array();
						echo "<li><b>$nev[0]</b><br>Feladatsor: <i>".$row["feladat_nev"]."</i><br> Eredmény: ".$row["szazalek"]."%</li>";
					}
				?>
			</ol>
		</aside>
	</div>
<?php 
	include('../includes/overall/footer.php'); 
	include('../includes/overall/db_disconnect.php');
?>