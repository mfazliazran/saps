<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
$check_kemudahan_download_apdm = download_apdm();

// echo $check_kemudahan_download_apdm.":=testing";

set_time_limit(0);
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Murid</p>

<?php
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
	echo "<center>ENROLMEN MURID $tkt[$ting] MENGIKUT KELAS (SAPS)</center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">NAMA KELAS</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">O</th>\n";
	echo "    <th scope=\"col\">ISLAM</th>\n";
	echo "    <th scope=\"col\">BUKAN <br>ISLAM</th>\n";	
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "  </tr>\n";	
	$query = "SELECT DISTINCT kelas$ting FROM tmurid WHERE kodsek$ting='$kodsek' AND $ting='$ting'  AND tahun$ting='".$_SESSION['tahun']."' ORDER BY kelas$ting"; 

	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$sql_jum = "SELECT * FROM tmurid WHERE kodsek$ting='$kodsek' AND $ting='$ting' AND tahun$ting='".$_SESSION['tahun']."'";
	$qry_sql_jum = oci_parse($conn_sispa,$sql_jum);
	oci_execute($qry_sql_jum);
	$jumlah = count_row($sql_jum);
	$bil=1;
	while ($row = oci_fetch_array($result))
	{
		//$namakelas=oci_escape_string($row["KELAS$ting"]);
		$namakelas=$row["KELAS$ting"];
		//$namakelas=str_replace('\'','*',$namakelas);
		//$namakelas=str_replace(' ','_',oci_escape_string($row["KELAS$ting"]));
		$sql_L = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_L FROM tmurid WHERE jantina ='L' AND kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='$namakelas' AND tahun$ting='".$_SESSION['tahun']."'"); 
		oci_execute($sql_L);
		$bil_L = oci_fetch_array($sql_L);
		
		$sql_P = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_P FROM tmurid WHERE jantina ='P' AND kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='$namakelas' AND tahun$ting='".$_SESSION['tahun']."'");
		oci_execute($sql_P);
		$bil_P = oci_fetch_array($sql_P);
		
		$sql_O = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_O FROM tmurid WHERE jantina NOT IN('L','P') AND kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='$namakelas' AND tahun$ting='".$_SESSION['tahun']."'");
		oci_execute($sql_O);
		$bil_O = oci_fetch_array($sql_O);
		
		$sql_AI = oci_parse($conn_sispa,"SELECT COUNT(agama) AS Bil_AI FROM tmurid WHERE AGAMA='01' AND kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='$namakelas' AND tahun$ting='".$_SESSION['tahun']."'");
		oci_execute($sql_AI);
		$bil_ai = oci_fetch_array($sql_AI);
		
		$sql_BI = oci_parse($conn_sispa,"SELECT COUNT(agama) AS Bil_BI FROM tmurid WHERE AGAMA<>'01' AND kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='$namakelas' AND tahun$ting='".$_SESSION['tahun']."'");
		oci_execute($sql_BI);
		$bil_bi = oci_fetch_array($sql_BI);		
		
		$jum_pel = $bil_L['BIL_L'] + $bil_P['BIL_P'] + $bil_O['BIL_O'];
		
		echo "<tr>\n";
		echo "<td><center>$bil</center></td>\n";
		echo "<td><center>".strtoupper($ting)."</a></center></td>\n";
		//echo "<td><center><a href=papar_semak_pelajar_apdm.php?data=".$ting."/".$kodsek."/".urlencode($namakelas).">".strtoupper($namakelas)."</a></center></td>\n";
		echo "<td><center>".strtoupper($namakelas)."</center></td>\n";
		echo "<td><center>".$bil_L['BIL_L']."</center></td>\n";
		echo "<td><center>".$bil_P['BIL_P']."</center></td>\n";
		echo "<td><center>".$bil_O['BIL_O']."</center></td>\n";
		echo "<td><center>".$bil_ai['BIL_AI']."</center></td>\n";
		echo "<td><center>".$bil_bi['BIL_BI']."</center></td>\n";
		echo "<td><center>$jum_pel</center></td>\n";
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
	echo "<center>ENROLMEN PELAJAR $tkt[$darjah] MENGIKUT KELAS (SAPS)</center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">DARJAH</th>\n";
	echo "    <th scope=\"col\">NAMA KELAS</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">O</th>\n";
	echo "    <th scope=\"col\">ISLAM</th>\n";
	echo "    <th scope=\"col\">BUKAN <br>ISLAM</th>\n";
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
		//$namakelas=oci_escape_string($row["KELAS$darjah"]);
		$namakelas=$row["KELAS$darjah"];
		//$namakelas=str_replace(' ','_',oci_escape_string($row["KELAS$darjah"]));
		$sql_L = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_L FROM tmuridsr WHERE jantina ='L' AND kodsek$darjah='$kodsek' AND $darjah='$darjah' AND kelas$darjah='$namakelas' AND tahun$darjah='".$_SESSION['tahun']."'"); 
		oci_execute($sql_L);
		$bil_L = oci_fetch_array($sql_L);
		
		$sql_P = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_P FROM tmuridsr WHERE jantina ='P' AND kodsek$darjah='$kodsek' AND $darjah='$darjah' AND kelas$darjah='$namakelas' AND tahun$darjah='".$_SESSION['tahun']."'");
		oci_execute($sql_P);
		$bil_P = oci_fetch_array($sql_P);
		
		$sql_O = oci_parse($conn_sispa,"SELECT COUNT(jantina) AS Bil_O FROM tmuridsr WHERE jantina NOT IN('L','P') AND kodsek$darjah='$kodsek' AND $darjah='$darjah' AND kelas$darjah='$namakelas' AND tahun$darjah='".$_SESSION['tahun']."'");
		oci_execute($sql_O);
		$bil_O = oci_fetch_array($sql_O);
		
		$sql_AI = oci_parse($conn_sispa,"SELECT COUNT(agama) AS Bil_AI FROM tmuridsr WHERE AGAMA='01' AND kodsek$darjah='$kodsek' AND $darjah='$darjah' AND kelas$darjah='$namakelas' AND tahun$darjah='".$_SESSION['tahun']."'");
		oci_execute($sql_AI);
		$bil_ai = oci_fetch_array($sql_AI);
		
		$sql_BI = oci_parse($conn_sispa,"SELECT COUNT(agama) AS Bil_BI FROM tmuridsr WHERE AGAMA<>'01' AND kodsek$darjah='$kodsek' AND $darjah='$darjah' AND kelas$darjah='$namakelas' AND tahun$darjah='".$_SESSION['tahun']."'");
		oci_execute($sql_BI);
		$bil_bi = oci_fetch_array($sql_BI);			
		
		$jum_pel = $bil_L['BIL_L'] + $bil_P['BIL_P'] + $bil_O['BIL_O'];
		
		echo "<tr>\n";
		echo "<td><center>$bil</center></td>\n";
		echo "<td><center>".strtoupper($darjah)."</a></center></td>\n";
		echo "<td><center>".strtoupper($namakelas)."</a></center></td>\n";
		//echo "<td><center><a href=papar_semak_pelajar_apdm.php?data=".$darjah."/".$kodsek."/".urlencode($namakelas).">".strtoupper($namakelas)."</a></center></td>\n";
		echo "<td><center>".$bil_L['BIL_L']."</center></td>\n";
		echo "<td><center>".$bil_P['BIL_P']."</center></td>\n";
		echo "<td><center>".$bil_O['BIL_O']."</center></td>\n";
		echo "<td><center>".$bil_ai['BIL_AI']."</center></td>\n";
		echo "<td><center>".$bil_bi['BIL_BI']."</center></td>\n";
		echo "<td><center>$jum_pel</center></td>\n";
		$bil++;
	}
}

