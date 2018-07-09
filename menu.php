<?php
/*session_start();
include 'kepala.php';
include_once('config.php');
*/

function hiddenmenu($kod,$hide)
{
 global $level;
 $hidden=0;
 
 if ($hide=="1"){
	 if ($level=="5"){//PPD
	   if ($_SESSION["kodsek"]<>$_SESSION["kodppd2"])
		 $hidden=1;
	 }
	 else if ($level=="6"){//JPN
	   if ($_SESSION["kodsek"]<>$_SESSION["kodjpn"])
		 $hidden=1;
	 }
	 else if (($kod=="BPI" or $kod=="BPTV" or $kod=="BPKHAS" or $kod=="SBT" or $kod=="SKK" or $kod=="LP" or $kod=="SBP")){
		 $hidden=1;
	 }	 

  }//$hide=1;	 
  return($hidden);	 
}
/* 
  -----------------------------------------------------
  | DYNAMIC MENU (Kemaskini 06 Mar 2015 - SHAFIE)     |
  | 1. Untuk tambah role baru - edit_role.php         |
  | 2. Untuk tambah menu baru - edit_menu.php         |
  | 3. Untuk grant menu kepada role - aksesmenu.php   |
  | --------------------------------------------------|
  | Login sebagai superadmin utk akses program        |
  ----------------------------------------------------- 
  */
?>
<!--menu mula -->
<td width="170" valign="top" class="leftColumn">
<!--<p>&nbsp;</p>-->
<table width="100%"  cellspacing="0" cellpadding="5">
<tr>
<td >
<?php if(trim($_SESSION['SESS_MEMBER_ID']=='')) { } else {?>
<!--<div id="sideBarNews">-->
<div id="newsHeader">:: PROFIL</div>
<div id="sideBarNewsContent">
<div align="center">
<b><?php include 'profil.php';?></b><br>
<a href="logout.php" style="text-decoration:none;"><img src="images/keluar.png" width="30" height="30" border="0" alt="Logout"><br>Keluar Sistem</a>
</div> 
</div>
<?php 

include 'profil_sekolah.php'; }

//MULA PAPAR MENU
  $qrystr_h="select menu.menuid,menu.menutitle,menu.menulink,menu.menutype,menu.hidden from menu,menu_access
           where menu.menuid=menu_access.menuid and role='$level' and ( menutype='header' or (menutype='menu' and menuheader=0) ) 
		   and active='1' order by menupos";
 //echo "$qrystr_h<br>";
 $result_h = oci_parse($conn_sispa,$qrystr_h);
 oci_execute($result_h);
 while ($row_h=oci_fetch_array($result_h))
{
  $m_id=$row_h["MENUID"];
  //if ($row_h["MENUTITLE"]=="MENU ADMIN" and ($_SESSION["level"]=="8" or $_SESSION["level"]=="9" or $_SESSION["level"]=="10"
//	    or $_SESSION["level"]=="11" or $_SESSION["level"]=="12" or $_SESSION["level"]=="13" or $_SESSION["level"]=="14" or $_SESSION["level"]=="15"))
//     $m_title="ANALISA PEPERIKSAAN";	 
//   else
  $m_title=$row_h["MENUTITLE"];
  
  $m_type=$row_h["MENUTYPE"];
  $hidden=$row_h["HIDDEN"];

  if (!hiddenmenu($_SESSION["kodsek"],$hidden)){
?>

<div class="glossymenu">
<?php if ($m_type=="header") { 
?>
<div id="newsHeader"><?php echo $m_title;?></div>
<?php
 }
 else { ?>
  <a class="menuitem submenuheader" href=""><font color="#80FF00" face="Trebuchet MS,helvetica,sans-serif"><?php echo $m_title;?></font></a>
<?php
  } 

  if ($m_type=="header") {
  //SUB MENU
  $qrystr_m="select menu.menuid,menu.menutitle,menu.menulink,menu.hidden from menu,menu_access
           where menu.menuid=menu_access.menuid and role='$level' and menutype='menu' and menuheader='$m_id' 
		   and active='1' order by menupos";
  $result_m = oci_parse($conn_sispa,$qrystr_m);
  oci_execute($result_m);
  while ($row_m=oci_fetch_array($result_m))
  {
     $menuid=$row_m["MENUID"];
     $menutitle=$row_m["MENUTITLE"];
     $hidden=$row_m["HIDDEN"];
	 if (!hiddenmenu($_SESSION["kodsek"],$hidden)){
?>
		<a class="menuitem submenuheader" href=""><?php echo $menutitle;?></a>
		<div class="submenu">
		<ul>
<?php
 //MENU ITEM
  $qrystr_sm="select menu.menuid,menu.menutitle,menu.menulink,menu.hidden from menu,menu_access
           where menu.menuid=menu_access.menuid and role='$level' and menutype='module' and menuparent='$menuid' 
		   and active='1' order by menupos";
 // echo "$qrystr_sm<br>";		   
  $result_sm = oci_parse($conn_sispa,$qrystr_sm);
  oci_execute($result_sm);
  while ($row_sm=oci_fetch_array($result_sm))
  {
     $submenuid=$row_sm["MENUID"];
     $submenutitle=$row_sm["MENUTITLE"];
     $submenulink=$row_sm["MENULINK"];
     $hidden=$row_sm["HIDDEN"];
	 
	 if (!hiddenmenu($_SESSION["kodsek"],$hidden))
	   echo "<li><a href=\"$submenulink\">$submenutitle</a></li>";

  }//while ($row_sm=oci_fetch 
?>
	  </ul>
	</div>

<?php		 
    }//if hidden
 }//while ($row_m=oci_fetch 
} //menutype==header
else {
?>
    <div class="submenu">
  <ul>
<?php	
	  $qrystr_sm="select menu.menuid,menu.menutitle,menu.menulink,menu.hidden from menu,menu_access
			   where menu.menuid=menu_access.menuid and role='$level' and menutype='module' and menuparent='$m_id' 
			   and active='1' order by menupos";
	 // echo "$qrystr_sm<br>";		   
	  $result_sm = oci_parse($conn_sispa,$qrystr_sm);
	  oci_execute($result_sm);
	  while ($row_sm=oci_fetch_array($result_sm))
	  {
		 $submenuid=$row_sm["MENUID"];
		 $submenutitle=$row_sm["MENUTITLE"];
		 $submenulink=$row_sm["MENULINK"];
		 $hidden=$row_sm["HIDDEN"];
		 
	    if (!hiddenmenu($_SESSION["kodsek"],$hidden))
	       echo "<li><a href=\"$submenulink\">$submenutitle</a></li>";

	}
?> 
  </ul>
  </div>


<?php
} 
?>
 </div>

<?php 
  }//hidden
 }//while ($row_h=oci_fetch 
echo "<br> ";
 
//TAMAT PAPAR MENU

if ($level == '7'){
?>
<div id="newsHeader">PELAWAT HARI INI</div>
<div id="sideBarNewsContent">
<div align="center">
<?php
//if ($level == '7')
//	include "include/useronline.php";
	include "semak_hit.php";
?>
</div> 
</div>
<?php
}
?>
</td>
</tr>
</table>
