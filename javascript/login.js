function show(){
	var input = document.getElementById("password");
	if(input.type=="password"){
		input.type="text";
	}
	else{
		input.type="password";
	}
}
function bezar(){
	var div=document.getElementById("hiba");
	div.style.display="none";
}