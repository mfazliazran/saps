<?php 
require_once ('auth.php');
include 'config.php';
include 'fungsi.php';
	$nokp=$_GET['data'];
	$st1=oci_parse($conn_sispa,"UPDATE login SET level1='1' WHERE nokp='$nokp'");
	oci_execute($st1);
	//require 'b_daftar_pkanan.php';
	location("b_daftar_pkanan.php");
?>