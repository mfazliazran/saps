<?php 
include 'config.php';
include 'fungsi.php';

$j_sek = $_POST['j_sek'];
$kod = $_POST['kodmp'];
$kodlembaga = $_POST['kodmplembaga'];
$mp = $_POST['mp'];
$kodlama = $_POST['kodmplama'];
$mplama = $_POST['mplama'];
$status_mp = $_POST["cbostatus_mp"];


if ($j_sek =="SM"){
$stmt = oci_parse($conn_sispa,"UPDATE mpsmkc SET kod='$kod', mp='$mp',kodlembaga='$kodlembaga',status_mp='$status_mp' WHERE  kod='$kodlama' AND mp='$mplama'");
oci_execute($stmt);
message("Mata Pelajaran Telah DiKemaskini!",1);
location("senarai-mp.php?data=SM");
}
if ($j_sek =="SR"){
$stmt = oci_parse($conn_sispa,"UPDATE  mpsr SET kod='$kod', mp='$mp',kodlembaga='$kodlembaga',status_mp='$status_mp' WHERE  kod='$kodlama' AND mp='$mplama'");
oci_execute($stmt);
message("Mata Pelajaran Telah DiKemaskini!",1);
location("senarai-mp.php?data=SR");
}

?>