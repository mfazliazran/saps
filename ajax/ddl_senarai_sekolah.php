<?php
include "../config.php";
session_start();
$kodnegeri = $_GET["kodnegeri"];
$kodppd = $_GET["kodppd"];
$sql = "SELECT kodsek, namasek FROM tsekolah where kodnegerijpn= :kodnegeri and kodppd= :kodppd order by namasek";
echo "<select name=\"txtSekolah\" id=\"txtSekolah\">";	
echo "<option value=''>-PILIH SEKOLAH-</option>";		

$qic = oci_parse($conn_sispa,$sql);
oci_bind_by_name($qic, ':kodnegeri', $kodnegeri);
oci_bind_by_name($qic, ':kodppd', $kodppd);
oci_execute($qic);
while($row = oci_fetch_array($qic)){
	
	$namasek = $row["NAMASEK"];
	$kodsek = $row["KODSEK"];
	echo "<option value=\"$kodsek\">$namasek</option>";
}
echo "</select>";
?>