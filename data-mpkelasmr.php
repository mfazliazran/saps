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
$filename = "Analisis_MP_Kelas_".$ting."_".$kelas."";

header('Content-type: application/vnd.ms-excel ');
header('Content-Disposition: attachment; filename="Analisis_MP_Kelas_'.$ting.'_'.$kelas.'_'.$jpep.'_'.$tahun.'.xls"');
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
echo "<h5><center>$namasek<br>ANALISIS MATA PELAJARAN KELAS<br>".("".jpep($_SESSION['jpep'])."")."<br>TAHUN ".$_SESSION['tahun']."</center></h5>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr>";
echo "GURU KELAS : $namagu<br>KELAS : $ting $kelas ";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Mata Pelajaran</div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">E</div></td>\n";
if($tahun>=2015){
	echo "    <td colspan=\"2\"><div align=\"center\">F</div></td>\n";
}
echo "	<td colspan=\"2\"><div align=\"center\">Lulus</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Gagal</div></td>\n";
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
echo "    <td ><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
if($tahun>=2015){//GRED F
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
}
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
$bil=1;

$q_mp = "SELECT * FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting= :ting AND amp.kelas= :kelas AND amp.kodmp=mp.kod ORDER BY mp.susun";
$qry = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($qry, ':ting', $ting);
oci_bind_by_name($qry, ':kelas', $kelas);
oci_execute($qry);
$sqlmp = "SELECT * FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting= :ting AND amp.kelas= :kelas AND amp.kodmp=mp.kod ORDER BY amp.kodmp";
$parameter=array(":ting",":kelas");
$value=array($ting,$kelas);
$bilmp = kira_bil_rekod($sqlmp,$parameter,$value);

if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($qry))
	{
		if($tahun<=2014){
			$lulus = $rowmp["A"]+$rowmp["B"]+$rowmp["C"]+$rowmp["D"] ;
		}else{//>2015
			$lulus = $rowmp["A"]+$rowmp["B"]+$rowmp["C"]+$rowmp["D"]+$rowmp["E"] ;
		}
		
		echo "    <td>".$bil++."</td>\n";
		echo "    <td>".$rowmp["MP"]."</td>\n";
		echo "    <td><center>".$rowmp["BCALON"]."</center></td>\n";
		echo "    <td><center>".$rowmp["AMBIL"]."</center></td>\n";
		echo "    <td><center>".$rowmp["TH"]."</center></td>\n";
		echo "    <td><center>".$rowmp["A"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["A"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["B"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["B"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["C"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["C"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["D"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["D"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["E"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["E"], $rowmp["AMBIL"])."</center></td>\n";
		if($tahun>=2015){//2015 ke atas
			echo "    <td><center>".$rowmp["F"]."</center></td>\n";
			echo "    <td><center>".peratus($rowmp["F"], $rowmp["AMBIL"])."</center></td>\n";
		}
		echo "    <td><center>$lulus</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rowmp["AMBIL"])."</center></td>\n";
		if($tahun<=2014){
			echo "    <td><center>".$rowmp["E"]."</center></td>\n";
			echo "    <td><center>".peratus($rowmp["E"], $rowmp["AMBIL"])."</center></td>\n";
		}
		if($tahun>=2015){//2015 ke atas
			echo "    <td><center>".$rowmp["F"]."</center></td>\n";
			echo "    <td><center>".peratus($rowmp["F"], $rowmp["AMBIL"])."</center></td>\n";
			echo "    <td><center>".gpmpmrsr_baru($rowmp["A"], $rowmp["B"], $rowmp["C"], $rowmp["D"], $rowmp["E"], $rowmp["F"], $rowmp["AMBIL"])."</center></td>\n";
		}else{//2014 ke bawah
			echo "    <td><center>".gpmpmrsr($rowmp["A"], $rowmp["B"], $rowmp["C"], $rowmp["D"], $rowmp["E"], $rowmp["AMBIL"])."</center></td>\n";
		}
		echo "  </tr>\n";
	}
}
else {
		echo "<tr>";
		echo "<br>";
		echo "<td colspan = \"20\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }
	 
echo "</table>\n";
?>

<br><br>

<?php
echo "</body>";
echo "</html>";
?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?> 
