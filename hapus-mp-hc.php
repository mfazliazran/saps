<?php 
include 'config.php';
include 'fungsi.php';

$m=$_GET['data'];
list ($kod, $js)=split('[/]', $m);

if ($js =="SM"){
$sqltmp="DELETE FROM sub_mr WHERE kod='$kod'";
$resultmp=oci_parse($conn_sispa,$sqltmp);
oci_execute($resultmp);
message("Mata Pelajaran Telah DiHapus!",1);
location("senarai-mp.php?data=SM");
}
if ($js =="SR"){
$sqltmp_sr="DELETE FROM sub_sr WHERE kod='$kod'";
$resultmp_sr=oci_parse($conn_sispa,$sqltmp_sr);
oci_execute($resultmp_sr);
message("Mata Pelajaran Telah DiHapus!",1);
location("senarai-mp.php?data=SR");
}
if ($js =="MA"){
$sqltmp_ma="DELETE FROM sub_ma_xambil WHERE kod='$kod'";
$resultmp_ma=oci_parse($conn_sispa,$sqltmp_ma);
oci_execute($resultmp_ma);
message("Mata Pelajaran Telah DiHapus!",1);
location("senarai-mp.php?data=MA");
}
?>