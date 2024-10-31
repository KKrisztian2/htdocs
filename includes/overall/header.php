<?php 

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php
			echo $_SESSION["title"];
		 ?></title>
		<meta charset = "utf-8">
		<?php
			if($oldal!="fooldal")
				echo '<link rel = "stylesheet" type = "text/css" href = "../css/alap.css">';
			else if($oldal=="fooldal")
				echo '<link rel = "stylesheet" type = "text/css" href = "css/alap.css">';
		?>
		<script src='https://kit.fontawesome.com/a076d05399.js'></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<?php 
			if(isset($oldal)){
			switch($oldal){
				case "login": 
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/login.css'>";
					break;
				case "znotesztletrehoz": 
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/znotesztletrehozStyle.css'>";
					break;
				case "fooldal":
					echo "<link rel = 'stylesheet' type = 'text/css' href = 'css/fooldalStyle.css'>";
					break;
				case "znotesztek":
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/znotesztStyle.css'>";
					break;
				case "sajatteszt":
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/sajattesztStyle.css'>";
					break;
				case "eredmenyek":
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/eredmenyekStyle.css'>";
					break;
				case "csoport_letrehoz":
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/csoportletrehozStyle.css'>";
					break;
				case "szerkesztofeladatok":
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/szerkesztofeladatokStyle.css'>";
					break;
				case "szerkeszto":
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/szerkesztoindexStyle.css'>";
					break;
				case "szerkesztodiak":
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/szerkesztodiakStyle.css'>";
					break;
				case "sajat_tesztek":
					echo "<link rel = 'stylesheet' type = 'text/css' href = '../css/sajattesztekStyle.css'>";
					break;
			}
				if($oldal != "szerkesztodiak" && !isset($_SESSION["sajat"]) && isset($_SESSION["sajat_teszt"])){
					unset($_SESSION["sajat_teszt"]);
				}

			}
		?>
	</head>	
	<body>
		<header>
			<nav>
				<ul>
					<?php
						if($oldal!="fooldal"){
							if(isset($_SESSION["rang"]) && $_SESSION["rang"] == "diak"){
								echo '<li><a href  = "../index.php">Főoldal</a></li>';
								echo '<li><a href  = "../znotesztek/index.php">Nyilvános tesztek</a></li>';
								echo '<li><a href  = "../sajat_teszt/index.php">Generált teszt</a></li>';
								echo '<li><a href  = "../sajat_tesztek/index.php">Kiadott feladatok</a></li>';
								echo '<li><a href  = "../eredmenyek/index.php">Eredmények</a></li>';
								echo '<li><a href  = "../csoportok_letrehozasa/index.php">Csoportok</a></li>';
								echo '<li><a href  = "../bejelentkezes/logout.php">Kijelentkezés</a></li>';
							}
							if(!isset($_SESSION["rang"])){
								echo '<li><a href  = "../index.php">Főoldal</a></li>';
								echo '<li><a href  = "../znotesztek/index.php">Nyilvános tesztek</a></li>';
								echo '<li><a href  = "../sajat_teszt/index.php">Generált teszt</a></li>';
								echo '<li><a href  = "../bejelentkezes/login_username.php">Bejelentkezés</a></li>';
							}
							if(isset($_SESSION["rang"]) && $_SESSION["rang"] == "tanar"){
								echo '<li><a href  = "../index.php">Főoldal</a></li>';
								echo '<li><a href  = "../znotesztek/index.php">Nyilvános tesztek</a></li>';
								echo '<li><a href  = "../sajat_teszt/index.php">Generált teszt</a></li>';
								echo '<li><a href  = "../znoteszt_letrehozasa/index.php">Feladatok kezelése</a></li>';
								echo '<li><a href  = "../sajat_tesztek/index.php">Feladatok kiadása</a></li>';
								echo '<li><a href  = "../eredmenyek/index.php">Eredmények</a></li>';
								echo '<li><a href  = "../csoportok_letrehozasa/index.php">Csoportok</a></li>';
								echo '<li><a href  = "../regisztracio/create_acc1.php">Regisztráció</a></li>';
								echo '<li><a href  = "../bejelentkezes/logout.php">Kijelentkezés</a></li>';
							}
						}
						else if($oldal=="fooldal"){
							if(isset($_SESSION["rang"]) && $_SESSION["rang"] == "diak"){
								echo '<li><a href  = "index.php">Főoldal</a></li>';
								echo '<li><a href  = "znotesztek/index.php">Nyilvános tesztek</a></li>';
								echo '<li><a href  = "sajat_teszt/index.php">Generált teszt</a></li>';
								echo '<li><a href  = "sajat_tesztek/index.php">Kiadott feladatok</a></li>';
								echo '<li><a href  = "eredmenyek/index.php">Eredmények</a></li>';
								echo '<li><a href  = "csoportok_letrehozasa/index.php">Csoportok</a></li>';
								echo '<li><a href  = "bejelentkezes/logout.php">Kijelentkezés</a></li>';
							}
							if(!isset($_SESSION["rang"])){
								echo '<li><a href  = "index.php">Főoldal</a></li>';
								echo '<li><a href  = "znotesztek/index.php">Nyilvános tesztek</a></li>';
								echo '<li><a href  = "sajat_teszt/index.php">Generált teszt</a></li>';
								echo '<li><a href  = "bejelentkezes/login_username.php">Bejelentkezés</a></li>';
							}
							if(isset($_SESSION["rang"]) && $_SESSION["rang"] == "tanar"){
								echo '<li><a href  = "index.php">Főoldal</a></li>';
								echo '<li><a href  = "znotesztek/index.php">Nyilvános tesztek</a></li>';
								echo '<li><a href  = "sajat_teszt/index.php">Generált teszt</a></li>';
								echo '<li><a href  = "znoteszt_letrehozasa/index.php">Feladatok kezelése</a></li>';
								echo '<li><a href  = "sajat_tesztek/index.php">Feladatok kiadása</a></li>';
								echo '<li><a href  = "eredmenyek/index.php">Eredmények</a></li>';
								echo '<li><a href  = "csoportok_letrehozasa/index.php">Csoportok</a></li>';
								echo '<li><a href  = "regisztracio/create_acc1.php">Regisztráció</a></li>';
								echo '<li><a href  = "bejelentkezes/logout.php">Kijelentkezés</a></li>';
							}
						}

					?>
					
				</ul>
			</nav>
		</header>
		<section>
