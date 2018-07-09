<?php 
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Tukar Kata Laluan</p>

<script type='text/javascript'>

function formValidator(){
	// Make quick references to our fields
	var nokp = document.getElementById('nokp');
	var user = document.getElementById('user');
	var pswdlama = document.getElementById('pswdlama');
	var pswdbaru = document.getElementById('pswdbaru');
	var cpswdbaru = document.getElementById('cpswdbaru');
	
	// Check each input in the order that it appears in the form!
	if(notEmpty(nokp, "Isikan Nokp")){
		if(notEmpty(user, "Isikan ID Pengguna")){
			if(notEmpty(pswdlama, "Isikan Katalaluan Lama")){
				if(notEmpty(pswdbaru, "Isikan Katalaluan Baru")){
					if(notEmpty(cpswdbaru, "Isikan Sah Katalaluan Baru")){
						     if(pswdbaru.value!=cpswdbaru.value){
								 alert("Katalaluan dan Sah Katalaluan tidak sama !");
								 return false;
							 }
							 return(chkPassword(pswdbaru.value))
								 
							 
							
						}
					}
				}
			}
    }	
	return false;
	
}

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
   
function notEmpty(elem, helperMsg){
	if(elem.value.length == 0){
		alert(helperMsg);
		elem.focus(); // set the focus to this input
		return false;
	}
	return true;
}
</script>
		<link rel="stylesheet" type="text/css" href="tulisexam.css">
		<form autocomplete="off" name="tukar" method="post" onsubmit='return formValidator()' action="data_tukar_pswd.php">
		<table width="500" align="center" cellpadding="0" cellspacing="0">
          <br><br><br><br><br>
		  <tr>
            <td width="13" height="26"><div align="right"><img src="admin/images/panel-left.gif" width="13" height="26"></div></td>
            <td width="100%" align="center" valign="middle" background="admin/images/panel-main.gif"><strong> TUKAR KATALALUAN </strong></td>
            <td width="13" height="26"><div align="left"><img src="admin/images/panel-right.gif" width="13" height="26"></div></td>
          </tr>
          <tr>
            <td background="admin/images/border-left.gif">&nbsp;</td>
            <td><div align="center">
              <table width="500">
                <tr>
                  <td bgcolor=""><div align="center">
				  	<br>
                    <table width="100%"  border="0" cellspacing="10">
                      <tr>
                        <td>
                          
                            <table width="500" cellspacing="5" cellpadding="0">
                              <tr>
                                <td width="203"><div align="left">NOKP</div></td>
                                <td width="280">
                                  <div align="left"><?php echo"$nokp"; ?>
                                    <input name="nokp" type="hidden" id="nokp" size="50" maxlength="12" value="<?php echo"$nokp"; ?>">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">ID PENGGUNA </div></td>
                                <td>
                                  <div align="left">
                                    <input name="user" type="text" id="user" size="50">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">KATA LALUAN LAMA </div></td>
								<td><div align="left">
								  <input name="pswdlama" type="password" id="pswdlama" size="50">
							    </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">KATA LALUAN BARU </div></td>
                                <td>
                                  <div align="left">
                                    <input name="pswdbaru" type="password" id="pswdbaru" size="50">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">SAHKAN KATA LALUAN BARU </div></td>
								<td><div align="left">
								  <input name="cpswdbaru" type="password" id="cpswdbaru" size="50">
							    </div></td>
                              </tr>
                               <tr>
                                 <td><div align="left"></div></td>
                                 <td><div align="left"></div></td>
                               </tr>
                               <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left">
                                  <input name="submit" type="submit" value="Hantar" >
                                </div></td>
                              </tr>
                            </table>
                         
					    </td>
                      </tr>
                    </table>
                  </div></td>
                </tr>
              </table>
            </div></td>
            <td background="admin/images/border-right.gif">&nbsp;</td>
          </tr>
          <tr>
            <td><img src="admin/images/border-bleft.gif" width="13" height="20"></td>
            <td background="admin/images/border-bmain.gif">&nbsp;</td>
            <td><div align="left"><img src="admin/images/border-bright.gif" width="13" height="20"></div></td>
          </tr>
        </table>
		</form>
		</td>
<?php include 'kaki.php';?> 