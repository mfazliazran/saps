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
$kodsek = $_SESSION['kodsek'];

$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun= :s_tahun AND gk.kodsek= :s_kodsek AND gk.ting= :ting AND gk.kelas= :kelas AND gk.kodsek=ts.kodsek");
oci_bind_by_name($q_guru, ':s_tahun', $_SESSION['tahun']);
oci_bind_by_name($q_guru, ':s_kodsek', $_SESSION['kodsek']);
oci_bind_by_name($q_guru, ':ting', $ting);
oci_bind_by_name($q_guru, ':kelas', $kelas);
OCIExecute($q_guru);
OCIFetch($q_guru);
$namagu = OCIResult($q_guru,"NAMA");
$namasek = OCIResult($q_guru,"NAMASEK");

$jpep = $_SESSION['jpep'];
$m="$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek";

?>
<html>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
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

<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>
<?php
echo "<h5><center>$namasek<br>ANALISIS KEPUTUSAN PEPERIKSAAN MURID<br>".jpep($jpep)."<br>TAHUN $tahun</center></h5><br>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo "<b>Guru Kelas : $namagu<br>Kelas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : $ting $kelas </b>";
echo "<br><br>";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\">Bil MP<br>Ambil</td>\n";
if($tahun<=2014){
	echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
}elseif($tahun==2015){
	if($ting=='D6')
		echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
	else
		echo "    <td colspan=\"7\"><div align=\"center\">Bilangan Gred </div></td>\n";
}else{
	echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
}
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Menguasai / <br>Tidak Menguasai</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">GPC</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\">A</div></td>\n";
echo "    <td><div align=\"center\">B</div></td>\n";
echo "    <td><div align=\"center\">C</div></td>\n";
echo "    <td><div align=\"center\">D</div></td>\n";
echo "    <td><div align=\"center\">E</div></td>\n";
if($tahun==2015){
	if($ting!='D6')
		echo "    <td><div align=\"center\">F</div></td>\n";
}
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

$q_nilai = "SELECT *  FROM tnilai_sr sr, tmuridsr tm WHERE sr.kodsek='$kodsek' AND sr.tahun='$tahun' AND sr.jpep='$jpep' AND sr.darjah= :ting AND sr.kelas= :kelas AND sr.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY sr.keputusan Desc, sr.gpc Asc, sr.peratus Desc";
$smt = oci_parse($conn_sispa,$q_nilai);
oci_bind_by_name($smt, ':ting', $ting);
oci_bind_by_name($smt, ':kelas', $kelas);
oci_execute($smt);
$parameter=array(":ting",":kelas");
$value=array($ting,$kelas);
$bilmurid = kira_bil_rekod($q_nilai,$parameter,$value);

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
	if($tahun==2015){
		if($ting!='D6')
			echo "    <td><center>&nbsp;$nilai[BILF]</td>\n";
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
echo "<br><br>";

if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>