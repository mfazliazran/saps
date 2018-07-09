<?php 
include 'auth.php';
include 'config.php';
?>
<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=1000height=800,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-500,screen.height/2-400);
}
</script>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<?php
$c = $_GET['data'];
//list ($nokp, $kodsek, $ting, $kelas, $tahun, $jspep) =split('[|]', $c);
list ($nokp, $ting, $kelas) =split('[|]', $c);
$kodsek = $_SESSION["kodsek"];
$tahun = $_SESSION["tahun"];
$jspep = $_SESSION["jpep"];

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
//////////////////////////////////////////////////////level ma /////////////////////////////////////////////////////////////////////////////////////////////////
///Mula Update table penilaian_smr
if($level=="MA"){
$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 

$gting=strtoupper($ting);
//$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' AND ($kodsekolah)");
$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND $gting='$ting' AND kelas$gting='$kelas' and tahun$gting='$tahun' and kodsek_semasa='$kodsek'");
oci_execute($q_murid);
$bil_pel=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' AND ($kodsekolah)");
//$bil_ting=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND ($kodsekolah)");
//echo "SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND ($kodsekolah)";

$q_nting = "SELECT *  FROM tnilai_sma ma, tmurid tm WHERE ma.tahun='$tahun' AND ma.jpep='$jspep' AND ma.ting='$ting' AND ma.kodsek='$kodsek' AND ma.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY ma.keputusan Desc, ma.gpc Asc, ma.peratus Desc";

$qrt = oci_parse($conn_sispa,$q_nting);
oci_execute($qrt);
$bil_ting = count_row($q_nting);


$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY ting");
oci_execute($q_slip);
while($row = oci_fetch_array($q_slip)){
$nama = $row["NAMA"];
$nokp = $row["NOKP"];
$ting = $row["TING"];
$kelas = $row["KELAS"];
$jantina = $row["JANTINA"];
$ulasan = $row["ULASAN"];
$hadir = $ulasanx["KEHADIRAN"];
$hadirpenuh = $ulasanx["KEHADIRANPENUH"];
$m="$nokp|$kodsek|$ting|$kelas|$tahun|$jpep|$lencana";
}

$jan=array("L" => "LELAKI",
		   "P" => "PEREMPUAN");
?>
<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('slipmuridma_excel.php?data=<?php echo $m;?>','win1');" /></center
><?php
	   
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
oci_execute($q_sek);
$rowsek = oci_fetch_array($q_sek);
$lencana=$rowsek["LENCANA"];
echo "<br><br>\n";
		//CA16110902
		if (isset($lencana)){
			echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
		}
		//CA16110902
echo "<center><b>".$rowsek["NAMASEK"]."</b></center>";
echo "<br>\n";
echo "<table width=\"750\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\"><strong>SLIP KEPUTUSAN - ".jpep($jspep)." - $tahun</strong></div></td>\n";
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
///////////////////////////////////////////////////////////////////////////ma//////////////////////////////////////////////////////////////////////////////////////
$kdk=0;
$kdt=0;
//$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsma WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY ting");
$q_nilai = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp='$nokp' AND ma.nokp='$nokp' AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep and mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jspep'");

oci_execute($q_nilai);
$row_result=oci_fetch_array($q_nilai);
$kdk = $row_result["KDK"];
$kdt = $row_result["KDT"];

$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep'");
		oci_execute($q_slip);
		$rowx = oci_fetch_array($q_slip);
		$nama = $rowx["NAMA"];
		$nokp = $rowx["NOKP"];
		$ting = $rowx["TING"];
		$kelas = $rowx["KELAS"];
		$jantina = $rowx["JANTINA"];
		$kehadiran = $rowx["KEHADIRAN"];
		$kehadiranpenuh = $rowx["KEHADIRANPENUH"];
		$m="$nokp|$kodsek|$ting|$kelas|$tahun|$jpep|$lencana";
		while($row = oci_fetch_array($q_slip)){
			$nama = $row["NAMA"]; $nokp = $row["NOKP"]; $ting = $row["TING"]; $kelas = $row["KELAS"]; $jantina = $row["JANTINA"];
		}

$q_nting1 = "SELECT *  FROM tnilai_sma ma, tmurid tm WHERE ma.tahun='$tahun' AND ma.jpep='$jpep' AND ma.ting='$ting' AND ma.kodsek='$kodsek' AND ma.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY ma.keputusan Desc, ma.gpc Asc, ma.peratus Desc";
$bilmtingg = count_row($q_nting1);

echo "</table>\n";
echo "<br>\n";
echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"5\"><hr align=\"center\" noshade></td>\n";
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
echo "	<td>:  ".$row_result["KDT"]." / $bil_ting</td>\n";
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
echo "<br>\n";
echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td>Ulasan Guru Kelas :$ulasan </td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br><br><br><br><br><p>\n";
echo "<br><br><br><br><br><p>\n";
echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Kelas </div></td>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Pengetua </div></td>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Penjaga </div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
//echo "SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'";
oci_execute($q_guru);
$row_guru = oci_fetch_array($q_guru);

$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
oci_execute($qgb);
$row_gb = oci_fetch_array($qgb);

echo "    <td><div align=\"center\">........................................<br>(".$row_guru["NAMA"].")</div></td>\n";
echo "    <td><div align=\"center\">........................................<br />(".$row_gb['NAMA'].")</div></td>\n";
echo "    <td><div align=\"center\">........................................</div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
}
//////////////////////////////////////////////////////level mr /////////////////////////////////////////////////////////////////////////////////////////////////
///Mula Update table penilaian_smr
if($level=="MR"){
$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 

$gting=strtoupper($ting);
//$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' AND ($kodsekolah)");
//oci_execute($q_murid);
//$bil_pel=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' AND ($kodsekolah)");
$bil_pel=count_row("SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND $gting='$ting' AND kelas$gting='$kelas' and tahun$gting='$tahun' and kodsek_semasa='$kodsek'");

$q_nting = "SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jspep' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc";
//$qrt = oci_parse($conn_sispa,$q_nting);
//oci_execute($qrt);
$bil_ting = count_row($q_nting);


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
$m="$nokp|$kodsek|$ting|$kelas|$tahun|$jpep|$lencana";
}

