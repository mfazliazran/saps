<?php 
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';
if ($_SESSION['level']<>"7")
{
  die(".");
}

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Capaian Menu</p>
<script type="text/javascript">
function setCheckBox(flg)
{
var cnt=document.frmmenu.menucount.value;
for(i=1;i<=cnt;i++){
 elts=document.forms['frmmenu'].elements['cb_'+i];
 elts.checked=flg;
}
return false;
}
</script>
<?php
 global $conn_sispa;
 global $menucount;
 $menucount=0;

 if (isset($_POST["txt_role"])){
    $role=$_POST["txt_role"];
	$_SESSION["role"]=$role;
 }	
 else if (isset($_SESSION["role"]))
    $role=$_SESSION["role"];	
 ?>
 
 
<table width="750" border="0" bgcolor="#FFFFFF">
<tr><td>
<form name="frmmenu" method="post" action="simpan_aksesmenu.php">
<?php
    echo "Role&nbsp;&nbsp;&nbsp;<select name=\"txt_role\" onchange=\"document.frmmenu.action='aksesmenu.php';document.frmmenu.submit();\">";

	$query = "SELECT id,role FROM role order by role ";
    $result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	    while($data=oci_fetch_array($result)){ 
			 
		  $id = $data["ID"];
		  $name = $data["ROLE"];
	     if (!isset($role))
	       $role=$id;
		  
		  if ($id==$role)
	        echo "<option selected value=\"$id\">$name ($id)</option>";
		  else
	        echo "<option value=\"$id\">$name ($id)</option>";
		}
	echo '</select></td></tr>';
?>	

<tr><td>  <table width="100%" border="0" cellspacing="1" > 
<tr><td colspan="2"></td><input name="Simpan" type="submit" value="Simpan">&nbsp;
	  <a href="#" onClick="return setCheckBox(true);">Check All</a>&nbsp;
	  <a href="#"  onClick="return setCheckBox(false);">Uncheck All</a>
</td></tr>
<tr><td>
   <?php
     $countmenu=getmenu($role);
  ?>
  </td></tr>
    <tr>
    <td colspan="2" >
	  <input type="hidden" name="menucount" value="<?php echo $menucount ?>">
	  <input name="Simpan" type="submit" value="Simpan">&nbsp;
	  <input name="post" type="hidden" value="1">
	  <a href="#" onClick="return setCheckBox(true);">Check All</a>&nbsp;
	  <a href="#"  onClick="return setCheckBox(false);">Uncheck All</a>	  
	</td>
  </tr></table>
  </td></tr></form>
</table>



<?php
 function checkgrant($menuid,$roleid)
 {
    global $conn_sispa;
	$query = "SELECT role FROM menu_access where role='$roleid' and menuid=$menuid ";
	//echo "$query<br>";
    $result = oci_parse($conn_sispa,$query);
	oci_execute($result);
    if ($data=oci_fetch_array($result))
	  $grant=1;
	else  
	  $grant=0;
  return($grant);	  
 }
 