echo "<tr>";
echo "<td colspan=\"8\"><center>JUMLAH PELAJAR</center></td>";
echo "<td colspan=\"1\"><center>$jumlah</center></td>";
echo "</tr>\n";
echo "</th>\n";
echo "</tr>\n";
echo "</table>\n";// //////////////////////////Tamat SAPS/////////////////////////
echo"</td><td>";

###########################################senarai murid APDM#############################################################

/////////////////////////////////////////senarai murid SAPAS//////////////////////////////////////////
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
	echo "<center>ENROLMEN MURID $tkt[$ting] MENGIKUT KELAS (APDM)</center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">NAMA KELAS</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">O</th>\n";
	echo "    <th scope=\"col\">ISLAM</th>\n";
	echo "    <th scope=\"col\">BUKAN <br>ISLAM</th>\n";	
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "    <th scope=\"col\">IMPORT KELAS</th>\n";
	echo "  </tr>\n";	

	//$query = "select idkelas,namakelas,namaguru,kodtingdariasal,aktif,nokpguru from tkelassm where kodtingdariasal='$ting' and kodsekolah='$kodsek' and aktif is null order by kodtingdariasal,namakelas asc";
	$query = "SELECT DISTINCT KODTINGKATANTAHUN,NAMAKELAS FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' and trim(KODTINGKATANTAHUN)='$ting' ORDER BY TRIM(KODTINGKATANTAHUN),NAMAKELAS"; 
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	//dapatkan jum untuk tingkatan
	$sql_jum = "SELECT NAMA,NOKP,NOSIJILLAHIR,KODKAUM,KODAGAMA,KODJANTINA,KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH,IDKODJENISSEKOLAH,IDKODPPD,IDKODNEGERI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODTINGKATANTAHUN='$ting' AND TRIM(KODTINGKATANTAHUN) IN ('P','T1','T2','T3','T4','T5') ORDER BY TRIM(KODTINGKATANTAHUN),NAMA";
	$qry_sql_jum = oci_parse($conn_sispa,$sql_jum);
	oci_execute($qry_sql_jum);
	$jumlah_apdm = count_row($sql_jum);
	####################jum
	$bil=1;
	while ($row = oci_fetch_array($result))
	{
		//$namakelas=oci_escape_string($row["NAMAKELAS"]);
		$namakelas=$row["NAMAKELAS"];
		$sql_L = oci_parse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_L FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='".oci_escape_string($row["NAMAKELAS"])."' AND KODJANTINA='L' AND TRIM(KODTINGKATANTAHUN) = '$ting' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA"); 
		oci_execute($sql_L);
		$bil_L_apdm = oci_fetch_array($sql_L);
		
		$sql_P = oci_parse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_P FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='".oci_escape_string($row["NAMAKELAS"])."' AND KODJANTINA='P' AND TRIM(KODTINGKATANTAHUN) = '$ting' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA");
		oci_execute($sql_P);
		$bil_P_apdm = oci_fetch_array($sql_P);
		
		$sql_O = oci_parse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_O FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='".oci_escape_string($row["NAMAKELAS"])."' AND KODJANTINA NOT IN('L','P') AND TRIM(KODTINGKATANTAHUN) = '$ting' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA");
		oci_execute($sql_O);
		$bil_O_apdm = oci_fetch_array($sql_O);
		
		$sql_AI = oci_parse($conn_sispa,"SELECT COUNT (KODAGAMA) AS BIL_AI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='".oci_escape_string($row["NAMAKELAS"])."' AND KODAGAMA='01' AND TRIM(KODTINGKATANTAHUN) = '$ting' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA");
		oci_execute($sql_AI);
		$bil_ai_apdm = oci_fetch_array($sql_AI);
		
		$sql_BI = oci_parse($conn_sispa,"SELECT COUNT (KODAGAMA) AS BIL_BI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='".oci_escape_string($row["NAMAKELAS"])."' AND KODAGAMA<>'01' AND TRIM(KODTINGKATANTAHUN) = '$ting' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA");
		oci_execute($sql_BI);
		$bil_bi_apdm = oci_fetch_array($sql_BI);
		
		$jum_pel_apdm = $bil_L_apdm['BIL_L'] + $bil_P_apdm['BIL_P'] + $bil_O_apdm['BIL_O'];
		
		echo "<tr>\n";
		echo "<td><center>$bil</center></td>\n";
		echo "<td><center>".strtoupper($ting)."</a></center></td>\n";
		echo "<td><center><a href=papar_semak_pelajar_apdm.php?data=".$ting."|".$kodsek."|".urlencode($namakelas).">".strtoupper($namakelas)."</a></center></td>\n";
		echo "<td><center>".$bil_L_apdm['BIL_L']."</center></td>\n";
		echo "<td><center>".$bil_P_apdm['BIL_P']."</center></td>\n";
		echo "<td><center>".$bil_O_apdm['BIL_O']."</center></td>\n";
		echo "<td><center>".$bil_ai_apdm['BIL_AI']."</center></td>\n";
		echo "<td><center>".$bil_bi_apdm['BIL_BI']."</center></td>\n";
		echo "<td><center>$jum_pel_apdm</center></td>\n";
		if($jum_pel_apdm=="")
			echo "<td bgcolor='$warna'><center>-</center></td>\n";
		else{
			//disabled 19/11/2012 boleh dibuka apabila awal tahun untuk import pelajar baru
			if($check_kemudahan_download_apdm==0){
				echo "<td bgcolor='$warna'><center><a href=\"migrate_tmuridsm_kelas.php?tahap=$ting&namakelas=".urlencode($namakelas)."\" onclick=\"return (confirm('Adakah anda pasti import data kelas $namakelas ?'))\">Import</a></center></td>\n";
			} else {
				echo "<td bgcolor='$warna'><center>Import</center></td>";
			}
			//echo "<td bgcolor='$warna'><center>-</center></td>\n";
		}
		$bil++;
	
	}
}

