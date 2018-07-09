<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
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
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Mata Pelajaran Ikut Kelas <font color="#FFFFFF"> (Tarikh Kemaskini 11/8/2011 12:46PM) </font></p>



<form action="" method="POST" target="_blank" name="f1">
<?php




echo "<h3><center>$namappd<br>ANALISA MATA PELAJARAN TINGKATAN $ting<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3>";
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

$q_mp = "SELECT mp.mp,amp.KODMP, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, 
					 SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg
					 FROM analisis_mpma amp, mpsmkc mp,tsekolah ts WHERE amp.tahun= :tahun AND amp.kodsek=ts.kodsek AND amp.jpep= :jpep 
					 AND  amp.ting= :ting AND amp.kodmp=mp.kod and ts.kodppd= :kodppd Group BY mp.mp,amp.KODMP ORDER BY mp.mp ";
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_bind_by_name($qry, ':tahun', $tahun);
                     oci_bind_by_name($qry, ':jpep', $jpep);
                     oci_bind_by_name($qry, ':ting', $ting);
                     oci_bind_by_name($qry, ':kodppd', $kodppd);
					 oci_execute($qry);


$parameter=array(":tahun",":jpep",":ting",":kodppd");
$value=array($tahun,$jpep,$ting,$kodppd);
$bilmp = kira_bil_rekod($q_mp,$parameter,$value);

