<?php
//ibubapa2/index.php
//ajax_carian.js
include "../config.php";
session_start();
$nokp = $_GET["nokp"];
$kodsek = $_GET["kodsek"];

$table1 = "tmuridsr";	
$table2 = "tmurid";	
$kodsek1 = "(kodsekd1='$kodsek' or kodsekd2='$kodsek' or kodsekd3='$kodsek' or kodsekd4='$kodsek' or kodsekd5='$kodsek' or kodsekd6='$kodsek')";
$kodsek2 = "(kodsekp='$kodsek' or kodsekt1='$kodsek' or kodsekt2='$kodsek' or kodsekt3='$kodsek' or kodsekt4='$kodsek' or kodsekt5='$kodsek')";

$sql = "SELECT NAMAP FROM $table2 where nokp='$nokp' and $kodsek2 order by nokp asc";
$qic = oci_parse($conn_sispa,$sql);
oci_execute($qic);
if($row = oci_fetch_array($qic)){
	$_SESSION["nokp"] = $nokp;
	$_SESSION["namamurid"] = $row["NAMAP"];
	echo "Wujud";
}else{
	$sql2 = "SELECT NAMAP FROM $table1 where nokp='$nokp' and $kodsek1 order by nokp asc";
	//echo $sql2;
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