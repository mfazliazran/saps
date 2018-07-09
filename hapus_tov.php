<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Hapus TOV/ETR</p>

<?php

$m=$_GET['data'];
list ($kelas, $ting, $kod, $tahun, $kodsek)=split('[|]', $m);

if ($_SESSION['statussek']=="SM"){
	$theadcount="headcount";
	$tmp="mpsmkc";
	$tahap="ting";
	$tpelajar = "tmurid";
}

if ($_SESSION['statussek']=="SR"){
	$theadcount="headcountsr";
	$tmp="mpsr";
	$tahap="darjah";
	$tpelajar = "tmuridsr";
}

/*$qrytentuhc = "SELECT jenpep,tahuntov,tingtov FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='$tahun' AND capai='TOV'";
$resulthc = oci_parse($conn_sispa,$qrytentuhc);
oci_execute($resulthc);
$num = count_row($qrytentuhc);

$row = oci_fetch_array($resulthc);
$tentuhc=$row['JENPEP'];
$tahuntov=$row['TAHUNTOV'];
$tingtov=$row['TINGTOV'];

$querymark="SELECT nokp, nama, hmp, tov, nt, gtov, etr, getr FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kod' AND etr is not null ORDER BY nama";
$resultmark = oci_parse($conn_sispa,$querymark);
oci_execute($resultmark);
$semakmark=count_row($querymark);

$querykod = "SELECT mp FROM $tmp WHERE kod='$kod'";
$resultnamamp=oci_parse($conn_sispa,$querykod);
oci_execute($resultnamamp);
$resultkod = oci_fetch_array($resultnamamp);
$namamp=$resultkod['MP'];*/
$qrytentuhc = "SELECT jenpep,tahuntov,tingtov FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='$tahun' AND capai='TOV'";
$resulthc = oci_parse($conn_sispa,$qrytentuhc);
oci_execute($resulthc);
//$num = count_row("SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='$tahun' AND capai='TOV'");

$row = oci_fetch_array($resulthc);
$tentuhc=$row["JENPEP"];
$tahuntov=$row["TAHUNTOV"];
$tingtov=$row["TINGTOV"];

$semakmark=count_row("SELECT nokp, nama, hmp, tov, nt, gtov, etr, getr FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kod' AND etr is not null ORDER BY nama");

$querykod = "SELECT * FROM $tmp WHERE kod='$kod'";
$resultnamamp=oci_parse($conn_sispa,$querykod);
oci_execute($resultnamamp);
$resultkod = oci_fetch_array($resultnamamp);
$namamp=$resultkod["MP"];
	
echo "<form name=\"form1\" method=\"post\" action=\"hapus_tov_berulang.php\">\n";
echo "<br><br>";
echo "<center><b><font color =\"#ff0000\" size=\"2\">SEMAKAN SEBELUM HAPUS TOV/ETR<br>Hapus Markah TOV Yang Bermasalah Secara Manual</font></center><br>";
//////////////////////////// data //////////////////////////////////////////////////////////////////////////////////////////
echo "<input name=\"kodsek\" type=\"hidden\" id=\"kodsek\" value=\"$kodsek\">\n";

echo "<input name=\"tahun\" type=\"hidden\" id=\"tahun\" value=\"$tahun\">\n";

echo "<input name=\"ting\" type=\"hidden\" id=\"ting\" value=\"$ting\">\n";

echo "<input name=\"kelas\" type=\"hidden\" id=\"kelas\" value=\"$kelas\">\n";

echo "<input name=\"mp\" type=\"hidden\" id=\"mp\" value=\"$kod\">\n";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
echo "    <th width=\"5%\" scope=\"col\"><center>HAPUS TOV</center></th>\n";
echo "  </tr>\n";
	
	

/*if($semakmark<>0)
{	
	$bil=0;
	while ($row = oci_fetch_array($resultnamamp))
	{
		$nokp = $row['NOKP'];
		$nama = $row['NAMA'];
		$tov = $row['TOV'];
		$gtov = $row['GTOV'];
		$nt = $row['NT'];
		$etr = $row['ETR'];
		$getr = $row['GETR'];
		$bil=$bil+1;*/
		if($semakmark<>0)
{	
	$bil=0;
	if($kod=='PIMA' or $kod=='PI')
		$querymark = "SELECT nokp, nama, hmp, tov, nt, gtov, etr, getr FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kod' and nokp not in (select nokp from $tpelajar where kodsek$ting='$kodsek' and tahun$ting='$tahun' and kelas$ting='$kelas' and agama='01') and (getr is not null or gtov is not null) ORDER BY nama";//AND etr is not null
	elseif($kod=='PMMA' or $kod=='PM')
		$querymark = "SELECT nokp, nama, hmp, tov, nt, gtov, etr, getr FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kod' and nokp not in (select nokp from $tpelajar where kodsek$ting='$kodsek' and tahun$ting='$tahun' and kelas$ting='$kelas' and agama='02') and (getr is not null or gtov is not null) ORDER BY nama";//AND etr is not null
	else
		$querymark = "SELECT nokp, nama, hmp, tov, nt, gtov, etr, getr FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kod' and nokp not in (select nokp from $tpelajar where kodsek$ting='$kodsek' and tahun$ting='$tahun' and kelas$ting='$kelas') ORDER BY nama";//AND etr is not null
		//if($kodsek='CBB4026')
			//echo $querymark;
	$resultmark = oci_parse($conn_sispa,$querymark);
	oci_execute($resultmark);
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
		echo "  <td><a href=hapus_tov_soso.php?data=".urlencode($kelas)."|".urlencode($nokp)."|".$ting."|".$kod."|".$_SESSION['tahun']."|".$kodsek." onclick=\"return (confirm('Adakah anda pasti hapus TOV Pelajar $nokp $nama ?'))\"><center><img src = images/drop.png width=12 height=12 Alt=\"Hapus Data TOV/ETR Ini\" border=0></center></a></td>\n";

	}
	echo "</th>\n";
	echo "</tr>\n";
	echo "</table></body>\n";
	echo "<br><br>";
	print "<center>";
	//print "<input type=\"submit\" name=\"add\" value=\"HAPUS DATA TOV/ETR BERULANG\">";
	print "</center>";
	echo "</form>";
	echo "<br><br><br>";
	}
else
{
?><script>alert('TARGET BELUM DIMASUKAN, SILA ISI TARGET')</script><?php
}		
  
?>
</table></td>
<?php include 'kaki.php';?> 