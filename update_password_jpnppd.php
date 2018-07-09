<?php
set_time_limit(0);
include 'config.php'; 
echo "Update password user JPN/PPD<br><br>";

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
	
	$qry="SELECT JPN,PASSWD FROM TEMP_PASSWORD_JPNPPD ";	
	$stmt = oci_parse($conn_sispa, $qry);
    oci_execute($stmt);	
    while($data=oci_fetch_array($stmt)){	
	    $jpn=$data["JPN"];
		$pswd=$data["PASSWD"];
	    $encrypted = encrypt($pswd, "vtech%5%52018");
	
		$update = "UPDATE login SET PSWD='$encrypted'  WHERE LEVEL1 IN ('5','6') AND NEGERI= '$jpn' ";
		$run_update = oci_parse($conn_sispa,$update);
		oci_execute($run_update);
	}
echo "Selesai...";
?>