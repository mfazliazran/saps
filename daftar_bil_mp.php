<?php
include 'auth.php';
include 'config.php';

$kodsek=$_POST['kodsek'];
$ting=$_POST['ting'];
$kelas=$_POST['kelas'];
$kodmp=$_POST['mp'];
$bilmurid=$_POST['bilmu'];
$bilambil=$_POST['bilam'];
$bil=$_POST['bil'];

$key=0;
if (is_array($ting))
{
	while ($bil>0)
	{
		$tg = $ting[$key];
		$kls = $kelas[$key];
		$mp = $kodmp[$key];
		$bam = $bilambil[$key];
		$ks= $kodsek[$key];
		$stmt = oci_parse($conn_sispa,"UPDATE sub_guru SET bilammp='$bam' WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$ks' AND nokp='".$_SESSION['nokp']."' AND ting='$tg' AND kelas='$kls' AND kodmp='$mp'")or die(oci_error());
		oci_execute($stmt);
		//echo "mana ni ".$_SESSION['tahun']." ".$_SESSION['nokp']." $tg $kls $mp $bam $ks<br>";
		$bil--; $key++;
	}
}
header("location: papar_subjek.php");
?>