<?php

session_start();
$timeoutseconds 	= "300";			// How long it it boefore the user is no longer online
//The following should only be modified if you know what you are doing
if($_SERVER['HTTP_X_FORWARDED_FOR']){
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
else{
  $ip=$_SERVER['REMOTE_ADDR'];
}

$scripturl = $_SERVER[’PHP_SELF’];

$timestamp=time();
$timeout=$timestamp-$timeoutseconds;
if ($_SESSION["SESS_MEMBER_ID"]<>""){
  if (count_row("select * from useronline where session_id='".session_id()."'")>0){
	  $stmt=oci_parse($conn_sispa,"update useronline set tm='$timestamp' where session_id='".session_id()."' ");
	  oci_execute($stmt);
  }
  else {
      //echo "insert into useronline(user1,tm) values ('".$_SESSION['SESS_MEMBER_ID']."','$timestamp')";
	  $stmt=oci_parse($conn_sispa,"insert into useronline(user1,tm,session_id) values ('".$_SESSION['SESS_MEMBER_ID']."','$timestamp','".session_id()."')");
	  oci_execute($stmt);
  }
}
$stmt=oci_parse($conn_sispa,"delete useronline WHERE tm<'$timeout'");
oci_execute($stmt);
if ($level == '7'){
$stmt=OCIParse($conn_sispa,"SELECT count(*) as BIL from useronline");
OCIExecute($stmt);
if(OCIFetch($stmt))
  $biluser=OCIResult($stmt,"BIL");

echo "<img src=\"images/ip.gif\"> : $biluser Orang<br>";
}
$biluser=0;

?>
