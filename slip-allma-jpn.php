<?php
include 'auth.php';
include 'config.php';
?>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<?php

$c = $_GET['data'];
list ($kodsek, $ting, $kelas, $tahun, $jpep, $lencana) = split('[|]', $c);

$q_sek = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
oci_execute($q_sek);
$rowsek = oci_fetch_array($q_sek);
$namasek = $rowsek["NAMASEK"];
$lencana = $rowsek["LENCANA"];

$q_murid = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_sma mr WHERE mkh.nokp=mr.nokp
					  AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep 
					 AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep' ORDER BY mkh.nama");
/*echo "SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp
					  AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep 
					 AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep' ORDER BY mkh.nama";*/

$gting = strtoupper($ting);
$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 


oci_execute($q_murid);

//$bilmurid = count_row("SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep' ORDER BY mkh.nama");
$bilmurid = count_row("SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'");

//$bilting=count_row("SELECT * FROM tmurid WHERE tahun$gting='$tahun' AND $gting='$ting' AND ($kodsekolah)");
$q_nting = "SELECT *  FROM tnilai_sma ma, tmurid tm WHERE ma.tahun='$tahun' AND ma.jpep='$jpep' AND ma.ting='$ting' AND ma.kodsek='$kodsek' AND ma.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY ma.keputusan Desc, ma.gpc Asc, ma.peratus Desc";
$qrt = oci_parse($conn_sispa,$q_nting);
oci_execute($qrt);
$bilting = count_row($q_nting);

while ( $rowmurid = oci_fetch_array($q_murid))

{
	$rpel[] = $rowmurid;
}

$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' AND sg.kelas='$kelas' AND mp.kod=sg.kodmp ORDER BY mp.kod");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$mpkelas[] = array("KODMP"=>$rowsub["KODMP"], "$rowsub[KOD]"=>$rowsub["MP"]);
}

$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' ORDER BY kelas");
oci_execute($q_guru);
$row_guru = oci_fetch_array($q_guru);

$jan = array("L" => "LELAKI","P" => "PEREMPUAN");

