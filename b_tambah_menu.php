<?php
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Tambah Menu</p>
<?php
 global $conn_sispa;

 function susun($ordering,$old_ordering,$flg,$jenis,$menu)
 {
   global $conn_sispa;
  //echo "ordering:$ordering old_ordering:$old_ordering jenis:$jenis menu:$menu<br>"; 
  if ($flg==0){
    if ($jenis=="header" or ($jenis=="menu" and $menu=0))
       $query = "SELECT menuid,menupos FROM menu where  (menutype='header' or (menutype='menu' and menuheader=0)) and menupos <= $ordering and menupos > $old_ordering order by menupos ";
    else if ($jenis=="menu")
       $query = "SELECT menuid,menupos FROM menu where  menutype='menu' and header='$menu' and menupos <= $ordering and menupos > $old_ordering order by menupos ";
    else if ($jenis=="module")
       $query = "SELECT menuid,menupos FROM menu where menutype='module' and menuparent='$menu' and menupos <= $ordering and menupos > $old_ordering order by menupos ";
  }	
  else {
    if ($jenis=="header" or ($jenis=="menu" and $menu=0))
       $query = "SELECT menuid,menupos FROM menu where (menutype='header' or (menutype='menu' and menuheader=0)) and menupos >= $ordering and menupos < $old_ordering order by menupos "; 
    else if ($jenis<>"menu")
       $query = "SELECT menuid,menupos FROM menu where menutype='menu' and  header='$menu' and menupos >= $ordering and menupos < $old_ordering order by menupos "; 
    else if ($jenis=="module")
       $query = "SELECT menuid,menupos FROM menu where menutype='module' and menuparent='$menu' and  menupos >= $ordering and menupos < $old_ordering order by menupos "; 
  }	 
  
 //echo $query."<br>";
  $result = oci_parse($conn_sispa,$query);
  oci_execute($result);
  while ($data=oci_fetch_array($result)) {
     $id=$data["MENUID"];
	 $ord=$data["MENUPOS"];
     if ($flg==0)
	    $ord--;
      else
	     $ord++;		
	  $query="update menu set menupos=". $ord ." where id=$id";

	 $res=oci_parse($conn_sispa,$query);  
	 oci_execute($res);
	 //echo $query."<br>";
  }
} //function susun  

 if ($_POST["post"]=="1"){

  $id=$_REQUEST["id"];
  $dbtrans=$_REQUEST["dbtrans"];
  $tajuk=$_REQUEST["txt_tajuk"];  
  $jenis=$_REQUEST["txt_jenis"];  
  $menu=(int) $_REQUEST["txt_menu"];  
  $pautan=$_REQUEST["txt_pautan"]; 
  $module=$_REQUEST["txt_modul"];  
  $aktif=$_REQUEST["txt_aktif"];  
  $menuheader=$_REQUEST["txt_header"];  
  $hidden=$_REQUEST["txt_hidden"];  

  if (!isset($category))
     $category=0;
  if (!isset($aktif))
     $aktif=0;
  $menugroup=$_REQUEST["txt_group"];  
  $menupos=$_REQUEST["txt_susunan"];  
  $oldpos=$_REQUEST["txt_oldpos"];  
  
  $targetwindow="_top";
  if ($menuheader==""){
    if ($jenis=="menu")
      $menuheader="0";
	else
      $menuheader="null";
  }	
  if ($dbtrans=="0"){ //insert
  
     $resmax=oci_parse($conn_sispa,"select max(menuid) as maxid from menu");
	 oci_execute($resmax);
	 $datamax=oci_fetch_array($resmax);
	 $max=$datamax["MAXID"];
	 $menuid=$max+1;
	 
	 if ($jenis=="menu")
       $sqlmax="select max(menupos) as maxid from menu where menuheader='$menuheader'";
	 else if ($jenis=="header")
       $sqlmax="select max(menupos) as maxid from menu where menutype='header'";
	 else if ($jenis=="module")
       $sqlmax="select max(menupos) as maxid from menu where menuparent='$menu'";
     $resmax=oci_parse($conn_sispa,$sqlmax);
	 oci_execute($resmax);
	 $datamax=oci_fetch_array($resmax);
	 $max=$datamax["MAXID"];
	 $menupos=$max+1;
	 //die("sqlmax:$sqlmax menupos:$menupos");
	
     $qry="insert into menu(menuid,menuparent,menutitle,menulink,menutype,menuheader,target_window,active,menupos,hidden,menugroup)
	       values('$menuid','$menu','$tajuk','$pautan','$jenis',$menuheader,'$targetwindow','$aktif',$menupos,'$hidden','$menugroup')"; 
  } 		   
  else if ($dbtrans=="1") { //update
     $qry="update menu set menuparent='$menu',menutitle='$tajuk',menulink='$pautan',menutype='$jenis',menugroup='$menugroup',
	       menuheader=$menuheader,active='$aktif',hidden='$hidden',menupos='$menupos' where menuid='$id'";
     if ($oldpos < $menupos)
       susun($menupos,$oldpos,0,$jenis,$menu);
	 else if ($oldpos > $menupos)  
       susun($menupos,$oldpos,1,$jenis,$menu);
	     
  }
  //die($qry);
  $res=oci_parse($conn_sispa,$qry);
  oci_execute($res);
  pageredirect("edit_menu.php"); 
 
 
 } //$_POST

 $dbtrans="0";
 
     $title=$_REQUEST["txt_tajuk"];
     $menu=$_REQUEST["txt_menu"];
	 if (!$menu){
      $query = "SELECT menuid FROM menu where type='menu' order by menupos";
      $result = oci_parse($conn_sispa,$query);
	  oci_execute($result);
	  $data=oci_fetch_array($result);
      $menu=$data["ID"];	 
	 }
     $pautan=$_REQUEST["txt_pautan"];
     $aktif=$_REQUEST["txt_aktif"];


	 $jenis=$_REQUEST["txt_jenis"];

 if ($_GET["id"]<>""){	 
	 $menuid=(int) $_GET["id"];
	 
	 $query = "SELECT   MENUPARENT,MENUTITLE,MENULINK,MENUTYPE,MODULE,TARGET_WINDOW,ACTIVE,MENUPOS,MENUHEADER,HIDDEN,MENUGROUP
			  FROM menu where menuid='$menuid'";
	//echo "$query<br>";		  
	 $result = oci_parse($conn_sispa,$query);
	 oci_execute($result);
	 if ($data=oci_fetch_array($result)){
	   $title=$data["MENUTITLE"];
	   $jenis=$data["MENUTYPE"];
	   $menupos=$data["MENUPOS"];
	   $header=$data["MENUHEADER"];
	   if($jenis=="menu")
	      $menu=$data["MENUHEADER"];
	   else	  
	      $menu=$data["MENUPARENT"];
	   $aktif=$data["ACTIVE"];   
	   $hidden=$data["HIDDEN"];   
	   $pautan=$data["MENULINK"];
	   $menugroup=$data["MENUGROUP"];
	   $dbtrans="1";
	 }
 }	 
 if (!$jenis)
   $jenis="menu";
 
 if ($jenis=="menu")  
    $query = "SELECT max(menupos) as maxorder FROM menu where admin='0' and type='$jenis'";
 else
    $query = "SELECT max(menupos) as maxorder FROM menu where admin='0' and parent='$menu'";

 if ($jenis=="pautan")
   $targetwindow="_blank";
 else
   $targetwindow="_top";
 
 $result = oci_parse($conn_sispa,$query);
 oci_execute($result);
 $data=oci_fetch_array($result);
 $max=$data["MAXORDER"];
 if ($max=="")
   $max=1;
 else
   $max=$max+1;
 $susunan=$max;	 
 ?>
