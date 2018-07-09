<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include 'fungsikira.php';

$sqlppd = oci_parse($conn_sispa,"select ppd from tkppd where kodppd='$kodsek'");
oci_execute($sqlppd);
$rowppd = oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Peperiksaan Daerah <?php echo "$namappd" ?></p>

<?php

if (isset($_POST['mpep']))
{
//include 'config.php';
  $tahun=$_POST['tahun_semasa'];
  $kodmp=$_POST['kodmp'];
  $ting=$_POST['ting'];
  $jpep=$_POST['pep'];
  $status=$_POST['statush'];
  $kodppd=$_POST['kodppd'];
  $namappd=$_POST['namappd'];
  //echo">>$tahun>>$kodmp>>$ting>>$jpep>>$status>>$kodppd>>$namappd>>";
// <a href="ctk_mpep-daerah.php?tahun=<?php echo $tahun;&&ting=<?php echo $ting;&&jpep=<?php echo $jpep;&&status=<?php echo $status;&&kodmp=<?php echo $kodmp;//&&kodppd=<?php echo $kodppd;" target=_blank ><img src=printi.jpg border=0></a></div><br><br>

echo "<form action=\"ctk_mpep-dae.php\" method=\"POST\" target=_blank>";
//echo "<form action=\"jpn-jpep1.php\" method=\"POST\" target=_blank>";
  if(($status=="MR") OR ($status=="MA")){
  		$status = "SM";
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod='$kodmp'");
		oci_execute($qmp);
		}
  if($status=="SR"){
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsr WHERE kod='$kodmp'");
		oci_execute($qmp);
		}
	    $rmp = oci_fetch_array($qmp); //$rmp

echo "<center><h3>ANALISIS PENCAPAIAN <br>MATA PELAJARAN ".$rmp["MP"]." <br>".jpep($jpep)." ".tahap($ting)." <br>DAERAH $namappd ($kodppd) TAHUN $tahun </h3></center><br>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
if (($ting=="T4") OR ($ting=="T5")){
echo "<tr>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Sekolah </div></td>\n";
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
  	

	$q_mp = "SELECT  kodsek, SUM(bcalon) as bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg FROM analisis_mpma WHERE tahun='$tahun' AND kodppd='$kodppd' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' Group BY kodsek ORDER BY kodsek ";
	
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
	echo "<td><a href=mp-daerah-ind.php?data=".$kodppd."/".$kodsekpapar."/".$ting."/".$kodmp."/".$tahun."/".$jpep."/".$status."  target=_blank>$kodsekpapar - $namasekolah</a></span></td>\n";
	echo "    <td><center>".$rbmurid["BCALON"]."</center></span></td>\n";
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

####################################### MENENGAH RENDAH ###########################################	
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
	if($tahun>=2015){
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
	}
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";

	$bil=0;
	
	$q_mp = "SELECT  kodsek, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpmr WHERE tahun='$tahun' AND kodppd='$kodppd' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' Group BY kodsek ORDER BY kodsek ";
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
		}else{
			$jumlulus+=(int)$rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];
			$lulus = $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];			
		}
		
		$kodsekpapar = $rbmurid["KODSEK"];
		$namasek1=oci_parse($conn_sispa,"select namasek from tsekolah where kodsek='$kodsekpapar'");
		oci_execute($namasek1);
		$papar = oci_fetch_array($namasek1);
		$namasekolah = $papar["NAMASEK"];
	
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "    <td><a href=mp-daerah-ind.php?data=".$kodppd."/".$kodsekpapar."/".$ting."/".$kodmp."/".$tahun."/".$jpep."/".$status."  target=_blank>$kodsekpapar - $namasekolah</a></span></td>\n";
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
	}//while
			
	echo "<tr>\n";
	echo "<td colspan=\"2\"><center>Jumlah</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">$jumdaftar</div></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">$jumambil</div></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">$jumth</div></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">$juma</div></td>\n";
	echo "<td><center>".peratus($juma, $jumambil)."</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jumb</td>\n";
	echo "<td><center>".peratus($jumb, $jumambil)."</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jumc</td>\n";
	echo "<td><center>".peratus($jumc, $jumambil)."</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jumd</td>\n";
	echo "<td><center>".peratus($jumd, $jumambil)."</center></td>\n";;
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jume</td>\n";
	echo "<td><center>".peratus($jume, $jumambil)."</center></td>\n";
	if($tahun>=2015){
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumf</td>\n";
		echo "<td><center>".peratus($jumf, $jumambil)."</center></td>\n";
	}
	echo "<td colspan=\"1\"><div align=\"center\"></div>$jumlulus</td>\n";
	echo "<td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr_baru($juma, $jumb, $jumc, $jumd, $jume, $jumf, $jumambil)."</div></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "<br><br><br>";
}//TAMAT T1-T3 MR

