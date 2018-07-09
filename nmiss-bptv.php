<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
$tahun=$_GET["tahun"];
$ting=$_GET["ting"];
?>
<td valign="top" class="rightColumn">
<p class="subHeader">NEAR MISS CEMERLANG (BPTV)</p>

<?php
if (isset($_POST['nmiss']))
{
	$tahun=$_POST['tahun'];
	$ting=$_POST['ting'];
	$jpep=$_POST['jpep'];
	
	switch ($ting)
		{
			/* case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
				$penilaian="tnilai_sr";
				//$bilgred= "(bilmp-(bilc+bild+bile+bilth))=1";
				$bilgred= "(TO_NUMBER(BILA)) > 3 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
				$tahap="darjah";
				$capai="(MINIMUM 4A)";
				break;
			case "P": case "T1": case "T2": case "T3":
				$penilaian="tnilai_smr";
				$bilgred= "(TO_NUMBER(BILA)) > 5 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
				$tahap="ting";
				$capai="(BERDASARKAN MP DIAMBIL)";
				break; */
			case "T4": case "T5":
				$penilaian="tnilai_sma";
				$bilgred=  "(TO_NUMBER(BILAP) + TO_NUMBER(BILA) + TO_NUMBER(BILAM)) > 5 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and TO_NUMBER(BILAP) + TO_NUMBER(BILA) + TO_NUMBER(BILAM)< TO_NUMBER(BILMP)";
				$tahap="ting";
				$capai="(BERDASARKAN MP DIAMBIL)";
				break;
		}

?>
<!-- <div align=right>
<a href="ctk_nmiss-jpn.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&jpep=<?php echo $jpep;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>" target=_blank ><img src=printi.jpg border=0></a></div><br><br> -->

<?php
	
	echo " <div align=\"center\"><p><strong>DATA NEAR MISS KECEMERLANGAN<br>$capai<br>".tahap($ting)."<br>".jpep($jpep)."<br>TAHUN $tahun</strong></p>\n";
	//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
	echo "   <table width=\"40%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
	echo "     <tr>\n";
	echo "       <td><div align=\"center\">BIL</div></td>\n";
	echo "       <td>NEGERI</td>\n";
	echo "       <td><div align=\"center\">BILANGAN</div></td>\n";
	echo "     </tr>\n";
//*****************************************************************

$qppd=oci_parse($conn_sispa,"SELECT * FROM tknegeri");
//echo "SELECT * FROM tkppd WHERE kodnegeri='$kodnegerijpn' ORDER BY kodppd";
//die ("$qppd");
oci_execute($qppd);
$bil=0; $jumbilnmiss=0;
while($rppd = oci_fetch_array($qppd))
{
	$bilppd = $bilppd + 1;
	$kodnegerijpn=$rppd["KODNEGERI"];

	//$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status='$status' AND kodjenissekolah IN ('202','203')");
	//oci_execute($qbm);
	$qbm2 = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodnegerijpn='$kodnegerijpn' and kodjenissekolah in ('203','303') ");
	//echo "SELECT * FROM tsekolah WHERE kodnegerijpn='$kodnegerijpn' and kodjenissekolah in ('202','203')<br>";
	oci_execute($qbm2);
	$jumbilnmiss=0;
	while($rppd2 = oci_fetch_array($qbm2)){
		$kodsekolah = $rppd2["KODSEK"];	
		$bilnmiss=count_row("SELECT * FROM $penilaian WHERE tahun='$tahun' AND jpep='$jpep' and kodsek='$kodsekolah' AND $tahap='$ting' AND $bilgred");
	//	echo "SELECT * FROM $penilaian WHERE tahun='$tahun' AND jpep='$jpep' and kodsek='$kodsekolah' AND $tahap='$ting' AND $bilgred <br>";
		$jumbilnmiss=$jumbilnmiss+$bilnmiss;	
	}
	$jumall+=$jumbilnmiss;
	$i = 0;
	//$rbm = oci_fetch_array($qbm2);
		$bil=$bil+1;
		echo "<tr><td><center>$bil</center></td>\n";
		echo "<td align=left><a href=nmiss-bptv-dae.php?data=".$rppd["NEGERI"]."/".$tahun."/".$ting."/".$jpep."/$status target='_blank'>".$rppd["NEGERI"]."</a></td>\n";
		//$bilcer = $rbm["BILC"]+0;
		//$qnmiss="SELECT * FROM $penilaian WHERE kodsek='$rbm[KODSEK]' AND tahun='$tahun' AND $tahap='$ting' AND jpep='$jpep' AND $bilgred";
		//$stmt = oci_parse($conn_sispa,$qnmiss);
		//oci_execute($stmt);
		//$bilnmiss=count_row("SELECT * FROM $penilaian WHERE kodsek='$rbm[KODSEK]' AND tahun='$tahun' AND $tahap='$ting' AND jpep='$jpep' AND $bilgred");
		echo "<td><center>$jumbilnmiss</center></td></tr>\n";
		//$jumbilnmiss=$jumbilnmiss+$bilnmiss;
	}
echo "<tr><td colspan=\"2\"><center>JUMLAH</center></td>\n";
echo "<td><center>$jumall</center></td></tr>\n";
echo "</table>\n";
echo "<br><br><br>";
}
else { ?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='nmiss-bptv.php?status='+val;
		}
		</script>
		
		<?php
		//echo "$kodsek";
		//echo "<br><br>";
		echo " <center></b>NEAR MISS CEMERLANG</b></center>";
		echo "<br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		
		//echo "  <tr bgcolor=\"#CCCCCC\">\n";
		 
		//if($status == "")
		//	$status = "SM";
		//$ting=$_GET['ting'];
		
		switch ($status)
		{
			case "SM" : $statussek = "SEKOLAH MENENGAH"; $tmp = "mpsmkc"; break;
			case "SR" : $statussek = "SEKOLAH RENDAH"; $tmp = "mpsr"; break;
			default : $statussek = "Pilih Jenis Sekolah"; break;
		}
		
		/*$SQLting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting");
		//echo "SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting";
		oci_execute($SQLting);
		$SQLmp = oci_parse($conn_sispa,"SELECT * FROM $tmp ORDER BY mp");
		//echo "SELECT DISTINCT * FROM $tmp ORDER BY mp";
		oci_execute($SQLmp);*/
		
     	//echo "<td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
		echo "<form method=post name='f1' action='nmiss-bptv.php'>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>JENIS SEKOLAH</td><td><select name=\"status\">";
		//echo "<option value=\"SR\"";if($status=='SR'){echo "SELECTED";} echo ">SEKOLAH RENDAH</option>";
		echo "<option value=\"SM\"";if($status=='SM'){echo "SELECTED";} echo ">SEKOLAH MENENGAH</option>";
		echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
		echo "<select name='ting'><option value=''>Ting/Darjah</option>";
		echo "<option value=\"T4\">T4</option>";
		echo "<option value=\"T5\">T5</option>";
						//break;
						
			/*case "SR" : 
						echo "<option value=\"D2\">D2</option>";
						echo "<option value=\"D3\">D3</option>";
						echo "<option value=\"D4\">D4</option>";
						echo "<option value=\"D5\">D5</option>";
						echo "<option value=\"D6\">D6</option>";
						break; */
		//}
		echo "</select>";
		echo "</td></tr>";
		
		$SQLpep = oci_parse($conn_sispa,"SELECT DISTINCT kod,jenis,rank FROM jpep ORDER BY rank");
		oci_execute($SQLpep);
		echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td><td>";
		echo "<select name='jpep'><option value=''>Pilih Peperiksaan</option>";
		while($rowpep = oci_fetch_array($SQLpep)) { 
			echo  "<option value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
		}
		echo "</select>";
		echo "</td></tr>";
		//echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr></table><br><br>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td>";
		$tahun_sekarang = date("Y");
		?>
        
			<select name="tahun" id="tahun">
			<option value="">-- Pilih Tahun --</option>
			<?php
			for($thn = 2011; $thn <= $tahun_sekarang; $thn++ ){
				if($tahun == $thn){
					echo "<option value='$thn' selected>$thn</option>";
				} else {
					echo "<option value='$thn'>$thn</option>";
				}
			}			
		echo "</td></tr></table><br><br>";
		 print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";
		echo "<center><input type='submit' name=\"nmiss\" value=\"Hantar\"></center>";

		echo "</form>";
} ?> 
</td>
<?php include 'kaki.php';?> 