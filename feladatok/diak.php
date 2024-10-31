<?php
	session_start();
	$oldal = "szerkesztodiak";
	$_SESSION["title"] = "Gyakorló teszt";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
?>
		
		<div class = "feladatok">
			<?php
				$db = 1;
				$adatok = [];
				
				if (isset($_SESSION["sajat"])){
	
					echo "<form action = '../sajat_tesztek/index.php' method = 'post'>";
					if (isset($_SESSION["hiba"]) && $_SESSION["hiba"] == "rossz nev"){
						echo "<p style = 'color: red'>A feladatsor neve nem kezdődhet 'zno'-val!</p>";
					}
					if (isset($_SESSION["hiba"]) && $_SESSION["hiba"] == "mar letezik"){
						echo "<p style = 'color: red'>Ilyen néven már létezik feladatsor!</p>";
					}
					if (isset($_GET["feladatsor"]))
						$_SESSION["feladatsor"] = $_GET["feladatsor"];
					
					echo "<input type = 'text' placeholder = 'A feladatsor neve' id = 'nev' name = 'nev' required"; if (isset($_POST["nev"])) echo "value = '".$_POST["nev"]."'>"; else echo ">";
					
					echo "<input type = 'submit' name = 'mentes' id = 'kiad_gomb' value = 'Mentés'>";
					echo "</form>";
				}
				if(isset($_GET["sajat_teszt"]) && isset($_SESSION["sajat_teszt"])){
					$db = count($_SESSION["sajat_teszt"]);
					//valtozoKiir($_SESSION["sajat_teszt"]);
					feladatokLetrehozasaGombokKiirasa();
				}else if(isset($_GET["sajat_teszt"]) && isset($_POST["szam"])){
					$szintKriterium = "";     //getTemakorTabla() hozza létre - SQl utasítás kritériuma
					$szintek = getSzintek();
					$temakor_tablak = getTemakorTablak($szintek);  //$temakor_tablak[0]["tabla"] - témakörtábla neve, $temakor_tablak[0]["feladatok"] - egy tömb, ahol a megfelelő témakörtáblában található $szintKriterium -nak megfelelő feladatok vannak(tabla_neve, feladat_id, feladat_szint)
					$feladatok_szama = $_POST["szam"];	
					$_SESSION["sajat_teszt"] = ["szintKriterium" => $szintKriterium]; //nem feltétlen kell elmenteni
					
					//////////////////feladatok számának ellenőrzése//////////////////////
					$a = 0;
					foreach($temakor_tablak as $tabla){
						$a += count($tabla["feladatok"]);
					}
					if($a < $feladatok_szama){
						$feladatok_szama = $a;
					}
					
					//feladatok véletlenszerű összeállítása
					$temakorok_szama = count($temakor_tablak);
					$seged = []; //abban lesz segítségemre, hogy egy feladatot ne válasszon ki többször az algoritmus
					while($db <= $feladatok_szama){
						$tabla_sorszam = rand(0,$temakorok_szama-1);
						$sor = rand(1,count($temakor_tablak[$tabla_sorszam]["feladatok"]));
						if(!in_array($tabla_sorszam."|".$sor,$seged)){
							$_SESSION["sajat_teszt"][$db-1]["tabla"] = $temakor_tablak[$tabla_sorszam]["tabla"];
							$_SESSION["sajat_teszt"][$db-1]["feladat"] = $temakor_tablak[$tabla_sorszam]["feladatok"][$sor-1];
							$seged[$db-1] = $tabla_sorszam."|".$sor;
							$db++;
						}
					}
					
					feladatokLetrehozasaGombokKiirasa();
				}
					
				else if(!isset($_GET["sajat_teszt"])){
					$sql = "SELECT * FROM ".$_GET["feladatsor"];
					$result = $conn->query($sql);
					if($result != false && $result->num_rows > 0){
						echo "<div class = 'gombok'>";
						while($row = $result->fetch_assoc()) {
							$adatok["feladat"][$db-1] = $row["feladat"];
							$adatok["id"][$db-1] = $row["id"];
							$adatok["feladat_tipusa"][$db-1] = $row["feladat_tipusa"];
							$adatok["valaszok_szama"][$db-1] = $row["valaszok_szama"];
							$adatok["megoldas"][$db-1] = $row["megoldas"];
							if ($db == 1)
								echo '<button class = "sorszam akt" onclick = "feladatvaltas(this)">'.$db.'</button>';
								else 
									echo '<button class = "sorszam" onclick = "feladatvaltas(this)">'.$db.'</button>';
							$db++;
						}
						echo "</div>";
						
					}else{
						echo "Nincs adat a táblában!";
					}
				}
				echo "<form method = 'post' action = ''>";
				echo "<div id = 'feladatok'>";
				$maxpont = 0;
				$eredmeny = 0;
				for ($i = 0; $i < $db-1; $i++){
					
					if ($i == 0){
						echo "<div class = 'feladat aktiv' id = '$i' style = 'display: block'>";
					}else {
						echo "<div class = 'feladat' style = 'display: none'>";
					}
					echo "<br>".$adatok["feladat"][$i]."<br>";
					echo "</div>";
					echo "<div class = 'megoldas'";                  
						if ($i != 0) echo "style = 'display: none'>"; //itt hiányzott egy: >
						else echo ">";                                //itt hiányzott egy: >
					
					if ($adatok["feladat_tipusa"][$i] == 1){
						$maxpont++;
						echo "<p>Az ön válasza:</p>";
						echo "<table>";
						echo "<tr>";
						echo "<td><label for = 'A'>A</label></td>";
						echo "<td><label for = 'B'>B</label></td>";
						echo "<td><label for = 'C'>C</label></td>";
						echo "<td><label for = 'D'>D</label></td>";
						echo "<td><label for = 'E'>E</label></td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td><input type = 'radio' name = '$i' value = 'A'"; if (isset($_POST["submit"])){ if (isset($_POST["$i"]) && $_POST["$i"] == "A") echo "checked"; else echo "disabled";} echo"></td>";
						
						echo "<td><input type = 'radio' name = '$i' value = 'B'"; if (isset($_POST["submit"])){if (isset($_POST["$i"]) && $_POST["$i"] == "B") echo "checked"; else echo "disabled";} echo"></td>";
						
						echo "<td><input type = 'radio' name = '$i' value = 'C'"; if (isset($_POST["submit"])){if (isset($_POST["$i"]) && $_POST["$i"] == "C") echo "checked"; else echo "disabled";} echo"></td>";
						
						echo "<td><input type = 'radio' name = '$i' value = 'D'"; if (isset($_POST["submit"])){if (isset($_POST["$i"]) && $_POST["$i"] == "D") echo "checked"; else echo "disabled";} echo"></td>";
						
						echo "<td><input type = 'radio' name = '$i' value = 'E'"; if (isset($_POST["submit"])){if (isset($_POST["$i"]) && $_POST["$i"] == "E") echo "checked"; else echo "disabled";} echo"></td>";
						if (isset($_POST["submit"]) && isset($_POST[$i]) && $_POST[$i] == $adatok["megoldas"][$i]){
							$eredmeny ++;
							echo "<td><i class = 'fas fa-check' style = 'font-size: 20px; color: green'></i></td>";
						}else {
							if (isset($_POST["submit"]))
								echo "<td><i class = 'fas fa-times' style = 'font-size: 20px; color:red'></i></td>";
						}
						echo "</tr>";
						
						echo "</table>";
						if (isset($_POST["submit"])){
							echo "<p>A helyes válasz: </p><br>";
							echo "<table>";
							echo "<tr>";
							echo "<td><label for = 'A'>A</label></td>";
							echo "<td><label for = 'B'>B</label></td>";
							echo "<td><label for = 'C'>C</label></td>";
							echo "<td><label for = 'D'>D</label></td>";
							echo "<td><label for = 'E'>E</label></td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td><input type = 'radio' value = 'A'"; if ($adatok["megoldas"][$i] == "A") echo "checked"; else echo "disabled"; echo"></td>";
							
							echo "<td><input type = 'radio' value = 'B'"; if ($adatok["megoldas"][$i] == "B") echo "checked"; else echo "disabled"; echo"></td>";
							
							echo "<td><input type = 'radio' value = 'C'"; if ($adatok["megoldas"][$i] == "C") echo "checked"; else echo "disabled"; echo"></td>";
							
							echo "<td><input type = 'radio' value = 'D'"; if ($adatok["megoldas"][$i] == "D") echo "checked"; else echo "disabled"; echo"></td>";
							
							echo "<td><input type = 'radio' value = 'E'"; if ($adatok["megoldas"][$i] == "E") echo "checked"; else echo "disabled"; echo"></td>";
							echo "</tr>";
							echo "</table>";
						}
					}else if ($adatok["feladat_tipusa"][$i] == 2){
						$maxpont = $maxpont + 4;
						echo "<p>Az ön válasza:</p>";
						echo '
							<table>
							<tr>
								<th></th>
								<th>A</th>
								<th>B</th>
								<th>C</th>
								<th>D</th>
								<th>E</th>
							</tr>
							<tr>
								<th>1</th>
								<td><input type = "radio" value = "A" name = "'.$i.'_elso"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_elso"]) && $_POST["$i"."_elso"] == "A") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "B" name = "'.$i.'_elso"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_elso"]) && $_POST["$i"."_elso"] == "B") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "C" name = "'.$i.'_elso"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_elso"]) && $_POST["$i"."_elso"] == "C") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "D" name = "'.$i.'_elso"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_elso"]) && $_POST["$i"."_elso"] == "D") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "E" name = "'.$i.'_elso"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_elso"]) && $_POST["$i"."_elso"] == "E") echo 'checked'; else echo 'disabled';} echo'></td>';
							if (isset($_POST["submit"]) && isset($_POST[$i."_elso"]) && $_POST[$i."_elso"] == substr($adatok["megoldas"][$i],0,1)){
								echo "<td><i class = 'fas fa-check' style = 'font-size: 20px; color: green'></i></td>";
								$eredmeny ++;
							}else 
								if (isset($_POST["submit"]))
									echo "<td><i class = 'fas fa-times' style = 'font-size: 20px; color:red'></i></td>";
							echo '</tr>
							<tr>
								<th>2</th>
								<td><input type = "radio" value = "A" name = "'.$i.'_masodik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_masodik"]) && $_POST["$i"."_masodik"] == "A") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "B" name = "'.$i.'_masodik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_masodik"]) && $_POST["$i"."_masodik"] == "B") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "C" name = "'.$i.'_masodik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_masodik"]) && $_POST["$i"."_masodik"] == "C") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "D" name = "'.$i.'_masodik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_masodik"]) && $_POST["$i"."_masodik"] == "D") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "E" name = "'.$i.'_masodik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_masodik"]) && $_POST["$i"."_masodik"] == "E") echo 'checked'; else echo 'disabled';} echo'></td>';
							if (isset($_POST["submit"]) &&  isset($_POST[$i."_masodik"]) && $_POST[$i."_masodik"] == substr($adatok["megoldas"][$i],2,1)){
								echo "<td><i class = 'fas fa-check' style = 'font-size: 20px; color: green'></i></td>";
								$eredmeny ++;
							}else 
								if (isset($_POST["submit"]))
									echo "<td><i class = 'fas fa-times' style = 'font-size: 20px; color:red'></i></td>";
							echo '</tr>
							<tr>
								<th>3</th>
								<td><input type = "radio" value = "A" name = "'.$i.'_harmadik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_harmadik"]) && $_POST["$i"."_harmadik"] == "A") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "B" name = "'.$i.'_harmadik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_harmadik"]) && $_POST["$i"."_harmadik"] == "B") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "C" name = "'.$i.'_harmadik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_harmadik"]) && $_POST["$i"."_harmadik"] == "C") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "D" name = "'.$i.'_harmadik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_harmadik"]) && $_POST["$i"."_harmadik"] == "D") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "E" name = "'.$i.'_harmadik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_harmadik"]) && $_POST["$i"."_harmadik"] == "E") echo 'checked'; else echo 'disabled';} echo'></td>';
							if (isset($_POST["submit"]) && isset($_POST[$i."_harmadik"]) &&  $_POST[$i."_harmadik"] == substr($adatok["megoldas"][$i],4,1)){
								echo "<td><i class = 'fas fa-check' style = 'font-size: 20px; color: green'></i></td>";
								$eredmeny ++;
							}else 
								if (isset($_POST["submit"]))
									echo "<td><i class = 'fas fa-times' style = 'font-size: 20px; color:red'></i></td>";
							echo '</tr>
							<tr>
								<th>4</th>
								<td><input type = "radio" value = "A" name = "'.$i.'_negyedik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_negyedik"]) && $_POST["$i"."_negyedik"] == "A") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "B" name = "'.$i.'_negyedik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_negyedik"]) && $_POST["$i"."_negyedik"] == "B") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "C" name = "'.$i.'_negyedik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_negyedik"]) && $_POST["$i"."_negyedik"] == "C") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "D" name = "'.$i.'_negyedik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_negyedik"]) && $_POST["$i"."_negyedik"] == "D") echo 'checked'; else echo 'disabled';} echo'></td>
								<td><input type = "radio" value = "E" name = "'.$i.'_negyedik"'; if (isset($_POST["submit"])){ if (isset($_POST["$i"."_negyedik"]) && $_POST["$i"."_negyedik"] == "E") echo 'checked'; else echo 'disabled';} echo'></td>';
							
							if (isset($_POST["submit"]) && isset($_POST[$i."_negyedik"]) && $_POST[$i."_negyedik"] == substr($adatok["megoldas"][$i],6,1)){
								echo "<td><i class = 'fas fa-check' style = 'font-size: 20px; color: green'></i></td>";
								$eredmeny ++;
							}else 
								if (isset($_POST["submit"]))
									echo "<td><i class = 'fas fa-times' style = 'font-size: 20px; color:red'></i></td>";
							echo '</tr>
						</table>
						';
						if (isset($_POST["submit"])){
							echo "<p>A helyes válasz: </p>";
							echo '
							<table>
							<tr>
								<th></th>
								<th>A</th>
								<th>B</th>
								<th>C</th>
								<th>D</th>
								<th>E</th>
							</tr>
							<tr>
								<th>1</th>
								<td><input type = "radio" value = "A" '; if (substr($adatok["megoldas"][$i],0,1) == "A") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "B" ';  if (substr($adatok["megoldas"][$i],0,1) == "B") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "C" ';  if (substr($adatok["megoldas"][$i],0,1) == "C") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "D" ';  if (substr($adatok["megoldas"][$i],0,1) =="D") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "E" ';  if (substr($adatok["megoldas"][$i],0,1) == "E") echo 'checked'; else echo 'disabled'; echo'></td>;
							
							</tr>
							<tr>
								<th>2</th>
								<td><input type = "radio" value = "A" ';  if (substr($adatok["megoldas"][$i],2,1) =="A") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "B" ';  if (substr($adatok["megoldas"][$i],2,1) =="B") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "C" ';  if (substr($adatok["megoldas"][$i],2,1) == "C") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "D" ';  if (substr($adatok["megoldas"][$i],2,1) == "D") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "E" ';  if (substr($adatok["megoldas"][$i],2,1) == "E") echo 'checked'; else echo 'disabled'; echo'></td>
							</tr>
							<tr>
								<th>3</th>
								<td><input type = "radio" value = "A" ';  if (substr($adatok["megoldas"][$i],4,1) == "A") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "B" ';  if (substr($adatok["megoldas"][$i],4,1) == "B") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "C" ';  if (substr($adatok["megoldas"][$i],4,1) == "C") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "D" ';  if (substr($adatok["megoldas"][$i],4,1) == "D") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "E" ';  if (substr($adatok["megoldas"][$i],4,1) == "E") echo 'checked'; else echo 'disabled'; echo'></td>
							</tr>
							<tr>
								<th>4</th>
								<td><input type = "radio" value = "A" ';  if (substr($adatok["megoldas"][$i],6,1) == "A") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "B" ';  if (substr($adatok["megoldas"][$i],6,1) == "B") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "C" ';  if (substr($adatok["megoldas"][$i],6,1) == "C") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "D" ';  if (substr($adatok["megoldas"][$i],6,1) == "D") echo 'checked'; else echo 'disabled'; echo'></td>
								<td><input type = "radio" value = "E" ';  if (substr($adatok["megoldas"][$i],6,1) == "E") echo 'checked'; else echo 'disabled'; echo'></td>
							</tr>
						</table>
						';
							
						}
					}else if ($adatok["feladat_tipusa"][$i] == 3){
						echo "<p>Az ön válasza:</p>";
						if ($adatok["valaszok_szama"][$i] > 0){ //itt == 1 volt
							$maxpont = $maxpont + 2;
							echo '<input type = "text" name = "'.$i.'_1"';
							if (isset($_POST[$i."_1"])) echo ' value = "'.$_POST[$i."_1"].'" readonly>';//itt >
							else echo ">"; //és ide kell az else is a > miatt
							if (isset($_POST["submit"])){
								$index = 0;
								while(substr($adatok["megoldas"][$i],$index,1) != "	" && $index < strlen($adatok["megoldas"][$i])){
									$index++;
								}
								if ($_POST[$i."_1"] == substr($adatok["megoldas"][$i],0,$index)){
									echo "<td><i class = 'fas fa-check' style = 'font-size: 20px; color: green'></i></td>";//pipa
									$eredmeny=$eredmeny+2;
								}else 
									echo "<td><i class = 'fas fa-times' style = 'font-size: 20px; color: red'></i></td>"; //x
								echo "<p>A helyes válasz: </p>";
								echo '<input type = "text" value = "'.substr($adatok["megoldas"][$i],0,$index).'" readonly>';//itt >
							}
						}
						if ($adatok["valaszok_szama"][$i] > 1){
							$maxpont = $maxpont + 2;
							echo "<p>Az ön válasza:</p>";
							echo '<input type = "text" name = "'.$i.'_2"' ; 
							if (isset($_POST[$i."_2"])) echo ' value = "'.$_POST[$i."_2"].'" readonly>';//itt >
							else echo ">";  //és ide kell az else is a > miatt
							
							if (isset($_POST["submit"])){
								$index2 = $index+1;
								while(substr($adatok["megoldas"][$i],$index2,1) != "	" && $index2 < strlen($adatok["megoldas"][$i])){
									$index2++;
								}
								if ($_POST[$i."_2"] == substr($adatok["megoldas"][$i],$index+1,$index2-$index-1)){
									echo "<td><i class = 'fas fa-check' style = 'font-size: 20px; color: green'></i></td>";//pipa
									$eredmeny=$eredmeny+2;
								}else 
									echo "<td><i class = 'fas fa-times' style = 'font-size: 20px; color: red'></i></td>"; //x
								echo "<p>A helyes válasz: </p>";
								echo '<input type = "text" value = "'.substr($adatok["megoldas"][$i],$index+1,$index2-$index-1).'" readonly>';//itt >
							}
						}
						if ($adatok["valaszok_szama"][$i] > 2){
							$maxpont = $maxpont + 2;
							echo "<p>Az ön válasza:</p>";
							echo '<input type = "text" name = "'.$i.'_3"' ;
							if (isset($_POST[$i."_2"])) echo ' value = "'.$_POST[$i."_3"].'" readonly>';//itt >
							else echo ">";  //és ide kell az else is a > miatt
							if (isset($_POST["submit"])){
								$index3 = $index2+1;
								while((substr($adatok["megoldas"][$i],$index3,1) != " ") && ($index3 < strlen($adatok["megoldas"][$i]))){
									$index3++;
								}
								if ($_POST[$i."_3"] == substr($adatok["megoldas"][$i],$index2+1,$index3-$index2)) {
									echo "<td><i class = 'fas fa-check' style = 'font-size: 20px; color: green'></i></td>";//pipa
									$eredmeny=$eredmeny+2;
								}
								else 
									echo "<td><i class = 'fas fa-times' style = 'font-size: 20px; color: red'></i></td>"; //x
								echo "<p>A helyes válasz: </p>";
								echo '<input type = "text" value = "'.substr($adatok["megoldas"][$i],$index2+1,$index3-$index2).'" readonly>';//itt >
							}
						}
						
					}
					echo "</div>";
				}
				echo "</div>";
				if(isset($_SESSION["mentve"]) && !isset($_GET["sajat_teszt"])){
					$ma=date("Y-m-d H:i:s");
					$sql="INSERT INTO eredmenyek(diak_id, feladat_nev, pontszam, szazalek, datum) VALUES (".$_SESSION["felhasznalo"].", '".$_GET["feladatsor"]."', ".$eredmeny.", ".round($eredmeny/$maxpont*100,2).", '".$ma."')";
					$result = $conn->query($sql);
				}
				echo "<div class = 'gombok'>";
					echo "<button type = 'button' onclick = 'elozo()' class = 'alap_gomb_kek'><< Vissza</button>";
					if (!isset($_POST["submit"]) && !isset($_SESSION["sajat"])){
						echo "<input type = 'submit' name = 'submit' value = 'Befejezés' id = 'submit' onclick = 'befejezes()' class = 'alap_gomb_piros'>";
						$_SESSION["mentve"]="2";
					}else{
						unset($_SESSION["mentve"]);
					}
					echo "<button type = 'button' onclick = 'kovetkezo()' class = 'alap_gomb_kek'>Tovább >></button>";
				echo "</div>";
				
				
				echo "</form>";
				
			?>
		</div>
		<div id  = "eredmeny" <?php if (isset($_POST["submit"])) echo "style = 'display: block'"?>>
			<?php 
					if(isset($_POST["submit"]) && isset($_SESSION["sajat_teszt"])){ //saját teszt esetén
						$adatok = $_SESSION["sajat_teszt"];
						unset($_SESSION["sajat_teszt"]);
					}
				
			?>
			<p>Az ön eredménye: </p>
			<span>
			<?php 
				if (isset($eredmeny)){
					echo round($eredmeny/$maxpont*100,2)." %";
				}
			?>
			</span><br>
			<button id = "oke">Oké <i class = "fas fa-check"></i></button>
		</div>

