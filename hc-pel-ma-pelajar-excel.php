<?php 
session_start();
include 'auth.php';
include 'config.php';
//include 'kepala.php';
//include 'menu.php';


$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$mp = $_GET['kodmp'];
$gmp = "G$mp";
$namasek = $_GET['namasek'];
$tahun = $_SESSION['tahun'];
$kodsek = $_SESSION['kodsek'];
switch ($ting)
{
	case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
		$level="SR";
		$theadcount="headcountsr";
		$tmarkah="markah_pelajarsr";
		$tajuk="DARJAH";
		$tmatap="mpsr";
		$tahap="DARJAH";
		break;

	case "P" : case "T1": case "T2": case "T3":
		$level="MR";
		$theadcount="headcount";
		$tmarkah="markah_pelajar";
		$tajuk="TINGKATAN";
		$tmatap="mpsmkc";
		$tahap="TING";
		break;

	case "T4": case "T5":
		$level="MA";
		$theadcount="headcount";
		$tmarkah="markah_pelajar";
		$tajuk="TINGKATAN";
		$tmatap="mpsmkc";
		$tahap="TING";		
		break;

}

$qnamamp = OCIParse($conn_sispa,"SELECT * FROM $tmatap WHERE kod='$mp'");
		OCIExecute($qnamamp);
		OCIFetch($qnamamp); //$nmp
		$namamp=OCIResult($qnamamp,"MP");//$nmp['mp'];

   header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="pencapaian_hc_matapelajaran_pelajar_'.$tahun.'.xls"');
   echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>PENCAPAIAN HEADCOUNT MATA PELAJARAN PELAJAR</TITLE>";
   echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
   echo "<body>";

?>

