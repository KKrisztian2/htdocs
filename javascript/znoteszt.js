var elso_cellak = document.querySelectorAll("#kesz_feladatsorok tr > td:first-of-type");

for (var i = 0; i < elso_cellak.length; i++){
	elso_cellak[i].addEventListener("click",feladatsorok);
}

function feladatsorok(){
	var sor = this.parentElement;
	var cellak = sor.children;
	console.log(cellak);
	console.log(cellak[1]);
	if (cellak[1].style.display == "table-cell"){
		for (var i = 1; i < cellak.length; i++){
			cellak[i].style.display = "none";
		}
		
	}else{
		
		for (var i = 1; i < cellak.length; i++){
			cellak[i].style.display = "table-cell";
		}
	}
	
}
