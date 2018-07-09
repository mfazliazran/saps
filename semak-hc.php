<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis TOV/ETR</p>
<?php

if (isset($_POST['semakhc']))

{	
	$ting = $_POST['ting'];	
	$tahun = $_SESSION['tahun'];
	$kodsek = $_SESSION['kodsek'];
	switch ($_SESSION['statussek'])
	{
		case "SR":
			$tmarkah = "markah_pelajarsr";
			$theadcount="headcountsr";
			$tmatap="mpsr";
			$tmurid="tmuridsr";
			$tajuk="DARJAH";
			$tahap="darjah";
			break;

		case "SM" :
			$tmarkah = "markah_pelajar";
			$theadcount="headcount";
			$tmatap="mpsmkc";
			$tmurid="tmurid";
			$tajuk="TINGKATAN";
			$tahap="ting";
			break;
	}

	echo "<H2><center>HEADCOUNT MATA PELAJARAN $ting<br>$namasek<br>TAHUN $tahun</center></H2>\n";
	echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
	echo "<tr><td><INPUT TYPE=\"BUTTON\" VALUE=\"<< KEMBALI\" ONCLICK=\"history.go(-1)\"></td></tr>\n";
	echo "</table>";
	echo "  <table  width=\"98%\"  align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
	echo "  <tr bgcolor=\"#FFCC99\">\n";
	echo "    <td rowspan=\"2\"> <div align=\"center\">BIL</div></td>\n";
	echo "    <td rowspan=\"2\"><div align=\"center\">MATA PELAJARAN</div></td>\n";
	echo "    <td rowspan=\"2\"><div align=\"center\">BIL<br>DAFTAR</div></td>\n";
	echo "    <td colspan=\"7\"><div align=\"center\">TOV</div></td>\n";
	echo "    <td colspan=\"7\"><div align=\"center\">ETR</div></td>\n";
	echo "  </tr>\n";
//////////////////////////  Table TOV  //////////////////////////////////////
	echo "  <tr bgcolor=\"#FFCC99\">\n";
	echo "    <td><div align=\"center\">BIL A</div></td>\n";
	echo "    <td><div align=\"center\">% A</div></td>\n";
	echo "    <td><div align=\"center\">BIL<br>LULUS</div></td>\n";
	echo "    <td><div align=\"center\">%<br>LULUS</div></td>\n";
	echo "    <td><div align=\"center\">BIL<br>GAGAL</div></td>\n";
	echo "    <td><div align=\"center\">%<br>GAGAL</div></td>\n";
	echo "    <td><div align=\"center\">BIL<br>TH</div></td>\n";
/////////////////////// Table ETR ////////////////////////////////////////////
	echo "    <td><div align=\"center\">BIL A</div></td>\n";
	echo "    <td><div align=\"center\">% A</div></td>\n";
	echo "    <td><div align=\"center\">BIL<br>LULUS</div></td>\n";
	echo "    <td><div align=\"center\">%<br>LULUS</div></td>\n";
	echo "    <td><div align=\"center\">BIL<br>GAGAL</div></td>\n";
	echo "    <td><div align=\"center\">%<br>GAGAL</div></td>\n";
	echo "    <td><div align=\"center\">BIL<br>TH</div></td>\n";
	echo "  </tr>\n";
////////////////////////////////////////////////////////////////////////
	$gting = strtolower($ting);
	$qtov = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tahunpep='$tahun' AND tingpep='$ting' AND capai='TOV'");
	oci_execute($qtov);
	$rowtov = oci_fetch_array($qtov);
	
	$qsubsek =  oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' ORDER BY kodmp");
	oci_execute($qsubsek);
	$bil=0;

	while ($rowmp = oci_fetch_array($qsubsek)){
		$subsek = $rowmp['KODMP'];

		$qhcmurid = "SELECT * FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND hmp='$subsek' and gtov is not null";
		$stmt = oci_parse($conn_sispa,$qhcmurid);
		oci_execute($stmt);
		$bilcalon = count_row($qhcmurid);

		switch ($ting) {
			case "D1": case "d1": case "D2": case "d2": case "D3": case "d3": case "D4": case "d4": case "D5": case "d5": case "D6": case "d6":
			{
				$tovA = $tovB = $tovC = $tovD = $tovE = $tovF = $tovL = $tovG = $tovTH = 0;
				$etrA = $etrB = $etrC = $etrD = $etrE = $etrF = $etrL = $etrG = $etrTH = 0;
				while ($record = oci_fetch_array($stmt))
				{
					switch (trim($record['GTOV']))
					{
						case 'A' : $tovA = $tovA + 1; break;
						case 'B' : $tovB = $tovB + 1; break;
						case 'C' : $tovC = $tovC + 1; break;
						case 'D' : $tovD = $tovD + 1; break;
						case 'E' : $tovE = $tovE + 1; break;
						case 'F' : $tovF = $tovF + 1; break;
						case 'TH' : $tovTH = $tovTH + 1; break;
					}
					switch (trim($record['GETR']))
					{
						case 'A' : $etrA = $etrA + 1; break;
						case 'B' : $etrB = $etrB + 1; break;
						case 'C' : $etrC = $etrC + 1; break;
						case 'D' : $etrD = $etrD + 1; break;
						case 'E' : $etrE = $etrE + 1; break;
						case 'F' : $etrF = $etrF + 1; break;
						case 'TH' : $etrTH = $etrTH + 1; break;
					}				
				}//while ($record = oci_fetch_array($stmt))
			}//case "D1": case "d1":
			break;
			
			case "P" : case "p" : case "T1": case "t1": case "T2": case "t2": case "T3": case "t3": 
			{
				$tovA = $tovL = $tovG = $tovTH = $etrA = $etrL = $etrG = $etrTH = $tovF = $etrF = 0 ;
				while ($record = oci_fetch_array($stmt))
				{
					switch (trim($record['GTOV']))
					{
						case 'A' : $tovA = $tovA + 1; break;
						case 'B' : case 'C' : case 'D' : $tovL = $tovL + 1; break;
						case 'E' : $tovG = $tovG + 1; break;
						case 'TH' : $tovTH = $tovTH + 1; break;
						case 'F' : $tovF = $tovF + 1; break;
					}

					switch (trim($record['GETR']))
					{
						case 'A' : $etrA = $etrA + 1; break;
						case 'B' : case 'C' : case 'D' : $etrL = $etrL + 1; break;
						case 'E' : $etrG = $etrG + 1; break;
						case 'TH' : $etrTH = $etrTH + 1; break;
						case 'F' : $etrF = $etrF + 1; break;
					}				
				}//while ($record = oci_fetch_array($stmt))
			}//case "P" : case "p" :
			break;

			case "T4": case "t4": case "T5": case "t5":
			{	
				$tovA = $tovL = $tovG = $tovTH = $etrA = $etrL = $etrG = $etrTH = 0;
				while ($record = oci_fetch_array($stmt))
				{
					switch (trim($record['GTOV']))
					{
						case 'A+' : case 'A' : case 'A-' : $tovA = $tovA + 1; break;
						case 'B+' : case 'B' : case 'C+' : case 'C' : case 'D' : case 'E' : $tovL = $tovL + 1; break;
						case 'G' : $tovG = $tovG + 1; break;
						case 'TH' : $tovTH = $tovTH + 1; break;
					}

					switch (trim($record['GETR']))
					{
						case 'A+' : case 'A' : case 'A-' : $etrA = $etrA + 1; break;
						case 'B+' : case 'B' : case 'C+' : case 'C' : case 'D' : case 'E' :  $etrL = $etrL + 1; break;
						case 'G' : $etrG = $etrG + 1; break;
						case 'TH' : $etrTH = $etrTH + 1; break;
					}										
				}//while ($record = oci_fetch_array($stmt))
			}//case "T4": case "t4":
			break;
		}//switch ($ting) {
		
		$bil=$bil+1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		if($tahun<=2014){
			$biltovlulus = $tovA + $tovB + $tovC ; 
			$biletrlulus = $etrA + $etrB + $etrC ;	
		}elseif($tahun==2015){
			if($ting=='D6'){
				$biltovlulus = $tovA + $tovB + $tovC ;
				$biletrlulus = $etrA + $etrB + $etrC ;
			}else{
				if($ting!='T4' and $ting!='T5'){//D1-D5
					$biltovlulus = $tovA + $tovL + $tovG;
					$biletrlulus = $etrA + $etrL + $etrG;	
					$tovG = $tovF;
					$etrG = $etrF;			
				}else{//T4 & T5
					$biltovlulus = $tovA + $tovL;
					$biletrlulus = $etrA + $etrL;
				}
			}
		}else{//> 2016
			if($ting=="T1" or $ting=="T2" or $ting=="T3"){
				$biltovlulus = $tovA + $tovL + $tovG;
				$biletrlulus = $etrA + $etrL + $etrG;	
				$tovG = $tovF;
				$etrG = $etrF;
			}elseif($ting=="T4" or $ting=="T5"){
				$biltovlulus = $tovA + $tovL;
				$biletrlulus = $etrA + $etrL;
			}else{//D1-D6 >= 2016
				$biltovlulus = $tovA + $tovB + $tovC + $tovD;
				$biletrlulus = $etrA + $etrB + $etrC + $etrD;
				$tovG = $tovE; // Gagal = E
				$etrG = $etrE;	
			}
		}
		
		if ($bilcalon != 0){
			$peratustovA = number_format(($tovA/($bilcalon - $tovTH))*100,2,'.',',');
			$peratustovlulus = number_format(($biltovlulus/($bilcalon - $tovTH))*100,2,'.',',');
			$peratustovG = number_format(($tovG/($bilcalon - $tovTH))*100,2,'.',',');
			$peratusetrA = number_format(($etrA/($bilcalon - $etrTH))*100,2,'.',',');
			$peratusetrlulus = number_format(($biletrlulus/($bilcalon - $etrTH))*100,2,'.',',');
			$peratusetrG = number_format(($etrG/($bilcalon - $etrTH))*100,2,'.',',');	
		} else { $peratustovA = $peratustovlulus = $peratustovG = $peratusetrA = $peratusetrlulus = $peratusetrG = 0.00 ; }

		$querykod = oci_parse($conn_sispa,"SELECT mp FROM $tmatap WHERE kod='$subsek'") or die('Error, query Subjek');
		oci_execute($querykod);
		$resultkod = oci_fetch_array($querykod);
		$namamp=$resultkod['MP'];

		echo "  <tr bgcolor='$bcol'>\n";
		echo "    <td><div align=\"center\">$bil</div></td>\n";
		//echo "<td><a href=semak-hc-mp.php?data=".$ting."/".$subsek."/".$_SESSION['tahun']."/".$kodsek." target=_blank><div align=\"left\">$namamp</div></td>\n";
		echo "<td><a href=semak-hc-mp.php?data=".$ting."|".$subsek." target=_blank><div align=\"left\">$namamp</div></td>\n";
		//<a href=hc-mp-ting.php?datahc=".$kodsek."/".$_SESSION['tahun']."/".$subsek."></a>
		echo "    <td><div align=\"center\">$bilcalon</div></td>\n";
		echo "    <td><div align=\"center\">$tovA</div></td>\n";
		echo "    <td><div align=\"center\">$peratustovA</div></td>\n";
		echo "    <td><div align=\"center\">$biltovlulus</div></td>\n";
		echo "    <td><div align=\"center\">$peratustovlulus</div></td>\n";
		echo "    <td><div align=\"center\">$tovG</div></td>\n";
		echo "    <td><div align=\"center\">$peratustovG</div></td>\n";
		echo "    <td><div align=\"center\">$tovTH</div></td>\n";
		echo "    <td><div align=\"center\">$etrA</div></td>\n";
		echo "    <td><div align=\"center\">$peratusetrA</div></td>\n";
		echo "    <td><div align=\"center\">$biletrlulus</div></td>\n";
		echo "    <td><div align=\"center\">$peratusetrlulus</div></td>\n";
		echo "    <td><div align=\"center\">$etrG</div></td>\n";
		echo "    <td><div align=\"center\">$peratusetrG</div></td>\n";
		echo "    <td><div align=\"center\">$etrTH</div></td>\n";
		echo "  </tr>\n";
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
		echo " <center><h3>HEADCOUNT MATA PELAJARAN MENGIKUT TINGKATAN</h3></center>";
		echo "<br>";
		echo " <center><b>SILA PILIH TINGKATAN/DARJAH</b></center>";
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


                                                           