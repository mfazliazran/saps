<?php 
//die("Harap maaf. Utiliti semakan murid APDM dihentikan buat sementara waktu.");
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
$check_kemudahan_download_apdm = download_apdm();

set_time_limit(0);
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Murid <font color="#FFFFFF"> Tarikh Kemaskini 12/02/2014 3:46 PM</font></p>
<?php
//echo "kodsekolah: $kodsek";
if($_SESSION["tahun"]<>date("Y"))
	die('Utiliti import data APDM hanya untuk tahun semasa sahaja.');
	
//if(date('Y')=='2017')
	//die("KEMUDAHAN INI DIBERHENTIKAN BUAT SEMENTARA WAKTU.");
	
echo "<br><br>";
echo"<table border=\"1\" align=\"center\" width='90%'>";
//echo "<form action=\"ctk_semak_pelajar.php\" method=\"POST\" target=\"_blank\">\n";
echo "<form action=\"migrate_tmuridsm.php\" method=\"POST\">\n";
echo"<tr><td align='center' colspan='2'><h3>ENROLMEN MURID</h3></td></tr>";
echo"<tr><td>";
/////////////////////////////////////////senarai murid SAPAS//////////////////////////////////////////
echo "<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
//echo "<br><br>";
if ($_SESSION['statussek']=="SM"){
	$kodseksm = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";
	echo "<center><h3>SAPS</h3></center>";
	//echo "<br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">O</th>\n";
	echo "    <th scope=\"col\">ISLAM</th>\n";
	echo "    <th scope=\"col\">BUKAN <br>ISLAM</th>\n";
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
	$darjah="T$c";}
	
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
		
		$sql_O = OCIParse($conn_sispa,"SELECT COUNT(jantina) AS Bil_O FROM tmurid WHERE jantina NOT IN('L','P') AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_O);
		OCIFetch($sql_O);
		$bil_O = OCIResult($sql_O,"BIL_O");
		
		$sql_AI = OCIParse($conn_sispa,"SELECT COUNT(AGAMA) AS Bil_AI FROM tmurid WHERE AGAMA='01' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_AI);
		OCIFetch($sql_AI);
		$bil_ai = OCIResult($sql_AI,"BIL_AI");
		
		$sql_BI = OCIParse($conn_sispa,"SELECT COUNT(AGAMA) AS Bil_BI FROM tmurid WHERE AGAMA<>'01' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_BI);
		OCIFetch($sql_BI);
		$bil_bi = OCIResult($sql_BI,"BIL_BI");
		
		$jum_pel = OCIResult($sql_L,"BIL_L") + OCIResult($sql_P,"BIL_P") + OCIResult($sql_O,"BIL_O");//$bil_P['Bil_P'];
		
		echo "<tr>\n";
		echo "<td><center>$bil</center></td>\n";
		echo "<td><center><a href=papar_kelas_pelajar_apdm.php?data=".strtoupper($darjah)."/".$kodsek.">".strtoupper($darjah)."</a></center></td>\n";
		echo "<td><center>".OCIResult($sql_L,"BIL_L")."</center></td>\n";
		echo "<td><center>".OCIResult($sql_P,"BIL_P")."</center></td>\n";
		echo "<td><center>".OCIResult($sql_O,"BIL_O")."</center></td>\n";
		echo "<td><center>".OCIResult($sql_AI,"BIL_AI")."</center></td>\n";
		echo "<td><center>".OCIResult($sql_BI,"BIL_BI")."</center></td>\n";		
		echo "<td><center>$jum_pel</center></td>\n";
		
		$jumlah=$jumlah+$jum_pel;
		
	$i=$i+1;
	//$bil_L[$i] = $bil_L;
	//$bil_P[$i] = $bil_P;
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
	echo "<center><h3>SAPS</h3></center>";
	//echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">O</th>\n";
	echo "    <th scope=\"col\">ISLAM</th>\n";
	echo "    <th scope=\"col\">BUKAN <br>ISLAM</th>\n";	
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "  </tr>\n";	


	$query = "SELECT DISTINCT d1,d2,d3,d4,d5,d6 FROM tmuridsr WHERE ($kodseksr) AND (tahund1='".$_SESSION['tahun']."' OR tahund2='".$_SESSION['tahun']."' OR tahund3='".$_SESSION['tahun']."' OR tahund4='".$_SESSION['tahun']."' OR tahund5='".$_SESSION['tahun']."' OR tahund6='".$_SESSION['tahun']."' ) "; //ORDER BY ting"; 
	$sql = OCIParse($conn_sispa,$query);
	OCIExecute($sql);
	$num=count_row($query);
	$bil=0; $i=0;
	for ($c=1; $c<=6; $c++){
	
	$darjah="D$c";
	OCIFetch($sql);
	
		$bil=$bil+1;
		
		$sql_L = OCIParse($conn_sispa,"SELECT COUNT(jantina) AS Bil_L FROM tmuridsr WHERE jantina ='L' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_L);
		OCIFetch($sql_L);
		$sql_P = OCIParse($conn_sispa,"SELECT COUNT(jantina) AS Bil_P FROM tmuridsr WHERE jantina ='P' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_P);
		OCIFetch($sql_P);
		$sql_O = OCIParse($conn_sispa,"SELECT COUNT(jantina) AS Bil_O FROM tmuridsr WHERE jantina NOT IN('L','P') AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_O);
		OCIFetch($sql_O);
		
		$sql_AI = OCIParse($conn_sispa,"SELECT COUNT(AGAMA) AS Bil_AI FROM tmuridsr WHERE AGAMA='01' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_AI);
		OCIFetch($sql_AI);
		$bil_ai = OCIResult($sql_AI,"BIL_AI");
		
		$sql_BI = OCIParse($conn_sispa,"SELECT COUNT(AGAMA) AS Bil_BI FROM tmuridsr WHERE AGAMA<>'01' AND kodsek$darjah='$kodsek' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		OCIExecute($sql_BI);
		OCIFetch($sql_BI);
		$bil_bi = OCIResult($sql_BI,"BIL_BI");
		
		$jum_pel = OCIResult($sql_L,"BIL_L") + OCIResult($sql_P,"BIL_P") + OCIResult($sql_O,"BIL_O");//$bil_P['Bil_P'];
		
		echo "<tr>\n";
		echo "<td><center>$bil</center></td>\n";
		echo "<td><center><a href=papar_kelas_pelajar_apdm.php?data=".strtoupper($darjah)."/".$kodsek.">".strtoupper($darjah)."</a></center></td>\n";
		echo "<td><center>".OCIResult($sql_L,"BIL_L")."</center></td>\n";
		echo "<td><center>".OCIResult($sql_P,"BIL_P")."</center></td>\n";
		echo "<td><center>".OCIResult($sql_O,"BIL_O")."</center></td>\n";
		echo "<td><center>".OCIResult($sql_AI,"BIL_AI")."</center></td>\n";
		echo "<td><center>".OCIResult($sql_BI,"BIL_BI")."</center></td>\n";
		echo "<td><center>$jum_pel</center></td>\n";
		
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

echo "<tr>";
echo "<td colspan=\"7\"><center>Jumlah Pelajar</center></td>";
echo "<td colspan=\"1\"><center>$jumlah</center></td>";
echo "</tr>\n";
echo "</th>\n";
echo "</tr>\n";
echo "</table>\n";// //////////////////////////Tamat SAPS/////////////////////////
echo"</td><td>";

###########################################senarai murid APDM#############################################################
echo "<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
if ($_SESSION['statussek']=="SM"){
	
	echo "<center><h3>APDM</h3></center>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">O</th>\n";
	echo "    <th scope=\"col\">ISLAM</th>\n";
	echo "    <th scope=\"col\">BUKAN <br>ISLAM</th>\n";	
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "    <th scope=\"col\">IMPORT TINGKATAN</th>\n";
	echo "  </tr>\n";	
	
	$query = "SELECT NAMA,NOKP,NOSIJILLAHIR,KODKAUM,KODAGAMA,KODJANTINA,KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH,IDKODJENISSEKOLAH,IDKODPPD,IDKODNEGERI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' and KODTINGKATANTAHUN IN ('P','T1','T2','T3','T4','T5') ORDER BY TRIM(KODTINGKATANTAHUN),NAMA";
	//echo $query;
	$stmt = OCIParse($conn_sispa,$query);
	OCIExecute($stmt);
	$num=count_row($query);
	$bil=0; $p=0;
	for ($c=0; $c<=5; $c++){
	
	if($c==0){
	 $darjah ="P";
	 }
	 else{
	$darjah="T$c";}
	
	OCIFetch($stmt);

	$bil=$bil+1;
		
	$sql_L = OCIParse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_L FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODJANTINA='L' AND TRIM(KODTINGKATANTAHUN) = '$darjah' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA");
	OCIExecute($sql_L);
	OCIFetch($sql_L);
	$bil_L_apdm = OCIResult($sql_L,"BIL_L");
		
		
	$sql_P = OCIParse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_P FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODJANTINA='P' AND TRIM(KODTINGKATANTAHUN) = '$darjah' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA"); 
	OCIExecute($sql_P);
	OCIFetch($sql_P);
	$bil_P_apdm = OCIResult($sql_P,"BIL_P");
		
	$sql_O = OCIParse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_O FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODJANTINA NOT IN('L','P') AND KODTINGKATANTAHUN = '$darjah' ORDER BY KODTINGKATANTAHUN,NAMA"); 
	OCIExecute($sql_O);
	OCIFetch($sql_O);
	$bil_O_apdm = OCIResult($sql_O,"BIL_O");
		
	$sql_AI = OCIParse($conn_sispa,"SELECT COUNT (KODAGAMA) AS BIL_AI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODAGAMA='01' AND KODTINGKATANTAHUN = '$darjah' ORDER BY KODTINGKATANTAHUN,NAMA"); 
	OCIExecute($sql_AI);
	OCIFetch($sql_AI);
	$bil_ai_apdm = OCIResult($sql_AI,"BIL_AI");	
		
	$sql_BI = OCIParse($conn_sispa,"SELECT COUNT (KODAGAMA) AS BIL_BI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODAGAMA<>'01' AND KODTINGKATANTAHUN = '$darjah' ORDER BY KODTINGKATANTAHUN,NAMA"); 
	OCIExecute($sql_BI);
	OCIFetch($sql_BI);
	$bil_bi_apdm = OCIResult($sql_BI,"BIL_BI");
		
	$jum_pel_apdm = OCIResult($sql_L,"BIL_L") + OCIResult($sql_P,"BIL_P") + OCIResult($sql_O,"BIL_O");
		
	$p=$p+1;
	$bil_L_apdm[$p] = OCIResult($sql_L,"BIL_L");
	$bil_P_apdm[$p] = OCIResult($sql_P,"BIL_P");
		
	echo "  <tr>\n";
	echo "    <td><center>$bil</center></td>\n";
	echo "    <td><center><a href=papar_kelas_pelajar_apdm.php?data=".strtoupper($darjah)."/".$kodsek.">".strtoupper($darjah)."</a></center></td>\n";
	echo "    <td><center>".OCIResult($sql_L,"BIL_L")."</center></td>\n";
	echo "    <td><center>".OCIResult($sql_P,"BIL_P")."</center></td>\n";
	echo "    <td><center>".OCIResult($sql_O,"BIL_O")."</center></td>\n";
	echo "    <td><center>".OCIResult($sql_AI,"BIL_AI")."</center></td>\n";
	echo "    <td><center>".OCIResult($sql_BI,"BIL_BI")."</center></td>\n";
	echo "    <td bgcolor='$warna'><center>$jum_pel_apdm</center></td>\n";
	if($jum_pel_apdm=="")
		echo "    <td bgcolor='$warna'><center>-</center></td>\n";
	else{
		//disabled 19/11/2012 boleh dibuka apabila awal tahun untuk import pelajar baru
		
		if($check_kemudahan_download_apdm==0){
			echo "    <td bgcolor='$warna'><center><a href=\"migrate_tmuridsm_ting.php?tahap=$darjah\" onclick=\"return (confirm('Adakah anda pasti import data tahap $darjah ?'))\">Import</a></center></td>\n";
		} else {
			echo "<td bgcolor='$warna'><center>import</center></td>";
		}
	}
	$warna = "";
		
	$jumlah_apdm=$jumlah_apdm+$jum_pel_apdm;
		
	print "<input name=\"bil\" type=\"hidden\" readonly value=\"$bil\">";
	print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
	print "<input name=\"darjah[$p]\" type=\"hidden\" readonly value=\"$darjah\">";
	print "<input name=\"bil_L[$p]\" type=\"hidden\" readonly value=\"$bil_L\">";
	print "<input name=\"bil_P[$p]\" type=\"hidden\" readonly value=\"$bil_P\">";
	print "<input name=\"jum_pel[$p]\" type=\"hidden\" readonly value=\"$jum_pel\">";
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
if ($_SESSION['statussek']=="SR"){
	$kodseksr = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	echo "<center><h3>APDM</h3></center>";
	//echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">O</th>\n";
	echo "    <th scope=\"col\">ISLAM</th>\n";
	echo "    <th scope=\"col\">BUKAN <br>ISLAM</th>\n";	
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "    <th scope=\"col\">IMPORT DARJAH</th>\n";
	echo "  </tr>\n";	


	$query = "SELECT NAMA,NOKP,NOSIJILLAHIR,KODKAUM,KODAGAMA,KODJANTINA,KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH,IDKODJENISSEKOLAH,IDKODPPD,IDKODNEGERI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND TRIM(KODTINGKATANTAHUN) IN ('D1','D2','D3','D4','D5','D6') ORDER BY TRIM(KODTINGKATANTAHUN),NAMA"; //ORDER BY ting"; 
	//if($kodsek=='PBC1055')
		//echo $query."<br>";
	$sql = OCIParse($conn_sispa,$query);
	OCIExecute($sql);
	$num=count_row($query);
	$bil=0; $i=0;
	for ($c=1; $c<=6; $c++){
	
	$darjah="D$c";
	OCIFetch($sql);
	
	$bil=$bil+1;
	
	$sql_L = OCIParse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_L FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODJANTINA='L' AND KODTINGKATANTAHUN = '$darjah' ORDER BY KODTINGKATANTAHUN,NAMA"); 
	OCIExecute($sql_L);
	OCIFetch($sql_L);

	$sql_P = OCIParse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_P FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODJANTINA='P' AND KODTINGKATANTAHUN = '$darjah' ORDER BY KODTINGKATANTAHUN,NAMA"); 
	OCIExecute($sql_P);
	OCIFetch($sql_P);
		
	$sql_O = OCIParse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_O FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODJANTINA NOT IN('L','P') AND KODTINGKATANTAHUN = '$darjah' ORDER BY KODTINGKATANTAHUN,NAMA"); 
	OCIExecute($sql_O);
	OCIFetch($sql_O);
		
	$sql_AI = OCIParse($conn_sispa,"SELECT COUNT (KODAGAMA) AS BIL_AI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODAGAMA='01' AND KODTINGKATANTAHUN = '$darjah' ORDER BY KODTINGKATANTAHUN,NAMA"); 
	OCIExecute($sql_AI);
	OCIFetch($sql_AI);
	$bil_ai_apdm = OCIResult($sql_AI,"BIL_AI");	
		
	$sql_BI = OCIParse($conn_sispa,"SELECT COUNT (KODAGAMA) AS BIL_BI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODAGAMA<>'01' AND KODTINGKATANTAHUN = '$darjah' ORDER BY KODTINGKATANTAHUN,NAMA"); 
	OCIExecute($sql_BI);
	OCIFetch($sql_BI);
	$bil_bi_apdm = OCIResult($sql_BI,"BIL_BI");		
		
	$jum_pel_apdm = OCIResult($sql_L,"BIL_L") + OCIResult($sql_P,"BIL_P") + OCIResult($sql_O,"BIL_O");
		
	echo "  <tr>\n";
	echo "    <td><center>$bil</center></td>\n";
	echo "    <td><center><a href=papar_kelas_pelajar_apdm.php?data=".strtoupper($darjah)."/".$kodsek.">".strtoupper($darjah)."</a></center></td>\n";
	echo "    <td><center>".OCIResult($sql_L,"BIL_L")."</center></td>\n";
	echo "    <td><center>".OCIResult($sql_P,"BIL_P")."</center></td>\n";
	echo "    <td><center>".OCIResult($sql_O,"BIL_O")."</center></td>\n";
	echo "    <td><center>".OCIResult($sql_AI,"BIL_AI")."</center></td>\n";
	echo "    <td><center>".OCIResult($sql_BI,"BIL_BI")."</center></td>\n";
	echo "    <td><center>$jum_pel_apdm</center></td>\n";
	if($jum_pel_apdm=="")
		echo "    <td bgcolor='$warna'><center>-</center></td>\n";
	else{
		//disabled 19/11/2012 boleh dibuka apabila awal tahun untuk import pelajar baru
		
		if($check_kemudahan_download_apdm==0){
			echo "    <td bgcolor='$warna'><center><a href=\"migrate_tmuridsm_ting.php?tahap=$darjah\" onclick=\"return (confirm('Adakah anda pasti import data tahap $darjah ?'))\">Import</a></center></td>\n";
		} else {
			echo "<td bgcolor='$warna'><center>import</center></td>";
		}
	}
		//}
		
	$jumlah_apdm=$jumlah_apdm+$jum_pel_apdm;
	$i=$i+1;

	print "<input name=\"bil\" type=\"hidden\" readonly value=\"$bil\">";
	print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
	print "<input name=\"darjah[$i]\" type=\"hidden\" readonly value=\"$darjah\">";
	print "<input name=\"bil_L[$i]\" type=\"hidden\" readonly value=\"$bil_L[Bil_L]\">";
	print "<input name=\"bil_P[$i]\" type=\"hidden\" readonly value=\"$bil_P[Bil_P]\">";
	print "<input name=\"jum_pel[$i]\" type=\"hidden\" readonly value=\"$jum_pel_apdm\">";
	}
}

echo "<tr>";
echo "<td colspan=\"7\"><center>Jumlah Pelajar</center></td>";
echo "<td colspan=\"1\"><center>$jumlah_apdm</center></td>";
echo "<td><center>-</center></td>";
echo "</tr>\n";
echo "</th>\n";
echo "</tr>\n";
echo "</table>\n";//////////////////////////////////////apdm///////////////////////////
echo"</td></tr>";
echo "</table>\n";/////////////////////Main table//////////////////////////
echo "<br>";
echo "<center>";
//disabled 19/11/2012 boleh dibuka apabila awal tahun untuk import pelajar baru
if($level=='3' or $level=='4'){
	if($check_kemudahan_download_apdm = 0){
		echo "<a href=\"migrate_tmuridsm.php\">IMPORT DATA APDM</a>";
	} else {
		echo "IMPORT DATA APDM";
	}
}
echo "</form>";
echo "</center>";

echo "<strong><font color='#FF0000'>* Sekiranya terdapat pelajar yang tidak dapat di import, sila import pelajar berkenaan secara individu mengikut kelas</font></strong>";
echo "</td>";

include 'kaki.php';?> 