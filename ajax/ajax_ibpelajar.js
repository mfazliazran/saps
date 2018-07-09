// JavaScript Document
//ibubapa2/semak.php
function get_details(tahun){
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
           s=ajaxRequest.responseText;
		   arr=s.split("|");
		   //alert(arr[0]);
		   if(arr[0]=="Wujud"){
			   document.getElementById("tahunting").innerHTML = arr[1];
			   document.getElementById("kelas").innerHTML = arr[2];
			   document.getElementById("kodseksemasa").innerHTML = arr[3];
			   document.getElementById("keputusan_pep").innerHTML = arr[4];
			   document.getElementById("gurump").innerHTML = arr[5];
		   }else if(arr[0]=="Tidak Wujud"){
			   document.getElementById("tahunting").innerHTML = "";
			   document.getElementById("kelas").innerHTML = "";
			   document.getElementById("kodseksemasa").innerHTML = "";
			   document.getElementById("keputusan_pep").innerHTML = "";
			   document.getElementById("gurump").innerHTML = "";
			   document.getElementById("btnpapar").style.display = "none";
			   alert("TIADA REKOD.");   
		   }
	   }
	}
	ajaxRequest.open("GET", "../ajax/maklumat_pelajar.php?tahun=" + tahun, true);
	ajaxRequest.send(null); 
}
function papar_btn(jpep,nokp,kodsek,darjah,kelas,tahun){
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
           s=ajaxRequest.responseText;
		  // alert('nokp=" + nokp + "&kodsek=" + kodsek + "&ting=" + darjah + "&kelas=" + kelas + "&tahun=" + tahun + "&jpep=" + jpep');
		   document.getElementById("btnpapar").style.display = "block";
		   document.getElementById("btnpapar").innerHTML = s;
	   }
	}
	ajaxRequest.open("GET", "../ajax/papar_btn.php?nokp=" + nokp + "&kodsek=" + kodsek + "&ting=" + darjah + "&kelas=" + kelas + "&tahun=" + tahun + "&jpep=" + jpep, true);
	ajaxRequest.send(null); 
}