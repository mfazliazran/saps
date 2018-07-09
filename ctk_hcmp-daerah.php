<html>
<body>
<STYLE type="text/css">
@media print {
  #mybutton { display:none; visibility:hidden; }

  #mybutton2 { display:none; visibility:hidden; }
 
}



</STYLE>

<style type="text/css">
.style1 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 16px;
	color: #000000;
 	font-weight: bold; 
}

.style2 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;
 
}

.style3 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;
 	font-weight: bold; 
}

</style>
<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>
<?php
include 'config.php';
include 'fungsi.php';
include 'input_validation.php';
session_start();

?>

<td valign="top" class="rightColumn">
<span class="style1">Analisis Headcount</span>
<?php

	$tahun = validate($_GET['tahun']);
	$kodmp = validate($_GET['kodmp']);
	$ting = validate($_GET['ting']);
	$status = validate($_GET['status']);
	
	$kodppd = validate($_SESSION["kodsek"]);
    $namappd = validate($_GET['namappd']);

	switch ($ting)
	{

		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$tahap="darjah";
			$headcount="headcountsr";
			$murid="tmuridsr";
			break;

		case "P": case "T1": case "T2": case "T3": 
			$tahap="ting";
			$level="MR";
			$headcount="headcount";
			$murid="tmurid";
			break;
			
		case "T4": case "T5":
			$tahap="ting";
			$headcount="headcount";
			$murid="tmurid";
			break;
	}

$bilsek=0;

  $sqlppd = "select ppd from tkppd where kodppd= :kodppd";
  $qryppd = oci_parse($conn_sispa,$sqlppd);
  oci_bind_by_name($qryppd, ':kodppd', $kodppd);
  oci_execute($qryppd);
  $rowppd = oci_fetch_array($qryppd);
  $kppd = $rowppd["PPD"];

  $sql3 = "select mp from mpsr where kod='$kodmp'";
  $qry3 = oci_parse($conn_sispa,$sql3);
  oci_bind_by_name($qry3, ':kodmp', $kodmp);
  oci_execute($qry3);
  $row3 = oci_fetch_array($qry3);
  $mp = $row3["MP"];

echo "<div align=\"center\"><span class=\"style3\">ANALISIS HEADCOUNT PELAJAR  ".tahap($ting)." ( $status )<BR>MATA PELAJARAN $mp<br>DAERAH $kppd ($kodppd) TAHUN $tahun</span><br><br>\n";

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<table width=\"90%\"  border=\"1\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\" bordercolor=\"#666666\">\n";
echo "<tr>\n";
echo "<td rowspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
echo "<td rowspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">Nama Sekolah </span></td>\n";
echo "<td rowspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">Bil Calon </span></td>\n";
echo "<td colspan=\"4\" align=\"center\" valign=\"top\"><span class=\"style3\">TOV</span></td>\n";
echo "<td colspan=\"4\" align=\"center\" valign=\"top\"><span class=\"style3\">Pertengahan Tahun </span></td>\n";
echo "<td colspan=\"4\" align=\"center\" valign=\"top\"><span class=\"style3\">Percubaan/PAT</span></td>\n";
echo "<td colspan=\"4\" align=\"center\" valign=\"top\"><span class=\"style3\">ETR</span></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">Lulus</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">Cemer</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">Lulus</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">Cemer</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">Lulus</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">Cemer</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">Lulus</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">Cemer</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
echo "  </tr>\n";

