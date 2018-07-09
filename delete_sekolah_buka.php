<?php
include 'config.php';
include 'fungsi.php';

session_start();

$kodsekolah = $_GET['kodsek'];

$sql="delete from bukasekolah where kodsek='$kodsekolah'";
//die($sql);
$resk=oci_parse($conn_sispa,$sql);
oci_execute($resk);

header("location:bukak_sekolah.php");

?>