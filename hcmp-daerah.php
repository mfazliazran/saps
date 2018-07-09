<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include 'input_validation.php';
set_time_limit(0);



function count_row_oci_by_name($sql,$val1,$val2,$val3,$val4,$param1,$param2,$param3,$param4){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	
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

function count_row_oci_by_name3($sql,$val1,$val2,$val3,$param1,$param2,$param3){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	
	$newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

	$qic = oci_parse($conn_sispa,$newsql);
	oci_bind_by_name($qic, $param1, $val1);
	oci_bind_by_name($qic, $param2, $val2);
	oci_bind_by_name($qic, $param3, $val3);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}


?>

<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Headcount</p>
<?php
if (isset($_POST['hc']))
{
	$tahun = validate($_POST['tahun']);
	$kodmp = validate($_POST['kodmp']);
	$ting = validate($_POST['ting']);
	$status = validate($_POST['statush']);
	$kodppd = validate($_POST['kodppd']);
?>
<div align=right><a href="ctk_hcmp-daerah.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&kodmp=<?php echo $kodmp;?>&&status=<?php echo $status;?>" target=_blank ><img src=printi.jpg border=0></a>&nbsp;&nbsp;&nbsp;</div>

<?php
	switch ($ting)
	{

		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$tahap="darjah";
			$status = "SR";
			$headcount="headcountsr";
			$murid="tmuridsr";
			break;

		case "P": case "T1": case "T2": case "T3": 
			$tahap="ting";
			$level="MR";
			$status = "MR";
			$headcount="headcount";
			$murid="tmurid";
			break;
			
		case "T4": case "T5":
			$tahap="ting";
			$status = "MA";
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

echo "<div align=\"center\">ANALISIS HEADCOUNT PELAJAR - $ting ( $status )<BR>MATA PELAJARAN $kodmp<br>DAERAH $kppd\n";

echo "<table width=\"95%\"  border=\"1\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\" bordercolor=\"#666666\">\n";
echo "  <tr>\n";
echo "    <td rowspan=\"2\"align=\"center\">Bil</td>\n";
echo "    <td rowspan=\"2\" align=\"center\">Nama Sekolah </td>\n";
echo "    <td rowspan=\"2\" align=\"center\">Bil Calon </td>\n";
echo "    <td colspan=\"4\" align=\"center\">TOV</td>\n";
echo "    <td colspan=\"4\" align=\"center\">Pertengahan Tahun </td>\n";
echo "    <td colspan=\"4\" align=\"center\">Percubaan/PAT</td>\n";
echo "    <td colspan=\"4\" align=\"center\">ETR</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td align=\"center\">Lulus</td>\n";
echo "    <td align=\"center\">%</td>\n";
echo "    <td align=\"center\">Cemerlang</td>\n";
echo "    <td align=\"center\">%</td>\n";
echo "    <td align=\"center\">Lulus</td>\n";
echo "    <td align=\"center\">%</td>\n";
echo "    <td align=\"center\">Cemerlang</td>\n";
echo "    <td align=\"center\">%</td>\n";
echo "    <td align=\"center\">Lulus</td>\n";
echo "    <td align=\"center\">%</td>\n";
echo "    <td align=\"center\">Cemerlang</td>\n";
echo "    <td align=\"center\">%</td>\n";
echo "    <td align=\"center\">Lulus</td>\n";
echo "    <td align=\"center\">%</td>\n";
echo "    <td align=\"center\">Cemerlang</td>\n";
echo "    <td align=\"center\">%</td>\n";
echo "  </tr>\n";
?>
<form action="jpn-jana1.php" method="POST" target=_self>

<?php
$qsek=oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status= :status and kodppd= :kodppd");
oci_bind_by_name($qsek, ':status', $status);
oci_bind_by_name($qsek, ':kodppd', $kodppd);
oci_execute($qsek);
$i=0;
  	while($rsek=oci_fetch_array($qsek)){

	   $ksek = $rsek["KODSEK"];
	   $labsek = $rsek["LABSEK"];
$bilsek=$bilsek+1;

echo "  <tr>\n";
echo "    <td>$bilsek</td>\n";
echo "    <td align=\"left\">".$rsek["NAMASEK"]."</td>\n";


$sqlbilmurid = "SELECT nokp FROM $headcount WHERE tahun= :tahun AND kodsek= :ksek AND $tahap= :ting AND hmp= :kodmp AND etr!=' '";
$bilmurid=count_row_oci_by_name($sqlbilmurid, $tahun, $ksek, $ting, $kodmp, ":tahun", ":ksek", ":ting", ":kodmp");

echo "    <td><center>$bilmurid</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
	
	$sqlbiltovL= "SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND (gtov='A+' OR gtov='A' OR gtov='A-' OR gtov='B+' OR gtov='B' OR gtov='C+' OR gtov='C' OR gtov='D' OR gtov='E')";
    
	}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	
	$sqlbiltovL="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND hmp= :kodmp AND (gtov='A' OR gtov='B' OR gtov='C' OR gtov='D' OR gtov='E')";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	
	$sqlbiltovL="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND hmp= :kodmp AND (gtov='A' OR gtov='B' OR gtov='C' OR gtov='D')";
}
$biltovL=count_row_oci_by_name($sqlbiltovL, $tahun, $ksek, $ting, $kodmp, ":tahun", ":ksek", ":ting", ":kodmp");

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
 $biltovC=count_row_oci_by_name($sqlbiltovC, $tahun, $ksek, $ting, $kodmp, ":tahun", ":ksek", ":ting", ":kodmp");

echo "    <td><center>$biltovC</center></td>\n";
if($bilmurid!=0) { $pertovC=number_format(($biltovC/$bilmurid)*100,'2','.',','); }
else { $pertovC=0.00; }
echo "    <td><center>$pertovC</center></td>\n";


if(($ting=="T4") OR ($ting=="T5")){
	
	$sqlbilatr1L="SELECT * FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PPT' AND     (G$kodmp='A+' OR G$kodmp='A' OR G$kodmp='A-' OR G$kodmp='B+' OR G$kodmp='B-' OR G$kodmp='C+' OR G$kodmp='C-' OR G$kodmp='D' OR G$kodmp='E') ";
   
	}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	
	$sqlbilatr1L="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PPT' AND (G$kodmp='A' OR G$kodmp='B' OR G$kodmp='C' OR G$kodmp='D' OR G$kodmp='E') ";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	 
	$sqlbilatr1L="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='PPT' AND (G$kodmp='A' OR G$kodmp='B' OR G$kodmp='C' OR G$kodmp='D') ";
}
 $bilatr1L=count_row_oci_by_name3($sqlbilatr1L, $tahun, $ksek, $ting, ":tahun", ":ksek", ":ting");
	
	echo "    <td><center>$bilatr1L</center></td>\n";
	if($bilmurid!=0) { $peratr1L=number_format(($bilatr1L/$bilmurid)*100,'2','.',','); }
	else { $peratr1L=0.00; }
	echo "    <td><center>$peratr1L</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
	 
	$sqlbilatr1C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PPT' AND (G$kodmp='A+' OR G$kodmp='A' OR G$kodmp='A-')";
   
	}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	 
	$sqlbilatr1C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PPT' AND G$kodmp='A'";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
	
	$sqlbilatr1C="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='PPT' AND G$kodmp='A'";
}
 $bilatr1C=count_row_oci_by_name3($sqlbilatr1C, $tahun, $ksek, $ting, ":tahun", ":ksek", ":ting");

