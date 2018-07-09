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
echo "<h5><center>$namappd<br>ANALISA MATA PELAJARAN TAHUN $ting<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h5>";
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
if($tahun=="2015"){
	if($ting<>'D6'){
		echo "    <td colspan=\"2\"><div align=\"center\">F</div></td>\n";
	}	
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
if($tahun=="2015"){
	if($ting<>'D6'){
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
	}
}

echo "  </tr>\n";
echo "  <tr>\n";
$bil=1;

$q_mp = "SELECT amp.kodmp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpsr amp, mpsr mp,tsekolah ts WHERE amp.tahun= :tahun and amp.kodsek=ts.kodsek and ts.Kodppd= :kodppd AND amp.jpep= :jpep AND  amp.darjah= :ting AND amp.kodmp=mp.kod Group BY amp.kodmp ORDER BY amp.kodmp ";

$smt = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($smt, ':tahun', $tahun);
oci_bind_by_name($smt, ':kodppd', $kodppd);
oci_bind_by_name($smt, ':jpep', $jpep);
oci_bind_by_name($smt, ':ting', $ting);
oci_execute($smt);

$parameter=array(":tahun",":kodppd",":jpep",":ting");
$value=array($tahun,$kodppd,$jpep,$ting);
$bilmp = kira_bil_rekod($q_mp,$parameter,$value);


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
		} elseif($tahun==2015){
			if($ting<>'D6'){
				$lulus = $rowmp[BILA]+$rowmp[BILB]+$rowmp[BILC]+$rowmp[BILD]+$rowmp[BILE] ;
				$gagal = $rowmp[BILF] ;
			} else {
				$lulus = $rowmp[BILA]+$rowmp[BILB]+$rowmp[BILC]+$rowmp[BILD] ;
				$gagal = $rowmp[BILE]+$rowmp[BILF] ;
			}
		}else{
			$lulus = $rowmp[BILA]+$rowmp[BILB]+$rowmp[BILC]+$rowmp[BILD] ;
			$gagal = $rowmp[BILE] ;
		}
		$kodmp=$rowmp[KODMP];
		$ressub=oci_parse($conn_sispa,"select MP from mpsr where KOD='$kodmp'");
		oci_execute($ressub);
		$datasub=oci_fetch_array($ressub);
		echo "    <td>".$bil++."</td>\n";
		echo "    <td>".$datasub["MP"]."</td>\n";
		echo "    <td><center>$rowmp[BCALON]</center></td>\n";
		$jumdaftar+=(int) $rowmp["BCALON"];
		echo "    <td><center>$rowmp[AMBIL]</center></td>\n";
		$jumambil+=(int) $rowmp["AMBIL"];
		echo "    <td><center>$rowmp[TH]</center></td>\n";
		$jumth+=(int) $rowmp["TH"];
		echo "    <td><center>$rowmp[BILA]</center></td>\n";
		$juma+=(int) $rowmp["BILA"];
		echo "    <td><center>".peratus($rowmp[BILA], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[BILB]</center></td>\n";
		$jumb+=(int) $rowmp["BILB"];
		echo "    <td><center>".peratus($rowmp[BILB], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[BILC]</center></td>\n";
		$jumc+=(int) $rowmp["BILC"];
		echo "    <td><center>".peratus($rowmp[BILC], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[BILD]</center></td>\n";
		$jumd+=(int) $rowmp["BILD"];
		echo "    <td><center>".peratus($rowmp[BILD], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[BILE]</center></td>\n";
		$jume+=(int) $rowmp["BILE"];
		echo "    <td><center>".peratus($rowmp[BILE], $rowmp[AMBIL])."</center></td>\n";
		if($tahun==2015){
			if($ting<>"D6"){
				echo "    <td><center>$rowmp[BILF]</center></td>\n";
				$jumf+=(int) $rowmp["BILF"];
				echo "    <td><center>".peratus($rowmp[BILF], $rowmp[AMBIL])."</center></td>\n";
			}
	
		}
		echo "    <td><center>$lulus</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rowmp[AMBIL])."</center></td>\n";
		if($tahun<=2014){
			$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
			$jumgagal+=(int) $rowmp["BILD"]+$rowmp["BILE"];
		}elseif($tahun==2015){
			if($ting<>"D6"){
				$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
				$jumgagal+=(int) $rowmp["BILD"]+$rowmp["BILE"];
			}else{
				$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
				$jumgagal+=(int) $rowmp["BILF"];
			}
		}else{
			$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
			$jumgagal+=(int) $rowmp["BILE"];
		}
		echo "    <td><center>$gagal</center></td>\n";
		echo "    <td><center>".peratus($gagal, $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>".gpmpmrsr_baru($rowmp[BILA], $rowmp[BILB], $rowmp[BILC], $rowmp[BILD], $rowmp[BILE], $rowmp[BILF], $rowmp[AMBIL])."</center></td>\n";
		echo "  </tr>\n";
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
if($tahun==2015){
	if($ting<>'D6'){
		echo "<td colspan=\"1\"><div align=\"center\">$jumf</div></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
	}
}
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumlulus</td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumgagal</td>\n";
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
?> <br>
<?php

echo "<table width=\"30%\"  border=\"1\" align=\"left\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "  <tr>";
echo "    <td rowspan=\"1\"><div align=\"center\">Bil </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">Mata Pelajaran Yang Tidak Dikira GPS </div></td>\n";
echo "  </tr>\n";
$bil=1;

$q_mp = "SELECT amp.kodmp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf
					 FROM analisis_mpsr amp, mpsr mp,tsekolah ts
					 WHERE amp.tahun= :tahun and amp.kodsek=ts.kodsek 
					 and ts.Kodppd= :kodppd 
					 AND amp.jpep= :jpep 
					 AND  amp.darjah= :ting AND amp.kodmp=mp.kod Group BY amp.kodmp ORDER BY amp.kodmp ";

$smt = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($smt, ':tahun', $tahun);
oci_bind_by_name($smt, ':kodppd', $kodppd);
oci_bind_by_name($smt, ':jpep', $jpep);
oci_bind_by_name($smt, ':ting', $ting);
oci_execute($smt);

$parameter=array(":tahun",":kodppd",":jpep",":ting");
$value=array($tahun,$kodppd,$jpep,$ting);
$bilmp = kira_bil_rekod($q_mp,$parameter,$value);


if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($smt))
	{
		$kodmp=$rowmp[KODMP];
		$ressub=oci_parse($conn_sispa,"select MP from mpsr where KOD='$kodmp'");
		oci_execute($ressub);
		$datasub=oci_fetch_array($ressub);
		
		if (subjek_tak_ambil($rowmp["KODMP"])==1){	
		echo "    <td>".$bil++."</td>\n";
		echo "    <td>".$datasub["MP"]."</td>\n";
		echo "  </tr>\n";
	}
	}
}
else {
		echo "<br>";
		echo "<td colspan = \"20\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
?>
<?php
function subjek_tak_ambil($kodmp)
{
 global $conn_sispa;
 $res=oci_parse($conn_sispa,"select mp from sub_sr_xambil where kod='$kodmp'");
 
 oci_execute($res);
 if ($data=oci_fetch_array($res))
    $takambil=1;
else
  $takambil=0;
return $takambil;  

}

?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>
<br><br>


