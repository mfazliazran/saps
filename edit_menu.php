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
<p class="subHeader">Kemaskini Menu</p>
<?php
// capaian mesti melalui page admin
global $username;
global $conn_sispa;

if ($_GET["hapus"]=="1"){
    $id=(int)$_GET["id"];
    $sql1="delete from menu where menuid='$id'";
    $resmenu=oci_parse($conn_sispa,$sql1);
	oci_execute($resmenu);
	pageredirect("edit_menu.php");
}

function displayrecord($data,$submenu,$adaheader)
{
$hlcolor= "#E5F3FB";
$hlcolor1="#CCFFFF";
$ncolor="#FFFFFF";	
$altcolor="#57C9FF";
$altcolor1="#57C9FF";
//$altcolor1="#57C900";

global $conn_sispa;
  if ($submenu==1){
    if($adaheader)
      echo "<tr bgcolor='$hlcolor1' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$hlcolor1'\">"; 
	else
      echo "<tr bgcolor='$altcolor1' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$altcolor1'\">"; 
  }	
  else if ($submenu==2)
    echo "<tr bgcolor='$ncolor' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$ncolor'\">"; 
  else
    echo "<tr bgcolor='$altcolor' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$altcolor'\">"; 

  $parentid = $data["MENUID"];
  $menu = $data["MENUTITLE"];
  $url=$data["MENULINK"];
  $jenis = $data["MENUTYPE"]; 
  $aktif = $data["ACTIVE"];
  $menupos = $data["MENUPOS"];
  $menugroup = $data["MENUGROUP"];
  
  echo "<td>";
  if ($submenu==1){
    echo ">>&nbsp;";//&nbsp;&nbsp;&nbsp;&nbsp;";
    $sql1="select count(*) as cnt from menu where menuparent='$parentid'";
    $resmenu=oci_parse($conn_sispa,$sql1);
	oci_execute($resmenu);
	$data2=oci_fetch_array($resmenu);
	$cntsubmenu=$data2["CNT"];
  }	
  else if ($submenu==2)
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  else {
     $sql1="select count(*) as cnt from menu where menuheader='$parentid'";
     $resmenu=oci_parse($conn_sispa,$sql1);
	 oci_execute($resmenu);
	 $data2=oci_fetch_array($resmenu);
	 $cntmenu=$data2["CNT"];
  }	 
  echo "$menu</td>";
  echo "<td>$url</td>";
  echo "<td>$menugroup</td>";
  echo "<td>$jenis</td>"; 
  echo "<td>$menupos</td>";
  echo "<td>$aktif</td>";
  echo "<td>&nbsp;<a href='b_tambah_menu.php?id=$parentid'><img src='images/edit.png' border='0' alt='Kemaskini'></a>";
  if (($submenu==1 and $cntsubmenu==0) or $submenu==2 or ($submenu==0 and $cntmenu==0)) //papar butang hapus jika menu tiada submenu
     echo "&nbsp;&nbsp;<a href='edit_menu.php?hapus=1&id=$parentid' onclick='return confirm(\"Hapuskan rekod ?\");'><img src='images/drop.png' border='0' alt='Hapus'></a></td>";

  echo "</tr>";
}

?>
<TABLE id="list_table" width="80%">
<tr><td ><input type="button" name="Tambah" value="Tambah" onclick="location.href='b_tambah_menu.php';">&nbsp;<input type="button" value="Susun Semula" name="btnSusun" onClick="location.href='admin.php?module=menu&task=susun';"></td></tr>
<tr><td>
<TABLE cellpadding="3" cellspacing="0" border="1" width="100%">
 <?php

    //MULA PAPAR MENU
	$query = "SELECT menuid,menutitle,menutype,active,menupos,menugroup FROM menu where menutype='header' or (menutype='menu' and menuheader=0) order by menupos";
	//echo "$query<br>";		   
    $result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	    echo "<tr bgcolor='#6699CC'><td>Tajuk</td><td>Pautan</td><td>Kumpulan</td><td>Jenis</td><td>Susunan<td>Aktif ?</td><td width=\"40\">Tindakan</td></tr>";
	    while ($data=oci_fetch_array($result)) {
             $m_id=$data["MENUID"];
             $menutype=$data["MENUTYPE"];
			 if ($menutype=='header'){ //MENU ADA HEADER
			     displayrecord($data,0,1);
				 $qrymenu = "SELECT menuid,menutitle,menutype,active,menupos,menugroup FROM menu where menutype='menu' and menuheader='$m_id' order by menupos";
				 //echo "*$qrymenu<br>";
				 $result_menu = oci_parse($conn_sispa,$qrymenu);
				 oci_execute($result_menu);
				 while ($data_menu=oci_fetch_array($result_menu)) {
					$menuid=$data_menu["MENUID"];
					displayrecord($data_menu,1,1);
				    $qrysubmenu = "SELECT menuid,menutitle,menutype,active,menupos,menulink,menugroup FROM menu where menutype='module' and menuparent='$menuid' order by menupos";
				    //echo "**$qrysubmenu<br>";
				    $result_submenu = oci_parse($conn_sispa,$qrysubmenu);
				    oci_execute($result_submenu);
				    while ($data_submenu=oci_fetch_array($result_submenu)) {
				       displayrecord($data_submenu,2,1);
				    }   
			    } //$data_menu 
			 }  //TAMAT MENU ADA HEADER
			 else { //MENU TIADA HEADER
		       displayrecord($data,1,0);
			   $qrysubmenu = "SELECT menuid,menutitle,menutype,active,menupos,menulink,menugroup FROM menu where menutype='module' and menuparent='$m_id' order by menupos";
			   //echo "**$qrysubmenu<br>";
			   $result_submenu = oci_parse($conn_sispa,$qrysubmenu);
			   oci_execute($result_submenu);
			   while ($data_submenu=oci_fetch_array($result_submenu)) {
			     displayrecord($data_submenu,2,0);
			   }  
			 } //TAMAT MENU TIADA HEADER
	} //end while 
    //TAMAT PAPAR MENU

		?>
   </table>					
		<tr><td colspan="4"><input type="button" name="Tambah" value="Tambah" onclick="location.href='b_tambah_menu.php';">&nbsp;<input type="button" value="Susun Semula" name="btnSusun" onClick="location.href='admin.php?module=menu&task=susun';"></td></tr>

		</td>
	</tr>
</table>
</td>
<?php include 'kaki.php';?> 
