<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'fungsikira.php';
include 'fungsi.php';
include "input_validation.php";

$tahun = $_SESSION['tahun'];
$ting = validate($_GET['ting']);
$kelas = validate($_GET['kelas']);
$namagu = validate($_GET['namaguru']);
$namasek = validate($_GET['namasekolah']);
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];

header('Content-type: application/vnd.ms-excel ');
header('Content-Disposition: attachment; filename="analisis_pencapaian_murid_all_'.$jpep.'_'.$ting.'_'.$tahun.'.xls"');
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
echo "<h5><center>$namasek<br>ANALISIS KEPUTUSAN PEPERIKSAAN MURID<br>TINGKATAN $ting<br>".jpep($jpep)."<br>TAHUN $tahun</center></h5>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">";
echo "  <tr>";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\">Kelas</td>\n";
echo "    <td rowspan=\"2\">Bil MP<br>Ambil</td>\n";
if($tahun<=2014){
	echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
}else{
	echo "    <td colspan=\"7\"><div align=\"center\">Bilangan Gred </div></td>\n";
}
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Menguasai / <br>Tidak Menguasai</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">GPC</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDT</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;B&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;C&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;D&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;E&nbsp;&nbsp;</div></td>\n";
if($tahun>=2015){
	echo "    <td><div align=\"center\">F</div></td>\n";
}
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

$q_nting = "SELECT tm.namap, mr.kelas, tm.nokp, mr.bilmp, mr.bila, mr.bilb, mr.bilc, mr.bild, mr.bile, mr.bilf, mr.bilth, mr.jummark, mr.peratus, mr.keputusan, mr.gpc, mr.kdk, mr.kdt, mr.pencapaian  FROM tnilai_smr mr, tmurid tm WHERE mr.kodsek='$kodsek' and mr.tahun='$tahun' AND mr.jpep='$jpep' AND mr.ting= :ting AND mr.nokp=tm.nokp and kodsek_semasa='$kodsek' GROUP BY tm.namap, mr.kelas, tm.nokp, mr.bilmp, mr.bila, mr.bilb, mr.bilc, mr.bild, mr.bile, mr.bilf, mr.bilth, mr.jummark, mr.peratus, mr.keputusan, mr.gpc, mr.kdk, mr.kdt, mr.pencapaian ORDER BY mr.keputusan ASC, mr.gpc ASC, mr.peratus DESC";
$qrt = oci_parse($conn_sispa,$q_nting);
oci_bind_by_name($qrt, ':ting', $ting);
oci_execute($qrt);
$parameter = array(":ting");
$value = array($ting);
$bilmting = kira_bil_rekod($q_nting,$parameter,$value);

$q_nkelas = "SELECT mr.kelas, COUNT(kelas) bilm  FROM tnilai_smr mr, tmurid tm WHERE mr.kodsek='$kodsek' and mr.tahun='$tahun' AND mr.jpep='$jpep' AND mr.ting= :ting AND mr.nokp=tm.nokp and kodsek_semasa='$kodsek' GROUP BY mr.kelas";
$stmt = oci_parse($conn_sispa,$q_nkelas);
oci_bind_by_name($stmt, ':ting', $ting);
oci_execute($stmt);
while($rowbil = oci_fetch_array($stmt))
{
	$bilpel[] = array("KELAS"=>$rowbil["KELAS"], "BIL"=>$rowbil["BILM"]);
}

while( $rownilai = oci_fetch_array($qrt))
{
	$penilai[] = $rownilai ;
}

if (!empty($penilai))
{
	$bil = 0;
	foreach( $penilai as $key => $nilai )
	{
		$bil=$bil+1;
		$kelas = $nilai["KELAS"];
		
		$bilpelkls = semakbilkls($kelas, $bilpel);
	
		echo "    <td>$bil</td>\n";
		echo "    <td>$nilai[NAMAP]</td>\n";
		echo "    <td>$nilai[KELAS]</td>\n";
		echo "    <td><center>$nilai[BILMP]</td>\n";
		echo "    <td><center>$nilai[BILA]</td>\n";
		echo "    <td><center>$nilai[BILB]</td>\n";
		echo "    <td><center>$nilai[BILC]</td>\n";
		echo "    <td><center>$nilai[BILD]</td>\n";
		echo "    <td><center>$nilai[BILE]</td>\n";
		if($tahun>=2015){
			echo "    <td><center>&nbsp;$nilai[BILF]</td>\n";
		}
		echo "    <td><center>$nilai[BILTH]</td>\n";
		echo "    <td><center>$nilai[JUMMARK]</td>\n";
		echo "    <td><center>$nilai[PERATUS]</td>\n";
		echo "    <td><center>$nilai[KEPUTUSAN]</td>\n";
		echo "    <td><center>$nilai[GPC]&nbsp;&nbsp;</td>\n";
		echo "    <td><center>$nilai[KDK]/$bilpelkls&nbsp;&nbsp;</td>\n";
		echo "    <td><center>$nilai[KDT]/$bilmting&nbsp;&nbsp;</td>\n";
		echo "    <td>$nilai[PENCAPAIAN]</td>\n";
		echo "</tr>";
	}
}
else {
	echo "<br>";
	echo "<td colspan = \"17\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
	echo "<br>";
	echo "</tr>";
 }
echo "</table>\n";

function semakbilkls($elem, $arraybilpel)
{
	foreach ($arraybilpel as  $key => $value)
	{
		if ($elem == $value["KELAS"]) { return $value["BIL"]; }
	}
}

echo "</body>";
echo "</html>";
 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?> 