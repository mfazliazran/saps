<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
session_start();

/*if(($_SESSION["level"]<>"3") OR ($_SESSION["level"]<>"4")){
echo("<SCRIPT language='javascript'> window.alert('Anda bukan SUP');window.location='b_edit_sub_guru.php'; </SCRIPT>");
exit();
die("Anda bukan SUP");	
}
*/

?>


<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Ralat Pilih Mata Pelajaran <font color="#FFFFFF">(Tarikh Kemaskini Program : 20/7/2012)</font></p>

<?php
	
	
	$qsubguru = "SELECT * FROM sub_guru WHERE kodsek='".$_SESSION['kodsek']."' AND tahun='".$_SESSION['tahun']."' ORDER BY ting";
	$rmpguru = oci_parse($conn_sispa,$qsubguru);
	oci_execute($rmpguru);
	
	$num=count_row($qsubguru);
	$datawujud=0;
	if($num == 0){
			echo "<br><br><br><br><br><br><br><br>";
			echo "<center><h3>KEMASKINI MATA PELAJARAN</h3></center>";
			echo "<br><br>";
			echo "<table width=\"500\" border =\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
			echo "  <tr>\n";
			echo "  <td colspan=\"4\"><center><font color =\"#ff0000\"><h3><br>Mata Pelajaran Belum Didaftarkan<br>Sila Daftar Mata Pelajaran</h3></font></center></td>\n";
			echo "  </tr>\n";
			echo "</table>\n";
			}
		
		else{
		echo "<br><br><br><br>";
		echo "<center><h3>KEMASKINI RALAT PILIH MATA PELAJARAN</h3></center>";
		echo "<br>";
		echo "<table align=\"center\" width=\"500\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"cccccc\">\n";
		echo "    <th scope=\"col\">TING</th>\n";
		echo "    <th scope=\"col\">KELAS</th>\n";
		echo "    <th scope=\"col\">MATA PELAJARAN SALAH</th>\n";
		echo "    <th scope=\"col\">MATA PELAJARAN BETUL</th>\n";
		echo "    <th scope=\"col\">UBAHSUAI</th>\n";
		echo "  </tr>\n";
		
		while ($data=oci_fetch_array($rmpguru))
		{
			$ting = $data ["TING"];
			$kelas = $data["KELAS"];
			$kodmp = $data["KODMP"];	
			
			if ($ting == "T2" or $ting == "T3" or $ting == "P"){				
					
			if ($kodmp == "BMMA" or $kodmp == "BIMA" or $kodmp == "PIMA" or $kodmp == "PMMA" or $kodmp == "SEJMA" or $kodmp == "M3MA" or $kodmp == "SNMA" or $kodmp == "GEOMA" or $kodmp == "PSVMA" or $kodmp == "BCMA" or $kodmp == "BTMA" or $kodmp == "BAMA"){
		
			$datawujud=1;
			echo "<tr>";
			echo "<form name=\"frm1\" method=\"post\" action=\"b_edit_sub_guru-simpan.php\">";
			echo "<td>\n";
			echo "<select name=\"ting\">";
			echo "<OPTION SELECTED VALUE=\"".$ting."\">".$ting."</OPTION>";	
			echo "</select>";
			echo "</td>\n";
			
			echo "<td>";
			echo "<select name=\"kelas\">";
			echo "<OPTION SELECTED VALUE=\"".$kelas."\">".$kelas."</OPTION>";	
			echo "</select></td>\n";
			
			echo "<td>";
			echo "<select name=\"kodmp_salah\">";
			$qmpsmkc = "SELECT * FROM mpsmkc WHERE kod = '$kodmp'";
			$rmpmp = oci_parse($conn_sispa,$qmpsmkc);
			oci_execute($rmpmp);
			while($datak = oci_fetch_array($rmpmp)){
			$nmp = $datak["MP"];
			echo "<OPTION SELECTED VALUE=\"".$kodmp."\">".$nmp."</OPTION>";	
			}			
			echo "</select>";
			echo "</td>";
	
			$pjgkod = substr($kodmp,0,-2);
			echo "<td>";
			echo "<select name=\"kodmp_betul\">";
			$qmpsmkc2 = "SELECT * FROM mpsmkc WHERE kod = '$pjgkod'";
			$rmpmp2 = oci_parse($conn_sispa,$qmpsmkc2);
			oci_execute($rmpmp2);		
			while($dataki = oci_fetch_array($rmpmp2)){
			$nmp1 = $dataki["MP"];
			$kodmp1 = $dataki["KOD"];
			echo "<OPTION SELECTED VALUE=\"".$kodmp1."\">".$nmp1."</OPTION>";	
			}			
			echo "</select></td>\n";
			
			echo "<td>";
			echo "<input type=\"submit\" value=\"Ubahsuai\" name=\"ubahsuai\ onclick=\"return confirm(\"Adakah anda pasti?\");\">";
			echo "</td>";
			echo "</form>";
			}//if kodmp
			}//if ting=t3 t2
			
			else if($ting == "T4" or $ting == "T5"){
			
			if ($kodmp == "BM" or $kodmp == "BI" or $kodmp == "PI" or $kodmp == "PM" or $kodmp == "SEJ" or $kodmp == "M3" or $kodmp == "SN" or $kodmp == "GEO" or $kodmp == "PSV" or $kodmp == "BC" or $kodmp == "BT" or $kodmp == "BA"){

			$datawujud=1;
			echo "<form name=\"frm1\" method=\"post\" action=\"b_edit_sub_guru-simpan.php\">";
			echo "<td>\n";
			echo "<select name=\"ting\">";
			echo "<OPTION SELECTED VALUE=\"".$ting."\">".$ting."</OPTION>";	
			echo "</select>";
			echo "</td>\n";
			
			echo "<td>";
			echo "<select name=\"kelas\">";
			echo "<OPTION SELECTED VALUE=\"".$kelas."\">".$kelas."</OPTION>";	
			echo "</select></td>\n";
			
			echo "<td>";
			echo "<select name=\"kodmp_salah\">";
			$qmpsmkc = "SELECT * FROM mpsmkc WHERE kod = '$kodmp'";
			$rmpmp = oci_parse($conn_sispa,$qmpsmkc);
			oci_execute($rmpmp);
			while($datak = oci_fetch_array($rmpmp)){
			$nmp = $datak["MP"];
			echo "<OPTION SELECTED VALUE=\"".$kodmp."\">".$nmp."</OPTION>";	
			}			
			echo "</select>";
			echo "</td>";
			
			$kodmpma = $kodmp."MA";
			echo "<td>";
			echo "<select name=\"kodmp_betul\">";
			$qmpsmkc2 = "SELECT * FROM mpsmkc WHERE kod = '$kodmpma'";
			$rmpmp2 = oci_parse($conn_sispa,$qmpsmkc2);
			oci_execute($rmpmp2);		
			while($dataki = oci_fetch_array($rmpmp2)){
			$nmp1 = $dataki["MP"];
			$kodmp1 = $dataki["KOD"];
			echo "<OPTION SELECTED VALUE=\"".$kodmp1."\">".$nmp1."</OPTION>";	
			}			
			echo "</select></td>\n";
			
			echo "<td>";
			echo "<input type=\"submit\" value=\"Ubahsuai\" name=\"ubahsuai\ onclick=\"return confirm('Adakah anda pasti?');\">";
			echo "</td>";
			echo "</form>";
			}		
			}
			echo "</tr>\n";
		}
		}
		if ($datawujud==0){
			echo "<tr><td align=\"center\" colspan=\"5\">Tiada Ralat Mata Pelajaran</td></tr>\n";
		}
		
			echo "</table>\n";
			echo "<br><br>";
			echo "<center><b><font color=\"red\">SELEPAS UBAHSUAI MATA PELAJARAN SILA <a href=\"proses_peperiksaan.php\">PROSES MARKAH PEPERIKSAAN </a>SEMULA UNTUK U1 dan PPT</font></b></center>\n";
			
//footer//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
</td>
<?php include 'kaki.php';?> 