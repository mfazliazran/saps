<?php
 // $pos=strrpos($_SERVER['HTTP_REFERER'],"/");
 // $s=substr($_SERVER['HTTP_REFERER'],0,$pos);
 // if ($s!="http://saps.moe.gov.my")
    // die("Die");
session_start();
include 'config.php';
//include('include/function.php');
include 'fungsi2.php';
?>
<script type="text/javascript">
/*function semak()
{

  if (document.frmkump.txtKatalaluan1.value==''){
    alert('Katalaluan Baru mesti diisi !');
    document.frmkump.txtKatalaluan1.focus();
    return false;
  }

  s=document.frmkump.txtKatalaluan1.value;
  if (s.length<8){
    alert('Katalaluan tidak boleh kurang dari 8 aksara !');
    document.frmkump.txtKatalaluan1.focus();
    return false;
  }

  if (document.frmkump.txtSahKatalaluan.value==''){
    alert('Sahkan Katalaluan Baru mesti diisi !');
    document.frmkump.txtSahKatalaluan.focus();
    return false;
  }

  if (document.frmkump.txtSahKatalaluan.value==''){
    alert('Sahkan Katalaluan Baru diisi !');
    document.frmkump.txtSahKatalaluan.focus();
    return false;
  }
  if (document.frmkump.txtKatalaluan1.value!=document.frmkump.txtSahKatalaluan.value){
    alert('Katalaluan Baru dan Sahkan Katalaluan Baru mesti sama !');
    document.frmkump.txtKatalaluan1.focus();
    return false;
  }
  
  
return true;

}*/

