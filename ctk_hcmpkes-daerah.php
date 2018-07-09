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

$tahun=validate($_GET['tahun']);
$ting=validate($_GET['ting']);
//$status=$_GET['status'];
//$kodppd=$_GET['kodppd'];
$kodppd=$_SESSION["kodsek"];
//$namappd=$_GET['namappd'];

$sqlppd = oci_parse($conn_sispa,"SELECT ppd FROM tkppd WHERE kodppd= :kodppd");
oci_bind_by_name($sqlppd, ':kodppd', $kodppd);
oci_execute($sqlppd);
$rowppd = oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];


switch ($ting)

	{

		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$tahap="darjah";
			$headcount="headcountsr";
			$gredL="E";
			$gredC="A";
			$murid="tmuridsr";
			$mp="mpsr";
			$jenis="TAHUN";
			$markah="markah_pelajarsr";
			break;

		case "P": case "T1": case "T2": case "T3": 
			$tahap="ting";
			$level="MR";
			$headcount="headcount";
			$gredL="E";
			$gredC="A";
			$murid="tmurid";
			$mp="mpsmkc";
			$jenis="TINGKATAN";
			$markah="markah_pelajar";
			break;
			
		case "T4": case "T5":
			$tahap="ting";
			$headcount="headcount";
			$gredL="9G";
			$gredC="1A";
			$murid="tmurid";
			$mp="mpsmkc";
			$jenis="TINGKATAN";
			$markah="markah_pelajar";
			break;
	}

echo "<div align=\"center\"><p>ANALISIS KESELURUHAN MATA PELAJARAN<br>".tahap($ting)."<br>DAERAH $namappd($kodppd) TAHUN $tahun</p>\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";

