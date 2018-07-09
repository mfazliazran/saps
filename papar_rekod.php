<?php
//$conn_sispa=oci_connect("portal_sispa","portal_sispa","//testappserver:1521/xe");
include "../config.php";
include '../fungsikira.php';
$hlcolor= "#AACCF2";
$ncolor="#ffffff";	
$altcolor="#57C9DD";

$currdate=date("Y-m-d");

$flg=$_GET["flg"];
$kod=$_GET["kod"];
$jenis=$_GET["jenis"];
$jenissekolah=$_GET["jenissek"];
$status=$_GET["status"];
$ting=$_GET["ting"];
$jpep=$_GET["jpep"];
$tahun=date("Y");
$kodnegeri=$_GET["KODNEGERI"];
//echo "flg=$flg kod=$kod kodnegeri=$kodnegeri";
//echo "Test";
$divkod=$kod;
if ($flg=="JPN" and $kod<>"16"){
     $sql="select KodPPD,PPD from tkppd where KodNegeri='$kod' order by KodPPD";
     $lbl="PPD";
  $res=oci_parse($conn_sispa,$sql);
			oci_execute($res);
	//echo"$kod";
  echo "<table width=\"100%\" border=\"2\" cellpadding=\"0\" cellspacing=\"0\"><tr><td width=\"10px\">&nbsp;</td><td>";
  echo "<TABLE  style=\"border: solid 1px #CCC;\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
  echo "<tr bgcolor='#DDDDDD'>
		<td width=\"30%\" align=\"center\"><strong>$lbl</strong></td>
		<td width=\"10%\" align=\"center\"><strong>JUMLAH SEKOLAH</strong></td>
		<td width=\"15%\" align=\"center\"><strong>JUMLAH PELAJAR</strong></td>
		<td width=\"15%\" align=\"center\"><strong>GPS</strong></td></tr>";
  $cnt=0;
  while ($data=oci_fetch_array($res)){
    $cnt++;
       $kodppd1 = $data["KODPPD"];
	   $ppd = $data["PPD"];

		$bilsekolah=count_row("select count(*) from tsekolah where kodppd='$kodppd1' and status='$jenissekolah'");//$data[0];
		$jumseko+=$bilsekolah;
			$sqlsek="select KODSEK,status from tsekolah where kodppd='$kodppd1' and status='$jenissekolah'";
			//echo $sqlsek."<br>";
			$res1=oci_parse($conn_sispa,$sqlsek);
			oci_execute($res1);
			$bilA=$bilB=$bilC=$bilD=$bilE=$bilamb=0;
			$jumA=$jumB=$jumC=$jumD=$jumE=$jumAmbil=0;
			$jumAP=$jumA=$jumAM=$jumBP=$jumB=$jumCP=$jumC=$jumD=$jumE=$jumG=$jumAmbil=0;
	   		while($data=oci_fetch_array($res1))
			{
				$kodsek2 = $data[0];
				$jenisseko = $data[1];
				//echo "$jenisseko $kodsek2<br>";
				if ($status=='SR'){
					if($ting=='D1')
						$kodting = "(KodSekD1='$kodsek2' and TahunD1='$tahun')";
					elseif($ting=='D2')
						$kodting = "(KodSekD2='$kodsek2' and TahunD2='$tahun')";
					elseif($ting=='D3')
						$kodting = "(KodSekD3='$kodsek2' and TahunD3='$tahun')";
					elseif($ting=='D4')
						$kodting = "(KodSekD4='$kodsek2' and TahunD4='$tahun')";
					elseif($ting=='D5')
						$kodting = "(KodSekD5='$kodsek2' and TahunD5='$tahun')";
					elseif($ting=='D6')
						$kodting = "(KodSekD6='$kodsek2' and TahunD6='$tahun')";
					else
						$kodting = "(KodSekD1='$kodsek2' and TahunD1='$tahun') or (KodSekD2='$kodsek2' and TahunD2='$tahun') or (KodSekD3='$kodsek2' and TahunD3='$tahun') or (KodSekD4='$kodsek2' and TahunD4='$tahun') or (KodSekD5='$kodsek2' and TahunD5='$tahun') or (KodSekD6='$kodsek2' and TahunD6='$tahun')";
						
					$jumpel="select count(nokp) as bilmurid from tmuridsr where $kodting";
					//echo $jumpel."<br>";
					$respel=oci_parse($conn_sispa,$jumpel);	 
					oci_execute($respel);
					while($datapel=oci_fetch_array($respel)){
						$cnt_pel=(int) $datapel["BILMURID"];
						$jummurid+=$cnt_pel;
					}
					$sqltnilai = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E,SUM(AMBIL) AS AMBIL FROM analisis_mpsr WHERE TAHUN='$tahun' AND JPEP='$jpep' AND KODSEK='$kodsek2' AND DARJAH='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) GROUP BY KODSEK";
					//echo $sqltnilai;
					$restnilai = oci_parse($conn_sispa,$sqltnilai);
					oci_execute($restnilai);
					$datatnilai = oci_fetch_array($restnilai);
					$bilA = (int) $datatnilai["A"];
					$bilB = (int) $datatnilai["B"];
					$bilC = (int) $datatnilai["C"];
					$bilD = (int) $datatnilai["D"];
					$bilE = (int) $datatnilai["E"];
					$bilamb = (int) $datatnilai["AMBIL"];
					
					$jumA+=$bilA;
					$jumB+=$bilB;
					$jumC+=$bilC;
					$jumD+=$bilD;
					$jumE+=$bilE;
					$jumAmbil+=$bilamb;	
					
					$jumlahA+=$bilA;
					$jumlahB+=$bilB;
					$jumlahC+=$bilC;
					$jumlahD+=$bilD;
					$jumlahE+=$bilE;
					$jumlahAmbil+=$bilamb;	
					//$jumpelajar+=$jummurid;
					$gpsall = gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil);
				}//if status SR
				elseif($status=="MR")
				{
					if($ting=='T1')
						$kodting = "(KodSekT1='$kodsek2' and TahunT1='$tahun')";
					elseif($ting=='T2')
						$kodting = "(KodSekT2='$kodsek2' and TahunT2='$tahun')";
					elseif($ting=='T3')
						$kodting = "(KodSekT3='$kodsek2' and TahunT3='$tahun')";
					else
						$kodting = "(KodSekT1='$kodsek2' and TahunT1='$tahun') or (KodSekT2='$kodsek2' and TahunT2='$tahun') or (KodSekT3='$kodsek2' and TahunT3='$tahun')";
							
					$jumpel="select count(nokp) as bilmurid from tmurid where $kodting";
					//echo $jumpel."<br>";
					$respel=oci_parse($conn_sispa,$jumpel);	 
					oci_execute($respel);
					while($datapel=oci_fetch_array($respel)){
						$cnt_pel=(int) $datapel["BILMURID"];
						$jummurid+=$cnt_pel;
					}
					
					$sqltnilai = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E,SUM(AMBIL) AS AMBIL FROM analisis_mpmr WHERE TAHUN='$tahun' AND JPEP='$jpep' AND KODSEK='$kodsek2' AND TING='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_MR_XAMBIL) GROUP BY KODSEK";
						//echo $sqltnilai."<br>";
					$restnilai = oci_parse($conn_sispa,$sqltnilai);
					oci_execute($restnilai);
					$datatnilai = oci_fetch_array($restnilai);
					$bilA = (int) $datatnilai["A"];
					$bilB = (int) $datatnilai["B"];
					$bilC = (int) $datatnilai["C"];
					$bilD = (int) $datatnilai["D"];
					$bilE = (int) $datatnilai["E"];
					$bilamb = (int) $datatnilai["AMBIL"];
						
					$jumA+=$bilA;
					$jumB+=$bilB;
					$jumC+=$bilC;
					$jumD+=$bilD;
					$jumE+=$bilE;
					$jumAmbil+=$bilamb;	
					
					$jumlahA+=$bilA;
					$jumlahB+=$bilB;
					$jumlahC+=$bilC;
					$jumlahD+=$bilD;
					$jumlahE+=$bilE;
					$jumlahAmbil+=$bilamb;
					//$jumpelajar+=$jummurid;
					$gpsall = gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil);
				}//if MR
				elseif($status=="MA")
				{
					if($ting=='T4')
						$kodting = "(KodSekT4='$kodsek2' and TahunT4='$tahun')";
					elseif($ting=='T5')
						$kodting = "(KodSekT5='$kodsek2' and TahunT5='$tahun')";
					else
						$kodting = "(KodSekT4='$kodsek2' and TahunT4='$tahun') or (KodSekT5='$kodsek2' and TahunT5='$tahun')";
						
					$jumpel="select count(nokp) as bilmurid from tmurid where $kodting";
					$respel=oci_parse($conn_sispa,$jumpel);	 
					oci_execute($respel);
					while($datapel=oci_fetch_array($respel)){
						$cnt_pel=(int) $datapel["BILMURID"];
						$jummurid+=$cnt_pel;
					}
						
					$sqltnilai = "select sum(AP) as AP, SUM(A) AS A, SUM(AM) AS AM, SUM(BP) AS BP, SUM(B) as B, SUM(CP) AS CP,SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(G) AS G, SUM(AMBIL) AS AMBIL FROM analisis_mpma WHERE TAHUN='$tahun' AND JPEP='$jpep' AND KODSEK='$kodsek2' AND  TING='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL) GROUP BY KODSEK";
						//echo $sqltnilai."<br>";
					$restnilai = oci_parse($conn_sispa,$sqltnilai);
					oci_execute($restnilai);
					$datatnilai = oci_fetch_array($restnilai);
					$bilAP = (int) $datatnilai["AP"];
					$bilA = (int) $datatnilai["A"];
					$bilAM = (int) $datatnilai["AM"];
					$bilBP = (int) $datatnilai["BP"];
					$bilB = (int) $datatnilai["B"];
					$bilCP = (int) $datatnilai["CP"];
					$bilC = (int) $datatnilai["C"];
					$bilD = (int) $datatnilai["D"];
					$bilE = (int) $datatnilai["E"];
					$bilG = (int) $datatnilai["G"];
					$bilamb = (int) $datatnilai["AMBIL"];
						
					$jumAP+=$bilAP;
					$jumA+=$bilA;
					$jumAM+=$bilAM;
					$jumBP+=$bilBP;
					$jumB+=$bilB;
					$jumCP+=$bilCP;
					$jumC+=$bilC;
					$jumD+=$bilD;
					$jumE+=$bilE;
					$jumG+=$bilG;
					$jumAmbil+=$bilamb;	
					
					$jumlahAP+=$bilAP;
					$jumlahA+=$bilA;
					$jumlahAM+=$bilAM;
					$jumlahBP+=$bilBP;
					$jumlahB+=$bilB;
					$jumlahCP+=$bilCP;
					$jumlahC+=$bilC;
					$jumlahD+=$bilD;
					$jumlahE+=$bilE;
					$jumlahG+=$bilG;
					$jumlahAmbil+=$bilamb;
					//$jumpelajar+=$jummurid;
					$gpsall = gpmpma($jumAP, $jumA, $jumAM, $jumBP, $jumB, $jumCP, $jumC, $jumD, $jumE, $jumG, $jumAmbil);
				}//if MA
			}//while
			$jumpelajar+=$jummurid;

	   echo "<tr bgcolor='#D8DFFC' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '#D8DFFC'\"> \n";
       echo "<td><a href=\"javascript:void(0);\" onClick=\"papar_rekod('PPD','$kodppd1','$cnt','','$tahun','$status','$jenisseko','$ting','$jpep');\">$kodppd1 - $ppd </a></td>
			<td align=\"right\"><center>$bilsekolah</center></td>
			<td align=\"right\">".number_format($jummurid)."</td>
			<td align=\"right\">$gpsall</td></tr>";
       echo "<tr><td colspan=\"6\"><div id=\"div_detail_ppd_".$kodppd1."_".$cnt."\" style=\"display:none\"></div></td></tr>";
	   $jummurid=0;
	   $gps=0;
	   $bilsekolah=0;
	   //$bilA=$bilB=$bilC=$bilD=$bilE=$bilamb=0;
	   //$jumA=$jumB=$jumC=$jumD=$jumE=$jumAmbil=0;
  }//while ppd

