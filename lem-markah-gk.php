<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
$tingkatan = $_GET['tingkatan'];
$namagu = $_GET['namaguru'];
$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];
$m="$ting&&kelas=$kelas&&namaguru=$namagu&&tingkatan=$tingkatan&&namasekolah=$namasek";

?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>

<form action="cetak_lembarangk.php?ting=<?php echo $m;?>" method="POST" target="_blank">
<td valign="top" class="rightColumn">
<p class="subHeader">Lembaran Markah Kelas <?php echo "$gting $gkelas";?></p>
<?php

$q_guru = oci_parse($conn_sispa,"SELECT NOKP,NAMA,NAMASEK,KODSEK,TING,KELAS,STATUSSEK FROM tguru_kelas WHERE tahun='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND nokp='".$_SESSION['nokp']."'");
oci_execute($q_guru);
$row=oci_fetch_array($q_guru);
$nokp=$row["NOKP"];
$nama=$row["NAMA"]; 
$namasek=$row["NAMASEK"]; 
$kodsek=$row["KODSEK"];
$ting=$row["TING"]; 
$gting=strtoupper($row["TING"]); 
$gkelas=$row["KELAS"];
$jsek = $row["STATUSSEK"];

if ($_SESSION['statussek']=="SM"){
	$nting="TINGKATAN";
	$tmarkah="markah_pelajar";
	$tmurid="tmurid";
	$tmp="mpsmkc";
	$tahap="ting";
	//$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";
	$kodsekolah = "kodsek$ting='$kodsek'";
}

if ($_SESSION['statussek']=="SR"){
	$nting="TAHUN";
	$tmarkah="markah_pelajarsr";
	$tmurid="tmuridsr";
	$tmp="mpsr";
	$tahap="darjah";
	//$kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	$kodsekolah = "kodsek$ting='$kodsek'";

}


echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h3><center>$namasek<br>LEMBARAN MARKAH MURID<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3><br>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<tr bgcolor=\"#FFCC99\">";
//echo "GURU KELAS : $nama<br>$nting : $ting  KELAS : $gkelas";
//echo "<br><br>";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";
$q_mp = oci_parse($conn_sispa,"SELECT KODMP FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas' ORDER BY kodmp");
oci_execute($q_mp);
//$num = count_row("SELECT KODMP FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas' ORDER BY kodmp");
$kirasub=0;
while($rowmp=oci_fetch_array($q_mp)){
$mp_kodmp = $rowmp["KODMP"];
echo "<td colspan = \"2\"><center>$mp_kodmp</center></td>";
$kirasub++;
}
echo "</tr>";
echo "<tr bgcolor=\"#FFCC99\">";
for($i=0;$i<$kirasub;$i++){
	echo "<td><center>M</center></td>";
	echo "<td><center>G</center></td>";
}
/*$q_mp = oci_parse($conn_sispa,"SELECT KODMP FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas' ORDER BY kodmp");
oci_execute($q_mp);
while($rowmp=oci_fetch_array($q_mp)){
$mp_kodmp = $rowmp["KODMP"];

	echo "<td><center>M</center></td>";
	echo "<td><center>G</center></td>";
	}*/
echo "</tr>";
//////habis kepala

$bil=0;
$q_murid = oci_parse($conn_sispa,"SELECT NAMAP, NOKP FROM $tmurid WHERE ($kodsekolah) AND $gting='$ting' and kelas$gting='$gkelas' and tahun$gting='".$_SESSION['tahun']."'  ORDER BY namap");
//echo "SELECT NAMAP, NOKP FROM $tmurid WHERE $kodsekolah AND $gting='$ting' and kelas$gting='$gkelas' and tahun$gting='".$_SESSION['tahun']."'  ORDER BY namap";
//echo ("SELECT * FROM $tmurid WHERE tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$gkelas' AND ($kodsekolah) ORDER BY namap");
oci_execute($q_murid);
//"".count_row("SELECT NAMAP, NOKP FROM $tmurid WHERE ($kodsekolah) AND $gting='$ting' and kelas$gting='$gkelas' and tahun$gting='".$_SESSION['tahun']."' ORDER BY namap")."";
while($rowmurid=oci_fetch_array($q_murid)){
$namap = $rowmurid["NAMAP"];
$nokpm = $rowmurid["NOKP"];
$bil=$bil+1;
if($bil&1) {
	$bcol = "#CDCDCD";
} else {
	$bcol = "";
}
echo "    <tr bgcolor='$bcol'>\n";
echo "    <td><center>$bil</center></td>\n";
echo "    <td>$namap</td>\n";

$q_mp = oci_parse($conn_sispa,"SELECT KODMP FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas' ORDER BY kodmp");
oci_execute($q_mp);

while($rowmp=oci_fetch_array($q_mp)){
$kodmp = $rowmp["KODMP"];
$gmp= "G$kodmp";

//echo "$kodmp $gmp $nokpm<br>";
$q_mark = oci_parse($conn_sispa,"SELECT * FROM $tmarkah WHERE nokp='$nokpm' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$gkelas' AND jpep='".$_SESSION['jpep']."' ORDER BY nama");
//echo ("SELECT * FROM $tmarkah WHERE nokp='$nokpm' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$gkelas' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' ORDER BY nama");
oci_execute($q_mark);
	//echo "budak".mysql_num_rows($q_mark)."";
	while($row = oci_fetch_array($q_mark)) {
		
		echo "    <td><center>&nbsp;".$row["$kodmp"]."</center></td>\n";
		echo "    <td><center>&nbsp;".$row["$gmp"]."</center></td>\n";
		}
	}
}

echo "</table>\n";
?>
<br><br>
&nbsp;&nbsp;<input type="submit" name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCELL" onclick="open_window('lembarangk_excel.php?ting=<?php echo $m;?>','win1');" />
</form>
</td>

<?php include 'kaki.php';?> 