if (!empty($rpel))
{
	$bilpage = $bilmurid;
	foreach( $rpel as $key => $murid )
	{   
		$jantina = $murid["JANTINA"];
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
		echo "<br><br>\n";
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
		echo "    <td width=\"550\">&nbsp;&nbsp;&nbsp;Nama : ".$murid["NAMA"]."<br></td>\n";
		echo "    <td width=\"200\">Tingkatan : $murid[TING] ".$murid["KELAS"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>&nbsp;&nbsp;&nbsp;No. KP :  ".$murid["NOKP"]."</td>\n";
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
	
		foreach($mpkelas as $keysub => $subjek)
		{
			$kodmp = $subjek["KODMP"];
			$gmp = "G$kodmp";
			if($murid["$kodmp"] != ''){
				$bil=$bil+1;
				echo "<td>&nbsp;&nbsp;&nbsp;$bil.</td>\n";
				echo "<td>";
				echo "$subjek[$kodmp]";
				echo "</td>\n";
				echo "    <td><center>$murid[$kodmp]</center></td>\n";
				echo "    <td><center>$murid[$gmp]</center></td>\n";
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
		echo "    <td width=\"350\">: ".$murid["BILMP"]."</td>\n";
		echo "    <td width=\"120\">Jumlah Markah </td>\n";
		echo "    <td width=\"60\">: ".$murid["JUMMARK"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>&nbsp;&nbsp;&nbsp;Kedudukan Dalam Kelas </td>\n";
		echo "    <td>: ".$murid["KDK"]." / $bilmurid</td>\n";
		echo "  <tr>\n";
		echo "	<td>&nbsp;&nbsp;&nbsp;Kedudukan Dalam Tingkatan </td>\n";
		echo "	<td>: ".$murid["KDT"]." / $bilting</td>\n";
		echo "    <td>Peratus</td>\n";
		echo "    <td>: ".$murid["PERATUS"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>&nbsp;&nbsp;&nbsp;Kehadiran</td>\n";
		echo "    <td>: ".$murid["KEHADIRAN"]." / ".$murid["KEHADIRANPENUH"]." Hari</td>\n";
		echo "    <td>Gred Purata Pelajar </td>\n";
		echo "    <td>: ".$murid["GPC"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>&nbsp;&nbsp;&nbsp;Pencapaian Gred &nbsp;&nbsp;&nbsp;Keseluruhan </td>\n";
		echo "    <td>: ".$murid["PENCAPAIAN"]."</td>\n";
		echo "    <td>Keputusan</td>\n";
		echo "    <td>: ".$murid["KEPUTUSAN"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		//echo "<br>\n";
		echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		
		
		$panggil = "SELECT ulasan FROM markah_pelajar WHERE nokp='".$murid["NOKP"]."' AND tahun='$tahun' AND kodsek='$kodsek' AND kelas='$kelas' AND jpep='$jpep'";
		
		//echo $panggil;
		$resx = oci_parse($conn_sispa,$panggil);
		oci_execute($resx);
		$ulasanx = oci_fetch_array($resx);
		$ulas = $ulasanx['ULASAN'];
		$kehadiran = $ulasanx['KEHADIRAN'];
		
		echo "  <tr>\n";
		echo "    <td>Ulasan Guru Kelas :<br /> $ulas </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br>";
		//echo "<br><br><br><br><br><p>\n";
		//echo "<br><br><br><br><br><p>\n";
		echo "<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
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
		
		$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
oci_execute($q_guru);
$row_guru = oci_fetch_array($q_guru);

$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'";
//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
oci_execute($qgb);
$row_gb = oci_fetch_array($qgb);
		
		echo "  <tr>\n";
		echo "    <td valign='top'><div align=\"center\">........................................<br>(".$row_guru["NAMA"].") </div></td>\n";
		echo "    <td valign='top'><div align=\"center\">........................................<br />(".$row_gb['NAMA'].")</div></td>\n";
		echo "    <td valign='top'><div align=\"center\">........................................</div></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br><br>";
		if ( $bilpage <> 1 ) { echo "<p style=\"page-break-before: always\">\n"; }
		$bilpage--;
		//for ($i=0; $i<13-$bil ; $i++){ echo "<br>\n"; }
	}
}
else {
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";
		echo "<br><br>\n";
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
		echo "    <td width=\"550\">&nbsp;&nbsp;&nbsp;Nama : ".$rowmurid["NAMA"]."<br></td>\n";
		echo "    <td width=\"200\">Tingkatan : ".$rowmurid["TING"]." ".$rowmurid["KELAS"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>&nbsp;&nbsp;&nbsp;No. KP :  ".$rowmurid["NOKP"]."</td>\n";
		echo "    <td>Jantina : $jan[$jantina] </td>";
		echo "  </tr>";
		echo "</table>\n";
		echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td colspan=\"4\"><div align=\"center\"></div><div align=\"center\">\n";
		echo "      <hr align=\"center\" noshade>\n";
		echo "    </div></td>\n";
		echo "  </tr>";
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
		echo "  <tr>\n";
		echo "	<td>&nbsp;&nbsp;&nbsp;Kedudukan Dalam Tingkatan </td>\n";
		echo "	<td>: ".$murid["KDT"]." / $bilting</td>\n";
		echo "    <td>Peratus</td>\n";
		echo "    <td>: ".$rowmurid["PERATUS"]."</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td>&nbsp;&nbsp;&nbsp;Kehadiran</td>\n";
		echo "    <td>: $kehadiran </td>\n";
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
		echo "<br>\n";
		echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		
		
	$panggil = "SELECT ulasan FROM markah_pelajar WHERE nokp='".$murid["NOKP"]."' AND tahun='$tahun' AND kodsek='$kodsek' AND kelas='$kelas' AND jpep='$jpep'";

	
	//echo $panggil;
	$resx = oci_parse($conn_sispa,$panggil);
	oci_execute($resx);
	$ulasanx = oci_fetch_array($resx);
	$ulas = $ulasanx['ULASAN'];
	//echo $ulas;
	
		echo "  <tr>\n";
		echo "    <td>&nbsp;&nbsp;&nbsp;&nbsp;Ulasan Guru Kelas :  <br /> &nbsp;&nbsp;&nbsp;&nbsp;$ulas </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br><br><br><br><br><p>\n";
		echo "<br><br><br><br><br><p>\n";
		echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Guru Kelas </div></td>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Pengetua </div></td>\n";
		echo "    <td width=\"33%\"><div align=\"center\">Tandatangan Penjaga </div></td>\n";
		echo "  </tr>\n";
		
		
		$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
oci_execute($q_guru);
$row_guru = oci_fetch_array($q_guru);

$qgb = oci_parse($conn_sispa,"SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'");
//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='P'";
//echo "SELECT NAMA , LEVEL1 FROM Login WHERE kodsek='$kodsek' AND LEVEL1='p'";
oci_execute($qgb);
$row_gb = oci_fetch_array($qgb);


echo "    <td><div align=\"center\">........................................<br>(".$row_guru["NAMA"].") </div></td>\n";
echo "    <td><div align=\"center\">........................................<br>(".$row_gb["NAMA"].")</div></td>\n";
echo "    <td><div align=\"center\">........................................</div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
	 }


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