$qsek=oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status='$status' and kodppd='$kodppd'");
oci_bind_by_name($qsek, ':status', $status);
oci_bind_by_name($qsek, ':kodppd', $kodppd);
oci_execute($qsek);
$i=0;
  	while($rsek=oci_fetch_array($qsek)){

	   $ksek = $rsek["KODSEK"];
	   $labsek = $rsek["LABSEK"];
$bilsek=$bilsek+1;

echo "<tr>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$bilsek</span></td>\n";
echo "<td valign=\"top\"><span class=\"style2\">".$rsek["NAMASEK"]."</span></td>\n";


$sqlbilmurid="SELECT nokp FROM $headcount WHERE tahun= :tahun AND kodsek= :ksek AND $tahap= :ting AND hmp= :kodmp AND etr!=' '";
$parameter=array(":tahun",":ksek",":ting",":kodmp");
$value=array($tahun,$ksek,$ting,$kodmp);
$bilmurid = kira_bil_rekod($sqlbilmurid,$parameter,$value);

echo "    <td><center>$bilmurid</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
	
	$sqlbiltovL="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND (gtov='A+' OR gtov='A' OR gtov='A-' OR gtov='B+' OR gtov='B' OR gtov='C+' OR gtov='C' OR gtov='D' OR gtov='E')";
   
	}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	
	$sqlbiltovL="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND (gtov='A' OR gtov='B' OR gtov='C' OR gtov='D' OR gtov='E')";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	
	$sqlbiltovL="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND hmp= :kodmp AND (gtov='A' OR gtov='B' OR gtov='C' OR gtov='D')";
}

    $parameter=array(":tahun",":ksek",":ting",":kodmp");
    $value=array($tahun,$ksek,$ting,$kodmp);
    $biltovL = kira_bil_rekod($sqlbiltovL,$parameter,$value);
echo "    <td><center>$biltovL</center></td>\n";
if($bilmurid!=0) { $pertovL=number_format(($biltovL/$bilmurid)*100,'2','.',','); }
else { $pertovL=0.00; }
echo "    <td><center>$pertovL</center></td>\n";


if(($ting=="T4") OR ($ting=="T5")){
	
	$sqlbiltovC="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND (gtov='A+' OR gtov='A' OR gtov='A-')";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	
	$sqlbiltovC="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND gtov='A'";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	
	$sqlbiltovC="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND hmp= :kodmp AND gtov='A'";
}
    $parameter=array(":tahun",":ksek",":ting",":kodmp");
    $value=array($tahun,$ksek,$ting,$kodmp);
    $biltovC = kira_bil_rekod($sqlbiltovC,$parameter,$value);

echo "    <td><center>$biltovC</center></td>\n";
if($bilmurid!=0) { $pertovC=number_format(($biltovC/$bilmurid)*100,'2','.',','); }
else { $pertovC=0.00; }
echo "    <td><center>$pertovC</center></td>\n";


if(($ting=="T4") OR ($ting=="T5")){
	
	$sqlbilatr1L="SELECT * FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PPT' AND     (G:kodmp='A+' OR G:kodmp='A' OR G:kodmp='A-' OR G:kodmp='B+' OR G:kodmp='B-' OR G:kodmp='C+' OR G:kodmp='C-' OR G:kodmp='D' OR G:kodmp='E') ";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	
	$sqlbilatr1L="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PPT' AND (G:kodmp='A' OR G:kodmp='B' OR G:kodmp='C' OR G:kodmp='D' OR G:kodmp='E') ";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	
	$sqlbilatr1L="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='PPT' AND (G:kodmp='A' OR G:kodmp='B' OR G:kodmp='C' OR G:kodmp='D') ";
}
	
	$parameter=array(":tahun",":ksek",":ting",":kodmp");
    $value=array($tahun,$ksek,$ting,$kodmp);
    $bilatr1L = kira_bil_rekod($sqlbilatr1L,$parameter,$value);
	echo "    <td><center>$bilatr1L</center></td>\n";
	if($bilmurid!=0) { $peratr1L=number_format(($bilatr1L/$bilmurid)*100,'2','.',','); }
	else { $peratr1L=0.00; }
	echo "    <td><center>$peratr1L</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){

	$sqlbilatr1C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PPT' AND (G:kodmp='A+' OR G:kodmp='A' OR G:kodmp='A-')";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	
	$sqlbilatr1C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PPT' AND G:kodmp='A'";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	
	$sqlbilatr1C="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='PPT' AND G:kodmp='A'";
}

    $parameter=array(":tahun",":ksek",":ting",":kodmp");
    $value=array($tahun,$ksek,$ting,$kodmp);
    $bilatr1C = kira_bil_rekod($sqlbilatr1C,$parameter,$value);
