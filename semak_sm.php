<?php
set_time_limit(0);
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<script language="javascript" type="text/javascript" src="ajax/ajax_murid.js"></script>
<td valign="top" class="rightColumn">
<p class="subHeader">Senarai Semak Sekolah Menengah <font color="#FFFFFF">(Tarikh Kemaskini Program : 16/8/2011 1.54PM)</font></p>
<?php
//$nokp="".$_SESSION['nokp']."";
$kodsek="".$_SESSION['kodsek']."";
$tahun=date("Y");
$jenispep = $_POST["cboPep"];

$querysub = "SELECT KODSEK,NAMASEK FROM tsekolah WHERE kodppd='$kodsek' AND STATUS='SM' order by KODSEK ASC, NAMASEK ASC";//DONE INDEXING
//$qry = OCIParse($conn_sispa,$querysub);
//OCIExecute($qry);
$num = count_row($querysub);
if($num == 0){
	echo "<br><br><br><br><br><br><br><br>";
	echo "<center><h3>SENARAI SEKOLAH MENENGAH</h3></center>";
	echo "<br><br>";
	echo "<table align=\"center\" width=\"500\" border =\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "  <td colspan=\"4\"><center><font color =\"#ff0000\"><h3><br>Sila login sebagai PPD.</h3></font></center></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	} else {
			
			echo "<br><br>";
			echo "<h3><center>SENARAI SEKOLAH MENENGAH BAGI TAHUN $tahun</center></h3><br><br>";
			echo "<form method=post name='f1' action=''>";
			echo "<table width=\"200\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
			echo "  <tr>\n";
			echo "    <th bgcolor=\"#ff9900\" width=\"70%\"><center><b>Peperiksaan</b></center></th>\n";
			echo "    <th bgcolor=\"#ff9900\" width=\"30%\"><center><b>&nbsp;</b></center></th>\n";
			echo "  </tr>\n";
			echo "  <tr>\n";
			echo "<td><select name='cboPep' id='cboPep'><option value=''>-PILIH-</option>";
			$kelas_sql = OCIParse($conn_sispa,"SELECT jpep FROM kawal_pep WHERE tahun ='$tahun' AND JPEP!='UPSRC' ORDER BY RANK");
			OCIExecute($kelas_sql);
			while(OCIFetch($kelas_sql)) { 
			if(OCIResult($kelas_sql,"JPEP")==$jenispep){
			   echo "<option selected value='".OCIResult($kelas_sql,"JPEP")."'>".jpep(OCIResult($kelas_sql,"JPEP"))."</option>"."<BR>";
			}
			else{
			  echo  "<option value='".OCIResult($kelas_sql,"JPEP")."'>".jpep(OCIResult($kelas_sql,"JPEP"))."</option>";
			  }
			}
			echo "</select></td>";
			echo "<td><center><input type=\"submit\" name=\"Submit\" value=\"Hantar\"></center></td>";
			echo "</tr>";
			echo " </table>\n";
			echo "</form>\n";
		if($jenispep<>""){
			echo "<br><br>";
			echo "<table width=\"600\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
			echo "  <tr>\n";
			echo "    <td rowspan=\"3\" scope=\"col\">Bil.</td>\n";
			echo "    <td rowspan=\"3\" scope=\"col\">Kod Sekolah</td>\n";
			echo "    <td rowspan=\"3\" scope=\"col\">Nama Sekolah</td>\n";
			echo "    <td rowspan=\"3\" scope=\"col\">Jumlah Pelajar</td>\n";
			echo "  <td colspan=\"18\"><center>Kelas</center></td>\n";
			echo "  </tr>\n";
			echo "<tr>\n";
			echo "  <td colspan=\"3\"><center>P</center></td>\n";
			echo "  <td colspan=\"3\"><center>T1</center></td>\n";
			echo "  <td colspan=\"3\"><center>T2</center></td>\n";
			echo "  <td colspan=\"3\"><center>T3</center></td>\n";
			echo "  <td colspan=\"3\"><center>T4</center></td>\n";
			echo "  <td colspan=\"3\"><center>T5</center></td>\n";			
			echo "</tr>\n";
			echo "  <tr>\n";
			echo "	<td>Markah&nbsp;</td>";
			echo "	<td>TOV&nbsp;</td>";
			echo "	<td>SAH&nbsp;</td>";
			echo "	<td>Markah&nbsp;</td>";
			echo "	<td>TOV&nbsp;</td>";
			echo "	<td>SAH&nbsp;</td>";
			echo "	<td>Markah&nbsp;</td>";
			echo "	<td>TOV&nbsp;</td>";
			echo "	<td>SAH&nbsp;</td>";
			echo "	<td>Markah&nbsp;</td>";
			echo "	<td>TOV&nbsp;</td>";
			echo "	<td>SAH&nbsp;</td>";
			echo "	<td>Markah&nbsp;</td>";
			echo "	<td>TOV&nbsp;</td>";
			echo "	<td>SAH&nbsp;</td>";
			echo "	<td>Markah&nbsp;</td>";
			echo "	<td>TOV&nbsp;</td>";
			echo "	<td>SAH&nbsp;</td>";
			echo "	</tr>\n";
			$qry = OCIParse($conn_sispa,$querysub);
			OCIExecute($qry);
			$bil=0;
			while(OCIFetch($qry)) {
				$kodsekolah = OCIResult($qry,"KODSEK");
				$namasekolah = OCIResult($qry,"NAMASEK");
			   	$warna_t1_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t2_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t3_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t4_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t5_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t6_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_d1 = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_d2 = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_d3 = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_d4 = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_d5 = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_d6 = "<center><img src = images/red.gif width=20 height=20></center>";
				$bilrekod_d1=0;$bilrekod_d2=0;$bilrekod_d3=0;$bilrekod_d4=0;$bilrekod_d5=0;$bilrekod_d6=0;
				$bilmarkah_d1=0;$bilmarkah_d2=0;$bilmarkah_d3=0;$bilmarkah_d4=0;$bilmarkah_d5=0;$bilmarkah_d6=0;
				$bilmarkah_tov_d1=0;$bilmarkah_tov_d2=0;$bilmarkah_tov_d3=0;$bilmarkah_tov_d4=0;$bilmarkah_tov_d5=0;$bilmarkah_tov_d6=0;
				$wujudp=$wujudt2=$wujudt3=$wujudt4=$wujudt5=0;

				$sql="select count(nokp) as bilmurid from tmurid where (KodSekP='$kodsekolah' and TahunP='$tahun') or (KodSekT1='$kodsekolah' and TahunT1='$tahun') or (KodSekT2='$kodsekolah' and TahunT2='$tahun') or (KodSekT3='$kodsekolah' and TahunT3='$tahun') or (KodSekT4='$kodsekolah' and TahunT4='$tahun') or (KodSekT5='$kodsekolah' and TahunT5='$tahun') or (KodSekT6='$kodsekolah' and TahunT6='$tahun')";
				//$sql="select count(nokp) as bilmurid from tmurid where (KodSekP='$kodsekolah' and TahunP='$tahun') or (KodSekT2='$kodsekolah' and TahunT2='$tahun') or (KodSekT3='$kodsekolah' and TahunT3='$tahun') or (KodSekT4='$kodsekolah' and TahunT4='$tahun') or (KodSekT5='$kodsekolah' and TahunT5='$tahun') or (KodSekT6='$kodsekolah' and TahunT6='$tahun')";
				$res=oci_parse($conn_sispa,$sql);	 
				oci_execute($res);
				$data=oci_fetch_array($res);
				$cnt_murid=$data["BILMURID"];
				
				$sql5 = "select ting,kodmp,sum(bilammp) as BILREKOD from sub_guru where tahun='$tahun' AND kodsek='$kodsekolah' AND KODMP IN (SELECT KOD FROM SUB_MR) and ting in ('P','T1','T2','T3') group by ting,kodmp";//DONE INDEXING
				//echo $sql5;
				$res5=oci_parse($conn_sispa,$sql5);
				oci_execute($res5);
				if(oci_error($res5)){
					$qryerr = "select ting,kodmp,kelas,bilammp,nama,nokp from sub_guru where tahun='$tahun' AND kodsek='$kodsekolah' and (bilammp like '% %' or bilammp like '%O%') AND KODMP IN (SELECT KOD FROM SUB_MR)";
					$reserr = oci_parse($conn_sispa,$qryerr);
					oci_execute($reserr);
					$dataerr = oci_fetch_array($reserr);
					$tingerr = $dataerr["TING"];
					$kelaserr = $dataerr["KELAS"];
					$kodmperr = $dataerr["KODMP"];
					$namaerr = $dataerr["NAMA"];
					$nokperr = $dataerr["NOKP"];
					echo "<font color='#FF0000'><strong>Sekolah $kodsekolah mempunyai masalah data di mana data jumlah ambil mata pelajaran yang dimasukkan mempunyai nilai bukan intiger atau ADA SPACE.<br>Nama Guru : $namaerr [$nokperr]<br>Tingkatan : $tingerr Kelas : $kelaserr Mata pelajaran : $kodmperr<br>SILA HUBUNGI SEKOLAH YANG BERKENAAN.</strong></font>";
				}
				$bilsubjek=0;
				$bilrekod_p=0;$bilrekod_t1=0;$bilrekod_t2=0;$bilrekod_t3=0;//$bilrekod_t4=0;$bilrekod_t5=0;
				$bilmarkah_p=0;$bilmarkah_t1=0;$bilmarkah_t2=0;$bilmarkah_t3=0;//$bilmarkah_t4=0;$bilmarkah_t5=0;
				while($data5=oci_fetch_array($res5)){
					$darjah4=trim($data5["TING"]);
					//if($darjah4=="P")
						//echo "$kodsekolah $namasekolah <br>";
				  	$bilrekod=$data5["BILREKOD"];
					$kodmp=$data5["KODMP"];
					$bilsubjek++;
					
					$sql2="select count(nokp) as bilmarkah from markah_pelajar where kodsek='$kodsekolah' and tahun='$tahun' and jpep='$jenispep' and ting='$darjah4' and $kodmp is not null";//done indexing
			
					$res=oci_parse($conn_sispa,$sql2);
					oci_execute($res);
					$data2=oci_fetch_array($res);
				    $bilmarkah=$data2["BILMARKAH"];

					if ($darjah4=="P"){
					  $bilrekod_p +=$bilrekod;
					  $bilmarkah_p +=$bilmarkah;
					  $wujudp=1;
					}
					else if ($darjah4=="T1"){
					  $bilrekod_t1 +=$bilrekod;
					  $bilmarkah_t1 +=$bilmarkah;
					}
					else if ($darjah4=="T2"){
					  $bilrekod_t2 +=$bilrekod;
					  $bilmarkah_t2 +=$bilmarkah;
					  $wujudt2=1;
					}
					else if ($darjah4=="T3"){
					  $bilrekod_t3 +=$bilrekod;
					  $bilmarkah_t3 +=$bilmarkah;
					  $wujudt3=1;
					}
					/*else if ($darjah4=="T4"){
					  $bilrekod_t4 +=$bilrekod;
					  $bilmarkah_t4 +=$bilmarkah;
					}	
					else if ($darjah4=="T5"){
					  $bilrekod_t5 +=$bilrekod;
					  $bilmarkah_t5 +=$bilmarkah;
					}	*/
				}//while data5
				
				//$sql7 = "select ting,kodmp,sum(bilammp) as BILREKOD from sub_guru where tahun='$tahun' AND kodsek='$kodsekolah' AND kodmp NOT IN (select KOD from SUB_MA_XAMBIL) group by ting,kodmp";//DONE INDEXING
				$sql7 = "select ting,kodmp,sum(bilammp) as BILREKOD from sub_guru where tahun='$tahun' AND kodsek='$kodsekolah' AND kodmp IN (select kod from sub_ma) and ting in ('T4','T5') AND kelas in (select kelas from tkelassek where kodsek='$kodsekolah' and tahun='$tahun' and ting in ('T4','T5')) group by ting,kodmp";//DONE INDEXING edit 15102016 naeim (tambah kelas)
				//echo $sql5;
				$res7=oci_parse($conn_sispa,$sql7);
				oci_execute($res7);
				if(oci_error($res7)){
					$qryerr7 = "select ting,kodmp,kelas,bilammp,nama,nokp from sub_guru where tahun='$tahun' AND kodsek='$kodsekolah' and (bilammp like '% %' or bilammp like '%O%') AND kodmp NOT in (select KOD from SUB_MA_XAMBIL)";
					$reserr7 = oci_parse($conn_sispa,$qryerr7);
					oci_execute($reserr7);
					$dataerr = oci_fetch_array($reserr7);
					$tingerr = $dataerr["TING"];
					$kelaserr = $dataerr["KELAS"];
					$kodmperr = $dataerr["KODMP"];
					$namaerr = $dataerr["NAMA"];
					$nokperr = $dataerr["NOKP"];
					echo "<font color='#FF0000'><strong>Sekolah $kodsekolah mempunyai masalah data di mana data jumlah ambil mata pelajaran yang dimasukkan mempunyai nilai bukan intiger atau ADA SPACE.<br>Nama Guru : $namaerr [$nokperr]<br>Tingkatan : $tingerr Kelas : $kelaserr Mata pelajaran : $kodmperr<br>SILA HUBUNGI SEKOLAH YANG BERKENAAN.</strong></font>";
				}
				$bilrekod_t4=0;$bilrekod_t5=0;
				$bilmarkah_t4=0;$bilmarkah_t5=0;
				while($data7=oci_fetch_array($res7)){
					$darjah4=trim($data7["TING"]);
					//if($darjah4=="P")
						//echo "$kodsekolah $namasekolah <br>";
				  	$bilrekod=$data7["BILREKOD"];
					$kodmp=$data7["KODMP"];
					$bilsubjek++;
					
					$sql2="select count(nokp) as bilmarkah from markah_pelajar where kodsek='$kodsekolah' and tahun='$tahun' and jpep='$jenispep' and ting='$darjah4' and $kodmp is not null";// and nokp in (select nokp from tmurid where kodsek$darjah4='$kodsekolah' and tahun$darjah4='$tahun')
					//if($kodsekolah == 'BEA8643' and $darjah4=='T5'){
						//echo $sql2."<br>"; 
					//}
					//done indexing
			
					$res=oci_parse($conn_sispa,$sql2);
					oci_execute($res);
					$data2=oci_fetch_array($res);
				    $bilmarkah=$data2["BILMARKAH"];
					//if($kodsekolah == 'BEA8643' and $darjah4=='T5'){
						//echo ("$bilmarkah,$bilrekod")."<br>"; 
					//}

					if ($darjah4=="T4"){
					  $bilrekod_t4 +=$bilrekod;
					  $bilmarkah_t4 +=$bilmarkah;
					  $wujudt4=1;
					}	
					else if ($darjah4=="T5"){
					  $bilrekod_t5 +=$bilrekod;
					  $bilmarkah_t5 +=$bilmarkah;
					  $wujudt5=1;
					}	
				}//while data7
				//if($kodsekolah == 'BEA8643' and $darjah4=='T5'){
						//echo ("$bilmarkah_t5,$bilrekod_t5")."<br>"; 
				//}
			$warna_p=semak_pengisian($bilmarkah_p,$bilrekod_p);
			$warna_t1=semak_pengisian($bilmarkah_t1,$bilrekod_t1);
			$warna_t2=semak_pengisian($bilmarkah_t2,$bilrekod_t2);
			$warna_t3=semak_pengisian($bilmarkah_t3,$bilrekod_t3);
			$warna_t4=semak_pengisian($bilmarkah_t4,$bilrekod_t4);
			$warna_t5=semak_pengisian($bilmarkah_t5,$bilrekod_t5);
				
			//TOV
			$sql3="select TING,count(nokp) as bilmarkah from headcount where kodsek='$kodsekolah' and tahun='$tahun' group by TING";
			$res=oci_parse($conn_sispa,$sql3);
			oci_execute($res);
			while($data3=oci_fetch_array($res)){
				$darjah2=trim($data3["TING"]);
			  	$bilmarkah2=$data3["BILMARKAH"];
				if($darjah2=="P")
					$bilmarkah_tov_d1=$bilmarkah2;
			  	else if($darjah2=="T1")
				  	$bilmarkah_tov_d2=$bilmarkah2;
				else if($darjah2=="T2")
				  	$bilmarkah_tov_d3=$bilmarkah2;
				else if($darjah2=="T3")
				  	$bilmarkah_tov_d4=$bilmarkah2;
				else if($darjah2=="T4")
				  	$bilmarkah_tov_d5=$bilmarkah2;
				else if($darjah2=="T5")
				  	$bilmarkah_tov_d6=$bilmarkah2;
			}//while data3
			$warna_tov_d1=semak_pengisian($bilmarkah_tov_d1,$bilrekod_p);//*$bilsubjek);
			$warna_tov_d2=semak_pengisian($bilmarkah_tov_d2,$bilrekod_t1);//*$bilsubjek);
			$warna_tov_d3=semak_pengisian($bilmarkah_tov_d3,$bilrekod_t2);//*$bilsubjek);
			$warna_tov_d4=semak_pengisian($bilmarkah_tov_d4,$bilrekod_t3);//*$bilsubjek);
			$warna_tov_d5=semak_pengisian($bilmarkah_tov_d5,$bilrekod_t4);//*$bilsubjek);
			$warna_tov_d6=semak_pengisian($bilmarkah_tov_d6,$bilrekod_t5);//*$bilsubjek);
			
			//SAH
			$sql4 = "select ting from tsah where kodsek='$kodsekolah' AND tahun='$tahun' and jpep='$jenispep'";//DONE INDEXING
			$res = oci_parse($conn_sispa,$sql4);
			oci_execute($res);
			while($data4=oci_fetch_array($res)){
				$ting3 = trim($data4["TING"]);
				if($ting3=="P"){
					$warna_t1_sah = "<center><img src = images/green.gif width=20 height=20></center>";
				}
				if($ting3=="T1"){
					$warna_t2_sah = "<center><img src = images/green.gif width=20 height=20></center>";
				}
				if($ting3=="T2"){
					$warna_t3_sah = "<center><img src = images/green.gif width=20 height=20></center>";
				}
				if($ting3=="T3"){
					$warna_t4_sah = "<center><img src = images/green.gif width=20 height=20></center>";
				}
				if($ting3=="T4"){
					$warna_t5_sah = "<center><img src = images/green.gif width=20 height=20></center>";
				}
				if($ting3=="T5"){
					$warna_t6_sah = "<center><img src = images/green.gif width=20 height=20></center>";
				}
			}//while data4
											 
  			$bil++;
			/*$sql6 = "select DISTINCT ting,kodmp,sum(bilammp) as BILREKOD from sub_guru where tahun='$tahun' AND kodsek='$kodsekolah' AND KODMP IN (SELECT KOD FROM SUB_MR) group by ting,kodmp";//DONE INDEXING
			$res6=oci_parse ($conn_sispa,$sql6);
			oci_execute($res6);
			$wujud=0;
			while($data6 = oci_fetch_array($res6)){
				$tingkatan = trim($data6["TING"]);
				if($tingkatan=="P")
					$wujud=1;
			}*/
			if($jenispep=='PMRC'){
				$warna_p = "-";
				$warna_tov_d1 = "-";
				$warna_t1_sah = "-";
				
				$warna_t1 = "-";
				$warna_tov_d2 = "-";
				$warna_t2_sah = "-";
				
				$warna_t2 = "-";
				$warna_tov_d3 = "-";
				$warna_t3_sah = "-";
								
				$warna_t4 = "-";
				$warna_tov_d5 = "-";
				$warna_t5_sah = "-";
				
				$warna_t5 = "-";
				$warna_tov_d6 = "-";
				$warna_t6_sah = "-";
			}
			if($jenispep=='SPMC'){
				$warna_p = "-";
				$warna_tov_d1 = "-";
				$warna_t1_sah = "-";
				
				$warna_t1 = "-";
				$warna_tov_d2 = "-";
				$warna_t2_sah = "-";
				
				$warna_t2 = "-";
				$warna_tov_d3 = "-";
				$warna_t3_sah = "-";
				
				$warna_t3 = "-";
				$warna_tov_d4 = "-";
				$warna_t4_sah = "-";
				
				$warna_t4 = "-";
				$warna_tov_d5 = "-";
				$warna_t5_sah = "-";
			}
			echo "<tr>\n";
			echo "<td>$bil</td>";
			echo "<td>$kodsekolah</td>";
			echo "<td>$namasekolah</td>";
			echo "<td>$cnt_murid</td>";
			if($wujudp==1){
			echo "<td>$warna_p</td>";
			echo "<td>$warna_tov_d1</td>";
			echo "<td>$warna_t1_sah</td>";
			}else{
			echo "<td align='center'>-&nbsp;</td>";
			echo "<td align='center'>-&nbsp;</td>";
			echo "<td align='center'>-&nbsp;</td>";
			}
			echo "<td>$warna_t1</td>";
			echo "<td>$warna_tov_d2</td>";
			echo "<td>$warna_t2_sah</td>";
			if($wujudt2==1){
			echo "<td>$warna_t2</td>";
			echo "<td>$warna_tov_d3</td>";
			echo "<td>$warna_t3_sah</td>";
			}else{
			echo "<td align='center'>-&nbsp;</td>";
			echo "<td align='center'>-&nbsp;</td>";
			echo "<td align='center'>-&nbsp;</td>";
			}
			if ($jenispep=="PAT"){
				echo "<td align='center'>-&nbsp;</td>";
				echo "<td align='center'>-&nbsp;</td>";
				echo "<td align='center'>-&nbsp;</td>";			
			}else {
				if($wujudt3==1){
				echo "<td>$warna_t3</td>";
				echo "<td>$warna_tov_d4</td>";
				echo "<td>$warna_t4_sah</td>";
				}else{
				echo "<td align='center'>-&nbsp;</td>";
				echo "<td align='center'>-&nbsp;</td>";
				echo "<td align='center'>-&nbsp;</td>";
				}
			}
			if($wujudt4==1){
			echo "<td>$warna_t4</td>";
			echo "<td>$warna_tov_d5</td>";
			echo "<td>$warna_t5_sah</td>";
			}else{
			echo "<td align='center'>-&nbsp;</td>";
			echo "<td align='center'>-&nbsp;</td>";
			echo "<td align='center'>-&nbsp;</td>";
			}
			if ($jenispep=="PAT"){
				echo "<td align='center'>-&nbsp;</td>";
				echo "<td align='center'>-&nbsp;</td>";
				echo "<td align='center'>-&nbsp;</td>";			
			}else {
				if($wujudt5==1){
				echo "<td>$warna_t5</td>";
				echo "<td>$warna_tov_d6</td>";
				echo "<td>$warna_t6_sah</td>";				
				}else{
				echo "<td align='center'>-&nbsp;</td>";
				echo "<td align='center'>-&nbsp;</td>";
				echo "<td align='center'>-&nbsp;</td>";
				}
			}
echo "</tr>\n";
		}// tamat while qry
echo "</table>\n";	
$green = "<center><img src = images/green.gif width=20 height=20></center>";
$yellow = "<center><img src = images/yellow.gif width=20 height=20></center>";
$red= "<center><img src = images/red.gif width=20 height=20></center>";
echo "<br><br><br>";
echo "<table width=\"500\" align=\"left\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
			echo "  <tr>\n";
			echo "    <th bgcolor=\"#ff9900\" width=\"10%\"><center><b>Gambar</b></center></th>\n";
			echo "    <th bgcolor=\"#ff9900\" width=\"90%\"><center><b>Keterangan</b></center></th>\n";
			echo "  </tr>\n";
			echo "<tr>\n";
			echo "<td>$green</td>";
			echo "<td>Markah & TOV secara keseluruhannya telah diisi kesemuanya.<br>Markah & TOV telah disahkan.</td>";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>$yellow</td>";
			echo "<td>Markah & TOV masih belum siap diisi.</td>";
			echo "</tr>";	
			echo "<tr>\n";
			echo "<td>$red</td>";
			echo "<td>Markah & TOV masih belum diisi dan belum disahkan.</td>";
			echo "</tr>";			
			echo " </table>\n";
	}	//jpep
}// tamat if($num == 0)

function semak_pengisian($bilmarkah,$bilambil)
{
	if ($bilmarkah>=$bilambil and $bilambil>0)
		$warna = "<center><img src = images/green.gif width=20 height=20></center>";
	else if ($bilmarkah>0)
		$warna = "<center><img src = images/yellow.gif width=20 height=20></center>";
	else
		$warna = "<center><img src = images/red.gif width=20 height=20></center>";
	return($warna);
}
?>
</td>
<?php include 'kaki.php';?> 