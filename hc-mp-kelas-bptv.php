<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php'
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Headcount Kelas (BPTV)</p>
<?php
$datahc = $_GET['datahc'];
list ($kodsek, $tahun ,$ting, $mp)=split('[/]', $datahc);

switch ($_SESSION['statussek'])
{
	case "SM" :
		$level="MR";
		$tmarkah = "markah_pelajar";
		//$theadcount="headcount";
		//$tmatap="mpsmkc";
		//$tajuk="TINGKATAN";
		//$tahap="TING";
		$penilaiansm="SEM";
		break;
}


$gdata = "SELECT * FROM tsekolah WHERE kodsek='$kodsek' and kodjenissekolah in ('203','303')";
//echo $gdata;
$rdata= oci_parse($conn_sispa,$gdata);
oci_execute($rdata);
$resultdata = oci_fetch_array($rdata);
$namasek=$resultdata['NAMASEK'];

$qmp = "SELECT * FROM mpsmkc WHERE kod='$mp'";
//echo $qmp;
$rmp= oci_parse($conn_sispa,$qmp);
oci_execute($rmp);
$resultmp = oci_fetch_array($rmp);
$tempmp=$resultmp['MP'];

$qryatr = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='$tahun' ORDER BY capai");
//echo "SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' ORDER BY capai";
oci_execute($qryatr);
while ($rowatr = oci_fetch_array($qryatr))
{
	switch ($rowatr[CAPAI])
	{
		case "ATR1" : 
			$jpepatr1=$rowatr['JENPEP']; $tahunatr1=$rowatr['TAHUNTOV']; $tingatr1=$rowatr['tingtov'];
			break;
		case "ATR2" : 
			$jpepatr2=$rowatr['JENPEP']; $tahunatr2=$rowatr['TAHUNTOV']; $tingatr2=$rowatr['tingtov'];
			break;
		case "ATR3" : 
			$jpepatr3=$rowatr['JENPEP']; $tahunatr3=$rowatr['TAHUNTOV']; $tingatr3=$rowatr['tingtov'];
			break;
	}
}
// "$jpepatr1 |  $tahunatr1 | $tingatr1<br>"; 
//echo "$jpepatr2 |  $tahunatr2 | $tingatr2<br>"; 
//echo "$jpepatr3 |  $tahunatr3 | $tingatr3<br>"; 
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<H2><center>ANALISIS PENCAPAIAN HEADCOUNT MATA PELAJARAN<br>TAHUN $tahun</center></H2>\n";
echo " <br>";
echo "  <table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo " <td>SEKOLAH : $namasek</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo " <td>TING : $ting</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo " <td>MATA PELAJARAN : $tempmp</td>\n";
echo "  </tr>\n";
echo "</table>";
echo " <br>";
echo "  <table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo "    <td rowspan=\"2\">Bil\n";
echo "    <div align=\"center\"></div></td>\n";
echo "    <td rowspan=\"2\">Nama Kelas\n";
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
echo "    <td><div align=\"center\">Bil. Ambil</div></td>\n";
echo "    <td><div align=\"center\">Bil.Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPK</div></td>\n";
echo "    <td><div align=\"center\">Bil.Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPK</div></td>\n";
echo "    <td><div align=\"center\">Bil. Ambil</div></td>\n";
echo "    <td><div align=\"center\">Bil.Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPK</div></td>\n";
echo "    <td><div align=\"center\">Bil.Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPK</div></td>\n";
echo "    <td><div align=\"center\">Bil. Ambil</div></td>\n";
echo "    <td><div align=\"center\">Bil.Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPK</div></td>\n";
echo "    <td><div align=\"center\">Bil.Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPK</div></td>\n";
echo "    <td><div align=\"center\">Bil. Ambil</div></td>\n";
echo "    <td><div align=\"center\">Bil.Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPK</div></td>\n";
echo "    <td><div align=\"center\">Bil.Lulus<br>%Lulus</div></td>\n";
echo "    <td><div align=\"center\">GPK</div></td>\n";
echo "  </tr>\n";

