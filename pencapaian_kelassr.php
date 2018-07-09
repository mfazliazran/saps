<?php

include 'auth.php';
include 'config.php';
include 'fungsi.php';
include 'fungsikira.php';
//include 'menu.php';
?>
<title>PENCAPAIAN SEKOLAH MENGIKUT KELAS</title>
<td valign="top" class="rightColumn">
<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>
<?php
$tahun = $_SESSION['tahun'];
$ting = $_POST['ting'];
$kodsek = $_SESSION['kodsek'];
$jenis = $_POST['jenis'];

//$/q_sql=("SELECT jenis FROM JPEP WHERE kod='U1' OR kod='U2' OR kod='PAT' OR kod='PPT' OR kod='UPSRC' ORDER BY jenis");
$q_sql=("SELECT namasek FROM tsekolah WHERE kodsek='$kodsek'");
$q_sql=oci_parse ($conn_sispa,$q_sql);
oci_execute($q_sql);
$row = oci_fetch_array($q_sql);
$namasek = $row["NAMASEK"];
$periksa = jpep ($jenis);

//echo "<h2><center><br>PENCAPAIAN SEKOLAH MENGIKUT KELAS<br></center></h2>";
//echo "<h2><center>(SEKOLAH RENDAH)<br></center></h2>";
//echo "<h2><center><br>PENCAPAIAN SEKOLAH MENGIKUT KELAS<br></center></h2>";
echo "<table width='90%' border='0' align='center'>";
echo "<tr><td colspan='3' align='center'><strong>PENCAPAIAN SEKOLAH MENGIKUT KELAS</strong></td></tr>";
echo "<tr><td colspan='3' align='center'><strong>(SEKOLAH RENDAH)</strong></td></tr>";
echo "<tr><td colspan='3' align='center'>&nbsp;</td></tr>";
echo "<tr><td colspan='3' align='center'><strong>PENCAPAIAN KESELURUHAN PELAJAR MENGIKUT KELAS</strong></td></tr>";
echo "<tr><td width='9%'>SEKOLAH</td><td width='1%'>:</td><td width='90%'><strong>$namasek</strong></td></tr>";
echo "<tr><td>PEPERIKSAAN</td><td>:</td><td><strong>$periksa</strong></td></tr>";
echo "<tr><td>TAHUN/TINGKATAN</td><td>:</td><td><strong>$ting</strong></td></tr>";
echo "</table>";
echo "<table width=\"600\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#ff9900\">";
echo "    <td rowspan=\"3\"><div align=\"center\">Kelas $kodmp</div></td>\n";
echo "    <td rowspan=\"3\"><div align=\"center\">Bil Calon Daftar</div></td>\n";
echo "    <td rowspan=\"3\"><div align=\"center\">Bil Calon Ambil </div></td>\n";
echo "    <td colspan=\"11\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";
echo "    <td rowspan=\"3\"><div align=\"center\">Peratus ABCD</div></td>\n";
echo "    <td rowspan=\"3\"><div align=\"center\">GPS kelas</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#ff9900\">\n";
echo "   <td rowspan='2'> <div align=\"center\">Semua A</div></td>\n";
echo "   <td rowspan='2'> <div align=\"center\">A semua MP</div></td>\n";
echo "   <td rowspan='2'> <div align=\"center\">Sekurangnya 1B</div></td>\n";
echo "   <td rowspan='2'> <div align=\"center\">Sekurangnya 1C</div></td>\n";
echo "   <td rowspan='2'> <div align=\"center\">Sekurangnya 1D</div></td>\n";
echo "   <td rowspan='2'> <div align=\"center\">Sekurangnya 1E</div></td>\n";
echo "   <td rowspan='2'> <div align=\"center\">Sekurangnya 1t</div></td>\n";
echo "   <td rowspan='2'> <div align=\"center\">E Semua MP</div></td>\n";
echo "   <td rowspan='2'> <div align=\"center\">Tidak Hadir Semua</div></td>\n";
echo "	 <td colspan=\"2\"><div align='center'>Keputusan</div></td>\n";
echo "</tr>\n";
echo "<tr bgcolor='#ff9900'>\n";
echo "   <td> <div align=\"center\">Capai ABCD</div></td>\n";
echo "   <td> <div align=\"center\">Tidak Capai</div></td>\n";
echo "  </tr>\n";

$w_kelas="SELECT DISTINCT KELAS FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting'";
$w_kelas=oci_parse($conn_sispa,$w_kelas);
oci_execute($w_kelas);
while($data=oci_fetch_array($w_kelas)){
	$kelas = $data["KELAS"];
	$bilambil = count_row("select from tmuridsr where kodsek$ting='$kodsek' and tahun$ting='$tahun' and $ting='$ting' and kelas$ting='$kelas'");
	$sql_nilai = "select * from tnilai_sr where kodsek='$kodsek' and tahun='$tahun' and darjah='$ting' and kelas='$kelas'";
	$res_nilai = oci_parse($conn_sispa,$sql_nilai);
	oci_execute($res_nilai);
	$cntA=0;$cntB=0;$cntC=0;$cntD=0;$cntE=0;$cntTH=0;$cntDE=0;$cntTHALL=0;$$cntABC=0;
	while($data_nilai = oci_fetch_array($res_nilai)){
		$bilmp = $data_nilai["BILMP"];
		$bilA = $data_nilai["BILA"];
		$bilB = $data_nilai["BILB"];
		$bilC = $data_nilai["BILC"];
		$bilD = $data_nilai["BILD"];
		$bilE = $data_nilai["BILE"];
		$bilTH = $data_nilai["BILTH"];
		$bilde = (int) $bilD + (int) $bilE;
		if($bilA==$bilmp)
			$cntA++;
		if($bilB>0)
			$cntB++;
		if($bilC>0 and $bilA==0 and $bilB==0)
			$cntC++;
		if($bilD>0 and $bilA==0 and $bilB==0 and $bilC==0)
			$cntD++;
		if($bilE>0 and $bilA==0 and $bilB==0 and $bilC==0 and $bilD==0)
			$cntE++;
		if($bilTH>0)
			$cntTH++;
		if($bilde==$bilmp)
			$cntDE++;
		if($bilTH==$bilmp)
			$cntTHALL++;
		$bilabc = (int) $cntA + (int) $cntB + (int) $cntC;
		$bilde = (int) $cntD + (int) $cntE;
		$peratuskuasai = peratus($bilabc,$bilambil);
		$gpskelas = gpmpmrsr($cntA,$cntB,$cntC,$cntD,$cntE,$bilambil);
	}
	//echo $sql_nilai;
	echo "<tr><td>$kelas</td>";
	echo "<td>$bilambil</td>";
	echo "<td>&nbsp;</td>";
	echo "<td>$cntA</td>";
	echo "<td>$cntB</td>";
	echo "<td>$cntC</td>";
	echo "<td>$cntD</td>";
	echo "<td>$cntE</td>";
	echo "<td>$cntTH</td>";
	echo "<td>$cntDE</td>";
	echo "<td>$cntTHALL</td>";
	echo "<td>$bilabc</td>";
	echo "<td>$bilde</td>";
	echo "<td>&nbsp;</td>";
	echo "<td>$peratuskuasai</td>";
	echo "<td>$gpskelas</td></tr>";
	
}

?>



