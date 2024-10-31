<?php
	session_start();
	if(!isset($_SESSION["elkuldve"])){
		$_SESSION["elkuldve"]="elkuldve";
		include('../includes/overall/db_connect.php');
		
			$cim=$_SESSION["new_email"];
			$nev=$_SESSION["new_nev"];

		
		function generateRandomString(){
			$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$randomString = "";
			for ($i=0; $i<16; $i++) {
				$randomString .= $characters[rand(0, strlen($characters)-1)];
			}
			return $randomString;
		}
		$str=generateRandomString();
		$_SESSION["pw"]=$str;
		date_default_timezone_set('Etc/UTC');
		require('PHPMailer-master/src/PHPMailer.php');
		require('PHPMailer-master/src/SMTP.php');
		$mail = new PHPMailer\PHPMailer\PHPMailer();
		$mail->isSMTP();
		$mail->SMTPDebug = 2;
		$mail->Debugoutput = 'html';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		/*$mail->Username = "email";
		$mail->Password = "jelszó";
		$mail->setFrom('email', 'név'); 
		$mail->addReplyTo('email', 'név');*/
																												$mail->Username = "znooldalmkk@gmail.com";
																												$mail->Password = "zno2020_";
																												$mail->setFrom('znooldalmkk@gmail.com', 'Gyakorlat'); 
																												$mail->addReplyTo('znooldalmkk@gmail.com', 'Gyakorlat');
		$mail->addAddress($cim, $nev);
		$mail->CharSet = "UTF-8";
		$mail->Subject = 'Ideiglenes jelszó';
		$mail->msgHTML("<h3>Üdvözletem, ".$nev."!</h3><br>"."<h3>Önt regisztrálták az http://elod.kmf.uz.ua:98/ZNO/ oldalon.<br> Ideiglenes jelszó: ".$str."</h3>");
		include('../includes/overall/db_disconnect.php');
		if (!$mail->send()) {
			echo "Mailer Error: ".$mail->ErrorInfo;
		}
		else
			echo "<script>window.location.assign('create_acc1.php')</script>";
	}
	else{
		header("Location: ../fooldal/index.php");
		exit();
	}
?>