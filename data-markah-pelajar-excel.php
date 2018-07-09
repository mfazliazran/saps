<?php 
include 'auth.php';
include 'config.php';
include 'fungsi.php';


$m=$_GET['data'];
list ($kelas, $ting, $kod, $tahun, $kodsek, $jpep)=split('[|]', $m);

   header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="senarai_markah_pelajar_'.$jpep.'_'.$ting.'_'.$tahun.'.xls"');
   echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>STATISTIK LINUS IKUT PPD</TITLE>";
   echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
   echo "<body>";



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
	case "PIMA":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND agama='01' AND $kod is not null ORDER BY nama";
		break;
	case "PM":
	case "PMMA":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND agama!='01' AND $kod is not null ORDER BY nama";
		break;
	/*case "BC":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND bangsa='02' AND $kod!='' ORDER BY nama";
		break; */
	//case "BT":
		//$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND bangsa='03' AND $kod is not null ORDER BY nama";
		//break;
	default :
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND $kod is not null ORDER BY nama";
		break;
}
//echo $querymark;
$resultmark = oci_parse($conn_sispa,$querymark);
oci_execute($resultmark);
$semakmark=count_row($querymark);

$querykod = "SELECT mp FROM $tmp WHERE kod='$kod'";
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
	echo "    <th scope=\"col\"><center><font size=\"2\">KELAS</font></center></th>\n";
	echo "    <th scope=\"col\"><center><font size=\"2\">MATA PELAJARAN</font></center></th>\n";
	echo "    <th scope=\"col\"><center><font size=\"2\">JENIS UJIAN</font></center></th>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td><center>$ting $kelas</center></td>\n";
	echo "    <td><center>$namamp</center></td>\n";
	echo "    <td><center>".jpep("".$_SESSION['jpep']."")."</center></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	echo "<br>";
	echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "  <tr bgcolor=\"CCCCCC\">\n";
	echo "    <th scope=\"col\"><font size=\"2\">BIL</font></th>\n";
	echo "    <th scope=\"col\"><font size=\"2\">NOKP</font></th>\n";
	echo "    <th scope=\"col\"><font size=\"2\">NAMA</font></th>\n";
	echo "    <th scope=\"col\"><font size=\"2\">MARKAH</font></th>\n";
	echo "    <th scope=\"col\"><font size=\"2\">GRED</font></th>\n";
	echo "  </tr>\n";
	
	$bil=0;
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

   echo "</body>";
   echo "</html>";

  //include 'kaki.php';
?> 
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?> 