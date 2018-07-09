<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php'
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Headcount Sekolah</p>
<?php
function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}
$tahun = $_SESSION['tahun'];
$jpep = $_SESSION['jpep'];
switch ($_SESSION['statussek'])
{
	case "SR":
		$level="SR";
		$theadcount="headcountsr";
		$tmatap="mpsr";
		$tajuk="Darjah";
		$tahap="DARJAH";
		break;
	case "SM" :
		$level="MR";
		$theadcount="headcount";
		$tmatap="mpsmkc";
		$tajuk="Ting";
		$tahap="TING";
		break;
}
$bil=0;
echo "<H2><center>ANALISIS PENCAPAIAN HEADCOUNT SEKOLAH<br>$namasek<br>TAHUN $tahun</center></H2>\n";
echo " <br>";
echo "  <table  width=\"98%\"  align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
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
echo "  <tr bgcolor=\"#FFCC99\">\n";
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

$qting = "SELECT DISTINCT $tahap FROM $theadcount WHERE kodsek='".$_SESSION['kodsek']."' AND tahun='".$_SESSION['tahun']."' ORDER BY $tahap";
$rting = OCIParse($conn_sispa,$qting);
OCIExecute($rting);
$bil=0;
$bilcalon=0;

while (OCIFetch($rting)){
		$tingtemp = OCIResult($rting,"$tahap");//$recordting["$tahap"];
		switch ($tingtemp) {
			case "D1": case "d1": case "D2": case "d2": case "D3": case "d3": case "D4": case "d4": case "D5": case "d5": case "D6": case "d6":
			{
				$analisis="analisis_mpsr amp, mpsr mp";
				$tingdar = "DARJAH";
				$bilgred = "BILA,BILB,BILC,BILD,BILE,BILF,BILTH";
				$minus = "select kod from sub_sr_xambil ";
				$level="SR";
				break;	
			}
			case "P": case "p": case "T1": case "t1": case "T2": case "t2": case "T3": case "t3": 
			{
				$analisis="analisis_mpmr amp, mpsmkc mp";
				$tingdar = "TING";
				$bilgred = "BILA,BILB,BILC,BILD,BILE,BILF,BILTH";
				$minus = "select kod from sub_mr_xambil ";
				$level="MR";
				break;
			}
			case "T4": case "t4": case "T5": case "t5": 
			{
				$analisis="analisis_mpma amp, mpsmkc mp";
				$tingdar = "TING";
				$bilgred = "BILAP,BILA,BILAM,BILBP,BILB,BILCP,BILC,BILD,BILE,BILG,BILTH";
				$minus = "select kod from sub_ma_xambil ";
				$level="MA";
				break;
			}
		}
			
		$qhc = "SELECT * FROM $theadcount WHERE kodsek='".$_SESSION['kodsek']."' AND tahun='".$_SESSION['tahun']."' AND $tahap='$tingtemp' and gtov is not null";
		$rhc = OCIParse($conn_sispa,$qhc);
		OCIExecute($rhc);
		$bilcalon = count_row($qhc);
		
		switch ($tingtemp) 
		{
			case "P": case "p": case "T1": case "t1": case "T2": case "t2": case "T3": case "t3": case "D1": case "d1": case "D2": case "d2": case "D3": case "d3": case "D4": case "d4": case "D5": case "d5": case "D6": case "d6":
			{
				$tovA=$tovB=$tovC=$tovD=$tovE=$tovF=$tovTH=$oti1A=$oti1B=$oti1C=$oti1D=$oti1E=$oti1F=$oti1TH=0;
				$oti2A=$oti2B=$oti2C=$oti2D=$oti2E=$oti2F=$oti2TH=$oti3A=$oti3B=$oti3C=$oti3D=$oti3E=$oti3F=$oti3TH=0;
				$atr1A=$atr1B=$atr1C=$atr1D=$atr1E=$atr1F=$atr1TH=$atr2A=$atr2B=$atr2C=$atr2D=$atr2E=$atr2F=$atr2TH=0;
				$atr3A=$atr3B=$atr3C=$atr3D=$atr3E=$atr3F=$atr3TH=$etrA=$etrB=$etrC=$etrD=$etrE=$etrF=$etrTH=0;
				
				$jumambilatr1=$jumdaftaratr1=$jumthatr1=$jumaatr1=$jumbatr1=$jumcatr1=$jumdatr1=$jumeatr1=$jumfatr1=$jumlulusatr1=0;
				$jumambilatr2=$jumdaftaratr2=$jumthatr2=$jumaatr2=$jumbatr2=$jumcatr2=$jumdatr2=$jumeatr2=$jumfatr2=$jumlulusatr2=0;
				$jumambilatr3=$jumdaftaratr3=$jumthatr3=$jumaatr3=$jumbatr3=$jumcatr3=$jumdatr3=$jumeatr3=$jumfatr3=$jumlulusatr3=0;
				
				$lulustov = $lulusoti1 = $lulusoti2 = $lulusoti3 = $lulusetr = 0 ;
				
				while (OCIFetch($rhc))
				{
					//************** DAPATKAN DATA TOV, OTR1, OTR2, OTR3, ETR ****************//
					if (OCIResult($rhc,"GTOV")=="A")
						$tovA=$tovA+1;
					if (OCIResult($rhc,"GOTR1")=="A")
						$oti1A=$oti1A+1;
					if (OCIResult($rhc,"GOTR2")=="A")
						$oti2A=$oti2A+1;
					if (OCIResult($rhc,"GOTR3")=="A")
						$oti3A=$oti3A+1;
					if (OCIResult($rhc,"GETR")=="A")
						$etrA=$etrA+1;
					if (OCIResult($rhc,"GTOV")=="B")
						$tovB=$tovB+1;
					if (OCIResult($rhc,"GOTR1")=="B")
						$oti1B=$oti1B+1;
					if (OCIResult($rhc,"GOTR2")=="B")
						$oti2B=$oti2B+1;
					if (OCIResult($rhc,"GOTR3")=="B")
						$oti3B=$oti3B+1;
					if (OCIResult($rhc,"GETR")=="B")
						$etrB=$etrB+1;
					if (OCIResult($rhc,"GTOV")=="C")
						$tovC=$tovC+1;												
					if (OCIResult($rhc,"GOTR1")=="C")
						$oti1C=$oti1C+1;						
					if (OCIResult($rhc,"GOTR2")=="C")
						$oti2C=$oti2C+1;						
					if (OCIResult($rhc,"GOTR3")=="C")
						$oti3C=$oti3C+1;						
					if (OCIResult($rhc,"GETR")=="C")
						$etrC=$etrC+1;							
					if (OCIResult($rhc,"GTOV")=="D")
						$tovD=$tovD+1;												
					if (OCIResult($rhc,"GOTR1")=="D")
						$oti1D=$oti1D+1;						
					if (OCIResult($rhc,"GOTR2")=="D")
						$oti2D=$oti2D+1;						
					if (OCIResult($rhc,"GOTR3")=="D")
						$oti3D=$oti3D+1;						
					if (OCIResult($rhc,"GETR")=="D")
						$etrD=$etrD+1;											
					if (OCIResult($rhc,"GTOV")=="E")
						$tovE=$tovE+1;												
					if (OCIResult($rhc,"GOTR1")=="E")
						$oti1E=$oti1E+1;						
					if (OCIResult($rhc,"GOTR2")=="E")
						$oti2E=$oti2E+1;
					if (OCIResult($rhc,"GOTR3")=="E")
						$oti3E=$oti3E+1;
					if (OCIResult($rhc,"GETR")=="E")
						$etrE=$etrE+1;
					if (OCIResult($rhc,"GTOV")=="F")
						$tovF=$tovF+1;												
					if (OCIResult($rhc,"GOTR1")=="F")
						$oti1F=$oti1F+1;						
					if (OCIResult($rhc,"GOTR2")=="F")
						$oti2F=$oti2F+1;
					if (OCIResult($rhc,"GOTR3")=="F")
						$oti3F=$oti3F+1;
					if (OCIResult($rhc,"GETR")=="F")
						$etrF=$etrF+1;
					if (OCIResult($rhc,"GTOV")=="TH")
						$tovTH=$tovTH+1;
					if (OCIResult($rhc,"GOTR1")=="TH")
						$oti1TH=$oti1TH+1;
					if (OCIResult($rhc,"GOTR2")=="TH")
						$oti2TH=$oti2TH+1;
					if (OCIResult($rhc,"GOTR3")=="TH")
						$oti3TH=$oti3TH+1;
					if (OCIResult($rhc,"GETR")=="TH")
						$etrTH=$etrTH+1;
				}
				if($tahun<=2014){
					$gpmptov=number_format((($tovA*1)+($tovB*2)+($tovC*3)+($tovD*4)+($tovE*5)+($tovTH*5))/($bilcalon-$tovTH),2,'.',',');
					$gpmpoti1=number_format((($oti1A*1)+($oti1B*2)+($oti1C*3)+($oti1D*4)+($oti1E*5)+($oti1TH*5))/($bilcalon-$oti1TH),2,'.',',');
					$gpmpoti2=number_format((($oti2A*1)+($oti2B*2)+($oti2C*3)+($oti2D*4)+($oti2E*5)+($oti2TH*5))/($bilcalon-$oti2TH),2,'.',',');
					$gpmpoti3=number_format((($oti3A*1)+($oti3B*2)+($oti3C*3)+($oti3D*4)+($oti3E*5)+($oti3TH*5))/($bilcalon-$oti3TH),2,'.',',');
					$gpmpetr=number_format((($etrA*1)+($etrB*2)+($etrC*3)+($etrD*4)+($etrE*5)+($etrTH*5))/($bilcalon-$etrTH),2,'.',',');
				}elseif($tahun==2015){
					if($tingtemp=='D6'){
						$gpmptov=number_format((($tovA*1)+($tovB*2)+($tovC*3)+($tovD*4)+($tovE*5)+($tovTH*5))/($bilcalon-$tovTH),2,'.',',');
						$gpmpoti1=number_format((($oti1A*1)+($oti1B*2)+($oti1C*3)+($oti1D*4)+($oti1E*5)+($oti1TH*5))/($bilcalon-$oti1TH),2,'.',',');
						$gpmpoti2=number_format((($oti2A*1)+($oti2B*2)+($oti2C*3)+($oti2D*4)+($oti2E*5)+($oti2TH*5))/($bilcalon-$oti2TH),2,'.',',');
						$gpmpoti3=number_format((($oti3A*1)+($oti3B*2)+($oti3C*3)+($oti3D*4)+($oti3E*5)+($oti3TH*5))/($bilcalon-$oti3TH),2,'.',',');
						$gpmpetr=number_format((($etrA*1)+($etrB*2)+($etrC*3)+($etrD*4)+($etrE*5)+($etrTH*5))/($bilcalon-$etrTH),2,'.',',');	
					}else{//D1-D5 T1-T3
					$gpmptov=number_format((($tovA*1)+($tovB*2)+($tovC*3)+($tovD*4)+($tovE*5)+($tovF*6)+($tovTH*6))/($bilcalon-$tovTH),2,'.',',');
					$gpmpoti1=number_format((($oti1A*1)+($oti1B*2)+($oti1C*3)+($oti1D*4)+($oti1E*5)+($oti1F*6)+($oti1TH*6))/($bilcalon-$oti1TH),2,'.',',');
					$gpmpoti2=number_format((($oti2A*1)+($oti2B*2)+($oti2C*3)+($oti2D*4)+($oti2E*5)+($oti2F*6)+($oti2TH*6))/($bilcalon-$oti2TH),2,'.',',');
					$gpmpoti3=number_format((($oti3A*1)+($oti3B*2)+($oti3C*3)+($oti3D*4)+($oti3E*5)+($oti3F*6)+($oti3TH*6))/($bilcalon-$oti3TH),2,'.',',');
					$gpmpetr=number_format((($etrA*1)+($etrB*2)+($etrC*3)+($etrD*4)+($etrE*5)+($etrF*6)+($etrTH*6))/($bilcalon-$etrTH),2,'.',',');											
					}					
				}else{//>2016 D1-D6 T1-T3
					if($level=="MR"){
					$gpmptov=number_format((($tovA*1)+($tovB*2)+($tovC*3)+($tovD*4)+($tovE*5)+($tovF*6)+($tovTH*6))/($bilcalon-$tovTH),2,'.',',');
					$gpmpoti1=number_format((($oti1A*1)+($oti1B*2)+($oti1C*3)+($oti1D*4)+($oti1E*5)+($oti1F*6)+($oti1TH*6))/($bilcalon-$oti1TH),2,'.',',');
					$gpmpoti2=number_format((($oti2A*1)+($oti2B*2)+($oti2C*3)+($oti2D*4)+($oti2E*5)+($oti2F*6)+($oti2TH*6))/($bilcalon-$oti2TH),2,'.',',');
					$gpmpoti3=number_format((($oti3A*1)+($oti3B*2)+($oti3C*3)+($oti3D*4)+($oti3E*5)+($oti3F*6)+($oti3TH*6))/($bilcalon-$oti3TH),2,'.',',');
					$gpmpetr=number_format((($etrA*1)+($etrB*2)+($etrC*3)+($etrD*4)+($etrE*5)+($etrF*6)+($etrTH*6))/($bilcalon-$etrTH),2,'.',',');					
					}else{
						$gpmptov=number_format((($tovA*1)+($tovB*2)+($tovC*3)+($tovD*4)+($tovE*5)+($tovTH*5))/($bilcalon-$tovTH),2,'.',',');
						$gpmpoti1=number_format((($oti1A*1)+($oti1B*2)+($oti1C*3)+($oti1D*4)+($oti1E*5)+($oti1TH*5))/($bilcalon-$oti1TH),2,'.',',');
						$gpmpoti2=number_format((($oti2A*1)+($oti2B*2)+($oti2C*3)+($oti2D*4)+($oti2E*5)+($oti2TH*5))/($bilcalon-$oti2TH),2,'.',',');
						$gpmpoti3=number_format((($oti3A*1)+($oti3B*2)+($oti3C*3)+($oti3D*4)+($oti3E*5)+($oti3TH*5))/($bilcalon-$oti3TH),2,'.',',');
						$gpmpetr=number_format((($etrA*1)+($etrB*2)+($etrC*3)+($etrD*4)+($etrE*5)+($etrTH*5))/($bilcalon-$etrTH),2,'.',',');	
					}
				}
			
				$bil=$bil+1;
				//echo $tingtemp."<br>";
				$sql_hc = "select jenpep,capai from tentu_hc where tingpep='$tingtemp' and tahunpep='$tahun' and capai<>'TOV'";
				//if($kodsek='YEE6402')
					//echo "$sql_hc <br>";
				$res_hc = oci_parse($conn_sispa,$sql_hc);
				oci_execute($res_hc);
				
				while($data_hc = oci_fetch_array($res_hc)){
					$jenpep = $data_hc["JENPEP"];	//jenis pep
					$capai = $data_hc["CAPAI"];//atr1/atr2/atr3
					//echo "<br>".$capai;
					$q_mp = "SELECT amp.kodmp, SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile, SUM(F) bilf FROM $analisis WHERE amp.tahun='$tahun' AND amp.kodsek='".$_SESSION['kodsek']."' AND amp.jpep='$jenpep' AND  amp.$tingdar='$tingtemp' AND amp.kodmp=mp.kod Group BY mp.susun,amp.kodmp";//and amp.kodmp not in ($minus) 
					//if($kodsek='YEE6402')
						//echo "$q_mp <br>";
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
								$jumfatr1+=(int) $rowmp["BILF"];
								if($tahun<=2014){
									if($level=="MR")
										$jumlulusatr1+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
									else //SR
										$jumlulusatr1+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"];
									$gpmpatr1 = gpmpmrsr($jumaatr1,$jumbatr1,$jumcatr1,$jumdatr1,$jumeatr1,$jumambilatr1);
								}elseif($tahun==2015){//2015
									if($tingtemp=='D6'){
										$jumlulusatr1+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"];	
										$gpmpatr1 = gpmpmrsr($jumaatr1,$jumbatr1,$jumcatr1,$jumdatr1,$jumeatr1,$jumambilatr1);
									}else{//D1-D5 T1-T3
										$jumlulusatr1+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
										$gpmpatr1 = gpmpmrsr_baru($jumaatr1,$jumbatr1,$jumcatr1,$jumdatr1,$jumeatr1,$jumfatr1,$jumambilatr1);
									}
								}else{//2016 D1-D6 T1-T3
									if($level=="MR")
										$jumlulusatr1+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
									else
										$jumlulusatr1+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
									$gpmpatr1 = gpmpmrsr_baru($jumaatr1,$jumbatr1,$jumcatr1,$jumdatr1,$jumeatr1,$jumfatr1,$jumambilatr1);
								}//if($tahun<=2014){
							}//if($capai == "ATR1"){
							 if($capai == "ATR2"){
								$jumdaftaratr2+=(int) $rowmp["BCALON"];
								$jumambilatr2+=(int) $rowmp["AMBIL"];
								$jumthatr2+=(int) $rowmp["TH"];
								$jumaatr2+=(int) $rowmp["BILA"];
								$jumbatr2+=(int) $rowmp["BILB"];
								$jumcatr2+=(int) $rowmp["BILC"];
								$jumdatr2+=(int) $rowmp["BILD"];
								$jumeatr2+=(int) $rowmp["BILE"];
								$jumfatr2+=(int) $rowmp["BILF"];
								if($tahun<=2014){
									if($level=="MR")
										$jumlulusatr2+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
									else //SR
										$jumlulusatr2+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"];
									$gpmpatr2 = gpmpmrsr($jumaatr2,$jumbatr2,$jumcatr2,$jumdatr2,$jumeatr2,$jumambilatr2);
								}elseif($tahun==2015){
									if($tingtemp=='D6'){
										$jumlulusatr2+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"];	
										$gpmpatr2 = gpmpmrsr($jumaatr2,$jumbatr2,$jumcatr2,$jumdatr2,$jumeatr2,$jumambilatr2);
									}else{//D1-D5 T1-T3
										$jumlulusatr2+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
										$gpmpatr2 = gpmpmrsr_baru($jumaatr2,$jumbatr2,$jumcatr2,$jumdatr2,$jumeatr2,$jumfatr2,$jumambilatr2);
									}
								}else{//D1-D6 T1-T3
									if($level=="MR")
										$jumlulusatr2+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
									else
										$jumlulusatr2+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
									$gpmpatr2 = gpmpmrsr_baru($jumaatr2,$jumbatr2,$jumcatr2,$jumdatr2,$jumeatr2,$jumfatr2,$jumambilatr2);
								}
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
								$jumfatr3+=(int) $rowmp["BILF"];
								if($tahun<=2014){
									if($level=="MR")
										$jumlulusatr3+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
									else //SR
										$jumlulusatr3+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"];
									$gpmpatr3 = gpmpmrsr($jumaatr3,$jumbatr3,$jumcatr3,$jumdatr3,$jumeatr3,$jumambilatr3);
								}elseif($tahun==2015){
									if($tingtemp=='D6'){
										$jumlulusatr3+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"];	
										$gpmpatr3 = gpmpmrsr($jumaatr3,$jumbatr3,$jumcatr3,$jumdatr3,$jumeatr3,$jumambilatr3);
									}else{//D1-D5 T1-T3
										$jumlulusatr3+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
										$gpmpatr3 = gpmpmrsr_baru($jumaatr3,$jumbatr3,$jumcatr3,$jumdatr3,$jumeatr3,$jumfatr3,$jumambilatr3);
									}
								}else{//D1-D6 T1-T3
									if($level=="MR")
										$jumlulusatr3+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"]+$rowmp["BILE"];
									else
										$jumlulusatr3+=(int) $rowmp["BILA"]+$rowmp["BILB"]+$rowmp["BILC"]+$rowmp["BILD"];
									$gpmpatr3 = gpmpmrsr_baru($jumaatr3,$jumbatr3,$jumcatr3,$jumdatr3,$jumeatr3,$jumfatr3,$jumambilatr3);
								}
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
				
				switch ($tingtemp) //switch 2
				{
					case "D1": case "d1": case "D2": case "d2": case "D3": case "d3": case "D4": case "d4": case "D5": case "d5": case "D6": case "d6":
					{
						if($tahun<=2014){
							$lulustov = $tovA + $tovB + $tovC;
							$lulusoti1 = $oti1A + $oti1B + $oti1C;
							$lulusoti2 = $oti2A + $oti2B + $oti2C;
							$lulusoti3 = $oti3A + $oti3B + $oti3C;
							$lulusetr = $etrA + $etrB + $etrC;
						}elseif($tahun==2015){
							if($tingtemp=='D6'){
								$lulustov = $tovA + $tovB + $tovC;
								$lulusoti1 = $oti1A + $oti1B + $oti1C;
								$lulusoti2 = $oti2A + $oti2B + $oti2C;
								$lulusoti3 = $oti3A + $oti3B + $oti3C;
								$lulusetr = $etrA + $etrB + $etrC;
							}else{
								$lulustov = $tovA + $tovB + $tovC + $tovD + $tovE;
								$lulusoti1 = $oti1A + $oti1B + $oti1C + $oti1D + $oti1E;
								$lulusoti2 = $oti2A + $oti2B + $oti2C + $oti2D + $oti2E;
								$lulusoti3 = $oti3A + $oti3B + $oti3C + $oti3D + $oti3E;
								$lulusetr = $etrA + $etrB + $etrC + $etrD + $etrE;
							}
						}else{//2016 D1-D6
							$lulustov = $tovA + $tovB + $tovC + $tovD;
							$lulusoti1 = $oti1A + $oti1B + $oti1C + $oti1D;
							$lulusoti2 = $oti2A + $oti2B + $oti2C + $oti2D;
							$lulusoti3 = $oti3A + $oti3B + $oti3C + $oti3D;
							$lulusetr = $etrA + $etrB + $etrC + $etrD;
						}
						echo "<tr bgcolor='$col'>\n";
						echo "<td><div align=\"center\">$bil</div></td>\n";
						echo "<td><div align=\"center\"><a href=hc-mp-ting.php?datahc=".$tingtemp.">$tingtemp</a></div></td>\n";
						echo "<td><div align=\"center\">".($bilcalon-$tovTH)."</div></td>\n";
						echo "<td><div align=\"center\">".($lulustov)."<br>".number_format((($lulustov)/($bilcalon-$tovTH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmptov</div></td>\n";
						echo "<td><div align=\"center\">".($lulusoti1)."<br>".number_format((($lulusoti1)/($bilcalon-$oti1TH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpoti1</div></td>\n";
						echo "<td><div align=\"center\">$jumambilatr1</div></td>\n";
						echo "<td><div align=\"center\">$jumlulusatr1<br>".peratus($jumlulusatr1,$bilcalon-$jumthatr1)."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpatr1</div></td>\n";
						echo "<td><div align=\"center\">".($lulusoti2)."<br>".number_format((($lulusoti2)/($bilcalon-$oti2TH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpoti2</div></td>\n";
						echo "<td><div align=\"center\">$jumambilatr2</div></td>\n";
						echo "<td><div align=\"center\">$jumlulusatr2<br>".peratus($jumlulusatr2,$bilcalon-$jumthatr2)."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpatr2</div></td>\n";
						echo "<td><div align=\"center\">".($lulusoti3)."<br>".number_format((($lulusoti3)/($bilcalon-$oti3TH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpoti3</div></td>\n";
						echo "<td><div align=\"center\">$jumambilatr3</div></td>\n";
						echo "<td><div align=\"center\">$jumlulusatr3<br>".peratus($jumlulusatr3,$bilcalon-$jumthatr3)."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpatr3</div></td>\n";
						echo "<td><div align=\"center\">".($lulusetr)."<br>".number_format((($lulusetr)/($bilcalon-$etrTH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpetr</div></td>\n";
						echo "</tr>\n";
						$gpmpatr1=0;
						$gpmpatr2=0;
						$gpmpatr3=0;
						break;	
					}
					case "P": case "p": case "T1": case "t1": case "T2": case "t2": case "T3": case "t3":
					{
						if($tahun<=2014){
							$lulustov = $tovA + $tovB + $tovC + $tovD;
							$lulusoti1 = $oti1A + $oti1B + $oti1C + $oti1D;
							$lulusoti2 = $oti2A + $oti2B + $oti2C + $oti2D;
							$lulusoti3 = $oti3A + $oti3B + $oti3C + $oti3D;
							$lulusetr = $etrA + $etrB + $etrC + $etrD;
						}elseif($tahun>=2015){
							$lulustov = $tovA + $tovB + $tovC + $tovD + $tovE;
							$lulusoti1 = $oti1A + $oti1B + $oti1C + $oti1D + $oti1E;
							$lulusoti2 = $oti2A + $oti2B + $oti2C + $oti2D + $oti2E;
							$lulusoti3 = $oti3A + $oti3B + $oti3C + $oti3D + $oti3E;
							$lulusetr = $etrA + $etrB + $etrC + $etrD + $etrE;
						}
						echo "<tr bgcolor='$col'>\n";
						echo "<td><div align=\"center\">$bil</div></td>\n";
						echo "<td><div align=\"center\"><a href=hc-mp-ting.php?datahc=".$tingtemp.">$tingtemp</a></div></td>\n";
						echo "<td><div align=\"center\">".($bilcalon-$tovTH)."</div></td>\n";//($bilcalon-$tovTH)
						echo "<td><div align=\"center\">".($lulustov)."<br>".number_format((($lulustov)/($bilcalon-$tovTH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmptov</div></td>\n";
						echo "<td><div align=\"center\">".($lulusoti1)."<br>".number_format((($lulusoti1)/($bilcalon-$oti1TH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpoti1</div></td>\n";
						echo "<td><div align=\"center\">$jumambilatr1</div></td>\n";
						echo "<td><div align=\"center\">$jumlulusatr1<br>".peratus($jumlulusatr1,$bilcalon-$jumthatr1)."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpatr1</div></td>\n";
						echo "<td><div align=\"center\">".($lulusoti2)."<br>".number_format((($lulusoti2)/($bilcalon-$oti2TH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpoti2</div></td>\n";
						echo "<td><div align=\"center\">$jumambilatr2</div></td>\n";
						echo "<td><div align=\"center\">$jumlulusatr2<br>".peratus($jumlulusatr2,$bilcalon-$jumthatr2)."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpatr2</div></td>\n";
						echo "<td><div align=\"center\">".($lulusoti3)."<br>".number_format((($lulusoti3)/($bilcalon-$oti3TH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpoti3</div></td>\n";
						echo "<td><div align=\"center\">$jumambilatr3</div></td>\n";
						echo "<td><div align=\"center\">$jumlulusatr3<br>".peratus($jumlulusatr3,$bilcalon-$jumthatr3)."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpatr3</div></td>\n";
						echo "<td><div align=\"center\">".($lulusetr)."<br>".number_format((($lulusetr)/($bilcalon-$etrTH)*100),2,'.',',')."</div></td>\n";
						echo "<td><div align=\"center\">$gpmpetr</div></td>\n";
						echo "</tr>\n";
						$gpmpatr1=0;
						$gpmpatr2=0;
						$gpmpatr3=0;
						break;
					}
				}//switch 2
				break;
			}
			
			case "T4": case "t4": case "T5": case "t5":
			{	
				//$tov1A=$tov2A=$tov3B=$tov4B=$tov5C=$tov6C=$tov7D=$tov8E=$tov9G=$tovTH=0;
				$tovAP=$tovA=$tovAM=$tovBP=$tovB=$tovCP=$tovC=$tovD=$tovE=$tovG=$tovTH=0;
				//$oti11A=$oti12A=$oti13B=$oti14B=$oti15C=$oti16C=$oti17D=$oti18E=$oti19G=$oti1TH=0;
				//$oti21A=$oti22A=$oti23B=$oti24B=$oti25C=$oti26C=$oti27D=$oti28E=$oti29G=$oti2TH=0;
				//$oti31A=$oti32A=$oti33B=$oti34B=$oti35C=$oti36C=$oti37D=$oti38E=$oti39G=$oti3TH=0;
				//$etr1A=$etr2A=$etr3B=$etr4B=$etr5C=$etr6C=$etr7D=$etr8E=$etr9G=$etrTH=0;
				$oti1AP=$oti1A=$oti1AM=$oti1BP=$oti1B=$oti1CP=$oti1C=$oti1D=$oti1E=$oti1G=$oti1TH=0;
				$oti2AP=$oti2A=$oti2AM=$oti2BP=$oti2B=$oti2CP=$oti2C=$oti2D=$oti2E=$oti2G=$oti2TH=0;
				$oti3AP=$oti3A=$oti3AM=$oti3BP=$oti3B=$oti3CP=$oti3C=$oti3D=$oti3E=$oti3G=$oti3TH=0;
				$etrAP=$etrA=$etrAM=$etrBP=$etrB=$etrCP=$etrC=$etrD=$etrE=$etrG=$etrTH=0;
				
				$atr11A=$atr12A=$atr13B=$atr14B=$atr15C=$atr16C=$atr17D=$atr18E=$atr19G=$atr1TH=0;
				$atr21A=$atr22A=$atr23B=$atr24B=$atr25C=$atr26C=$atr27D=$atr28E=$atr29G=$atr2TH=0;
				$atr31A=$atr32A=$atr33B=$atr34B=$atr35C=$atr36C=$atr37D=$atr38E=$atr39G=$atr3TH=0;
				//if($kodsek=='CHA8001')
					//echo $qhc."<br>";
					
				while (OCIFetch($rhc))
				{
					switch (OCIResult($rhc,"GTOV"))
					{
						case 'A+' : $tovAP = $tovAP + 1; break;
						case 'A' : $tovA = $tovA + 1; break;
						case 'A-' : $tovAM = $tovAM + 1; break;
						case 'B+' : $tovBP = $tovBP + 1; break;
						case 'B' : $tovB = $tovB + 1; break;
						case 'C+' : $tovCP = $tovCP + 1; break;
						case 'C' : $tovC = $tovC + 1; break;
						case 'D' : $tovD = $tovD + 1; break;
						case 'E' : $tovE = $tovE + 1; break;
						case 'G' : $tovG = $tovG + 1; break;
						case 'TH' : $tovTH = $tovTH + 1; break;
					}
					
					switch (OCIResult($rhc,"GOTR1"))
					{
						case 'A+' : $oti1AP = $oti1AP + 1; break;
						case 'A' : $oti1A = $oti1A + 1; break;
						case 'A-' : $oti1AM = $oti1AM + 1; break;
						case 'B+' : $oti1BP = $oti1BP + 1; break;
						case 'B' : $oti1B = $oti1B + 1; break;
						case 'C+' : $oti1CP = $oti1CP + 1; break;
						case 'C' : $oti1C = $oti1C + 1; break;
						case 'D' : $oti1D = $oti1D + 1; break;
						case 'E' : $oti1E = $oti1E + 1; break;
						case 'G' : $oti1G = $oti1G + 1; break;
						case 'TH' : $oti1TH = $oti1TH + 1; break;
					}
					
					switch (OCIResult($rhc,"GOTR2"))
					{
						case 'A+' : $oti2AP = $oti2AP + 1; break;
						case 'A' : $oti2A = $oti2A + 1; break;
						case 'A-' : $oti2AM = $oti2AM + 1; break;
						case 'B+' : $oti2BP = $oti2BP + 1; break;
						case 'B' : $oti2B = $oti2B + 1; break;
						case 'C+' : $oti2CP = $oti2CP + 1; break;
						case 'C' : $oti2C = $oti2C + 1; break;
						case 'D' : $oti2D = $oti2D + 1; break;
						case 'E' : $oti2E = $oti2E + 1; break;
						case 'G' : $oti2G = $oti2G + 1; break;
						case 'TH' : $oti2TH = $oti2TH + 1; break;
					}
					
					switch (OCIResult($rhc,"GOTR3"))
					{
						case 'A+' : $oti3AP = $oti3AP + 1; break;
						case 'A' : $oti3A = $oti3A + 1; break;
						case 'A-' : $oti3AM = $oti3AM + 1; break;
						case 'B+' : $oti3BP = $oti3BP + 1; break;
						case 'B' : $oti3B = $oti3B + 1; break;
						case 'C+' : $oti3CP = $oti3CP + 1; break;
						case 'C' : $oti3C = $oti3C + 1; break;
						case 'D' : $oti3D = $oti3D + 1; break;
						case 'E' : $oti3E = $oti3E + 1; break;
						case 'G' : $oti3G = $oti3G + 1; break;
						case 'TH' : $oti3TH = $oti3TH + 1; break;
					}
					
					switch (OCIResult($rhc,"GETR"))
					{
						case 'A+' : $etrAP = $etrAP + 1; break;
						case 'A' : $etrA = $etrA + 1; break;
						case 'A-' : $etrAM = $etrAM + 1; break;
						case 'B+' : $etrBP = $etrBP + 1; break;
						case 'B' : $etrB = $etrB + 1; break;
						case 'C+' : $etrCP = $etrCP + 1; break;
						case 'C' : $etrC = $etrC + 1; break;
						case 'D' : $etrD = $etrD + 1; break;
						case 'E' : $etrE = $etrE + 1; break;
						case 'G' : $etrG = $etrG + 1; break;
						case 'TH' : $etrTH = $etrTH + 1; break;
					}
				}

				$gpmptov=gpmpma($tovAP, $tovA, $tovAM, $tovBP, $tovB, $tovCP, $tovC, $tovD, $tovE, $tovG, ($bilcalon-$tovTH));
				$gpmpoti1=gpmpma($oti1AP, $oti1A, $oti1AM, $oti1BP, $oti1B, $oti1CP, $oti1C, $oti1D, $oti1E, $oti1G, ($bilcalon-$oti1TH));
				$gpmpoti2=gpmpma($oti2AP, $oti2A, $oti2AM, $oti2BP, $oti2B, $oti2CP, $oti2C, $oti2D, $oti2E, $oti2G, ($bilcalon-$oti2TH));
				$gpmpoti3=gpmpma($oti3AP, $oti3A, $oti3AM, $oti3BP, $oti3B, $oti3CP, $oti3C, $oti3D, $oti3E, $oti3G, ($bilcalon-$oti3TH));
				$gpmpetr=gpmpma($etrAP, $etrA, $etrAM, $etrBP, $etrB, $etrCP, $etrC, $etrD, $etrE, $etrG, ($bilcalon-$etrTH));
				
				$bil=$bil+1;
				  
				//echo $tingtemp."<br>";
				$sql_ha = "select jenpep,capai from tentu_hc where tingpep='$tingtemp' and tahunpep='$tahun' and capai<>'TOV'";
				//if($kodsek=='CHA8001')
					//echo $sql_ha."<br>";
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
					 FROM analisis_mpma amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$_SESSION['kodsek']."' AND amp.jpep='$jenpep' 
					 AND  amp.$tingdar='$tingtemp' AND amp.kodmp=mp.kod and amp.kodmp not in ($minus) Group BY mp.susun,amp.kodmp";
					//if($kodsek=='CHA8001')
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
						//echo "<br>$tingtemp bilambatr1 $jumambilatr1 billulus $jumlulusatr1 atr1 $gpmpatr1";	
				}//while jenpep		
				$bilang = (int) $bil;
				
				if(checkNum($bilang) === TRUE){
				  $col="#CDCDCD";
				}else{
				  $col="";
				}
				//if($kodsek=='CHA8001')
					//echo "<br>$tov1A + $tov2A + $tov3B + $tov4B + $tov5C + $tov6C + $tov7D + $tov8E<br>";
				echo "  <tr bgcolor='$col'>\n";
				echo "    <td><div align=\"center\">$bil</div></td>\n";
				echo "    <td><div align=\"center\"><a href=hc-mp-ting.php?datahc=".$tingtemp.">$tingtemp</a></div></td>\n";
				echo "    <td><div align=\"center\">".($bilcalon-$tovTH)."</div></td>\n";
				//echo "    <td><div align=\"center\">".($tov1A+$tov2A+$tov3B+$tov4B+$tov5C+$tov6C+$tov7D+$tov8E)."<br>".number_format((($tov1A+$tov2A+$tov3B+$tov4B+$tov5C+$tov6C+$tov7D+$tov8E)/($bilcalon-$tovTH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">".($tovAP+$tovA+$tovAM+$tovBP+$tovB+$tovCP+$tovC+$tovD+$tovE)."<br>".number_format((($tovAP+$tovA+$tovAM+$tovBP+$tovB+$tovCP+$tovC+$tovD+$tovE)/($bilcalon-$tovTH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmptov</div></td>\n";
				//echo "    <td><div align=\"center\">".($oti11A+$oti12A+$oti13B+$oti14B+$oti15C+$oti16C+$oti17D+$oti18E)."<br>".number_format((($oti11A+$oti12A+$oti13B+$oti14B+$oti15C+$oti16C+$oti17D+$oti18E)/($bilcalon-$oti1TH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">".($oti1AP+$oti1A+$oti1AM+$oti1BP+$oti1B+$oti1CP+$oti1C+$oti1D+$oti1E)."<br>".number_format((($oti1AP+$oti1A+$oti1AM+$oti1BP+$oti1B+$oti1CP+$oti1C+$oti1D+$oti1E)/($bilcalon-$oti1TH)*100),2,'.',',')."</div></td>\n";
				
				
				echo "    <td><div align=\"center\">$gpmpoti1</div></td>\n";
				echo "    <td><div align=\"center\">$jumambilatr1</div></td>\n";
				echo "    <td><div align=\"center\">$jumlulusatr1<br>".peratus($jumlulusatr1,$bilcalon-$jumthatr1)."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpatr1</div></td>\n";
				//echo "    <td><div align=\"center\">".($oti21A+$oti22A+$oti23B+$oti24B+$oti25C+$oti26C+$oti27D+$oti28E)."<br>".number_format((($oti21A+$oti22A+$oti23B+$oti24B+$oti25C+$oti26C+$oti27D+$oti28E)/($bilcalon-$oti2TH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">".($oti2AP+$oti2A+$oti2AM+$oti2BP+$oti2B+$oti2CP+$oti2C+$oti2D+$oti2E)."<br>".number_format((($oti2AP+$oti2A+$oti2AM+$oti2BP+$oti2B+$oti2CP+$oti2C+$oti2D+$oti2E)/($bilcalon-$oti2TH)*100),2,'.',',')."</div></td>\n";
				
				echo "    <td><div align=\"center\">$gpmpoti2</div></td>\n";
				echo "    <td><div align=\"center\">$jumambilatr2</div></td>\n";
				echo "    <td><div align=\"center\">$jumlulusatr2<br>".peratus($jumlulusatr2,$bilcalon-$jumthatr2)."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpatr2</div></td>\n";
				echo "    <td><div align=\"center\">".($oti3AP+$oti3A+$oti3AM+$oti3BP+$oti3B+$oti3CP+$oti3C+$oti3D+$oti3E)."<br>".number_format((($oti3AP+$oti3A+$oti3AM+$oti3BP+$oti3B+$oti3CP+$oti3C+$oti3D+$oti3E)/($bilcalon-$oti3TH)*100),2,'.',',')."</div></td>\n";
				
				echo "    <td><div align=\"center\">$gpmpoti3</div></td>\n";	
				echo "    <td><div align=\"center\">$jumambilatr3</div></td>\n";
				echo "    <td><div align=\"center\">$jumlulusatr3<br>".peratus($jumlulusatr3,$bilcalon-$jumthatr3)."</div></td>\n";
				echo "    <td><div align=\"center\">$gpmpatr3</div></td>\n";
				//echo "    <td><div align=\"center\">".($etr1A+$etr2A+$etr3B+$etr4B+$etr5C+$etr6C+$etr7D+$etr8E)."<br>".number_format((($etr1A+$etr2A+$etr3B+$etr4B+$etr5C+$etr6C+$etr7D+$etr8E)/($bilcalon-$etrTH)*100),2,'.',',')."</div></td>\n";
				echo "    <td><div align=\"center\">".($etrAP+$etrA+$etrAM+$etrBP+$etrB+$etrCP+$etrC+$etrD+$etrE)."<br>".number_format((($etrAP+$etrA+$etrAM+$etrBP+$etrB+$etrCP+$etrC+$etrD+$etrE)/($bilcalon-$etrTH)*100),2,'.',',')."</div></td>\n";
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