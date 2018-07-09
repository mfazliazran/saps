<?php
session_start();
?>
<html>
<body>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<STYLE type="text/css">
@media print {
  #mybutton { display:none; visibility:hidden; }

  #mybutton2 { display:none; visibility:hidden; }
 
}

</STYLE>
<style type="text/css">
.style1 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 16px;
	color: #000000;
 	font-weight: bold; 
}

.style2 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;
 
}

.style3 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;
 	font-weight: bold; 
}

</style>

<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>

<?php

include 'config.php';
include 'fungsi.php';
include 'fungsikira.php';
$level=$_SESSION['level'];
  $m=$_GET['data'];
  list( $paparppd, $ting, $kodmp, $tahun, $jpep, $status)=split('[/]', $m);
 

  if($status=="SM"){ 
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod='$kodmp'"); 
		oci_execute($qmp);
		}
  if($status=="SR"){ 
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsr WHERE kod='$kodmp'"); 
		oci_execute($qmp);
		}
$rmp=oci_fetch_array($qmp);
	
   //echo "<span class=\"style1\">ANALISIS PEPERIKSAAN</span><br><br>" ; 
   //echo "<center><span class=\"style3\">ANALISIS PENCAPAIAN MATA PELAJARAN ".$rmp["MP"]." - ".tahap($ting)."" ; 
  
   $sqlppd = oci_parse($conn_sispa,"select ppd from tkppd where kodppd='$paparppd'");
oci_execute($sqlppd);
$rowppd = oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];
  
