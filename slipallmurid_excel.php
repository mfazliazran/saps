<?php
//session_start();
include 'auth.php';
include 'config.php';
//include 'kepala.php';
//include 'menu.php';

   header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="slipallmurid.xls"');
   echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>CETAK SEMUA SLIP</TITLE>";
   echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
   echo "<body>";
   
$m = $_GET['data'];
list ( $nokp, $kodsek, $ting, $kelas, $tahun, $jpep) =split('[|]', $m);
$jpep = $_SESSION["jpep"];
$kodsek= $_SESSION["kodsek"];
$tahun = $_SESSION["tahun"];
?>
<html>
<titel></title>
<head>
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

<?php


$gting=strtoupper($ting);
switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$level="SR";
			break;
		case "P": case "T1": case "T2": case "T3":
			$level="MR";
			break;
		case "T4": case "T5":
			$level="MA";
			break;
	}

if($level=="MA"){
	$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 
	$q_murid = "SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND $gting='$ting' AND kelas$gting='$kelas' AND tahun$gting='$tahun' ORDER BY namap"; 
	$stmt = oci_parse($conn_sispa,$q_murid);
	oci_execute($stmt);
	$bil_pel = count_row("SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND $gting='$ting' AND kelas$gting='$kelas' AND tahun$gting='$tahun' ORDER BY namap");
	//$bil_ting=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND ($kodsekolah)");
	
	$q_nting = "SELECT *  FROM tnilai_sma ma, tmurid tm WHERE ma.tahun='$tahun' AND ma.jpep='$jpep' AND ma.ting='$ting' AND ma.kodsek='$kodsek' AND ma.nokp=tm.nokp ORDER BY ma.keputusan Desc, ma.gpc Asc, ma.peratus Desc";

$qrt = oci_parse($conn_sispa,$q_nting);
oci_execute($qrt);
$bil_ting = count_row($q_nting);

	$bilpage = $bil_pel ;
	while ($row = oci_fetch_array($stmt))
	{
		$nokp = $row["NOKP"];
		$nama = $row["NAMAP"];
		$jantina = $row["JANTINA"];
		$ulasan = $row["ULASAN"];
		$hadir = $row["KEHADIRAN"];
		$hadirpenuh = $row["KEHADIRANPENUH"];
		
		
	
		/*$q_slip = mysql_query("SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jpep'");
		while($row = mysql_fetch_array($q_slip)){
			$nama = $row[nama]; $nokp = $row[nokp]; $ting = $row[ting]; $kelas = $row[kelas]; $jantina = $row[jantina];
		}*/
		$jan=array("L" => "LELAKI", "P" => "PEREMPUAN");   
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
		$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
		oci_execute($q_sek);
		$rowsek = oci_fetch_array($q_sek);
		$lencana=$rowsek["LENCANA"];
//		echo "<br><br>\n";
//		echo "<center><img src=\"../exam/images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
		//echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
		echo "<center><b>".$rowsek["NAMASEK"]."</b></center>";
		echo "<br>\n";
		echo "<table width=\"750\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
		echo "  <tr>\n";
		echo "    <td><div align=\"center\"><strong>SLIP KEPUTUSAN - ".jpep($jpep)." - $tahun</strong></div></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"750\"  border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td width=\"547\">Nama : $nama<br></td>\n";
		echo "    <td width=\"233\">Tingkatan : $ting $kelas</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>No. KP :  $nokp</td>\n";
		echo "    <td>Jantina : $jan[$jantina] </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><div align=\"center\"></div><div align=\"center\">\n";
		echo "      <hr align=\"center\" noshade>\n";
		echo "    </div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Bil</td>\n";
		echo "    <td>Mata Pelajaran </td>\n";
		echo "    <td><div align=\"center\">Markah</div></td>\n";
		echo "    <td><div align=\"center\">Gred</div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		$bil=0;
		$q_sub = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp"); 
		oci_execute($q_sub);
		while($rowsub = oci_fetch_array($q_sub)){
			$kodmp=$rowsub["KODMP"];
			$gmp="G$kodmp";
			$t_mark = "SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jpep' ";
			$stmt1 = oci_parse($conn_sispa,$t_mark);
			oci_execute($stmt1);
			$num=count_row("SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jpep' ");
			while($row_mark=oci_fetch_array($stmt1)){
				if($row_mark[$kodmp] != ''){
					$bil=$bil+1;
					$stmt2 = oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod='$kodmp'");
					oci_execute($stmt2);
					$t_mp = oci_fetch_array($stmt2);
					echo "    <td>$bil</td>\n";
					echo "    <td>".$t_mp["MP"]."</td>\n";
					echo "    <td><center>$row_mark[$kodmp]</center></td>\n";
					echo "    <td><center>$row_mark[$gmp]</center></td>\n";
				}
			}
			echo "  </tr>\n";
		}
		//echo "test";
		//$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsma WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jpep'");
		$q_nilai = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp='$nokp' AND ma.nokp='$nokp' AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep and mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'");

		oci_execute($q_nilai);
		$row_result=oci_fetch_array($q_nilai);
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td width=\"300\">Bilangan Mata Pelajaran Daftar </td>\n";
		echo "    <td width=\"750\">: ".$row_result["BILMP"]."</td>\n";
		echo "    <td width=\"250\">Jumlah Markah </td>\n";
		echo "    <td width=\"150\">: ".$row_result["JUMMARK"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Kedudukan Dalam Kelas </td>\n";
		echo "    <td>: ".$row_result["KDK"]." / $bil_pel</td>\n";
		echo "	<tr>\n";
		echo "	<td>Kedudukan Dalam Tingkatan </td>\n";
		echo "	<td>: ".$row_result["KDT"]." / $bil_ting</td>\n";
		echo "    <td>Peratus</td>\n";
		echo "    <td>: ".$row_result["PERATUS"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Kedatangan</td>\n";
		echo "    <td>: $kehadiran </td>\n";
		echo "    <td>Gred Purata Pelajar </td>\n";
		echo "    <td>: ".$row_result["GPC"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Pencapaian Gred Keseluruhan </td>\n";
		echo "    <td width=\"350\">: ".$row_result["PENCAPAIAN"]."</td>\n";
		echo "    <td>Keputusan</td>\n";
		echo "    <td>: ".$row_result["KEPUTUSAN"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		//echo "<br>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		
		$panggil = "SELECT ulasan FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND kelas='$kelas' AND jpep='$jpep'";
		
		$resx = oci_parse($conn_sispa,$panggil);
		oci_execute($resx);
		$ulasanx = oci_fetch_array($resx);
		$ulas = $ulasanx['ULASAN'];
		
		echo "  <tr>\n";
		echo "    <td>Ulasan Guru Kelas : <br />$ulas</td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br>";
		//echo "<br><br><br><br><p>\n";
		//echo "<br><br><br><br><p>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Kelas </div></td>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Pengetua </div></td>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Penjaga </div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
//		echo "    <td>&nbsp;</td>\n";
//		echo "    <td>&nbsp;</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		
		$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
		oci_execute($q_guru);
		$row_guru = oci_fetch_array($q_guru);
		
		$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
		//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
		oci_execute($qgb);
		$row_gb = oci_fetch_array($qgb);
		
		echo "    <td valign='top'><div align=\"center\">........................................<br>(".$row_guru["NAMA"]." )</div></td>\n";
		echo "    <td valign='top'><div align=\"center\">........................................<br> (".$row_gb["NAMA"]." )</div></td>\n";
		echo "    <td valign='top'><div align=\"center\">........................................</div></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br><br>";
		if ( $bilpage <> 1 ) 
		{ echo "<p style=\"page-break-before: always\">\n"; }
		$bilpage--;
		//for ($i=0; $i<13-$bil ; $i++){ echo "<br>\n"; }
	}
}
//////////////////////////////////////////////////////level mr /////////////////////////////////////////////////////////////////////////////////////////////////
if($level=="MR"){
	$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 
	$q_murid = "SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' ORDER BY namap";
	$stmt5 = oci_parse($conn_sispa,$q_murid);
	oci_execute($stmt5);
	$bil_pel = count_row("SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' ORDER BY namap");
	//$bil_ting=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND ($kodsekolah)");
	//echo "SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND ($kodsekolah)";
	//echo $bil_ting;
		
	$q_nting = "SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc";

//echo $q_nting;

$qrt = oci_parse($conn_sispa,$q_nting);
oci_execute($qrt);
$bil_ting = count_row($q_nting);

	//echo "bilpel:$bil_pel";
	$bilpage = $bil_pel ;
	while ($row = oci_fetch_array($stmt5))
	{
		$nokp = $row["NOKP"];
		$nama = $row["NAMAP"];
		$jantina = $row["JANTINA"];
		$hadir = $row["KEHADIRAN"];
		$hadirpenuh = $row["KEHADIRANPENUH"];

		$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jpep'");
		oci_execute($q_slip);
		$rowx = oci_fetch_array($q_slip);
		$nama = $rowx["NAMA"];
		$nokp = $rowx["NOKP"];
		$ting = $rowx["TING"];
		$kelas = $rowx["KELAS"];
		$jantina = $rowx["JANTINA"];
		$kehadiran = $rowx["KEHADIRAN"];
		$kehadiranpenuh = $rowx["KEHADIRANPENUH"];
		while($row = oci_fetch_array($q_slip)){
			$nama = $row["NAMA"]; $nokp = $row["NOKP"]; $ting = $row["TING"]; $kelas = $row["KELAS"]; $jantina = $row["JANTINA"];
		}
	
		$jan=array("L" => "LELAKI", "P" => "PEREMPUAN");
			   
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
		$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
		oci_execute($q_sek);
		$rowsek = oci_fetch_array($q_sek);
		$lencana=$rowsek["LENCANA"];
		//echo "<br><br>\n";
		//echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
		echo "<center><b>".$rowsek["NAMASEK"]."</b></center>";
		echo "<br>\n";
		echo "<table width=\"750\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
		echo "  <tr>\n";
		echo "    <td><div align=\"center\"><strong>SLIP KEPUTUSAN - ".jpep($jpep)." - $tahun</strong></div></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br><br>\n";
		echo "<table width=\"750\"  border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td width=\"547\">Nama : $nama<br></td>\n";
		echo "    <td width=\"233\">Tingkatan : $ting $kelas</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>No. KP :  $nokp</td>\n";
		echo "    <td>Jantina : $jan[$jantina] </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><div align=\"center\"></div><div align=\"center\">\n";
		echo "      <hr align=\"center\" noshade>\n";
		echo "    </div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Bil</td>\n";
		echo "    <td>Mata Pelajaran </td>\n";
		echo "    <td><div align=\"center\">Markah</div></td>\n";
		echo "    <td><div align=\"center\">Gred</div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		$bil=0;
		$q_sub = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp");
		oci_execute($q_sub);
		while($rowsub = oci_fetch_array($q_sub)){
			$kodmp=$rowsub["KODMP"];
			$gmp="G$kodmp";
			$t_mark = "SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jpep' ";
			$stmt6 = oci_parse($conn_sispa,$t_mark);
			oci_execute($stmt6);
			$num=count_row("SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jpep' ");
			while($row_mark=oci_fetch_array($stmt6)){
				if($row_mark[$kodmp] != ''){
					$bil=$bil+1;
					$stmt7 = oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod='$kodmp'");
					oci_execute($stmt7);
					$t_mp = oci_fetch_array($stmt7);
					echo "    <td>$bil</td>\n";
					echo "    <td>".$t_mp["MP"]."</td>\n";
					echo "    <td><center>$row_mark[$kodmp]</center></td>\n";
					echo "    <td><center>$row_mark[$gmp]</center></td>\n";
				}
				
				}
			echo "  </tr>\n";
			}

		//$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsmr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jpep'");
		//echo "SELECT * FROM penilaian_muridsmr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jpep'";
		$q_nilai = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp='$nokp' AND mr.nokp='$nokp' 
AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep 					
					AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'");
		oci_execute($q_nilai);
		$row_result=oci_fetch_array($q_nilai);
		
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td width=\"300\">Bilangan Mata Pelajaran Daftar </td>\n";
		echo "    <td width=\"750\">: ".$row_result["BILMP"]."</td>\n";
		echo "    <td width=\"250\">Jumlah Markah </td>\n";
		echo "    <td width=\"150\">: ".$row_result["JUMMARK"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Kedudukan Dalam Kelas </td>\n";
		echo "    <td>: ".$row_result["KDK"]." / $bil_pel</td>\n";
		echo "	<tr>\n";
		echo "	<td>Kedudukan Dalam Tingkatan </td>\n";
		echo "	<td>: ".$row_result["KDT"]." / $bil_ting</td>\n";
		echo "    <td>Peratus</td>\n";
		echo "    <td>: ".$row_result["PERATUS"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Kehadiran</td>\n";
		echo "    <td>: $kehadiran / $kehadiranpenuh Hari</td>\n";
		echo "    <td>Gred Purata Pelajar </td>\n";
		echo "    <td>: ".$row_result["GPC"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Pencapaian Gred Keseluruhan </td>\n";
		echo "    <td>: ".$row_result["PENCAPAIAN"]."</td>\n";
		echo "    <td>Keputusan</td>\n";
		echo "    <td>: ".$row_result["KEPUTUSAN"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		//echo "<br>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		
		$panggil = "SELECT ulasan FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND kelas='$kelas' AND jpep='$jpep'";
		
		$resx = oci_parse($conn_sispa,$panggil);
		oci_execute($resx);
		$ulasanx = oci_fetch_array($resx);
		$ulas = $ulasanx['ULASAN'];
		
		echo "  <tr>\n";
		echo "    <td>Ulasan Guru Kelas : <br />$ulas </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br>";
		//echo "<br><br><br><br><p>\n";
		//echo "<br><br><br><br><p>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Kelas </div></td>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Pengetua </div></td>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Penjaga </div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
//		echo "    <td>&nbsp;</td>\n";
//		echo "    <td>&nbsp;</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		
		$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
		oci_execute($q_guru);
		$row_guru = oci_fetch_array($q_guru);
		
		$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
		//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
		oci_execute($qgb);
		$row_gb = oci_fetch_array($qgb);
		
		
		
		echo "    <td valign='top'><div align=\"center\">........................................<br>(".$row_guru["NAMA"]." )</div></td>\n";
		echo "    <td valign='top'><div align=\"center\">........................................<br> (".$row_gb["NAMA"]." )</div></td>\n";
		echo "    <td valign='top'><div align=\"center\">........................................</div></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br><br>";
		if ( $bilpage <> 1 ) 
		{ echo "<p style=\"page-break-before: always\">\n"; }
		$bilpage--;
		//for ($i=0; $i<13-$bil ; $i++){ echo "<br>\n"; }
	}
}
//////////////////////////////////////////////////////level sr /////////////////////////////////////////////////////////////////////////////////////////////////
///Mula Update table penilaian_smr
if($level=="SR"){
	
	$kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	
	
	$gting=strtoupper($ting);
	$q_murid = "SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' ORDER BY namap";
	$stmt8 = oci_parse($conn_sispa,$q_murid);
	oci_execute($stmt8);
	$bil_pel=count_row("SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' ORDER BY namap");
	//$bil_darjah=count_row("SELECT * FROM tmuridsr WHERE tahun$gting='$tahun' AND $gting='$ting' AND ($kodsekolah)");
	$q_nting = "SELECT *  FROM tnilai_sr sr, tmuridsr tm WHERE sr.tahun='$tahun' AND sr.jpep='$jpep' AND sr.darjah='$ting' AND sr.kodsek='$kodsek' AND sr.nokp=tm.nokp ORDER BY sr.keputusan Desc, sr.gpc Asc, sr.peratus Desc";

$qry = oci_parse($conn_sispa,$q_nting);
oci_execute($qry);
$bil_darjah = count_row($q_nting);


	$bilpage = $bil_pel ;
	while ($row = oci_fetch_array($stmt8))
	{
		$nokp = $row["NOKP"];
		$nama = $row["NAMAP"];
		$jantina = $row["JANTINA"];

		$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jpep' ORDER BY darjah");
		oci_execute($q_slip);
		while($row = oci_fetch_array($q_slip)){
			$nama = $row["NAMA"]; $nokp = $row["NOKP"]; $ting = $row["DARJAH"]; $kelas = $row["KELAS"]; $jantina = $row["JANTINA"];$kehadiran=$row["KEHADIRAN"]; $kehadiranpenuh=$row["KEHADIRANPENUH"];
		}
		
		$jan=array("L" => "LELAKI", "P" => "PEREMPUAN");
		//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
		$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
		oci_execute($q_sek);
		$rowsek = oci_fetch_array($q_sek);
		$lencana=$rowsek["LENCANA"];
		//echo $jpep;
		//echo "$lencana\n";
		//echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
		echo "<center><b>".$rowsek["NAMASEK"]."</b></center>";
		echo "<br>\n";
		echo "<table width=\"750\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
		echo "  <tr>\n";
		echo "    <td><div align=\"center\"><strong>SLIP KEPUTUSAN - ".jpep($jpep)." - $tahun</strong></div></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br><br>\n";
		echo "<table width=\"750\"  border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td width=\"547\">Nama : $nama<br></td>\n";
		echo "    <td width=\"233\">Tahun : $ting $kelas</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>No. KP :  $nokp</td>\n";
		echo "    <td>Jantina : $jan[$jantina] </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><div align=\"center\"></div><div align=\"center\">\n";
		echo "      <hr align=\"center\" noshade>\n";
		echo "    </div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Bil</td>\n";
		echo "    <td>Mata Pelajaran </td>\n";
		echo "    <td><div align=\"center\">Markah</div></td>\n";
		echo "    <td><div align=\"center\">Gred</div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";

		$bil=0;
		$q_sub = oci_parse($conn_sispa,"SELECT distinct KODMP FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp");
		oci_execute($q_sub);
		while($rowsub = oci_fetch_array($q_sub)){
			$kodmp=$rowsub["KODMP"];
			$gmp="G$kodmp";

			$t_mark = "SELECT $kodmp, $gmp , KEHADIRAN FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jpep' ";
			$stmtw = oci_parse($conn_sispa,$t_mark);
			oci_execute($stmtw);
			//$num=count_row("SELECT $kodmp, $gmp FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jpep' ");
			while($row_mark=oci_fetch_array($stmtw)){
				if($row_mark[$kodmp] != ''){
					$bil=$bil+1;
					$stmte = oci_parse($conn_sispa,"SELECT * FROM mpsr WHERE kod='$kodmp'");
					oci_execute($stmte);
					$t_mp = oci_fetch_array($stmte);
					echo "    <td>$bil</td>\n";
					echo "    <td>".$t_mp["MP"]."</td>\n";
					echo "    <td><center>$row_mark[$kodmp]</center></td>\n";
					echo "    <td><center>$row_mark[$gmp]</center></td>\n";
				}			
			}
			echo "  </tr>\n";
		}
	
		//$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jpep'");
		$q_nilai = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.nokp='$nokp' AND 
 mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek
and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep and
					sr.nokp='$nokp' AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.darjah='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'");
		oci_execute($q_nilai);
		$row_result=oci_fetch_array($q_nilai);
		echo "</table>\n";
		echo "<br>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td width=\"300\">Bilangan Mata Pelajaran Daftar </td>\n";
		echo "    <td width=\"750\">: ".$row_result["BILMP"]."</td>\n";
		echo "    <td width=\"250\">Jumlah Markah </td>\n";
		echo "    <td width=\"150\">: ".$row_result["JUMMARK"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Kedudukan Dalam Kelas </td>\n";
		echo "    <td>: ".$row_result["KDK"]." / $bil_pel</td>\n";
		echo "	<tr>\n";
		echo "	<td>Kedudukan Dalam Darjah </td>\n";
		echo "	<td>: ".$row_result["KDT"]." / $bil_darjah</td>\n";
		echo "    <td>Peratus</td>\n";
		echo "    <td>: ".$row_result["PERATUS"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Kedatangan</td>\n";
		echo "    <td>: $kehadiran / $kehadiranpenuh Hari</td>\n";
		echo "    <td>Gred Purata Pelajar </td>\n";
		echo "    <td>: ".$row_result["GPC"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>Pencapaian Gred Keseluruhan </td>\n";
		echo "    <td>: ".$row_result["PENCAPAIAN"]."</td>\n";
		echo "    <td>Keputusan</td>\n";
		echo "    <td>: ".$row_result["KEPUTUSAN"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		//echo "<br>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		

if($level == "SR"){
	$panggil = "SELECT ulasan,kehadiran,kehadiranpenuh FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jpep'";
} else {
	$panggil = "SELECT ulasan,kehadiran,kehadiranpenuh FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND kelas='$kelas' AND jpep='$jpep'";
	}
	
	//echo $panggil;
	$resx = oci_parse($conn_sispa,$panggil);
	oci_execute($resx);
	$ulasanx = oci_fetch_array($resx);
	$ulas = $ulasanx['ULASAN'];
	$hadir = $ulasanx["KEHADIRAN"];
	$hadirpenuh = $ulasanx["KEHADIRANPENUH"];

		
		$resx = oci_parse($conn_sispa,$panggil);
		oci_execute($resx);
		$ulasanx = oci_fetch_array($resx);
		$ulas = $ulasanx['ULASAN'];
		$hadir = $ulasanx["KEHADIRAN"];
		$hadirpenuh = $ulasanx["KEHADIRANPENUH"];
		
		echo "  <tr>\n";
		echo "    <td>Ulasan Guru Kelas : <br />$ulas  </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br>";
		//echo "<br><br><br><br><p>\n";
		//echo "<br><br><br><br><p>\n";
		echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Kelas </div></td>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Besar </div></td>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Penjaga </div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
//		echo "    <td>&nbsp;</td>\n";
//		echo "    <td>&nbsp;</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		
		$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
		oci_execute($q_guru);
		$row_guru = oci_fetch_array($q_guru);
		
		$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
		//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
		oci_execute($qgb);
		$row_gb = oci_fetch_array($qgb);
		
		echo "    <td valign='top'><div align=\"center\">........................................<br>(".$row_guru["NAMA"]." )</div></td>\n";
		echo "    <td valign='top'><div align=\"center\">........................................<br>(".$row_gb["NAMA"]." )</div></td>\n";
		echo "    <td valign='top'><div align=\"center\">........................................</div></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br><br>";
		if ( $bilpage <> 1 ) 
		{ echo "<p style=\"page-break-before: always\">\n"; }
		$bilpage--;
		//for ($i=0; $i<13-$bil ; $i++){ echo "<br>\n"; }
	}
}

///////////////////////////////////////////////////////end level//////////////////////////////////////////////////////////////////
function jpep($kodpep){
switch ($kodpep){
	case "U1":
	$npep="UJIAN 1";
	break;
	case "U2":
	$npep="UJIAN 2";
	break;
	case "PAT":
	$npep="PEPERIKSAAN AKHIR TAHUN";
	break;
	case "PPT":
	$npep="PEPERIKSAAN PERTENGAHAN TAHUN";
	break;
	case "PMRC":
	$npep="PEPERIKSAAN PERCUBAAN PMR";
	break;
	case "SPMC":
	$npep="PEPERIKSAAN PERCUBAAN SPM";
	break;
}
return $npep;
}
?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>