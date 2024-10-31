<?php
	session_start();
	$_SESSION["title"] = "Jelszó változtatása";
	$oldal = "login";
	include('../includes/overall/header.php');
	include('../includes/overall/db_connect.php');
	$password="";
	$hibak=[];
	if(isset($_SESSION["pw"])){
		if(isset($_POST["megvaltoztat"])){
			$password=megtisztit($_POST["password"]);
			if(empty($password)){
				$hibak["password"]="Töltse ki a mezőt";
			}
			if(!empty($password) && !preg_match("/^[a-zA-Z|0-9|_]{8,16}$/",$password)){
				$hibak["password"]="A jelszó minimum 8, maximum 16 karakterből állhat, csak szám, betű és '_' karaktereket tartalmazhat.";
			}
			if(count($hibak)==0){
				$stmt=$conn->prepare("UPDATE felhasznalok SET jelszo=? WHERE email=?");
				$password = password_hash($password, PASSWORD_DEFAULT);
				$stmt->bind_param("ss", $password, $_SESSION["email"]);
				$stmt->execute();
				$password="";
				unset($_SESSION["pw"]);
				$stmt=$conn->prepare("SELECT * FROM felhasznalok WHERE email=?");
				$stmt->bind_param("s",$_SESSION["email"]);
				$stmt->execute();
				$result=$stmt->get_result();
				if($result->num_rows>0){
					$row=$result->fetch_array();
					$_SESSION["felhasznalo"]=$row[0];
					$_SESSION["rang"]=$row[3];
				}
				include('../includes/overall/db_disconnect.php');
				header("Location: ../index.php");
				exit();
			}
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
			<h2>Jelszó megváltoztatása</h2>
			<p>Kérem adja meg az új jelszót</p>
		</div>
			<input type="password" name="password" id="password" value="<?php echo $password?>" autocomplete="off" placeholder="Jelszó"><br>
			<div id="p_alatt">
				<div></div>
				<span id="mutat_szoveg">Jelszó megjelenítése:</span><span id="megmutat"><input type="checkbox" onclick="show()" id="mutat"></span>
			</div>
			<input type="submit" name="megvaltoztat" value="Jelszó megváltoztatása">
			<div id="reg_div2">
				<a href="login_password.php" id="back">Vissza</a>
			</div>
		</form>
	</body>
</html>
<?php
	include('../includes/overall/footer.php'); 
 ?>