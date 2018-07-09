<!--
//Browser Support Code

function edit_tov(nokp,cnt,tov,etr,bil){


	var ajaxRequest;  // The variable that makes Ajax possible!
    document.getElementById("divtov"+cnt).style.display ="block";
    document.getElementById("divLabeltov"+cnt).style.display ="none";
    document.getElementById("divtov"+cnt).innerHTML = "<img src='loading.gif'>";

    document.getElementById("divetr"+cnt).style.display ="block";
    document.getElementById("divLabeletr"+cnt).style.display ="none";
    document.getElementById("divetr"+cnt).innerHTML = "<img src='loading.gif'>";

    for(i=0;i<bil;i++){
	  if (i!=cnt){
        document.getElementById("divLabeltov"+i).style.display ="block";
        document.getElementById("divtov"+i).innerHTML ="";

        document.getElementById("divLabeletr"+i).style.display ="block";
        document.getElementById("divetr"+i).innerHTML ="";
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
 		   document.getElementById("divtov"+cnt).innerHTML = arr[0];
 		   document.getElementById("divetr"+cnt).innerHTML = arr[1];\
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
	ajaxRequest.open("GET", "ajax/edit_tov_ajax.php?nokp=" + nokp+"&tov="+tov+"&etr="+etr+"&ting="+ting+"&kelas="+kelas+"&mp="+mp+"&jpep="+jpep+"&cnt="+cnt+"&tahun="+tahun, true);
	ajaxRequest.send(null);
} //function edit_tov


function simpan_tov(kodsek,nokp,tahun,ting,kelas,mp,jpep,cnt,tov,etr){


//alert("tov:"+tov +" etr:"+etr);
   var ajaxRequest;  // The variable that makes Ajax possible!
   if (IsNumeric(tov)){
	 v=parseInt(tov);
	 if (v<0 || v > 100){
		alert("Sila masukkan tov 0 hingga 100 sahaja"); 
		return false;
	 }
	  
   }

   if (IsNumeric(etr)){
	 v=parseInt(etr);
	 if (v<0 || v > 100){
		alert("Sila masukkan etr 0 hingga 100 sahaja"); 
		return false;
	 }
	  
   }

   //if(tov!=''){
	   if (!IsNumeric(tov) && tov!="TH" && tov!=''){
		   alert("Sila masukkan TOV nombor atau TH (Tidak Hadir) atau biarkan kosong  !");
		   return false;
	   }
	   if (!IsNumeric(etr) && etr!="TH" && etr!=''){
		   alert("Sila masukkan ETR nombor atau TH (Tidak Hadir) atau biarkan kosong  !");
		   return false;
	   }
   //}
   document.getElementById("divtov"+cnt).innerHTML = "<img src='loading.gif'>";
   document.getElementById("divetr"+cnt).innerHTML = "<img src='loading.gif'>";

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
 	//alert(s);
		   document.getElementById("divtov"+cnt).innerHTML = "";
           document.getElementById("divLabeltov"+cnt).style.display ="block";
		   document.getElementById("divLabeltov"+cnt).innerHTML=arr[0];
		   document.getElementById("tovp_"+cnt).innerHTML=arr[0];
		   
		   document.getElementById("divetr"+cnt).innerHTML = "";
           document.getElementById("divLabeletr"+cnt).style.display ="block";
		   document.getElementById("divLabeletr"+cnt).innerHTML=arr[1];
		   document.getElementById("etrp_"+cnt).innerHTML=arr[1];

	   }
	}
	
	ajaxRequest.open("GET", "ajax/simpan_tov_ajax.php?kodsek="+kodsek+"&nokp=" + nokp+"&tahun="+tahun+"&ting="+ting+"&kelas="+kelas+"&mp="+mp+"&jpep="+jpep+"&tov="+tov+"&etr="+etr, true);
	ajaxRequest.send(null);
} //function simpan_tov

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