echo "    <td><center>$bilatr1C</center></td>\n";
if($bilmurid!=0) { $peratr1C=number_format(($bilatr1C/$bilmurid)*100,'2','.',','); }
else { $peratr1C=0.00; }
echo "    <td><center>$peratr1C </center></td>\n";


if($ting=="T4"){
	
	$sqlbilatr2L="SELECT * FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PAT' AND (G:kodmp='A+' OR G:kodmp='A' OR G:kodmp='A-' OR G:kodmp='B+' OR G:kodmp='B-' OR G:kodmp='C+' OR G:kodmp='C-' OR G:kodmp='D' OR G:kodmp='E') ";
}
if($ting=="T5"){
	
	$sqlbilatr2L="SELECT * FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='SPMC' AND (G:kodmp='A+' OR G:kodmp='A' OR G:kodmp='A-' OR G:kodmp='B+' OR G:kodmp='B-' OR G:kodmp='C+' OR G:kodmp='C-' OR G:kodmp='D' OR G:kodmp='E') ";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	 
	$sqlbilatr2L="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PAT' AND (G:kodmp='A' OR G:kodmp='B' OR G:kodmp='C' OR G:kodmp='D' OR G:kodmp='E') ";
}

if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5")){
	
	$sqlbilatr2L="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='PAT' AND (G:kodmp='A' OR G:kodmp='B' OR G:kodmp='C' OR G:kodmp='D') ";
}
if($ting=="D6"){
	 
	$sqlbilatr2L="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='UPSRC' AND (G:kodmp='A' OR G:kodmp='B' OR G:kodmp='C' OR G:kodmp='D') ";
}

    $parameter=array(":tahun",":ksek",":ting",":kodmp");
    $value=array($tahun,$ksek,$ting,$kodmp);
    $bilatr2L = kira_bil_rekod($sqlbilatr2L,$parameter,$value);
echo "    <td><center>$bilatr2L</center></td>\n";
if($bilmurid!=0) { $peratr2L=number_format(($bilatr2L/$bilmurid)*100,'2','.',','); }
else { $peratr2L=0.00; }
echo "    <td><center>$peratr2L</center></td>\n";

if($ting=="T4"){
	
	$sqlbilatr2C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PAT' AND (G:kodmp='A+' OR G:kodmp='A' OR G:kodmp='A-')";
}
if($ting=="T5"){
	
	$sqlbilatr2C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='SPMC' AND (G:kodmp='A+' OR G:kodmp='A' OR G:kodmp='A-')";
}

if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	
	$sqlbilatr2C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PAT' AND G:kodmp='A'";
}


if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5")){
	
	$sqlbilatr2C="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='PAT' AND G:kodmp='A'"; 
}
if($ting=="D6"){
	
	$sqlbilatr2C="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='UPSRC' AND G:kodmp='A'"; 
}


    $parameter=array(":tahun",":ksek",":ting",":kodmp");
    $value=array($tahun,$ksek,$ting,$kodmp);
    $bilatr2C = kira_bil_rekod($sqlbilatr2C,$parameter,$value);
echo "    <td><center>$bilatr2C</center></td>\n";
if($bilmurid!=0) { $peratr2C=number_format(($bilatr2C/$bilmurid)*100,'2','.',','); }
else { $peratr2C=0.00; }
echo "    <td><center>$peratr2C </center></td>\n";


if(($ting=="T4") OR ($ting=="T5")){
	
	$sqlbiletrL="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND (getr='A+' OR getr='A' OR getr='A-' OR getr='B+' OR getr='B' OR getr='C+' OR getr='C' OR getr='D' OR getr='E') ";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	
	$sqlbiletrL="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND (getr='A' OR getr='B' OR getr='C' OR getr='D' OR getr='E') ";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	 
	$sqlbiletrL="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND hmp= :kodmp AND (getr='A' OR getr='B' OR getr='C' OR getr='D') ";
}

    $parameter=array(":tahun",":ksek",":ting",":kodmp");
    $value=array($tahun,$ksek,$ting,$kodmp);
    $biletrL = kira_bil_rekod($sqlbiletrL,$parameter,$value);
