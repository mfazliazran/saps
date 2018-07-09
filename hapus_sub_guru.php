<?php
session_start();
include("config.php");
include "input_validation.php";
$m=validate($_GET['data']);
list ($tahun, $nokp, $kodsek, $ting, $kelas, $kodmp)=explode("|", $m);
$sql="DELETE FROM sub_guru WHERE tahun=:tahun AND nokp=:nokp AND kodsek=:kodsek AND ting=:ting AND kelas=:kelas AND kodmp=:kodmp";

$stmt=oci_parse($conn_sispa,$sql);

oci_bind_by_name($stmt,":tahun",$tahun);
oci_bind_by_name($stmt,":nokp",$nokp);
oci_bind_by_name($stmt,":kodsek",$kodsek);
oci_bind_by_name($stmt,":ting",$ting);
oci_bind_by_name($stmt,":kelas",$kelas);
oci_bind_by_name($stmt,":kodmp",$kodmp);

oci_execute($stmt);

//echo $sql1;
header('Location: b_edit_sub_guru.php');
?>
                                                    