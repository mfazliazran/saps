<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Target Murid Cemerlang dan Lulus Semua Mata Pelajaran</p>
<?php
if (isset($_POST['semakhc']))
{	
$ting = $_POST['ting'];	
$tahun = $_SESSION['tahun'];
$kodsek = $_SESSION['kodsek'];
switch ($ting)
{
	case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
	$level="SR";
	$theadcount="headcountsr";
	$tmarkah="markah_pelajarsr";
	$tajuk="DARJAH";
	$tmatap="mpsr";
	$tahap="DARJAH";
	break;
	
	case "P" : case "T1": case "T2": case "T3":
	$level="MR";
	$theadcount="headcount";
	$tmarkah="markah_pelajar";
	$tajuk="TINGKATAN";
	$tmatap="mpsmkc";
	$tahap="TING";
	break;

	case "T4": case "T5":
	$level="MA";
	$theadcount="headcount";
	$tmarkah="markah_pelajar";
	$tajuk="TINGKATAN";
	$tmatap="mpsmkc";
	$tahap="TING";		
	break;

}
$gdata = "SELECT * FROM tsekolah WHERE kodsek='$kodsek'";
$rdata= oci_parse($conn_sispa,$gdata);
oci_execute($rdata);
$resultdata = oci_fetch_array($rdata);
$namasek=$resultdata['NAMASEK'];

	echo "<H2><center>HC6 - TINDAKAN : SETIAUSAHA PEPERIKSAAN</center></H2>\n";
	echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
	echo "<tr><td><b>SEKOLAH : $namasek</b></td></tr>\n";
	echo "<tr><td><b>$tajuk : $ting</b></td></tr>\n";
	echo "<tr><td><INPUT TYPE=\"BUTTON\" VALUE=\"<< KEMBALI\" ONCLICK=\"history.go(-1)\"></td></tr>\n";
	echo "</table>";
	
	echo "  <table  width=\"98%\"  align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
	echo "  <tr bgcolor=\"#FFCC99\">\n";
	echo "    <td rowspan=\"2\"> <div align=\"center\">BIL</div></td>\n";
	echo "    <td rowspan=\"2\"><div align=\"center\">NAMA</div></td>\n";
	echo "    <td rowspan=\"2\"><div align=\"center\">KELAS</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">TOV</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">ETR</div></td>\n";
	echo "  </tr>\n";
	echo "  <tr bgcolor=\"#FFCC99\">\n";
	echo "    <td><div align=\"center\">PENCAPAIAN</div></td>\n";
	echo "    <td><div align=\"center\">GP</div></td>\n";
	echo "    <td><div align=\"center\">PENCAPAIAN</div></td>\n";
	echo "    <td><div align=\"center\">GP</div></td>\n";
	echo "  </tr>\n";
	
	//dapatkan senarai pelajar dari list headcount
	$qhcmurid = "SELECT distinct nokp,nama,kelas FROM $theadcount WHERE kodsek='$kodsek' AND tahun='$tahun' AND $tahap='$ting' and getr is not null group by nokp,nama,kelas order by kelas, nama";
	//echo $qhcmurid."<br>";
	$stmt = oci_parse($conn_sispa,$qhcmurid);
	oci_execute($stmt);
	//$bilcalonetr = count_row($qhcmurid);

	$jumtov12A=$jumtov11A=$jumtov10A=$jumtov9A=$jumtov8A=$jumtov7A=$jumtov6A=$jumtov5A=0;
	$jumetr12A=$jumetr11A=$jumetr10A=$jumetr9A=$jumetr8A=$jumetr7A=$jumetr6A=$jumetr5A=0;
	$jumalltovA=$jumalletrA=0;
	$jumtov12AAll=$jumtov11AAll=$jumtov10AAll=$jumtov9AAll=$jumtov8AAll=$jumtov7AAll=0;
	$jumetr12AAll=$jumetr11AAll=$jumetr10AAll=$jumetr9AAll=$jumetr8AAll=$jumetr7AAll=0;
	$bilcalon=0;
	while ($record = oci_fetch_array($stmt)){
		$bilcalon++;
		$nokp = $record["NOKP"];
		$nama = $record["NAMA"];
		$kelas = $record["KELAS"];
		$qhc = "SELECT * FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' and nokp='$nokp' and getr is not null";	
		// and not(hmp='SEJMA' and gtov='TH') and not(hmp='SEJMA' and gtov='G') and not (hmp='BMMA' and gtov='TH') and not (hmp='BMMA' and gtov='G') 
		$stmthc = oci_parse($conn_sispa,$qhc);
		oci_execute($stmthc);
		
		$biltovAplus=$biltovA=$biltovAminus=$biltovBplus=$biltovB=$biltovCplus=$biltovC=$biltovD=$biltovE=$biltovF=$biltovG=$biltovTH=0;//TOV
	 	$biletrAplus=$biletrA=$biletrAminus=$biletrBplus=$biletrB=$biletrCplus=$biletrC=$biletrD=$biletrE=$biletrF=$biletrG=$biletrTH=0;//ETR
		$pencapaian=$pencapaianETR="";
		$bilmplulustov=$bilmpgagaltov=$bilmplulusetr=$bilmpgagaletr=$bilmp=0;
		while ($rcdhc = oci_fetch_array($stmthc)){
			$kodmp = $rcdhc['HMP'];
			$bilmp++;
			if($level=='MA'){
				if(($kodmp=='BMMA' or $kodmp=='SEJMA') and ($rcdhc['GTOV']=='G' or $rcdhc['GTOV']=='TH') and $tahun<=2015){
					$bilmpgagaltov++;
				}elseif(($kodmp=='BMMA' or $kodmp=='SEJMA' or $kodmp=='BIMA') and ($rcdhc['GTOV']=='G' or $rcdhc['GTOV']=='TH') and $tahun>=2016){
					$bilmpgagaltov++;
				}else{
					$bilmplulustov++;
					switch ($rcdhc['GTOV']){//TOV
						case "A+": $biltovAplus++; break;
						case "A": $biltovA++; break;	
						case "A-": $biltovAminus++; break;	
						case "B+": $biltovBplus++; break;
						case "B": $biltovB++; break;
						case "C+": $biltovCplus++; break;
						case "C": $biltovC++; break;
						case "D": $biltovD++; break;
						case "E": $biltovE++; break;
						case "G": $biltovG++; break;
						case "TH": $biltovTH++; break;
					}//switch TOV
				}
			}
			if($level=='MR'){
				//if(($rcdhc['GTOV']=='E' or $rcdhc['GTOV']=='TH') and $level=='MR'){
				if(($rcdhc['GTOV']=='E' or $rcdhc['GTOV']=='TH') and $tahun<=2014){
					$bilmpgagaltov++;
				}elseif(($rcdhc['GTOV']=='F' or $rcdhc['GTOV']=='TH') and $tahun>=2015){
					$bilmpgagaltov++;
				}else{
					$bilmplulustov++;
					switch ($rcdhc['GTOV']){//TOV
						case "A": $biltovA++; break;	
						case "B": $biltovB++; break;
						case "C": $biltovC++; break;
						case "D": $biltovD++; break;
						case "E": $biltovE++; break;
						case "F": $biltovF++; break;
						case "G": $biltovG++; break;
						case "TH": $biltovTH++; break;
					}//switch TOV
				}
			}
			if($level=='SR'){
				if(($rcdhc['GTOV']=='D' or $rcdhc['GTOV']=='E' or $rcdhc['GTOV']=='TH') and $tahun!=2015){//D1-D6 2014 ke bawah
					$bilmpgagaltov++;
				}elseif(($rcdhc['GTOV']=='D' or $rcdhc['GTOV']=='E' or $rcdhc['GTOV']=='TH') and $tahun==2015 and $ting=='D6'){//2015 D6
					$bilmpgagaltov++;
				}elseif(($rcdhc['GTOV']=='F' or $rcdhc['GTOV']=='TH') and $tahun==2015 and $ting<>'D6'){//2015 D1-D5
					$bilmpgagaltov++;
				}elseif(($rcdhc['GTOV']=='E' or $rcdhc['GTOV']=='TH') and $tahun>=2016){//2016 ke atas D1-D6
					$bilmpgagaltov++;
				}
				else{
					$bilmplulustov++;
					switch ($rcdhc['GTOV']){//TOV
						case "A": $biltovA++; break;	
						case "B": $biltovB++; break;
						case "C": $biltovC++; break;
						case "D": $biltovD++; break;
						case "E": $biltovE++; break;
						case "F": $biltovF++; break;
						case "G": $biltovG++; break;
						case "TH": $biltovTH++; break;
					}//switch TOV
				}//if kodmp gtov
			}
			
			//ETR
			if($level=='MA'){
				if(($kodmp=='BMMA' or $kodmp=='SEJMA') and ($rcdhc['GETR']=='G' or $rcdhc['GETR']=='TH')){
					$bilmpgagaletr++;
				}else{
					$bilmplulusetr++;
					switch ($rcdhc['GETR']){//ETR
						case "A+": $biletrAplus++; break;
						case "A": $biletrA++; break;	
						case "A-": $biletrAminus++; break;	
						case "B+": $biletrBplus++; break;
						case "B": $biletrB++; break;
						case "C+": $biletrCplus++; break;
						case "C": $biletrC++; break;
						case "D": $biletrD++; break;
						case "E": $biletrE++; break;
						case "G": $biletrG++; break;
						case "TH": $biletrTH++; break;
					}//switch TOV
				}
			}
			if($level=='MR'){
				//if(($rcdhc['GETR']=='E' or $rcdhc['GETR']=='TH') and $level=='MR'){
				if(($rcdhc['GETR']=='E' or $rcdhc['GETR']=='TH') and $tahun<=2014){
					$bilmpgagaletr++;
				}elseif(($rcdhc['GETR']=='F' or $rcdhc['GETR']=='TH') and $tahun>=2015){
					$bilmpgagaletr++;
				}else{
					$bilmplulusetr++;
					switch ($rcdhc['GETR']){//ETR
						case "A": $biletrA++; break;	
						case "B": $biletrB++; break;
						case "C": $biletrC++; break;
						case "D": $biletrD++; break;
						case "E": $biletrE++; break;
						case "F": $biletrF++; break;
						case "G": $biletrG++; break;
						case "TH": $biletrTH++; break;
					}//switch TOV
				}
			}
			if($level=='SR'){
				//if(($rcdhc['GETR']=='D' or $rcdhc['GETR']=='E' or $rcdhc['GETR']=='TH')){
					//$bilmpgagaletr++;
				if(($rcdhc['GETR']=='D' or $rcdhc['GETR']=='E' or $rcdhc['GETR']=='TH') and $tahun!=2015){//D1-D6 2014 ke bawah
					$bilmpgagaletr++;
				}elseif(($rcdhc['GETR']=='D' or $rcdhc['GETR']=='E' or $rcdhc['GETR']=='TH') and $tahun==2015 and $ting=='D6'){//2015 D6
					$bilmpgagaletr++;
				}elseif(($rcdhc['GETR']=='F' or $rcdhc['GETR']=='TH') and $tahun==2015 and $ting<>'D6'){//2015 D1-D5
					$bilmpgagaletr++;
				}elseif(($rcdhc['GETR']=='E' or $rcdhc['GETR']=='TH') and $tahun>=2016){//2016 ke atas D1-D6
					$bilmpgagaletr++;
				}
				else{
					$bilmplulusetr++;
					switch ($rcdhc['GETR']){//ETR
						case "A": $biletrA++; break;	
						case "B": $biletrB++; break;
						case "C": $biletrC++; break;
						case "D": $biletrD++; break;
						case "E": $biletrE++; break;
						case "F": $biletrF++; break;
						case "G": $biletrG++; break;
						case "TH": $biletrTH++; break;
					}//switch TOV
				}//if kodmp GETR
			}//if ME
		}//while rcdhc		
		
		//Pencapaian TOV
		if($biltovAplus != ''){ $pencapaian = "".$pencapaian."".$biltovAplus."[A+] "; }
		if($biltovA != ''){ $pencapaian = "".$pencapaian."".$biltovA."[A] ";}
		if($biltovAminus != ''){ $pencapaian = "".$pencapaian."".$biltovAminus."[A-] "; }
		if($biltovBplus != ''){ $pencapaian = "".$pencapaian."".$biltovBplus."[B+] "; }
		if($biltovB != ''){ $pencapaian = "".$pencapaian."".$biltovB."[B] "; }
		if($biltovCplus != ''){ $pencapaian = "".$pencapaian."".$biltovCplus."[C+] "; }
		if($biltovC != ''){ $pencapaian = "".$pencapaian."".$biltovC."[C] "; }	
		if($biltovD != ''){ $pencapaian = "".$pencapaian."".$biltovD."[D] ";; }
		if($biltovE != ''){ $pencapaian = "".$pencapaian."".$biltovE."[E] "; }
		if($biltovF != ''){ $pencapaian = "".$pencapaian."".$biltovF."[F] "; }
		if($biltovG != ''){ $pencapaian = "".$pencapaian."".$biltovG."[G] "; }	
		if($biltovTH != ''){ $pencapaian = "".$pencapaian."".$biltovTH."[TH] "; }
		
		//Pencapaian ETR
		if($biletrAplus != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrAplus."[A+] "; }
		if($biletrA != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrA."[A] "; }
		if($biletrAminus != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrAminus."[A-] "; }
		if($biletrBplus != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrBplus."[B+] "; }
		if($biletrB != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrB."[B] "; }
		if($biletrCplus != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrCplus."[C+] "; }
		if($biletrC != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrC."[C] "; }	
		if($biletrD != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrD."[D] ";; }
		if($biletrE != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrE."[E] "; }
		if($biletrF != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrF."[F] "; }
		if($biletrG != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrG."[G] "; }	
		if($biletrTH != ''){ $pencapaianETR = "".$pencapaianETR."".$biletrTH."[TH] "; }
		
		//TOV
		if(($biltovAplus + $biltovA + $biltovAminus)=='12' and ($biltovAplus + $biltovA + $biltovAminus)==$bilmplulustov)
			$jumtov12AAll++;
		if($biltovA=='12' and $biltovA==$bilmplulustov)
			$jumtov12A++;
		if(($biltovAplus + $biltovA + $biltovAminus)=='11' and ($biltovAplus + $biltovA + $biltovAminus)==$bilmplulustov)
			$jumtov11AAll++;
		if($biltovA=='11' and $biltovA==$bilmplulustov)
			$jumtov11A++;
		if(($biltovAplus + $biltovA + $biltovAminus)=='10' and ($biltovAplus + $biltovA + $biltovAminus)==$bilmplulustov)
			$jumtov10AAll++;
		if($biltovA=='10' and $biltovA==$bilmplulustov)
			$jumtov10A++;
		if(($biltovAplus + $biltovA + $biltovAminus)=='9' and ($biltovAplus + $biltovA + $biltovAminus)==$bilmplulustov)
			$jumtov9AAll++;
		if($biltovA=='9' and $biltovA==$bilmplulustov)
			$jumtov9A++;
		if(($biltovAplus + $biltovA + $biltovAminus)=='8' and ($biltovAplus + $biltovA + $biltovAminus)==$bilmplulustov)
			$jumtov8AAll++;
		if($biltovA=='8' and $biltovA==$bilmplulustov)
			$jumtov8A++;
		if(($biltovAplus + $biltovA + $biltovAminus)=='7' and ($biltovAplus + $biltovA + $biltovAminus)==$bilmplulustov)
			$jumtov7AAll++;
		if($biltovA=='7' and $biltovA==$bilmplulustov)
			$jumtov7A++;
		if($biltovA=='6' and $biltovA==$bilmplulustov)
			$jumtov6A++;
		if($biltovA=='5' and $biltovA==$bilmplulustov)
			$jumtov5A++;
		
		//ETR
		if(($biletrAplus + $biletrA + $biletrAminus)=='12' and ($biletrAplus + $biletrA + $biletrAminus)==$bilmplulusetr)
			$jumetr12AAll++;
		if($biletrA=='12' and $biletrA==$bilmplulusetr)
			$jumetr12A++;
		if(($biletrAplus + $biletrA + $biletrAminus)=='11' and ($biletrAplus + $biletrA + $biletrAminus)==$bilmplulusetr)
			$jumetr11AAll++;
		if($biletrA=='11' and $biletrA==$bilmplulusetr)
			$jumetr11A++;
		if(($biletrAplus + $biletrA + $biletrAminus)=='10' and ($biletrAplus + $biletrA + $biletrAminus)==$bilmplulusetr)
			$jumetr10AAll++;
		if($biletrA=='10' and $biletrA==$bilmplulusetr)
			$jumetr10A++;
		if(($biletrAplus + $biletrA + $biletrAminus)=='9' and ($biletrAplus + $biletrA + $biletrAminus)==$bilmplulusetr)
			$jumetr9AAll++;
		if($biletrA=='9' and $biletrA==$bilmplulusetr)
			$jumetr9A++;
		if(($biletrAplus + $biletrA + $biletrAminus)=='8' and ($biletrAplus + $biletrA + $biletrAminus)==$bilmplulusetr)
			$jumetr18AAll++;
		if($biletrA=='8' and $biletrA==$bilmplulusetr)
			$jumetr8A++;
		if(($biletrAplus + $biletrA + $biletrAminus)=='7' and ($biletrAplus + $biletrA + $biletrAminus)==$bilmplulusetr)
			$jumetr7AAll++;
		if($biletrA=='7' and $biletrA==$bilmplulusetr)
			$jumetr7A++;
		if($biletrA=='6' and $biletrA==$bilmplulusetr)
			$jumetr6A++;
		if($biletrA=='5' and $biletrA==$bilmplulusetr)
			$jumetr5A++;
			
		if($level=='SR' or $level=='MR'){
		$gptov = gpmpmrsr_baru($biltovA,$biltovB,$biltovC,$biltovD,$biltovE,$biltovF,$bilmplulustov);
		$gpetr = gpmpmrsr_baru($biletrA,$biletrB,$biletrC,$biletrD,$biletrE,$biletrF,$bilmplulusetr);
		$jumalltovA = $jumtov12A+$jumtov11A+$jumtov10A+$jumtov9A+$jumtov8A+$jumtov7A;
		$jumalletrA = $jumetr12A+$jumetr11A+$jumetr10A+$jumetr9A+$jumetr8A+$jumetr7A;
		}
		if($level=='MA'){
		$gptov = gpmpma($biltovAplus,$biltovA,$biltovAminus,$biltovBplus,$biltovB,$biltovCplus,$biltovC,$biltovD,$biltovE,$biltovG,$bilmplulustov);
		$gpetr = gpmpma($biletrAplus,$biletrA,$biletrAminus,$biletrBplus,$biletrB,$biletrCplus,$biletrC,$biletrD,$biletrE,$biletrG,$bilmplulusetr);
		$jumalltovA = $jumtov12AAll+$jumtov12A+$jumtov11AAll+$jumtov11A+$jumtov10AAll+$jumtov10A+$jumtov9AAll+$jumtov9A+$jumtov8AAll+$jumtov8A+$jumtov7AAll+$jumtov7A;
		$jumalletrA = $jumetr12AAll+$jumetr12A+$jumetr11AAll+$jumetr11A+$jumetr10AAll+$jumetr10A+$jumetr9AAll+$jumetr9A+$jumetr8AAll+$jumetr8A+$jumetr7AAll+$jumetr7A;
		}
		
		$penilaian[] = array("NAMA" =>$nama,"NOKP" =>$nokp, "KELAS" =>$kelas, "PENCAPAIANTOV" =>$pencapaian, "GPTOV" =>$gptov, "PENCAPAIANETR" =>$pencapaianETR,"GPETR" =>$gpetr, "BILMP" =>$bilmp, "BILMPLULUSETR" =>$bilmplulusetr, "BILMPLULUSTOV" =>$bilmplulustov);
		
	}//record
	
	foreach ($penilaian as $key => $row) {
	   $result[$key]  = $row["GPETR"];
	}
	array_multisort($result, SORT_ASC, $penilaian);
	
	foreach ($penilaian as $key => $row) {	   
		if($row["BILMPLULUSETR"]==$row["BILMP"]){	
		$bil=$bil+1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		//echo $row["NAMA"]." - ".$row["BILMPLULUSTOV"]."-".$row["BILMP"]." - ".$row["PENCAPAIANTOV"]."<br>";
		if($row["BILMPLULUSTOV"]==$row["BILMP"]){
			$billulustov++;
		}
		if($row["BILMPLULUSETR"]==$row["BILMP"]){
			$billulusetr++;
		}
		echo "<tr bgcolor='$bcol'>\n";
		echo "<td><div align=\"center\">$bil</div></td>\n";
		echo "<td><div align=\"left\">".$row["NAMA"]." ".$row["NOKP"]."</div></td>\n";
		echo "<td><div align=\"center\">".$row["KELAS"]."</div></td>\n";
		echo "<td><div align=\"center\">".$row["PENCAPAIANTOV"]."</div></td>\n";
		echo "<td><div align=\"center\">".$row["GPTOV"]."</div></td>\n";
		echo "<td><div align=\"center\">".$row["PENCAPAIANETR"]."</div></td>\n";
		echo "<td><div align=\"center\">".$row["GPETR"]."</div></td>\n";
		echo "</tr>\n";
		}//lulus sahaja dipaparkan
	}
	echo "</table>\n";
//if($kodsek=='BEA7610'){
//####################### RUMUSAN #######################///
echo "<br>";
echo "<br>";
echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "<tr><td><b>MURID MENDAPAT SEMUA A DAN LULUS SEMUA MATA PELAJARAN</b></td></tr>\n";
echo "</table>";
// RUMUSAN UNTUK SR DAN MR
if($level=='SR'){
	include 'h6_sr.php';
}
if($level=='MR'){
	include 'h6_mr.php';
}
if($level=='MA'){
	include 'h6_ma.php';	
}
//}//if kodsek

} else {
	session_start();
	switch ($_SESSION['statussek'])
	{
		case "SR":
			$theadcount="headcountsr";
			$tahap="DARJAH";
			break;

	case "SM" :
			$theadcount="headcount";
			$tahap="TING";
			break;
	}
	echo " <center><h3>SILA PILIH TINGKATAN/DARJAH</h3></center>";
	echo "<form method=\"post\">\n";
	echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
	echo "  <tr bgcolor=\"#CCCCCC\">\n";
	echo "  <td colspan=\"2\"><center>TINGKATAN/DARJAH</center></td>\n";
	echo " </tr>";
	echo "  <tr bgcolor=\"#CCCCCC\">\n";
	echo "  <td align=\"center\">\n";
	$SQL_tkelas = "SELECT DISTINCT $tahap FROM $theadcount WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY $tahap";
	$rs_tkelas = oci_parse($conn_sispa,$SQL_tkelas);
	oci_execute($rs_tkelas);
	echo "<select name=\"ting\">";
	echo "<OPTION VALUE=\"\">Pilih Tingkatan/Darjah</OPTION>";
	while($rs_ting = oci_fetch_array($rs_tkelas)){			
		echo "<OPTION VALUE=\"".$rs_ting["$tahap"]."\">".$rs_ting["$tahap"]."</OPTION>";
	}
	echo " </select></th>\n";
	echo "  <td><center><input type=\"submit\" id=\"semakhc\" name=\"semakhc\" value=\"Hantar\" Alt=\"Hantar\"></td>\n";
	echo "</table>\n";
	echo "</form>";
}


?>
</td>
<?php include 'kaki.php';?> 


                                                           