$sek=oci_parse($conn_sispa,"SELECT DISTINCT tsekolah.kodsek,tsekolah.namasek FROM sub_guru, tsekolah WHERE sub_guru.ting= :ting AND tsekolah.kodsek=sub_guru.kodsek AND tsekolah.kodppd= :kodppd");
oci_bind_by_name($sek, ':ting', $ting);
oci_bind_by_name($sek, ':kodppd', $kodppd);
//$sek=oci_parse($conn_sispa,"SELECT kodsek,namasek FROM tsekolah WHERE tsekolah.kodppd='$kodppd'");
//echo "SELECT DISTINCT sub_guru.kodmp, sub_guru.kodsek, tsekolah.kodppd FROM sub_guru, tsekolah WHERE sub_guru.ting='$ting' AND tsekolah.kodsek=sub_guru.kodsek AND tsekolah.kodppd='$kodppd' GROUP BY sub_guru.kodmp,sub_guru.kodsek,tsekolah.kodppd";
oci_execute($sek);
while($datasek=oci_fetch_array($sek)){
$kodsek=$datasek["KODSEK"];
$namasek=$datasek["NAMASEK"];
echo "<br>$kodsek $namasek";
echo "<table width=\"95%\"  border=\"1\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\" bordercolor=\"#666666\">\n";
echo "  <tr>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil</td>\n";
//echo "    <td rowspan=\"2\"><div align=\"center\">Sekolah</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Mata Pelajaran</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"4\"><div align=\"center\">TOV</div></td>\n";
echo "    <td colspan=\"4\"><div align=\"center\">Pertengahan Tahun </div></td>\n";
echo "    <td colspan=\"4\"><div align=\"center\">Percubaan </div></td>\n";
echo "    <td colspan=\"4\"><div align=\"center\">ETR</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemerlang</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemerlang</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemerlang</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemerlang</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "  </tr>\n";
	?>
<form action="jpn-allmp.php" method="POST" target=_self>

<?php

$qsek=oci_parse($conn_sispa,"SELECT DISTINCT sub_guru.kodmp, sub_guru.kodsek, tsekolah.kodppd FROM sub_guru, tsekolah WHERE sub_guru.ting= :ting AND tsekolah.kodsek=sub_guru.kodsek AND tsekolah.kodsek= :kodsek and tahun= :tahun GROUP BY sub_guru.kodmp,sub_guru.kodsek,tsekolah.kodppd ORDER BY sub_guru.kodsek");
oci_bind_by_name($qsek, ':ting', $ting);
oci_bind_by_name($qsek, ':kodsek', $kodsek);
oci_bind_by_name($qsek, ':tahun', $tahun);
//echo "SELECT DISTINCT sub_guru.kodmp, sub_guru.kodsek, tsekolah.kodppd FROM sub_guru, tsekolah WHERE sub_guru.ting='$ting' AND tsekolah.kodsek=sub_guru.kodsek AND tsekolah.kodppd='$kodppd' GROUP BY sub_guru.kodmp,sub_guru.kodsek,tsekolah.kodppd";
oci_execute($qsek);
$bilsek=0;
$jumbil=0;
$jumbiltovL=0;
$a=0;
$jumbiltovC=0;
$b=0;
$jumbilatr1L=0;
$c=0;
$jumbilatr1C=0;
$d=0;
$jumbilcubaL=0;
$g=0;
$jumbilcubaC=0;
$h=0;
$jumbiletrL=0;
$e=0;
$jumbiletrC=0;
$f=0;

while($rsek=oci_fetch_array($qsek)){
$kod=$rsek["KODMP"];
$kodsekolah=$rsek["KODSEK"];
	$bilsek=$bilsek+1;
			//echo "$bilsek | kod sek : $rsek[KODMP] | kod ppd : $rsek[KODPPD]<br>";
	echo "  <tr>\n";
	echo "    <td>$bilsek</td>\n";
	/*$qnmpi=oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$rsek[KODSEK]'");
	oci_execute($qnmpi);
	while($rowi=oci_fetch_array($qnmpi)){
	$namaseko=$rowi["NAMASEK"];
	}
	echo "    <td>$kodsekolah ($namaseko)</td>\n";
	*/
	$qnmp=oci_parse($conn_sispa,"SELECT * FROM $mp WHERE kod='$rsek[KODMP]'");
	oci_execute($qnmp);
	while($row=oci_fetch_array($qnmp)){
	echo "    <td align=left>".$row["MP"]."</td>\n";
	
	$qbilmurid= "SELECT nokp FROM $headcount WHERE tahun= :tahun AND $tahap= :ting AND hmp='$rsek[KODMP]' AND etr is not null AND kodsek='$rsek[KODSEK]'";
	
    
	//echo "SELECT nokp FROM $headcount WHERE tahun='$tahun' AND $tahap='$ting' AND hmp='$rsek[KODMP]' AND etr!='' AND kodsek='$rsek[KODSEK]'";
	

/*
$qbilmurid=mysql_query("SELECT a.nokp FROM $headcount a tsekolah b WHERE a.tahun='$tahun' AND $tahap='$ting' AND a.hmp='$rsek[kodmp]' AND a.etr!='' AND b.kodppd = '$kodppd' and a.kodsek = b.kodsek");	
*/

/*if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3") OR ($ting=="T4") OR ($ting=="T5")){
$qbilmurid=mysql_query("SELECT * FROM markah_pelajar WHERE tahun='$tahun' AND ting='$ting' AND BI !=''"); }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qbilmurid=mysql_query("SELECT * FROM markah_pelajarsr WHERE tahun='$tahun' AND darjah='$ting' AND BI !=''"); }
*/
$parameter=array(":tahun",":ting");
$value=array($tahun,$ting);
$bilmurid = kira_bil_rekod($qbilmurid,$parameter,$value);
echo "    <td><center>$bilmurid</center></td>\n";
////////////////////////////////////////////////////////////////////tov////////////////////////////////////////////////////////////////////////////////////
if(($ting=="T4") OR ($ting=="T5")){
$qtovL= "SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND kodsek='$rsek[KODSEK]' AND (gtov='A+' OR gtov='A' OR gtov='A-' OR gtov='B+' OR gtov='B' OR gtov='C+' OR gtov='C' OR gtov='D' OR gtov='E')";
 }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qtovL="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND kodsek='$rsek[KODSEK]' AND (gtov='A' OR gtov='B' OR gtov='C' OR gtov='D' OR gtov='E') ";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qtovL="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND darjah= :ting AND hmp='$rsek[KODMP]' AND kodsek='$rsek[KODSEK]' AND (gtov='A' OR gtov='B' OR gtov='C' OR gtov='D') ";
}
$parameter=array(":tahun",":ting");
$value=array($tahun,$ting);
$biltovL = kira_bil_rekod($qtovL,$parameter,$value);
echo "    <td><center>$biltovL</center></td>\n";
if($bilmurid!=0) { $pertovL=number_format(($biltovL/$bilmurid)*100,'2','.',','); }
else { $pertovL=0.00; }
echo "    <td><center>$pertovL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qtovC="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND kodsek='$rsek[KODSEK]' AND (gtov='A+' OR gtov='A' OR gtov='A+')";
 }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qtovC="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND kodsek='$rsek[KODSEK]' AND gtov='A'";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qtovC="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND darjah= :ting AND hmp='$rsek[KODMP]' AND kodsek='$rsek[KODSEK]' AND gtov='A'";
 }
