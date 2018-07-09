<?php
session_start();
include 'auth.php';
include 'config.php';
include 'fungsikira.php';
include 'fungsi.php';
include 'input_validation.php';


$tahun = validate($_GET['tahun']);
$ting = validate($_GET['ting']);
$kodppd = validate($_SESSION['kodsek']);
$jpep = validate($_GET['jpep']);


$res=oci_parse($conn_sispa,"select ppd,kodppd from tkppd where kodppd= :kodppd");
oci_bind_by_name($res, ':kodppd', $kodppd);
oci_execute($res);
$datappd=oci_fetch_array($res);
$namappd=$datappd["PPD"];

echo "<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>";
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
if($tahun>=2015){
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

$q_mp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpmr amp, mpsmkc mp,tsekolah ts WHERE amp.tahun= :tahun AND amp.kodsek=ts.kodsek AND amp.jpep= :jpep AND  amp.ting= :ting AND amp.kodmp=mp.kod and ts.kodppd= :kodppd Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ";

$qry = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($qry, ':tahun', $tahun);
oci_bind_by_name($qry, ':jpep', $jpep);
oci_bind_by_name($qry, ':ting', $ting);
oci_bind_by_name($qry, ':kodppd', $kodppd);
oci_execute($qry);

$sqlbilmp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpmr amp, mpsmkc mp,tsekolah ts WHERE amp.tahun= :tahun AND amp.kodsek=ts.kodsek AND amp.jpep= :jpep AND  amp.ting= :ting AND amp.kodmp=mp.kod and ts.kodppd= :kodppd Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ";

$parameter=array(":tahun",":jpep",":ting",":kodppd");
$value=array($tahun,$jpep,$ting,$kodppd);
$bilmp = kira_bil_rekod($sqlbilmp,$parameter,$value);

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
		if($tahun<=2014){
			$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"] ;
		}else{
			$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"] ;
		}
		
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
		echo "    <td><center>".gpmpmrsr_baru($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["BILF"], $rowmp["AMBIL"])."</center></td>\n";
		echo "  </tr>\n";
		
		$jumdaftar+=(int) $rowmp["BCALON"];
		$jumambil+=(int) $rowmp["AMBIL"];
		$jumth+=(int) $rowmp["TH"];
		$juma+=(int) $rowmp["BILA"];
		$jumb+=(int) $rowmp["BILB"];
		$jumc+=(int) $rowmp["BILC"];
		$jumd+=(int) $rowmp["BILD"];
		$jume+=(int) $rowmp["BILE"];
		$jumf+=(int) $rowmp["BILF"];
		if($tahun<=2014){
			$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
		}else{
			$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
		}
		
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


echo "<table width=\"30%\"  border=\"1\" align=\"justify\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "  <tr>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">Mata Pelajaran Yang Tidak Dikira Untuk GPS </div></td>\n";

echo "  <tr>\n";
$bil=1;
$q_mp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpmr amp, mpsmkc mp,tsekolah ts WHERE amp.tahun= :tahun AND amp.kodsek=ts.kodsek AND amp.jpep= :jpep AND  amp.ting= :ting AND amp.kodmp=mp.kod and ts.kodppd= :kodppd Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ";

$qry = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($qry, ':tahun', $tahun);
oci_bind_by_name($qry, ':jpep', $jpep);
oci_bind_by_name($qry, ':ting', $ting);
oci_bind_by_name($qry, ':kodppd', $kodppd);
oci_execute($qry);

$sqlbilmp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpmr amp, mpsmkc mp,tsekolah ts WHERE amp.tahun= :tahun AND amp.kodsek=ts.kodsek AND amp.jpep= :jpep AND  amp.ting= :ting AND amp.kodmp=mp.kod and ts.kodppd= :kodppd Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ";

$parameter=array(":tahun",":jpep",":ting",":kodppd");
$value=array($tahun,$jpep,$ting,$kodppd);
$bilmp = kira_bil_rekod($sqlbilmp,$parameter,$value);

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
		$kodmp=$rowmp["KODMP"];
		$ressub=oci_parse($conn_sispa,"select MP from mpsr where KOD='$kodmp'");
		oci_execute($ressub);
		$datasub=oci_fetch_array($ressub);
		
		if (subjek_tak_ambil($rowmp["KODMP"])==1){	
		echo "    <tr><td>".$bil++."</td>\n";
		echo "    <td>".$rowmp["KODMP"]."/".$rowmp["MP"]."</td>\n";
		echo "  </tr>\n";

		}

	}
}

echo "</table>\n";


?> 
  <?php
function subjek_tak_ambil($kodmp)
{
 global $conn_sispa;
 $res=oci_parse($conn_sispa,"select mp from sub_mr_xambil where kod='$kodmp'");
 
 oci_execute($res);
 if ($data=oci_fetch_array($res))
    $takambil=1;
else
  $takambil=0;
return $takambil;  

} 
 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>
<br><br>