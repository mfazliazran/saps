<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_POST['kelas'];
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];

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

$q_sql="SELECT ts.NAMASEK,NAMA FROM tguru_kelas gk, tsekolah ts WHERE gk.kodsek='$kodsek' and gk.tahun='$tahun' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek";
$q_sql=oci_parse ($conn_sispa,$q_sql);
oci_execute($q_sql);
$row = oci_fetch_array($q_sql);
$namasek = $row["NAMASEK"];
$namagu = $row["NAMA"];

?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
function reload(form)
{
var val=form.ting.options[form.ting.options.selectedIndex].value;
self.location='hc4.php?ting=' + val;
}
</script>

<td valign="top" class="rightColumn">
<p class="subHeader">ETR Murid Bagi Kelas</p>
<?php
if($_SESSION["level"]<>'4' and $_SESSION["level"]<>'3' and $_SESSION["level"]<>'11')
	die('Tidak dibenarkan.');
	
if (isset($_POST['semakhc']))
{
$q_murid = oci_parse($conn_sispa,"SELECT distinct nokp,nama,kelas FROM $theadcount where kodsek='$kodsek' and tahun='$tahun' and $tahap='$ting' and kelas='$kelas' order by nama");
oci_execute($q_murid);
while ( $rowmurid = oci_fetch_array($q_murid))
{
	$rpel[] = $rowmurid;
}

$q_sub = oci_parse($conn_sispa,"SELECT DISTINCT KODMP FROM sub_guru WHERE kodsek='$kodsek' and tahun='$tahun' AND ting='$ting' and kelas='$kelas'  ORDER BY KODMP");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$mpkelas[] = array("KODMP"=>$rowsub["KODMP"],"ETR"=>"","GETR"=>"","GTOV"=>"","TOV"=>"");
}

echo "<H2><center>HC4 - TINDAKAN : GURU KELAS</center></H2>\n";
echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "<tr><td width='100%' colspan=\"3\"><b>SASARAN SETIAP MURID MENGIKUT MATA PELAJARAN YANG DIAMBIL</b></td></tr>";
echo "<tr><td width='10%'><b>GURU KELAS</b></td><td width='1%'><b>:</b></td><td width='87%'><b>$namagu</b></td></tr>";
echo "<tr><td><b width='10%'>TINGKATAN</b><td width='1%'><b>:</b></td><td width='87%'><b>$ting  $kelas</b></td></tr>";
echo "<tr><td><INPUT TYPE=\"BUTTON\" VALUE=\"<< KEMBALI\" ONCLICK=\"history.go(-1)\"></td></tr>\n";
echo "</table>";

echo "<br>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<tr bgcolor=\"#FFCC99\">";
echo "<td rowspan = \"2\"><center>BIL</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";
echo "<td rowspan = \"2\">&nbsp;</td>";
if (!empty($mpkelas))
{
	foreach($mpkelas as $key => $subjek)
	{
		echo "<td colspan = \"2\"><center>".$subjek["KODMP"]."</center></td>";
	}
}
else {
		echo "<td colspan = \"2\"><center>SUBJEK KELAS</center></td>";
     }
	  
echo "<td rowspan = \"2\"><center>GP</center></td>";
echo "</tr>";
echo "<tr bgcolor=\"#FFCC99\">";
for ($i = 0; $i <= $key; $i++)
{
	echo "<td><center>M</center></td>";
	echo "<td><center>G</center></td>";
}
echo "</tr>";
//////habis kepala

