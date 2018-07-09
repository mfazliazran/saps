<?php
require('auth.php');
include 'config.php';
session_start();
//$kodsek=$_GET["kodsek"];
$_SESSION["kodseksbt"]=$_GET["kodsek"];
$stmt=OCIParse($conn_sispa,"SELECT NAMASEK,STATUS,SBT,KODJENISSEKOLAH,KLUSTER,KODNEGERIJPN FROM tsekolah WHERE kodsek='".$_GET["kodsek"]."'");
OCIExecute($stmt);
if(OCIFetch($stmt)){
	$namasek = OCIResult($stmt,"NAMASEK");
	$statu = OCIResult($stmt,"STATUS");
	$jenisbt = OCIResult($stmt,"SBT");
	$jenissek = OCIResult($stmt,"KODJENISSEKOLAH");
	$kluster = OCIResult($stmt,"KLUSTER");
	$sbp = OCIResult($stmt,"SBP");
	$kodjpn = OCIResult($stmt,"KODNEGERIJPN");
	/*echo "Jenis - ".$jenissek."<br>";
	echo "Kluster - ".$kluster."<br>";
	echo "SBT - ".$jenisbt."<br>";	
	echo $_SESSION['level']."<br>";*/

	if($jenissek=='107' and $jenissek=='204' and $jenissek=='209')//BPI
		$jenis = 1;
	if ($_SESSION['level']=="11" and $jenis==1) // BPI
		die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH.');
	elseif ($_SESSION['level']=="12" and !($jenisbt=='Y' or $kluster=='Y') ) // SBT
		die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH..');
	else if ($_SESSION['level']=="13" and $kluster<>'Y') // SKK
		die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH...');
	else if ($_SESSION['level']=="15" and $jenissek<>'206') // SBP
		die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH....');
	else if ($_SESSION['level']=="8" and ($jenissek<>'203' and $jenissek<>'303')) // BPTV
		die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH......');

	//if ($_SESSION['level']=="6" and $kodjpn <> $_SESSION["kodsek"])
		//die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH');
		
	$_SESSION["namasekolah"] = $namasek;
	$_SESSION["statusseksbt"] = $statu;
	$_SESSION["kodjpn2"] = $kodjpn;
	/*$_SESSION['kodsek'] = $_SESSION["kodseksbt"];	
	$_SESSION['kodsekolah'] = $_SESSION["kodseksbt"];
	$_SESSION["statussek"] = $_SESSION["statusseksbt"];
*/} 

//die($_SESSION['kodsek']);
session_write_close();
pageredirect("senarai_sek_bhg.php");
/*if ($_SESSION['level']=="11")
	pageredirect("senarai_sekbpi.php");
else if ($_SESSION['level']=="12")
	pageredirect("senarai_seksbt.php");
else if ($_SESSION['level']=="13")
	pageredirect("senarai_skk.php");
else if ($_SESSION['level']=="15")
	pageredirect("senarai_sbp.php");
else if ($_SESSION['level']=="8")
	pageredirect("senarai_sekbptv.php");
else if ($_SESSION['level']=="6")
	pageredirect("senarai_sekjpn.php");*/
?>