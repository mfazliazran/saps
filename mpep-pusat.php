<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include 'fungsikira.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Peperiksaan Negeri Mengikut Mata Pelajaran.</p>
<script type="text/javascript">
 
function pilih_status(status)
{
	location.href="mpep-pusat.php?status=" + status
}

function pilih_tahun(tahun_semasa){
	status = document.f1.status.value
	pep = document.f1.pep.value
	location.href="mpep-pusat.php?status=" + status + "&tahun=" + tahun_semasa +"&pep=" + pep
}
</script>
<?php

if (isset($_POST['mpep']))
{
//include 'config.php';
  $tahun=htmlentities($_POST['tahun_semasa']);
  $kodmp=htmlentities($_POST['kodmp']);
  $ting=htmlentities($_POST['ting']);
  $jpep=htmlentities($_POST['pep']);
  $status=htmlentities($_POST['statush']);
  $kodppd=htmlentities($_POST['kodppd']);
  $namappd=htmlentities($_POST['namappd']);
  $namajpn=htmlentities($_POST['namajpn']);
  $level=$_SESSION['level'];

echo "<form action=\"ctk_mpep-pusat.php\" method=\"POST\" target=_blank>";
  	if(($status=="MR") OR ($status=="MA")){
  		//$status = "SM";
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod='$kodmp'");
		oci_execute($qmp);
	}
  	if($status=="SR"){
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsr WHERE kod='$kodmp'");
		oci_execute($qmp);
	}
	$rmp = oci_fetch_array($qmp); //$rmp
	
print "<input name=\"tahunpost\" type=\"hidden\" readonly value=\"$tahun\">";
print "<input name=\"kodmppost\" type=\"hidden\" readonly value=\"$kodmp\">";
print "<input name=\"tingpost\" type=\"hidden\" readonly value=\"$ting\">";
print "<input name=\"jpeppost\" type=\"hidden\" readonly value=\"$jpep\">";
print "<input name=\"statuspost\" type=\"hidden\" readonly value=\"$status\">";
print "<input name=\"levelpost\" type=\"hidden\" readonly value=\"$level\">";

echo "<center><h3>ANALISIS PENCAPAIAN <br>MATA PELAJARAN ".$rmp["MP"]." <br>".jpep($jpep)." ".tahap($ting)." <br>TAHUN $tahun </h3></center><br>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
if (($ting=="T4") OR ($ting=="T5")){
	echo "<tr>";
	echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
	echo "    <td rowspan=\"2\"><div align=\"center\">Negeri </div></td>\n";
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
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
  	
	if ($level=='8') // BPTV
		$q_mp = "SELECT  analisis_mpma.negeri, SUM(bcalon) as bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg FROM analisis_mpma,tsekolah WHERE tahun='$tahun' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' and analisis_mpma.kodsek=tsekolah.kodsek and tsekolah.kodjenissekolah in ('202','203') Group BY analisis_mpma.negeri ORDER BY analisis_mpma.negeri ";
	else
		$q_mp = "SELECT  negeri, SUM(bcalon) as bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam,SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg FROM analisis_mpma WHERE tahun='$tahun' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' Group BY negeri ORDER BY negeri ";
	
	$qry = oci_parse($conn_sispa,$q_mp);
	oci_execute($qry);
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
	
		$paparnegeri = $rbmurid["NEGERI"];
		
		$namanegeri1=oci_parse($conn_sispa,"select kodnegeri from tknegeri where negeri='$paparnegeri'");
		oci_execute($namanegeri1);
  		$papar = oci_fetch_array($namanegeri1);
		$kodnegeri = $papar["KODNEGERI"];
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "    <td><center><a href=mp-pusat-ind.php?data=".$kodnegeri."/".$ting."/".$kodmp."/".$tahun."/".$jpep."/".$status."  target=_blank>$paparnegeri</a></center></span></td>\n";
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
		echo "   <td><center>".gpmpma($rbmurid["BILAP"], $rbmurid["BILA"], $rbmurid["BILAM"], $rbmurid["BILBP"], $rbmurid["BILB"],$rbmurid["BILCP"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["BILG"], $rbmurid["AMBIL"])."</center></td>\n";
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
	}//end MA
	
	if (($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
		echo "<tr>";
		echo "    <td rowspan=\"2\"><div align=\"center\">Bil</div></td>\n";
		echo "    <td rowspan=\"2\"><div align=\"center\">Negeri</div></td>\n";
		echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED A</div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED B</div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED C</div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED D</div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED E</div></td>\n";
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
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";

		$bil=0;
		if ($level=='8') // BPTV
			$q_mp = "SELECT  analisis_mpmr.negeri, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpmr,tsekolah WHERE tahun='$tahun' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' and analisis_mpma.kodsek=tsekolah.kodsek and tsekolah.kodjenissekolah in ('202','203') Group BY analisis_mpmr.negeri ORDER BY analisis_mpmr.negeri ";			
		else
			$q_mp = "SELECT  negeri, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpmr WHERE tahun='$tahun' AND jpep='$jpep' AND  ting='$ting' AND kodmp='$kodmp' Group BY negeri ORDER BY negeri ";
			$qry = oci_parse($conn_sispa,$q_mp);
			oci_execute($qry);
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
				$jumlulus+=(int)$rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];
					
				$lulus = $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];
				$paparnegeri = $rbmurid["NEGERI"];
		
		$namanegeri1=oci_parse($conn_sispa,"select kodnegeri from tknegeri where negeri='$paparnegeri'");
		oci_execute($namanegeri1);
		$papar = oci_fetch_array($namanegeri1);
		$kodnegeri = $papar["KODNEGERI"];
	
	
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "    <td><center><a href=mp-pusat-ind.php?data=".$kodnegeri."/".$ting."/".$kodmp."/".$tahun."/".$jpep."/".$status."  target=_blank>$paparnegeri</a></center></span></td>\n";
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
		echo "    <td><center>".$lulus."</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".gpmpmrsr($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["AMBIL"])."</center></td>\n";
		echo "  </tr>\n";
	}
			
		echo "  <tr>\n";
		echo "    <td colspan=\"2\"><center>Jumlah</center></td>\n";
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
		echo "<td colspan=\"1\"><div align=\"center\"></div>$jumlulus</td>\n";
		echo "    <td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
		echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr($juma, $jumb, $jumc, $jumd, $jume, $jumambil)."</div></td>\n";
		echo "  </tr>\n";
		echo "   </table>\n";
		echo "<br><br><br>";
	}//end MR
	
	if (($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
		echo "<tr>";
		echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
		echo "    <td rowspan=\"2\"><div align=\"center\">Negeri</div></td>\n";
		echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED A</div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED B</div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED C</div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED D</div></td>\n";
		echo "    <td colspan=\"2\"><div align=\"center\">GRED E</div></td>\n";
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
		echo "    <td><div align=\"center\">%</div></td>\n";
		echo "    <td ><div align=\"center\">Bil</div></td>\n";
		echo "    <td ><div align=\"center\">%</div></td>\n";
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";


	$bil=0;
	if ($level=='8') // BPTV
		$q_mp = "SELECT  analisis_mpsr.negeri, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr,tsekolah WHERE tahun='$tahun' AND jpep='$jpep' AND  darjah='$ting' AND kodmp='$kodmp' and analisis_mpma.kodsek=tsekolah.kodsek and tsekolah.kodjenissekolah in ('202','203') Group BY analisis_mpsr.negeri ORDER BY analisis_mpsr.negeri ";
	else
		$q_mp = "SELECT  negeri, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr WHERE tahun='$tahun' AND jpep='$jpep' AND  darjah='$ting' AND kodmp='$kodmp' Group BY negeri ORDER BY negeri ";

	$qry = oci_parse($conn_sispa,$q_mp);
	oci_execute($qry);
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
		$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];//+$rbmurid["BILD"];
			
		$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"];//+$rbmurid["BILD"];
		$paparnegeri = $rbmurid["NEGERI"];
		
		$namanegeri1=oci_parse($conn_sispa,"select kodnegeri from tknegeri where negeri='$paparnegeri'");
		oci_execute($namanegeri1);
		$papar = oci_fetch_array($namanegeri1);
		$kodnegeri = $papar["KODNEGERI"];
	
	
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "    <td><a href=mp-pusat-ind.php?data=".$kodnegeri."/".$ting."/".$kodmp."/".$tahun."/".$jpep."/".$status."  target=_blank>$paparnegeri</a></span></td>\n";
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
		echo "    <td><center>".$lulus."</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rbmurid["AMBIL"])."</center></td>\n";
		echo "    <td><center>".gpmpmrsr($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["AMBIL"])."</center></td>\n";
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
	echo "<td colspan=\"1\"><div align=\"center\">$jumlulus</div></td>\n";
	echo "    <td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr($juma, $jumb, $jumc, $jumd, $jume, $jumambil)."</div></td>\n";
	echo "  </tr>\n";
	echo "   </table>\n";
	echo "<br><br><br>";
}//end SR	
	echo "<center><input type=\"submit\" name=\"submit\" value=\"PAPARAN CETAK\"></center>\n";
	echo "</form>";
}else { 

if($tahun_semasa <> "") {
	$tahun = $tahun_semasa;
} else {
	$tahun = date("Y");
}
$_SESSION['tahun'] = $tahun;
$tahun_sekarang = date("Y");

		echo " <center></b>PENCAPAIAN MATA PELAJARAN.</b></center>";
		echo "<br>";
		echo "<form method='post' name='f1' action='mpep-pusat.php'>";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		$tahun = $_GET['tahun'];
		$pepr = $_GET["pep"];
		$status = $_GET['status'];
		if ($status=="")
		  $status="SR";
		  
		switch ($status)
		{
			case "MR" : $tahap = "MENENGAH RENDAH";  $tmp = "sub_mr"; $kodjpep = " where kod!='SPMC' and kod!='UPSRC'"; break;
			case "MA" : $tahap = "MENENGAH ATAS"; $tmp = "mpsmkc"; $kodjpep = " where kod!='PMRC' and kod!='UPSRC'"; break;
			case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "sub_sr";$kodjpep = " where kod!='SPMC' and kod!='PMRC'"; break;
			default : $tahap = "Pilih Tahap"; break;
		}
		//echo "<form method=post name='f1' action='mpep-pusat.php'>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHAP</td>";
		echo "<td><select name=\"status\" onchange=\"pilih_status(this.value)\">";
		?>
		<option <?php if ($status=="MR") echo " selected "; ?> value="MR">MENENGAH RENDAH</option>
		<option <?php if ($status=="MA") echo " selected "; ?> value="MA">MENENGAH ATAS</option>
		<option <?php if ($status=="SR") echo " selected "; ?> value="SR">SEKOLAH RENDAH</option>
		<?php 
		echo "</select><input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";

		$SQLpep = oci_parse($conn_sispa,"SELECT DISTINCT kod, jenis,rank FROM jpep $kodjpep ORDER BY rank");
		oci_execute($SQLpep);
		echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td>";
		echo "<td><select name='pep'><option value=''>Pilih Peperiksaan</option>";
		while($rowpep = oci_fetch_array($SQLpep)) {
			if($pepr==$rowpep["KOD"])
				echo  "<option value='".$rowpep["KOD"]."' selected='selected'>".$rowpep["JENIS"]."</option>";
			else
				echo  "<option value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
		}
		echo "</select></td></tr>";
		
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
			
		echo "</td></tr>";

		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td>";
		echo "<td><select name='ting'><option value=''>Ting/Darjah</option>";
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
		echo "</select></td></tr>";
		
		//////////        Starting of second drop downlist /////////
if($tmp=='mpsmkc'){
	if($tahun=='2011')
		$qry = " where kod not in (select kod from mpsmkc where kod like '%MA%')";
	else
		$qry = " where kod not in (select kod from sub_mr)";
		
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp $qry ORDER BY mp");
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td>";
		echo "<td><select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) {
			echo  "<option value='".$rowmp["KOD"]."'>".$rowmp["MP"]."</option>";
		}
		echo "</select></td></tr>";
} else {
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td>";
		echo "<td><select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) {
			echo  "<option value='".$rowmp["KOD"]."'>".$rowmp["MP"]."</option>";
		}
		echo "</select></td></tr>";
}
//echo "</td></tr>";
echo "</table><br><br>";
print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";
print "<input name=\"namajpn\" type=\"hidden\" readonly value=\"$namajpn\">";
echo "<center><input type='submit' name=\"mpep\" value=\"Hantar\"></center>";
echo "</form>";
} 
?>
</td>
<?php include 'kaki.php';?>

