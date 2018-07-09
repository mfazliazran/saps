<?php
set_time_limit(0);
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<script language="javascript" type="text/javascript" src="ajax/ajax_murid.js"></script>
<td valign="top" class="rightColumn">
<p class="subHeader">Senarai Semak Sekolah Rendah <font color="#FFFFFF">(Tarikh Kemaskini Program : 10/8/2011 11.20AM)</font></p>
<?php
//$nokp="".$_SESSION['nokp']."";
$kodsek="".$_SESSION['kodsek']."";
$tahun=date("Y");
$jenispep = $_POST["cboPep"];

if ($_SESSION['level']=="6")
	$querysub = "SELECT KODSEK,NAMASEK FROM tsekolah WHERE STATUS='SR' and KODNEGERIJPN='".$_SESSION["kodnegeri"]."' order by KODSEK ASC, NAMASEK ASC";//DONE INDEXING
elseif ($_SESSION['level']=="7")
	$querysub = "SELECT KODSEK,NAMASEK FROM tsekolah WHERE STATUS='SR' order by KODSEK ASC, NAMASEK ASC";//DONE INDEXING

//$qry = OCIParse($conn_sispa,$querysub);
//OCIExecute($qry);
$num = count_row($querysub);
if($num == 0){
	echo "<br><br><br><br><br><br><br><br>";
	echo "<center><h3>SENARAI SEKOLAH RENDAH</h3></center>";
	echo "<br><br>";
	echo "<table align=\"center\" width=\"500\" border =\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "  <td colspan=\"4\"><center><font color =\"#ff0000\"><h3><br>Tiada Sekolah.</h3></font></center></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	} else {
			
			echo "<br><br>";
			echo "<h3><center>SENARAI SEKOLAH RENDAH BAGI TAHUN $tahun</center></h3><br><br>";
			echo "<form method=post name='f1' action=''>";
			echo "<table width=\"200\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
			echo "  <tr>\n";
			echo "    <th bgcolor=\"#ff9900\" width=\"70%\"><center><b>Peperiksaan</b></center></th>\n";
			echo "    <th bgcolor=\"#ff9900\" width=\"30%\"><center><b>&nbsp;</b></center></th>\n";
			echo "  </tr>\n";
			echo "  <tr>\n";
			echo "<td><select name='cboPep' id='cboPep'><option value=''>-PILIH-</option>";
			$kelas_sql = OCIParse($conn_sispa,"SELECT jpep FROM kawal_pep WHERE tahun ='$tahun' AND JPEP!='PMRC' and JPEP!='SPMC' ORDER BY RANK");
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
			echo "  <td colspan=\"15\"><center>TAHAP</center></td>\n";
			echo "  </tr>\n";
			echo "<tr>\n";
			echo "  <td colspan=\"3\"><center>D2</center></td>\n";
			echo "  <td colspan=\"3\"><center>D3</center></td>\n";
			echo "  <td colspan=\"3\"><center>D4</center></td>\n";
			echo "  <td colspan=\"3\"><center>D5</center></td>\n";
			echo "  <td colspan=\"3\"><center>D6</center></td>\n";
			echo "</tr>\n";
			echo "  	<tr>\n";
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
				$namasekolah = OCIResult($qry,"NAMASEK");//$row['kelas'];
			   /*	
				$warna_t1_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t2_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t3_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t4_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t5_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				$warna_t6_sah = "<center><img src = images/red.gif width=20 height=20></center>";
				*/
				$warna_t1_sah = "<center>M</center>";
				$warna_t2_sah = "<center>M</center>";
				$warna_t3_sah = "<center>M</center>";
				$warna_t4_sah = "<center>M</center>";
				$warna_t5_sah = "<center>M</center>";
				$warna_t6_sah = "<center>M</center>";

				$bilrekod_d1=0;$bilrekod_d2=0;$bilrekod_d3=0;$bilrekod_d4=0;$bilrekod_d5=0;$bilrekod_d6=0;
				$bilmarkah_d1=0;$bilmarkah_d2=0;$bilmarkah_d3=0;$bilmarkah_d4=0;$bilmarkah_d5=0;$bilmarkah_d6=0;
				$bilmarkah_tov_d1=0;$bilmarkah_tov_d2=0;$bilmarkah_tov_d3=0;$bilmarkah_tov_d4=0;$bilmarkah_tov_d5=0;$bilmarkah_tov_d6=0;
				//$kodmp = OCIResult($qry,"KODMP");//$row['kodmp'];
				//$bil_am = OCIResult($qry,"BILAMMP");//$row['bilammp'];
				$sql="select count(nokp) as bilmurid from tmuridsr where (KodSekD1='$kodsekolah' and TahunD1='$tahun') or (KodSekD2='$kodsekolah' and TahunD2='$tahun') 
					  or (KodSekD3='$kodsekolah' and TahunD3='$tahun') or (KodSekD4='$kodsekolah' and TahunD4='$tahun')
					 or (KodSekD5='$kodsekolah' and TahunD5='$tahun') or (KodSekD6='$kodsekolah' and TahunD6='$tahun') and kodsek_semasa='$kodsekolah'";
				$res=oci_parse($conn_sispa,$sql);	 
				oci_execute($res);
				$data=oci_fetch_array($res);
				$cnt_murid=$data["BILMURID"];
				
				$sql5 = "select ting,kodmp,sum(bilammp) as BILREKOD from sub_guru where kodsek='$kodsekolah' AND tahun='$tahun' AND KODMP IN (SELECT KOD FROM SUB_SR) group by ting,kodmp";
				$res5=oci_parse($conn_sispa,$sql5);
				oci_execute($res5);
				if(oci_error($res5)){
					$qryerr = "select ting,kodmp,kelas,bilammp,nama,nokp from sub_guru where kodsek='$kodsekolah' AND tahun='$tahun' and (bilammp like '% %' or bilammp like '%O%') AND KODMP IN (SELECT KOD FROM SUB_SR)";
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
				$bilrekod_D2=0;$bilrekod_D3=0;$bilrekod_D4=0;$bilrekod_D5=0;$bilrekod_D6=0;
				$bilmarkah_D2=0;$bilmarkah_D3=0;$bilmarkah_D4=0;$bilmarkah_D5=0;$bilmarkah_D6=0;
				while($data5=oci_fetch_array($res5)){
					$darjah4=$data5["TING"];
				  	$bilrekod=$data5["BILREKOD"];
					$kodmp=$data5["KODMP"];
					$bilsubjek++;
					
					$sql2="select count(nokp) as bilmarkah from markah_pelajarsr where kodsek='$kodsekolah' and tahun='$tahun' and jpep='$jenispep'                       and darjah='$darjah4' and $kodmp is not null";// and nokp in (select nokp from tmuridsr where kodsek$darjah4='$kodsekolah' and darjah$darjah4='$darjah4' and tahun$darjah4='')";//done indexing
					$res=oci_parse($conn_sispa,$sql2);
					oci_execute($res);
					$data2=oci_fetch_array($res);
				    $bilmarkah=$data2["BILMARKAH"];

					if ($darjah4=="D2"){
					  $bilrekod_D2 +=$bilrekod;
					  $bilmarkah_D2 +=$bilmarkah;
					}
					else if ($darjah4=="D3"){
					  $bilrekod_D3 +=$bilrekod;
					  $bilmarkah_D3 +=$bilmarkah;
					}
					else if ($darjah4=="D4"){
					  $bilrekod_D4 +=$bilrekod;
					  $bilmarkah_D4 +=$bilmarkah;
					}
					else if ($darjah4=="D5"){
					  $bilrekod_D5 +=$bilrekod;
					  $bilmarkah_D5 +=$bilmarkah;
					}
					else if ($darjah4=="D6"){
					  $bilrekod_D6 +=$bilrekod;
					  $bilmarkah_D6 +=$bilmarkah;
					}
				}
				$warna_D2=semak_pengisian($bilmarkah_D2,$bilrekod_D2);
				$warna_D3=semak_pengisian($bilmarkah_D3,$bilrekod_D3);
				$warna_D4=semak_pengisian($bilmarkah_D4,$bilrekod_D4);
				$warna_D5=semak_pengisian($bilmarkah_D5,$bilrekod_D5);
				$warna_D6=semak_pengisian($bilmarkah_D6,$bilrekod_D6);
				
				//TOV
				$sql3="select darjah,count(nokp) as bilmarkah from headcountsr where kodsek='$kodsekolah' and tahun='$tahun' group by darjah";//DONE IDX
				$res=oci_parse($conn_sispa,$sql3);
				oci_execute($res);
				while($data3=oci_fetch_array($res)){
					$darjah2=$data3["DARJAH"];
				  	$bilmarkah2=$data3["BILMARKAH"];
					if($darjah2=="D1")
					  $bilmarkah_tov_d1=$bilmarkah2;
				  	else if($darjah2=="D2")
					  $bilmarkah_tov_d2=$bilmarkah2;
					else if($darjah2=="D3")
					  $bilmarkah_tov_d3=$bilmarkah2;
					else if($darjah2=="D4")
					  $bilmarkah_tov_d4=$bilmarkah2;
					else if($darjah2=="D5")
					  $bilmarkah_tov_d5=$bilmarkah2;
					else if($darjah2=="D6")
					  $bilmarkah_tov_d6=$bilmarkah2;

				}
				//$warna_tov_d1=semak_pengisian($bilmarkah_tov_d1,$bilrekod_d1*$bilsubjek);
				$warna_tov_d2=semak_pengisian($bilmarkah_tov_d2,$bilrekod_D2);//*$bilsubjek);
				$warna_tov_d3=semak_pengisian($bilmarkah_tov_d3,$bilrekod_D3);//*$bilsubjek);
				$warna_tov_d4=semak_pengisian($bilmarkah_tov_d4,$bilrekod_D4);//*$bilsubjek);
				$warna_tov_d5=semak_pengisian($bilmarkah_tov_d5,$bilrekod_D5);//*$bilsubjek);
				$warna_tov_d6=semak_pengisian($bilmarkah_tov_d6,$bilrekod_D6);//*$bilsubjek);
				
				//SAH
				$sql4 = "select ting from tsah where kodsek='$kodsekolah' AND tahun='$tahun' and jpep='$jenispep'";//DONE INDEXING
				$res = oci_parse($conn_sispa,$sql4);
				oci_execute($res);
				while($data4=oci_fetch_array($res)){
					$ting3 = $data4["TING"];

				  	if($ting3=="D1"){
					     //$warna_t1_sah = "<center><img src = images/green.gif width=20 height=20></center>";
						 $warna_t1_sah = "<center>H</center>";
				  	}
					if($ting3=="D2"){
						//$warna_t2_sah = "<center><img src = images/green.gif width=20 height=20></center>";
						$warna_t2_sah = "<center>H</center>";
					}
					if($ting3=="D3"){
						//$warna_t3_sah = "<center><img src = images/green.gif width=20 height=20></center>";
						$warna_t3_sah = "<center>H</center>";
					}
					if($ting3=="D4"){
						//$warna_t4_sah = "<center><img src = images/green.gif width=20 height=20></center>";
						$warna_t4_sah = "<center>H</center>";
					}
					if($ting3=="D5"){
						//$warna_t5_sah = "<center><img src = images/green.gif width=20 height=20></center>";
						$warna_t5_sah = "<center>H</center>";
					}
					if($ting3=="D6"){
						//$warna_t6_sah = "<center><img src = images/green.gif width=20 height=20></center>";
						$warna_t6_sah = "<center>H</center>";
					}
				}
				
				if($jenispep=='UPSRC'){
					$warna_D2 = "-";
					$warna_tov_d2 = "-";
					$warna_t2_sah = "-";
					
					$warna_D3 = "-";
					$warna_tov_d3 = "-";
					$warna_t3_sah = "-";
					
					$warna_D4 = "-";
					$warna_tov_d4 = "-";
					$warna_t4_sah = "-";
					
					$warna_D5 = "-";
					$warna_tov_d5 = "-";
					$warna_t5_sah = "-";					
				}
				
  				$bil++;
				echo "<tr>\n";
				echo "<td>$bil</td>";
				echo "<td>$kodsekolah</td>";
				echo "<td>$namasekolah</td>";
				echo "<td>$cnt_murid</td>";
				//echo "<td>$warna_D1</td>";
				//echo "<td>$warna_tov_d1</td>";
				//echo "<td>$warna_t1_sah</td>";
				echo "<td>$warna_D2</td>";
				echo "<td>$warna_tov_d2</td>";
				echo "<td>$warna_t2_sah</td>";
				echo "<td>$warna_D3</td>";
				echo "<td>$warna_tov_d3</td>";
				echo "<td>$warna_t3_sah</td>";
				echo "<td>$warna_D4</td>";
				echo "<td>$warna_tov_d4</td>";
				echo "<td>$warna_t4_sah</td>";
				echo "<td>$warna_D5</td>";
				echo "<td>$warna_tov_d5</td>";
				echo "<td>$warna_t5_sah</td>";
				echo "<td>$warna_D6</td>";
				echo "<td>$warna_tov_d6</td>";
				echo "<td>$warna_t6_sah</td>";				
				echo "</tr>\n";
			}// tamat while
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
			echo "<td>H</td>";
			echo "<td>Markah & TOV secara keseluruhannya telah diisi kesemuanya.<br>Markah & TOV telah disahkan.</td>";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>K</td>";
			echo "<td>Markah & TOV masih belum siap diisi.</td>";
			echo "</tr>";	
			echo "<tr>\n";
			echo "<td>M</td>";
			echo "<td>Markah & TOV masih belum diisi dan belum disahkan.</td>";
			echo "</tr>";			
			echo " </table>\n";
}	//jpep

//echo "</div>"; 
//echo "</td>"; 
//echo"</table>";
//echo "</th>\n";
}// tamat if($num == 0)

function semak_pengisian($bilmarkah,$bilambil)
{
				if ($bilmarkah>=$bilambil and $bilambil>0)
					//$warna = "<center><img src = images/green.gif width=20 height=20></center>";
					$warna = "<center>H</center>";
				else if ($bilmarkah>0)
					//$warna = "<center><img src = images/yellow.gif width=20 height=20></center>";
					$warna = "<center>K</center>";
				else
					//$warna = "<center><img src = images/red.gif width=20 height=20></center>";
					$warna = "<center>M</center>";
	return($warna);
}
?>
</td>
<?php include 'kaki.php';?> 