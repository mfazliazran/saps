<?php 
include 'auth.php';
include 'config.php';
include 'fungsi2.php';
//include 'kepala.php';
//include 'menu.php';
$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$tkt = array("P" => "PERALIHAN","T1" => "TINGKATAN SATU","T2" => "TINGKATAN DUA","T3" => "TINGKATAN TIGA","T4" => "TINGKATAN EMPAT","T5" => "TINGKATAN LIMA","D1" => "TAHUN SATU","D2" => "TAHUN DUA","D3" => "TAHUN TIGA","D4" => "TAHUN EMPAT","D5" => "TAHUN LIMA","D6" => "TAHUN ENAM");
$tingkatan = $tkt["$ting"] ;
//$tingkatan = $_GET['tingkatan'];
//$namagu = $_GET['namaguru'];
//$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek2'];
$jpep = $_SESSION['jpep'];

if($kelas<>""){
	$q_sql=("SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='$tahun' AND gk.kodsek='$kodsek' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek");
	$kodkelas = "AND mkh.kelas='$kelas'";
	$kodkelas2 = "AND sg.kelas='$kelas'";
}else{
	$q_sql=("SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='$tahun' AND gk.kodsek='$kodsek' AND gk.ting='$ting' AND gk.kodsek=ts.kodsek");
	$kodkelas= "";
	$kodkelas2 = "";
}
$q_sql=oci_parse ($conn_sispa,$q_sql);
oci_execute($q_sql);
$row = oci_fetch_array($q_sql);
$namasek = $row["NAMASEK"];
if($kelas<>"")
	$namagu = $row["NAMA"];
else
	$namagu = "KESELURUHAN $tingkatan";
?>
<html>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
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

$q_mkdt = "SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.kodsek='$kodsek' and mkh.nokp=sr.nokp AND mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep AND mkh.tahun='$tahun' AND mkh.darjah='$ting' AND mkh.jpep='$jpep' ORDER BY sr.keputusan DESC, sr.gpc ASC, sr.peratus DESC";
$qry_mkdt = oci_parse($conn_sispa,$q_mkdt);
oci_execute($qry_mkdt);
$bilmkdt = count_row($q_mkdt);

/*$q_murid = "SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.nokp=sr.nokp AND 
 mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek
and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep
AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.darjah='$ting' $kodkelas AND mkh.kelas='$kelas' AND mkh.jpep='$jpep' ORDER BY sr.keputusan DESC, sr.gpc ASC, sr.peratus DESC";*/
$q_murid = "SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.kodsek='$kodsek' and mkh.nokp=sr.nokp AND 
 mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek
and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep
AND mkh.tahun='$tahun' AND mkh.darjah='$ting' $kodkelas AND mkh.jpep='$jpep' ORDER BY sr.keputusan DESC, sr.gpc ASC, sr.peratus DESC";

$qry_murid = oci_parse($conn_sispa,$q_murid);
oci_execute($qry_murid);
$bilmurid = count_row($q_murid);
while ( $rowmurid = oci_fetch_array($qry_murid))
{
	$rpel[] = $rowmurid;
}

//$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsr mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' AND sg.kelas='$kelas' AND mp.kod=sg.kodmp ORDER BY mp.kod");
if($kelas<>"")
	$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsr mp, sub_guru sg WHERE sg.kodsek='$kodsek' and sg.tahun='$tahun' AND sg.ting='$ting' AND sg.kelas='$kelas' AND mp.kod=sg.kodmp ORDER BY mp.kod");
else
	$q_sub = oci_parse($conn_sispa,"SELECT DISTINCT KODMP,KOD FROM mpsr mp, sub_guru sg WHERE sg.kodsek='$kodsek' and sg.tahun='$tahun' AND sg.ting='$ting' AND mp.kod=sg.kodmp ORDER BY mp.kod");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
//echo "ada";
	$mpkelas[] = array("KODMP"=>$rowsub[KODMP]);
}

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h3><center>$namasek<br>LEMBARAN MARKAH MURID $tingkatan<br>".$jpep." TAHUN ".$tahun."</center></h3><br>";
echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "<tr><td width='10%'><b>GURU KELAS</b></td><td width='1%'><b>:</b></td><td width='87%'><b>$namagu</b></td></tr>";
echo "<tr><td><b width='10%'>TINGKATAN</b><td width='1%'><b>:</b></td><td width='87%'><b>$ting  $kelas</b></td></td></tr>";
echo "</table>";
echo "<br>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<tr>";
//echo "<b>GURU KELAS : $namagu<br>TAHUN : $ting  $kelas</b>";
//echo "<br><br>";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";

foreach($mpkelas as $key => $subjek)
{
	echo "<td colspan = \"2\"><center>$subjek[KODMP]</center></td>";
}
echo "<td rowspan = \"2\"><center>JUM MARKAH</center></td>";
echo "<td rowspan = \"2\"><center>PERATUS</center></td>";
echo "<td rowspan = \"2\"><center>GPC</center></td>";
echo "<td rowspan = \"2\"><center>Menguasai / <br>Tidak Menguasai</center></td>";
echo "<td rowspan = \"2\"><center>KDK</center></td>";
echo "<td rowspan = \"2\"><center>KDT</center></td>";
echo "<td rowspan = \"2\"><center>PENCAPAIAN</center></td>";
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
		echo "    <td>$murid[NAMA]<br>[$murid[KELAS]] <br>$murid[NOKP]</td>\n";
		foreach( $mpkelas as $key2 => $sub )
		{		
			$mkh = $sub[KODMP];
			$gmkh = "G$mkh";
			echo "    <td><center>&nbsp;".$murid["$mkh"]."</center></td>\n";
			echo "    <td><center>&nbsp;".$murid["$gmkh"]."</center></td>\n";
		}
		echo "    <td><center>".$murid[JUMMARK]."</center></td>\n";
		echo "    <td><center>".$murid[PERATUS]."</center></td>\n";
		echo "    <td><center>".$murid[GPC]."</center></td>\n";
		echo "    <td><center>".$murid[KEPUTUSAN]."</center></td>\n";
		echo "    <td><center>".$murid[KDK]."/$bilmurid</center></td>\n";
		echo "    <td><center>".$murid[KDT]."/$bilmkdt</center></td>\n";
		echo "    <td>".$murid[PENCAPAIAN]."</td>\n";
	}
}

else {
		$bilcol = 9 + ($key+1)*2;
		echo "<tr>";
		echo "<br>";
		echo "<td colspan = \"$bilcol\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
echo "<br><br>";
//include 'kaki.php';
?> 
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>