<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
set_time_limit(0);
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Headcount Negeri</p>
<?php
if (isset($_POST['hcmp']))
{
	$tahun=$_POST['tahun'];
	$ting=$_POST['ting'];
	$status=$_POST['statush'];
	
?>
<!--
<div align=right>
<a href="ctk_hcmpkes-jpn.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>" target=_blank ><img src=printi.jpg border=0></a></div><br><br> -->
<?php	
	
	switch ($ting)
	{
/*
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
			break; */
			
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

echo "<div align=\"center\"><p>ANALISIS HEADCOUNT KESELURUHAN MATA PELAJARAN ( $status )<br>".tahap($ting)."  TAHUN $tahun </p>\n";
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
$qsek=oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru, tsekolah WHERE ting='$ting' AND tahun='$tahun' and kodjenissekolah IN ('203','303')  ORDER BY kodmp ASC");
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

$qbilmurid=("SELECT nokp FROM headcount WHERE tahun='$tahun' AND $tahap='$ting' AND hmp='$rsek[KODMP]' AND etr is not null");
$qry = oci_parse($conn_sispa,$qbilmurid);	
oci_execute($qry);

/*if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3") OR ($ting=="T4") OR ($ting=="T5")){
$qbilmurid=mysql_query("SELECT * FROM markah_pelajar WHERE tahun='$tahun' AND ting='$ting' AND BI !=''"); }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qbilmurid=mysql_query("SELECT * FROM markah_pelajarsr WHERE tahun='$tahun' AND darjah='$ting' AND BI !=''"); }
*/
$bilmurid=count_row($qbilmurid);
echo "    <td><center>$bilmurid</center></td>\n";
////////////////////////////////////////////////////////////////////tov////////////////////////////////////////////////////////////////////////////////////
if(($ting=="T4") OR ($ting=="T5")){
$qtovL="SELECT nokp FROM headcount,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND hmp='$rsek[KODMP]' AND (gtov='A+' OR gtov='A' OR gtov='A-' OR gtov='B+' OR gtov='B' OR gtov='C+' OR gtov='C' OR gtov='D' OR gtov='E') AND kodjenissekolah IN ('203','303') ";
$qry = oci_parse($conn_sispa,$qtovL);
oci_execute($qry); }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qtovL="SELECT nokp FROM headcount WHERE tahun='$tahun' AND ting='$ting' AND hmp='$rsek[KODMP]' AND (gtov='A' OR gtov='B' OR gtov='C' OR gtov='D') ";
$qry = oci_parse($conn_sispa,$qtovL);
oci_execute($qry); }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qtovL="SELECT nokp FROM headcountsr WHERE tahun='$tahun' AND darjah='$ting' AND hmp='$rsek[KODMP]' AND (gtov='A' OR gtov='B' OR gtov='C') ";
$qry = oci_parse($conn_sispa,$qtovL);
oci_execute($qry); }
$biltovL=count_row($qtovL);
echo "    <td><center>$biltovL</center></td>\n";
if($bilmurid!=0) { $pertovL=number_format(($biltovL/$bilmurid)*100,'2','.',','); }
else { $pertovL=0.00; }
echo "    <td><center>$pertovL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qtovC="SELECT nokp FROM headcount,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND hmp='$rsek[KODMP]' AND (gtov='A+' OR gtov='A' OR gtov='A+') AND kodjenissekolah IN ('203','303')";
$qry = oci_parse($conn_sispa,$qtovC);
oci_execute($qry); }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qtovC="SELECT nokp FROM headcount WHERE tahun='$tahun' AND ting='$ting' AND hmp='$rsek[KODMP]' AND gtov='A'";
$qry = oci_parse($conn_sispa,$qtovC);
oci_execute($qry); }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qtovC="SELECT nokp FROM headcountsr WHERE tahun='$tahun' AND darjah='$ting' AND hmp='$rsek[KODMP]' AND gtov='A'";
$qry = oci_parse($conn_sispa,$qtovC);
oci_execute($qry); }
$biltovC=count_row($qtovC);
echo "    <td><center>$biltovC</center></td>\n";
if($bilmurid!=0) { $pertovC=number_format(($biltovC/$bilmurid)*100,'2','.',','); }
else { $pertovC=0.00; }
echo "    <td><center>$pertovC</center></td>\n";
/////////////////////////////////////////////////////////////////////////////////////ppt////////////////////////////////////////////////////////////////////////////////////////
if(($ting=="T4") OR ($ting=="T5")){
$qatr1L="SELECT * FROM markah_pelajar,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND jpep='PPT' AND ($rsek[KODMP]='A+' OR $rsek[kodmp]='A' OR $rsek[KODMP]='A-' OR $rsek[KODMP]='B+' OR $rsek[KODMP]='B' OR $rsek[KODMP]='C+' OR $rsek[KODMP]='C' OR $rsek[KODMP]='D' OR $rsek[KODMP]='E') AND kodjenissekolah IN ('203','303') ";
$qry = oci_parse($conn_sispa,$qatr1L);
oci_execute($qry); }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qatr1L="SELECT nokp FROM markah_pelajar WHERE tahun='$tahun' AND ting='$ting' AND jpep='PPT' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D') ";
$qry = oci_parse($conn_sispa,$qatr1L);
oci_execute($qry); }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qatr1L="SELECT nokp FROM markah_pelajarsr WHERE tahun='$tahun' AND darjah='$ting' AND jpep='PPT' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C') ";
$qry = oci_parse($conn_sispa,$qatr1L);
oci_execute($qry); }
$bilatr1L=count_row($qatr1L);
echo "    <td><center>$bilatr1L</center></td>\n";
if($bilmurid!=0) { $peratr1L=number_format(($bilatr1L/$bilmurid)*100,'2','.',','); }
else { $peratr1L=0.00; }
echo "    <td><center>$peratr1L</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qatr1C="SELECT nokp FROM markah_pelajar,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND jpep='PPT' AND (G$rsek[KODMP]='A+' OR G$rsek[KODMP]='A' OR G$rsek[KODMP]='A-') AND kodjenissekolah IN ('203','303')";
$qry = oci_parse($conn_sispa,$qatr1C);
oci_execute($qry); }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qatr1C="SELECT nokp FROM markah_pelajar WHERE tahun='$tahun' AND ting='$ting' AND jpep='PPT' AND G$rsek[KODMP]='A'";
$qry = oci_parse($conn_sispa,$qatr1C);
oci_execute($qry); }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qatr1C="SELECT nokp FROM markah_pelajarsr WHERE tahun='$tahun' AND darjah='$ting' AND jpep='PPT' AND G$rsek[KODMP]='A'";
$qry = oci_parse($conn_sispa,$qatr1C);
oci_execute($qry); }
$bilatr1C=count_row($qatr1C);
echo "    <td><center>$bilatr1C</center></td>\n";
if($bilmurid!=0) { $peratr1C=number_format(($bilatr1C/$bilmurid)*100,'2','.',','); }
else { $peratr1C=0.00; }
echo "    <td><center>$peratr1C </center></td>\n";
/////////////////////////////////////////////////////////////////////////////////////percubaan////////////////////////////////////////////////////////////////////////////////////////
if(($ting=="T4") OR ($ting=="T5")){
$qcubaL="SELECT * FROM markah_pelajar,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND jpep='SPMC' AND (G$rsek[KODMP]='A+' OR G$rsek[kodmp]='A' OR G$rsek[KODMP]='A-' OR G$rsek[KODMP]='B+' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C+' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D' OR G$rsek[KODMP]='E' OR G$rsek[KODMP]='G') AND kodjenissekolah IN ('203','303')";
$qry = oci_parse($conn_sispa,$qcubaL);
oci_execute($qry); }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qcubaL="SELECT nokp FROM markah_pelajar WHERE tahun='$tahun' AND ting='$ting' AND jpep='PMRC' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C' OR G$rsek[KODMP]='D') ";
$qry = oci_parse($conn_sispa,$qcubaL);
oci_execute($qry);  }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qcubaL="SELECT nokp FROM markah_pelajarsr WHERE tahun='$tahun' AND darjah='$ting' AND jpep='UPSRC' AND (G$rsek[KODMP]='A' OR G$rsek[KODMP]='B' OR G$rsek[KODMP]='C') ";
$qry = oci_parse($conn_sispa,$qcubaL);
oci_execute($qry);  }
$bilcubaL=count_row($qcubaL);
echo "    <td><center>$bilcubaL</center></td>\n";
if($bilmurid!=0) { $percubaL=number_format(($bilcubaL/$bilmurid)*100,'2','.',','); }
else { $percubaL=0.00; }
echo "    <td><center>$percubaL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qcubaC="SELECT nokp FROM markah_pelajar,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND jpep='SPMC' AND (G$rsek[KODMP]='A+' OR G$rsek[KODMP]='A' OR G$rsek[KODMP]='A-') AND kodjenissekolah IN ('203','303')";
$qry = oci_parse($conn_sispa,$qcubaC);
oci_execute($qry);  }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qcubaC="SELECT nokp FROM markah_pelajar WHERE tahun='$tahun' AND ting='$ting' AND jpep='PMRC' AND G$rsek[KODMP]='A'";
$qry = oci_parse($conn_sispa,$qcubaC);
oci_execute($qry); }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qcubaC="SELECT nokp FROM markah_pelajarsr WHERE tahun='$tahun' AND darjah='$ting' AND jpep='UPSRC' AND G$rsek[KODMP]='A'";
$qry = oci_parse($conn_sispa,$qcubaC);
oci_execute($qry); }
$bilcubaC=count_row($qcubaC);
echo "    <td><center>$bilcubaC</center></td>\n";
if($bilmurid!=0) { $percubaC=number_format(($bilcubaC/$bilmurid)*100,'2','.',','); }
else { $percubaC=0.00; }
echo "    <td><center>$percubaC </center></td>\n";
/////////////////////////////////////////////////////////////////////////////////////etr/////////////////////////////////////////////////////////////////////////////////////////////////
if(($ting=="T4") OR ($ting=="T5")){
$qetrL="SELECT nokp FROM headcount,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND hmp='$rsek[KODMP]' AND (getr='A+' OR getr='A' OR getr='A-' OR getr='B+' OR getr='B' OR getr='C+' OR getr='C' OR getr='D' OR getr='E') AND kodjenissekolah IN ('203','303') ";
$qry = oci_parse($conn_sispa,$qetrL);
oci_execute($qry); }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qetrL="SELECT nokp FROM headcount WHERE tahun='$tahun' AND ting='$ting' AND hmp='$rsek[KODMP]' AND (getr='A' OR getr='B' OR getr='C' OR getr='D') ";
$qry = oci_parse($conn_sispa,$qetrL);
oci_execute($qry); }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qetrL="SELECT nokp FROM headcountsr WHERE tahun='$tahun' AND darjah='$ting' AND hmp='$rsek[KODMP]' AND (getr='A' OR getr='B' OR getr='C') ";
$qry = oci_parse($conn_sispa,$qetrL);
oci_execute($qry); }
$biletrL=count_row($qetrL);
echo "    <td><center>$biletrL</center></td>\n";
if($bilmurid!=0) { $peretrL=number_format(($biletrL/$bilmurid)*100,'2','.',','); }
else { $peretrL=0.00; }
echo "    <td><center>$peretrL</center></td>\n";

