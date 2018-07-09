// JavaScript Document
function senarai_sekolah(kodnegeri,namasekolah){
	//alert(namasekolah);
	if(namasekolah=="" && namasekolah !="xCarianx"){
		alert("Sila masukkan Nama Sekolah.");
		document.getElementById("txtCariSekolah").focus();
		return false;
	}
	if(namasekolah=="xCarianx"){
		namasekolah = "";	
	}
	var ajaxRequest;  // The variable that makes Ajax possible!
	//document.getElementById("txtKodJenisRuang").value="";
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
           s=ajaxRequest.responseText;
		   document.getElementById("divCariSekolah").style.display = "block";
		   document.getElementById("txtCariSekolah").focus();
		   document.getElementById("divSenaraiSekolah").style.display = "block";
 		   document.getElementById("divSenaraiSekolah").innerHTML = s;
	   }
	}
	ajaxRequest.open("GET", "../ajax/senarai_sekolah.php?kodnegeri=" + kodnegeri + "&namasek=" + namasekolah, true);
	ajaxRequest.send(null); 
}