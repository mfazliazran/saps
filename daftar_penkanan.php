<?php 
include 'config.php';
include 'fungsi.php';
$nokp=$_POST['nokp'];
$nokppgb=$_POST['nokppgb'];

$st1=oci_parse($conn_sispa,"UPDATE login SET level1='1' WHERE nokp='$nokppgb'");
//echo "UPDATE login SET level1='1' WHERE nokp='$nokppgb'<br>";
oci_execute($st1);
$st2=oci_parse ($conn_sispa,"UPDATE login SET level1='PK' WHERE nokp='$nokp'");
//die("UPDATE login SET level1='PK' WHERE nokp='$nokp'");
oci_execute($st2);
//require 'b_daftar_pkanan.php';
location('b_daftar_pkanan.php');
?>

 
