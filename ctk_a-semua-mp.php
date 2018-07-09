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
include 'input_validation.php';


?>
<td valign="top" class="rightColumn">

<?php


	$tahun=validate($_GET['tahun']);
	$ting=validate($_GET['ting']);
	$jpep=validate($_GET['jpep']);
	$status=validate($_GET['status']);
	$kodppd=validate($_GET['kodppd']);


   $sqlppd = oci_parse($conn_sispa,"select ppd from tkppd where kodppd= :kodppd");
	oci_bind_by_name($sqlppd, ':kodppd', $kodppd);
	oci_execute($sqlppd);
oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];

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

 ?>
 <?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); ?>                      