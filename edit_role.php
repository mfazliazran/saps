<?php 
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';
if ($_SESSION['level']<>"7")
{
  pageredirect("die.php");
}
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Role</p>
<?php
// capaian mesti melalui page admin
global $username;
global $conn_sispa;

if ($_GET["hapus"]=="1"){
    $id=(int)$_GET["id"];
    $sql1="delete from role where id='$id'";
	//echo "$sql1<br>";
    $resmenu=oci_parse($conn_sispa,$sql1);
	oci_execute($resmenu);
	pageredirect("edit_role.php");
}

function displayrecord($data)
{
$hlcolor= "#E5F3FB";
$hlcolor1="#CCCCCC";
$ncolor="#FFFFFF";	
$altcolor="#57C9DD";

global $conn_sispa;
 echo "<tr bgcolor='$ncolor' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$ncolor'\">"; 

  $id = $data["ID"];
  $role = $data["ROLE"]; 
  $startup = $data["STARTUP"]; 
  
  echo "<td>$id</td>"; 
  echo "<td>$role</td>";
  echo "<td>$startup</td>";
  echo "<td>&nbsp;<a href='b_tambah_role.php?id=$id'><img src='images/edit.png' border='0' alt='Kemaskini'></a>";
  echo "&nbsp;&nbsp;<a href='edit_role.php?hapus=1&id=$id' onclick='return confirm(\"Hapuskan rekod ?\");'><img src='images/drop.png' border='0' alt='Hapus'></a></td>";

  echo "</tr>";
}

?>
<TABLE id="list_table" width="80%">
<tr><td ><input type="button" name="Tambah" value="Tambah" onclick="location.href='b_tambah_role.php';"></td></tr>
<tr><td>
<TABLE cellpadding="3" cellspacing="0" border="1" width="100%">
 <?php

	//MULA
	 $qryrole = "SELECT id,role,startup FROM role order by id";
	 $result_role = oci_parse($conn_sispa,$qryrole);
	 oci_execute($result_role);
     echo "<tr bgcolor='#6699CC'><td>ID</td><td>Role</td><td>Startup</td><td width=\"40\">Tindakan</td></tr>";
	 while ($data_role=oci_fetch_array($result_role)) {
		   displayrecord($data_role);
     }		
		?>
   </table>					
		<tr><td colspan="4"><input type="button" name="Tambah" value="Tambah" onclick="location.href='b_tambah_role.php';"></td></tr>

		</td>
	</tr>
</table>
</td>
<?php include 'kaki.php';?> 
