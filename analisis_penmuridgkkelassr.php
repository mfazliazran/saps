<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
include "input_validation.php";
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Pencapaian Murid Kelas</p>

<?php

$tahun = $_SESSION['tahun'];
$ting = validate($_GET['ting']);
$kelas = validate($_GET['kelas']);
$namagu = validate($_GET['namaguru']);
$namasek = validate($_GET['namasekolah']);
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h5><center>$namasek<br>ANALISIS KEPUTUSAN PEPERIKSAAN MURID<br>".jpep($jpep)." TAHUN $tahun</center></h5><br>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "  <tr>";
echo "Guru Kelas : $namagu<br>Kelas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : $ting $kelas ";
//echo "<br><br>";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\">Bil MP<br>Ambil</td>\n";
echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Keputusan</div></td>\n";
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
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
$bil = 0;
$sqlmurid = "SELECT * FROM tnilai_sr sr, tmuridsr tm WHERE sr.tahun='$tahun' AND sr.jpep='$jpep' AND sr.darjah= :ting AND sr.kelas= :kelas AND sr.kodsek='$kodsek' AND sr.nokp=tm.nokp ORDER BY sr.keputusan Desc, sr.gpc Asc, sr.peratus Desc";
$q_nilai = oci_parse($conn_sispa,$sqlmurid);
oci_bind_by_name($q_nilai, ':ting', $ting);
oci_bind_by_name($q_nilai, ':kelas', $kelas);
oci_execute($q_nilai);
$parameter=array(":ting",":kelas");
$value=array($ting,$kelas);
$bilmurid = kira_bil_rekod($sqlmurid,$parameter,$value);

while($rownilai = oci_fetch_array($q_nilai))
{

	$bil=$bil+1;
	echo "    <td>$bil</td>\n";
	echo "    <td>".$rownilai["NAMAP"]."</td>\n";
	echo "    <td><center>&nbsp;".$rownilai["BILMP"]."</td>\n";
	echo "    <td><center>&nbsp;".$rownilai["BILA"]."</td>\n";
	echo "    <td><center>&nbsp;".$rownilai["BILB"]."</td>\n";
	echo "    <td><center>&nbsp;".$rownilai["BILC"]."</td>\n";
	echo "    <td><center>&nbsp;".$rownilai["BILD"]."</td>\n";
	echo "    <td><center>&nbsp;".$rownilai["BILE"]."</td>\n";
	echo "    <td><center>&nbsp;".$rownilai["BILTH"]."</td>\n";
	echo "    <td><center>&nbsp;".$rownilai["JUMMARK"]."</td>\n";
	echo "    <td><center>&nbsp;".$rownilai["PERATUS"]."</td>\n";
	echo "    <td><center>".$rownilai["KEPUTUSAN"]."</td>\n";
	echo "    <td><center>&nbsp;&nbsp;".$rownilai["GPC"]."&nbsp;&nbsp;</td>\n";
	echo "    <td><center>&nbsp;&nbsp;".$rownilai["KDK"]."/$bilmurid&nbsp;&nbsp;</td>\n";
	echo "    <td>".$rownilai["PENCAPAIAN"]."</td>\n";
	echo "</tr>";
}
echo "</table>\n";

include 'kaki.php';
?> 