<?php
session_start();
include("config.php");
$m=$_GET['data'];
list ($nokptmarkah,$ting,$kelas,$kelasold)=split('[|]', $m);

$kodsek = $_SESSION["kodsek"];
$tahun = $_SESSION["tahun"];
$jpep = $_SESSION['jpep'];

if ($_SESSION['statussek']=="SM"){

if ($kelasold==""){
//echo"MAsuksini";	
$sql="DELETE FROM markah_pelajar WHERE nokp='$nokptmarkah' AND kodsek='$kodsek' AND ting='$ting' AND kelas='".str_replace(' ','_',oci_escape_string($kelas))."' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."'";

$stmt=oci_parse($conn_sispa,$sql);
oci_execute($stmt);
//echo"".$ting."|".$kelas."";
//echo"sql= $sql<br>";
pageredirect("data-semakmarkah-bermasalah.php?data=".$ting."|".str_replace(' ','_',oci_escape_string($kelas))."");

}else{
//echo"MAsuk";
$sql="DELETE FROM markah_pelajar WHERE nokp='$nokptmarkah' AND kodsek='$kodsek' AND ting='$ting' AND kelas='".str_replace(' ','_',oci_escape_string($kelasold))."' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."'";

$stmt=oci_parse($conn_sispa,$sql);
oci_execute($stmt);
//echo"".$ting."|".$kelas."";
echo"sql= $sql<br>";
pageredirect("data-semakmarkah-bermasalah.php?data=".$ting."|".str_replace(' ','_',oci_escape_string($kelas))."");
	
}

	
}
if ($_SESSION['statussek']=="SR"){
	
if ($kelasold==""){
	
$sql="DELETE FROM markah_pelajarsr WHERE nokp='$nokptmarkah' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='".str_replace(' ','_',oci_escape_string($kelas))."' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."'";

$stmt=oci_parse($conn_sispa,$sql);
oci_execute($stmt);
//echo"".$ting."|".$kelas."";
//echo"sql= $sql<br>";
pageredirect("data-semakmarkah-bermasalah.php?data=".$ting."|".str_replace(' ','_',oci_escape_string($kelas))."");

} else {
	
$sql="DELETE FROM markah_pelajarsr WHERE nokp='$nokptmarkah' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='".str_replace(' ','_',oci_escape_string($kelasold))."' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."'";

$stmt=oci_parse($conn_sispa,$sql);
oci_execute($stmt);
//echo"".$ting."|".$kelas."";
//echo"sql= $sql<br>";
pageredirect("data-semakmarkah-bermasalah.php?data=".$ting."|".str_replace(' ','_',oci_escape_string($kelas))."");	
	
}
	
}

?>
                                                    