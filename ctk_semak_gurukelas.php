<html>
<body>
<STYLE type="text/css">
@media print {
  #mybutton { display:none; visibility:hidden; }

  #mybutton2 { display:none; visibility:hidden; }
 
}



</STYLE>

<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>

<?php

include 'config.php';
include 'fungsi.php';

$bil = $_POST['bil']; 
$tahun = $_POST['tahun'];
$kodsek =$_POST['kodsek'];
$nama =  $_POST['nama']; 
$ting =  $_POST['ting']; 
$kelas= $_POST['kelas']; 


echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"include/kpm.css\">";
echo "<center><h3>SENARAI GURU KELAS ".$_SESSION['tahun']."</h3></center>";
echo "<br>";
echo "<table width=\"600\" border=\"1\" align=\"center\" cellpadding=\"2\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td align=center><b>BIL</b></td>\n";
echo "    <td align=center><b>NAMA</b></td>\n";
echo "    <td align=center><b>TINGKATAN</b></td>\n";
echo "    <td align=center><b>KELAS</b></td>\n";
echo "  </tr>\n";

for ( $i = 1; $i<= $bil; $i++){

//echo "bil=$bil | darjah=$darjah[$i] | bilL=$bil_L[$i] | bilP=$bil_P[$i] | jumpel=$jum_pel[$i] | daftar=$daftar[$i] |"; 

	echo "  <tr>\n";	
	echo "    <td><center>$i</center></td>\n";
	echo "    <td>$nama[$i]</td>\n";
	echo "    <td><center>$ting[$i]</center></td>\n";
	echo "    <td align=center>$kelas[$i]</td>\n";
 echo "</tr>\n"; 
}

echo "</table>\n";
//message("Laporan PPD Telah Dihantar ke JPN",1);
//location("nmiss-dae.php?tahun=$tahun&&jpep=$jpep&&kodppd=kodppd&&ting=$ting");

?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>