<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
?>
<script language="javascript" type="text/javascript" src="ajax/ajax.js"></script>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis dan Target Mata Pelajaran</p>
<?php
if (isset($_POST['hc']))
{
$datahc = $_GET['datahc'];
//list ($kodsek, $tahun ,$ting, $mp, $kelas)=split('[/]', $datahc); 
list ($ting, $mp, $kelas)=split('[|]', $datahc); 
$kodsek = $_SESSION["kodsek"];
$tahun = $_SESSION["tahun"];

$ting = $_POST['ting'];
$mp = $_POST['kodmp'];
$gurump = $_POST['gurump'];

/*switch ($_SESSION['statussek'])
{
	case "SR":
		//$level="SR";
		$theadcount="headcountsr";
		$tmarkah = "markah_pelajarsr";
		$tmatap="mpsr";
		$tajuk="DARJAH";
		$tahap="darjah";
		break;
	case "SM" :
		//$level="MR";
		$theadcount="headcount";
		$tmarkah = "markah_pelajar";
		$tmatap="mpsmkc";
		$tajuk="TINGKATAN";
		$tahap="ting";
		break;
}*/
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

$gmp = "G$mp";
$gdata = "SELECT * FROM tsekolah WHERE kodsek='$kodsek'";
$rdata= oci_parse($conn_sispa,$gdata);
oci_execute($rdata);
$resultdata = oci_fetch_array($rdata);
$namasek=$resultdata['NAMASEK'];

$wdata = "SELECT NAMA FROM login WHERE nokp='$gurump'";
$wdata= oci_parse($conn_sispa,$wdata);
oci_execute($wdata);
$resultwdata = oci_fetch_array($wdata);
$namaguru=$resultwdata['NAMA'];

$qmp = "SELECT * FROM $tmatap WHERE kod='$mp'";
$rmp= oci_parse($conn_sispa,$qmp);
oci_execute($rmp);
$resultmp = oci_fetch_array($rmp);
$tempmp=$resultmp['MP'];

$biltovAplus=$biltovA=$biltovAminus=$biltovBplus=$biltovB=$biltovCplus=$biltovC=$biltovD=$biltovE=$biltovF=$biltovG=$biltovTH=0;//TOV
$bilatr1Aplus=$bilatr1A=$bilatr1Aminus=$bilatr1Bplus=$bilatr1B=$bilatr1Cplus=$bilatr1C=$bilatr1D=$bilatr1E=$bilatr1F=$bilatr1G=$bilatr1TH=0;//U1
$biloti1Aplus=$biloti1A=$biloti1Aminus=$biloti1Bplus=$biloti1B=$biloti1Cplus=$biloti1C=$biloti1D=$biloti1E=$biloti1F=$biloti1G=$biloti1TH=0;//OTI1
$bilatr2Aplus=$bilatr2A=$bilatr2Aminus=$bilatr2Bplus=$bilatr2B=$bilatr2Cplus=$bilatr2C=$bilatr2D=$bilatr2E=$bilatr2F=$bilatr2G=$bilatr2TH=0;//PPT
$biloti2Aplus=$biloti2A=$biloti2Aminus=$biloti2Bplus=$biloti2B=$biloti2Cplus=$biloti2C=$biloti2D=$biloti2E=$biloti2F=$biloti2G=$biloti2TH=0;//OTI2
$bilatr3Aplus=$bilatr3A=$bilatr3Aminus=$bilatr3Bplus=$bilatr3B=$bilatr3Cplus=$bilatr3C=$bilatr3D=$bilatr3E=$bilatr3F=$bilatr3G=$bilatr3TH=0;//PAT/UPSR/PMR
$biletrAplus=$biletrA=$biletrAminus=$biletrBplus=$biletrB=$biletrCplus=$biletrC=$biletrD=$biletrE=$biletrF=$biletrG=$biletrTH=0;//ETR

$qryatr = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' ORDER BY capai");
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
if(($ting !='') AND($mp != '') AND($gurump != '')) {
echo "<H2><center>HC3 - TINDAKAN : GURU MATA PELAJARAN </center></H2>\n";
echo "<br>";
echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "<tr><td><b>SEKOLAH : $namasek</b></td></tr>\n";
echo "<tr><td><b>$tajuk : $ting  $kelas</b></td></tr>\n";
echo "<tr><td><b>MATA PELAJARAN : $tempmp</b></td></tr>\n";
echo "<tr><td><b>NAMA GURU : $namaguru</b></td></tr>\n";
echo "<tr><td><INPUT TYPE=\"BUTTON\" VALUE=\"<< KEMBALI\" ONCLICK=\"history.go(-1)\"></td></tr>\n";
echo "</table>";
echo " <br>";
echo "  <table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td rowspan=\"2\">BIL\n";
echo "    <div align=\"center\"></div></td>\n";
echo "    <td rowspan=\"2\">NAMA </td>\n";
echo "    <td rowspan=\"2\" align=\"center\">KELAS </td>\n";
echo "    <div align=\"center\"></div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">TOV</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">OTI1</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">$jpepatr1</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">OTI2</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">$jpepatr2</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">OTI3</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">$jpepatr3</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">ETR</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td><div align=\"center\">M</div></td>\n";
echo "    <td><div align=\"center\">G</div></td>\n";

echo "    <td><div align=\"center\">M</div></td>\n";
echo "    <td><div align=\"center\">G</div></td>\n";

echo "    <td><div align=\"center\">M</div></td>\n";
echo "    <td><div align=\"center\">G</div></td>\n";

echo "    <td><div align=\"center\">M</div></td>\n";
echo "    <td><div align=\"center\">G</div></td>\n";

echo "    <td><div align=\"center\">M</div></td>\n";
echo "    <td><div align=\"center\">G</div></td>\n";

echo "    <td><div align=\"center\">M</div></td>\n";
echo "    <td><div align=\"center\">G</div></td>\n";

echo "    <td><div align=\"center\">M</div></td>\n";
echo "    <td><div align=\"center\">G</div></td>\n";

echo "    <td><div align=\"center\">M</div></td>\n";
echo "    <td><div align=\"center\">G</div></td>\n";
echo "  </tr>\n";

$sqlkelas = "select kelas from sub_guru where tahun='$tahun' and kodsek='$kodsek' and ting='$ting' and kodmp='$mp' and nokp='$gurump'";
$rkelas = oci_parse($conn_sispa,$sqlkelas);
oci_execute($rkelas);
while ($rcdkelas = oci_fetch_array($rkelas)){
$kelas = $rcdkelas["KELAS"];

$qting = "SELECT * FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND hmp='$mp' AND kelas='$kelas' and gtov is not null ORDER BY nama";
$rting = oci_parse($conn_sispa,$qting);
oci_execute($rting);
$biltov = count_row("SELECT gtov FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND hmp='$mp' AND kelas='$kelas' and gtov is not null ORDER BY nama");//TOV
$jumtov+=$biltov;

$bilatr1 = count_row("SELECT $mp,G$mp FROM $tmarkah WHERE kodsek='".$_SESSION['kodsek']."' AND $tahap='$tingatr1' AND kelas='$kelas' AND jpep='$jpepatr1' AND tahun='$tahunatr1' AND $mp IS NOT NULL");//U1
$jumatr1+=$bilatr1;

$biloti1 = count_row("SELECT otr1 FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND hmp='$mp' AND kelas='$kelas' and otr1 is not null ORDER BY nama");//OTI1
$jumoti1+=$biloti1;

$bilatr2 = count_row("SELECT $mp,G$mp FROM $tmarkah WHERE kodsek='".$_SESSION['kodsek']."' AND $tahap='$tingatr2' AND kelas='$kelas' AND jpep='$jpepatr2' AND tahun='$tahunatr2' AND $mp IS NOT NULL");//PPT
$jumatr2+=$bilatr2;

$biloti2 = count_row("SELECT otr2 FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND hmp='$mp' AND kelas='$kelas' and otr2 is not null ORDER BY nama");//OTI2
$jumoti2+=$biloti2;

$bilatr3 = count_row("SELECT $mp,G$mp FROM $tmarkah WHERE kodsek='".$_SESSION['kodsek']."' AND $tahap='$tingatr3' AND kelas='$kelas' AND jpep='$jpepatr3' AND tahun='$tahunatr3' AND $mp IS NOT NULL");//PAT/PMR/UPSR
$jumatr3+=$bilatr3;

$biletr = count_row("SELECT getr FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND hmp='$mp' AND kelas='$kelas' and getr is not null ORDER BY nama");//ETR
$jumetr+=$biletr;


while ($record = oci_fetch_array($rting)){
	$nama = $record['NAMA'];
	$nokp = $record['NOKP'];
	$kelascalon = $record['KELAS'];			
	$q_atr1 = oci_parse($conn_sispa,"SELECT $mp, $gmp FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahunatr1' AND kodsek='$kodsek' AND $tahap='$tingatr1' AND kelas='$kelas' AND jpep='$jpepatr1'");
	oci_execute($q_atr1);
	$rowatr1 = oci_fetch_array($q_atr1);
	$q_atr2 = oci_parse($conn_sispa,"SELECT $mp, $gmp FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahunatr2' AND kodsek='$kodsek' AND $tahap='$tingatr2' AND kelas='$kelas' AND jpep='$jpepatr2'");
	oci_execute($q_atr2);
	$rowatr2 = oci_fetch_array($q_atr2);
	$q_atr3 = oci_parse($conn_sispa,"SELECT $mp, $gmp FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahunatr3' AND kodsek='$kodsek' AND $tahap='$tingatr3' AND kelas='$kelas' AND jpep='$jpepatr3'"); 
	oci_execute($q_atr3);
	$rowatr3 = oci_fetch_array($q_atr3);
	
	$bil=$bil+1;
	if($bil&1) {
		$bcol = "#CDCDCD";
	} else {
		$bcol = "";
	}
	echo "  <tr bgcolor='$bcol'>\n";
	echo "    <td><div align=\"center\">$bil</div></td>\n";
	echo "    <td><div align=\"left\">$nama</div></td>\n";
	echo "    <td><div align=\"center\">$kelascalon</div></td>\n";
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
	
	switch ($record['GTOV']){//TOV
		case "A+": $biltovAplus++; break;
		case "A": $biltovA++; break;	
		case "A-": $biltovAminus++; break;	
		case "B+": $biltovBplus++; break;
		case "B": $biltovB++; break;
		case "C+": $biltovCplus++; break;
		case "C": $biltovC++; break;
		case "D": $biltovD++; break;
		case "E": $biltovE++; break;
		case "F": $biltovF++; break;
		case "G": $biltovG++; break;
		case "TH": $biltovTH++; break;
	}
	switch ($rowatr1["$gmp"]){//U1
		case "A+": $bilatr1Aplus++; break;	
		case "A": $bilatr1A++; break;
		case "A-": $bilatr1Aminus++; break;			
		case "B+": $bilatr1Bplus++; break;
		case "B": $bilatr1B++; break;
		case "C+": $bilatr1Cplus++; break;
		case "C": $bilatr1C++; break;
		case "D": $bilatr1D++; break;
		case "E": $bilatr1E++; break;
		case "F": $bilatr1F++; break;
		case "G": $bilatr1G++; break;
		case "TH": $bilatr1TH++; break;
	}
	switch ($record['GOTR1']){//OTI1
		case "A+": $biloti1Aplus++; break;	
		case "A": $biloti1A++; break;	
		case "A-": $biloti1Aminus++; break;		
		case "B+": $biloti1Bplus++; break;
		case "B": $biloti1B++; break;
		case "C+": $biloti1Cplus++; break;
		case "C": $biloti1C++; break;
		case "D": $biloti1D++; break;
		case "E": $biloti1E++; break;
		case "F": $biloti1F++; break;
		case "G": $biloti1G++; break;
		case "TH": $biloti1TH++; break;
	}
	switch ($rowatr2["$gmp"]){//PPT
		case "A+": $bilatr2Aplus++; break;
		case "A": $bilatr2A++; break;	
		case "A-": $bilatr2Aminus++; break;	
		case "B+": $bilatr2Bplus++; break;
		case "B": $bilatr2B++; break;
		case "C+": $bilatr2Cplus++; break;
		case "C": $bilatr2C++; break;
		case "D": $bilatr2D++; break;
		case "E": $bilatr2E++; break;
		case "F": $bilatr2F++; break;
		case "G": $bilatr2G++; break;
		case "TH": $bilatr2TH++; break;
	}
	switch ($record['GOTR2']){//OTI2
		case "A+": $biloti2Aplus++; break;	
		case "A": $biloti2A++; break;	
		case "A-": $biloti2Aminus++; break;	
		case "B+": $biloti2Bplus++; break;	
		case "B": $biloti2B++; break;
		case "C+": $biloti2Cplus++; break;
		case "C": $biloti2C++; break;
		case "D": $biloti2D++; break;
		case "E": $biloti2E++; break;
		case "F": $biloti2F++; break;
		case "G": $biloti2G++; break;
		case "TH": $biloti2TH++; break;
	}
	switch ($rowatr3["$gmp"]){//PAT/UPSR/PMR
		case "A+": $bilatr3Aplus++; break;	
		case "A": $bilatr3A++; break;	
		case "A-": $bilatr3Aminus++; break;		
		case "B+": $bilatr3Bplus++; break;
		case "B": $bilatr3B++; break;
		case "C+": $bilatr3Cplus++; break;
		case "C": $bilatr3C++; break;
		case "D": $bilatr3D++; break;
		case "E": $bilatr3E++; break;
		case "F": $bilatr3F++; break;
		case "G": $bilatr3G++; break;
		case "TH": $bilatr3TH++; break;
	}
	switch ($record['GETR']){//ETR
		case "A+": $biletrAplus++; break;
		case "A": $biletrA++; break;	
		case "A-": $biletrAminus++; break;	
		case "B+": $biletrBplus++; break;
		case "B": $biletrB++; break;
		case "C+": $biletrCplus++; break;
		case "C": $biletrC++; break;
		case "D": $biletrD++; break;
		case "E": $biletrE++; break;
		case "F": $biletrF++; break;
		case "G": $biletrG++; break;
		case "TH": $biletrTH++; break;
	}
}
}
echo "</table>\n";
//####################### RUMUSAN #######################///
echo "<br>";
echo "<br>";
echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "<tr><td><b>RUMUSAN</b></td></tr>\n";
echo "</table>";
// RUMUSAN UNTUK SR DAN MR
if($level=='SR' or $level=='MR'){
	include 'h3_mrsr.php';
}
if($level=='MA'){
	include 'h3_ma.php';	
}

} else{
	echo "<br><br><br>";
	echo "<table width=\"450\"  border=\"1\" align=\"center\" cellpadding=\"30\" cellspacing=\"0\" bordercolor=\"#0000FF\">\n";
	echo "<tr>\n<td bgcolor=\"#FFFF99\"><div align=\"center\"><h3>SILA PILIH $tajuk, MATA PELAJARAN & GURU MATA PELAJARAN</h3><br>\n<br>\n";
	echo "<< <a href=\"hc3.php\">Kembali</a></td>\n</tr>\n";
	echo "</table>\n";
}
}
else{
	switch ($_SESSION['statussek'])
	{
		case "SR":
			$theadcount="headcountsr";
			$tmatap="mpsr";
			$tajuk="DARJAH";
			$tahap="DARJAH";
			break;

		case "SM":
			$theadcount="headcount";
			$tmatap="mpsmkc";
			$tajuk="TINGKATAN";
			$tahap="TING";
			break;
}
?>

<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.ting.options[form.ting.options.selectedIndex].value;
self.location='hc3.php?ting=' + val;
}
</script>

