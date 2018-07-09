<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Penentuan Gred Purata Sekolah Tahun <?php echo $tahun;?> (ETR)</p>
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

	echo "<H2><center>HC5 - TINDAKAN SETIAUSAHA PEPERIKSAAN</center></H2>\n";
	echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
	echo "<tr><td><b>SEKOLAH : $namasek</b></td></tr>\n";
	echo "<tr><td><b>$tajuk : $ting</b></td></tr>\n";
	echo "<tr><td><INPUT TYPE=\"BUTTON\" VALUE=\"<< KEMBALI\" ONCLICK=\"history.go(-1)\"></td></tr>\n";
	echo "</table>";
	
	echo "  <table  width=\"98%\"  align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
	echo "  <tr bgcolor=\"#FFCC99\">\n";
	echo "    <td rowspan=\"2\"> <div align=\"center\">BIL</div></td>\n";
	echo "    <td rowspan=\"2\"><div align=\"center\">MATA PELAJARAN</div></td>\n";
	echo "    <td rowspan=\"2\"><div align=\"center\">BIL<br>MURID</div></td>\n";
	if($level=='SR' or $level=='MR'){
	echo "    <td colspan=\"2\"><div align=\"center\">A</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">B</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">C</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">D</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">E</div></td>\n";
		if($tahun==2015 and $ting<>"D6"){
			echo "    <td colspan=\"2\"><div align=\"center\">F</div></td>\n";
		}elseif($tahun>=2016 and $level=='MR'){
			echo "    <td colspan=\"2\"><div align=\"center\">F</div></td>\n";
		}
	}
	if($level=='MA'){
	echo "    <td colspan=\"2\"><div align=\"center\">A+</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">A</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">A-</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">B+</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">B</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">C+</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">C</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">D</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">E</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">G</div></td>\n";
	}
	echo "    <td colspan=\"2\"><div align=\"center\">ETR</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">TOV</div></td>\n";
	echo "  </tr>\n";
	echo "  <tr bgcolor=\"#FFCC99\">\n";
	echo "    <td><div align=\"center\">BIL</div></td>\n";//A
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">BIL</div></td>\n";//B
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">BIL</div></td>\n";//C
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">BIL</div></td>\n";//D
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">BIL</div></td>\n";//E
	echo "    <td><div align=\"center\">%</div></td>\n";
	if($level=='SR' or $level=='MR'){
		if($tahun==2015 and $ting<>"D6"){
			echo "    <td><div align=\"center\">BIL</div></td>\n";//F
			echo "    <td><div align=\"center\">%</div></td>\n";
		}elseif($tahun>=2016 and $level=='MR'){
			echo "    <td><div align=\"center\">BIL</div></td>\n";//F
			echo "    <td><div align=\"center\">%</div></td>\n";
		}
	}
	if($level=='MA'){
	echo "    <td><div align=\"center\">BIL</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">BIL</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">BIL</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">BIL</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">BIL</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";	
	}
	echo "    <td><div align=\"center\">GP</div></td>\n";
	echo "    <td><div align=\"center\">% LULUS</div></td>\n";
	echo "    <td><div align=\"center\">GP</div></td>\n";
	echo "    <td><div align=\"center\">% LULUS</div></td>\n";
	echo "  </tr>\n";
	
	$qsubsek =  oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' ORDER BY kodmp");
	oci_execute($qsubsek);
	$bil=0;
	
	while ($rowmp = oci_fetch_array($qsubsek)){
		$subsek = $rowmp['KODMP'];

		$qhcmurid = "SELECT * FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND hmp='$subsek' and getr is not null";
		$stmt = oci_parse($conn_sispa,$qhcmurid);
		oci_execute($stmt);
		$bilcalon = count_row($qhcmurid);
		
		$biltovAplus=$biltovA=$biltovAminus=$biltovBplus=$biltovB=$biltovCplus=$biltovC=$biltovD=$biltovE=$biltovF=$biltovG=$biltovTH=0;//TOV
	 	$biletrAplus=$biletrA=$biletrAminus=$biletrBplus=$biletrB=$biletrCplus=$biletrC=$biletrD=$biletrE=$biletrF=$biletrG=$biletrTH=0;//ETR
		$jumlahlulusetr=$jumlahlulustov=0;
		while ($record = oci_fetch_array($stmt)){
			switch ($record['GTOV']){//TOV
				case "A+": $biltovAplus++; break;
				case "A" : $biltovA++; break;	
				case "A-": $biltovAminus++; break;	
				case "B+": $biltovBplus++; break;
				case "B" : $biltovB++; break;
				case "C+": $biltovCplus++; break;
				case "C" : $biltovC++; break;
				case "D" : $biltovD++; break;
				case "E" : $biltovE++; break;
				case "F" : $biltovF++; break;
				case "G" : $biltovG++; break;
				case "TH": $biltovTH++; break;
			}
			switch ($record['GETR']){//ETR
				case "A+": $biletrAplus++; break;
				case "A": $biletrA++; break;	
				case "A-": $biletrAminus++; break;	
				case "B+": $biletrBplus++; break;
				case "B": $biletrB++; break;
				case "C+": $biletrCplus++; break;
				case "C": $biletrC++; break;
				case "D": $biletrD++; break;
				case "E": $biletrE++; break;
				case "F": $biletrF++; break;
				case "G": $biletrG++; break;
				case "TH": $biletrTH++; break;
			}
			if($ting=='D1' or $ting=='D2' or $ting=='D3' or $ting=='D4' or $ting=='D5' or $ting=='D6'){
				//echo "3 $subsek<br>";
				if($tahun<=2014){
					if($record['GETR']=='A' or $record['GETR']=='B' or $record['GETR']=='C'){
						$jumlahlulusetr++;
					}
					if($record['GTOV']=='A' or $record['GTOV']=='B' or $record['GTOV']=='C'){
						$jumlahlulustov++;
						//echo $record['GTOV']." $jumlahlulustov $biltovB<br>";
					}
				}elseif($tahun==2015){
					if($ting<>"D6"){
						if($record['GETR']=='A' or $record['GETR']=='B' or $record['GETR']=='C' or $record['GETR']=='D' or $record['GETR']=='E'){
							$jumlahlulusetr++;
						}
						if($record['GTOV']=='A' or $record['GTOV']=='B' or $record['GTOV']=='C' or $record['GTOV']=='D' or $record['GTOV']=='E'){
							$jumlahlulustov++;
						}
					}else{
						if($record['GETR']=='A' or $record['GETR']=='B' or $record['GETR']=='C'){
							$jumlahlulusetr++;
						}
						if($record['GTOV']=='A' or $record['GTOV']=='B' or $record['GTOV']=='C'){
							$jumlahlulustov++;
						}
					}
				}else{//2016 D1-D6
					if($record['GETR']=='A' or $record['GETR']=='B' or $record['GETR']=='C' or $record['GETR']=='D'){
						$jumlahlulusetr++;
					}
					if($record['GTOV']=='A' or $record['GTOV']=='B' or $record['GTOV']=='C' or $record['GTOV']=='D'){
						$jumlahlulustov++;
					}
				}
			}//SR
			if($ting=='P' or $ting=='T1' or $ting=='T2' or $ting=='T3'){
				//echo "4 $subsek<br>";
				if($tahun<=2014){
					if($record['GETR']=='A' or $record['GETR']=='B' or $record['GETR']=='C' or $record['GETR']=='D'){
						$jumlahlulusetr++;
					}
					if($record['GTOV']=='A' or $record['GTOV']=='B' or $record['GTOV']=='C' or $record['GTOV']=='D'){
						$jumlahlulustov++;
					}
				}else{
					if($record['GETR']=='A' or $record['GETR']=='B' or $record['GETR']=='C' or $record['GETR']=='D' or $record['GETR']=='E'){
						$jumlahlulusetr++;
					}
					if($record['GTOV']=='A' or $record['GTOV']=='B' or $record['GTOV']=='C' or $record['GTOV']=='D' or $record['GTOV']=='E'){
						$jumlahlulustov++;
					}
				}
			}//MR
			/* asal 2962015
			if($ting=='T4' or $ting=='T5'){
				if($subsek=="BMMA" or $subsek=="SEJMA"){
					if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!=''){
						$jumlahlulusetr++;
					}
					if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!=''){
						$jumlahlulustov++;
					}
				}else{
					if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!='')
						$jumlahlulusetr++;
					if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!='')
						$jumlahlulustov++;
				}
			}*/
			if($ting=='T4'){
				if($tahun>=2015){//2015 ke atas
					if($subsek=="BMMA" or $subsek=="SEJMA" or $subsek=="BIMA"){
						if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!=''){
							$jumlahlulusetr++;
						}
						if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!=''){
							$jumlahlulustov++;
						}
					}else{//Selain BMMA & SEJMA & BIMA
						if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!=''){
							$jumlahlulusetr++;
						}
						if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!=''){
							$jumlahlulustov++;
						}
					}
				}else{//2014 ke bawah
					if($subsek=="BMMA" or $subsek=="SEJMA"){
						if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!=''){
							$jumlahlulusetr++;
						}
						if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!=''){
							$jumlahlulustov++;
						}
					}else{//Selain BMMA & SEJMA
						if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!=''){
							$jumlahlulusetr++;
						}
						if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!=''){
							$jumlahlulustov++;
						}
					}
				}//if($tahun<=2015)
			}//T4
			
			if($ting=='T5'){
				if($tahun<=2015){//2015 ke atas
					if($subsek=="BMMA" or $subsek=="SEJMA"){
						if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!=''){
							$jumlahlulusetr++;
						}
						if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!=''){
							$jumlahlulustov++;
						}
					}else{//Selain BMMA & SEJMA
						if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!=''){
							$jumlahlulusetr++;
						}
						if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!=''){
							$jumlahlulustov++;
						}
					}
				}else{//2016 ke atas
					if($subsek=="BMMA" or $subsek=="SEJMA" or $subsek=="BIMA"){
						if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!=''){
							$jumlahlulusetr++;
						}
						if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!=''){
							$jumlahlulustov++;
						}
					}else{//Selain BMMA & SEJMA & BIMA
						if($record['GETR']!='G' and $record['GETR']!='TH' and $record['GETR']!=''){
							$jumlahlulusetr++;
						}
						if($record['GTOV']!='G' and $record['GTOV']!='TH' and $record['GTOV']!=''){
							$jumlahlulustov++;
						}
					}
				}//if($tahun<=2015)
			}//T4
			
			
		}

		$querykod = oci_parse($conn_sispa,"SELECT mp FROM $tmatap WHERE kod='$subsek'") or die('Error, query Subjek');
		oci_execute($querykod);
		$resultkod = oci_fetch_array($querykod);
		$namamp=$resultkod['MP'];
		
		if ($bilcalon != 0){
			$peratusetrAplus = number_format(($biletrAplus/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrA = number_format(($biletrA/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrAminus = number_format(($biletrAminus/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrBplus = number_format(($biletrBplus/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrB = number_format(($biletrB/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrCplus = number_format(($biletrCplus/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrC = number_format(($biletrC/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrD = number_format(($biletrD/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrE = number_format(($biletrE/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrF = number_format(($biletrF/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetrG = number_format(($biletrG/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratusetr = number_format(($jumlahlulusetr/($bilcalon-$biletrTH))*100,2,'.',',');
			$peratustov = number_format(($jumlahlulustov/($bilcalon-$biltovTH))*100,2,'.',',');
			//echo $jumlahlulustov." $bilcalon-$biltovTH <br>";
		} else { 
			$peratusetrAplus=$peratusetrA=$peratusetrAminus=$peratusetrBplus=$peratusetrB=$peratusetrCplus=$peratusetrC=$peratusetrD=$peratusetrE=$peratusetrF=$peratusetrG= 0.00 ;
			$peratusetr=$peratustov= 0.00; }
		
		$bil=$bil+1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "<tr bgcolor='$bcol'>\n";
		echo "<td><div align=\"center\">$bil</div></td>\n";
		echo "<td><div align=\"left\">$namamp</div></td>\n";
		echo "<td><div align=\"center\">$bilcalon</div></td>\n";
		if($level=='SR' or $level=='MR'){
		echo "<td><div align=\"center\">$biletrA</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrA</div></td>\n";
		echo "<td><div align=\"center\">$biletrB</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrB</div></td>\n";
		echo "<td><div align=\"center\">$biletrC</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrC</div></td>\n";
		echo "<td><div align=\"center\">$biletrD</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrD</div></td>\n";
		echo "<td><div align=\"center\">$biletrE</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrE</div></td>\n";
		if($level=='SR' or $level=='MR'){
			if($tahun==2015 and $ting<>"D6"){
				echo "<td><div align=\"center\">$biletrF</div></td>\n";
				echo "<td><div align=\"center\">$peratusetrF</div></td>\n";
			}elseif($tahun>=2016 and $level=='MR'){
				echo "<td><div align=\"center\">$biletrF</div></td>\n";
				echo "<td><div align=\"center\">$peratusetrF</div></td>\n";
			}
		}
		}
		if($level=='MA'){
		echo "<td><div align=\"center\">$biletrAplus</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrAplus</div></td>\n";
		echo "<td><div align=\"center\">$biletrA</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrA</div></td>\n";
		echo "<td><div align=\"center\">$biletrAminus</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrAminus</div></td>\n";
		echo "<td><div align=\"center\">$biletrBplus</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrBplus</div></td>\n";
		echo "<td><div align=\"center\">$biletrB</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrB</div></td>\n";
		echo "<td><div align=\"center\">$biletrCplus</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrCplus</div></td>\n";
		echo "<td><div align=\"center\">$biletrC</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrC</div></td>\n";
		echo "<td><div align=\"center\">$biletrD</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrD</div></td>\n";
		echo "<td><div align=\"center\">$biletrE</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrE</div></td>\n";
		echo "<td><div align=\"center\">$biletrG</div></td>\n";
		echo "<td><div align=\"center\">$peratusetrG</div></td>\n";
		}
		if($level=='SR' or $level=='MR'){
		echo "<td><div align=\"center\">".gpmpmrsr_baru($biletrA,$biletrB,$biletrC,$biletrD,$biletrE,$biletrF,$bilcalon)."</div></td>\n";
		}
		if($level=='MA'){
		echo "<td><div align=\"center\">".gpmpma($biletrAplus,$biletrA,$biletrAminus,$biletrBplus,$biletrB,$biletrCplus,$biletrC,$biletrD,$biletrE,$biletrG,$bilcalon)."</div></td>\n";
		}
		echo "<td><div align=\"center\">$jumlahlulusetr <br>$peratusetr</div></td>\n";
		if($level=='SR' or $level=='MR'){
		echo "<td><div align=\"center\">".gpmpmrsr_baru($biltovA,$biltovB,$biltovC,$biltovD,$biltovE,$biltovF,$bilcalon)."</div></td>\n";
		}
		if($level=='MA'){
		echo "<td><div align=\"center\">".gpmpma($biltovAplus,$biltovA,$biltovAminus,$biltovBplus,$biltovB,$biltovCplus,$biltovC,$biltovD,$biltovE,$biltovG,$bilcalon)."</div></td>\n";
		}
		echo "<td><div align=\"center\">$jumlahlulustov <br>$peratustov</div></td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";

} else {
			session_start();
			
			switch ($_SESSION['statussek'])
			{
				case "SR":
					//$level="SR";
					$theadcount="headcountsr";
					$tajuk="DARJAH";
					$tahap="DARJAH";
					$markah="markah_pelajarsr";
					break;

				case "SM" :
					//$level="MR";
					$theadcount="headcount";
					$tajuk="TINGKATAN";
					$tahap="TING";
					$markah="markah_pelajar";
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
		//if($kodsek=='ABB6068')
		//echo $SQL_tkelas;
		$rs_tkelas = oci_parse($conn_sispa,$SQL_tkelas);
		oci_execute($rs_tkelas);
		echo "<select name=\"ting\">";
		echo "<OPTION VALUE=\"\">Pilih Tingkatan/Darjah</OPTION>";
		while($rs_ting = oci_fetch_array($rs_tkelas))
		{			
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


                                                           