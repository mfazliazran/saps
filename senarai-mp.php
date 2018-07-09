<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Senarai Mata Pelajaran Sekolah</p>
<?php
$j_sek=$_GET['data'];

switch($j_sek){
		 case "SR": $js = "SEKOLAH RENDAH"; break;
		 case "SM": $js = "SEKOLAH MENENGAH"; break;
		}

echo "<br>";
echo "<center><h3>$namasekSENARAI MATA PELAJARAN $js</center></h3><br>";
echo "<table width=\"800\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">Kod</div></td>\n";
echo "    <td><div align=\"center\">Kod Lembaga</div></td>\n";
echo "    <td>Mata Pelajaran </td>\n";
echo "    <td>Status</td>\n";
echo "    <td>Edit</td>\n";
echo "    <td>Hapus</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
if($j_sek=="SM"){
$query_sm = "SELECT * FROM mpsmkc order by mp";
$result_sm = oci_parse($conn_sispa,$query_sm);
oci_execute($result_sm);
	$bil=0;
	while($sm = oci_fetch_array($result_sm)){
	$bil=$bil+1;
	$status_mp = $sm["STATUS_MP"];
	if($status_mp=='1'){
		$ktrg_status = "AKTIF";
	}else{
		$ktrg_status = "TIDAK AKTIF";
	}
	echo "  <td><center>$bil</center></td>\n";
	echo "  <td>".$sm["KOD"]."</td>\n";
	echo "  <td>".$sm["KODLEMBAGA"]."</td>\n";
	echo "  <td>".$sm["MP"]."</td>\n";
	echo "  <td>$ktrg_status</td>\n";
	echo "  <td><a href=edit-mp.php?data=".$sm["KOD"]."/$j_sek><center><img src = images/edit.png width=12 height=13 Alt=\"Sunting\" border=0></center></a></td>\n";
	echo "  <td><a href=hapus-mp.php?data=".$sm["KOD"]."/$j_sek onclick=\"return (confirm('Adakah anda pasti hapus ".$sm["MP"]." ?'))\"><center><img src = images/drop.png width=12 height=13 Alt=\"Hapus\" border=0></center></a></td>\n";
	echo "  </tr>\n";
	}
}
if($j_sek=="SR"){
$query_sr = "SELECT * FROM mpsr ORDER BY MP";
//echo $query_sr;
$result_sr = oci_parse($conn_sispa,$query_sr);
oci_execute($result_sr);
	$bil=0;
	while($sr = oci_fetch_array($result_sr)){
	$bil=$bil+1;
	$status_mp = $sr["STATUS_MP"];
	if($status_mp==1){
		$ktrg_status = "AKTIF";
	}else{
		$ktrg_status = "TIDAK AKTIF";
	}
	echo "  <td><center>$bil</center></td>\n";
	echo "  <td>".$sr["KOD"]."</td>\n";
	echo "  <td>".$sr["KODLEMBAGA"]."</td>\n";
	echo "  <td>".$sr["MP"]."</td>\n";
	echo "  <td>$ktrg_status</td>\n";
	echo "  <td><a href=edit-mp.php?data=".$sr["KOD"]."/$j_sek><center><img src = images/edit.png width=12 height=13 Alt=\"Sunting\" border=0></center></a></td>\n";
	echo "  <td><a href=hapus-mp.php?data=".$sr["KOD"]."/$j_sek onclick=\"return (confirm('Adakah anda pasti hapus ".$sr["MP"]." ?'))\"><center><img src = images/drop.png width=12 height=13 Alt=\"Hapus\" border=0></center></a></td>\n";
	echo "  </tr>\n";
	}
}
echo "</table>\n";
echo "<br>";
?>
</td>
<?php include 'kaki.php';?>