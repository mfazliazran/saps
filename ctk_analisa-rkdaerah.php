<html>
<body>
<STYLE type="text/css">
@media print {
  #mybutton { display:none; visibility:hidden; }

  #mybutton2 { display:none; visibility:hidden; }
 
}



</STYLE>

<style type="text/css">
.style1 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 16px;
	color: #000000;
 	font-weight: bold; 
}

.style2 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;
 
}

.style3 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;
 	font-weight: bold; 
}

</style>
<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>
<?php
include 'config.php';
include 'fungsi.php';
include 'input_validation.php';

?>


<td valign="top" class="rightColumn">
<span class="style1">Analisis Peperiksaan</span>

<?php

	$tahun=validate($_GET['tahun']);
	$ting=validate($_GET['ting']);
	$status=validate($_GET['status']);
	$kodppd = validate($_GET['kodppd']);

    $sqlppd = "SELECT ppd FROM tkppd WHERE kodppd= :kodppd";
	$stmt=oci_parse($conn_sispa,$sqlppd);
	oci_bind_by_name($stmt, ':kodppd', $kodppd);
    oci_execute($stmt);
	
    $rowppd = oci_fetch_array($stmt);
    $namappd = $rowppd["PPD"];

	echo "<div align=\"center\"><span class=\"style3\">KEPUTUSAN KESELURUHAN<br>TOV DAN TARGET SEKOLAH RK DAN KAA<br> ".tahap($ting)."<br>DAERAH $namappd($kodppd) TAHUN $tahun</span></div><br>\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
	echo "<table width=\"90%\" border=\"1\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <td rowspan=\"3\" align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
	echo "    <td rowspan=\"3\" width=30% align=\"center\" valign=\"top\"><span class=\"style3\">Nama Sekolah </span></td>\n";
	echo "    <td rowspan=\"3\" align=\"center\" valign=\"top\"><span class=\"style3\">Kelas</span></td>\n";
	echo "    <td rowspan=\"3\" align=\"center\" valign=\"top\"><span class=\"style3\">Bil Murid </span></td>\n";
	echo "    <td colspan=\"8\" align=\"center\" valign=\"top\"><span class=\"style3\">A Semua Mata Pelajaran </span></td>\n";
	echo "    <td colspan=\"8\" align=\"center\" valign=\"top\"><span class=\"style3\">Sekurang-kurangnya Lulus Semua D </span></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">TOV</span></td>\n";
	echo "    <td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">PPT / AR1 </span></td>\n";
	echo "    <td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">PMRC / AR2 </span></td>\n";
	echo "    <td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">ETR</span></td>\n";
	echo "    <td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">TOV</span></td>\n";
	echo "    <td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">PPT/ AR1 </span></td>\n";
	echo "    <td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">PMRC / AR2 </span></td>\n";
	echo "    <td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style3\">ETR</span></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">Bil</span></td>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style3\">%</span></td>\n";
	echo "  </tr>\n";
	

	$bil=0;
	$qnama_rk = "SELECT * FROM tkaark rk, tsekolah ppd WHERE rk.kodsek=ppd.kodsek AND ppd.kodppd= :kodppd AND rk.ting= :ting ORDER BY rk.namasek";	
	$stmt=oci_parse($conn_sispa,$qnama_rk);
	oci_bind_by_name($stmt, ':kodppd', $kodppd);
	oci_bind_by_name($stmt, ':ting', $ting);
    oci_execute($stmt);
	
	$i=0;
	while($r_kaark = oci_fetch_array($stmt))
	{
		$biltovSA = $biltovSD = $biletrSA = $biletrSD =  $bilatrSA = $bilatrSD = $bilatr2SA = $bilatr2SD = 0; 
		$bil=$bil+1; $kodsek=$r_kaark["KODSEK"]; $namasek=$r_kaark["NAMASEK"]; $ting=$r_kaark["TING"]; $kelas=$r_kaark["KELAS"]; $cting=strtoupper($ting);
		
		$q_nokp = "SELECT DISTINCT nokp FROM headcount WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas   ORDER BY nokp";
		$stmt=oci_parse($conn_sispa,$q_nokp);
        oci_bind_by_name($stmt, ':tahun', $tahun);
	    oci_bind_by_name($stmt, ':kodsek', $kodsek);
		oci_bind_by_name($stmt, ':ting', $ting);
	    oci_bind_by_name($stmt, ':kelas', $kelas);
	    oci_execute($stmt);

		$num = OCIFetch($stmt);	
		while($r_nokp = OCIResult($stmt,"R_NOKP"));
		{
			$q_tovetr = "SELECT * FROM headcount WHERE nokp='$r_nokp[nokp]' AND tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas ORDER BY hmp";
			$stmt=oci_parse($conn_sispa,$q_tovetr);
            oci_bind_by_name($stmt, ':tahun', $tahun);
	        oci_bind_by_name($stmt, ':kodsek', $kodsek);
		    oci_bind_by_name($stmt, ':ting', $ting);
	        oci_bind_by_name($stmt, ':kelas', $kelas);
			oci_execute($stmt);
			
			$bilmp = $biltovA = $biltovLS = $biletrA = $biletrLS = $bilatrA = $bilatrLS =  $bilatr2A = $bilatr2LS = 0;
			while($r_tovetr = OCIFetch($stmt))
			{
				$q_sub = "SELECT * FROM sub_mr WHERE kod='$r_tovetr[hmp]'";
				$stmt=OCIParse($conn_sispa,$q_sub);
                OCIExecute($stmt);
				
				if (count_row($stmt)!= 0){
					$bilmp = $bilmp + 1;
					switch ($r_tovetr[gtov])
					{
						case "A" : $biltovA = $biltovA + 1; break;
						case "B" : case "C" : case "D" : $biltovLS = $biltovLS + 1 ; break;
					}
					switch ($r_tovetr[getr])
					{
						case "A" : $biletrA = $biletrA + 1; break;
						case "B" : case "C" : case "D" : $biletrLS = $biletrLS + 1 ; break;
					}
					$gmp = "G$r_tovetr[hmp]";
					$q_atr = "SELECT $gmp FROM markah_pelajar WHERE nokp='$r_nokp[nokp]' AND tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND jpep='PPT'";
					$stmt=oci_parse($conn_sispa,$q_atr);
                    oci_bind_by_name($stmt, ':tahun', $tahun);
	                oci_bind_by_name($stmt, ':kodsek', $kodsek);
		            oci_bind_by_name($stmt, ':ting', $ting);
	                oci_bind_by_name($stmt, ':kelas', $kelas);
					oci_execute($stmt);
				
					$r_atr = OCIFetch($stmt);
					switch (OCIResult($stmt,"GMP"))
					{
						case "A" : $bilatrA = $bilatrA + 1; break;
						case "B" : case "C" : case "D" : $bilatrLS = $bilatrLS + 1 ; break;
					}
					$q_atr2 = "SELECT $gmp FROM markah_pelajar WHERE nokp='$r_nokp[nokp]' AND tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND jpep='PMRC'"; 
					$stmt=oci_parse($conn_sispa,$q_atr2);
                    oci_bind_by_name($stmt, ':tahun', $tahun);
	                oci_bind_by_name($stmt, ':kodsek', $kodsek);
		            oci_bind_by_name($stmt, ':ting', $ting);
	                oci_bind_by_name($stmt, ':kelas', $kelas);
					oci_execute($stmt);
					
					$r_atr2 = OCIFetch($stmt);
					switch ($r_atr2["$gmp"])
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
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "    <td valign=\"top\"><span class=\"style2\">$namasek</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$kelas</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$num</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$biltovSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratustovSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bilatrSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusatrSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bilatr2SA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusatr2SA</span></td>\n";	
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$biletrSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusetrSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$biltovSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratustovSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bilatrSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusatrSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bilatr2SD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusatr2SD</span></td>\n";	
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$biletrSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusetrSD</span></td>\n";
		echo "  </tr>\n";
		
		
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
		echo "    <td colspan=\"3\" align=\"center\" valign=\"top\"><span class=\"style2\">Jumlah</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbil</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumtovSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumtovSA </span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumatrSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumatrSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumatr2SA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumatr2SA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumetrSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumetrSA</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumtovSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumtovSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumatrSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumatrSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumatr2SD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$peratusjumatr2SD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumetrSD</span></td>\n";
		echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\"></center>$peratusjumetrSD</span></td>\n";
		echo "  </tr>\n";
	echo "</table>\n";
	
	
?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>