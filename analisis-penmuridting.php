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
<p class="subHeader">Analisis Pencapaian Murid Tingkatan/Tahun</p>

<?php

if (isset($_POST['pep']))
{		
	$ting = validate($_POST['ting']);
	
	$q_sek = OCIParse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='".$_SESSION['kodsek']."'");
	OCIExecute($q_sek);
	OCIFetch($q_sek);
	$namasek = OCIResult($q_sek,"NAMASEK");

	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			location("analisis_penmuridtingsr.php?ting=$ting");
			break;
		case "P": case "T1": case "T2": case "T3":
			location("analisis_penmuridtingmr.php?ting=$ting");
			break;
		case "T4": case "T5":
			location("analisis_penmuridtingma.php?ting=$ting");
			break;
	}
}
else{
		//echo "<br><br><br><br>";
		echo " <center><h3>MENU<br>ANALISA PENCAPAIAN MENGIKUT TINGKATAN/DARJAH</h3></center>";
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
		$tingSQL = OCIParse($conn_sispa,"SELECT DISTINCT ting FROM tkelassek WHERE tahun='".$_SESSION['tahun']."' AND kodsek ='".$_SESSION['kodsek']."' ORDER BY ting");
		OCIExecute($tingSQL);
		while(OCIFetch($tingSQL))
		{ 
			echo  "<option value='".OCIResult($tingSQL,"TING")."'>".OCIResult($tingSQL,"TING")."</option>";
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