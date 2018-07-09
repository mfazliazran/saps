<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Guru</p>

<?php
echo "<center><br><h3>Senarai Nama Guru</h3></center>";
echo "<br><br>";
echo "<table width=\"800\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <th scope=\"col\">BIL</th>\n";
echo "    <th scope=\"col\">NO.KP</th>\n";
echo "    <th scope=\"col\">NAMA</th>\n";
echo "    <th scope=\"col\">JANTINA</th>\n";
echo "    <th scope=\"col\">STATUS DAFTAR</th>\n";
echo "  </tr>\n";
//echo "$kodsek";

$query = "SELECT nokp,nama,jan,user1,pswd FROM login WHERE kodsek='$kodsek' ORDER BY nama"; 
$result = OCIParse($conn_sispa,$query);
OCIExecute($result);

echo "<form action=\"ctk_semak_guru.php\" method=\"POST\" target=\"_blank\">\n";
$bil=0;
while (OCIFetch($result))
{
	$nokp = OCIResult($result,"NOKP");//$row['nokp'];
	$nama = OCIResult($result,"NAMA");//$row['nama'];
	$jantina = OCIResult($result,"JAN");//$row['jan'];
	$user = OCIResult($result,"USER1");//$row['user'];
	$pswd = OCIResult($result,"PSWD");//$row['pswd'];
	$bil=$bil+1;
	
	if(($user=='') AND ($pswd=='')){
	$daftar =  "<center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center>";}
	else { $daftar =  "<center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center>";}
	
	$sql_L = "SELECT * FROM login WHERE jan ='L' AND kodsek='$kodsek'"; 
	$a = OCIParse($conn_sispa,$sql_L);
	OCIExecute($a);
	$bil_L= count_row($sql_L);
	$sql_P = "SELECT * FROM login WHERE jan ='P' AND kodsek='$kodsek'";
	$b = OCIParse($conn_sispa,$sql_P);
	OCIExecute($b);
	$bil_P= count_row($sql_P);
	$sql_jum = "SELECT * FROM login WHERE kodsek='$kodsek'";
	$c = OCIParse($conn_sispa,$sql_jum);
	OCIExecute($c);
	$jumlah= count_row($sql_jum);
	$jump = $bil_L + $bil_P;
	
	echo "  <tr>\n";
	echo "    <td><center>$bil</center></td>\n";
	echo "    <td align=center>$nokp</td>\n";
	echo "    <td>$nama</td>\n";
	echo "    <td><center>$jantina</center></td>\n";
	echo "    <td><center>$daftar</center></td>\n";
	
	$i=$i+1;

print "<input name=\"bil\" type=\"hidden\" readonly value=\"$bil\">";
print "<input name=\"kodsek\" type=\"hidden\" readonly value=\"$kodsek\">";
print "<input name=\"nokp[$i]\" type=\"hidden\" readonly value=\"$nokp\">";
print "<input name=\"nama[$i]\" type=\"hidden\" readonly value=\"$nama\">";
print "<input name=\"jantina[$i]\" type=\"hidden\" readonly value=\"$jantina\">";
print "<input name=\"user[$i]\" type=\"hidden\" readonly value=\"$user\">";
print "<input name=\"pswd[$i]\" type=\"hidden\" readonly value=\"$pswd\">";		
}
echo "<tr>";
echo "<td colspan=\"4\"><center>Bilangan Guru Lelaki</center></td>";
echo "<td colspan=\"1\"><center>$bil_L</center></td>";
echo "</tr>\n";
echo "<tr>";
echo "<td colspan=\"4\"><center>Bilangan Guru Perempuan</center></td>";
echo "<td colspan=\"1\"><center>$bil_P</center></td>";
echo "</tr>\n";
echo "<tr>";
echo "<td colspan=\"4\"><center>Jumlah Guru</center></td>";
echo "<td colspan=\"1\"><center>$jumlah</center></td>";
echo "</tr>\n";
echo "</th>\n";
echo "</tr>\n";
echo "</table><br><br>\n";
echo "<br>";
print "<input name=\"bil_L\" type=\"hidden\" readonly value=\"$bil_L\">";
print "<input name=\"bil_P\" type=\"hidden\" readonly value=\"$bil_P\">";
print "<input name=\"jumlah\" type=\"hidden\" readonly value=\"$jumlah\">";
echo "<center>";
echo "<input type=\"submit\" name=\"submit\" value=\"PAPARAN CETAK\">\n";
echo "</form>";
echo "</center>";	
?>
</td>
<?php include 'kaki.php';?> 