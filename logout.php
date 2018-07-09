<?php
	//Start session
	//die("sistem saps sedang diselenggara sehingga 8.00am 9 januari 2012. sebarang kesulitan amat dikesali.");
	echo "<title>SAPS</title>\n";
	//include("under.php");
	//die();
	
	//Unset the variable SESS_MEMBER_ID stored in session
	/* disabled on 14/6/2016 by naeim
	session_start();
	
	$username=$_SESSION['SESS_MEMBER_ID'];
	*/
	include_once("config.php");
	//$stmt=OCIParse($conn_sispa,"DELETE FROM tmpjpep WHERE user='$username'");
	//OCIExecute($stmt);	
	
	// fungsi untuk paparkan useronline
	
    $stmt=OCIParse($conn_sispa,"delete from useronline where session_id='".session_id()."'");///of online user
	OCIExecute($stmt);	
    
	/*
	//DISABLED atas arahan En Suhaimi...berdasarkan email dr perimas network pada 17/06/2013
	$stmt=OCIParse($conn_sispa,"SELECT count(*) as BIL from useronline");
	OCIExecute($stmt);
	if(OCIFetch($stmt))
	  $biluser=OCIResult($stmt,"BIL");
	  */
	
	
	//OCILogoff($conn_sispa);
	/* disabled on 14/6/2016 by naeim
	unset($_SESSION['SESS_MEMBER_ID']);
	
  	session_destroy();*/
  session_start();
  session_destroy();
/*  session_start();
  $token = md5(uniqid(rand(), true));
  $_SESSION['token'] = $token;*/
  
  //header("location: access-denied.php");
  //pageredirect("access-denied.php");
  echo "<title>SAPS</title>\n";
  
 ?>
<style type="text/css">
body {
  height: 100%;
  min-height: 610px;
  margin: 0px;
  font-family: Verdana, Arial, Geneva, Helvetica, sans-serif;
  font-size: 12px;
  color: #333333;
  position: relative;
  line-height: 1.2em;
  background-color: #dfeff9; 
  background: #FFFFFF no-repeat center top;
  }
  
a {
  color: #006699;
  text-decoration: none; }
a:hover {
    text-decoration: underline; }
a {
  font-size: 11px; }

input[type=text], textarea, input[type=password] {
  border: 2px solid #a8a8a8;
  font-family: inherit;
  padding: 3px;
  width: 254px;
  margin-right: 4px; }
  input[type=text].hint, textarea.hint, input[type=password].hint {
    color: #999999; }
  input[type=text].error, textarea.error, input[type=password].error {
    border-color: red; }

label {
  font-size: 85%; }

html {
  height: 100%; }  
  
  
.glossymenu{
margin: 5px 0;
padding: 0;
width: 160px; /*width of menu*/
border: 1px solid #9A9A9A;
border-bottom-width: 0;
}

.glossymenu a.menuitem{
background: #004A66;
font: normal 13px "Trebuchet MS", Verdana, Helvetica, sans-serif;
color: white;
display: block;
position: relative; /*To help in the anchoring of the ".statusicon" icon image*/
width: auto;
padding: 4px 0;
padding-left: 10px;
text-decoration: none;
border-bottom:1px solid #FFF;
}

h1
{
color:orange;
text-align:left;
}

.glossymenu a.menuitem:visited, .glossymenu .menuitem:active{
color: white;
}

.glossymenu a.menuitem .statusicon{ /*CSS for icon image that gets dynamically added to headers*/
position: absolute;
top: 5px;
right: 5px;
border: none;
}

.glossymenu a.menuitem:hover{
background-image: url(../saps2011/glossyback2.gif);
}

.glossymenu div.submenu{ /*DIV that contains each sub menu*/
background: white;
}

.glossymenu div.submenu ul{ /*UL of each sub menu*/
list-style-type: none;
margin: 0;
padding: 0;
}

.glossymenu div.submenu ul li{
border-bottom: 1px solid blue;
}

.glossymenu div.submenu ul li a{
display: block;
font: normal 12px "Trebuchet MS", Verdana, Helvetica, sans-serif;
color: black;
text-decoration: none;
padding: 2px 0;
padding-left: 10px;
}

.garisPutihfoot {
	padding:10px 0 10px 0;
	margin:10px 0;
	height:20px;

}

#kosong {
	margin:0 auto;
	padding:0;
	height:40px;
	width:100%;	
}

