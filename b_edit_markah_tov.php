<?php
require_once ('auth.php');
include 'menu.php';
include 'config.php';
$m=$_GET['data'];
list ($ting, $kelas, $kod, $tahuntov, $kodsek, $jpeptov, $tingtov)=split('[/]', $m);
$tg=strtolower($ting);

$tahun = $_SESSION['tahun'];

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
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND kelas='$kelas' AND jpep='$jpeptov' AND jantina='L' AND $kod!='' ORDER BY nama"  ;
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$tg' AND kelas$tg='$kelas' AND jantina='L' ORDER BY namap";
		break;
	case "KH2":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND kelas='$kelas' AND jpep='$jpeptov' AND jantina='P' AND $kod!='' ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$tgu' AND kelas$tg='$kelas' AND jantina='P' ORDER BY namap";
		break;  */
	case "PI":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov' AND agama='01' AND $kod!='' ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$tg' AND kelas$tg='$kelas' AND agama='01' ORDER BY namap";
		break;
	case "PM":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov' AND agama='02' AND $kod!='' ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$tg' AND kelas$tg='$kelas' AND agama='02' ORDER BY namap";
		break;
/*	case "BC":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND kelas='$kelas' AND jpep='$jpeptov' AND kaum='02' AND $kod!='' ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$tg' AND kelas$tg='$kelas' AND kaum='02' ORDER BY namap";
		break; */
	case "BT":
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov' AND kaum='03' AND $kod!='' ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$tg' AND kelas$tg='$kelas' AND kaum='03' ORDER BY namap";
		break;
	default :
		$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov' AND $kod!=''  ORDER BY nama";
		$querymurid = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$tg' AND kelas$tg='$kelas' ORDER BY namap";
		break;
}
$resultmurid = oci_parse($conn_sispa,$querymurid);
oci_execute($resultmurid);
$rmark_pel = oci_parse($conn_sispa,$querymark);
oci_execute($rmark_pel);

if (count_row($rmark_pel)==0)
{
	echo "<th width=\"79%\" bgcolor=\"#FFFFFF\" align=\"center\" valign=\"top\" scope=\"col\">";
	?> <script>alert('MARKAH BELUM DIMASUKKAN')</script> <?php
}
else 
{ 
	borang($resultmurid, $ting, $kelas, $kod, $tahuntov, $kodsek, $jpeptov, $namapep, $tmp, $tmarkah, $tahap, $tingtov); 
}

echo "</th>\n";
echo "</tr>\n";
echo "</table></body>\n";

//fungsi borang markah
function borang($result, $ting, $kelas, $kod, $tahuntov, $kodsek, $jpeptov, $namapep, $tmp, $tmarkah, $tahap, $tingtov)
{
	$order_form = ""; /* Will contain product form data */
	include 'config.php';
	$resultnamamp= oci_parse($conn_sispa,"SELECT * FROM $tmp WHERE kod='$kod'");
	oci_execute($resultnamamp);
	$row = oci_fetch_array($resultnamamp);
	$namamp=$row['MP'];
	$kod=$row['KOD'];

	echo "<th width=\"80%\" bgcolor=\"#CCCCFF\" align=\"center\" valign=\"top\" scope=\"col\">";
	//session_start(); 
	echo "<form name=\"form1\" method=\"post\" action=\"edit_markah_tov.php\">\n";
	print '<br><br>';
	echo "<center><font size=\"4\">KEMASKINI MARKAH TOV</center><br>";
	echo "<input name=\"kodsek\" type=\"hidden\" id=\"kodsek\" value=\"$kodsek\">\n";
	echo "<input name=\"tahuntov\" type=\"hidden\" id=\"tahuntov\" value=\"$tahuntov\">\n";
	echo "<input name=\"ting\" type=\"hidden\" id=\"ting\" value=\"$ting\">\n";
	echo "<input name=\"tingtov\" type=\"hidden\" id=\"tingtov\" value=\"$tingtov\">\n";
	echo "<input name=\"kelas\" type=\"hidden\" id=\"kelas\" value=\"$kelas\">\n";
	echo "<input name=\"mp\" type=\"hidden\" id=\"mp\" value=\"$kod\">\n";
	echo "<input name=\"jpeptov\" type=\"hidden\" id=\"jpeptov\" value=\"$jpeptov\">\n";                                                        
	echo "<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
	echo "<tr bgcolor=\"#FFCC66\">\n";
	echo "    <th width=\"33%\" scope=\"col\">KELAS</th>\n";
	echo "    <th width=\"33%\" scope=\"col\">MATAPELAJARAN</th>\n";
	echo "    <th width=\"33%\" scope=\"col\">JENIS PEPERIKSAAN</th>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">$ting $kelas</th>\n";
	echo "    <th scope=\"col\">$namamp</th>\n";
	echo "    <th scope=\"col\">";
	echo " TOV";
	echo " </th>\n";
	echo "  </tr>\n";
	echo " </table>\n";
	print '<br>';
	print '<table width="500" border="1" align="center" cellpadding="5" cellspacing="0">';
	print '<tr bgcolor="#FFCC66">';
	print '<td width="4%"><div align="left" class="style1"><strong><center>BIL</center></strong></div></td>';
	print '<td width="16%"><div align="left" class="style1"><strong><center>NO KP</center></strong></div></td>';
	print '<td width="24%"><div align="left" class="style1"><strong><center>NAMA</center></strong></div></td>';
	print '<td width="10%"><div align="left" class="style1"><strong><center>MARKAH</center></strong></div></td>';
	print '</tr>';

	$i=0; $bil=0;
	while($row = oci_fetch_array($result)) 
	{
		// Loop through the results from the MySQL query.
		$nokp = stripslashes($row['NOKP']);
		$nama = $row['NAMAP']; $jantina = $row['JANTINA']; $kaum = $row['KAUM']; $agama = $row['AGAMA'];
		$qmark_pel = oci_parse($conn_sispa,"SELECT $kod FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov'");
		oci_execute($qmark_pel);
		if (count_row("SELECT $kod FROM $tmarkah WHERE nokp='$nokp' AND tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov'")!=0){
			$mark_pel = oci_fetch_array($qmark_pel);
			$markah = $mark_pel["$kod"];
		} else { $markah = ''; }
		$bil=$bil+1;
		print "<tr bgcolor=\"#FFFFCC\">";
		print "<td><center><strong>$bil</strong></center></td>";
		print "<td><input name=\"nokp[$i]\" type=\"text\" readonly value=\"$nokp\" size=\"15\"></td>";
		print "<td><input name=\"nama[$i]\" type=\"text\" readonly value=\"$nama\" size=\"50\"><input name=\"jantina[$i]\" type=\"hidden\" readonly value=\"$jantina\" size=\"2\"><input name=\"kaum[$i]\" type=\"hidden\" readonly value=\"$kaum\" size=\"1\"><input name=\"agama[$i]\" type=\"hidden\" readonly value=\"$agama\" size=\"1\"></td>";
		print "<td><center><input name=\"markah[$i]\" type=\"text\" value=\"$markah\" tabindex=\"4+$tab\" size=\"2\"><center></td>";
		print "</tr>";
		$i++; $tab=$tab+4;
	}
print "<td><input name=\"bil\" type=\"hidden\" readonly value=\"$bil\" size=\"10\"></td>";



print "</table>";
print "<br><br>";
print "<center>";
print '<input type="submit" name="add" value="KEMASKINI MARKAH">';
echo "</form>";
print "</center>";
}
?>
