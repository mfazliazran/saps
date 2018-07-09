function pilih_sekolah(kodsek){
	//alert (kodsek);
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
 		   s = ajaxRequest.responseText;
		   //alert (s);
           arr=s.split("|");
           opener.document.getElementById("kodsek").value=arr[0];
           opener.document.getElementById("sek").value=arr[1];
		   //var status = arr[2];
		   //var ppd = arr[3];
		   //alert (arr[0]+'/'+arr[1]+'/'+arr[2]+'/'+arr[3]);
           opener.document.getElementById("data1").value=ajaxRequest.responseText;//arr[0]+'/'+arr[1]+'/'+arr[2]+'/'+arr[3];
		   
           window.close();
	   }
	}
	ajaxRequest.open("GET", "ajax/cari_sekolah.php?kodsek=" + kodsek, true);
	ajaxRequest.send(null); 
} //pilih syarikat
