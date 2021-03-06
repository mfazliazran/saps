<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
include "input_validation.php";

$tahun = $_SESSION['tahun'];
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];
$ting = validate($_GET['ting']);
$kelas = validate($_GET['kelas']);

$tkt = array("P" => "PERALIHAN","T1" => "TINGKATAN SATU","T2" => "TINGKATAN DUA","T3" => "TINGKATAN TIGA","T4" => "TINGKATAN EMPAT","T5" => "TINGKATAN LIMA","D1" => "TAHUN SATU","D2" => "TAHUN DUA","D3" => "TAHUN TIGA","D4" => "TAHUN EMPAT","D5" => "TAHUN LIMA","D6" => "TAHUN ENAM");
$tingkatan = $tkt["$ting"] ;

$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='$tahun' AND gk.kodsek='$kodsek' AND gk.ting= :ting AND gk.kelas= :kelas AND gk.kodsek=ts.kodsek");
oci_bind_by_name($q_guru, ':ting', $ting);
oci_bind_by_name($q_guru, ':kelas', $kelas);
OCIExecute($q_guru);
OCIFetch($q_guru);
$namagu = OCIResult($q_guru,"NAMA"); 
$namasek = OCIResult($q_guru,"NAMASEK");

if($ting=="" and $kelas==""){
	$ting=$gting; 
	$kelas=$gkelas;
	$gurukel=1;
}
$m="$ting&&kelas=$kelas";

?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>


<td valign="top" class="rightColumn">
<p class="subHeader">Senarai Pencapaian Kecemerlangan Calon Mengikut Kategori</p>
<form action="ctk_misssr.php?ting=<?php echo $m;?>" method="POST" target="_blank">
<?php

$q_mkdt = "SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.nokp=sr.nokp AND mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.darjah= :ting AND mkh.jpep='$jpep' ORDER BY sr.keputusan DESC, sr.gpc ASC, sr.peratus DESC";
$qry_mkdt = oci_parse($conn_sispa,$q_mkdt);
oci_bind_by_name($qry_mkdt, ':ting', $ting);
oci_execute($qry_mkdt);
$parameter = array(":ting");
$value = array($ting);
$bilmkdt = kira_bil_rekod($q_mkdt,$parameter,$value);

$q_murid = "SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.nokp=sr.nokp AND 
 mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek AND mkh.darjah=sr.darjah and mkh.kelas=sr.kelas AND mkh.jpep=sr.jpep 
 AND (BILB > 0) AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILTH,0)=0 AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.darjah= :ting AND mkh.kelas= :kelas AND mkh.jpep='$jpep' ORDER BY sr.keputusan DESC, sr.gpc ASC, sr.peratus DESC";

$qry_murid = oci_parse($conn_sispa,$q_murid);
oci_bind_by_name($qry_murid, ':ting', $ting);
oci_bind_by_name($qry_murid, ':kelas', $kelas);
oci_execute($qry_murid);
$parameter = array(":ting",":kelas");
$value = array($ting,$kelas);
$bilmurid = kira_bil_rekod($q_murid,$parameter,$value);
while ( $rowmurid = oci_fetch_array($qry_murid))
{
	$rpel[] = $rowmurid;
}

$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsr mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting= :ting AND sg.kelas= :kelas AND mp.kod=sg.kodmp ORDER BY mp.susun");
oci_bind_by_name($q_sub, ':ting', $ting);
oci_bind_by_name($q_sub, ':kelas', $kelas);
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$mpkelas[] = array("KODMP"=>$rowsub[KODMP]);
}

echo "<h3><center>SENARAI PENCAPAIAN KECEMERLANGAN CALON MENGIKUT KATEGORI $tingkatan<br>".jpep($jpep)." TAHUN ".$tahun."<br>SEKURANG-KURANGNYA SATU B</center></h3><br>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
if($gurukel==1){
	echo "<b>GURU KELAS : $nama<br>TAHUN : $gting  $gkelas</b>";
}else{
	echo "<b>GURU KELAS : $namagu<br>TAHUN : $ting  $kelas</b>";
}
echo "<br><br>";
echo "<tr bgcolor=\"#FFCC99\">";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";
echo "<td rowspan = \"2\"><center>BIL MP</center></td>";
echo "<td rowspan = \"2\"><center>PENCAPAIAN</center></td>";

foreach($mpkelas as $key => $subjek)
{
	echo "<td colspan = \"2\"><center>$subjek[KODMP]</center></td>";
}
echo "<td rowspan = \"2\"><center>GPS</center></td>";
echo "</tr>";
echo "<tr bgcolor=\"#FFCC99\">";
for ($i = 0; $i <= $key; $i++)
{
	echo "<td><center>M</center></td>";
	echo "<td><center>G</center></td>";
}
echo "</tr>";

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
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>".$murid['NAMA']."</td>\n";
		echo "    <td><center>".$murid['BILMP']."</center></td>\n";
		echo "    <td>".$murid['PENCAPAIAN']."</td>\n";
		$bilA=0;$bilB=0;$bilC=0;$bilD=0;$bilE=0;$gps=0;
		foreach( $mpkelas as $key2 => $sub )
		{		
			$mkh = $sub["KODMP"];
			$gmkh = "G$mkh";
			$gred = trim($murid["$gmkh"]);
			if($gred=='A')
				$bilA++;
			if($gred=='B')
				$bilB++;
			if($gred=='C')
				$bilC++;
			if($gred=='D')
				$bilD++;
			if($gred=='E')
				$bilE++;
			echo "    <td><center>&nbsp;".$murid["$mkh"]."</center></td>\n";
			echo "    <td><center>&nbsp;".$murid["$gmkh"]."</center></td>\n";
		}

		$gps = gpmpmrsr($bilA,$bilB,$bilC,$bilD,$bilE,$murid['BILMP']);
		echo "    <td><center>$gps</center></td>\n";

	}
}

else {
		$bilcol = 9 + ($key+1)*2;
		echo "<tr>";
		echo "<br>";
		echo "<td colspan = \"$bilcol\"><center><font color=\"#FF0000\"><strong>1. MARKAH AKAN DIPAPARKAN JIKA KELAS TERSEBUT ADA A DAN B SAHAJA <br> 2. KEPADA SUP, SILA KLIK BUTANG 'PROSES MARKAH PEPERIKSAAN' UNTUK MENDAPAT MARKAH TERKINI.</strong></font><center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
?>
<br><br>
  <center><input type="submit"  name="submit" value="CETAK">
  </center>
</p>
<?php
?>
<br>
</form>
<?php
include 'kaki.php';
?> 