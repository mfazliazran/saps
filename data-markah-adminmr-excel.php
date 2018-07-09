<?php 
include 'auth.php';
include 'config.php';

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$tingkatan = $_GET['tingkatan'];
$namagu = $_GET['namaguru'];
//$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek'];
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
	
$m="$ting&&kelas=$kelas&&namaguru=$namagu&&tingkatan=$tingkatan&&namasekolah=$namasek";

   header('Content-type: application/vnd.ms-excel ');
   header('Content-Disposition: attachment; filename="lembaranmarkahmr.xls"');
   echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>STATISTIK LINUS IKUT PPD</TITLE>";
   echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
   echo "<body>";

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



$q_mkdt = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE 
mkh.nokp=mr.nokp and
 mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep
AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.jpep='$jpep' ORDER BY mr.keputusan Asc, mr.gpc ASC, mr.peratus DESC");
oci_execute($q_mkdt);
$bilmkdt = count_row("SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp=mr.nokp AND 
 mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep and
mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.jpep='$jpep' ORDER BY mr.keputusan Asc, mr.gpc ASC, mr.peratus DESC");

$q_murid = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp=mr.nokp 
 AND 
 mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep 
AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' $kodkelas AND mkh.jpep='$jpep' ORDER BY mr.keputusan Asc, mr.gpc ASC, mr.peratus DESC");
oci_execute($q_murid);
$bilmurid = count_row("SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp=mr.nokp AND 
 mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep and 
mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' $kodkelas AND mkh.jpep='$jpep' ORDER BY mr.keputusan Asc, mr.gpc ASC, mr.peratus DESC");
while ( $rowmurid = oci_fetch_array($q_murid))
{
	$rpel[] = $rowmurid;
}
if($kelas<>"")
	$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' AND sg.kelas='$kelas' AND mp.kod=sg.kodmp ORDER BY mp.kod");
else
	$q_sub = oci_parse($conn_sispa,"SELECT DISTINCT KODMP,KOD FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' AND mp.kod=sg.kodmp ORDER BY mp.kod");
//echo "SELECT * FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' AND sg.kelas='$kelas' AND mp.kod=sg.kodmp ORDER BY mp.kod";
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$mpkelas[] = array("KODMP"=>$rowsub["KODMP"]);
	//echo $rowsub["KODMP"]."<br>";
}

//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h5><center>$namasek<br>LEMBARAN MARKAH MURID $tingkatan<br>".jpep($jpep)." TAHUN ".$tahun."</center></h5>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<tr>";
echo "GURU KELAS : $namagu<br>TINGKATAN : $ting  $kelas";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";
echo "<td rowspan = \"2\">NOKP</td>";
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
	  
echo "<td rowspan = \"2\"><center>JUM MARKAH</center></td>";
echo "<td rowspan = \"2\"><center>PERATUS</center></td>";
echo "<td rowspan = \"2\"><center>GPC</center></td>";
echo "<td rowspan = \"2\"><center>MENGUASAI / <br>TIDAK MENGUASAI</center></td>";
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
		if($kelas=="")
			$namakelas = "[".$murid["KELAS"]."]";
		else
			$namkelas = "";
		echo "    <td>".$murid["NAMA"]." $namakelas</td>\n";
		$str = $murid[NOKP];
		if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      		$str = "'$str";
    	}
		echo "<td>$str</td>\n";
		foreach( $mpkelas as $key2 => $sub )
		{		
			$mkh = $sub["KODMP"];
			//die ("$mkh");
			$gmkh = "G$mkh";
			//die ("$gmkh");
			echo "    <td><center>".$murid["$mkh"]."</center></td>\n";
			echo "    <td><center>".$murid["$gmkh"]."</center></td>\n";
		}
		if($kelas=="")
			$bilmurid = count_row("SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp=mr.nokp AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep and mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' and mkh.kelas='".$murid["KELAS"]."' AND mkh.jpep='$jpep' ORDER BY mr.keputusan DESC, mr.gpc ASC, mr.peratus DESC");
		echo "    <td><center>".$murid["JUMMARK"]."</center></td>\n";
		echo "    <td><center>".$murid["PERATUS"]."</center></td>\n";
		echo "    <td><center>".$murid["GPC"]."</center></td>\n";
		echo "    <td><center>".$murid["KEPUTUSAN"]."</center></td>\n";
		echo "    <td><center>".$murid["KDK"]." ./$bilmurid</center></td>\n";
		echo "    <td><center>".$murid["KDT"]."./$bilmkdt</center></td>\n";
		echo "    <td>".$murid["PENCAPAIAN"]."</td>\n";
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
   
function jpep($kodpep){
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
    case "LNS01":
	$npep="SARINGAN LINUS KHAS 1";
	break;
}
return $npep;
} 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?> 