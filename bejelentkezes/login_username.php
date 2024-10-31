<?php
		session_start();
		$_SESSION["title"] = "Bejelentkezés";
		$oldal = "login";
		include('../includes/overall/header.php');
		include('../includes/overall/db_connect.php');
?>
<?php
	$user="";
	$hibak=[];
	if(isset($_POST["tovabb"])){
		$user=megtisztit($_POST["user"]);
		if(empty($user)){
			$hibak["user"]="Töltse ki a mezőt.";
		}
		else if(!empty($user) && filter_var($user, FILTER_VALIDATE_EMAIL)){
			$email=$user;
		}
		else if(!empty($user)){
			$hibak["user"]="Hibásan töltötte ki a mezőt.";
		}
		if(count($hibak)==0){
			if(isset($email)){
				$stmt=$conn->prepare("SELECT * FROM felhasznalok WHERE email=?");
				$stmt->bind_param("s",$user);
			}
			$stmt->execute();
			$result=$stmt->get_result();
			if($result->num_rows>0){
				$row=$result->fetch_array();
				$_SESSION["email"]=$user;
				$user="";
				header("Location: login_password.php");
				exit();
			}
			else{
				$hibak["user"]="Ilyen adatokkal rendelkező felhasználó nem regisztrált";
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
				if(isset($hibak["user"])){
					echo "<div id='hiba'>";
					echo $hibak["user"];
					echo '<button id="bezar" onclick="bezar()">x</button>';
					echo "</div>";
				}?>
		</div>
		<form action="" method="post">
		<div id="fejlec">
			<h2>Bejelentkezés</h2>
			<h3>Jelentkezzen be a fiókjába</h3>
		</div>
			<input type="text" name="user" value="<?php echo $user?>" autocomplete="off" placeholder="E-mail-cím"><br>
			<input type="submit" name="tovabb" value="Tovább">
		</form>
	</body>
</html>
<?php
	include('../includes/overall/footer.php'); 
 ?>