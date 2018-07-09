function semak(nokp){
	var ajaxRequest;  // The variable that makes Ajax possible!
  	//alert(nokp);
	if(nokp==""){
		alert("Sila masukkan No Kad Pengenalan / Sijil Lahir!.");
		document.getElementById("txtIC").focus();
		return false;
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
		if(ajaxRequest.readyState === 4){
           s=ajaxRequest.responseText;
		   arr=s.split("|");
		   //alert(arr[0]);
		   if(arr[0]=="Wujud"){
			   document.getElementById("txtIC").disabled = true;
			   document.getElementById("Cari").disabled = true;
			   document.getElementById("div_cariansekolah").style.display = "block";
			   document.getElementById("div_carianmurid").style.display = "block";	
			   document.getElementById("txtNegeri").disabled = false;
			   document.getElementById("Semak").disabled = false;   
			   document.getElementById("CariSekolah").disabled = false; 
			  
		   }else if (arr[0]=="Tidak Wujud"){
			  document.getElementById("txtNegeri").selectedIndex = 0;
			  document.getElementById("txtSekolah").value = "";
			  document.getElementById("txtNegeri").disabled = true;
			  document.getElementById("Semak").disabled = true;
			  document.getElementById("CariSekolah").disabled = true; 
			  document.getElementById("error_msg").style.display = "block"; 
		   }
	   }
	}
	ajaxRequest.open("GET", "../ajax/papar_carian.php?nokp=" + nokp , true);
	ajaxRequest.send(null);
} //function simpan_markah


function semaksekolah(nokp,kodsek){
	var ajaxRequest;  // The variable that makes Ajax possible!
  	//alert(nokp);
	if(kodsek==""){
		alert("Sila buat carian sekolah.");
		document.getElementById("txtKodSekolah").focus();
		return false;
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
		if(ajaxRequest.readyState === 4){
           s=ajaxRequest.responseText;
		   arr=s.split("|");
		   //alert(arr[0]);
		   if(arr[0]=="Wujud"){
				location.href='semak.php';  
		   }else if (arr[0]=="Tidak Wujud"){
			  document.getElementById("txtNegeri").selectedIndex = 0;
			  document.getElementById("txtKodSekolah").value = "";
			  document.getElementById("txtSekolah").value = "";
			  document.getElementById("txtCariSekolah").value = "";
			  //document.getElementById("txtNegeri").disabled = true;
			  //document.getElementById("Semak").disabled = true;
			  document.getElementById("error_msg2").style.display = "block"; 
		   }
	   }
	}
	ajaxRequest.open("GET", "../ajax/papar_carianpelajar.php?nokp=" + nokp + "&kodsek=" + kodsek , true);
	ajaxRequest.send(null);
} //function simpan_markah