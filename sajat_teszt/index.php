<?php 
	$oldal = "sajatteszt";
	session_start();
	$_SESSION["title"] = "Generált teszt";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
	$tomb = [];
	$db = 0;
	$sql = "SELECT * FROM temakorok"; 
	$result = $conn->query($sql);
	while($row = mysqli_fetch_array($result,MYSQLI_NUM)){
		$sql = "SELECT * FROM ".$row[2];   //$row[2] - temakor_tabla 
		$probaResult = $conn->query($sql);
		if($conn->error){
			if($conn->errno != 1146){
				die("Váratlan hiba történt!");
			}
		}else{
			$tomb[$db] = $row;    //$tomb[i][0] - id, $tomb[i][1] - temakor_neve
			$db++;                
		}
	}
	if (isset($_GET["sajat"])){
		$_SESSION["sajat"] = $_GET["sajat"];
 	}
	else {
			if (isset($_SESSION["sajat"]))
				unset($_SESSION["sajat"]);
		}
?>
			<h3>Saját teszt létrehozása</h3>
			<p>Generált teszt indításához töltse ki az alábbi űrlapot: </p>
				<form method = "post" action = "../feladatok/diak.php?sajat_teszt=sajat_teszt" onsubmit = "return feladatSzamaEllenorzes()">
				<div id = "elso">
					<label for = "checkbox">Összes témakör</label>
					<input type = "checkbox" name = "osszes_temakor" id = "osszes_temakor" checked><br>
					<?php 
						for($i=0;$i<$db; $i++){
							$sum = $i+1;
							echo "<label for = 'temakor_".$tomb[$i][0]."'>".$tomb[$i][1]."</label>";
							echo "<input type = 'checkbox' name = 'temakor_".$tomb[$i][0]."' id ='temakor_".$tomb[$i][0]."' disabled> <br>";
						}
						
					?>
					<button type = "button" class = "alap_gomb" id = "temakor_tovabb">Tovább</button>
				</div>
				
				<div id = "masodik">
					<label for = "">Összes szint</label>
					<input type = "checkbox" name = "osszes_szint" id = "osszes_szint" checked><br>
					<label for = "szint_1">I. szint</label>
					<input type = "checkbox" name = "szint_1" id = "szint_1" disabled><br>
					<label for = "szint_2">II. szint</label>
					<input type = "checkbox" name = "szint_2" id = "szint_2" disabled><br>
					<label for = "szint_3">III. szint</label>
					<input type = "checkbox" name = "szint_3" id = "szint_3" disabled><br>
					<label for = "szint_4">IV. szint</label>
					<input type = "checkbox" name = "szint_4" id = "szint_4" disabled><br>
					<button type = "button" class = "alap_gomb" id = "szint_tovabb">Tovább</button>
				</div>
				
				<div id = "harmadik">
					<label for = "szam">Hány feladatot szeretne?</label><br>
					<input type = "number" name = "szam" id = "szam" min = "1"  value = "1">
					<input type = "submit" name = "inditas" class = "alap_gomb" value = "Teszt indítása">
				</div>
			</form>
			
			<div id = "warning">
				<div class = "dialog_header">
					Figyelem!
					<span id = "close_dialog">+</span>
				</div>
				<div class = "dialog_body">
					<p id = "warning_text"></p>
				</div>
			</div>
<?php 
	include('../includes/overall/footer.php'); 
	include('../includes/overall/db_disconnect.php');
?>