<script src='https://kit.fontawesome.com/a076d05399.js'></script>
<?php 
	include('../includes/overall/footer.php'); 
	include('../includes/overall/db_disconnect.php');
?>

<?php
	function valtozoKiir($valtozo){
		echo "<pre>";
		print_r($valtozo);
		echo "</pre>";
	}
	
	function getTemakorTablak($szintek){
		global $conn, $szintKriterium;
		$szintKriterium = "";
		for($i = 0; $i < 4; $i++){  //$i < 4 - 4 szint van
			if($szintek[$i]){
				$szintKriterium .= "feladat_szint = ".($i+1)." OR "; //feladat_szint - egy adott témakör táblában egy mező
			}
		}
		$szintKriterium = rtrim($szintKriterium," OR ");
		
		$temakorok_szama = 0;
		$temakor_tablak = [];
		if(isset($_POST["osszes_temakor"])){
			$sql = "SELECT * FROM temakorok"; 
			$result = $conn->query($sql);
			while($sor = $result->fetch_assoc()){
				//tábla létezésének ellenőrzése
				$sql = "SELECT * FROM ".$sor["temakor_tabla"]; 
				$probaResult = $conn->query($sql);
				if($conn->error){
					if($conn->errno != 1146){
						die("Váratlan hiba történt!");
					}
				}else{
					$sql = "SELECT * FROM ".$sor["temakor_tabla"]." WHERE ".$szintKriterium;
					$sor2 = $conn->query($sql);
					if($sor2->num_rows > 0){
						$temakor_tablak[$temakorok_szama]["tabla"] = $sor["temakor_tabla"];
						$temakor_tablak[$temakorok_szama]["feladatok"] = $sor2->fetch_all(MYSQLI_ASSOC);
						$temakorok_szama++;
					}
				}
			}
		}else{
			$sql = "SELECT * FROM temakorok"; 
			$result = $conn->query($sql);
			while($sor = $result->fetch_assoc()){
				$kulcs = "temakor_".$sor["id"];
				if(array_key_exists($kulcs,$_POST)){
					$sql = "SELECT * FROM ".$sor["temakor_tabla"]." WHERE ".$szintKriterium;
					$sor2 = $conn->query($sql);
					if($sor2->num_rows > 0){
						$temakor_tablak[$temakorok_szama]["tabla"] = $sor["temakor_tabla"];  //itt nem kell ellenőrizni a tábla meglétét, mert a jelölőnégyzetek úgy vannak összeállítva, hogy a létező táblákat veszik csak
						$temakor_tablak[$temakorok_szama]["feladatok"] = $sor2->fetch_all(MYSQLI_ASSOC);
						$temakorok_szama++;
					}
				}
			}
		}
		return $temakor_tablak;
	}
	
	function getSzintek(){
		$szintek = [false,false,false,false];
		if(isset($_POST["osszes_szint"])){
			$szintek = [true,true,true,true,];
		}else{
			for($i = 1; $i <= 4; $i++){
				$kulcs = "szint_".$i;
				if(array_key_exists($kulcs,$_POST)){
					$szintek[$i-1] = true;
				}
			}
		}
		return $szintek;
	}
	
	function feladatokLetrehozasaGombokKiirasa(){
		global $conn, $adatok, $db;
		echo "<div class = 'gombok'>";
		for($i = 0; $i < $db-1; $i++){
			$sql = "SELECT * FROM ".$_SESSION["sajat_teszt"][$i]["feladat"]["tabla_neve"]." WHERE id = ".$_SESSION["sajat_teszt"][$i]["feladat"]["feladat_id"];
			$result = $conn->query($sql);
			//valtozoKiir($_SESSION["sajat_teszt"][$i]);
			$row = $result->fetch_all(MYSQLI_ASSOC)[0];
			$adatok["feladat"][$i] = $row["feladat"];
			$adatok["id"][$i] = $row["id"];
			$adatok["feladat_tipusa"][$i] = $row["feladat_tipusa"];
			$adatok["valaszok_szama"][$i] = $row["valaszok_szama"];
			$adatok["megoldas"][$i] = $row["megoldas"];
			$adatok["szint"][$i] = $row["szint"];
			$adatok["temakor"][$i] = $row["temakor"];
			if (isset($_SESSION["sajat"]) && $_SESSION["sajat"] == 1){
				$_SESSION["beilleszt"][$i]["sorszam"] = $i+1;
				$_SESSION["beilleszt"][$i]["feladat"] = $adatok["feladat"][$i];
				$_SESSION["beilleszt"][$i]["feladat_tipusa"] =  $adatok["feladat_tipusa"][$i];
				$_SESSION["beilleszt"][$i]["valaszok_szama"] = $adatok["valaszok_szama"][$i];
				$_SESSION["beilleszt"][$i]["megoldas"] = $adatok["megoldas"][$i];
				$_SESSION["beilleszt"][$i]["szint"] = $adatok["szint"][$i];
				$_SESSION["beilleszt"][$i]["temakor"] = $adatok["temakor"][$i];
			}
			if ($i == 0)
				echo '<button class = "sorszam akt" onclick = "feladatvaltas(this)">'.($i+1).'</button>';
			else 
				echo '<button class = "sorszam" onclick = "feladatvaltas(this)">'.($i+1).'</button>';	
		}
		echo "</div>";
	}
?>
		