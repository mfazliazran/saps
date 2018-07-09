<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
include "input_validation.php";

$tahun = validate($_SESSION['tahun']);
$ting = validate($_GET['ting']);
$kodsek = validate($_SESSION['kodsek']);
$jpep = validate($_SESSION['jpep']);

$m="$ting&namasekolah=$namasek";
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Mata Pelajaran Ikut Kelas <font color="#FFFFFF"> (Tarikh Kemaskini 10/8/2011 2:32 PM) </font></p>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=600,height=400,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-200,screen.height/2-100);
}
</script>

<form action="ctk_analisis-mptingma.php?ting=<?php echo $ting;?>" method="POST" target="_blank">
<?php
echo "<h3><center>$namasek<br>ANALISA MATA PELAJARAN TINGKATAN $ting<br>".jpep($jpep)." TAHUN ".$tahun."</center></h3>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#FFCC99\">";
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
echo "	<td colspan=\"2\"><div align=\"center\">Lulus /<br>Gagal</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Tidak Layak Sijil</div></td>\n";
echo "	<td rowspan=\"2\"><div align=\"center\">GPMP</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
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

$bil=0; 

$q_mp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg 
      FROM analisis_mpma amp, mpsmkc mp WHERE amp.kodsek=:kodsek and amp.tahun=:tahun AND amp.jpep=:jpep AND  amp.ting=:ting 
	  AND amp.kodmp=mp.kod and amp.kodmp not in (select kod from sub_ma_xambil) Group BY amp.kodmp, mp.susun, mp.mp ORDER BY mp.susun ";
$qry = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($qry,":kodsek",$kodsek);
oci_bind_by_name($qry,":tahun",$tahun);
oci_bind_by_name($qry,":jpep",$jpep);
oci_bind_by_name($qry,":ting",$ting);
oci_execute($qry);

					 
$prm=array(":kodsek",":tahun",":jpep",":ting");
$val=array($kodsek,$tahun,$jpep,$ting);	
$bilmp = kira_bil_rekod("SELECT mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, 
					 SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg
					 FROM analisis_mpma amp, mpsmkc mp WHERE amp.kodsek=:kodsek and amp.tahun=:tahun AND amp.jpep=:jpep  
					 AND  amp.ting=:ting AND amp.kodmp=mp.kod and amp.kodmp not in (select kod from sub_ma_xambil) Group BY mp.mp ORDER BY mp.mp ",$prm,$val);

if ($bilmp != 0)
{
	$jumambil=0;
	$jumdaftar=0;
	$jumth=0;
	//A
	$jumaplus=0;
	$juma=0;
	$jumaminus=0;
	//B
	$jumbplus=0;
	$jumb=0;
	//C
	$jumcplus=0;
	$jumc=0;
	//D,E,G
	$jumd=0;
	$jume=0;
	$jumg=0;
	$jumlulus=$jumgagal=0;
	$gps=0;
	
	while($rowmp = oci_fetch_array($qry))
	{
		$lulus = $rowmp["BILAP"]+$rowmp["BILA"]+$rowmp["BILAM"]+$rowmp["BILBP"]+$rowmp["BILB"]+$rowmp["BILCP"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"] ;
		$bil++;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "  <tr bgcolor='$bcol'>\n";
		echo "    <td>".$bil."</td>\n";
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
		
		//if ($rowmp["KODMP"]<>"PJK" AND $rowmp["KODMP"]<>"SIV" ){
		if (subjek_tak_ambil($rowmp["KODMP"])==0){			
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
			$jumgagal+=(int) $rowmp["BILG"];

	}
}
//echo "$jumaplus, $juma, $jumaminus ,$jumbplus, $jumb, $jumcplus, $jumc, $jumd, $jume, $jumg, $jumambil";
echo "<td colspan=\"2\"><div align=\"center\"> JUMLAH (mengikut MP dikira GPS Sekolah)</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumdaftar</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumambil</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumth</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumaplus</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumaplus, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$juma</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($juma, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumaminus</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumaminus, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumbplus</td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumbplus, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumb</td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumb, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumcplus</td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumcplus, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumc</td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumc, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumd</td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumd, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jume</td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jume, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumg</td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumg, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\"></div>$jumlulus</td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumlulus, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumgagal</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumgagal, $jumambil)."</div></td>\n";
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
//////////////////////////////////GPS/////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<table width=\"40%\"  border=\"1\" align=\"left\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr><td ><div align=\"center\">Bil </div></td>\n";
echo "<td><div align=\"center\">Mata Pelajaran Yang Tidak Dikira Untuk GPS </div></td></tr>\n"; 

$q_mp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, SUM(Bp) bilbp, 
SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg 
FROM analisis_mpma amp, mpsmkc mp WHERE amp.kodsek=:kodsek and amp.tahun=:tahun AND amp.jpep=:jpep AND  amp.ting=:ting  
AND amp.kodmp=mp.kod Group BY amp.kodmp, mp.susun, mp.mp ORDER BY mp.susun ";
$qry = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($qry,":kodsek",$kodsek);
oci_bind_by_name($qry,":tahun",$tahun);
oci_bind_by_name($qry,":jpep",$jpep);
oci_bind_by_name($qry,":ting",$ting);
oci_execute($qry);

					 
$prm=array(":kodsek",":tahun",":jpep",":ting");
$val=array($kodsek,$tahun,$jpep,$ting);	
//echo $q_mp;
$bilmp = kira_bil_rekod($q_mp,$prm,$val);

if ($bilmp != 0)
{
	$jumambil=0;
	$jumdaftar=0;
	$jumth=0;
	//A
	$jumaplus=0;
	$juma=0;
	$jumaminus=0;
	//B
	$jumbplus=0;
	$jumb=0;
	//C
	$jumcplus=0;
	$jumc=0;
	//D,E,G
	$jumd=0;
	$jume=0;
	$jumg=0;
	$jumlulus=0;
	$gps=0;
	$bil=1;
	while($rowmp = oci_fetch_array($qry))
	{
		//$lulus = $rowmp["BILAP"]+$rowmp["BILA"]+$rowmp["BILAM"]+$rowmp["BILBP"]+$rowmp["BILB"]+$rowmp["BILCP"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"] ;
		//if($kodsek=='AEE3045')
			//echo "<br>kod -".$rowmp["KODMP"]." - ".subjek_tak_ambil($rowmp["KODMP"]);
		if (subjek_tak_ambil($rowmp["KODMP"])==1){	
			echo "<tr><td>".$bil++."</td>\n";
			echo "<td>$rowmp[MP]</td>\n";
			echo "</tr>\n";
		}
	}
}
echo "</table>\n";
function subjek_tak_ambil($kodmp)
{
 global $conn_sispa;
 $res=oci_parse($conn_sispa,"select mp from sub_ma_xambil where kod='$kodmp'");
 //echo "<br>select mp from sub_ma_xambil where kod='$kodmp'";
 oci_execute($res);
 if ($data=oci_fetch_array($res))
    $takambil=1;
 else
    $takambil=0;
return $takambil;  

}
echo "<br><br><br><br><br>";
?>
<input type="submit" name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('data-analisis-mptingma-excel.php?ting=<?php echo $m;?>','win1');" />
</form>


<?php 
include 'kaki.php';
?> 