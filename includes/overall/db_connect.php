<?php
	$conn=new mysqli("localhost","root","","szofttech");
	//$conn=new mysqli("sql100.epizy.com","epiz_30530589","CZPJeVxiqLpqd","epiz_30530589_szofttech");
		if($conn->connect_error){
			die($conn->connect_error);
		}
		mysqli_query($conn, "SET NAMES utf8");
?>