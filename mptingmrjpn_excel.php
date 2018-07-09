<?php
session_start();
include 'auth.php';
include 'config.php';
include 'fungsikira.php';
include 'fungsi.php';

header('Content-type: application/vnd.ms-excel ');
header('Content-Disposition: attachment; filename="mpting_jpnmr.xls"');
echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>STATISTIK LINUS IKUT JPN</TITLE>";
echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
echo "<body>";

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];
$tahun = $_GET['tahun'];
$ting = $_GET['ting'];
$kodnegeri = $_SESSION['kodsek'];
$jpep = $_GET['jpep'];


$res=oci_parse($conn_sispa,"select negeri,kodnegeri from tknegeri where kodnegeri='$kodnegeri'");
oci_execute($res);
$datappd=oci_fetch_array($res);
$namappd=$datappd["NEGERI"];

echo "<h5><center>$namappd<br>ANALISA MATA PELAJARAN TINGKATAN $ting<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h5>";
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
if($tahun>="2015"){
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
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
if($tahun>="2015"){
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
}
echo "  </tr>\n";
echo "  <tr>\n";
$bil=1;

$q_mp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpmr amp, mpsmkc mp,tsekolah ts WHERE amp.tahun='$tahun' AND amp.kodsek=ts.kodsek AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kodmp=mp.kod and ts.kodnegerijpn='$kodnegeri' Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ";
//echo("$q_mp");
$qry = oci_parse($conn_sispa,$q_mp);
oci_execute($qry);
					 
$bilmp = count_row("SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpmr amp, mpsmkc mp,tsekolah ts WHERE amp.tahun='$tahun' AND amp.kodsek=ts.kodsek AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kodmp=mp.kod and ts.kodnegerijpn='$kodnegeri' Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ");
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
	while($rowmp = oci_fetch_array($qry))
	{
		// $lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"] ;
		if($tahun <= 2014){
			$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"] ;
		} elseif($tahun>=2015) {
			$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
		}
		//echo $rowmp['BILA'];
		echo "    <td>".$bil++."</td>\n";
		echo "    <td>".$rowmp["KODMP"]."/".$rowmp["MP"]."</td>\n";
		echo "    <td><center>".$rowmp["BCALON"]."</center></td>\n";
		echo "    <td><center>".$rowmp["AMBIL"]."</center></td>\n";
		echo "    <td><center>".$rowmp["TH"]."</center></td>\n";
		echo "    <td><center>".$rowmp["BILA"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILA"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["BILB"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILB"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["BILC"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILC"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["BILD"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILD"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rowmp["BILE"]."</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
		if($tahun>=2015){
			echo "    <td><center>".$rowmp["BILF"]."</center></td>\n";
			echo "    <td><center>".peratus($rowmp["BILF"], $rowmp["AMBIL"])."</center></td>\n";
		}
		echo "    <td><center>$lulus</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rowmp["AMBIL"])."</center></td>\n";
		if($tahun<=2014){
			echo "    <td><center>".$rowmp["BILE"]."</center></td>\n";
			echo "    <td><center>".peratus($rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
		}else{
			echo "    <td><center>".$rowmp["BILF"]."</center></td>\n";
			echo "    <td><center>".peratus($rowmp["BILF"], $rowmp["AMBIL"])."</center></td>\n";			
		}
		// echo "    <td><center>".gpmpmrsr($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".gpmpmrsr_baru($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["BILF"], $rowmp["AMBIL"])."</center></td>\n";
		echo "  </tr>\n";
		//if ($rowmp["KODMP"]<>"PM" AND $rowmp["KODMP"]<>"PSV" AND $rowmp["KODMP"]<>"SIV" AND $rowmp["KODMP"]<>"PJK" AND $rowmp["KODMP"]<>"KAQ"){
			$jumdaftar+=(int) $rowmp["BCALON"];
			$jumambil+=(int) $rowmp["AMBIL"];
			$jumth+=(int) $rowmp["TH"];
			$juma+=(int) $rowmp["BILA"];
			$jumb+=(int) $rowmp["BILB"];
			$jumc+=(int) $rowmp["BILC"];
			$jumd+=(int) $rowmp["BILD"];
			$jume+=(int) $rowmp["BILE"];
			$jumf+=(int) $rowmp["BILF"];
			// $jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
			if($tahun<=2014){
				$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
			} elseif($tahun>=2015){
				$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
			}
		//}
	}
echo "<td colspan=\"2\"><div align=\"center\"> JUMLAH (mengikut MP dikira GPS Sekolah)</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumdaftar</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumambil</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumth</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$juma</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumb</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumc</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumd</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jume</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
if($tahun>=2015){
	echo "<td colspan=\"1\"><div align=\"center\">$jumf</div></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
}
echo "<td colspan=\"1\"><div align=\"center\">$jumlulus</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
// echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr($juma, $jumb, $jumc, $jumd, $jume, $jumambil)."</div></td>\n";
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
<?php

/////////////////////////////////////////////////////////////MATA PELAJARAN YANG TIDAK DIKIRA UNTUK GPS////////////////////////////////////////////
echo "<table width=\"30%\"  border=\"1\" align=\"justify\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "  <tr>";
echo "    <td colspan=\"1\"><div align=\"center\">Bil </div></td>\n";
echo "    <td colspan=\"1\"><div align=\"center\">Mata Pelajaran Yang Tidak Dikira Untuk GPS </div></td>\n";
//echo " <td></td>";
//echo "  <tr>\n";
$bil=1;
$q_mp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpmr amp, mpsmkc mp,tsekolah ts WHERE amp.tahun='$tahun' AND amp.kodsek=ts.kodsek AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kodmp=mp.kod and ts.kodnegerijpn='$kodnegeri' Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ";
//echo("$q_mp");
$qry = oci_parse($conn_sispa,$q_mp);
oci_execute($qry);
					 
$bilmp = count_row("SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpmr amp, mpsmkc mp,tsekolah ts WHERE amp.tahun='$tahun' AND amp.kodsek=ts.kodsek AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kodmp=mp.kod and ts.kodnegerijpn='$kodnegeri' Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ");
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
	while($rowmp = oci_fetch_array($qry))
	{
		// $lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"] ;
		/*if($tahun<=2014){
			$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"] ;
		} elseif($tahun>=2015){
			if($ting<>'T4' or $ting<>'T5'){
				$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
			} else {
				$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"] ;
			}
		}*/
		if (subjek_tak_ambil($rowmp["KODMP"])==1){	
		echo "    <tr><td>".$bil++."</td>\n";
		echo "    <td>".$rowmp["KODMP"]."/".$rowmp["MP"]."</td>\n";
		echo "  </tr>\n";

		}

	} //while
}

echo "</table>\n";


?> 
<?php
function subjek_tak_ambil($kodmp)
{
 global $conn_sispa;
 $res=oci_parse($conn_sispa,"select mp from sub_mr_xambil where kod='$kodmp'");
 //echo "<br>select mp from sub_mr_xambil where kod='$kodmp'";
 oci_execute($res);
 if ($data=oci_fetch_array($res))
    $takambil=1;
else
  $takambil=0;
return $takambil;  

} 
?> 