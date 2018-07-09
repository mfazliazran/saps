<?php
require('auth.php');
include 'config.php';
session_start();
//$kodsek=$_GET["kodsek"];
$_SESSION["kodseksbt"]=$_GET["kodsek"];
$stmt=OCIParse($conn_sispa,"SELECT NAMASEK,STATUS,SBT,KODJENISSEKOLAH,KLUSTER,KODNEGERIJPN,KODPPD FROM tsekolah WHERE kodsek='".$_GET["kodsek"]."'");
OCIExecute($stmt);
if(OCIFetch($stmt)){
	$namasek = OCIResult($stmt,"NAMASEK");
	$statu = OCIResult($stmt,"STATUS");
	$jenisbt = OCIResult($stmt,"SBT");
	$jenissek = OCIResult($stmt,"KODJENISSEKOLAH");
	$kluster = OCIResult($stmt,"KLUSTER");
	$kodjpn = OCIResult($stmt,"KODNEGERIJPN");
	$kodppd = OCIResult($stmt,"KODPPD");

	if($jenissek=='204' or $jenissek=='209')
		$jenis = 1;
	if ($_SESSION['level']=="11" and $jenis<>1) // BPI
		die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH');
	if ($_SESSION['level']=="12" and $jenisbt<>'Y') // SBT
		die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH');
	if ($_SESSION['level']=="13" and $kluster<>'Y') // SKK
		die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH');
	//if ($_SESSION['level']=="6" and $kodjpn <> $_SESSION["kodsek"])
		//die('KOD SEKOLAH YANG ANDA PILIH TIDAK SAH');
		
	$_SESSION["namasekolah"] = $namasek;
	$_SESSION["statusseksbt"] = $statu;
	$_SESSION["kodjpn"] = $kodjpn;
	$_SESSION["kodppd2"] = $kodppd;
	//$_SESSION["kodjpn3"] = $kodjpn;
	//$tompang = $_SESSION['kodsek'];
	//$_SESSION['kodjpn4'] = $tompang;
	//$_SESSION['kodsek'] = $_SESSION["kodseksbt"];	
	
	$_SESSION['kodsek2'] = $_SESSION["kodseksbt"];
	//$_SESSION["statussek"] = $_SESSION["statusseksbt"];
	} 

//die($_SESSION['kodsek']);
session_write_close();
//pageredirect("mainpage.php?module=Profail_Sekolah&task=am2");
if ($_SESSION['level']=="11")
	pageredirect("senarai_sekbpi.php");
else if ($_SESSION['level']=="12")
	pageredirect("senarai_seksbt.php");
else if ($_SESSION['level']=="13")
	pageredirect("senarai_skk.php");
else if ($_SESSION['level']=="6")
	pageredirect("senarai_sekjpn.php");
else if ($_SESSION['level']=="5")
	pageredirect("senarai_sekppd.php");
else if ($_SESSION['level']=="14")
	pageredirect("senara_seklb.php");?>