<?php
include 'auth.php';
//include 'kepala.php';
//include 'menu.php';
include_once ('config.php');
if($_SESSION['level']<>'3' and $_SESSION['level']<>'4'){ 
	die("Anda bukan SUP");
}

if($_SESSION["tahun"]<>date("Y"))
	die('Utiliti import data APDM hanya untuk tahun semasa sahaja.');
	
//if(date('Y')=='2017')
	//die("KEMUDAHAN INI DIBERHENTIKAN BUAT SEMENTARA WAKTU.");
	
set_time_limit(0);
function semak_sekolah($kodsek){
	global $conn_sispa;
	
	$sql2 = "select status from tsekolah where kodsek='$kodsek'";
	$stmt2=oci_parse($conn_sispa,$sql2);
	oci_execute($stmt2);
	if($dt2=oci_fetch_array($stmt2)){
		$status = $dt2["STATUS"];	
		return $status;
	}
}
$nokp = $_GET["nokp"];
$tahap = $_GET["tahap"];
$kelas = $_GET["kelas"];
$kodsek = $_SESSION['kodsek'];
if(semak_sekolah($kodsek)=='SM'){
	$table = "tmurid";	
}else{
	$table = "tmuridsr";	
}
if ($nokp=="")
	$sql="select * from $table where nokp is null and $tahap='$tahap' and kelas$tahap='$kelas' and tahun$tahap='".$_SESSION['tahun']."'";
else
	$sql="select * from $table where nokp='$nokp' and $tahap='$tahap' and kelas$tahap='$kelas' and tahun$tahap='".$_SESSION['tahun']."'";
$stmt=oci_parse($conn_sispa,$sql);
oci_execute($stmt);
if($dt=oci_fetch_array($stmt)){
	if ($nokp=="")
		$sqlup=oci_parse($conn_sispa,"delete from $table where KODSEK$tahap='$kodsek' AND TAHUN$tahap='".$_SESSION['tahun']."' AND $tahap='$tahap' AND KELAS$tahap='$kelas' AND NOKP is null");
	else
		$sqlup=oci_parse($conn_sispa,"update $table set KODSEK$tahap=null,TAHUN$tahap=null,$tahap=null,KELAS$tahap=null,KODSEK_SEMASA=null where KODSEK$tahap='$kodsek' AND TAHUN$tahap='".$_SESSION['tahun']."' AND $tahap='$tahap' AND KELAS$tahap='$kelas' AND NOKP='$nokp'");
	oci_execute($sqlup);
	//echo "update $table set KODSEK$tahap='',TAHUN$tahap='',$tahap='',KELAS$tahap='' where KODSEK$tahap='$kodsek' AND TAHUN$tahap='2012' AND $tahap='$tahap' AND KELAS$tahap='$kelas' AND NOKP='$nokp'";
}else{
	?>
    <script language="javascript" type="text/javascript">
	alert('Rekod pelajar tidak dijumpai.');
	</script>
    <?php
}
pageredirect("papar_semak_pelajar_apdm.php?data=$tahap|$kodsek|".str_replace('_','+',$kelas)."");
?>