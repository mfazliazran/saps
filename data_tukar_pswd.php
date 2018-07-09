<?php 
require_once ('auth.php');
include 'config.php';
include "input_validation.php";

$nokp=validate($_POST['nokp']);
$user=validate($_POST['user']);
$pswdlama=encrypt(validate($_POST['pswdlama']),"vtech%5%52018");
$pswdbaru=encrypt(validate($_POST['pswdbaru']), "vtech%5%52018");
$cpswdbaru=encrypt(validate($_POST['cpswdbaru']), "vtech%5%52018");

function encrypt($string, $key) {
	$result = '';
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	}
	return base64_encode($result);
}

$q_pswd = oci_parse($conn_sispa,"SELECT * FROM login WHERE nokp='$nokp'");

oci_execute($q_pswd);
$rowpswd = oci_fetch_array($q_pswd);


	if(($rowpswd[NOKP]==$nokp) AND ($rowpswd[USER1]==$user) AND ($rowpswd[PSWD]==$pswdlama)){
	$mysql=oci_parse($conn_sispa,"UPDATE login SET pswd= :passwordbaru1,lastchangepassword=sysdate WHERE nokp= :nokp1");
	oci_bind_by_name($mysql, ':nokp1', $nokp);
	oci_bind_by_name($mysql, ':passwordbaru1', $pswdbaru);
	oci_execute($mysql);
	require 'logout.php';
	?> <script>alert('Kata laluan telah ditukar, Sila login semula')</script> <?php
	}
	else{
	?> <script>alert('Maklumat Tidak sama Dalam Database')</script> <?php
	require 'tukar_pswd.php';
	}
?>