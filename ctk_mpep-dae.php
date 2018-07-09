<html>
<titel></title>
<head>
<link href="file://///SHUKRI-PC/sispa/include/kpm.css" type="text/css" rel="stylesheet" />
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
include 'config.php';
include 'fungsi.php';


$kodppd = $_POST['kodppd'];
$namappd = $_POST['namappd']; 
$kodsek = $_POST['kodsek'];
$namasek = $_POST['namasek'];
$JenisSekolah =  $_POST['JenisSekolah']; 
$status =  $_POST['status']; 
$mp =  $_POST['mp']; 
$bil = $_POST['bil']; 
$ting =  $_POST['ting']; 
$tahun =  $_POST['tahun']; 
$jpep = $_POST['jpep'];
$bilmurid = $_POST['bilmurid']; 
$bilamb = $_POST['bilamb']; 
$bilth = $_POST['bilth'];
$bilBMA = $_POST['bilBMA'];
$bilBMpc = $_POST['bilBMpc'];
$bilBMl = $_POST['bilBMl'];
$bilBMpl = $_POST['bilBMpl'];
$jumbilmurid = $_POST['jumbilmurid'];
$jumbilBMpl = $_POST['jumbilBMpl'];
$jumbilBMl = $_POST['jumbilBMl'];
$jumbilBMpc = $_POST['jumbilBMpc'];
$jumbilBMA = $_POST['jumbilBMA'];
$jumbilth = $_POST['jumbilth'];
$jumbilamb = $_POST['jumbilamb'];

function ting($ting){
	switch ($ting){
		case "T1":
		$tkt="TINGKATAN SATU";
		break;
		case "T2":
		$tkt="TINGKATAN DUA";
		break;
		case "T3":
		$tkt="TINGKATAN TIGA";
		break;
		case "T4":
		$tkt="TINGKATAN EMPAT";
		break;
		case "T5":
		$tkt="TINGKATAN LIMA";
		break;
		case "P":
		$tkt="PERALIHAN";
		break;
		case "D1":
		$npep="TAHUN SATU";
		break;
		case "D2":
		$tkt="TAHUN DUA";
		break;
		case "D3":
		$tkt="TAHUN TIGA";
		break;
		case "D4":
		$tkt="TAHUN EMPAT";
		break;
		case "D5":
		$tkt="TAHUN LIMA";
		break;
		case "D6":
		$tkt="TAHUN ENAM";
		break;
	}
return $tkt;
}

function pep($kodpep){
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

<form>
&nbsp;&nbsp;&nbsp;&nbsp;<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>

<?php
echo "<center>\n";
echo "<h3>ANALISIS PENCAPAIAN<BR> MATAPELAJARAN $mp<br>".jpep($jpep)." TAHUN $tahun";?> <br>DAERAH <?php echo "$namappd ($kodppd)<br></h3>";
echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"2\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "   <td width=\"3%\" rowspan=\"2\" align=\"center\">Bil</td>\n";
echo "   <td width=\"36%\" rowspan=\"2\" align=\"center\">Nama Sekolah</td>\n";
echo "    <td width=\"9%\" rowspan=\"2\" align=\"center\">Bilangan<br>Calon</td>\n";
echo "    <td width=\"9%\" rowspan=\"2\" align=\"center\">Bilangan<br>Ambil</td>\n";
echo "    <td width=\"9%\" rowspan=\"2\" align=\"center\">Bilangan<br>Tak Hadir</td>\n";
echo "    <td colspan=\"4\" align=\"center\">Bilangan Murid</td>\n";
echo "  </tr>\n";
echo " <tr>\n";
	echo " <td width=\"13%\" align=\"center\" valign=\"top\"><span class=\"style2\">Cemerlang<br>(75-100)</span></td>\n";
	echo " <td width=\"13%\" align=\"center\" valign=\"top\"><span class=\"style2\">Potensi Cemerlang<br>(65-74)</span></td>\n";
	echo " <td width=\"13%\" align=\"center\" valign=\"top\"><span class=\"style2\">Lulus<br>(30-64)</span></td>\n";
	echo " <td width=\"13%\" align=\"center\" valign=\"top\"><span class=\"style2\">Potensi Lulus<br>(21-39)</span></td>\n";
	echo "  </tr>\n";
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">";


$baris = 1 ;
for ( $i = 1; $i<= $bil; $i++)
{
	//echo "  $mp" ;
//	echo "  $namasek[$i]  |$kodsek[$i]  |JenisSekolah=$JenisSekolah[$i]  | jpep=$jpep[$i] | kodppd=$kodppd | status=$status | kodmp=$kodmp | bil=$bil | bilmurid=$bilmurid[$i] | bilBMA=$bilBMA[$i] | bilBMpc=$bilBMpc[$i] | bilBMl=$bilBMl[$i] | bilBMpl=$bilBMpl[$i]<br>";
	
	
echo "  <tr>\n";
//echo "  <td rowspan =\"$num[$i]\"><center>$num</center></td>";
//echo "  <tr>\n";

echo  "<td>&nbsp;".$baris."</td><td>".$namasek[$i]."&nbsp;</td><td><center>&nbsp;".$bilmurid[$i]."</td><td><center>".$bilamb[$i]."</center></td><td><center>".$bilth[$i]."</center></td><td><center>".$bilBMA[$i]."</center></td><td><center>".$bilBMpc[$i]."</center></td><td><center>".$bilBMl[$i]."</center></td><td><center>".$bilBMpl[$i]."</center></td>";
$baris++ ;
//echo  "<td>&nbsp;".$bil[$i]."</td><td>".$namasek[$i]."&nbsp;</td><td>&nbsp;".$bilmurid[$i]."</td><td><center>".$bilamb[$i]."</center></td><td><center>".$bilth[$i]."</center></td><td><center>".$bilBMA[$i]."</center></td><td><center>".$bilBMpc[$i]."</center></td><td><center>".$bilBMl[$i]."</center></td><td><center>".$bilBMpl[$i]."</center></td>";
//echo "<p STYLE='page-break-after: always'>\n";
echo "  </tr>\n";
}
echo "  <tr>\n";
	echo "    <td colspan=\"2\"><center>Jumlah</center></td>\n";
	echo "    <td><center>".number_format($jumbilmurid)."</center></td>\n";
	echo "    <td><center>".number_format($jumbilamb)."</center></td>\n";
	echo "    <td><center>".number_format($jumbilth)."</center></td>\n";
	echo "    <td><center>".number_format($jumbilBMA)."</center></td>\n";
	echo "    <td><center>".number_format($jumbilBMpc)."</center></td>\n";
	echo "    <td><center>".number_format($jumbilBMl)."</center></td>\n";
	echo "    <td><center>".number_format($jumbilBMpl)."</center></td>\n";
	echo "  </tr>\n";
	echo "   </table>\n";

?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>