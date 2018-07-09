<?php 
include 'config.php';
$m=$_GET['data'];
list ($nokp, $kodsek)=split('[|]', $m);
$tahun_h = date("Y");

if ($nokp==""){
	$sqlguru="DELETE FROM login WHERE nokp is null and kodsek='$kodsek'";
	//die ($sqlguru);
	$result1=oci_parse($conn_sispa,$sqlguru);
	oci_execute($result1);
	oci_free_statement($result1);

	$sqlsub_guru="DELETE FROM sub_guru WHERE nokp is null AND kodsek='$kodsek' AND tahun='$tahun_h'";
	$result2=oci_parse($conn_sispa,$sqlsub_guru);
	oci_execute($result2);
	oci_free_statement($result2);

	$result3=oci_parse($conn_sispa,"DELETE FROM tguru_kelas WHERE nokp is null AND kodsek='$kodsek' AND tahun='$tahun_h'");  
	oci_execute($result3);
	oci_free_statement($result3);

}else {
	$sqlguru="DELETE FROM login WHERE nokp='$nokp' and kodsek='$kodsek'";
	$result1=oci_parse($conn_sispa,$sqlguru);
	oci_execute($result1);
	oci_free_statement($result1);

	$sqlsub_guru="DELETE FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='$tahun_h'";
	$result2=oci_parse($conn_sispa,$sqlsub_guru);
	oci_execute($result2);
	oci_free_statement($result2);

	$result3=oci_parse($conn_sispa,"DELETE FROM tguru_kelas WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='$tahun_h'");  
	oci_execute($result3);
	oci_free_statement($result3);

}
//die ($sqlguru);
header('Location: edit_guru.php');
?>