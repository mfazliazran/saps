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
if($tahun=="")
	$tahun = date("Y");
if($_SESSION["ses_tahun"]<>"")
	$tahun = $_SESSION["ses_tahun"];
$ting = $_SESSION['pk_ting'];
$level = $_SESSION["level"];

$kodsek = $_SESSION['kodsek2'];
$jenis = $_SESSION['pk_jenis'];
$data = $_GET["data"];
$pk_kelas = $_GET["kelas"];

switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$jensekolah = "SEKOLAH RENDAH";
			$tnilai = "tnilai_sr";
			$darjah = "DARJAH";
			$paparlaporan = 1;
			$tmurid = "tmuridsr";
			$tingdarjah = "Darjah";
			break;
		case "P": case "T1": case "T2": case "T3":
			$jensekolah = "SEKOLAH MENENGAH";
			$tnilai = "tnilai_smr";
			$darjah = "TING";
			$paparlaporan = 0;
			$tmurid = "tmurid";
			$tingdarjah = "Tingkatan";
			break;
		case "T4": case "T5":
			$jensekolah = "SEKOLAH MENENGAH";
			$tnilai = "tnilai_sma";
			$darjah = "TING";
			$paparlaporan = 2;
			$tmurid = "tmurid";
			$tingdarjah = "Tingkatan";
			break;
	}

$q_sql=("SELECT namasek,lencana FROM tsekolah WHERE kodsek='$kodsek'");
$q_sql=oci_parse ($conn_sispa,$q_sql);
oci_execute($q_sql);
$row = oci_fetch_array($q_sql);
$namasek = $row["NAMASEK"];
$lencana = $row["LENCANA"];
$periksa = jpep ($jenis);

