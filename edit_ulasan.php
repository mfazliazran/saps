<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include "input_validation.php";

function count_row_oci_by_name5($sql,$val1,$val2,$val3,$val4,$val5,$param1,$param2,$param3,$param4,$param5){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	//echo "POS $pos :- $sql";
	$newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

	$qic = oci_parse($conn_sispa,$newsql);
	oci_bind_by_name($qic, $param1, $val1);
	oci_bind_by_name($qic, $param2, $val2);
	oci_bind_by_name($qic, $param3, $val3);
	oci_bind_by_name($qic, $param4, $val4);
	oci_bind_by_name($qic, $param5, $val5);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

function count_row_oci_by_name4($sql,$val1,$val2,$val3,$val4,$param1,$param2,$param3,$param4){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	//echo "POS $pos :- $sql";
	$newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

	$qic = oci_parse($conn_sispa,$newsql);
	oci_bind_by_name($qic, $param1, $val1);
	oci_bind_by_name($qic, $param2, $val2);
	oci_bind_by_name($qic, $param3, $val3);
	oci_bind_by_name($qic, $param4, $val4);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

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

$c = validate($_GET['data']);
list ($nokp, $kodsek, $ting, $kelas, $tahun, $jspep) =explode("|", $c);

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

if(validate($_POST['hantar'])){
	$ulasan_guru = oci_escape_string(validate($_POST['ulasan']));
	$kehadiran = oci_escape_string(validate($_POST['hadir']));
	$kehadiranpenuh = oci_escape_string(validate($_POST['hadirpenuh']));
	
	// cek status sama ada sekolah rendah menengah
	// kalau sekolah rendah
	if($level == "SR"){
		$squery = "UPDATE markah_pelajarsr SET ulasan= :ulasan, kehadiran= :kehadiran, kehadiranpenuh= :kehadiranpenuh WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND darjah= :ting AND kelas= :kelas AND jpep= :jspep ";
	} else{
		$squery = "UPDATE markah_pelajar SET ulasan= :ulasan, kehadiran= :kehadiran, kehadiranpenuh= :kehadiranpenuh WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND kelas= :kelas AND jpep= :jspep ";
	}
	
	// die("$squery, $ulasan_guru, $kehadiran, $kehadiranpenuh, $nokp, $tahun, $kodsek, $ting, $kelas, $jspep");
	$result = oci_parse($conn_sispa,$squery);
	oci_bind_by_name($result, ':ulasan', $ulasan_guru);
	oci_bind_by_name($result, ':kehadiran', $kehadiran);
	oci_bind_by_name($result, ':kehadiranpenuh', $kehadiranpenuh);
	oci_bind_by_name($result, ':nokp', $nokp);
	oci_bind_by_name($result, ':tahun', $tahun);
	oci_bind_by_name($result, ':kodsek', $kodsek);
	oci_bind_by_name($result, ':ting', $ting);
	oci_bind_by_name($result, ':kelas', $kelas);
	oci_bind_by_name($result, ':jspep', $jspep);
	oci_execute($result);

	location("slip-nama.php");		
}

///Mula Update table penilaian_smr
if($level=="MA"){
// $kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";

$gting=strtoupper($ting);
$sql_pel = "SELECT * FROM tmurid WHERE tahun$gting= :tahun AND $gting= :ting AND kelas$gting= :kelas AND kodsek$ting= :kodsek AND kodsek_semasa= :kodsek_s";
$bil_pel = count_row_oci_by_name5($sql_pel, $tahun, $ting, $kelas, $kodsek, $kodsek, ":tahun", ":ting", ":kelas", ":kodsek", ":kodsek_s");
$sql_ting = "SELECT * FROM tmurid WHERE tahun$gting= :tahun AND $gting= :ting AND kodsek$gting= :kodsek AND kodsek_semasa= :kodsek_s'";
$bil_ting = count_row_oci_by_name4($sql_ting, $tahun, $ting, $kodsek, $kodsek, ":tahun", ":ting", ":kodsek", ":kodsek_s");
$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND jpep= :jspep ORDER BY ting");
oci_bind_by_name($q_slip, ':nokp', $nokp);
oci_bind_by_name($q_slip, ':tahun', $tahun);
oci_bind_by_name($q_slip, ':kodsek', $kodsek);
oci_bind_by_name($q_slip, ':ting', $ting);
oci_bind_by_name($q_slip, ':kelas', $kelas);
oci_bind_by_name($q_slip, ':jspep', $jspep);
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
$q_sub = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas ORDER BY kodmp");
oci_bind_by_name($q_sub, ':tahun', $tahun);
oci_bind_by_name($q_sub, ':kodsek', $kodsek);
oci_bind_by_name($q_sub, ':ting', $ting);
oci_bind_by_name($q_sub, ':kelas', $kelas);
oci_execute($q_sub);
while($rowsub = oci_fetch_array($q_sub)){
	$kodmp=$rowsub["KODMP"];
	$gmp="G$kodmp";
	$t_mark = oci_parse($conn_sispa,"SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND jpep= :jspep ");
	oci_bind_by_name($t_mark, ':nokp', $nokp);
	oci_bind_by_name($t_mark, ':tahun', $tahun);
	oci_bind_by_name($t_mark, ':kodsek', $kodsek);
	oci_bind_by_name($t_mark, ':ting', $ting);
	oci_bind_by_name($t_mark, ':kelas', $kelas);
	oci_bind_by_name($t_mark, ':jspep', $jspep);
	oci_execute($t_mark);

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

$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsma WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND jpep= :jspep ORDER BY ting");//penilaian_muridsma
oci_bind_by_name($q_nilai, ':nokp', $nokp);
oci_bind_by_name($q_nilai, ':tahun', $tahun);
oci_bind_by_name($q_nilai, ':kodsek', $kodsek);
oci_bind_by_name($q_nilai, ':ting', $ting);
oci_bind_by_name($q_nilai, ':kelas', $kelas);
oci_bind_by_name($q_nilai, ':jspep', $jspep);
oci_execute($q_nilai);
$row_result=oci_fetch_array($q_nilai);

$q_nilai2 = oci_parse($conn_sispa,"SELECT KDT FROM tnilai_sma mr WHERE mr.nokp= :nokp AND mr.tahun= :tahun AND mr.kodsek= :kodsek AND mr.ting= :ting AND mr.kelas= :kelas AND mr.jpep= :jspep");
oci_bind_by_name($q_nilai2, ':nokp', $nokp);
oci_bind_by_name($q_nilai2, ':tahun', $tahun);
oci_bind_by_name($q_nilai2, ':kodsek', $kodsek);
oci_bind_by_name($q_nilai2, ':ting', $ting);
oci_bind_by_name($q_nilai2, ':kelas', $kelas);
oci_bind_by_name($q_nilai2, ':jspep', $jspep);
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
if($level=="MR"){

// $kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 

$gting=strtoupper($ting);
$sql_pel = "SELECT * FROM tmurid WHERE tahun$gting= :tahun AND $gting= :ting AND kelas$gting= :kelas AND kodsek$gting= :kodsek and kodsek_semasa= :kodsek_s";
$bil_pel = count_row_oci_by_name5($sql_pel, $tahun, $ting, $kelas, $kodsek, $kodsek, ":tahun", ":ting", ":kelas", ":kodsek", ":kodsek_s");
$sql_ting = "SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kodsek$gting='$kodsek' and kodsek_semasa='$kodsek'";
$bil_ting = count_row_oci_by_name4($sql_ting, $tahun, $ting, $kodsek, $kodsek, ":tahun", ":ting", ":kodsek", ":kodsek_s");

$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND jpep= :jspep ORDER BY ting");
oci_bind_by_name($q_slip, ':nokp', $nokp);
oci_bind_by_name($q_slip, ':tahun', $tahun);
oci_bind_by_name($q_slip, ':kodsek', $kodsek);
oci_bind_by_name($q_slip, ':ting', $ting);
oci_bind_by_name($q_slip, ':kelas', $kelas);
oci_bind_by_name($q_slip, ':jspep', $jspep);
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
$q_sub = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas ORDER BY kodmp");
oci_bind_by_name($q_sub, ':tahun', $tahun);
oci_bind_by_name($q_sub, ':kodsek', $kodsek);
oci_bind_by_name($q_sub, ':ting', $ting);
oci_bind_by_name($q_sub, ':kelas', $kelas);
oci_execute($q_sub);
while($rowsub = oci_fetch_array($q_sub)){
	$kodmp=$rowsub["KODMP"];
	$gmp="G$kodmp";
	$t_mark = oci_parse($conn_sispa,"SELECT $kodmp, $gmp FROM markah_pelajar WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND jpep= :jspep ");
	oci_bind_by_name($t_mark, ':nokp', $nokp);
	oci_bind_by_name($t_mark, ':tahun', $tahun);
	oci_bind_by_name($t_mark, ':kodsek', $kodsek);
	oci_bind_by_name($t_mark, ':ting', $ting);
	oci_bind_by_name($t_mark, ':kelas', $kelas);
	oci_bind_by_name($t_mark, ':jspep', $jspep);
	oci_execute($t_mark);
	while($row_mark=oci_fetch_array($t_mark)){
		if($row_mark[$kodmp] != ''){
			$bil=$bil+1;
			$stmt=oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod='$kodmp'");
			oci_execute($stmt);
			$t_mp = oci_fetch_array($stmt);
			
			echo "    <td>$bil</td>\n";
			echo "    <td>".$t_mp["MP"]."</td>\n";
			echo "    <td><center>$row_mark[$kodmp]</center></td>\n";
			echo "    <td><center>$row_mark[$gmp]</center></td>\n";
		}
		
		}
	echo "  </tr>\n";
	}


$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsmr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY ting");
$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsmr WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND jpep= :jspep ORDER BY ting");
oci_bind_by_name($q_nilai, ':nokp', $nokp);
oci_bind_by_name($q_nilai, ':tahun', $tahun);
oci_bind_by_name($q_nilai, ':kodsek', $kodsek);
oci_bind_by_name($q_nilai, ':ting', $ting);
oci_bind_by_name($q_nilai, ':kelas', $kelas);
oci_bind_by_name($q_nilai, ':jspep', $jspep);
oci_execute($q_nilai);
$row_result=oci_fetch_array($q_nilai);

$q_nilai2 = oci_parse($conn_sispa,"SELECT KDT FROM tnilai_smr mr WHERE mr.nokp= :nokp AND mr.tahun= :tahun AND mr.kodsek= :kodsek AND mr.ting= :ting AND mr.kelas= :kelas AND mr.jpep= :jspep");
oci_bind_by_name($q_nilai2, ':nokp', $nokp);
oci_bind_by_name($q_nilai2, ':tahun', $tahun);
oci_bind_by_name($q_nilai2, ':kodsek', $kodsek);
oci_bind_by_name($q_nilai2, ':ting', $ting);
oci_bind_by_name($q_nilai2, ':kelas', $kelas);
oci_bind_by_name($q_nilai2, ':jspep', $jspep);
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
///SLIP UNTUK SEKOLAH RENDAH
if($level=="SR"){
// $kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";

$gting=strtoupper($ting);

$sql_pel = "SELECT * FROM tmuridsr WHERE tahun$gting= :tahun AND $gting= :ting AND kelas$gting= :kelas AND kodsek$gting= :kodsek AND kodsek_semasa= :kodsek_s";
$bil_pel = count_row_oci_by_name5($sql_pel, $tahun, $ting, $kelas, $kodsek, $kodsek, ":tahun", ":ting", ":kelas", ":kodsek", ":kodsek_s");
$sql_darjah = "SELECT * FROM tmuridsr WHERE tahun$gting= :tahun AND $gting= :ting AND kodsek$gting= :kodsek AND kodsek_semasa= :kodsek_s";
$bil_darjah = count_row_oci_by_name4($sql_darjah, $tahun, $ting, $kodsek, $kodsek, ":tahun", ":ting", ":kodsek", ":kodsek_s");

$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND darjah= :ting AND kelas= :kelas AND jpep= :jspep ORDER BY darjah");
oci_bind_by_name($q_slip, ':nokp', $nokp);
oci_bind_by_name($q_slip, ':tahun', $tahun);
oci_bind_by_name($q_slip, ':kodsek', $kodsek);
oci_bind_by_name($q_slip, ':ting', $ting);
oci_bind_by_name($q_slip, ':kelas', $kelas);
oci_bind_by_name($q_slip, ':jspep', $jspep);
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
$q_sub = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas ORDER BY kodmp");
oci_bind_by_name($q_sub, ':tahun', $tahun);
oci_bind_by_name($q_sub, ':kodsek', $kodsek);
oci_bind_by_name($q_sub, ':ting', $ting);
oci_bind_by_name($q_sub, ':kelas', $kelas);
oci_execute($q_sub);
while($rowsub = oci_fetch_array($q_sub)){
	$kodmp=$rowsub["KODMP"];
	$gmp="G$kodmp";
	$t_mark = oci_parse($conn_sispa,"SELECT $kodmp, $gmp FROM markah_pelajarsr WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND darjah= :ting AND kelas= :kelas AND jpep= :jspep ");
	oci_bind_by_name($t_mark, ':nokp', $nokp);
	oci_bind_by_name($t_mark, ':tahun', $tahun);
	oci_bind_by_name($t_mark, ':kodsek', $kodsek);
	oci_bind_by_name($t_mark, ':ting', $ting);
	oci_bind_by_name($t_mark, ':kelas', $kelas);
	oci_bind_by_name($t_mark, ':jspep', $jspep);
	oci_execute($t_mark);
	// $num=count_row("SELECT $kodmp, $gmp FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep' ");

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


$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsr WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND darjah= :ting AND kelas= :kelas AND jpep= :jspep ORDER BY darjah");
oci_bind_by_name($q_nilai, ':nokp', $nokp);
oci_bind_by_name($q_nilai, ':tahun', $tahun);
oci_bind_by_name($q_nilai, ':kodsek', $kodsek);
oci_bind_by_name($q_nilai, ':ting', $ting);
oci_bind_by_name($q_nilai, ':kelas', $kelas);
oci_bind_by_name($q_nilai, ':jspep', $jspep);
oci_execute($q_nilai);
$row_result=oci_fetch_array($q_nilai);

$q_nilai2 = oci_parse($conn_sispa,"SELECT KDT FROM tnilai_sr mr WHERE mr.nokp= :nokp AND mr.tahun= :tahun AND mr.kodsek= :kodsek AND mr.darjah= :ting AND mr.kelas= :kelas AND mr.jpep= :jspep");
oci_bind_by_name($q_nilai2, ':nokp', $nokp);
oci_bind_by_name($q_nilai2, ':tahun', $tahun);
oci_bind_by_name($q_nilai2, ':kodsek', $kodsek);
oci_bind_by_name($q_nilai2, ':ting', $ting);
oci_bind_by_name($q_nilai2, ':kelas', $kelas);
oci_bind_by_name($q_nilai2, ':jspep', $jspep);
oci_execute($q_nilai2);
$row_result2=oci_fetch_array($q_nilai2);
$kdt = $row_result2["KDT"];

echo "</table>\n";

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

echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
}
?>
<?php

if($level == "SR"){
	$panggil = "SELECT ulasan,kehadiran,kehadiranpenuh FROM markah_pelajarsr WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND darjah= :ting AND kelas= :kelas AND jpep= :jspep";
} else{
	$panggil = "SELECT ulasan,kehadiran,kehadiranpenuh FROM markah_pelajar WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND kelas= :kelas AND jpep= :jspep";
}
	
	$resx = oci_parse($conn_sispa,$panggil);
	oci_bind_by_name($resx, ':nokp', $nokp);
	oci_bind_by_name($resx, ':tahun', $tahun);
	oci_bind_by_name($resx, ':kodsek', $kodsek);
	oci_bind_by_name($resx, ':ting', $ting);
	oci_bind_by_name($resx, ':kelas', $kelas);
	oci_bind_by_name($resx, ':jspep', $jspep);
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
include 'kaki.php';
?>
