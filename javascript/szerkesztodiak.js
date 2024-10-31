function feladatvaltas(gomb){
	console.log(gomb);
	 var sorszam = gomb.innerHTML-1;
	 var feladatok = document.getElementsByClassName("feladat");
	 console.log(feladatok);
	 var gomb2 = document.getElementsByClassName("akt")[0];
	 gomb2.classList.remove("akt");
	 gomb.classList.add("akt");
	 for (var i = 0; i < feladatok.length; i++){
		 if (i == sorszam){
			feladatok[i].style.display = "block";
			feladatok[i].nextElementSibling.style.display = "block";
			feladatok[i].classList.add("aktiv");
		 }
		else {
			feladatok[i].style.display = "none";
			feladatok[i].nextElementSibling.style.display = "none";
			feladatok[i].classList.remove("aktiv");
		}
	 }
}

function kovetkezo(){
	var feladat = document.getElementsByClassName("aktiv")[0];
	var gombok = document.getElementsByClassName("sorszam");
	var gomb = document.getElementsByClassName("akt")[0];
	if (gomb.innerHTML != gombok[gombok.length-1].innerHTML){
		gomb.classList.remove("akt");
		console.log(feladat.nextElementSibling);
		console.log(feladat.nextElementSibling.nextElementSibling);
		console.log(feladat.nextElementSibling.nextElementSibling.nextElementSibling);
		gomb.nextElementSibling.classList.add("akt");
		feladat.style.display = "none";
		feladat.nextElementSibling.style.display = "none";
		feladat.classList.remove("aktiv");
		feladat.nextElementSibling.nextElementSibling.classList.add("aktiv");
		feladat.nextElementSibling.nextElementSibling.style.display = "block";
		console.log(feladat.nextElementSibling.nextElementSibling.classList);
		feladat.nextElementSibling.nextElementSibling.nextElementSibling.style.display = "block";
	}
}

function elozo(){
	var feladat = document.getElementsByClassName("aktiv")[0];
	var gomb = document.getElementsByClassName("akt")[0];
	if (gomb.innerHTML != "1"){
		gomb.classList.remove("akt");
		gomb.previousElementSibling.classList.add("akt");
		feladat.style.display = "none";
		feladat.nextElementSibling.style.display = "none";
		feladat.classList.remove("aktiv");
		feladat.previousElementSibling.style.display = "block";
		feladat.previousElementSibling.previousElementSibling.style.display = "block";
		feladat.previousElementSibling.previousElementSibling.classList.add("aktiv");
	}
	
}

function befejezes(){
	var eredmeny = document.getElementById("eredmeny");
	eredmeny.style.display = "block";
}

var oke = document.getElementById("oke");
oke.addEventListener("click",oke_f);

function oke_f(){
	window.location.assign("../index.php");
}