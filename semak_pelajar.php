<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Murid <font color="#FFFFFF"> Tarikh Kemaskini 11/8/2011 3:46 PM</font></p>

<?php

echo "<table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "<br><br><br>";
//$bilp = mysql_query("SELECT * FROM tmurid WHERE kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'");
//$numpe = mysql_num_rows($bilp);
//echo "$kodsek - $numpe";


echo "<form action=\"ctk_semak_pelajar.php\" method=\"POST\" target=\"_blank\">\n";

	
if ($_SESSION['statussek']=="SM"){
	$kodseksm = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";
	echo "<center><h3>ENROLMEN MURID MENGIKUT TINGKATAN</h3></center>";
	echo "<br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "  </tr>\n";	
	
	$query = "SELECT DISTINCT p,t1,t2,t3,t4,t5 FROM tmurid WHERE ($kodseksm) AND (tahunp='".$_SESSION['tahun']."' OR tahunt1='".$_SESSION['tahun']."' OR tahunt2='".$_SESSION['tahun']."' OR tahunt3='".$_SESSION['tahun']."' OR tahunt4='".$_SESSION['tahun']."' OR tahunt5='".$_SESSION['tahun']."' ) "; //ORDER BY ting"; 
	$stmt = OCIParse($conn_sispa,$query);
	OCIExecute($stmt);
	$num=count_row($query);
	$bil=0; $i=0;
	for ($c=0; $c<=5; $c++){
	
	if($c==0){
	 $darjah ="p";
	 }
	 else{
	$darjah="t$c";}
	
	OCIFetch($stmt);

		$bil=$bil+1;
		
		$sql_L = OCIParse($conn_sispa,"SELECT COUNT(jantina) AS Bil_L FROM tmurid WHERE jantina ='L' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_L);
		OCIFetch($sql_L);
		$bil_L = OCIResult($sql_L,"BIL_L");
		
		
		$sql_P = OCIParse($conn_sispa,"SELECT COUNT(jantina) AS Bil_P FROM tmurid WHERE jantina ='P' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_P);
		OCIFetch($sql_P);
		$bil_P = OCIResult($sql_P,"BIL_P");
		$jum_pel = OCIResult($sql_L,"BIL_L")/*$bil_L['Bil_L']*/ + OCIResult($sql_P,"BIL_P");//$bil_P['Bil_P'];
		
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td><center><a href=papar_kelas_pelajar.php?data=".strtoupper($darjah)."/".$kodsek.">".strtoupper($darjah)."</a></center></td>\n";
		echo "    <td><center>".OCIResult($sql_L,"BIL_L")."</center></td>\n";
		echo "    <td><center>".OCIResult($sql_P,"BIL_P")."</center></td>\n";
		echo "    <td><center>$jum_pel</center></td>\n";
		
		$jumlah=$jumlah+$jum_pel;
		
			$i=$i+1;

print "<input name=\"bil\" type=\"hidden\" readonly value=\"$bil\">";
print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
print "<input name=\"darjah[$i]\" type=\"hidden\" readonly value=\"$darjah\">";
print "<input name=\"bil_L[$i]\" type=\"hidden\" readonly value=\"$bil_L\">";
print "<input name=\"bil_P[$i]\" type=\"hidden\" readonly value=\"$bil_P\">";
print "<input name=\"jum_pel[$i]\" type=\"hidden\" readonly value=\"$jum_pel\">";

	}
	


}

OCIFreeStatement($stmt);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
if ($_SESSION['statussek']=="SR"){
	$kodseksr = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	echo "<center><h3>ENROLMEN MURID MENGIKUT DARJAH</h3></center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "  </tr>\n";	

	//$query = "SELECT DISTINCT d1,d2,d3,d4,d5,d6 FROM tmuridsr WHERE ($kodseksr) AND (tahund1='".$_SESSION['tahun']."' OR tahund2='".$_SESSION['tahun']."' OR tahund3='".$_SESSION['tahun']."' OR tahund4='".$_SESSION['tahun']."' OR tahund5='".$_SESSION['tahun']."' OR tahund6='".$_SESSION['tahun']."' ) "; //ORDER BY ting"; 
	$query = "SELECT DISTINCT d1,d2,d3,d4,d5,d6 FROM tmuridsr WHERE ($kodseksr) AND (tahund1='".$_SESSION['tahun']."' OR tahund2='".$_SESSION['tahun']."' OR tahund3='".$_SESSION['tahun']."' OR tahund4='".$_SESSION['tahun']."' OR tahund5='".$_SESSION['tahun']."' OR tahund6='".$_SESSION['tahun']."' ) "; //ORDER BY ting"; 
	$sql = OCIParse($conn_sispa,$query);
	OCIExecute($sql);
	$num=count_row($query);
	$bil=0; $i=0;
	for ($c=1; $c<=6; $c++){
	
	//echo "kodsek=$kodsek | darjah=$darjah[$i] | bil_L=$bil_L[$i] | bil_P=$bil_P[$i] | jum_pel=$jum_pel";
	$darjah="d$c";
	OCIFetch($sql);
	
		$bil=$bil+1;
		
		$sql_L = OCIParse($conn_sispa,"SELECT COUNT(jantina) AS Bil_L FROM tmuridsr WHERE jantina ='L' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_L);
		OCIFetch($sql_L);
		$sql_P = OCIParse($conn_sispa,"SELECT COUNT(jantina) AS Bil_P FROM tmuridsr WHERE jantina ='P' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_P);
		OCIFetch($sql_P);
		$jum_pel = OCIResult($sql_L,"BIL_L")/*$bil_L['Bil_L']*/ + OCIResult($sql_P,"BIL_P");//$bil_P['Bil_P'];
		
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td><center><a href=papar_kelas_pelajar.php?data=".strtoupper($darjah)."/".$kodsek.">".strtoupper($darjah)."</a></center></td>\n";
		echo "    <td><center>".OCIResult($sql_L,"BIL_L")."</center></td>\n";
		echo "    <td><center>".OCIResult($sql_P,"BIL_P")."</center></td>\n";
		echo "    <td><center>$jum_pel</center></td>\n";
		
		$jumlah=$jumlah+$jum_pel;
		
			$i=$i+1;

print "<input name=\"bil\" type=\"hidden\" readonly value=\"$bil\">";
print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
print "<input name=\"darjah[$i]\" type=\"hidden\" readonly value=\"$darjah\">";
print "<input name=\"bil_L[$i]\" type=\"hidden\" readonly value=\"$bil_L[Bil_L]\">";
print "<input name=\"bil_P[$i]\" type=\"hidden\" readonly value=\"$bil_P[Bil_P]\">";
print "<input name=\"jum_pel[$i]\" type=\"hidden\" readonly value=\"$jum_pel\">";

	}
	


}

//OCIFreeStatement($sql);

/*
if ($_SESSION['statussek']=="SR"){
    $kodseksr = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	//$kodseksr = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	echo "<center>ENROLMEN PELAJAR MENGIKUT DARJAH</center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">DARJAH</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "  </tr>\n";
	
	$query = mysql_query("SELECT DISTINCT d1,d2,d3,d4,d5,d6 FROM tmuridsr WHERE ($kodseksr) AND (tahund1='".$_SESSION['tahun']."' OR tahund2='".$_SESSION['tahun']."' OR tahund3='".$_SESSION['tahun']."' OR tahund4='".$_SESSION['tahun']."' OR tahund5'".$_SESSION['tahun']."'  OR tahund6'".$_SESSION['tahun']."') "); //ORDER BY ting"; 
	$num=mysql_num_rows($query);
	$bil=0; $i=0;
	for ($c=1; $c<=6; $c++){
	echo "$kodppd | $kodsek | $tahund1 | $ting | $bil6a[$i] | $jpep |";
	//echo "kodsek=$kodsek | darjah=$darjah[$i] | bil_L=$bil_L[$i] | bil_P=$bil_P[$i] | jum_pel=$jum_pel";
	$darjah="d$c";
	$row = mysql_fetch_array($query);

		$bil=$bil+1;
		
		$sql_L = mysql_query("SELECT COUNT(jantina) AS Bil_L FROM tmuridsr WHERE jantina ='L' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		$bil_L = mysql_fetch_array($sql_L);
		$sql_P = mysql_query("SELECT COUNT(jantina) AS Bil_P FROM tmuridsr WHERE jantina ='P' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		$bil_P = mysql_fetch_array($sql_P);
		$jum_pel = $bil_L['Bil_L'] + $bil_P['Bil_P'];
		
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td><center><a href=papar_kelas_pelajar.php?data=".strtoupper($darjah)."/".$kodsek.">".strtoupper($darjah)."</a></center></td>\n";
		echo "    <td><center>".$bil_L['Bil_L']."</center></td>\n";
		echo "    <td><center>".$bil_P['Bil_P']."</center></td>\n";
		echo "    <td><center>$jum_pel</center></td>\n";
		
		$jumlah=$jumlah+$jum_pel;
		
					$i=$i+1;

print "<input name=\"bil\" type=\"hidden\" readonly value=\"$bil\">";
print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
print "<input name=\"darjah[$i]\" type=\"hidden\" readonly value=\"$darjah\">";
print "<input name=\"bil_L[$i]\" type=\"hidden\" readonly value=\"$bil_L[Bil_L]\">";
print "<input name=\"bil_P[$i]\" type=\"hidden\" readonly value=\"$bil_P[Bil_P]\">";
print "<input name=\"jum_pel[$i]\" type=\"hidden\" readonly value=\"$jum_pel\">";
		
		
	}


}
*/
echo "<tr>";
echo "<td colspan=\"4\"><center>Jumlah Pelajar</center></td>";
echo "<td colspan=\"1\"><center>$jumlah</center></td>";
echo "</tr>\n";
echo "</th>\n";
echo "</tr>\n";
echo "</table>\n";
echo "<br>";
echo "<center>";
echo "<input type=\"submit\" name=\"submit\" value=\"PAPARAN CETAK\">\n";
echo "</form>";
echo "</center>";
//echo "<br><br><center><a href=edit_kelas.php>KEMASKINI NAMA KELAS BERMASALAH<br>TUKAR NAMA KELAS</a></center>";

echo "</td>";

include 'kaki.php';?> 