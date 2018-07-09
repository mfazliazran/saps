<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php'
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Headcount Sekolah (BPTV)</p>
<?php
function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}
$kodsek = $_GET['kodsek'];
$tahun = $_GET['tahun'];
if ($tahun=="")
	$tahun = $_SESSION['tahun'];
$jpep = $_SESSION['jpep'];
//switch ($_SESSION['statussek'])
//{
//	case "SM" :
		//$level="MR";
		$theadcount="headcount";
		$tmatap="mpsmkc";
		$tajuk="Ting";
		$tahap="TING";
		$penilaiansm="SEM";
//		break;
//}
$bil=0;
echo "<H2><center>ANALISIS PENCAPAIAN HEADCOUNT SEKOLAH<br>$namasek<br>TAHUN $tahun</center></H2>\n";
echo " <br>";
echo "  <table  width=\"98%\"  align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo "    <td rowspan=\"2\">Bil\n";
echo "    <div align=\"center\"></div></td>\n";
echo "    <td rowspan=\"2\">$tajuk\n";
echo "    <div align=\"center\"></div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">TOV</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">OTR1</div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">AR1</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">OTR2</div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">AR2</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">OTR3</div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">AR3</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">ETR</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\">Bil<br>Ambil</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPMP</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPMP</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Ambil</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPMP</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPMP</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Ambil</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPMP</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPMP</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Ambil</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPMP</div></td>\n";
echo "    <td><div align=\"center\">Bil<br>Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPMP</div></td>\n";
echo "  </tr>\n";


