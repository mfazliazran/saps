<?php 
include 'auth.php';
include 'config.php';
include 'fungsi.php';

?>
<html>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<head>
<link href="include/kpm.css" type="text/css" rel="stylesheet" />
<style type="text/css">
P {
	page-break-after: always;
}
</style>

</head>
<body>
<STYLE type="text/css">
@media print {
  #mybutton { display:none; visibility:hidden; }

  #mybutton2 { display:none; visibility:hidden; }
 
}



</STYLE>


<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>

<?php


$m=$_GET['data'];
//die($m);

list ($kelas, $ting, $kod, $tahun, $kodsek, $jpep)=split('[|]', $m);
//echo $m;

if ($_SESSION['statussek']=="SM"){
	$tmarkah="markah_pelajar";
	$tmurid="tmurid";
	$tmp="mpsmkc";
	$tahap="ting";
}

if ($_SESSION['statussek']=="SR"){
	$tmarkah="markah_pelajarsr";
	$tmurid="tmuridsr";
	$tmp="mpsr";
	$tahap="darjah";
}

$gmp="G$kod";
switch ($kod) 
{
/*	case "KH1":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND jantina='L' AND $kod!='' ORDER BY nama"  ;
		break;
	case "KH2":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND jantina='P' AND $kod!='' ORDER BY nama";
		break;  */
	case "PI":
		//$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND agama='01' AND $kod is not null ORDER BY nama";
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND $kod is not null ORDER BY nama";
		break;
	case "PM":
		//$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND agama='02' AND $kod is not null ORDER BY nama";
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND $kod is not null ORDER BY nama";
		break;
	/*case "BC":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND bangsa='02' AND $kod!='' ORDER BY nama";
		break; */
	case "BT":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND (kaum='0300' or kaum='03') AND $kod is not null ORDER BY nama";
		break;
	default :
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND $kod is not null ORDER BY nama";
		break;
}
//die($querymark);
//echo "$querymark<br>";
$semakmark=count_row($querymark);

$querykod = "SELECT * FROM $tmp WHERE kod='$kod'";
$resultnamamp=oci_parse($conn_sispa,$querykod);
oci_execute($resultnamamp);
$resultkod = oci_fetch_array($resultnamamp);
$namamp=$resultkod['MP'];


if($semakmark > 0)
{
	echo "<br><br>";
	echo "<center><b><font color =\"#ff0000\" size=\"2\">SENARAI MARKAH PELAJAR </font></center><br>";
	echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\">\n";
	echo "  <tr bgcolor=\"CCCCCC\">\n";
	echo "    <th scope=\"col\"><center>KELAS</center></th>\n";
	echo "    <th scope=\"col\"><center>MATA PELAJARAN</center></th>\n";
	echo "    <th scope=\"col\"><center>JENIS UJIAN</center></th>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td><center>$ting $kelas</center></td>\n";
	echo "    <td><center>$namamp</center></td>\n";
	echo "    <td><center>".jpep("".$_SESSION['jpep']."")."</center></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	$sqlsemak = "select distinct count(NOKP) as us,NOKP from $tmurid where kodsek$ting='".$_SESSION["kodsek"]."' and tahun$ting='".$_SESSION["tahun"]."' group by NOKP having count(NOKP) > 1";
$ressemak=oci_parse($conn_sispa,$sqlsemak);
oci_execute($ressemak);
while($rowsemak = oci_fetch_array($ressemak)){
	$nokpdouble=$rowsemak['NOKP'];
	echo "<center><b><font color='#FF0000'><br>NO KP/ NO SIJIL LAHIR $nokpdouble telah digunakan oleh lebih dari seorang pelajar. <br>Sila maklumkan kepada SUP untuk pengemaskinian data pelajar di APDM.<br></font></b></center>";
	
	$sqld = "Select nokp, namap, KELAS$ting from $tmurid where nokp='$nokpdouble'";
	$resd=oci_parse($conn_sispa,$sqld);
	oci_execute($resd);
	while($rowd = oci_fetch_array($resd)){
		$nokpd = $rowd["NOKP"];
		$nama = $rowd["NAMAP"];
		$kelasd = $rowd["KELAS$ting"];
		echo "<center><b><font color='#FF0000'><br>No. KP :- $nokpd Nama :- $nama [$kelasd]<br></font></b></center>";
	}
}

	echo "<br>";
	echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "  <tr bgcolor=\"CCCCCC\">\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">NOKP</th>\n";
	echo "    <th scope=\"col\">NAMA</th>\n";
	echo "    <th scope=\"col\">MARKAH</th>\n";
	echo "    <th scope=\"col\">GRED</th>\n";
	echo "  </tr>\n";
	
	$bil=0;
	$resultmark = oci_parse($conn_sispa,$querymark);
	oci_execute($resultmark);
	while ($row = oci_fetch_array($resultmark))
	{
		//echo "".$row['nokp']." ".$row['nama']." ".$row[7]." ".$row[8]."<br>";
		$nokp = $row['NOKP'];
		$nama = $row['NAMA'];
		$mp = $row["$kod"];
		$gred = $row["$gmp"];
		$bil=$bil+1;
		
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$nokp</td>\n";
		echo "    <td>$nama</td>\n";
		echo "    <td><center>$mp</center></td>\n";
		echo "    <td><center>$gred</center></td>\n";
	}
	echo "</tr>";
	echo "</table>";
	echo "<br><br><br>";
}
else
{
echo "<br><br><br><br><br><center><h3>Markah Belum Dimasukan Lagi !!!</h3></center>";
?><script>alert('MARKAH BELUM DIMASUKAN')</script> <?php
}                                                     
?>
</td>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>