<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
set_time_limit(0);

function count_row_oci_by_name($sql,$val1,$val2,$param1,$param2){
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
	
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}


function validate($var)
 {
  $var=trim($var);
  $var=strip_tags($var);
  $var=htmlspecialchars($var);
  return $var;
 }
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Headcount Negeri</p>
<?php
if (isset($_POST['hcmp']))
{
	$tahun=validate($_POST['tahun']);
	$ting=validate($_POST['ting']);
	$status=validate($_POST['statush']);
	
?>
<div align=right>
<a href="ctk_hcmpkes-jpn.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>" target=_blank ><img src=printi.jpg border=0></a></div><br><br>
<?php	
	
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

echo "<div align=\"center\"><p>ANALISIS HEADCOUNT KESELURUHAN MATA PELAJARAN ( $status )<br>JABATAN PELAJARAN PERAK<br>".tahap($ting)."  TAHUN $tahun </p>\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<table width=\"95%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#666666\">\n";
echo "  <tr>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil</td>\n";
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
echo "    <td><div align=\"center\">Cemer</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemer</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemer</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemer</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "  </tr>\n";
$qsek=oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru WHERE ting= :ting AND tahun= :tahun  ORDER BY kodmp ASC");
oci_bind_by_name($qsek, ':ting', $ting);
oci_bind_by_name($qsek, ':tahun', $tahun);
oci_execute($qsek);
  	while($rsek=oci_fetch_array($qsek)){
	$kod=$rsek['KODMP'];
	$bilsek=$bilsek+1;
	echo "  <tr>\n";
	echo "    <td>$bilsek</td>\n";
	$qnmp=oci_parse($conn_sispa,"SELECT * FROM $mp WHERE kod='$rsek[KODMP]'");
	oci_execute($qnmp);
	while($rmp=oci_fetch_array($qnmp)){
	echo "    <td align=left>$rmp[MP]</td>\n";

$qbilmurid=("SELECT nokp FROM $headcount WHERE tahun= :tahun AND $tahap= :ting AND hmp='$rsek[KODMP]' AND etr is not null");
$bilmurid=count_row_oci_by_name($qbilmurid, $tahun, $ting, ":tahun", ":ting");
echo "    <td><center>$bilmurid</center></td>\n";
if(($ting=="T4") OR ($ting=="T5")){
$qtovL="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND (gtov='A+' OR gtov='A' OR gtov='A-' OR gtov='B+' OR gtov='B' OR gtov='C+' OR gtov='C' OR gtov='D' OR gtov='E') ";
 }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qtovL="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND (gtov='A' OR gtov='B' OR gtov='C' OR gtov='D' OR gtov='E') ";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qtovL="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND darjah= :ting AND hmp='$rsek[KODMP]' AND (gtov='A' OR gtov='B' OR gtov='C' OR gtov='D') ";
 }

$biltovL=count_row_oci_by_name($qtovL, $tahun, $ting, ":tahun", ":ting");
echo "    <td><center>$biltovL</center></td>\n";
if($bilmurid!=0) { $pertovL=number_format(($biltovL/$bilmurid)*100,'2','.',','); }
else { $pertovL=0.00; }
echo "    <td><center>$pertovL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qtovC="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND (gtov='A+' OR gtov='A' OR gtov='A+')";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qtovC="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND gtov='A'";
 }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qtovC="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND darjah= :ting AND hmp='$rsek[KODMP]' AND gtov='A'";
 }
$biltovC=count_row_oci_by_name($qtovC, $tahun, $ting, ":tahun", ":ting");
echo "    <td><center>$biltovC</center></td>\n";
if($bilmurid!=0) { $pertovC=number_format(($biltovC/$bilmurid)*100,'2','.',','); }
else { $pertovC=0.00; }
echo "    <td><center>$pertovC</center></td>\n";
if(($ting=="T4") OR ($ting=="T5")){
$qatr1L="SELECT * FROM markah_pelajar WHERE tahun= :tahun AND ting= :ting AND jpep='PPT' AND (G$rsek[KODMP]='A+' OR G$rsek[kodmp]='A' OR G$rsek[KODMP]='A-' OR G$rsek[KODMP]='B+' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C+' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D' OR G$rsek[KODMP]='E') ";
 }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qatr1L="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND ting= :ting AND jpep='PPT' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D' OR G$rsek[KODMP]='E') ";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qatr1L="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND darjah= :ting AND jpep='PPT' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D') ";
 }
