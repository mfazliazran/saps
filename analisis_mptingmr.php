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
$namasek = validate($_GET['namasekolah']);
$kodsek = validate($_SESSION['kodsek']);
$jpep = validate($_SESSION['jpep']);
//$m="$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek";
?>
<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=600,height=400,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-200,screen.height/2-100);
}
</script>


<form action="ctk_analisis_mptingmr.php?ting=<?php echo $ting;?>" method="POST" target="_blank">

<td valign="top" class="rightColumn">
<p class="subHeader">Analisa Mata Pelajaran Tingkatan / Tahun<font color="#FFFFFF">(Tarikh Kemaskini 10/8/2011) </font></p>
  <?php

echo "<h5><center>$namasek<br>ANALISA MATA PELAJARAN TINGKATAN $ting<br>".jpep($jpep)."<br>TAHUN ".$tahun."</center></h5>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "  <tr bgcolor=\"#FFCC99\">";
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
echo "	<td colspan=\"2\"><div align=\"center\">Menguasai</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Tidak <br>Menguasai</div></td>\n";
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

$bil=0;
$q_mp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf 
FROM analisis_mpmr amp, mpsmkc mp WHERE amp.kodsek=:kodsek and amp.tahun=:tahun AND amp.jpep=:jpep AND  amp.ting=:ting 
AND amp.kodmp=mp.kod Group BY mp.susun,amp.kodmp,mp.mp ORDER BY mp.susun ";
$qry = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($qry,":kodsek",$kodsek);
oci_bind_by_name($qry,":tahun",$tahun);
oci_bind_by_name($qry,":jpep",$jpep);
oci_bind_by_name($qry,":ting",$ting);
oci_execute($qry);

$prm=array(":kodsek",":tahun",":jpep",":ting");
$val=array($kodsek,$tahun,$jpep,$ting);						 
$bilmp = kira_bil_rekod("SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf 
FROM analisis_mpmr amp, mpsmkc mp WHERE amp.kodsek=:kodsek and amp.tahun=:tahun AND amp.jpep=:jpep AND  amp.ting=:ting  
AND amp.kodmp=mp.kod Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ",$prm,$val);
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
	$jumlulus=$jumgagal=0;
	$gps=0;
	while($rowmp = oci_fetch_array($qry))
	{
		if($tahun<=2014){
			$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"] ;
		}else{//>2015
			$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"] ;
		}
		$bil++;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}	
		echo "    <tr bgcolor='$bcol'><td>".$bil."</td>\n";
		echo "    <td>".$rowmp["MP"]."</td>\n";
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
			echo "    <td><center>".gpmpmrsr($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
		}else{//>2015
			echo "    <td><center>".$rowmp["BILF"]."</center></td>\n";
			echo "    <td><center>".peratus($rowmp["BILF"], $rowmp["AMBIL"])."</center></td>\n";			
			echo "    <td><center>".gpmpmrsr_baru($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["BILF"], $rowmp["AMBIL"])."</center></td>\n";
		}
		echo "  </tr>\n";
		
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
			if($tahun<=2014){
				$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
				$jumgagal+=(int) $rowmp["BILE"];
			}else{//>2015
				$jumlulus+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
				$jumgagal+=(int) $rowmp["BILF"];
			}	
		}
	} //while
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
	echo "<td colspan=\"1\"><div align=\"center\">$jumf</div></td>\n";
	echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumf, $jumambil)."</div></td>\n";
}
echo "<td colspan=\"1\"><div align=\"center\">$jumlulus</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumlulus, $jumambil)."</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">$jumgagal</div></td>\n";
echo "<td colspan=\"1\"><div align=\"center\">".peratus($jumgagal, $jumambil)."</div></td>\n";
	if($tahun<=2014){
		echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr($juma, $jumb, $jumc, $jumd, $jume, $jumambil)."</div></td>\n";
	}else{
		echo "<td colspan=\"1\"><div align=\"center\">".gpmpmrsr_baru($juma, $jumb, $jumc, $jumd, $jume, $jumf, $jumambil)."</div></td>\n";
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
<br>
<?php
/////////////////////////////////////////////////////////////MATA PELAJARAN YANG TIDAK DIKIRA UNTUK GPS////////////////////////////////////////////
echo "<table width=\"30%\"  border=\"1\" align=\"justify\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "  <tr>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">Mata Pelajaran Yang Tidak Dikira Untuk GPS </div></td>\n";
//echo " <td></td>";
echo "  <tr>\n";
$bil=1;
//and amp.kodmp not in (select kodmp from sub_mr_xambil)
$q_mp = "SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile
					 FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun=:tahun AND amp.kodsek=:kodsek AND amp.jpep=:jpep  
					 AND  amp.ting=:ting AND amp.kodmp=mp.kod 
					 Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ";
$qry = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($qry,":tahun",$tahun);
oci_bind_by_name($qry,":kodsek",$kodsek);
oci_bind_by_name($qry,":jpep",$jpep);
oci_bind_by_name($qry,":ting",$ting);
oci_execute($qry);

$prm=array(":tahun",":kodsek",":jpep",":ting");
$val=array($tahun,$kodsek,$jpep,$ting);							 
$bilmp = kira_bil_rekod("SELECT amp.kodmp, mp.mp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile
					 FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun=:tahun AND amp.kodsek=:kodsek AND amp.jpep=:jpep  
					 AND  amp.ting=:ting AND amp.kodmp=mp.kod Group BY amp.kodmp,mp.mp ORDER BY amp.kodmp ",$prm,$val);
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
	$jumlulus=0;
	$gps=0;
	while($rowmp = oci_fetch_array($qry))
	{
		//$lulus = $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"] ;
		if (subjek_tak_ambil($rowmp["KODMP"])==1){	
		echo "    <tr><td>".$bil++."</td>\n";
		echo "    <td>".$rowmp["MP"]."</td>\n";
		echo "  </tr>\n";
		}
	} //while
}

echo "</table>\n";

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
?> 
  
<center><input type="submit"  name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('data-analisis_mptingmr-excel.php?ting=<?php echo $ting;?>','win1');" /></center>
</p>
</form>

<?php
include 'kaki.php';
?> 