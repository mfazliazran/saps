

<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>

<?php
session_start();
include 'config.php';
include 'fungsi.php';
include 'fungsikira.php';
?>
<title>Sistem Analisis Peperiksaan Sekolah</title>
<?php
	
 $tahun_semasa=$_POST['tahun_semasa'];
  $kodmp=$_POST['kodmp'];
  $ting=$_POST['ting'];
  $jpep=$_POST['pep'];
  $status1=$_POST['statussek'];
  $kodsek=$_SESSION['kodsek2'];
  
  
  $data = "kodsek-$kodsek|ting-$ting|kodmp-$kodmp|tahun-$tahun_semasa|jpep-$jpep|status-$status1";
  //echo $data;
  
  //echo "$data";
 // $m=$_GET['data'];
  //list(,$kodsek, $ting, $kodmp, $tahun_semasa, $jpep, $status)=split('[/]', $m);

  if($status1=="SM"){ 
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod='$kodmp'"); 
		oci_execute($qmp);
		}
  if($status1=="SR"){ 
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsr WHERE kod='$kodmp'"); 
		oci_execute($qmp);
		}
$rmp=oci_fetch_array($qmp);
	
   //echo "<span class=\"style1\">ANALISIS PEPERIKSAAN</span><br><br>" ; 
   //echo "<center><span class=\"style3\">ANALISIS PENCAPAIAN MATA PELAJARAN ".$rmp["MP"]." - ".tahap($ting)."" ; 
  
   
   $qstatus=oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek' AND status='$status1'"); 
   oci_execute($qstatus);
   $rstatus=oci_fetch_array($qstatus);
    //echo "SELECT * FROM tsekolah WHERE kodsek='$kodsek' AND status='$status1'";
	?>
   <?php
   if (($ting=="T4") OR ($ting=="T5")){
  echo "<h3><center> ".jpep($jpep)." <br>ANALISIS MATA PELAJARAN <br>TAHUN ".$tahun_semasa."<br><br>".$rmp["MP"]." </center></h3>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<b>SEKOLAH : </b>".$rstatus["NAMASEK"]."<br>";
echo "<b>TAHUN / TINGKATAN : </b>".tahap($ting)."";
echo "<tr>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Kelas </div></td>\n";
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
echo "	<td colspan=\"2\"><div align=\"center\">LULUS</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED G</div></td>\n";
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
echo "  </tr>\n";
echo "  <tr>\n";


  //echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
  
    
	$bil=0;
	
	$q_mp = "SELECT  kelas, SUM(bcalon) as bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, 
					 SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg
					 FROM analisis_mpma WHERE kodsek='$kodsek' and tahun='$tahun_semasa' AND jpep='$jpep' 
					 AND  ting='$ting' AND kodmp='$kodmp' and kodmp in (select kodmp from sub_guru where kodsek='$kodsek' and tahun='$tahun_semasa' and ting='$ting' and analisis_mpma.kelas=sub_guru.kelas) Group BY kelas ORDER BY kelas ";
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
	//if($kodsek=='KEA4062')
		//echo"$q_mp<br>";
	$kirajum = count_row($q_mp);
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
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["KELAS"]."</center></span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["BCALON"]."</center></span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["AMBIL"]."</center></span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["TH"]."</center></span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["BILAP"]."</center></span></td>\n";
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
echo "<td colspan=\"1\"><div align=\"center\">$jumbplus</div></td>\n";
echo "    <td><center>".peratus($jumbplus, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumb</div></td>\n";
echo "    <td><center>".peratus($jumb, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumcplus</div></td>\n";
echo "    <td><center>".peratus($jumcplus, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumc</div></td>\n";
echo "    <td><center>".peratus($jumc, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumd</div></td>\n";
echo "    <td><center>".peratus($jumd, $jumambil)."</center></td>\n";;
echo "<td colspan=\"1\"><div align=\"center\">$jume</div></td>\n";
echo "    <td><center>".peratus($jume, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumlulus</div></td>\n";
echo "    <td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumg</div></td>\n";
echo "    <td><center>".peratus($jumg, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".gpmpma($jumaplus, $juma, $jumaminus ,$jumbplus, $jumb, $jumcplus, $jumc, $jumd, $jume, $jumg, $jumambil)."</div></td>\n";
	}

if (($ting=="P") OR ($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){		
echo "<h3><center> ".jpep($jpep)." <br>ANALISIS MATA PELAJARAN <br>TAHUN ".$tahun_semasa."<br><br>".$rmp["MP"]." </center></h3>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<b>SEKOLAH : </b>".$rstatus["NAMASEK"]."<br>";
echo "<b>TAHUN / TINGKATAN : </b>".tahap($ting)."";
echo "<tr>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Kelas </div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED E</div></td>\n";
if($tahun_semasa>=2015){
	echo "    <td colspan=\"2\"><div align=\"center\">GRED F</div></td>\n";
}
echo "	<td colspan=\"2\"><div align=\"center\">Menguasai</div></td>\n";
echo "	<td rowspan=\"2\"><div align=\"center\">GPMP</div></td>\n";
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
if($tahun_semasa>=2015){
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
}
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

	$bil=0;
	
	$q_mp = "SELECT  kelas, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf
					 FROM analisis_mpmr WHERE kodsek='$kodsek' and tahun='$tahun_semasa' AND jpep='$jpep' 
					 AND  ting='$ting' AND kodmp='$kodmp' and kodmp in (select kodmp from sub_guru where kodsek='$kodsek' and tahun='$tahun_semasa' and ting='$ting' and analisis_mpmr.kelas=sub_guru.kelas) Group BY kelas ORDER BY kelas";
					 //if($kodsek=='AEE3045')
					 	//echo $q_mp;
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
	//if($kodsek=='WEB0210')
		//echo"$q_mp<br>";
	$kirajum = count_row($q_mp);
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
		$jumf+=(int) $rbmurid["BILF"];
		$jumg+=(int) $rbmurid["BILG"];
		
		if($tahun_semasa<=2014){
			$jumlulus+=(int)$rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];	
			$lulus = $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];
		}elseif($tahun_semasa>=2015){
			$jumlulus+=(int)$rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];	
			$lulus = $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"]+$rbmurid["BILE"];
		}
		//$jumlulus+=(int)$rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];	
		//$lulus = $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]+$rbmurid["BILD"];
	
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["KELAS"]."</center></span></td>\n";
		echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["BCALON"]."</center></span></td>\n";
		echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["AMBIL"]."</center></span></td>\n";
		echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["TH"]."</center></span></td>\n";
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
		if($tahun_semasa>=2015){
			echo "    <td><center>".$rbmurid[BILF]."</center></td>\n";
			echo "    <td><center>".peratus($rbmurid["BILF"], $rbmurid["AMBIL"])."</center></td>\n";
		}
		echo "<td><center>".$lulus."</center></td>\n";
		echo "<td><center>".peratus($lulus, $rbmurid["AMBIL"])."</center></td>\n";
		if($tahun_semasa<=2014){
			echo "<td><center>".gpmpmrsr($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["AMBIL"])."</center></td>\n";
		}elseif($tahun_semasa>=2015){
			echo "<td><center>".gpmpmrsr_baru($rbmurid["BILA"], $rbmurid["BILB"], $rbmurid["BILC"], $rbmurid["BILD"], $rbmurid["BILE"], $rbmurid["BILF"], $rbmurid["AMBIL"])."</center></td>\n";
		}
		echo "  </tr>\n";
	}
			
echo "<tr><td colspan=\"2\"><center>Jumlah</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumdaftar</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumambil</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumth</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$juma</div></td>\n";
echo "<td><center>".peratus($juma, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumb</div></td>\n";
echo "<td><center>".peratus($jumb, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumc</div></td>\n";
echo "<td><center>".peratus($jumc, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumd</div></td>\n";
echo "<td><center>".peratus($jumd, $jumambil)."</center></td>\n";;
echo "<td colspan=\"1\"><div align=\"center\">$jume</div></td>\n";
echo "<td><center>".peratus($jume, $jumambil)."</center></td>\n";
if($tahun_semasa>=2015){
	echo "<td colspan=\"1\"><div align=\"center\">$jumf</div></td>\n";
	echo "<td><center>".peratus($jumf, $jumambil)."</center></td>\n";
}
echo "<td colspan=\"1\"><div align=\"center\">$jumlulus</div></td>\n";
echo "<td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
if($tahun_semasa<=2014){
	echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr($juma, $jumb, $jumc, $jumd, $jume, $jumambil)."</div></td>\n";
}elseif($tahun_semasa>=2015){
	echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr_baru($juma, $jumb, $jumc, $jumd, $jume, $jumf, $jumambil)."</div></td>\n";
}
}
	
if (($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){		
echo "<h3><center> ".jpep($jpep)." <br>ANALISIS MATA PELAJARAN <br>TAHUN ".$tahun_semasa."<br><br>".$rmp["MP"]." </center></h3>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<b>SEKOLAH : </b>".$rstatus["NAMASEK"]."<br>";
echo "<b>TAHUN / TINGKATAN : </b>".tahap($ting)."";
echo "<tr>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Kelas </div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">GRED E</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Menguasai</div></td>\n";
//echo "	<td colspan=\"2\"><div align=\"center\">Gagal</div></td>\n";
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
//echo "    <td><div align=\"center\">Bil</div></td>\n";
//echo "    <td><div align=\"center\">%</div></td>\n";

echo "  </tr>\n";
echo "  <tr>\n";

	$bil=0;
	
	$q_mp = "SELECT  kelas, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile
					 FROM analisis_mpsr WHERE kodsek='$kodsek' and tahun='$tahun_semasa' AND jpep='$jpep' 
					 AND  darjah='$ting' AND kodmp='$kodmp' and kodmp in (select kodmp from sub_guru where kodsek='$kodsek' and tahun='$tahun_semasa' and ting='$ting' and analisis_mpsr.kelas=sub_guru.kelas) Group BY kelas ORDER BY kelas ";
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
	//echo"$q_mp<br>";
	$kirajum = count_row($q_mp);
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
				$jumlulus+=(int) $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]; //+$rbmurid["BILD"];
			
	$lulus=  $rbmurid["BILA"]+$rbmurid["BILB"]+$rbmurid["BILC"]; //+$rbmurid["BILD"];
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["KELAS"]."</center></span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["BCALON"]."</center></span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["AMBIL"]."</center></span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["TH"]."</center></span></td>\n";
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
echo "<td colspan=\"1\"><div align=\"center\">$jumlulus</div></td>\n";
echo "    <td><center>".peratus($jumlulus, $jumambil)."</center></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr($juma, $jumb, $jumc, $jumd, $jume, $jumambil)."</div></td>\n";
	}
	if($kirajum==0){
		echo "<tr><td colspan='18' align='center'><font color='#FF0000'><strong>SUP masih belum lagi memproses Markah bagi ".tahap($ting)."</strong></font></td></tr>";
	}
echo "</table>";
echo "<br><br>";
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
  	
?>