	</section>
		<?php 
			if(isset($oldal)){
				switch($oldal){
					case "login": 
						echo "<script src= '../javascript/login.js'></script>";
						break;
					case "znotesztletrehoz": 
						echo "<script src= '../javascript/znotesztletrehoz.js'></script>";
						break;
					case "znotesztek":
						echo "<script src= '../javascript/znoteszt.js'></script>";
						break;
					case "sajatteszt":
						echo "<script src= '../javascript/sajatTeszt.js'></script>";
						break;
					case "csoport_letrehoz":
						echo "<script src= '../javascript/csoportletrehoz.js'></script>";
						break;
					case "szerkesztodiak":
						echo "<script src= '../javascript/szerkesztodiak.js'></script>";
						echo "<script src='../tinymce/js/tinymce/tinymce.min.js' referrerpolicy='origin'></script>";
						echo "<script src='../tinymce/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image'></script>";
						break;
					case "szerkeszto":
						echo "<script src= '../javascript/szerkesztoindex.js'></script>";
						break;
					case "sajat_tesztek":
						echo "<script src= '../javascript/sajattesztek.js'></script>";
						break;
					case "szerkesztofeladatok":
						echo "<script src='../tinymce/js/tinymce/tinymce.min.js' referrerpolicy='origin'></script>";
						echo "<script src='../tinymce/js/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image'></script>";
						break;
				}

			}
		?>
	</body>
</html>