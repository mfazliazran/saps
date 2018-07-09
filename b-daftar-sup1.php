<?php
include 'kepala.php';
$tahun =date("Y");
?>

<script type='text/javascript'>

function formValidator(){
	// Make quick references to our fields
	var kodsek = document.getElementById('kodsek');
	// Check each input in the order that it appears in the form!
	if(notEmpty(kodsek, "Isikan Kod Sekolah")){
		return true
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
<p class="subHeader">Daftar Setiausaha Peperiksaan (Admin)</p><br>
<blockquote>
                          <form id="loginForm" name="loginForm" method="post" onsubmit='return formValidator()' action="b-daftar-sup.php">
                            <table width="528" align="center" cellpadding="10" cellspacing="0">
                              <tr>
                                <td width="122"><div align="center">KOD SEKOLAH </div></td>
                                <td width="360">
                                  <div align="left">
                                    <input autocomplete="off" maxlength="7" name="kodsek" type="text" id="kodsek" onBlur="this.value=this.value.toUpperCase()">
                                   Contoh : AEA0040                                    </div></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><div align="left">
                                  <input type="submit" name="Submit" value="Hantar">
                                </div></td>
                              </tr>
  							</table>
                          </form>  </blockquote> 
						  </td>
<?php include 'kaki.php';?>                       