<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'fungsikira.php';
//include 'kepala.php';
//include 'menu.php';

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$tingkatan = $_GET['tingkatan'];
$namagu = $_GET['namaguru'];
$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek2'];
$jpep = $_SESSION['jpep'];

$m="$ting&&kelas=$kelas&&namaguru=$namagu&&tingkatan=$tingkatan&&namasekolah=$namasek";

?>
<html>
<titel></title>
<head>
<link href="include/kpm.css" type="text/css" rel="stylesheet" />
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

<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>
<?php
$q_mkdt = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.jpep='$jpep' ORDER BY ma.keputusan DESC, ma.gpc ASC, ma.peratus DESC");
oci_execute($q_mkdt);
//$bilmkdt = count_row("SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.jpep='$jpep' ORDER BY ma.keputusan DESC, ma.gpc ASC, ma.peratus DESC");
$bilmkdt = count_row("SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND 
 mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek
and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep and
mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.jpep='$jpep' ORDER BY ma.keputusan DESC, ma.gpc ASC, ma.peratus DESC");

$q_murid = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek
and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep' AND (BILBP > 0 OR BILB > 0 ) AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0  AND NVL(BILTH,0)=0 order by gpc ASC");
oci_execute($q_murid);
$bilmurid = count_row("SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek
and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='$kelas' AND mkh.jpep='$jpep'");
while ( $rowmurid = oci_fetch_array($q_murid))
{
	$rpel[] = $rowmurid;
}

$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' AND sg.kelas='$kelas' AND mp.kod=sg.kodmp ORDER BY mp.susun");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$mpkelas[] = array("KODMP"=>$rowsub["KODMP"]);
}

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h5><center>$namasek<br>LEMBARAN MARKAH MURID $tingkatan<br>".$_SESSION['jpep']." TAHUN ".$tahun."</center></h5>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<h5><center>SEKURANG-KURANGNYA SATU B</center></h5>";
echo "<tr>";
echo "GURU KELAS : $namagu<br>TINGKATAN : $ting  $kelas";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";
echo "<td rowspan = \"2\"><center>BIL MP</center></td>";
echo "<td rowspan = \"2\"><center>PENCAPAIAN</center></td>";

foreach($mpkelas as $key => $subjek)
{
	echo "<td colspan = \"2\"><center>".$subjek["KODMP"]."</center></td>";
}
echo "<td rowspan = \"2\"><center>GPS</center></td>";
echo "</tr>";
echo "<tr>";
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
		echo "    <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>".$murid["NAMA"]."</td>\n";
		echo "    <td><center>".$murid["BILMP"]."</center></td>\n";
		echo "    <td><center>".$murid["PENCAPAIAN"]."</center></td>\n";
		$bilAp=0;$bilA=0;$bilAm=0;$bilBp=0;$bilB=0;$bilCp=0;$bilC=0;$bilD=0;$bilE=0;$bilG=0;$amb=0;
		foreach( $mpkelas as $key2 => $sub )
		{		
			$mkh = $sub["KODMP"];
			$gmkh = "G$mkh";
			$gred = trim($murid["$gmkh"]);
			if($gred=='A+')
				$bilAp++;
			if($gred=='A')
				$bilA++;
			if($gred=='A-')
				$bilAm++;
			if($gred=='B+')
				$bilBp++;
			if($gred=='B')
				$bilB++;
			if($gred=='C+')
				$bilCp++;
			if($gred=='C')
				$bilC++;
			if($gred=='D')
				$bilD++;
			if($gred=='E')
				$bilE++;
			if($gred=='G')
				$bilG++;
			echo "    <td><center>&nbsp;".$murid["$mkh"]."</center></td>\n";
			echo "    <td><center>&nbsp;".$murid["$gmkh"]."</center></td>\n";
		}
		//echo "<br>$bilAp,$bilA,$bilAm,$bilBp,$bilB,$bilCp,$bilC,$bilD,$bilE,$bilG";
		$gps = gpmpma($bilAp,$bilA,$bilAm,$bilBp,$bilB,$bilCp,$bilC,$bilD,$bilE,$bilG,$murid['BILMP']);
		echo "    <td>$gps</td>\n";
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
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>