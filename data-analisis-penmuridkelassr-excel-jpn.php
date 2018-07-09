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
//$namagu = $_GET['namaguru'];
//$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek2'];
$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='".$_SESSION['tahun']."' AND gk.kodsek='".$_SESSION['kodsek2']."' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek");
OCIExecute($q_guru);
OCIFetch($q_guru);
$namagu = OCIResult($q_guru,"NAMA");//$row[nama]; 
$namasek = OCIResult($q_guru,"NAMASEK");//$row[namasek];

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

/*$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$namagu = $_GET['namaguru'];
$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek2'];
$jpep = $_SESSION['jpep'];*/

//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h5><center>$namasek<br>ANALISIS KEPUTUSAN PEPERIKSAAN MURID<br>".jpep($jpep)." TAHUN $tahun</center></h5>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "<tr>\n";
echo "Guru Kelas : $namagu<br>Kelas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : $ting $kelas ";
echo "<br><br>";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\">Bil MP<br>Ambil</td>\n";
echo "    <td colspan=\"7\"><div align=\"center\">Bilangan Gred </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Menguasai / <br>Tidak Menguasai</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">GPC</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;B&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;C&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;D&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;E&nbsp;&nbsp;</div></td>\n";
if($tahun>=2015){
	if($ting<>'D6'){
		echo "    <td><div align=\"center\">&nbsp;&nbsp;F&nbsp;&nbsp;</div></td>\n";
	}
}
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

$q_nilai = "SELECT *  FROM tnilai_sr sr, tmuridsr tm WHERE sr.tahun='$tahun' AND sr.jpep='$jpep' AND sr.darjah='$ting' AND sr.kelas='$kelas' AND sr.kodsek='$kodsek' AND sr.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY sr.keputusan Desc, sr.gpc Asc, sr.peratus Desc";
//echo $q_nilai;
$smt = oci_parse($conn_sispa,$q_nilai);
oci_execute($smt);
$bilmurid = count_row($q_nilai);

$bil = 0;
while($rownilai = oci_fetch_array($smt))
{
	$penilai[] = $rownilai ;
}


foreach( $penilai as $key => $nilai )
{

	$bil=$bil+1;
	echo "    <td>$bil</td>\n";
	echo "    <td>$nilai[NAMAP]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILMP]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILA]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILB]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILC]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILD]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILE]</td>\n";
	if($tahun>=2015){
		if($ting<>'D6'){
			echo "    <td><center>&nbsp;$nilai[BILF]</td>\n";
		}
	}
	echo "    <td><center>&nbsp;$nilai[BILTH]</td>\n";
	echo "    <td><center>&nbsp;$nilai[JUMMARK]</td>\n";
	echo "    <td><center>&nbsp;$nilai[PERATUS]</td>\n";
	echo "    <td><center>$nilai[KEPUTUSAN]</td>\n";
	echo "    <td><center>&nbsp;&nbsp;$nilai[GPC]&nbsp;&nbsp;</td>\n";
	echo "    <td><center>&nbsp;&nbsp;$nilai[KDK]/$bilmurid&nbsp;&nbsp;</td>\n";
	echo "    <td>$nilai[PENCAPAIAN]</td>\n";
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