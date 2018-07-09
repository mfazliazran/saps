<?php
set_time_limit(0);
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';

?>
<script type="text/javascript" src="ajax/ajaxssqs.js"></script>
<td valign="top" class="rightColumn">
<p class="subHeader">STATUS GPS....<font color="#FFFFFF">(Tarikh Kemaskini Program : 27/2/2015 9:36AM)</font></p>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.status.options[form.status.options.selectedIndex].value;
	self.location='senarai_umum.php?status=' + val;
}
</script>
<?php
	$tahun = htmlentities($_POST['tahun']);
	if($tahun==""){
		$tahun = date("Y");
	}
	$tahun_sekarang = date("Y");
	
	$_SESSION["ses_tahun"] = $tahun;//digunakan utk paparan pencapaian kelas
	$ting = htmlentities($_POST['ting']);
	$jpep = htmlentities($_POST['pep']);
	$lokasi = htmlentities($_POST["txtLokasi"]);
		
		echo "<br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		//echo "  <tr bgcolor=\"#CCCCCC\">\n";
		$status = htmlentities($_GET['status']);
		switch ($status)
		{
			case "MR" : 
				$tahap = "MENENGAH RENDAH"; 
				$kodjpep = " where kod!='SPMC' and kod!='UPSRC'";
				$jenissekolah = "SM";
				break;
			case "MA" : 
				$tahap = "MENENGAH ATAS"; 
				$kodjpep = " where kod!='PMRC' and kod!='UPSRC'";
				$jenissekolah = "SM";
				break;
			case "SR" : 
				$tahap = "SEKOLAH RENDAH"; 
				$kodjpep = " where kod!='SPMC' and kod!='PMRC'";
				$jenissekolah = "SR";
				break;
			default : 
				$tahap = "Pilih Tahap"; 
				$kodjpep = " where kod is not null";
				break;
		}

     	
		echo "<form method=post name='f1' action='senarai_umum.php'>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHAP</td><td><select name=\"status\" onchange=\"reload(this.form)\">";
		
		?>
        <option value="">Pilih Tahap</option>
		<option <?php if ($status=="MR") echo " selected "; ?> value="MR">MENENGAH RENDAH</option>
		<option <?php if ($status=="MA") echo " selected "; ?> value="MA">MENENGAH ATAS</option>
		<option <?php if ($status=="SR") echo " selected "; ?> value="SR">SEKOLAH RENDAH</option>
		<?php echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td><td>";
		echo "<select name='pep'><option value=''>Pilih Peperiksaan</option>";
		$sqljpep = "SELECT DISTINCT kod, jenis,rank FROM jpep $kodjpep ORDER BY rank";
		$SQLpep = oci_parse($conn_sispa,$sqljpep);
		oci_execute($SQLpep);
		while($rowpep = oci_fetch_array($SQLpep)) {
			if($jpep == $rowpep["KOD"])
				echo  "<option selected value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
			else
				echo  "<option value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
		}
		echo "</select>";
		echo "</td></tr>";

		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
		echo "<select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			case "MR" :	echo "<option "; if($ting=='P'){ echo "SELECTED";} echo " value=\"P\">P</option>";
						echo "<option "; if($ting=='T1'){ echo "SELECTED";} echo " value=\"T1\">T1</option>";
						echo "<option "; if($ting=='T2'){ echo "SELECTED";} echo " value=\"T2\">T2</option>";
						echo "<option "; if($ting=='T3'){ echo "SELECTED";} echo " value=\"T3\">T3</option>";
						break;

			case "MA" : echo "<option "; if($ting=='T4'){ echo "SELECTED";} echo " value=\"T4\">T4</option>";
						echo "<option "; if($ting=='T5'){ echo "SELECTED";} echo " value=\"T5\">T5</option>";
						break;

			case "SR" :	echo "<option "; if($ting=='D1'){ echo "SELECTED";} echo " value=\"D1\">D1</option>";
						echo "<option "; if($ting=='D2'){ echo "SELECTED";} echo " value=\"D2\">D2</option>";
						echo "<option "; if($ting=='D3'){ echo "SELECTED";} echo " value=\"D3\">D3</option>";
						echo "<option "; if($ting=='D4'){ echo "SELECTED";} echo " value=\"D4\">D4</option>";
						echo "<option "; if($ting=='D5'){ echo "SELECTED";} echo " value=\"D5\">D5</option>";
						echo "<option "; if($ting=='D6'){ echo "SELECTED";} echo " value=\"D6\">D6</option>";
						break;
		}
		echo "</select></td></tr>";
		if($lokasi=="1"){
			$dis1 = "selected";
		}elseif($lokasi=="2"){
			$dis2 = "selected";
		}else{
			$dis3 = "selected";
		}
		if ($level == "5" or ($level == "6" and $kodjpn=="16" ) or $level == "6"){// untuk role ppd dan JPN Putrajaya dan JPN
			echo "<tr bgcolor=\"#CCCCCC\"><td>LOKASI</td>";
			echo "<td><select name=\"txtLokasi\" id=\"txtLokasi\" >";
			echo "<option value=\"\" $dis3>-Semua-</option>";
			echo "<option value=\"1\" $dis1>Bandar</option>";
			echo "<option value=\"2\" $dis2>Luar Bandar</option>";
			echo "</select></td></tr>";
		}
        
		//echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".$_SESSION["ses_tahun"]."\" size=\"3\" maxlength=\"4\"></td></tr>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td>";
		echo "<select name=\"tahun\" id=\"tahun\">";
		echo "<option value=\"\">-- Pilih Tahun --</option>";
		for($thn = 2011; $thn <= $tahun_sekarang; $thn++ ){
			if($tahun == $thn){
				echo "<option value='$thn' selected>$thn</option>";
			} else {
				echo "<option value='$thn'>$thn</option>";
			}
		}			
		echo "</select>";	
		echo "</td></tr>";
		echo "</table><br><br>";
		print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";
		echo "<center><input type='submit' name=\"mpep\" value=\"Hantar\"></center>";
		echo "</form>";

