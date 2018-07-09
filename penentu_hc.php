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

$qtov = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE capai='$pencapaian' AND tahunpep='$tahun' ORDER BY tingpep");
//die("SELECT * FROM tentu_hc WHERE capai='$pencapaian' AND tahunpep='$tahun' ORDER BY tingpep");
oci_execute($qtov);
$jum = count_row("SELECT * FROM tentu_hc WHERE capai='$pencapaian' AND tahunpep='$tahun' ORDER BY tingpep");


echo "<blockquote>";
echo "<center><h3>Sila Tetapkan Data $pencapaian $tahun</h3></center>\n";
if($jum == 0 ){
	echo "<center>Data $pencapaian Belum Ditetapkan.</center>";	
	echo "<center>Sila Klik Butang <input type=\"button\" name=\"tambah\" id=\"tambah\" value=\"SALIN\" onclick=\"location.href='salin_tentuhc.php?data=$pencapaian';\"/> untuk Menyalin Konfigurasi Tahun Sebelum.</center>";		
}
echo "<form method=\"post\" action=\"penentu_datahc.php?data=$pencapaian/$s/$tahun\">";
//echo "<center><a href=tahun-hc.php?data=".$pencapaian."/".$tahun.">Tambah Jenis Peperiksaan</a></center>";
echo "<br><table width=\"600\" align=\"center\" border=\"1\" bordercolor=\"#999999\" cellpadding=\"5\" cellspacing=\"0\" >\n";
echo "  <tr>\n";
echo "    <td>Tingkatan Semasa</td>\n";
echo "    <td>Tingkatan $pencapaian</td>\n";
echo "    <td>Tahun $pencapaian</td>\n";
echo "    <td>Jenis Peperiksaan</td>\n";
echo "    <td>Tindakan</td>\n";
echo "  </tr>\n";

while($row=oci_fetch_array($qtov)){
$tingpep = $row['TINGPEP'];
$tingtov = $row['TINGTOV'];
$tahuntov = $row['TAHUNTOV'];
$jenpep = $row['JENPEP'];

//echo $jenpep ."/". $tahuntov ."/". $tingtov ."/". $tingpep ."/". $pencapaian ;

echo "  <tr>\n";
echo "    <td>$tingpep</td>\n";
echo "    <td>$tingtov</td>\n";
echo "    <td>$tahuntov</td>\n";
echo "    <td>$jenpep</td>\n";
//echo "	  <td><a href='hapus_tentu_hc.php?data=$jenpep/$tingtov/$tahuntov/$tingpep/$pencapaian' onclick='return(confirm('Adakah anda pasti ?'))<img src='images/drop.png' border='0'></a></td>'";
echo "    <td><a href=\"hapus_tentu_hc.php?data=$jenpep/$tahuntov/$tingtov/$tingpep/$pencapaian\" onclick=\"return (confirm('Adakah anda pasti ?'))\"><img src=images/drop.png border=0></a></td>\n";
echo "  </tr>\n";
}
echo "  </table>\n";
echo "</form>\n";
echo "</blockquote>";
////////////////////////////kaki
echo "</td>";
include 'kaki.php';
?>