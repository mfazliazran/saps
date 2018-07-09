<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';

$cola + $_GET['tahun'];
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Headcount Mengikut Mata Pelajaran</p>
<?php
if (isset($_POST['hc']))
{
	$tahun = $_POST['tahun_semasa'];
	$kodmp = $_POST['kodmp'];
	$ting = $_POST['ting'];
	$status = $_POST['statush'];
  ?>
<div align=right>
<a href="ctk_hcmp-jpn.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&kodmp=<?php echo $kodmp;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>" target=_blank ><img src=printi.jpg border=0></a></div><br><br>
<?php
	switch ($ting)
	{

		//case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			//$tahap="darjah";
			//$headcount="headcountsr";
			//$murid="tmuridsr";
			//break;

		//case "P": case "T1": case "T2": case "T3": 
			//$tahap="ting";
			///$level="MR";
			//$headcount="headcount";
			//$murid="tmurid";
			//break;
			
		case "T4": case "T5":
			$tahap="ting";
			$headcount="headcount";
			$murid="tmurid";
			break;
	}


echo "<div align=\"center\">ANALISIS HEADCOUNT PELAJAR - ".tahap($ting)." ( $status )<BR>MATA PELAJARAN $kodmp<br>SELURUH NEGERI<br> TAHUN $tahun</h3></div><BR>\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<table width=\"90%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#666666\">\n";
echo "  <tr>\n";
echo "    <td rowspan=\"2\">Bil</td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">NEGERI</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"4\"><div align=\"center\">TOV</div></td>\n";
echo "    <td colspan=\"4\"><div align=\"center\">Pertengahan Tahun </div></td>\n";
echo "    <td colspan=\"4\"><div align=\"center\">Percubaan/PAT</div></td>\n";
echo "    <td colspan=\"4\"><div align=\"center\">ETR</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemer</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemer</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemer</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Lulus</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Cemer</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "  </tr>\n";

//$qppd=oci_parse($conn_sispa,"SELECT * FROM tkppd WHERE kodnegeri='".$_SESSION["negeri"]."'ORDER BY KodPPD");
$qppd=oci_parse($conn_sispa,"SELECT negeri FROM tknegeri order by negeri");
oci_execute($qppd);
while($rppd = oci_fetch_array($qppd))
{
	$bilppd = $bilppd + 1;
	echo "  <tr>\n";
	echo "    <td>$bilppd</td>\n";
	echo "    <td>$rppd[NEGERI]</td>\n";

	if($level=='8')
	$qjpnhc = oci_parse($conn_sispa,"SELECT sum(bcalon) as BILC,sum(tovlulus) as TOVL,sum(tovcmlang) as TOVC, sum(pptlulus) as PPTL, sum(pptcmlang) as PPTC,sum(patcmlang) as PATC, sum(patlulus) as PATL, sum(etrlulus) as ETRL, sum(etrcmlang) as ETRC FROM jpnhc,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND kodmp='$kodmp' AND kodjenissekolah in ('202','203')");
	
	else
	$qjpnhc = oci_parse($conn_sispa,"SELECT sum(bcalon) as BILC,sum(tovlulus) as TOVL,sum(tovcmlang) as TOVC, sum(pptlulus) as PPTL, sum(pptcmlang) as PPTC,sum(patcmlang) as PATC, sum(patlulus) as PATL, sum(etrlulus) as ETRL, sum(etrcmlang) as ETRC FROM jpnhc WHERE tahun='$tahun' AND kodppd='$rppd[KODPPD]' AND ting='$ting' AND kodmp='$kodmp'");
	
	//echo "SELECT sum(bcalon) as BILC,sum(tovlulus) as TOVL,sum(tovcmlang) as TOVC, sum(pptlulus) as PPTL, sum(pptcmlang) as PPTC,sum(patcmlang) as PATC, sum(patlulus) as PATL, sum(etrlulus) as ETRL, sum(etrcmlang) as ETRC FROM jpnhc,tsekolah WHERE tahun='$tahun' AND ting='$ting' AND kodmp='$kodmp' AND kodjenissekolah in ('202','203')";
	
	oci_execute($qjpnhc);
	$rjpnhc = oci_fetch_array($qjpnhc);
	$bcalon1 = $rjpnhc[BILC] + 0;
	$tovlulus1 = $rjpnhc[TOVL] + 0;
	$tovcmlang1 = $rjpnhc[TOVC] + 0;
	$pptlulus1 = $rjpnhc[PPTL] + 0;
	$pptcmlang1 = $rjpnhc[PPTC] + 0;
	$patcmlang1 = $rjpnhc[PATC] + 0;
	$patlulus1 = $rjpnhc[PATL] + 0;
	$etrlulus1 = $rjpnhc[ETRL] + 0;
	$etrcmlang1 = $rjpnhc[ETRC] + 0;

	//echo "    <td><center>$rjpnhc[bcalon]</center></td>\n";
	echo "    <td><center>$bcalon1</center></td>\n";
	//echo "    <td><center>$rjpnhc[tovlulus]</center></td>\n";
	echo "    <td><center>$tovlulus1</center></td>\n";
	if($rjpnhc[TOVL]!=0) { $pertovL = number_format(($rjpnhc[TOVL]/$rjpnhc[BILC])*100,'2','.',',');
	 } else { $pertovL = 0.00; }
	echo "    <td><center>$pertovL</center></td>\n";

	//echo "    <td><center>$rjpnhc[tovcmlang]</center></td>\n";
	echo "    <td><center>$tovcmlang1</center></td>\n";
	
	if($rjpnhc[TOVC]!=0) { $pertovC = number_format(($rjpnhc[TOVC]/$rjpnhc[BILC])*100,'2','.',','); 
	} else { $pertovC = 0.00; }
	//echo "    <td><center>$pertovcmlang</center></td>\n";
	echo "    <td><center>$pertovC</center></td>\n";

	//echo "    <td><center>$rjpnhc[pptlulus]</center></td>\n";
	echo "    <td><center>$pptlulus1</center></td>\n";
	
	if($rjpnhc[PPTL]!=0) { $perpptL = number_format(($rjpnhc[PPTL]/$rjpnhc[BILC])*100,'2','.',','); 
	} else { $perpptL = 0.00; }
	echo "    <td><center>$perpptL</center></td>\n";
	
	//echo "    <td><center>$rjpnhc[pptcmlang]</center></td>\n";
	echo "    <td><center>$pptcmlang1</center></td>\n";
	
	if($rjpnhc[PPTC]!=0) { $perpptC = number_format(($rjpnhc[PPTC]/$rjpnhc[BILC])*100,'2','.',','); 
	} else { $perpptC = 0.00; }
	echo "    <td><center>$perpptC</center></td>\n";
	
	///////////////////////////// akhir tahun atau percubaan bagi T3 dan T5 ///////////////////////////////////////////
	//echo "    <td><center>$rjpnhc[patlulus]</center></td>\n";
	echo "    <td><center>$patlulus1</center></td>\n";
	
	if($rjpnhc[PATL]!=0) { $perpatL = number_format(($rjpnhc[PATL]/$rjpnhc[BILC])*100,'2','.',','); 
	} else { $perpatL = 0.00; }
	echo "    <td><center>$perpatL</center></td>\n";
	
	//echo "    <td><center>$rjpnhc[patcmlang]</center></td>\n";
	echo "    <td><center>$patcmlang1</center></td>\n";
	if($rjpnhc[PATC]!=0) { $perpatC = number_format(($rjpnhc[PATC]/$rjpnhc[BILC])*100,'2','.',','); 
	} else { $perpatC = 0.00; }
	echo "    <td><center>$perpatC</center></td>\n";
	
	//////////////////////////////////////////////////////////////////////////// target //////////////////////////////////////////////////////////////
	//echo "    <td><center>$rjpnhc[etrlulus]</center></td>\n";
	echo "    <td><center>$etrlulus1</center></td>\n";
	
	if($rjpnhc[ETRL]!=0) { $peretrL = number_format(($rjpnhc[ETRL]/$rjpnhc[BILC])*100,'2','.',','); 
	} else { $peretrL = 0.00; }
	echo "    <td><center>$peretrL</center></td>\n";
	
	//echo "    <td><center>$rjpnhc[etrcmlang]</center></td>\n";
	echo "    <td><center>$etrcmlang1</center></td>\n";
	
	if($rjpnhc[ETRC]!=0) { $perpatC = number_format(($rjpnhc[ETRC]/$rjpnhc[BILC])*100,'2','.',','); 
	} else { $peretrC = 0.00; }
	echo "    <td><center>$perpatC</center></td>\n";
	echo "  </tr>\n";
	/////////////////////////////////////////////////////////////////
	$jumbil = $jumbil + $rjpnhc[BILC];
	$jumbiltovL = $jumbiltovL + $rjpnhc[TOVL];
	$jumbiltovC = $jumbiltovC + $rjpnhc[TOVC];
	$jumbilatr1L = $jumbilatr1L + $rjpnhc[PPTL];
	$jumbilatr1C = $jumbilatr1C + $rjpnhc[PPTC];
	$jumbilatr2L = $jumbilatr2L + $rjpnhc[PATL];
	$jumbilatr2C = $jumbilatr2C + $rjpnhc[PATC];
	$jumbiletrL = $jumbiletrL + $rjpnhc[ETRL];
	$jumbiletrC = $jumbiletrC + $rjpnhc[ETRC];
	//////////////////////////////////////////
	$jumpertovL=$jumpertovL + $pertovL;
	$jumpertovC=$jumpertovC + $pertovC;
	$jumperatr1L=$jumperatr1L + $perpptL;
	$jumperatr1C=$jumperatr1C + $perpptC;
	$jumperatr2L=$jumperatr2L + $perpatL;
	$jumperatr2C=$jumperatr2C + $perpatC;
	$jumperetrL=$jumperetrL + $peretrL;
	$jumperetrC=$jumperetrC + $peretrC;
}

if ($jumbil != 0 )
{ 	$a=number_format(($jumbiltovL/$jumbil)*100,'2','.',',');
	$b=number_format(($jumbiltovC/$jumbil)*100,'2','.',',');
	$c=number_format(($jumbilatr1L/$jumbil)*100,'2','.',',');
	$d=number_format(($jumbilatr1C/$jumbil)*100,'2','.',',');
	$e=number_format(($jumbiletrL/$jumbil)*100,'2','.',',');
	$f=number_format(($jumbiletrC/$jumbil)*100,'2','.',',');
	$g=number_format(($jumbilatr2L/$jumbil)*100,'2','.',',');
	$h=number_format(($jumbilatr2C/$jumbil)*100,'2','.',',');
} else {
			$a = $b = $c = $d = $e = $f = $g = $h = 0.00 ;
		}
		
echo "  <tr>\n";
echo "    <td colspan=\"2\"><div align=\"center\">Jumlah</div></td>\n";
echo "    <td><center>$jumbil</center></td>\n";
echo "    <td><center>$jumbiltovL</center></td>\n";
echo "    <td><center>$a</center></td>\n";
echo "    <td><center>$jumbiltovC</center></td>\n";
echo "   <td><center>$b</center></td>\n";
echo "    <td><center>$jumbilatr1L</center></td>\n";
echo "    <td><center>$c</center></td>\n";
echo "    <td><center>$jumbilatr1C</center></td>\n";
echo "    <td><center>$d</center></td>\n";
echo "    <td><center>$jumbilatr2L</center></td>\n";
echo "    <td><center>$g</center></td>\n";
echo "    <td><center>$jumbilatr2C</center></td>\n";
echo "    <td><center>$h</center></td>\n";
echo "    <td><center>$jumbiletrL</center></td>\n";
echo "    <td><center>$e</center></td>\n";
echo "    <td><center>$jumbiletrC</center></td>\n";
echo "     <td><center>$f</center></td>\n";
echo "  </tr>\n";
echo "</table>\n";
} 
else { ?>
 <script type="text/javascript">
 
//function pilih_status(status)
//{
//alert(nama_bulan)

//location.href="hcmp-bptv.php?status=" + status

//}

function pilih_tahun(tahun_semasa)
{
//alert(nama_bulan)
//alert(tahun)



status = document.f1.status.value
location.href="hcmp-bptv.php?tahun=" + tahun_semasa


}
</script>

<?php

if($tahun_semasa <> "") {
	$tahun = $tahun_semasa;
} else {
	$tahun = date("Y");
}

$_SESSION['tahun'] = $tahun;

$tahun_sekarang = date("Y");


?>
		<?php
		//echo "$kodsek";
		echo "<br><br><br><br><br>";
		echo " <center><b>SILA PILIH TAHUN, TINGKATAN DAN MATA PELAJARAN</b></center>";
		echo "<br><br>";
		//echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"450\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		//echo "  <td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td>";
		echo " </tr><tr>";
		
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
			
		$tahun = $_GET['tahun'];
		// $pepr = $_GET["pep"];	
		$status = $_GET['status'];
		if($status == "")
			$status = "MA";
		
		switch ($status)
		{
			//case "MR" : $tahap = "MENENGAH RENDAH"; ; $tmp = "sub_mr"; break;
			case "MA" : $tahap = "MENENGAH ATAS"; $tmp = "mpsmkc"; break;
			//case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "sub_sr"; break;
			default : $tahap = "Pilih Tahap"; break;
		}	
		
     	

		echo "<form method=post name='f1' action='hcmp-bptv.php'>";
		echo "<td>TAHAP</td><td><select name=\"status\" onchange=\"pilih_status(this.value)\"><option value=''>$tahap</option>";
		//echo "<option value=\"MR\">MENENGAH RENDAH</option>";
		echo "<option value=\"MA\">MENENGAH ATAS</option>";
		//echo "<option value=\"SR\">SEKOLAH RENDAH</option>";
		echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";


		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td>";
		?>
        
			<select name="tahun_semasa" id="tahun_semasa" onchange="pilih_tahun(this.value)">
			<option value="">-- Pilih Tahun --</option>
			<?php
			for($thn = 2011; $thn <= $tahun_sekarang; $thn++ ){
				if($tahun == $thn){
					echo "<option value='$thn' selected>$thn</option>";
				} else {
					echo "<option value='$thn'>$thn</option>";
				}
			}			
		echo "</td></tr>";


	echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN</td><td>";
		echo "<select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			//case "MR" :	echo "<option value=\"P\">P</option>";
						//echo "<option value=\"T1\">T1</option>";
						//echo "<option value=\"T2\">T2</option>";
						//echo "<option value=\"T3\">T3</option>";
						//break;
						
			case "MA" : echo "<option value=\"T4\">T4</option>";
						echo "<option value=\"T5\">T5</option>";
						break;
						
			//case "SR" :	echo "<option value=\"D1\">D1</option>";
						//echo "<option value=\"D2\">D2</option>";
						//echo "<option value=\"D3\">D3</option>";
						//echo "<option value=\"D4\">D4</option>";
						//echo "<option value=\"D5\">D5</option>";
						//echo "<option value=\"D6\">D6</option>";
						//break;
		}
		echo "</select>";


		//////////        Starting of second drop downlist /////////
	if($tmp=='mpsmkc'){
	if($tahun=='2011')
		$qry = " where kod not in (select kod from mpsmkc where kod like '%MA%')";
	else
		$qry = " where kod not in (select kod from sub_mr)";
		
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp $qry ORDER BY mp");
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
		echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) {
			echo  "<option value='".$rowmp["KOD"]."'>".$rowmp["MP"]."</option>";
		}
		echo "</select>";
	} else {
			$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
		echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) {
			echo  "<option value='".$rowmp["KOD"]."'>".$rowmp["MP"]."</option>";
		}
		echo "</select>";
	}
		echo "</td>";

		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</tr></table><br><br>";
         print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";	
		 echo "<center><input type='submit' name=\"hc\" value=\"Hantar\"></center>";

		echo "</form>";
} ?> 
</td>
<?php include 'kaki.php';?> 