if (isset($_POST['mpep']))
{	
?>
    <TABLE width="100%" border="1">
	<tr><td>
		<TABLE cellpadding="1" cellspacing="1" border="1"  width="100%" style="border: solid 1px #CCC;">
			<?php
			$kodppd="".$_SESSION['kodsek']."";
			$kodjpn="".$_SESSION["kodnegeri"]."";
			if($lokasi<>""){
				$sqlqry = " and KodLokasiSemasa='$lokasi'";		
			}

			if ($level == "5" or ($level == "6" and $kodjpn=="16" ))
			{ //PPD
			if ($level == "5")
				$sql2="SELECT KODSEK,NAMASEK FROM tsekolah WHERE kodppd='$kodppd' $sqlqry";
			else
				$sql2="SELECT KODSEK,NAMASEK FROM tsekolah WHERE KODNEGERIJPN='$kodjpn' $sqlqry";
			}
			else if ($level == "6" and $kodjpn<>"16")
			{ //JPN
				$sql2="select KODPPD,PPD from tkppd where KodNegeri='$kodjpn'";				
			}
			elseif ($level == "7" or $level == "8" or $level == "9"  or $level == "10" or $level == "11")
			{  //PUSAT
				$sql2="select KodNegeri,Negeri from tknegeri where KodNegeri<>'98'";
			}
			
			$jum_sekolah_siap=0;
			$qid2=oci_parse($conn_sispa,$sql2);
			oci_execute($qid2);
			$numrows = count_row($sql2);
					
			if($numrows > 0) 
			{
				//kira statistik Jumlah sekolah, Jumlah dah siap dan %
				if ($level == "6" and $kodjpn<>"16"){ //JPN kecuali JPN putrajaya
					$labell="PPD";        
				}else if ($level == "7" or $level == "8" or $level == "9" or $level == "10" or $level == "11"){
					$labell="NEGERI";
				}
				
				if ($level == "6" or ($level == "6" and $kodjpn<>"16" )) // JPN and putrajaya
				{ 
					echo "<TABLE  style=\"border: solid 1px #CCC;\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
					echo "<tr bgcolor='#DDDDDD' height='30'>
					<td width=\"25%\"><strong>$labell</strong></td>
					<td align=\"center\" width=\"15%\"><strong>JUMLAH SEKOLAH</strong></td>
					<td align=\"center\" width=\"15%\"><strong>JUMLAH CALON MENDUDUKI</strong></td>
					<td align=\"center\" width=\"15%\"><strong>BIL LULUS/(%)</strong></td>
					<td align=\"center\" width=\"15%\"><strong>BIL GAGAL/(%)</strong></td>
					<td align=\"center\" width=\"15%\"><strong>GPS</strong></td>
					</tr>";  
					//die('masuk');
				}
				else if ($level == "7" or $level == "8" or $level == "9" or $level == "10" or $level == "11"){//pusat /superadmin
					echo "<TABLE  style=\"border: solid 1px #CCC;\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
					echo "<tr><td colspan=\"10\" align=\"center\"></td><strong>PERINGATAN: Nilai GPS akan dikira semula apabila negeri diklik. Sila klik negeri yang berkenaan dan klik butang REFRESH.</strong></tr>";
					echo "<tr bgcolor='#DDDDDD'>
					<td width=\"20%\"><strong><center>$labell</center></strong></td>
					<td align=\"center\" width=\"20%\"><strong><center>JUMLAH SEKOLAH</center></strong></td>
					<td align=\"center\" width=\"15%\"><strong>BILANGAN PELAJAR</strong></td>
					<td align=\"center\" width=\"15%\"><strong>BIL LULUS/(%)</strong></td>
					<td align=\"center\" width=\"15%\"><strong>BIL GAGAL/(%)</strong></td>
					<td align=\"center\" width=\"15%\"><strong>GPS</strong></td>
					</tr>";
				}
$cnt=0;
				
if ($level == "5" or ($level == "6" and $kodjpn=="16" )) // untuk role ppd dan JPN Putrajaya
{
	echo "<TABLE  style=\"border: solid 1px #CCC;\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
	echo "<tr>";
	echo "	<td  width=\"5%\" bgcolor=\"#ff9900\"><strong>BIL</strong></td>
			<td width=\"10%\" bgcolor=\"#ff9900\"><strong>KOD SEKOLAH</strong></td>
			<td width=\"50%\" bgcolor=\"#ff9900\"><strong>NAMA SEKOLAH</strong></td>
			<td width=\"10%\" bgcolor=\"#ff9900\"><strong><center>BILANGAN PELAJAR</center></strong></td>
			<td width=\"10%\" bgcolor=\"#ff9900\"><strong><center>BIL LULUS/(%)</center></strong></td>
			<td width=\"10%\" bgcolor=\"#ff9900\"><strong><center>BIL GAGAL/(%)</center></strong></td>
			<td width=\"15%\" bgcolor=\"#ff9900\"><strong><center>GPS</center></strong></td>"; 
	echo "</tr>\n";		

	$c=" where ";
	$sql="SELECT KODSEK,NAMASEK,STATUS,KODJENISSEKOLAH FROM tsekolah  ";
	if ($level=="6"){//jpn
		$sql.="$c KodNegeriJPN='$kodjpn'";
		$c=" and ";
		if($lokasi<>""){
			$sql.=" and KodLokasiSemasa='$lokasi'";		
		}
	}
	if ($level=="5"){//ppd
		$sql.="$c kodppd='$kodppd'";
		$c=" and ";
		if($lokasi<>""){
			$sql.=" and KodLokasiSemasa='$lokasi'";		
		}
	}
	if($status=='SR')
		$sql.=" and status='SR' or (kodppd='$kodppd' and status='SM' and Kodjenissekolah='207' $sqlqry) ";
	else
		$sql.=" and status='SM'";
	$sql.=" order by KodSek";
	//if($kodppd=="B010")
		//echo $sql."<br>";
		
	$res=oci_parse($conn_sispa,$sql);
	oci_execute($res);

	$count=0;
	$jumlah_sekolah=0;
	$jumpel=0;
	while ($data=oci_fetch_array($res)) 
	{
	$kodsek1 = $data["KODSEK"];
	$namasekolah = $data["NAMASEK"];
	$statussek = $data["STATUS"];
	$kodjenissekolah = $data["KODJENISSEKOLAH"];

	$jumlah_sekolah++;
	$count++;
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
		$res1=oci_parse($conn_sispa,$jumpel);	 
		oci_execute($res1);
		$data1=oci_fetch_array($res1);
		$cnt_murid=(int) $data1["BILMURID"];
		
		$sqltnilai = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(F) AS F,SUM(AMBIL) AS AMBIL FROM analisis_mpsr WHERE KODSEK='$kodsek1' AND TAHUN='$tahun' AND JPEP='$jpep' AND DARJAH='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) GROUP BY KODSEK";
		//INDEX KODSEK SAHAJA
		//echo $sqltnilai;
		$restnilai = oci_parse($conn_sispa,$sqltnilai);
		oci_execute($restnilai);
		$datatnilai = oci_fetch_array($restnilai);
		$bilA = (int) $datatnilai["A"];
		$bilB = (int) $datatnilai["B"];
		$bilC = (int) $datatnilai["C"];
		$bilD = (int) $datatnilai["D"];
		$bilE = (int) $datatnilai["E"];
		$bilF = (int) $datatnilai["F"];
		$bilamb = (int) $datatnilai["AMBIL"];
		
		$jumA+=$bilA;
		$jumB+=$bilB;
		$jumC+=$bilC;
		$jumD+=$bilD;
		$jumE+=$bilE;
		$jumF+=$bilF;
		$jumAmbil+=$bilamb;	
		$jumpelajar+=$cnt_murid;
		if($tahun<=2014){
			//semua tahap pakai satu jek
			$gps = gpmpmrsr($bilA,$bilB,$bilC,$bilD,$bilE,$bilamb);
		}elseif($tahun==2015){
			if($ting=="D6"){
				$gps = gpmpmrsr($bilA,$bilB,$bilC,$bilD,$bilE,$bilamb);
			}else{//D1,D2,D3,D4,D5
				$gps = gpmpmrsr_baru($bilA,$bilB,$bilC,$bilD,$bilE,$bilF,$bilamb);
			}
		}elseif($tahun>=2016){
			$gps = gpmpmrsr_baru($bilA,$bilB,$bilC,$bilD,$bilE,$bilF,$bilamb);
		}
		
		$sqltnilai2 = "select KEPUTUSAN,BILMP FROM tnilai_sr WHERE KODSEK='$kodsek1' AND TAHUN='$tahun' AND JPEP='$jpep' AND DARJAH='$ting'";
		//INDEX KODSEK SAHAJA
		$restnilai2 = oci_parse($conn_sispa,$sqltnilai2);
		oci_execute($restnilai2);
		$billulus=0;$bilgagal=0;
		while($datatnilai2 = oci_fetch_array($restnilai2)){
			$keputusan = $datatnilai2["KEPUTUSAN"];
			//echo $keputusan."<br>";
			$bilambil = (int) $datatnilai2["BILMP"];
			$jumambil+=$bilambil;
			if($tahun<=2015){
				if($keputusan=="LULUS"){
					$billulus++;
				}else{
					$bilgagal++;
				}
			}else{
				if($keputusan=="BELUM MENCAPAI TAHAP MINIMUM"){
					$bilgagal++;
				}else{
					$billulus++;
				}	
			}
		}
	} //if ($status=="SR"){
	if ($status=="MR"){
		if($ting=='T1')
			$kodting = "(KodSekT1='$kodsek1' and TahunT1='$tahun')";
		elseif($ting=='T2')
			$kodting = "(KodSekT2='$kodsek1' and TahunT2='$tahun')";
		elseif($ting=='T3')
			$kodting = "(KodSekT3='$kodsek1' and TahunT3='$tahun')";
		else
			$kodting = "(KodSekT1='$kodsek1' and TahunT1='$tahun') or (KodSekT2='$kodsek1' and TahunT2='$tahun') or (KodSekT3='$kodsek1' and TahunT3='$tahun')";
		$jumpel1="select count(nokp) as bilmurid from tmurid where $kodting";
		$res2=oci_parse($conn_sispa,$jumpel1);	 
		oci_execute($res2);
		$data2=oci_fetch_array($res2);
		$cnt_murid=(int) $data2["BILMURID"];
		
		$sqltnilai = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(F) AS F, SUM(AMBIL) AS AMBIL FROM analisis_mpmr WHERE KODSEK='$kodsek1' AND TAHUN='$tahun' AND JPEP='$jpep' AND TING='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_MR_XAMBIL) GROUP BY KODSEK";
		//INDEX KODSEK SAHAJA
		$restnilai = oci_parse($conn_sispa,$sqltnilai);
		oci_execute($restnilai);
		$datatnilai = oci_fetch_array($restnilai);
		$bilA = (int) $datatnilai["A"];
		$bilB = (int) $datatnilai["B"];
		$bilC = (int) $datatnilai["C"];
		$bilD = (int) $datatnilai["D"];
		$bilE = (int) $datatnilai["E"];
		$bilF = (int) $datatnilai["F"];
		$bilamb = (int) $datatnilai["AMBIL"];
		//echo $bilamb."<br>";
		
		$jumA+=$bilA;
		$jumB+=$bilB;
		$jumC+=$bilC;
		$jumD+=$bilD;
		$jumE+=$bilE;
		$jumF+=$bilF;
		$jumAmbil+=$bilamb;	
		$jumpelajar+=$cnt_murid;
		if($tahun<=2014){
			$gps = gpmpmrsr($bilA,$bilB,$bilC,$bilD,$bilE,$bilamb);
		}else{//2015 ke atas
			$gps = gpmpmrsr_baru($bilA,$bilB,$bilC,$bilD,$bilE,$bilF,$bilamb);
		}
		
		//$sqltnilai3 = "select KEPUTUSAN,BILMP FROM tnilai_smr WHERE TAHUN='$tahun' AND JPEP='$jpep' AND KODSEK='$kodsek1' AND TING='$ting'";
		$sqltnilai3 = "select KEPUTUSAN,BILMP FROM tnilai_smr WHERE KODSEK='$kodsek1' AND TAHUN='$tahun' AND JPEP='$jpep' AND TING='$ting'";
		//INDEX KODSEK SAHAJA
		//echo "select KEPUTUSAN,BILMP FROM tnilai_smr WHERE TAHUN='$tahun' AND JPEP='$jpep' AND KODSEK='$kodsek1' AND TING='$ting'";
		$restnilai3 = oci_parse($conn_sispa,$sqltnilai3);
		oci_execute($restnilai3);
		$billulus=0;$bilgagal=0;
		while($datatnilai3 = oci_fetch_array($restnilai3)){
			$keputusan = $datatnilai3["KEPUTUSAN"];
			$bilambil = (int) $datatnilai3["BILMP"];
			$jumambil+=$bilambil;
			if($keputusan=="LULUS"){
				$billulus++;
			}else{
				$bilgagal++;
			}
		}
		
	}//if ($status=="MR"){
	if ($status=="MA"){
		if($ting=='T4')
			$kodting = "(KodSekT4='$kodsek1' and TahunT4='$tahun')";
		elseif($ting=='T5')
			$kodting = "(KodSekT5='$kodsek1' and TahunT5='$tahun')";
		else
			$kodting = "(KodSekT4='$kodsek1' and TahunT4='$tahun') or (KodSekT5='$kodsek1' and TahunT5='$tahun')";
		$jumpel1="select count(nokp) as bilmurid from tmurid where $kodting";
		//echo $jumpel1;
		$res2=oci_parse($conn_sispa,$jumpel1);	 
		oci_execute($res2);
		$data2=oci_fetch_array($res2);
		$cnt_murid= (int) $data2["BILMURID"];
		
		$sqltnilai = "select sum(AP) as AP, SUM(A) AS A, SUM(AM) AS AM, SUM(BP) AS BP, SUM(B) as B, SUM(CP) AS CP,SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(G) AS G, SUM(AMBIL) AS AMBIL FROM analisis_mpma WHERE KODSEK='$kodsek1' AND TAHUN='$tahun' AND JPEP='$jpep' AND  TING='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL) GROUP BY KODSEK";
		//INDEX KODSEK SAHAJA
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
		
		//$sqlamb = "SELECT SUM(BILAMMP) AS BILAMB FROM SUB_GURU WHERE KODSEK='$kodsek1' AND TAHUN='$tahun' and TING='$ting' and KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL)";
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
		$gps = gpmpma($bilAP, $bilA, $bilAM, $bilBP, $bilB, $bilCP, $bilC, $bilD, $bilE, $bilG, $bilamb);
		
		$sqltnilai4 = "select KEPUTUSAN,BILMP FROM tnilai_sma WHERE KODSEK='$kodsek1' AND TAHUN='$tahun' AND JPEP='$jpep' AND TING='$ting'";
		//INDEX KODSEK SAHAJA
		$restnilai4 = oci_parse($conn_sispa,$sqltnilai4);
		oci_execute($restnilai4);
		$billulus=0;$bilgagal=0;
		while($datatnilai4 = oci_fetch_array($restnilai4)){
			$keputusan = $datatnilai4["KEPUTUSAN"];
			$bilambil = (int) $datatnilai4["BILMP"];
			$jumambil+=$bilambil;
			if($keputusan=="LULUS"){
				$billulus++;
			}else{
				$bilgagal++;
			}
		}
		
	}//if ($status=="MA"){
	
	echo "<tr bgcolor='#E8EFFC' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '#E8EFFC'\"> \n";
	echo "<td>$count</td>";
	echo "<td ><strong><a href='pencapaian_kelas.php?ting=$ting&kodsek=$kodsek1&jenis=$jpep' target='_blank'>$kodsek1</a></strong></td>"; 
	echo "<td >$namasekolah </td>"; 
	echo "<td align='center'>$cnt_murid</td>";
	echo "<td align='center'>$billulus (".peratus($billulus,$cnt_murid)." %)</td>";
	echo "<td align='center'>$bilgagal (".peratus($bilgagal,$cnt_murid)." %)</td>";
	if ($gps==0) {
		if ($kodjenissekolah=="207")
				$statussek="SR";
		echo "<td align='center'><strong><a href='data-semakmp-ting.php?ting=$ting&kodsek=$kodsek1&jenis=$jpep&tahun=$tahun&status=$statussek' target='_blank'>$gps</a></strong></td>"; 		
	} else{
		echo "<td align='center'>$gps</td>";
	}
//echo "<td align='center'>$gps</td>";	
	$gps=0;
}//while data

/* disabled 27/2/2015...sbb xder yg pakai pon
if($status=='SR' or $status=='MR')
	$gpssemua = gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil);
else
	$gpssemua = gpmpma($jumAP,$jumA,$jumAM,$jumBP,$jumB,$jumCP,$jumC,$jumD,$jumE,$jumG,$jumAmbil);
*/
	
$tahap = tahap($ting);
if($status=='SR' or $status=='MR'){
	if($tahun<=2014){
		$gps = gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil);
	}else{//2015 ke atas
		$gps = gpmpmrsr_baru($jumA,$jumB,$jumC,$jumD,$jumE,$jumF,$jumAmbil);
	}
	//echo "<tr bgcolor='#CCCCCC'> \n";
	//echo "<td colspan='3'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\" colspan='2'><strong><center>$jumlah_sekolah</center></strong></td>
	//<td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumpelajar)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	//echo "<tr bgcolor='#CCCCCC'> \n";
	//echo "<td colspan='3'><strong>JUMLAH CALON</strong></td><td align=\"right\" colspan='2'><strong><center>$jumAmbil</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (A*1)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumA)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumA*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (B*2)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumB)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumB*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (C*3)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumC)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumC*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (D*4)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumD)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumD*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (E*5)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumE)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumE*5))."</center></strong></td></tr>";
	echo "<td colspan='3'><strong>JUMLAH (F*6)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumF)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumF*6))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>PENGIRAAN </strong></td><td align=\"right\" colspan='1'><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".(number_format($jumA*1+$jumB*2+$jumC*3+$jumD*4+$jumE*5+$jumF*6))."/".number_format($jumAmbil)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>GPS KESELURUHAN PPD</strong></td><td align=\"right\" colspan='1'><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>$gps</center></strong></td></tr>";
}//if($status=='SR' or $status=='MR'){
	
if($status=='MA'){
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\" colspan='1'><strong><center>$jumlah_sekolah</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumpelajar)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (AP*0)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumAP)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumAP*0))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (A*1)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumA)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumA*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (AM*2)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumAM)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumAM*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (BP*3)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumBP)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumBP*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (B*4)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumB)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumB*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (CP*5)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumCP)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumCP*5))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (C*6)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumC)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumC*6))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (D*7)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumD)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumD*7))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (E*8)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumE)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumE*8))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>JUMLAH (G*9)</strong></td><td align=\"right\" colspan='1'><strong><center>".number_format($jumG)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumG*9))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>PENGIRAAN </strong></td><td align=\"right\" colspan='1'><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".(number_format($jumAP*0+$jumA*1+$jumAM*2+$jumBP*3+$jumB*4+$jumCP*5+$jumC*6+$jumD*7+$jumE*8+$jumG*9))."/".number_format($jumAmbil)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='3'><strong>GPS KESELURUHAN PPD</strong></td><td align=\"right\" colspan='1'><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>".gpmpma($jumAP,$jumA,$jumAM,$jumBP,$jumB,$jumCP,$jumC,$jumD,$jumE,$jumG,$jumAmbil)."</center></strong></td></tr>";		
}//if($status=='MA'){
}//if ($level == "5" or ($level == "6" and $kodjpn=="16" )) untuk role ppd dan JPN Putrajaya
?>					
<!--</table>-->
<?php 

