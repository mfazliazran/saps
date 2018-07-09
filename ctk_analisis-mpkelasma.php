<?php
session_start();
include 'auth.php';
include 'config.php';
include 'fungsikira.php';
include "input_validation.php";

$tahun = $_SESSION['tahun'];
$ting = validate($_GET['ting']);
$kelas = validate($_GET['kelas']);
$kelas = oci_escape_string($kelas);
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];

$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun= :s_tahun AND gk.kodsek= :s_kodsek AND gk.ting= :ting AND gk.kelas= :kelas AND gk.kodsek=ts.kodsek");
oci_bind_by_name($q_guru, ':ting', $ting);
oci_bind_by_name($q_guru, ':kelas', $kelas);
OCIExecute($q_guru);
OCIFetch($q_guru);
$namagu = OCIResult($q_guru,"NAMA");
$namasek = OCIResult($q_guru,"NAMASEK");

$m="$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek";

?>

<html>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
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

<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>

<?php


//echo "<a href=../index.php> Kembali</a><br>";
echo "<h5><center>$namasek<br>ANALISA MATA PELAJARAN KELAS<br>".("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h5>";
echo "<b>GURU KELAS : $namagu<br>KELAS : $ting $kelas</b><br><br>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr>";
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
echo "  <tr>\n";
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
echo "  <tr>\n";
$bil=1;

$q_mp = "SELECT * FROM analisis_mpma amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting= :ting AND amp.kelas= :kelas AND amp.kodmp=mp.kod ORDER BY mp.susun";
$qry = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($qry, ':ting', $ting);
oci_bind_by_name($qry, ':kelas', $kelas);
oci_execute($qry);
$sqlmp = "SELECT * FROM analisis_mpma amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting= :ting AND amp.kelas= :kelas AND amp.kodmp=mp.kod ORDER BY amp.kodmp";
$parameter=array(":ting",":kelas");
$value=array($ting,$kelas);
$bilmp = kira_bil_rekod($sqlmp,$parameter,$value);

if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($qry))
	{
		$lulus = $rowmp["AP"]+$rowmp["A"]+$rowmp["AM"]+$rowmp["BP"]+$rowmp["B"]+$rowmp["C"]+$rowmp["CP"]+$rowmp["D"]+$rowmp["E"] ;
		
		echo "    <td>".$bil++."</td>\n";
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
echo "<br><br>";	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>
