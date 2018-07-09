<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Headcount Mata Pelajaran Kelas</p>
<?php
$kodsek = $_SESSION["kodsek2"];
$namasek = $_SESSION["namasekolah"];

if (isset($_POST['hc']))
{
		$ting = $_POST['ting'];
		$kelas = $_POST['kelas'];
		$mp = $_POST['kodmp'];
		$gmp = "G$mp";
		$tahun = $_SESSION['tahun'];
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

		$qnamamp = OCIParse($conn_sispa,"SELECT * FROM $tmatap WHERE kod='$mp'");
		OCIExecute($qnamamp);
		OCIFetch($qnamamp); //$nmp
		$namamp=OCIResult($qnamamp,"MP");//$nmp['mp'];

		if(($ting !='') AND ($kelas !='') AND($mp != '')) {
			echo "<H2><center>ANALISIS HEADCOUNT MATA PELAJARAN MENGIKUT KELAS<br>TAHUN $tahun</center></H2>\n";
			echo "  <table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"6\" cellspacing=\"0\" bordercolor=\"#999999\"><tr>\n";
			echo " <td><b>SEKOLAH :</b> $namasek</td></tr>\n";
			echo " <td><b>$tajuk :</b> $ting $kelas</td></tr>\n";
			echo " <td><b>MATA PELAJARAN :</b> $namamp</td>\n";
			echo "</table>";
			echo " <br>";
			
////////////////////////////////////////// BLOK MENENGAH RENDAH & SEKOLAH RENDAH  ///////////////////////////////////////////////////////////
		if (($level=="MR") OR ($level=="SR")){

			$qbcalon = "SELECT * FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$mp'";
			$qbc = OCIParse($conn_sispa,$qbcalon);
			OCIExecute($qbc);
			$bilcalon = count_row($qbcalon);
			$biltovA = $biltovB = $biltovC = $biltovD = $biltovE = $biltovTH = $biltovL = 0;
			$bilotr1A = $bilotr1B = $bilotr1C = $bilotr1D = $bilotr1E = $bilotr1TH = $bilotr1L = 0;
			$bilotr2A = $bilotr2B = $bilotr2C = $bilotr2D = $bilotr2E = $bilotr2TH = $bilotr2L = 0;
			$bilotr3A = $bilotr3B = $bilotr3C = $bilotr3D = $bilotr3E = $bilotr3TH = $bilotr3L = 0;
			$bilatr1A = $bilatr1B = $bilatr1C = $bilatr1D = $bilatr1E = $bilatr1TH = $bilatr1L = 0;
			$bilatr2A = $bilatr2B = $bilatr2C = $bilatr2D = $bilatr2E = $bilatr2TH = $bilatr2L = 0;
			$bilatr3A = $bilatr3B = $bilatr3C = $bilatr3D = $bilatr3E = $bilatr3TH = $bilatr3L = 0;
			$biletrA = $biletrB = $biletrC = $biletrD = $biletrE = $biletrTH = $biletrL = 0;
			/////////////////////////////////////// CARI BIL TOV BARU ////////////////////////
			while (OCIFetch($qbc))
			{
				switch (/*$rowhc['gtov']*/trim(OCIResult($qbc,"GTOV")))
				{
					case 'A'  : $biltovA = $biltovA + 1; break;
					case 'B'  : $biltovB = $biltovB + 1; break;
					case 'C'  : $biltovC = $biltovC + 1; break;
					case 'D'  : $biltovD = $biltovD + 1; break;
					case 'E'  : $biltovE = $biltovE + 1; break;
					case 'TH' : $biltovTH = $biltovTH + 1; break;
				}
				switch (trim(OCIResult($qbc,"GOTR1"))/*$rowhc['gotr1']*/)
				{
					case 'A'  : $bilotr1A = $bilotr1A + 1; break;
					case 'B'  : $bilotr1B = $bilotr1B + 1; break;
					case 'C'  : $bilotr1C = $bilotr1C + 1; break;
					case 'D'  : $bilotr1D = $bilotr1D + 1; break;
					case 'E'  : $bilotr1E = $bilotr1E + 1; break;
					case 'TH' : $bilotr1TH = $bilotr1TH + 1; break;
				}
				switch (trim(OCIResult($qbc,"GOTR2"))/*$rowhc['gotr2']*/)
				{
					case 'A'  : $bilotr2A = $bilotr2A + 1; break;
					case 'B'  : $bilotr2B = $bilotr2B + 1; break;
					case 'C'  : $bilotr2C = $bilotr2C + 1; break;
					case 'D'  : $bilotr2D = $bilotr2D + 1; break;
					case 'E'  : $bilotr2E = $bilotr2E + 1; break;
					case 'TH' : $bilotr2TH = $bilotr2TH + 1; break;
				}
				switch (trim(OCIResult($qbc,"GOTR3"))/*$rowhc['gotr3']*/)
				{
					case 'A'  : $bilotr3A = $bilotr3A + 1; break;
					case 'B'  : $bilotr3B = $bilotr3B + 1; break;
					case 'C'  : $bilotr3C = $bilotr3C + 1; break;
					case 'D'  : $bilotr3D = $bilotr3D + 1; break;
					case 'E'  : $bilotr3E = $bilotr3E + 1; break;
					case 'TH' : $bilotr3TH = $bilotr3TH + 1; break;
				}
				switch (trim(OCIResult($qbc,"GETR"))/*$rowhc['getr']*/)
				{
					case 'A'  : $biletrA = $biletrA + 1; break;
					case 'B'  : $biletrB = $biletrB + 1; break;
					case 'C'  : $biletrC = $biletrC + 1; break;
					case 'D'  : $biletrD = $biletrD + 1; break;
					case 'E'  : $biletrE = $biletrE + 1; break;
					case 'TH' : $biletrTH = $biletrTH + 1; break;
				}
			}
			if ($level == "MR")
			{ 
				$biltovL = $biltovA + $biltovB + $biltovC + $biltovD;
				$bilotr1L = $bilotr1A + $bilotr1B + $bilotr1C + $bilotr1D;
				$bilotr2L = $bilotr2A + $bilotr2B + $bilotr2C + $bilotr2D;
				$bilotr3L = $bilotr3A + $bilotr3B + $bilotr3C + $bilotr3D;
				$biletrL = $biletrA + $biletrB + $biletrC + $biletrD;
			}
								 
			if ($level == "SR")
			{ 
				$biltovL = $biltovA + $biltovB + $biltovC; 
				$bilotr1L = $bilotr1A + $bilotr1B + $bilotr1C;
				$bilotr2L = $bilotr2A + $bilotr2B + $bilotr2C;
				$bilotr3L = $bilotr3A + $bilotr3B + $bilotr3C;
				$biletrL = $biletrA + $biletrB + $biletrC;
			}
			///////////////////////////////////// TAMAT CARI TOV BARU //////////////////////////
            ////////////////////////////// ANALISA ATR1  ///////////////////////////////////////////		

			$qrytentuhcatr1 = OCIParse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='ATR1'");
			OCIExecute($qrytentuhcatr1);
			OCIFetch($qrytentuhcatr1);	//$rowtentuatr1
			$jpepatr1=trim(OCIResult($qrytentuhcatr1,"JENPEP"))/*$rowtentuatr1['jenpep']*/; 
			$tahunatr1=/*$rowtentuatr1['tahuntov']*/trim(OCIResult($qrytentuhcatr1,"TAHUNTOV")); 
			$tingatr1=/*$rowtentuatr1['tingtov']*/trim(OCIResult($qrytentuhcatr1,"TINGTOV"));
			$qryatr1 = "SELECT * FROM $tmarkah WHERE kodsek='".$_SESSION['kodsek2']."' AND $tahap='$tingatr1' AND kelas='$kelas' AND jpep='$jpepatr1' AND tahun='$tahunatr1' AND $mp IS NOT NULL";// $mp!=''";
			//if($kodsek='BEA8638')
				//echo $qryatr1."<br>";
			$qry = OCIParse($conn_sispa,$qryatr1);
			OCIExecute($qry);
			$bilatr1 = count_row($qryatr1);
			while (OCIFetch($qry)) // $rowatr1
			{
				switch (trim(OCIResult($qry,"$gmp"))/*$rowatr1["$gmp"]*/)
				{
					case 'A'  : $bilatr1A = $bilatr1A + 1; break;
					case 'B'  : $bilatr1B = $bilatr1B + 1; break;
					case 'C'  : $bilatr1C = $bilatr1C + 1; break;
					case 'D'  : $bilatr1D = $bilatr1D + 1; break;
					case 'E'  : $bilatr1E = $bilatr1E + 1; break;
					case 'TH' : $bilatr1TH = $bilatr1TH + 1; break;
				}
			}
			if ($level == "MR"){ $bilatr1L = $bilatr1A + $bilatr1B + $bilatr1C + $bilatr1D; }
			if ($level == "SR"){ $bilatr1L = $bilatr1A + $bilatr1B + $bilatr1C; }

			////////////////////////////// ANALISA ATR2  ///////////////////////////////////////////		

			$qrytentuhcatr2 = OCIParse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='ATR2'");
			OCIExecute($qrytentuhcatr2);
			OCIFetch($qrytentuhcatr2); // $rowtentuatr2	
			$jpepatr2=/*$rowtentuatr2['jenpep']*/trim(OCIResult($qrytentuhcatr2,"JENPEP")); 
			$tahunatr2=/*$rowtentuatr2['tahuntov']*/trim(OCIResult($qrytentuhcatr2,"TAHUNTOV")); 
			$tingatr2=/*$rowtentuatr2['tingtov']*/trim(OCIResult($qrytentuhcatr2,"TINGTOV"));
			$qryatr2 = "SELECT * FROM $tmarkah WHERE kodsek='".$_SESSION['kodsek2']."' AND $tahap='$tingatr2' AND kelas='$kelas' AND jpep='$jpepatr2' AND tahun='$tahunatr2' AND $mp IS NOT NULL";//$mp!=''";
			//if($kodsek='BEA8638')
				//echo $qryatr2."<br>";
			$qry2 = OCIParse($conn_sispa,$qryatr2);
			OCIExecute($qry2);
			$bilatr2 = count_row($qryatr2);
			while (trim(OCIFetch($qry2))) //$woratr2
			{
				switch (/*$rowatr2["$gmp"]*/trim(OCIResult($qry2,"$gmp")))
				{
					case 'A'  : $bilatr2A = $bilatr2A + 1; break;
					case 'B'  : $bilatr2B = $bilatr2B + 1; break;
					case 'C'  : $bilatr2C = $bilatr2C + 1; break;
					case 'D'  : $bilatr2D = $bilatr2D + 1; break;
					case 'E'  : $bilatr2E = $bilatr2E + 1; break;
					case 'TH' : $bilatr2TH = $bilatr2TH + 1; break;
				}
			}
			if ($level == "MR"){ $bilatr2L = $bilatr2A + $bilatr2B + $bilatr2C + $bilatr2D; }
			if ($level == "SR"){ $bilatr2L = $bilatr2A + $bilatr2B + $bilatr2C; }
			
			////////////////////////////// ANALISA ATR3  ///////////////////////////////////////////		

			$qrytentuhcatr3 = OCIParse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='ATR3'");
			OCIExecute($qrytentuhcatr3);
			OCIFetch($qrytentuhcatr3);	//$rowtentuatr3
			$jpepatr3=/*$rowtentuatr3['jenpep']*/OCIResult($qrytentuhcatr3,"JENPEP"); $tahunatr3=/*$rowtentuatr3['tahuntov']*/OCIResult($qrytentuhcatr3,"TAHUNTOV"); $tingatr3=/*$rowtentuatr3['tingtov']*/OCIResult($qrytentuhcatr3,"TINGTOV");
			$qryatr3 = "SELECT * FROM $tmarkah WHERE kodsek='".$_SESSION['kodsek2']."' AND $tahap='$tingatr3' AND kelas='$kelas' AND jpep='$jpepatr3' AND tahun='$tahunatr3' AND  $mp IS NOT NULL";//$mp!=''";
			$qry3 = OCIParse($conn_sispa,$qryatr3);
			OCIExecute($qry3);
			$bilatr3 = count_row($qryatr3);
			while (OCIFetch($qry3)) //$rowatr3
			{
				switch (/*$rowatr3["$gmp"]*/OCIResult($qry3,"$gmp"))
				{
					case 'A'  : $bilatr3A = $bilatr3A + 1; break;
					case 'B'  : $bilatr3B = $bilatr3B + 1; break;
					case 'C'  : $bilatr3C = $bilatr3C + 1; break;
					case 'D'  : $bilatr3D = $bilatr3D + 1; break;
					case 'E'  : $bilatr3E = $bilatr3E + 1; break;
					case 'TH' : $bilatr3TH = $bilatr3TH + 1; break;
				}
			}
			if ($level == "MR"){ $bilatr3L = $bilatr3A + $bilatr3B + $bilatr3C + $bilatr3D; }
			if ($level == "SR"){ $bilatr3L = $bilatr3A + $bilatr3B + $bilatr3C; }
			
			///////////////////////////  PENGIRAAN PERATUS DAN GPK MENENGAH RENDAH/SEKOLAH RENDAH  ////////////////////////////
			if ($bilcalon != 0){
				$peratustovA = number_format(($biltovA/($bilcalon - $biltovT))*100,2,'.',',');
				$peratustovB = number_format(($biltovB/($bilcalon - $biltovTH))*100,2,'.',',');
				$peratustovC = number_format(($biltovC/($bilcalon - $biltovTH))*100,2,'.',',');
				$peratustovD = number_format(($biltovD/($bilcalon - $biltovTH))*100,2,'.',',');
				$peratustovE = number_format(($biltovE/($bilcalon - $biltovTH))*100,2,'.',',');
				$peratustovL = number_format(($biltovL/($bilcalon - $biltovTH))*100,2,'.',',');
				$gpktov = number_format((($biltovA*1)+($biltovB*2)+($biltovC*3)+($biltovD*4)+($biltovE*5))/($bilcalon - $biltovTH),2,'.',',');
				
				$peratusotr1A = number_format(($bilotr1A/($bilcalon - $bilotr1TH))*100,2,'.',',');
				$peratusotr1B = number_format(($bilotr1B/($bilcalon - $bilotr1TH))*100,2,'.',',');
				$peratusotr1C = number_format(($bilotr1C/($bilcalon - $bilotr1TH))*100,2,'.',',');
				$peratusotr1E = number_format(($bilotr1E/($bilcalon - $bilotr1TH))*100,2,'.',',');
				$peratusotr1D = number_format(($bilotr1D/($bilcalon - $bilotr1TH))*100,2,'.',',');
				$peratusotr1L = number_format(($bilotr1L/($bilcalon - $bilotr1TH))*100,2,'.',',');
				$gpkotr1 = number_format((($bilotr1A*1)+($bilotr1B*2)+($bilotr1C*3)+($bilotr1D*4)+($bilotr1E*5))/($bilcalon - $bilotr1TH),2,'.',',');

				$peratusotr2A = number_format(($bilotr2A/($bilcalon - $bilotr2TH))*100,2,'.',',');
				$peratusotr2B = number_format(($bilotr2B/($bilcalon - $bilotr2TH))*100,2,'.',',');
				$peratusotr2C = number_format(($bilotr2C/($bilcalon - $bilotr2TH))*100,2,'.',',');
				$peratusotr2D = number_format(($bilotr2D/($bilcalon - $bilotr2TH))*100,2,'.',',');
				$peratusotr2E = number_format(($bilotr2E/($bilcalon - $bilotr2TH))*100,2,'.',',');
				$peratusotr2L = number_format(($bilotr2L/($bilcalon - $bilotr2TH))*100,2,'.',',');
				$gpkotr2 = number_format((($bilotr2A*1)+($bilotr2B*2)+($bilotr2C*3)+($bilotr2D*4)+($bilotr2E*5))/($bilcalon - $bilotr2TH),2,'.',',');

				$peratusotr3A = number_format(($bilotr3A/($bilcalon - $bilotr3TH))*100,2,'.',',');
				$peratusotr3B = number_format(($bilotr3B/($bilcalon - $bilotr3TH))*100,2,'.',',');
				$peratusotr3C = number_format(($bilotr3C/($bilcalon - $bilotr3TH))*100,2,'.',',');
				$peratusotr3D = number_format(($bilotr3D/($bilcalon - $bilotr3TH))*100,2,'.',',');
				$peratusotr3E = number_format(($bilotr3E/($bilcalon - $bilotr3TH))*100,2,'.',',');
				$peratusotr3L = number_format(($bilotr3L/($bilcalon - $bilotr3TH))*100,2,'.',',');
				$gpkotr3 = number_format((($bilotr3A*1)+($bilotr3B*2)+($bilotr3C*3)+($bilotr3D*4)+($bilotr3E*5))/($bilcalon - $bilotr3TH),2,'.',',');

				$peratusetrA = number_format(($biletrA/($bilcalon - $biletrTH))*100,2,'.',',');
				$peratusetrB = number_format(($biletrB/($bilcalon - $biletrTH))*100,2,'.',',');
				$peratusetrC = number_format(($biletrC/($bilcalon - $biletrTH))*100,2,'.',',');
				$peratusetrD = number_format(($biletrD/($bilcalon - $biletrTH))*100,2,'.',',');
				$peratusetrE = number_format(($biletrE/($bilcalon - $biletrTH))*100,2,'.',',');
				$peratusetrL = number_format(($biletrL/($bilcalon - $biletrTH))*100,2,'.',',');
				$gpketr = number_format((($biletrA*1)+($biletrB*2)+($biletrC*3)+($biletrD*4)+($biletrE*5))/($bilcalon - $biletrTH),2,'.',',');

			}
			else {
				$peratustovA = $peratustovB = $peratustovC = $peratustovD = $peratustovL = $peratustovE =  0.00; 
				$peratusotr1A = $peratusotr1B = $peratusotr1C = $peratusotr1D = $peratusotr1L = $peratusotr1E = 0.00;
				$peratusotr2A = $peratusotr2B = $peratusotr2C = $peratusotr2D = $peratusotr2L = $peratusotr2E = 0.00;
				$peratusotr3A = $peratusotr3B = $peratusotr3C = $peratusotr3D = $peratusotr3L = $peratusotr3E = 0.00;
				$peratusetrA = $peratusetrB = $peratusetrC = $peratusetrD = $peratusetrL = $peratusetrE = 0.00;
				$gpktov = $gpkotr1 = $gpkotr2 = $gpkotr3 = $gpketr = 0.00;
			}

			///////////////////////////////////////////// peratus atr1 /////////////////////////////////////		 
			if ($bilatr1 != 0){
				$peratusatr1A = number_format(($bilatr1A/($bilatr1 - $bilatr1TH))*100,2,'.',',');
				$peratusatr1B = number_format(($bilatr1B/($bilatr1 - $bilatr1TH))*100,2,'.',',');
				$peratusatr1C = number_format(($bilatr1C/($bilatr1 - $bilatr1TH))*100,2,'.',',');
				$peratusatr1D = number_format(($bilatr1D/($bilatr1 - $bilatr1TH))*100,2,'.',',');
				$peratusatr1E = number_format(($bilatr1E/($bilatr1 - $bilatr1TH))*100,2,'.',',');
				$peratusatr1L = number_format(($bilatr1L/($bilatr1 - $bilatr1TH))*100,2,'.',',');
				$gpkatr1 = number_format((($bilatr1A*1)+($bilatr1B*2)+($bilatr1C*3)+($bilatr1D*4)+($bilatr1E*5))/($bilatr1 - $bilatr1TH),2,'.',',');
			} else { 
				$gpkatr1 = $peratusatr1A = $peratusatr1B = $peratusatr1C = $peratusatr1D = $peratusatr1L = $peratusatr1E =  0.00;  
			}

			///////////////////////////////////////////// peratus atr2 /////////////////////////////////////		 
			if ($bilatr2 != 0){
				$peratusatr2A = number_format(($bilatr2A/($bilatr2 - $bilatr2TH))*100,2,'.',',');
				$peratusatr2B = number_format(($bilatr2B/($bilatr2 - $bilatr2TH))*100,2,'.',',');
				$peratusatr2C = number_format(($bilatr2C/($bilatr2 - $bilatr2TH))*100,2,'.',',');
				$peratusatr2E = number_format(($bilatr2E/($bilatr2 - $bilatr2TH))*100,2,'.',',');
				$peratusatr2D = number_format(($bilatr2D/($bilatr2 - $bilatr2TH))*100,2,'.',',');
				$peratusatr2L = number_format(($bilatr2L/($bilatr2 - $bilatr2TH))*100,2,'.',',');
				$gpkatr2 = number_format((($bilatr2A*1)+($bilatr2B*2)+($bilatr2C*3)+($bilatr2D*4)+($bilatr2E*5))/($bilatr2 - $bilatr2TH),2,'.',',');
			} else { 
				$gpkatr2 = $peratusatr2A = $peratusatr2B = $peratusatr2C = $peratusatr2D = $peratusatr2L = $peratusatr2E =  0.00;  
			}
			
			///////////////////////////////////////////// peratus atr3 /////////////////////////////////////		 
			if ($bilatr3 != 0){
				$peratusatr3A = number_format(($bilatr3A/($bilatr3 - $bilatr3TH))*100,2,'.',',');
				$peratusatr3B = number_format(($bilatr3B/($bilatr3 - $bilatr3TH))*100,2,'.',',');
				$peratusatr3C = number_format(($bilatr3C/($bilatr3 - $bilatr3TH))*100,2,'.',',');
				$peratusatr3D = number_format(($bilatr3D/($bilatr3 - $bilatr3TH))*100,2,'.',',');
				$peratusatr3E = number_format(($bilatr3E/($bilatr3 - $bilatr3TH))*100,2,'.',',');
				$peratusatr3L = number_format(($bilatr3L/($bilatr3 - $bilatr3TH))*100,2,'.',',');				
				$gpkatr3 = number_format((($bilatr3*1)+($bilatr3B*2)+($bilatr3C*3)+($bilatr3D*4)+($bilatr3E*5))/($bilatr3 - $bilatr3TH),2,'.',',');
			} else { 
				$gpkatr3 = $peratusatr3A = $peratusatr3B = $peratusatr3C = $peratusatr3D = $peratusatr3L = $peratusatr3E =  0.00;  
			}
			
			//////////////////////  JADUAL ANALISA HEADCOUNT MP KELAS MENENGAH RENDAH/SEKOLAH RENDAH  ////////////////////////////////////////////////
			echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\"><tr>\n";
			echo "    <td width=\"7%\" rowspan=\"2\">Pencapaian</td>\n";
			echo "    <td width=\"8%\" rowspan=\"2\"><div align=\"center\">Calon Daftar </div></td>\n";
			echo "    <td width=\"8%\" rowspan=\"2\"><div align=\"center\">Calon Ambil </div></td>\n";	
			echo "    <td width=\"4%\" rowspan=\"2\"><div align=\"center\">Bil TH </div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\">Gred A </div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\">Gred B </div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\">Gred C </div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\">Gred D </div></td>\n";
			echo "    <td width=\"5%\" rowspan=\"2\"><div align=\"center\">Bil Lulus </div></td>\n";
			echo "    <td width=\"5%\" rowspan=\"2\"><div align=\"center\">% Lulus </div></td>\n";
			echo "    <td colspan=\"2\"><div align=\"center\">Gred E </div></td>\n";
			echo "    <td width=\"5%\" rowspan=\"2\"><div align=\"center\">GPMP</div></td></tr>\n";
			echo "  <tr><td width=\"3%\"><div align=\"center\">Bil</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";
			echo "    <td width=\"3%\"><div align=\"center\">%</div></td></tr>\n";
			

            //////////////////////////// TOV ////////////////////////////////////////
			echo "  <tr bgcolor='#CDCDCD'><td>TOV</td>\n";
			echo "    <td><div align=\"center\">$bilcalon</div></td>\n";
			echo "    <td><div align=\"center\">".($bilcalon - $biltovTH)."</div></td>\n";
			echo "    <td><div align=\"center\">$biltovTH</div></td>\n";
			echo "    <td><div align=\"center\">$biltovA</div></td>\n";
			echo "    <td><div align=\"center\">$peratustovA</div></td>\n";
			echo "    <td><div align=\"center\">$biltovB</div></td>\n";
			echo "    <td><div align=\"center\">$peratustovB</div></td>\n";
			echo "    <td><div align=\"center\">$biltovC</div></td>\n";
			echo "    <td><div align=\"center\">$peratustovC</div></td>\n";
			echo "    <td><div align=\"center\">$biltovD</div></td>\n";
			echo "    <td><div align=\"center\">$peratustovD</div></td>\n";
			echo "    <td><div align=\"center\">$biltovL</div></td>\n";
			echo "    <td><div align=\"center\">$peratustovL</div></td>\n";
			echo "    <td><div align=\"center\">$biltovE</div></td>\n";
			echo "    <td><div align=\"center\">$peratustovE</div></td>\n";
			echo "    <td><div align=\"center\">$gpktov</div></td></tr>\n";
			echo "  <tr>\n";

            ////////////////////////////////////// OTR1 //////////////////////////////////////////////////////////
			echo "    <td>OTR1</td>\n";
			echo "    <td><div align=\"center\">$bilcalon</div></td>\n";
			echo "    <td><div align=\"center\">".($bilcalon - $bilotr1TH)."</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr1TH</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr1A</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr1A</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr1B</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr1B</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr1C</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr1C</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr1D</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr1D</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr1L</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr1L</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr1E</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr1E</div></td>\n";
			echo "    <td><div align=\"center\">$gpkotr1</div></td></tr>\n";
			
            ////////////////////////////////////// ATR1 //////////////////////////////////////////////////////////
			echo "  <tr bgcolor='#CDCDCD'><td>AR1</td>\n";
			echo "    <td><div align=\"center\">$bilatr1</div></td>\n";
			echo "    <td><div align=\"center\">".($bilatr1 - $bilatr1TH)."</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr1TH</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr1A</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr1A</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr1B</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr1B</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr1C</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr1C</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr1D</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr1D</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr1L</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr1L</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr1E</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr1E</div></td>\n";
			echo "    <td><div align=\"center\">$gpkatr1</div></td></tr>\n";
			
            ////////////////////////////////////// OTR2 //////////////////////////////////////////////////////////
			echo "  <tr><td>OTR2</td>\n";
			echo "    <td><div align=\"center\">$bilcalon</div></td>\n";
			echo "    <td><div align=\"center\">".($bilcalon - $bilotr2TH)."</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr2TH</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr2A</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr2A</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr2B</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr2B</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr2C</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr2C</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr2D</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr2D</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr2L</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr2L</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr2E</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr2E</div></td>\n";
			echo "    <td><div align=\"center\">$gpkotr2</div></td></tr>\n";
			

			////////////////////////////////////// ATR2 //////////////////////////////////////////////////////////
			echo "  <tr bgcolor='#CDCDCD'><td>AR2</td>\n";
			echo "    <td><div align=\"center\">$bilatr2</div></td>\n";
			echo "    <td><div align=\"center\">".($bilatr2 - $bilatr2TH)."</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr2TH</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr2A</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr2A</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr2B</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr2B</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr2C</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr2C</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr2D</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr2D</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr2L</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr2L</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr2E</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr2E</div></td>\n";
			echo "    <td><div align=\"center\">$gpkatr2</div></td></tr>\n";

            ////////////////////////////////////// OTR3 //////////////////////////////////////////////////////////
			echo "  <tr><td>OTR3</td>\n";
			echo "    <td><div align=\"center\">$bilcalon</div></td>\n";
			echo "    <td><div align=\"center\">".($bilcalon - $bilotr3TH)."</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr3TH</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr3A</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr3A</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr3B</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr3B</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr3C</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr3C</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr3D</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr3D</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr3L</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr3L</div></td>\n";
			echo "    <td><div align=\"center\">$bilotr3E</div></td>\n";
			echo "    <td><div align=\"center\">$peratusotr3E</div></td>\n";
			echo "    <td><div align=\"center\">$gpkotr3</div></td></tr>\n";

			////////////////////////////////////// ATR3 //////////////////////////////////////////////////////////
			echo "  <tr bgcolor='#CDCDCD'><td>AR3</td>\n";
			echo "    <td><div align=\"center\">$bilatr3</div></td>\n";
			echo "    <td><div align=\"center\">".($bilatr3 - $bilatr3TH)."</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr3TH</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr3A</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr3A</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr3B</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr3B</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr3C</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr3C</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr3D</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr3D</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr3L</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr3L</div></td>\n";
			echo "    <td><div align=\"center\">$bilatr3E</div></td>\n";
			echo "    <td><div align=\"center\">$peratusatr3E</div></td>\n";
			echo "    <td><div align=\"center\">$gpkatr3</div></td>\n";
			echo "  </tr>\n";
			

            ////////////////////////////////////// ETR //////////////////////////////////////////////////////////
			echo "  <tr>\n";
			echo "    <td>ETR</td>\n";
			echo "    <td><div align=\"center\">$bilcalon</div></td>\n";
			echo "    <td><div align=\"center\">".($bilcalon - $biletrTH)."</div></td>\n";
			echo "    <td><div align=\"center\">$biletrTH</div></td>\n";
			echo "    <td><div align=\"center\">$biletrA</div></td>\n";
			echo "    <td><div align=\"center\">$peratusetrA</div></td>\n";
			echo "    <td><div align=\"center\">$biletrB</div></td>\n";
			echo "    <td><div align=\"center\">$peratusetrB</div></td>\n";
			echo "    <td><div align=\"center\">$biletrC</div></td>\n";
			echo "    <td><div align=\"center\">$peratusetrC</div></td>\n";
			echo "    <td><div align=\"center\">$biletrD</div></td>\n";
			echo "    <td><div align=\"center\">$peratusetrD</div></td>\n";
			echo "    <td><div align=\"center\">$biletrL</div></td>\n";
			echo "    <td><div align=\"center\">$peratusetrL</div></td>\n";
			echo "    <td><div align=\"center\">$biletrE</div></td>\n";
			echo "    <td><div align=\"center\">$peratusetrE</div></td>\n";
			echo "    <td><div align=\"center\">$gpketr</div></td>\n";
			echo "  </tr>\n";

			echo "  <tr>\n";
			echo "</table>\n";			
		}

/////////////////////////////////////   TAMAT BLOK MENENGAH RENDAH DAN SEKOLAH RENDAH  ///////////////////////////////////////////

////////////////////////////////////  BLOK MENENGAH ATAS  ///////////////////////////////////////////////////////////////////////

if ($level=="MA"){
	$qbcalon = "SELECT * FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$mp' AND gtov is not null";
	$qbc = OCIParse($conn_sispa,$qbcalon);
	OCIExecute($qbc);
	
	$bilcalon = count_row($qbcalon);
	$biltovL = $biltov1AA = $biltov1A = $biltov2A = $biltov3B = $biltov4B = $biltov5C = $biltov6C = $biltov7D = $biltov8E = $biltov9G = $biltovTH = 0 ;
	$bilotr1L=$bilotr11AA=$bilotr11A=$bilotr12A=$bilotr13B=$bilotr14B=$bilotr15C=$bilotr16C=$bilotr17D=$bilotr18E=$bilotr19G=$bilotr1TH = 0 ;
	$bilotr2L=$bilotr21AA=$bilotr21A=$bilotr22A=$bilotr23B=$bilotr24B=$bilotr25C=$bilotr26C=$bilotr27D=$bilotr28E=$bilotr29G=$bilotr2TH = 0 ;
	$bilotr3L=$bilotr31AA=$bilotr31A=$bilotr32A=$bilotr33B=$bilotr34B=$bilotr35C=$bilotr36C=$bilotr37D=$bilotr38E=$bilotr39G=$bilotr3TH = 0 ;
	$biletrL =$biletr1AA=$biletr1A=$biletr2A=$biletr3B=$biletr4B=$biletr5C=$biletr6C=$biletr7D=$biletr8E=$biletr9G=$biletrTH = 0 ;
	$bilatr1 =$bilatr1L=$bilatr11AA=$bilatr11A=$bilatr12A=$bilatr13B=$bilatr14B=$bilatr15C=$bilatr16C=$bilatr17D=$bilatr18E=$bilatr19G=$bilatr1TH = 0 ;
	$bilatr2 =$bilatr2L=$bilatr21AA=$bilatr21A=$bilatr22A=$bilatr23B=$bilatr24B=$bilatr25C=$bilatr26C=$bilatr27D=$bilatr28E=$bilatr29G=$bilatr2TH = 0 ;
	$bilatr3 =$bilatr3L=$bilatr31AA=$bilatr31A=$bilatr32A=$bilatr33B=$bilatr34B=$bilatr35C=$bilatr36C=$bilatr37D=$bilatr38E=$bilatr39G=$bilatr3TH = 0 ;

    //////////////////////////// ANALISA TOV //////////////////////////////////////////////////
	while(OCIFetch($qbc)) //$rowhc
	{
		switch (trim(OCIResult($qbc,"GTOV")))
		{	
			case 'A+' : $biltov1AA = $biltov1AA + 1; break;
			case 'A' : $biltov1A = $biltov1A + 1; break;
			case 'A-' : $biltov2A = $biltov2A + 1; break;
			case 'B+' : $biltov3B = $biltov3B + 1; break;
			case 'B' : $biltov4B = $biltov4B + 1; break;
			case 'C+' : $biltov5C = $biltov5C + 1; break;
			case 'C' : $biltov6C = $biltov6C + 1; break;
			case 'D' : $biltov7D = $biltov7D + 1; break;
			case 'E' : $biltov8E = $biltov8E + 1; break;
			case 'G' : $biltov9G = $biltov9G + 1; break;
			case 'TH' : $biltovTH = $biltovTH + 1; break;
		}
		switch (trim(OCIResult($qbc,"GOTR1")))
		{	
			case 'A+' : $bilotr11AA = $bilotr11AA + 1; break;
			case 'A' : $bilotr11A = $bilotr11A + 1; break;
			case 'A-' : $bilotr12A = $bilotr12A + 1; break;
			case 'B+' : $bilotr13B = $bilotr13B + 1; break;
			case 'B' : $bilotr14B = $bilotr14B + 1; break;
			case 'C+' : $bilotr15C = $bilotr15C + 1; break;
			case 'C' : $bilotr16C = $bilotr16C + 1; break;
			case 'D' : $bilotr17D = $bilotr17D + 1; break;
			case 'E' : $bilotr18E = $bilotr18E + 1; break;
			case 'G' : $bilotr19G = $bilotr19G + 1; break;
			case 'TH' : $bilotr1TH = $bilotr1TH + 1; break;
		}
		switch (/*$rowhc['gotr2']*/trim(OCIResult($qbc,"GOTR2")))
		{	
			case 'A+' : $bilotr21AA = $bilotr21AA + 1; break;
			case 'A' : $bilotr21A = $bilotr21A + 1; break;
			case 'A-' : $bilotr22A = $bilotr22A + 1; break;
			case 'B+' : $bilotr23B = $bilotr23B + 1; break;
			case 'B' : $bilotr24B = $bilotr24B + 1; break;
			case 'C+' : $bilotr25C = $bilotr25C + 1; break;
			case 'C' : $bilotr26C = $bilotr26C + 1; break;
			case 'D' : $bilotr27D = $bilotr27D + 1; break;
			case 'E' : $bilotr28E = $bilotr28E + 1; break;
			case 'G' : $bilotr29G = $bilotr29G + 1; break;
			case 'TH' : $bilotr2TH = $bilotr2TH + 1; break;
		}
				switch (/*$rowhc['gotr3']*/trim(OCIResult($qbc,"GOTR3")))
				{
					case 'A+' : $bilotr31AA = $bilotr31AA + 1; break;
					case 'A' : $bilotr31A = $bilotr31A + 1; break;
					case 'A-' : $bilotr32A = $bilotr32A + 1; break;
					case 'B+' : $bilotr33B = $bilotr33B + 1; break;
					case 'B' : $bilotr34B = $bilotr34B + 1; break;
					case 'C+' : $bilotr35C = $bilotr35C + 1; break;
					case 'C' : $bilotr36C = $bilotr36C + 1; break;
					case 'D' : $bilotr37D = $bilotr37D + 1; break;
					case 'E' : $bilotr38E = $bilotr38E + 1; break;
					case 'G' : $bilotr39G = $bilotr39G + 1; break;
					case 'TH' : $bilotr3TH = $bilotr3TH + 1; break;
				}
				switch (/*$rowhc['getr']*/trim(OCIResult($qbc,"GETR")))
				{
					case 'A+' : $biletr1AA = $biletr1AA + 1; break;
					case 'A' : $biletr1A = $biletr1A + 1; break;
					case 'A-' : $biletr2A = $biletr2A + 1; break;
					case 'B+' : $biletr3B = $biletr3B + 1; break;
					case 'B' : $biletr4B = $biletr4B + 1; break;
					case 'C+' : $biletr5C = $biletr5C + 1; break;
					case 'C' : $biletr6C = $biletr6C + 1; break;
					case 'D' : $biletr7D = $biletr7D + 1; break;
					case 'E' : $biletr8E = $biletr8E + 1; break;
					case 'G' : $biletr9G = $biletr9G + 1; break;
					case 'TH' : $biletrTH = $biletrTH + 1; break;
				}

			}
			$biltovL = $biltov1AA + $biltov1A + $biltov2A + $biltov3B + $biltov4B + $biltov5C + $biltov6C + $biltov7D + $biltov8E ;
			$bilotr1L = $bilotr11AA + $bilotr11A + $bilotr12A + $bilotr13B + $bilotr14B + $bilotr15C + $bilotr16C + $bilotr17D + $bilotr18E ;
			$bilotr2L = $bilotr21AA + $bilotr21A + $bilotr22A + $bilotr23B + $bilotr24B + $bilotr25C + $bilotr26C + $bilotr27D + $bilotr28E ;
			$bilotr3L = $bilotr31AA + $bilotr31A + $bilotr32A + $bilotr33B + $bilotr34B + $bilotr35C + $bilotr36C + $bilotr37D + $bilotr38E ;
			$biletrL = $biletr1AA + $biletr1A + $biletr2A + $biletr3B + $biletr4B + $biletr5C + $biletr6C + $biletr7D + $biletr8E ;
			
			
            ////////////////////////////// ANALISA ATR1  ///////////////////////////////////////////		

			$qrytentuhcatr1 = OCIParse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='ATR1'");
			OCIExecute($qrytentuhcatr1);
			OCIFetch($qrytentuhcatr1);	//$rowtentuatr1
			$jpepatr1=/*$rowtentuatr1['jenpep']*/trim(OCIResult($qrytentuhcatr1,"JENPEP")); $tahunatr1=/*$rowtentuatr1['tahuntov']*/trim(OCIResult($qrytentuhcatr1,"TAHUNTOV")); $tingatr1=/*$rowtentuatr1['tingtov']*/trim(OCIResult($qrytentuhcatr1,"TINGTOV"));
			$qryatr1 = "SELECT * FROM markah_pelajar WHERE kodsek='".$_SESSION['kodsek2']."' AND $tahap='$tingatr1' AND kelas='$kelas' AND jpep='$jpepatr1' AND tahun='$tahunatr1' AND $mp IS NOT NULL";//$mp!=''";
			$qry = OCIParse($conn_sispa,$qryatr1);
			OCIExecute($qry);
			$bilatr1 = count_row($qryatr1);
			while (OCIFetch($qry))
			{
				switch (/*$rowatr1["$gmp"]*/trim(OCIResult($qry,"$gmp")))
				{
					case 'A+' : $bilatr11AA = $bilatr11AA + 1; break;
					case 'A' : $bilatr11A = $bilatr11A + 1; break;
					case 'A-' : $bilatr12A = $bilatr12A + 1; break;
					case 'B+' : $bilatr13B = $bilatr13B + 1; break;
					case 'B' : $bilatr14B = $bilatr14B + 1; break;
					case 'C+' : $bilatr15C = $bilatr15C + 1; break;
					case 'C' : $bilatr16C = $bilatr16C + 1; break;
					case 'D' : $bilatr17D = $bilatr17D + 1; break;
					case 'E' : $bilatr18E = $bilatr18E + 1; break;
					case 'G' : $bilatr19G = $bilatr19G + 1; break;
					case 'TH' : $bilatr1TH = $bilatr1TH + 1; break;
				}
			}
			$bilatr1L = $bilatr11AA + $bilatr11A + $bilatr12A + $bilatr13B + $bilatr14B + $bilatr15C + $bilatr16C + $bilatr17D + $bilatr18E ;
			$bilatr1 = $bilatr11AA + $bilatr11A + $bilatr12A + $bilatr13B + $bilatr14B + $bilatr15C + $bilatr16C + $bilatr17D + $bilatr18E + $bilatr19G + $bilatr1TH;
			
            ////////////////////////////// ANALISA ATR2  ///////////////////////////////////////////		

			$qrytentuhcatr2 = OCIParse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='ATR2'");
			OCIExecute($qrytentuhcatr2);
			OCIFetch($qrytentuhcatr2);	
			$jpepatr2=/*$rowtentuatr2['jenpep']*/trim(OCIResult($qrytentuhcatr2,"JENPEP")); $tahunatr2=/*$rowtentuatr2['tahuntov']*/trim(OCIResult($qrytentuhcatr2,"TAHUNTOV")); $tingatr2=/*$rowtentuatr2['tingtov']*/trim(OCIResult($qrytentuhcatr2,"TINGTOV"));
			$qryatr2 = "SELECT * FROM markah_pelajar WHERE kodsek='".$_SESSION['kodsek2']."' AND $tahap='$tingatr2' AND kelas='$kelas' AND jpep='$jpepatr2' AND tahun='$tahunatr2' AND $mp IS NOT NULL";//$mp!=''";
			$qry2 = OCIParse($conn_sispa,$qryatr2);
			OCIExecute($qry2);
			$bilatr2 = count_row($qryatr2);
			while (OCIFetch($qry2))
			{
				switch (/*$rowatr2["$gmp"]*/trim(OCIResult($qry2,"$gmp")))
				{	
					case 'A+' : $bilatr21AA = $bilatr21AA + 1; break;
					case 'A' : $bilatr21A = $bilatr21A + 1; break;
					case 'A-' : $bilatr22A = $bilatr22A + 1; break;
					case 'B+' : $bilatr23B = $bilatr23B + 1; break;
					case 'B' : $bilatr24B = $bilatr24B + 1; break;
					case 'C+' : $bilatr25C = $bilatr25C + 1; break;
					case 'C' : $bilatr26C = $bilatr26C + 1; break;
					case 'D' : $bilatr27D = $bilatr27D + 1; break;
					case 'E' : $bilatr28E = $bilatr28E + 1; break;
					case 'G' : $bilatr29G = $bilatr29G + 1; break;
					case 'TH' : $bilatr2TH = $bilatr2TH + 1; break;
				}
			}
			$bilatr2L = $bilatr21AA + $bilatr21A + $bilatr22A + $bilatr23B + $bilatr24B + $bilatr25C + $bilatr26C + $bilatr27D + $bilatr28E ;
			$bilatr2 = $bilatr21AA + $bilatr21A + $bilatr22A + $bilatr23B + $bilatr24B + $bilatr25C + $bilatr26C + $bilatr27D + $bilatr28E  + $bilatr29G + $bilatr2TH;			$bilatr2 = $bilatr21A + $bilatr22A + $bilatr23B + $bilatr24B + $bilatr25C + $bilatr26C + $bilatr27D + $bilatr28E  + $bilatr29G + $bilatr2TH;
			
            ////////////////////////////// ANALISA ATR3 ///////////////////////////////////////////		

			$qrytentuhcatr3 = OCIParse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='ATR3'");
			OCIExecute($qrytentuhcatr3);
			OCIFetch($qrytentuhcatr3);	
			$jpepatr3=/*$rowtentuatr3['jenpep']*/trim(OCIResult($qrytentuhcatr3,"JENPEP")); $tahunatr3=/*$rowtentuatr3['tahuntov']*/trim(OCIResult($qrytentuhcatr3,"TAHUNTOV")); $tingatr3=/*$rowtentuatr3['tingtov']*/trim(OCIResult($qrytentuhcatr3,"TINGTOV"));
			$qryatr3 = "SELECT * FROM markah_pelajar WHERE kodsek='".$_SESSION['kodsek2']."' AND $tahap='$tingatr3' AND kelas='$kelas' AND jpep='$jpepatr3' AND tahun='$tahunatr3' AND $mp IS NOT NULL";// $mp!=''";
			$qry3 = OCIParse($conn_sispa,$qryatr3);
			OCIExecute($qry3);
			$bilatr3 = count_row($qryatr3);
			while (OCIFetch($qry3))
			{
				switch (/*$rowatr3["$gmp"]*/trim(OCIResult($qry3,"$gmp")))
				{
					case 'A+' : $bilatr31AA = $bilatr31AA + 1; break;
					case 'A' : $bilatr31A = $bilatr31A + 1; break;
					case 'A-' : $bilatr32A = $bilatr32A + 1; break;
					case 'B+' : $bilatr33B = $bilatr33B + 1; break;
					case 'B' : $bilatr34B = $bilatr34B + 1; break;
					case 'C+' : $bilatr35C = $bilatr35C + 1; break;
					case 'C' : $bilatr36C = $bilatr36C + 1; break;
					case 'D' : $bilatr37D = $bilatr37D + 1; break;
					case 'E' : $bilatr38E = $bilatr38E + 1; break;
					case 'G' : $bilatr39G = $bilatr39G + 1; break;
					case 'TH' : $bilatr3TH = $bilatr3TH + 1; break;
				}
			}
			$bilatr3L = $bilatr31AA + $bilatr31A + $bilatr32A + $bilatr33B + $bilatr34B + $bilatr35C + $bilatr36C + $bilatr37D + $bilatr38E ;
			$bilatr3 = $bilatr31AA + $bilatr31A + $bilatr32A + $bilatr33B + $bilatr34B + $bilatr35C + $bilatr36C + $bilatr37D + $bilatr38E  + $bilatr39G + $bilatr3TH ;
						
			///////////////////////////  PENGIRAAN PERATUS DAN GPK MENENGAH ATAS ////////////////////////////

			if ($bilcalon != 0){

				/////////////////////////////////////////// tov ////////////////////////////////

				$peratustov1AA = number_format(($biltov1AA/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustov1A = number_format(($biltov1A/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustov2A = number_format(($biltov2A/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustov3B = number_format(($biltov3B/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustov4B = number_format(($biltov4B/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustov5C = number_format(($biltov5C/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustov6C = number_format(($biltov6C/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustov7D = number_format(($biltov7D/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustov8E = number_format(($biltov8E/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustovL = number_format(($biltovL/($bilcalon - $biltovTH))*100,2,'.',',');

				$peratustov9G = number_format(($biltov9G/($bilcalon - $biltovTH))*100,2,'.',',');

				$gpktov=number_format((($biltov1AA*0)+($biltov1A*1)+($biltov2A*2)+($biltov3B*3)+($biltov4B*4)+($biltov5C*5)+($biltov6C*6)+($biltov7D*7)+($biltov8E*8)+($biltov9G*9))/($bilcalon - $biltovTH),2,'.',',');				

				///////////////////////////////////////// otr1 ///////////////////////////////////////////
				
				$peratusotr11AA = number_format(($bilotr11AA/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr11A = number_format(($bilotr11A/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr12A = number_format(($bilotr12A/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr13B = number_format(($bilotr13B/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr14B = number_format(($bilotr14B/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr15C = number_format(($bilotr15C/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr16C = number_format(($bilotr16C/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr17D = number_format(($bilotr17D/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr18E = number_format(($bilotr18E/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr1L = number_format(($bilotr1L/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$peratusotr19G = number_format(($bilotr19G/($bilcalon - $bilotr1TH))*100,2,'.',',');

				$gpkotr1 = number_format((($bilotr11AA*0)+($bilotr11A*1)+($bilotr12A*2)+($bilotr13B*3)+($bilotr14B*4)+($bilotr15C*5)+($bilotr16C*6)+($bilotr17D*7)+($bilotr18E*8)+($bilotr19G*9))/($bilcalon - $bilotr1TH),2,'.',',');

				//////////////////////////////////// otr2 ///////////////////////////////////////////////
				
				$peratusotr21AA = number_format(($bilotr21AA/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr21A = number_format(($bilotr21A/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr22A = number_format(($bilotr22A/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr23B = number_format(($bilotr23B/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr24B = number_format(($bilotr24B/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr25C = number_format(($bilotr25C/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr26C = number_format(($bilotr26C/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr27D = number_format(($bilotr27D/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr28E = number_format(($bilotr28E/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr2L = number_format(($bilotr2L/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$peratusotr29G = number_format(($bilotr29G/($bilcalon - $bilotr2TH))*100,2,'.',',');

				$gpkotr2 = number_format((($bilotr21AA*0)+($bilotr21A*1)+($bilotr22A*2)+($bilotr23B*3)+($bilotr24B*4)+($bilotr25C*5)+($bilotr26C*6)+($bilotr27D*7)+($bilotr28E*8)+($bilotr29G*9))/($bilcalon - $bilotr2TH),2,'.',',');
				
				//////////////////////////////////// otr3 ///////////////////////////////////////////////
				
				$peratusotr31AA = number_format(($bilotr31AA/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr31A = number_format(($bilotr31A/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr32A = number_format(($bilotr32A/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr33B = number_format(($bilotr33B/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr34B = number_format(($bilotr34B/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr35C = number_format(($bilotr35C/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr36C = number_format(($bilotr36C/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr37D = number_format(($bilotr37D/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr38E = number_format(($bilotr38E/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr3L = number_format(($bilotr3L/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$peratusotr39G = number_format(($bilotr39G/($bilcalon - $bilotr3TH))*100,2,'.',',');

				$gpkotr3=number_format((($bilotr31AA*0)+($bilotr31A*1)+($bilotr32A*2)+($bilotr33B*3)+($bilotr34B*4)+($bilotr35C*5)+($bilotr36C*6)+($bilotr37D*7)+($bilotr38E*8)+($bilotr39G*9))/($bilcalon - $bilotr3TH),2,'.',',');
				

				//////////////////////////////////// etr ///////////////////////////////////////////////
				
				$peratusetr1AA = number_format(($biletr1AA/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetr1A = number_format(($biletr1A/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetr2A = number_format(($biletr2A/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetr3B = number_format(($biletr3B/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetr4B = number_format(($biletr4B/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetr5C = number_format(($biletr5C/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetr6C = number_format(($biletr6C/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetr7D = number_format(($biletr7D/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetr8E = number_format(($biletr8E/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetrL = number_format(($biletrL/($bilcalon - $biletrTH))*100,2,'.',',');

				$peratusetr9G = number_format(($biletr9G/($bilcalon - $biletrTH))*100,2,'.',',');

				$gpketr=number_format((($biletr1AA*0)+($biletr1A*1)+($biletr2A*2)+($biletr3B*3)+($biletr4B*4)+($biletr5C*5)+($biletr6C*6)+($biletr7D*7)+($biletr8E*8)+($biletr9G*9))/($bilcalon - $biletrTH),2,'.',',');
				

			} else {	

					$peratustov1AA = $peratustov1A = $peratustov2A = $peratustov3B = $peratustov4B = $peratustov5C = $peratustov6C = $peratustov7D = $peratustov8E = $peratustovL = $peratustov9G = 0.00;				

					$peratusotr11AA = $peratusotr11A = $peratusotr12A = $peratusotr13B = $peratusotr14B = $peratusotr15C = $peratusotr16C = $peratusotr17D = $peratusotr18E = $peratusotr1L = $peratusotr19G = 0.00;

					$peratusotr21AA = $peratusotr21A = $peratusotr22A = $peratusotr23B = $peratusotr24B = $peratusotr25C = $peratusotr26C = $peratusotr27D = $peratusotr28E = $peratusotr2L = $peratusotr29G = 0.00;

					$peratusotr31AA = $peratusotr31A = $peratusotr32A = $peratusotr33B = $peratusotr34B = $peratusotr35C = $peratusotr36C = $peratusotr37D = $peratusotr38E = $peratusotr3L = $peratusotr39G = 0.00;

					$peratusetr1AA = $peratusetr1A = $peratusetr2A = $peratusetr3B = $peratusetr4B = $peratusetr5C = $peratusetr6C = $peratusetr7D = $peratusetr8E = $peratusetrL = $peratusetr9G = 0.00;

					$gpktov = $gpkotr1 =  $gpkotr2 = $gpkotr3 = $gpketr = 0.00;

				   }

			/////////////////////////////////////////////  atr1 /////////////////////////////////////

			if ($bilatr1 != 0){
				
				$peratusatr11AA = number_format(($bilatr11AA/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr11A = number_format(($bilatr11A/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr12A = number_format(($bilatr12A/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr13B = number_format(($bilatr13B/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr14B = number_format(($bilatr14B/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr15C = number_format(($bilatr15C/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr16C = number_format(($bilatr16C/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr17D = number_format(($bilatr17D/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr18E = number_format(($bilatr18E/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr1L = number_format(($bilatr1L/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$peratusatr19G = number_format(($bilatr19G/($bilatr1 - $bilatr1TH))*100,2,'.',',');

				$gpkatr1 = number_format((($bilatr11AA*0)+($bilatr11A*1)+($bilatr12A*2)+($bilatr13B*3)+($bilatr14B*4)+($bilatr15C*5)+($bilatr16C*6)+($bilatr17D*7)+($bilatr18E*8)+($bilatr19G*9))/($bilatr1 - $bilatr1TH),2,'.',',');
				
			} else { $gpkatr1 = $peratusatr11AA = $peratusatr11A = $peratusatr12A = $peratusatr13B = $peratusatr14B = $peratusatr15C = $peratusatr16C = $peratusatr17D = $peratusatr18E = $peratusatr1L = $peratusatr19G = 0.00; }

			/////////////////////////////////////////////  atr2 /////////////////////////////////////

			if ($bilatr2 != 0){
			
				$peratusatr21AA = number_format(($bilatr21AA/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr21A = number_format(($bilatr21A/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr22A = number_format(($bilatr22A/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr23B = number_format(($bilatr23B/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr24B = number_format(($bilatr24B/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr25C = number_format(($bilatr25C/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr26C = number_format(($bilatr26C/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr27D = number_format(($bilatr27D/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr28E = number_format(($bilatr28E/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr2L = number_format(($bilatr2L/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$peratusatr29G = number_format(($bilatr29G/($bilatr2 - $bilatr2TH))*100,2,'.',',');

				$gpkatr2 = number_format((($bilatr21AA*0)+($bilatr21A*1)+($bilatr22A*2)+($bilatr23B*3)+($bilatr24B*4)+($bilatr25C*5)+($bilatr26C*6)+($bilatr27D*7)+($bilatr28E*8)+($bilatr29G*9))/($bilatr2 - $bilatr2TH),2,'.',',');
				
			} else { $gpkatr2 = $peratusatr21AA = $peratusatr21A = $peratusatr22A = $peratusatr23B = $peratusatr24B = $peratusatr25C = $peratusatr26C = $peratusatr27D = $peratusatr28E = $peratusatr2L = $peratusatr29G = 0.00; }

			/////////////////////////////////////////////  atr3 /////////////////////////////////////

			if ($bilatr3 != 0){
			
				$peratusatr31AA = number_format(($bilatr31AA/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr31A = number_format(($bilatr31A/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr32A = number_format(($bilatr32A/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr33B = number_format(($bilatr33B/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr34B = number_format(($bilatr34B/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr35C = number_format(($bilatr35C/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr36C = number_format(($bilatr36C/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr37D = number_format(($bilatr37D/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr38E = number_format(($bilatr38E/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr3L = number_format(($bilatr3L/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$peratusatr39G = number_format(($bilatr39G/($bilatr3 - $bilatr3TH))*100,2,'.',',');

				$gpkatr3 = number_format((($bilatr31AA*0)+($bilatr31A*1)+($bilatr32A*2)+($bilatr33B*3)+($bilatr34B*4)+($bilatr35C*5)+($bilatr36C*6)+($bilatr37D*7)+($bilatr38E*8)+($bilatr39G*9))/($bilatr3 - $bilatr3TH),2,'.',',');
				
			} else { $gpkatr3 = $peratusatr31AA = $peratusatr31A = $peratusatr32A = $peratusatr33B = $peratusatr34B = $peratusatr35C = $peratusatr36C = $peratusatr37D = $peratusatr38E = $peratusatr3L = $peratusatr39G = 0.00; }


		//////////////////////  JADUAL ANALISA HEADCOUNT MP KELAS MENENGAH ATAS  ////////////////////////////////////////////////

		echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";

		echo "  <tr bgcolor=\"#FFCC99\">\n";

		echo "    <td width=\"7%\" rowspan=\"2\">Pencapaian</td>\n";

		echo "    <td width=\"8%\" rowspan=\"2\"><div align=\"center\">Calon Daftar </div></td>\n";

		echo "    <td width=\"8%\" rowspan=\"2\"><div align=\"center\">Calon Ambil </div></td>\n";
		
		echo "    <td colspan=\"2\"><div align=\"center\">Gred A+ </div></td>\n";

		echo "    <td colspan=\"2\"><div align=\"center\">Gred A </div></td>\n";

		echo "    <td colspan=\"2\"><div align=\"center\">Gred A- </div></td>\n";

		echo "    <td colspan=\"2\"><div align=\"center\">Gred B+ </div></td>\n";

		echo "    <td colspan=\"2\"><div align=\"center\">Gred B</div></td>\n";

		echo "    <td colspan=\"2\"><div align=\"center\">Gred C+ </div></td>\n";

		echo "    <td colspan=\"2\"><div align=\"center\">Gred C </div></td>\n";

		echo "    <td colspan=\"2\"><div align=\"center\">Gred D </div></td>\n";

		echo "    <td colspan=\"2\"><div align=\"center\">Gred E</div></td>\n";

		echo "    <td width=\"5%\" rowspan=\"2\"><div align=\"center\">Bil Lulus </div></td>\n";

		echo "    <td width=\"5%\" rowspan=\"2\"><div align=\"center\">% Lulus </div></td>\n";

		echo "    <td colspan=\"2\"><div align=\"center\">Gred G</div></td>\n";

		echo "    <td width=\"4%\" rowspan=\"2\"><div align=\"center\">Bil TH </div></td>\n";

		echo "    <td width=\"5%\" rowspan=\"2\"><div align=\"center\">GPMP</div></td>\n";

		echo "  </tr>\n";

		echo "  <tr bgcolor=\"#FFCC99\">\n";
		
		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">Bil</div></td>\n";

		echo "    <td width=\"3%\"><div align=\"center\">%</div></td>\n";

		echo "  </tr>\n";

		echo "  <tr bgcolor='#CDCDCD'>\n";

		echo "    <td>TOV</td>\n";

		echo "    <td><div align=\"center\">$bilcalon</div></td>\n";

		echo "    <td><div align=\"center\">".($bilcalon - $biltovTH)."</div></td>\n";
		
		echo "    <td><div align=\"center\">$biltov1AA</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov1AA</div></td>\n";

		echo "    <td><div align=\"center\">$biltov1A</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov1A</div></td>\n";

		echo "    <td><div align=\"center\">$biltov2A</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov2A</div></td>\n";

		echo "    <td><div align=\"center\">$biltov3B</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov3B</div></td>\n";

		echo "    <td><div align=\"center\">$biltov4B</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov4B</div></td>\n";

		echo "    <td><div align=\"center\">$biltov5C</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov5C</div></td>\n";

		echo "    <td><div align=\"center\">$biltov6C</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov6C</div></td>\n";

		echo "    <td><div align=\"center\">$biltov7D</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov7D</div></td>\n";

		echo "    <td><div align=\"center\">$biltov8E</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov8E</div></td>\n";

		echo "    <td><div align=\"center\">$biltovL</div></td>\n";

		echo "    <td><div align=\"center\">$peratustovL</div></td>\n";

		echo "    <td><div align=\"center\">$biltov9G</div></td>\n";

		echo "    <td><div align=\"center\">$peratustov9G</div></td>\n";

		echo "    <td><div align=\"center\">$biltovTH</div></td>\n";

		echo "    <td><div align=\"center\">$gpktov</div></td>\n";

		echo "  </tr>\n";

		echo "  <tr>\n";

		echo "    <td>OTR1 </td>\n";

		echo "    <td><div align=\"center\">$bilcalon</div></td>\n";

		echo "    <td><div align=\"center\">".($bilcalon - $bilotr1TH)."</div></td>\n";
		
		echo "    <td><div align=\"center\">$bilotr11AA</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr11AA</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr11A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr11A</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr12A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr12A</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr13B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr13B</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr14B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr14B</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr15C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr15C</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr16C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr16C</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr17D</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr17D</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr18E</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr18E</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr1L</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr1L</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr19G</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr19G</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr1TH</div></td>\n";

		echo "    <td><div align=\"center\">$gpkotr1</div></td>\n";

		echo "  </tr>\n";

		echo "  <tr bgcolor='#CDCDCD'>\n";

		echo "    <td>AR1 </td>\n";

		echo "    <td><div align=\"center\">$bilatr1</div></td>\n";

		echo "    <td><div align=\"center\">".($bilatr1 - $bilatr1TH)."</div></td>\n";
		
  		echo "    <td><div align=\"center\">$bilatr11AA</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr11AA</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr11A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr11A</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr12A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr12A</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr13B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr13B</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr14B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr14B</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr15C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr15C</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr16C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr16C</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr17D</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr17D</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr18E</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr18E</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr1L</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr1L</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr19G</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr19G</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr1TH</div></td>\n";

		echo "    <td><div align=\"center\">$gpkatr1</div></td>\n";

		echo "  </tr>\n";

		echo "  <tr>\n";

		echo "    <td>OTR2</td>\n";

		echo "    <td><div align=\"center\">$bilcalon</div></td>\n";

		echo "    <td><div align=\"center\">".($bilcalon - $bilotr2TH)."</div></td>\n";
		
		echo "    <td><div align=\"center\">$bilotr21AA</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr21AA</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr21A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr21A</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr22A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr22A</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr23B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr23B</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr24B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr24B</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr25C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr25C</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr26C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr26C</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr27D</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr27D</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr28E</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr28E</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr2L</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr2L</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr29G</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr29G</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr2TH</div></td>\n";

		echo "    <td><div align=\"center\">$gpkotr2</div></td>\n";

		echo "  </tr>\n";

		echo "  <tr bgcolor='#CDCDCD'>\n";

		echo "    <td>AR2 </td>\n";

		echo "    <td><div align=\"center\">$bilatr2</div></td>\n";

		echo "    <td><div align=\"center\">".($bilatr2 - $bilatr2TH)."</div></td>\n";
		
		echo "    <td><div align=\"center\">$bilatr21AA</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr21AA</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr21A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr21A</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr22A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr22A</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr23B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr23B</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr24B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr24B</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr25C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr25C</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr26C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr26C</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr27D</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr27D</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr28E</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr28E</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr2L</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr2L</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr29G</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr29G</div></td>\n";


		echo "    <td><div align=\"center\">$bilatr2TH</div></td>\n";

		echo "    <td><div align=\"center\">$gpkatr2</div></td>\n";

		echo "  </tr>\n";

		echo "  <tr>\n";

		echo "    <td>OTR3</td>\n";

		echo "    <td><div align=\"center\">$bilcalon</div></td>\n";

		echo "    <td><div align=\"center\">".($bilcalon - $bilotr3TH)."</div></td>\n";
		
		echo "    <td><div align=\"center\">$bilotr31AA</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr31AA</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr31A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr31A</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr32A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr32A</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr33B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr33B</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr34B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr34B</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr35C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr35C</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr36C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr36C</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr37D</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr37D</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr38E</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr38E</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr3L</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr3L</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr39G</div></td>\n";

		echo "    <td><div align=\"center\">$peratusotr39G</div></td>\n";

		echo "    <td><div align=\"center\">$bilotr3TH</div></td>\n";

		echo "    <td><div align=\"center\">$gpkotr3</div></td>\n";

		echo "  </tr>\n";

		echo "  <tr bgcolor='#CDCDCD'>\n";

		echo "    <td>AR3 </td>\n";

		echo "    <td><div align=\"center\">$bilatr3</div></td>\n";

		echo "    <td><div align=\"center\">".($bilatr3 - $bilatr3TH)."</div></td>\n";
	
		echo "    <td><div align=\"center\">$bilatr31AA</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr31AA</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr31A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr31A</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr32A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr32A</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr33B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr33B</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr34B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr34B</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr35C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr35C</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr36C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr36C</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr37D</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr37D</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr38E</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr38E</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr3L</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr3L</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr39G</div></td>\n";

		echo "    <td><div align=\"center\">$peratusatr39G</div></td>\n";

		echo "    <td><div align=\"center\">$bilatr3TH</div></td>\n";

		echo "    <td><div align=\"center\">$gpkatr3</div></td>\n";

		echo "  </tr>\n";

		echo "  <tr>\n";

		echo "    <td>ETR</td>\n";

		echo "    <td><div align=\"center\">$bilcalon</div></td>\n";

		echo "    <td><div align=\"center\">".($bilcalon - $biletrTH)."</div></td>\n";
		
		echo "    <td><div align=\"center\">$biletr1AA</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr1AA</div></td>\n";

		echo "    <td><div align=\"center\">$biletr1A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr1A</div></td>\n";

		echo "    <td><div align=\"center\">$biletr2A</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr2A</div></td>\n";

		echo "    <td><div align=\"center\">$biletr3B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr3B</div></td>\n";

		echo "    <td><div align=\"center\">$biletr4B</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr4B</div></td>\n";

		echo "    <td><div align=\"center\">$biletr5C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr5C</div></td>\n";

		echo "    <td><div align=\"center\">$biletr6C</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr6C</div></td>\n";

		echo "    <td><div align=\"center\">$biletr7D</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr7D</div></td>\n";

		echo "    <td><div align=\"center\">$biletr8E</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr8E</div></td>\n";

		echo "    <td><div align=\"center\">$biletrL</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetrL</div></td>\n";

		echo "    <td><div align=\"center\">$biletr9G</div></td>\n";

		echo "    <td><div align=\"center\">$peratusetr9G</div></td>\n";

		echo "    <td><div align=\"center\">$biletrTH</div></td>\n";

		echo "    <td><div align=\"center\">$gpketr</div></td>\n";

		echo "  </tr>\n";

		echo "</table>\n";

		echo "<br><br>";

		}

} else{

		echo "<br><br><br>";

		echo "<table width=\"450\"  border=\"1\" align=\"center\" cellpadding=\"30\" cellspacing=\"0\" bordercolor=\"#0000FF\">\n";

		echo "  <tr>\n";

		echo "    <td bgcolor=\"#FFFF99\"><div align=\"center\"><h3>SILA PILIH $tajuk, KELAS DAN MATA PELAJARAN</h3><br>\n";

		echo "      <br>\n";

		echo "      << <a href=\"hc-pel-ma-kelas-jpn.php\">Kembali</a></td>\n";

		echo "  </tr>\n";

		echo "</table>\n";

	}

}

else{
	switch ($_SESSION['statusseksbt'])
	{
		case "SR":

			//$level="SR";

			$theadcount="headcountsr";

			$tmatap="mpsr";

			$tajuk="DARJAH";

			$tahap="DARJAH";

			break;

		case "SM" :
			//$level="MR";
			$theadcount="headcount";
			$tmatap="mpsmkc";
			$tajuk="TINGKATAN";
			$tahap="TING";
			break;
}
?>

<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.ting.options[form.ting.options.selectedIndex].value;
self.location='hc-pel-ma-kelas-jpn.php?ting=' + val;
}
</script>

<?php
$ting=$_GET['ting'];
$kelas=$_GET['kelas'];
echo " <center><h3>SILA PILIH $tajuk, KELAS DAN MATA PELAJARAN</h3></center>";
//echo "<form method=\"post\">\n";
echo "<form method=post name='f1' action='hc-pel-ma-kelas-jpn.php'>";
echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
echo "<tr bgcolor=\"#CCCCCC\"><td>$tajuk</td><td>KELAS</td><td>MATA PELAJARAN</td><td>&nbsp;</td></tr>";

//echo "<form method=post name='f1' action='hc-pel-ma-kelas.php'>";
echo "<tr bgcolor=\"#CCCCCC\"><td>\n";
echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih ".strtolower($tajuk)."</option>";
$SQL_tkelas = "SELECT DISTINCT $tahap FROM $theadcount WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='$kodsek' ORDER BY $tahap";
$sql = OCIParse($conn_sispa,$SQL_tkelas);
OCIExecute($sql);
while(OCIFetch($sql)) { 
	if(OCIResult($sql,"$tahap")==@$ting){
		echo "<option selected value='".OCIResult($sql,"$tahap")."'>".OCIResult($sql,"$tahap")."</option>"."<BR>";
	}else{
		echo  "<option value='".OCIResult($sql,"$tahap")."'>".OCIResult($sql,"$tahap")."</option>";
	}
}
echo "</select>";
echo "</td>";

echo "<td>";
echo "<select name='kelas' ><option value=''>Pilih Kelas</option>";
$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM $theadcount WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND $tahap='$ting' ORDER BY kelas");
OCIExecute($kelas_sql);
while(OCIFetch($kelas_sql)) { 
	if(OCIResult($kelas_sql,"KELAS")==@$kelas){
		echo "<option selected value='".OCIResult($sql,"KELAS")."'>".OCIResult($sql,"KELAS")."</option>"."<BR>";
	}else{
		echo  "<option value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>";
	}
}
echo "</td>";

echo "<td>";
echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
$mpSQL = OCIParse($conn_sispa,"SELECT DISTINCT hmp FROM $theadcount WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND $tahap='$ting' ORDER BY hmp");
OCIExecute($mpSQL);
while(OCIFetch($mpSQL)) { //$noticia
	$kodsubjek = OCIResult($mpSQL,"HMP");
	$tempmpSQL = "SELECT * FROM $tmatap WHERE kod ='$kodsubjek'";
	$temprs_mp = OCIParse($conn_sispa,$tempmpSQL);
	OCIExecute($temprs_mp);
	OCIFetch($temprs_mp); //temmp
	echo  "<option value='".OCIResult($mpSQL,"HMP")."'>".OCIResult($temprs_mp,"MP")." - ".OCIResult($temprs_mp,"KODLEMBAGA")."</option>";
}
echo "</select>";
echo "</td>";

echo "<td><input type='submit' name=\"hc\" value=\"Hantar\"></td>";
echo "</form>";
}
?>
</table>
</td>
<?php include 'kaki.php';?>                                                                                                           