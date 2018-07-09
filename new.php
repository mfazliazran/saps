<?php
	include_once("config.php");
	$id=ereg_replace("'"," ",$_GET["ids"]);//str_replace("'","",$_GET["ids"]);
	//$id=trim($id);
	//$id=addslashes($_GET["ids"]);
	
	 //echo $id;
	$sql2 = "select perkara,tarikh,umum from penambahbaikan where status='1'";
	$res2 = OCIParse($conn_sispa,"select perkara,tarikh,umum from penambahbaikan where id= :id and status= :statusx");
	$status = 1;
	oci_bind_by_name($res2, ':id', $id);
	oci_bind_by_name($res2, ':statusx', $status);
	//$res2 = OCIParse ($conn_sispa, "select perkara,tarikh,umum from penambahbaikan where trim(id):=id");
	//ocibindbyname($res2, ":id", $id);
//	echo "select perkara,tarikh,umum from penambahbaikan where id='$id' and status='1'";
	OCIExecute($res2);
	$bil = count_row($sql2);
	$cnt=0;
	if($bil>0){
		while($data=oci_fetch_array($res2)){
			$perkara=$data["PERKARA"];	
			$tarikh=$data["TARIKH"];
			$umum=ereg_replace("\r","<br>",$data["UMUM"]);
			$cnt++;
		}
	}
	 echo "<title>SAPS</title>\n";
?>
<table width="650" align="center" height="444" border="1">
  <tr>
    <td height="100"><img src="images/saps2011/banner.jpg" align="align""center" alt="" width="1210" height="96" /></td>
  </tr>
  <tr>
    <td height="299"><table width="1194" border="0">
        <tr>
          <td width="200" height="292"><table width="200" border="0">
            <tr>
              <td><img src="images/saps2012/menu.gif" alt="" width="196" height="35" /></td>
            </tr>
            <tr>
              <td><a href="muatturun.php" target="_blank"><img src="images/saps2012/muatturun.jpg" alt="" width="197" height="70" align="center"/></td>
            </tr>
            <tr>
              <td><a href="http://www.speedtest.net/" target="_blank"><img src="images/saps2012/ujikelajuan.jpg" alt="" width="197" height="70" align="center"/></td>
            </tr>
            <tr>
              <td><a href="faqsaps.php" target="_blank"><img src="images/saps2012/faq.jpg" alt="" width="197" height="70" align="center"/></td>
            </tr>
            <tr>
              <td><a href="saps_ibubapa/index.php" target="_blank"><img src="images/saps2012/ibubapa.jpg" alt="" width="197" height="70" align="left"/></td>
            </tr>
          </table></td>
          <td width="978" valign="top"><table width="969" bgcolor="#FFCC66" height="54" bordercolor="#FF6600" border="1">
            <tr>
              <td width="70" height="23"><font color="#0000CC"><strong><center>Tarikh</center></strong></font></td>
              <td width="296"><font color="#0000CC"><strong><right>Perkara</right></strong></font></td>
              <td width="581"><font color="#0000CC"><strong><center>Keterangan</center></strong></font></td>
            </tr>
            <tr>
              <td height="23"><center><strong><?php echo $tarikh;?>&nbsp;</strong></center></td>
              <td><?php echo $perkara;?>&nbsp;</td>
              <td><?php echo $umum;?>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height="23"><table width="1201" height="31" align="center" bgcolor="#0066FF" border="0">
      <tr>
        <td><font size="1" color="#FFFFFF">
          <center>
            SILA GUNAKAN IE8, GOOGLE CHROME DAN MOZILLA 3 KEATAS SAHAJA. APLIKASI SAPS TIDAK DIUJI MENGGUNAKAN SAFARI DAN OPERA.
          </center>
        </font></td>
      </tr>
    </table></td>
  </tr>
</table>
