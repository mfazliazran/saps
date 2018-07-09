<?php 
//CA16111204
//session_start();
if(!isset($_SESSION)){session_start();}

//CA16111402
$gting="";
//CA16111402

include_once('config.php');
//include 'fungsi.php';

$sql="SELECT NAMA,NOKP,KODSEK,NAMASEK,DAERAH,NEGERI,USER1,LEVEL1,STATUSSEK,KODNEGERI FROM login WHERE user1='".$_SESSION['SESS_MEMBER_ID']."' and pswd='".$_SESSION['SESS_PASSWORD']."'";
//echo $sql;
$stmt=OCIParse($conn_sispa,$sql);
OCIExecute($stmt);
//$bil=count_row($sql);

if(OCIFetch($stmt)){
	$nama = OCIResult($stmt,"NAMA");
	$nokp = OCIResult($stmt,"NOKP");
	$kodsek = OCIResult($stmt,"KODSEK");
	$namasek = OCIResult($stmt,"NAMASEK");
	$daerah = OCIResult($stmt,"DAERAH");
	$negeri = OCIResult($stmt,"NEGERI");
	$user = OCIResult($stmt,"USER1");
	$level = trim(OCIResult($stmt,"LEVEL1"));
	$jsek = OCIResult($stmt,"STATUSSEK");
	$kodnegerijpn = OCIResult($stmt,"KODNEGERI");//untuk menu status pengisian (jpn)
}
OCIFreeStatement($stmt);

$stmt=OCIParse($conn_sispa,"SELECT TING,KELAS,LEVEL1 FROM tguru_kelas WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND nokp='$nokp'");
OCIExecute($stmt);
if(OCIFetch($stmt)){
	$gting = OCIResult($stmt,"TING");
	$gkelas = OCIResult($stmt,"KELAS");
	$level_tgk = trim(OCIResult($stmt,"LEVEL1"));
}
OCIFreeStatement($stmt);
$ting_kecil = strtolower($gting);

$_SESSION['statussek']=$jsek;
$_SESSION['kodsek'] = $kodsek;
$_SESSION['kodsekolah'] = $kodsek;
if($kodsek=='SBT' or $kodsek=='BPI' or $kodsek=='SKK' or $kodsek=='LP' 
	or $kodsek=='SBP' or $kodsek=='BPTV' or $kodsek=='BPSH'){
	if($_SESSION["kodseksbt"]<>""){
		$_SESSION['kodsek'] = $_SESSION["kodseksbt"];	
		$_SESSION['kodsekolah'] = $_SESSION["kodseksbt"];
		$_SESSION["statussek"] = $_SESSION["statusseksbt"];
		$namasek = $_SESSION["namasekolah"];	
	}
}
switch ($level){
	case "1" : $statusguru = "Guru Mata Pelajaran"; break;
	case "2" : $statusguru = "Guru Kelas ".$gting." ".$gkelas.""; break;
	case "3" : $statusguru = "Admin Sekolah<br>(SU Peperiksaan)"; break;
	case "4" : $statusguru = "Admin Sekolah<br>(SU Peperiksaan) / Guru Kelas ".$gting." ".$gkelas.""; break;
	case "P" : $statusguru = "Pengetua / Guru Besar"; break;
	}
switch ($gting){
	//guru kelas ting
	case "P" : case "T2" : case "T3" : $statusgurukelas = "MR"; break;
	case "T4" : case "T5" : $statusgurukelas = "MA"; break;
	default : $statusgurukelas = "SR"; break;
	echo "statusgurukelas".$statusgurukelas;
}

$year = "".$_SESSION['tahun']."";
//echo "**Profil:<br>";
if(($_SESSION['level']=="1") OR ($_SESSION['level']=="2") OR ($_SESSION['level']=="3") OR ($_SESSION['level']=="4") OR ($_SESSION['level']=="P") OR ($_SESSION['level']=="PK")){
echo "$statusguru<br>$nama<br>$namasek<br>$daerah<br>$negeri<br>";
echo "<center><font color =\"#ff0000\"><b>JENIS PEPERIKSAAN<br>".jpep("".$_SESSION['jpep']."")."<br>".$_SESSION['tahun']."</b></font></center>";}

if(($_SESSION['level']=="5") OR ($_SESSION['level']=="6")){
echo "$nama<br>$namasek<br>$daerah<br>$negeri<br>";
echo "<center><font color =\"#ff0000\"><b>".$_SESSION['tahun']."</b></font></center>";}

if($_SESSION['level']=="7" OR $_SESSION['level']=="8" OR $_SESSION['level']=="9" OR $_SESSION['level']=="10" OR $_SESSION['level']=="11" 
   OR $_SESSION['level']=="12" OR $_SESSION['level']=="13"  OR $_SESSION['level']=="14" OR $_SESSION['level']=="15"){
echo "$nama<br>$negeri<br>";
echo "<center><font color =\"#ff0000\"><b>".$_SESSION['tahun']."</b></font></center>";}

if($_SESSION['level']=="8" OR $_SESSION['level']=="11" or $_SESSION['level']=="12" or $_SESSION['level']=="13" OR $_SESSION['level']=="14" OR $_SESSION['level']=="15"){
echo "$namasek<br>";
echo "<center><font color =\"#ff0000\"><b>JENIS PEPERIKSAAN<br>".jpep("".$_SESSION['jpep']."")."</font></center>";
}



function jpep($kodpep)
{
	switch ($kodpep){
		case "U1":
		$npep="UJIAN 1 / SUMATIF 1";
		break;
		case "U2":
		$npep="UJIAN 2";
		break;
		case "PAT":
		$npep="PEPERIKSAAN AKHIR TAHUN / SUMATIF 3";
		break;
		case "PPT":
		$npep="PEPERIKSAAN PERTENGAHAN TAHUN / SUMATIF 2";
		break;
		case "PMRC":
		$npep="PEPERIKSAAN PERCUBAAN PMR";
		break;
		case "SPMC":
		$npep="PEPERIKSAAN PERCUBAAN SPM";
		break;
		case "UPSRC":
		$npep="PEPERIKSAAN PERCUBAAN UPSR";
		break;
		case "LNS01":
		$npep="SARINGAN LINUS KHAS KOHORT 2";
		break;
	}
return $npep;
}

?>