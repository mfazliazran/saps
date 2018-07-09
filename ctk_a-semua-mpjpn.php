<html>
<body>
<STYLE type="text/css">
@media print {
  #mybutton { display:none; visibility:hidden; }

  #mybutton2 { display:none; visibility:hidden; }
 
}



</STYLE>


<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>
<?php
include 'config.php';
include 'fungsi.php';


?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Peperiksaan</p>

<?php

	$tahun=$_GET['tahun'];
	$ting=$_GET['ting'];
	$jpep=$_GET['jpep'];
	$status=$_GET['status'];
	$kodppd=$_GET['kodppd'];


    $sqlppd = oci_parse($conn_sispa,"select ppd from tkppd where kodppd='$kodppd'");
	oci_execute($sqlppd);
$rowppd = oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];

	
	switch ($ting)
		{
			case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
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
				break;
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

		echo " <div align=\"center\"><p><strong>BILANGAN MURID CEMERLANG MENGIKUT SEKOLAH <br>".tahap($ting)."<br>".jpep($jpep)."<br>DAERAH $namappd($kodppd) TAHUN $tahun</strong></p>\n";
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
		$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status='$status'");
		oci_execute($qbm);
		while($rbm = oci_fetch_array($qbm))
		{
			$bil=$bil+1;
			echo "     <tr>\n";
			echo "       <td><center>$bil</center></td>\n";
			echo "       <td><a href=senarai-cemerlang-dae.php?data=".$rbm[KODSEK]."/".$tahun."/".$ting."/".$jpep.">$rbm[NAMASEK]</a></td>\n";
			
				$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status='$status' and kodppd=$kodppd'");
				oci_execute($qbm);
				while($rbm = oci_fetch_array($qbm))
				{
					$bil=$bil+1;
					echo "     <tr>\n";
					echo "       <td><center>$bil</center></td>\n";
					echo "       <td><a href=senarai-cemerlang-dae.php?data=".$rbm[KODSEK]."/".$tahun."/".$ting."/".$jpep.">$rbm[NAMASEK]</a></td>\n";
					$bilsa = 0;	
					$qa = oci_parse($conn_sispa,"SELECT * FROM $penilaian_hc WHERE kodsek='$rbm[KODSEK]' AND tahun='$tahun' AND $tahap='$ting' AND jpep='$rowtov[JENPEP]'");
					oci_execute($qa);
		
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
			
			$qbm = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status='$status'");
			oci_execute($qbm);
			while($rbm = oci_fetch_array($qbm))
			{
				$bil=$bil+1;
				echo "     <tr>\n";
				echo "       <td><center>$bil</center></td>\n";
				echo "       <td><a href=senarai-cemerlang-dae.php?data=".$rbm[KODSEK]."/".$tahun."/".$ting."/".$jpep.">$rbm[NAMASEK]</a></td>\n";
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
	
					case "SR" : 
						{
							while($rowsa = oci_fetch_array($qa))
							{
								if (($rowsa['BILMP'] == $rowsa['BILA']) AND ($rowsa['BILMP']>=5))
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

 ?>                      
 <?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>