$parameter=array(":tahun",":ting");
$value=array($tahun,$ting);
$biltovC = kira_bil_rekod($qtovC,$parameter,$value);
echo "    <td><center>$biltovC</center></td>\n";
if($bilmurid!=0) { $pertovC=number_format(($biltovC/$bilmurid)*100,'2','.',','); }
else { $pertovC=0.00; }
echo "    <td><center>$pertovC</center></td>\n";
/////////////////////////////////////////////////////////////////////////////////////ppt////////////////////////////////////////////////////////////////////////////////////////
if(($ting=="T4") OR ($ting=="T5")){
$qatr1L = "SELECT * FROM markah_pelajar WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND jpep='PPT' AND (G$rsek[KODMP]='A+' OR G$rsek[KODMP]='A' OR G$rsek[KODMP]='A-' OR G$rsek[KODMP]='B+' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C+' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D' OR G$rsek[KODMP]='E') ";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qatr1L="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND jpep='PPT' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D' OR G$rsek[KODMP]='E') ";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qatr1L="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND darjah= :ting AND jpep='PPT' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D') ";
}
$parameter=array(":tahun",":ting");
$value=array($tahun,$ting);
$bilatr1L = kira_bil_rekod($qatr1L,$parameter,$value);
echo "    <td><center>$bilatr1L</center></td>\n";
if($bilmurid!=0) { $peratr1L=number_format(($bilatr1L/$bilmurid)*100,'2','.',','); }
else { $peratr1L=0.00; }
echo "    <td><center>$peratr1L</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qatr1C = "SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND jpep='PPT' AND (G$rsek[KODMP]='A+' OR G$rsek[KODMP]='A' OR G$rsek[KODMP]='A-')";
 }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qatr1C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND jpep='PPT' AND G$rsek[KODMP]='A'";
 }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qatr1C="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND darjah= :ting AND jpep='PPT' AND G$rsek[KODMP]='A'";
 }
$parameter=array(":tahun",":ting");
$value=array($tahun,$ting);
$bilatr1C = kira_bil_rekod($qatr1C,$parameter,$value);
echo "    <td><center>$bilatr1C</center></td>\n";
if($bilmurid!=0) { $peratr1C=number_format(($bilatr1C/$bilmurid)*100,'2','.',','); }
else { $peratr1C=0.00; }
echo "    <td><center>$peratr1C </center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qcubaL="SELECT * FROM markah_pelajar WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND jpep='SPMC' AND (G$rsek[KODMP]='A+' OR G$rsek[KODMP]='A' OR G$rsek[KODMP]='A-' OR G$rsek[KODMP]='B+' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C+' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D' OR G$rsek[KODMP]='E')";
 }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qcubaL="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND jpep='PMRC' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D' OR G$rsek[KODMP]='E') ";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qcubaL="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND darjah= :ting AND jpep='UPSRC' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D') ";
 }
