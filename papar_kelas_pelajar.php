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
//	$kodseksm = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";
	$m=$_GET['data'];
	list ($ting, $kodsek)=split('[/]', $m);
	
	$tkt=array("P" => "PERALIHAN",
			   "T1" => "TINGKATAN SATU",
			   "T2" => "TINGKATAN DUA",
			   "T3" => "TINGKATAN TIGA",
			   "T4" => "TINGKATAN EMPAT",
			   "T5" => "TINGKATAN LIMA");
	
	echo "<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<br><br><br>";
	echo "<center>ENROLMEN MURID $tkt[$ting] MENGIKUT KELAS</center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">NAMA KELAS</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "  </tr>\n";	
	$query = "SELECT DISTINCT kelas$ting FROM tmurid WHERE kodsek$ting='$kodsek' AND $ting='$ting'  AND tahun$ting='".$_SESSION['tahun']."' ORDER BY kelas$ting"; 
//		if ($kodsek=='BEA4613')
//			echo "$query<br>";		

	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$sql_jum = "SELECT * FROM tmurid WHERE kodsek$ting='$kodsek' AND $ting='$ting' AND tahun$ting='".$_SESSION['tahun']."'";
	$qry_sql_jum = oci_parse($conn_sispa,$sql_jum);
	oci_execute($qry_sql_jum);
	$jumlah = count_row($sql_jum);
	$bil=1;
	while ($row = oci_fetch_array($result))
	{
		$namakelas=$row["KELAS$ting"];
		
		$sql_L = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_L FROM tmurid WHERE jantina ='L' AND kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='$namakelas' AND tahun$ting='".$_SESSION['tahun']."'"); 
		oci_execute($sql_L);
		$bil_L = oci_fetch_array($sql_L);
		$sql_P = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_P FROM tmurid WHERE jantina ='P' AND kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='$namakelas' AND tahun$ting='".$_SESSION['tahun']."'");
		oci_execute($sql_P);
		$bil_P = oci_fetch_array($sql_P);
		$jum_pel = $bil_L['BIL_L'] + $bil_P['BIL_P'];
		
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td><center><a href=papar_semak_pelajar.php?data=".$ting."/".$kodsek."/".$namakelas.">".strtoupper($ting)."</a></center></td>\n";
		echo "    <td><center><a href=papar_semak_pelajar.php?data=".$ting."/".$kodsek."/".$namakelas.">".strtoupper($namakelas)."</a></center></td>\n";
		echo "    <td><center>".$bil_L['BIL_L']."</center></td>\n";
		echo "    <td><center>".$bil_P['BIL_P']."</center></td>\n";
		echo "    <td><center>$jum_pel</center></td>\n";
		$bil++;
	
	}
}

if ($_SESSION['statussek']=="SR"){
//	$kodseksr = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	$m=$_GET['data'];
	list ($darjah, $kodsek)=split('[/]', $m);
	
	$tkt=array("d1" => "DARJAH SATU",
			   "d2" => "DARJAH DUA",
			   "d3" => "DARJAH TIGA",
			   "d4" => "DARJAH EMPAT",
			   "d5" => "DARJAH LIMA",
			   "d6" => "DARJAH ENAM");
	
	echo "<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<br><br><br>";
	echo "<center>ENROLMEN PELAJAR $tkt[$darjah] MENGIKUT KELAS</center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">DARJAH</th>\n";
	echo "    <th scope=\"col\">NAMA KELAS</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "  </tr>\n";
	
	$query = "SELECT DISTINCT kelas$darjah FROM tmuridsr WHERE kodsek$darjah='$kodsek' AND $darjah='$darjah' AND tahun$darjah='".$_SESSION['tahun']."' ORDER BY kelas$darjah"; 
	//echo $query;
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$sql_jum = "SELECT * FROM tmuridsr WHERE kodsek$darjah='$kodsek' AND $darjah='$darjah' AND tahun$darjah='".$_SESSION['tahun']."'";
	$qry = oci_parse($conn_sispa,$sql_jum);
	oci_execute($qry);
	$jumlah = count_row($sql_jum);
	$bil=1;
	while ($row = oci_fetch_array($result))
	{
		$namakelas=$row["KELAS$darjah"];
		
		$sql_L = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_L FROM tmuridsr WHERE jantina ='L' AND kodsek$darjah='$kodsek' AND $darjah='$darjah' AND kelas$darjah='$namakelas' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		oci_execute($sql_L);
		$bil_L = oci_fetch_array($sql_L);
		$sql_P = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_P FROM tmuridsr WHERE jantina ='P' AND kodsek$darjah='$kodsek' AND $darjah='$darjah' AND kelas$darjah='$namakelas' AND tahun$darjah='".$_SESSION['tahun']."'");
		oci_execute($sql_P);
		$bil_P = oci_fetch_array($sql_P);
		$jum_pel = $bil_L['BIL_L'] + $bil_P['BIL_P'];
		
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td><center><a href=papar_semak_pelajar.php?data=".$darjah."/".$kodsek."/".$namakelas.">".strtoupper($darjah)."</a></center></td>\n";
		echo "    <td><center><a href=papar_semak_pelajar.php?data=".$darjah."/".$kodsek."/".$namakelas.">".strtoupper($namakelas)."</a></center></td>\n";
		echo "    <td><center>".$bil_L['BIL_L']."</center></td>\n";
		echo "    <td><center>".$bil_P['BIL_P']."</center></td>\n";
		echo "    <td><center>$jum_pel</center></td>\n";
		$bil++;
	
	}
}


echo "<tr>";
echo "<td colspan=\"5\"><center>JUMLAH PELAJAR</center></td>";
echo "<td colspan=\"1\"><center>$jumlah</center></td>";
echo "</tr>\n";
echo "</th>\n";
echo "</tr>\n";
echo "</table>";
echo "<br><br><center><a href=semak_pelajar.php><< Kembali</a></center>";
?>
</td>
<?php include 'kaki.php';?> 