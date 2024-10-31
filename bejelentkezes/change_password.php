<?php
	session_start();
	$_SESSION["title"] = "Jelszó változtatása";
	$oldal = "login";
	include('../includes/overall/header.php');
	if(!isset($_SESSION["elkuldve"])){
		header("Location: send_email.php");
		exit();
	}
	$pwd="";
	$hibak=[];
	if(isset($_POST["tovabb"])){
		$pwd=megtisztit($_POST["kod"]);
		if($pwd==$_SESSION["pw"]){
			$pwd="";
			header("Location: change_password2.php");
			exit();
		}
		else{
			$hibak["pass"]="Helytelen az ideiglenes jelszó";
		}
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
		<title>Login</title>
		<meta charset="utf-8">
	</head>
	<body>
		<div id="doboz">
			<?php
				if(isset($hibak["pass"])){
					echo "<div id='hiba'>";
					echo $hibak["pass"];
					echo '<button id="bezar" onclick="bezar()">x</button>';
					echo "</div>";
				}?>
		</div>
		<form action="" method="post">
		<div id="fejlec">
			<h2>Jelszó megváltoztatása</h2>
			<p>A fiókhoz kapcsolódó e-mail címre küldtünk egy ideiglenes jelszót, kérem adja meg azt</p>
		</div>
			<input type="text" name="kod" id="kod" value="<?php echo $pwd?>" autocomplete="off" placeholder="Ideiglenes jelszó"><br>
			<input type="submit" name="tovabb" value="Tovább">
			<div id="reg_div2">
				<a href="login_password.php" id="back">Vissza</a>
			</div>
		</form>
	</body>
</html>
<?php
	include('../includes/overall/footer.php'); 
 ?>