switch ($ting) {
			case "T4": case "t4": case "T5": case "t5": 
			{
				$analisis="analisis_mpma amp, mpsmkc mp";
				$tingdar = "TING";
				$bilgred = "BILAP,BILA,BILAM,BILBP,BILB,BILCP,BILC,BILD,BILE,BILG,BILTH";
				$minus = "select kod from sub_ma_xambil ";
				break;
			}
		}

$qting = "SELECT DISTINCT kelas FROM headcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND hmp='$mp' ORDER BY kelas";
//echo "SELECT DISTINCT kelas FROM headcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND hmp='$mp' ORDER BY kelas";
$rting = oci_parse($conn_sispa,$qting);
oci_execute($rting);

while ($recordting=oci_fetch_array($rting)){
	$kelas = $recordting['KELAS'];
	$gmp = "G$mp";
		
		$q_atr1 = "SELECT $gmp FROM $tmarkah WHERE tahun='$tahunatr1' AND kodsek='$kodsek' AND ting='$tingatr1' AND kelas='$kelas' AND jpep='$jpepatr1' AND $gmp!=''";
	$qry_atr1 = oci_parse($conn_sispa,$q_atr1);
	oci_execute($qry_atr1);
	$calon_atr1 = count_row($q_atr1);
	$q_atr2 = "SELECT $gmp FROM $tmarkah WHERE tahun='$tahunatr2' AND kodsek='$kodsek' AND ting='$tingatr2' AND kelas='$kelas' AND jpep='$jpepatr2' AND $gmp!=''";
	$qry_atr2 = oci_parse($conn_sispa,$q_atr2);
	oci_execute($qry_atr2);
	$calon_atr2 = count_row($q_atr2);
	$q_atr3 = "SELECT $gmp FROM $tmarkah WHERE tahun='$tahunatr3' AND kodsek='$kodsek' AND ting='$tingatr3' AND kelas='$kelas' AND jpep='$jpepatr3' AND $gmp!=''";
	$qry_atr3 = oci_parse($conn_sispa,$q_atr3);
	oci_execute($qry_atr3);
	$calon_atr3 = count_row($q_atr3);
	
	$qhc = "SELECT * FROM headcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND hmp='$mp' AND ting='$ting' AND kelas='$kelas'";
	$qry_qhc = oci_parse($conn_sispa,$qhc);
	oci_execute($qry_qhc);
	$bilcalon = count_row($qhc);
	

	switch ($ting) {
		case "P": case "p": case "T1": case "t1": case "T2": case "t2": case "T3": case "t3": case "D1": case "d1": case "D2": case "d2": case "D3": case "d3": case "D4": case "d4": case "D5": case "d5": case "D6": case "d6":
		{
			$tovA=$tovB=$tovC=$tovD=$tovE=$tovTH=$oti1A=$oti1B=$oti1C=$oti1D=$oti1E=$oti1TH=0;
			$oti2A=$oti2B=$oti2C=$oti2D=$oti2E=$oti2TH=$oti3A=$oti3B=$oti3C=$oti3D=$oti3E=$oti3TH=0;
			$atr1A=$atr1B=$atr1C=$atr1D=$atr1E=$atr1TH=$atr2A=$atr2B=$atr2C=$atr2D=$atr2E=$atr2TH=0;
			$atr3A=$atr3B=$atr3C=$atr3D=$atr3E=$atr3TH=$etrA=$etrB=$etrC=$etrD=$etrE=$etrTH=0;
			
			while ($record = oci_fetch_array($qry_qhc))
			{
				switch ($record['GTOV'])
				{
					case "A" : $tovA=$tovA+1; break;
					case "B" : $tovB=$tovB+1; break;
					case "C" : $tovC=$tovC+1; break;
					case "D" : $tovD=$tovD+1; break;
					case "E" : $tovE=$tovE+1; break;
				}
				switch ($record['GOTR1'])
				{
					case "A" : $oti1A=$oti1A+1; break;
					case "B" : $oti1B=$oti1B+1; break;
					case "C" : $oti1C=$oti1C+1; break;
					case "D" : $oti1D=$oti1D+1; break;
					case "E" : $oti1E=$oti1E+1; break;
				}
				switch ($record['GOTR2'])
				{
					case "A" : $oti2A=$oti2A+1; break;
					case "B" : $oti2B=$oti2B+1; break;
					case "C" : $oti2C=$oti2C+1; break;
					case "D" : $oti2D=$oti2D+1; break;
					case "E" : $oti2E=$oti2E+1; break;
				}
				switch ($record['GOTR3'])
				{
					case "A" : $oti3A=$oti3A+1; break;
					case "B" : $oti3B=$oti3B+1; break;
					case "C" : $oti3C=$oti3C+1; break;
					case "D" : $oti3D=$oti3D+1; break;
					case "E" : $oti3E=$oti3E+1; break;
				}
					
				switch ($record['GETR'])
				{
					case "A" : $etrA=$etrA+1; break;
					case "B" : $etrB=$etrB+1; break;
					case "C" : $etrC=$etrC+1; break;
					case "D" : $etrD=$etrD+1; break;
					case "E" : $etrE=$etrE+1; break;
				}
			}
			
			switch ($level)
			{
				case "MR" :
					$tovlulus = $tovA+$tovB+$tovC+$tovD;
					$oti1lulus = $oti1A+$oti1B+$oti1C+$oti1D;
					$oti2lulus = $oti2A+$oti2B+$oti2C+$oti2D;
					$oti3lulus = $oti3A+$oti3B+$oti3C+$oti3D;
					$atr1lulus = $atr1A+$atr1B+$atr1C+$atr1D;

					$atr2lulus = $atr2A+$atr2B+$atr2C+$atr2D;
					$atr3lulus = $atr3A+$atr3B+$atr3C+$atr3D;
					$etrlulus = $etrA+$etrB+$etrC+$etrD;
					break;
				case "SR" :
					$tovlulus = $tovA+$tovB+$tovC;
					$oti1lulus = $oti1A+$oti1B+$oti1C;
					$oti2lulus = $oti2A+$oti2B+$oti2C;
					$oti3lulus = $oti3A+$oti3B+$oti3C;					
					$atr1lulus = $atr1A+$atr1B+$atr1C;
					$atr2lulus = $atr2A+$atr2B+$atr2C;
					$atr3lulus = $atr3A+$atr3B+$atr3C;
					$etrlulus = $etrA+$etrB+$etrC;
					break;				
			}
			$peratustovlulus = number_format(($tovlulus/($bilcalon-$tovTH))*100,2,'.',',');
			$gpmptov = number_format((($tovA*1)+($tovB*2)+($tovC*3)+($tovD*4)+($tovE*5))/($bilcalon-$tovTH),2,'.',',');
			$peratusoti1lulus = number_format(($oti1lulus/($bilcalon-$oti1TH))*100,2,'.',',');
			$gpmpoti1 = number_format((($oti1A*1)+($oti1B*2)+($oti1C*3)+($oti1D*4)+($oti1E*5))/($bilcalon-$oti1TH),2,'.',',');
			if ($calon_atr1!=0){							
				$gpmpatr1 = number_format((($atr1A*1)+($atr1B*2)+($atr1C*3)+($atr1D*4)+($atr1E*5))/($calon_atr1-$atr1TH),2,'.',',');
				$peratusatr1lulus = number_format(($atr1lulus/($calon_atr1-$atr1TH))*100,2,'.',',');
			} else { $gpmpatr1 = $peratusatr1lulus = 0.00; }
			$peratusoti2lulus = number_format(($oti2lulus/($bilcalon-$oti2TH))*100,2,'.',',');
			$gpmpoti2 = number_format((($oti2A*1)+($oti2B*2)+($oti2C*3)+($oti2D*4)+($oti2E*5))/($bilcalon-$oti2TH),2,'.',',');
			if ($calon_atr2!=0){
				$gpmpatr2 = number_format((($atr2A*1)+($atr2B*2)+($atr2C*3)+($atr2D*4)+($atr2E*5))/($calon_atr2-$atr2TH),2,'.',',');
				$peratusatr2lulus = number_format(($atr2lulus/($calon_atr2-$atr2TH))*100,2,'.',',');				
			} else { $gpmpatr2 = $peratusatr2lulus = 0.00; }
			$peratusoti3lulus = number_format(($oti3lulus/($bilcalon-$oti3TH))*100,2,'.',',');
			$gpmpoti3 = number_format((($oti3A*1)+($oti3B*2)+($oti3C*3)+($oti3D*4)+($oti3E*5))/($bilcalon-$oti3TH),2,'.',',');
			if ($calon_atr3!=0){
				$gpmpatr3 = number_format((($atr3A*1)+($atr3B*2)+($atr3C*3)+($atr3D*4)+($atr3E*5))/($calon_atr3-$atr3TH),2,'.',',');
				$peratusatr3lulus = number_format(($atr3lulus/($calon_atr3-$atr3TH))*100,2,'.',',');				
			} else { $gpmpatr3 = $peratusatr3lulus = 0.00; }
			$peratusetrlulus = number_format(($etrlulus/($bilcalon-$etrTH))*100,2,'.',',');
			$gpmpetr = number_format((($etrA*1)+($etrB*2)+($etrC*3)+($etrD*4)+($etrE*5))/($bilcalon-$etrTH),2,'.',',');

			$bil=$bil+1;
			//echo $ting."<br>";
				$sql_hc = "select jenpep,capai from tentu_hc where tingpep='$ting' and tahunpep='$tahun' and capai!='TOV'";
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
							 FROM $analisis WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jenpep' AND  amp.$tingdar='$ting' AND amp.kelas='$kelas' AND amp.kodmp=mp.kod and amp.kodmp='$mp' and amp.kodmp not in ($minus) Group BY mp.susun,amp.kodmp";
							 
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
					//if($capai == "ATR2")
						//echo "<br>$tingtemp bilambatr2 $jumambilatr1 billulus $jumlulusatr2 atr2 $gpmpatr2";	
				}//while jenpep
			
			
			echo "  <tr>\n";
			echo "    <td><div align=\"center\">$bil</div></td>\n";
			echo "    <td><div align=\"left\"><a href=hc-mp-ind-bptv.php?datahc=".$kodsek."/".$tahun."/".$ting."/".$mp."/".$kelas.">$ting $kelas</div></td>\n";
			echo "    <td><div align=\"center\">".($bilcalon-$tovTH)."</div></td>\n";
			echo "    <td><div align=\"center\">$tovlulus<br>$peratustovlulus</div></td>\n";
			echo "    <td><div align=\"center\">$gpmptov</div></td>\n";
			echo "    <td><div align=\"center\">$oti1lulus<br>$peratusoti1lulus</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpoti1</div></td>\n";
			echo "    <td><div align=\"center\">$jumambilatr1</div></td>\n";
			echo "    <td><div align=\"center\">$jumlulusatr1<br>".peratus($jumlulusatr1,$bilcalon-$bilanganthATR1)."</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpatr1</div></td>\n";
			echo "    <td><div align=\"center\">$oti2lulus<br>$peratusoti2lulus</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpoti2</div></td>\n";
			echo "    <td><div align=\"center\">$jumambilatr2</div></td>\n";
			echo "    <td><div align=\"center\">$jumlulusatr2<br>".peratus($jumlulusatr2,$bilcalon-$bilanganthATR2)."</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpatr2</div></td>\n";
			echo "    <td><div align=\"center\">$oti3lulus<br>$peratusoti3lulus</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpoti3</div></td>\n";
			echo "    <td><div align=\"center\">$jumambilatr3</div></td>\n";
			echo "    <td><div align=\"center\">$jumlulusatr1<br>".peratus($jumlulusatr3,$bilcalon-$bilanganthATR3)."</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpatr3</div></td>\n";
			echo "    <td><div align=\"center\">$etrlulus<br>$peratusetrlulus</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpetr</div></td>\n";
			echo "  </tr>\n";
			break;
		}
		
		case "T4": case "t4": case "T5": case "t5":
		{	
				$tov1AA=$tov1A=$tov2A=$tov3B=$tov4B=$tov5C=$tov6C=$tov7D=$tov8E=$tov9G=$tovTH=0;
				$oti11AA=$oti11A=$oti12A=$oti13B=$oti14B=$oti15C=$oti16C=$oti17D=$oti18E=$oti19G=$oti1TH=0;
				$oti21AA=$oti21A=$oti22A=$oti23B=$oti24B=$oti25C=$oti26C=$oti27D=$oti28E=$oti29G=$oti2TH=0;
				$oti31AA=$oti31A=$oti32A=$oti33B=$oti34B=$oti35C=$oti36C=$oti37D=$oti38E=$oti39G=$oti3TH=0;
				$etr1AA=$etr1A=$etr2A=$etr3B=$etr4B=$etr5C=$etr6C=$etr7D=$etr8E=$etr9G=$etrTH=0;
				$atr11AA=$atr11A=$atr12A=$atr13B=$atr14B=$atr15C=$atr16C=$atr17D=$atr18E=$atr19G=$atr1TH=0;
				$atr21AA=$atr21A=$atr22A=$atr23B=$atr24B=$atr25C=$atr26C=$atr27D=$atr28E=$atr29G=$atr2TH=0;
				$atr31AA=$atr31A=$atr32A=$atr33B=$atr34B=$atr35C=$atr36C=$atr37D=$atr38E=$atr39G=$atr3TH=0;
				
			while ($record = oci_fetch_array($qry_qhc))
			{
				switch ($record['GTOV'])
				{	
					case "A+" : $tov1AA=$tov1AA+1; break;
					case "A" : $tov1A=$tov1A+1; break;
					case "A-" : $tov2A=$tov2A+1; break;
					case "B+" : $tov3B=$tov3B+1; break;
					case "B" : $tov4B=$tov4B+1; break;
					case "C+" : $tov5C=$tov5C+1; break;
					case "C" : $tov6C=$tov6C+1; break;
					case "D" : $tov7D=$tov7D+1; break;
					case "E" : $tov8E=$tov8E+1; break;
					case "G" : $tov9G=$tov9G+1; break;
					case "TH" : $tovTH=$tovTH+1; break;
				}
				switch ($record['GOTR1'])
				{	
					case "A+" : $oti11AA=$oti11AA+1; break;
					case "A" : $oti11A=$oti11A+1; break;
					case "A-" : $oti12A=$oti12A+1; break;
					case "B+" : $oti13B=$oti13B+1; break;
					case "B" : $oti14B=$oti14B+1; break;
					case "C+" : $oti15C=$oti15C+1; break;
					case "C" : $oti16C=$oti16C+1; break;
					case "D" : $oti17D=$oti17D+1; break;
					case "E" : $oti18E=$oti18E+1; break;
					case "G" : $oti19G=$oti19G+1; break;
					case "TH" : $oti1TH=$oti1TH+1; break;
				}
				switch ($record['GOTR2'])
				{	
					case "A+" : $oti21AA=$oti21AA+1; break;
					case "A" : $oti21A=$oti21A+1; break;
					case "A-" : $oti22A=$oti22A+1; break;
					case "B+" : $oti23B=$oti23B+1; break;
					case "B" : $oti24B=$oti24B+1; break;
					case "C+" : $oti25C=$oti25C+1; break;
					case "C" : $oti26C=$oti26C+1; break;
					case "D" : $oti27D=$oti27D+1; break;
					case "E" : $oti28E=$oti28E+1; break;
					case "G" : $oti29G=$oti29G+1; break;
					case "TH" : $oti2TH=$oti2TH+1; break;	
				}
				switch ($record['GOTR3'])
				{
				    case "A+" : $oti31AA=$oti31AA+1; break;
					case "A-" : $oti31A=$oti31A+1; break;
					case "A" : $oti32A=$oti32A+1; break;
					case "B+" : $oti33B=$oti33B+1; break;
					case "B" : $oti34B=$oti34B+1; break;
					case "C+" : $oti35C=$oti35C+1; break;
					case "C" : $oti36C=$oti36C+1; break;
					case "D" : $oti37D=$oti37D+1; break;
					case "E" : $oti38E=$oti38E+1; break;
					case "G" : $oti39G=$oti39G+1; break;
					case "TH" : $oti3TH=$oti3TH+1; break;				}
					
				switch ($record['GETR'])
				{	
					case "A+" : $etr1AA=$etr1AA+1; break;
					case "A-" : $etr1A=$etr1A+1; break;
					case "A" : $etr2A=$etr2A+1; break;
					case "B+" : $etr3B=$etr3B+1; break;
					case "B" : $etr4B=$etr4B+1; break;
					case "C+" : $etr5C=$etr5C+1; break;
					case "C" : $etr6C=$etr6C+1; break;
					case "D" : $etr7D=$etr7D+1; break;
					case "E" : $etr8E=$etr8E+1; break;
					case "G" : $etr9G=$etr9G+1; break;
					case "TH" : $etrTH=$etrTH+1; break;
				}
			}
			while ($r_atr1 = oci_fetch_array($qry_atr1))
			{
				switch ($r_atr1["$gmp"])
				{
					case "A+" : $atr11AA=$atr11AA+1; break;
					case "A-" : $atr11A=$atr11A+1; break;
					case "A" : $atr12A=$atr12A+1; break;
					case "B+" : $atr13B=$atr13B+1; break;
					case "B" : $atr14B=$atr14B+1; break;
					case "C+" : $atr15C=$atr15C+1; break;
					case "C" : $atr16C=$atr16C+1; break;
					case "D" : $atr17D=$atr17D+1; break;
					case "E" : $atr18E=$atr18E+1; break;
					case "G" : $atr19G=$atr19G+1; break;
					case "TH" : $atr1TH=$atr1TH+1; break;
				}
			}
			while ($r_atr2 = oci_fetch_array($qry_atr2))
			{
				switch ($r_atr2["$gmp"])
				{	
					case "A+" : $atr21AA=$atr21AA+1; break;
					case "A-" : $atr21A=$atr21A+1; break;
					case "A" : $atr22A=$atr22A+1; break;
					case "B+" : $atr23B=$atr23B+1; break;
					case "B" : $atr24B=$atr24B+1; break;
					case "C+" : $atr25C=$atr25C+1; break;
					case "C" : $atr26C=$atr26C+1; break;
					case "D" : $atr27D=$atr27D+1; break;
					case "E" : $atr28E=$atr28E+1; break;
					case "G" : $atr29G=$atr29G+1; break;
					case "TH" : $atr2TH=$atr2TH+1; break;
				}
			}
			while ($r_atr3 = oci_fetch_array($qry_atr3))
			{
				switch ($r_atr3["$gmp"])
				{	
					case "A+" : $atr31AA=$atr31AA+1; break;
					case "A-" : $atr31A=$atr31A+1; break;
					case "A" : $atr32A=$atr32A+1; break;
					case "B+" : $atr33B=$atr33B+1; break;
					case "B" : $atr34B=$atr34B+1; break;
					case "C+" : $atr35C=$atr35C+1; break;
					case "C" : $atr36C=$atr36C+1; break;
					case "D" : $atr37D=$atr37D+1; break;
					case "E" : $atr38E=$atr38E+1; break;
					case "G" : $atr39G=$atr39G+1; break;
					case "TH" : $atr3TH=$atr3TH+1; break;
				}
			}
			$tovlulus = $tov1AA+$tov1A+$tov2A+$tov3B+$tov4B+$tov5C+$tov6C+$tov7D+$tov8E;
			$oti1lulus = $oti11AA+$oti11A+$oti12A+$oti13B+$oti14B+$oti15C+$oti16C+$oti17D+$oti18E;	
			$oti2lulus = $oti21AA+$oti21A+$oti22A+$oti23B+$oti24B+$oti25C+$oti26C+$oti27D+$oti28E;
			$oti3lulus = $oti31AA+$oti31A+$oti32A+$oti33B+$oti34B+$oti35C+$oti36C+$oti37D+$oti38E;
			$atr1lulus = $atr11AA+$atr11A+$atr12A+$atr13B+$atr14B+$atr15C+$atr16C+$atr17D+$atr18E;
			$atr2lulus = $atr21AA+$atr21A+$atr22A+$atr23B+$atr24B+$atr25C+$atr26C+$atr27D+$atr28E;	
			$atr3lulus = $atr31AA+$atr31A+$atr32A+$atr33B+$atr34B+$atr35C+$atr36C+$atr37D+$atr38E;		
			$etrlulus = $etr1AA+$etr1A+$etr2A+$etr3B+$etr4B+$etr5C+$etr6C+$etr7D+$etr8E;
			$peratustovlulus = number_format(($tovlulus/($bilcalon-$tovTH))*100,2,'.',',');
			$peratusoti1lulus = number_format(($oti1lulus/($bilcalon-$oti1TH))*100,2,'.',',');
			$peratusoti2lulus = number_format(($oti2lulus/($bilcalon-$oti2TH))*100,2,'.',',');
			$peratusoti3lulus = number_format(($oti3lulus/($bilcalon-$oti3TH))*100,2,'.',',');
			$gpmptov = number_format((($tov1AA*0)+($tov1A*1)+($tov2A*2)+($tov3B*3)+($tov4B*4)+($tov5C*5)+($tov6C*6)+($tov7D*7)+($tov8E*8)+($tov9G*9))/($bilcalon-$tovTH),2,'.',',');
			$gpmpoti1 = number_format((($oti11AA*0)+($oti11A*1)+($oti12A*2)+($oti13B*3)+($oti14B*4)+($oti15C*5)+($oti16C*6)+($oti17D*7)+($oti18E*8)+($oti19G*9))/($bilcalon-$oti1TH),2,'.',',');
			if ($calon_atr1 != 0){
				$gpmpatr1 = number_format((($atr11AA*0)+($atr11A*1)+($atr12A*2)+($atr13B*3)+($atr14B*4)+($atr15C*5)+($atr16C*6)+($atr17D*7)+($atr18E*8)+($atr19G*9))/($calon_atr1-$atr1TH),2,'.',',');
				$peratusatr1lulus = number_format(($atr1lulus/($calon_atr1-$atr1TH))*100,2,'.',',');
			} else { $gpmpatr1 = $peratusatr1lulus = 0.00; }
			$gpmpoti2 = number_format((($oti21AA*0)+($oti21A*1)+($oti22A*2)+($oti23B*3)+($oti24B*4)+($oti25C*5)+($oti26C*6)+($oti27D*7)+($oti28E*8)+($oti29G*9))/($bilcalon-$oti2TH),2,'.',',');
			if ($calon_atr2 != 0){
				$gpmpatr2 = number_format((($atr21AA*0)+($atr21A*1)+($atr22A*2)+($atr23B*3)+($atr24B*4)+($atr25C*5)+($atr26C*6)+($atr27D*7)+($atr28E*8)+($atr29G*9))/($calon_atr2-$atr2TH),2,'.',',');
				$peratusatr2lulus = number_format(($atr2lulus/($calon_atr2-$atr2TH))*100,2,'.',',');
			} else { $gpmpatr2 = $peratusatr2lulus = 0.00; }
			$gpmpoti3 = number_format((($oti31AA*0)+($oti31A*1)+($oti32A*2)+($oti33B*3)+($oti34B*4)+($oti35C*5)+($oti36C*6)+($oti37D*7)+($oti38E*8)+($oti39G*9))/($bilcalon-$oti3TH),2,'.',',');
			if ($calon_atr3 != 0){
				$gpmpatr3 = number_format((($atr31AA*0)+($atr31A*1)+($atr32A*2)+($atr33B*3)+($atr34B*4)+($atr35C*5)+($atr36C*6)+($atr37D*7)+($atr38E*8)+($atr39G*9))/($calon_atr3-$atr3TH),2,'.',',');
				$peratusatr3lulus = number_format(($atr3lulus/($calon_atr3-$atr3TH))*100,2,'.',',');
			} else { $gpmpatr3 = $peratusatr3lulus = 0.00; }
			$peratusetrlulus = number_format(($etrlulus/($bilcalon-$etrTH))*100,2,'.',',');
			$gpmpetr = number_format((($etr1AA*0)+($etr1A*1)+($etr2A*2)+($etr3B*3)+($etr4B*4)+($etr5C*5)+($etr6C*6)+($etr7D*7)+($etr8E*8)+($etr9G*9))/($bilcalon-$etrTH),2,'.',',');
			
			$bil=$bil+1;
			//echo $tingtemp."<br>";
				$sql_ha = "select jenpep,capai from tentu_hc where tingpep='$ting' and tahunpep='$tahun' and capai!='TOV'";
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
					 FROM $analisis WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jenpep' 
					 AND  amp.$tingdar='$ting' AND amp.kelas='$kelas' AND amp.kodmp=mp.kod and amp.kodmp='$mp' and amp.kodmp not in ($minus) Group BY mp.susun,amp.kodmp";

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
					//if($capai == "ATR2")
						//echo "<br>$tingtemp bilambatr2 $jumambilatr2 billulus $jumlulusatr2 atr2 $gpmpatr2";	
				}//while jenpep		
			echo "  <tr>\n";
			echo "    <td><div align=\"center\">$bil</div></td>\n";
			echo "    <td><div align=\"left\"><a href=hc-mp-ind-bptv.php?datahc=".$kodsek."/".$tahun."/".$ting."/".$mp."/".$kelas.">$ting $kelas</div></td>\n";
			echo "    <td><div align=\"center\">".($bilcalon-$tovTH)."</div></td>\n";
			echo "    <td><div align=\"center\">$tovlulus<br>$peratuslulustov</div></td>\n";
			echo "    <td><div align=\"center\">$gpmptov</div></td>\n";
			echo "    <td><div align=\"center\">$oti1lulus<br>$peratusoti1lulus</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpoti1</div></td>\n";
			echo "    <td><div align=\"center\">$jumambilatr1</div></td>\n";
			echo "    <td><div align=\"center\">$jumlulusatr1<br>".peratus($jumlulusatr1,$bilcalon-$bilanganthATR1)."</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpatr1</div></td>\n";
			echo "    <td><div align=\"center\">$oti2lulus<br>$peratusoti2lulus</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpoti2</div></td>\n";
			echo "    <td><div align=\"center\">$jumambilatr2</div></td>\n";
			echo "    <td><div align=\"center\">$jumlulusatr2<br>".peratus($jumlulusatr2,$bilcalon-$bilanganthATR2)."</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpatr2</div></td>\n";
			echo "    <td><div align=\"center\">$oti3lulus<br>$peratusoti3lulus</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpoti3</div></td>\n";
			echo "    <td><div align=\"center\">$jumambilatr3</div></td>\n";
			echo "    <td><div align=\"center\">$jumlulusatr3<br>".peratus($jumlulusatr3,$bilcalon-$bilanganthATR3)."</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpatr3</div></td>\n";
			echo "    <td><div align=\"center\">$etrlulus<br>$peratusetrlulus</div></td>\n";
			echo "    <td><div align=\"center\">$gpmpetr</div></td>\n";
			echo "  </tr>\n";
			break;		}
		echo "</table>\n";
		echo " <br><br><br><br><br>";
	}//end case $tingtemp
}
?></tr></table>
</td>
<?php include 'kaki.php';?>                                                           
                                                           