<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Proses Keputusan Peperiksaan</p>

<?php

if (isset($_POST['proses']))
{		
//	$tahun = $_POST['tahun'];
	$ting = $_POST['ting'];
	//die($ting);
//	$status = $_POST['statush'];
//	$jpep= $_POST['jpep'];

	switch ($ting)
	{
		case "P": case "T1": case "T2": case "T3": 
//			location("proses_mr.php?tahun=$tahun&&status=$status&&kodppd=$kodsek&&namappd=$namasek&&ting=$ting&&jpep=$jpep");
			location("proses_mr.php?ting=$ting");
			break;
			
		case "T4": case "T5":
//			location("proses_ma.php?tahun=$tahun&&status=$status&&kodppd=$kodsek&&namappd=$namasek&&ting=$ting&&jpep=$jpep");
			location("proses_ma.php?ting=$ting");
			break;
		
		case "D1": case "D2": case "D3": case "D4": case "D5": case "D6":
//			location("proses_sr.php?tahun=$tahun&&status=$status&&kodppd=$kodsek&&namappd=$namasek&&ting=$ting&&jpep=$jpep");
			location("proses_sr.php?ting=$ting");
			break;
	}
////////////////////////////////////////////habis level
}
else{
		echo " <center><h3>PROSES PEPERIKSAAN MURID TINGKATAN/DARJAH</h3></center>";
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
		$rting= oci_parse($conn_sispa,"SELECT DISTINCT ting FROM tkelassek WHERE tahun='".$_SESSION['tahun']."' AND kodsek ='".$_SESSION['kodsek']."' ORDER BY ting");
		oci_execute($rting);
		while($row = oci_fetch_array($rting)) { 
			echo  "<option value='$row[TING]'>$row[TING]</option>";
		}
		echo "</select>";
		echo "</td>";
		echo "<td>";
		echo "<input type='submit' name=\"proses\" value=\"Proses\">";
		echo "</td>";
		echo "</form></td></table>";
		}
		
		echo "<br><br>";
		echo "<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
		echo "  <tr>\n";
		echo "    <td width=\"146\"><div align=\"center\">Tahun / Tingkatan </div></td>\n";
		echo "    <td width=\"154\"><div align=\"center\">Tarikh Proses</div></td>\n";
		echo "    <td width=\"170\"><div align=\"center\">Masa Proses</div></td>\n";
		echo "  </tr>\n";
		
		$qtproses=oci_parse($conn_sispa,"SELECT tproses.*,  TO_CHAR(MASA,'HH12:MI:SS AM') as Hiredate FROM tproses WHERE tahun='".$_SESSION['tahun']."' AND kodsek ='".$_SESSION['kodsek']."' AND jpep='".$_SESSION['jpep']."' ORDER BY ting");
		oci_execute($qtproses);
		while($rowp=oci_fetch_array($qtproses)){
			$masa = $rowp["HIREDATE"];
			//echo $masa."<br>";
		echo "  <tr>\n";
		echo "    <td><div align=\"center\">$rowp[TING]</div></td>\n";
		echo "    <td><div align=\"center\">$rowp[TARIKH]</div></td>\n";
		echo "    <td><div align=\"center\">$masa</div></td>\n";//$rowp[MASA]
		echo "  </tr>\n";
		}
		echo "</table>\n";

include 'kaki.php';?> 