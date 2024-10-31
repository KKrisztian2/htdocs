var error = document.getElementById("generalError");
if(error.innerHTML == ""){
	error.style.display = "none";
}

var regOK = document.getElementById("registOK");
if(regOK.innerHTML == ""){
		regOK.style.display = "none";	
}

setTimeout(regOKOff, 3000);
function regOKOff(){
		regOK.style.display = "none";
		console.log("regOKOff fuggvény lefutott!");
}


var tipus = document.getElementById("tipus");
tipus.addEventListener("change",inputok);
console.log("Tipus: "+tipus.value);


var helyes_teszt = document.getElementById("helyes_teszt");
var helyes_input1 = document.getElementById("helyes1");
var helyes_input2 = document.getElementById("helyes2");
var helyes_input3 = document.getElementById("helyes3");
var tabla = document.querySelectorAll("table")[0];
var radio = document.querySelectorAll("table input");

var valaszok_szama = document.getElementById("db");
valaszok_szama.addEventListener("change",valaszok);

if (tipus.value != ""){
	inputok();
}
if (valaszok_szama.value != ""){
	valaszok();
}

function inputok(){
	if (tipus.value == "1"){
		helyes_teszt.style.display = "inline-block";
		helyes_teszt.removeAttribute("disabled","disabled");
		helyes_teszt.previousElementSibling.style.display = "block";
		helyes_input1.style.display = "none";
		helyes_input1.setAttribute("disabled","disabled");
		helyes_input1.previousElementSibling.style.display = "none";
		helyes_input2.style.display = "none";
		helyes_input2.setAttribute("disabled","disabled");
		helyes_input3.style.display = "none";
		helyes_input3.setAttribute("disabled","disabled");
		tabla.style.display = "none";
		for (var i = 0; i < radio.length; i++){
			radio[i].setAttribute("disabled","disabled");
		}
		valaszok_szama.setAttribute("disabled","disabled");
		valaszok_szama.style.display = "none";
		valaszok_szama.previousElementSibling.style.display = "none";
		valaszok_szama.value = "";
		valaszok_szama.previousElementSibling.style.display = "none";
	}else if (tipus.value == "2"){
		tabla.style.display = "block";
		for (var i = 0; i < radio.length; i++){
			radio[i].removeAttribute("disabled","disabled");
		}
		helyes_input1.previousElementSibling.style.display = "none";
		helyes_input1.style.display = "none";
		helyes_input1.setAttribute("disabled","disabled");
		helyes_input2.style.display = "none";
		helyes_input2.setAttribute("disabled","disabled");
		helyes_input3.style.display = "none";
		helyes_input3.setAttribute("disabled","disabled");
		helyes_teszt.previousElementSibling.style.display = "none";
		helyes_teszt.style.display = "none";
		helyes_teszt.setAttribute("disabled","disabled");
		helyes_teszt.previousElementSibling.style.display = "none";
		valaszok_szama.setAttribute("disabled","disabled");
		valaszok_szama.style.display = "none";	
		valaszok_szama.previousElementSibling.style.display = "none";
		valaszok_szama.value = "";
		valaszok_szama.previousElementSibling.style.display = "none";
	}else if (tipus.value == "3"){
		helyes_input1.previousElementSibling.style.display = "none";
		helyes_input1.style.display = "none";
		helyes_input2.style.display = "none";
		helyes_input3.style.display = "none";
		helyes_teszt.previousElementSibling.style.display = "none";
		helyes_teszt.style.display = "none";
		helyes_teszt.setAttribute("disabled","disabled");
		helyes_input1.setAttribute("disabled","disabled");
		helyes_input2.setAttribute("disabled","disabled");
		helyes_input3.setAttribute("disabled","disabled");
		valaszok_szama.previousElementSibling.style.display = "block";
		valaszok_szama.removeAttribute("disabled","disabled");
		valaszok_szama.style.display = "inline-block";
		tabla.style.display = "none";
		for (var i = 0; i < radio.length; i++){
			radio[i].setAttribute("disabled","disabled");
		}
	}
	else if (tipus.value == "4" || tipus.value == ""){
		helyes_input1.previousElementSibling.style.display = "none";
		helyes_input1.style.display = "none";
		helyes_input2.style.display = "none";
		helyes_input3.style.display = "none";
		helyes_input2.setAttribute("disabled","disabled");
		helyes_input1.setAttribute("disabled","disabled");
		helyes_input3.setAttribute("disabled","disabled");
		helyes_teszt.previousElementSibling.style.display = "none";
		helyes_teszt.style.display = "none";
		helyes_teszt.setAttribute("disabled","disabled");
		tabla.style.display = "none";
		for (var i = 0; i < radio.length; i++){
			radio[i].setAttribute("disabled","disabled");
		}
		valaszok_szama.setAttribute("disabled","disabled");
		valaszok_szama.style.display = "none";
		valaszok_szama.previousElementSibling.style.display = "none";
		valaszok_szama.value = "";
	}
} 

function valaszok(){
	if (valaszok_szama.value == ""){
			helyes_input1.previousElementSibling.style.display = "none";
			helyes_input1.style.display = "none";
			helyes_input2.style.display = "none";
			helyes_input3.style.display = "none";
			helyes_input1.setAttribute("disabled","disabled");
			helyes_input2.setAttribute("disabled","disabled");
			helyes_input3.setAttribute("disabled","disabled");
	}else if (valaszok_szama.value == "1"){
			helyes_input1.previousElementSibling.innerHTML = "Helyes válasz: ";
			helyes_input1.previousElementSibling.style.display = "block";
			helyes_input1.style.display = "inline-block";
			helyes_input2.style.display = "none";
			helyes_input3.style.display = "none";
			helyes_input1.removeAttribute("disabled")
			helyes_input2.setAttribute("disabled","disabled");
			helyes_input3.setAttribute("disabled","disabled");
		}else if (valaszok_szama.value == "2"){
			helyes_input1.previousElementSibling.innerHTML = "Helyes válaszok: ";
			helyes_input1.previousElementSibling.style.display = "block";
			helyes_input1.style.display = "inline-block";
			helyes_input2.style.display = "inline-block";
			helyes_input3.style.display = "none";
			helyes_input1.removeAttribute("disabled");
			helyes_input2.removeAttribute("disabled");
			helyes_input3.setAttribute("disabled","disabled");
		}else if (valaszok_szama.value == "3"){
			helyes_input1.previousElementSibling.innerHTML = "Helyes válaszok: ";
			helyes_input1.previousElementSibling.style.display = "block";
			helyes_input1.style.display = "inline-block";
			helyes_input2.style.display = "inline-block";
			helyes_input3.style.display = "inline-block";
			helyes_input1.removeAttribute("disabled");
			helyes_input2.removeAttribute("disabled");
			helyes_input3.removeAttribute("disabled");
		}
}