//echo "<TABLE  style=\"border: solid 1px #CCC;\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";

	while ($data2=oci_fetch_array($qid2)) 
	{
		$cnt++;
		//
		//echo $cnt.'<br>count';
		if ($level == "6" and $kodjpn<>"16") // role JPN dan bukan JPN putrajaya
		{ 
			//echo "didalam while role=".$_SESSION["userrole2"]." kodjpn=$kodjpn 111<br> ";
			$kodppd1=$data2["KODPPD"];
			$ppd =$data2["PPD"];
			
			$sqljpn = "select count(*) from tsekolah where kodppd='$kodppd1'";// and status='$jenissekolah'";
			if($status=='SR')
				$sqljpn.=" and status='SR' $sqlqry or (kodppd='$kodppd1' and status='SM' and Kodjenissekolah='207' $sqlqry) ";
			else
				$sqljpn.=" and status='SM' $sqlqry";
				
			$bilsekolah=count_row("$sqljpn");//$data[0];
			//if($kodjpn=='10')
				//echo "$sqljpn<br>";

			$sqlsek="select KODSEK,status from tsekolah where kodppd='$kodppd1' and status='$jenissekolah' $sqlqry";
			//if($kodjpn=='10')
				//echo $sqlsek."<br>";
			//die($sqlsek);
			$res1=oci_parse($conn_sispa,$sqlsek);
			oci_execute($res1);
			$bilA=$bilB=$bilC=$bilD=$bilE=$bilF=$bilamb=0;
			$jumA_ppd=$jumB_ppd=$jumC_ppd=$jumD_ppd=$jumE_ppd=$jumF_ppd=$jumAmbil_ppd=0;
			$jumAP_ppd=$jumAM_ppd=$jumBP_ppd=$jumCP_ppd=$jumpelajar_ppd=0;	 
			$billulus=$bilgagal=0;  				
			while($data=oci_fetch_array($res1))
			{
				$kodsek2 = $data[0];
				$jenisseko = $data[1];
				$cntmurid=0;
				//echo "$jenisseko $kodsek2<br>";
				
				if ($status=="SR"){
					if($ting=="D1")
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
					$respel=oci_parse($conn_sispa,$jumpel);	 
					oci_execute($respel);
					while($datapel=oci_fetch_array($respel)){
						$cnt_pel=(int) $datapel["BILMURID"];
						$cntmurid+=$cnt_pel;
					}

					$sqltnilai = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(F) AS F,SUM(AMBIL) AS AMBIL FROM analisis_mpsr WHERE KODSEK='$kodsek2' AND TAHUN='$tahun' AND JPEP='$jpep' AND DARJAH='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) GROUP BY KODSEK";
					//INDEX KODSEK SAHAJA
					//if($kodjpn=='10')
						//echo $sqltnilai."<br>";
					//die($sqlnilai);
					$restnilai = oci_parse($conn_sispa,$sqltnilai);
					oci_execute($restnilai);
					$datatnilai = oci_fetch_array($restnilai);
					$bilA = (int) $datatnilai["A"];
					$bilB = (int) $datatnilai["B"];
					$bilC = (int) $datatnilai["C"];
					$bilD = (int) $datatnilai["D"];
					$bilE = (int) $datatnilai["E"];
					$bilF = (int) $datatnilai["F"];
					$bilamb = (int) $datatnilai["AMBIL"];
					
					$jumA_ppd+=$bilA;
					$jumB_ppd+=$bilB;
					$jumC_ppd+=$bilC;
					$jumD_ppd+=$bilD;
					$jumE_ppd+=$bilE;
					$jumF_ppd+=$bilF;
					$jumAmbil_ppd+=$bilamb;	
					
					//if($kodjpn=='10')
						//echo "$jumA_ppd,$jumB_ppd,$jumC_ppd,$jumD_ppd,$jumE_ppd,$jumF_ppd,$jumAmbil_ppd <br>";
					
					//$jumpelajar+=$cnt_murid;
					//$gpsall = gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil);
					//$gpsall = gpmpmrsr($bilA,$bilB,$bilC,$bilD,$bilE,$bilamb);
					//echo "$status $ting $kodsek2 $jpep $tahun<br>";
					//echo "bilbiasa $kodsek2 $bilA,$bilB,$bilC,$bilD,$bilE,$bilamb<br>";
					//echo "jumlah+ $kodsek2 $juma $jumb $jumc $jumd $jume $jumambil<br>";
					//echo "<br>".$gps;
					
					$sqltnilai5 ="select KEPUTUSAN,BILMP FROM tnilai_sr WHERE KODPPD='$kodppd1' AND TAHUN='$tahun' AND JPEP='$jpep' AND DARJAH='$ting'";
					//$sqltnilai5 ="select KEPUTUSAN,BILMP FROM tnilai_sr WHERE KODSEK='$kodsek2' AND TAHUN='$tahun' AND JPEP='$jpep' AND DARJAH='$ting'";
					//INDEX KODPPD SAHAJA
					//if($kodjpn=='10')
						//echo $sqltnilai5."<br>";
						//die($sqltnilai5);
					$restnilai5 = oci_parse($conn_sispa,$sqltnilai5);
					oci_execute($restnilai5);
					$billulus=0;$bilgagal=0;
					while($datatnilai5 = oci_fetch_array($restnilai5)){
						$keputusan = $datatnilai5["KEPUTUSAN"];
						//echo $keputusan."<br>";
						$bilambil = (int) $datatnilai5["BILMP"];
						$jumambil+=$bilambil;
						if($tahun<=2015){
							if($keputusan=="LULUS"){
								$billulus++;
							}else{
								$bilgagal++;
							}
						}else{//>=2016
							if($keputusan=="BELUM MENCAPAI TAHAP MINIMUM"){
								$bilgagal++;
							}else{
								$billulus++;
							}
						}
					}
				}//if status SR
				elseif($status=="MR")
				{
					if($ting=="T1")
						$kodting = "(KodSekT1='$kodsek2' and TahunT1='$tahun')";
					elseif($ting=="T2")
						$kodting = "(KodSekT2='$kodsek2' and TahunT2='$tahun')";
					elseif($ting=="T3")
						$kodting = "(KodSekT3='$kodsek2' and TahunT3='$tahun')";
					else
						$kodting = "(KodSekT1='$kodsek2' and TahunT1='$tahun') or (KodSekT2='$kodsek2' and TahunT2='$tahun') or (KodSekT3='$kodsek2' and TahunT3='$tahun')";
							
					$jumpel="select count(nokp) as bilmurid from tmurid where $kodting";
					//echo "$jumpel<br>";
					$respel=oci_parse($conn_sispa,$jumpel);	 
					oci_execute($respel);
					while($datapel=oci_fetch_array($respel)){
						$cnt_pel=(int) $datapel["BILMURID"];
						$cntmurid+=$cnt_pel;
					}
					//echo "$jummurid_JPN<br>";
					$sqltnilai = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(F) AS F,SUM(AMBIL) AS AMBIL FROM analisis_mpmr WHERE KODSEK='$kodsek2' AND TAHUN='$tahun' AND JPEP='$jpep' AND TING='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_MR_XAMBIL) GROUP BY KODSEK";
					//index kodsek shj
					//echo $sqltnilai."<br>";
					//die($sqltnilai);
					$restnilai = oci_parse($conn_sispa,$sqltnilai);
					oci_execute($restnilai);
					$datatnilai = oci_fetch_array($restnilai);
					$bilA = (int) $datatnilai["A"];
					$bilB = (int) $datatnilai["B"];
					$bilC = (int) $datatnilai["C"];
					$bilD = (int) $datatnilai["D"];
					$bilE = (int) $datatnilai["E"];
					$bilF = (int) $datatnilai["F"];
					$bilamb = (int) $datatnilai["AMBIL"];
					//echo bilamb."<br>";
						
					$jumA_ppd+=$bilA;
					$jumB_ppd+=$bilB;
					$jumC_ppd+=$bilC;
					$jumD_ppd+=$bilD;
					$jumE_ppd+=$bilE;
					$jumF_ppd+=$bilF;
					$jumAmbil_ppd+=$bilamb;	
					//$jumpelajar_ppd+=$cntmurid;
					//$gpsppd = gpmpmrsr($jumA_JPN,$jumB_JPN,$jumC_JPN,$jumD_JPN,$jumE_JPN,$jumAmbil_JPN);
					
					$sqltnilai6 = "select KEPUTUSAN,BILMP FROM tnilai_smr WHERE KODPPD='$kodppd1' AND TAHUN='$tahun' AND JPEP='$jpep' AND TING='$ting'";
					//$sqltnilai6 = "select KEPUTUSAN,BILMP FROM tnilai_smr WHERE KODSEK='$kodsek2' AND TAHUN='$tahun' AND JPEP='$jpep' AND TING='$ting'";
					//index kodppd sahaja
					//die($sqltnilai6);
					$restnilai6 = oci_parse($conn_sispa,$sqltnilai6);
					oci_execute($restnilai6);
					$billulus=0;$bilgagal=0;
					while($datatnilai6 = oci_fetch_array($restnilai6)){
						$keputusan = $datatnilai6["KEPUTUSAN"];
						$bilambil = (int) $datatnilai6["BILMP"];
						$jumambil+=$bilambil;
						if($keputusan=="LULUS"){
							$billulus++;
						}else{
							$bilgagal++;
						}
					}
				}//if MR
				elseif($status=="MA")
				{
					if($ting=="T4")
						$kodting = "(KodSekT4='$kodsek2' and TahunT4='$tahun')";
					elseif($ting=="T5")
						$kodting = "(KodSekT5='$kodsek2' and TahunT5='$tahun')";
					else
						$kodting = "(KodSekT4='$kodsek2' and TahunT4='$tahun') or (KodSekT5='$kodsek2' and TahunT5='$tahun')";
						
					$jumpel="select count(nokp) as bilmurid from tmurid where $kodting";
					//echo $jumpel;
					$respel=oci_parse($conn_sispa,$jumpel);	 
					oci_execute($respel);
					while($datapel=oci_fetch_array($respel)){
						$cnt_pel=(int) $datapel["BILMURID"];
						$cntmurid+=$cnt_pel;
					}
						
					$sqltnilai = "select sum(AP) as AP, SUM(A) AS A, SUM(AM) AS AM, SUM(BP) AS BP, SUM(B) as B, SUM(CP) AS CP,SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(G) AS G, SUM(AMBIL) AS AMBIL FROM analisis_mpma WHERE KODSEK='$kodsek2' AND TAHUN='$tahun' AND JPEP='$jpep' AND  TING='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL) GROUP BY KODSEK";
					//INDEX KODSEK SAHAJA
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
					$billulus = $datanilai["JUMLULUS"];
					$bilgagal = $datanilai["JUMGAGAL"];
					$bilcalon = $datanilai["JUMCALON"];

					$jumAP_ppd+=$bilAP;
					$jumA_ppd+=$bilA;
					$jumAM_ppd+=$bilAM;
					$jumBP_ppd+=$bilBP;
					$jumB_ppd+=$bilB;
					$jumCP_ppd+=$bilCP;
					$jumC_ppd+=$bilC;
					$jumD_ppd+=$bilD;
					$jumE_ppd+=$bilE;
					$jumG_ppd+=$bilG;
					$jumAmbil_ppd+=$bilamb;	
					//$jumpelajar_JPN+=$jummurid_JPN; //$cnt_murid;
					//echo "$jumAP, $jumA, $jumAM, $jumBP, $jumB, $jumCP, $jumC, $jumD, $jumE, $jumG, $jumAmbil<br>";
					//$gpsall = gpmpma($jumAP_JPN, $jumA_JPN, $jumAM_JPN, $jumBP_JPN, $jumB_JPN, $jumCP_JPN, $jumC_JPN, $jumD_JPN, $jumE_JPN, $jumG_JPN, $jumAmbil_JPN);
					
					$sqltnilai7 = "select KEPUTUSAN,BILMP FROM tnilai_sma WHERE KODPPD='$kodppd1' AND TAHUN='$tahun' AND JPEP='$jpep' AND TING='$ting'";
					//$sqltnilai7 = "select KEPUTUSAN,BILMP FROM tnilai_sma WHERE KODSEK='$kodsek2' AND TAHUN='$tahun' AND JPEP='$jpep' AND TING='$ting'";
					//INDEX KODPPD SAHAJA  
					$restnilai7 = oci_parse($conn_sispa,$sqltnilai7);
					oci_execute($restnilai7);
					$billulus=0;$bilgagal=0;
					while($datatnilai7 = oci_fetch_array($restnilai7)){
						$keputusan = $datatnilai7["KEPUTUSAN"];
						$bilambil = (int) $datatnilai7["BILMP"];
						$jumambil+=$bilambil;
						if($keputusan=="LULUS"){
							$billulus++;
						}else{
							$bilgagal++;
						}
					}
				}//if MA
				$jumpelajar_ppd+=$cntmurid;
				$cntmurid=0;
			}//while
				   
			$jumlah_sekolah +=$bilsekolah;
			if($status=="SR" or $status=="MR"){
				$jumA_jpn+=$jumA_ppd;
				$jumB_jpn+=$jumB_ppd;
				$jumC_jpn+=$jumC_ppd;
				$jumD_jpn+=$jumD_ppd;
				$jumE_jpn+=$jumE_ppd;
				$jumF_jpn+=$jumF_ppd;
				$jumAmbil_jpn+=$jumAmbil_ppd;	
				if($tahun<=2014){
					$gpsppd = gpmpmrsr($jumA_ppd,$jumB_ppd,$jumC_ppd,$jumD_ppd,$jumE_ppd,$jumAmbil_ppd);
					//echo " 1 - ".$gpsppd."<br>";
				}elseif($tahun<=2015){
					if($ting=='D6'){
						$gpsppd = gpmpmrsr($jumA_ppd,$jumB_ppd,$jumC_ppd,$jumD_ppd,$jumE_ppd,$jumAmbil_ppd);
						//if($kodppd1=='B040')
							//echo "$jumA_ppd,$jumB_ppd,$jumC_ppd,$jumD_ppd,$jumE_ppd,$jumF_ppd,$jumAmbil_ppd <br>";
					}else{//D1,D2,D3,D4,D5,P,T1,T2,T3
						$gpsppd = gpmpmrsr_baru($jumA_ppd,$jumB_ppd,$jumC_ppd,$jumD_ppd,$jumE_ppd,$jumF_ppd,$jumAmbil_ppd);
					}
				}else{//2016 ke atas
					$gpsppd = gpmpmrsr_baru($jumA_ppd,$jumB_ppd,$jumC_ppd,$jumD_ppd,$jumE_ppd,$jumF_ppd,$jumAmbil_ppd);
					//echo "$jumA_ppd,$jumB_ppd,$jumC_ppd,$jumD_ppd,$jumE_ppd,$jumF_ppd,$jumAmbil_ppd <br>";
					//echo " 2 - ".$gpsppd."<br>";
				}
			} else if ($status=="MA" ) {
				$jumAP_jpn+=$jumAP_ppd;
				$jumA_jpn+=$jumA_ppd;
				$jumAM_jpn+=$jumAM_ppd;
				$jumBP_jpn+=$jumBP_ppd;
				$jumB_jpn+=$jumB_ppd;
				$jumCP_jpn+=$jumCP_ppd;
				$jumC_jpn+=$jumC_ppd;
				$jumD_jpn+=$jumD_ppd;
				$jumE_jpn+=$jumE_ppd;
				$jumG_jpn+=$jumG_ppd;
				$jumAmbil_jpn+=$jumAmbil_ppd;	
				$gpsppd = gpmpma($jumAP_ppd, $jumA_ppd, $jumAM_ppd, $jumBP_ppd, $jumB_ppd, $jumCP_ppd, $jumC_ppd, $jumD_ppd, $jumE_ppd, $jumG_ppd, $jumAmbil_ppd);
			}
			//$jumpelajar_JPN+=$jummurid_JPN;
			echo "<tr bgcolor='#D8DFFC' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '#D8DFFC'\"> \n";
			echo "<td><a href=\"javascript:void(0);\" onClick=\"papar_rekod('PPD','$kodppd1','$cnt','','$tahun','$status','$jenisseko','$ting','$jpep');\">$kodppd1 - $ppd  </a></td>";
			echo "<td align=\"center\"><center>$bilsekolah</center></td>";
			echo "<td align=\"center\">$jumpelajar_ppd</td>";
			echo "<td align=\"center\"> $billulus (".peratus($billulus,$jumpelajar_ppd)." %)</td>";
			echo "<td align=\"center\"> $bilgagal (".peratus($bilgagal,$jumpelajar_ppd)." %)</td>";
			echo "<td align=\"center\">$gpsppd</td>";
			echo "</tr>";
			echo "<tr><td colspan=\"4\"><div id=\"div_detail_ppd_".$kodppd1."_".$cnt."\" style=\"display:none\"></div></td></tr>";
			$jumpelajar_jpn+=$jumpelajar_ppd;
			$jumpelajar_ppd=$jumAP_ppd=$jumA_ppd=$jumAM_ppd=$jumBP_ppd=$jumB_ppd=$jumCP_ppd=$jumC_ppd=$jumD_ppd=$jumE_ppd=$jumF_ppd=$jumG_ppd=0;
			//            echo "<tr><td colspan=\"4\"><div id=\"div_detail_negeri_".$kodnegeri."_".$cnt."\" style=\"display:none\"></div></td></tr>";												
		}//if level==6 //$_SESSION["userrole2"]=="3" or ($_SESSION["userrole2"]=="4" and $kodjpn=="16"								
		else if ($level == "7" or $level == "8" or $level == "9" or $level == "10" or $level == "11") //untuk pusat & kppm
		{ 
		$kodnegeri = $data2["KODNEGERI"];
		$negeri = $data2["NEGERI"];
		if ($level == "8") {
			$bilsekolah=count_row("select count(*) from tsekolah where kodnegerijpn='$kodnegeri' and status='$jenissekolah' and kodjenissekolah in ('203','303')");
			$sqlgps = "SELECT * FROM GPS_NEGERI_BPTV WHERE KODNEGERI='$kodnegeri' and tahun='$tahun' and jpep='$jpep' and tahap='$status' and ting='$ting'";		}else if ($level == "11") {
			$bilsekolah=count_row("select count(*) from tsekolah where kodnegerijpn='$kodnegeri' and status='$jenissekolah' and kodjenissekolah in ('107','204','209')");
			$sqlgps = "SELECT * FROM GPS_NEGERI_BPI WHERE KODNEGERI='$kodnegeri' and tahun='$tahun' and jpep='$jpep' and tahap='$status' and ting='$ting'";
		} else {
			$bilsekolah=count_row("select count(*) from tsekolah where kodnegerijpn='$kodnegeri' and status='$jenissekolah'");//$data[0];
			$sqlgps = "SELECT * FROM GPS_NEGERI WHERE KODNEGERI='$kodnegeri' and tahun='$tahun' and jpep='$jpep' and tahap='$status' and ting='$ting'";
		}
		//echo $sqlgps;
		$res4=oci_parse($conn_sispa,$sqlgps);
		oci_execute($res4);
		$jpel=0;$gps=0;
		$billulus=0;$bilgagal=0;$bilcalon=0;
		while($data4=oci_fetch_array($res4))
		
		{
			$jumA = $data4["JUMA"];
			$jumB = $data4["JUMB"];
			$jumC = $data4["JUMC"];
			$jumD = $data4["JUMD"];
			$jumE = $data4["JUME"];
			if($status=='MA'){
				$jumAP = $data4["JUMAP"];
				$jumAM = $data4["JUMAM"];
				$jumBP = $data4["JUMBP"];
				$jumCP = $data4["JUMCP"];
				$jumG = $data4["JUMG"];
				
				$jumlahAP+=$jumAP;
				$jumlahAM+=$jumAM;
				$jumlahBP+=$jumBP;
				$jumlahCP+=$jumCP;
				$jumlahG+=$jumG;
			}
			$jpel = $data4["JUMPELAJAR"];
			$jumkeseluruhan = $data4["JUMKESELURUHAN"];
			$jumamb =$data4["JUMAMBIL"];
			$gps = $data4["GPS"];
			$billulus = $data4["JUMLULUS"];
			$bilgagal = $data4["JUMGAGAL"];
			$bilcalon = $data4["JUMCALON"];
			$jumlahpelajar+=$jpel;
			
			$jumlahA+=$jumA;
			$jumlahB+=$jumB;
			$jumlahC+=$jumC;
			$jumlahD+=$jumD;
			$jumlahE+=$jumE;
			$jumlahkeseluruhan+=$jumkeseluruhan;
			$jumlahAmbil+=$jumamb;
		}
	//papar_rekod('JPN','$kodnegeri','$cnt','$jenis','$year');
	$jumlah_sekolah +=$bilsekolah;	
	//echo $jumlah_sekolah." $jummurid<br>";
						
	echo "<tr bgcolor='#E8EFFC' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '#E8EFFC'\"> \n";
	echo "<td><a href=\"javascript:void(0);\" onClick=\"papar_rekod('JPN','$kodnegeri','$cnt','','$tahun','$status','$jenissekolah','$ting','$jpep');\">$negeri </a></td>
	<td align=\"LEFT\"><center>$bilsekolah</center></td>
	<td align=\"right\">".number_format($jpel)."</td>
	<td align=\"right\">$billulus (".peratus($billulus,$bilcalon)." %)</td>
	<td align=\"right\">$bilgagal (".peratus($bilgagal,$bilcalon)." %)</td>
	<td align=\"right\">$gps</td></tr>";
	echo "<tr><td colspan=\"5\" width=\"800\"><div id=\"div_detail_negeri_".$kodnegeri."_".$cnt."\" style=\"display:none\"></div></td></tr>";
	
	
	} //level 7

} //while untuk JPN dan Pusat

