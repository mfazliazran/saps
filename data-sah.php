<?php 
include 'config.php';
include 'fungsi.php';

$m=$_GET['data'];
list($tahun, $kodsek, $ting, $jpep, $tsah)=split('[/]', $m);

$q_sah=oci_parse($conn_sispa,"SELECT * FROM tsah WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND jpep='$jpep'");
oci_execute($q_sah);
$num=count_row("SELECT * FROM tsah WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND jpep='$jpep'");
if($jpep=='TOV'){
	if($num >=1){
	$stmt=oci_parse($conn_sispa,"UPDATE tsah SET tahun='$tahun', kodsek='$kodsek', ting='$ting', tksah='$tsah' WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND jpep='$jpep'");
	oci_execute($stmt);
	message("Data Telah Disahkan",1);
	location("sah-etr.php");
	}
	else {
	$stmr=oci_parse($conn_sispa,"INSERT INTO tsah (tahun, kodsek, ting, jpep, tksah) VALUES ('$tahun','$kodsek','$ting','$jpep','$tsah')");
	oci_execute($stmr);
	message("Data Telah Disahkan",1);
	location("sah-etr.php");
	}
}
else {
	if($num >= 1){
	$stmt=oci_parse($conn_sispa,"UPDATE tsah SET tahun='$tahun', kodsek='$kodsek', ting='$ting', tksah='$tsah' WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND jpep='$jpep'");
	oci_execute($stmt);
	message("Data Telah Disahkan",1);
	location("sah-markah.php");
	}
	else {
	$stmr=oci_parse($conn_sispa,"INSERT INTO tsah (tahun, kodsek, ting, jpep, tksah) VALUES ('$tahun','$kodsek','$ting','$jpep','$tsah')");
	oci_execute($stmr);
	message("Data Telah Disahkan",1);
	location("sah-markah.php");
	}
}
 ?>