if($paparlaporan==1){
	//SR
if($data=="ALL"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas'";	
	else
		$koddata = "";	
	$head = "Keseluruhan Pelajar";
}
if($data=="A"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and to_number(BILA)=to_number(BILMP)";	
	else
		$koddata = "and BILA=BILMP";	
	$head = "Semua A";
}
if($data=="B"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and (BILA > 0 or BILB > 0) and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and NVL(BILA,0) < BILMP";	
	else
		$koddata = "and (BILA > 0 or BILB > 0) and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and NVL(BILA,0) < BILMP";	
	$head = "Sekurangnya Satu Gred B";
}
if($data=="C"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILC > 0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	else
		$koddata = "and BILC > 0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred C";
}
if($data=="D"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILD > 0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	else
		$koddata = "and BILD > 0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred D";
}
if($data=="E"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILE > 0 and NVL(BILTH,0)=0 and to_number(BILE) < to_number(BILMP)";	
	else
		$koddata = "and BILE > 0 and NVL(BILTH,0)=0 and to_number(BILE) < to_number(BILMP)";	
	$head = "Sekurangnya Satu Gred E";
}
if($data=="TH"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILTH > 0";// and NVL(BILA,0)=0 and NVL(BILB,0)=0 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0";	
	else
		$koddata = "and BILTH > 0";// and NVL(BILA,0)=0 and NVL(BILB,0)=0 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0";	
	$head = "Sekurangnya Satu Gred TH";
}
if($data=="DEALL"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and (BILD > 0 or BILE > 0) and NVL(BILA,0)=0 and NVL(BILB,0)=0 and NVL(BILC,0)=0 and NVL(BILTH,0)=0";	
	else
		$koddata = "and (BILD > 0 or BILE > 0) and NVL(BILA,0)=0 and NVL(BILB,0)=0 and NVL(BILC,0)=0 and NVL(BILTH,0)=0";	
	$head = "Semua DE";
}
if($data=="THALL"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and to_number(BILMP)=to_number(BILTH)";	
	else
		$koddata = "and to_number(BILMP)=to_number(BILTH)";	
	$head = "Tidak Hadir Semua";
}
if($data=="ABC"){
	if($pk_kelas<>"")	
		$koddata = "and kelas='$pk_kelas' AND (BILA > 0 OR BILB > 0 OR BILC >0) and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	else
		$koddata = " AND (BILA > 0 OR BILB > 0 OR BILC >0) and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	$head = "BIL. CALON A B C";
}
if($data=="DE"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' AND (BILD > 0 OR BILE > 0)";	
	else
		$koddata = "AND (BILD > 0 OR BILE > 0)";	
	$head = "BIL. CALON D E";
}
echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
echo "<table width='90%' border='0' align='center'>";
echo "<tr><td colspan='3' align='center'><strong>SENARAI PENCAPAIAN KECEMERLANGAN CALON MENGIKUT KATEGORI</strong></td></tr>";
echo "<tr><td colspan='3' align='center'><strong>$periksa</strong></td></tr>";
echo "<tr><td colspan='3' align='center'><strong>TAHUN $tahun</strong></td></tr>";
echo "<tr><td width='14%'>SEKOLAH</td><td width='1%'>:</td><td width='85%'><strong>$namasek</strong></td></tr>";
echo "<tr><td>".strtoupper($tingdarjah)." / KELAS</td><td>:</td><td><strong>$ting $pk_kelas</strong></td></tr>";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td><strong>$head</strong></td></tr>";
echo "</table>";
echo "<table width=\"1000\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#ff9900\">";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">No. KP</div></td>\n";
echo "    <td><div align=\"center\">Nama</div></td>\n";
echo "    <td><div align=\"center\">Jantina</div></td>\n";
echo "    <td><div align=\"center\">Kelas</div></td>\n";
echo "    <td><div align=\"center\">GPS Ind.</div></td>\n";
echo "    <td><div align=\"center\">MP Daftar</div></td>\n";
echo "    <td><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";

	$sql_nilai = "select $tnilai.*, namap,jantina from $tnilai 
				  LEFT JOIN $tmurid ON $tnilai.nokp=$tmurid.nokp where kodsek='$kodsek' and $darjah='$ting' and tahun='$tahun' AND jpep='$jenis' $koddata and kodsek_semasa='$kodsek' ORDER BY gpc asc,pencapaian asc";//INDEX TAHUN JPEP KODSEK TING KELAS
	 // if($kodsek=='JBA0004')
		//echo $sql_nilai."<br>";
	$res_nilai = oci_parse($conn_sispa,$sql_nilai);
	oci_execute($res_nilai);
	$cnt=0;
	while($data_nilai = oci_fetch_array($res_nilai)){
		$cnt++;
		$bilmp = $data_nilai["BILMP"];
		$nokp = $data_nilai["NOKP"];
		$namap = $data_nilai["NAMAP"];
		$jantina = $data_nilai["JANTINA"];
		$namakelas = $data_nilai["KELAS"];
		$ting = $data_nilai["$darjah"];
		$gpc = $data_nilai["GPC"];
		$pencapaian = $data_nilai["PENCAPAIAN"];
		$bila = $data_nilai["BILA"];
		$bilb = $data_nilai["BILB"];
		if($data=="B"){
			if($bilb>0 or ($bilb==0 and $bila>0 and $bila< $bilmp)){
				$cntb++;
				echo "<tr><td>$cntb</td>";
				echo "<td align='left'>$nokp&nbsp;</td>";
				echo "<td align='left'>$namap&nbsp;</td>";
				echo "<td align='center'>$jantina&nbsp;</td>";
				echo "<td align='left'>$namakelas&nbsp;</td>";
				echo "<td align='center'>$gpc&nbsp;</td>";
				echo "<td align='center'>$bilmp&nbsp;</td>";
				echo "<td align='left'>$pencapaian&nbsp;</td>";	
			}
		}else{
			echo "<tr><td>$cnt</td>";
			echo "<td align='left'>$nokp&nbsp;</td>";
			echo "<td align='left'>$namap&nbsp;</td>";
			echo "<td align='center'>$jantina&nbsp;</td>";
			echo "<td align='left'>$namakelas&nbsp;</td>";
			echo "<td align='center'>$gpc&nbsp;</td>";
			echo "<td align='center'>$bilmp&nbsp;</td>";
			echo "<td align='left'>$pencapaian&nbsp;</td>";
		}
	}
}
if($paparlaporan==0){
//SMR
if($data=="ALL"){//papar semua ambil
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas'";	
	else
		$koddata = "";	
	$head = "Keseluruhan Pelajar";
}
if($data=="A"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILA=BILMP";	
	else
		$koddata = "and BILA=BILMP";	
	$head = "Semua A";
}
if($data=="B"){
	if($pk_kelas<>"")
		//$koddata = "and kelas='$pk_kelas' and (BILB > 0 or NVL(BILB,0)=0)";// or (BILB = 0 and BILA > 0 and BILA < BILMP)";	
		$koddata = "and kelas='$pk_kelas' and (to_number(BILA) > 0 or to_number(BILB)>0) and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and to_number(BILA) < to_number(BILMP)";
	else
		$koddata = "and (BILA > 0 or BILB>0) and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and BILA < BILMP";	
	$head = "Sekurangnya Satu Gred B";
}
if($data=="C"){
	if($pk_kelas<>"")
		//$koddata = "and kelas='$pk_kelas' and BILC > 0 and NVL(BILA,0)=0 and NVL(BILB,0)=0";	
		$koddata = "and kelas='$pk_kelas' and BILC > 0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";
	else
		$koddata = "and BILC > 0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred C";
}
if($data=="D"){
	if($pk_kelas<>"")
		//$koddata = "and kelas='$pk_kelas' and BILD > 0 and NVL(BILA,0)=0 and NVL(BILB,0)=0 and NVL(BILC,0)=0";	
		$koddata = "and kelas='$pk_kelas' and BILD > 0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	else
		$koddata = "and BILD > 0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred D";
}
if($data=="E"){
	if($pk_kelas<>"")
		//$koddata = "and kelas='$pk_kelas' and BILE > 0 and NVL(BILA,0)=0 and NVL(BILB,0)=0 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and BILE < BILMP";	
		$koddata = "and kelas='$pk_kelas' and BILE > 0 and NVL(BILTH,0)=0 and BILE < BILMP";	
	else
		$koddata = "and BILE > 0 and NVL(BILTH,0)=0 and BILE < BILMP";	
	$head = "Sekurangnya Satu Gred E";
}
if($data=="TH"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILTH > 0";// and NVL(BILA,0)=0 and NVL(BILB,0)=0 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0";	
	else
		$koddata = "and BILTH > 0";// and NVL(BILA,0)=0 and NVL(BILB,0)=0 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0";	
	$head = "Sekurangnya Satu Gred TH";
}
if($data=="EALL"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILE=BILMP";
	else
		$koddata = "and BILE=BILMP";
	$head = "E Semua Mata Pelajaran";
}
if($data=="THALL"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILMP=BILTH";	
	else
		$koddata = "and BILMP=BILTH";	
	$head = "Tidak Hadir Semua";
}
if($data=="ABCD"){
	if($pk_kelas<>"")	
		$koddata = "and kelas='$pk_kelas' AND (BILA > 0 OR BILB > 0 OR BILC >0 OR BILD >0) and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	else
		$koddata = " AND (BILA > 0 OR BILB > 0 OR BILC >0 OR BILD >0) and NVL(BILE,0)=0 and NVL(BILTH,0)=0";	
	$head = "Keputusan Capai A B C D";
}
if($data=="ETH"){
	if($pk_kelas<>"")
		//$koddata = "and kelas='$pk_kelas' AND NVL(BILA,0)=0 AND NVL(BILB,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND (BILE > 0 OR BILTH > 0)";	
		$koddata = "and kelas='$pk_kelas' and to_number(BILE) > 0 and NVL(BILTH,0)=0";	
	else
		$koddata = "and to_number(BILE) > 0 and NVL(BILTH,0)=0";	
	$head = "Keputusan Tidak Capai";
}
echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
echo "<table width='90%' border='0' align='center'>";
echo "<tr><td colspan='3' align='center'><strong>SENARAI PENCAPAIAN KECEMERLANGAN CALON MENGIKUT KATEGORI</strong></td></tr>";
echo "<tr><td colspan='3' align='center'><strong>$periksa</strong></td></tr>";
echo "<tr><td colspan='3' align='center'><strong>TAHUN $tahun</strong></td></tr>";
echo "<tr><td width='14%'>SEKOLAH</td><td width='1%'>:</td><td width='85%'><strong>$namasek</strong></td></tr>";
echo "<tr><td>".strtoupper($tingdarjah)." / KELAS</td><td>:</td><td><strong>$ting $pk_kelas</strong></td></tr>";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td><strong>$head</strong></td></tr>";
echo "</table>";
echo "<table width=\"1000\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#ff9900\">";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">No. KP</div></td>\n";
echo "    <td><div align=\"center\">Nama</div></td>\n";
echo "    <td><div align=\"center\">Jantina</div></td>\n";
echo "    <td><div align=\"center\">Kelas</div></td>\n";
echo "    <td><div align=\"center\">GPS Ind.</div></td>\n";
echo "    <td><div align=\"center\">MP Daftar</div></td>\n";
echo "    <td><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";

	$sql_nilai = "select $tnilai.*, namap,jantina from $tnilai 
				  LEFT JOIN $tmurid ON $tnilai.nokp=$tmurid.nokp where tahun='$tahun' AND jpep='$jenis' AND kodsek='$kodsek' and $darjah='$ting' $koddata and kodsek_semasa='$kodsek' ORDER BY gpc ASC,pencapaian asc";//INDEX TAHUN JPEP KODSEK TING KELAS
	//echo $sql_nilai."<br>";
	
	$res_nilai = oci_parse($conn_sispa,$sql_nilai);
	oci_execute($res_nilai);
	$cnt=0;$cntb=0;$$cntde=0;$bilmp=0;$bila=0;$bilb=0;$bild=0;$bile=0;
	while($data_nilai = oci_fetch_array($res_nilai)){
		$cnt++;
		$bilmp = $data_nilai["BILMP"];
		$nokp = $data_nilai["NOKP"];
		$namap = $data_nilai["NAMAP"];
		$jantina = $data_nilai["JANTINA"];
		$namakelas = $data_nilai["KELAS"];
		$ting = $data_nilai["$darjah"];
		$gpc = $data_nilai["GPC"];
		$pencapaian = $data_nilai["PENCAPAIAN"];
		$bila = $data_nilai["BILA"];
		$bilb = $data_nilai["BILB"];
		$bild = $data_nilai["BILD"];
		$bile = $data_nilai["BILE"];
		$bilde = (int) $bild + (int) $bile;
		if($data=="DEALL"){
			if($bilde==$bilmp){
				$cntde++;
				echo "<tr><td>$cntde</td>";
				echo "<td align='left'>$nokp&nbsp;</td>";
				echo "<td align='left'>$namap&nbsp;</td>";
				echo "<td align='center'>$jantina&nbsp;</td>";
				echo "<td align='left'>$namakelas&nbsp;</td>";
				echo "<td align='center'>$gpc&nbsp;</td>";
				echo "<td align='center'>$bilmp&nbsp;</td>";
				echo "<td align='left'>$pencapaian&nbsp;</td>";	
			}
		}
		elseif($data=="B"){
			if($bilb>0 or ($bilb==0 and $bila>0 and $bila< $bilmp)){
				$cntb++;
				echo "<tr><td>$cntb</td>";
				echo "<td align='left'>$nokp&nbsp;</td>";
				echo "<td align='left'>$namap&nbsp;</td>";
				echo "<td align='center'>$jantina&nbsp;</td>";
				echo "<td align='left'>$namakelas&nbsp;</td>";
				echo "<td align='center'>$gpc&nbsp;</td>";
				echo "<td align='center'>$bilmp&nbsp;</td>";
				echo "<td align='left'>$pencapaian&nbsp;</td>";	
			}
		}
		else{
			echo "<tr><td>$cnt</td>";
			echo "<td align='left'>$nokp&nbsp;</td>";
			echo "<td align='left'>$namap&nbsp;</td>";
			echo "<td align='center'>$jantina&nbsp;</td>";
			echo "<td align='left'>$namakelas&nbsp;</td>";
			echo "<td align='center'>$gpc&nbsp;</td>";
			echo "<td align='center'>$bilmp&nbsp;</td>";
			echo "<td align='left'>$pencapaian&nbsp;</td>";
		}
	}
}
if($paparlaporan==2){
	//SMA
if($data=="ALL"){//papar semua ambil
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas'";	
	else
		$koddata = "";	
	$head = "Keseluruhan Pelajar";
}	
if($data=="AP"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and to_number(BILMP)=to_number(BILAP)";	
	else
		$koddata = "and to_number(BILMP)=to_number(BILAP)";	
	$head = "Semua A+";
}	
if($data=="A"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and (BILAP > 0 or BILA>0) and NVL(BILAM,0)=0 and NVL(BILBP,0)=0 AND NVL(BILB,0)=0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and NVL(BILAP,0) < to_number(BILMP)";
	else
		$koddata = "and (BILAP > 0 or BILA>0) and NVL(BILAM,0)=0 and NVL(BILBP,0)=0 AND NVL(BILB,0)=0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and NVL(BILAP,0) < to_number(BILMP)";	
	$head = "Sekurangnya Satu Gred A";
}
if($data=="AM"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILAM > 0 and NVL(BILBP,0)=0 AND NVL(BILB,0)=0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	else
		$koddata = "and BILAM > 0 and NVL(BILBP,0)=0 AND NVL(BILB,0)=0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred A-";
}
if($data=="BP"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILBP > 0 AND NVL(BILB,0)=0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	else
		$koddata = "and BILBP > 0 AND NVL(BILB,0)=0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred B+";
}
if($data=="B"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILB > 0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	else
		$koddata = "and BILB > 0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred B";
}
if($data=="CP"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILCP > 0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	else
		$koddata = "and BILCP > 0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred C+";
}
if($data=="C"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILC > 0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	else
		$koddata = "and BILC > 0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred C";
}
if($data=="D"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILD > 0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	else
		$koddata = "and BILD > 0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred D";
}
if($data=="E"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and BILE > 0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and NVL(BILE,0) < to_number(BILMP)";	
	else
		$koddata = "and BILE > 0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and NVL(BILE,0) < to_number(BILMP)";	
	$head = "Sekurangnya Satu Gred E";
}
if($data=="G"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and to_number(BILG) > 0 AND NVL(BILTH,0)=0";	
	else
		$koddata = "and to_number(BILG) > 0 AND NVL(BILTH,0)=0";	
	$head = "Sekurangnya Satu Gred G";
}
if($data=="TH"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and to_number(BILTH) > 0";	
	else
		$koddata = "and to_number(BILTH) > 0";	
	$head = "Sekurangnya Satu Gred TH";
}
if($data=="EALL"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and to_number(BILE)=to_number(BILMP)";	
	else
		$koddata = "and to_number(BILE)=to_number(BILMP)";	
	$head = "E Semua Mata Pelajaran";
}
if($data=="THALL"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and to_number(BILMP)=to_number(BILTH)";	
	else
		$koddata = "and to_number(BILMP)=to_number(BILTH)";	
	$head = "Tidak Hadir Semua";
}
if($data=="ABCDE"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and (to_number(BILAP) > 0 OR to_number(BILA) > 0 OR to_number(BILAM) > 0 OR to_number(BILBP) > 0 OR to_number(BILB) > 0 OR to_number(BILCP) > 0 OR to_number(BILC) > 0 OR to_number(BILD) > 0 OR to_number(BILE) > 0) and NVL(BILG,0)=0 and NVL(BILTH,0)=0";	
	else
		$koddata = "and (to_number(BILAP) > 0 OR to_number(BILA) > 0 OR to_number(BILAM) > 0 OR to_number(BILBP) > 0 OR to_number(BILB) > 0 OR to_number(BILCP) > 0 OR to_number(BILC) > 0 OR to_number(BILD) > 0 OR to_number(BILE) > 0) and NVL(BILG,0)=0 and NVL(BILTH,0)=0";	
	$head = "Semua A+ A A- B+ B C+ C D E";
}
if($data=="GTH"){
	if($pk_kelas<>"")
		$koddata = "and kelas='$pk_kelas' and to_number(BILG) > 0 and NVL(BILTH,0)=0";// AND NVL(BILAP,0)=0 AND NVL(BILA,0)=0 AND NVL(BILAM,0)=0 AND NVL(BILBP,0)=0 AND NVL(BILB,0)=0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0";
	else 
		$koddata = "and to_number(BILG) > 0 and NVL(BILTH,0)=0";// AND NVL(BILAP,0)=0 AND NVL(BILA,0)=0 AND NVL(BILAM,0)=0 AND NVL(BILBP,0)=0 AND NVL(BILB,0)=0 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0";	
	$head = "Semua G TH";
}
	//paparlaporan=2
echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";	
echo "<table width='90%' border='0' align='center'>";
echo "<tr><td colspan='3' align='center'><strong>SENARAI PENCAPAIAN KECEMERLANGAN CALON MENGIKUT KATEGORI</strong></td></tr>";
echo "<tr><td colspan='3' align='center'><strong>$periksa</strong></td></tr>";
echo "<tr><td colspan='3' align='center'><strong>TAHUN $tahun</strong></td></tr>";
echo "<tr><td width='14%'>SEKOLAH</td><td width='1%'>:</td><td width='85%'><strong>$namasek</strong></td></tr>";
echo "<tr><td>".strtoupper($tingdarjah)." / KELAS</td><td>:</td><td><strong>$ting $pk_kelas</strong></td></tr>";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td><strong>$head</strong></td></tr>";
echo "</table>";	
echo "<table width=\"1000\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#ff9900\">";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">No. KP</div></td>\n";
echo "    <td><div align=\"center\">Nama</div></td>\n";
echo "    <td><div align=\"center\">Jantina</div></td>\n";
echo "    <td><div align=\"center\">Kelas</div></td>\n";
echo "    <td><div align=\"center\">GPS Ind.</div></td>\n";
echo "    <td><div align=\"center\">MP Daftar</div></td>\n";
echo "    <td><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";


	$sql_nilai = "select $tnilai.*, namap,jantina from $tnilai LEFT JOIN $tmurid ON $tnilai.nokp=$tmurid.nokp where tahun='$tahun' AND jpep='$jenis' AND kodsek='$kodsek' and $darjah='$ting' $koddata and kodsek_semasa='$kodsek' ORDER BY gpc ASC,pencapaian asc";
	//echo $sql_nilai."<br>";
	$res_nilai = oci_parse($conn_sispa,$sql_nilai);
	oci_execute($res_nilai);
	$cnt=0;
	while($data_nilai = oci_fetch_array($res_nilai)){
		$cnt++;
		$bilmp = $data_nilai["BILMP"];
		$nokp = $data_nilai["NOKP"];
		$namap = $data_nilai["NAMAP"];
		$jantina = $data_nilai["JANTINA"];
		$ting = $data_nilai["$darjah"];
		$gpc = $data_nilai["GPC"];
		$pencapaian = $data_nilai["PENCAPAIAN"];
		$namakelas = $data_nilai["KELAS"];
		$bilAplus = $data_nilai["BILAP"];
		$bilA = $data_nilai["BILA"];
		/*if($data=="A"){
			if($bilA>0 or ($bilA==0 and $bilAplus>0 and $bilAplus < $bilmp)){
				$cnta++;
				echo "<tr><td>$cnta</td>";
				echo "<td align='left'>$nokp&nbsp;</td>";
				echo "<td align='left'>$namap&nbsp;</td>";
				echo "<td align='center'>$jantina&nbsp;</td>";
				echo "<td align='left'>$namakelas&nbsp;</td>";
				echo "<td align='center'>$gpc&nbsp;</td>";
				echo "<td align='center'>$bilmp&nbsp;</td>";
				echo "<td align='left'>$pencapaian&nbsp;</td>";
			}
		}else{*/
			echo "<tr><td>$cnt</td>";
			echo "<td align='left'>$nokp&nbsp;</td>";
			echo "<td align='left'>$namap&nbsp;</td>";
			echo "<td align='center'>$jantina&nbsp;</td>";
			echo "<td align='left'>$namakelas&nbsp;</td>";
			echo "<td align='center'>$gpc&nbsp;</td>";
			echo "<td align='center'>$bilmp&nbsp;</td>";
			echo "<td align='left'>$pencapaian&nbsp;</td>";
		//}
	}
}
?>



