<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
// $check_kemudahan_download_apdm = download_apdm();


set_time_limit(0);
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Murid SAPS dan APDM</p>

<?php
if($_SESSION["tahun"]<>date("Y"))
	die('Utiliti import data APDM hanya untuk tahun semasa sahaja.');
	
//if(date('Y')=='2017')
	//die("KEMUDAHAN INI DIBERHENTIKAN BUAT SEMENTARA WAKTU.");
	
$m=$_GET['data'];
list ($ting, $kodsek, $kelas)=split('[|]', $m);
//list ($kelas, $ting, $kodsek)=split('[|]', $m);
//$kelas = htmlentities($kelas);

echo "<b>Senarai Pelajar bagi $ting $kelas</b>";	
echo "<br><br>";
echo"<table border=\"1\" align=\"center\" width='100%'>";
echo"<tr><td align='center' colspan='2'><h3>ENROLMEN MURID</h3></td></tr>";
echo"<tr><td valign='top'>";
if ($_SESSION['statussek']=="SM"){
	$kodseksm = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";
	$m=$_GET['data'];
	list ($ting, $kodsek, $kelas)=split('[|]', $m);
	$kelas = htmlentities($kelas);
	//$kelas=str_replace('\'','\'',$kelas);//latest nama kelas
	$kelas=str_replace('\'','*',$kelas);//latest nama kelas
	//$kelas=str_replace('\'','`',$kelas);
	$tkt=array("p" => "PERALIHAN",
			   "t1" => "TINGKATAN SATU",
			   "t2" => "TINGKATAN DUA",
			   "t3" => "TINGKATAN TIGA",
			   "t4" => "TINGKATAN EMPAT",
			   "t5" => "TINGKATAN LIMA");
	
	echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<center>SENARAI NAMA MURID SAPS</center>";
	echo "<br>";
	echo "<tr>\n";
	echo "<th scope=\"col\">BIL</th>\n";
	echo "<th scope=\"col\">NOKP /<br> NO. SIJIL LAHIR</th>\n";
	echo "<th scope=\"col\">NAMA</th>\n";
	echo "<th scope=\"col\">JANTINA</th>\n";
	echo "<th scope=\"col\">AGAMA</th>\n";
	echo "<th scope=\"col\">TINDAKAN</th>\n";
	echo "</tr>\n";
//$namkelas = str_replace(' ','_',$namkelas);
	$query = "SELECT nokp,namap,jantina,agama FROM tmurid WHERE kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='".str_replace(' ','_',oci_escape_string($kelas))."' AND tahun$ting='".$_SESSION['tahun']."' ORDER BY namap"; 
	//if($kodsek='TEA5035')
		//echo $query;
	//echo $query;
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$bil=0;
	while ($row = oci_fetch_array($result))
	{
		$nokp = $row['NOKP'];
		$nama = $row['NAMAP'];
		$jantina = $row['JANTINA'];
		$agama = $row['AGAMA'];
		if($agama=='01')
			$namaagama="ISLAM";
		else
			$namaagama="BUKAN ISLAM";
		$bil=$bil+1;
			
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$nokp<br>&nbsp;</td>\n";
		echo "    <td>$nama</td>\n";
		echo "    <td><center>$jantina</center></td>\n";
		echo "    <td><center>$namaagama</center></td>\n";
		echo "    <td><center><a href='kemaskini_pelajar_saps.php?nokp=$nokp&tahap=$ting&kelas=".str_replace(' ','_',$kelas)."' onclick=\"return (confirm('Adakah anda pasti hapus $nama ?'))\"><img src='images/btn_delete.gif'></a></center></td>\n";		
	}
echo "</th>\n";
echo "</tr>\n";
echo "</table>";
}

