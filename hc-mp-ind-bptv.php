<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Headcount Individu (BPTV)</p>
<?php

$datahc = $_GET['datahc'];
list ($kodsek, $tahun ,$ting, $mp, $kelas)=split('[/]', $datahc); 

switch ($_SESSION['statussek'])
{
	case "SM" :
		//$level="MR";
		//$theadcount="headcount";
		$tmarkah = "markah_pelajar";
		//$tmatap="mpsmkc";
		$tajuk="TINGKATAN";
		//$tahap="ting";
		break;
}

$gmp = "G$mp";
$gdata = "SELECT * FROM tsekolah WHERE kodsek='$kodsek' and kodjenissekolah in ('203','303')";
$rdata= oci_parse($conn_sispa,$gdata);
oci_execute($rdata);
$resultdata = oci_fetch_array($rdata);
$namasek=$resultdata['NAMASEK'];

$qmp = "SELECT * FROM mpsmkc WHERE kod='$mp'";
$rmp= oci_parse($conn_sispa,$qmp);
oci_execute($rmp);
$resultmp = oci_fetch_array($rmp);
$tempmp=$resultmp['MP'];

$qryatr = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='$tahun' ORDER BY capai");
//echo "SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' ORDER BY capai";
oci_execute($qryatr);
while ($rowatr = oci_fetch_array($qryatr))
{
	switch ($rowatr[CAPAI])
	{
		case "ATR1" : 
			$jpepatr1=$rowatr['JENPEP']; $tahunatr1=$rowatr['TAHUNTOV']; $tingatr1=$rowatr['TINGTOV'];
			break;
		case "ATR2" : 
			$jpepatr2=$rowatr['JENPEP']; $tahunatr2=$rowatr['TAHUNTOV']; $tingatr2=$rowatr['TINGTOV'];
			break;
		case "ATR3" : 
			$jpepatr3=$rowatr['JENPEP']; $tahunatr3=$rowatr['TAHUNTOV']; $tingatr3=$rowatr['TINGTOV'];
			break;
	}
}

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<H2><center>ANALISIS PENCAPAIAN HEADCOUNT MATA PELAJARAN<br>TAHUN $tahun</center></H2>\n";
echo " <br>";
echo "  <table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo " <td>SEKOLAH : $namasek</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo " <td>TING : $ting  $kelas</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo " <td>MATA PELAJARAN : $tempmp</td>\n";
echo "  </tr>\n";
echo "</table>";
echo " <br>";
echo "  <table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo "    <td rowspan=\"2\">Bil\n";
echo "    <div align=\"center\"></div></td>\n";
echo "    <td rowspan=\"2\">Nama </td>\n";
echo "    <div align=\"center\"></div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">TOV</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">OTR1</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">AR1</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">OTR2</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">AR2</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">OTR3</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">AR3</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">ETR</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";

echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">GRED</div></td>\n";

echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">GRED</div></td>\n";

echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">GRED</div></td>\n";

echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">GRED</div></td>\n";

echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">GRED</div></td>\n";

echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">GRED</div></td>\n";

echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">GRED</div></td>\n";

echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">GRED</div></td>\n";
echo "  </tr>\n";

$qting = "SELECT * FROM headcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND hmp='$mp' AND kelas='$kelas' ORDER BY nama";
//echo "SELECT * FROM headcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND hmp='$mp' AND kelas='$kelas' ORDER BY nama";
$rting = oci_parse($conn_sispa,$qting);
oci_execute($rting);

while ($record = oci_fetch_array($rting)){
	$nama = $record['NAMA'];
	$nokp = $record['NOKP'];			
	$q_atr1 = oci_parse($conn_sispa,"SELECT $mp, $gmp FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahunatr1' AND kodsek='$kodsek' AND ting='$tingatr1' AND kelas='$kelas' AND jpep='$jpepatr1'");
	oci_execute($q_atr1);
	$rowatr1 = oci_fetch_array($q_atr1);
	$q_atr2 = oci_parse($conn_sispa,"SELECT $mp, $gmp FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahunatr2' AND kodsek='$kodsek' AND ting='$tingatr2' AND kelas='$kelas' AND jpep='$jpepatr2'");
	oci_execute($q_atr2);
	$rowatr2 = oci_fetch_array($q_atr2);
	$q_atr3 = oci_parse($conn_sispa,"SELECT $mp, $gmp FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahunatr3' AND kodsek='$kodsek' AND ting='$tingatr3' AND kelas='$kelas' AND jpep='$jpepatr3'"); 
	oci_execute($q_atr3);
	$rowatr3 = oci_fetch_array($q_atr3);
	
	$bil=$bil+1;
	echo "  <tr>\n";
	echo "    <td><div align=\"center\">$bil</div></td>\n";
	echo "    <td><div align=\"left\">$nama</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['TOV']."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['GTOV']."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['OTR1']."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['GOTR1']."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$rowatr1["$mp"]."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$rowatr1["$gmp"]."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['OTR2']."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['GOTR2']."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$rowatr2["$mp"]."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$rowatr2["$gmp"]."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['OTR3']."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['GOTR3']."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$rowatr3["$mp"]."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$rowatr3["$gmp"]."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['ETR']."</div></td>\n";
	echo "    <td><div align=\"center\">&nbsp;".$record['GETR']."</div></td>\n";
	echo "  </tr>\n";
}
echo "</table>\n";
echo " <br><br><br><br><br>";

?>
</td>
<?php include 'kaki.php';?>                                                           
                                                           