if ($level == "6" and $kodjpn<>"16"){
	
if($status=="SR" or $status=="MR"){
	
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\"><strong><center>$jumlah_sekolah</center></strong></td>
	<td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\"><strong><center>".number_format($jumpelajar_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	//echo "<tr bgcolor='#CCCCCC'> \n";
	//echo "<td colspan='3'><strong>JUMLAH CALON</strong></td><td align=\"right\" colspan='2'><strong><center>$jumAmbil</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (A*1)</strong></td><td align=\"right\"><strong><center>".number_format($jumA_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumA_jpn*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (B*2)</strong></td><td align=\"right\"><strong><center>".number_format($jumB_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumB_jpn*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (C*3)</strong></td><td align=\"right\"><strong><center>".number_format($jumC_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumC_jpn*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (D*4)</strong></td><td align=\"right\"><strong><center>".number_format($jumD_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumD_jpn*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (E*5)</strong></td><td align=\"right\"><strong><center>".number_format($jumE_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumE_jpn*5))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>PENGIRAAN </strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".(number_format($jumA_jpn*1+$jumB_jpn*2+$jumC_jpn*3+$jumD_jpn*4+$jumE_jpn*5))."/".number_format($jumAmbil_jpn)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>GPS KESELURUHAN NEGERI </strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>".gpmpmrsr($jumA_jpn,$jumB_jpn,$jumC_jpn,$jumD_jpn,$jumE_jpn,$jumAmbil_jpn)."</center></strong></td></tr>";
}//if sr mr
if($status=='MA'){
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\" ><strong><center>$jumlah_sekolah</center></strong></td>
	<td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\" ><strong><center>".number_format($jumpelajar_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (AP*0)</strong></td><td align=\"right\" ><strong><center>".number_format($jumAP_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumAP_jpn*0))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (A*1)</strong></td><td align=\"right\" ><strong><center>".number_format($jumA_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumA_jpn*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (AM*2)</strong></td><td align=\"right\" ><strong><center>".number_format($jumAM_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumAM_jpn*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (BP*3)</strong></td><td align=\"right\" ><strong><center>".number_format($jumBP_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumBP_jpn*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (B*4)</strong></td><td align=\"right\" ><strong><center>".number_format($jumB_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumB_jpn*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (CP*5)</strong></td><td align=\"right\"><strong><center>".number_format($jumCP_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumCP_jpn*5))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (C*6)</strong></td><td align=\"right\" ><strong><center>".number_format($jumC_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumC_jpn*6))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (D*7)</strong></td><td align=\"right\"><strong><center>".number_format($jumD_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumD_jpn*7))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (E*8)</strong></td><td align=\"right\" ><strong><center>".number_format($jumE_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumE_jpn*8))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (G*9)</strong></td><td align=\"right\"><strong><center>".number_format($jumG_jpn)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumG_jpn*9))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>PENGIRAAN </strong></td><td align=\"right\" ><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".(number_format($jumAP_jpn*0+$jumA_jpn*1+$jumAM_jpn*2+$jumBP_jpn*3+$jumB_jpn*4+$jumCP_jpn*5+$jumC_jpn*6+$jumD_jpn*7+$jumE_jpn*8+$jumG_jpn*9))."/".number_format($jumAmbil_jpn)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>GPS KESELURUHAN NEGERI </strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>".gpmpma($jumAP_jpn,$jumA_jpn,$jumAM_jpn,$jumBP_jpn,$jumB_jpn,$jumCP_jpn,$jumC_jpn,$jumD_jpn,$jumE_jpn,$jumG_jpn,$jumAmbil_jpn)."</center></strong></td></tr>";	
}//if ma

}
if ($level == "7" or $level == "8" or $level == "9" or $level == "10" or $level == "11"){
if($status=='SR' or $status=='MR'){
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='1'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\"><strong><center>$jumlah_sekolah</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='1'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\"><strong><center>".number_format($jumlahpelajar)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='1'><strong>JUMLAH (A*1)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahA)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahA*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='1'><strong>JUMLAH (B*2)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahB)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahB*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='1'><strong>JUMLAH (C*3)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahC)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahC*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='1'><strong>JUMLAH (D*4)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahD)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahD*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='1'><strong>JUMLAH (E*5)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahE)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahE*5))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='1'><strong>PENGIRAAN </strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format($jumlahkeseluruhan)."/".number_format($jumlahAmbil)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan=''><strong>GPS KESELURUHAN NEGERI </strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>".gpmpmrsr($jumlahA,$jumlahB,$jumlahC,$jumlahD,$jumlahE,$jumlahAmbil)."</center></strong></td></tr>";
}//if sr mr

