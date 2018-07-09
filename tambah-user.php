<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
include 'fungsi2.php';

if ($_POST["post"]=="1"){
  $nokp=$_POST["nokp"];
  $nama=oci_escape_string($_POST["txtNama"]);
  $kodsek=$_POST["txtKodSek"];
  $level=$_POST["txtLevel"];
  $login=$_POST["txtLogin"];
  $katalaluan=$_POST["txtKatalaluan"];
  $kodjpn=$_POST["txtKodJPN"];
  $kodppd=$_POST["txtKodPPD"];

  if ($level<>"5" and $level<>"6"){
	  $query=" select STATUS,KODNEGERIJPN,KODPPD from TSEKOLAH where kodsek='$kodsek'";
	  $result = oci_parse($conn_sispa,$query);
	  oci_execute($result);
	  $data=oci_fetch_array($result);
	  $statussek=$data["STATUS"];
	  $kodjpn=$data["KODNEGERIJPN"];
	  $kodppd=$data["KODPPD"];
	  
  }  
  else
     $kodnegeri=$kodjpn;
	 
  $query2=" select PPD from TKPPD where KODPPD='$kodppd'";
  $result2 = oci_parse($conn_sispa,$query2);
  oci_execute($result2);
  $data2=oci_fetch_array($result2);
  $ppd=$data2["PPD"];
  $daerah=ereg_replace("PPD","",$ppd);
  $query2=" select NEGERI from TKNEGERI where KODNEGERI='$kodnegeri'";
  $result2 = oci_parse($conn_sispa,$query2);
  oci_execute($result2);
  $data2=oci_fetch_array($result2);
  $negeri=$data2["NEGERI"];

  if ($level=="5"){
    //$nokp=$kodppd;
	$kodsek=$kodppd;
	$namasek=$ppd;
  }	
  if ($level=="6"){
    //$nokp=$kodjpn;
	$kodsek=$kodjpn;
	$namasek="JPN $negeri";
  }	
	
  $tahun=date("Y");
	//Check for duplicate login ID
	$qry_su = "SELECT *  FROM login WHERE user1='$login'";
	if (count_row($qry_su)>0){
		     message("Username telah wujud..sila tukar yang lain",1);
     		 location("tambah-user.php");
		}							

  $query_sm=" insert into login(tahun,nokp,user1,pswd,kodsek,namasek,nama,level1,statussek,negeri,daerah,kodnegeri) 
              values ('$tahun','$nokp','$login','$katalaluan','$kodsek','$namasek','$nama','$level','$statussek','$negeri','$daerah','$kodnegeri')";
  $result_sm = oci_parse($conn_sispa,$query_sm);
  //die($query_sm);

  oci_execute($result_sm);
  location("senarai-user.php");
 }
?>
<script language="javascript" type="text/javascript" src="ajax/ajax.js"></script>
<script language="javascript" type="text/javascript">
function ucase(e,obj) {
tecla = (document.all) ? e.keyCode : e.which;
//alert(tecla);
if (tecla!="8" && tecla!="0"){
obj.value += String.fromCharCode(tecla).toUpperCase();
return false;
}else{
return true;
}
}

function check_level(level)
{
  if (level=='1' || level=='2' || level=='3' || level=='4'){ //sekolah
    document.frm1.txtKodPPD.disabled=true;
    document.frm1.txtKodJPN.disabled=true;
    document.frm1.txtKodPPD.value='';
    document.frm1.txtKodJPN.value='';
	document.frm1.txtKodSek.disabled=false;
 }
 else if (level=='5'){ //ppd 
    document.frm1.txtKodPPD.disabled=false;
    document.frm1.txtKodJPN.disabled=false;
    document.frm1.txtKodSek.disabled=true;
    document.frm1.txtKodSek.value='';
 }
 else if (level=='6'){ //JPN
    document.frm1.txtKodPPD.disabled=true;
    document.frm1.txtKodSek.disabled=true;
    document.frm1.txtKodPPD.value='';
    document.frm1.txtKodSek.value='';
    document.frm1.txtKodJPN.disabled=false;
 }
}
</script>

