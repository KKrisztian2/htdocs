var uj_znoteszt_gomb = document.getElementById("alap_gomb");
uj_znoteszt_gomb.addEventListener("click",ujznoteszt);

var ujznoteszt_div = document.getElementById("ujznoteszt_div");

var bezar_gomb = document.getElementById("bezar_gomb");
bezar_gomb.addEventListener("click",bezaras);

var hiba_div = document.getElementById("hibajelzes");

var torles_uzenet = document.getElementById("torles_uzenet");
var torles_main = document.getElementById("torles_main");
torles_uzenet.style.display = "none";
var torles_uzenet_bezar = document.getElementById("torles_uzenet_bezar");
var torles_gombok = document.getElementsByClassName("torles");
for (var i = 0; i < torles_gombok.length; i++){
	torles_gombok[i].addEventListener("click",megerosites);
}

function ujznoteszt(){
	ujznoteszt_div.style.display = "block";
}

function bezaras(){
	ujznoteszt_div.style.display = "none";
}

function szerkesztes(){
	window.location.assign("../feladatok/feladatok.php");
}

function megerosites(){
	torles_main.style.display = "block";
	torles_uzenet.style.display = "block";
	var torles_div = document.createElement("div");
	torles_uzenet.appendChild(torles_div);
	var evszam = this.parentElement.parentElement.parentElement.parentElement.firstChild.nodeValue;
	var torles_gyerek = this.parentElement.parentElement.firstElementChild.innerHTML;
	var torles_uzenet_p = document.createElement("p");
	torles_uzenet_p.innerHTML = "Biztosan törölni szeretné a következőt: "+evszam+" "+torles_gyerek+"?";
	torles_div.appendChild(torles_uzenet_p);
	var torles_megerosit_gomb = document.createElement("a");
	torles_megerosit_gomb.href = "torles.php?ev=";
	torles_megerosit_gomb.href += evszam;
	torles_megerosit_gomb.href += "&znonev=";
	torles_megerosit_gomb.href += torles_gyerek;
	torles_megerosit_gomb.classList.add("alap_gomb", "torles_megerosit_gomb");
	torles_megerosit_gomb.innerHTML = "Megerősít";
	var torles_megse_gomb = document.createElement("button");
	torles_megse_gomb.classList.add("alap_gomb", "torles_megse_gomb");
	torles_megse_gomb.innerHTML = "Mégse";
	torles_div.appendChild(torles_megerosit_gomb);
	torles_div.appendChild(torles_megse_gomb);
	torles_megse_gomb.addEventListener("click", torles_megse);
	torles_uzenet_bezar.addEventListener("click", torles_uzenet_bezaras);
}

function torles_uzenet_bezaras(){
	torles_uzenet.style.display = "none";
	this.parentElement.children[1].innerHTML = "";
	torles_uzenet.removeChild(this.parentElement.children[1]);
	torles_main.style.display = "none";

}
function torles_megse(){
	torles_uzenet.style.display = "none";
	torles_uzenet.removeChild(this.parentElement);
	torles_main.style.display = "none";

}

window.onclick = function(event) {
	if (event.target == torles_main) {
	  torles_main.style.display = "none";
	  torles_uzenet.children[1].remove();
	 }
}


function hiba_div_eltunes(){
	hiba_div.style.display = "none";
}

if (hiba_div.firstElementChild.innerHTML != ""){
	hiba_div.style.display = "block";
	setTimeout(hiba_div_eltunes,3000);
}
