<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>
<?php
session_start();
include 'auth.php';
include 'config.php';
?>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<?php

$m=$_GET['data'];
//list ($ting, $kod, $tahun, $kodsek)=split('[/]', $m);
list ($ting, $kod)=split('[|]', $m);
$kodsek = $_SESSION["kodsek2"];
$tahun = $_SESSION['tahun'];

switch ($_SESSION['statusseksbt'])

{

	case "SR":
		$tmarkah = "markah_pelajarsr";
		$theadcount="headcountsr";
		$tmatap="mpsr";
		$tmurid="tmuridsr";
		$tajuk="DARJAH";
		$tahap="darjah";
		break;

	case "SM" :
		$tmarkah = "markah_pelajar";
		$theadcount="headcount";
		$tmatap="mpsmkc";
		$tmurid="tmurid";
		$tajuk="TINGKATAN";
		$tahap="ting";
		break;
}

$querykod = oci_parse($conn_sispa,"SELECT * FROM $tmatap, tsekolah WHERE $tmatap.kod='$kod' AND tsekolah.kodsek='$kodsek'");
oci_execute($querykod);
$resultkod = oci_fetch_array($querykod);
$namamp=$resultkod['MP'];
$namasek=$resultkod['NAMASEK'];

//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"include/kpm.css\">";
echo "<H2><center>HEADCOUNT MATA PELAJARAN $ting<br>$namasek<br>TAHUN $tahun</center></H2>\n";
echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "<tr><td><b>SEKOLAH : $namasek</b></td></tr>\n";
echo "<tr><td><b>MATA PELAJARAN : $namamp</b></td></tr>\n";
//echo "<tr><td><INPUT TYPE=\"BUTTON\" VALUE=\"<< KEMBALI\" ONCLICK=\"history.go(-1)\"></td></tr>\n";
echo "</table>";
echo "<table  width=\"98%\"  align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo "    <td rowspan=\"2\"> <div align=\"center\"><b>BIL</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\"><b>KELAS</b></div></td>\n";

echo "    <td rowspan=\"2\"><div align=\"center\"><b>BIL<br>DAFTAR</b></div></td>\n";

echo "    <td colspan=\"7\"><div align=\"center\"><b>TOV</b></div></td>\n";

echo "    <td colspan=\"7\"><div align=\"center\"><b>ETR</b></div></td>\n";

echo "  </tr>\n";
//////////////////////////  Table TOV  //////////////////////////////////////
echo "  <tr>\n";

echo "    <td><div align=\"center\"><b>BIL A</b></div></td>\n";

echo "    <td><div align=\"center\"><b>% A</b></div></td>\n";

echo "    <td><div align=\"center\"><b>BIL<br>LULUS</b></div></td>\n";

echo "    <td><div align=\"center\"><b>%<br>LULUS</b></div></td>\n";

echo "    <td><div align=\"center\"><b>BIL<br>GAGAL</b></div></td>\n";

echo "    <td><div align=\"center\"><b>%<br>GAGAL</b></div></td>\n";
echo "    <td><div align=\"center\"><b>BIL<br>TH</b></div></td>\n";
//////////////////////////////////////////////////////////////////////////////
/////////////////////// Table ETR ////////////////////////////////////////////
echo "    <td><div align=\"center\"><b>BIL A</b></div></td>\n";

echo "    <td><div align=\"center\"><b>% A</b></div></td>\n";

echo "    <td><div align=\"center\"><b>BIL<br>LULUS</b></div></td>\n";

echo "    <td><div align=\"center\"><b>%<br>LULUS</b></div></td>\n";

echo "    <td><div align=\"center\"><b>BIL<br>GAGAL</b></div></td>\n";