$jan=array("L" => "LELAKI",
		   "P" => "PEREMPUAN");
?>
<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('slipmuridmr_excel.php?data=<?php echo $m;?>','win1');" /></center>
</form>
<?php
	   
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
oci_execute($q_sek);
$rowsek = oci_fetch_array($q_sek);
$lencana=$rowsek["LENCANA"];
echo "<br><br>\n";
echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
echo "<center><b>".$rowsek["NAMASEK"]."</b></center>";
echo "<br>\n";
echo "<table width=\"750\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\"><strong>SLIP KEPUTUSAN - ".jpep($jspep)." - $tahun</strong></div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br><br>\n";
echo "<table width=\"700\"  border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td width=\"80\">&nbsp;Nama</font><br></td>\n";
echo "    <td width=\"1\">:</font><br></td>\n";
echo "    <td width=\"388\">&nbsp;$nama</font><br></td>\n";
echo "    <td width=\"80\">&nbsp;Tingkatan</td>\n";
echo "    <td width=\"1\">:</font><br></td>\n";
echo "    <td width=\"150\">&nbsp;$ting $kelas</font><br></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>&nbsp;No. KP</td>\n";
echo "    <td>:</td>\n";
echo "    <td>&nbsp;$nokp</td>\n";
echo "    <td>&nbsp;Jantina</td>\n";
echo "    <td>:</td>\n";
echo "    <td>&nbsp;$jan[$jantina]</td>\n";
echo "  </tr>\n";
echo "</table>\n";

echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\">\n";
echo "<tr><td colspan=\"4\"><div align=\"center\"></div><div align=\"center\"><hr align=\"center\" noshade></div></td></tr>\n";
echo "<tr><td>Bil</td><td>Mata Pelajaran </td><td><div align=\"center\">Markah</div></td><td><div align=\"center\">Gred</div></td></tr>\n";
echo "<tr><td colspan=\"4\"><hr align=\"center\" noshade></td></tr>\n";
echo "<tr>\n";
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
echo "</table>\n";
$kdk=0;
$kdt=0;
//$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsmr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='$jspep' ORDER BY ting");
$q_nilai = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp='$nokp' AND mr.nokp='$nokp' 
AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jspep'");
oci_execute($q_nilai);
$row_result=oci_fetch_array($q_nilai);
$kdk = $row_result["KDK"];
$kdt = $row_result["KDT"];

//$q_ntin = "SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jspep' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc";
//$bilmr = count_row($q_ntin);


echo "<br>\n";
echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"200\">Bilangan Mata Pelajaran Daftar </td>\n";
echo "    <td width=\"300\">: ".$row_result["BILMP"]."</td>\n";
echo "    <td width=\"130\">Jumlah Markah </td>\n";
echo "    <td width=\"70\">: ".$row_result["JUMMARK"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kedudukan Dalam Kelas </td>\n";
echo "    <td>: ".$row_result["KDK"]." / $bil_pel</td>\n";
echo "    <td>Peratus</td>\n";
echo "    <td>: ".$row_result["PERATUS"]."</td>\n";
echo "	<tr>\n";
echo "	<td>Kedudukan Dalam Tingkatan </td>\n";
echo "	<td>: ".$row_result["KDT"]." / $bil_ting </td>\n";
echo "    <td>Gred Purata Pelajar </td>\n";
echo "    <td>: ".$row_result["GPC"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kehadiran</td>\n";
echo "    <td>: $kehadiran / $kehadiranpenuh Hari</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Pencapaian Gred Keseluruhan </td>\n";
echo "    <td colspan='3'>: ".$row_result["PENCAPAIAN"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td colspan='3'>Keputusan</td>\n";
echo "    <td>: ".$row_result["KEPUTUSAN"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "</table>\n";


echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td>Ulasan Guru Kelas : <br>$ulasan</td>\n";
echo "  </tr>\n";
echo "</table>\n";

//echo "<br><br><br><br><br><p>\n";
//echo "<br><br><br><br><br><p>\n";
echo "<br><br><p>\n";
echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Kelas </div></td>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Pengetua </div></td>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Penjaga </div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
oci_execute($q_guru);
$row_guru = oci_fetch_array($q_guru);

$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
oci_execute($qgb);
$row_gb = oci_fetch_array($qgb);

echo "    <td><div align=\"center\">........................................<br>(".$row_guru["NAMA"].")</div></td>\n";
echo "    <td><div align=\"center\">........................................<br>(".$row_gb["NAMA"].")</div></td>\n";
echo "    <td><div align=\"center\">........................................</div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
}
//////////////////////////////////////////////////////level sr /////////////////////////////////////////////////////////////////////////////////////////////////
///Mula Update table penilaian_smr
if($level=="SR"){
$kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";

$gting=strtoupper($ting);
$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND $gting='$ting' AND kelas$gting='$kelas' AND tahun$gting='$tahun' and kodsek_semasa='$kodsek'");//AND($kodsekolah) ");
oci_execute($q_murid);

$q_nting = "SELECT *  FROM tnilai_sr sr, tmuridsr tm WHERE sr.tahun='$tahun' AND sr.jpep='$jspep' AND sr.kodsek='$kodsek' AND sr.darjah='$ting' AND sr.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY sr.keputusan Desc, sr.gpc Asc, sr.peratus Desc";

$qry = oci_parse($conn_sispa,$q_nting);
oci_execute($qry);
$bil_darjah = count_row($q_nting);

$bil_pel=count_row("SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND $gting='$ting' and KELAS$ting='$kelas' AND tahun$gting='$tahun' and kodsek_semasa='$kodsek'");// AND ($kodsekolah)");

$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr WHERE nokp='$nokp' AND kodsek='$kodsek' AND kelas='$kelas' AND tahun='$tahun' AND jpep='$jspep' AND darjah='$ting' ORDER BY darjah");
oci_execute($q_slip);
while($row = oci_fetch_array($q_slip)){
$nama = $row["NAMA"];
$nokp = $row["NOKP"];
$ting = $row["DARJAH"];
$kelas = $row["KELAS"];
$jantina = $row["JANTINA"];
$ulas = $row["ULASAN"];
$kehadiran = $row["KEHADIRAN"];
$kehadiranpenuh = $row["KEHADIRANPENUH"];
$m="$nokp|$kodsek|$ting|$kelas|$tahun|$jpep|$lencana";
}

$jan=array("L" => "LELAKI",
		   "P" => "PEREMPUAN");
?>

<?php
	   
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
oci_execute($q_sek);
$rowsek = oci_fetch_array($q_sek);
$lencana=$rowsek["LENCANA"];
echo "<br><br>\n";
echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
echo "<center><b>".$rowsek["NAMASEK"]."</b></center>";
echo "<br>\n";
echo "<table width=\"750\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\"><strong>SLIP KEPUTUSAN - ".jpep($jspep)." - $tahun</strong></div></td>\n";
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
$q_sub = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp");
oci_execute($q_sub);
while($rowsub = oci_fetch_array($q_sub)){
	$kodmp=$rowsub["KODMP"];
	$gmp="G$kodmp";
	$t_mark = oci_parse($conn_sispa,"SELECT $kodmp, $gmp FROM markah_pelajarsr WHERE nokp='$nokp' AND kodsek='$kodsek' AND kelas='$kelas' AND tahun='$tahun' AND jpep='$jspep' AND darjah='$ting'");
	oci_execute($t_mark);
	//$num=count_row("SELECT $kodmp, $gmp FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep' ");
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

$kdk=0;
$kdt=0;
$q_nilai = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.nokp='$nokp' AND mkh.kodsek='$kodsek' AND mkh.kelas='$kelas' AND mkh.tahun='$tahun' AND mkh.jpep='$jspep' AND mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek
and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep and sr.nokp='$nokp' AND mkh.darjah='$ting'");
oci_execute($q_nilai);
$row_result=oci_fetch_array($q_nilai);
$kdk = $row_result["KDK"];
$kdt = $row_result["KDT"];

echo "</table>\n";
echo "<br>\n";
echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"5\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"209\">Bilangan Mata Pelajaran Daftar </td>\n";
echo "    <td width=\"178\">: ". $row_result["BILMP"]."</td>\n";
echo "    <td width=\"161\">Jumlah Markah </td>\n";
echo "    <td width=\"228\">: ".$row_result["JUMMARK"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kedudukan Dalam Kelas </td>\n";
echo "    <td>: $kdk / $bil_pel</td>\n";
echo "	<tr>\n";
echo " <td>Kedudukan Dalam Darjah </td>\n";
echo "  <td>: ".$row_result["KDT"]." / $bil_darjah</td>\n";
echo "   <td>Peratus</td>\n";
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
echo "    <td colspan=\"5\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br>\n";
echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";

if($level == "SR"){
$panggil = "SELECT ulasan FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep'";
} else {
	$panggil = "SELECT ulasan FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jspep'";
	}
	
	//echo $panggil;
	//$resx = oci_parse($conn_sispa,$panggil);
	//oci_execute($resx);
	//$ulasanx = oci_fetch_array($resx);
	//$ulas = $ulasanx['ULASAN'];
	//echo $ulas;


echo "    <td>Ulasan Guru Kelas : <br />$ulas </td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br><br><br><br><br><p>\n";
echo "<br><br><br><br><br><p>\n";
echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Kelas </div></td>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Besar </div></td>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Penjaga </div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
//echo "SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'";
oci_execute($q_guru);
$row_guru = oci_fetch_array($q_guru);


$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
oci_execute($qgb);
$row_gb = oci_fetch_array($qgb);


echo "    <td><div align=\"center\">........................................<br>(".$row_guru["NAMA"]." )</div></td>\n";
echo "    <td><div align=\"center\">........................................<br>(".$row_gb["NAMA"].")</div></td>\n";
echo "    <td><div align=\"center\">........................................</div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
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
	case "UPSRC":
	$npep="PEPERIKSAAN PERCUBAAN UPSR";
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
