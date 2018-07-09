<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Ulasan Guru Kelas</p>

<?php
function location($locate) {
 ?>
 <script language="JavaScript">
  var temp = "<?php print($locate)?>";
  window.location=temp;
 </script>
 <?php
}

$c = $_GET['data'];
list ($nokp, $kodsek, $ting, $kelas, $tahun, $jspep) =split('[|]', $c);

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

if($_POST['hantar']){
	$ulasan_guru = oci_escape_string($_POST['ulasan']);
	$kehadiran = oci_escape_string($_POST['hadir']);
	$kehadiranpenuh = oci_escape_string($_POST['hadirpenuh']);
	
	// cek status sama ada sekolah rendah menengah
	// kalau sekolah rendah
	if($level == "SR"){
	$squery = "UPDATE markah_pelajarsr SET ulasan='$ulasan_guru',kehadiran='$kehadiran',kehadiranpenuh='$kehadiranpenuh' WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep' ";
	} else {
	$squery = "UPDATE markah_pelajar SET ulasan='$ulasan_guru',kehadiran='$kehadiran',kehadiranpenuh='$kehadiranpenuh' WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND kelas='$kelas' AND jpep='$jspep' ";
	}
	
	//die($squery);
	$result = oci_parse($conn_sispa,$squery);
	oci_execute($result);
	//location("slip-nama.php");
	/*if($_SESSION["level"]=='4' or $_SESSION["level"]=='3'){
	if($ting=='D3' or $ting=='D4' or $ting=='D5' or $ting=='D6')
		location("slip_namadminsr.php?ting=$ting&kelas=".urlencode($kelas)."");
	if($ting=='P' or $ting=='T2' or $ting=='T3')
		location("slip_namadminmr.php?ting=$ting&kelas=".urlencode($kelas)."");
	if($ting=='T4' or $ting=='T5')
		location("slip_namadminma.php?ting=$ting&kelas=".urlencode($kelas)."");
	}else{*/
		location("slip-nama.php");
	//}
	//pageredirect("edit_ulasan.php");
		
}

//////////////////////////////////////////////////////level ma /////////////////////////////////////////////////////////////////////////////////////////////////
///Mula Update table penilaian_smr
if($level=="MA"){
$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 

$gting=strtoupper($ting);
//$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' AND kodsek$gting='$kodsek' and kodsek_semasa='$kodsek'");// ($kodsekolah)");
//oci_execute($q_murid);
$bil_pel=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' AND kodsek$ting='$kodsek' and kodsek_semasa='$kodsek'");//($kodsekolah)");
$bil_ting=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kodsek$gting='$kodsek' and kodsek_semasa='$kodsek'");//($kodsekolah)");
$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY ting");
oci_execute($q_slip);
while($row = oci_fetch_array($q_slip)){
$nama = $row["NAMA"];
$nokp = $row["NOKP"];
$ting = $row["TING"];
$kelas = $row["KELAS"];
$jantina = $row["JANTINA"];
$ulasan = $row["ULASAN"];
$kehadiran = $row["KEHADIRAN"];
$kehadiranpenuh = $row["KEHADIRANPENUH"];
}
$jan=array("L" => "LELAKI",
		   "P" => "PEREMPUAN");