$qting = "SELECT DISTINCT $tahap FROM $theadcount WHERE kodsek='$kodsek' AND tahun='$tahun' $exclude ORDER BY $tahap";
//echo $qting;
$rting = OCIParse($conn_sispa,$qting);
OCIExecute($rting);
$bil=0;
$bilcalon=0;
while (OCIFetch($rting)){
		$tingtemp = OCIResult($rting,"$tahap");//$recordting["$tahap"];
		switch ($tingtemp) {
			case "T4": case "t4": case "T5": case "t5": 
			{
				$analisis="analisis_mpma amp, mpsmkc mp";
				$tingdar = "TING";
				$bilgred = "BILAP,BILA,BILAM,BILBP,BILB,BILCP,BILC,BILD,BILE,BILG,BILTH";
				$minus = "select kod from sub_ma_xambil ";
				break;
			}
		}
			
		$qhc = "SELECT * FROM $theadcount WHERE kodsek='$kodsek' AND tahun='$tahun' AND $tahap='$tingtemp' ";
		//echo $qhc."<br>";
		$rhc = OCIParse($conn_sispa,$qhc);
		OCIExecute($rhc);
		/*if($penilaiansm=="SEM"){
			switch ($tingtemp) {
				case "P": case "T1": case "T2": case "T3":
					$penilaian="penilaian_muridsmr";
					break;
				case "T4": case "T5":
					$penilaian="penilaian_muridsma";
					break;
			}
		}*/
		$qhc2 = "SELECT nokp FROM $theadcount WHERE kodsek='$kodsek' AND tahun='$tahun' AND $tahap='$tingtemp'";
		//echo $qhc2."<br>";
		$rhc2 = OCIParse($conn_sispa,$qhc2);
		OCIExecute($rhc2);
		$bilcalon=0;
		while($datx = OCIFetch($rhc2)){
			//$nokppp = $datx['NOKP'];
			$bilcalon++;	
		}
		//$bilcalon = count_row($qhc2);
		//echo $qhc2." - $bilcalon <br>";

		switch ($tingtemp) {
			case "P": case "p": case "T1": case "t1": case "T2": case "t2": case "T3": case "t3": case "D1": case "d1": case "D2": case "d2": case "D3": case "d3": case "D4": case "d4": case "D5": case "d5": case "D6": case "d6":
			{
				$tovA=$tovB=$tovC=$tovD=$tovE=$tovTH=$oti1A=$oti1B=$oti1C=$oti1D=$oti1E=$oti1TH=0;
				$oti2A=$oti2B=$oti2C=$oti2D=$oti2E=$oti2TH=$oti3A=$oti3B=$oti3C=$oti3D=$oti3E=$oti3TH=0;
				$atr1A=$atr1B=$atr1C=$atr1D=$atr1E=$atr1TH=$atr2A=$atr2B=$atr2C=$atr2D=$atr2E=$atr2TH=0;
				$atr3A=$atr3B=$atr3C=$atr3D=$atr3E=$atr3TH=$etrA=$etrB=$etrC=$etrD=$etrE=$etrTH=0;
				
				while (OCIFetch($rhc))
				{
					//echo OCIResult($rhc,"GTOV")."<br>"; 
					if (OCIResult($rhc,"GTOV")=="A"){
						$tovA=$tovA+1;
					}
					//echo $tovA."<br>";	
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="A")
						$atr1A=$atr1A+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="A")
						$atr2A=$atr2A+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="A")
						$atr3A=$atr3A+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="A")
						$oti1A=$oti1A+1;

					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="A")
						$oti2A=$oti2A+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="A")
						$oti3A=$oti3A+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="A")
						$etrA=$etrA+1;
						
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="B")
						$tovB=$tovB+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="B")
						$atr1B=$atr1B+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="B")
						$atr2B=$atr2B+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="B")
						$atr3B=$atr3B+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="B")
						$oti1B=$oti1B+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="B")
						$oti2B=$oti2B+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="B")
						$oti3B=$oti3B+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="B")
						$etrB=$etrB+1;
						
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="C")
						$tovC=$tovC+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="C")
						$atr1C=$atr1C+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="C")
						$atr2C=$atr2C+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="C")
						$atr3C=$atr3C+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="C")
						$oti1C=$oti1C+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="C")
						$oti2C=$oti2C+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="C")
						$oti3C=$oti3C+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="C")
						$etr2A=$etr2A+1;
							
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="D")
						$tovD=$tovD+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="D")
						$atr1D=$atr1D+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="D")
						$atr2D=$atr2D+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="D")
						$atr3D=$atr3D+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="D")
						$oti1D=$oti1D+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="D")
						$oti2D=$oti2D+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="D")
						$oti3D=$oti3D+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="D")
						$etrD=$etrD+1;
						
					
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="E")
						$tovE=$tovE+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="E")
						$atr1E=$atr1E+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="E")
						$atr2E=$atr2E+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="E")
						$atr3E=$atr3E+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="E")
						$oti1E=$oti1E+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="E")
						$oti2E=$oti2E+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="E")
						$oti3E=$oti3E+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="E")
						$etrE=$etrE+1;
					
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="TH")
						$tovTH=$tovTH+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="TH")
						$atr1TH=$atr1TH+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="TH")
						$atr2TH=$atr2TH+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="TH")
						$atr3TH=$atr3TH+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="TH")
						$oti1TH=$oti1TH+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="TH")
						$oti2TH=$oti2TH+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="TH")
						$oti3TH=$oti3TH+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="TH")
						$etrTH=$etrTH+1;
						
				}
				$gpmptov=number_format((($tovA*1)+($tovB*2)+($tovC*3)+($tovD*4)+($tovE*5)+($tovTH*5))/($bilcalon-$tovTH),2,'.',',');
				$gpmpoti1=number_format((($oti1A*1)+($oti1B*2)+($oti1C*3)+($oti1D*4)+($oti1E*5)+($oti1TH*5))/($bilcalon-$oti1TH),2,'.',',');
				//$gpmpatr1=number_format((($atr1A*1)+($atr1B*2)+($atr1C*3)+($atr1D*4)+($atr1E*5)+($atr1TH*5))/($bilcalon-$atr1TH),2,'.',',');
				$gpmpoti2=number_format((($oti2A*1)+($oti2B*2)+($oti2C*3)+($oti2D*4)+($oti2E*5)+($oti2TH*5))/($bilcalon-$oti2TH),2,'.',',');
				//$gpmpatr2=number_format((($atr2A*1)+($atr2B*2)+($atr2C*3)+($atr2D*4)+($atr2E*5)+($atr2TH*5))/($bilcalon-$atr2TH),2,'.',',');
				$gpmpoti3=number_format((($oti3A*1)+($oti3B*2)+($oti3C*3)+($oti3D*4)+($oti3E*5)+($oti3TH*5))/($bilcalon-$oti3TH),2,'.',',');
				//$gpmpatr3=number_format((($atr3A*1)+($atr3B*2)+($atr3C*3)+($atr3D*4)+($atr3E*5)+($atr3TH*5))/($bilcalon-$atr3TH),2,'.',',');
				$gpmpetr=number_format((($etrA*1)+($etrB*2)+($etrC*3)+($etrD*4)+($etrE*5)+($etrTH*5))/($bilcalon-$etrTH),2,'.',',');
			
				
			//gmpsmamr	
				
				$bil=$bil+1;
				//echo $tingtemp."<br>";
				$sql_hc = "select jenpep,capai from tentu_hc where tingpep='$tingtemp' and tahunpep='$tahun' and capai!='TOV'";
				$res_hc = oci_parse($conn_sispa,$sql_hc);
				oci_execute($res_hc);
				$jumambilatr1=$jumdaftaratr1=$jumthatr1=$jumaatr1=$jumbatr1=$jumcatr1=$jumdatr1=$jumeatr1=$jumlulusatr1=0;
				$jumambilatr2=$jumdaftaratr2=$jumthatr2=$jumaatr2=$jumbatr2=$jumcatr2=$jumdatr2=$jumeatr2=$jumlulusatr2=0;
				$jumambilatr3=$jumdaftaratr3=$jumthatr3=$jumaatr3=$jumbatr3=$jumcatr3=$jumdatr3=$jumeatr3=$jumlulusatr3=0;
				
				//echo $jumambilatr3=$jumdaftaratr3=$jumthatr3=$jumaatr3=$jumbatr3=$jumcatr3=$jumdatr3=$jumeatr3=$jumlulusatr3=0;;
				
				while($data_hc = oci_fetch_array($res_hc)){
					$jenpep = $data_hc["JENPEP"];	//jenis pep
					$capai = $data_hc["CAPAI"];//atr1/atr2/atr3
					//echo "<br>".$capai;
				
					$q_mp = "SELECT amp.kodmp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile
							 FROM $analisis,tsekolah ts WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jenpep' AND  amp.$tingdar='$tingtemp' AND amp.kodmp=mp.kod and amp.kodmp not in ($minus) and ts.kodjenissekolah in ('203','303') Group BY mp.susun,amp.kodmp";
							 
					//echo "<br>".$q_mp."<br>";
					
					$smt = oci_parse($conn_sispa,$q_mp);
					
					oci_execute($smt);
					//echo $smt;
					$bilmp = count_row($q_mp);
					if ($bilmp != 0)
					{						
						while($rowmp = oci_fetch_array($smt))
						{		
							if($capai == "ATR1"){
								$jumdaftaratr1+=(int) $rowmp["BCALON"];
								$jumambilatr1+=(int) $rowmp["AMBIL"];
								$jumthatr1+=(int) $rowmp["TH"];
								$jumaatr1+=(int) $rowmp["BILA"];
								$jumbatr1+=(int) $rowmp["BILB"];
								$jumcatr1+=(int) $rowmp["BILC"];
								$jumdatr1+=(int) $rowmp["BILD"];
								$jumeatr1+=(int) $rowmp["BILE"];
								$jumlulusatr1+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
								$gpmpatr1 = gpmpmrsr($jumaatr1,$jumbatr1,$jumcatr1,$jumdatr1,$jumeatr1,$jumambilatr1);
						}//if
							 if($capai == "ATR2"){
								$jumdaftaratr2+=(int) $rowmp["BCALON"];
								$jumambilatr2+=(int) $rowmp["AMBIL"];
								$jumthatr2+=(int) $rowmp["TH"];
								$jumaatr2+=(int) $rowmp["BILA"];
								$jumbatr2+=(int) $rowmp["BILB"];
								$jumcatr2+=(int) $rowmp["BILC"];
								$jumdatr2+=(int) $rowmp["BILD"];
								$jumeatr2+=(int) $rowmp["BILE"];
								$jumlulusatr2+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
								$gpmpatr2 = gpmpmrsr($jumaatr2,$jumbatr2,$jumcatr2,$jumdatr2,$jumeatr2,$jumambilatr2);
						}//if
							if($capai == "ATR3"){
								$jumdaftaratr3+=(int) $rowmp["BCALON"];
								$jumambilatr3+=(int) $rowmp["AMBIL"];
								$jumthatr3+=(int) $rowmp["TH"];
								$jumaatr3+=(int) $rowmp["BILA"];
								$jumbatr3+=(int) $rowmp["BILB"];
								$jumcatr3+=(int) $rowmp["BILC"];
								$jumdatr3+=(int) $rowmp["BILD"];
								$jumeatr3+=(int) $rowmp["BILE"];
								$jumlulusatr3+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
								$gpmpatr3 = gpmpmrsr($jumaatr3,$jumbatr3,$jumcatr3,$jumdatr3,$jumeatr3,$jumambilatr3);
							}//if
						}//while rowmp
					}//if bilmp!0
					//if($capai == "ATR1")
						//echo "<br>$tingtemp bilambatr1 $jumambilatr1 billulus $jumlulusatr1 atr1 $gpmpatr1";	
				}//while jenpep
				$bilang = (int) $bil;
				
				if(checkNum($bilang) === TRUE){
				  $col="#CDCDCD";
				}else{
				  $col="";
				}	
				echo "  <tr bgcolor='$col'>\n";
				echo "    <td><div align=\"center\">$bil</div></td>\n";
				echo "    <td><div align=\"center\"><a href=hc-mp-ting-bptv.php?datahc=".$kodsek."/".$tahun."/".$tingtemp.">$tingtemp</a></div></td>\n";
				//echo "    <td><div align=\"center\"><a href=analisis_mptingsr.php?ting=$tingtemp>$tingtemp</a></div></td>\n";
				echo "    <td><div align=\"center\">".($bilcalon-$tovTH)."</div></td>\n";
				echo "    <td><div align=\"center\">".($tovA+$tovB+$tovC+$tovD)."<br>".number_format((($tovA+$tovB+$tovC+$tovD)/($bilcalon-$tovTH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmptov</div></td>\n";
				echo "    <td><div align=\"center\">".($oti1A+$oti1B+$oti1C+$oti1D)."<br>".number_format((($oti1A+$oti1B+$oti1C+$oti1D)/($bilcalon-$oti1TH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpoti1</div></td>\n";
				echo "    <td><div align=\"center\">$jumambilatr1</div></td>\n";
				echo "    <td><div align=\"center\">$jumlulusatr1<br>".peratus($jumlulusatr1,$bilcalon-$bilanganthATR1)."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpatr1</div></td>\n";
				echo "    <td><div align=\"center\">".($oti2A+$oti2B+$oti2C+$oti2D)."<br>".number_format((($oti2A+$oti2B+$oti2C+$oti2D)/($bilcalon-$oti2TH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpoti2</div></td>\n";
				echo "    <td><div align=\"center\">$jumambilatr2</div></td>\n";
				echo "    <td><div align=\"center\">$jumlulusatr2<br>".peratus($jumlulusatr2,$bilcalon-$bilanganthATR2)."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpatr2</div></td>\n";
				echo "    <td><div align=\"center\">".($oti3A+$oti3B+$oti3C+$oti3D)."<br>".number_format((($oti3A+$oti3B+$oti3C+$oti3D)/($bilcalon-$oti3TH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpoti3</div></td>\n";
				echo "    <td><div align=\"center\">$jumambilatr3</div></td>\n";
				echo "    <td><div align=\"center\">$jumlulusatr3<br>".peratus($jumlulusatr3,$bilcalon-$bilanganthATR3)."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpatr3</div></td>\n";
				echo "    <td><div align=\"center\">".($etrA+$etrB+$etrC+$etrD)."<br>".number_format((($etrA+$etrB+$etrC+$etrD)/($bilcalon-$etrTH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpetr</div></td>\n";
				echo "  </tr>\n";
				$gpmpatr1=0;
				$gpmpatr2=0;
				$gpmpatr3=0;
				break;
			}
			
			case "T4": case "t4": case "T5": case "t5":
			{	
				$tov1A=$tov2A=$tov3B=$tov4B=$tov5C=$tov6C=$tov7D=$tov8E=$tov9G=$tovTH=0;
				$oti11A=$oti12A=$oti13B=$oti14B=$oti15C=$oti16C=$oti17D=$oti18E=$oti19G=$oti1TH=0;
				$oti21A=$oti22A=$oti23B=$oti24B=$oti25C=$oti26C=$oti27D=$oti28E=$oti29G=$oti2TH=0;
				$oti31A=$oti32A=$oti33B=$oti34B=$oti35C=$oti36C=$oti37D=$oti38E=$oti39G=$oti3TH=0;
				$etr1A=$etr2A=$etr3B=$etr4B=$etr5C=$etr6C=$etr7D=$etr8E=$etr9G=$etrTH=0;
				$atr11A=$atr12A=$atr13B=$atr14B=$atr15C=$atr16C=$atr17D=$atr18E=$atr19G=$atr1TH=0;
				$atr21A=$atr22A=$atr23B=$atr24B=$atr25C=$atr26C=$atr27D=$atr28E=$atr29G=$atr2TH=0;
				$atr31A=$atr32A=$atr33B=$atr34B=$atr35C=$atr36C=$atr37D=$atr38E=$atr39G=$atr3TH=0;
					
				while (OCIFetch($rhc))
				{
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="1A")
						$tov1A=$tov1A+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="1A")
						$atr11A=$atr11A+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="1A")
						$atr21A=$atr21A+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="1A")
						$atr31A=$atr31A+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="1A")
						$oti11A=$oti11A+1;
							
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="1A")
						$oti21A=$oti21A+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="1A")
						$oti31A=$oti31A+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="1A")
						$etr1A=$etr1A+1;
						
						
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="2A")
						$tov2A=$tov2A+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="2A")
						$atr12A=$atr12A+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="2A")
						$atr22A=$atr22A+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="2A")
						$atr32A=$atr32A+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="2A")
						$oti12A=$oti12A+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="2A")
						$oti22A=$oti22A+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="2A")
						$oti32A=$oti32A+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="2A")
						$etr2A=$etr2A+1;
							
					
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="3B")
						$tov3B=$tov3B+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="3B")
						$atr13B=$atr13B+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="3B")
						$atr23B=$atr23B+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="3B")
						$atr33B=$atr33B+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="3B")
						$oti13B=$oti13B+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="3B")
						$oti23B=$oti23B+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="3B")
						$oti33B=$oti33B+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="3B")
						$etr3B=$etr3B+1;
						
					
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="4B")
						$tov4B=$tov4B+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="4B")
						$atr14B=$atr14B+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="4B")
						$atr24B=$atr24B+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="4B")
						$atr34B=$atr34B+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="4B")
						$oti14B=$oti14B+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="4B")
						$oti24B=$oti24B+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="4B")
						$oti34B=$oti34B+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="4B")
						$etr4B=$etr4B+1;
						
					
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="5C")
						$tov5C=$tov5C+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="5C")
						$atr15C=$atr15C+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="5C")
						$atr25C=$atr25C+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="5C")
						$atr35C=$atr35C+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="5C")
						$oti15C=$oti15C+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="5C")
						$oti25C=$oti25C+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="5C")
						$oti35C=$oti35C+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="5C")
						$etr5C=$etr5C+1;
						
						
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="6C")
						$tov6C=$tov6C+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="6C")
						$atr16C=$atr16C+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="6C")
						$atr26C=$atr26C+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="6C")
						$atr36C=$atr36C+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="6C")
						$oti16C=$oti16C+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="6C")
						$oti26C=$oti26C+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="6C")
						$oti36C=$oti36C+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="6C")
						$etr6C=$etr6C+1;
						
					
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="7D")
						$tov7D=$tov7D+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="7D")
						$atr17D=$atr17D+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="7D")
						$atr27D=$atr27D+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="7D")
						$atr37D=$atr37D+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="7D")
						$oti17D=$oti17D+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="7D")
						$oti27D=$oti27D+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="7D")
						$oti37D=$oti37D+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="7D")
						$etr7D=$etr7D+1;
						
					
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="8E")
						$tov8E=$tov8E+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="8E")
						$atr18E=$atr18E+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="8E")
						$atr28E=$atr28E+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="8E")
						$atr38E=$atr38E+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="8E")
						$oti18E=$oti18E+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="8E")
						$oti28E=$oti28E+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="8E")
						$oti38E=$oti38E+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="8E")
						$etr8E=$etr8E+1;
						
						
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="9G")
						$tov9G=$tov9G+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="9G")
						$atr19G=$atr19G+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="9G")
						$atr29G=$atr29G+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="9G")
						$atr39G=$atr39G+1;
						

					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="9G")
						$oti19G=$oti19G+1;
						
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="9G")
						$oti29G=$oti29G+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="9G")
						$oti39G=$oti39G+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="9G")
						$etr9G=$etr9G+1;
						
						
					if (/*$record['gtov']*/OCIResult($rhc,"GTOV")=="TH")
						$tovTH=$tovTH+1;
						
					if (/*$record['gatr1']*/OCIResult($rhc,"GATR1")=="TH")
						$atr1TH=$atr1TH+1;
						
					if (/*$record['gatr2']*/OCIResult($rhc,"GATR2")=="TH")
						$atr2TH=$atr2TH+1;
						
					if (/*$record['gatr3']*/OCIResult($rhc,"GATR3")=="TH")
						$atr3TH=$atr3TH+1;
						
					if (/*$record['gotr1']*/OCIResult($rhc,"GOTR1")=="TH")
						$oti1TH=$oti1TH+1;
					
					if (/*$record['gotr2']*/OCIResult($rhc,"GOTR2")=="TH")
						$oti2TH=$oti2TH+1;
						
					if (/*$record['gotr3']*/OCIResult($rhc,"GOTR3")=="TH")
						$oti3TH=$oti3TH+1;
						
					if (/*$record['getr']*/OCIResult($rhc,"GETR")=="TH")
						$etrTH=$etrTH+1;
				}

				$gpmptov=number_format((($tov1A*1)+($tov2A*2)+($tov3B*3)+($tov4B*4)+($tov5C*5)+($tov6C*6)+($tov7D*7)+($tov8E*8)+($tov9G*9)+($tovTH*9))/($bilcalon-$tovTH),2,'.',',');
				$gpmpoti1=number_format((($oti11A*1)+($oti12A*2)+($oti13B*3)+($oti14B*4)+($oti15C*5)+($oti16C*6)+($oti17D*7)+($oti18E*8)+($oti19G*9)+($oti1TH*9))/($bilcalon-$oti1TH),2,'.',',');
				$gpmpatr1=number_format((($atr11A*1)+($atr12A*2)+($atr13B*3)+($atr14B*4)+($atr15C*5)+($atr16C*6)+($atr17D*7)+($atr18E*8)+($atr19G*9)+($atr1TH*9))/($bilcalon-$atr1TH),2,'.',',');
				$gpmpoti2=number_format((($oti21A*1)+($oti22A*2)+($oti23B*3)+($oti24B*4)+($oti25C*5)+($oti26C*6)+($oti27D*7)+($oti28E*8)+($oti29G*9)+($oti2TH*9))/($bilcalon-$oti2TH),2,'.',',');
				$gpmpatr2=number_format((($atr21A*1)+($atr22A*2)+($atr23B*3)+($atr24B*4)+($atr25C*5)+($atr26C*6)+($atr27D*7)+($atr28E*8)+($atr29G*9)+($atr2TH*9))/($bilcalon-$atr2TH),2,'.',',');
				$gpmpoti3=number_format((($oti31A*1)+($oti32A*2)+($oti33B*3)+($oti34B*4)+($oti35C*5)+($oti36C*6)+($oti37D*7)+($oti38E*8)+($oti39G*9)+($oti3TH*9))/($bilcalon-$oti3TH),2,'.',',');
				$gpmpatr3=number_format((($atr31A*1)+($atr32A*2)+($atr33B*3)+($atr34B*4)+($atr35C*5)+($atr36C*6)+($atr37D*7)+($atr38E*8)+($atr39G*9)+($atr3TH*9))/($bilcalon-$atr3TH),2,'.',',');
				$gpmpetr=number_format((($etr1A*1)+($etr2A*2)+($etr3B*3)+($etr4B*4)+($etr5C*5)+($etr6C*6)+($etr7D*7)+($etr8E*8)+($etr9G*9)+($etrTH*9))/($bilcalon-$etrTH),2,'.',',');
				
				$bil=$bil+1;
				  
				//echo $tingtemp."<br>";
				$sql_ha = "select jenpep,capai from tentu_hc where tingpep='$tingtemp' and tahunpep='$tahun' and capai!='TOV'";
				//echo $sql_hc2."<br>";
				$res_ha = oci_parse($conn_sispa,$sql_ha);
				oci_execute($res_ha);
				$jumdaftaratr1=$jumambilatr1=$jumthatr1=$jumapatr1=$jumaatr1=$jumamatr1=$jumbpatr1=$jumbatr1=$jumcpatr1=$jumcatr1=$jumdatr1=$jumeatr1=$jumgatr1=$jumlulusatr1=0;
				$jumdaftaratr2=$jumambilatr2=$jumthatr2=$jumapatr2=$jumaatr2=$jumamatr2=$jumbpatr2=$jumbatr2=$jumcpatr2=$jumcatr2=$jumdatr2=$jumeatr2=$jumgatr2=$jumlulusatr2=0;
				$jumdaftaratr3=$jumambilatr3=$jumthatr3=$jumapatr3=$jumaatr3=$jumamatr3=$jumbpatr3=$jumbatr3=$jumcpatr3=$jumcatr3=$jumdatr3=$jumeatr3=$jumgatr3=$jumlulusatr3=0;
				
				while($data_ha = oci_fetch_array($res_ha)){
					$jenpep = $data_ha["JENPEP"];	//jenis pep
					$capai = $data_ha["CAPAI"];//atr1/atr2/atr3
					//echo "<br>".$capai2;
				
					 $q_mpp ="SELECT amp.kodmp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(Ap) bilap, SUM(A) bila, SUM(Am) bilam, 
					 SUM(Bp) bilbp, SUM(B) bilb, SUM(Cp) bilcp, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(G) bilg
					 FROM analisis_mpma amp, mpsmkc mp, tsekolah ts WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jenpep' 
					 AND  amp.$tingdar='$tingtemp' AND amp.kodmp=mp.kod and amp.kodmp not in ($minus) and ts.kodjenissekolah in ('203','303') Group BY mp.susun,amp.kodmp";

					//echo "<br>".$q_mpp."<br>";
					$smt2 = oci_parse($conn_sispa,$q_mpp);
					oci_execute($smt2);

					//echo $smt2;
					$bilmp2 = count_row($q_mpp);
					if ($bilmp2 != 0)
					{						
						while($rowmp = oci_fetch_array($smt2))
						{		
							if($capai == "ATR1"){
								$jumdaftaratr1+=(int) $rowmp["BCALON"];
								$jumambilatr1+=(int) $rowmp["AMBIL"];
								$jumthatr1+=(int) $rowmp["TH"];
								$jumapatr1+=(int) $rowmp["BILAP"];
								$jumaatr1+=(int) $rowmp["BILA"];
								$jumamatr1+=(int) $rowmp["BILAM"];
								$jumbpatr1+=(int) $rowmp["BILBP"];
								$jumbatr1+=(int) $rowmp["BILB"];
								$jumcpatr1+=(int) $rowmp["BILCP"];
								$jumcatr1+=(int) $rowmp["BILC"];
								$jumdatr1+=(int) $rowmp["BILD"];
								$jumeatr1+=(int) $rowmp["BILE"];
								$jumgatr1+=(int) $rowmp["BILG"];
								$jumlulusatr1+=(int) $rowmp["BILAP"]+$rowmp["BILA"]+$rowmp["BILAM"]+$rowmp["BILBP"]+$rowmp["BILB"]+$rowmp["BILCP"]+$rowmp["BILC"]+$rowmp["BILD"];
								$gpmpatr1 = gpmpma($jumapatr1,$jumaatr1,$jumamatr1,$jumbpatr1,$jumbatr1,$jumcpatr1,$jumcatr1,$jumdatr1,$jumeatr1,$jumgatr1,$jumambilatr1);
						}//if
							 if($capai == "ATR2"){
								$jumdaftaratr2+=(int) $rowmp["BCALON"];
								$jumambilatr2+=(int) $rowmp["AMBIL"];
								$jumthatr2+=(int) $rowmp["TH"];
								$jumapatr2+=(int) $rowmp["BILAP"];
								$jumaatr2+=(int) $rowmp["BILA"];
								$jumamatr2+=(int) $rowmp["BILAM"];
								$jumbpatr2+=(int) $rowmp["BILBP"];
								$jumbatr2+=(int) $rowmp["BILB"];
								$jumcpatr2+=(int) $rowmp["BILCP"];
								$jumcatr2+=(int) $rowmp["BILC"];
								$jumdatr2+=(int) $rowmp["BILD"];
								$jumeatr2+=(int) $rowmp["BILE"];
								$jumgatr2+=(int) $rowmp["BILG"];
								$jumlulusatr2+=(int) $rowmp["BILAP"]+$rowmp["BILA"]+$rowmp["BILAM"]+$rowmp["BILBP"]+$rowmp["BILB"]+$rowmp["BILCP"]+$rowmp["BILC"]+$rowmp["BILD"];
								$gpmpatr2 = gpmpma($jumapatr2,$jumaatr2,$jumamatr2,$jumbpatr2,$jumbatr2,$jumcpatr2,$jumcatr2,$jumdatr2,$jumeatr2,$jumgatr2,$jumambilatr2);
						}//if
							if($capai == "ATR3"){
								$jumdaftaratr3+=(int) $rowmp["BCALON"];
								$jumambilatr3+=(int) $rowmp["AMBIL"];
								$jumthatr3+=(int) $rowmp["TH"];
								$jumapatr3+=(int) $rowmp["BILAP"];
								$jumaatr3+=(int) $rowmp["BILA"];
								$jumamatr3+=(int) $rowmp["BILAM"];
								$jumbpatr3+=(int) $rowmp["BILBP"];
								$jumbatr3+=(int) $rowmp["BILB"];
								$jumcpatr3+=(int) $rowmp["BILCP"];
								$jumcatr3+=(int) $rowmp["BILC"];
								$jumdatr3+=(int) $rowmp["BILD"];
								$jumeatr3+=(int) $rowmp["BILE"];
								$jumgatr3+=(int) $rowmp["BILG"];
								$jumlulusatr3+=(int) $rowmp["BILAP"]+$rowmp["BILA"]+$rowmp["BILAM"]+$rowmp["BILBP"]+$rowmp["BILB"]+$rowmp["BILCP"]+$rowmp["BILC"]+$rowmp["BILD"];
								//echo $jumlulusatr2;
								$gpmpatr3 = gpmpma($jumapatr3,$jumaatr3,$jumamatr3,$jumbpatr2,$jumbatr3,$jumcpatr3,$jumcatr3,$jumdatr3,$jumeatr3,$jumgatr3,$jumambilatr3);
							}//if
						}//while rowmp
					}//if bilmp!0
					//if($capai == "ATR1")
					//	echo "<br>$tingtemp bilambatr1 $jumambilatr1 billulus $jumlulusatr1 atr1 $gpmpatr1";	
				}//while jenpep		
				$bilang = (int) $bil;
				
				if(checkNum($bilang) === TRUE){
				  $col="#CDCDCD";
				}else{
				  $col="";
				}
				echo "  <tr bgcolor='$col'>\n";
				echo "    <td><div align=\"center\">$bil</div></td>\n";
				echo "    <td><div align=\"center\"><a href=hc-mp-ting-bptv.php?datahc=".$kodsek."/".$tahun."/".$tingtemp.">$tingtemp</a></div></td>\n";
				echo "    <td><div align=\"center\">".($bilcalon-$tovTH)."</div></td>\n";
				echo "    <td><div align=\"center\">".($tov1A+$tov2A+$tov3B+$tov4B+$tov5C+$tov6C+$tov7D+$tov8E)."<br>".number_format((($tov1A+$tov2A+$tov3B+$tov4B+$tov5C+$tov6C+$tov7D+$tov8E)/($bilcalon-$tovTH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmptov</div></td>\n";
				echo "    <td><div align=\"center\">".($oti11A+$oti12A+$oti13B+$oti14B+$oti15C+$oti16C+$oti17D+$oti18E)."<br>".number_format((($oti11A+$oti12A+$oti13B+$oti14B+$oti15C+$oti16C+$oti17D+$oti18E)/($bilcalon-$oti1TH)*100),2,'.',',')."</div></td>\n";
				
				echo "    <td><div align=\"center\">$gpmpoti1</div></td>\n";
				echo "    <td><div align=\"center\">$jumambilatr1</div></td>\n";
				echo "    <td><div align=\"center\">$jumlulusatr1<br>".peratus($jumlulusatr1,$bilcalon-$bilanganthATR1)."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpatr1</div></td>\n";
				echo "    <td><div align=\"center\">".($oti21A+$oti22A+$oti23B+$oti24B+$oti25C+$oti26C+$oti27D+$oti28E)."<br>".number_format((($oti21A+$oti22A+$oti23B+$oti24B+$oti25C+$oti26C+$oti27D+$oti28E)/($bilcalon-$oti2TH)*100),2,'.',',')."</div></td>\n";
				
				echo "    <td><div align=\"center\">$gpmpoti2</div></td>\n";
				echo "    <td><div align=\"center\">$jumambilatr2</div></td>\n";
				echo "    <td><div align=\"center\">$jumlulusatr2<br>".peratus($jumlulusatr2,$bilcalon-$bilanganthATR2)."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpatr2</div></td>\n";
				echo "    <td><div align=\"center\">".($oti31A+$oti32A+$oti33B+$oti34B+$oti35C+$oti36C+$oti37D+$oti38E)."<br>".number_format((($oti31A+$oti32A+$oti33B+$oti34B+$oti35C+$oti36C+$oti37D+$oti38E)/($bilcalon-$oti3TH)*100),2,'.',',')."</div></td>\n";
				
				echo "    <td><div align=\"center\">$gpmpoti3</div></td>\n";	
				echo "    <td><div align=\"center\">$jumambilatr3</div></td>\n";
				echo "    <td><div align=\"center\">$jumlulusatr3<br>".peratus($jumlulusatr3,$bilcalon-$bilanganthATR3)."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpatr3</div></td>\n";
				echo "    <td><div align=\"center\">".($etr1A+$etr2A+$etr3B+$etr4B+$etr5C+$etr6C+$etr7D+$etr8E)."<br>".number_format((($etr1A+$etr2A+$etr3B+$etr4B+$etr5C+$etr6C+$etr7D+$etr8E)/($bilcalon-$etrTH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpetr</div></td>\n";
				echo "  </tr>\n";
				$gpmpatr1=0;
				$gpmpatr2=0;
				$gpmpatr3=0;
				break;
		}
		echo "</table>\n";
		echo " <br><br><br><br><br>";
	}
}
?>
</tr></table>
</td>
<?php include 'kaki.php';?>                                                           