<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include_once ('menu.php');
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Tentu Headcount</p>
<?php
$pencapaian=$_GET['data'];
//$tahun="2011";
$tahun = date("Y");
//$tahun = '2018';
$tahunpep = $tahun - 1;

$qtov = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE capai='$pencapaian' AND tahunpep='$tahunpep' ORDER BY tingpep");
//die("SELECT * FROM tentu_hc WHERE capai='$pencapaian' AND tahunpep='$tahun' ORDER BY tingpep");
oci_execute($qtov);
$jum = count_row("SELECT * FROM tentu_hc WHERE capai='$pencapaian' AND tahunpep='$tahunpep' ORDER BY tingpep");


echo "<blockquote>";
echo "<center><h3>Sila Tetapkan Data $pencapaian $tahun</h3></center>\n";
if($jum == 0 ){
	echo "<center>Data $pencapaian Belum Ditetapkan.</center>";	
	echo "<center>Sila Klik Butang <input type=\"button\" name=\"tambah\" id=\"tambah\" value=\"SALIN\" onclick=\"location.href='salin_tentuhc.php?data=$pencapaian';\"/> untuk Menyalin Konfigurasi Tahun Sebelum.</center>";		
}
echo "<form method=\"post\" action=\"simpan_salintentuhc.php\">";
echo "<br><table width=\"600\" align=\"center\" border=\"1\" bordercolor=\"#999999\" cellpadding=\"5\" cellspacing=\"0\" >\n";
echo "  <tr>\n";
echo "    <td>Tingkatan</td>\n";
echo "    <td>Ting $pencapaian</td>\n";
echo "    <td>Tahun $pencapaian</td>\n";
echo "    <td>Jenis Pep</td>\n";
echo "  </tr>\n";
$cnt=0;
while($row=oci_fetch_array($qtov)){
$tingpep = trim($row['TINGPEP']);
$tingtov = $row['TINGTOV'];
$tahuntov = $row['TAHUNTOV'];
$jenpep = $row['JENPEP'];
//echo "$tingpep $tahuntov<br>";
if($pencapaian == "TOV"){
	if($tingpep=="D1"){
		$tahuntov = $tahun;
	}elseif($tingpep=="D2" or $tingpep=="D3" or $tingpep=="D4" or $tingpep=="D5" or $tingpep=="D6"){
		$tahuntov = $tahunpep;
	}elseif($tingpep=="P" or $tingpep=="T1" or $tingpep=="T4"){
		$tahuntov = $tahun;
		//echo "$tingpep $tahuntov<br>";
	}elseif($tingpep=="T2" or $tingpep=="T3" or $tingpep=="T5"){
		$tahuntov = $tahunpep;
	}
}elseif($pencapaian == "ATR1" or $pencapaian == "ATR2" or $pencapaian == "ATR3"){
	$tahuntov = $tahun;
}
$cnt++;
echo "  <tr>\n";
echo "    <td><input type=\"text\" id=\"txt_tingpep$cnt\"  name=\"txt_tingpep$cnt\" value=\"$tingpep\" size=\"5\" readonly/></td>\n";
echo "    <td><input type=\"text\" id=\"txt_tingtov$cnt\"  name=\"txt_tingtov$cnt\" value=\"$tingtov\" size=\"5\" readonly/></td>\n";
echo "    <td><input type=\"text\" id=\"txt_tahuntov$cnt\"  name=\"txt_tahuntov$cnt\" value=\"$tahuntov\" size=\"5\" readonly/></td>\n";
echo "    <td><input type=\"text\" id=\"txt_jenpep$cnt\"  name=\"txt_jenpep$cnt\" value=\"$jenpep\" size=\"5\" readonly/></td>\n";
echo "  </tr>\n";
}
echo "  </table>\n";
echo "<input type=\"hidden\" name=\"hdn_cnt\" id=\"hdn_cnt\" value=\"$cnt\">";
echo "<input type=\"hidden\" name=\"hdn_capai\" id=\"hdn_capai\" value=\"$pencapaian\">";
echo "<input type=\"hidden\" name=\"hdn_tahunpep\" id=\"hdn_tahunpep\" value=\"$tahun\">";
echo "<br><center><input name=\"Simpan\" value=\"Simpan\" id=\"Simpan\" type=\"submit\" onClick=\"return semak();\" /></center>";
echo "</form>\n";
echo "</blockquote>";
////////////////////////////kaki
echo "</td>";
include 'kaki.php';
?>