if ($_SESSION['statussek']=="SR"){
//	$kodseksr = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	$m=$_GET['data'];
	list ($darjah, $kodsek)=split('[/]', $m);
	
	$tkt=array("D1" => "DARJAH SATU",
			   "D2" => "DARJAH DUA",
			   "D3" => "DARJAH TIGA",
			   "D4" => "DARJAH EMPAT",
			   "D5" => "DARJAH LIMA",
			   "D6" => "DARJAH ENAM");
	
	echo "<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<br><br><br>";
	echo "<center>ENROLMEN PELAJAR $tkt[$darjah] MENGIKUT KELAS (APDM)</center>";
	echo "<br><br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">DARJAH</th>\n";
	echo "    <th scope=\"col\">NAMA KELAS</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">O</th>\n";
	echo "    <th scope=\"col\">ISLAM</th>\n";
	echo "    <th scope=\"col\">BUKAN <br>ISLAM</th>\n";	
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "    <th scope=\"col\">IMPORT KELAS</th>\n";
	echo "  </tr>\n";
	
	$query = "SELECT DISTINCT KODTINGKATANTAHUN,NAMAKELAS FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' and trim(KODTINGKATANTAHUN)='$darjah' ORDER BY TRIM(KODTINGKATANTAHUN),NAMAKELAS"; 
	//echo $query;
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$sql_jum = "SELECT NAMA,NOKP,NOSIJILLAHIR,KODKAUM,KODAGAMA,KODJANTINA,KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH,IDKODJENISSEKOLAH,IDKODPPD,IDKODNEGERI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND KODTINGKATANTAHUN='$darjah' AND TRIM(KODTINGKATANTAHUN) IN ('D1','D2','D3','D4','D5','D6') ORDER BY TRIM(KODTINGKATANTAHUN),NAMA";
	
	$qry = oci_parse($conn_sispa,$sql_jum);
	oci_execute($qry);
	$jumlah_apdm = count_row($sql_jum);
	$bil=1;
	while ($row = oci_fetch_array($result))
	{
		$namakelas=oci_escape_string($row["NAMAKELAS"]);
		$namakelas2=$row["NAMAKELAS"];//untuk kawan single qoute
		//$namakelas=str_replace(' ','_',oci_escape_string($row["NAMAKELAS"]));
		$sql_L = oci_parse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_L FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='$namakelas' AND KODJANTINA='L' AND TRIM(KODTINGKATANTAHUN) = '$darjah' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA"); 
		oci_execute($sql_L);
		$bil_L_apdm = oci_fetch_array($sql_L);
		
		$sql_P = oci_parse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_P FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='$namakelas' AND KODJANTINA='P' AND TRIM(KODTINGKATANTAHUN) = '$darjah' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA");
		oci_execute($sql_P);
		$bil_P_apdm = oci_fetch_array($sql_P);
		
		$sql_O = oci_parse($conn_sispa,"SELECT COUNT (KODJANTINA) AS BIL_O FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='$namakelas' AND KODJANTINA NOT IN('L','P') AND TRIM(KODTINGKATANTAHUN) = '$darjah' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA");
		oci_execute($sql_O);
		$bil_O_apdm = oci_fetch_array($sql_O);
		
		$sql_AI = oci_parse($conn_sispa,"SELECT COUNT (KODAGAMA) AS BIL_AI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='".oci_escape_string($row["NAMAKELAS"])."' AND KODAGAMA='01' AND TRIM(KODTINGKATANTAHUN) = '$darjah' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA");
		oci_execute($sql_AI);
		$bil_ai_apdm = oci_fetch_array($sql_AI);
		
		$sql_BI = oci_parse($conn_sispa,"SELECT COUNT (KODAGAMA) AS BIL_BI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND NAMAKELAS='".oci_escape_string($row["NAMAKELAS"])."' AND KODAGAMA<>'01' AND TRIM(KODTINGKATANTAHUN) = '$darjah' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA");
		oci_execute($sql_BI);
		$bil_bi_apdm = oci_fetch_array($sql_BI);
		
		$jum_pel_apdm = $bil_L_apdm['BIL_L'] + $bil_P_apdm['BIL_P'] + $bil_O_apdm['BIL_O'];
		
		echo "<tr>\n";
		echo "<td><center>$bil</center></td>\n";
		echo "<td><center>".strtoupper($darjah)."</a></center></td>\n";
		echo "<td><center><a href=papar_semak_pelajar_apdm.php?data=".$darjah."|".$kodsek."|".urlencode($namakelas2).">".strtoupper($namakelas2)."</a></center></td>\n";
		echo "<td><center>".$bil_L_apdm['BIL_L']."</center></td>\n";
		echo "<td><center>".$bil_P_apdm['BIL_P']."</center></td>\n";
		echo "<td><center>".$bil_O_apdm['BIL_O']."</center></td>\n";
		echo "<td><center>".$bil_ai_apdm['BIL_AI']."</center></td>\n";
		echo "<td><center>".$bil_bi_apdm['BIL_BI']."</center></td>\n";
		echo "<td><center>$jum_pel_apdm</center></td>\n";
		if($jum_pel_apdm=="")
			echo "<td bgcolor='$warna'><center>-</center></td>\n";
		else{
			//disabled 19/11/2012 boleh dibuka apabila awal tahun untuk import pelajar baru
			if($check_kemudahan_download_apdm==0){
				echo "<td bgcolor='$warna'><center><a href=\"migrate_tmuridsm_kelas.php?tahap=$darjah&namakelas=".urlencode($namakelas2)."\" onclick=\"return (confirm('Adakah anda pasti import data kelas $namakelas2 ?'))\">Import</a></center></td>\n";
			} else {
				echo "<td bgcolor='$warna'><center>Import</center></td>";
			}
			//echo "<td bgcolor='$warna'><center>-</center></td>\n";
		}
		$bil++;
	
	}
}


echo "<tr>";
echo "<td colspan=\"8\"><center>JUMLAH PELAJAR</center></td>";
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
echo "<br><br><center><a href=semak_pelajar_apdm.php><< Kembali</a></center>";
?>
</td>
<?php include 'kaki.php';?> 