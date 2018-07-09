<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Senarai Mata Pelajaran Peperiksaan</p>
<?php
$j_sek=$_GET['data'];

switch($j_sek){
		 case "SR": $js = "Sekolah Rendah"; break;
		 case "SM": $js = "Sekolah Menengah Tingkatan 1, 2 dan 3"; break;
		 case "MA": $js = "Tidah Dikira Dalam Peperiksaan<br>Sekolah Menengah Tingkatan 4 dan 5"; break;
		}

echo "<h3><center>SENARAI MATA PELAJARAN<br>$js</center></h3><br>";
echo "<table width=\"500\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">Kod</div></td>\n";
echo "    <td>Mata Pelajaran </td>\n";
echo "    <td><center>Hapus</center></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
if($j_sek=="SM"){
$query_sm = "SELECT * FROM sub_mr ORDER BY mp";
$result_sm = oci_parse($conn_sispa,$query_sm);
oci_execute($result_sm);
$bil=0;
	while($sm = oci_fetch_array($result_sm)){
	$bil=$bil+1;
	echo "  <td><center>$bil</center></td>\n";
	echo "  <td>".$sm["KOD"]."</td>\n";
	echo "  <td>".$sm["MP"]."</td>\n";
	echo "  <td><a href=hapus-mp-hc.php?data=".$sm["KOD"]."/$j_sek onclick=\"return (confirm('Adakah anda pasti hapus ".$sm["MP"]." ?'))\"><center><img src = images/drop.png width=12 height=13 Alt=\"Hapus\" border=0></center></a></td>\n";
	echo "  </tr>\n";
	}
}
if($j_sek=="SR"){
$query_sr = "SELECT * FROM sub_sr ORDER BY mp";
$result_sr = oci_parse($conn_sispa,$query_sr);
oci_execute($result_sr);
$bil=0;
	while($sr = oci_fetch_array($result_sr)){
	$bil=$bil+1;
	echo "  <td><center>$bil</center></td>\n";
	echo "  <td>".$sr["KOD"]."</td>\n";
	echo "  <td>".$sr["MP"]."</td>\n";
	echo "  <td><a href=hapus-mp-hc.php?data=".$sr["KOD"]."/$j_sek onclick=\"return (confirm('Adakah anda pasti hapus ".$sr["MP"]." ?'))\"><center><img src = images/drop.png width=12 height=13 Alt=\"Hapus\" border=0></center></a></td>\n";
	echo "  </tr>\n";
	}
}
if($j_sek=="MA"){
$query_ma = "SELECT * FROM sub_ma_xambil ORDER BY mp";
$result_ma = oci_parse($conn_sispa,$query_ma);
oci_execute($result_ma);
$bil=0;
	while($ma = oci_fetch_array($result_ma)){
	$bil=$bil+1;
	echo "  <td><center>$bil</center></td>\n";
	echo "  <td>".$ma["KOD"]."</td>\n";
	echo "  <td>".$ma["MP"]."</td>\n";
	echo "  <td><a href=hapus-mp-hc.php?data=".$ma["KOD"]."/$j_sek onclick=\"return (confirm('Adakah anda pasti hapus ".$ma["MP"]." ?'))\"><center><img src = images/drop.png width=12 height=13 Alt=\"Hapus\" border=0></center></a></td>\n";
	echo "  </tr>\n";
	}
}
echo "</table>\n";
echo "<br>";
?>
</td>
<?php include 'kaki.php';?>