echo "    <td><center>$bilatr1C</center></td>\n";
if($bilmurid!=0) { $peratr1C=number_format(($bilatr1C/$bilmurid)*100,'2','.',','); }
else { $peratr1C=0.00; }
echo "    <td><center>$peratr1C </center></td>\n";


if($ting=="T4"){
	 
	$sqlbilatr2L="SELECT * FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PAT' AND (G$kodmp='A+' OR G$kodmp='A' OR G$kodmp='A-' OR G$kodmp='B+' OR G$kodmp='B-' OR G$kodmp='C+' OR G$kodmp='C-' OR G$kodmp='D' OR G$kodmp='E') ";
   
	}
if($ting=="T5"){
	
	$sqlbilatr2L="SELECT * FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='SPMC' AND (G$kodmp='A+' OR G$kodmp='A' OR G$kodmp='A-' OR G$kodmp='B+' OR G$kodmp='B-' OR G$kodmp='C+' OR G$kodmp='C-' OR G$kodmp='D' OR G$kodmp='E') ";
    
	}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	 
	$sqlbilatr2L="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PAT' AND (G$kodmp='A' OR G$kodmp='B' OR G$kodmp='C' OR G$kodmp='D' OR G$kodmp='E') ";
}

if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5")){
	
	$sqlbilatr2L="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='PAT' AND (G$kodmp='A' OR G$kodmp='B' OR G$kodmp='C' OR G$kodmp='D') ";
}
if($ting=="D6"){
	 
	$sqlbilatr2L="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='UPSRC' AND (G$kodmp='A' OR G$kodmp='B' OR G$kodmp='C' OR G$kodmp='D') ";
}
 $bilatr2L=count_row_oci_by_name3($sqlbilatr2L, $tahun, $ksek, $ting, ":tahun", ":ksek", ":ting");

