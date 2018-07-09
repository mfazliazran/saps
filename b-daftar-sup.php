<?php
include 'kepala.php';
include 'config.php';
include 'fungsi.php';
include "input_validation.php";
$kodsek= validate($_POST['kodsek']);

$q_login="SELECT * FROM login WHERE kodsek= :kodsek AND (level1='3' OR level1='4')";
$stmt=OCIParse($conn_sispa,$q_login);
oci_bind_by_name($stmt, ':kodsek', $kodsek);
OCIExecute($stmt);

if (OCIFetch($stmt))
{
	message("Admin sekolah anda telah wujud",1);
	location("b-daftar-sup1.php");

} else {
		$q_sek = "SELECT * FROM tsekolah WHERE kodsek= :kodsek2";
		$stmt=OCIParse($conn_sispa,$q_sek);
		oci_bind_by_name($stmt, ':kodsek2', $kodsek);
		OCIExecute($stmt);
		if($row = OCIFetch($stmt)){
			//$num = count_row($q_sek);
			$namasek = OCIResult($stmt,"NAMASEK");
			$kodppd = OCIResult($stmt,"KODPPD");
			$negeri = OCIResult($stmt,"NEGERI");
			$num=1;
		}

		if($num==0){ 
			message("Kod Sekolah ini ($kodsek) tiada dalam pangkalan data",1);
			location("b-daftar-sup1.php");
		} else {
					$q_ppd="SELECT * FROM tkppd WHERE KodPPD= :kodppd";
					$stmk=OCIParse($conn_sispa,$q_ppd);
					oci_bind_by_name($stmk, ':kodppd', $kodppd);
					OCIExecute($stmk);
					$rowp=OCIFetch($stmk);
					$ppd=OCIResult($stmk,"PPD");
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
						var nama = document.getElementById('nama');
						var jan = document.getElementById('jan');
						var kodsek = document.getElementById('kodsek');
						var daerah = document.getElementById('daerah');
						var user = document.getElementById('user');
						var password = document.getElementById('password');
						var cpassword = document.getElementById('cpassword');
						var keycode = document.getElementById('keycode');
						
						// Check each input in the order that it appears in the form!
						if(notEmpty(nokp, "Isikan Nokp")){
							if(notEmpty(nama, "Isikan Nama")){
								if(notEmpty(jan, "Isikan Jantina")){
									if(notEmpty(kodsek, "Isikan Kod Sekolah")){
										  if(notEmpty(user, "Isikan Nama Pengguna")){
												if(notEmpty(password, "Isikan Kata Laluan")){
													if(notEmpty(cpassword, "Isikan Sahkan Kata Laluan")){
														if(notEmpty(keycode, "Isikan Kata Kunci")){
															
														if(password.value!=cpassword.value){
															alert("Katalaluan dan Sah Katalaluan tidak sama !");
															return false;
														}
														return(chkPassword(password.value))
														
														
																
															}//katakunci
														}//sahkan kata laluan
													}//isikan kata laluan 7
												}
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
					<p class="subHeader">Daftar Setiausaha Peperiksaan (Admin)</p><br>
					<blockquote>
					  <form autocomplete="off" id="loginForm" name="loginForm" method="post" onsubmit='return formValidator()' action="daftar_admin.php">
						<table width="600" align="center" cellpadding="0" cellspacing="5">
						  <tr>
							<td><div align="left">NAMA SEKOLAH </div></td>
							<td>
							  <div align="left">
								<input name="kodsek" type="hidden" id="kodsek"  value="<?php echo "$kodsek"; ?>">
							  : <?php echo "$namasek - $kodsek"; ?></div></td>
						  </tr>
						  <tr>
							<td><div align="left">DAERAH</div></td>
							<td><div align="left">
							  <input name="daerah" type="hidden" id="daerah" value=" <?php echo "$ppd"; ?>">
							: <?php echo "$ppd"; ?></div></td>
						  </tr>
						  <tr>
							<td width="167"><div align="left">NOKP</div></td>
							<td width="316">
							  <div align="left">
								<input name="nokp" type="text" id="nokp" size="50" maxlength="12">
								</div></td>
						  </tr>
						  <tr>
							<td><div align="left">NAMA</div></td>
							<td>
							  <div align="left">
								<input maxlength="80" name="nama" type="text" id="nama" onBlur="this.value=this.value.toUpperCase()" size="50">
								</div></td>
						  </tr>
						  <tr>
							<td><div align="left">JANTINA</div></td>
							<td>
							  <div align="left">
								<select name="jan" id="jan">
								  <option value=""></option>
								  <option value="L">LELAKI</option>
								  <option value="P">PEREMPUAN</option>
								</select>
							  </div></td>
						  </tr>
						  <tr>
							<td><div align="left">NAMA PENGGUNA (ID) </div></td>
							<td>
							  <div align="left">
								<input maxlength="30" name="user" type="text" id="user" size="50">
								</div></td>
						  </tr>
						  <tr>
							<td><div align="left">KATA LALUAN </div></td>
							<td>
							  <div align="left">
								<input autocomplete="off" name="password" type="password" id="password" size="50" maxlength="15">
								</div></td>
						  </tr>
						  <tr>
							<td><div align="left">SAHKAN KATA LALUAN</div></td>
							<td>
							  <div align="left">
								<input autocomplete="off" name="cpassword" type="password" id="cpassword" size="50" maxlength="15">
								</div></td>
						  </tr>
						  <tr>
							<td><div align="left">KATA KUNCI </div></td>
							<td>
							  <div align="left">
								<input maxlength="30" name="keycode" type="text" id="keycode" size="50">
							  </div></td>
						  </tr>
						  <tr>
							<td>
							  <div align="left">
								<input name="level" type="hidden" id="level" value="3" size="3">
								<input name="tahun" type="hidden" id="tahun" value="<?php echo"$tahun";?>" size="8">
								</div></td>
							<td>
							  <div align="left">
								<input maxlength="30" name="negeri" type="hidden" id="negeri" value="<?php echo "$negeri"; ?>">                                   
							  </div></td>
	
						  </tr>
						  <tr>
							<td><div align="left"></div></td>
							<td>                                  <div align="left">
								<input type="submit" name="Submit" value="Hantar">
							  <a href="b-daftar-sup1.php">BATAL</a></div></td>
						  </tr>
						</table>
					  </form>  </blockquote> <?php }

				}?>
	</td>
<?php include 'kaki.php';?>                       