$parameter=array(":tahun",":ting");
$value=array($tahun,$ting);
$bilcubaL = kira_bil_rekod($qcubaL,$parameter,$value);
echo "    <td><center>$bilcubaL</center></td>\n";
if($bilmurid!=0) { $percubaL=number_format(($bilcubaL/$bilmurid)*100,'2','.',','); }
else { $percubaL=0.00; }
echo "    <td><center>$percubaL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qcubaC="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]'AND ting= :ting AND jpep='SPMC' AND (G$rsek[KODMP]='A+' OR G$rsek[KODMP]='A' OR G$rsek[KODMP]='A-')";
$qry = oci_parse($conn_sispa,$qcubaC);
oci_execute($qry); }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qcubaC="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND jpep='PMRC' AND G$rsek[KODMP]='A'";
$qry = oci_parse($conn_sispa,$qcubaC);
oci_execute($qry);  }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qcubaC="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND darjah= :ting AND jpep='UPSRC' AND G$rsek[KODMP]='A'";
$qry = oci_parse($conn_sispa,$qcubaC);
oci_execute($qry);  }
$parameter=array(":tahun",":ting");
$value=array($tahun,$ting);
$bilcubaC = kira_bil_rekod($qcubaC,$parameter,$value);
echo "    <td><center>$bilcubaC</center></td>\n";
if($bilmurid!=0) { $percubaC=number_format(($bilcubaC/$bilmurid)*100,'2','.',','); }
else { $percubaC=0.00; }
echo "    <td><center>$percubaC </center></td>\n";
/////////////////////////////////////////////////////////////////////////////////////etr/////////////////////////////////////////////////////////////////////////////////////////////////
if(($ting=="T4") OR ($ting=="T5")){
$qetrL="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND hmp='$rsek[KODMP]' AND (getr='A+' OR getr='A' OR getr='A-' OR getr='B+' OR getr='B' OR getr='C+' OR getr='C' OR getr='D' OR getr='E') ";
  }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qetrL="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND hmp='$rsek[KODMP]' AND (getr='A' OR getr='B' OR getr='C' OR getr='D' OR getr='E') ";
  }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qetrL="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND darjah= :ting AND hmp='$rsek[KODMP]' AND (getr='A' OR getr='B' OR getr='C' OR getr='D') ";
   }
$parameter=array(":tahun",":ting");
$value=array($tahun,$ting);
$biletrL = kira_bil_rekod($qetrL,$parameter,$value);
echo "    <td><center>$biletrL</center></td>\n";
if($bilmurid!=0) { $peretrL=number_format(($biletrL/$bilmurid)*100,'2','.',','); }
else { $peretrL=0.00; }
echo "    <td><center>$peretrL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qetrC="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND hmp='$rsek[KODMP]' AND (getr='A+' OR getr='A' OR getr='A-')";
 }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qetrC="SELECT nokp FROM headcount WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND ting= :ting AND hmp='$rsek[KODMP]' AND getr='A'";
 }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qetrC="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND kodsek='$rsek[KODSEK]' AND darjah= :ting AND hmp='$rsek[KODMP]' AND getr='A'";
 }
$parameter=array(":tahun",":ting");
$value=array($tahun,$ting);
$biletrC = kira_bil_rekod($qetrC,$parameter,$value);
echo "    <td><center>$biletrC</center></td>\n";
if($bilmurid!=0) { $peretrC=number_format(($biletrC/$bilmurid)*100,'2','.',','); }
else { $peretrC=0.00; }
echo "    <td><center>$peretrC </center></td>\n";
echo "  </tr>\n";
	}
$jumbil=$jumbil+$bilmurid;
$jumbiltovL=$jumbiltovL+$biltovL;
$jumbiltovC=$jumbiltovC+$biltovC;
$jumbilatr1L=$jumbilatr1L+$bilatr1L;
$jumbilatr1C=$jumbilatr1C+$bilatr1C;

