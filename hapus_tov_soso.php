<?php
session_start();
include 'config.php';
include 'fungsi.php';
$m = $_GET['data'];
list ($kelas, $nokp, $ting, $kodmp, $tahun, $kodsek)=split('[|]', $m);

if ($_SESSION['statussek']=="SM"){
	$theadcount="headcount";
	$tmp="mpsmkc";
	$tahap="TING";
}

if ($_SESSION['statussek']=="SR"){
	$theadcount="headcountsr";
	$tmp="mpsr";
	$tahap="DARJAH";
}

$sql = oci_parse($conn_sispa,"DELETE FROM $theadcount WHERE nokp='$nokp' AND $tahap='$ting' AND kelas='$kelas' AND tahun='$tahun' AND kodsek='$kodsek' AND hmp='$kodmp'");

//echo "DELETE FROM $theadcount WHERE nokp='$nokp' AND $tahap='$ting' AND kelas='$kelas' AND tahun='$tahun' AND kodsek='$kodsek' AND hmp='$kodmp'";
//die();
oci_execute($sql);

message("Markah TOV Pelajar $nokp Telah Dihapuskan",1);
//location("nilaitambah.php");
location("hapus_tov.php?data=$kelas|$ting|$kodmp|$tahun|$kodsek");

?>
                                                    