?>
<?php
	   
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
//$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
//oci_execute($q_sek);
//$rowsek = oci_fetch_array($q_sek);
//$lencana=$rowsek["LENCANA"];
/*
echo "<br><br>\n";
echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
echo "<center><b>".$rowsek["NAMASEK"]."</b></center>";
echo "<br>\n";
*/
//echo "<br>";
echo "<table width=\"70%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\"><strong>ULASAN GURU KELAS - ".jpep($jspep)." - $tahun</strong></div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br>\n";
echo "<table width=\"70%\"  border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td width=\"547\">Nama : $nama<br></td>\n";
echo "    <td width=\"233\">Tingkatan : $ting $kelas</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>No. KP :  $nokp</td>\n";
echo "    <td>Jantina : $jan[$jantina] </td>\n";
echo "  </tr>\n";
echo "</table>\n";
//echo "<br>\n";
echo "<table width=\"70%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
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
	$t_mark = oci_parse($conn_sispa,"SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ");
	oci_execute($t_mark);
	$num=count_row("SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ");
	while($row_mark=oci_fetch_array($t_mark)){
		if($row_mark[$kodmp] != ''){
			$bil=$bil+1;
			//echo "testing<br>";
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

$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsma WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY ting");//penilaian_muridsma
oci_execute($q_nilai);
$row_result=oci_fetch_array($q_nilai);

$q_nilai2 = oci_parse($conn_sispa,"SELECT KDT FROM tnilai_sma mr WHERE mr.nokp='$nokp' AND mr.tahun='$tahun' AND mr.kodsek='$kodsek' AND mr.ting='$ting' AND mr.kelas='$kelas' AND mr.jpep='$jspep'");
oci_execute($q_nilai2);
$row_result2=oci_fetch_array($q_nilai2);
$kdt = $row_result2["KDT"];

echo "</table>\n";
//echo "<br>\n";
echo "<table width=\"70%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"209\">Bilangan Mata Pelajaran Daftar </td>\n";
echo "    <td width=\"178\">: ".$row_result["BILMP"]."</td>\n";
echo "    <td width=\"161\">Jumlah Markah </td>\n";
echo "    <td width=\"228\">: ".$row_result["JUMMARK"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kedudukan Dalam Kelas </td>\n";
echo "    <td>: ".$row_result["KDK"]." / $bil_pel</td>\n";
echo "	<tr>\n";
echo "	<td>Kedudukan Dalam Tingkatan </td>\n";
echo "	<td>: $kdt / $bil_ting</td>\n";
echo "    <td>Peratus</td>\n";
echo "    <td>: ".$row_result["PERATUS"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kedatangan</td>\n";
echo "    <td>: $kehadiran/ $kehadiranpenuh  Hari</td>\n";
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
echo "<br>\n";
echo "<table width=\"70%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<table width=\"70%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "  </tr>\n";
echo "  <tr>\n";
}
//////////////////////////////////////////////////////level mr /////////////////////////////////////////////////////////////////////////////////////////////////
///Mula Update table penilaian_smr
if($level=="MR"){
$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 

$gting=strtoupper($ting);
//$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' AND kodsek$gting='$kodsek' and kodsek_semasa='$kodsek'");//($kodsekolah)");
//oci_execute($q_murid);
$bil_pel=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' AND kodsek$gting='$kodsek' and kodsek_semasa='$kodsek'");//($kodsekolah)");
$bil_ting=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kodsek$gting='$kodsek' and kodsek_semasa='$kodsek'");//($kodsekolah)");

$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY ting");
oci_execute($q_slip);
while($row = oci_fetch_array($q_slip)){
$nama = $row["NAMA"];
$nokp = $row["NOKP"];
$ting = $row["TING"];
$kelas = $row["KELAS"];
$jantina = $row["JANTINA"];
$kehadiran = $row["KEHADIRAN"];
$kehadiranpenuh = $row["KEHADIRANPENUH"];
}

$jan=array("L" => "LELAKI",
		   "P" => "PEREMPUAN");
	   
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
//$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
//oci_execute($q_sek);
//$rowsek = oci_fetch_array($q_sek);
//$lencana=$rowsek["LENCANA"];
//echo "<br><br>\n";
//echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
//echo "<center><b>".$rowsek["NAMASEK"]."</b></center>";
//echo "<br>\n";
echo "<table width=\"750\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\"><strong>ULASAN GURU KELAS - ".jpep($jspep)." - $tahun</strong></div></td>\n";
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
//echo "<br>\n";
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
	$t_mark = oci_parse($conn_sispa,"SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ");
	oci_execute($t_mark);
	//$num=count_row("SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ");
	while($row_mark=oci_fetch_array($t_mark)){
		if($row_mark[$kodmp] != ''){
			$bil=$bil+1;
			$stmt=oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod='$kodmp'");
			oci_execute($stmt);
			$t_mp = oci_fetch_array($stmt);
			//echo "SELECT * FROM mpsmkc WHERE kod='$kodmp'";
			
			echo "    <td>$bil</td>\n";
			echo "    <td>".$t_mp["MP"]."</td>\n";
			echo "    <td><center>$row_mark[$kodmp]</center></td>\n";
			echo "    <td><center>$row_mark[$gmp]</center></td>\n";
		}
		
		}
	echo "  </tr>\n";
	}


$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsmr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY ting");
oci_execute($q_nilai);
$row_result=oci_fetch_array($q_nilai);

$q_nilai2 = oci_parse($conn_sispa,"SELECT KDT FROM tnilai_smr mr WHERE mr.nokp='$nokp' AND mr.tahun='$tahun' AND mr.kodsek='$kodsek' AND mr.ting='$ting' AND mr.kelas='$kelas' AND mr.jpep='$jspep'");
oci_execute($q_nilai2);
$row_result2=oci_fetch_array($q_nilai2);
$kdt = $row_result2["KDT"];

echo "</table>\n";
//echo "<br>\n";
echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"209\">Bilangan Mata Pelajaran Daftar </td>\n";
echo "    <td width=\"178\">: ".$row_result["BILMP"]."</td>\n";
echo "    <td width=\"161\">Jumlah Markah </td>\n";
echo "    <td width=\"228\">: ".$row_result["JUMMARK"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kedudukan Dalam Kelas </td>\n";
echo "    <td>: ".$row_result["KDK"]." / $bil_pel</td>\n";
echo "	<tr>\n";
echo "	<td>Kedudukan Dalam Tingkatan </td>\n";
echo "	<td>:  $kdt / $bil_ting</td>\n";
echo "    <td>Peratus</td>\n";
echo "    <td>: ".$row_result["PERATUS"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kedatangan</td>\n";
echo "    <td>: $kehadiran / $kehadiranpenuh Hari </td>\n";
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
echo "  <tr>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "  </tr>\n";
echo "  <tr>\n";
}
//////////////////////////////////////////////////////level sr /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///SLIP UNTUK SEKOLAH RENDAH
if($level=="SR"){
$kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";

$gting=strtoupper($ting);
//$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmuridsr WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' AND kodsek$gting='$kodsek' and kodsek_semasa='$kodsek'");//($kodsekolah) ");
//oci_execute($q_murid);

$bil_pel=count_row("SELECT * FROM tmuridsr WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' AND kodsek$gting='$kodsek' and kodsek_semasa='$kodsek'");//($kodsekolah) ");

$bil_darjah=count_row("SELECT * FROM tmuridsr WHERE tahun$gting='$tahun' AND $gting='$ting' AND kodsek$gting='$kodsek' and kodsek_semasa='$kodsek'");

$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY darjah");
oci_execute($q_slip);
while($row = oci_fetch_array($q_slip)){
$nama = $row["NAMA"];
$nokp = $row["NOKP"];
$ting = $row["DARJAH"];
$kelas = $row["KELAS"];
$jantina = $row["JANTINA"];
$kehadiran = $row["KEHADIRAN"];
$kehadiranpenuh = $row["KEHADIRANPENUH"];
}

$jan=array("L" => "LELAKI",
		   "P" => "PEREMPUAN");
?>
<?php
	   
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
//$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
//oci_execute($q_sek);
//$rowsek = oci_fetch_array($q_sek);
//echo "<br><br>\n";
//echo "<center><b>".$rowsek["NAMASEK"]."</b></center>";
//echo "<br>\n";
echo "<table width=\"750\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\"><strong>ULASAN GURU KELAS - ".jpep($jspep)." - $tahun</strong></div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br>\n";
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
//echo "<br>\n";
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
	$t_mark = oci_parse($conn_sispa,"SELECT $kodmp, $gmp FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep' ");
	oci_execute($t_mark);
	$num=count_row("SELECT $kodmp, $gmp FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep' ");
	while($row_mark=oci_fetch_array($t_mark)){
		if($row_mark["$kodmp"] != ''){
			$bil=$bil+1;
			$stm=oci_parse($conn_sispa,"SELECT * FROM mpsr WHERE kod='$kodmp'");
			oci_execute($stm);
			$t_mp = oci_fetch_array($stm);
			
			echo "    <td>$bil</td>\n";
			echo "    <td>".$t_mp["MP"]."</td>\n";
			echo "    <td><center>$row_mark[$kodmp]</center></td>\n";
			echo "    <td><center>$row_mark[$gmp]</center></td>\n";
		}
		
		}
	echo "  </tr>\n";
	}


$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY darjah");
oci_execute($q_nilai);
$row_result=oci_fetch_array($q_nilai);

$q_nilai2 = oci_parse($conn_sispa,"SELECT KDT FROM tnilai_sr mr WHERE mr.nokp='$nokp' AND mr.tahun='$tahun' AND mr.kodsek='$kodsek' AND mr.darjah='$ting' AND mr.kelas='$kelas' AND mr.jpep='$jspep'");
oci_execute($q_nilai2);
$row_result2=oci_fetch_array($q_nilai2);
$kdt = $row_result2["KDT"];

echo "</table>\n";
//echo "<br>\n";
echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"209\">Bilangan Mata Pelajaran Daftar </td>\n";
echo "    <td width=\"178\">: ". $row_result["BILMP"]."</td>\n";
echo "    <td width=\"161\">Jumlah Markah </td>\n";
echo "    <td width=\"228\">: ".$row_result["JUMMARK"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kedudukan Dalam Kelas </td>\n";
echo "    <td>: ".$row_result["KDK"]." / $bil_pel</td>\n";
echo "	<tr>\n";
echo "	<td>Kedudukan Dalam Tingkatan </td>\n";
echo "	<td>: $kdt / $bil_darjah</td>\n";
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
echo "  <tr>\n";
}
?>
<?php

if($level == "SR"){
	$panggil = "SELECT ulasan,kehadiran,kehadiranpenuh FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep'";
} else {
	$panggil = "SELECT ulasan,kehadiran,kehadiranpenuh FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND kelas='$kelas' AND jpep='$jspep'";
	}
	
	//echo $panggil;
	$resx = oci_parse($conn_sispa,$panggil);
	oci_execute($resx);
	$ulasanx = oci_fetch_array($resx);
	$ulas = $ulasanx['ULASAN'];
	$hadir = $ulasanx["KEHADIRAN"];
	$hadirpenuh = $ulasanx["KEHADIRANPENUH"];
?>

<?php ?>
<table width="98%"><tr><td colspan="8">
<form name="form1" method="post" action="">
  <center>
    <table width="65%" bgcolor="#FFCC66" border="1" cellpadding="0" cellspacing="0">
    <tr height="30">
      <td width="3%" height="33" scope="col"><strong>Kedatangan</strong>
        </th>
      <td width="2%" scope="col">&nbsp;<strong>:</strong>
        </th>
      <td width="91%" scope="col" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="2" name="hadir" id="hadir" value="<?php echo $hadir;?>"/>
        /
          <input type="text" size="2" name="hadirpenuh" id="hadirpenuh" value="<?php echo $hadirpenuh;?>"/>
Hari     
    </tr>
    <tr height="20">
		<td width="3%" height="99" scope="col"><strong>Ulasan</strong>
		  </th>
      <td width="2%" scope="col">&nbsp;<strong>:</strong>
        </th>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;<textarea name="ulasan" id="ulasan" cols="100" rows="4"><?php echo $ulas;?></textarea></td>
    </tr>
  </table>
  </center>
  <p>
    <center>
      <input name="hantar" type="submit" id="hantar" value="Hantar" />
      </center>
  </p>
  <p>&nbsp;</p>
</form>
</left>
</td></tr>
  <tr>
    <td colspan="8">&nbsp;</td>
  </tr>
</table>
<?php

///////////////////////////////////////////////////////end level//////////////////////////////////////////////////////////////////////////////
/*
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
*/
include 'kaki.php';
?>
