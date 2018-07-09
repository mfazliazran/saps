<?php 
include 'config.php';
include 'fungsi.php';

$status = $_POST['status'];
$tahun = $_POST['tahun'];
$tarikhmula1 = $_POST['tarikhbuka'];
$tarikhtamat1 = $_POST['tarikhtamat'];
$d = $_POST['jpep'];
list ($jpep, $rank)=split('[/]', $d);
//echo "$status $tahun $jpep $rank $tarikhmula1 $tarikhtamat1 <br>";

$date = str_replace('/', '-', $tarikhmula1);
$tarikhmula =  date('Y-m-d', strtotime($date));

$date2 = str_replace('/', '-', $tarikhtamat1);
$tarikhtamat =  date('Y-m-d', strtotime($date2));
//echo "$tarikhmula $tarikhtamat";
//die();
$querydata=oci_parse($conn_sispa,"SELECT * FROM kawal_pep WHERE tahun='$tahun' AND  jpep='$jpep'");
oci_execute($querydata);
$bil = count_row("SELECT * FROM kawal_pep WHERE tahun='$tahun' AND  jpep='$jpep'");

if ($bil > 0){
	$stmt = oci_parse($conn_sispa,"UPDATE kawal_pep SET status='$status',mula_pengisian='$tarikhmula', tamat_pengisian='$tarikhtamat' WHERE tahun='$tahun' AND  jpep='$jpep'");
	oci_execute($stmt);
	message("DATA TELAH DIKEMASKINI",1);
	location("papar-tahun.php");
	}
	else {
	$stmt = oci_parse($conn_sispa,"INSERT INTO kawal_pep (tahun, jpep, status, rank, mula_pengisian, tamat_pengisian) VALUES ('$tahun','$jpep','$status','$rank', '$tarikhmula' , '$tarikhtamat')");
	oci_execute($stmt);
	message("DATA TELAH DIMASUKKAN",1);
	location("papar-tahun.php");
	}

?>