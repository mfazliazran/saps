
<?php 
/*
 selenggara_kod.php - 19-April-2018
*/
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';

$ncolor="#FFFFFF";
$hlcolor="#EEEEEE";

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Selenggara Kod</p>
<script type="text/javascript">
function papar_rekod()
{
 var cnt=document.getElementById("txtCount").value;
 pilih=0;
 for(i=1;i<=cnt;i++){
  if (document.getElementById("chkbox"+i).checked){
	 pilih++;
  }
 } 
 if (pilih==0){
   alert("Sila pilih medan yang hendak dipapar !");
   return false;
 }  
 
 document.frm1.action="selenggara_kod.php?task=papar";
 document.frm1.submit();
 return true;
}

function tambah_rekod()
{
 if (document.getElementById("txtTname").value==""){
	 alert("Sila pilih Table dahulu !");
	 return false;
 }
 document.frm1.action="selenggara_kod.php?task=tambah";
 document.frm1.submit();
 return true;
}



function check_all(flg)
{
	 var cnt=document.getElementById("txtCount").value;
	 for(i=1;i<=cnt;i++){
       document.getElementById("chkbox"+i).checked=flg;
	 }  
}

function hapus_rekod(v)
{
  if (confirm('Hapuskan rekod ini ?')){
	  document.frm1.action="selenggara_kod.php?task=hapus"+v;
	  document.frm1.submit();
	  return true;
  }
  else
    return false;
}

function edit_rekod(v)
{
  document.frm1.action="selenggara_kod.php?task=kemaskini"+v;
  document.frm1.submit();
  return true;
}

function semak_tambah(flg)
{

 if(flg=="simpan")
   document.frm1.action="selenggara_kod.php?task=tambah";
   
 document.frm1.submit();
 return true;
}

function papar_senarai_rekod()
{
  document.frm1.action="selenggara_kod.php?task=papar";
  document.frm1.submit();
  return true;
}
</script>
<br>

<?php
 global $dbi;
 global $conn_sispa;
 
 $task=$_GET["task"];
 if($task=="")
	 $border="1";
 else
	 $border="0";
 
 //$sqltable="select tname from tab where tname like 'TK%' order by tname ";
 $sqltable="select tname from tab  order by tname ";
 $user=md5($_SESSION['SESS_MEMBER_ID']);
?>
<table align="left" class="form_content" border="<?php echo $border;?>" bgcolor="#FFFFFF" cellpadding="3" cellspacing="0">
<tr><td valign="top">
<?php

