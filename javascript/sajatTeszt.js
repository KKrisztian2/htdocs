var feladatokSzama = 0;

var elso_form = document.getElementById("elso");
var masodik_form = document.getElementById("masodik");
var harmadik_form = document.getElementById("harmadik");

var tovabb_gomb = document.getElementsByClassName("alap_gomb");

for (var i = 0; i < tovabb_gomb.length-1; i++) {
	tovabb_gomb[i].addEventListener("click",kovetkezo);
	console.log(tovabb_gomb[i].parentElement);
}

var osszes_temakor = document.getElementById("osszes_temakor");
console.log(osszes_temakor);
osszes_temakor.addEventListener("change",temakorok_checkboxok); 
var temakorok = document.querySelectorAll("#elso > input[type='checkbox']:not(:first-of-type)");
console.log(temakorok);
for (var i = 0; i < temakorok.length; i++){
	temakorok[i].addEventListener("change",osszes_temakor_checked);
}

var osszes_szint = document.getElementById("osszes_szint");
console.log(osszes_szint);
osszes_szint.addEventListener("change",szintek_checkboxok); 
var szintek = document.querySelectorAll("#masodik > input[name^='szint']"); ///////////////////////
/*console.log(szintek);
for (var i = 0; i < szintek.length; i++){
	szintek[i].addEventListener("change",osszes_szint_checked);
}*/

function temakorok_checkboxok(){
	if (this.checked == false){
		for (var i = 0; i < temakorok.length; i++){
			temakorok[i].removeAttribute("disabled","disabled");
		}
	}else if (this.checked == true){
		for (var i = 0; i < temakorok.length; i++){
			temakorok[i].setAttribute("disabled","disabled");
		}
	}
}

function kovetkezo(){
	this.parentElement.style.display = "none";
	this.parentElement.nextElementSibling.style.display = "block";
}

function szintek_checkboxok(){
	var szintek = document.querySelectorAll("#masodik > input[name^='szint']")
	if (this.checked == false){
		for (var i = 0; i < szintek.length; i++){
			szintek[i].removeAttribute("disabled","disabled");
		}
	}else if (this.checked == true){
		for (var i = 0; i < szintek.length; i++){
			szintek[i].setAttribute("disabled","disabled");
		}
	}
}

function osszes_temakor_checked(){
	console.log(temakorok.length);
	var db = 0;
	for (var i = 0; i < temakorok.length; i++){
		if (temakorok[i].checked == true){
			db++;	
		}
	}
	if (db == temakorok.length){
		osszes_temakor.checked = true;
	}else {
		osszes_temakor.checked = false;
	}
	
}

function osszes_szint_checked(){
	var db = 0;
	for (var i = 0; i < szintek.length; i++){
		if (szintek[i].checked == true){
			db++;
		}
	}
	if (db == szintek.length){
		osszes_szint.checked = true;
	}else {
		osszes_szint.checked = false;
	}
}

///////////////////////////////////////////
var temakor_tovabb = document.getElementById("temakor_tovabb");
temakor_tovabb.addEventListener("click",szintekLekerese);
var szint_tovabb = document.getElementById("szint_tovabb");
szint_tovabb.addEventListener("click",feladatokSzamaLekeres);

var szintek;

function szintekLekerese(){
	var keres = szintKeres();
	ajax("../AJAX/index.php","action=szintek&"+keres,szintekMegjelenit);
}

function szintKeres(){
	var elso = document.getElementById("elso");
	var temak = elso.getElementsByTagName("input");
	var db = temak.length-1;
	var keres = "";
	for(var i = 1; i <= db; i++){
		if(osszes_temakor.checked){
			if(keres != "")
				keres += "&";
			keres += temak[i].id+"=1";
		}else{
			
			if(temak[i].checked){
				if(keres != "")
					keres += "&";
				keres += temak[i].id+"=1";
			}
		}
	}
	return keres;
}

function szintekMegjelenit(json){
	szintek = JSON.parse(json);
	console.log(szintek);
	var masodik = document.getElementById("masodik");
	var checkbox = masodik.getElementsByTagName("input");
	var j = 0;
	for(var i = 1; i < 5; i++){
		if(szintek[j] == i){
			j++;
		}else{
			checkbox[i].previousElementSibling.style.display = "none";
			checkbox[i].nextElementSibling.style.display = "none";
			checkbox[i].style.display = "none";
		}
	}
}


function feladatokSzamaLekeres(){
	var keres2 = "";
	for(i in szintek){
		if(osszes_szint.checked){
			if(keres2 != "")
				keres2 += "&";
			keres2 += "szint_"+szintek[i]+"=1";
		}else{
			var szint = document.getElementById("szint_"+szintek[i]);
			if(szint.checked){
				if(keres2 != "")
					keres2 += "&";
				keres2 += "szint_"+szintek[i]+"=1";
			}
		}
	}
	var keres = szintKeres();
	ajax("../AJAX/index.php","action=feladatokSzama&"+keres+"&"+keres2,feladatokSzamaBeallit);
	console.log("action=feladatokSzama&"+keres+"&"+keres2);
}

function feladatokSzamaBeallit(szam){
	feladatokSzama = szam;
	console.log("Feladatok maximális szama: "+feladatokSzama);
}

function feladatSzamaEllenorzes(){
	if(feladatokSzama < parseInt(document.getElementById("szam").value)){
		document.getElementById("warning").style.display = "block";
		document.getElementById("warning_text").innerHTML = "A megadott kritériumok alapján maximum csak "+feladatokSzama+" feladat választható!";
	}
	
	return feladatokSzama >= parseInt(document.getElementById("szam").value);
}


var close_dialog = document.getElementById("close_dialog");
close_dialog.addEventListener("click",dialogBezar);

function dialogBezar(){
	var dialogAblak = this.parentElement.parentElement;
	dialogAblak.removeAttribute("style");
}

///////////AJAX///////////
function ajax(url, adat, fuggveny){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(xhttp.readyState == 4 && xhttp.status == 200){
			fuggveny(xhttp.responseText);
		}
	};
	xhttp.open("POST",url,true);
	xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhttp.send(adat);
}

function hiba(uz){
	console.log(uz);
}