echo "    <td><center>$bilatr2L</center></td>\n";
if($bilmurid!=0) { $peratr2L=number_format(($bilatr2L/$bilmurid)*100,'2','.',','); }
else { $peratr2L=0.00; }
echo "    <td><center>$peratr2L</center></td>\n";

if($ting=="T4"){
	
	$sqlbilatr2C=count_row("SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PAT' AND (G$kodmp='A+' OR G$kodmp='A' OR G$kodmp='A-')");
   
	}
if($ting=="T5"){
	 
	$sqlbilatr2C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='SPMC' AND (G$kodmp='A+' OR G$kodmp='A' OR G$kodmp='A-')";
}

if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
	
	$sqlbilatr2C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek= :ksek AND ting= :ting AND jpep='PAT' AND G$kodmp='A'";
}


if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5")){
	
	$sqlbilatr2C="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek= :ksek AND darjah= :ting AND jpep='PAT' AND G$kodmp='A'"; 
}
if($ting=="D6"){
	 
	$sqlbilatr2C="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun' AND kodsek= :ksek AND darjah= :ting AND jpep='UPSRC' AND G$kodmp='A'"; 
}
 $bilatr2C=count_row_oci_by_name3($sqlbilatr2C, $tahun, $ksek, $ting, ":tahun", ":ksek", ":ting");


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
$biletrL=count_row_oci_by_name($sqlbiletrL, $tahun, $ksek, $ting, $kodmp, ":tahun", ":ksek", ":ting" ,":kodmp");

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
$biletrC=count_row_oci_by_name($sqlbiletrC, $tahun, $ksek, $ting, $kodmp, ":tahun", ":ksek", ":ting" ,":kodmp");

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

print "<input name=\"status\" type=\"hidden\" readonly value=\"$status\">";
print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
print "<input name=\"ksek[$i]\" type=\"hidden\" readonly value=\"$ksek\">";
print "<input name=\"kodmp\" type=\"hidden\" readonly value=\"$kodmp\">";
print "<input name=\"bilsek\" type=\"hidden\" readonly value=\"$bilsek\">";
print "<input name=\"ting\" type=\"hidden\" readonly value=\"$ting\">";
print "<input name=\"tahun\" type=\"hidden\" readonly value=\"$tahun\">";
print "<input name=\"labsek[$i]\" type=\"hidden\" readonly value=\"$labsek\">";
print "<input name=\"bilmurid[$i]\" type=\"hidden\" readonly value=\"$bilmurid\">";
print "<input name=\"biltovL[$i]\" type=\"hidden\" readonly value=\"$biltovL\">";
print "<input name=\"biltovC[$i]\" type=\"hidden\" readonly value=\"$biltovC\">";
print "<input name=\"bilatr1L[$i]\" type=\"hidden\" readonly value=\"$bilatr1L\">";
print "<input name=\"bilatr1C[$i]\" type=\"hidden\" readonly value=\"$bilatr1C\">";
print "<input name=\"bilatr2L[$i]\" type=\"hidden\" readonly value=\"$bilatr2L\">";
print "<input name=\"bilatr2C[$i]\" type=\"hidden\" readonly value=\"$bilatr2C\">";
print "<input name=\"biletrL[$i]\" type=\"hidden\" readonly value=\"$biletrL\">";
print "<input name=\"biletrC[$i]\" type=\"hidden\" readonly value=\"$biletrC\">";

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