//MULA PAPAR
 if($task=="papar"){
  $res=oci_parse($conn_sispa,"select user from dual");
  oci_execute($res);
  $data=oci_fetch_array($res);
  $current_user=$data["USER"];
	 
  $maxrecord=50;
  $tname=$_POST["txtTname"];
  $count=$_POST["txtCount"];
	  $res = oci_parse($conn_sispa,"SELECT COLUMN_NAME,DATA_TYPE FROM ALL_TAB_COLUMNS WHERE TABLE_NAME='$tname' and OWNER='$current_user' ORDER BY COLUMN_ID");
	  oci_execute($res);

	  $cnt=0;
	  while($data=oci_fetch_array($res)){
		$arr_medan[$cnt]=$data["COLUMN_NAME"];
		$arr_key[$cnt]=$data["DATA_TYPE"];
		$cnt++;
	  }
      $pagenum=(int)$_POST["pagenum"];
	  
	  $sqlquery=$_POST["sql"];
	  //echo "sqlquery:$sqlquery<br>";
	  if ($pagenum==0)
	    $pagenum=1;
	
      $colspan=$cnt+3;	
	  $startrow=($pagenum-1)*$maxrecord;
      echo "<form name=\"frm1\" action=\"\" method=\"post\">";
	  echo "<table border=\"1\" bgcolor=\"#FFFFFF\" cellpadding=\"3\" cellspacing=\"0\">";	
	  echo "<tr><td  colspan=\"$colspan\"><strong>$tname</strong></td></tr>";
	   if ($sqlquery==""){
		  $sql1="select ROWIDTOCHAR(ROWID) as R_ID, ";
		  $pilih=0;
		  echo "<tr>";
		  $where=" where ";
		  $cntsyarat=0;
		  for($i=1;$i<=$count;$i++){
			  $medan=$_POST["txtmedan$i"];
			  $syarat=$_POST["txtsyarat$i"];
			  $nilai=$_POST["txtnilai$i"];
			  if ($syarat<>""){
				 if ($cntsyarat>0)
				   $where.=" and ";
				 if ($syarat=="LIKE % %")
					$where .= "$medan LIKE '%$nilai%'";
				 else	
					$where .= "$medan $syarat '$nilai'";
				 $cntsyarat++;
			  }
			  if ($_POST["chkbox$i"]=="1"){
				  $pilih++;		  
				  if ($pilih>1)
					$sql1.=",";
				  $sql1.=" $medan";
			  }
		  }
		  echo "</tr>";
		  $sql1.=" from $tname";	
		  if ($where<>" where ")
			$sql1.=$where;
	  }	
	  else
	    $sql1=$sqlquery;
	//echo "sql1:$sql1<br>";
	  $result=oci_parse($conn_sispa,$sql1);
	  oci_execute($result);
		  //****
	echo "<tr>";
	echo "<td  height=\"45%\" align=\"center\" bgcolor=\"#58D3F7\"><strong>BIL</strong></td>";
	echo "<td  height=\"45%\" align=\"center\" bgcolor=\"#58D3F7\">&nbsp;</td>";
	echo "<td  height=\"45%\" align=\"center\" bgcolor=\"#58D3F7\">&nbsp;</td>";
	
	$medan=str_replace("select","",$sql1);
	$pos2=strpos($medan,"from");
	$medan=substr($medan,0,$pos2);
	//echo "medan:$medan<br>";
	$arr_medan=split(",",$medan);
	
	$count_field=count($arr_medan);
	for($i = 1; $i < $count_field; $i++) {
		echo "<td  height=\"45%\" align=\"center\" bgcolor=\"#58D3F7\"><strong>".$arr_medan[$i]."</strong></td>";
	}
	echo "</tr>";		  

///*************
    $TotalRecords=count_row($sql1,$conn_sispa);
echo "Bilangan Rekod: <b>$TotalRecords</b><br>";
		$pg=(int) $_GET["pg"];
		if ($pg==0)
		 $pg=1;
		
		$recordpage=$maxrecord;
		$startrec=($pg-1)*$recordpage+1;
		$endrec=($startrec+$recordpage)-1;  
		$rowstart=($pg-1)*$maxrecord;

	  $bal=$TotalRecords%$recordpage;		
      $page=($TotalRecords-$bal)/$recordpage;
	  if ($bal>0)
	    $page++;
	
      $lastrow=($pagenum*$recordpage);
	  $result=oci_parse($conn_sispa,"$sql1"); // limit $startrow,$maxrecord",$dbi;

	$qrystr2="select * from ( select a.*,rownum rnum from ($sql1) a where rownum<=$endrec) where rnum>=$startrec";
    $result = oci_parse($conn_sispa,$qrystr2);
	oci_execute($result);
	
      oci_execute($result);
	  $bil=$startrow;
	  while($row = oci_fetch_array($result)) {
	    $bil++;
		$arr_nilai[0]=$row[0];
		$c=0;
        
		for($c=0;$c<$count_field;$c++){
		  if(is_null($row[$c]))
		    $arr_nilai[$c]="null";
		  else
            $arr_nilai[$c]=$row[$c];		  
		}
		echo "<tr><tr bgcolor='$ncolor' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$ncolor'\"><td>$bil</td>";
		echo "<td>";
		$var="&tname=$tname";
		$var2="&tname=$tname";
		$countkey=1;
		$var=$var."&f1=ROWID&v1=".urlencode($arr_nilai[0]);
		
		$var=$var."&countkey=".$countkey;
		$var2=$var2."&countkey=".$num;
		if($countkey==0)
		  $var=$var2;

	    echo "<a href=\"javascript:void(0);\" onClick=\"return edit_rekod('$var');\"";
		echo "\"><img src=\"images/edit.png\"></a></td>";
		echo "<td><a href=\"javascript:void(0);\" onClick=\"return hapus_rekod('$var');\"><img src=\"images/drop.png\"></a></td>";
		$idx=0;	
		for($i=1;$i<count($arr_nilai);$i++){
		  $nilai=$arr_nilai[$i];	
		  $datatype=$arr_key[$idx];
		  $idx++;
		  if($datatype=="DATE"){
			  if ($nilai<>"" and $nilai<>"null"){
				  $dy=substr($nilai,0,2);
				  $mth=substr($nilai,3,3);
				  $yr=substr($nilai,7,4);
				  if($mth=="JAN")
					$mth="01";
				  else if($mth=="FEB")
					$mth="02";
				  else if($mth=="MAR")
					$mth="03";
				  else if($mth=="APR")
					$mth="04";
				  else if($mth=="MEI")
					$mth="05";
				  else if($mth=="JUN")
					$mth="06";
				  else if($mth=="JUL")
					$mth="07";
				  else if($mth=="AUG")
					$mth="08";
				  else if($mth=="SEP")
					$mth="09";
				  else if($mth=="OCT")
					$mth="10";
				  else if($mth=="NOV")
					$mth="11";
				  else if($mth=="DEC")
					$mth="12";
			  
                  $nilai="$dy/$mth/$yr"; 				
			  }
		  }
		  echo "<td>$nilai&nbsp;</td>";
		}
		echo "</tr>";
	  }


	 echo "</table>";
	 echo "<input type=\"button\" class=\"button\" name=\"back\" value=\"Kembali\" onClick=\"location.href='selenggara_kod.php?tname=$tname';\">&nbsp;";
	 echo "&nbsp;|&nbsp;";
	 echo "Muka surat : <select name=\"pagenum\" onChange=\"document.frm1.submit();\">";
	 for($i=1;$i<=$page;$i++){
	   echo "<option ";
	   if ($pagenum==$i)
	     echo " selected ";
	   echo " value=\"$i\">$i</option>";
	 }  
	 echo "</select>";
	 echo "<br><input type=\"hidden\" name=\"sql\" value=\"$sql1\">";
	 echo "<input type=\"hidden\" name=\"post\" value=\"1\">";
	 echo "<input type=\"hidden\" name=\"txtTname\" value=\"$tname\">";
	 echo "<input type=\"hidden\" name=\"txtCount\" value=\"$count\">";
	 echo "</form>";
 }
