<?php
	session_start();
	$_SESSION["title"] = "Regisztráció";
	$oldal = "login";
	include('../includes/overall/header.php');
	$nev="";
	$hibak=[];
	if(isset($_SESSION["rang"]) && $_SESSION["rang"]=="tanar"){
		if(isset($_POST["tovabb"])){
			$nev=megtisztit($_POST["nev"]);
			if(empty($nev)){
				$hibak["nev"]="Töltse ki a mezőt.";
			}
			if(!empty($nev) && !preg_match("/^[A-Za-zÉÁŰŐÚÖÜÓéáűőúóüö\s]+$/", $nev)){
				$hibak["nev"]="Hibásan töltötte ki a mezőt.";
			}
			if(count($hibak)==0){
				$_SESSION["new_nev"]=$nev;
				$nev="";
				header("Location: create_acc2.php");
				exit();
			}
		}
	}
	else{
		header("Location: ../index.php");
		exit();
	}
	function megtisztit($adat){
		$adat=trim($adat);
		$adat=stripslashes($adat);
		$adat=htmlspecialchars($adat);
		return $adat;
	}
	
	if(isset($_SESSION["elkuldve"])){
		include('../includes/overall/db_connect.php');
		$jelszo = password_hash($_SESSION["pw"],PASSWORD_DEFAULT);
		echo $jelszo,
		$sql="INSERT INTO felhasznalok (nev, email, rang, jelszo) VALUES ('".$_SESSION["new_nev"]."', '".$_SESSION["new_email"]."', '".$_SESSION["new_rang"]."', '".$jelszo."')";
		$conn->query($sql);
		unset($_SESSION["new_nev"]);
		unset($_SESSION["new_email"]);
		unset($_SESSION["new_rang"]);
		unset($_SESSION["elkuldve"]);
		unset($_SESSION["pw"]);
		include('../includes/overall/db_disconnect.php');
		header("Location: ../index.php");
		exit();
	}


?>

<!DOCTYPE html>
<html>
	<head>
		<title>Regisztráció</title>
		<meta charset="utf-8">
	</head>
	<body>
		<div id="doboz">
			<?php
				if(isset($hibak["nev"])){
					echo "<div id='hiba'>";
					echo $hibak["nev"];
					echo '<button id="bezar" onclick="bezar()">x</button>';
					echo "</div>";
				}?>
		</div>
		<form action="" method="post">
		<div id="fejlec">
			<h2>Regisztráció</h2>
			<h3>Regisztráljon egy új fiókot</h3>
		</div>
			<input type="text" name="nev" value="<?php echo $nev?>" autocomplete="off" placeholder="Név"><br>
			<input type="submit" name="tovabb" value="Tovább">
		</form>
		<script src="login.js"></script>
	</body>
</html>
<?php
	include('../includes/overall/footer.php');
?>