echo "    <td><center>$biletrL</center></td>\n";
if($bilmurid!=0) { $peretrL=number_format(($biletrL/$bilmurid)*100,'2','.',','); }
else { $peretrL=0.00; }
echo "    <td><center>$peretrL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
	
	$sqlbiletrC="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND (getr='A+' OR getr='A' OR getr='A-')";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	 
	$sqlbiletrC="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND getr='A'";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	
	$sqlbiletrC="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND hmp= :kodmp AND getr='A'";
}

    $parameter=array(":tahun",":ksek",":ting",":kodmp");
    $value=array($tahun,$ksek,$ting,$kodmp);
    $biletrC = kira_bil_rekod($sqlbiletrC,$parameter,$value);
echo "    <td><center>$biletrC</center></td>\n";
if($bilmurid!=0) { $peretrC=number_format(($biletrC/$bilmurid)*100,'2','.',','); }
else { $peretrC=0.00; }
echo "    <td><center>$peretrC </center></td>\n";
echo "  </tr>\n";

$jumbil=$jumbil+$bilmurid;
$jumbiltovL=$jumbiltovL+$biltovL;
$jumbiltovC=$jumbiltovC+$biltovC;
$jumbilatr1L=$jumbilatr1L+$bilatr1L;
$jumbilatr1C=$jumbilatr1C+$bilatr1C;
$jumbilatr2L=$jumbilatr2L+$bilatr2L;
$jumbilatr2C=$jumbilatr2C+$bilatr2C;
$jumbiletrL=$jumbiletrL+$biletrL;
$jumbiletrC=$jumbiletrC+$biletrC;

$jumpertovL=$jumpertovL+$pertovL;
$jumpertovC=$jumpertovC+$pertovC;
$jumperatr1L=$jumperatr1L+$peratr1L;
$jumperatr1C=$jumperatr1C+$peratr1C;
$jumperatr2L=$jumperatr2L+$peratr2L;
$jumperatr2C=$jumperatr2C+$peratr2C;
$jumperetrL=$jumperetrL+$peretrL;
$jumperetrC=$jumperetrC+$peretrC;
$i=$i+1;


}


if ( $jumbil != 0 )
{
	$peratusjumbiltovL = number_format(($jumbiltovL/$jumbil*100),2,'.',',');
	$peratusjumbiltovC = number_format(($jumbiltovC/$jumbil*100),2,'.',',');
	$peratusjumbilatr1L = number_format(($jumbilatr1L/$jumbil*100),2,'.',',');
	$peratusjumbilatr1C = number_format(($jumbilatr1C/$jumbil*100),2,'.',',');
	$peratusjumbiletrL = number_format(($jumbiletrL/$jumbil*100),2,'.',',');
	$peratusjumbiletrC = number_format(($jumbiletrC/$jumbil*100),2,'.',',');
	$peratusjumbilatr2L = number_format(($jumbilatr2L/$jumbil*100),2,'.',',');
	$peratusjumbilatr2C = number_format(($jumbilatr2C/$jumbil*100),2,'.',',');
	
}
else {
		$peratusjumtovL = $peratusjumbiltovL = $peratusjumbilatr1L  = $peratusjumbiletrL = $peratusjumbilatr2L =  0.00;
		$peratusjumtovC = $peratusjumbiltovC = $peratusjumbilatr1C = $peratusjumbiletrC  = $peratusjumbilatr2C =  0.00;
	 }



echo "<tr>\n";
echo "<td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style2\">Jumlah</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbil</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbiltovL</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumbiltovL</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbiltovC</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumbiltovC</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbilatr1L</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumbilatr1L</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbilatr1C</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumbilatr1C</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbilatr2L</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumbilatr2L</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbilatr2C</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumbilatr2C</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbiletrL</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumbiletrL</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbiletrC</span></td>\n";
echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumbiletrC</span></td>\n";
echo "  </tr>\n";
echo "</table>\n";

?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>
