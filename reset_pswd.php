<?php 
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Lupa Kata Laluan</p>

<?php
echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "<br><br><br>";
echo "<center><h3>KOSONGKAN ID PENGGUNA DAN KATALALUAN GURU</h3></center>";
echo "<br><br>";
echo "  <tr>\n";
echo "    <th scope=\"col\">BIL</th>\n";
echo "    <th scope=\"col\">NO.KP</th>\n";
echo "    <th scope=\"col\">NAMA</th>\n";
echo "    <th scope=\"col\">JANTINA</th>\n";
echo "    <th scope=\"col\">STATUS<br>DAFTAR</th>\n";
echo "    <th scope=\"col\">KOSONGKAN<br>KATALALUAN</th>\n";
echo "  </tr>\n";
//echo "$kodsek";

$query = "SELECT * FROM login WHERE kodsek='$kodsek' ORDER BY nama"; 
$result = OCIParse($conn_sispa,$query);
OCIExecute($result);

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
	$s_L = OCIParse($conn_sispa,$sql_L);
	OCIExecute($s_L);
	$bil_L= count_row($sql_L);
	
	$sql_P = "SELECT * FROM login WHERE jan ='P' AND kodsek='$kodsek'";
	$s_P = OCIParse($conn_sispa,$sql_P);
	OCIExecute($s_P);
	$bil_P= count_row($sql_P);
	
	$sql_jum = "SELECT * FROM login WHERE kodsek='$kodsek'";
	$s_jum = OCIParse($conn_sispa,$sql_jum);
	OCIExecute($s_jum);
	$jumlah= count_row($sql_jum);
	$jump = $bil_L + $bil_P;
	
	echo "  <tr>\n";
	echo "    <td><center>$bil</center></td>\n";
	echo "    <td>$nokp</td>\n";
	echo "    <td>$nama</td>\n";
	echo "    <td><center>$jantina</center></td>\n";
	echo "    <td><center>$daftar</center></td>\n";
	echo "    <td><a href=hapus_idpswd.php?data=".$nokp."|".$kodsek." onclick=\"return (confirm('Adakah anda pasti hapus ID Dan Katalaluan $nama ?'))\"><center><img src=\"images/logout.png\" width=\"20\" height=\"20\" border></center></a></td>\n";
			
}
echo "<tr>";
echo "<td colspan=\"5\"><center>Bilangan Guru Lelaki</center></td>";
echo "<td colspan=\"1\"><center>$bil_L</center></td>";
echo "</tr>\n";
echo "<tr>";
echo "<td colspan=\"5\"><center>Bilangan Guru Perempuan</center></td>";
echo "<td colspan=\"1\"><center>$bil_P</center></td>";
echo "</tr>\n";
echo "<tr>";
echo "<td colspan=\"5\"><center>Jumlah Guru</center></td>";
echo "<td colspan=\"1\"><center>$jumlah</center></td>";
echo "</tr>\n";
echo "</th>\n";
echo "</tr>\n";
echo "</table><br><br></body>\n";
?>
<?php include 'kaki.php';?> 