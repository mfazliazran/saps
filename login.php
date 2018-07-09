<?php
	//Start session
	//Unset the variable SESS_MEMBER_ID stored in session
	$username=$_SESSION['SESS_MEMBER_ID'];
	include_once("config.php");
	//$stmt=OCIParse($conn_sispa,"DELETE FROM tmpjpep WHERE user='$username'");
	//OCIExecute($stmt);	

    $stmt=OCIParse($conn_sispa,"delete from useronline where session_id='".session_id()."'");///of online user
	OCIExecute($stmt);	
    
	$stmt=OCIParse($conn_sispa,"SELECT count(*) as BIL from useronline");
	OCIExecute($stmt);
	if(OCIFetch($stmt))
	  $biluser=OCIResult($stmt,"BIL");
	    
	//OCILogoff($conn_sispa);
	unset($_SESSION['SESS_MEMBER_ID']);
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
<table width="1211"  align="center" border="0">
  <tr>
    <td width="1205" height="104"><img src="images/saps2011/banner.jpg" align="align""center" alt="" width="1210" height="96" />
      <table width="1210" bgcolor="#FF9900" border="0">
        <tr>
          <td height="1"></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td height="346"><font size="3" color="#FF0000"><strong><center>
</center>
    </strong>
      <table width="1200" border="0">
        <tr>
        <td width="4" height="319"><table width="200" align="center" border="0">
          <tr>
            <td><a href="muatturun.php" target="_blank"><img src="images/saps2011/MUAT.jpg" alt="" width="197" height="70" align="center"/></td>
          </tr>
          <tr>
            <td><a href="http://www.speedtest.net/" target="_blank"><img src="images/saps2011/LAJU.jpg" alt="" width="197" height="70" align="center"/></td>
          </tr>
           <tr>
            <td><a href="faqsaps.php" target="_blank"><img src="images/saps2011/FAQQ.jpg" alt="" width="197" height="70" align="center"/></td>
          </tr>
          <tr>
            <td><a href="penambahbaikkan.php" target="_blank"></td>
          </tr>
          <tr>
            <td><a href="saps_ibubapa/index.php" target="_blank"><img src="images/saps2011/IBUBAPA.jpg" alt="" width="197" height="70" align="left"/></td>
          </tr>
        </table></td>
        <td width="350"><table width="500" align="right" height="130" border="0">
          <tr>
            <td width="468" height="36"><font size="3" align="align""center" color="#FFA500"><strong><center>PENGUMUMAN PENTING</center></strong></font></td>
          </tr>
          <tr>
            <td height="88"><table width="493" border="0">
              <tr>
                <td><div align="left"><font size="2" color="#000033"><strong><center>
				<?php 
				$s_umum=oci_parse($conn_sispa,"SELECT * FROM umum WHERE penting='1'");
				oci_execute($s_umum);
				while($data=oci_fetch_array($s_umum)){
					$umum=$data['UMUM'];
					echo "$umum<br><br>";
				} ?>
                </center></strong></font></div>
                </td>
              </tr>
              <tr>
                <td height="20">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p></td>
        <td width="316"><table width="437" align="center" height="234" border="0">
          <tr>
            <td width="72" height="36"><font color="#FFA500" size="3"><strong>LOGIN</strong></font></td>
            <td width="193">&nbsp;</td>
            <td width="158">&nbsp;</td>
          </tr>
          <tr>
            <td height="141"><img src="images/saps2011/9.png" alt="" width="80" height="100" /></td>
            <td colspan="2"><table width="350" border="0" align="center" cellpadding="5" cellspacing="0">
            <form name="form1" method="post" action="http://saps.moe.gov.my/login-exec2.php">
              <tr>
                <td rowspan="3">&nbsp;</td>
                <td>ID Pengguna</td>
                <td><input name="user" type="text" id="user"></td>
              </tr>
              <tr>
                <td>Kata Laluan </td>
                <td><input name="password" type="password" id="password"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input name="hantar" type="submit" id="hantar" value="Login"></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3">PENGGUNA ATAS TALIAN : <img src="images/saps2011/ip.gif" alt="" /> <?php echo $biluser ?> Orang</td>
          </tr>
          <tr>
            <td colspan="3"><a href="b-daftar-id-guru.php">Daftar ID Guru</a> | <a href="b-daftar-sup1.php">Daftar SU Peperiksaan</a></td>
            </tr>
        </table>
          <p>&nbsp;</p></td>
  </table></td>
  </tr>
      <td height="128"><table width="1191" height="138" border="0">
      <tr>
        <td align="right" width="243" height="79">&nbsp;</td>
        <td width="924"><table width="669" border="0">
        </table>
          <font size="3" align="align""center" color="#FFA500"><strong><img src="images/saps2011/baik2.gif" alt="" width="55" height="55"></strong></font>
          <table width="925" align="center" border="0">
            <tr>
              <td width="18" height="46">&nbsp;</td>
              <td width="897"><table width="900" border="0">
                <tr>
                  <td colspan="3"><font size="3" align="align""center" color="#FFA500"><strong>SENARAI PENAMBAHBAIKAN TERKINI </strong></font></td>
                </tr>
                <tr>
                  <td width="50"><strong><font size="2" color="#00008B">
                    <left>BIL</left>
                  </font></strong></td>
                  <td width="200"><strong><font size="2" color="#00008B">
                    <left>TARIKH</left>
                  </font></strong></td>
                  <td width="650"><strong><font size="2" color="#00008B">
                    <left>
                    PERKARA
                    </center>
                  </font></strong></td>
                </tr>
                <?php
				  	 $sql2 = "select perkara,tarikh from penambahbaikan where status='1' order by tarikh2 DESC";
					 $jum = count_row("select perkara,tarikh from penambahbaikan");
					 $res2 = OCIParse($conn_sispa,"select perkara,tarikh,id,tarikhkemaskini from penambahbaikan where status='1' order by tarikh2 DESC");
					 OCIExecute($res2);
					 $bil = count_row($sql2);
					 $cnt=0;
					 if($bil>0){
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
							if($cnt<=4){
							echo "<tr><td>$cnt</td>";
							echo "<td>$tarikh</td>";
							echo "<td><font size=\"4\"><strong><a href=\"new.php?ids=$id\" target=\"_blank\">$perkara </strong></a>"; if($display=='1'){echo "<img src=\"images/saps2011/blinkingNEW.gif\" width=\"17\" height=\"17\" />";} echo "</td></tr>";
								$end=1;
							}else{
								if($end==1){
									echo "<tr align='center'><td colspan='3'><a href='arkib.php' target='_blank'><font color=\"#FF9900\">Arkib...</font></a></td></tr>";
									$end=0;
								}
							}	
						}
					 }
					OCILogoff($conn_sispa);
				  ?>
                <!--<tr>
                  <td><font size="2"><strong><center>14/9/2011</center></strong></td>
                  <td><a href="new.php"><strong><font size="2" color="#000033">1. Menu untuk pilih jenis peperiksaan di SUP,guru kelas dan guru mata pelajaran</font><font size="4" align="align""center" color="#FFA500"><strong><img src="images/saps2011/blinkingNEW.gif" alt="" width="17" height="17" /></strong></font></strong></td>
                  </tr>
                <tr>
                  <td><center>
                    <font size="2"><strong>5/9/2011</strong></font>
                  </center></td>
                  <td><strong><font size="2" color="#000033">2. Export Data PPD dan JPN</font></strong><strong><font size="4" align="align""center" color="#FFA500"><strong><img src="images/saps2011/blinkingNEW.gif" alt="" width="17" height="17" /></strong></font></strong></td>
                  </tr>
                <tr>
                  <td><font size="2"><strong><center>25/8/2011</center></strong></font></td>
                  <td><strong><font size="2" color="#000033">3. Menu Pengetua</font><font size="4" align="align""center" color="#FFA500"><strong><img src="images/saps2011/blinkingNEW.gif" alt="" width="17" height="17" /></strong></font></strong></td>
                  </tr>
                <tr>
                  <td><font size="2"><strong><center>12/9/2011</center></strong></font></td>
                  <td><strong><font size="2" color="#000033">4. Download dokumen dan FAQ</font><font size="4" align="align""center" color="#FFA500"><strong><img src="images/saps2011/blinkingNEW.gif" alt="" width="17" height="17" /></strong></font></strong></td>
                  <tr>
                  <td><font size="2"><strong><center>22/8/2011</center></strong></font>
                  <td><strong><font size="2" color="#000033">5. Edit nama kelas untuk tukar dan update</font><font size="4" align="align""center" color="#FFA500"><strong><img src="images/saps2011/blinkingNEW.gif" alt="" width="17" height="17" /></strong></font></strong>
                  </td>
                  </tr>-->
              </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right" height="20">&nbsp;</td>
        <td>&nbsp;</td>
      <td height="20"></tr>
    </table></td>
  </tr>
  <tr>
    <td height="73"><table width="1207" height="71" border="0">
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
                    <font color="#FFFFFF" size="1">SILA GUNAKAN IE8, GOOGLE CHROME DAN MOZILLA 3 KEATAS SAHAJA. APLIKASI SAPS TIDAK DIUJI MENGGUNAKAN SAFARI DAN OPERA.
                    Best View Screen Resolution 1280 x 1024 </font>
                  </center>
                  </font></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="20">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
