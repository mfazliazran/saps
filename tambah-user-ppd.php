<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
include 'fungsi2.php';

  if ($_SESSION["level"]<>"5")
    die ("Skrin ini hanya untuk level PPD");


if ($_POST["post"]=="1"){
  $nokp=$_POST["txtLogin"];//$_POST["txtNoKP"];
  $nama=oci_escape_string($_POST["txtNama"]);
  $jawatan=oci_escape_string($_POST["txtJawatan"]);
  $level="5";
  $login=$_POST["txtLogin"];
  $katalaluan=$_POST["txtKatalaluan"];
  $kodppd=$_SESSION["kodsek"];
  $kodjpn=$_SESSION["kodnegeri"];	
  $tahun=date("Y");
  $statussek="";

  $query2=" select PPD from TKPPD where KODPPD='$kodppd'";
  $result2 = oci_parse($conn_sispa,$query2);
  oci_execute($result2);
  $data2=oci_fetch_array($result2);
  $ppd=$data2["PPD"];
  $daerah=ereg_replace("PPD","",$ppd);


  $query2=" select NEGERI from TKNEGERI where KODNEGERI='$kodjpn'";
  $result2 = oci_parse($conn_sispa,$query2);
  oci_execute($result2);
  $data2=oci_fetch_array($result2);
  $negeri=$data2["NEGERI"];

	//Check for duplicate login ID
	$qry_su = "SELECT *  FROM login WHERE user1='$login'";
	if (count_row($qry_su)>0){
		     message("Username telah wujud..sila tukar yang lain",1);
     		 location("tambah-user-ppd.php");
	} else {							

	  $query_sm=" insert into login(tahun,nokp,user1,pswd,kodsek,namasek,nama,level1,statussek,negeri,daerah,kodnegeri) 
				  values ('$tahun','$nokp','$login','$katalaluan','$kodppd','$jawatan','$nama','$level','$statussek','$negeri',' $daerah','$kodjpn')";
	  $result_sm = oci_parse($conn_sispa,$query_sm);
	  //die($query_sm);
	
	  oci_execute($result_sm);
	  location("senarai-user.php");
	}
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

function semak()
{
if (document.frm1.txtNama.value==""){
  alert("Nama mesti diisi !");
  document.frm1.txtNama.focus();
  return false;
}  
if (document.frm1.txtJawatan.value==""){
  alert("Jawatan mesti diisi !");
  document.frm1.txtJawatan.focus();
  return false;
}  
if (document.frm1.txtLogin.value==""){
  alert("Login mesti diisi !");
  document.frm1.txtLogin.focus();
  return false;
}  
if (document.frm1.txtKatalaluan.value==""){
  alert("Katalaluan mesti diisi !");
  document.frm1.txtKatalaluan.focus();
  return false;
}  

return confirm("Simpan maklumat Pengguna?");
}
</script>

<td valign="top" class="rightColumn">
<p class="subHeader">Tambah Pengguna</p>

<form name="frm1" method="post" action="">
<TABLE>
<tr><td>NAMA</td><td>:</td><td><input type="text" name="txtNama" id="txtNama" size="50" maxlength="50" onkeypress="return ucase(event,this);"/></td></tr>
<tr><td>JAWATAN</td><td>:</td><td><input type="text" name="txtJawatan" id="txtJawatan" size="80" maxlength="80" onkeypress="return ucase(event,this);"/></td></tr>
<tr><td>LEVEL</td><td>:</td><td><b>PPD</b><input type="hidden" name="txtLevel" value="6"></td></tr>
<tr><td>LOGIN</td><td>:</td><td><input type="text" name="txtLogin" size="30" maxlength="30"  /></td></tr>
<tr><td>KATALALUAN</td><td>:</td><td><input type="text" name="txtKatalaluan" size="30" maxlength="30"  /></td></tr>
<tr><td colspan="3"><input type="submit" value="Simpan" name="simpan" onClick="return semak();"/>
<input type="hidden" name="post" value="1">
<input type="button" value="Kembali" name="batal" onClick="location.href='senarai-user.php';"/>
</td></tr>
</TABLE>
</form>
<?php

?>
</td>
<?php include 'kaki.php';?>