$bilatr1L=count_row_oci_by_name($qatr1L, $tahun, $ting, ":tahun", ":ting");
echo "    <td><center>$bilatr1L</center></td>\n";
if($bilmurid!=0) { $peratr1L=number_format(($bilatr1L/$bilmurid)*100,'2','.',','); }
else { $peratr1L=0.00; }
echo "    <td><center>$peratr1L</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qatr1C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND ting= :ting AND jpep='PPT' AND (G$rsek[KODMP]='A+' OR G$rsek[KODMP]='A' OR G$rsek[KODMP]='A-')";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qatr1C="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND ting= :ting AND jpep='PPT' AND G$rsek[KODMP]='A'";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qatr1C="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND darjah= :ting AND jpep='PPT' AND G$rsek[KODMP]='A'";
}
$bilatr1C=count_row_oci_by_name($qatr1C, $tahun, $ting, ":tahun", ":ting");
echo "    <td><center>$bilatr1C</center></td>\n";
if($bilmurid!=0) { $peratr1C=number_format(($bilatr1C/$bilmurid)*100,'2','.',','); }
else { $peratr1C=0.00; }
echo "    <td><center>$peratr1C </center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qcubaL="SELECT * FROM markah_pelajar WHERE tahun= :tahun AND ting= :ting AND jpep='SPMC' AND (G$rsek[KODMP]='A+' OR G$rsek[kodmp]='A' OR G$rsek[KODMP]='A-' OR G$rsek[KODMP]='B+' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C+' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D' OR G$rsek[KODMP]='E')";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qcubaL="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND ting= :ting AND jpep='PMRC' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D' OR G$rsek[KODMP]='E') ";
 }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qcubaL="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND darjah= :ting AND jpep='UPSRC' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D') ";
  }
$bilcubaL=count_row_oci_by_name($qcubaL, $tahun, $ting, ":tahun", ":ting");
echo "    <td><center>$bilcubaL</center></td>\n";
if($bilmurid!=0) { $percubaL=number_format(($bilcubaL/$bilmurid)*100,'2','.',','); }
else { $percubaL=0.00; }
echo "    <td><center>$percubaL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qcubaC="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND ting= :ting AND jpep='SPMC' AND (G$rsek[KODMP]='A+' OR G$rsek[KODMP]='A' OR G$rsek[KODMP]='A-')";
 }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qcubaC="SELECT nokp FROM markah_pelajar WHERE tahun= :tahun AND ting= :ting AND jpep='PMRC' AND G$rsek[KODMP]='A'";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qcubaC="SELECT nokp FROM markah_pelajarsr WHERE tahun= :tahun AND darjah= :ting AND jpep='UPSRC' AND G$rsek[KODMP]='A'";
}

$bilcubaC=count_row_oci_by_name($qcubaC, $tahun, $ting, ":tahun", ":ting");
echo "    <td><center>$bilcubaC</center></td>\n";
if($bilmurid!=0) { $percubaC=number_format(($bilcubaC/$bilmurid)*100,'2','.',','); }
else { $percubaC=0.00; }
echo "    <td><center>$percubaC </center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qetrL="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND (getr='A+' OR getr='A' OR getr='A-' OR getr='B+' OR getr='B' OR getr='C+' OR getr='C' OR getr='D' OR getr='E') ";
 }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qetrL="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND (getr='A' OR getr='B' OR getr='C' OR getr='D' OR getr='E') ";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qetrL="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND darjah= :ting AND hmp='$rsek[KODMP]' AND (getr='A' OR getr='B' OR getr='C' OR getr='D') ";
}
$biletrL=count_row_oci_by_name($qetrL, $tahun, $ting, ":tahun", ":ting");
echo "    <td><center>$biletrL</center></td>\n";
if($bilmurid!=0) { $peretrL=number_format(($biletrL/$bilmurid)*100,'2','.',','); }
else { $peretrL=0.00; }
echo "    <td><center>$peretrL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qetrC="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND (getr='A+' OR getr='A' OR getr='A-')";
}
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qetrC="SELECT nokp FROM headcount WHERE tahun= :tahun AND ting= :ting AND hmp='$rsek[KODMP]' AND getr='A'";
}
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qetrC="SELECT nokp FROM headcountsr WHERE tahun= :tahun AND darjah= :ting AND hmp='$rsek[KODMP]' AND getr='A'";
 }
$biletrC=count_row_oci_by_name($qetrC, $tahun, $ting, ":tahun", ":ting");
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

$jumpertovL=$jumpertovL+$pertovL;
$jumpertovC=$jumpertovC+$pertovC;
$jumperatr1L=$jumperatr1L+$peratr1L;
$jumperatr1C=$jumperatr1C+$peratr1C;

