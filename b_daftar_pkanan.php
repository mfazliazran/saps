<?php
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Daftar Penolong Kanan Pentadbiran</p>
<?php
echo "<br><br>";

$q_guru = "SELECT * FROM login WHERE kodsek='$kodsek' AND level1='PK'";
$qry_guru = OCIParse($conn_sispa,$q_guru);
OCIExecute($qry_guru);
OCIFetch($qry_guru); // rowg

echo "<form name=\"form1\" method=\"post\" action=\"daftar_penkanan.php\">\n";
		echo "<input name=\"kodsek\" type=\"hidden\" value=\"$kodsek\" size=\"8\">";
		//echo "<table width=\"600\" border=\"1\" align=\"center\" cellspacing=\"5\" cellpadding=\"0\">\n";//bordercolor=\"#ccccccc\" 
		echo "<table width=\"600\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
		echo "    <tr>\n";
		echo "<td bgcolor=\"#ff9900\" scope=\"col\"><center>GURU</center></td>\n";
		echo "<td bgcolor=\"#ff9900\" scope=\"col\"><center>HANTAR</center></td>\n";
		echo "    </tr>\n";
		echo "    <tr>\n";
		//////////        Starting of second drop downlist /////////
		echo "<td><center>";
		echo "<input name=\"nokppgb\" type=\"hidden\" id=\"nokppgb\" value=\"".OCIResult($qry_guru,"NOKP")."\">";
		echo "<select name=\"nokp\">";
		echo "<OPTION VALUE=\"\">Pilih Guru</OPTION>";
		
		$strSQL = "SELECT * FROM login WHERE kodsek='$kodsek' ORDER BY nama";
		$rs = OCIParse($conn_sispa,$strSQL);
		OCIExecute($rs);
		$nr = count_row($strSQL);
		for ($k=0; $k<$nr; $k++) {
			OCIFetch($rs);
			echo "<OPTION VALUE=\"".OCIResult($rs,"NOKP")."\">".OCIResult($rs,"NAMA")."</OPTION>";
			}
		echo "</select>";
		echo "</td></center>";
		echo "<td><center>";
		echo "<center><input type=\"submit\" name=\"Submit\" value=\"Hantar\"></center>\n";
		echo "</td></center>";
		echo "</table>";
		echo "<br><br>";
		echo "</form>\n";		//////papar subjek
				
		echo "<center><b>NAMA PENOLONG KANAN PENTADBIRAN</b></center><br>";
		echo "<table width=\"600\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
		echo "  <tr>\n";
		//echo "    <th bgcolor=\"#ff9900\" width=\"10%\"scope=\"col\">BIL</th>\n";
		//echo "   <td bgcolor=\"#ff9900\"><center><b>$tahap</b></center></td>\n";
		//echo "    <th bgcolor=\"#ff9900\" width=\"20%\"scope=\"col\">KELAS</th>\n";
		echo "    <th bgcolor=\"#ff9900\" width=\"50%\" scope=\"col\">NAMA GURU</th>\n";
		echo "    <th bgcolor=\"#ff9900\" width=\"10%\"scope=\"col\">HAPUS</th>\n";
		echo "  </tr>\n";
		
		echo "  <tr>\n";
		//echo "  <td><center>$bil</center></td>";
		//echo "  <td><center>$rowk[ting]</center></td>";
		//echo "  <td>$rowk[kelas]</td>\n";
		
		
				
		if ($numg = count_row($q_guru)==0){
			echo "<td><img src=\"images/ko.png\" width=\"20\" height=\"20\"></td>\n";
			echo "<td><img src=\"images/ko.png\" width=\"20\" height=\"20\"></td>\n";
		}
		else{
			echo "<td><center>".OCIResult($qry_guru,"NAMA")."</center></td>\n";
			echo "<td><a href=hapus_pkanan.php?data=".OCIResult($qry_guru,"NOKP")."><center><img src = images/drop.png width=12 height=13 Alt=\"Hapus\" border=0></center></a></td>\n";
		}
		echo "  </tr>\n";
		
echo "</th>\n";
echo "</tr>\n";
echo "</table></body>\n";
echo "<br><br><br><br>";
?>
</td>
<?php include 'kaki.php';?> 