if (!empty($rpel))
{
	foreach( $rpel as $key1 => $murid )
	{
		$bil = $key1 + 1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "    <tr bgcolor='$bcol'>\n";
		echo "    <td rowspan=\"2\"><center>$bil</center></td>\n";
		echo "    <td rowspan=\"2\">".$murid["NAMA"]."</td>\n";
		//TOV
		echo "    <td>TOV</td>\n";
		$bilmp=0;
		$biltovAplus=$biltovA=$biltovAminus=$biltovBplus=$biltovB=$biltovCplus=$biltovC=$biltovD=$biltovE=$biltovG=$biltovTH=0;//TOV
	 	$biletrAplus=$biletrA=$biletrAminus=$biletrBplus=$biletrB=$biletrCplus=$biletrC=$biletrD=$biletrE=$biletrG=$biletrTH=0;//ETR
		foreach($mpkelas as $key => $subjek){
			$kodmp = $subjek["KODMP"];
			$bilmp++;
			$q_markah = oci_parse($conn_sispa,"SELECT * FROM $theadcount where kodsek='$kodsek' and tahun='$tahun' and $tahap='$ting' and kelas='$kelas' and nokp='".$murid["NOKP"]."' and hmp='$kodmp'");
			oci_execute($q_markah);
			$rowmarkah = oci_fetch_array($q_markah);
			echo "    <td><center>&nbsp;".$rowmarkah["TOV"]."</center></td>\n";
			echo "    <td><center>&nbsp;".$rowmarkah["GTOV"]."</center></td>\n";
			switch ($rowmarkah['GTOV']){//TOV
				case "A+": $biltovAplus++; break;
				case "A": $biltovA++; break;	
				case "A-": $biltovAminus++; break;	
				case "B+": $biltovBplus++; break;
				case "B": $biltovB++; break;
				case "C+": $biltovCplus++; break;
				case "C": $biltovC++; break;
				case "D": $biltovD++; break;
				case "E": $biltovE++; break;
				case "G": $biltovG++; break;
				case "TH": $biltovTH++; break;
			}
		}
		if($level=='SR' or $level=='MR'){
		echo "    <td><center>".gpmpmrsr($biltovA,$biltovB,$biltovC,$biltovD,$biltovE,$bilmp)."</center></td></tr>\n";
		}
		if($level=='MA'){
		echo "    <td><center>".gpmpma($biltovAplus,$biltovA,$biltovAminus,$biltovBplus,$biltovB,$biltovCplus,$biltovC,$biltovD,$biltovE,$biltovG,$bilmp)."</center></td></tr>\n";
		}
		//ETR
		echo "<tr bgcolor='$bcol'><td>ETR</td>\n";
		foreach($mpkelas as $key => $subjek){	
			$kodmp = $subjek["KODMP"];
			$q_markah = oci_parse($conn_sispa,"SELECT * FROM $theadcount where kodsek='$kodsek' and tahun='$tahun' and $tahap='$ting' and kelas='$kelas' and nokp='".$murid["NOKP"]."' and hmp='$kodmp'");
			oci_execute($q_markah);
			$rowmarkah = oci_fetch_array($q_markah);
			echo "    <td><center>&nbsp;".$rowmarkah["ETR"]."</center></td>\n";
			echo "    <td><center>&nbsp;".$rowmarkah["GETR"]."</center></td>\n";
			switch ($rowmarkah['GETR']){//ETR
				case "A+": $biletrAplus++; break;
				case "A": $biletrA++; break;	
				case "A-": $biletrAminus++; break;	
				case "B+": $biletrBplus++; break;
				case "B": $biletrB++; break;
				case "C+": $biletrCplus++; break;
				case "C": $biletrC++; break;
				case "D": $biletrD++; break;
				case "E": $biletrE++; break;
				case "G": $biletrG++; break;
				case "TH": $biletrTH++; break;
			}
		}
		if($level=='SR' or $level=='MR'){
		echo "    <td><center>".gpmpmrsr($biletrA,$biletrB,$biletrC,$biletrD,$biletrE,$bilmp)."</center></td></tr>\n";
		}
		if($level=='MA'){
		echo "    <td><center>".gpmpma($biletrAplus,$biletrA,$biletrAminus,$biletrBplus,$biletrB,$biletrCplus,$biletrC,$biletrD,$biletrE,$biletrG,$bilmp)."</center></td></tr>\n";
		}
	}
}
else {
		$bilcol = 9 + ($key+1)*2;
		echo "<tr>";
		echo "<br>";
		echo "<td colspan = \"$bilcol\"><center>SILA PILIH TINGKATAN/DARJAH DAN KELAS<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
} else {
	session_start();
	switch ($_SESSION['statussek'])
	{
		case "SR":
			$theadcount="headcountsr";
			$tahap="DARJAH";
			break;

	case "SM" :
			$theadcount="headcount";
			$tahap="TING";
			break;
	}
	echo " <center><b>SILA PILIH TINGKATAN/DARJAH DAN KELAS</b></center>";
	echo "<br>";
	echo "<form method=\"post\">\n";
	echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
	echo "  <tr bgcolor=\"#CCCCCC\">\n";
	echo "  <td>TINGKATAN/DARJAH</td>\n";
	echo "  <td>KELAS</td>\n";
	echo "  <td>HANTAR</td>\n";
	echo " </tr>";
	echo "  <tr bgcolor=\"#CCCCCC\">\n";
	echo "  <td>\n";
	
	$ting=$_GET['ting'];
	//$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY ting";
	$SQL_tkelas = "SELECT DISTINCT $tahap FROM $theadcount WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY $tahap";
	//echo $SQL_tkelas;
	$sql = OCIParse($conn_sispa,$SQL_tkelas);
	OCIExecute($sql);
	//$num = count_row($SQL_tkelas);
	echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih Tingkatan/Darjah</option>";
	while(OCIFetch($sql)) { 
		if(OCIResult($sql,"$tahap")==@$ting){echo "<option selected value='".OCIResult($sql,"$tahap")."'>".OCIResult($sql,"$tahap")."</option>"."<BR>";}
		else{echo  "<option value='".OCIResult($sql,"$tahap")."'>".OCIResult($sql,"$tahap")."</option>";}
	}
	echo "</select>";
	echo "</td>";
		
	echo "<td>";
	echo "<select name='kelas' ><option value=''>Pilih Kelas</option>";
	$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND ting='$ting' ORDER BY kelas");
	OCIExecute($kelas_sql);
	while(OCIFetch($kelas_sql)) { 
		if(OCIResult($kelas_sql,"KELAS")==@$kelas){echo "<option selected value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>"."<BR>";}
		else{echo  "<option value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>";}
	}
	echo "</td>";
	echo "  <td><center><input type=\"submit\" id=\"semakhc\" name=\"semakhc\" value=\"Hantar\" Alt=\"Hantar\"></td>\n";
	echo "</table>\n";
	echo "</form>";
}
?>
</td>
<?php 
include 'kaki.php';
?> 