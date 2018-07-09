<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';

$tahun = $_GET['tahun'];
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Mata Pelajaran Mengikut Tingkatan (BPTV)</p>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='analisis_mptingbptv.php?status=' + val;
		}
		</script>

<?php

if (isset($_POST['pep']))
{	$tahun=$_POST["tahun"];	
	$ting = $_POST['ting'];
	$jpep = $_POST['jpep'];
	
	switch ($ting)
	{
		//case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			//location("mptingjpn_sr.php?ting=$ting&tahun=$tahun&jpep=$jpep");
			//break;
		//case "P": case "T1": case "T2": case "T3":
			//location("mptingjpn_mr.php?ting=$ting&tahun=$tahun&jpep=$jpep");
			//break;
		case "T4": case "T5":
			location("mptingbptv.php?ting=$ting&tahun=$tahun&jpep=$jpep");
			break;
	}
	
}
else{
		echo "<br><br><br><br><br>";
		echo " <center><h3>MENU<br>ANALISIS MATA PELAJARAN TINGKATAN/TAHUN </h3></center>";
		echo "<br>";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"500\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "<form method=post name='f1' action='analisis_mptingbptv.php'>";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>JENIS PEPERIKSAAN</td>\n";
		echo "  <td>";
		echo "<select name='jpep'><option value=''>Pilih Peperiksaan</option>";
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
		//echo "</td></tr>";
		echo " </td></tr>";

		echo "  <tr bgcolor=\"#CCCCCC\">\n";

		$status = $_GET['status'];
		if ($status=="")
		   $status="MA";
		   
		switch ($status)
		{
			//case "MR" : $tahap = "MENENGAH RENDAH";  $tmp = "sub_mr"; break;
			case "MA" : $tahap = "MENENGAH ATAS"; $tmp = "mpsmkc"; break;
			//case "SM" : $tahap = "SEKOLAH MENENGAH"; $tmp = "mpsmkc"; break;
			//case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "sub_sr"; break;
			default : $tahap = "Pilih Tahap"; break;
		}	
		

		echo "<td>TAHAP</td><td><select name=\"status\" onchange=\"reload(this.form)\">";
		?>
		<option <?php if ($status=="MA") echo " selected "; ?> value="MA">MENENGAH ATAS</option>
		<?php echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";

		$SQL_ting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM tkelassek ORDER BY ting");
		oci_execute($SQL_ting);
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN</td><td>";
		echo "<select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			//case "MR" :	echo "<option value=\"P\">P</option>";
						//echo "<option value=\"T1\">T1</option>";
						///echo "<option value=\"T2\">T2</option>";
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
		
		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td>";
		echo "<select name='tahun' id='tahun'><option value=''>Pilih Tahun</option>";
		for($i=2011;$i<=2015;$i++){
			echo "<option value='$i'>$i</option>";	
		}
		echo "</select>";
		//echo " <input name=\"tahuns\" type=\"text\" id=\"tahuns\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\">";
		echo "</select>";
		echo "</td></tr>";
        echo "<tr><td colspan=\"2\">";
        print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";	
		 echo "<center><input type='submit' name=\"pep\" value=\"Hantar\"></center>";
		echo "</td></tr>";
		echo "</form>";
}
/////////////
?></tr></table>
</td>
<?php include 'kaki.php';?> 