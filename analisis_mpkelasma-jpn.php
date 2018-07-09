<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';

$ting2=$_POST["ting"];
$ting= substr($ting2,0,2);

$kelas2=$_POST["kelas"];
$kelas= htmlspecialchars($kelas2, ENT_QUOTES);

$tahun = $_SESSION['tahun'];
$kodsek = $_SESSION['kodsek2'];
$jpep = $_SESSION['jpep'];

$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='".$_SESSION['tahun']."' AND gk.kodsek='".$_SESSION['kodsek2']."' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek");
OCIExecute($q_guru);
OCIFetch($q_guru);
$namagu = OCIResult($q_guru,"NAMA");//$row[nama]; 
$namasek = OCIResult($q_guru,"NAMASEK");//$row[namasek];

$m="$ting&&kelas=$kelas&&namaguru=".urlencode($namagu)."&&namasekolah=$namasek";
$m2="$ting&kelas=".urlencode($kelas)."";

?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=200,height=200,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-200,screen.height/2-100);
}
</script>


<form action="ctk_analisis-mpkelasma-jpn.php?ting=<?php echo $m2;?>" method="POST" target="_blank">

<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Mata Pelajaran Ikut Kelas</p>

<?php


//echo "<a href=../index.php> Kembali</a><br>";
echo "<h5><center>$namasek<br>ANALISA MATA PELAJARAN KELAS<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h5>";
echo "<b>GURU KELAS : $namagu<br>KELAS : $ting $kelas</b><br><br>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#FFCC99\">";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Mata Pelajaran </div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A+</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A-</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">B+</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">C+</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">E</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">G</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Lulus /<br>Gagal</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Tidak Layak Sijil</div></td>\n";
echo "	<td rowspan=\"2\"><div align=\"center\">GPMP</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td ><div align=\"center\">Daftar</div></td>\n";
echo "    <td ><div align=\"center\">Ambil</div></td>\n";
echo "    <td ><div align=\"center\">TH</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td ><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td ><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "  </tr>\n";

$bil=0;

$q_mp = "SELECT * FROM analisis_mpma amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp=mp.kod ORDER BY mp.susun";
$qry = oci_parse($conn_sispa,$q_mp);
oci_execute($qry);
$bilmp = count_row("SELECT * FROM analisis_mpma amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp=mp.kod ORDER BY amp.kodmp");

if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($qry))
	{
		$lulus = $rowmp["AP"]+$rowmp["A"]+$rowmp["AM"]+$rowmp["BP"]+$rowmp["B"]+$rowmp["C"]+$rowmp["CP"]+$rowmp["D"]+$rowmp["E"] ;
		$bil++;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "  <tr bgcolor='$bcol'>\n";
		echo "    <td>".$bil."</td>\n";
		echo "    <td>".$rowmp["MP"]."</td>\n";
		echo "    <td><center>".$rowmp["BCALON"]."</center></td>\n";
		echo "    <td><center>".$rowmp["AMBIL"]."</center></td>\n";
		echo "    <td><center>".$rowmp["TH"]."</center></td>\n";
		echo "    <td><center>".$rowmp["AP"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["AP"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["A"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["A"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["AM"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["AM"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["BP"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BP"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["B"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["B"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["CP"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["CP"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["C"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["C"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["D"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["D"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["E"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["E"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["G"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["G"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$lulus</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["G"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["G"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".gpmpma($rowmp["AP"], $rowmp["A"], $rowmp["AM"], $rowmp["BP"], $rowmp["B"], 
								$rowmp["CP"], $rowmp["C"], $rowmp["D"], $rowmp["E"], $rowmp["G"], $rowmp["AMBIL"])."</center></td>\n";
		echo "  </tr>\n";
	}
}
else {
		echo "<tr>";
		echo "<br>";
		echo "<td colspan = \"30\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
?>
<br><br>
&nbsp;&nbsp;<input type="submit" name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('data-analisis-mpkelasma-excel-jpn.php?ting=<?php echo $m;?>','win1');" />
</form>

<?php
include 'kaki.php';
?>
