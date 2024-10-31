<?php
	session_start();
	$_SESSION["title"] = "Regisztráció";
	$oldal = "login";
	include('../includes/overall/header.php');
	$email="";
	$hibak=[];
	if(isset($_SESSION["rang"]) && $_SESSION["rang"]=="tanar" && isset($_SESSION["new_nev"])){
		if(isset($_POST["tovabb"])){
			$email=megtisztit($_POST["email"]);
			if(empty($email)){
				$hibak["email"]="Töltse ki a mezőt.";
			}
			if(!empty($email) && filter_var($user, FILTER_VALIDATE_EMAIL)){
				$hibak["email"]="Hibásan töltötte ki a mezőt.";
			}
			if(count($hibak)==0){
				$_SESSION["new_email"]=$email;
				$email="";
				header("Location: create_acc3.php");
				exit();
			}
		}
	}
	else{
		header("Location: create_acc1.php");
		exit();
	}
	function megtisztit($adat){
		$adat=trim($adat);
		$adat=stripslashes($adat);
		$adat=htmlspecialchars($adat);
		return $adat;
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
				if(isset($hibak["email"])){
					echo "<div id='hiba'>";
					echo $hibak["email"];
					echo '<button id="bezar" onclick="bezar()">x</button>';
					echo "</div>";
				}?>
		</div>
		<form action="" method="post">
		<div id="fejlec">
			<h2>Regisztráció</h2>
			<h3>Regisztráljon egy új fiókot</h3>
		</div>
			<input type="text" name="email" value="<?php echo $email?>" autocomplete="off" placeholder="E-mail"><br>
			<input type="submit" name="tovabb" value="Tovább">
		</form>
	</body>
</html>
<?php
	include('../includes/overall/footer.php');
?>