<?php
$ting=$_GET['ting'];
$kelas=$_GET['kelas'];
echo " <center><h3>SILA PILIH $tajuk, MATA PELAJARAN & GURU MATA PELAJARAN</h3></center>";
echo "<form method=post name='f1' action='hc3.php'>";
echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
echo "<tr bgcolor=\"#CCCCCC\"><td>$tajuk</td><td>MATA PELAJARAN</td><td>GURU MATA PELAJARAN</td><td>&nbsp;</td></tr>";
echo "<tr bgcolor=\"#CCCCCC\"><td>\n";
echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih ".strtolower($tajuk)."</option>";
$SQL_tkelas = "SELECT DISTINCT $tahap FROM $theadcount WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY $tahap";
$sql = OCIParse($conn_sispa,$SQL_tkelas);
OCIExecute($sql);
while(OCIFetch($sql)) { 
	if(OCIResult($sql,"$tahap")==@$ting){
		echo "<option selected value='".OCIResult($sql,"$tahap")."'>".OCIResult($sql,"$tahap")."</option>"."<BR>";
	}else{
		echo  "<option value='".OCIResult($sql,"$tahap")."'>".OCIResult($sql,"$tahap")."</option>";
	}
}
echo "</select>";
echo "</td>";

echo "<td>";
echo "<select name='kodmp' onchange=\"papar_guruMP(this.value,'$ting')\"><option value=''>Sila Pilih Mata Pelajaran</option>";
$mpSQL = OCIParse($conn_sispa,"SELECT DISTINCT hmp FROM $theadcount WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND $tahap='$ting' ORDER BY hmp");
OCIExecute($mpSQL);
while(OCIFetch($mpSQL)) { //$noticia
	$kodsubjek = OCIResult($mpSQL,"HMP");
	$tempmpSQL = "SELECT * FROM $tmatap WHERE kod ='$kodsubjek'";
	$temprs_mp = OCIParse($conn_sispa,$tempmpSQL);
	OCIExecute($temprs_mp);
	OCIFetch($temprs_mp); //temmp
	echo  "<option value='".OCIResult($mpSQL,"HMP")."'>".OCIResult($temprs_mp,"MP")." - ".OCIResult($temprs_mp,"KODLEMBAGA")."</option>";
}
echo "</select>";
echo "</td>";

echo "<td><div id=\"divGuruMP\">";
echo "<select name='kelas' ><option value=''>Sila Pilih</option>";
$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM $theadcount WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND $tahap='$ting' ORDER BY kelas");
OCIExecute($kelas_sql);
while(OCIFetch($kelas_sql)) { 
	if(OCIResult($kelas_sql,"KELAS")==@$kelas){
		echo "<option selected value='".OCIResult($sql,"KELAS")."'>".OCIResult($sql,"KELAS")."</option>"."<BR>";
	}else{
		echo  "<option value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>";
	}
}
echo "<div></td>";

echo "<td><input type='submit' name=\"hc\" value=\"Hantar\"></td>";
echo "</form>";
}
?>
</table></td>
<?php include 'kaki.php';?>                                                           
                                                           