if($status=='SR' or $status=='MR'){
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\" colspan='2'><strong><center>$jumseko</center></strong></td>
	<td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumpelajar)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	//echo "<tr bgcolor='#CCCCCC'> \n";
	//echo "<td colspan='3'><strong>JUMLAH CALON</strong></td><td align=\"right\" colspan='2'><strong><center>$jumAmbil</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (A*1)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahA)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahA*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (B*2)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahB)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahB*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (C*3)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahC)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahC*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (D*4)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahD)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahD*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (E*5)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahE)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahE*5))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>PENGIRAAN </strong></td><td align=\"right\" colspan='2'><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".(number_format($jumlahA*1+$jumlahB*2+$jumlahC*3+$jumlahD*4+$jumlahE*5))."/".number_format($jumlahAmbil)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>GPS KESELURUHAN NEGERI </strong></td><td align=\"right\" colspan='2'><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>".gpmpmrsr($jumlahA,$jumlahB,$jumlahC,$jumlahD,$jumlahE,$jumlahAmbil)."</center></strong></td></tr>";
}//if sr mr
if($status=='MA'){
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\" colspan='2'><strong><center>$jumseko</center></strong></td>
	<td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumpelajar)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (AP*0)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahAP)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahAP*0))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (A*1)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahA)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahA*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (AM*2)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahAM)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahAM*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (BP*3)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahBP)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahBP*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (B*4)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahB)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahB*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (CP*5)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahCP)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahCP*5))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (C*6)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahC)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahC*6))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (D*7)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahD)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahD*7))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (E*8)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahE)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahE*8))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (G*9)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumlahG)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahG*9))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>PENGIRAAN </strong></td><td align=\"right\" colspan='2'><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".(number_format($jumlahAP*0+$jumlahA*1+$jumlahAM*2+$jumlahBP*3+$jumlahB*4+$jumlahCP*5+$jumlahC*6+$jumlahD*7+$jumlahE*8+$jumlahG*9))."/".number_format($jumlahAmbil)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>GPS KESELURUHAN NEGERI </strong></td><td align=\"right\" colspan='2'><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>".gpmpma($jumlahAP,$jumlahA,$jumlahAM,$jumlahBP,$jumlahB,$jumlahCP,$jumlahC,$jumlahD,$jumlahE,$jumlahG,$jumlahAmbil)."</center></strong></td></tr>";	
}//if ma
	##############################INSERT INTO GPS NEGERI###########################
	$wujud = count_row("SELECT * FROM GPS_NEGERI WHERE KODNEGERI='$kod' and tahun='$tahun' and jpep='$jpep' and tahap='$status' and ting='$ting'");
	if($wujud!=0){
		//update
		//echo "update";
		
	}else{
		echo "$status";
		if($status=='SR' or $status=='MR'){
			$jumkeseluruhan = $jumlahA*1+$jumlahB*2+$jumlahC*3+$jumlahD*4+$jumlahE*5;
			$gpsin = gpmpmrsr($jumlahA,$jumlahB,$jumlahC,$jumlahD,$jumlahE,$jumlahAmbil);
			$stmt = oci_parse($conn_sispa,"INSERT INTO GPS_NEGERI (KODNEGERI,TAHUN,TAHAP,JPEP,TING,JUMPELAJAR,JUMSEKOLAH,JUMA,JUMB,JUMC,JUMD,JUME,JUMKESELURUHAN,JUMAMBIL,GPS) VALUES ('$kod','$tahun','$status','$jpep','$ting','$jumpelajar','$jumseko','$jumlahA','$jumlahB','$jumlahC','$jumlahD','$jumlahE','$jumkeseluruhan','$jumlahAmbil','$gpsin')");	
			oci_execute($stmt);
		}
		else{
			$jumkeseluruhan = $jumlahAP*0+$jumlahA*1+$jumlahAM*2+$jumlahBP*3+$jumlahB*4+$jumlahCP*5+$jumlahC*6+$jumlahD*7+$jumlahE*8+$jumlahG*9;
			$gpsin = gpmpma($jumlahAP,$jumlahA,$jumlahAM,$jumlahBP,$jumlahB,$jumlahCP,$jumlahC,$jumlahD,$jumlahE,$jumlahG,$jumlahAmbil);
			$stmt = oci_parse($conn_sispa,"INSERT INTO GPS_NEGERI (KODNEGERI,TAHUN,TAHAP,JPEP,TING,JUMPELAJAR,JUMSEKOLAH,JUMAP,JUMA,JUMAM,JUMBP,JUMB,JUMCP,JUMC,JUMD,JUME,JUMG,JUMKESELURUHAN,JUMAMBIL,GPS) VALUES ('$kod','$tahun','$status','$jpep','$ting','$jumpelajar','$jumseko','$jumlahAP','$jumlahA','$jumlahAM','$jumlahBP','$jumlahB','$jumlahCP','$jumlahC','$jumlahD','$jumlahE','$jumlahG','$jumkeseluruhan','$jumlahAmbil','$gpsin')");	
			oci_execute($stmt);
		}
		
	}
 /* echo "<tr bgcolor='#DDDDDD'> \n";
  echo "<td><strong>JUMLAH</strong></td><td align=\"right\"><strong><center>$jumlah_sekolah</center></strong></td>
  		<td align=\"right\"><strong></strong></td>
  		<td align=\"right\"><strong></strong></td>
		<td align=\"right\"><strong></strong></td>
		<td align=\"right\"><strong></strong></td>
		<td align=\"right\"><strong></strong></td></tr>";
  echo "</table>";
  echo "</td></tr></table>";*/

} 
if ($flg=="PPD"){
	//echo "PPD";
$c=" where ";
	$sql="SELECT KODSEK,NAMASEK,STATUS FROM tsekolah  ";
	//echo $sql;
	/*if ($flg=="PPD")or ($flg="JPN" and $kod=="16")){//jpn
		$sql.="$c KodNegeriJPN='$kodjpn'";
		$c=" and ";
	}*/
	if ($flg=="PPD"){//ppd
		$sql.="$c kodppd='$kod'";
		$c=" and ";
	}
	if($status=='SR')
		$sql.=" and status='SR'";
	else
		$sql.=" and status='SM'";
	$sql.=" order by KodSek";
	//echo "$sql $status<br>";
	$lbl="Sekolah";
  	$res1=oci_parse($conn_sispa,$sql);
	oci_execute($res1);

 	echo "<table><tr><td width=\"5px\">&nbsp;</td><td>";
	echo "<TABLE  style=\"border: solid 1px #CCC;\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" width=\"900\">\n";
	echo "<tr><td  width=\"5%\" class=\"list_table_header\"><strong><center>Bil</center></strong></td>
		      <td  width=\"20%\" class=\"list_table_header\"><strong><center>Kod Sekolah</center></strong></td>
			  <td  width=\"45%\" class=\"list_table_header\"><strong><center>Nama Sekolah</center></strong></td>
			  <td  width=\"10\" height=\"5%\" class=\"list_table_header\"><strong><center>Status Sekolah</center></strong></td>			
			  <td  height=\"10%\" class=\"list_table_header\"><strong>Bil Pelajar</strong></td>
			  <td  height=\"10%\" class=\"list_table_header\"><strong>GPS</strong></td></tr>\n";			  

		$count=$rowstart;
        $jumlah_sekolah=0;
		$gps=0;
while ($data=oci_fetch_array($res1)) 
{
	$kodsek1 = $data["KODSEK"];
	$namasekolah = $data["NAMASEK"];
	$statussek = $data["STATUS"];
	
	if ($status=="SR"){
		if($ting=='D1')
			$kodting = "(KodSekD1='$kodsek1' and TahunD1='$tahun')";
		elseif($ting=='D2')
			$kodting = "(KodSekD2='$kodsek1' and TahunD2='$tahun')";
		elseif($ting=='D3')
			$kodting = "(KodSekD3='$kodsek1' and TahunD3='$tahun')";
		elseif($ting=='D4')
			$kodting = "(KodSekD4='$kodsek1' and TahunD4='$tahun')";
		elseif($ting=='D5')
			$kodting = "(KodSekD5='$kodsek1' and TahunD5='$tahun')";
		elseif($ting=='D6')
			$kodting = "(KodSekD6='$kodsek1' and TahunD6='$tahun')";
		else
			$kodting = "(KodSekD1='$kodsek1' and TahunD1='$tahun') or (KodSekD2='$kodsek1' and TahunD2='$tahun') or (KodSekD3='$kodsek1' and TahunD3='$tahun') or (KodSekD4='$kodsek1' and TahunD4='$tahun') or (KodSekD5='$kodsek1' and TahunD5='$tahun') or (KodSekD6='$kodsek1' and TahunD6='$tahun')";
			
		$jumpel="select count(nokp) as bilmurid from tmuridsr where $kodting";
		$respel=oci_parse($conn_sispa,$jumpel);	 
		oci_execute($respel);
		$datapel=oci_fetch_array($respel);
		$cnt_murid=(int) $datapel["BILMURID"];
		
		$sqltnilai = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E,SUM(AMBIL) AS AMBIL FROM analisis_mpsr WHERE TAHUN='$tahun' AND JPEP='$jpep' AND KODSEK='$kodsek1' AND DARJAH='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) GROUP BY KODSEK";
		//echo $sqltnilai;
		$restnilai = oci_parse($conn_sispa,$sqltnilai);
		oci_execute($restnilai);
		$datatnilai = oci_fetch_array($restnilai);
		$bilA = (int) $datatnilai["A"];
		$bilB = (int) $datatnilai["B"];
		$bilC = (int) $datatnilai["C"];
		$bilD = (int) $datatnilai["D"];
		$bilE = (int) $datatnilai["E"];
		$bilamb = (int) $datatnilai["AMBIL"];
		
		$jumA+=$bilA;
		$jumB+=$bilB;
		$jumC+=$bilC;
		$jumD+=$bilD;
		$jumE+=$bilE;
		$jumAmbil+=$bilamb;	
		$jumpelajar+=$cnt_murid;
		$gpspel = gpmpmrsr($bilA,$bilB,$bilC,$bilD,$bilE,$bilamb);
	} //statussek=SR
	if($status=="MR")
	{
		if($ting=='T1')
			$kodting = "(KodSekT1='$kodsek1' and TahunT1='$tahun')";
		elseif($ting=='T2')
			$kodting = "(KodSekT2='$kodsek1' and TahunT2='$tahun')";
		elseif($ting=='T3')
			$kodting = "(KodSekT3='$kodsek1' and TahunT3='$tahun')";
		else
			$kodting = "(KodSekT1='$kodsek1' and TahunT1='$tahun') or (KodSekT2='$kodsek1' and TahunT2='$tahun') or (KodSekT3='$kodsek1' and TahunT3='$tahun')";
							
		$jumpel="select count(nokp) as bilmurid from tmurid where $kodting";
		//echo $jumpel."<br>";
		$respel=oci_parse($conn_sispa,$jumpel);	 
		oci_execute($respel);
		$datapel=oci_fetch_array($respel);
		$cnt_murid=(int) $datapel["BILMURID"];
					
		$sqltnilai = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E,SUM(AMBIL) AS AMBIL FROM analisis_mpmr WHERE TAHUN='$tahun' AND JPEP='$jpep' AND KODSEK='$kodsek1' AND TING='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_MR_XAMBIL) GROUP BY KODSEK";
						//echo $sqltnilai."<br>";
		$restnilai = oci_parse($conn_sispa,$sqltnilai);
		oci_execute($restnilai);
		$datatnilai = oci_fetch_array($restnilai);
		$bilA = (int) $datatnilai["A"];
		$bilB = (int) $datatnilai["B"];
		$bilC = (int) $datatnilai["C"];
		$bilD = (int) $datatnilai["D"];
		$bilE = (int) $datatnilai["E"];
		$bilamb = (int) $datatnilai["AMBIL"];
		//echo $bilamb."<br>";
						
		$jumA+=$bilA;
		$jumB+=$bilB;
		$jumC+=$bilC;
		$jumD+=$bilD;
		$jumE+=$bilE;
		$jumAmbil+=$bilamb;	
		$jumpelajar+=$cnt_murid;
		$gpspel = gpmpmrsr($bilA,$bilB,$bilC,$bilD,$bilE,$bilamb);
		//$gpsall = gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil);
	}//if MR
	if($status=="MA")
	{
		if($ting=='T4')
			$kodting = "(KodSekT4='$kodsek1' and TahunT4='$tahun')";
		elseif($ting=='T5')
			$kodting = "(KodSekT5='$kodsek1' and TahunT5='$tahun')";
		else
			$kodting = "(KodSekT4='$kodsek1' and TahunT4='$tahun') or (KodSekT5='$kodsek1' and TahunT5='$tahun')";
						
		$jumpel="select count(nokp) as bilmurid from tmurid where $kodting";
		$respel=oci_parse($conn_sispa,$jumpel);	 
		oci_execute($respel);
		$datapel=oci_fetch_array($respel);
		$cnt_murid=(int) $datapel["BILMURID"];
			
		$sqltnilai = "select sum(AP) as AP, SUM(A) AS A, SUM(AM) AS AM, SUM(BP) AS BP, SUM(B) as B, SUM(CP) AS CP,SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(G) AS G, SUM(AMBIL) AS AMBIL FROM analisis_mpma WHERE TAHUN='$tahun' AND JPEP='$jpep' AND KODSEK='$kodsek1' AND  TING='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL) GROUP BY KODSEK";
		//echo $sqltnilai."<br>";
		$restnilai = oci_parse($conn_sispa,$sqltnilai);
		oci_execute($restnilai);
		$datatnilai = oci_fetch_array($restnilai);
		$bilAP = (int) $datatnilai["AP"];
		$bilA = (int) $datatnilai["A"];
		$bilAM = (int) $datatnilai["AM"];
		$bilBP = (int) $datatnilai["BP"];
		$bilB = (int) $datatnilai["B"];
		$bilCP = (int) $datatnilai["CP"];
		$bilC = (int) $datatnilai["C"];
		$bilD = (int) $datatnilai["D"];
		$bilE = (int) $datatnilai["E"];
		$bilG = (int) $datatnilai["G"];
		$bilamb = (int) $datatnilai["AMBIL"];
						
		$jumAP+=$bilAP;
		$jumA+=$bilA;
		$jumAM+=$bilAM;
		$jumBP+=$bilBP;
		$jumB+=$bilB;
		$jumCP+=$bilCP;
		$jumC+=$bilC;
		$jumD+=$bilD;
		$jumE+=$bilE;
		$jumG+=$bilG;
		$jumAmbil+=$bilamb;	
		$jumpelajar+=$cnt_murid;
		//echo "$bilAP, $bilA, $bilAM, $bilBP, $bilB, $bilCP, $bilC, $bilD, $bilE, $bilG, $bilamb<br>";
		$gpspel = gpmpma($bilAP, $bilA, $bilAM, $bilBP, $bilB, $bilCP, $bilC, $bilD, $bilE, $bilG, $bilamb);
		//$gpsall = gpmpma($jumAP, $jumA, $jumAM, $jumBP, $jumB, $jumCP, $jumC, $jumD, $jumE, $jumG, $jumAmbil);
	}//if MA
		$jumlah_sekolah++;
		$count++;
		echo "<tr bgcolor='#E8EFFC' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '#E8EFFC'\"> \n";
		echo "<td>$count</td>";
		echo "<td ><strong><a href='pencapaian_kelas.php?ting=$ting&kodsek=$kodsek1&jenis=$jpep' target='_blank'>$kodsek1</a></strong></td>"; 
		echo "<td >$namasekolah </td>"; 
		echo "<td >$statussek</td>";
		echo "<td >$cnt_murid</td>";
		echo "<td align='center'>$gpspel</td>";
		$gps=0;
}
if($status=='SR' or $status=='MR'){
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\" colspan='2'><strong><center>$jumlah_sekolah</center></strong></td>
	<td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumpelajar)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	//echo "<tr bgcolor='#CCCCCC'> \n";
	//echo "<td colspan='3'><strong>JUMLAH CALON</strong></td><td align=\"right\" colspan='2'><strong><center>$jumAmbil</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (A*1)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumA)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumA*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (B*2)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumB)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumB*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (C*3)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumC)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumC*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (D*4)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumD)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumD*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (E*5)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumE)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumE*5))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>PENGIRAAN </strong></td><td align=\"right\" colspan='2'><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".(number_format($jumA*1+$jumB*2+$jumC*3+$jumD*4+$jumE*5))."/".number_format($jumAmbil)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>GPS KESELURUHAN PPD </strong></td><td align=\"right\" colspan='2'><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>".gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil)."</center></strong></td></tr>";

}//if sr mr
if($status=='MA'){
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\" colspan='2'><strong><center>$jumlah_sekolah</center></strong></td>
	<td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumpelajar)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (AP*0)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumAP)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumAP*0))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (A*1)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumA)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumA*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (AM*2)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumAM)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumAM*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (BP*3)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumBP)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumBP*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (B*4)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumB)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumB*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (CP*5)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumCP)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumCP*5))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (C*6)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumC)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumC*6))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (D*7)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumD)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumD*7))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (E*8)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumE)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumE*8))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (G*9)</strong></td><td align=\"right\" colspan='2'><strong><center>".number_format($jumG)."</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumG*9))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>PENGIRAAN </strong></td><td align=\"right\" colspan='2'><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".(number_format($jumAP*0+$jumA*1+$jumAM*2+$jumBP*3+$jumB*4+$jumCP*5+$jumC*6+$jumD*7+$jumE*8+$jumG*9))."/".number_format($jumAmbil)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>GPS KESELURUHAN PPD </strong></td><td align=\"right\" colspan='2'><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>".gpmpma($jumAP,$jumA,$jumAM,$jumBP,$jumB,$jumCP,$jumC,$jumD,$jumE,$jumG,$jumAmbil)."</center></strong></td></tr>";	
}//if ma
}//flag ppd
?>					
</table>
<?php

  //echo "<tr bgcolor='#CCCCCC'><td colspan=\"3\"><table>";
 // echo "<tr bgcolor='#CCCCCC'><td><strong>JUMLAH SEKOLAH</strong><td><strong>:</strong></td><td><strong>$jumlah_sekolah</strong></td></tr>";
  /*echo "<tr bgcolor='#CCCCCC'><td><strong>JUMLAH SEKOLAH SIAP MENJAWAB / PERATUS</strong><td><strong>:</strong></td><td><strong>$jumlah_siap (".number_format($jumlah_peratus_siap,2,".","")."%)</strong></td></tr>";
  echo "<tr bgcolor='#CCCCCC'><td><strong>JUMLAH SEKOLAH MASALAH</strong><td><strong>:</strong></td><td><strong>$jumlah_masalah</strong></td></tr>";  
  echo "<tr bgcolor='#CCCCCC'><td><strong>ADA PERUBAHAN GPS</strong><td><strong>:</strong></td><td><strong>$jumlah_setuju (".number_format($jumlah_peratus_setuju,2,".","")."%)</strong></td></tr>";
  echo "<tr bgcolor='#CCCCCC'><td><strong>TIADA PERUBAHAN GPS</strong><td><strong>:</strong></td><td><strong>$jumlah_tidaksetuju (".number_format($jumlah_peratus_tidaksetuju,2,".","")."%)</strong></td></tr>";  echo "<tr bgcolor='#CCCCCC'><td><strong>JUMLAH SEKOLAH SIAP MENCETAK / PERATUS</strong><td><strong>:</strong></td><td><strong>$jumlah_siap_cetak (".number_format($jumlah_peratus_cetak,2,".","")."%)</strong></td></tr>";*/
 // echo "</table></td></tr>";

  echo "</table>";
  echo "</td></tr></table>";


?>