if ($bilmp != 0)
{
	$jumambil=0;
	$jumdaftar=0;
	$jumth=0;
	
	$jumaplus=0;
	$juma=0;
	$jumaminus=0;
	
	$jumbplus=0;
	$jumb=0;
	
	$jumcplus=0;
	$jumc=0;
	
	$jumd=0;
	$jume=0;
	$jumg=0;
	$jumlulus=0;
	$gps=0;
	
	while($rowmp = oci_fetch_array($qry))
	{
		$lulus = $rowmp["BILAP"]+$rowmp["BILA"]+$rowmp["BILAM"]+$rowmp["BILBP"]+$rowmp["BILB"]+$rowmp["BILCP"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"] ;
		
		echo "    <td>".$bil++."</td>\n";
		echo "    <td>$rowmp[MP]</td>\n";
		echo "    <td><center>$rowmp[BCALON]</center></td>\n";
		echo "    <td><center>$rowmp[AMBIL]</center></td>\n";
		echo "    <td><center>$rowmp[TH]</center></td>\n";
		echo "    <td><center>$rowmp[BILAP]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILAP"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILA]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILA"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILAM]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILAM"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILBP]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILBP"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILB]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILB"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILCP]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILCP"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILC]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILC"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILD]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILD"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILE]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILG]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILG"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$lulus</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>$rowmp[BILG]</center></td>\n";
		echo "    <td><center>".peratus($rowmp["BILG"], $rowmp["AMBIL"])."</center></td>\n";
		echo "    <td><center>".gpmpma($rowmp["BILAP"], $rowmp["BILA"], $rowmp["BILAM"], $rowmp["BILBP"], $rowmp["BILB"], 
								$rowmp["BILCP"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["BILG"], $rowmp["AMBIL"])."</center></td>\n";
		echo "  </tr>\n";
		
		if ($rowmp["KODMP"]<>"PJK" AND $rowmp["KODMP"]<>"SIV" ){
			
			$jumdaftar+=(int) $rowmp["BCALON"];
			$jumambil+=(int) $rowmp["AMBIL"];
			$jumth+=(int) $rowmp["TH"];
			$jumaplus+=(int) $rowmp["BILAP"];
			$juma+=(int) $rowmp["BILA"];
			$jumaminus+=(int) $rowmp["BILAM"];
			$jumbplus+=(int) $rowmp["BILBP"];
			$jumb+=(int) $rowmp["BILB"];
			$jumcplus+=(int) $rowmp["BILCP"];
			$jumc+=(int) $rowmp["BILC"];
			$jumd+=(int) $rowmp["BILD"];
			$jume+=(int) $rowmp["BILE"];
			$jumg+=(int) $rowmp["BILG"];
			$jumlulus+=(int) $rowmp["BILAP"]+$rowmp["BILA"]+$rowmp["BILAM"]+$rowmp["BILBP"]+$rowmp["BILB"]+$rowmp["BILBM"]+$rowmp["BILCP"]+$rowmp["BILC"]+$rowmp["BILD"];

	}
}
echo "<td colspan=\"2\"><div align=\"center\"> JUMLAH (mengikut MP dikira GPS Sekolah)</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumdaftar</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumambil</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumth</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumaplus</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$juma</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumaminus</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumbplus</td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumb</td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumcplus</td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumc</td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumd</td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jume</td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumg</td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumlulus</td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".gpmpma($jumaplus, $juma, $jumaminus ,$jumbplus, $jumb, $jumcplus, $jumc, $jumd, $jume, $jumg, $jumambil)."</div></td>\n";
}
else {
		echo "<br>";
		echo "<td colspan = \"30\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
?>
<br>
<?php

echo "<table width=\"40%\"  border=\"1\" align=\"left\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr>";
echo "    <td ><div align=\"center\">Bil </div></td>\n";
echo "    <td ><div align=\"center\">Mata Pelajaran Yang Tidak Dikira Untuk GPS </div></td>\n";
echo "  </tr>\n";
$bil=1; 

$q_mp = "SELECT mp.mp,amp.KODMP, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, 
					 SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg
					 FROM analisis_mpma amp, mpsmkc mp,tsekolah ts WHERE amp.tahun= :tahun AND amp.kodsek=ts.kodsek AND amp.jpep= :jpep 
					 AND  amp.ting= :ting AND amp.kodmp=mp.kod and ts.kodppd= :kodppd Group BY mp.mp,amp.KODMP ORDER BY mp.mp ";
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_bind_by_name($qry, ':tahun', $tahun);
                     oci_bind_by_name($qry, ':jpep', $jpep);
                     oci_bind_by_name($qry, ':ting', $ting);
                     oci_bind_by_name($qry, ':kodppd', $kodppd);
					 oci_execute($qry);
					

$parameter=array(":tahun",":jpep",":ting",":kodppd");
$value=array($tahun,$jpep,$ting,$kodppd);
$bilmp = kira_bil_rekod($q_mp,$parameter,$value);


if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($qry))
	{
		$lulus = $rowmp["BILAP"]+$rowmp["BILA"]+$rowmp["BILAM"]+$rowmp["BILBP"]+$rowmp["BILB"]+$rowmp["BILCP"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"] ;
		if (subjek_tak_ambil($rowmp["KODMP"])==1){	
		echo "  <tr>\n";
		echo "    <td>".$bil++."</td>\n";
		echo "    <td>$rowmp[MP]</td>\n";
		
		echo "  </tr>\n";
	}
	}
}
echo "</table>\n";
?> <?php
function subjek_tak_ambil($kodmp)
{
 global $conn_sispa;
 $res=oci_parse($conn_sispa,"select mp from sub_ma_xambil where kod='$kodmp'");

 oci_execute($res);
 if ($data=oci_fetch_array($res))
    $takambil=1;
else
  $takambil=0;
return $takambil;  

}
?>
<br><br>
&nbsp;&nbsp;<input type="button" name="cetak" value="CETAK" onClick="document.f1.action='ctk_analisis_mptingmadaerah.php?<?php echo "ting=$ting&tahun=$tahun&jpep=$jpep";?>';document.f1.submit();">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="document.f1.action='analisis_mptingmadaerah_excel.php?<?php echo "ting=$ting&tahun=$tahun&jpep=$jpep";?>';document.f1.submit();" />
</form>


<?php 
include 'kaki.php';
?> 