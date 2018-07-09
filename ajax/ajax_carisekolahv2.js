// JavaScript Document
function senarai_PPD(kodnegeri){
	//alert(kodnegeri);
	document.getElementById("error_msg2").style.display = "none"; 
	document.getElementById("txtDaerah").disabled = false;
	var ajaxRequest;  // The variable that makes Ajax possible!
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
			document.getElementById("txtSekolah").selectedIndex = 0;
			document.getElementById("txtSekolah").disabled = true;
 		   document.getElementById("txtDaerah").innerHTML = ajaxRequest.responseText;
	   }
	}
	ajaxRequest.open("GET", "../ajax/senarai_ppd.php?kodnegeri=" + kodnegeri, true);
	ajaxRequest.send(null); 
}

function senarai_Sekolah(kodnegeri, kodppd){
	//alert(kodnegeri);
	var ajaxRequest;  // The variable that makes Ajax possible!
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
 		   document.getElementById("divSekolah").innerHTML = ajaxRequest.responseText;
	   }
	}
	ajaxRequest.open("GET", "../ajax/ddl_senarai_sekolah.php?kodnegeri=" + kodnegeri + "&kodppd=" + kodppd, true);
	ajaxRequest.send(null); 
}