echo "<center><h3>ANALISIS PENCAPAIAN <br>MATA PELAJARAN ".$rmp["MP"]." <br>".jpep($jpep)." ".tahap($ting)." <br>DAERAH $namappd ($paparppd) TAHUN $tahun </h3></center><br>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
if (($ting=="T4") OR ($ting=="T5")){
echo "<tr>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Sekolah</div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED A+</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED A-</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED B+</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED C+</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED E</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Lulus</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED G</div></td>\n";
echo "	<td rowspan=\"2\"><div align=\"center\">GPS</div></td>\n";
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
echo "  </tr>\n";
echo "  <tr>\n";
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
  	
	if ($level=='8')
		$q_mp = "SELECT  analisis_mpma.kodsek, SUM(bcalon) as bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg 
		FROM analisis_mpma,tsekolah WHERE tahun='$tahun' AND analisis_mpma.kodppd='$paparppd' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' 
		and analisis_mpma.kodsek=tsekolah.kodsek and tsekolah.kodjenissekolah in ('202','203') 
		Group BY analisis_mpma.kodsek ORDER BY analisis_mpma.kodsek ";
	else
		$q_mp = "SELECT  kodsek, SUM(bcalon) as bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg 
		FROM analisis_mpma WHERE tahun='$tahun' AND kodppd='$paparppd' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' 
		Group BY kodsek ORDER BY kodsek ";
	
	$qry = oci_parse($conn_sispa,$q_mp);
	oci_execute($qry);
	
	//echo"ma--$q_mp<br>";
	while($rbmurid=oci_fetch_array($qry)){
	$bil=$bil+1;
	
			$jumdaftar+=(int) $rbmurid["BCALON"];
			$jumambil+=(int) $rbmurid["AMBIL"];
			$jumth+=(int) $rbmurid["TH"];
			$jumaplus+=(int) $rbmurid["BILAP"];
			$juma+=(int) $rbmurid["BILA"];
			$jumaminus+=(int) $rbmurid["BILAM"];
			$jumbplus+=(int) $rbmurid["BILBP"];
			$jumb+=(int) $rbmurid["BILB"];
			$jumcplus+=(int) $rbmurid["BILCP"];
			$jumc+=(int) $rbmurid["BILC"];
			$jumd+=(int) $rbmurid["BILD"];
			$jume+=(int) $rbmurid["BILE"];
			$jumg+=(int) $rbmurid["BILG"];
			$jumlulus+=(int) $rbmurid["BILAP"]+$rbmurid["BILA"]+$rbmurid["BILAM"]+$rbmurid["BILBP"]+$rbmurid["BILB"]+$rbmurid["BILBM"]+$rbmurid["BILCP"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];
			
	$lulus = $rbmurid["BILAP"]+$rbmurid["BILA"]+$rbmurid["BILAM"]+$rbmurid["BILBP"]+$rbmurid["BILB"]+$rbmurid["BILCP"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"] ;
	
	$kodsekpapar = $rbmurid["KODSEK"];
	$namasek1=oci_parse($conn_sispa,"select namasek from tsekolah where kodsek='$kodsekpapar'");
		oci_execute($namasek1);
		//echo"$namasek1";
  	$papar = oci_fetch_array($namasek1);
	$namasekolah = $papar["NAMASEK"];
	
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
	echo "    <td align=\"left\"><a href=mp-daerah-ind.php?data=".$kodppd."/".$kodsekpapar."/".$ting."/".$kodmp."/".$tahun."/".$jpep."/".$status."  target=_blank>$kodsekpapar - $namasekolah</a></span></td>\n";
	echo "    <td><center>".$rbmurid["BCALON"]."</left></span></td>\n";
	echo "    <td><center>".$rbmurid["AMBIL"]."</center></span></td>\n";
	echo "    <td><center>".$rbmurid["TH"]."</center></span></td>\n";
	echo "    <td><center>".$rbmurid["BILAP"]."</center></span></td>\n";
	echo "    <td><center>".peratus($rbmurid["BILAP"], $rbmurid["AMBIL"])."</center></td>\n";
	echo "    <td><center>".$rbmurid[BILA]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILA"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid[BILAM]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILAM"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid[BILBP]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILBP"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid[BILB]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILB"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid[BILCP]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILCP"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid[BILC]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILC"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid[BILD]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILD"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid[BILE]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILE"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$lulus."</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid[BILG]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILG"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "   <td><center>".gpmpma($rbmurid["BILAP"], $rbmurid["BILA"], $rbmurid["BILAM"], $rbmurid["BILBP"], $rbmurid["BILB"], 
								$rbmurid["BILCP"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["BILG"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "  </tr>\n";	
}	
		echo "  <tr>\n";
		echo "    <td colspan=\"2\"><center>Jumlah</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\">$jumdaftar</div></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\">$jumambil</div></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\">$jumth</div></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\">$jumaplus</div></td>\n";
		echo "    <td><center>".peratus($jumaplus, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\">$juma</div></td>\n";
		echo "    <td><center>".peratus($juma, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\">$jumaminus</div></td>\n";
		echo "    <td><center>".peratus($jumaminus, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumbplus</td>\n";
		echo "    <td><center>".peratus($jumbplus, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumb</td>\n";
		echo "    <td><center>".peratus($jumb, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumcplus</td>\n";
		echo "    <td><center>".peratus($jumcplus, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumc</td>\n";
		echo "    <td><center>".peratus($jumc, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumd</td>\n";
		echo "    <td><center>".peratus($jumd, $jumambil)."</center></td>\n";;
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jume</td>\n";
		echo "    <td><center>".peratus($jume, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumlulus</td>\n";
		echo "    <td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumg</td>\n";
		echo "    <td><center>".peratus($jumg, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\">".gpmpma($jumaplus, $juma, $jumaminus ,$jumbplus, $jumb, $jumcplus, $jumc, $jumd, $jume, $jumg, $jumambil)."</div></td>\n";
		echo "  </tr>\n";
		echo "   </table>\n";
		echo "<br><br><br>";
}//TAMAT MA
	
if (($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	echo "<tr>";
	echo "    <td rowspan=\"2\"><div align=\"center\">Bil</div></td>\n";
	echo "    <td rowspan=\"2\"><div align=\"center\">Sekolah</div></td>\n";
	echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED A</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED B</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED C</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED D</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED E</div></td>\n";
	if($tahun>=2015){
		echo "    <td colspan=\"2\"><div align=\"center\">GRED F</div></td>\n";
	}
	echo "	<td colspan=\"2\"><div align=\"center\">Lulus</div></td>\n";
	echo "	<td rowspan=\"2\"><div align=\"center\">GPS</div></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td ><div align=\"center\">Daftar</div></td>\n";
	echo "    <td ><div align=\"center\">Ambil</div></td>\n";
	echo "    <td ><div align=\"center\">TH</div></td>\n";
	echo "    <td ><div align=\"center\">Bil</div></td>\n";
	echo "    <td ><div align=\"center\">%</div></td>\n";
	echo "    <td ><div align=\"center\">Bil</div></td>\n";
	echo "    <td ><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	if($tahun>=2015){//F
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
	}
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";

	$bil=0;
	if ($level=='8')	
		$q_mp = "SELECT  analisis_mpmr.kodsek, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf 
		FROM analisis_mpmr,tsekolah WHERE tahun='$tahun' AND analisis_mpmr.kodppd='$paparppd' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' 
		analisis_mpmr.kodsek=tsekolah.kodsek and tsekolah.kodjenissekolah in ('202','203')
		Group BY analisis_mpmr.kodsek ORDER BY analisis_mpmr.kodsek ";
	else
		$q_mp = "SELECT  kodsek, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf 
		FROM analisis_mpmr WHERE tahun='$tahun' AND kodppd='$paparppd' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' 
		Group BY kodsek ORDER BY kodsek ";
	$qry = oci_parse($conn_sispa,$q_mp);
	oci_execute($qry);
	//echo"mr--$q_mp<br>";
	while($rbmurid=oci_fetch_array($qry)){
		$bil=$bil+1;
		$jumdaftar+=(int) $rbmurid["BCALON"];
		$jumambil+=(int) $rbmurid["AMBIL"];
		$jumth+=(int) $rbmurid["TH"];
		//$jumaplus+=(int) $rbmurid["BILAP"];
		$juma+=(int) $rbmurid["BILA"];
		//$jumaminus+=(int) $rbmurid["BILAM"];
		//$jumbplus+=(int) $rbmurid["BILBP"];
		$jumb+=(int) $rbmurid["BILB"];
		//$jumcplus+=(int) $rbmurid["BILCP"];
		$jumc+=(int) $rbmurid["BILC"];
		$jumd+=(int) $rbmurid["BILD"];
		$jume+=(int) $rbmurid["BILE"];
		$jumf+=(int) $rbmurid["BILF"];
		//$jumg+=(int) $rbmurid["BILG"];
		
		if($tahun<=2014){
			$jumlulus+=(int)$rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];		
			$lulus = $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];
		}else{// >=2015
			$jumlulus+=(int)$rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];		
			$lulus = $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];			
		}
	$kodsekpapar = $rbmurid["KODSEK"];
	$namasek1=oci_parse($conn_sispa,"select namasek from tsekolah where kodsek='$kodsekpapar'");
	oci_execute($namasek1);
  	$papar = oci_fetch_array($namasek1);
	$namasekolah = $papar["NAMASEK"];
	
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
	echo "    <td align=\"left\"><a href=mp-daerah-ind.php?data=".$kodppd."/".$kodsekpapar."/".$ting."/".$kodmp."/".$tahun."/".$jpep."/".$status."  target=_blank>$kodsekpapar - $namasekolah</a></span></td>\n";
	echo "    <td><center>".$rbmurid["BCALON"]."</center></span></td>\n";
	echo "    <td><center>".$rbmurid["AMBIL"]."</center></span></td>\n";
	echo "    <td><center>".$rbmurid["TH"]."</center></span></td>\n";
	echo "    <td><center>".$rbmurid[BILA]."</center></td>\n";
	echo "    <td><center>".peratus($rbmurid["BILA"], $rbmurid["AMBIL"])."</center></td>\n";
	echo "    <td><center>".$rbmurid[BILB]."</center></td>\n";
	echo "    <td><center>".peratus($rbmurid["BILB"], $rbmurid["AMBIL"])."</center></td>\n";
	echo "    <td><center>".$rbmurid[BILC]."</center></td>\n";
	echo "    <td><center>".peratus($rbmurid["BILC"], $rbmurid["AMBIL"])."</center></td>\n";
	echo "    <td><center>".$rbmurid[BILD]."</center></td>\n";
	echo "    <td><center>".peratus($rbmurid["BILD"], $rbmurid["AMBIL"])."</center></td>\n";
	echo "    <td><center>".$rbmurid[BILE]."</center></td>\n";
	echo "    <td><center>".peratus($rbmurid["BILE"], $rbmurid["AMBIL"])."</center></td>\n";
	if($tahun>=2015){
		echo "    <td><center>".$rbmurid[BILF]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILF"], $rbmurid["AMBIL"])."</center></td>\n";
	}
	echo "    <td><center>".$lulus."</center></td>\n";
	echo "    <td><center>".peratus($lulus, $rbmurid["AMBIL"])."</center></td>\n";
	echo "    <td><center>".gpmpmrsr_baru($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["BILF"], $rbmurid["AMBIL"])."</center></td>\n";
	echo "  </tr>\n";
	}
		
	echo "<tr>\n";
	echo "<td colspan=\"2\"><center>Jumlah</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">$jumdaftar</div></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">$jumambil</div></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">$jumth</div></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">$juma</div></td>\n";
	echo "    <td><center>".peratus($juma, $jumambil)."</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jumb</td>\n";
	echo "    <td><center>".peratus($jumb, $jumambil)."</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jumc</td>\n";
	echo "    <td><center>".peratus($jumc, $jumambil)."</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jumd</td>\n";
	echo "    <td><center>".peratus($jumd, $jumambil)."</center></td>\n";;
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jume</td>\n";
	echo "    <td><center>".peratus($jume, $jumambil)."</center></td>\n";
	if($tahun>=2015){
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumf</td>\n";
		echo "<td><center>".peratus($jumf, $jumambil)."</center></td>\n";
	}
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jumlulus</td>\n";
	echo "    <td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr_baru($juma, $jumb, $jumc, $jumd, $jume, $jumf, $jumambil)."</div></td>\n";
	echo "  </tr>\n";
	echo "   </table>\n";
	echo "<br><br><br>";
}//TAMAT MR
	
###################### SEKOLAH RENDAH ##################################################################
if (($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	
	echo "<tr>";
	echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
	echo "    <td rowspan=\"2\"><div align=\"center\">Sekolah</div></td>\n";
	echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED A</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED B</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED C</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED D</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">GRED E</div></td>\n";
	if($tahun==2015){
		if($ting<>"D6"){//D1-D5
			echo "<td colspan=\"2\"><div align=\"center\">GRED F</div></td>\n";
		}
	}
	echo "	<td colspan=\"2\"><div align=\"center\">Lulus</div></td>\n";
	//echo "	<td colspan=\"2\"><div align=\"center\">Gagal</div></td>\n";
	echo "	<td rowspan=\"2\"><div align=\"center\">GPS</div></td>\n";
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
	if($tahun==2015){
		if($ting<>"D6"){//D1-D5
			echo "    <td><div align=\"center\">Bil</div></td>\n";
			echo "    <td><div align=\"center\">%</div></td>\n";
		}
	}
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	//echo "    <td><div align=\"center\">Bil</div></td>\n";
	//echo "    <td><div align=\"center\">%</div></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";

	$bil=0;
	if ($level=='8')
		$q_mp = "SELECT  analisis_mpsr.kodsek, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf 
		FROM analisis_mpsr,tsekolah WHERE tahun='$tahun' AND analisis_mpsr.kodppd='$paparppd' AND jpep='$jpep' AND  darjah='$ting' AND kodmp='$kodmp' 
		analisis_mpsr.kodsek=tsekolah.kodsek and tsekolah.kodjenissekolah in ('202','203') 
		Group BY analisis_mpsr.kodsek ORDER BY analisis_mpsr.kodsek ";
	else
		$q_mp = "SELECT  kodsek, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf 
		FROM analisis_mpsr WHERE tahun='$tahun' AND kodppd='$paparppd' AND jpep='$jpep' AND  darjah='$ting' AND kodmp='$kodmp' 
		Group BY kodsek ORDER BY kodsek ";
		$qry = oci_parse($conn_sispa,$q_mp);
		oci_execute($qry);
	//echo"sr--$q_mp<br>";
	while($rbmurid=oci_fetch_array($qry)){
		$bil=$bil+1;
		$jumdaftar+=(int) $rbmurid["BCALON"];
		$jumambil+=(int) $rbmurid["AMBIL"];
		$jumth+=(int) $rbmurid["TH"];
		$juma+=(int) $rbmurid["BILA"];
		$jumb+=(int) $rbmurid["BILB"];
		$jumc+=(int) $rbmurid["BILC"];
		$jumd+=(int) $rbmurid["BILD"];
		$jume+=(int) $rbmurid["BILE"];
		$jumf+=(int) $rbmurid["BILF"];
		
		if($tahun<=2014){
			$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];
			$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];
		}elseif($tahun==2015){
			if($ting<>"D6"){//D1-D5
				$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];
				$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];	
			}else{//D6
				$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];
				$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];
			}
		}else{//2016 D1-D6
			$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];
			$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];		
		}
	
		$kodsekpapar = $rbmurid["KODSEK"];
		$namasek1=oci_parse($conn_sispa,"select namasek from tsekolah where kodsek='$kodsekpapar'");
		oci_execute($namasek1);
		//echo"$namasek1";
		$papar = oci_fetch_array($namasek1);
		$namasekolah = $papar["NAMASEK"];
	
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "    <td align=\"left\"><a href=mp-daerah-ind.php?data=".$kodppd."/".$kodsekpapar."/".$ting."/".$kodmp."/".$tahun."/".$jpep."/".$status."  target=_blank>$kodsekpapar - $namasekolah</a></span></td>\n";
		echo "    <td><center>".$rbmurid["BCALON"]."</center></span></td>\n";
		echo "    <td><center>".$rbmurid["AMBIL"]."</center></span></td>\n";
		echo "    <td><center>".$rbmurid["TH"]."</center></span></td>\n";
		echo "    <td><center>".$rbmurid["BILA"]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILA"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid["BILB"]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILB"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid["BILC"]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILC"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid["BILD"]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILD"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".$rbmurid["BILE"]."</center></td>\n";
		echo "    <td><center>".peratus($rbmurid["BILE"], $rbmurid["AMBIL"])."</center></td>\n";
		if($tahun==2015){
			if($ting<>"D6"){//D1-D5
				echo "    <td><center>".$rbmurid["BILF"]."</center></td>\n";
				echo "    <td><center>".peratus($rbmurid["BILF"], $rbmurid["AMBIL"])."</center></td>\n";
			}
		}
		echo "    <td><center>".$lulus."</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rbmurid["AMBIL"])."</center></td>\n";
		if($tahun<=2014){
			echo "    <td><center>".gpmpmrsr($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["AMBIL"])."</center></td>\n";
		}elseif($tahun==2015){
			if($ting<>"D6"){//D1-D5
				echo "    <td><center>".gpmpmrsr_baru($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["BILF"], $rbmurid["AMBIL"])."</center></td>\n";
			}else{//D6
				echo "    <td><center>".gpmpmrsr($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["AMBIL"])."</center></td>\n";
			}
		}else{//2016 D1-D6
			echo "    <td><center>".gpmpmrsr_baru($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["BILF"], $rbmurid["AMBIL"])."</center></td>\n";
		}
		//echo "    <td><center>".$gagal."</center></td>\n";
		//echo "    <td><center>".peratus($gagal, $rbmurid[AMBIL])."</center></td>\n";
	echo "  </tr>\n";
		}
			
echo "  <tr>\n";
echo "    <td colspan=\"2\"><center>Jumlah</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumdaftar</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumambil</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumth</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$juma</div></td>\n";
echo "    <td><center>".peratus($juma, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumb</div></td>\n";
echo "    <td><center>".peratus($jumb, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumc</div></td>\n";
echo "    <td><center>".peratus($jumc, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumd</div></td>\n";
echo "    <td><center>".peratus($jumd, $jumambil)."</center></td>\n";;
echo "<td colspan=\"1\"><div align=\"center\">$jume</div></td>\n";
echo "    <td><center>".peratus($jume, $jumambil)."</center></td>\n";
if($tahun==2015){
	if($ting<>"D6"){
		echo "<td colspan=\"1\"><div align=\"center\">$jumf</div></td>\n";
		echo "    <td><center>".peratus($jumf, $jumambil)."</center></td>\n";
	}
} 
echo "<td colspan=\"1\"><div align=\"center\">$jumlulus</div></td>\n";
echo "<td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
if($tahun<=2014){
	echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr($juma, $jumb, $jumc, $jumd, $jume, $jumambil)."</div></td>\n";
}elseif($tahun==2015){
	if($ting<>""){//D1-D5
		echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr_baru($juma, $jumb, $jumc, $jumd, $jume, $jumf, $jumambil)."</div></td>\n";
	}else{//D6
		echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr($juma, $jumb, $jumc, $jumd, $jume, $jumambil)."</div></td>\n";
	}
}else{//2016 D1-D6
	echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr_baru($juma, $jumb, $jumc, $jumd, $jume, $jumf, $jumambil)."</div></td>\n";
}
echo "  </tr>\n";
echo "   </table>\n";
echo "<br><br><br>";
}//end SR

//echo "<center><input type=\"submit\" name=\"submit\" value=\"PAPARAN CETAK\"></center>\n";
//include 'kaki.php';?> 