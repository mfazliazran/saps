<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include 'input_validation.php';


?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>

<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Peperiksaan</p>

<?php

if (isset($_POST['semuaa']))
{
	$tahun=validate($_POST['tahun']);
	$ting=validate($_POST['ting']);
	$jpep=validate($_POST['jpep']);
	$status=validate($_POST['status']);
	$kodppd=validate($_POST['kodppd']);


    $sqlppd = oci_parse($conn_sispa,"select ppd from tkppd where kodppd= :kodppd");
	oci_bind_by_name($sqlppd, ':kodppd', $kodppd);
	oci_execute($sqlppd);
oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];

?>
<input type="button" name="export" value="EXPORT KE EXCELL" onclick="open_window('a-semua-mp-excel.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&jpep=<?php echo $jpep;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>','win1');" />

	
<div align=right><a href="ctk_a-semua-mp.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&jpep=<?php echo $jpep;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>" target=_blank ><img src=printi.jpg border=0></a></div><br>

<?php
	
	switch ($ting)
		{
			case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
				//$penilaian="penilaian_muridsr";
				$penilaian="tnilai_sr";
				$level = "SR";
				$bilgred="bila";
				$tahap="darjah";
				$penilaian_hc="penilaian_hcsr";
				//$penilaian_hc="tnilai_sr";
				$bilgredetr="biletra";
				break;
			case "P": case "T1": case "T2": case "T3":
				//$penilaian="penilaian_muridsmr";
				$penilaian="tnilai_smr";
				$level = "MR";
				$bilgred="bila";
				$tahap="ting";
				$penilaian_hc="penilaian_hcsmr";
				$bilgredetr="biletra";
				break;
			case "T4": case "T5":
				//$penilaian="penilaian_muridsma";
				$penilaian="tnilai_sma";
				$level = "MA";
				$bilgred="bil1a+bil2a";
				$tahap="ting";
				$penilaian_hc="penilaian_hcsma";
				$bilgredetr="biletr1a";
				break;
		}
	if ($jpep=="getr"){

		echo " <div align=\"center\"><p><strong>BILANGAN MURID CEMERLANG MENGIKUT SEKOLAH <br>".tahap($ting)."<br>".jpep($jpep)."<br>DAERAH $namappd($kodppd) TAHUN $tahun</strong></p>\n";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
		echo "   <table width=\"80%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
		echo "     <tr>\n";
		echo "       <td><div align=\"center\">BIL</div></td>\n";
		echo "       <td>NAMA SEKOLAH </td>\n";
		echo "       <td><div align=\"center\">BILANGAN</div></td>\n";
		echo "     </tr>\n";
		$gting = strtolower($ting);
		$qtov = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tahunpep= :tahun AND tingpep= :ting AND capai='TOV'");
		oci_bind_by_name($qtov, ':tahun', $tahun);
		oci_bind_by_name($qtov, ':ting', $ting);
		oci_execute($qtov);
		oci_fetch_array($qtov); // rowtov
		
		$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status='$status' and KodPPD='$kodppd' order by NAMASEK");
		oci_bind_by_name($qbm, ':status', $status);
		oci_bind_by_name($qbm, ':kodppd', $kodppd);
		oci_execute($qbm);
		while($row = oci_fetch_array($qbm)) //rbm
		{
			$bil=$bil+1;
			echo "     <tr>\n";
			echo "       <td><center>$bil</center></td>\n";
			echo "       <td><a href=senarai-cemerlang-dae.php?data=".$row["KODSEK"]."/".$tahun."/".$ting."/".$jpep.">".$row["NAMASEK"]."</a></td>\n";
			
				$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status= :status and kodppd= :kodppd order by NAMASEK");
				oci_bind_by_name($qbm, ':status', $status);
		        oci_bind_by_name($qbm, ':kodppd', $kodppd);
				oci_execute($qbm);
				while($row = oci_fetch_array($qbm)) //rbm
				{
					$bil=$bil+1;
					echo "     <tr>\n";
					echo "       <td><center>$bil</center></td>\n";
					echo "       <td><a href=senarai-cemerlang-dae.php?data=".$row["KODSEK"]."/".$tahun."/".$ting."/".$jpep.">".$row["NAMASEK"]."</a></td>\n";
					$bilsa = 0;	
					$qa = oci_parse($conn_sispa,"SELECT * FROM $penilaian_hc WHERE kodsek='$rbm[KODSEK]' AND tahun= :tahun AND $tahap= :ting AND jpep='$rowtov[jenpep]'");
					oci_bind_by_name($qa, ':tahun', $tahun);
		            oci_bind_by_name($qa, ':ting', $ting);
					oci_execute($qa);
		
					switch ($level)
					{
						case "MA" : 
							{
								while($rowsa = oci_fetch_array($qa)) //rowsa
								{
									if ($row["BILMP"] == ($row["BILAP"] + $row["BILA"] + $row["BILAM"]))
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
									if ($row["BILMP"] == $row["BILA"])
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
									if ($row["BILMP"] == $row["BILA"])
									{
										$bilsa = $bilsa + 1 ;
									}
								}
							}
						break;		
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
			echo " <div align=\"center\"><p><strong>BILANGAN MURID CEMERLANG MENGIKUT SEKOLAH <br>".tahap($ting)."<br>".jpep($jpep)."<br>DAERAH $namappd($kodppd) TAHUN $tahun</strong></p>\n";
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
			echo "   <table width=\"40%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
			echo "     <tr>\n";
			echo "       <td><div align=\"center\">BIL</div></td>\n";
			echo "       <td>NAMA SEKOLAH </td>\n";
			echo "       <td><div align=\"center\">BILANGAN</div></td>\n";
			echo "     </tr>\n";
			
			$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status= :status and KodPPD= :kodppd order by NAMASEK");
			oci_bind_by_name($qbm, ':status', $status);
		    oci_bind_by_name($qbm, ':kodppd', $kodppd);
			
			oci_execute($qbm);
			$bil=0;
			while($row = oci_fetch_array($qbm))
			{
				$bil=$bil+1;
				echo "     <tr>\n";
				echo "       <td><center>$bil</center></td>\n";
				echo "       <td><a href=senarai-cemerlang-dae.php?data=".$row["KODSEK"]."/".$tahun."/".$ting."/".$jpep.">".$row["NAMASEK"]."</a></td>\n";
				$bilsa = 0;	
				$qa = oci_parse($conn_sispa,"SELECT * FROM $penilaian WHERE kodsek='$row[KODSEK]' AND tahun= :tahun AND $tahap= :ting AND jpep= :jpep");
				oci_bind_by_name($qa, ':tahun', $tahun);
		        oci_bind_by_name($qa, ':ting', $ting);
				oci_bind_by_name($qa, ':jpep', $jpep);
				//echo "SELECT * FROM $penilaian WHERE kodsek='$row[KODSEK]' AND tahun='$tahun' AND $tahap='$ting' AND jpep='$jpep'<br>";
				oci_execute($qa);
	
				switch ($level)
				{
					case "MA" : 
						{
							while($row = oci_fetch_array($qa))
							{
								if ($row["BILMP"] == ($row["BILAP"] + $row["BILA"] + $row["BILAM"]))
								{
									$bilsa = $bilsa + 1 ;
								}
							}
						}
					break;
	
					case "MR" : 
						{
							while($row = oci_fetch_array($qa))
							{
								if ($row["BILMP"] == $row["BILA"])
								{
									$bilsa = $bilsa + 1 ;
								}
							}
						}
					break;
	
					case "SR" : 
						{
							while($row = oci_fetch_array($qa))
							{
								if ($row["BILMP"] == $row["BILA"])
								{
									$bilsa = $bilsa + 1 ;
								}
							}
						}
					break;		
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
	echo "<br><br><br>";
} else { ?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='a-semua-mp.php?status=' + val;
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
		if ($status=="")
		$status="SR";
		
		switch ($status)
		{
			case "SM" : $statussek = "SEKOLAH MENENGAH"; $tmp = "mpsmkc"; break;
			case "SR" : $statussek = "SEKOLAH RENDAH"; $tmp = "mpsr"; break;
			default : $statussek = "Pilih Jenis Sekolah"; break;
		}
		
		$SQLting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting");
		oci_execute($SQLting);
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);
		
     	echo "<td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
		echo "<form method=post name='f1' action='a-semua-mp.php'>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>JENIS SEKOLAH</td><td><select name=\"status\" onchange=\"reload(this.form)\">";
				?>
        
		<option <?php if ($status=="SR") echo " selected "; ?> value="SR">SEKOLAH RENDAH</option>
		<option <?php if ($status=="SM") echo " selected "; ?> value="SM">SEKOLAH MENENGAH</option>
		<?php echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";

		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
		echo "<select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			case "SM" :	echo "<option value=\"P\">P</option>";
						echo "<option value=\"T1\">T1</option>";
						echo "<option value=\"T2\">T2</option>";
						echo "<option value=\"T3\">T3</option>";
						echo "<option value=\"T4\">T4</option>";
						echo "<option value=\"T5\">T5</option>";
						break;
						
			case "SR" : echo "<option value=\"D1\">D1</option>";
						echo "<option value=\"D2\">D2</option>";
						echo "<option value=\"D3\">D3</option>";
						echo "<option value=\"D4\">D4</option>";
						echo "<option value=\"D5\">D5</option>";
						echo "<option value=\"D6\">D6</option>";
						break;
		}
		echo "</select>";
		echo "</td></tr>";

		$SQLpep = oci_parse($conn_sispa,"SELECT DISTINCT kod,jenis, rank FROM jpep ORDER BY rank");
		oci_execute($SQLpep);
		echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td><td>";
		echo "<select name='jpep'><option value=''>Pilih Peperiksaan</option>";
		while($row = oci_fetch_array($SQLpep)) { 
			echo  "<option value='".$row["KOD"]."'>".$row["JENIS"]."</option>";
		}
		echo "</select>";
		echo "</td></tr>";

		//////////        Starting of second drop downlist /////////	
		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</tr></table><br><br>";
		
		print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";
		
		echo "<center><input type='submit' name=\"semuaa\" value=\"Hantar\"></center>";

		echo "</form>";
}?> 
</td>
<?php include 'kaki.php';?> 


                       