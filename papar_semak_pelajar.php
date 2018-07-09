<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Murid</p>

<?php

if ($_SESSION['statussek']=="SM"){
	$kodseksm = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";
	$m=$_GET['data'];
	list ($ting, $kodsek, $kelas)=split('[/]', $m);
	
	$tkt=array("p" => "PERALIHAN",
			   "t1" => "TINGKATAN SATU",
			   "t2" => "TINGKATAN DUA",
			   "t3" => "TINGKATAN TIGA",
			   "t4" => "TINGKATAN EMPAT",
			   "t5" => "TINGKATAN LIMA");
	
	echo "<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<br><br><br>";
	echo "<center>SENARAI NAMA MURID $tkt[$ting]<br>".strtoupper($ting)." $kelas</center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">NOKP</th>\n";
	echo "    <th scope=\"col\">NAMA</th>\n";
	echo "    <th scope=\"col\">JANTINA</th>\n";
	echo "  </tr>\n";
	//echo "$kodsek";
	
	//$query = "SELECT nokp,namap,jantina FROM tmurid WHERE ($kodseksm) AND $ting='$ting' AND kelas$ting='$kelas' AND tahun$ting='".$_SESSION['tahun']."' ORDER BY namap"; 
	$query = "SELECT nokp,namap,jantina FROM tmurid WHERE kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='$kelas' AND tahun$ting='".$_SESSION['tahun']."' ORDER BY namap"; 
	//echo $query;
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$bil=0;
	while ($row = oci_fetch_array($result))
	{
		$nokp = $row['NOKP'];
		$nama = $row['NAMAP'];
		$jantina = $row['JANTINA'];
		$bil=$bil+1;
			
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$nokp</a></td>\n";
		echo "    <td>$nama</a></td>\n";
		echo "    <td><center>$jantina</center></td>\n";
				
	}
echo "</th>\n";
echo "</tr>\n";
echo "</table>";
echo "<br><br><center><a href=papar_kelas_pelajar.php?data=".$ting."/".$kodsek."><< Kembali</a></center>";
}

if ($_SESSION['statussek']=="SR"){
	$kodseksr = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	$m=$_GET['data'];
	list ($darjah, $kodsek, $kelas)=split('[/]', $m);
	
	$tkt=array("d1" => "DARJAH SATU",
			   "d2" => "DARJAH DUA",
			   "d3" => "DARJAH TIGA",
			   "d4" => "DARJAH EMPAT",
			   "d5" => "DARJAH LIMA",
			   "d6" => "DARJAH ENAM");
	
	echo "<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<br><br><br>";
	echo "<center>SENARAI NAMA PELAJAR $tkt[$darjah]<br>".strtoupper($darjah)." $kelas</center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">NOKP</th>\n";
	echo "    <th scope=\"col\">NAMA</th>\n";
	echo "    <th scope=\"col\">JANTINA</th>\n";
	echo "  </tr>\n";
	//echo "$kodsek";
	
	$query = "SELECT * FROM tmuridsr WHERE ($kodseksr) AND $darjah='$darjah' AND kelas$darjah='$kelas' AND tahun$darjah='".$_SESSION['tahun']."' ORDER BY namap"; 
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$bil=0;
	//echo "$query<br>";
	while ($row = oci_fetch_array($result))
	{
		$nokp = $row['NOKP'];
		$nama = $row['NAMAP'];
		$jantina = $row['JANTINA'];
		$bil=$bil+1;
			
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$nokp</a></td>\n";
		echo "    <td>$nama</a></td>\n";
		echo "    <td><center>$jantina</center></td>\n";
				
	}
echo "</th>\n";
echo "</tr>\n";
echo "</table>";
echo "<br><br><center><a href=papar_kelas_pelajar.php?data=".$darjah."/".$kodsek."><< Kembali</a></center>";

}

?>
</td>
<?php include 'kaki.php';?> 