<?php 
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Menu</p>
<?php
 global $conn_sispa;


 
 function checkmenuaccess($roleid,$menuid)
 {
    global $conn_sispa;
	$query = "SELECT role FROM menu_access where role='$roleid' and menuid='$menuid' ";

    $result = oci_parse($conn_sispa,$query);
	oci_execute($result);
    if ($data=oci_fetch_array($result))
	  $exist=1;
	else  
	  $exist=0;
  return($exist); 
 }
 
  function deleteparent($roleid,$parentid)
 {

    global $conn_sispa;
	$query = "SELECT menu.menuid FROM menu_access,menu WHERE role = '$roleid' AND menu_access.menuid=menu.menuid and menu.menuparent = '$parentid' ";
    $result = oci_parse($conn_sispa,$query);
	oci_execute($result);
    if ($data=oci_fetch_array($result)) {
	   $qry1="delete from menu_access where role='$roleid' and  menuid='$parentid'";
	   $res=oci_parse($conn_sispa,$qry1);
 
	   //oci_execute($res);
    }
 }

  $menucount=$_REQUEST["menucount"];  
  $role=$_REQUEST["txt_role"];  
  for ($cnt=1;$cnt<=$menucount;$cnt++){
     $grant=$_REQUEST["cb_$cnt"];
	 $menuid=$_REQUEST["menu_$cnt"]; 
	 $parentid=$_REQUEST["parent_$cnt"]; 
	 $headerid=$_REQUEST["header_$cnt"]; 
	 if ($grant=="1") {
	   if (checkmenuaccess($role,$menuid)==0) {
           $qry="insert into menu_access(role,menuid) values('$role','$menuid')"; 
           $res1=oci_parse($conn_sispa,$qry);
		   oci_execute($res1);
		   if (checkmenuaccess($role,$parentid)==0){
              $qry="insert into menu_access(role,menuid) values('$role','$parentid')"; 
			  $res1=oci_parse($conn_sispa,$qry);
 			  oci_execute($res1);
		   }
		   if($headerid<>"0" or $headerid<>""){
			   if (checkmenuaccess($role,$headerid)==0){
				  $qry="insert into menu_access(role,menuid) values('$role','$headerid')"; 
				  $res1=oci_parse($conn_sispa,$qry);
				  oci_execute($res1);
			   }
		   }
	   }
	 } //$grant==1  	   
     else {		   
       $qry="delete from menu_access where role=$role and menuid=$menuid"; 
       $res1=oci_parse($conn_sispa,$qry);
	   oci_execute($res1);
	   deleteparent($role,$parentid);
	 }  
       //sql_query($qry,$dbi);
	  
  } //for

?>
<script type="text/javascript">
alert('Rekod berjaya disimpan.');
location.href='aksesmenu.php';
</script>
</td>
<?php include 'kaki.php';?> 
