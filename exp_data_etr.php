<?php
//edited 22/6/2015 Naeim
//edited 1/7/2018 Pie
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
//include 'config.php';

set_time_limit(0);
?>

<SCRIPT type="text/javascript">
function reload(form)
{
	var val=form.status.options[form.status.options.selectedIndex].value;
	self.location='exp_data_etr.php?status=' + val;
}
</script>
<td valign="top" class="rightColumn">
<p class="subHeader">Eksport Data ETR</p>

<?php

$tahun_sekarang = date("Y");
		//echo "$kodsek";
		echo "<br>";
		echo " <center></b>EKSPORT DATA ETR</b></center>";
		echo "<br>";
		echo "<form method=post name='f1' action='data_exp_data_etr.php' target='_blank'>";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		//echo "  <tr bgcolor=\"#CCCCCC\">\n";
		
		//$SQLting = mysql_query("SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting");
		
		$status = $_GET['status'];
		if($status=="")
			$status="SM";
		switch ($status)
		{
			case "SM" : $tahap = "SEKOLAH MENENGAH"; $tmp = "mpsmkc"; $sm=" SELECTED ";  break;
			case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "sub_sr"; $sr=" SELECTED ";  break;
			default : $tahap = "Pilih Tahap"; break;
		}	
		
     	//echo "<td>TAHUN h</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHAP</td>";
		echo "<td><select name=\"status\" onchange=\"reload(this.form)\">";
		echo "<option $sm value=\"SM\">SEKOLAH MENENGAH</option>";
		echo "<option $sr value=\"SR\">SEKOLAH RENDAH</option>";
		echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";


		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
		echo "<select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			case "SM" :	echo "<option value=\"P\">P</option>";
						echo "<option value=\"T1\">T1</option>";
						echo "<option value=\"T2\">T2</option>";
						echo "<option value=\"T3\">T3</option>";
						echo "<option value=\"T4\">T4</option>";
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
		
		echo "<tr bgcolor=\"#CCCCCC\"><td>GRED/MARKAH</td><td>";
		echo "<select name='gredmark'><option value=''>Kedua-duanya</option>";
		echo "<option value=\"1\">Markah</option>";
		echo "<option value=\"2\">Gred</option>";
		echo "</select>";
		echo "</td></tr>";
     	echo "<tr bgcolor=\"#CCCCCC\"><td>TAHUN</td><td>";
		//echo "<input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\">";
		echo "<select name=\"tahun\" id=\"tahun\">";// onchange=\"pilih_tahun(this.value)\"
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
		echo "</td>";
		
		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</tr></table><br><br>";
		echo " <center></b>Proses export data akan mengambil masa di antara 5-10 minit.</b></center>";
		echo "<br>";
		 print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";
		echo "<center><input type='submit' name=\"mpep\" value=\"Hantar\"></center>";
		echo "</form>";	

include 'kaki.php';		

?> 
</td>