<form name="frmmenu" method="post" action="b_tambah_menu.php">
<table id="form_table_outer" width="60%">
  <tr><td>
<table id="form_table_inner" width="100%" border="0" bgcolor="#EEF3FF" cellpadding="2" cellspacing="0">
          <?php
	  $jenismenu[0]="header";
	  $jenismenu[1]="menu";
	  $jenismenu[2]="module";

	  $labeljenis[0]="Header";
	  $labeljenis[1]="Menu";
	  $labeljenis[2]="Modul";
	  
	  echo "<td>Jenis</td>";
      echo "<td colspan=\"3\"><select name=\"txt_jenis\" onChange=\"document.frmmenu.action='b_tambah_menu.php';document.frmmenu.post.value='0';document.frmmenu.submit();\">";
	  for ($idx=0;$idx<3;$idx++){
	      if ($jenis==$jenismenu[$idx])
            echo "<option selected value=\"$jenismenu[$idx]\">$labeljenis[$idx]</option>";
		  else
            echo "<option value=\"$jenismenu[$idx]\">$labeljenis[$idx]</option>";
	  }	  
      echo "</select></td></td></tr>";
	?>
          <tr> 
            <td width="20%">Tajuk Menu</td>
            <td width="80%" ><input name="txt_tajuk"  type="text" size="50" maxlength="200" value="<?php echo $title; ?>"></td>
          </tr>
          <?php
      if (!isset($jenis))
	     $jenis="header";

      $kump[0]="UMUM";		 
      $kump[1]="SEKOLAH";		 
      $kump[2]="PPD";		 
      $kump[3]="JPN";		 
      $kump[4]="PPD/JPN";		 
      $kump[5]="BAHAGIAN";		 
      $kump[6]="SUPERADMIN";		 
	  //echo "jenis:$jenis header:$header <<";
	  if ($jenis=="header" or ($jenis=="menu" and ($header=="0" or $header==""))){
        echo "<tr><td>Kumpulan</td><td colspan=\"3\"><select name=\"txt_group\">";
		for($idx=0;$idx<count($kump);$idx++){
		     $m_id = $kump[$idx];
		     $tajuk = $kump[$idx];
			 if ($m_id==$menugroup)
	           echo "<option selected value=\"$m_id\">$tajuk</option>";
			 else
	           echo "<option value=\"$m_id\">$tajuk</option>";
		  }
        echo "</select></td></tr>";
	 } //jenis=="header   
		 
	  if ($jenis=="menu"){
        echo "<tr><td>Header Menu</td><td colspan=\"3\"><select name=\"txt_header\">";
		echo "<option value=\"\">-TIADA-</option>";
	    $query = "SELECT menuid,menutitle FROM menu where menutype='header' order by menupos";
        $result = oci_parse($conn_sispa,$query);
		oci_execute($result);
	      while($data=oci_fetch_array($result)){ 
		     $m_id = $data["MENUID"];
		     $tajuk = $data["MENUTITLE"];
			 if ($m_id==$menu)
	           echo "<option selected value=\"$m_id\">$tajuk</option>";
			 else
	           echo "<option value=\"$m_id\">$tajuk</option>";
		  }
        echo "</select></td></tr>";
	 } //jenis<>"menu   
	 
	  if ($jenis=="module"){
        echo "<tr><td>Menu</td><td colspan=\"3\"><select name=\"txt_menu\">";
	    $query = "SELECT menuid,menuheader,menutitle,menugroup FROM menu where menutype='menu' order by menuheader,menupos";
        $result = oci_parse($conn_sispa,$query);
		oci_execute($result);
	      while($data=oci_fetch_array($result)){ 
		     $m_id = $data["MENUID"];
		     $h_id = $data["MENUHEADER"];
		     $tajuk = $data["MENUTITLE"];
		     $menugroup = $data["MENUGROUP"];
			 
			 
	         $queryh = "SELECT menutitle,menugroup FROM menu where menuid='$h_id' order by menupos";
             $resulth = oci_parse($conn_sispa,$queryh);
		     oci_execute($resulth);
	         $datah=oci_fetch_array($resulth); 
			 $menuheader=$datah["MENUTITLE"];
		     $menugrp = $datah["MENUGROUP"];
			 
			 
			 if ($menuheader<>"")
			   $tajuk=$menuheader." ($menugrp) -> ".$tajuk;
			 else
               $tajuk=$tajuk." ($menugroup)";			 
			 if ($m_id==$menu)
	           echo "<option selected value=\"$m_id\">$tajuk</option>";
			 else
	           echo "<option value=\"$m_id\">$tajuk</option>";
		  }
        echo "</select></td></tr>";
	 } //jenis<>"header   
 

		 if ($jenis<>"header" and $jenis<>"menu") {
	        echo "<tr><td>Pautan</td><td colspan=\"3\"><input name=\"txt_pautan\" ";
			//if ($jenis<>"pautan")
			  //echo " readonly ";
		   echo " type=\"text\" size=\"80\" maxlength=\"80\" value=\"$pautan\"></td></tr>";
         }

	  ?>
       <!--   <tr>
            <td valign="top">Catatan</td>
            <td><textarea name="txt_catatan" cols="60" rows="4" id="txt_catatan"></textarea></td>
          </tr>-->
          <tr> 
            <td>Aktif</td>
            <td width="4%"> 
              <?php if ($aktif=="1")
	            echo "<input type=\"checkbox\" checked name=\"txt_aktif\" value=\"1\">";
	         else
	            echo "<input type=\"checkbox\" name=\"txt_aktif\" value=\"1\">";
	   ?>
            </td>
          </tr>
          <tr> 
            <td>Tidak Kelihatan</td>
            <td width="4%"> 
              <?php if ($hidden=="1")
	            echo "<input type=\"checkbox\" checked name=\"txt_hidden\" value=\"1\">";
	         else
	            echo "<input type=\"checkbox\" name=\"txt_hidden\" value=\"1\">";
	   ?>&nbsp;Untuk Bahagian,JPN dan PPD.Menu akan dipaparkan jika ada sekolah dipilih.
            </td>
          </tr>
          <tr> 
            <td>Susunan</td>
            <td width="4%"><?php //echo "jenis:$jenis parent:$parent header:$header <br>";
			       if ($jenis=="header" or ($jenis=="menu" and $header=="0"))
				      $sqlpos="select menupos from menu where menutype='header' or (menutype='menu' and menuheader=0) order by menupos";
			       else if ($jenis=="menu")
				      $sqlpos="select menupos from menu where menutype='menu' and menuheader='$menu' order by menupos";
			       else if ($jenis=="module")
				      $sqlpos="select menupos from menu where menutype='module' and menuparent='$menu' order by menupos";
				  //echo "$sqlpos<br>";	  
			      $respos=oci_parse($conn_sispa,$sqlpos);
			
			
			?>
			<select name="txt_susunan">
			<?php
				   oci_execute($respos);
				   while($datapos=oci_fetch_array($respos)){
					 $pos=$datapos["MENUPOS"];
					 echo "<option ";
					 if ($menupos==$pos)
					   echo " selected ";
					   
					 echo "value=\"$pos\">$pos</option>";  
				   }
			   
			
			?>
			</select>
            </td>
          </tr>
		  
		  
          <tr> 
            <td colspan="2" > <input type="hidden" name="txt_targetwindow" value="<? echo $targetwindow; ?>"> 
              <input name="txt_oldpos"  type="hidden" size="5" maxlength="5" value="<?php echo $menupos; ?>"> 
              <input type="hidden" name="dbtrans" value="<?php echo $dbtrans;?>"> 
			  <input type="hidden" name="post" id="post" value="1">
			  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>"> 
              <input name="Simpan" type="submit" value="Simpan"> <input name="Kembali" type="Button" value="Kembali" onclick="location.href='edit_menu.php';"> 
            </td>
          </tr>
        </table></td></tr></table>
</form>

		</td>
	</tr>
</table>
</td>
<?php include 'kaki.php';?> 