if ($_SESSION['statussek']=="SR"){
	$kodseksr = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	$m=$_GET['data'];
	list ($darjah, $kodsek, $kelas)=split('[|]', $m);
	$kelas2 = oci_escape_string($kelas);
	$kelas = htmlentities($kelas);
	$kelas2=str_replace('\'','*',$kelas);//latest nama kelas
	$tkt=array("d1" => "DARJAH SATU",
			   "d2" => "DARJAH DUA",
			   "d3" => "DARJAH TIGA",
			   "d4" => "DARJAH EMPAT",
			   "d5" => "DARJAH LIMA",
			   "d6" => "DARJAH ENAM");
	
	echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<center>SENARAI NAMA PELAJAR SAPS</center>";
	echo "<br>";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">NOKP /<br> NO. SIJIL LAHIR</th>\n";
	echo "    <th scope=\"col\">NAMA</th>\n";
	echo "    <th scope=\"col\">JANTINA</th>\n";
	echo "<th scope=\"col\">AGAMA</th>\n";
	echo "    <th scope=\"col\">TINDAKAN</th>\n";
	echo "  </tr>\n";
	//echo "$kodsek";
	
	//$query = "SELECT * FROM tmuridsr WHERE ($kodseksr) AND $darjah='$darjah' AND kelas$darjah='".str_replace(' ','_',$kelas)."' AND tahun$darjah='".$_SESSION['tahun']."' ORDER BY namap"; 
	$query = "SELECT * FROM tmuridsr WHERE kodsek$darjah='$kodsek' and $darjah='$darjah' AND kelas$darjah='".str_replace(' ','_',$kelas2)."' AND tahun$darjah='".$_SESSION['tahun']."' ORDER BY namap";
	//if($kodsek=='ABA7002')
		//echo $query;
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$bil=0;
	while ($row = oci_fetch_array($result))
	{
		$nokp = $row['NOKP'];
		$nama = $row['NAMAP'];
		$jantina = $row['JANTINA'];
		$agama = $row['AGAMA'];
		if($agama=='01')
			$namaagama="ISLAM";
		else
			$namaagama="BUKAN ISLAM";
		$bil=$bil+1;
			
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$nokp<br>&nbsp;</td>\n";
		echo "    <td>$nama</td>\n";
		echo "    <td><center>$jantina</center></td>\n";
		echo "    <td><center>$namaagama</center></td>\n";
		echo "    <td><center><a href='kemaskini_pelajar_saps.php?nokp=$nokp&tahap=$darjah&kelas=".str_replace(' ','_',$kelas)."' onclick=\"return (confirm('Adakah anda pasti hapus $nama ?'))\"><img src='images/btn_delete.gif'></a></center></td>\n";		
		$darjahsblm="";
	}
echo "</th>\n";
echo "</tr>\n";
echo "</table>";//SAPS
}
echo"</td><td valign='top'>";
##########################################################DATA APDM#######################################
if ($_SESSION['statussek']=="SM"){
	$kodseksm = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";
	$m=$_GET['data'];
	list ($ting, $kodsek,$kelas)=split('[|]', $m);
	$kelas2 = oci_escape_string($kelas);
	$kelas = htmlentities($kelas);
	$tingkkk = $ting;
	$tkt=array("p" => "PERALIHAN",
			   "t1" => "TINGKATAN SATU",
			   "t2" => "TINGKATAN DUA",
			   "t3" => "TINGKATAN TIGA",
			   "t4" => "TINGKATAN EMPAT",
			   "t5" => "TINGKATAN LIMA");
	
	echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<center>SENARAI NAMA MURID APDM</center>";
	echo "<br>";
	echo "<tr>\n";
	echo "<th scope=\"col\">BIL</th>\n";
	echo "<th scope=\"col\">NOKP /<br> NO. SIJIL LAHIR</th>\n";
	echo "<th scope=\"col\">NAMA</th>\n";
	echo "<th scope=\"col\">JANTINA</th>\n";
	echo "<th scope=\"col\">AGAMA</th>\n";
	echo "<th scope=\"col\">IMPORT</th>\n";
	echo "</tr>\n";

	$query = "SELECT IDMURID,NAMA,NOKP,NOSIJILLAHIR,NOPASPORT,MURIDTIADADOKUMEN,KODKAUM,KODAGAMA,KODJANTINA,KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH,IDKODJENISSEKOLAH,IDKODPPD,IDKODNEGERI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND TRIM(KODTINGKATANTAHUN)='$ting' and NAMAKELAS='".str_replace('+',' ',$kelas2)."' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA"; 
	//if($kodsek=='AEA1109')
		//echo $query;
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$bil=0;
	while ($row = oci_fetch_array($result))
	{
		$nokp = $row['NOKP'];
		//$nosijil = str_replace(" ","",strtoupper($row["NOSIJILLAHIR"]));
		$nosijil = strtoupper($row["NOSIJILLAHIR"]);
		$nopasport = strtoupper($row["NOPASPORT"]);
		$muridtiadadokumen = strtoupper($row["MURIDTIADADOKUMEN"]);
		$idmurid = strtoupper($row["IDMURID"]);
		
		$nama = $row['NAMA'];
		$jantina = $row['KODJANTINA'];
		$agama = $row['KODAGAMA'];
		if($agama=='01')
			$namaagama="ISLAM";
		else
			$namaagama="BUKAN ISLAM";
		$bil=$bil+1;
		if($nokp==""){
			if($idmurid<>"")
				$nokputama = $idmurid."<br>&nbsp;";
			if($muridtiadadokumen<>"")
				$nokputama = $muridtiadadokumen."<br>&nbsp;";
			if($nopasport<>"")
				$nokputama = $nopasport."<br>&nbsp;";
			if($nosijil<>"")
				$nokputama = $nosijil."<br>&nbsp;";
		}else{
			$nokputama = "$nokp<br>&nbsp;";
		}
		/*if($nokp=="" and $nosijil<>"")
			$nokputama = "$nosijil<br>&nbsp;";
		elseif($nokp<>"" and $nosijil<>"")
			$nokputama = "$nokp<br>$nosijil";
		else
			$nokputama = "$nokp<br>&nbsp;";
			*/
		/*if($nokp=="")
			$nokputama = $nosijil;
		else
			$nokputama = $nokp;*/
		echo "<tr>\n";
		echo "<td><center>$bil</center></td>\n";
		echo "<td>$nokputama</a></td>\n";
		echo "<td>$nama</a></td>\n";
		echo "<td><center>$jantina</center></td>\n";
		echo "<td><center>$namaagama</center></td>\n";
		echo "<td><center><a href=\"migrate_tmuridsm_pelajar2.php?nokp=$nokp&sijil=$nosijil&pasport=$nopasport&tiadadoc=".urlencode($muridtiadadokumen)."&idmurid=$idmurid&ting=$ting&kelas=".urlencode($kelas)."\" onclick=\"return (confirm('Adakah anda pasti import data $nama ?'))\">Import</a></center></td>\n";
		echo "</tr>\n";
	}
//echo "</th>\n";
//echo "</tr>\n";
echo "</table>";
}//SM APDM

