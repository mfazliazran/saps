<!--
//Browser Support Code

function edit_markah(nokp,cnt,markah,bil){

	var ajaxRequest;  // The variable that makes Ajax possible!
    document.getElementById("divMarkah"+cnt).style.display ="block";
    document.getElementById("divLabelMarkah"+cnt).style.display ="none";
    document.getElementById("divMarkah"+cnt).innerHTML = "<img src='loading.gif'>";
    for(i=0;i<bil;i++){
	  if (i!=cnt){
        document.getElementById("divLabelMarkah"+i).style.display ="block";
        document.getElementById("divMarkah"+i).innerHTML ="";
	  }
    }
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
		   var arr=s.split("|");
 		   document.getElementById("divMarkah"+cnt).innerHTML = s;
	   }
	}
	//echo "<input name=\"ting\" type=\"hidden\" id=\"ting\" value=\"$ting\">\n";
	//echo "<input name=\"kelas\" type=\"hidden\" id=\"kelas\" value=\"$kelas\">\n";
	//echo "<input name=\"mp\" type=\"hidden\" id=\"mp\" value=\"$kod\">\n";
	//echo "<input name=\"jpep\" type=\"hidden\" id=\"jpep\" value=\"$jpep\">\n";

	ting=document.form1.ting.value;
	kelas=document.form1.kelas.value;
	mp=document.form1.mp.value;
	jpep=document.form1.jpep.value;
	tahun=document.form1.tahun.value;
	ajaxRequest.open("GET", "ajax/edit_markah_ajax.php?nokp=" + nokp+"&markah="+markah+"&ting="+ting+"&kelas="+kelas+"&mp="+mp+"&jpep="+jpep+"&cnt="+cnt+"&tahun="+tahun, true);
	ajaxRequest.send(null);
} //function edit_markah


function simpan_markah(kodsek,nokp,tahun,ting,kelas,mp,jpep,cnt,markah){

   var ajaxRequest;  // The variable that makes Ajax possible!
   if (IsNumeric(markah)){
	 v=parseInt(markah);
	 if (v<0 || v > 100){
		alert("Sila masukkan markah 0 hingga 100 sahaja"); 
		return false;
	 }
	  
   }
   //if(markah!=''){
	   if (!IsNumeric(markah) && markah!="TH" && markah!=''){
		   alert("Sila masukkan nombor atau TH (Tidak Hadir) atau biarkan kosong  !");
		   return false;
	   }
   //}
   document.getElementById("divMarkah"+cnt).innerHTML = "<img src='loading.gif'>";

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
 		   document.getElementById("divMarkah"+cnt).innerHTML = "";
           document.getElementById("divLabelMarkah"+cnt).style.display ="block";
		   document.getElementById("divLabelMarkah"+cnt).innerHTML=s;
		   document.getElementById("markahp_"+cnt).innerHTML=s;
		   

	   }
	}
	ajaxRequest.open("GET", "ajax/simpan_markah_ajax.php?kodsek="+kodsek+"&nokp=" + nokp+"&tahun="+tahun+"&ting="+ting+"&kelas="+kelas+"&mp="+mp+"&jpep="+jpep+"&markah="+markah, true);
	ajaxRequest.send(null);
} //function simpan_markah



function list_ppd(kodjpn){
	var ajaxRequest;  // The variable that makes Ajax possible!
   document.getElementById("divPPD").innerHTML = "<img src='loading.gif'>";

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
 		   document.getElementById("divPPD").innerHTML = s;
		   

	   }
	}
	ajaxRequest.open("GET", "ajax/list_ppd.php?kodjpn="+kodjpn, true);
	ajaxRequest.send(null);
} //function list_ppd



function IsNumeric(strString)
   //  check for valid numeric strings	
   {
   var strValidChars = "0123456789";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         blnResult = false;
         }
      }
   return blnResult;
   }
   
function papar_guruMP(kodmp,ting){
	var ajaxRequest;  // The variable that makes Ajax possible!
   //document.getElementById("divPPD").innerHTML = "<img src='loading.gif'>";

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
 		   document.getElementById("divGuruMP").innerHTML = s;
		   

	   }
	}
	ajaxRequest.open("GET", "ajax/papar_guruMP.php?kodmp="+kodmp+"&ting="+ting, true);
	ajaxRequest.send(null);
} //function list_ppd

  