function chkPassword(txtpass,txtold)
  {
     var len=0;
	 var len2=0;
	 var len3 = 0;
	 var chr=0;
	 var chr2 = 0;
	 var num=0;
	 var special=0;
	 var combine = 0;
	 
	  if (document.frmkump.txtKatalaluan1.value==''){
		alert('Katalaluan mesti diisi !');
		return false;
	  } 

	  if (document.frmkump.txtSahKatalaluan.value==''){
		alert('Pengesahan Katalaluan mesti diisi !');
		return false;
	  } 
	  if (document.frmkump.txtKatalaluan1.value!=document.frmkump.txtSahKatalaluan.value){
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
	   
     
     //if txtpass has lower or uppercase characters give 1 point
     //if ( ( txtpass.match(/[a-z]/) ) || ( txtpass.match(/[A-Z]/) ) )
		//chr=1;
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

     if (txtpass==txtold){
      alert("Katalaluan yang baru tidak boleh sama dengan katalaluan lama !");
	  return false;
     }
	 	
     /*if (len==0){
      alert("Katalaluan mesti mengandungi sekurang-kurangnya 6 aksara !");
	  return false;
     }
	 
     if (len2==1){
      alert("Katalaluan tidak boleh melebihi 12 aksara !");
	  return false;
     }*/
	 
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

$user_lama = oci_escape_string($_SESSION['SESS_MEMBER_ID']);
$pass_lama = oci_escape_string($_SESSION['SESS_PASSWORD']);
$kodsekolah = $_SESSION['SESS_KODSEK'];

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

if ($_POST["txtPost"]=="1"){
/*	$kodsekolah = oci_escape_string($_POST['txtkodsekolah']);
	$pass_baru1 = oci_escape_string($_POST['txtKatalaluan1']);	
	$pass_baru2 = oci_escape_string($_POST['txtSahKatalaluan']);
	$loginsah=$_POST["txtSahKatalaluan"];
	$usersah=$_POST["user_lama"];
	
	if($pass_baru1 == $pass_baru2){
		$sql = "SELECT USER1,PSWD,NOKP,LEVEL1,TING,KELAS,KODNEGERI,STATUSLOGIN FROM login WHERE USER1= :user_lama AND KODSEK = :kod_lama";
		$stmt = OCIParse($conn_sispa,$sql);
		oci_bind_by_name($stmt, ':user_lama', $user_lama);
		oci_bind_by_name($stmt, ':kod_lama', $kodsekolah);
		OCIExecute($stmt);
		
		if (OCIFetch($stmt)){
			$update = "UPDATE login SET PSWD='$pass_baru1' , STATUSLOGIN='0' WHERE USER1= :user_lama AND KODSEK = :kod_lama";
			$run_update = OCIParse($conn_sispa,$update);
			oci_bind_by_name($run_update, ':user_lama', $user_lama);
			oci_bind_by_name($run_update, ':kod_lama', $kodsekolah);
			OCIExecute($run_update);
			session_destroy();
			message("Pertukaran katalaluan berjaya. Sila login semula dengan menggunakan katalaluan baru.",1);
            pageredirect("index.php");
		}//ofifetch	
  	}//if pass1=pass2*/
	$user_lama = $_POST["user_lama"];
	$pass_baru1 = oci_escape_string($_POST['txtKatalaluan1']);
	$encrypted = encrypt("$pass_baru1", "vtech%5%52018");	
	$lvl = $_SESSION["level"];
	//echo "lvl ".$_SESSION['level'];
	if ($_SESSION["level"]=="7"){//admin
		$update1 = "UPDATE login SET PSWD='$encrypted' , LastChangePassword=TO_DATE('".date("Y-m-d h:i:s")."', 'yyyy/mm/dd hh24:mi:ss') WHERE USER1= :user_lama";// AND LEVEL1 = :level";
		$run_update1 = OCIParse($conn_sispa,$update1);
		oci_bind_by_name($run_update1, ':user_lama', $user_lama);
		oci_bind_by_name($run_update1, ':level', $lvl);
		OCIExecute($run_update1);
	}else{
		$update = "UPDATE login SET PSWD='$encrypted' , LastChangePassword=TO_DATE('".date("Y-m-d h:i:s")."', 'yyyy/mm/dd hh24:mi:ss') WHERE USER1= :user_lama AND KODSEK = :kod_lama";
		$run_update = OCIParse($conn_sispa,$update);
		oci_bind_by_name($run_update, ':user_lama', $user_lama);
		oci_bind_by_name($run_update, ':kod_lama', $kodsekolah);
		OCIExecute($run_update);
	}
	//die($update);
	
	
	
	session_destroy();
	message("Pertukaran katalaluan berjaya. Sila login semula dengan menggunakan katalaluan baru.",1);
	pageredirect("index.php");
}//post
//}
?>
<title>Sistem Analisis Peperiksaan Sekolah</title>
<form action="" method="post" name="frmkump">
<table align="center" width="200" border="0">
  <tr>
    <td><img src="images/saps2011/banner.jpg" align="align""center" alt="" width="1210" height="96" /></td>
  </tr>
  <tr>
    <td><p>&nbsp;</p>
      <table align="center" bgcolor="#FFF68F" width="100%" border="0">
      <tr>
        <td width="98" rowspan="6"><img src="images/saps2011/9.png" alt="" width="80" height="100" /></td>
        <td height="21" colspan="4" align="center"><font color="#FF0000"><blink><b>Sila masukkan katalaluan yang baru</b></blink></font></td>
        </tr>
        <p>
      <tr>
        <td width="212" height="24"><b>ID Pengguna</b></td>
        <td width="7"><b>:</b></td>
        <td width="144"><?php echo $user_lama;?>
         <input type="hidden" name="user_lama" id="user_lama" value="<?php echo $user_lama;?>"/> 
        <!--<input type="hidden" name="pass_lama" id="pass_lama" value="<?php echo $pass_lama;?>"/> -->
        </td>
        <td width="297">&nbsp;</td>
      </tr>
<!--      <tr>
        <td><b>Kod Sekolah / PPD / JPN</b></td>
        <td><b>:</b></td>
         <td><input name="txtkodsekolah" align="top" type="text" id="txtkodsekolah"  onBlur="this.value=this.value.toUpperCase();"/></td>
        <td><font color="red">* JPN, sila guna NOMBOR negeri sahaja (Cth : Selangor : 10 bukan B10)</font></td>
      </tr>-->
      <tr>
        <td><b>Katalaluan Baru</b></td>
        <td><b>:</b></td>
        <td><input name="txtKatalaluan1" align="top" type="password" id="txtKatalaluan1" maxlength="20" autocomplete="new-password"/></td>
        <td>
        <p>&nbsp;</p></td>
      </tr>
      <tr>
        <td><b>Sahkan Katalaluan Baru</b></td>
        <td><b>:</b></td>
        <td><input name="txtSahKatalaluan" type="password" id="txtSahKatalaluan" maxlength="20" autocomplete="new-password"/></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="hidden" name="txtOld" id="txtOld" maxlength="20" value="<?php echo $pass_lama; ?>">
        <input type="hidden" name="txtKodsekolah" id="txtKodsekolah" value="<?php echo $kodsekolah; ?>">
        <input type="hidden" name="txtPost" id="txtPost" value="1">
        <input name="hantar2" type="button" id="hantar2" value="Kembali" onClick="location.href ='access-denied.php'" />
          <input class="button" type="submit" name="Submit" value="Hantar" onClick="return chkPassword(document.getElementById('txtKatalaluan1').value,document.getElementById('txtOld').value);"> </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="36" colspan="4" align="left"><font color="#FF0000">* Katalaluan mesti sekurang-kurangnya 12 aksara dan mengandungi kombinasi huruf dan nombor.
          <br>* Katalaluan belum pernah ditukar lebih dari 3 bulan. Katalaluan perlu ditukar sebelum anda dibenarkan mengakses Sistem Analisis Peperiksaan Sekolah</font></td>
        </tr>
    </table>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td><table width="1211" height="31" bgcolor="#0066FF" border="0">
      <tr>
        <td width="1205"><font size="1" color="#FFFFFF">
          <center>
            <font color="#FFFFFF" size="1">SILA GUNAKAN IE8, GOOGLE CHROME DAN MOZILLA 3 KEATAS SAHAJA. APLIKASI SAPS TIDAK DIUJI MENGGUNAKAN SAFARI DAN OPERA.
              Best View Screen Resolution 1280 x 1024 </font>
          </center>
        </font></td>
      </tr>
    </table></td>
  </tr>
</table>
</p>
</form>