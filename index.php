<?php 
	session_start();
	$_SESSION["title"] = "Főoldal";
	$oldal = "fooldal";
	include('includes/overall/header.php');
	unset($_SESSION["pw"]);
	unset($_SESSION["elkuldve"]);
?>
				
		<div id = "leiras">
			<p>

			</p>
		</div>
<?php
	include('includes/overall/footer.php'); 
 ?>