function displayrecord($data,$submenu,$adaheader,$roleid)
{
$hlcolor= "#E5F3FB";
$hlcolor1="#CCFFFF";
$ncolor="#FFFFFF";	
$altcolor="#57C9FF";
$altcolor1="#57C9FF";
//$altcolor1="#57C900";

global $conn_sispa;
global $menucount;

  if ($submenu==1){
    if($adaheader)
      echo "<tr bgcolor='$hlcolor1' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$hlcolor1'\">"; 
	else
      echo "<tr bgcolor='$altcolor' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$altcolor'\">"; 
  }	
  else if ($submenu==2)
    echo "<tr bgcolor='$ncolor' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$ncolor'\">"; 
  else
    echo "<tr bgcolor='$altcolor' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$altcolor'\">"; 

  $menuid = $data["MENUID"];
  $parentid = $data["MENUPARENT"];
  
  $resh=oci_parse($conn_sispa,"select menuheader from menu where menuid='$parentid'");
  oci_execute($resh);
  $datah=oci_fetch_array($resh);
  $headerid = $datah["MENUHEADER"];
  
  $menuid = $data["MENUID"];
  $menu = $data["MENUTITLE"];
  $pautan = $data["MENULINK"];
  $jenis = $data["MENUTYPE"]; 
  $aktif = $data["ACTIVE"];
  $menupos = $data["MENUPOS"];
  $menugroup = $data["MENUGROUP"];

  echo "<td>";
  if ($submenu==1){
    echo ">>&nbsp;";
    $sql1="select count(*) as cnt from menu where menuparent='$menuid'";
    $resmenu=oci_parse($conn_sispa,$sql1);
	oci_execute($resmenu);
	$data2=oci_fetch_array($resmenu);
	$cntsubmenu=$data2["CNT"];
  }	
  else if ($submenu==2)
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  else {
     $sql1="select count(*) as cnt from menu where menuheader='$menuid'";
     $resmenu=oci_parse($conn_sispa,$sql1);
	 oci_execute($resmenu);
	 $data2=oci_fetch_array($resmenu);
	 $cntmenu=$data2["CNT"];
  }	 
  echo "$menu</td>";
  echo "<td>$pautan</td>";
  echo "<td>$menugroup</td>";
  if ($aktif)
    $aktif="Ya";
  else
    $aktif="Tidak";  
  echo "<td align=\"center\">$aktif</td>";
  echo "<td align=\"center\">";
  
  
  if ($jenis=="module"){
    $menucount++;
	echo "<input size=\"5\" type=\"hidden\" name=\"menu_$menucount\" value=\"$menuid\">";
	echo "<input size=\"5\" type=\"hidden\" name=\"parent_$menucount\" value=\"$parentid\">";
	echo "<input size=\"5\" type=\"hidden\" name=\"header_$menucount\" value=\"$headerid\">";
    echo "<input type=\"checkbox\" name=\"cb_$menucount\" ";
	if (checkgrant($menuid,$roleid))
	  echo " checked ";
	echo "value=\"1\">";
  }	
  else
    echo "&nbsp;";  
  echo "</td>";
  echo "</tr>";
} 

 function getmenu($roleid)
 {
  global $conn_sispa; 
   echo "<TABLE cellpadding=\"3\" cellspacing=\"0\" border=\"1\" width=\"100%\">";

    //MULA PAPAR MENU
	$query = "SELECT menuid,menutitle,menutype,active,menupos,menugroup FROM menu where menutype='header' or (menutype='menu' and menuheader=0) order by menupos ";
    $result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	    echo "<tr bgcolor='#6699CC'><td>Tajuk</td><td>Pautan</td><td>Kumpulan</td><td width=\"40\">Aktif</td><td width=\"40\">Benarkan ?</td></tr>";
	    while ($data=oci_fetch_array($result)) {
             $m_id=$data["MENUID"];
             $menutype=$data["MENUTYPE"];
			 if ($menutype=='header'){ //MENU ADA HEADER
			     displayrecord($data,0,1,$roleid);
				 $qrymenu = "SELECT menuid,menutitle,menutype,active,menupos,menuparent,menugroup FROM menu where menutype='menu' and menuheader='$m_id' order by menupos";
				 //echo "*$qrymenu<br>";
				 $result_menu = oci_parse($conn_sispa,$qrymenu);
				 oci_execute($result_menu);
				 while ($data_menu=oci_fetch_array($result_menu)) {
					$menuid=$data_menu["MENUID"];
					displayrecord($data_menu,1,1,$roleid);
				    $qrysubmenu = "SELECT menuid,menutitle,menutype,active,menupos,menulink,menuparent,menugroup FROM menu where menutype='module' and menuparent='$menuid' order by menupos";
				    //echo "**$qrysubmenu<br>";
				    $result_submenu = oci_parse($conn_sispa,$qrysubmenu);
				    oci_execute($result_submenu);
				    while ($data_submenu=oci_fetch_array($result_submenu)) {
				       displayrecord($data_submenu,2,1,$roleid);
				    }   
			    } //$data_menu 
			 }  //TAMAT MENU ADA HEADER
			 else { //MENU TIADA HEADER
		       displayrecord($data,1,0,$roleid);
			   $qrysubmenu = "SELECT menuid,menutitle,menutype,active,menupos,menulink,menuparent,menugroup FROM menu where menutype='module' and menuparent='$m_id' order by menupos";
			   //echo "**$qrysubmenu<br>";
			   $result_submenu = oci_parse($conn_sispa,$qrysubmenu);
			   oci_execute($result_submenu);
			   while ($data_submenu=oci_fetch_array($result_submenu)) {
			     displayrecord($data_submenu,2,0,$roleid);
			   }  
			 } //TAMAT MENU TIADA HEADER

	} //end while 
    //TAMAT PAPR MENU

   echo "</table>";	
     return $countmenu;
  }
?>
</td>
<?php include 'kaki.php';?> 
</form>