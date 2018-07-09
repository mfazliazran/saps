<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<script type="text/javascript" src="ajax/ajax.js"></script>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Markah Peperiksaan <font color="#FFFFFF">(7/8/2011 - tidak filter Bangsa untuk MP BT)</font></p>
<script language="JavaScript">
var bgcolor = "#FFCC66";
var change_color = "#FFFFFF"
function mover(aa) {
 aa.style.backgroundColor = change_color;
}
function mout(aa) {
 aa.style.backgroundColor = bgcolor;
}
</script>

<?php
$m=$_GET['data'];
list ($ting, $kelas, $kod, $tahun, $kodsek, $jpep)=split('[/]', $m);
$tg=strtolower($ting);

if ($_SESSION['statussek']=="SM"){
	$tmarkah="markah_pelajar";
	$tmurid="tmurid";
	$tmp="mpsmkc";
	$tahap="ting";
}

if ($_SESSION['statussek']=="SR"){
	$tmarkah="markah_pelajarsr";
	$tmurid="tmuridsr";
	$tmp="mpsr";
	$tahap="darjah";
}

$gmp="G$kod";

switch ($kod)
{
/*	case "KH1":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND jantina='L' AND $kod!='' ORDER BY nama"  ;
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$tg='$kelas' AND jantina='L' ORDER BY namap";
		break;
	case "KH2":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND jantina='P' AND $kod!='' ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$tg='$kelas' AND jantina='P' ORDER BY namap";
		break;   */
	case "PI":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE kodsek='$kodsek' and tahun='$tahun' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND agama='01' AND $kod is not null ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$tg='$kelas' AND agama='01' ORDER BY namap";
		break;
	case "PM":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE kodsek='$kodsek' AND tahun='$tahun' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND agama='02' AND $kod is not null ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$tg='$kelas' AND agama='02' ORDER BY namap";
		break;
/*	case "BC":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND kaum='02' AND $kod!='' ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$tg='$kelas' AND kaum='02' ORDER BY namap";
		break; 
	case "BT":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND (kaum='0300' or trim(kaum)='03') AND $kod is not null ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$tg='$kelas' AND (kaum='0300' or trim(kaum)='03') ORDER BY namap";
		if ($kodsek=="PEA4004") {
		echo "$querymark<br>";
		echo "$querymurid<br>";
		}
		break; */
	default :
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE kodsek='$kodsek' and tahun='$tahun' AND $tahap='$ting' AND jpep='$jpep' and kelas='$kelas' AND $kod is not null  ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$tg='$kelas' ORDER BY namap";
		break;
}
//echo "query murid: $querymurid<br>";
//echo "query markah: $querymark<br>";
$bilmurid=count_row($querymurid);

$resultmurid = oci_parse($conn_sispa,$querymurid);
oci_execute($resultmurid);

//$rmark_pel = oci_parse($conn_sispa,$querymark);
//oci_execute($rmark_pel);

/*if (count_row($querymark)==0)
{
	echo "<br><br><br><br><br><br><center><h3>Klik Markah Peperiksaan Pada Menu Guru Mata Pelajaran<br>Untuk Masuk Markah!</h3></center>";
	?> <script>alert('MARKAH BELUM DIMASUKKAN')</script> <?php
}
else
{*/
	borang($resultmurid, $ting, $kelas, $kod, $tahun, $kodsek, $jpep, $namapep, $tmp, $tmarkah, $tahap);
/*}*/

echo "</th>\n";
echo "</tr>\n";
echo "</table></body>\n";

