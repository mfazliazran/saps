<?php
session_start();
include 'config.php';
include 'fungsi.php';
$m = $_GET['data'];
list ($tahun, $jpep, $status)=split('[/]', $m);

$sql = oci_parse($conn_sispa,"DELETE FROM KAWAL_PEP WHERE TAHUN='$tahun' AND JPEP='$jpep' AND STATUS='$status'");
//die("DELETE FROM KAWAL_PEP WHERE TAHUN='$tahun' AND JPEP='$jpep' AND STATUS='$status'");
oci_execute($sql);

message("Kawalan Peperiksaan Telah Dihapuskan",1);
location("papar-tahun.php");

?>
                                                    