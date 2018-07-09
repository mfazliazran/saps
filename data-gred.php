<?php 
include 'config.php';
include 'fungsi.php';

$tahap = $_POST['tahap'];
$gred = $_POST['gred'];
$min = $_POST['min'];
$max = $_POST['max'];

$stmt = oci_parse($conn_sispa,"UPDATE gred SET min='$min', max='$max' WHERE tahap='$tahap' AND gred='$gred'");
oci_execute($stmt);
message("Gred telah dikemaskini",1);
location("gred.php?data=$tahap");
?>