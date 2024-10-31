<?php
	session_start();
	if(isset($_SESSION["error"])){
		if($_SESSION["error"] == false)
			$message = $_SESSION["mesage"];
		else
			$error_message = $_SESSION["error_message"];
		unset($_SESSION["error"]);
	}
	$oldal = "szerkeszto";
	$_SESSION["title"] = "Szerkesztő";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
	$sql = "SELECT * FROM temakorok";
	$temakorok = $conn->query($sql);
	$sql = "SELECT * FROM feladat_tipus";
	$feladat_tipus = $conn->query($sql);
	
?>

		<main>
			<?php
				if(isset($_SESSION["edit_task"])){
					$_SESSION["sorszam"]=$_SESSION["edit_task"]["sorszam"];
				}
				else{
					$sql = "SELECT * FROM ".$_GET["feladatsor"];
					$_SESSION["sorszam"] = $conn->query($sql);
					$_SESSION["sorszam"] = $_SESSION["sorszam"]->num_rows;
					$_SESSION["sorszam"] = $_SESSION["sorszam"]+1;
				}
					echo "<p>Feladat sorszama: ".$_SESSION["sorszam"]."</p>";
			?>
			<div style = "display: flex; flex-wrap: wrap;">
				<a href = "../feladatok/feladatok.php?feladatsor=<?php echo $_GET["feladatsor"]?> "class = "alap_gomb">Feladatok</a>
				<span class = "error general" id = "generalError"><?php if(isset($error_message)) echo $error_message; ?></span>
				<span class = "registOK" id = "registOK"><?php if(isset($message)) echo $message?></span>
			</div>
			<form action = <?php echo "save.php?feladatsor=".$_GET["feladatsor"] ?> method = "post">
				<textarea name = "szoveg" rows = "22">
					<?php 
						if(isset($_SESSION["edit_task"])){ 
							echo $_SESSION["edit_task"]["feladat"];
						}
					?>
				</textarea>
				<br>
				<div id = "plusz_informaciok">
					<label for = "tipus">Feladat típusa:</label>
					<select type = "text" name = "tipus" id = "tipus">
						<option value = "">Válasszon</option>
						<?php
							if(isset($feladat_tipus)){
								$adatok = $feladat_tipus->fetch_all(MYSQLI_NUM);
								foreach($adatok as $sor){
									if(isset($_SESSION["edit_task"]["feladat_tipusa"]) && $_SESSION["edit_task"]["feladat_tipusa"] == $sor[0]){
										echo "<option value = \"".$sor[0]."\" selected>".$sor[1]."</option>";
									}else
										echo "<option value = \"".$sor[0]."\">".$sor[1]."</option>";
								}
							}
						?>
					</select>
					<label for = "temakor">Feladat témaköre:</label>
					<select name = "temakor" id = "temakor">
						<option value = "">Válasszon</option>
						<?php
							if(isset($temakorok)){
								$adatok = $temakorok->fetch_all(MYSQLI_NUM);
								foreach($adatok as $sor){
									if(isset($_SESSION["edit_task"]["temakor"]) && $_SESSION["edit_task"]["temakor"] == $sor[0])
										echo "<option value = \"".$sor[0]."\" selected>".$sor[1]."</option>";
									else
										echo "<option value = \"".$sor[0]."\">".$sor[1]."</option>";
								}
							}
						?>
					</select>
					<label for = "szint">Feladat nehézségi szintje:</label>
					<select name = "szint" id = "szint">
						<option value = "">Válasszon</option>
						<?php
							$romai_szamok = ["I","II","III","IV"];
							foreach($romai_szamok as $kulcs => $szam){
								if(isset($_SESSION["edit_task"]["szint"]) && $_SESSION["edit_task"]["szint"] == ($kulcs+1))
									echo "<option value = '".($kulcs+1)."' selected>".$szam."</option>";
								else
									echo "<option value = '".($kulcs+1)."'>".$szam."</option>";
							}
						?>
					</select>
				
					<!--Megoldások kiválogatása-->
					<?php
						$megoldas = ["","",""];
						if(isset($_SESSION["edit_task"]["megoldas"])){
							switch($_SESSION["edit_task"]["feladat_tipusa"]){
								case 1: 
									$megoldas[0] = $_SESSION["edit_task"]["megoldas"];
									break;
								case 2: 
								case 3: 
									$megoldas = explode("\t",$_SESSION["edit_task"]["megoldas"]); //muszáj hogy az indexek számok legyenek 0-tól ...
									break;
							}
							//print_r($megoldas);
						}
					?>
					
					<table>
						<?php
							$lista1 = ["1","2","3","4"];                       //muszáj hogy az indexek számok legyenek 0-tól ...
							$lista2 = ["A","B","C","D","E"];                   //muszáj hogy az indexek számok legyenek 0-tól ...
							$nevek = ["elso","masodik","harmadik","negyedik"]; //muszáj hogy az indexek számok legyenek 0-tól ...
							echo "<tr>";
							echo "<th></th>";
							foreach($lista2 as $ertek){
								echo "<th>".$ertek."</th>";
							}
							echo "</tr>";
							foreach($lista1 as $index => $ertek1){
								echo "<tr>";
								echo "<th>".$ertek1."</th>";
								foreach($lista2 as $ertek2){
									if(isset($_SESSION["edit_task"]["feladat_tipusa"]) && $_SESSION["edit_task"]["feladat_tipusa"] == "2" && $ertek2 == $megoldas[$index]){
										echo "<td><input type = 'radio' value = '".$ertek2."' name = '".$nevek[$index]."' checked></td>";
									}else
										echo "<td><input type = 'radio' value = '".$ertek2."' name = '".$nevek[$index]."'></td>";
								}
								echo "</tr>";
							}
						?>
						
					</table>
					<label for = "db">Válaszok száma:</label>
					<select name = "db" id = "db">
						<option value = "">Válasszon</option>
						<?php
							$valaszok_szama = 3;
							for($i = 1; $i <= $valaszok_szama; $i++){
								if(isset($_SESSION["edit_task"]["valaszok_szama"]) && $_SESSION["edit_task"]["valaszok_szama"] == $i)
									echo "<option value = '".$i."' selected>".$i."</option>";
								else
									echo "<option value = '".$i."'>".$i."</option>";
							}
						?>
					</select>
					<label for = "helyes_teszt">Helyes válasz:</label>
					<select name = "helyes_teszt" id = "helyes_teszt">
						<option value = "">Válasszon</option>
						<?php
							$valaszok = ["A","B","C","D","E"];
							for($i = 0; $i < 5; $i++){
								if(isset($_SESSION["edit_task"]["megoldas"]) && $_SESSION["edit_task"]["szint"] == 1 && $_SESSION["edit_task"]["megoldas"]==$valaszok[$i])
									echo "<option value = '".$valaszok[$i]."' selected>".$valaszok[$i]."</option>";
								else
									echo "<option value = '".$valaszok[$i]."'>".$valaszok[$i]."</option>";
							}
						?>
					</select>
					<label for = "helyes1">Helyes válaszok:</label>
					<input type = "text" name = "helyes1" id = "helyes1" placeholder = "" value = "<?php echo $megoldas[0]?>">
					<input type = "text" name = "helyes2" id = "helyes2" placeholder = "" value = "<?php echo $megoldas[1]?>"><br>
					<input type = "text" name = "helyes3" id = "helyes3" placeholder = "" value = "<?php echo $megoldas[2]?>"><br>
				</div>
				<input type = "submit" name = "submit" id = "mentes" value = "Mentés" class = "alap_gomb">
				
				<?php
					if(isset($_SESSION["edit_task"])){
						unset($_SESSION["edit_task"]);
					}
				?>
			</form>
		</main>
		</section>
		<script src="../tinymce/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
	    <script src="../tinymce/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image"></script>
	    <script>tinymce.init({ 
								selector:'textarea' ,
								menu: {
									view: { title: 'View', items: 'code | visualaid visualchars visualblocks | preview ' },
									insert: { title: 'Insert', items: 'image link inserttable | charmap hr | nonbreaking ' }
								},
								plugins : 'tiny_mce_wiris table image code hr nonbreaking link lists advlist charmap visualchars visualblocks preview',
								toolbar: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | tiny_mce_wiris_formulaEditor | tiny_mce_wiris_formulaEditorChemistry',
								menubar: 'file edit view insert format table',
								advlist_bullet_styles: 'circle disc square',
								advlist_number_styles: 'default lower-alpha lower-greek lower-roman upper-alpha upper-roman'
						   });
		</script>
	
<?php 
	include("../includes/overall/footer.php"); 
	include('../includes/overall/db_disconnect.php');
?>