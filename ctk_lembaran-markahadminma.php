<?php 
include 'auth.php';
include 'config.php';
//include 'kepala.php';
//include 'menu.php';

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
//$tingkatan = $_GET['tingkatan'];
//$namagu = $_GET['namaguru'];
//$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];
$tingkatan = tahap($ting);

//$m="$ting&&kelas=$kelas&&namaguru=$namagu&&tingkatan=$tingkatan&&namasekolah=$namasek";
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
if($kelas<>"")
	$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' $kodkelas2 AND mp.kod=sg.kodmp ORDER BY mp.kod");
else
	$q_sub = oci_parse($conn_sispa,"SELECT DISTINCT KODMP,KOD FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' AND mp.kod=sg.kodmp ORDER BY mp.kod");

oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$qry_subj.=",".$rowsub["KODMP"];
    $qry_subj.=",G".$rowsub["KODMP"];
	$mpkelas[] = array("KODMP"=>$rowsub["KODMP"]);
}

$q_mkdt = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.jpep='$jpep' ORDER BY ma.keputusan DESC, ma.gpc ASC, ma.peratus DESC");
oci_execute($q_mkdt);

$bilmkdt = count_row("SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND 
 mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek
and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep and
mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.jpep='$jpep' ORDER BY ma.keputusan DESC, ma.gpc ASC, ma.peratus DESC");
	
//$q_murid = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' $kodkelas AND mkh.jpep='$jpep' ORDER BY ma.keputusan DESC, ma.gpc ASC, ma.peratus DESC");
$q_murid = oci_parse($conn_sispa,"SELECT NAMA,mkh.KELAS,JUMMARK,PERATUS,GPC,KEPUTUSAN,KDK,KDT,PENCAPAIAN $qry_subj FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek
and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' $kodkelas AND mkh.jpep='$jpep' ORDER BY ma.keputusan DESC, ma.gpc ASC, ma.peratus DESC");
oci_execute($q_murid);

$bilmurid = count_row("SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek
and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' $kodkelas AND mkh.jpep='$jpep'");

while ( $rowmurid = oci_fetch_array($q_murid))
{
	$rpel[] = $rowmurid;
}

/*$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' AND sg.kelas='$kelas' AND mp.kod=sg.kodmp ORDER BY mp.kod");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$mpkelas[] = array("KODMP"=>$rowsub["KODMP"]);
}*/

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h5><center>$namasek<br>LEMBARAN MARKAH MURID $tingkatan<br>".$jpep." TAHUN ".$tahun."</center></h5>";
echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "<tr><td width='10%'><b>GURU KELAS</b></td><td width='1%'><b>:</b></td><td width='87%'><b>$namagu</b></td></tr>";
echo "<tr><td><b width='10%'>TINGKATAN</b><td width='1%'><b>:</b></td><td width='87%'><b>$ting  $kelas</b></td></td></tr>";
echo "</table>";
echo "<br>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<tr>";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";

foreach($mpkelas as $key => $subjek)
{
	echo "<td colspan = \"2\"><center>".$subjek["KODMP"]."</center></td>";
}
echo "<td rowspan = \"2\"><center>JUM MARKAH</center></td>";
echo "<td rowspan = \"2\"><center>PERATUS</center></td>";
echo "<td rowspan = \"2\"><center>GPC</center></td>";
echo "<td rowspan = \"2\"><center>KPT</center></td>";
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
		echo "    <td>".$murid["NAMA"]."</td>\n";
		foreach( $mpkelas as $key2 => $sub )
		{		
			$mkh = $sub["KODMP"];
			$gmkh = "G$mkh";
			echo "    <td><center>&nbsp;".$murid["$mkh"]."</center></td>\n";
			echo "    <td><center>&nbsp;".$murid["$gmkh"]."</center></td>\n";
		}
		if($kelas=="")
			$bilmurid = count_row("SELECT * FROM markah_pelajar mkh, tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek
and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting' AND mkh.kelas='".$murid["KELAS"]."' AND mkh.jpep='$jpep'");
		echo "    <td><center>".$murid["JUMMARK"]."</center></td>\n";
		echo "    <td><center>".$murid["PERATUS"]."</center></td>\n";
		echo "    <td><center>".$murid["GPC"]."</center></td>\n";
		echo "    <td><center>".$murid["KEPUTUSAN"]."</center></td>\n";
		echo "    <td><center>".$murid["KDK"]."/$bilmurid</center></td>\n";
		echo "    <td><center>".$murid["KDT"]."/$bilmkdt</center></td>\n";
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
echo "<br><br>";
function tahap($ting)
{
	switch ($ting){
		case "P": $sting="KELAS PERALIHAN";
		break;
		case "T1":
		$sting="TINGKATAN 1";
		break;
		case "T2":
		$sting="TINGKATAN 2";
		break;
		case "T3":
		$sting="TINGKATAN 3";
		break;
		case "T4":
		$sting="TINGKATAN 4";
		break;
		case "T5":
		$sting="TINGKATAN 5";
		break;
		case "D1":
		$sting="TAHUN 1";
		break;
		case "D2":
		$sting="TAHUN 2";
		break;
		case "D3":
		$sting="TAHUN 3";
		break;
		case "D4":
		$sting="TAHUN 4";
		break;
		case "D5":
		$sting="TAHUN 5";
		break;
		case "D6":
		$sting="TAHUN 6";
		break;
	}
return $sting;
}
?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>