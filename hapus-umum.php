<?php 
include("config.php");
include 'fungsi.php';

$id=$_GET['id'];
//mysql_query ("DELETE FROM umum WHERE id='$id'")or die(mysql_error()); 
$stmt=OCIParse ($conn_sispa,"DELETE FROM umum WHERE id='$id'"); 
OCIExecute($stmt);

location("b_umum.php");
?>