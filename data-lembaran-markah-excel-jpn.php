<?php 
include 'auth.php';
include 'config.php';
include 'fungsi2.php';

   header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="lembaranmarkahsr.xls"');
   echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>STATISTIK LINUS IKUT PPD</TITLE>";
   echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
   echo "<body>";

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$tingkatan = $_GET['tingkatan'];
//$namagu = $_GET['namaguru'];
//$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek2'];
$jpep = $_SESSION['jpep'];
if($kelas<>""){
	$kodkelas = "AND mkh.kelas='$kelas'";
	$q_sql=("SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='$tahun' AND gk.kodsek='$kodsek' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek");
}else{
	$kodkelas = "";
	$q_sql=("SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='$tahun' AND gk.kodsek='$kodsek' AND gk.ting='$ting' AND gk.kodsek=ts.kodsek");
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
<titel></title>
<head>
<!--<link href="include/kpm.css" type="text/css" rel="stylesheet" />-->
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

<?php

$q_mkdt = "SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.kodsek='$kodsek' and mkh.nokp=sr.nokp AND mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep AND mkh.tahun='$tahun' AND mkh.darjah='$ting' AND mkh.jpep='$jpep' ORDER BY sr.keputusan DESC, sr.gpc ASC, sr.peratus DESC";
$qry_mkdt = oci_parse($conn_sispa,$q_mkdt);
oci_execute($qry_mkdt);
$bilmkdt = count_row($q_mkdt);

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

//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h3><center>$namasek<br>LEMBARAN MARKAH MURID $tingkatan<br>".jpep($jpep)." TAHUN ".$tahun."</center></h3><br>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<tr>";
echo "GURU KELAS : $namagu<br>TAHUN : $ting  $kelas";
echo "<br><br>";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";
echo "<td rowspan = \"2\">NOKP</td>";
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
		echo "<tr>\n";
		echo "<td><center>$bil</center></td>\n";
		if($kelas=="")
			$namakelas = "[$murid[KELAS]]";
		else
			$namakelas = "";
		echo "<td>$murid[NAMA] $namakelas</td>\n";
		$str = $murid[NOKP];
		if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      		$str = "'$str";
    	}
		echo "<td>$str</td>\n";
		foreach( $mpkelas as $key2 => $sub )
		{		
			$mkh = $sub[KODMP];
			$gmkh = "G$mkh";
			echo "<td><center>&nbsp;".$murid["$mkh"]."</center></td>\n";
			echo "<td><center>&nbsp;".$murid["$gmkh"]."</center></td>\n";
		}
		if($kelas=="")
		   	$bilmurid = count_row("SELECT * FROM markah_pelajarsr mkh, tnilai_sr sr WHERE mkh.kodsek='$kodsek' and mkh.nokp=sr.nokp AND mkh.tahun=sr.tahun and mkh.kodsek=sr.kodsek and mkh.darjah=sr.darjah and mkh.kelas=sr.kelas and mkh.jpep=sr.jpep AND mkh.tahun='$tahun' AND mkh.darjah='$ting' and mkh.kelas='".$murid[KELAS]."' AND mkh.jpep='$jpep' ORDER BY sr.keputusan DESC, sr.gpc ASC, sr.peratus DESC");
		echo "<td><center>".$murid[JUMMARK]."</center></td>\n";
		echo "<td><center>".$murid[PERATUS]."</center></td>\n";
		echo "<td><center>".$murid[GPC]."</center></td>\n";
		echo "<td><center>".$murid[KEPUTUSAN]."</center></td>\n";
		echo "<td><center>".$murid[KDK]."./$bilmurid</center></td>\n";
		echo "<td><center>".$murid[KDT]."./$bilmkdt</center></td>\n";
		echo "<td>".$murid[PENCAPAIAN]."</td>\n";
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
   echo "</body>";
   echo "</html>";
function jpep($kodpep)
{
	switch ($kodpep){
		case "U1":
		$npep="UJIAN 1";
		break;
		case "U2":
		$npep="UJIAN 2";
		break;
		case "PAT":
		$npep="PEPERIKSAAN AKHIR TAHUN";
		break;
		case "PPT":
		$npep="PEPERIKSAAN PERTENGAHAN TAHUN";
		break;
		case "PMRC":
		$npep="PEPERIKSAAN PERCUBAAN PMR";
		break;
		case "SPMC":
		$npep="PEPERIKSAAN PERCUBAAN SPM";
		break;
		case "UPSRC":
		$npep="PEPERIKSAAN PERCUBAAN UPSR";
		break;
	}
return $npep;
}
?> 
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?> 