<td valign="top" class="rightColumn">
<p class="subHeader">Tambah Pengguna</p>

<form name="frm1" method="post" action="">
<TABLE>
<tr><td>NO. K/P</td><td>:</td><td><b><input type="text" name="txtNoKP" id="txtNoKP" size="14" maxlength="12" /></b></td></tr>
<tr><td>NAMA</td><td>:</td><td><input type="text" name="txtNama" id="txtNama" size="50" maxlength="50" onkeypress="return ucase(event,this);"/></td></tr>
<tr><td>LEVEL</td><td>:</td><td>
<?php
  $cnt_level=(int)$_SESSION["level"]-1;
?>
<select name="txtLevel" id="txtLevel" onchange="check_level(this.value);">
<?php
 $arr[0]="1";
 $arr[1]="2";
 $arr[2]="3";
 $arr[3]="4";
 $arr[4]="5";
 $arr[5]="6";
 $arr[6]="7";

 $arr_lbl[0]="GURU MATAPELAJARAN";
 $arr_lbl[1]="GURU KELAS";
 $arr_lbl[2]="SU PEPERIKSAAN";
 $arr_lbl[3]="SU PEPERIKSAAN/GURU KELAS";
 $arr_lbl[4]="PPD";
 $arr_lbl[5]="JPN";
 $arr_lbl[6]="SUPER ADMIN";
 
 echo "<option value=\"\">-Pilih-</option>";
 for($i=0;$i<$cnt_level;$i++){
     echo "<option value=\"".$arr[$i]."\">".$arr[$i]." - ".$arr_lbl[$i]."</option>";
 }
?>
</select>
</td>
</tr>
<tr><td>KOD SEKOLAH</td><td>:</td><td><input type="text" name="txtKodSek" id="txtKodSek" size="10" maxlength="7"/></td></tr>
<tr><td>KOD JPN</td><td>:</td><td>
   <select name="txtKodJPN" id="txtKodJPN" onChange="list_ppd(this.value);">
<option value="">-Pilih-</option>
<?php
  $query=" select KodNegeri,Negeri from tknegeri ";
  if ($_SESSION["level"]=="6")
    $query.=" where  KodNegeri='".$_SESSION["kodnegeri"]."'";
  $query.=" order by KodNegeri";
  $result = oci_parse($conn_sispa,$query);

  oci_execute($result);
  while($data=oci_fetch_array($result)){
    $kod=$data["KODNEGERI"];
	$ppd=$data["NEGERI"];
	echo "<option value=\"$kod\">$kod - $ppd</option>";
  }
?>


</select>
</td></tr>
<tr><td>KOD PPD</td><td>:</td><td>
<div id="divPPD">
<select name="txtKodPPD" id="txtKodPPD">
<option value="">-Pilih-</option>
<?php
  $query=" select KodPPD,PPD from tkppd where KodNegeri='$kodjpn'";
  $result = oci_parse($conn_sispa,$query);

  oci_execute($result);
  while($data=oci_fetch_array($result)){
    $kod=$data["KODPPD"];
	$ppd=$data["PPD"];
	echo "<option value=\"$kod\">$kod - $ppd</option>";
  }

?>
</select>
</div>
</td></tr>

<tr><td>LOGIN</td><td>:</td><td><input type="text" name="txtLogin" size="30" maxlength="30"  /></td></tr>
<tr><td>KATALALUAN</td><td>:</td><td><input type="text" name="txtKatalaluan" size="30" maxlength="30"  /></td></tr>
<tr><td colspan="3"><input type="submit" value="Simpan" name="simpan" />
<input type="hidden" name="post" value="1">
<input type="button" value="Batal" name="batal" onClick="location.href='senarai-user.php';"/>
</td></tr>
</TABLE>
</form>
<?php

?>
</td>
<?php include 'kaki.php';?>