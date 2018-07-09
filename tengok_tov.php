<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include "input_validation.php";

$m=validate($_GET['data']);
?>
<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>

<td valign="top" class="rightColumn">
<p class="subHeader">Markah TOV/ETR</p>

<form action="ctk_data-target-pelajar.php?data=<?php echo $m;?>" method="POST" target="_blank">

<?php
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
?>
<br><br>
&nbsp;&nbsp;<input type="submit" name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCELL" onclick="open_window('data-tov-pelajar-excel.php?data=<?php echo $m;?>','win1');" />
</form>

<?php
	
	echo "<br><br>";
	echo "<center><b><font color =\"#ff0000\" size=\"2\">SENARAI MARKAH TOV, NILAI TAMBAH DAN TARGET PELAJAR</font></center><br>";
	echo "<table width=\"800\" border=\"1\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <th width=\"50%\" ><center>KELAS</center></th>\n";
	echo "    <th width=\"50%\" ><center>MATA PELAJARAN</center></th>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td><center>$ting $kelas</center></td>\n";
	echo "    <td><center>$namamp</center></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	echo "<br>";
	echo "<table width=\"800\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center>BIL</center></th>\n";
	echo "    <th width=\"15%\" scope=\"col\"><center>NOKP</center></th>\n";
	echo "    <th width=\"40%\" scope=\"col\"><center>NAMA</center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center>MARKAH TOV</center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center>GRED TOV</center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center>NILAI TAMBAH</center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center>MARKAH TARGET</center></th>\n";
	echo "    <th width=\"5%\" scope=\"col\"><center>GRED TARGET</center></th>\n";
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
<?php include 'kaki.php';?> 
