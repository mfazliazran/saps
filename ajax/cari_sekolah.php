<?php
include "../config.php";

$kodsek=$_GET["kodsek"];

    $query = "select * from tsekolah where kodsek='$kodsek'";

	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$num_rows = count_row($query);
	if($num_rows > 0) {
	    $data=oci_fetch_array($result);
		$namasek = $data["NAMASEK"];
		$status = $data["STATUS"];
		$kodppd = $data["KODPPD"];
		//$mukim = $data["MUKIM"];
		echo "$kodsek|$namasek|$status|$kodppd";
	}
	else
		echo "|";
?>