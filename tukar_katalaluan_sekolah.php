<?php 
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';
if ($_SESSION['level']<>"7" and $_SESSION['level']<>"9" )
{
	echo "test";
 // die("Skrin ini hanya untuk kegunaan admin sahaja!");
}

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Katalaluan Sekolah</p>

Skrin bertujuan untuk menukar katalaluan semua pengguna di dalam sekolah.
<br><br>
<script type="text/javascript">


function semak()
  {
     var len=0;
	 var len2=0;
	 var len3 = 0;
	 var chr=0;
	 var chr2 = 0;
	 var num=0;
	 var special=0;
	 var combine = 0;

	 
	txtpass=document.frm1.txtKatalaluan.value; 



	if(document.frm1.txtKodSekolah.value==""){
		alert('Kod Sekolah mesti diisi !');
		return false;
	}

	if (document.frm1.txtKatalaluan.value==''){
		alert('Katalaluan mesti diisi !');
		return false;
	  } 

	  if (document.frm1.txtSahKatalaluan.value==''){
		alert('Pengesahan Katalaluan mesti diisi !');
		return false;
	  } 
	  if (document.frm1.txtKatalaluan.value!=document.frm1.txtSahKatalaluan.value){
		alert('Katalaluan dan Pengesahan Katalaluan tidak sama !');
		return false;
	  }
	 
     //if txtpass bigger than 6 give 1 point
     if (txtpass.length >= 6)
	   len=1;
	   
     if (txtpass.length > 12)
	   len2=1;
	   
	 if(txtpass.length < 12)
	 	len3 = 1;

	//if txtpass has lowercase characters give 1 point
	if ( txtpass.match(/[a-z]/) )
		chr=1;
	//if txtpass has uppercase characters give 1 point	
	if ( txtpass.match(/[A-Z]/) )
		chr2=1;
	
	if(chr== 1 && chr2==1)
		combine = 1;
     
     //if txtpass has at least one number give 1 point
     if (txtpass.match(/\d+/))
		num=1;
     //if txtpass has at least one special caracther give 1 point
     if ( txtpass.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )
		special=1;


	 
	 if (len3==1){
      alert("Katalaluan tidak boleh kurang 12 aksara !");
	  return false;
     }
	 
     if (!(combine==1 && num==1 && special==1)){
        alert("Katalaluan mesti mengandungi kombinasi huruf kecil dan besar , nombor dan aksara khas !");
		return false;
	 }
    return true;	 

   }
</script>
<?php
 global $conn_sispa;

 function encrypt($string, $key) {
	$result = '';
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	}
	return base64_encode($result);
}


if($_POST["post"]=="1"){
	$kodsekolah=$_POST["txtKodSekolah"];
	$katalaluan=$_POST["txtKatalaluan"];

	$encrypted = encrypt("$katalaluan", "vtech%5%52018");	

	$ressemak=oci_parse($conn_sispa,"select kodsek,namasek from tsekolah where kodsek=:kodsek");
	oci_bind_by_name($ressemak, ':kodsek', $kodsekolah);
	oci_execute($ressemak);
	if($data=oci_fetch_array($ressemak)){
		$kodsek2=$data["KODSEK"];
		$namasek2=$data["NAMASEK"];
		$update1 = "UPDATE login SET PSWD=:pswd , LastChangePassword=TO_DATE('2018-01-01', 'yyyy/mm/dd hh24:mi:ss') WHERE KODSEK= :kodsek";
		$run_update1 = oci_parse($conn_sispa,$update1);
		oci_bind_by_name($run_update1, ':pswd', $encrypted);
		oci_bind_by_name($run_update1, ':kodsek', $kodsekolah);
		oci_execute($run_update1);
		echo "Katalaluan semua pengguna <b>$kodsek2 - $namasek2</b> telah ditukar. ";
	} else {
		echo "<strong><font color=\"#FF0000\">Kod Sekolah tidak wujud dalam database SAPS.</font></strong>";
	}
}
 else {
 ?>
 
 
<form name="frm1" method="post" action="tukar_katalaluan_sekolah.php" autocomplete="off">
<table width="=600" border="0" bgcolor="#FFFFFF">
<tr><td>KOD SEKOLAH</td><td>:</td>
<?php
    echo "<td><input type=\"text\" size=\"10\" maxlength=\"7\" name=\"txtKodSekolah\" id=\"txtKodSekolah\"></td>";
?>	
</tr>
<tr><td>KATALALUAN</td><td>:</td>
<?php
    echo "<td><input type=\"password\" size=\"20\" maxlength=\"20\" name=\"txtKatalaluan\" id=\"txtKatalaluan\"></td>";
?>	
</tr>
<tr><td>SAHKAN KATALALUAN</td><td>:</td>
<?php
    echo "<td><input type=\"password\" size=\"20\" maxlength=\"20\" name=\"txtSahKatalaluan\" id=\"txtSahKatalaluan\"></td>";
?>	
</tr>
<tr><td colspan="3">
  <input name="Simpan" type="submit" value="Hantar" onClick="return semak();">
  <input type="hidden" name="post" value="1">
</td></tr>
</table>
</form>

<?php
 }
 
echo "</td>";
include 'kaki.php'; 
?>