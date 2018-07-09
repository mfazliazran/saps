<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';

$tahun = $_SESSION['tahun'];
$kodsek = $_SESSION['kodsek'];
$ting=$_POST["ting"];
$kelas=$_POST["kelas"];

$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='".$_SESSION['tahun']."' AND gk.kodsek='".$_SESSION['kodsek']."' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek");
OCIExecute($q_guru);
OCIFetch($q_guru);
$namagu = OCIResult($q_guru,"NAMA");
$namasek = OCIResult($q_guru,"NAMASEK");

$jpep = $_SESSION['jpep'];
$m="$ting&&kelas=$kelas";

?>
<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>

<form action="ctk_analisis-penmuridkelassr.php?ting=<?php echo $m;?>" method="POST" target="_blank">

<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Pencapaian Murid Mengikut Kelas</p>
<?php
echo "<h5><center>$namasek<br>ANALISIS KEPUTUSAN PEPERIKSAAN MURID<br>".jpep($jpep)."<br>TAHUN $tahun</center></h5><br>";
echo "<b>Guru Kelas : $namagu<br>Kelas &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : $ting $kelas </b>";
echo "<br><br>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
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
echo "    <td rowspan=\"2\"><div align=\"center\">Menguasai /<br>Tidak Menguasai</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">GPC</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td><div align=\"center\">A</div></td>\n";
echo "    <td><div align=\"center\">B</div></td>\n";
echo "    <td><div align=\"center\">C</div></td>\n";
echo "    <td><div align=\"center\">D</div></td>\n";
echo "    <td><div align=\"center\">E</div></td>\n";
if($tahun==2015){
	if($ting!='D6')
		echo "    <td><div align=\"center\">F</div></td>\n";
}elseif($tahun>=2016){
	// echo "    <td><div align=\"center\">F</div></td>\n";	
}
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";

// $q_nilai = "SELECT tm.namap, sr.bilmp, sr.bila, sr.bilb, sr.bilc, sr.bild, sr.bile, sr.bilf, sr.bilth, sr.jummark, sr.peratus, sr.keputusan, sr.gpc, sr.kdk, sr.pencapaian FROM tnilai_sr sr, tmuridsr tm WHERE sr.kodsek='$kodsek' AND sr.tahun='$tahun' AND sr.jpep='$jpep' AND sr.darjah='$ting' AND sr.kelas='$kelas' AND sr.nokp=tm.nokp and kodsek_semasa='$kodsek' GROUP BY tm.namap, sr.bilmp, sr.bila, sr.bilb, sr.bilc, sr.bild, sr.bile, sr.bilf, sr.bilth, sr.jummark, sr.peratus, sr.keputusan, sr.gpc, sr.kdk, sr.pencapaian Order By Case sr.keputusan When 'CEMERLANG' Then 1
//     When 'BAIK' Then 2
//     When 'MEMUASKAN' Then 3
// 	When 'MENCAPAI TAHAP MINIMUM' Then 4
// 	When 'BELUM MENCAPAI TAHAP MINIMUM' Then 5
// 	When 'Lulus' Then 6
//     Else 7 End, gpc ASC, peratus DESC";
$q_nilai = "SELECT tm.namap, sr.bilmp, sr.bila, sr.bilb, sr.bilc, sr.bild, sr.bile, sr.bilf, sr.bilth, sr.jummark, sr.peratus, sr.keputusan, sr.gpc, sr.kdk, sr.pencapaian FROM tnilai_sr sr, tmuridsr tm WHERE sr.kodsek='$kodsek' AND sr.tahun='$tahun' AND sr.jpep='$jpep' AND sr.darjah='$ting' AND sr.kelas='$kelas' AND sr.nokp=tm.nokp and kodsek_semasa='$kodsek' GROUP BY tm.namap, sr.bilmp, sr.bila, sr.bilb, sr.bilc, sr.bild, sr.bile, sr.bilf, sr.bilth, sr.jummark, sr.peratus, sr.keputusan, sr.gpc, sr.kdk, sr.pencapaian Order By Case sr.keputusan When 'CEMERLANG' Then 1
    When 'BAIK' Then 2
    When 'MEMUASKAN' Then 3
	When 'MENCAPAI TAHAP MINIMUM' Then 4
	When 'BELUM MENCAPAI TAHAP MINIMUM' Then 5
	When 'Lulus' Then 6
    Else 7 End, gpc ASC, peratus DESC";
//echo $q_nilai;
$smt = oci_parse($conn_sispa,$q_nilai);
oci_execute($smt);
$bilmurid = 0;
// $bilmurid = count_row($q_nilai);
$bil = 0;
while($rownilai = oci_fetch_array($smt))
{
	$penilai[] = $rownilai ;
	$bilmurid++;
}

// echo $bilx;

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
	echo "    <td><center>&nbsp;$nilai[BILA]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILB]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILC]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILD]</td>\n";
	echo "    <td><center>&nbsp;$nilai[BILE]</td>\n";
	if($tahun==2015){
		if($ting!='D6')
			echo "    <td><center>&nbsp;$nilai[BILF]</td>\n";
	}elseif($tahun>=2016){
		// echo "    <td><center>&nbsp;$nilai[BILF]</td>\n";	
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
?>
<br><br>
&nbsp;&nbsp;<input type="submit" name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('data-analisis-penmuridkelassr-excel.php?ting=<?php echo $m;?>','win1');" />
</form>


<?php
include 'kaki.php';
?> 