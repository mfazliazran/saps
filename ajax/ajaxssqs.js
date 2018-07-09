function papar_rekod(flg,kod,cnt,jenis,year,status,jenissek,ting,jpep){
	//alert('flg - '+flg+' kod - '+kod+' cnt - '+cnt+' jenis - '+jenis+' year - '+year+' status - '+status+' jenissek - '+jenissek);

//alert('flg='+flg);
 if (flg=="JPN"){
   if (document.getElementById("div_detail_negeri_"+kod+"_"+cnt).style.display=="block"){
     document.getElementById("div_detail_negeri_"+kod+"_"+cnt).style.display="none";
     return;
   }
   document.getElementById("div_detail_negeri_"+kod+"_"+cnt).style.display="block";
   document.getElementById("div_detail_negeri_"+kod+"_"+cnt).innerHTML = "<img src='images/loading.gif'>";
}
else if (flg=="JPN2"){
   if (document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display=="block"){
     document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display="none";
     return;
   }
   document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display="block";
   document.getElementById("div_detail_ppd_"+kod+"_"+cnt).innerHTML = "<img src='images/loading.gif'>";
}

else if (flg=="PTPB"){
   if (document.getElementById("div_detail_ptpb_"+kod+"_"+cnt).style.display=="block"){
     document.getElementById("div_detail_ptpb_"+kod+"_"+cnt).style.display="none";
     return;
   }
   document.getElementById("div_detail_ptpb_"+kod+"_"+cnt).style.display="block";
   document.getElementById("div_detail_ptpb_"+kod+"_"+cnt).innerHTML = "<img src='images/loading.gif'>";
}
else if (flg=="PPD"){
   if (document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display=="block"){
     document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display="none";
     return;
   }
   document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display="block";
   document.getElementById("div_detail_ppd_"+kod+"_"+cnt).innerHTML = "<img src='images/loading.gif'>";
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
 		     document.getElementById("div_detail_negeri_"+kod+"_"+cnt).style.display="block";
 		     document.getElementById("div_detail_negeri_"+kod+"_"+cnt).innerHTML = ajaxRequest.responseText;
           }
           else if (flg=="PTPB"){
 		     document.getElementById("div_detail_ptpb_"+kod+"_"+cnt).style.display="block";
 		     document.getElementById("div_detail_ptpb_"+kod+"_"+cnt).innerHTML = ajaxRequest.responseText;
           }
           else if (flg=="PPD"){
 		     document.getElementById("div_detail_ppd_"+kod+"_"+cnt).style.display="block";
 		     document.getElementById("div_detail_ppd_"+kod+"_"+cnt).innerHTML = ajaxRequest.responseText;
           }

		}
	}//jenis,year
//alert ("ajax/papar_rekod.php?flg=" + flg +'&kod=' + kod +'&jenis=' + jenis +'&year=' + year);
	ajaxRequest.open("GET", "ajax/papar_rekod.php?flg=" + flg +'&kod=' + kod +'&jenis=' + jenis +'&year=' + year+'&status=' + status +'&jenissek=' + jenissek+'&ting=' + ting+'&jpep=' + jpep , true);
	ajaxRequest.send(null); 
}



function papar_soalan(role,dimensi){
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
 		   document.getElementById("div_soalan").innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "ajax/soalan.php?role=" + role +'&dimensi=' + dimensi , true);
	ajaxRequest.send(null); 
}

function cari_pkg(negeri){
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
 		   document.getElementById("div_pkg").innerHTML = ajaxRequest.responseText;
		}
	}
	ajaxRequest.open("GET", "ajax/pkg.php?negeri=" + negeri, true);
	ajaxRequest.send(null); 
}