//fungsi borang markah
function borang($result, $ting, $kelas, $kod, $tahun, $kodsek, $jpep, $namapep, $tmp, $tmarkah, $tahap)
{
	global $conn_sispa;
    global $bilmurid;

	$order_form = ""; /* Will contain product form data */
	// 'config.php';
	$stmt=oci_parse($conn_sispa,"SELECT mp,kod FROM $tmp WHERE kod='$kod'");
	oci_execute($stmt);
	$resultnamamp=oci_fetch_array($stmt);
	$namamp=$resultnamamp['MP'];
	$kod=$resultnamamp['KOD'];

	//session_start();
	echo "<form name=\"form1\" method=\"post\" action=\"edit_markah.php\">\n";
	print '<br><br>';
	echo "<center><h3>KEMASKINI MARKAH PELAJAR TAHUN $tahun</h3></center><br>";
	echo "<input name=\"kodsek\" type=\"hidden\" id=\"kodsek\" value=\"$kodsek\">\n";
	echo "<input name=\"tahun\" type=\"hidden\" id=\"tahun\" value=\"$tahun\">\n";
	echo "<input name=\"ting\" type=\"hidden\" id=\"ting\" value=\"$ting\">\n";
	echo "<input name=\"kelas\" type=\"hidden\" id=\"kelas\" value=\"$kelas\">\n";
	echo "<input name=\"mp\" type=\"hidden\" id=\"mp\" value=\"$kod\">\n";
	echo "<input name=\"jpep\" type=\"hidden\" id=\"jpep\" value=\"$jpep\">\n";
    echo "</form>";
	echo "<table width=\"70%\" border=\"1\" align=\"center\" bordercolor=\"#cccccc\" cellpadding=\"3\" cellspacing=\"0\">\n";
	echo "<tr height=\"30\" bgcolor=\"#FFCC66\" >\n";
	echo "    <th width=\"33%\" scope=\"col\">KELAS</th>\n";
	echo "    <th width=\"33%\" scope=\"col\">MATAPELAJARAN</th>\n";
	echo "    <th width=\"33%\" scope=\"col\">JENIS PEPERIKSAAN</th>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">$ting $kelas</th>\n";
	echo "    <th scope=\"col\">$namamp</th>\n";
	echo "    <th scope=\"col\">";
	echo " ".jpep("".$_SESSION['jpep']."")."";
	echo " </th>\n";
	echo "  </tr>\n";
	echo " </table>\n";
	print '<br>';
	print '<table width="70%" border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#ffffff">';
	print '<tr><td align="center" colspan="4"><strong><font color="#ff0000">Sila klik pada nama pelajar untuk membuat pengisian markah.</font></strong></td></tr>';
	print '<tr bgcolor="#FFCC66">';
	print '<td width="2%"><div align="left" class="style1"><strong><center>BIL</center></strong></div></td>';
	print '<td width="10%"><div align="left" class="style1"><strong><center>NO KP</center></strong></div></td>';
	print '<td width="35%"><div align="left" class="style1"><strong><center>NAMA</center></strong></div></td>';
	print '<td width="10%"><div align="left" class="style1"><strong><center>MARKAH</center></strong></div></td>';
	//print '<td width="5%"><div align="left" class="style1"><strong><center>KEMASKINI</center></strong></div></td>';

	print '</tr>';

	$i=0; $bil=0;
	while($row = oci_fetch_array($result))
	{
		// Loop through the results from the MySQL query.
		$nokp = stripslashes($row['NOKP']);
		$nama = $row['NAMAP']; $jantina = $row['JANTINA']; $kaum = $row['KAUM']; $agama = $row['AGAMA'];
        $qry="SELECT $kod FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep'";
		//echo "<br>SELECT $kod FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep'<br>";
		$qmark_pel = oci_parse($conn_sispa,$qry);
		oci_execute($qmark_pel);
		if (count_row($qry)!=0){
			$mark_pel = oci_fetch_array($qmark_pel);
			$markah = $mark_pel["$kod"];
		} 
		else { $markah = ''; }
		$bil=$bil+1;
		print "<tr onmouseover=\"mover(this);\"  onmouseout=\"mout(this);\" bgcolor=\"#FFCC66\">";
		print "<td><center><strong>$bil</strong></center></td>";
		print "<td>$nokp</td>";
		print "<td><span id=\"markahp_$i\" style=\"display:none;\">$markah</span><a href=\"javascript:void(0);\" onClick=\"edit_markah('$nokp','$i',document.getElementById('markahp_$i').innerHTML,'$bilmurid');\">$nama</a></td>";
		print "<td><center><div id=\"divLabelMarkah$i\">$markah</div><center><div id=\"divMarkah$i\" style=\"display:none\">&nbsp;</div><center></td>";
		//print "<td><input type=\"button\" name=\"simpan$i\" value=\"Kemaskini\"></td>";
		print "</tr>";
		$i++; $tab=$tab+4;
	}
	print "<td><input name=\"bil\" type=\"hidden\" readonly value=\"$bil\" size=\"10\"></td>";



print "</table>";
print "<br><br>";
print "<center>";
print "</center>";
}
?>
</td>
<?php include 'kaki.php';?>