if($status=='MA'){
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH SEKOLAH</strong></td><td align=\"right\"><strong><center>$jumlah_sekolah</center></strong></td>
	<td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>\n";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH PELAJAR $tahap</strong></td><td align=\"right\"><strong><center>".number_format($jumlahpelajar)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (AP*0)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahAP)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahAP*0))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (A*1)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahA)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahA*1))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (AM*2)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahAM)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahAM*2))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (BP*3)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahBP)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahBP*3))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (B*4)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahB)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahB*4))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (CP*5)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahCP)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahCP*5))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (C*6)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahC)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahC*6))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (D*7)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahD)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahD*7))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (E*8)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahE)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahE*8))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>JUMLAH (G*9)</strong></td><td align=\"right\"><strong><center>".number_format($jumlahG)."</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".number_format(($jumlahG*9))."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>PENGIRAAN </strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF9900'><strong><center>".(number_format($jumlahAP*0+$julahmA*1+$jumlahAM*2+$jumlahBP*3+$jumlahB*4+$jumlahCP*5+$jumlahC*6+$jumlahD*7+$jumlahE*8+$jumlahG*9))."/".number_format($jumlahAmbil)."</center></strong></td></tr>";
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td colspan='2'><strong>GPS KESELURUHAN NEGARA</strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\"><strong><center>&nbsp;</center></strong></td><td align=\"right\" bgcolor='#FF0000'><strong><center>".gpmpma($jumlahAP,$jumlahA,$jumlahAM,$jumlahBP,$jumlahB,$jumlahCP,$jumlahC,$jumlahD,$jumlahE,$jumlahG,$jumlahAmbil)."</center></strong></td></tr>";	
		
}//if ma
}//if level 7

