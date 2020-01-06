//window.alert("Näe, ta töötab!");
//console.log("Näe, ta töötab!");

window.onload = function(){
	document.getElementById("submitPic").disabled = true;
	document.getElementById("notice").innerHTML = "Vali üleslaadimiseks pilt!";
	document.getElementById("fileToUpload").addEventListener("change", checkSize);
}

function checkSize(){
	//console.log(document.getElementById("fileToUpload").files[0]);
	if(document.getElementById("fileToUpload").files[0].size <= 2500000){
		document.getElementById("submitPic").disabled = false;
		document.getElementById("notice").innerHTML = "";
	} else {
		document.getElementById("notice").innerHTML = "Valitud pilt on liiga suur!";
		document.getElementById("submitPic").disabled = true;
	}
}