$jumpercubaL=$jumpercubaL+$percubaL;
$jumpercubaC=$jumpercubaC+$percubaC;

$jumperetrL=$jumperetrL+$peretrL;
$jumperetrC=$jumperetrC+$peretrC;
}


if ($jumbil != 0 )
{ 	$a=number_format(($jumbiltovL/$jumbil)*100,'2','.',',');
	$b=number_format(($jumbiltovC/$jumbil)*100,'2','.',',');
	$c=number_format(($jumbilatr1L/$jumbil)*100,'2','.',',');
	$d=number_format(($jumbilatr1C/$jumbil)*100,'2','.',',');
	$e=number_format(($jumbiletrL/$jumbil)*100,'2','.',',');
	$f=number_format(($jumbiletrC/$jumbil)*100,'2','.',',');
	$g=number_format(($jumbilcubaL/$jumbil)*100,'2','.',',');
	$h=number_format(($jumbilcubaC/$jumbil)*100,'2','.',',');
} else {
			$a = $b = $c = $d = $e = $f = $g = $h = 0.00 ;
		}


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
echo "    <td><center>$jumbiletrL</center></td>\n";
echo "    <td><center>$e</center></td>\n";
echo "    <td><center>$jumbiletrC</center></td>\n";
echo "     <td><center>$f</center></td>\n";
echo "    <td><center>$jumbilcubaL</center></td>\n";
echo "    <td><center>$g</center></td>\n";
echo "    <td><center>$jumbilcubaC</center></td>\n";
echo "     <td><center>$h</center></td>\n";
echo "  </tr>\n";
echo "</table>\n";
}
else { ?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='hcmpkes-jpn.php?status=' + val;
		}
		</script>
		
		<?php
		
		echo "<br><br><br>";
		echo " <center><b>SILA PILIH TAHUN, PEPERIKSAAN, TINGKATAN DAN MATA PELAJARAN</b></center>";
		echo "<br><br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"500\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>TAHUN</td>\n";
		echo "<td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";

		$status = $_GET['status'];
		if($status == "")
			$status = "SM";

		switch ($status)
		{
			case "SM" : $statussek = "SEKOLAH MENENGAH"; $tmp = "mpsmkc"; break;
			case "SR" : $statussek = "SEKOLAH RENDAH"; $tmp = "mpsr"; break;
			default : $statussek = "Pilih Jenis Sekolah"; break;
		}
		
		$SQLting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM tkelassek ORDER BY ting");
		oci_execute($SQLting);
		$SQLppd = oci_parse($conn_sispa,"SELECT DISTINCT kodppd, ppd FROM tkppd WHERE kodnegeri='".$_SESSION["negeri"]."' ORDER BY kodppd");
		oci_execute($SQLppd);
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);

		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>STATUS SEKOLAH</td>\n";
		echo "<form method=post name='f1' action='hcmpkes-jpn.php'>";
		echo "<td><select name=\"status\" onchange=\"reload(this.form)\"><option value=''>$statussek</option>";
		echo "<option value=\"SR\">SEKOLAH RENDAH</option>";
		echo "<option value=\"SM\">SEKOLAH MENENGAH</option>";
		echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\"</td></tr>";

		echo "  <tr bgcolor=\"#CCCCCC\">\n";		
		echo "  <td>PP DAERAH</td>\n";
		echo "<td><select name='kodppd'><option value=''>Pilih PPD Daerah</option>";
		while($rowppd = oci_fetch_array($SQLppd)) { 
			echo  "<option value='$rowppd[KODPPD]'>$rowppd[PPD]</option>";
		}
		echo "</select>";
		echo "</td></tr>";


		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>TINGKATAN/DARJAH</td>\n";
		echo "<td><select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			case "SM" :	echo "<option value=\"P\">P</option>";
						echo "<option value=\"T1\">T1</option>";
						echo "<option value=\"T2\">T2</option>";
						echo "<option value=\"T3\">T3</option>";
						echo "<option value=\"T4\">T4</option>";
						echo "<option value=\"T5\">T5</option>";
						break;
						
			case "SR" : echo "<option value=\"D1\">D1</option>";
						echo "<option value=\"D2\">D2</option>";
						echo "<option value=\"D3\">D3</option>";
						echo "<option value=\"D4\">D4</option>";
						echo "<option value=\"D5\">D5</option>";
						echo "<option value=\"D6\">D6</option>";
						break;
		}
		echo "</select>";
		echo "</td></tr>";
		
		echo "</table><br><br>";
		
		echo "<center><input type='submit' name=\"hcmp\" value=\"Hantar\"></center>";

		echo "</form>";
} ?> 
</td>
<?php include 'kaki.php';?> 
