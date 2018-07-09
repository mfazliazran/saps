<?php
include "../config.php";
session_start();
$kodnegeri = $_GET["kodnegeri"];
echo "<select name=\"txtDaerah\" id=\"txtDaerah\" disabled onChange=\"senarai_Sekolah(this.value);\">";	
echo "<option value=''>-PILIH PPD-</option>";		
$sql = "SELECT PPD, KODPPD FROM tkppd where kodnegeri= :kodnegeri order by kodppd, PPD";
$qic = oci_parse($conn_sispa,$sql);
oci_bind_by_name($qic, ':kodnegeri', $kodnegeri);
oci_execute($qic);
while($row = oci_fetch_array($qic)){
	$ppd = $row["PPD"];
	$kodppd = $row["KODPPD"];
	
	echo "<option value=\"$kodppd\">$kodppd - $ppd</option>";
}
echo "</select>";
?>