<html>
<titel></title>
<head>
<!--<link href="include/kpm.css" type="text/css" rel="stylesheet" />-->
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

			//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
			echo "<H2><center>PENCAPAIAN HEADCOUNT MATA PELAJARAN PELAJAR<br>TAHUN $tahun</center></H2>\n";
			echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#999999\"><tr>\n";
			echo " <td><b>SEKOLAH :</b> $namasek</td></tr>\n";
			echo " <td><b>$tajuk :</b> $ting $kelas</td></tr>\n";
			echo " <td><b>MATA PELAJARAN :</b> $namamp</td>\n";
			echo "</table>";
			echo " <br>";
			
			//////////////////////  JADUAL PENCAPAIAN HEADCOUNT MP PELAJAR MENENGAH RENDAH & ATAS/SEKOLAH RENDAH  ////////////////////////////////////////////////
			echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\"><tr>\n";
			echo "    <td width=\"1%\" rowspan=\"2\">BIL</td>\n";
			echo "    <td width=\"20%\" rowspan=\"2\"><div align=\"center\">NAMA PELAJAR</div></td>\n";
			echo "    <td width=\"10%\" rowspan=\"2\"><div align=\"center\">NO.KP</div></td>\n";	
			echo "    <td width=\"4%\" rowspan=\"2\"><div align=\"center\"><b>Nilai<br>Tambah</b></div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\"><b>TOV</b></div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\"><b>OTR 1</b></div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\"><b>AR 1</b></div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\"><b>OTR 2</b></div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\"><b>AR 2</b></div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\"><b>OTR 3</b></div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\"><b>AR 3</b></div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\"><b>ETR</b></div></td>\n";
			echo "  <tr><td width=\"3%\"><div align=\"center\">MKH</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">GRD</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">MKH</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">GRD</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">MKH</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">GRD</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">MKH</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">GRD</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">MKH</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">GRD</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">MKH</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">GRD</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">MKH</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">GRD</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">MKH</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">GRD</div></td>\n";
		
			$qbcalon = oci_parse($conn_sispa,"SELECT * FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$mp' order by nama");
			oci_execute($qbcalon);
			while($rowcalon = oci_fetch_array($qbcalon)){
				
			$bilcalon++;
			$nokpcalon = $rowcalon["NOKP"];
			$namacalon = $rowcalon["NAMA"];
			$TOVcalon = $rowcalon["TOV"];
			$GTOVcalon = $rowcalon["GTOV"];
			$NTcalon = $rowcalon["NT"];
			$OTR1calon = $rowcalon["OTR1"];
			$GOTR1calon = $rowcalon["GOTR1"];
			//$ATR1calon = $rowcalon["ATR1"];
			//$GATR1calon = $rowcalon["GATR1"];
			$OTR2calon = $rowcalon["OTR2"];
			$GOTR2calon = $rowcalon["GOTR2"];
			$ATR2calon = $rowcalon["ATR2"];
			$GATR2calon = $rowcalon["GATR2"];
			$OTR3calon = $rowcalon["OTR3"];
			$GOTR3calon = $rowcalon["GOTR3"];
			$ATR3calon = $rowcalon["ATR3"];
			$GATR3calon = $rowcalon["GATR3"];
			$ETRcalon = $rowcalon["ETR"];
			$GETRcalon = $rowcalon["GETR"];
			
			/////////////////////////////////ATR1///////////////////////////////////////////
			$qrytentuhcatr1 = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='ATR1'");
			oci_execute($qrytentuhcatr1);
			$rowtentuatr1 = oci_fetch_array($qrytentuhcatr1);
			
			$jpepatr1 = $rowtentuatr1['JENPEP']; 
			$tahunatr1 = $rowtentuatr1['TAHUNTOV']; 
			$tingatr1 = $rowtentuatr1['TINGTOV'];
			

			$qryatr1 = oci_parse($conn_sispa,"SELECT $mp,G$mp FROM $tmarkah WHERE nokp='$nokpcalon' AND kodsek='".$_SESSION['kodsek']."' AND $tahap='$tingatr1' AND kelas='$kelas' AND jpep='$jpepatr1' AND tahun='$tahunatr1' AND $mp IS NOT NULL");
			oci_execute($qryatr1);
			$rowatr1 = oci_fetch_array($qryatr1);
			
			$AR1calon = $rowatr1["$mp"]; 
			$GAR1calon = $rowatr1["G$mp"];
			
			/////////////////////////////////ATR2///////////////////////////////////////////
			$qrytentuhcatr2 = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='ATR2'");
			oci_execute($qrytentuhcatr2);
			$rowtentuatr2 = oci_fetch_array($qrytentuhcatr2);
			
			$jpepatr2 = $rowtentuatr2['JENPEP']; 
			$tahunatr2 = $rowtentuatr2['TAHUNTOV']; 
			$tingatr2 = $rowtentuatr2['TINGTOV'];
			

			$qryatr2 = oci_parse($conn_sispa,"SELECT $mp,G$mp FROM $tmarkah WHERE nokp='$nokpcalon' AND kodsek='".$_SESSION['kodsek']."' AND $tahap='$tingatr2' AND kelas='$kelas' AND jpep='$jpepatr2' AND tahun='$tahunatr2' AND $mp IS NOT NULL");
			oci_execute($qryatr2);
			$rowatr2 = oci_fetch_array($qryatr2);
			
			$AR2calon = $rowatr2["$mp"]; 
			$GAR2calon = $rowatr2["G$mp"];
			
			/////////////////////////////////ATR3///////////////////////////////////////////
			$qrytentuhcatr3 = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='ATR3'");
			oci_execute($qrytentuhcatr2);
			$rowtentuatr3 = oci_fetch_array($qrytentuhcatr2);
			
			$jpepatr3 = $rowtentuatr3['JENPEP']; 
			$tahunatr3 = $rowtentuatr3['TAHUNTOV']; 
			$tingatr3 = $rowtentuatr3['TINGTOV'];
			

			$qryatr3 = oci_parse($conn_sispa,"SELECT $mp,G$mp FROM $tmarkah WHERE nokp='$nokpcalon' AND kodsek='".$_SESSION['kodsek']."' AND $tahap='$tingatr3' AND kelas='$kelas' AND jpep='$jpepatr3' AND tahun='$tahunatr3' AND $mp IS NOT NULL");
			oci_execute($qryatr3);
			$rowatr3 = oci_fetch_array($qryatr3);
			
			$AR3calon = $rowatr3["$mp"]; 
			$GAR3calon = $rowatr3["G$mp"];
			
			//if($kodsek='BEA4613')
				//echo $qryatr1."<br>1. $AR1calon<br>2. $GAR1calon<br>";
				$bil=$bilcalon+1;
			if($bil&1) {
				$bcol = "#CDCDCD";
			} else {
				$bcol = "";
			}
			echo "  <tr bgcolor='$bcol'>\n";
			echo "  <td>$bilcalon</td>\n";
			echo "    <td><div align=\"center\">$namacalon</div></td>\n";
			echo "    <td><div align=\"center\">$nokpcalon</div></td>\n";
			echo "    <td><div align=\"center\"><b>$NTcalon</b></div></td>\n";
			if($level=="MA"){
				//echo"ATAS";
				if($GTOVcalon=="G" or $GTOVcalon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$TOVcalon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GTOVcalon</b></font></div></td>\n";
				} else{
				echo "    <td><div align=\"center\">$TOVcalon</div></td>\n";
				echo "    <td><div align=\"center\">$GTOVcalon</div></td>\n";
				}
				if($GOTR1calon=="G" or $GOTR1calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$OTR1calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GOTR1calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$OTR1calon</div></td>\n";
				echo "    <td><div align=\"center\">$GOTR1calon</div></td>\n";	
				}
				if($GAR1calon=="G" or $GAR1calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$AR1calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GAR1calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$AR1calon</div></td>\n";
				echo "    <td><div align=\"center\">$GAR1calon</div></td>\n";
				}
				if($GOTR2calon=="G" or $GOTR2calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$OTR2calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GOTR2calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$OTR2calon</div></td>\n";
				echo "    <td><div align=\"center\">$GOTR2calon</div></td>\n";
				}
				if($GAR2calon=="G" or $GAR2calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$AR2calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GAR2calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$AR2calon</div></td>\n";
				echo "    <td><div align=\"center\">$GAR2calon</div></td>\n";
				}
				if($GOTR3calon=="G" or $GOTR3calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$OTR3calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GOTR3calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$OTR3calon</div></td>\n";
				echo "    <td><div align=\"center\">$GOTR3calon</div></td>\n";
				}
				if($GAR3calon=="G" or $GAR3calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$AR3calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GAR3calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$AR3calon</div></td>\n";
				echo "    <td><div align=\"center\">$GAR3calon</div></td>\n";
				}
				if($ETRcalon=="G" or $ETRcalon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$ETRcalon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$ETRcalon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$ETRcalon</div></td>\n";
				echo "    <td><div align=\"center\">$GETRcalon</div></td></tr>\n";
				}
				
			}else {
				//echo"RENDAH";
				if($GTOVcalon=="E" or $GTOVcalon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$TOVcalon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GTOVcalon</b></font></div></td>\n";
				} else{
				echo "    <td><div align=\"center\">$TOVcalon</div></td>\n";
				echo "    <td><div align=\"center\">$GTOVcalon</div></td>\n";
				}
				if($GOTR1calon=='E' or $GOTR1calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$OTR1calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GOTR1calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$OTR1calon</div></td>\n";
				echo "    <td><div align=\"center\">$GOTR1calon</div></td>\n";	
				}
				if($GAR1calon=="E" or $GAR1calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$AR1calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GAR1calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$AR1calon</div></td>\n";
				echo "    <td><div align=\"center\">$GAR1calon</div></td>\n";
				}
				if($GOTR2calon=="E" or $GOTR2calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$OTR2calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GOTR2calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$OTR2calon</div></td>\n";
				echo "    <td><div align=\"center\">$GOTR2calon</div></td>\n";
				}
				if($GAR2calon=="E" or $GAR2calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$AR2calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GAR2calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$AR2calon</div></td>\n";
				echo "    <td><div align=\"center\">$GAR2calon</div></td>\n";
				}
				if($GOTR3calon=="E" or $GOTR3calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$OTR3calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GOTR3calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$OTR3calon</div></td>\n";
				echo "    <td><div align=\"center\">$GOTR3calon</div></td>\n";
				}
				if($GAR3calon=="E" or $GAR3calon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$AR3calon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$GAR3calon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$AR3calon</div></td>\n";
				echo "    <td><div align=\"center\">$GAR3calon</div></td>\n";
				}
				if($ETRcalon=="E" or $ETRcalon=="TH"){
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$ETRcalon</b></font></div></td>\n";
				echo "    <td><div align=\"center\"><font color=\"#FF0000\"><b>$ETRcalon</b></font></div></td>\n";
				}else{
				echo "    <td><div align=\"center\">$ETRcalon</div></td>\n";
				echo "    <td><div align=\"center\">$GETRcalon</div></td></tr>\n";
				}
			}
			echo "  </tr>\n";
			
			}//// end while rowcalon
		echo "</table>\n";


function semakbilkls($elem, $arraybilpel)
{
	foreach ($arraybilpel as  $key => $value)
	{
		if ($elem == $value["KELAS"]) { return $value["BIL"]; }
	}
}
?>

<?php
echo "</body>";
echo "</html>";

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
    case "UPSRC":
	$npep="PEPERIKSAAN PERCUBAAN UPSR";
	break;
    case "LNS01":
	$npep="SARINGAN LINUS KHAS 1";
	break;
}
return $npep;
}
 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?> 