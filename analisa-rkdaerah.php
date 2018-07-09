<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';

$sqlppd = oci_parse($conn_sispa,"SELECT ppd FROM tkppd WHERE kodppd='$kodsek'");
oci_execute($sqlppd);
$rowppd = oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Peperiksaan Daerah <?php echo "$namappd" ?></p>

<?php

if (isset($_POST['rkaa']))
{
	$tahun=$_POST['tahun'];
	$ting=$_POST['ting'];
	$status=$_POST['statush'];
	$kodppd = $_POST['kodppd'];
	$namappd = $_POST['namappd'];
	//$tahun = date("Y");
?>

<div align=right><a href="ctk_analisa-rkdaerah.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>&&namappd=<?php echo $namappd;?>" target=_blank ><img src=printi.jpg border=0></a></div>

<?php
	echo "<div align=\"center\"><p>KEPUTUSAN KESELURUHAN<br>TOV DAN TARGET SEKOLAH RK DAN KAA<br> ".tahap($ting)." TAHUN $tahun<br>DAERAH $namappd</p></div>\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
	echo "<table width=\"90%\" border=\"1\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <td rowspan=\"3\"><div align=\"center\">Bil</div>      <div align=\"center\"></div>      <div align=\"center\"></div></td>\n";
	echo "    <td rowspan=\"3\" width=30%><div align=\"center\">Nama Sekolah </div></td>\n";
	echo "    <td rowspan=\"3\"><div align=\"center\">Kelas</div></td>\n";
	echo "    <td rowspan=\"3\"><div align=\"center\">Bil Murid </div></td>\n";
	echo "    <td colspan=\"8\"><div align=\"center\">A Semua Mata Pelajaran </div></td>\n";
	echo "    <td colspan=\"8\"><div align=\"center\">Sekurang-kurangnya Lulus Semua D </div></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">TOV</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">PPT / AR1 </div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">PMRC / AR2 </div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">ETR</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">TOV</div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">PPT/ AR1 </div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">PMRC / AR2 </div></td>\n";
	echo "    <td colspan=\"2\"><div align=\"center\">ETR</div></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
	echo "  </tr>\n";
	
	
	?>
<form action="jpn-rkdaerah.php" method="POST" target=_self>

<?php

	$bil=0;
	$qnama_rk = oci_parse($conn_sispa,"SELECT * FROM tkaark rk, tsekolah ppd WHERE rk.kodsek=ppd.kodsek AND ppd.kodppd= :kodppd AND rk.ting= :ting ORDER BY rk.namasek");
	oci_bind_by_name($qnama_rk, ':ting', $ting);
	oci_bind_by_name($qnama_rk, ':kodppd', $kodppd);
	oci_execute($qnama_rk);
	$i=0;
	while($r_kaark = oci_fetch_array($qnama_rk)){
		$biltovSA = $biltovSD = $biletrSA = $biletrSD =  $bilatrSA = $bilatrSD = $bilatr2SA = $bilatr2SD = 0; 
		$bil=$bil+1; $kodsek=$r_kaark["KODSEK"]; $namasek=$r_kaark["NAMASEK"]; $ting=$r_kaark["TING"]; $kelas=$r_kaark["KELAS"]; $cting=strtoupper($ting);
		$q_nokp = "SELECT DISTINCT nokp FROM headcount WHERE tahun= :tahun AND kodsek='$kodsek' AND ting= :ting AND kelas='$kelas' ORDER BY nokp";
		$qnokp = oci_parse($conn_sispa,$q_nokp);
		oci_bind_by_name($qnokp, ':tahun', $tahun);
		oci_bind_by_name($qnokp, ':ting', $ting);
		oci_execute($qnokp);
		$num = count_row("SELECT DISTINCT nokp FROM headcount WHERE tahun= :tahun AND kodsek='$kodsek' AND ting= :ting AND kelas='$kelas' ORDER BY nokp");	
		while($r_nokp = oci_fetch_array($q_nokp)){
			$q_tovetr = oci_parse($conn_sispa,"SELECT * FROM headcount WHERE nokp='$r_nokp[nokp]' AND tahun= :tahun AND kodsek='$kodsek' AND ting= :ting AND kelas='$kelas' ORDER BY hmp");
			oci_bind_by_name($q_tovetr, ':tahun', $tahun);
			oci_bind_by_name($q_tovetr, ':ting', $ting);
			oci_execute($q_tovetr);
			$bilmp = $biltovA = $biltovLS = $biletrA = $biletrLS = $bilatrA = $bilatrLS =  $bilatr2A = $bilatr2LS = 0;
			while($r_tovetr = oci_fetch_array($q_tovetr)){
				$q_sub = oci_parse($conn_sispa,"SELECT * FROM sub_mr WHERE kod='".$r_tovetr["HMP"]."'");
				oci_execute($q_sub);
				if (count_row("SELECT * FROM sub_mr WHERE kod='".$r_tovetr["HMP"]."'")!= 0){
					$bilmp = $bilmp + 1;
					switch (OCIResult($q_tovetr,"GTOV"))
					{
						case "A" : $biltovA = $biltovA + 1; break;
						case "B" : case "C" : case "D" : $biltovLS = $biltovLS + 1 ; break;
					}
					switch (OCIResult($q_tovetr,"GETR"))
					{
						case "A" : $biletrA = $biletrA + 1; break;
						case "B" : case "C" : case "D" : $biletrLS = $biletrLS + 1 ; break;
					}
					$gmp = "G".OCIResult($q_tovetr,"HMP")."";
					$q_atr = oci_parse($conn_sispa,"SELECT $gmp FROM markah_pelajar WHERE nokp='".OCIResult($qnokp,"NOKP")."' AND tahun= :tahun AND kodsek='$kodsek' AND ting= :ting AND kelas='$kelas' AND jpep='PPT'"); 
					oci_bind_by_name($q_atr, ':tahun', $tahun);
					oci_bind_by_name($q_atr, ':ting', $ting);
					oci_execute($q_atr);
					OCIFetch($q_atr); // $r_atr
					switch (OCIResult($q_atr,"$gmp"))
					{
						case "A" : $bilatrA = $bilatrA + 1; break;
						case "B" : case "C" : case "D" : $bilatrLS = $bilatrLS + 1 ; break;
					}
					$q_atr2 = oci_parse($conn_sispa,"SELECT $gmp FROM markah_pelajar WHERE nokp='".OCIResult($qnokp,"NOKP")."' AND tahun= :tahun AND kodsek='$kodsek' AND ting= :ting AND kelas='$kelas' AND jpep='PMRC'");
					oci_bind_by_name($q_atr2, ':tahun', $tahun);
					oci_bind_by_name($q_atr2, ':ting', $ting);
					oci_execute($q_atr2);
					OCIFetch($q_atr2); //$r_atr2
					switch (OCIResult($q_atr2,"$gmp"))
					{
						case "A" : $bilatr2A = $bilatr2A + 1; break;
						case "B" : case "C" : case "D" : $bilatr2LS = $bilatr2LS + 1 ; break;
					}
	
				}
			}
		if ($bilmp == $biltovA) { $biltovSA = $biltovSA + 1; }
		if ($bilmp == $biltovA + $biltovLS) { $biltovSD = $biltovSD + 1; }
		if ($bilmp == $biletrA) { $biletrSA = $biletrSA + 1; }
		if ($bilmp == $biletrA + $biletrLS) { $biletrSD = $biletrSD + 1; }
		if ($bilmp == $bilatrA) { $bilatrSA = $bilatrSA + 1; }
		if ($bilmp == $bilatrA + $bilatrLS) { $bilatrSD = $bilatrSD + 1; }	
		if ($bilmp == $bilatr2A) { $bilatr2SA = $bilatr2SA + 1; }
		if ($bilmp == $bilatr2A + $bilatr2LS) { $bilatr2SD = $bilatr2SD + 1; }	
	
		}
		if ($num!=0){
			$peratustovSA = number_format(($biltovSA/$num*100),2,'.',',');
			$peratusatrSA = number_format(($bilatrSA/$num*100),2,'.',',');
			$peratusatr2SA = number_format(($bilatr2SA/$num*100),2,'.',',');
			$peratusetrSA = number_format(($biletrSA/$num*100),2,'.',',');
			$peratustovSD = number_format(($biltovSD/$num*100),2,'.',',');
			$peratusatrSD = number_format(($bilatrSD/$num*100),2,'.',',');
			$peratusatr2SD = number_format(($bilatr2SD/$num*100),2,'.',',');
			$peratusetrSD = number_format(($biletrSD/$num*100),2,'.',',');
		} 
		else {
				$peratustovSA = 0.00; $peratusatrSA = 0.00; $peratusatr2SA = 0.00; $peratusetrSA = 0.00;
				$peratustovSD = 0.00; $peratusatrSD = 0.00; $peratusatr2SD = 0.00; $peratusetrSD = 0.00;
			 }
		
		echo "  <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$namasek</td>\n";
		echo "    <td>&nbsp;$kelas&nbsp;</td>\n";
		echo "    <td><center>$num</center></td>\n";
		echo "    <td><center>$biltovSA</center></td>\n";
		echo "    <td><center>$peratustovSA</center></td>\n";
		echo "    <td><center>$bilatrSA</center></td>\n";
		echo "    <td><center>$peratusatrSA</center></td>\n";
		echo "    <td><center>$bilatr2SA</center></td>\n";
		echo "    <td><center>$peratusatr2SA</center></td>\n";	
		echo "    <td><center>$biletrSA</center></td>\n";
		echo "    <td><center>$peratusetrSA</center></td>\n";
		echo "    <td><center>$biltovSD</center></td>\n";
		echo "    <td><center>$peratustovSD</center></td>\n";
		echo "    <td><center>$bilatrSD</center></td>\n";
		echo "    <td><center>$peratusatrSD</center></td>\n";
		echo "    <td><center>$bilatr2SD</center></td>\n";
		echo "    <td><center>$peratusatr2SD</center></td>\n";	
		echo "    <td><center>$biletrSD</center></td>\n";
		echo "    <td><center>$peratusetrSD</center></td>\n";
		echo "  </tr>\n";
		
				
		$i = $i+1;

		print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodppd\">";
		print "<input name=\"kodsek[$i]\" type=\"hidden\" readonly value=\"$kodsek\">";
		print "<input name=\"status\" type=\"hidden\" readonly value=\"$status\">";
		print "<input name=\"kelas[$i]\" type=\"hidden\" readonly value=\"$kelas\">";
		print "<input name=\"bil\" type=\"hidden\" readonly value=\"$bil\">";
		print "<input name=\"ting\" type=\"hidden\" readonly value=\"$ting\">";
		print "<input name=\"tahun\" type=\"hidden\" readonly value=\"$tahun\">";
		print "<input name=\"bilmp[$i]\" type=\"hidden\" readonly value=\"$num\">";
		print "<input name=\"biltovSA[$i]\" type=\"hidden\" readonly value=\"$biltovSA\">";
		print "<input name=\"bilatrSA[$i]\" type=\"hidden\" readonly value=\"$bilatrSA\">";
		print "<input name=\"bilatr2SA[$i]\" type=\"hidden\" readonly value=\"$bilatr2SA\">";
		print "<input name=\"biletrSA[$i]\" type=\"hidden\" readonly value=\"$biletrSA\">";
		print "<input name=\"biltovSD[$i]\" type=\"hidden\" readonly value=\"$biltovSD\">";
		print "<input name=\"bilatrSD[$i]\" type=\"hidden\" readonly value=\"$bilatrSD\">";
		print "<input name=\"bilatr2SD[$i]\" type=\"hidden\" readonly value=\"$bilatr2SD\">";
		print "<input name=\"biletrSD[$i]\" type=\"hidden\" readonly value=\"$biletrSD\">";
		
		
		$jumbil=$jumbil+$num;
		$jumtovSA = $jumtovSA + $biltovSA;
		$jumetrSA = $jumetrSA + $biletrSA;
		$jumatrSA = $jumatrSA + $bilatrSA;
		$jumatr2SA = $jumatr2SA + $bilatr2SA;
		$jumtovSD = $jumtovSD + $biltovSD;
		$jumetrSD = $jumetrSD + $biletrSD;
		$jumatrSD = $jumatrSD + $bilatrSD;
		$jumatr2SD = $jumatr2SD + $bilatr2SD;

		
	}
	
	if ($jumbil!=0)
	{
		$peratusjumtovSA = number_format(($jumtovSA/$jumbil*100),2,'.',',');
		$peratusjumatrSA = number_format(($jumatrSA/$jumbil*100),2,'.',',');
		$peratusjumatr2SA = number_format(($jumatr2SA/$jumbil*100),2,'.',',');
		$peratusjumetrSA = number_format(($jumetrSA/$jumbil*100),2,'.',',');
		$peratusjumtovSD = number_format(($jumtovSD/$jumbil*100),2,'.',',');
		$peratusjumatrSD = number_format(($jumatrSD/$jumbil*100),2,'.',',');
		$peratusjumatr2SD = number_format(($jumatr2SD/$jumbil*100),2,'.',',');
		$peratusjumetrSD = number_format(($jumetrSD/$jumbil*100),2,'.',',');
	}
	
		
	else {
			$peratusjumtovSA = $peratusjumatrSA = $peratusjumatr2SA = $peratusjumetrSA = 0.00;
			$peratusjumtovSD = $peratusjumatrSD = $peratusjumatr2SD = $peratusjumetrSD = 0.00;
		 }
	
		echo "  <tr>\n";
		echo "    <td colspan=\"3\"><center>Jumlah</center></td>\n";
		echo "    <td><center>$jumbil</center></td>\n";
		echo "    <td><center>$jumtovSA</center></td>\n";
		echo "    <td><center>$peratusjumtovSA </center></td>\n";
		echo "    <td><center>$jumatrSA</center></td>\n";
		echo "    <td><center>$peratusjumatrSA</center></td>\n";
		echo "    <td><center>$jumatr2SA</center></td>\n";
		echo "    <td><center>$peratusjumatr2SA</center></td>\n";
		echo "    <td><center>$jumetrSA</center></td>\n";
		echo "    <td><center>$peratusjumetrSA</center></td>\n";
		echo "    <td><center>$jumtovSD</center></td>\n";
		echo "    <td><center></center>$peratusjumtovSD</td>\n";
		echo "    <td><center>$jumatrSD</center></td>\n";
		echo "    <td><center>$peratusjumatrSD</center></td>\n";
		echo "    <td><center>$jumatr2SD</center></td>\n";
		echo "    <td><center>$peratusjumatr2SD</center></td>\n";
		echo "    <td><center>$jumetrSD</center></td>\n";
		echo "    <td><center></center>$peratusjumetrSD</td>\n";
		echo "  </tr>\n";
	echo "</table>\n";
	
	
?>

<br>
<center>
<input type="submit" name="submit" value="HANTAR LAPORAN KE JPN">
</form>
</center>
<?php	
} 
else { ?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='analisa-rkdaerah.php?status=' + val;
		}
		</script>
		
		<?php
		//echo "$kodsek";
		echo "<br><br><br><br>";
		echo " <center></b>ANALISIS HEADCOUNT KESELURUHAN MATA PELAJARAN<br>SEKOLAH RK DAN KAA</b></center>";
		echo "<br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"500\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>TAHUN</td>\n";
		echo "  <td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";

		$status = $_GET['status'];
		if ($status=="")
		$status="SM";
		
		switch ($status)
		{
			case "SM" : $statussek = "MENENGAH RENDAH"; $tmp = "mpsmkc"; break;
			//case "SR" : $statussek = "SEKOLAH RENDAH"; $tmp = "mpsr"; break;
			default : $statussek = "Pilih Jenis Sekolah"; break;
		}

		echo "<form method=post name='f1' action='analisa-rkdaerah.php.php'>";
		echo "<td>JENIS SEKOLAH</td><td><select name=\"status\" onchange=\"reload(this.form)\">";
		?>
		<option <?php if ($status=="SM") echo " selected "; ?> value="SM">MENENGAH RENDAH</option>
		<?php echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";

		$SQL_ting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM tkelassek ORDER BY ting");
		oci_execute($SQL_ting);
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);

		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN</td><td>";
		echo "<select name='ting'><option value=''>Tingkatan</option>";
		switch ($status)
		{
			case "SM" :	//echo "<option value=\"P\">P</option>";
						echo "<option value=\"T1\">T1</option>";
						echo "<option value=\"T2\">T2</option>";
						echo "<option value=\"T3\">T3</option>";
						//echo "<option value=\"T4\">T4</option>";
						//echo "<option value=\"T5\">T5</option>";
						//break;
						
			//case "SR" : echo "<option value=\"D1\">D1</option>";
						//echo "<option value=\"D2\">D2</option>";
						//echo "<option value=\"D3\">D3</option>";
						//echo "<option value=\"D4\">D4</option>";
						//echo "<option value=\"D5\">D5</option>";
						//echo "<option value=\"D6\">D6</option>";
						//break;
		}
		echo "</select>";
		echo "</td>";

		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</tr></table><br><br>";
		print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";	

		echo "<center><input type='submit' name=\"rkaa\" value=\"Hantar\"></center>";

		echo "</form>";
}
?>
</td>
<?php include 'kaki.php';?> 
