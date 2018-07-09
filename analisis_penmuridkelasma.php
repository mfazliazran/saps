<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
include "input_validation.php";

$tahun = $_SESSION['tahun'];
$ting=validate($_POST["ting"]);
$kelas=validate($_POST["kelas"]);

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
$m2="$ting&kelas=".urlencode($kelas)."";
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Pencapaian Murid Mengikut Kelas</p>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=200,height=200,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-200,screen.height/2-100);
}
</script>

<form action="ctk_analisis-penmuridkelasma.php?ting=<?php echo $m2;?>" method="POST" target="_blank">

<?php


echo "<h5><center>$namasek<br>ANALISIS KEPUTUSAN PEPERIKSAAN MURID<br>".jpep($jpep)." TAHUN $tahun</center></h5><br>";
echo "<b>GURU KELAS : $namagu<br>KELAS : $ting $kelas </b><br><br>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\">Bil MP<br>Ambil</td>\n";
echo "    <td colspan=\"11\"><div align=\"center\">Bilangan Gred </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Keputusan</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Kiraan GP<br>Murid</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
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

$q_nilai = "SELECT tm.namap, tm.nokp, ma.bilmp, ma.bilap, ma.bila, ma.bilam, ma.bilbp, ma.bilb, ma.bilcp, ma.bilc, ma.bild, ma.bile, ma.bilg, ma.bilth, ma.jummark, ma.peratus, ma.keputusan, ma.gpc, ma.kdk, ma.pencapaian  FROM tnilai_sma ma, tmurid tm WHERE ma.kodsek='$kodsek' and ma.tahun='$tahun' AND ma.jpep='$jpep' AND ma.ting='$ting' AND ma.kelas='$kelas' AND ma.nokp=tm.nokp and kodsek_semasa='$kodsek' GROUP BY  tm.namap, tm.nokp, ma.bilmp, ma.bilap, ma.bila, ma.bilam, ma.bilbp, ma.bilb, ma.bilcp, ma.bilc, ma.bild, ma.bile, ma.bilg, ma.bilth, ma.jummark, ma.peratus, ma.keputusan, ma.gpc, ma.kdk, ma.pencapaian ORDER BY ma.keputusan Desc, ma.gpc Asc, ma.peratus Desc";
$q_nilai = "SELECT tm.namap, tm.nokp, ma.bilmp, ma.bilap, ma.bila, ma.bilam, ma.bilbp, ma.bilb, ma.bilcp, ma.bilc, ma.bild, ma.bile, ma.bilg, ma.bilth, ma.jummark, ma.peratus, ma.keputusan, ma.gpc, ma.kdk, ma.pencapaian  FROM tnilai_sma ma, tmurid tm WHERE ma.kodsek='$kodsek' and ma.tahun='$tahun' AND ma.jpep='$jpep' AND ma.ting= :ting AND ma.kelas= :kelas AND ma.nokp=tm.nokp and kodsek_semasa='$kodsek' GROUP BY  tm.namap, tm.nokp, ma.bilmp, ma.bilap, ma.bila, ma.bilam, ma.bilbp, ma.bilb, ma.bilcp, ma.bilc, ma.bild, ma.bile, ma.bilg, ma.bilth, ma.jummark, ma.peratus, ma.keputusan, ma.gpc, ma.kdk, ma.pencapaian ORDER BY ma.keputusan Desc, ma.gpc Asc, ma.peratus Desc";
$qry_nilai = oci_parse($conn_sispa,$q_nilai);
oci_bind_by_name($qry_nilai, ':ting', $ting);
oci_bind_by_name($qry_nilai, ':kelas', $kelas);
oci_execute($qry_nilai);
$bilmurid = 0;

while($rownilai = oci_fetch_array($qry_nilai))
{
	$penilai[] = $rownilai ;
	$bilmurid++;
}

if (!empty($penilai))
{
	$bil = 0;
	foreach( $penilai as $key => $nilai )
	{
		$bil=$bil+1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "  <tr bgcolor='$bcol'>\n";
		echo "    <td>$bil</td>\n";
		echo "    <td>$nilai[NAMAP]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILMP]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILAP]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILA]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILAM]</td>\n";	
		echo "    <td><center>&nbsp;$nilai[BILBP]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILB]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILCP]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILC]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILD]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILE]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILG]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILTH]</td>\n";
		echo "    <td><center>&nbsp;$nilai[JUMMARK]</td>\n";
		echo "    <td><center>&nbsp;$nilai[PERATUS]</td>\n";
		echo "    <td><center>$nilai[KEPUTUSAN]</td>\n";
		echo "    <td><center>&nbsp;&nbsp;$nilai[GPC]&nbsp;&nbsp;</td>\n";
		echo "    <td><center>&nbsp;&nbsp;$nilai[KDK]/$bilmurid&nbsp;&nbsp;</td>\n";
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
?>
<br><br>
&nbsp;&nbsp;<input type="submit" name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('data-analisis-penmuridkelasma-excel.php?ting=<?php echo $m;?>','win1');" />
</form>
<?php 
include 'kaki.php';
?> 