######################### SEKOLAH RENDAH #################################################################	
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
			echo "    <td colspan=\"2\"><div align=\"center\">GRED F</div></td>\n";
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
	
	$q_mp = "SELECT  kodsek, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM analisis_mpsr WHERE tahun='$tahun' AND kodppd='$kodppd' AND jpep='$jpep' AND  darjah='$ting' AND kodmp='$kodmp' Group BY kodsek ORDER BY kodsek ";
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
			$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];//+$rbmurid["BILD"];
			$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];//+$rbmurid["BILD"];
		}elseif($tahun==2015){
			if($ting<>"D6"){//D1-D5
				$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];
				$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];				
			}else{//D6
				$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];//+$rbmurid["BILD"];
				$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];//+$rbmurid["BILD"];				
			}			
		}else{//D1-D6
			$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];
			$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];			
		}
	
		$kodsekpapar = $rbmurid["KODSEK"];
		$namasek1=oci_parse($conn_sispa,"select namasek from tsekolah where kodsek='$kodsekpapar'");
		oci_execute($namasek1);
		$papar = oci_fetch_array($namasek1);
		$namasekolah = $papar["NAMASEK"];
	
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "    <td><a href=mp-daerah-ind.php?data=".$kodppd."/".$kodsekpapar."/".$ting."/".$kodmp."/".$tahun."/".$jpep."/".$status."  target=_blank>$kodsekpapar - $namasekolah</a></span></td>\n";
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
			if($ting<>"D6"){
				echo "    <td><center>".$rbmurid["BILF"]."</center></td>\n";
				echo "    <td><center>".peratus($rbmurid["BILF"], $rbmurid["AMBIL"])."</center></td>\n";
			}
		}
		echo "    <td><center>".$lulus."</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".gpmpmrsr_baru($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["BILF"], $rbmurid["AMBIL"])."</center></td>\n";
		//echo "    <td><center>".$gagal."</center></td>\n";
		//echo "    <td><center>".peratus($gagal, $rbmurid[AMBIL])."</center></td>\n";
		echo "  </tr>\n";
	}//while
			
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
echo "    <td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr_baru($juma, $jumb, $jumc, $jumd, $jume, $jumf, $jumambil)."</div></td>\n";
echo "  </tr>\n";
echo "   </table>\n";
echo "<br><br><br>";
}//TAMAT SR

	
//echo "<center><input type=\"submit\" name=\"submit\" value=\"PAPARAN CETAK\"></center>\n";
//</form>
/*
echo "<form name=\"form1\" method=\"post\" action=\"\">\n";
echo "<br><br><center><input type=\"submit\" name=\"cetak\" onClick=\"window.open('ctk_mpep-daerah.php?data=".$bil."/".$kodsek."/TOV','mywindow','toolbal=no,menubar=yes,resizable=yes,scrollbars=yes')\" value=\"PAPARAN CETAK\"></center>\n";
echo "</form>\n";
*/

}
else { ?>

 <script type="text/javascript">
 
function pilih_status(status)
{
//alert(nama_bulan)

location.href="mpep-daerah.php?status=" + status

}

function pilih_tahun(tahun_semasa)
{
//alert(nama_bulan)
//alert(tahun)

status = document.f1.status.value

pep = document.f1.pep.value
location.href="mpep-daerah.php?status=" + status + "&tahun=" + tahun_semasa +"&pep=" + pep


}
</script>

<?php

if($tahun_semasa <> "") {
	$tahun = $tahun_semasa;
} else {
	$tahun = date("Y");
}

$_SESSION['tahun'] = $tahun;

$tahun_sekarang = date("Y");


?>
		<?php
		//echo "$kodsek";
		//echo "<br><br>";
		echo " <center></b>PENCAPAIAN MATA PELAJARAN</b></center>";
		echo "<br>";
		//echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";

		echo "  <tr bgcolor=\"#CCCCCC\">\n";

		//$SQLting = mysql_query("SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting");
$tahun = $_GET['tahun'];
 
		$status = $_GET['status'];
		if ($status=="")
		  $status="SR";
		 $pepr = $_GET["pep"];
			
	//echo">>>$tahun>>>$status";	  
		switch ($status)
		{
			case "MR" : $tahap = "MENENGAH RENDAH";  $tmp = "sub_mr"; $kodjpep = " where kod!='SPMC' and kod!='UPSRC'"; break;
			case "MA" : $tahap = "MENENGAH ATAS"; $tmp = "mpsmkc"; $kodjpep = " where kod!='PMRC' and kod!='UPSRC'"; break;
			case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "mpsr";$kodjpep = " where kod!='SPMC' and kod!='PMRC'"; break;
			default : $tahap = "Pilih Tahap"; break;
		}

     	
		echo "<form method=post name='f1' action='mpep-daerah.php'>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHAP</td><td><select name=\"status\" onchange=\"pilih_status(this.value)\">";
		
		?>
		<option <?php if ($status=="MR") echo " selected "; ?> value="MR">MENENGAH RENDAH</option>
		<option <?php if ($status=="MA") echo " selected "; ?> value="MA">MENENGAH ATAS</option>
		<option <?php if ($status=="SR") echo " selected "; ?> value="SR">SEKOLAH RENDAH</option>
		<?php echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";

		$SQLpep = oci_parse($conn_sispa,"SELECT DISTINCT kod, jenis,rank FROM jpep $kodjpep and kod not in ('LNS01','U2') ORDER BY rank");
		oci_execute($SQLpep);
		echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td><td>";
		echo "<select name='pep'><option value=''>Pilih Peperiksaan</option>";
		while($rowpep = oci_fetch_array($SQLpep)) {
			if($pepr==$rowpep["KOD"])
				echo  "<option value='".$rowpep["KOD"]."' selected='selected'>".$rowpep["JENIS"]."</option>";
			else
				echo  "<option value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
		}
		echo "</select>";
		echo "</td></tr>";
		
		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td>";
		echo "<select name=\"tahun_semasa\" id=\"tahun_semasa\" onchange=\"pilih_tahun(this.value)\">";
		echo "<option value=\"\">-- Pilih Tahun --</option>";
		for($thn = 2011; $thn <= $tahun_sekarang; $thn++ ){
			if($tahun == $thn){
				echo "<option value='$thn' selected>$thn</option>";
			} else {
				echo "<option value='$thn'>$thn</option>";
			}
		}			
		echo "</select>";	
		echo "</td></tr>";
			
		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
		echo "<select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			case "MR" :	echo "<option value=\"P\">P</option>";
						echo "<option value=\"T1\">T1</option>";
						echo "<option value=\"T2\">T2</option>";
						echo "<option value=\"T3\">T3</option>";
						break;

			case "MA" : echo "<option value=\"T4\">T4</option>";
						echo "<option value=\"T5\">T5</option>";
						break;

			case "SR" :	echo "<option value=\"D1\">D1</option>";
						echo "<option value=\"D2\">D2</option>";
						echo "<option value=\"D3\">D3</option>";
						echo "<option value=\"D4\">D4</option>";
						echo "<option value=\"D5\">D5</option>";
						echo "<option value=\"D6\">D6</option>";
						break;
		}
		echo "</select>";
		
		echo "</td></tr>";
		
		//////////        Starting of second drop downlist /////////
		/*if($tmp=='mpsmkc'){
			if($tahun=='2011')
				$qry = " where kod not in (select kod from mpsmkc where kod like '%MA%')";
			else
				$qry = " where kod not in (select kod from sub_mr)";
			
			$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp $qry ORDER BY mp");
			oci_execute($SQLmp);
			echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
			echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
			while($rowmp = oci_fetch_array($SQLmp)) {
				echo  "<option value='".$rowmp["KOD"]."'>".$rowmp["MP"]."</option>";
			}
				echo "</select>";
			} else {
				$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
				oci_execute($SQLmp);
				echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
				echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
				while($rowmp = oci_fetch_array($SQLmp)) {
					echo  "<option value='".$rowmp["KOD"]."'>".$rowmp["MP"]."</option>";
				}
				echo "</select>";
			}
		echo "</td></tr>";*/
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
		echo "<select name=\"kodmp\">";
		echo "<OPTION VALUE=\"\">Sila Pilih Mata Pelajaran</OPTION>";
		
		if ($status=="MR"){
			//if($ting == "P" or $ting == "T1" or $ting == "T2" or $ting == "T3"){
				$strSQL = "SELECT KOD,MP,KODLEMBAGA FROM mpsmkc where status_mp='1' and kod in (SELECT KODMP FROM sub_guru WHERE KELAS='$kelas' and TING='$ting') OR BARU IS NULL OR BARU='MR' ORDER BY mp";
		} 
		if($status=="MA"){
			//if($ting == "T4" or $ting == "T5"){
				$strSQL = "SELECT KOD,MP,KODLEMBAGA FROM mpsmkc where status_mp='1' and kod in (SELECT KODMP FROM sub_guru WHERE KELAS='$kelas' and TING='$ting') OR BARU IS NULL OR BARU='MA' ORDER BY mp";
			//}
		}
		if ($status=="SR"){
			$strSQL = "SELECT KOD,MP,KODLEMBAGA FROM mpsr where status_mp='1' ORDER BY mp";
		}
		echo $SQLpep;
		$rs = oci_parse($conn_sispa,$strSQL);
		oci_execute($rs);
		$nr = count_row($strSQL);
		for ($k=0; $k<$nr; $k++) {
			$r = oci_fetch_array($rs);
			echo "<OPTION VALUE=\"".$r["KOD"]."\">".$r["MP"].' / '.$r["KODLEMBAGA"]."</OPTION>";
			}

		echo "</select>";
		echo "</td></tr>";

		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</table><br><br>";
		 print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";
		echo "<center><input type='submit' name=\"mpep\" value=\"Hantar\"></center>";
		echo "</form>";
} ?>


</td>

<?php include 'kaki.php';?>

