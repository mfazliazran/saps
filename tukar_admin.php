<?php 
include_once('auth.php');
include_once('config.php');
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Tukar Admin Sekolah</p>

<?php
echo "<br><br>";
echo "<center><h3>TUKAR ADMIN SEKOLAH</h3></center>";
echo "<br><br>";
echo "<form name=\"form1\" method=\"post\" action=\"data_tukar_admin.php\">\n";
echo "  <table width=\"600\" border=\"1\" bordercolor=\"#ccccccc\" align=\"center\" cellspacing=\"\" cellpadding=\"10\">\n";
echo "    <tr>\n";
echo "   <td bgcolor=\"#ff9900\"><center>GURU</center><input name=\"nokpadmin\" type=\"hidden\" value=\"$nokp\" size=\"8\">
		 <input name=\"kodsek\" type=\"hidden\" value=\"$kodsek\" size=\"8\"><input name=\"leveladmin\" type=\"hidden\" value=\"$level\" size=\"8\"></td>\n";
echo "   <td bgcolor=\"#ff9900\"><center>HANTAR</center></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "  <td><center>\n";
echo "<select name=\"nokp\">";
echo "<OPTION VALUE=\"\">Pilih Guru</OPTION>";
$strSQL = "SELECT * FROM login WHERE kodsek='$kodsek' AND nokp!='$nokp' ORDER BY nama";
$rs = OCIParse($conn_sispa,$strSQL);
OCIExecute($rs);
$nr = count_row($strSQL);
for ($k=0; $k<$nr; $k++) {
	OCIFetch($rs); //$r
	echo "<OPTION VALUE=\"".OCIResult($rs,"NOKP")."/".OCIResult($rs,"LEVEL1")."/".OCIResult($rs,"NAMA")."\">".OCIResult($rs,"NOKP")." ".OCIResult($rs,"NAMA")."</OPTION>";
	}
echo "</select>";
echo "</td>";
echo "<td><center>";
echo "<center><input type=\"submit\" name=\"Submit\" value=\"Hantar\"></center>\n";
echo "</td></center>";
echo "</table>";
echo "<br><br>";
echo "</form>\n";
////////////////////////////////papar admin
echo "<br><br>";
echo "<table width=\"600\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <th bgcolor=\"#ff9900\" width=\"10%\"scope=\"col\">ADMIN</th>\n";
echo "  </tr>\n";
$q_admin = OCIParse($conn_sispa,"SELECT * FROM login WHERE kodsek='$kodsek' AND (level1='3' OR level1='4')");
OCIExecute($q_admin);
OCIFetch($q_admin);
echo "  <tr>\n";
echo "  <td><center>".OCIResult($q_admin,"NAMA")." - ".OCIResult($q_admin,"NOKP")."</center></td>";
echo "  </tr>\n";
echo "</table>";
?>
</td>
<?php include 'kaki.php';?> 