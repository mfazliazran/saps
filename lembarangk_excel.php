<?php 
include 'auth.php';
include 'config.php';
//include 'kepala.php';
//include 'menu.php';

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$tingkatan = $_GET['tingkatan'];
$namagu = $_GET['namaguru'];
$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];
$m="$ting&&kelas=$kelas&&namaguru=$namagu&&tingkatan=$tingkatan&&namasekolah=$namasek";

   header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="markah.xls"');
   echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>STATISTIK LINUS IKUT PPD</TITLE>";
   echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
   echo "<body>";

?>

<html>
<titel></title>
<head>
<link href="include/kpm.css" type="text/css" rel="stylesheet" />
<style type="text/css">
P {
	page-break-after: always;
}
</style>

</head>
<body>
<STYLE type="text/css">
@media print {
  #mybutton { display:none; visibility:hidden; }

  #mybutton2 { display:none; visibility:hidden; }
 
}



</STYLE>

<?php

$q_guru = oci_parse($conn_sispa,"SELECT NOKP,NAMA,NAMASEK,KODSEK,TING,KELAS,STATUSSEK FROM tguru_kelas WHERE tahun='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND nokp='".$_SESSION['nokp']."'");
oci_execute($q_guru);
$row=oci_fetch_array($q_guru);
$nokp=$row["NOKP"];
$nama=$row["NAMA"]; 
$namasek=$row["NAMASEK"]; 
$kodsek=$row["KODSEK"];
$ting=$row["TING"]; 
$gting=strtoupper($row["TING"]); 
$gkelas=$row["KELAS"];
$jsek = $row["STATUSSEK"];

if ($_SESSION['statussek']=="SM"){
	$nting="TINGKATAN";
	$tmarkah="markah_pelajar";
	$tmurid="tmurid";
	$tmp="mpsmkc";
	$tahap="ting";
	//$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";
	$kodsekolah = "kodsek$ting='$kodsek'";
}

if ($_SESSION['statussek']=="SR"){
	$nting="TAHUN";
	$tmarkah="markah_pelajarsr";
	$tmurid="tmuridsr";
	$tmp="mpsr";
	$tahap="darjah";
	//$kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	$kodsekolah = "kodsek$ting='$kodsek'";

}


echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
//echo "<h3><center>$namasek<br>LEMBARAN MARKAH MURID<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3><br>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<tr>";
echo "GURU KELAS : $nama<br>$nting : $ting  KELAS : $gkelas";
echo "<br><br>";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";
$q_mp = oci_parse($conn_sispa,"SELECT KODMP FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas' ORDER BY kodmp");
oci_execute($q_mp);
$num = count_row("SELECT KODMP FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas' ORDER BY kodmp");

while($rowmp=oci_fetch_array($q_mp)){
$mp_kodmp = $rowmp["KODMP"];
echo "<td colspan = \"2\"><center>$mp_kodmp</center></td>";
}
echo "</tr>";
echo "<tr>";

$q_mp = oci_parse($conn_sispa,"SELECT KODMP FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas' ORDER BY kodmp");
oci_execute($q_mp);
while($rowmp=oci_fetch_array($q_mp)){
$mp_kodmp = $rowmp["KODMP"];

	echo "<td><center>M</center></td>";
	echo "<td><center>G</center></td>";
	}
echo "</tr>";
//////habis kepala

$bil=0;
$q_murid = oci_parse($conn_sispa,"SELECT NAMAP, NOKP FROM $tmurid WHERE ($kodsekolah) AND $gting='$ting' and kelas$gting='$gkelas' and tahun$gting='".$_SESSION['tahun']."'  ORDER BY namap");
//echo "SELECT NAMAP, NOKP FROM $tmurid WHERE $kodsekolah AND $gting='$ting' and kelas$gting='$gkelas' and tahun$gting='".$_SESSION['tahun']."'  ORDER BY namap";
//echo ("SELECT * FROM $tmurid WHERE tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$gkelas' AND ($kodsekolah) ORDER BY namap");
oci_execute($q_murid);
"".count_row("SELECT NAMAP, NOKP FROM $tmurid WHERE ($kodsekolah) AND $gting='$ting' and kelas$gting='$gkelas' and tahun$gting='".$_SESSION['tahun']."' ORDER BY namap")."";
while($rowmurid=oci_fetch_array($q_murid)){
$namap = $rowmurid["NAMAP"];
$nokpm = $rowmurid["NOKP"];
$bil=$bil+1;
echo "    <tr>\n";
echo "    <td><center>$bil</center></td>\n";
echo "    <td>$namap</td>\n";

$q_mp = oci_parse($conn_sispa,"SELECT KODMP FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas' ORDER BY kodmp");
oci_execute($q_mp);

while($rowmp=oci_fetch_array($q_mp)){
$kodmp = $rowmp["KODMP"];
$gmp= "G$kodmp";

//echo "$kodmp $gmp $nokpm<br>";
$q_mark = oci_parse($conn_sispa,"SELECT * FROM $tmarkah WHERE nokp='$nokpm' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$gkelas' AND jpep='".$_SESSION['jpep']."' ORDER BY nama");
//echo ("SELECT * FROM $tmarkah WHERE nokp='$nokpm' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$gkelas' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' ORDER BY nama");
oci_execute($q_mark);
	//echo "budak".mysql_num_rows($q_mark)."";
	while($row = oci_fetch_array($q_mark)) {
		
		echo "    <td><center>&nbsp;".$row["$kodmp"]."</center></td>\n";
		echo "    <td><center>&nbsp;".$row["$gmp"]."</center></td>\n";
		}
	}
}
echo "</table>\n";
?>
<?php 
   echo "</body>";
   echo "</html>";
?> 