<?php
session_start();
include 'auth.php';
include 'config.php';
//include 'kepala.php';
//include 'menu.php';
include 'fungsikira.php';

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$namagu = $_GET['namaguru'];
$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek2'];
$jpep = $_SESSION['jpep'];

$m="$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek";

header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="analisis_pencapaian_murid_'.$jpep.'_'.$ting.'_'.$tahun.'.xls"');
   echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>STATISTIK LINUS IKUT PPD</TITLE>";
   echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
   echo "<body>";
?>
<html>
<titel></title>
<head>
<!--<link href="include/kpm.css" type="text/css" rel="stylesheet" />-->
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

<html>
<titel></title>
<head>
<!--<link href="include/kpm.css" type="text/css" rel="stylesheet" />-->
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


//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h5><center>$namasek<br>ANALISIS KEPUTUSAN PEPERIKSAAN MURID<br>".jpep($jpep)." TAHUN $tahun</center></h5><br>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo "GURU KELAS : $namagu<br>KELAS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : $ting $kelas ";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\">Bil MP<br>Ambil</td>\n";
echo "    <td colspan=\"11\"><div align=\"center\">Bilangan Gred </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Keputusan</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">GPC</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A+&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A-&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;B+&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;B&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;C+&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;C&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;D&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;E&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;G&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

$q_nilai = "SELECT *  FROM tnilai_sma ma, tmurid tm WHERE ma.kodsek='$kodsek' and ma.tahun='$tahun' AND ma.jpep='$jpep' AND ma.ting='$ting' AND ma.kelas='$kelas' AND ma.nokp=tm.nokp ORDER BY ma.keputusan Desc, ma.gpc Asc, ma.peratus Desc";
$qry_nilai = oci_parse($conn_sispa,$q_nilai);
oci_execute($qry_nilai);
$bilmurid = count_row($q_nilai);

while($rownilai = oci_fetch_array($qry_nilai))
{
	$penilai[] = $rownilai ;
}

if (!empty($penilai))
{
	$bil = 0;
	foreach( $penilai as $key => $nilai )
	{
		$bil=$bil+1;
		echo "    <td>$bil</td>\n";
		echo "    <td>$nilai[NAMAP]</td>\n";
		echo "    <td><center>$nilai[BILMP]</td>\n";
		echo "    <td><center>$nilai[BILAP]</td>\n";
		echo "    <td><center>$nilai[BILA]</td>\n";
		echo "    <td><center>$nilai[BILAM]</td>\n";	
		echo "    <td><center>$nilai[BILBP]</td>\n";
		echo "    <td><center>$nilai[BILB]</td>\n";
		echo "    <td><center>$nilai[BILCP]</td>\n";
		echo "    <td><center>$nilai[BILC]</td>\n";
		echo "    <td><center>$nilai[BILD]</td>\n";
		echo "    <td><center>$nilai[BILE]</td>\n";
		echo "    <td><center>$nilai[BILG]</td>\n";
		echo "    <td><center>$nilai[BILTH]</td>\n";
		echo "    <td><center>$nilai[JUMMARK]</td>\n";
		echo "    <td><center>$nilai[PERATUS]</td>\n";
		echo "    <td><center>$nilai[KEPUTUSAN]</td>\n";
		echo "    <td><center>&nbsp;$nilai[GPC]&nbsp;&nbsp;</td>\n";
		echo "    <td><center>$nilai[KDK]/$bilmurid&nbsp;&nbsp;</td>\n";
		echo "    <td>$nilai[PENCAPAIAN]</td>\n";
		echo "</tr>";
	}
}
else {
		echo "<br>";
		echo "<td colspan = \"22\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n"; 
echo "</body>";
echo "</html>";

function jpep($kodpep){
switch ($kodpep){
	case "U1":
	$npep="UJIAN 1";
	break;
	case "U2":
	$npep="UJIAN 2";
	break;
	case "PAT":
	$npep="PEPERIKSAAN AKHIR TAHUN";
	break;
	case "PPT":
	$npep="PEPERIKSAAN PERTENGAHAN TAHUN";
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
	$npep="SARINGAN LINUS KHAS 1";
	break;
}
return $npep;
}

if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?> 