<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=1000height=800,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-500,screen.height/2-400);
}
</script>

<td valign="top" class="rightColumn">
<p class="subHeader">Slip Keputusan Murid</p>

<?php

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$tingkatan = $_GET['tingkatan'];
$namagu = $_GET['namaguru'];
$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek2'];
$jpep = $_SESSION['jpep'];
$m="".urlencode($nokp)."|$kodsek|$ting|".urlencode($kelas)."|$tahun|$jpep|$lencana";

echo "<br><br><br>";
echo "<center><h3>SENARAI NAMA MURID $tingkatan<br>$ting $kelas</h3></center>";
echo "<table width=\"800\"  border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "<tr>\n";
//echo "<th width=\"150\" scope=\"col\">&nbsp;</th>";
echo "<th width=\"100%\" scope=\"col\"><center><a href=slip-allsr-jpn.php?data=".$kodsek."|".$ting."|".urlencode($kelas)."|".$tahun."|".$jpep."|".$lencana." target=_blank><img src = images/printer.png width=25 height=25 border=\"0\"><br>Cetak Semua</center></a></th>\n";
echo "  <tr>\n";
echo "</table>";
echo "<table width=\"800\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <th scope=\"col\">BIL</th>\n";
echo "    <th scope=\"col\">NOKP</th>\n";
echo "    <th scope=\"col\">NAMA</th>\n";
echo "    <th scope=\"col\">JANTINA</th>\n";
echo "    <th scope=\"col\">CETAK SLIP</th>\n";
echo "    <th scope=\"col\">EXPORT KE EXCEL</th>\n";
//echo "	  <th scope=\"col\">ULASAN</th>\n";
echo "	  <th scope=\"col\">KEHADIRAN</th>\n";
echo "  </tr>\n";

$bil=0;
$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr WHERE kodsek='$kodsek' AND kelas='$kelas' AND tahun='$tahun' AND jpep='$jpep' AND darjah='$ting' ORDER BY nama");
oci_execute($q_slip);
while ($row = oci_fetch_array($q_slip))
{
	$nokp = $row["NOKP"];
	$nama = $row["NAMA"];
	$jantina = $row["JANTINA"];
	$kehadiran = $row["KEHADIRAN"];
	$kehadiranpenuh = $row["KEHADIRANPENUH"];
	$bil=$bil+1;
	if($bil&1) {
		$bcol = "#CDCDCD";
	} else {
		$bcol = "";
	}
	echo "  <tr bgcolor='$bcol'>\n";
	echo "    <td><center>$bil</center></td>\n";
	echo "    <td>$nokp</a></td>\n";
	echo "    <td>$nama</a></td>\n";
	echo "    <td><center>$jantina</center></td>\n";
	echo "    <td><a href=slipsr-jpn.php?data=".urlencode($nokp)."|".$kodsek."|".$ting."|".urlencode($kelas)."|".$tahun."|".$jpep."|".$lencana." target=_blank><center><img src = images/printer.png width=13 height=13 Alt=\"Cetak\" border=0></center></a></td>\n";
	
	echo "    <td><a href=slipmuridsr_excel-jpn.php?data=".urlencode($nokp)."|".$kodsek."|".$ting."|".urlencode($kelas)."|".$tahun."|".$jpep."|".$lencana." target=_blank><center><img src = images/printer.png width=13 height=13 Alt=\"Cetak\" border=0></center></a></td>\n";
	
	//echo "    <td><a href=edit_ulasan.php?data=".$nokp."|".$kodsek."|".$gting."|".$gkelas."|".$_SESSION['tahun']."|".$_SESSION['jpep']."><center><img src = images/edit.png width=12 height=13 Alt=\"Sunting\" border=0></center></a></td>\n";
	echo "<td align='center'>$kehadiran / $kehadiranpenuh</td>\n";
			
}
echo "</th>\n";
echo "</tr>\n";
echo "</table></body>\n";
?>
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('slipallsr_excel-jpn.php?data=<?php echo $m;?>','win1');" /></center>
<?php
echo "<br><br><br>";
include 'kaki.php';
?> 