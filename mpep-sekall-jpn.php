<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include 'fungsikira.php';
$statussek1 = $_SESSION["statusseksbt"];
//oecho $statussek1;
?>
 <script type="text/javascript">
 
function pilih_status(status)
{
	location.href="mpep-sekall-jpn.php?status=" + status
}

function pilih_tahun(tahun_semasa,status1)
{
	status1 = document.f1.status.value
	pep = document.f1.pep.value
	location.href="mpep-sekall-jpn.php?status=" + status1 + "&tahunpep=" + tahun_semasa +"&pep=" + pep
}
</script>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Pencapaian Mata Pelajaran</p>
<?php

/*if($tahun_semasa <> "") {
	$tahun = $tahun_semasa;
} else {
	$tahun = date("Y");
}*/

$tahun_sekarang = date("Y");    
if ($statussek1=="SM"){
	echo "<h3><center>ANALISIS PENCAPAIAN MATA PELAJARAN ".$_SESSION["tahun"]."</center></h3><br>";
	//echo "<center><b>PENCAPAIAN MATA PELAJARAN</b></center>";
   	echo "<form method='post' name='f1' action='mp-sekolah-jpn.php' target=\"_blank\">";
	echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
	//echo "  <tr bgcolor=\"#CCCCCC\">\n";
	$tahunpep = $_GET['tahunpep'];
	$status = $_GET['status'];
	$pepr = $_GET["pep"];
	
	if ($status=="")
	  $status="MR";
		  
	switch ($status){
		case "MR" : $tahap = "MENENGAH RENDAH";  $tmp = "sub_mr"; $kodjpep = " where kod!='SPMC' and kod!='UPSRC'"; break;
		case "MA" : $tahap = "MENENGAH ATAS"; $tmp = "mpsmkc"; $kodjpep = " where kod!='PMRC' and kod!='UPSRC'"; break;
		case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "mpsr";$kodjpep = " where kod!='SPMC' and kod!='PMRC'"; break;//TMP ASAL = sub_sr
		default : $tahap = "Pilih Tahap"; break;
	}

	echo "<tr bgcolor=\"#CCCCCC\"><td>TAHAP</td><td>";
?>
	<select name="status" onchange="pilih_status(this.value)">
	<option value="">-- Pilih Tahap --</option>
	<option <?php if ($status=="MR") echo " selected "; ?> value="MR">MENENGAH RENDAH</option>
	<option <?php if ($status=="MA") echo " selected "; ?> value="MA">MENENGAH ATAS</option>
	</select>
<?php
	echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\">";
	echo "</td></tr>";
	
	$SQLpep = oci_parse($conn_sispa,"SELECT DISTINCT kod, jenis,rank FROM jpep $kodjpep ORDER BY rank");
	oci_execute($SQLpep);
	echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td><td>";
	echo "<select name='pep'><option value=''>Pilih Peperiksaan</option>";
	while($rowpep = oci_fetch_array($SQLpep)) {
		if($pepr==$rowpep["KOD"])
			echo  "<option value='".$rowpep["KOD"]."' selected>".$rowpep["JENIS"]."</option>";
		else
			echo  "<option value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	
    echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td>";
	echo "<select name=\"tahun_semasa\" id=\"tahun_semasa\" onchange=\"pilih_tahun(this.value)\">";//,status.value
	echo "<option value=''>-- Pilih Tahun --</option>";
	for($thn = 2011; $thn <= $tahun_sekarang; $thn++ ){
		if($tahunpep == $thn){
			echo "<option value='$thn' selected>$thn</option>";
		} else {
			echo "<option value='$thn'>$thn</option>";
		}
	}			
	echo "</select>";		
	echo "</td></tr>";
		
	echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
	echo "<select name='ting'><option value=''>Ting/Darjah</option>";
	switch ($status)
	{
		case "MR" :	echo "<option value=\"P\">P</option>";
					echo "<option value=\"T1\">T1</option>";
					echo "<option value=\"T2\">T2</option>";
					echo "<option value=\"T3\">T3</option>";
					break;
		case "MA" : echo "<option value=\"T4\">T4</option>";
					echo "<option value=\"T5\">T5</option>";
					break;

		case "SR" :	echo "<option value=\"D2\">D2</option>";
					echo "<option value=\"D3\">D3</option>";
					echo "<option value=\"D4\">D4</option>";
					echo "<option value=\"D5\">D5</option>";
					echo "<option value=\"D6\">D6</option>";
					break;
	}
	echo "</select>";
	echo "</td></tr>";
		
	$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru,mpsmkc WHERE kodsek='".$_SESSION['kodsek2']."' and sub_guru.tahun='$tahunpep' AND kod=kodmp ORDER BY kodmp");
	oci_execute($SQLmp);
	echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
	echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
	while($rowmp = oci_fetch_array($SQLmp)) {
		$kodmata = $rowmp["KODMP"];
		$sqlm = oci_parse($conn_sispa,"SELECT KOD,MP,KODLEMBAGA FROM MPSMKC WHERE KOD='$kodmata'");
		oci_execute($sqlm);
		$datamps=oci_fetch_array($sqlm);
		echo  "<option value='".$datamps["KOD"]."'>".$datamps["MP"]."/".$datamps["KODLEMBAGA"]."</option>";
	}
	echo "</select>";
	echo "</td></tr>";
		//////////        Starting of second drop downlist /////////
	/*if($tmp=='mpsmkc'){
	if($tahun=='2011')
		$qry = " where kod not in (select kod from mpsmkc where kod like '%MA%')";
	else
		$qry = " where kod not in (select kod from sub_mr)";
		
		//$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp $qry ORDER BY mp");
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru,mpsmkc WHERE kod=kodmp and kodsek='".$_SESSION['kodsek2']."' AND sub_guru.tahun='$tahun' ORDER BY kodmp");
		//$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp,KOD,MP,KODLEMBAGA FROM sub_guru,mpsr WHERE kod=kodmp and kodsek='".$_SESSION['kodsek2']."' AND tahun='$tahun' ORDER BY kodmp");
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
		echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) {
			$kodmata = $rowmp["KODMP"];
			$sqlm = oci_parse($conn_sispa,"SELECT KOD,MP,KODLEMBAGA FROM MPSMKC WHERE KOD='$kodmata'");
			oci_execute($sqlm);
			$datamps=oci_fetch_array($sqlm);
			echo  "<option value='".$datamps["KOD"]."'>".$datamps["MP"]."/".$datamps["KODLEMBAGA"]."</option>";
		}
		echo "</select>";
	} else {
		//SR
		//$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru,mpsmkc WHERE kod=kodmp and kodsek='".$_SESSION['kodsek2']."' AND sub_guru.tahun='$tahun' ORDER BY kodmp");
		//$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp,KOD,MP,KODLEMBAGA FROM sub_guru,mpsr WHERE kod=kodmp and kodsek='".$_SESSION['kodsek2']."' AND tahun='$tahun' ORDER BY kodmp");
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
		echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) {
			$kodmata = $rowmp["KODMP"];
			$sqlm = oci_parse($conn_sispa,"SELECT KOD,MP,KODLEMBAGA FROM MPSMKC WHERE KOD='$kodmata'");
			oci_execute($sqlm);
			$datamps=oci_fetch_array($sqlm);
			echo  "<option value='".$datamps["KOD"]."'>".$datamps["MP"]."/".$datamps["KODLEMBAGA"]."</option>";
		}
		echo "</select>";
	}
		echo "</td>";*/

		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</table><br><br>";
		//print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
		print "<input name=\"statussek\" type=\"hidden\" readonly value=\"$statussek1\">";
		echo "<center><input type='submit' name=\"mpep\" value=\"Hantar\"></center>";
		echo "</form>";
}

if ($statussek1=="SR"){
	echo "<h3><center>ANALISIS PENCAPAIAN MATA PELAJARAN ".$_SESSION["tahun"]."</center></h3><br>";
	//echo "<center></b>PENCAPAIAN MATA PELAJARAN</b></center>";
	//echo "<br>";		
	echo "<form method='post' name='f1' action='mp-sekolah-jpn.php' target=\"_blank\">";
	echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
	//echo "  <tr bgcolor=\"#CCCCCC\">\n";
	$tahunpep = $_GET['tahunpep'];
	$status = $_GET['status'];
	$pepr = $_GET["pep"];
	if ($status=="")
	  $status="SR";
		 
	switch ($status){
		case "MR" : $tahap = "MENENGAH RENDAH"; ; $tmp = "sub_mr"; $kodjpep = " where kod!='SPMC' and kod!='UPSRC'"; break;
		case "MA" : $tahap = "MENENGAH ATAS"; $tmp = "mpsmkc"; $kodjpep = " where kod!='PMRC' and kod!='UPSRC'"; break;
		case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "mpsr"; $kodjpep = " where kod!='SPMC' and kod!='PMRC'"; break;
		default : $tahap = "PILIH TAHAP"; break;
	}

	echo "<tr bgcolor=\"#CCCCCC\"><td>TAHAP</td><td>";
?>
	<select name="status" onchange="pilih_status(this.value)">
    <option <?php if ($status=="SR") echo " selected "; ?> value="SR">SEKOLAH RENDAH</option>
	</select>
    <?php
	echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\">";
	echo "</td></tr>";

	$SQLpep = oci_parse($conn_sispa,"SELECT DISTINCT kod, jenis,rank FROM jpep $kodjpep ORDER BY rank");
	oci_execute($SQLpep);
	echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td><td>";
	echo "<select name='pep'><option value=''>Pilih Peperiksaan</option>";
	while($rowpep = oci_fetch_array($SQLpep)) {
		if($pepr==$rowpep["KOD"])
			echo  "<option value='".$rowpep["KOD"]."' selected>".$rowpep["JENIS"]."</option>";
		else
			echo  "<option value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	
	echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td>";
	echo "<select name=\"tahun_semasa\" id=\"tahun_semasa\" onchange=\"pilih_tahun(this.value)\">";
	echo "<option value=''>-- Pilih Tahun --</option>";
	for($thn = 2011; $thn <= $tahun_sekarang; $thn++ ){
		if($tahunpep == $thn){
			echo "<option value='$thn' selected>$thn</option>";
		} else {
			echo "<option value='$thn'>$thn</option>";
		}
	}			
	echo "</select>";		
	echo "</td></tr>";
	
	echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
	echo "<select name='ting'><option value=''>Ting/Darjah</option>";
	switch ($status){
		case "MR" :	echo "<option value=\"P\">P</option>";
					echo "<option value=\"T1\">T1</option>";
					echo "<option value=\"T2\">T2</option>";
					echo "<option value=\"T3\">T3</option>";
					break;
		case "MA" : echo "<option value=\"T4\">T4</option>";
					echo "<option value=\"T5\">T5</option>";
					break;
		case "SR" :	echo "<option value=\"D2\">D2</option>";
					echo "<option value=\"D3\">D3</option>";
					echo "<option value=\"D4\">D4</option>";
					echo "<option value=\"D5\">D5</option>";
					echo "<option value=\"D6\">D6</option>";
					break;
	}
	echo "</select>";
	echo "</td></tr>";
		
	$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru,mpsr WHERE kodsek='".$_SESSION['kodsek2']."' and sub_guru.tahun='$tahunpep' AND kod=kodmp ORDER BY kodmp");
	oci_execute($SQLmp);
	echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
	echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
	while($rowmp = oci_fetch_array($SQLmp)) {
		$kodmata = $rowmp["KODMP"];
		$sqlm = oci_parse($conn_sispa,"SELECT KOD,MP,KODLEMBAGA FROM MPSR WHERE KOD='$kodmata'");
		oci_execute($sqlm);
		$datamps=oci_fetch_array($sqlm);
		echo  "<option value='".$datamps["KOD"]."'>".$datamps["MP"]." [".$datamps["KODLEMBAGA"]."]</option>";
	}
	echo "</select>";
	echo "</td></tr>";
		//////////        Starting of second drop downlist /////////

	/*if($tmp=='mpsmkc'){
	if($tahun=='2011')
		$qry = " where kod not in (select kod from mpsmkc where baru <> 'MA')";
	else
		$qry = " where kod not in (select kod from sub_mr)";
		
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp $qry ORDER BY mp");
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
		echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) {
			echo  "<option value='".$rowmp["KOD"]."'>".$rowmp["KODLEMBAGA"]."</option>";
		}
		echo "</select>";
	} else {
		//sr
		//$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru,mpsr WHERE kod=kodmp and kodsek='".$_SESSION['kodsek2']."' AND sub_guru.tahun='$tahun' ORDER BY kodmp");
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
		echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) {
			$kodmata = $rowmp["KODMP"];
			$sqlm = oci_parse($conn_sispa,"SELECT KOD,MP,KODLEMBAGA FROM MPSR WHERE KOD='$kodmata'");
			oci_execute($sqlm);
			$datamps=oci_fetch_array($sqlm);
			echo  "<option value='".$datamps["KOD"]."'>".$datamps["MP"]." [".$datamps["KODLEMBAGA"]."]</option>";
		}
		echo "</select>";
	}
		echo "</td>";*/
		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</table><br><br>";
		print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
		print "<input name=\"statussek\" type=\"hidden\" readonly value=\"$statussek1\">";
		echo "<center><input type='submit' name=\"mpep\" value=\"Hantar\"></center>";
		echo "</form>";
}
?>
</td>
<?php include 'kaki.php';?>