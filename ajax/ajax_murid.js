function papar_kelas(ting){

	var ajaxRequest;  // The variable that makes Ajax possible!
   document.getElementById("divKelas").innerHTML = "<img src='loading.gif'>";

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
 		   document.getElementById("divKelas").innerHTML = s;
		   

	   }
	}
	ajaxRequest.open("GET", "ajax/papar_kelas.php?ting="+ting, true);
	ajaxRequest.send(null);
} //function simpan_markah



