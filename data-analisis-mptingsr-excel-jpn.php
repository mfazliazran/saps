<?php

session_start();
include 'auth.php';
include 'config.php';
//include 'kepala.php';
//include 'menu.php';
include 'fungsikira.php';

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
//$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek2'];

$q_guru = OCIParse($conn_sispa,"SELECT NAMASEK FROM tsekolah WHERE kodsek='$kodsek'");
OCIExecute($q_guru);
OCIFetch($q_guru);
$namasek = OCIResult($q_guru,"NAMASEK");//$row[namasek];

$jpep = $_SESSION['jpep'];
$m="$ting&&namasekolah=$namasek";

   header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="analisis_mp_'.$jpep.'_'.$ting.'_'.$tahun.'.xls"');
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


echo "<h5><center>$namasek<br>ANALISA MATA PELAJARAN TAHUN $ting<br>".("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h5>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "  <tr>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Mata Pelajaran </div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">E</div></td>\n";
if($tahun>=2015){
	if($ting<>'D6'){
		echo "    <td colspan=\"2\"><div align=\"center\">F</div></td>\n";
	}
}
echo "	<td colspan=\"2\"><div align=\"center\">Menguasai</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Tidak<br>Menguasai</div></td>\n";
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
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
if($tahun>=2015){
	if($ting<>'D6'){
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
	}
}
echo "  </tr>\n";
echo "  <tr>\n";
$bil=1;

$q_mp = "SELECT amp.kodmp,mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf
					 FROM analisis_mpsr amp, mpsr mp WHERE amp.kodsek='$kodsek' and amp.tahun='$tahun' AND amp.jpep='$jpep' 
					 AND  amp.darjah='$ting' AND amp.kodmp=mp.kod Group BY mp.susun,mp.mp, amp.kodmp ORDER BY mp.susun ";
//					 echo $q_mp;
$smt = oci_parse($conn_sispa,$q_mp);
oci_execute($smt);
$bilmp = count_row($q_mp);
//echo "$bilmp ";

if ($bilmp != 0)
{
	$jumambil=0;
	$jumdaftar=0;
	$jumth=0;
	$juma=0;
	$jumb=0;
	$jumc=0;
	$jumd=0;
	$jume=0;
	$jumf=0;
	$jumlulus=0;
	$gps=0;
	
	while($rowmp = oci_fetch_array($smt))
	{
		if($tahun<=2014){
			$lulus = $rowmp[BILA]+$rowmp[BILB]+$rowmp[BILC] ;
			$gagal = $rowmp[BILD]+$rowmp[BILE] ;
		} elseif($tahun>=2015){
			if($ting=='D6'){
				$lulus = $rowmp[BILA]+$rowmp[BILB]+$rowmp[BILC]+$rowmp[BILD] ;
				$gagal = $rowmp[BILE]+$rowmp[BILF] ;
			} else {
				$lulus = $rowmp[BILA]+$rowmp[BILB]+$rowmp[BILC]+$rowmp[BILD]+$rowmp[BILE] ;
				$gagal = $rowmp[BILF] ;
			}
		}
		$kodmp=$rowmp[KODMP];
		$ressub=oci_parse($conn_sispa,"select MP from mpsr where KOD='$kodmp'");
		oci_execute($ressub);
		$datasub=oci_fetch_array($ressub);
		echo "    <td>".$bil++."</td>\n";
		echo "    <td>".$datasub["MP"]."</td>\n";
		echo "    <td><center>$rowmp[BCALON]</center></td>\n";
		echo "    <td><center>$rowmp[AMBIL]</center></td>\n";
		echo "    <td><center>$rowmp[TH]</center></td>\n";
		echo "    <td><center>$rowmp[BILA]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[BILA], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[BILB]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[BILB], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[BILC]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[BILC], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[BILD]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[BILD], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[BILE]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[BILE], $rowmp[AMBIL])."</center></td>\n";
		if($tahun>=2015){
			if($ting<>'D6'){
						echo "    <td><center>$rowmp[BILF]</center></td>\n";
						echo "    <td><center>".peratus($rowmp[BILF], $rowmp[AMBIL])."</center></td>\n";
			}
		}
		echo "    <td><center>$lulus</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$gagal</center></td>\n";
		echo "    <td><center>".peratus($gagal, $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>".gpmpmrsr_baru($rowmp[BILA], $rowmp[BILB], $rowmp[BILC], $rowmp[BILD], $rowmp[BILE], $rowmp[BILF], $rowmp[AMBIL])."</center></td>\n";
		echo "  </tr>\n";
		
		//if($rowmp["KODMP"]) { 
		if (subjek_tak_ambil($rowmp["KODMP"])==0){
			$jumdaftar+=(int) $rowmp["BCALON"];
			$jumambil+=(int) $rowmp["AMBIL"];
			$jumth+=(int) $rowmp["TH"];
			$juma+=(int) $rowmp["BILA"];
			$jumb+=(int) $rowmp["BILB"];
			$jumc+=(int) $rowmp["BILC"];
			$jumd+=(int) $rowmp["BILD"];
			$jume+=(int) $rowmp["BILE"];
			$jumf+=(int) $rowmp["BILF"];
			if($tahun<=2014) {
				$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"];//+$rowmp["BILD"];
				$jumgagal+=(int) $rowmp["BILD"]+$rowmp["BILE"];
			} elseif($tahun>=2015){
				if($ting=='D6'){
					$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];//+$rowmp["BILD"];
					$jumgagal+=(int) $rowmp["BILE"]+$rowmp["BILF"];
				} else {
					$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];//+$rowmp["BILD"];
					$jumgagal+=(int) $rowmp["BILF"];
				}
			}
		}
	}
echo "<td colspan=\"2\"><div align=\"center\"> JUMLAH (mengikut MP dikira GPS Sekolah)</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumdaftar</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumambil</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumth</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$juma</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($juma, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumb</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumb, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumc</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumc, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumd</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumd, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jume</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jume, $jumambil)."</div></td>\n";
if($tahun>=2015){
	if($ting<>'D6'){
		echo "<td colspan=\"1\"><div align=\"center\">$jumf</div></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumf, $jumambil)."</div></td>\n";
	}
}
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumlulus</td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumlulus, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumgagal</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumgagal, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr_baru($juma, $jumb, $jumc, $jumd, $jume, $jumf, $jumambil)."</div></td>\n";
}
else {
		echo "<br>";
		echo "<td colspan = \"20\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
?>
<br>
<?
//////////////////////////////////////////////////////////////////////////GPS///////////////////////////////////////////////////////////////////
echo "<table width=\"40%\"  border=\"1\" align=\"left\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr>";
echo "    <td ><div align=\"center\">Bil </div></td>\n";
echo "    <td ><div align=\"center\">Mata Pelajaran Yang Tidak Dikira Untuk GPS </div></td>\n";
echo "  </tr>\n";
$bil=1; 


					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
//echo $q_mp;
$bilmp = count_row($q_mp);


if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($qry))
	{
		$lulus = $rowmp["BILAP"]+$rowmp["BILA"]+$rowmp["BILAM"]+$rowmp["BILBP"]+$rowmp["BILB"]+$rowmp["BILCP"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILF"];
		if (subjek_tak_ambil($rowmp["KODMP"])==1){	
		echo "  <tr>\n";
		echo "    <td>".$bil++."</td>\n";
		echo "    <td>$rowmp[MP]</td>\n";
		//echo "    <td><center>$rowmp[BCALON]</center></td>\n";
		echo "  </tr>\n";
	}
	}
}
echo "</table>\n";
?> <?php
function subjek_tak_ambil($kodmp)
{
 global $conn_sispa;
 $res=oci_parse($conn_sispa,"select mp from sub_sr_xambil where kod='$kodmp'");
// echo "<br>select mp from sub_sr_xambil where kod='$kodmp'";
 oci_execute($res);
 if ($data=oci_fetch_array($res))
    $takambil=1;
else
  $takambil=0;
return $takambil;  

}
?>
<?php
echo "</body>";
echo "</html>";
?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?> 