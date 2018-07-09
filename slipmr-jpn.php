<?php
include 'auth.php';
include 'config.php';
?>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=1000height=800,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-500,screen.height/2-400);
}
function printPage(){
document.getElementById('mybutton').style.display='none';
window.print();
document.getElementById('mybutton').style.display='block';
}
</script>

<?php
$c = $_GET['data'];
//list ($nokp, $kodsek, $ting, $kelas, $tahun, $jpep, $lencana) = split('[|]', $c);
list ($nokp, $ting, $kelas) = split('[|]', $c);
$kodsek = $_SESSION["kodsek2"];
$tahun = $_SESSION["tahun"];
$jpep = $_SESSION['jpep'];

$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
oci_execute($q_sek);
$rowsek = oci_fetch_array($q_sek);
$namasek = $rowsek["NAMASEK"];
$lencana = $rowsek["LENCANA"];
$m="$nokp|$kodsek|$ting|$kelas|$tahun|$jpep|$lencana";

$gting = strtoupper($ting);
$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 

$q_murid = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp=mr.nokp AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'");
oci_execute($q_murid);
$bilmurid = count_row("SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp=mr.nokp 
AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep 					  
					  AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'");

$q_nting = "SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc";
$bilting = count_row($q_nting);

$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp='$nokp' AND mr.nokp='$nokp' 
AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'");
oci_execute($q_slip);
$rowmurid = oci_fetch_array($q_slip);
$jantina = $rowmurid["JANTINA"];
$kehadiran = $rowmurid["KEHADIRAN"];
$kehadiranpenuh = $rowmurid["KEHADIRANPENUH"];
$ulas = $rowmurid['ULASAN'];

$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc ORDER BY kod");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$namamp[] = array("$rowsub[KOD]"=>$rowsub["MP"]);
}

$jan = array("L" => "LELAKI","P" => "PEREMPUAN");
?>

<?php
//echo "<br><br>\n";
echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
echo "<center><b>$namasek</b></center>";
echo "<br>\n";
echo "<table width=\"700\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\"><strong>SLIP KEPUTUSAN - ".jpep($jpep)." - $tahun</strong></div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br>\n";
echo "<table width=\"700\"  border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td width=\"80\">&nbsp;Nama</font><br></td>\n";
echo "    <td width=\"1\">:</font><br></td>\n";
echo "    <td width=\"388\">&nbsp;".$rowmurid["NAMA"]."</font><br></td>\n";
echo "    <td width=\"80\">&nbsp;Tingkatan</td>\n";
echo "    <td width=\"1\">:</font><br></td>\n";
echo "    <td width=\"150\">&nbsp;".$rowmurid["TING"]." ".$rowmurid["KELAS"]."</font><br></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>&nbsp;No. KP</td>\n";
echo "    <td>:</td>\n";
echo "    <td>&nbsp;".$rowmurid["NOKP"]."</td>\n";
echo "    <td>&nbsp;Jantina</td>\n";
echo "    <td>:</td>\n";
echo "    <td>&nbsp;$jan[$jantina]</td>\n";
echo "  </tr>\n";
echo "</table>\n";
//echo "<br>\n";
echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\">\n";
echo "<tr><td colspan=\"4\"><div align=\"center\"></div><div align=\"center\"><hr align=\"center\" noshade></div></td></tr>\n";
echo "<tr><td>Bil</td><td>Mata Pelajaran </td><td><div align=\"center\">Markah</div></td><td><div align=\"center\">Gred</div></td></tr>\n";
echo "<tr><td colspan=\"4\"><hr align=\"center\" noshade></td></tr>\n";
echo "<tr>\n";
$bil=0;
$q_subgu = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp");
oci_execute($q_subgu);
while($rowsubgu = oci_fetch_array($q_subgu))
{
	$kodmp = $rowsubgu["KODMP"];
	$gmp = "G$kodmp";
	if($rowmurid["$kodmp"] != ''){
		$bil=$bil+1;
		echo "<td>$bil.</td>\n";
		echo "<td>";
		foreach ($namamp as $key => $mp)
		{
			echo "$mp[$kodmp]";
		}
		echo "</td>\n";
		echo "    <td><center>$rowmurid[$kodmp]</center></td>\n";
		echo "    <td><center>$rowmurid[$gmp]</center></td>\n";
	}
	echo "  </tr>\n";
}
echo "</table>\n";
echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"200\">Bilangan Mata Pelajaran Daftar </td>\n";
echo "    <td width=\"300\">: ".$rowmurid["BILMP"]."</td>\n";
echo "    <td width=\"130\">Jumlah Markah </td>\n";
echo "    <td width=\"70\">: ".$rowmurid["JUMMARK"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kedudukan Dalam Kelas </td>\n";
echo "    <td>: ".$rowmurid["KDK"]." / $bilmurid</td>\n";
echo "    <td>Peratus</td>\n";
echo "    <td>: ".$rowmurid["PERATUS"]."</td>\n";
echo "	<tr>\n";
echo "	<td>Kedudukan Dalam Tingkatan </td>\n";
echo "	<td>: ".$rowmurid["KDT"]." / $bilting</td>\n";
echo "    <td>Gred Purata Pelajar </td>\n";
echo "    <td>: ".$rowmurid["GPC"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Kehadiran</td>\n";
echo "    <td>: $kehadiran / $kehadiranpenuh Hari</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "    <td>&nbsp;</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Pencapaian Gred Keseluruhan </td>\n";
echo "    <td colspan='3'>: ".$rowmurid["PENCAPAIAN"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>Keputusan</td>\n";
echo "    <td colspan='3'>: ".$rowmurid["KEPUTUSAN"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "</table>\n";

echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td>Ulasan Guru Kelas : <br />$ulas</td>\n"; 
echo "  </tr>\n";
echo "</table>\n";
echo "<br><br><p>\n";
echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Kelas </div></td>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Pengetua </div></td>\n";
echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Penjaga </div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
echo "    <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
echo "    <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
oci_execute($q_guru);
$row_guru = oci_fetch_array($q_guru);

$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
oci_execute($qgb);
$row_gb = oci_fetch_array($qgb);



echo "    <td><div align=\"center\">..................................................<br>(".$row_guru["NAMA"].") </div></td>\n";
echo "    <td><div align=\"center\">..................................................<br>(".$row_gb["NAMA"].")</div></td>\n";
echo "    <td><div align=\"center\">..................................................<br>&nbsp;</div></td>\n";
echo "  </tr>\n";
echo "</table>\n";


function jpep($kodpep){
	switch ($kodpep)
	{
		case "U1": $npep="UJIAN 1"; break;
		case "U2": $npep="UJIAN 2"; break;
		case "PAT": $npep="PEPERIKSAAN AKHIR TAHUN"; break;
		case "PPT": $npep="PEPERIKSAAN PERTENGAHAN TAHUN"; break;
		case "PMRC": $npep="PEPERIKSAAN PERCUBAAN PMR"; break;
		case "SPMC": $npep="PEPERIKSAAN PERCUBAAN SPM"; break;
	}
	return $npep;
}
?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>