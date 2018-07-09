<?php 
include 'config.php';
include 'fungsi.php';
$nokp = $_POST['nokp'];
$nokplama = $_POST['nokplama'];
$nama = oci_escape_string($_POST['nama']);
$jantina = $_POST['jan'];
$level = $_POST['level'];
$negeri = $_POST['negeri'];
$daerah = $_POST['daerah'];
$namasek = $_POST['namasek'];
$statussek = $_POST['ssek'];
$kodsek = $_POST['kodsek'];
$ting = $_POST['ting'];
$kelas = $_POST['kelas'];

$stmt = oci_parse($conn_sispa,"UPDATE sub_guru SET nokp= :nokp1, nama= :nama1 WHERE nokp= :nokplama1");
oci_bind_by_name($stmt, ':nokp1', $nokp);
oci_bind_by_name($stmt, ':nama1', $nama);
oci_bind_by_name($stmt, ':nokplama1', $nokplama);
oci_execute($stmt);

oci_free_statement($stmt);
$qry = oci_parse($conn_sispa,"UPDATE login SET nokp= :nokp1, nama= :nama1, jan= :jantina1, kodsek= :kodsek1, statussek= :statussek1 WHERE nokp= :nokplama1");
oci_bind_by_name($qry, ':nokp1', $nokp);
oci_bind_by_name($qry, ':nama1', $nama);
oci_bind_by_name($qry, ':jantina1', $jantina);
oci_bind_by_name($qry, ':kodsek1', $kodsek);
oci_bind_by_name($qry, ':statussek1', $statussek);
oci_bind_by_name($qry, ':nokplama1', $nokplama);
oci_execute($qry);

oci_free_statement($qry);
message("Data Guru Telah Dikemaskini",1);
location("edit_guru.php");
?>