var nevsor = document.getElementById("nevsor");
var nevsor_li = nevsor.getElementsByTagName("li");
var nevsor_ul = nevsor.getElementsByTagName("ul")[0];

var csoport = document.getElementById("csoport");
var csoport_li = csoport.getElementsByTagName("li");
var csoport_ul = csoport.getElementsByTagName("ul")[0];

var input = document.getElementById("kereses");
input.addEventListener("keyup",szures);

var gomb_bedob = document.getElementById("bedob");
gomb_bedob.addEventListener("click",hozzaad);
var gomb_kidob = document.getElementById("kidob");
gomb_kidob.addEventListener("click",visszavon);

var uj_csoport = document.getElementById("alap_gomb");
if(uj_csoport !== null){
	uj_csoport.addEventListener("click",csoport_letrehozasa)
}


var bezar_gomb = document.getElementById("bezar_gomb");
console.log(bezar_gomb);
bezar_gomb.addEventListener("click",bezar);
bezar_gomb.style.display="none";
var letrehoz_gomb = document.getElementById("letrehoz");
letrehoz_gomb.style.display = "none";
letrehoz_gomb.addEventListener("click",csoport_letrehoz);

var ablak = document.getElementById("alap_gomb");

var csoportnevek = document.querySelectorAll(".csoport h3");
var megjeleno = document.getElementsByClassName("megjeleno");

for (var i = 0; i < megjeleno.length; i++){
	megjeleno[i].addEventListener("mousemove",fuggveny);
}

for(x of csoportnevek){
	x.addEventListener("mousemove",fuggveny);
	x.addEventListener("wheel",fuggveny);
	x.addEventListener("mouseenter",megjelenit);
	x.addEventListener("mouseleave",eltuntet);
}

function fuggveny(e){
	var index;
	for (var i = 0; i < csoportnevek.length; i++){
		if (csoportnevek[i] == this){
			index = i;
			break;
		}
	}
	megjeleno[index].style.left = e.pageX+5 + "px";
	megjeleno[index].style.top = e.pageY-10 + "px";
}

function megjelenit(e){
	var index;
	for (var i = 0; i < csoportnevek.length; i++){
		if (csoportnevek[i] == this){
			index = i;
			break;
		}
	}
	megjeleno[index].style.left = e.pageX+5 + "px";
	megjeleno[index].style.top = e.pageY-10 + "px";
	megjeleno[index].style.display = "block";
}

function eltuntet(e){
	var index;
	for (var i = 0; i < csoportnevek.length; i++){
		if (csoportnevek[i] == this){
			index = i;
			break;
		}
	}
	megjeleno[index].style.left = e.pageX+5 + "px";
	megjeleno[index].style.top = e.pageY-10 + "px";
	megjeleno[index].style.display = null;
}

for(x of nevsor_li){
	x.kattint = false;
	x.addEventListener("click",kattintaskor);
}
for(x of csoport_li){
	x.kattint = false;
	x.addEventListener("click",kattintaskor);
}
function kattintaskor(){
	this.kattint = !this.kattint;
	if(this.kattint == true){
		this.style.backgroundColor = "#80ff84";
	}else{
		this.style.backgroundColor = "#ccc";
	}
}
function hozzaad(){
	for(i=0;i<nevsor_li.length;i++){
		if(nevsor_li[i].kattint == true){
			nevsor_li[i].kattint = false;
			nevsor_li[i].style.backgroundColor = "#ccc";
			csoport_ul.appendChild(nevsor_li[i]);
			i--;
		}
	}
}
function visszavon(){
	for(i=0;i<csoport_li.length;i++){
		if(csoport_li[i].kattint == true){
			csoport_li[i].kattint = false;
			csoport_li[i].style.backgroundColor = "#ccc";
			nevsor_ul.appendChild(csoport_li[i]);
			i--;
		}
	}
}

function szures(){
	for(var i=0;i<nevsor_li.length;i++){
			if(nevsor_li[i].innerHTML.toLowerCase().startsWith(input.value.trim().toLowerCase())){
				nevsor_li[i].style.display="block";
			}
			else{
				nevsor_li[i].style.display="none";
			}
		
	}
}

function elougro_csoport(){
	console.log(this);
	var nevsor = this.nextElementSibling;
	console.log(nevsor);
	nevsor.style.display = "block";
}

function csoport_eltuno(){
	console.log(this);
	var nevsor = this.getElementsByTagName("div")[0];
	console.log(nevsor);
	nevsor.style.display = "none";
	for (var i = 0; i < nevsor.children.length; i++){
		nevsor.children[i].style.display = "none";
	}
}

function bezar(){
	console.log(this);
	this.parentElement.style.display = "none";
}

function csoport_letrehozasa(){
	this.nextElementSibling.style.display = "block";
	letrehoz_gomb.style.display="block";
	this.nextElementSibling.children[1].style.display = "flex";
	bezar_gomb.style.display = "block";
}

function keres(url, adat, fuggveny){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if (xhttp.readyState == 4 && xhttp.status == 200){
			fuggveny(xhttp.responseText);
		}
	}
	xhttp.open("POST",url,true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send(adat);
}

function letrehozva(){
	document.getElementById("uzenet").style.display = "none";
}


function uzenet(adat){
	console.log(adat);
	document.getElementById("uzenet").style.display = "block";
	document.getElementById("uzenet").innerHTML = "<p>Csoport létrehozása megtörtént!</p>";
	setTimeout(letrehozva,2000);
}

function csoport_letrehoz(){
	console.log(this);
	console.log(this.parentElement);
	console.log(this.parentElement.previousElementSibling);
	console.log(this.parentElement.previousElementSibling.children[2]);
	var csoport_nev = this.parentElement.previousElementSibling.children[2].value.trim();
	var diakok = this.parentElement.previousElementSibling.children[3].innerHTML;
	if (csoport_nev != "" && diakok.length != 0){
		var diakok = this.parentElement.previousElementSibling.lastElementChild.children;
		console.log(diakok);
		var keres_string = "";
		for (var i = 0; i < diakok.length; i++){
			console.log(diakok[i].innerHTML);
			keres_string += ("diak"+i+"="+diakok[i].innerHTML);
			if (i != diakok.length-1){
				keres_string += "&";
			}
		}
		
		console.log( "csoport_nev="+csoport_nev+"&"+keres_string);
		
		this.parentElement.parentElement.parentElement.style.display = "none";
		
		keres("../csoportok_letrehozasa/valasz.php","csoport_nev="+csoport_nev+"&"+keres_string, uzenet);
	}
}

