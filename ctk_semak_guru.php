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
$kodsek =$_POST['kodsek'];
$nokp = $_POST['nokp'];
$nama =  $_POST['nama']; 
$jantina =  $_POST['jantina']; 
$bil_L= $_POST['bil_L']; 
$bil_P =$_POST['bil_P'];
$jumlah = $_POST['jumlah'];
$user =$_POST['user'];
$pswd = $_POST['pswd'];


echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"include/kpm.css\">";
echo "<center><h3>Senarai Nama Guru</h3></center><br>";
echo "<br>";
echo "<table width=\"800\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "   <td align=center><b>BIL</b></td>\n";
echo "   <td align=center><b>NO.KP</b></td>\n";
echo "   <td align=center><b>NAMA</b></td>\n";
echo "  <td align=center><b>JANTINA</b></td>\n";
echo "  <td align=center><b>STATUS DAFTAR</b></td>\n";
echo "  </tr>\n";

	$juma=0;
for ( $i = 1; $i<= $bil; $i++){

//echo "kodsek=$kodsek | darjah=$darjah[$i] | bilL=$bil_L[$i] | bilP=$bil_P[$i] | jumpel=$jum_pel[$i] | daftar=$daftar[$i] |"; 

if(($user[$i]=='') AND ($pswd[$i]=='')){
	$daftar[$i] =  "<center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center>";}
	else { $daftar[$i] =  "<center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center>";}
		
	echo "  <tr>\n";
	echo "    <td><center>$i</center></td>\n";
	echo "    <td align=center>$nokp[$i]</td>\n";
	echo "    <td>$nama[$i]</td>\n";
	echo "    <td><center>$jantina[$i]</center></td>\n";
	echo "    <td><center>$daftar[$i]</center></td>\n";



$juma = $juma + $jum_pel[$i];

 
}
/*
echo "<tr>\n";
echo "<td colspan=\"4\"><center>Jumlah Pelajar</center></td>";
echo "<td colspan=\"1\"><center>$juma</center></td>";
echo "</tr>\n";
*/
echo "<tr>";
echo "<td colspan=\"4\"><center>Bilangan Guru Lelaki</center></td>";
echo "<td colspan=\"1\"><center>$bil_L</center></td>";
echo "</tr>\n";
echo "<tr>";
echo "<td colspan=\"4\"><center>Bilangan Guru Perempuan</center></td>";
echo "<td colspan=\"1\"><center>$bil_P</center></td>";
echo "</tr>\n";
echo "<tr>";
echo "<td colspan=\"4\"><center>Jumlah Guru</center></td>";
echo "<td colspan=\"1\"><center>$jumlah</center></td>";
echo "</tr>\n";


echo "</table>\n";
//message("Laporan PPD Telah Dihantar ke JPN",1);
//location("nmiss-dae.php?tahun=$tahun&&jpep=$jpep&&kodppd=kodppd&&ting=$ting");

?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>