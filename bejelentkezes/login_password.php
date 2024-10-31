<?php 
	session_start();
	$_SESSION["title"] = "Bejelentkezés";
	$oldal = "login";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
?>
<?php
	if(!isset($_SESSION["email"])){
		header ("Location: login_username.php");
		exit();
	}
	if(isset($_SESSION["pw"]))
		unset($_SESSION["pw"]);
	if(isset($_SESSION["elkuldve"]))
		unset($_SESSION["elkuldve"]);
	
	$password="";
	$hibak=[];
	if(isset($_POST["bejelentkezes"])){
		$password=megtisztit($_POST["password"]);
		if(empty($password)){
			$hibak["password"]="Töltse ki a mezőt";
		}
		if(!empty($password) && !preg_match("/^[a-zA-Z|0-9|_]{8,16}$/",$password)){
			$hibak["password"]="A jelszó minimum 8, maximum 16 karakterből állhat, csak szám, betű és '_' karaktereket tartalmazhat.";
		}
		if(count($hibak)==0){
			$stmt=$conn->prepare("SELECT * FROM felhasznalok WHERE email=?");
			$stmt->bind_param("s",$_SESSION["email"]);
			$stmt->execute();
			$result=$stmt->get_result();
			if($result->num_rows>0){
				$row=$result->fetch_array();
				if($password==$row[4] || password_verify($password, $row[4])){
					$_SESSION["felhasznalo"]=$row[0];
					$_SESSION["rang"]=$row[3];
					$password="";
					header("Location: ../index.php");
					exit();
				}
				else{
					$hibak["password"]="Téves jelszót adott meg";
				}
			}
			$stmt->close();
			include('../includes/overall/db_disconnect.php');
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
				if(isset($hibak["password"])){
					echo "<div id='hiba'>";
					echo $hibak["password"];
					echo '<button id="bezar" onclick="bezar()">x</button>';
					echo "</div>";
				}?>
		</div>
		<form action="" method="post">
		<div id="fejlec">
			<h2>Bejelentkezés</h2>
			<h3>Adja meg a jelszavát</h3>
		</div>
			<input type="password" name="password" id="password" value="<?php echo $password?>" autocomplete="off" placeholder="Jelszó"><br>
			<div id="p_alatt">
				<a href="change_password.php" id="valtoztat">Elfelejtette a jelszavát?</a>
				<span id="mutat_szoveg">Jelszó megjelenítése:</span><span id="megmutat"><input type="checkbox" onclick="show()" id="mutat"></span>
			</div>
			<input type="submit" name="bejelentkezes" value="Bejelentkezés">
			<div id="reg_div2">
				<a href="login_username.php" id="back">Vissza</a>
			</div>
		</form>
	</body>
</html>
<?php
	include('../includes/overall/footer.php'); 
 ?>