<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Guru</p>

<?php
echo "<center><h3>SENARAI GURU KELAS ".$_SESSION['tahun']."</h3></center>";
echo "<br>";
echo "<table width=\"600\" border=\"1\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <th scope=\"col\">BIL</th>\n";
echo "    <th scope=\"col\">NAMA</th>\n";
echo "    <th scope=\"col\">TINGKATAN</th>\n";
echo "    <th scope=\"col\">KELAS</th>\n";
echo "    <th scope=\"col\">HAPUS</th>\n";
echo "  </tr>\n";
//echo "$kodsek";

echo "<form action=\"ctk_semak_gurukelas.php\" method=\"POST\" target=\"_blank\">\n";

$bil=1;
$query = "SELECT * FROM tguru_kelas WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND (level1='2' OR level1='4') ORDER BY ting, kelas"; 
$result = OCIParse($conn_sispa,$query);
OCIExecute($result);
$num = count_row($query);
while (OCIFetch($result))
{
	$nama = OCIResult($result,"NAMA");//$row['nama'];
	$ting = OCIResult($result,"TING");//$row['ting'];
	$kelas =OCIResult($result,"KELAS");//$row['kelas'];
	
	echo "  <tr>\n";
	echo "    <td><center>$bil</center></td>\n";
	echo "    <td>$nama</td>\n";
	echo "    <td><center>$ting</center></td>\n";
	echo "    <td align=center>$kelas</td>\n";
	echo "    <td><a href=hapus_gkelas.php?data=".$_SESSION['tahun']."/".$kodsek."/".$ting."/".$kelas."><center><img src = images/drop.png width=12 height=13 Alt=\"Hapus\" border=0></center></a></td>\n";

	$bil++;
	$i=$i+1;

	print "<input name=\"bil\" type=\"hidden\" readonly value=\"$num\">";
	print "<input name=\"tahun\" type=\"hidden\" readonly value=\"$tahun\">";
	print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
	print "<input name=\"nama[$i]\" type=\"hidden\" readonly value=\"$nama\">";
	print "<input name=\"ting[$i]\" type=\"hidden\" readonly value=\"$ting\">";
	print "<input name=\"kelas[$i]\" type=\"hidden\" readonly value=\"$kelas\">";
	
}
OCIFreeStatement($result);
echo "</th>\n";
echo "</tr>\n";
echo "</table></br>\n";
echo "<center>";
echo "<input type=\"submit\" name=\"submit\" value=\"PAPARAN CETAK\">\n";
echo "</form>";
echo "</center>";
?>
</td>
<?php include 'kaki.php';?> 