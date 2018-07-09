<?php 
include 'auth.php';
include 'config.php';
include "input_validation.php";

   header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="markah.xls"');
   echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>STATISTIK LINUS IKUT PPD</TITLE>";
   echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
   echo "<body>";


$m=validate($_GET['data']);
// list ($ting, $kelas, $kod, $tahun, $kodsek)=explode("|", $m);
list ($kelas, $ting, $kod, $tahun, $kodsek)=explode("|", $m);

if ($_SESSION['statussek']=="SM"){
	$theadcount="headcount";
	$tmp="mpsmkc";
	$tahap="ting";
}

if ($_SESSION['statussek']=="SR"){
	$theadcount="headcountsr";
	$tmp="mpsr";
	$tahap="darjah";
}

$qrytentuhc = "SELECT * FROM tentu_hc WHERE tingpep= :ting AND tahunpep= :tahun AND capai='TOV'";
$resulthc = oci_parse($conn_sispa,$qrytentuhc);
oci_bind_by_name($resulthc, ':ting', $ting);
oci_bind_by_name($resulthc, ':tahun', $tahun);
oci_execute($resulthc);
$parameter=array(":ting",":tahun");
$value=array($ting,$tahun);
$num = kira_bil_rekod($qrytentuhc,$parameter,$value);

$row = oci_fetch_array($resulthc);
$tentuhc=$row["JENPEP"];
$tahuntov=$row["TAHUNTOV"];
$tingtov=$row["TINGTOV"];

$querymark = "SELECT nokp, nama, hmp, tov, nt, gtov, etr, getr FROM $theadcount WHERE tahun= :tahun AND kodsek= :kodsek AND $tahap= :ting AND kelas= :kelas AND hmp= :kod AND etr is not null ORDER BY nama";
$resultmark = oci_parse($conn_sispa,$querymark);
oci_bind_by_name($resultmark, ':tahun', $tahun);
oci_bind_by_name($resultmark, ':kodsek', $kodsek);
oci_bind_by_name($resultmark, ':ting', $ting);
oci_bind_by_name($resultmark, ':kelas', $kelas);
oci_bind_by_name($resultmark, ':kod', $kod);
oci_execute($resultmark);
$parameter=array(":tahun",":kodsek",":ting",":kelas",":kod");
$value=array($tahun,$kodsek,$ting,$kelas,$kod);
$semakmark = kira_bil_rekod($querymark,$parameter,$value);

$querykod = "SELECT * FROM $tmp WHERE kod= :kod";
$resultnamamp=oci_parse($conn_sispa,$querykod);
oci_bind_by_name($resultnamamp, ':kod', $kod);
oci_execute($resultnamamp);
$resultkod = oci_fetch_array($resultnamamp);
$namamp=$resultkod["MP"];
	
	//echo "<br><br>";
	echo "<center><b><font color =\"#ff0000\" size=\"2\">SENARAI MARKAH TOV, NILAI TAMBAH DAN TARGET PELAJAR</font></center><br>";
	echo "<table width=\"800\" border=\"1\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <th width=\"50%\" ><center><font size=\"2\">KELAS</font></center></th>\n";
	echo "    <th width=\"50%\" ><center><font size=\"2\">MATA PELAJARAN</font></center></th>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td><center>$ting $kelas</center></td>\n";
	echo "    <td><center>$namamp</center></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	echo "<br>";
	echo "<table width=\"800\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center><font size=\"2\">BIL</font></center></th>\n";
	echo "    <th width=\"15%\" scope=\"col\"><center><font size=\"2\">NOKP</font></center></th>\n";
	echo "    <th width=\"40%\" scope=\"col\"><center><font size=\"2\">NAMA</font></center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center><font size=\"2\">MARKAH TOV</font></center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center><font size=\"2\">GRED TOV</font></center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center><font size=\"2\">NILAI TAMBAH</font></center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center><font size=\"2\">MARKAH TARGET</font></center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center><font size=\"2\">GRED TARGET</font></center></th>\n";
	echo "  </tr>\n";
	
	

if($semakmark<>0)
{	
	$bil=0;
	while ($row = oci_fetch_array($resultmark))
	{
		$nokp = $row["NOKP"];
		$nama = $row["NAMA"];
		$tov = $row["TOV"];
		$gtov = $row["GTOV"];
		$nt = $row["NT"];
		$etr = $row["ETR"];
		$getr = $row["GETR"];
		$bil=$bil+1;
		
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$nokp</td>\n";
		echo "    <td>$nama</td>\n";
		echo "    <td><center>$tov</center></td>\n";
		echo "    <td><center>$gtov</center></td>\n";
		echo "    <td><center>$nt</center></td>\n";
		echo "    <td><center>$etr</center></td>\n";
		echo "    <td><center>$getr</center></td>\n";
	}
	echo "</th>\n";
	echo "</tr>\n";
	echo "</table></body>\n";
	echo "<br><br><br>";
	}
else
{
?><script>alert('TARGET BELUM DIMASUKAN, SILA ISI TARGET')</script> <?php
}		
?>
</td>
<?php    
echo "</body>";
echo "</html>";
?> 
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?> 