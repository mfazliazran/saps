<?php 
include 'config.php';
include 'fungsi.php';

$m=$_GET['data'];
list ($kod, $js)=split('[/]', $m);

if ($js =="SM"){
   $cnt=count_row("select $kod from markah_pelajar where $kod is not null");
   if ($cnt>0)
    message("Subjek tidak boleh dihapuskan kerana sekolah telah memasukkan markah",1);
  else {	
	$sqltmp="DELETE FROM mpsmkc WHERE kod='$kod'";
	$resultmp=oci_parse($conn_sispa,$sqltmp);
	oci_execute($resultmp);
	$stmt=oci_parse($conn_sispa,"ALTER TABLE markah_pelajar DROP COLUMN $kod"); 
	oci_execute($stmt);
	$stmt=oci_parse($conn_sispa,"ALTER TABLE markah_pelajar DROP COLUMN G$kod"); 
	oci_execute($stmt);
	message("Mata Pelajaran Telah DiHapus!",1);
 }	
 location("senarai-mp.php?data=SM");

}
if ($js =="SR"){
   $cnt=count_row("select $kod from markah_pelajarsr where $kod is not null");
   if ($cnt>0)
    message("Subjek tidak boleh dihapuskan kerana sekolah telah memasukkan markah",1);
  else {	
	$sqltmp_sr="DELETE FROM mpsr WHERE kod='$kod'";
	$resultmp_sr=oci_parse($conn_sispa,$sqltmp_sr);
	oci_execute($resultmp_sr);
	$stmt=oci_parse($conn_sispa,"ALTER TABLE markah_pelajarsr DROP COLUMN $kod"); 
	oci_execute($stmt);
	$stmt=oci_parse($conn_sispa,"ALTER TABLE markah_pelajarsr DROP COLUMN G$kod"); 
	oci_execute($stmt);
	message("Mata Pelajaran Telah DiHapus!",1);
  }	
  location("senarai-mp.php?data=SR");
}

?>