<?php 
include 'config.php';
$m = $_GET['data'];
list($nokp, $kodsek)=split('[|]', $m);

$qlevel = oci_parse($conn_sispa,"SELECT * FROM login WHERE nokp='$nokp' AND kodsek='$kodsek'");
//die("SELECT * FROM login WHERE nokp='$nokp' AND kodsek='$kodsek'");
oci_execute($qlevel);
$row = oci_fetch_array($qlevel);
$level = $row['LEVEL1'];
//die($level);

$sql = oci_parse($conn_sispa,"UPDATE login SET user1='', pswd='', level1='$level' WHERE nokp='$nokp' AND kodsek='$kodsek'");
//die("UPDATE login SET user1='', pswd='', level1='$level' WHERE nokp='$nokp' AND kodsek='$kodsek'");
oci_execute($sql);
require 'reset_pswd.php';
?> 