<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include "input_validation.php";
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Mata Pelajaran Mengikut Tingkatan</p>

<?php

if (isset($_POST['pep']))
{		
	$ting = validate($_POST['ting']);
	
	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			location("analisis_mptingsr.php?ting=$ting");//&&namasekolah=$namasek");
			break;
		case "P": case "T1": case "T2": case "T3":
			location("analisis_mptingmr.php?ting=$ting");//&&namasekolah=$namasek");
			break;
		case "T4": case "T5":
			location("analisis_mptingma.php?ting=$ting");//&&namasekolah=$namasek");
			break;
	}
	
}
else{
		//echo "<br><br><br><br><br>";
		echo " <center><h3>MENU<br>ANALISIS MATA PELAJARAN TINGKATAN/TAHUN</h3></center>";
		echo " <center><b>SILA PILIH TINGKATAN/DARJAH</b></center>";
		echo "<br><br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>TINGKATAN/DARJAH</td>\n";
		echo "  <td></td>\n";
		echo " </tr>";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		
		echo "<td>";
		echo "<select name='ting'><option value=''>Sila Pilih Tingkatan/Darjah</option>";
		$tingSQL = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM tkelassek WHERE tahun= :tahun AND kodsek = :kodsek ORDER BY ting");
		oci_bind_by_name($tingSQL,":tahun",$_SESSION["tahun"]);
		oci_bind_by_name($tingSQL,":kodsek",$_SESSION["kodsek"]);
		oci_execute($tingSQL);

		while($dt=oci_fetch_array($tingSQL)) { 
			echo  "<option value='".$dt["TING"]."'>".$dt["TING"]."</option>";
		}
		echo "</select>";
		echo "</td>";
		echo "<td>";
		echo "<input type='submit' name=\"pep\" value=\"Hantar\">";
		echo "</td>";
		echo "</form>";
}

?></tr></table>
</td>
<?php include 'kaki.php';?> 