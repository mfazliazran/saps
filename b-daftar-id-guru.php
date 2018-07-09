<?php
include 'kepala.php';
?>
<script type='text/javascript'>
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

function formValidator(){
	// Make quick references to our fields
	var nokp = document.getElementById('nokp');
	var user = document.getElementById('user');
	var password = document.getElementById('password');
	var cpassword = document.getElementById('cpassword');
	
	// Check each input in the order that it appears in the form!
	if(notEmpty(nokp, "Isikan Nokp")){
		if(notEmpty(user, "Isikan Nama Pengguna")){
			if(notEmpty(password, "Isikan Kata Laluan")){
				if(notEmpty(cpassword, "Isikan Sahkan Kata Laluan")){
						if(password.value!=cpassword.value){
							alert("Katalaluan dan Sah Katalaluan tidak sama !");
							return false;
						}
						return(chkPassword(password.value))
						}
					}
				}
			}
	return false;
	
}

function notEmpty(elem, helperMsg){
	if(elem.value.length == 0){
		alert(helperMsg);
		elem.focus(); // set the focus to this input
		return false;
	}
	return true;
}
</script> 

<td valign="top" class="rightColumn">
<p class="subHeader">Daftar ID Guru</p><br>
<blockquote>
<form autocomplete="off" id="loginForm" name="loginForm" method="post" onsubmit='return formValidator()' action="register-id-guru.php">
<table width="400" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <th><div align="left">NOKP</div></th>
    <td><div align="left">
      <input maxlength="12" name="nokp" type="text" class="textfield" id="nokp" />
    </div></td>
  </tr>
  <tr>
    <th width="261"><div align="left">NAMA PENGGUNA - ID </div></th>
    <td width="201"><div align="left">
      <input maxlength="20" name="user" type="text" class="textfield" id="user" />
    </div></td>
  </tr>
  <tr>
    <th><div align="left">KATA LALUAN </div></th>
    <td><div align="left">
      <input autocomplete="off" maxlength="20" name="password" type="password" class="textfield" id="password" />
    </div></td>
  </tr>
  <tr>
    <th><div align="left">SAHKAN KATA LALUAN</div></th>
    <td><div align="left">
      <input autocomplete="off" maxlength="20" name="cpassword" type="password" class="textfield" id="cpassword" />
    </div></td>
  </tr>
  <tr>
    <td><div align="left"></div></td>
    <td><div align="left">
      <input type="submit" name="Submit" value="Daftar" />
    </div></td>
  </tr>
</table></form>
</blockquote> 
</td>
<?php include 'kaki.php';?>                       