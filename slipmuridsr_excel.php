<?php
include 'auth.php';
include 'config.php';
//include 'kepala.php';
//include 'menu.php';

   header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="slipmuridsr.xls"');
   echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>CETAK SEMUA SLIP</TITLE>";
   echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
   echo "<body>";

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
$m = $_GET['data'];
list ($nokp, $kodsek, $ting, $kelas, $tahun, $jpep, $lencana) = split('[|]', $m);
$jpep = $_SESSION["jpep"];
$kodsek= $_SESSION["kodsek"];
$tahun = $_SESSION["tahun"];
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

$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
oci_execute($q_sek);
$rowsek = oci_fetch_array($q_sek);
$namasek = $rowsek["NAMASEK"];
$lencana = $rowsek["LENCANA"];


$gting=strtoupper($ting);
$kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";


//$bildarjah=count_row("SELECT * FROM tmuridsr WHERE tahun$gting='$tahun' AND $gting='$ting' AND ($kodsekolah)");
//echo "SELECT * FROM tmuridsr WHERE tahun$gting='$tahun' AND $gting='$ting' AND ($kodsekolah)";
$q_nting =("SELECT *  FROM tnilai_sr sr, tmuridsr tm WHERE sr.tahun='$tahun' AND sr.jpep='$jpep' AND sr.darjah='$ting' AND sr.kodsek='$kodsek' AND sr.nokp=tm.nokp ORDER BY sr.keputusan Desc, sr.gpc Asc, sr.peratus Desc");
$qrt = oci_parse($conn_sispa,$q_nting);
oci_execute($qrt);
$bildarjah = count_row($q_nting);
//echo "$q_nting";


//$q_murid = "SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.nokp=sr.nokp AND 
// mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek
//and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep and 
//mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.darjah='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'";
//$stmt = oci_parse($conn_sispa,$q_murid);
//oci_execute($stmt);
$bilmurid=count_row("SELECT * FROM tmuridsr WHERE tahun$gting='$tahun' AND $gting='$ting' and KELAS$ting='$kelas' AND ($kodsekolah)");
//$bilmurid = count_row($q_murid);

$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.nokp='$nokp' AND 
 mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek
and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep and
					sr.nokp='$nokp' AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.darjah='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'");
oci_execute($q_slip);
$rowmurid = oci_fetch_array($q_slip);
$jantina = $rowmurid["JANTINA"];
$kehadiran = $rowmurid["KEHADIRAN"];
$kehadiranpenuh = $rowmurid["KEHADIRANPENUH"];

$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsr ORDER BY kod");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$namamp[] = array("$rowsub[KOD]"=>$rowsub["MP"]);
}

$jan = array("L" => "LELAKI","P" => "PEREMPUAN");
?>
<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>
<input type="button" name="export" value="EXPORT KE EXCEL" onClick="open_window('slipmuridsr_excel.php?data=<?php echo $m;?>','win1');" /></center>
<?php
	   
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
echo "<br>\n";
//echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
echo "<center><b>$namasek</b></center>";
echo "<br>\n";
echo "<center><b><strong>SLIP KEPUTUSAN - ".jpep($jpep)." - $tahun</strong></b></center>";
echo "<br>\n";
echo "<table width=\"700\"  border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td width=\"550\">&nbsp;&nbsp;&nbsp;Nama : ".$rowmurid["NAMA"]."<br></td>\n";
echo "    <td width=\"200\">Tingkatan : ".$rowmurid["DARJAH"]." ".$rowmurid["KELAS"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>&nbsp;&nbsp;&nbsp;No. KP :  ".$rowmurid["NOKP"]."</td>\n";
echo "    <td>Jantina : $jan[$jantina] </td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br>\n";
echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><div align=\"center\"></div><div align=\"center\">\n";
echo "      <hr align=\"center\" noshade>\n";
echo "    </div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>&nbsp;&nbsp;&nbsp;Bil</td>\n";
echo "    <td>Mata Pelajaran </td>\n";
echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">Gred</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
$bil=0;
$q_subgu = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp");
oci_execute($q_subgu);
while($rowsubgu = oci_fetch_array($q_subgu))
{
	$kodmp = $rowsubgu["KODMP"];
	$gmp = "G$kodmp";
	if($rowmurid["$kodmp"] != ''){
		$bil=$bil+1;
		echo "<td>&nbsp;&nbsp;&nbsp;$bil.</td>\n";
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
echo "    <td width=\"200\">&nbsp;&nbsp;&nbsp;Bilangan Mata Pelajaran &nbsp;&nbsp;&nbsp;Daftar </td>\n";
echo "    <td width=\"350\">: ".$rowmurid["BILMP"]."</td>\n";
echo "    <td width=\"120\">Jumlah Markah </td>\n";
echo "    <td width=\"60\">: ".$rowmurid["JUMMARK"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>&nbsp;&nbsp;&nbsp;Kedudukan Dalam Kelas </td>\n";
echo "    <td>: ".$rowmurid["KDK"]." / $bilmurid</td>\n";
echo "	<tr>\n";
echo "	<td>&nbsp;&nbsp;&nbsp;Kedudukan Dalam Darjah</td>\n";
echo "	<td>: ".$rowmurid["KDT"]." / $bildarjah</td>\n";

//echo("$bilmurid");
echo "    <td>Peratus</td>\n";
echo "    <td>: ".$rowmurid["PERATUS"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>&nbsp;&nbsp;&nbsp;Kehadiran</td>\n";
echo "    <td>: $kehadiran / $kehadiranpenuh Hari </td>\n";
echo "    <td>Gred Purata Pelajar </td>\n";
echo "    <td>: ".$rowmurid["GPC"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td>&nbsp;&nbsp;&nbsp;Pencapaian Gred &nbsp;&nbsp;&nbsp;Keseluruhan </td>\n";
echo "    <td>: ".$rowmurid["PENCAPAIAN"]."</td>\n";
echo "    <td>Keputusan</td>\n";
echo "    <td>: ".$rowmurid["KEPUTUSAN"]."</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
echo "  </tr>\n";
echo "</table>\n";

if($level == "SR"){
$panggil = "SELECT ulasan FROM markah_pelajarsr WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='$jpep'";
} else {
	$panggil = "SELECT ulasan FROM markah_pelajar WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND kelas='$kelas' AND jpep='$jpep'";
	}
	
	//echo $panggil;
	$resx = oci_parse($conn_sispa,$panggil);
	oci_execute($resx);
	$ulasanx = oci_fetch_array($resx);
	$ulas = $ulasanx['ULASAN'];
	//echo $ulas;


echo "<br>\n";
echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td>&nbsp;&nbsp;&nbsp;&nbsp;Ulasan Guru Kelas : <br /><br /> &nbsp;&nbsp;&nbsp;&nbsp;$ulas</td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br><br><br><br><br><p>\n";
echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
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
oci_execute($q_guru);
$row_guru = oci_fetch_array($q_guru);


$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
oci_execute($qgb);
$row_gb = oci_fetch_array($qgb);

echo "    <td><div align=\"center\">........................................<br>(".$row_guru["NAMA"].") </div></td>\n";
echo "    <td><div align=\"center\">........................................<br>(".$row_gb["NAMA"].")</div></td>\n";
echo "    <td><div align=\"center\">........................................</div></td>\n";
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