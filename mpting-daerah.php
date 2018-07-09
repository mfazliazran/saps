<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Mata Pelajaran Mengikut Tingkatan</p>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='mpting-daerah.php?status=' + val;
		}
		</script>

<?php

if (isset($_POST['pep']))
{	$tahun=$_POST["tahun"];	
	$ting = $_POST['ting'];
	$jpep = $_POST['jpep'];
	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			location("analisis_mptingsrdaerah.php?ting=$ting&tahun=$tahun&jpep=$jpep");
			break;
		case "P": case "T1": case "T2": case "T3":
			location("analisis_mptingmrdaerah.php?ting=$ting&tahun=$tahun&jpep=$jpep");
			break;
		case "T4": case "T5":
			location("analisis_mptingmadaerah.php?ting=$ting&tahun=$tahun&jpep=$jpep");
			break;
	}
	
}
else{
		echo "<br><br><br><br><br>";
		echo " <center><h3>MENU<br>ANALISIS MATA PELAJARAN TINGKATAN/TAHUN</h3></center>";
		echo "<br>";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"500\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "<form method=post name='f1' action='mpting-daerah.php'>";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>TAHUN</td>\n";
		echo "  <td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";

		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>JPEP</td>\n";
		echo "  <td>";
		  $sql="SELECT * FROM kawal_pep WHERE tahun= :tarikh ORDER BY rank";
		  echo "<select name=\"jpep\">";
		  $qic = oci_parse($conn_sispa,$sql);
		  oci_bind_by_name($qic, ':tarikh', date("Y"));
		  oci_execute($qic);
		  $i=0;
		  while(OCIFetch($qic))
			{
			$tahun = OCIResult($qic,"TAHUN");
			$jpep = OCIResult($qic,"JPEP");
			$status = OCIResult($qic,"STATUS");
			$status_pep = OCIResult($qic,"STATUS");
			echo "<option value=\"$jpep\">".jpep($jpep)."</option>";
		  }	
		echo "</select>";
		echo " </td></tr>";

		echo "  <tr bgcolor=\"#CCCCCC\">\n";

		$status = $_GET['status'];
		if ($status=="")
		   $status="SR";
		   
		switch ($status)
		{
			case "MR" : $tahap = "MENENGAH RENDAH";  $tmp = "sub_mr"; break;
			case "MA" : $tahap = "MENENGAH ATAS"; $tmp = "mpsmkc"; break;
			
			case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "sub_sr"; break;
			default : $tahap = "Pilih Tahap"; break;
		}	
		

		echo "<td>TAHAP</td><td><select name=\"status\" onchange=\"reload(this.form)\">";
		?>
		<option <?php if ($status=="MR") echo " selected "; ?> value="MR">MENENGAH RENDAH</option>
		<option <?php if ($status=="MA") echo " selected "; ?> value="MA">MENENGAH ATAS</option>
		<option <?php if ($status=="SR") echo " selected "; ?> value="SR">SEKOLAH RENDAH</option>
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
			case "MR" :	echo "<option value=\"P\">P</option>";
						echo "<option value=\"T1\">T1</option>";
						echo "<option value=\"T2\">T2</option>";
						echo "<option value=\"T3\">T3</option>";
						break;
						
			case "MA" : echo "<option value=\"T4\">T4</option>";
						echo "<option value=\"T5\">T5</option>";
						break;
						
			case "SR" :	echo "<option value=\"D1\">D1</option>";
						echo "<option value=\"D2\">D2</option>";
						echo "<option value=\"D3\">D3</option>";
						echo "<option value=\"D4\">D4</option>";
						echo "<option value=\"D5\">D5</option>";
						echo "<option value=\"D6\">D6</option>";
						break;
		}
		echo "</select>";
		echo "</td></tr>";
        echo "<tr><td colspan=\"2\">";
        print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";	
		 echo "<center><input type='submit' name=\"pep\" value=\"Hantar\"></center>";
		echo "</td></tr>";
		echo "</form>";
}

?></tr></table>
</td>
<?php include 'kaki.php';?> 