echo "    <td><div align=\"center\"><b>%<br>GAGAL</b></div></td>\n";
echo "    <td><div align=\"center\"><b>BIL<br>TH</b></div></td>\n";
echo "  </tr>\n";
////////////////////////////////////////////////////////////////////////
$jumcalon=$jumtovA=$jumetrA=$jumtovlulus=$jumetrlulus=$jumtovG=$jumetrG=$jumtovTH=$jumetrTH = 0;
$q_kelas = oci_parse($conn_sispa,"SELECT * FROM tkelassek WHERE tahun='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek2']."' AND ting='$ting' ORDER BY kelas");
oci_execute($q_kelas);
while ($rowkelas = oci_fetch_array($q_kelas))
{
	$kelas = $rowkelas['KELAS'];
	$qhcmurid = "SELECT * FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kod'";
	$stmt = oci_parse($conn_sispa,$qhcmurid);
	oci_execute($stmt);
	$bilcalon = count_row($qhcmurid);
	switch ($ting) {
		
		case "D1": case "d1": case "D2": case "d2": case "D3": case "d3": case "D4": case "d4": case "D5": case "d5": case "D6": case "d6":
		{
			$tovA = $tovL = $tovG = $tovTH = $etrA = $etrL = $etrG = $etrTH = 0 ;
			while ($record = oci_fetch_array($stmt))
			{
				switch ($record['GTOV'])
				{
					case 'A' : $tovA = $tovA + 1; break;
					case 'A' : case 'B' : case 'C' : $tovL = $tovL + 1; break;
					case 'D' : case 'E' : $tovG = $tovG + 1; break;
					case 'TH' : $tovTH = $tovTH + 1; break;
				}
				switch ($record['GETR'])
				{
					case 'A' : $etrA = $etrA + 1; break;
					case 'A' : case 'B' : case 'C' : case 'D' : $etrL = $etrL + 1; break;
					case 'E' : $etrG = $etrG + 1; break;
					case 'TH' : $etrTH = $etrTH + 1; break;
				}				
			}
		}
		break;
		
		case "P" : case "p" : case "T1": case "t1": case "T2": case "t2": case "T3": case "t3": 
		{
			$tovA = $tovL = $tovG = $tovTH = $etrA = $etrL = $etrG = $etrTH = 0 ;
			while ($record = oci_fetch_array($stmt))
			{
				switch ($record['GTOV'])
				{
					case 'A' : $tovA = $tovA + 1; break;
					case 'B' : case 'C' : case 'D' : $tovL = $tovL + 1; break;
					case 'E' : $tovG = $tovG + 1; break;
					case 'TH' : $tovTH = $tovTH + 1; break;
				}

				switch ($record['GETR'])
				{
					case 'A' : $etrA = $etrA + 1; break;
					case 'B' : case 'C' : case 'D' : $etrL = $etrL + 1; break;
					case 'E' : $etrG = $etrG + 1; break;
					case 'TH' : $etrTH = $etrTH + 1; break;
				}				
			}
		}
		break;

		case "T4": case "t4": case "T5": case "t5":
		{	
			$tovA = $tovL = $tovG = $tovTH = $etrA = $etrL = $etrG = $etrTH = 0;
			while ($record = oci_fetch_array($stmt))
			{
				switch ($record['GTOV'])
				{
					case 'A+' : case 'A' : case 'A-' : $tovA = $tovA + 1; break;
					case 'B+' : case 'B' : case 'C+' : case 'C' : case 'D' : case 'E' : $tovL = $tovL + 1; break;
					case 'G' : $tovG = $tovG + 1; break;
					case 'TH' : $tovTH = $tovTH + 1; break;
				}

				switch ($record['GETR'])
				{
					case 'A+' : case 'A' : case 'A-' : $etrA = $etrA + 1; break;
					case 'B+' : case 'B' : case 'C+' : case 'C' : case 'D' : case 'E' :  $etrL = $etrL + 1; break;
					case 'G' : $etrG = $etrG + 1; break;
					case 'TH' : $etrTH = $etrTH + 1; break;
				}										
			}
		}
		break;
	}
	$bil=$bil+1;
	$biltovlulus = $tovA + $tovL ;
	$biletrlulus = $etrA + $etrL ;

	$jumtovA = $jumtovA + $tovA;
	$jumtovlulus = $jumtovlulus + $biltovlulus;
	$jumtovG = $jumtovG + $tovG;
	$jumtovTH = $jumtovTH + $tovTH;
	$jumetrA = $jumetrA + $etrA;
	$jumetrlulus = $jumetrlulus + $biletrlulus;
	$jumetrG = $jumetrG + $etrG;
	$jumetrTH = $jumetrTH + $etrTH;
	$jumcalon = $jumcalon + $bilcalon;
	
	if ($bilcalon != 0){
		$peratustovA = number_format(($tovA/($bilcalon - $tovTH))*100,2,'.',',');
		$peratustovlulus = number_format(($biltovlulus/($bilcalon - $tovTH))*100,2,'.',',');
		$peratustovG = number_format(($tovG/($bilcalon - $tovTH))*100,2,'.',',');
		$peratusetrA = number_format(($etrA/($bilcalon - $etrTH))*100,2,'.',',');
		$peratusetrlulus = number_format(($biletrlulus/($bilcalon - $etrTH))*100,2,'.',',');
		$peratusetrG = number_format(($etrG/($bilcalon - $etrTH))*100,2,'.',',');	
	} else { $peratustovA = $peratustovlulus = $peratustovG = $peratusetrA = $peratusetrlulus = $peratusetrG = 0.00 ; }

	echo "  <tr>\n";
	echo "    <td><div align=\"center\">$bil</div></td>\n";
	echo "    <td><div align=\"center\">$kelas</div></td>\n";
	echo "    <td><div align=\"center\">$bilcalon</div></td>\n";
	echo "    <td><div align=\"center\">$tovA</div></td>\n";
	echo "    <td><div align=\"center\">$peratustovA</div></td>\n";
	echo "    <td><div align=\"center\">$biltovlulus</div></td>\n";
	echo "    <td><div align=\"center\">$peratustovlulus</div></td>\n";
	echo "    <td><div align=\"center\">$tovG</div></td>\n";
	echo "    <td><div align=\"center\">$peratustovG</div></td>\n";
	echo "    <td><div align=\"center\">$tovTH</div></td>\n";
	echo "    <td><div align=\"center\">$etrA</div></td>\n";
	echo "    <td><div align=\"center\">$peratusetrA</div></td>\n";
	echo "    <td><div align=\"center\">$biletrlulus</div></td>\n";
	echo "    <td><div align=\"center\">$peratusetrlulus</div></td>\n";
	echo "    <td><div align=\"center\">$etrG</div></td>\n";
	echo "    <td><div align=\"center\">$peratusetrG</div></td>\n";
	echo "    <td><div align=\"center\">$etrTH</div></td>\n";
	echo "  </tr>\n";
}

if ($jumcalon != 0){
	$pjumtovA = number_format(($jumtovA/($jumcalon - $jumtovTH))*100,2,'.',',');
	$pjumtovlulus = number_format(($jumtovlulus/($jumcalon - $jumtovTH))*100,2,'.',',');
	$pjumtovG = number_format(($jumtovG/($jumcalon - $jumtovTH))*100,2,'.',',');
	$pjumetrA = number_format(($jumetrA/($jumcalon - $jumetrTH))*100,2,'.',',');
	$pjumetrlulus = number_format(($jumetrlulus/($jumcalon - $jumetrTH))*100,2,'.',',');
	$pjumetrG = number_format(($jumetrG/($jumcalon - $jumetrTH))*100,2,'.',',');	
} else { $pjumtovA = $pjumtovlulus = $pjumtovG = $pjumetrA = $pjumetrlulus = $pjumetrG = 0.00 ; }

echo "  <tr>\n";
echo "    <td colspan=\"2\"> <div align=\"center\"><b>JUMLAH</div></td>\n";
echo "    <td><div align=\"center\"><b>$jumcalon</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$jumtovA</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$pjumtovA</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$jumtovlulus</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$pjumtovlulus</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$jumtovG</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$pjumtovG</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$jumtovTH</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$jumetrA</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$pjumetrA</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$jumetrlulus</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$pjumetrlulus</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$jumetrG</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$pjumetrG</b></div></td>\n";
echo "    <td><div align=\"center\"><b>$jumetrTH</b></div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo " <br><br><br><br>";
?>