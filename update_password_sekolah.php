<?php
set_time_limit(0);
include 'config.php'; 
echo "Update password user sekolah<br><br>";

	function encrypt($string, $key) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}
	
	$qry="SELECT SEK,PASSWD FROM TEMP_PASSWORD_SEKOLAH";	
	$stmt = oci_parse($conn_sispa, $qry);
    oci_execute($stmt);	
    while($data=oci_fetch_array($stmt)){	
	    $sek=$data["SEK"];
		$pswd=$data["PASSWD"];
	    $encrypted = encrypt($pswd, "vtech%5%52018");
	
		$update = "UPDATE login SET PSWD='$encrypted'  WHERE KODSEK= '$sek' ";
		$run_update = oci_parse($conn_sispa,$update);
		oci_execute($run_update);
	}
echo "Selesai...";
?>