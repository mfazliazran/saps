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
&nbsp;&nbsp;<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>
<?php
include 'config.php';
include 'fungsi.php';

$bilrow = $_POST['bilrow']; 
$bilm = $_POST['bilm'];
$nama =  $_POST['nama']; 
$tahun =  $_POST['tahun']; 
$kodsek =  $_POST['kodsek']; 
$bilk = $_POST['bilk']; 
$ting =  $_POST['ting']; 
$kelas =  $_POST['kelas']; 
$mp = $_POST['mp'];
$bil_mark = $_POST['bil_mark']; 
$bilamp = $_POST['bilamp']; 
$bilammp = $_POST['bilammp'];
$num = $_POST['num'];
$bilk = $_POST['bilk'];


function ting($ting){
	switch ($ting){
		case "T1":
		$tkt="TINGKATAN SATU";
		break;
		case "T2":
		$tkt="TINGKATAN DUA";
		break;
		case "T3":
		$tkt="TINGKATAN TIGA";
		break;
		case "T4":
		$tkt="TINGKATAN EMPAT";
		break;
		case "T5":
		$tkt="TINGKATAN LIMA";
		break;
		case "P":
		$tkt="PERALIHAN";
		break;
		case "D1":
		$npep="TAHUN SATU";
		break;
		case "D2":
		$tkt="TAHUN DUA";
		break;
		case "D3":
		$tkt="TAHUN TIGA";
		break;
		case "D4":
		$tkt="TAHUN EMPAT";
		break;
		case "D5":
		$tkt="TAHUN LIMA";
		break;
		case "D6":
		$tkt="TAHUN ENAM";
		break;
	}
return $tkt;
}

function pep($kodpep){
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
	}
return $npep;
}

echo "</p>";
echo "<h7><b><center>SENARAI SEMAK KEMASUKAN DATA ".pep($jpep)."<br>MENGIKUT MATA PELAJARAN KELAS<br>".ting($ting)." TAHUN ".$tahun."</b></h7></center><br><br>";
echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "  <tr align=\"center\">\n";
echo "    <td>BIL</td>\n";
echo "    <td>KELAS</td>\n";
echo "    <td>MATA PELAJARAN</td>\n";
echo "    <td>NAMA GURU</td>\n";
echo "    <td width='7%'>BIL<br>AMBIL</td>\n";
echo "    <td width='7%'>BIL<br>MARKAH</td>\n";
echo "    <td>STATUS<br>MARKAH</td>\n";
echo "  </tr>\n";

//echo "$num[1] | $num[2] | $num[3] | $num[4] | $num[5] | $num[6]<br>";

$kls = ""; $baris = 1 ;
for ( $i = 1; $i<= $bilk; $i++)
{
	
	//echo " $bilmp[$i]  |$bilmurid[$i]  | $nama[$i] | $bilrow | $ting | $tahun | $ting | $jpep[$i] | $bil_mark[$i] | $bilammp[$i] | $kelas[$i] | $mp[$i]<br>";
	//echo " $bil_mark[$i]  |$bil_etr[$i] <br>";
	
echo "  <tr>\n";
//echo "  <td rowspan =\"$num[$i]\"><center>$num</center></td>";
//echo "  <tr>\n";
if ($kelas[$i] <> $kls){
    echo "	<td rowspan =\"$num[$baris]\"><center>$baris</center></td>";
	echo "	<td rowspan =\"$num[$baris]\"><center>$ting[$i]<br>$kelas[$i] <br>[$bilm[$i] Murid]</center></td>";
	
$baris++ ;
echo "<p STYLE='page-break-after: always'>";
//echo "	<td ><center>$ting&nbsp;&nbsp;&nbsp; $kelas[$i] <br>[$bilmurid[$i] Murid]</center></td>";
}
echo  "<td>".$mp[$i]."&nbsp;</td><td>&nbsp;".$nama[$i]."</td><td><center>".$bilammp[$i]."</center></td>";

if(($bil_mark[$i] == $bilammp[$i])){
					echo  "<td><center>$bil_mark[$i]</center></td><td><center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center></td>";
					 $mpok = $mpok + 1;
				} else {
						echo  "<td><center>$bil_mark[$i]</center></td><td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></td>";
						}

//echo "  </tr>\n";
$kls = $kelas[$i];
echo "<p>";
echo "  </tr>\n";

}

echo "  </p>\n";
echo "  </tr></table>\n";
?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>