if ($_SESSION['statussek']=="SR"){
	$kodseksr = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	$m=$_GET['data'];
	list ($darjah, $kodsek, $kelass)=split('[|]', $m);
	$kelassr = htmlentities($kelass);
	$kelas2 = oci_escape_string($kelass);
	$tingkkk = $darjah;
	//if($kodsek='ACT3002')
		//echo str_replace("\'","_",$kelass) . "<br>";
	$tkt=array("d1" => "DARJAH SATU",
			   "d2" => "DARJAH DUA",
			   "d3" => "DARJAH TIGA",
			   "d4" => "DARJAH EMPAT",
			   "d5" => "DARJAH LIMA",
			   "d6" => "DARJAH ENAM");
	
	echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "<center>SENARAI NAMA PELAJAR APDM</center>";
	echo "<br>";
	echo "<tr>\n";
	echo "<th scope=\"col\">BIL</th>\n";
	echo "<th scope=\"col\">NOKP /<br> NO. SIJIL LAHIR</th>\n";
	echo "<th scope=\"col\">NAMA</th>\n";
	echo "<th scope=\"col\">JANTINA</th>\n";
	echo "<th scope=\"col\">AGAMA</th>\n";
	echo "<th scope=\"col\">IMPORT</th>\n";
	echo "</tr>\n";
	//echo "$kodsek";
	
	$query = "SELECT IDMURID,NAMA,NOKP,NOSIJILLAHIR,NOPASPORT,MURIDTIADADOKUMEN,KODKAUM,KODAGAMA,KODJANTINA,KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH,IDKODJENISSEKOLAH,IDKODPPD,IDKODNEGERI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND TRIM(KODTINGKATANTAHUN)='$darjah' and NAMAKELAS='".str_replace('+',' ',$kelas2)."' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA"; 
	//echo $query."<br>";
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	$bil=0;
	while ($row = oci_fetch_array($result))
	{
		$nokp = $row['NOKP'];
		//$nosijil = trim(str_replace(" ","",strtoupper($row["NOSIJILLAHIR"])));
		$nosijil = $row["NOSIJILLAHIR"];
		$nopasport = strtoupper($row["NOPASPORT"]);
		$muridtiadadokumen = strtoupper($row["MURIDTIADADOKUMEN"]);
		$idmurid = strtoupper($row["IDMURID"]);
		
		$nama = $row['NAMA'];
		$jantina = $row['KODJANTINA'];
		$agama = $row['KODAGAMA'];
		if($agama=='01')
			$namaagama="ISLAM";
		else
			$namaagama="BUKAN ISLAM";
		$bil=$bil+1;
		/*if($nokp=="" and $nosijil<>"")
			$nokputama = "$nosijil<br>&nbsp;";
		elseif($nokp<>"" and $nosijil<>"")
			$nokputama = "$nokp<br>$nosijil";
		else
			$nokputama = "$nokp<br>&nbsp;";
			*/
		if($nokp==""){
			if($idmurid<>"")
				$nokputama = $idmurid."<br>&nbsp;";
			if($muridtiadadokumen<>"")
				$nokputama = $muridtiadadokumen."<br>&nbsp;";
			if($nopasport<>"")
				$nokputama = $nopasport."<br>&nbsp;";
			if($nosijil<>"")
				$nokputama = $nosijil."<br>&nbsp;";
		}else{
			$nokputama = "$nokp<br>&nbsp;";
		}
			
		echo "<tr>\n";
		echo "<td><center>$bil</center></td>\n";
		echo "<td>$nokputama</td>\n";
		echo "<td>$nama</td>\n";
		echo "<td><center>$jantina</center></td>\n";
		echo "<td><center>$namaagama</center></td>\n";
		
		echo "<td><center><a href=\"migrate_tmuridsm_pelajar2.php?nokp=$nokp&sijil=$nosijil&pasport=$nopasport&tiadadoc=".urlencode($muridtiadadokumen)."&idmurid=$idmurid&ting=$ting&kelas=".urlencode($kelassr)."\" onclick=\"return (confirm('Adakah anda pasti import data $nama ?'))\">Import</a></center></td>\n";		
		
	}
echo "</th>\n";
echo "</tr>\n";
echo "</table>";
}//if SR APDM
echo "</td></tr></table>";//main
echo "<br><br><center><a href=papar_kelas_pelajar_apdm.php?data=".$tingkkk."/".$kodsek."><< Kembali</a></center>";
?>
</td>
<?php include 'kaki.php';?> 