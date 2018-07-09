<?php
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Tambah Role</p>
<?php
 global $conn_sispa;


 if ($_POST["post"]=="1"){

  $id=$_REQUEST["id"];
  $roleid=$_REQUEST["txt_id"];
  $dbtrans=$_REQUEST["dbtrans"];
  $role=$_REQUEST["txt_role"];  
  $startup=$_REQUEST["txt_startup"];  

  if ($dbtrans=="0"){ //insert
     $qry="insert into role(id,role,startup)
	       values('$roleid','$role','$startup')"; 
  } 		   
  else if ($dbtrans=="1") { //update
     $qry="update role set id='$roleid',role='$role',startup='$startup' where id='$id'";

	     
  }
  //die("trans:$dbtrans qry:$qry");
  $res=oci_parse($conn_sispa,$qry);
  oci_execute($res);
  pageredirect("edit_role.php"); 
 
 
 } //$_POST

 $dbtrans="0";

 if ($_GET["id"]<>""){	 
	 $roleid=$_GET["id"];
	 
	 $query = "SELECT ID,ROLE,STARTUP from ROLE where id='$roleid'";
	//echo "$query<br>";		  
	 $result = oci_parse($conn_sispa,$query);
	 oci_execute($result);
	 if ($data=oci_fetch_array($result)){
	   $id=$data["ID"];
	   $role=$data["ROLE"];
	   $startup=$data["STARTUP"];
	   $dbtrans="1";
	 }
 }	 
 ?>
<form name="frmmenu" method="post" action="b_tambah_role.php">
<table id="form_table_outer" width="60%">
  <tr><td>
<table id="form_table_inner" width="100%" border="0" bgcolor="#EEF3FF" cellpadding="2" cellspacing="0">
          <tr> 
            <td width="20%">ID</td>
            <td width="80%" ><input name="txt_id"  type="text" size="10" maxlength="10" value="<?php echo $id; ?>"></td>
          </tr>
          <tr> 
            <td width="20%">Role</td>
            <td width="80%" ><input name="txt_role"  type="text" size="50" maxlength="50" value="<?php echo $role; ?>"></td>
          </tr>          <tr> 
            <td width="20%">Startup</td>
            <td width="80%" ><input name="txt_startup"  type="text" size="50" maxlength="100" value="<?php echo $startup; ?>"></td>
          </tr>
          <tr> 
            <td colspan="2" > 
              <input type="hidden" name="dbtrans" value="<?php echo $dbtrans;?>"> 
			  <input type="hidden" name="post" id="post" value="1">
			  <input type="hidden" name="id" value="<?php echo $id; ?>"> 
              <input name="Simpan" type="submit" value="Simpan"> <input name="Kembali" type="Button" value="Kembali" onclick="location.href='edit_role.php';"> 
            </td>
          </tr>
        </table></td></tr></table>
</form>

		</td>
	</tr>
</table>
</td>
<?php include 'kaki.php';?> 