</style>
<body leftmargin="0" topmargin="0" bottommargin="0" marginheight="0" marginwidth="0">
<table width="1216"  align="center" border="0">
  <tr> 
    <td width="1210" height="104"><img src="images/saps2011/banner_moe_baru.jpg" align="align""center" alt="" width="1210" height="96" /> 
      <table width="1210" bgcolor="#FF9900" border="0">
        <tr> 
          <td height="1"></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="300"><font size="3" color="#FF0000"><strong> 
      <center>
      </center>
      </strong> <table width="1200" border="0">
        <tr> 
          <td width="4" valign="top"><table width="200" border="0">
              <tr> 
                <td><img src="images/saps2012/menu.gif" alt="" width="196" height="35"></td>
              </tr>
              <tr> 
                <td><a href="muatturun.php" target="_blank"> <img src="images/saps2012/muatturun.jpg" alt="" width="197" height="70" align="center"/></td>
              </tr>
              <tr> 
                <td><a href="http://www.speedtest.net/" target="_blank"> <img src="images/saps2012/ujikelajuan.jpg" alt="" width="197" height="70" align="center"/></td>
              </tr>
              <tr> 
                <td><a href="dokumen/Panduan Am Masalah SAPS.pdf" target="_blank"> 
                  <img src="images/saps2012/faq.jpg" alt="" width="197" height="70" align="center"/></td>
              </tr>
              <tr> 
                <td><a href="ibubapa2/index.php" target="_blank"> <img src="images/saps2012/ibubapa.jpg" alt="" width="197" height="70" align="left"/></td>
              </tr>
            </table></td>
          <td width="350" valign="top"><table width="496" align="right" height="133" border="0">
              <tr> 
                <td width="490" height="78"><font size="3" align="left" color="#FFA500"><strong> 
                  <center>
                    <img src="images/saps2012/SELIT.png" width="349" align="left" height="76"> 
                  </center>
                  </strong></font></td>
              </tr>
              <tr> 
                <td height="48"><table align="right" background="images/saps2012/binder-paper-note-book.gif" width="462" border="0">
                    <tr> 
                      <td width="426"><div align="left"><font size="2" color="#000033"><strong> 
                          <center>
                            <?php 
								$s_umum=oci_parse($conn_sispa,"SELECT umum FROM umum WHERE penting='1' order by susunan");
								oci_execute($s_umum);
								while($data=oci_fetch_array($s_umum)){
								  $umum=$data['UMUM'];
								  echo "<br>$umum<br>";
								} ?>
                          </center>
                          </strong></font></div></td>
                    </tr>
                    <tr> 
                      <td height="20">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
          <td width="316" valign="top"><table width="437" align="center" height="234" border="0">
              <tr> 
                <td height="36" align="right" colspan="3"><img src="images/saps2012/login.gif" alt="" width="378" height="31"></td>
              </tr>
              <tr> 
                <td width="72" height="141"><img src="images/saps2011/01.png" alt="" width="80" height="100" /></td>
                <td colspan="2"><table width="350" bgcolor="#BDEDFF" border="0" align="center" cellpadding="5" cellspacing="0"><form name="form1" method="post" action="login-exec.php">
                    <tr> 
                      <td rowspan="5">&nbsp;</td>
                      <td>ID Pengguna</td>
                      <td><input name="user" type="text" id="user"></td>
                    </tr>
                    <tr> 
                      <td>Kata Laluan </td>
                      <td><input name="password" type="password" id="password"></td>
                    </tr>
                    <tr>
                      <td colspan="2"><font color="#FF0000">Masukkan kod pengesahan</font></td>
                    </tr>
                    <tr>
                      <td>&nbsp;<img src="randomimage.php"></td>
                      <td>&nbsp;<input type="password" name="user_verify" size="12" maxlength="5"></td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                      <td><input name="hantar" type="submit" id="hantar" value="Login"></td>
                    </tr>
                  </table></td>
              </tr>
              <!--<tr> 
                <td align="center" colspan="3">PENGGUNA ATAS TALIAN : <img src="images/saps2011/ip.gif" alt="" /> 
                  <?php echo $biluser ?> Orang</td>
              </tr>-->
              <tr> 
                <td colspan="3"><div align="center"><a href="b-daftar-id-guru.php">Daftar 
                    ID Guru</a> | <a href="b-daftar-sup1.php">Daftar SU Peperiksaan</a></div></td>
              </tr>
              <?php
                $sqlhelpdesk = "SELECT no_telefon, no_telefon2, hari1, hari2, waktu1, waktu2, rehat_biasa1, rehat_biasa2, rehat_jumaat1, rehat_jumaat2 FROM helpdesk";
                // echo $sqlhelpdesk;
                $reshelpdesk = oci_parse($conn_sispa,$sqlhelpdesk);
                $exec = oci_execute($reshelpdesk);
                // if (!$exec) {
                //     $e = oci_error($reshelpdesk);  // For oci_execute errors pass the statement handle
                //     print htmlentities($e['message']);
                //     print "\n<pre>\n";
                //     print htmlentities($e['sqltext']);
                //     printf("\n%".($e['offset']+1)."s", "^");
                //     print  "\n</pre>\n";
                // } 
                
                while($datahelpdesk = oci_fetch_array($reshelpdesk)){
                    $notelefon1 = $datahelpdesk['NO_TELEFON'];
                    // echo $notelefon1;
                    $notelefon2 = $datahelpdesk['NO_TELEFON2'];
                    $hari1 = $datahelpdesk['HARI1'];
                    $hari2 = $datahelpdesk['HARI2'];
                    $waktu1 = $datahelpdesk['WAKTU1'];
                    $waktu2 = $datahelpdesk['WAKTU2'];
                    $rehatbiasa1 = $datahelpdesk['REHAT_BIASA1'];
                    $rehatbiasa2 = $datahelpdesk['REHAT_BIASA2'];
                    $rehatjumaat1 = $datahelpdesk['REHAT_JUMAAT1'];
                    $rehatjumaat2 = $datahelpdesk['REHAT_JUMAAT2'];
                    // echo "masuk";
                }
                // echo "masuk";
                
              ?>
			  <?php 
			        $header_style="background:#2471A3; color:#FFFFFF;font-size:12px;font-weight:bold;text-align:center"; 
			        $col_style1="background:#A9CCE3; color:#000000;font-size:12px;font-weight:normal;text-align:center"; 
			        $col_style2="background:#D4E6F1; color:#000000;font-size:12px;font-weight:normal;text-align:center"; 
			  ?>
			  
              <tr align="center"> 
                <td colspan="3"><strong>Perlukan Bantuan ?</strong>
				<table cellpadding="4">
				<tr><td style="<?php echo $header_style;?>">PENGGUNA</td>
				    <td style="<?php echo $header_style;?>">PEGAWAI MEJA BANTUAN</td>
					<td style="<?php echo $header_style;?>">CARA MENDAPATKAN BANTUAN</td>
				</tr>
				<tr><td style="<?php echo $col_style1;?>">Guru MP/Guru Kelas/ Pengetua/Guru Besar</td>
                    <td style="<?php echo $col_style1;?>">SUP SAPS Sekolah</td>
                    <td style="<?php echo $col_style1;?>">Hubungi SUP SAPS sekolah anda</td>
			    </tr>
				<tr><td style="<?php echo $col_style2;?>">Setiausaha Peperiksaan Sekolah (SUP)</td>
				    <td style="<?php echo $col_style2;?>">Pegawai Unit ICT/ SPA di PPD</td>
					<td style="<?php echo $col_style2;?>">Login ke SAPS dan dapatkan nombor talian yang diperlukan</td>
				</tr>
				<tr><td style="<?php echo $col_style1;?>">Pegawai SAPS PPD dan JPN</td>
				    <td style="<?php echo $col_style1;?>">Pegawai Unit ICT/SPA di BPSH, KPM</td>
					<td style="<?php echo $col_style1;?>">Login ke SAPS dan dapatkan nombor talian yang diperlukan atau mengisi borang aduan.</td>
				</tr>
				</table></td>
              </tr>
              <!--<tr bgcolor="#FF0000"> 
                <td height="42" colspan="3" bgcolor="#0000FF"><div align="center"><a href="status_pengisian_u1.php" target="_blank"><strong><font color="#FFFFFF" size="+2">STATUS 
                    PENGISIAN U1 2014</font></strong></a></div></td>
              </tr>-->
              <!--<tr bgcolor="#FF0000"> 
                <td height="42" colspan="3" bgcolor="#0000FF"><div align="center"><a href="status_pengisian_pat.php" target="_blank"><strong><font color="#FFFFFF" size="+2">STATUS 
                    PENGISIAN PAT 2014</font></strong></a></div></td>
              </tr>-->
              <!--<tr bgcolor="#FF0000"> 
                <td height="42" colspan="3" bgcolor="#0000FF"><div align="center"><a href="status_pengisian_upsrc.php" target="_blank"><strong><font color="#FFFFFF" size="+2">STATUS 
                    PENGISIAN UPSRC 2014</font></strong></a></div></td>
              </tr>-->
              <!--<tr bgcolor="#FF0000"> 
                <td height="42" colspan="3"><div align="center"><a href="status_pengisian_pmrc.php" target="_blank"><strong><font color="#FFFFFF" size="+2">STATUS 
                    PENGISIAN PMRC</font></strong></a></div></td>
              </tr>-->
              <!--<tr bgcolor="#FF0000"> 
                <td height="42" colspan="3" bgcolor="#0000FF"><div align="center"><a href="status_pengisian_spmc.php" target="_blank"><strong><font color="#FFFFFF" size="+2">STATUS 
                    PENGISIAN SPMC 2014</font></strong></a></div></td>
              </tr>-->
              <!--<tr bgcolor="#FF0000"> 
                <td height="42" colspan="3"><div align="center"><a href="status_pengisian_ppt.php" target="_blank"><strong><font color="#FFFFFF" size="+2">STATUS PENGISIAN PPT 2013</font></strong></a></div></td>
              </tr>-->
            </table>
            <p>&nbsp;</p></td>
      </table></td>
  </tr>
  <tr> 
    <td height="107">&nbsp; <table width="1191" height="103" border="0">
        <tr> 
          <td align="right" width="243" height="72">&nbsp;</td>
          <td width="924"><table width="669" border="0">
            </table>
            <img src="images/saps2012/senarai.gif" alt="" width="300" height="36"> 
            <table width="925" align="center" border="0">
              <tr> 
                <td width="18" height="24">&nbsp;</td>
                <td width="897"><table width="900" border="0">
                    <tr> 
                      <td width="650" colspan="3"><strong><font size="2" color="#00008B"> 
                        <left>BIL</left> </font></strong><strong><font size="2" color="#00008B"> 
                        <left>TARIKH</left> </font></strong><strong><font size="2" color="#00008B"> 
                        <left> PERKARA </center> </font></strong></td>
                    </tr>
                    <?php
				  	 $sql2 = "select perkara,tarikh from penambahbaikan where status='1' order by tarikh2 DESC";
					 //$jum = count_row("select perkara,tarikh from penambahbaikan");
					 $res2 = OCIParse($conn_sispa,"select perkara,tarikh,id,tarikhkemaskini from penambahbaikan where status='1' order by tarikh2 DESC");
					 OCIExecute($res2);
					 //$bil = count_row($sql2);
					 $cnt=0;
					 //if($bil>0){
						while($data=oci_fetch_array($res2)){
							$display=1;
							$id=$data["ID"];
							$perkara=$data["PERKARA"];	
							$tarikh=$data["TARIKH"];
							$tarikhkemaskini=$data["TARIKHKEMASKINI"];
							$day = substr($tarikhkemaskini,0,2);//19/09/2011
							$month = substr($tarikhkemaskini,3,2);
							$year = substr($tarikhkemaskini,6,4);
							$calculate = mktime(0, 0, 0, date($month), date($day)+7, date($year));//exp date
							$dt_exp = date("d/m/Y", $calculate);
							
							$day1 = substr(date("d/m/Y"),0,2);//19/09/2011
							$month1 = substr(date("d/m/Y"),3,2);
							$year1 = substr(date("d/m/Y"),6,4);
							$calculate1 = mktime(0, 0, 0, date($month1), date($day1), date($year1));
							$dt_exp2 = date("d/m/Y", $calculate1);
							//echo "<br>exp - $calculate crr - $calculate1";
							//echo "<br>exp - $dt_exp crr - $dt_exp2";
							if($calculate < $calculate1)
								$display=0;
							$cnt++;
							if($cnt<=5){
							echo "<tr><td>$cnt</td>";
							echo "<td>$tarikh</td>";
							echo "<td><font size=\"4\"><strong><a href=\"new.php?ids=$id\" target=\"_blank\">$perkara </strong></a>"; if($display=='1'){echo "<img src=\"images/saps2011/blinkingNEW.gif\" width=\"17\" height=\"17\" />";} echo "</td></tr>";
								$end=1;
							} /*else{
								if($end==1){
									echo "<tr align='center'><td colspan='3'><a href='arkib.php' target='_blank'><font color=\"#FF9900\">Arkib...</font></a></td></tr>";
									$end=0;
								}
							} */	
						}
					//}
					echo "<tr align='center'><td colspan='3'><a href='arkib.php' target='_blank'><font color=\"#FF9900\">Arkib...</font></a></td></tr>";
					OCILogoff($conn_sispa);
				  ?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td height="20" colspan="3" align="right">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="51"><table width="1207" height="49" border="0">
        <tr> 
          <td height="45"><table width="200" border="0">
              <tr bgcolor="#FF9900"> 
                <td height="4"></td>
              </tr>
              <tr> 
                <td height="33"><table width="1201" height="31" bgcolor="#0066FF" border="0">
                    <tr> 
                      <td><font size="1" color="#FFFFFF"> 
                        <center>
                          <font color="#FFFFFF" size="1">SILA GUNAKAN IE8, GOOGLE 
                          CHROME DAN MOZILLA 3 KEATAS SAHAJA. APLIKASI SAPS TIDAK 
                          DIUJI MENGGUNAKAN SAFARI DAN OPERA. Best View Screen 
                          Resolution 1280 x 1024 </font> 
                        </center>
                        </font></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
