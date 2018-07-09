<?php
//ibubapa2/index.php
//ajax_carian.js
include "../config.php";
session_start();
$nokp = $_GET["nokp"];

$table1 = "tmuridsr";	
$table2 = "tmurid";	

$sql = "SELECT NAMAP FROM $table2 where nokp='$nokp' order by nokp asc";
$qic = oci_parse($conn_sispa,$sql);
oci_execute($qic);
if($row = oci_fetch_array($qic)){
	$_SESSION["nokp"] = $nokp;
	$_SESSION["namamurid"] = $row["NAMAP"];
	echo "Wujud";
}else{
	$sql2 = "SELECT NAMAP FROM $table1 where nokp='$nokp' order by nokp asc";
	$qic2 = oci_parse($conn_sispa,$sql2);
	oci_execute($qic2);
	if($row = oci_fetch_array($qic2)){
		$_SESSION["nokp"] = $nokp;
		$_SESSION["namamurid"] = $row["NAMAP"];
		echo "Wujud";
	}else{
		echo "Tidak Wujud";	
	}
}
?>