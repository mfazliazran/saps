<?php 
include 'config.php';
$nokp=$_POST['nokp'];
$nokppgb=$_POST['nokppgb'];

$st1=oci_parse($conn_sispa,"UPDATE login SET level1='1' WHERE nokp='$nokppgb'");
oci_execute($st1);
$st2=oci_parse ($conn_sispa,"UPDATE login SET level1='P' WHERE nokp='$nokp'");
oci_execute($st2);
require 'b_daftar_pengetua.php';
?>

 
