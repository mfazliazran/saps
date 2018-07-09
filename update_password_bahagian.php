<?php
set_time_limit(0);
include 'config.php'; 
echo "Update password user BAHAGIAN<br><br>";

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
	
	$qry="SELECT LEVEL1,PASSWD FROM TEMP_PASSWORD_BAHAGIAN ";	
	$stmt = oci_parse($conn_sispa, $qry);
    oci_execute($stmt);	
    while($data=oci_fetch_array($stmt)){	
	    $level1=$data["LEVEL1"];
		$pswd=$data["PASSWD"];
	    $encrypted = encrypt($pswd, "vtech%5%52018");
	
		$update = "UPDATE login SET PSWD='$encrypted'  WHERE LEVEL1= '$level1' ";
		$run_update = oci_parse($conn_sispa,$update);
		oci_execute($run_update);
	}
echo "Selesai...";
?>