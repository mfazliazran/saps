<?php 
session_start();
include 'config.php';
include 'fungsi.php';
function susun($ordering,$old_ordering,$flg)
{
global $conn_sispa;

  if ($flg==0)
    $query = "SELECT id,susunan FROM umum where susunan <= $ordering and susunan > $old_ordering order by susunan ";
  else
    $query = "SELECT id,susunan FROM umum where susunan >= $ordering and susunan < $old_ordering order by susunan ";
	//echo $query."<br>";
	$q_umum=oci_parse($conn_sispa,$query);
	oci_execute($q_umum);
	while($data=oci_fetch_array($q_umum)){
     $id=$data["ID"];
	 $ord=$data["SUSUNAN"];
     if ($flg==0)
	    $ord--;
      else
	     $ord++;	
	$stmt = oci_parse($conn_sispa,"update umum set susunan=". $ord ." where id=$id");	
	oci_execute($stmt); 
	 //echo "update umum set susunan=". $ord ." where id=$id <br>";
  }
} //function susun 

$umum= oci_escape_string($_POST['umum']);//nl2br($_POST['umum']);
$id=$_POST['id'];
$penting=$_POST['status'];
$flg=$_POST['flg'];
$ordering=$_REQUEST["txt_ordering"];  
$old_ordering=$_REQUEST["txt_old_ordering"];
$tarikh = date("d-m-Y");
$masa = date('H:i:s', time());
if($penting==""){
	$penting = "1";	
}
if($flg=="add"){
	$qmx_umum=oci_parse($conn_sispa,"SELECT max(susunan) as maxidsusun, max(id) as maxid FROM umum");
	oci_execute($qmx_umum);
	while($datamax=oci_fetch_array($qmx_umum)){
		$maxid=$datamax["MAXID"];
		$maxidsusun=$datamax["MAXIDSUSUN"];
        $maxid++;	
		$maxidsusun++;	
		$id=$maxid;
		$idbaru=$maxidsusun;		
	}
}
if($flg=="add"){
	$stmt = oci_parse($conn_sispa,"INSERT INTO UMUM(ID, UMUM, TARIKH, MASA, PENTING, SUSUNAN) VALUES('$id','$umum','$tarikh','$masa','$penting','$idbaru')");
	//die("INSERT INTO UMUM(ID, UMUM, TARIKH, MASA, PENTING, SUSUNAN) VALUES('$id','$umum','$tarikh','$masa','$penting','$idbaru')");
}else{
	if ($old_ordering < $ordering)
		susun($ordering,$old_ordering,0);
	else  if ($old_ordering > $ordering)
		susun($ordering,$old_ordering,1);
	$stmt = oci_parse($conn_sispa,"UPDATE umum SET umum='$umum',penting='$penting', susunan='$ordering' WHERE id='$id'");
}
//die();
oci_execute($stmt);

message("Pengumuman Telah Dikemaskini",1);
location("b_umum.php");
?>
 