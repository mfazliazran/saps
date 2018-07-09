<?php 
include("config.php");
$b=$_GET['data'];
list ($nokp, $kodsek, $tahun, $ting, $kelas, $levelget)=split('[|]', $b);
$kelas = htmlentities($kelas);

$sqlkelas="DELETE FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'";
$result1=oci_parse($conn_sispa,$sqlkelas);
oci_execute($result1);

$sqlkelas2="DELETE FROM TKELASSEK WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'";
$result2=oci_parse($conn_sispa,$sqlkelas2);
oci_execute($result2);

if($levelget==2){
	$st1=oci_parse($conn_sispa,"UPDATE login SET level1='1', ting='', kelas='' WHERE nokp='$nokp' AND kodsek='$kodsek'");
    oci_execute($st1);
	oci_free_statement($st1);
	}
if($levelget==4){
	$st2=oci_parse($conn_sispa,"UPDATE login SET level1='3', ting='', kelas='' WHERE nokp='$nokp' AND kodsek='$kodsek'");
    oci_execute($st2);
	oci_free_statement($st2);
	}
require 'b_daftar_guru_kelas.php';
?>