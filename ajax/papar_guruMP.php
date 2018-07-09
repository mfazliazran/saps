<?php 
session_start();
$kodsek=$_SESSION["kodsek"];
$tahun=$_SESSION["tahun"];
include "../config.php";
$kodmp=$_GET["kodmp"];
$ting = $_GET["ting"];

//echo "SELECT NOKP,NAMA kelas FROM sub_guru WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' and kodmp='$kodmp' ORDER BY NAMA";							
echo "<select name='gurump' id='gurump'><option value=''>Sila Pilih</option>";

$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT NOKP,NAMA FROM sub_guru WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' and kodmp='$kodmp' ORDER BY NAMA");
OCIExecute($kelas_sql);
while(OCIFetch($kelas_sql)) { 
	echo  "<option value='".OCIResult($kelas_sql,"NOKP")."'>".OCIResult($kelas_sql,"NAMA")."</option>";
}
echo "</select>";
?>		