//TAMAT PAPAR
//MULA TAMBAH 
 else if ($task=="tambah"){
?>	 
<form name="frm1" action="" method="post">
<?php
  $tname=$_POST["txtTname"];

if($_POST["post"]=="add")
{
  $count=$_POST["txtCount"];

  $sql1="insert into $tname( ";
  $pilih=0;
  echo "<tr>";
  $val="(";
  $cntsyarat=0;
  for($i=1;$i<=$count;$i++){
	  $medan=$_POST["txtMedan$i"];
	  $nilai=$_POST["txtNilai$i"];
	  if ($i>1){
		$sql1.=",";
		$val.=",";
	  }	
	  $sql1.=$medan;
	  $val.="'$nilai'";
	  
  }
  $sql1.=")";
  $val.=")";
  $sql1="$sql1 values $val";
  //echo "$sql1<br>";
	$res=oci_parse($conn_sispa,$sql1);
	$exec = oci_execute($res);
	if (!$exec) {
	    $e = oci_error($res);  // For oci_execute errors pass the statement handle
	    print htmlentities($e['message']);
	    print "\n<pre>\n";
	    print htmlentities($e['sqltext']);
	    printf("\n%".($e['offset']+1)."s", "^");
	    print  "\n</pre>\n";
	} 	
    else {
        echo "<font color=\"#006600\">Rekod telah berjaya disimpan...</font><br>";
	}	
  echo "<input type=\"button\" class=\"button\" name=\"back\" value=\"<<\" onClick=\"location.href='selenggara_kod.php?tname=$tname';\">";
	
}	
else {
	$res=oci_parse($conn_sispa,"select user from dual");
	oci_execute($res);
	$data=oci_fetch_array($res);
	$current_user=$data["USER"];

	$sql = "SELECT COLUMN_NAME,DATA_TYPE FROM ALL_TAB_COLUMNS WHERE TABLE_NAME='$tname' and OWNER='$current_user' ORDER BY COLUMN_ID";

	$res = oci_parse($conn_sispa,$sql);
	oci_execute($res);
	$cnt=0;
	echo "<table  border=\"0\" bgcolor=\"#666666\" cellpadding=\"3\" cellspacing=\"1\">";	
	echo "<tr><td bgcolor=\"#58D3F7\" colspan=\"2\"><strong>$tname</strong></td></tr>";

	while($data = oci_fetch_array($res)){
		$cnt++;
		$medan = $data["COLUMN_NAME"];
		$type = $data["DATA_TYPE"];
		echo "<tr><td bgcolor=\"#EEEEEE\">$medan<input type=\"hidden\" name=\"txtMedan$cnt\" id=\"txtMedan$cnt\" value=\"$medan\"></td>";
		echo "<td bgcolor=\"#FFFFFF\"><input name=\"txtNilai$cnt\" value=\"\" size=\"40\"></td>";			  
		echo "</tr>";
	}
	echo "<input type=\"hidden\" name=\"txtCount\" id=\"txtCount\" value=\"$cnt\">";
	echo "</table>";
?>


	<input type="hidden" name="txtTname" id="txtTname" value="<?php echo $tname;?>">
	<input type="button" class="button" name="tambah" value="Simpan" onClick="return semak_tambah('simpan');">
	<input type="button" class="button" name="batal" value="Batal" onClick="location.href='selenggara_kod.php?tname=<?php echo $tname;?>';">
	<input type="hidden" name="post" value="add">
	</form>
<?php
	}
 }
 //TAMAT TAMBAH
 //MULA HAPUS
 else if ($task=="hapus"){
   $tname=$_POST["txtTname"];
   $count=$_POST["txtCount"];
   $sqlquery=$_POST["sql"];
   $pagenum=$_POST["pagenum"];
	//die("tname:$tname count$count sqlquery:$sqlquery pagenum:$pagenum");  
    //$tname=$_GET["tname"];
	$countkey=$_GET["countkey"];
	$where="where ";
	for($i=1;$i<=$countkey;$i++){
	  if($i>1){
		$where=$where." and ";
	  }
	  $f=$_GET["f$i"];
	  $v=htmlentities($_GET["v$i"]);
	  if($v=="null")
		 $where=$where."$f is null";
	  else
		 $where=$where."$f='$v'";
	}
    $sqldel="delete from $tname $where ";
	//die($sqldel);
	//echo session_id() ."delete:$sqldel<br>";
	$res=oci_parse($conn_sispa,$sqldel);
	oci_execute($res);
	$exec = oci_execute($res);
	if (!$exec) {
	    $e = oci_error($res);  // For oci_execute errors pass the statement handle
	    print htmlentities($e['message']);
	    print "\n<pre>\n";
	    print htmlentities($e['sqltext']);
	    printf("\n%".($e['offset']+1)."s", "^");
	    print  "\n</pre>\n";
	} 	
  echo "<form name=\"frm1\" method=\"post\" action=\"selenggara_kod.php?task=papar\">";
  echo "<input type=\"hidden\" name=\"sql\" value=\"$sqlquery\">";
  echo "<input type=\"hidden\" name=\"txtTname\" value=\"$tname\">";
  echo "<input type=\"hidden\" name=\"txtCount\" value=\"$count\">";
  echo "<input type=\"hidden\" name=\"pagenum\" value=\"$pagenum\">";
  echo "<input type=\"button\" class=\"button\" name=\"back\" value=\"<<\" onClick=\"papar_senarai_rekod();\">";
  echo "</form>";		
 }
 //TAMAT HAPUS
 //MULA KEMASKINI
 else if ($task=="kemaskini"){
	if($_POST["post"]=="update"){
	  $tname=$_POST["txtTname"];
	  $count=$_POST["txtCount"];
	  $sqlquery=$_POST["sql"];
	  $pagenum=$_POST["pagenum"];
	  $where=stripslashes($_POST["txtWhere"]);
	  $sql="update $tname set ";
	  $pilih=0;
	  $cntsyarat=0;
	  for($i=0;$i<$count;$i++){
		  $medan=$_POST["txtMedan$i"];
		  $nilai=$_POST["txtNilai$i"];
		  $null=$_POST["txtCb$i"];
		  if ($i>0){
			$sql.=",";
		  }	
		  if($null=="1")
			$sql=$sql."$medan=null";
		  else
			$sql=$sql."$medan='$nilai'";
		  
	  }
	  $sql.=" $where";
	  $res=oci_parse($conn_sispa,$sql);
	  $exec = oci_execute($res);
	 if (!$exec) {
		echo "fatal<br>"; 
		$e = oci_error($res);  // For oci_execute errors pass the statement handle
		print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%".($e['offset']+1)."s", "^");
		print  "\n</pre>\n";
	 }
	 else 	
		echo "<font color=\"#006600\">Rekod telah berjaya disimpan...</font><br><br>";

	  echo "<form name=\"frm1\" method=\"post\" action=\"selenggara_kod.php?task=papar\">";
	  echo "<input type=\"hidden\" name=\"sql\" value=\"$sqlquery\">";
	  echo "<input type=\"hidden\" name=\"txtTname\" value=\"$tname\">";
	  echo "<input type=\"hidden\" name=\"txtCount\" value=\"$count\">";
	  echo "<input type=\"hidden\" name=\"pagenum\" value=\"$pagenum\">";
	  echo "<input type=\"button\" class=\"button\" name=\"back\" value=\"<<\" onClick=\"papar_senarai_rekod();\">";
	  echo "</form>";	
	}
	else {
    echo "<form name=\"frm1\" action=\"\" method=\"post\">";
	$tname=$_GET["tname"];
	$countkey=$_GET["countkey"];
	$where="where ";
	$sqlquery=$_POST["sql"];
	$pagenum=$_POST["pagenum"];
	//echo "sqlquery:$sqlquery - pagenum:$pagenum<br>";
	for($i=1;$i<=$countkey;$i++){
	  if($i>1){
		$where=$where." and ";
	  }
	  $f=$_GET["f$i"];
	  $v=htmlentities($_GET["v$i"]);
	  if($v=="null")
		 $where=$where."$f is null";
	  else
		 $where=$where."$f='$v'";
	}



	echo "<table border=\"1\" bgcolor=\"#FFFFFF\" cellpadding=\"3\" cellspacing=\"0\">";	
	echo "<tr><td class=\"form_header\" colspan=\"3\"><strong>$tname</strong></td></tr>";
	echo "<tr><td  height=\"45%\" align=\"center\" bgcolor=\"#58D3F7\"><strong>Medan</strong></td>";
	echo "<td  height=\"45%\" align=\"center\" bgcolor=\"#58D3F7\"><strong>Nilai</strong></td>";
	echo "<td  height=\"45%\" align=\"center\" bgcolor=\"#58D3F7\"><strong>Null?</strong></td></tr>";

	$cnt=0;
	$res=oci_parse($conn_sispa,"select user from dual");
	oci_execute($res);
	$data=oci_fetch_array($res);
	$current_user=$data["USER"];
	 
	$res = oci_parse($conn_sispa,"SELECT COLUMN_NAME,DATA_TYPE FROM ALL_TAB_COLUMNS WHERE TABLE_NAME='$tname' and OWNER='$current_user' ORDER BY COLUMN_ID");
	oci_execute($res);
	while($data=oci_fetch_array($res)){
	$arr_medan[$cnt]=$data["COLUMN_NAME"];
	$cnt++;
	}
	//echo "conn_sispa:$conn_sispa<br>";	
	$resd=oci_parse($conn_sispa,"select * from $tname $where");
	oci_execute($resd); 	
	//echo "select * from $tname $where";
	if($datad=oci_fetch_array($resd)){	
		for($i=0;$i<count($arr_medan);$i++){
			echo "<tr><td class=\"form_label\">".$arr_medan[$i]."<input type=\"hidden\" name=\"txtMedan$i\" id=\"txtMedan$i\" value=\"".$arr_medan[$i]."\"></td>";
			$n=$arr_medan[$i];
			echo "<td bgcolor=\"#FFFFFF\"><input name=\"txtNilai$i\" value=\"".$datad[$n]."\" size=\"40\"></td>";			  
			if(is_null($datad[$n]))
			   echo "<td bgcolor=\"#FFFFFF\"><input type=\"checkbox\" checked name=\"txtCb$i\" value=\"1\"></td>";			  
			else
			   echo "<td bgcolor=\"#FFFFFF\"><input type=\"checkbox\" name=\"txtCb$i\" value=\"1\"></td>";			  
			echo "</tr>";
		}
		$count=count($arr_medan);
	}
	echo "</table>";
	?>


	<input type="hidden" name="txtTname" id="txtTname" value="<?php echo $tname;?>">
	<input type="submit" class="button" name="tambah" value="Simpan">
	<input type="button" class="button" name="batal" value="Batal" onClick="papar_senarai_rekod();">
	<input type="hidden" name="txtWhere" size="50" value="<?php echo $where;?>">
	<input type="hidden" name="sql" value="<?php echo $sqlquery;?>">
	<input type="hidden" name="txtCount" value="<?php echo $count;?>">
	<input type="hidden" name="pagenum" value="<?php echo $pagenum;?>">
	<input type="hidden" name="post" value="update">
	</form>
	<?php
	}
		 
 }
 //TAMAT KEMASKINI
 //MULA PAPAR KEPUTUSAN
 else if ($task=="paparkeputusan"){
	$maxrecord=50;
	  
	$pg=(int)$_POST["pagenum"];
	if ($pg==0)
	$pg=1;

	$startrec=($pg-1)*$maxrecord+1;
	$endrec=($startrec+$maxrecord)-1;  
	$rowstart=($pg-1)*$maxrecord;

	$catatan = $_POST["txtCatatan"];

	$stid = oci_parse($conn_sispa, $catatan);
	$exec=oci_execute($stid);
	if (!$exec) {
		echo "error";
		$e = oci_error($stid);  // For oci_execute errors pass the statement handle
		print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%".($e['offset']+1)."s", "^");
		print  "\n</pre>\n";
		echo "<input type=\"button\" class=\"button\" name=\"back\" value=\"Kembali\" onClick=\"location.href='selenggara_kod.php';\">";	
	} 
	else {
		
		//select kodsekolah,count(*) from tsharta where kodsekolah like 'BEA%'group by kodsekolah
		$qrystr2="select * from ( select rownum bil,a.* from ($catatan) a where rownum<=$endrec) where bil>=$startrec";
		$stid = oci_parse($conn_sispa, $qrystr2);
		$exec=oci_execute($stid);


		$rescount=oci_parse($conn_sispa,"select count(*) as cnt from ($catatan)");
		oci_execute($rescount);
		$datacount=oci_fetch_array($rescount);
		$TotalRecords=$datacount["CNT"];
		echo "$catatan<br>";
		echo "Rekod: <b>$TotalRecords</b><br>";

		$bal=$TotalRecords%$maxrecord;		
		$page=($TotalRecords-$bal)/$maxrecord;
		if ($bal>0)
		$page++;
			
		echo "<table  border=\"0\" bgcolor=\"#CCCCCC\" cellpadding=\"3\" cellspacing=\"1\">\n";

		$ncols = oci_num_fields($stid);
		echo "<tr>\n";
		for ($i = 1; $i <= $ncols; ++$i) {
			$colname = oci_field_name($stid, $i);
			echo "<td bgcolor=\"#58D3F7\"><b>".htmlentities($colname, ENT_QUOTES)."</b></td>\n";
		}
		echo "</tr>\n";
		$cnt=$rowstart;
		$cntrec=0;
		while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
			$cnt++;
			$cntrec++;
			 echo "<tr bgcolor='$ncolor' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$ncolor'\">\n";
			 foreach ($row as $item) {
				 echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES):" ")."</td>\n"; 
			 }
			echo "</tr>\n";
		}
		if($cntrec==0){
			echo "<tr><td bgcolor=\"#FFFFFF\" colspan=\"$ncols\"><font color=\"#FF0000\">Tiada rekod.</font></td></tr>";
		}
		echo "<tr><td align=\"left\" colspan=\"$ncols\" bgcolor=\"#EEEEEE\">";

		 echo "<form name=\"frm1\" action=\"\" method=\"post\">";
		 echo "<input type=\"hidden\" name=\"txtCatatan\" value=\"$catatan\">";
		 echo "<input type=\"button\" class=\"button\" name=\"back\" value=\"Kembali\" onClick=\"location.href='selenggara_kod.php?role=1';\">";
		 echo "&nbsp;|&nbsp;";
		 echo "Muka surat : <select name=\"pagenum\" onChange=\"document.frm1.submit();\">";
		 for($i=1;$i<=$page;$i++){
		   echo "<option ";
		   if ($pg==$i)
			 echo " selected ";
		   echo " value=\"$i\">$i</option>";
		 }  
		 echo "</select>";
		 echo "</td></tr>";
		 echo "</table>\n";
	}
	 
 }	 
 //TAMAT PAPAR KEPUTUSAN
 else {
 $res=oci_parse($conn_sispa,$sqltable);
 oci_execute($res);
 $cnt=0;
 while($data=oci_fetch_array($res)){
	$cnt++;
	$tname=$data["TNAME"];
	if($_GET["tname"]==$tname)
		echo "<font color=\"#FF0000\">$tname</font><br>"; 
	else
		echo "<a href=\"selenggara_kod.php?tname=$tname\">$tname</a><br>"; 
 }


?>
</td>
<td valign="top">
<?php 
	echo "<b>Makluman</b><br><br>Utiliti ini disediakan bagi memudahkan pihak ADMIN SAPS menyelenggara rekod-rekod <br>  yang ada di dalam sistem SAPS.<br><br>";	 
	if($user=="7b4354bded455eb379e3c764806cda7b") {
		
		echo "<br><b>Catatan</b><br>";
		echo "<form name=\"frmpapar\" method=\"post\" action=\"selenggara_kod.php?task=paparkeputusan\">";
		echo "<textarea cols=\"80\" rows=\"10\" name=\"txtCatatan\"></textarea><br>";
		echo "<input type=\"submit\" class=\"button\" name=\"submit\" value=\"Teruskan\">";
		echo "</form>";
	 } 
?>
<form name="frm1" action="selenggara_kod.php" method="post">
<div id="divMedan">
<?php
  if($_GET["tname"]<>""){
    $tname=$_GET["tname"];
	$op[0]="";
	$op[1]="=";
	$op[2]=">";
	$op[3]=">=";
	$op[4]="<";
	$op[5]="<=";
	$op[6]="!=";
	$op[7]="LIKE";
	$op[8]="LIKE % %";
	?>

	<?php
	 $res=oci_parse($conn_sispa,"select user from dual");
	 oci_execute($res);
	 $data=oci_fetch_array($res);
	 $current_user=$data["USER"];

	 $sql = "SELECT COLUMN_ID,COLUMN_NAME,DATA_TYPE FROM ALL_TAB_COLUMNS WHERE TABLE_NAME='$tname' and OWNER='$current_user' ORDER BY COLUMN_ID";

	//echo $sql;
	//echo $sqlOKU."<br />";
	$res = oci_parse($conn_sispa,$sql);
	oci_execute($res);
	$cnt=0;
	echo "<strong>$tname</strong>";
	echo "<table border=\"0\" bgcolor=\"#666666\" cellpadding=\"3\" cellspacing=\"1\">";	
	echo "<tr><td valign=\"center\" bgcolor=\"#58D3F7\"><input type=\"checkbox\" checked name=\"cbcheck\" id=\"cbcheck\" onClick=\"check_all(this.checked)\"></td>
	<td bgcolor=\"#58D3F7\"><strong>Medan</strong></td><td bgcolor=\"#58D3F7\"><strong>Syarat</strong></td>
	<td bgcolor=\"#58D3F7\"><strong>Nilai</strong></td></tr>";

	while($data = oci_fetch_array($res)){
		$cnt++;
		$medan = $data["COLUMN_NAME"];
		$type = $data["DATA_TYPE"];
		echo "<tr>";
		echo "<td bgcolor=\"#FFFFFF\"><input type=\"checkbox\" checked name=\"chkbox$cnt\" id=\"chkbox$cnt\" value=\"1\">
				  <input type=\"hidden\" name=\"txtmedan$cnt\" value=\"$medan\"></td><td bgcolor=\"#FFFFFF\">$medan </td>";
		echo "<td bgcolor=\"#FFFFFF\">";
		echo "<select name=\"txtsyarat$cnt\" style=\"width: 100px;\">";
		if (substr($type,0,7)=="VARCHAR" or substr($type,0,4)=="CHAR")
		  $e=count($op);
		else
		  $e=count($op)-2;	
		  
		for($i=0;$i<$e;$i++){
		  echo "<option value=\"".$op[$i]."\">".$op[$i]."</option>";
		}
		echo "</select>";
		
		echo "</td>";			  
		echo "<td bgcolor=\"#FFFFFF\"><input name=\"txtnilai$cnt\" value=\"\" size=\"40\"></td>";			  
		echo "</tr>";
	}
	echo "<input type=\"hidden\" name=\"txtCount\" id=\"txtCount\" value=\"$cnt\">";
	echo "<input type=\"hidden\" name=\"txtTname\" id=\"txtTname\" value=\"$tname\">";
	echo "</table>";
    $display="block";	
  }
  else {
	  $display="none";
  }
?></div>
<div id="divButton" style="display:<?php echo $display;?>;">
<input type="button" class="button" name="btnPapar" id="btnPapar" value="Papar Data" onClick="return papar_rekod();">
<input type="button" class="button" name="btnTambah" id="btnTambah" value="Tambah Rekod" onClick="return tambah_rekod();">
<input type="hidden" name="post" value="1">
</div>
</form>
<?php
 } //$task==""
?>
</td></tr>
</table>

</td>
<?php include 'kaki.php';?> 