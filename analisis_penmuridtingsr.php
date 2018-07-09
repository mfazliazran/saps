<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include "input_validation.php";

$tahun = $_SESSION['tahun'];
$ting = validate($_GET['ting']);
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];
$m="$ting";
?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>


<form action="ctk_penmuridtingsr.php?ting=<?php echo $m;?>" method="POST" target="_blank">

<td valign="top" class="rightColumn">
<p class="subHeader">Analisa Pencapaian Murid Tingkatan / Tahun</p>
<?php
echo "<h5><center>$namasek<br>ANALISIS KEPUTUSAN PEPERIKSAAN MURID $ting<br>".jpep($jpep)." TAHUN $tahun</center></h5>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\">Kelas</td>\n";
echo "    <td rowspan=\"2\">Bil MP<br>Ambil</td>\n";
if($tahun<=2014){
	echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
}elseif($tahun==2015){
	if($ting=='D6')
		echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
	else
		echo "    <td colspan=\"7\"><div align=\"center\">Bilangan Gred </div></td>\n";
}else{
	echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
}
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Menguasai / <br>Tidak Menguasai</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">GPC</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDT</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;B&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;C&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;D&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;E&nbsp;&nbsp;</div></td>\n";
if($tahun==2015){
	if($ting!='D6')
		echo "    <td><div align=\"center\">F</div></td>\n";
}
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";

$q_nting = "SELECT *  FROM tnilai_sr sr, tmuridsr tm WHERE sr.kodsek='$kodsek' AND sr.tahun='$tahun' AND sr.jpep='$jpep' AND sr.darjah= :ting AND sr.nokp=tm.nokp and kodsek_semasa='$kodsek' Order By Case sr.keputusan When 'CEMERLANG' Then 1
    When 'BAIK' Then 2
    When 'MEMUASKAN' Then 3
	When 'MENCAPAI TAHAP MINIMUM' Then 4
	When 'BELUM MENCAPAI TAHAP MINIMUM' Then 5
	When 'Lulus' Then 6
    Else 7 End, gpc ASC, peratus DESC";
$qry = oci_parse($conn_sispa,$q_nting);
oci_bind_by_name($qry, ':ting', $ting);
oci_execute($qry);
$parameter = array(":ting");
$value = array($ting);
$bilmting = kira_bil_rekod($q_nting,$parameter,$value);

$q_nkelas = "SELECT sr.kelas, COUNT(kelas) bilm  FROM tnilai_sr sr, tmuridsr tm WHERE sr.kodsek='$kodsek' AND sr.tahun='$tahun' AND sr.jpep='$jpep' AND sr.darjah= :ting AND sr.nokp=tm.nokp and kodsek_semasa='$kodsek' GROUP BY sr.kelas ";
$stmt = oci_parse($conn_sispa,$q_nkelas);
oci_bind_by_name($stmt, ':ting', $ting);
oci_execute($stmt);
while($rowbil = oci_fetch_array($stmt))
{
	$bilpel[] = array("KELAS"=>$rowbil[KELAS], "BIL"=>$rowbil[BILM]);
}

while( $rownilai = oci_fetch_array($qry) )
{
	$penilai[] = $rownilai ;
}

if (!empty($penilai))
{
	$bil = 0;
	foreach( $penilai as $key => $nilai )
	{
		$bil=$bil+1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		$kelas = $nilai[KELAS];
		
		$bilpelkls = semakbilkls($kelas, $bilpel);
		echo "  <tr bgcolor='$bcol'>\n";
		echo "    <td>$bil</td>\n";
		echo "    <td>$nilai[NAMAP]</td>\n";
		echo "    <td>$nilai[KELAS]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILMP]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILA]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILB]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILC]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILD]</td>\n";
		echo "    <td><center>&nbsp;$nilai[BILE]</td>\n";
		if($tahun==2015){
			if($ting!='D6')
				echo "    <td><center>&nbsp;$nilai[BILF]</td>\n";
		}
		echo "    <td><center>&nbsp;$nilai[BILTH]</td>\n";
		echo "    <td><center>&nbsp;$nilai[JUMMARK]</td>\n";
		echo "    <td><center>&nbsp;$nilai[PERATUS]</td>\n";
		echo "    <td><center>$nilai[KEPUTUSAN]</td>\n";
		echo "    <td><center>&nbsp;&nbsp;$nilai[GPC]&nbsp;&nbsp;</td>\n";
		echo "    <td><center>&nbsp;&nbsp;$nilai[KDK]/$bilpelkls&nbsp;&nbsp;</td>\n";
		echo "    <td><center>&nbsp;&nbsp;$nilai[KDT]/$bilmting&nbsp;&nbsp;</td>\n";
		echo "    <td>$nilai[PENCAPAIAN]</td>\n";
		echo "</tr>";
	}
}
else {
	echo "<br>";
	echo "<td colspan = \"17\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
	echo "<br>";
	echo "</tr>";
 }

echo "</table>\n";


function semakbilkls($elem, $arraybilpel)
{
	foreach ($arraybilpel as  $key => $value)
	{
		
		if ($elem == $value[KELAS]) { return $value[BIL]; }
	}
}
?>
<br><br>
&nbsp;&nbsp;<input type="submit" name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('data-penmuridtingsr-excel.php?ting=<?php echo $m;?>','win1');" />
</form>

<?php

include 'kaki.php';
?> 