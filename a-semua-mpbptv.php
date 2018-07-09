<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';


?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Peperiksaan</p>

<?php
if (isset($_POST['semuaa']))
{
	$tahun=$_POST['tahun'];
	$ting=$_POST['ting'];
	$jpep=$_POST['jpep'];
	$status=$_POST['status'];
	$kodnegeri=$_POST['kodnegeri'];
 

    $sqlnegeri = oci_parse($conn_sispa,"select negeri from tknegeri where kodnegeri='$kodnegeri' ");
	oci_execute($sqlnegeri);
    $rownegeri = oci_fetch_array($sqlnegeri);
    $namanegeri = $rownegeri["NEGERI"];

?>
	
<!-- <div align=right><a href="ctk_a-semua-mpjpn.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&jpep=<?php echo $jpep;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>" target=_blank ><img src=printi.jpg border=0></a></div><br> -->

<?php
	
	switch ($ting)
		{
		/*	case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
				$penilaian="penilaian_muridsr";
				$level = "SR";
				$bilgred="bila";
				$tahap="darjah";
				$penilaian_hc="penilaian_hcsr";
				$bilgredetr="biletra";
				break;
			case "P": case "T1": case "T2": case "T3":
				$penilaian="penilaian_muridsmr";
				$level = "MR";
				$bilgred="bila";
				$tahap="ting";
				$penilaian_hc="penilaian_hcsmr";
				$bilgredetr="biletra";
				break; */
			case "T4": case "T5":
				$penilaian="penilaian_muridsma";
				$level = "MA";
				$bilgred="bil1a+bil2a";
				$tahap="ting";
				$penilaian_hc="penilaian_hcsma";
				$bilgredetr="biletr1a";
				break;
		}
	if ($jpep=="getr"){

		echo " <div align=\"center\"><p><strong>BILANGAN MURID CEMERLANG MENGIKUT SEKOLAH <br>".tahap($ting)."<br>".jpep($jpep)."<br>NEGERI $namanegeri ($kodnegeri) TAHUN $tahun</strong></p>\n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
		echo "   <table width=\"80%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
		echo "     <tr>\n";
		echo "       <td><div align=\"center\">BIL</div></td>\n";
		echo "       <td>NAMA SEKOLAH </td>\n";
		echo "       <td><div align=\"center\">BILANGAN</div></td>\n";
		echo "     </tr>\n";
		$gting = strtolower($ting);
		$qtov = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tahunpep='$tahun' AND tingpep='$ting' AND capai='TOV'");
		oci_execute($qtov);
		$rowtov = oci_fetch_array($qtov);
		$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status='$status' and kodnegeri='$kodnegeri' and kodjenissekolah IN ('203','303') ");
		//echo haha;
		oci_execute($qbm);
		while($rbm = oci_fetch_array($qbm))
		{
			$bil=$bil+1;
			echo "     <tr>\n";
			echo "       <td><center>$bil</center></td>\n";
			//echo "       <td><a href=senarai-cemerlang-dae.php?data=".$rbm["KODSEK"]."/".$tahun."/".$ting."/".$jpep.">$rbm[NAMASEK]</a></td>\n";
			
				$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status='$statuss' and kodnegeri=$kodnegeri' and kodjenissekolah IN ('203','303') ");
				//echo haha;
				oci_execute($qbm);
				while($rbm = oci_fetch_array($qbm))
				{
					$bil=$bil+1;
					echo "     <tr>\n";
					echo "       <td><center>$bil</center></td>\n";
					echo "       <td>$rbm[NAMASEK]</td>\n";
					$bilsa = 0;	
					$qa = oci_parse($conn_sispa,"SELECT * FROM $penilaian_hcmr WHERE kodsek='$rbm[KODSEK]' AND tahun='$tahun' AND $tahap='$ting' AND jpep='$rowtov[JENPEP]'");
					oci_execute($qa);
		//echo haha;
					switch ($level)
					{
						case "MA" : 
							{
								while($rowsa = oci_fetch_array($qa))
								{
									if (($rowsa['BILMP'] == $rowsa['BILETR1A'] + $rowsa['BILETR2A']) AND ($rowsa['BILMP']>=7))
									{
										$bilsa = $bilsa + 1 ;
									}
								}
							}
						break;
		/* 
						case "MR" : 
							{
								while($rowsa = oci_fetch_array($qa))
								{
									if (($rowsa['BILMP'] == $rowsa['BILETRA']) AND ($rowsa['BILMP']>=7))
									{
										$bilsa = $bilsa + 1 ;
									}
								}
							}
						break;
		
						case "SR" : 
							{
								while($rowsa = oci_fetch_array($qa))
								{
									if (($rowsa['BILMP'] == $rowsa['BILETRA']) AND ($rowsa['BILMP']>=5))
									{
										$bilsa = $bilsa + 1 ;
									}
								}
							}
						break; */		
					}
					echo "       <td><center>$bilsa</center></td>\n";
					echo "     </tr>\n"; 
					$jumbilsa = $jumbilsa + $bilsa;
				}
				echo "     <tr>\n";
				echo "       <td colspan=\"2\"><center>JUMLAH</center></td>\n";
				echo "       <td><center>$jumbilsa</center></td>\n";
				echo "     </tr>\n";
				echo "   </table>\n";
			}
	}
	else{
			echo " <div align=\"center\"><p><strong>BILANGAN MURID CEMERLANG MENGIKUT SEKOLAH <br>".tahap($ting)."<br>".jpep($jpep)."<br>NEGERI $namanegeri ($kodnegeri) TAHUN $tahun</strong></p>\n";
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
			echo "   <table width=\"40%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
			echo "     <tr>\n";
			echo "       <td><div align=\"center\">BIL</div></td>\n";
			echo "       <td>NAMA SEKOLAH </td>\n";
			echo "       <td><div align=\"center\">BILANGAN</div></td>\n";
			echo "     </tr>\n";
			$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status='$status' and kodjenissekolah IN ('203','303')");
			oci_execute($qbm);
			while($rbm = oci_fetch_array($qbm))
			{
				$bil=$bil+1;
				echo "     <tr>\n";
				echo "       <td><center>$bil</center></td>\n";
				//echo "       <td><a href=senarai-cemerlang-dae.php?data=".$rbm[KODSEK]."/".$tahun."/".$ting."/".$jpep.">$rbm[NAMASEK]</a></td>\n";
				$bilsa = 0;	
				$qa = oci_parse($conn_sispa,"SELECT * FROM $penilaian WHERE kodsek='$rbm[KODSEK]' AND tahun='$tahun' AND $tahap='$ting' AND jpep='$jpep'");
				oci_execute($qa);
	
				switch ($level)
				{
					case "MA" : 
						{
							while($rowsa = oci_fetch_array($qa))
							{
								if (($rowsa['BILMP'] == $rowsa['BIL1A'] + $rowsa['BIL2A']) AND ($rowsa['BILMP']>=7))
								{
									$bilsa = $bilsa + 1 ;
								}
							}
						}
					break;
	
					case "MR" : 
						{
							while($rowsa = oci_fetch_array($qa))
							{
								if (($rowsa['BILMP'] == $rowsa['BILA']) AND ($rowsa['BILMP']>=7))
								{
									$bilsa = $bilsa + 1 ;
								}
							}
						}
					break;
	
					/* case "SR" : 
						{
							while($rowsa = oci_fetch_array($qa))
							{
								if (($rowsa['BILMP'] == $rowsa['BILA']) AND ($rowsa['BILMP']>=5))
								{
									$bilsa = $bilsa + 1 ;
								}
							}
						}
					break;	*/	
				}
				echo "       <td><center>$bilsa</center></td>\n";
				echo "     </tr>\n"; 
				$jumbilsa = $jumbilsa + $bilsa;
			}
			echo "     <tr>\n";
			echo "       <td colspan=\"2\"border=\"1\"><center>JUMLAH</center></td>\n";
			echo "       <td><center>$jumbilsa</center></td>\n";
			echo "     </tr>\n";
			echo "   </table>\n";
	}
	echo "<br><br><br>";
} else { ?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='a-semua-mpbptv.php?status=' + val;
		}
		</script>
		
		<?php
		//echo "$kodsek";
		//echo "<br><br>";
		echo " <center></b>CEMERLANG SEMUA MATA PELAJARAN</b></center>";
		echo "<br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		
		
		$status = $_GET['status'];
		if($status == "")
			$status = "SM";
		
		switch ($status)
		{
			case "SM" : $statussek = "SEKOLAH MENENGAH"; $tmp = "mpsmkc"; break;
			//case "SR" : $statussek = "SEKOLAH RENDAH"; $tmp = "mpsr"; break;
			default : $statussek = "Pilih Jenis Sekolah"; break;
		}
		
		$SQLting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting");
		oci_execute($SQLting);
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);
		//echo "SELECT DISTINCT kodppd, ppd FROM tkppd WHERE kodnegeri='".$_SESSION["kodnegeri"]."' ORDER BY kodppd";
     	//echo "<td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
		echo "<form method=post name='f1' action='a-semua-mpbptv.php'>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>JENIS SEKOLAH</td><td><select name=\"status\" onchange=\"reload(this.form)\"><option value=''>$statussek</option>";
		//echo "<option value=\"SR\">SEKOLAH RENDAH</option>";
		echo "<option value=\"SM\">SEKOLAH MENENGAH</option>";
		echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";
		
		$SQLppd = oci_parse($conn_sispa,"SELECT DISTINCT kodnegeri, negeri FROM tknegeri ");
		//echo "SELECT DISTINCT kodnegeri, negeri FROM tknegeri";
		oci_execute($SQLppd);
		
		echo "  <tr bgcolor=\"#CCCCCC\">\n";		
		echo "  <td>NEGERI</td>\n";
		echo "<td><select name='kodnegeri'><option value=''>Pilih Negeri</option>";
		while($rownegeri = oci_fetch_array($SQLppd)) { 
			echo  "<option value='$rownegeri[KODNEGERI]'>$rownegeri[NEGERI]</option>";
		}
		echo "</select>";
		echo "</td></tr>";

		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
		echo "<select name='ting'><option value=''>Tingkatan</option>";
		switch ($status)
		{
			case "SM" :	//echo "<option value=\"P\">P</option>";
						//echo "<option value=\"T1\">T1</option>";
						//echo "<option value=\"T2\">T2</option>";
						//echo "<option value=\"T3\">T3</option>";
						echo "<option value=\"T4\">T4</option>";
						echo "<option value=\"T5\">T5</option>";
						break;
						
		/*	case "SR" : echo "<option value=\"D1\">D1</option>";
						echo "<option value=\"D2\">D2</option>";
						echo "<option value=\"D3\">D3</option>";
						echo "<option value=\"D4\">D4</option>";
						echo "<option value=\"D5\">D5</option>";
						echo "<option value=\"D6\">D6</option>";
						break; */
		}
		echo "</select>";
		echo "</td></tr>";

		$SQLpep = oci_parse($conn_sispa,"SELECT * FROM jpep ORDER BY rank");
		oci_execute($SQLpep);
		echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td><td>";
		echo "<select name='jpep'><option value=''>Pilih Peperiksaan</option>";
		while($rowpep = oci_fetch_array($SQLpep)) { 
			echo  "<option value='$rowpep[KOD]'>$rowpep[JENIS]</option>";
		}
		echo "</select>";
		
		echo "</td></tr>";
		echo "<td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";

		//////////        Starting of second drop downlist /////////	
		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</tr></table><br><br>";
		
		//print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$rowppd[kodppd]\">";
		print "<input name=\"namanegeri\" type=\"hidden\" readonly value=\"$namanegeri\">";
		
		echo "<center><input type='submit' name=\"semuaa\" value=\"Hantar\"></center>";

		echo "</form>";
}?> 
</td>
<?php include 'kaki.php';?> 


                       