<html>
<body>
<STYLE type="text/css">
@media print {
  #mybutton { display:none; visibility:hidden; }

  #mybutton2 { display:none; visibility:hidden; }
 
}



</STYLE>

<style type="text/css">
.style1 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 16px;
	color: #000000;
 	font-weight: bold; 
}

.style2 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;
 
}

.style3 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;
 	font-weight: bold; 
}

</style>
<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>

<?php

include 'config.php';
include 'fungsi.php';

$bil = $_POST['bil']; 
$kodsek =$_POST['kodsek'];
$darjah = $_POST['darjah'];
$bil_L =  $_POST['bil_L']; 
$bil_P =  $_POST['bil_P']; 
$jum_pel =  $_POST['jum_pel']; 

echo "<center><h3>ENROLMEN MURID MENGIKUT TINGKATAN</h3></center>";
	echo "<br><br>";
	echo "<table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">BIL</th>\n";
	echo "    <th scope=\"col\">TINGKATAN</th>\n";
	echo "    <th scope=\"col\">L</th>\n";
	echo "    <th scope=\"col\">P</th>\n";
	echo "    <th scope=\"col\">JUMLAH</th>\n";
	echo "  </tr>\n";
	

	$juma=0;
for ( $i = 1; $i<= $bil; $i++){

//echo "kodsek=$kodsek | darjah=$darjah[$i] | bilL=$bil_L[$i] | bilP=$bil_P[$i] | jumpel=$jum_pel[$i] | bil=$bil[$i] |"; 

echo "$juma[$i]";

	echo "  <tr>\n";
		echo "    <td><center>$i</center></td>\n";
		echo "    <td><center>".strtoupper($darjah[$i])."</a></center></td>\n";
		echo "    <td><center>$bil_L[$i]</center></td>\n";
		echo "    <td><center>$bil_P[$i]</center></td>\n";
		echo "    <td><center>$jum_pel[$i]</center></td>\n";
		echo "<tr>";



$juma = $juma + $jum_pel[$i];

 
}

echo "<tr>\n";
echo "<td colspan=\"4\"><center>Jumlah Pelajar</center></td>";
echo "<td colspan=\"1\"><center>$juma</center></td>";
echo "</tr>\n";
echo "</table>\n";
//message("Laporan PPD Telah Dihantar ke JPN",1);
//location("nmiss-dae.php?tahun=$tahun&&jpep=$jpep&&kodppd=kodppd&&ting=$ting");

?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>