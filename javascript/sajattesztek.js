var teszt_nevek = document.querySelectorAll(".teszt h4");
var megjeleno = document.getElementsByClassName("megjeleno");

var kiad_gomb = document.getElementsByClassName("kiad_gomb");
var div=document.getElementById("ki");
div.style.display="none";
var kiadas = document.getElementById("kiad");
kiadas.style.display = "none";
var bezar_gomb = document.getElementById("bezar_gomb");
bezar_gomb.addEventListener("click",bezar);
bezar_gomb.style.display="none";
for(x of kiad_gomb){
	x.addEventListener("click",feladat_kiad);
}
for (var i = 0; i < megjeleno.length; i++){
	megjeleno[i].addEventListener("mousemove",fuggveny);
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

function kiadva(){
	document.getElementById("uzenet").style.display = "none";
}


function uzenet(adat){
	
	document.getElementById("uzenet").style.display = "block";
	document.getElementById("uzenet").innerHTML = "<p>"+adat+"</p>";
	setTimeout(kiadva,3000);
}

var teszt_kiadas_gomb = document.getElementById("kiad");
kiad.addEventListener("click",teszt_kiad);

function bezar(){
	div.style.display = "none";
}

var teszt_nev = "";

function feladat_kiad(){
	console.log(div);
	div.style.display = "block";
	kiad.style.display="block";
	div.children[1].style.display = "flex";
	bezar_gomb.style.display = "block";
	console.log(this);
	console.log(this.previousElementSibling);
	console.log(this.previousElementSibling.firstElementChild);
	teszt_nev = this.previousElementSibling.previousElementSibling.firstElementChild.innerHTML.trim();
}

for(x of teszt_nevek){
	x.addEventListener("mousemove",fuggveny);
	x.addEventListener("wheel",fuggveny);
	x.addEventListener("mouseenter",megjelenit);
	x.addEventListener("mouseleave",eltuntet);
}

function fuggveny(e){
	var index;
	for (var i = 0; i < teszt_nevek.length; i++){
		if (teszt_nevek[i] == this){
			index = i;
			break;
		}
	}
	megjeleno[index].style.left = e.pageX+5 + "px";
	megjeleno[index].style.top = e.pageY-10 + "px";
}

function megjelenit(e){
	var index;
	for (var i = 0; i < teszt_nevek.length; i++){
		if (teszt_nevek[i] == this){
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
	for (var i = 0; i < teszt_nevek.length; i++){
		if (teszt_nevek[i] == this){
			index = i;
			break;
		}
	}
	megjeleno[index].style.left = e.pageX+5 + "px";
	megjeleno[index].style.top = e.pageY-10 + "px";
	megjeleno[index].style.display = null;
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
var nevsor = document.getElementById("nevsor");
var nevsor_li = nevsor.getElementsByTagName("li");
var nevsor_ul = nevsor.getElementsByTagName("ul")[0];


var csoport = document.getElementById("csoport");
var csoport_li = csoport.getElementsByTagName("li");
var csoport_ul = csoport.getElementsByTagName("ul")[0];
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

var gomb_bedob = document.getElementById("bedob");
gomb_bedob.addEventListener("click",hozzaad);
var gomb_kidob = document.getElementById("kidob");
gomb_kidob.addEventListener("click",visszavon);
var input = document.getElementById("kereses");
input.addEventListener("keyup",szures);


function teszt_kiad(){
	var csoportok = this.parentElement.previousElementSibling.firstElementChild.children;
	var keres_string = "";
	for (var i = 0; i < csoportok.length; i++){
		keres_string += ("csoport"+i+"="+csoportok[i].innerHTML);
		if (i != csoportok.length-1){
			keres_string += "&";
		}
	}
	console.log( "teszt_nev="+teszt_nev+"&"+keres_string);
	this.parentElement.parentElement.parentElement.style.display = "none";
	
	keres("../sajat_tesztek/valasz.php","teszt_nev="+teszt_nev+"&"+keres_string, uzenet);
	
}