echo "  <tr>\n";
echo "    <td colspan=\"2\"><div align=\"center\">Jumlah</div></td>\n";
echo "    <td><center>$jumbil</center></td>\n";
echo "    <td><center>$jumbiltovL</center></td>\n";
echo "    <td><center>$peratusjumbiltovL</center></td>\n";
echo "    <td><center>$jumbiltovC</center></td>\n";
echo "   <td><center>$peratusjumbiltovC</center></td>\n";
echo "    <td><center>$jumbilatr1L</center></td>\n";
echo "    <td><center>$peratusjumbilatr1L</center></td>\n";
echo "    <td><center>$jumbilatr1C</center></td>\n";
echo "    <td><center>$peratusjumbilatr1C</center></td>\n";
echo "    <td><center>$jumbilatr2L</center></td>\n";
echo "    <td><center>$peratusjumbilatr2L</center></td>\n";
echo "    <td><center>$jumbilatr2C</center></td>\n";
echo "    <td><center>$peratusjumbilatr2C</center></td>\n";
echo "    <td><center>$jumbiletrL</center></td>\n";
echo "    <td><center>$peratusjumbiletrL</center></td>\n";
echo "    <td><center>$jumbiletrC</center></td>\n";
echo "     <td><center>$peratusjumbiletrC</center></td>\n";
echo "  </tr>\n";
echo "</table>\n";

?>

<br>
<input type="submit" name="submit" value="HANTAR LAPORAN KE JPN">
</form>

<?php

} 
else { ?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='hcmp-daerah.php?status=' + val;
		}
		</script>
		
		<?php
		
		echo "<br><br><br><br>";
		echo " <center></b>ANALISIS HEADCOUNT PELAJAR MENGIKUT MP</b></center>";
		echo "<br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"500\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>TAHUN</td>\n";
		echo "  <td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";

		$status = $_GET['status'];
		if ($status=="")
		   $status="SR";
		   
		switch ($status)
		{
			case "MR" : $tahap = "MENENGAH RENDAH"; ; $tmp = "sub_mr"; break;
			case "MA" : $tahap = "MENENGAH ATAS"; $tmp = "mpsmkc"; break;
			
			case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "mpsr"; break;
			default : $tahap = "Pilih Tahap"; break;
		}	
		

		echo "<form method=post name='f1' action='hcmp-daerah.php'>";
		echo "<td>TAHAP</td><td><select name=\"status\" onchange=\"reload(this.form)\">";
		?>
		<option <?php if ($status=="MR") echo " selected "; ?> value="MR">MENENGAH RENDAH</option>
		<option <?php if ($status=="MA") echo " selected "; ?> value="MA">MENENGAH ATAS</option>
		<option <?php if ($status=="SR") echo " selected "; ?> value="SR">SEKOLAH RENDAH</option>
		<?php echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";

		$SQL_ting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM tkelassek ORDER BY ting");
		oci_execute($SQL_ting);
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);

		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN</td><td>";
		echo "<select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			case "MR" :	echo "<option value=\"P\">P</option>";
						echo "<option value=\"T1\">T1</option>";
						echo "<option value=\"T2\">T2</option>";
						echo "<option value=\"T3\">T3</option>";
						break;
						
			case "MA" : echo "<option value=\"T4\">T4</option>";
						echo "<option value=\"T5\">T5</option>";
						break;
						
			case "SR" :	echo "<option value=\"D1\">D1</option>";
						echo "<option value=\"D2\">D2</option>";
						echo "<option value=\"D3\">D3</option>";
						echo "<option value=\"D4\">D4</option>";
						echo "<option value=\"D5\">D5</option>";
						echo "<option value=\"D6\">D6</option>";
						break;
		}
		echo "</select>";
		echo "</td></tr>";

		
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
		echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) { 
			echo  "<option value='".$rowmp["KOD"]."'>".$rowmp["MP"]."</option>";
		}
		echo "</select>";
		echo "</td>";
		
		
		echo "</tr></table><br><br>";
         print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";	
		 echo "<center><input type='submit' name=\"hc\" value=\"Hantar\"></center>";

		echo "</form>";
} ?> 
</td>

<?php include 'kaki.php';?> 