$jumbilcubaL=$jumbilcubaL+$bilcubaL;
$jumbilcubaC=$jumbilcubaC+$bilcubaC;

$jumbiletrL=$jumbiletrL+$biletrL;
$jumbiletrC=$jumbiletrC+$biletrC;
//////////////////////////////////////////
$jumpertovL=$jumpertovL+$pertovL;
$jumpertovC=$jumpertovC+$pertovC;
$jumperatr1L=$jumperatr1L+$peratr1L;
$jumperatr1C=$jumperatr1C+$peratr1C;

$jumpercubaL=$jumpercubaL+$percubaL;
$jumpercubaC=$jumpercubaC+$percubaC;

$jumperetrL=$jumperetrL+$peretrL;
$jumperetrC=$jumperetrC+$peretrC;

$i=$i+1;

} //loop sekolah
/*
$a=number_format(($jumbiltovL/$jumbil)*100,'2','.',',');
$b=number_format(($jumbiltovC/$jumbil)*100,'2','.',',');
$c=number_format(($jumbilatr1L/$jumbil)*100,'2','.',',');
$d=number_format(($jumbilatr1C/$jumbil)*100,'2','.',',');
$e=number_format(($jumbiletrL/$jumbil)*100,'2','.',',');
$f=number_format(($jumbiletrC/$jumbil)*100,'2','.',',');
$g=number_format(($jumbilcubaL/$jumbil)*100,'2','.',',');
$h=number_format(($jumbilcubaC/$jumbil)*100,'2','.',',');
*/

if ( $jumbil != 0 )
{
	$a = number_format(($jumbiltovL/$jumbil*100),2,'.',',');
	$b = number_format(($jumbiltovC/$jumbil*100),2,'.',',');
	$c = number_format(($jumbilatr1L/$jumbil*100),2,'.',',');
	$d = number_format(($jumbilatr1C/$jumbil*100),2,'.',',');
	$e = number_format(($jumbiletrL/$jumbil*100),2,'.',',');
	$f = number_format(($jumbiletrC/$jumbil*100),2,'.',',');
	$g = number_format(($jumbilcubaL/$jumbil*100),2,'.',',');
	$h = number_format(($jumbilcubaC/$jumbil*100),2,'.',',');
	
}



//******************************************************************************
echo "  <tr>\n";
echo "    <td colspan=\"2\"><div align=\"center\">Jumlah</div></td>\n";
echo "    <td><center>$jumbil</center></td>\n";
echo "    <td><center>$jumbiltovL</center></td>\n";
echo "    <td><center>$a</center></td>\n";
echo "    <td><center>$jumbiltovC</center></td>\n";
echo "   <td><center>$b</center></td>\n";
echo "    <td><center>$jumbilatr1L</center></td>\n";
echo "    <td><center>$c</center></td>\n";
echo "    <td><center>$jumbilatr1C</center></td>\n";
echo "    <td><center>$d</center></td>\n";
echo "    <td><center>$jumbilcubaL</center></td>\n";
echo "    <td><center>$g</center></td>\n";
echo "    <td><center>$jumbilcubaC</center></td>\n";
echo "     <td><center>$h</center></td>\n";
echo "    <td><center>$jumbiletrL</center></td>\n";
echo "    <td><center>$e</center></td>\n";
echo "    <td><center>$jumbiletrC</center></td>\n";
echo "     <td><center>$f</center></td>\n";

echo "  </tr>\n";
echo "</table>\n";


$jumbil=$jumbil+$bilmurid;
$jumbiltovL=$jumbiltovL+$biltovL;
$jumbiltovC=$jumbiltovC+$biltovC;
$jumbilatr1L=$jumbilatr1L+$bilatr1L;
$jumbilatr1C=$jumbilatr1C+$bilatr1C;

$jumbilcubaL=$jumbilcubaL+$bilcubaL;
$jumbilcubaC=$jumbilcubaC+$bilcubaC;

$jumbiletrL=$jumbiletrL+$biletrL;
$jumbiletrC=$jumbiletrC+$biletrC;

}	
?>

</body>
</html>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>
