<?php

session_start();
include 'config.php';
include 'fungsi.php';

global $conn_sispa;

if($_POST['hantar_helpdesk']){
	// echo "masuk";
	$notelefon = $_POST['inpNoTelefon'];
	$notelefon2 = $_POST['inpNoTelefon2'];
	$hari1 = $_POST['inpHari1'];
	$hari2 = $_POST['inpHari2'];
	$waktu1 = $_POST['inpWaktu1'];
	$waktu2 = $_POST['inpWaktu2'];
	$rehatbiasa1 = $_POST['inpRehat1'];
	$rehatbiasa2 = $_POST['inpRehat2'];
	$rehatJumaat1 = $_POST['inpRehatJumaat1'];
	$rehatJumaat2 = $_POST['inpRehatJumaat2'];
	$datetime=oradate(date("d-m-Y"));
	$savedBy = $_SESSION['nokp'];

	// echo $savedBy;
	$query1 = "UPDATE HELPDESK SET NO_TELEFON='$notelefon', NO_TELEFON2='$notelefon2', HARI1='$hari1', HARI2='$hari2', WAKTU1='$waktu1', WAKTU2='$waktu2', REHAT_BIASA1='$rehatbiasa1', REHAT_BIASA2='$rehatbiasa2', REHAT_JUMAAT1='$rehatJumaat1', REHAT_JUMAAT2='$rehatJumaat2', TARIKH='$datetime', SIMPAN_OLEH='$savedBy'";
	// echo $query;
	// die($query);
	$result1 = oci_parse($conn_sispa,$query1);
	$exec = oci_execute($result1);

	if (!$exec) {
	    $e = oci_error($result1);  // For oci_execute errors pass the statement handle
	    print htmlentities($e['message']);
	    print "\n<pre>\n";
	    print htmlentities($e['sqltext']);
	    printf("\n%".($e['offset']+1)."s", "^");
	    print  "\n</pre>\n";
	}

	if($result1){
		message("Data Helpdesk Telah Dikemaskini",1);
		location("helpdesk.php");
	} else {
		message("error");
	}
	
}