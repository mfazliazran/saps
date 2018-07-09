function papar_rekod(flg,kod,cnt,year,jpep,key){
	//'JPN','$kodnegeri','$bil','$tahun','$jpep'
if (flg=="JPN"){
   if (document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display=="block"){
     document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display="none";
     return;
   }
   document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display="block";
   document.getElementById("div_detail_ppd_"+kod+"_"+cnt).innerHTML = "<img src='images/loading.gif'>";
}
if (flg=="PPD"){
   if (document.getElementById("div_detail_sek_"+kod+"_"+cnt).style.display=="block"){
     document.getElementById("div_detail_sek_"+kod+"_"+cnt).style.display="none";
     return;
   }
   document.getElementById("div_detail_sek_"+kod+"_"+cnt).style.display="block";
   document.getElementById("div_detail_sek_"+kod+"_"+cnt).innerHTML = "<img src='images/loading.gif'>";
}

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
           if (flg=="JPN"){
 		     document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display="block";
 		     document.getElementById("div_detail_ppd_"+kod+"_"+cnt).innerHTML = ajaxRequest.responseText;
           }
		   if (flg=="PPD"){
 		     document.getElementById("div_detail_sek_"+kod+"_"+cnt).style.display="block";
 		     document.getElementById("div_detail_sek_"+kod+"_"+cnt).innerHTML = ajaxRequest.responseText;
           }
		}
	}//jenis,year
//alert ("ajax/papar_rekod.php?flg=" + flg +'&kod=' + kod +'&jenis=' + jenis +'&year=' + year);
	if(key=="2"){//linus
		ajaxRequest.open("GET", "ajax/papar_stat_ppd_linus.php?flg=" + flg +'&kod=' + kod +'&year=' + year +'&jpep=' + jpep , true);
	}else{
		ajaxRequest.open("GET", "ajax/papar_stat_ppd.php?flg=" + flg +'&kod=' + kod +'&year=' + year +'&jpep=' + jpep , true);
	}
	ajaxRequest.send(null); 
}