/*if ($kodjpn<>"16")
{
	echo "<tr bgcolor='#CCCCCC'> \n";
	echo "<td><strong>JUMLAH</strong></td>
	<td align=\"right\"><strong><center>$jumlah_sekolah</center></strong></td>
	<td align=\"right\"><strong><center>---</center></strong></td>
	<td align=\"right\"><strong></strong></td>
	<td align=\"right\"><strong></strong></td>
	<td align=\"right\"><strong></strong></td></tr>";
}*/
} //end if($numrows > 0)
else
	echo "<tr><td colspan=\"7\" align=\"center\"><strong>TIADA REKOD.</strong></td></tr>"; 			 
								
			echo "</table>\n";
		echo"</td>\n";
	echo"</tr>\n";
echo"</table>\n";
} 


function tahap($ting)
{
	switch ($ting){
		case "P": $sting="KELAS PERALIHAN";
		break;
		case "T1":
		$sting="TINGKATAN 1";
		break;
		case "T2":
		$sting="TINGKATAN 2";
		break;
		case "T3":
		$sting="TINGKATAN 3";
		break;
		case "T4":
		$sting="TINGKATAN 4";
		break;
		case "T5":
		$sting="TINGKATAN 5";
		break;
		case "D1":
		$sting="TAHUN 1";
		break;
		case "D2":
		$sting="TAHUN 2";
		break;
		case "D3":
		$sting="TAHUN 3";
		break;
		case "D4":
		$sting="TAHUN 4";
		break;
		case "D5":
		$sting="TAHUN 5";
		break;
		case "D6":
		$sting="TAHUN 6";
		break;
	}
return $sting;
}
?> 		

<?php include 'kaki.php';?> 