if(($ting=="T4") OR ($ting=="T5")){
$qetrC="SELECT nokp FROM headcount,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND hmp='$rsek[KODMP]' AND (getr='A+' OR getr='A' OR getr='A-') AND kodjenissekolah IN ('203','303')";
$qry = oci_parse($conn_sispa,$qetrC);
oci_execute($qry); }
if(($ting=="T1") OR ($ting=="T2") OR ($ting=="T3")){
$qetrC="SELECT nokp FROM headcount WHERE tahun='$tahun' AND ting='$ting' AND hmp='$rsek[KODMP]' AND getr='A'";
$qry = oci_parse($conn_sispa,$qetrC);
oci_execute($qry); }
if(($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){
$qetrC="SELECT nokp FROM headcountsr WHERE tahun='$tahun' AND darjah='$ting' AND hmp='$rsek[KODMP]' AND getr='A'";
$qry = oci_parse($conn_sispa,$qetrC);
oci_execute($qry); }
$biletrC=count_row($qetrC);
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
}
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
			self.location='hcmpkes-bptv.php?status=' + val;
		}
		</script>
		
		<?php
		//echo "$kodsek";
		echo "<br><br><br>";
		echo " <center><b>SILA PILIH TAHUN, PEPERIKSAAN, TINGKATAN DAN MATA PELAJARAN</b></center>";
		echo "<br><br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"500\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		//echo "  <td>TAHUN</td>\n";
		//echo "<td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";

		$status = $_GET['status'];
		if($status == "")
			$status = "SM";

		switch ($status)
		{
			case "SM" : $statussek = "SEKOLAH MENENGAH"; $tmp = "mpsmkc"; break;
			//case "SR" : $statussek = "SEKOLAH RENDAH"; $tmp = "mpsr"; break;
			default : $statussek = "Pilih Jenis Sekolah"; break;
		}
		
		$SQLting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM tkelassek ORDER BY ting");
		oci_execute($SQLting);
		$SQLppd = oci_parse($conn_sispa,"SELECT DISTINCT kodnegeri, negeri FROM tknegeri ");
		oci_execute($SQLppd);
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);

		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>STATUS SEKOLAH</td>\n";
		echo "<form method=post name='f1' action='hcmpkes-bptv.php'>";
		echo "<td><select name=\"status\" onchange=\"reload(this.form)\"><option value=''>$statussek</option>";
		//echo "<option value=\"SR\">SEKOLAH RENDAH</option>";
		echo "<option value=\"SM\">SEKOLAH MENENGAH</option>";
		echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\"</td></tr>";

		echo "  <tr bgcolor=\"#CCCCCC\">\n";		
		echo "  <td>NEGERI</td>\n";
		echo "<td><select name='kodnegeri'><option value=''>Pilih Negeri</option>";
		while($rownegeri = oci_fetch_array($SQLppd)) { 
			echo  "<option value='$rownegeri[KODNEGERI]'>$rownegeri[NEGERI]</option>";
		}
		echo "</select>";
		echo "</td></tr>";


		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>TINGKATAN</td>\n";
		echo "<td><select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			case "SM" ://	echo "<option value=\"P\">P</option>";
						//echo "<option value=\"T1\">T1</option>";
						//echo "<option value=\"T2\">T2</option>";
						//echo "<option value=\"T3\">T3</option>";
						echo "<option value=\"T4\">T4</option>";
						echo "<option value=\"T5\">T5</option>";
						break;
						
		/*	case "SR" : echo "<option value=\"D1\">D1</option>";
						echo "<option value=\"D2\">D2</option>";
						echo "<option value=\"D3\">D3</option>";
						echo "<option value=\"D4\">D4</option>";
						echo "<option value=\"D5\">D5</option>";
						echo "<option value=\"D6\">D6</option>";
						break; */
		}
		echo "</select>";
		
		echo "</td></tr>";
		echo "  <td>TAHUN</td>\n";
		echo "<td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
		//// Add your other form fields as needed here/////
		echo "</table><br><br>";
		
		echo "<center><input type='submit' name=\"hcmp\" value=\"Hantar\"></center>";

		echo "</form>";
} ?> 
</td>
<?php include 'kaki.php';?> 
