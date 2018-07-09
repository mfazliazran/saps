<?php

include 'auth.php';
include 'config.php';
include 'fungsi.php';
include 'fungsikira.php';
//include 'menu.php';
?>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<td valign="top" class="rightColumn">
<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>

<?php
$tahun = $_SESSION['tahun'];
$ting = $_POST['ting'];
$kodsek = $_SESSION['kodsek2'];
$jenis = $_POST['jenis'];
if($ting=="" and $jenis==""){
	//superadmin
	$ting = $_GET["ting"];
	$kodsek = $_GET["kodsek"];
	$_SESSION["kodseko"] = $kodsek;
	$jenis = $_GET["jenis"];
	if($_SESSION["ses_tahun"]<>"")
		$tahun = $_SESSION["ses_tahun"];
	else
		$tahun = date("Y");
}
$_SESSION["pk_ting"] = $ting;
$_SESSION["pk_jenis"] = $jenis;
//echo $ting;

switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			//location("analisis_mptingsr.php?ting=$ting&&namasekolah=$namasek");
			$jensekolah = "SEKOLAH RENDAH";
			$tnilai = "tnilai_sr";
			//echo "select * from $tnilai where tahun='$tahun' AND jpep='$jenis' AND kodsek='$kodsek' and $darjah='$ting' and kelas='$kelas'";
			//$kiradata = count_row("select * from $tnilai where tahun='$tahun' AND jpep='$jenis' AND kodsek='$kodsek' and $darjah='$ting' and kelas='$kelas'");
			//if($kiradata==0){
				//$tnilai = "penilaian_muridsr";	
			//}
			$darjah = "darjah";
			$paparlaporan = 1;
			$tingdarjah = "DARJAH";
			break;
		case "P": case "T1": case "T2": case "T3":
			//location("analisis_mptingmr.php?ting=$ting&&namasekolah=$namasek");
			$jensekolah = "SEKOLAH MENENGAH";
			$tnilai = "tnilai_smr";
			$darjah = "ting";
			$paparlaporan = 0;
			$tingdarjah = "TINGKATAN";
			break;
		case "T4": case "T5":
			//location("analisis_mptingma.php?ting=$ting&&namasekolah=$namasek");
			$jensekolah = "SEKOLAH MENENGAH";
			$tnilai = "tnilai_sma";
			$darjah = "ting";
			$paparlaporan = 2;
			$tingdarjah = "TINGKATAN";
			break;
	}

//$/q_sql=("SELECT jenis FROM JPEP WHERE kod='U1' OR kod='U2' OR kod='PAT' OR kod='PPT' OR kod='UPSRC' ORDER BY jenis");
$q_sql=("SELECT namasek,lencana FROM tsekolah WHERE kodsek='$kodsek'");
$q_sql=oci_parse ($conn_sispa,$q_sql);
oci_execute($q_sql);
$row = oci_fetch_array($q_sql);
$namasek = $row["NAMASEK"];
$lencana = $row["LENCANA"];
$periksa = jpep ($jenis);

echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
echo "<table width='90%' border='0' align='center'>";
echo "<tr><td colspan='3' align='center'><strong>PENCAPAIAN SEKOLAH MENGIKUT KELAS ($tahun)</strong></td></tr>";
echo "<tr><td colspan='3' align='center'><strong>($jensekolah)</strong></td></tr>";
echo "<tr><td colspan='3' align='center'>&nbsp;</td></tr>";
echo "<tr><td colspan='3' align='center'><strong>PENCAPAIAN KESELURUHAN PELAJAR MENGIKUT KELAS</strong></td></tr>";
echo "<tr><td width='9%'>SEKOLAH</td><td width='1%'>:</td><td width='90%'><strong>$namasek</strong></td></tr>";
echo "<tr><td>PEPERIKSAAN</td><td>:</td><td><strong>$periksa</strong></td></tr>";
echo "<tr><td>$tingdarjah</td><td>:</td><td><strong>$ting</strong></td></tr>";
echo "</table>";
if($paparlaporan==1){
	//SR
	$cols='14';
echo "<table width=\"1000\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td rowspan=\"3\"><div align=\"center\">Kelas $kodmp</div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">Bil Calon <br>Daftar</div></td>\n";
echo "<td colspan=\"10\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">Peratus <br>Menguasai</div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">GPS kelas</div></td>\n";
echo "</tr>\n";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td rowspan=\"2\" align='center'>Semua A</td>";
echo "<td colspan=\"5\" align='center'>Sekurangnya</td>";
echo "<td rowspan=\"2\" align='center'>Semua D E</td>";
echo "<td rowspan=\"2\" align='center'>Tidak Hadir <br>Semua</td>";
echo "<td colspan=\"2\" align='center'>Bil Calon</td>";
echo "</tr>";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td align='center'>1B</td>";
echo "<td align='center'>1C</td>";
echo "<td align='center'>1D</td>";
echo "<td align='center'>1E</td>";
echo "<td align='center'>1T</td>";
echo "<td align='center'>A B C</td>";
echo "<td align='center'>D E</td>";
echo "</tr>";
$w_kelas="SELECT DISTINCT KELAS FROM sub_guru WHERE kodsek='$kodsek' and tahun='$tahun' AND ting='$ting'";//INDEX GREDUPSR2
$w_kelas=oci_parse($conn_sispa,$w_kelas);
oci_execute($w_kelas);
while($data=oci_fetch_array($w_kelas)){
	$kelas = $data["KELAS"];//TNILAI  - TAHUN JPEP KODSEK DARJAH/TING
	$bilambil = count_row("select * from $tnilai where kodsek='$kodsek' and tahun='$tahun' AND jpep='$jenis' and $darjah='$ting' and kelas='$kelas'");
	$sql_nilai = "select * from $tnilai where tahun='$tahun' AND jpep='$jenis' AND kodsek='$kodsek' and $darjah='$ting' and kelas='$kelas'";
	//if($kodsek=='JBA0004')
		//echo $sql_nilai."<br>";
	$res_nilai = oci_parse($conn_sispa,$sql_nilai);
	oci_execute($res_nilai);
	$cntA=0;$cntB=0;$cntC=0;$cntD=0;$cntE=0;$cntTH=0;$cntDE=0;$cntTHALL=0;$cntABC=0;$bilabc=0;$bilde=0;$gpskelas=0;$peratuskuasai=0;
	$cuma=$sumb=$sumc=$sumd=$sume=$sumamb=0;$juma=$jumb=$jumc=$jumd=$jume=$jumamb;
	while($data_nilai = oci_fetch_array($res_nilai)){
		$bilmp = $data_nilai["BILMP"];
		$bilA = $data_nilai["BILA"];
		$bilB = $data_nilai["BILB"];
		$bilC = $data_nilai["BILC"];
		$bilD = $data_nilai["BILD"];
		$bilE = $data_nilai["BILE"];
		$bilTH = $data_nilai["BILTH"];
		$pk_kelas = $data_nilai["KELAS"];
		$bilde = (int) $bilD + (int) $bilE;
		if($bilA==$bilmp)
			$cntA++;
		if(($bilA>0 or $bilB>0) and $bilC==0 and $bilD==0 and $bilE==0 and $bilTH==0 and $bilA < $bilmp)
			$cntB++;
		if($bilC>0 and $bilD==0 and $bilE==0 and $bilTH==0)
			$cntC++;
		if($bilD>0 and $bilE==0 and $bilTH==0)
			$cntD++;
		if($bilE>0 and $bilTH==0 and $bilE < $bilmp)
			$cntE++;
		if($bilTH>0)
			$cntTH++;
		if($bilde==$bilmp)
			$cntDE++;
		if($bilTH==$bilmp)
			$cntTHALL++;
		//$jumbilA+=$bilA;
		$bilabc = (int) $cntA + (int) $cntB + (int) $cntC;
		$bilde = (int) $cntD + (int) $cntE;
		$peratuskuasai = peratus($bilabc,$bilambil);
	}//while tnilaisr
	
		$analisis = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E,SUM(AMBIL) AS AMBIL FROM analisis_mpsr WHERE KODSEK='$kodsek' AND TAHUN='$tahun' AND JPEP='$jenis' AND DARJAH='$ting' AND kelas='$kelas' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) GROUP BY KODSEK";
		$sr = oci_parse($conn_sispa,$analisis);
		oci_execute($sr);
		while($dataN=oci_fetch_array($sr)){
			$suma = $dataN["A"];	
			$sumb = $dataN["B"];
			$sumc = $dataN["C"];
			$sumd = $dataN["D"];
			$sume = $dataN["E"];
			$sumamb = $dataN["AMBIL"];
			$jumA+=$suma;
			$jumB+=$sumb;
			$jumC+=$sumc;
			$jumD+=$sumd;
			$jumE+=$sume;
			$jumAmbil+=$sumamb;
			$gpskelas = gpmpmrsr($suma,$sumb,$sumc,$sumd,$sume,$sumamb);
		}
	$bilcalondaftar+=$bilambil;
	$bilcalonA+=$cntA;
	$bilcalonB+=$cntB;
	$bilcalonC+=$cntC;
	$bilcalonD+=$cntD;
	$bilcalonE+=$cntE;
	$bilcalonTH+=$cntTH;
	$bilcalonDE+=$cntDE;
	$bilcalonTHALL+=$cntTHALL;
	$bilcalonabc+=$bilabc;
	$bilcalonde+=$bilde;
	//echo $sql_nilai;
	echo "<tr><td>$kelas $jumbilA</td>";
	if($bilambil<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ALL&kelas=$kelas' target='_blank'>$bilambil</a></td>";
	else
		echo "<td align='center'>$bilambil</td>";
	if($cntA<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=A&kelas=$kelas' target='_blank'>$cntA</a></td>";
	else
		echo "<td align='center'>$cntA</td>";
	if($cntB<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=B&kelas=$kelas' target='_blank'>$cntB</a></td>";
	else
		echo "<td align='center'>$cntB</td>";
	if($cntC<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=C&kelas=$kelas' target='_blank'>$cntC</a></td>";
	else
		echo "<td align='center'>$cntC</td>";
	if($cntD<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=D&kelas=$kelas' target='_blank'>$cntD</a></td>";
	else
		echo "<td align='center'>$cntD</td>";
	if($cntE<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=E&kelas=$kelas' target='_blank'>$cntE</a></td>";
	else
		echo "<td align='center'>$cntE</td>";
	if($cntTH<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=TH&kelas=$kelas' target='_blank'>$cntTH</a></td>";
	else
		echo "<td align='center'>$cntTH</td>";
	if($cntDE<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=DEALL&kelas=$kelas' target='_blank'>$cntDE</a></td>";
	else
		echo "<td align='center'>$cntDE</td>";
	if($cntTHALL<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=THALL&kelas=$kelas' target='_blank'>$cntTHALL</a></td>";
	else
		echo "<td align='center'>$cntTHALL</td>";
	if($bilabc<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ABC&kelas=$kelas' target='_blank'>$bilabc</a></td>";
	else
		echo "<td align='center'>$bilabc</td>";
	if($bilde<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=DE&kelas=$kelas' target='_blank'>$bilde</a></td>";
	else
		echo "<td align='center'>$bilde</td>";
	echo "<td align='right'>$peratuskuasai %</td>";
	echo "<td align='center'>$gpskelas</td></tr>";
}
	/*$analisisall = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E,SUM(AMBIL) AS AMBIL FROM analisis_mpsr WHERE TAHUN='$tahun' AND JPEP='$jenis' AND KODSEK='$kodsek' AND DARJAH='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) GROUP BY KODSEK";
	$sr2 = oci_parse($conn_sispa,$analisisall);
	oci_execute($sr2);
	while($dataN2=oci_fetch_array($sr2)){
		$juma = $dataN2["A"];	
		$jumb = $dataN2["B"];
		$jumc = $dataN2["C"];
		$jumd = $dataN2["D"];
		$jume = $dataN2["E"];
		$jumamb = $dataN2["AMBIL"];
	}*/
	echo "<tr bgcolor='#999999'><td>JUMLAH</td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ALL' target='_blank'>$bilcalondaftar</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=A' target='_blank'>$bilcalonA</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=B' target='_blank'>$bilcalonB</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=C' target='_blank'>$bilcalonC</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=D' target='_blank'>$bilcalonD</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=E' target='_blank'>$bilcalonE</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=TH' target='_blank'>$bilcalonTH</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=DEALL' target='_blank'>$bilcalonDE</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=THALL' target='_blank'>$bilcalonTHALL</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ABC' target='_blank'>$bilcalonabc</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=DE' target='_blank'>$bilcalonde</a></td>";
	echo "<td align='right'>".peratus($bilcalonabc,$bilcalondaftar)." %</td>";
	echo "<td align='center'>".gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil)."</td>";
	echo "</tr>";	
}
if($paparlaporan==0){
	//SMR
	$cols='14';
echo "<table width=\"1000\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td rowspan=\"3\"><div align=\"center\">Kelas</div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">Bil Calon <br>Daftar</div></td>\n";
echo "<td colspan=\"10\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">Peratus <br>ABCD</div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">GPS kelas</div></td>\n";
echo "</tr>\n";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td rowspan=\"2\" align='center'>A <br>Semua <br>MP</td>";
echo "<td colspan=\"5\" align='center'>Sekurangnya</td>";
echo "<td rowspan=\"2\" align='center'>E <br>Semua<br>MP</td>";
echo "<td rowspan=\"2\" align='center'>Tidak <br>Hadir <br>Semua</td>";
echo "<td colspan=\"2\" align='center'>MENGUASAI/TIDAK MENGUASAI</td>";
echo "</tr>";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td align='center'>1B</td>";
echo "<td align='center'>1C</td>";
echo "<td align='center'>1D</td>";
echo "<td align='center'>1E</td>";
echo "<td align='center'>1T</td>";
echo "<td align='center'>Menguasai<br>Semua<br>MP</td>";
echo "<td align='center'>Tidak Menguasai<br>Semua<br>MP</td>";
echo "</tr>";

$w_kelas="SELECT DISTINCT KELAS FROM sub_guru WHERE kodsek='$kodsek' and tahun='$tahun' AND ting='$ting'";//INDEX GREDUPSR2
//echo $w_kelas;
$w_kelas=oci_parse($conn_sispa,$w_kelas);
oci_execute($w_kelas);
while($data=oci_fetch_array($w_kelas)){
	$kelas = $data["KELAS"];//TNILAI  - TAHUN JPEP KODSEK DARJAH/TING
	$bilambil = count_row("select * from $tnilai where kodsek='$kodsek' and tahun='$tahun' AND jpep='$jenis' and $darjah='$ting' and kelas='$kelas'");
	$sql_nilai = "select * from $tnilai where tahun='$tahun' AND jpep='$jenis' AND kodsek='$kodsek' and $darjah='$ting' and kelas='$kelas'";
	//echo $sql_nilai."<br>";
	$res_nilai = oci_parse($conn_sispa,$sql_nilai);
	oci_execute($res_nilai);
	$cntA=0;$cntB=0;$cntC=0;$cntD=0;$cntE=0;$cntTH=0;$cntDE=0;$cntTHALL=0;$cntABC=0;$bilabc=0;$bilde=0;$gpskelas=0;$peratuskuasai=0;$cntEALL=0;$bilabcd=0;$bilmp=0;$bilA=0;$bilB=0;$bilC=0;$bilD=0;$bilE=0;$bilTH=0;$bileth=0;
	while($data_nilai = oci_fetch_array($res_nilai)){
		$bilmp = (int) $data_nilai["BILMP"];
		$bilA = (int) $data_nilai["BILA"];
		$bilB = (int) $data_nilai["BILB"];
		$bilC = (int) $data_nilai["BILC"];
		$bilD = (int) $data_nilai["BILD"];
		$bilE = (int) $data_nilai["BILE"];
		$bilTH = (int) $data_nilai["BILTH"];
		$pk_kelas = $data_nilai["KELAS"];
		if($bilA==$bilmp)//semua A
			$cntA++;
//		if($bilB>0 or ($bilB==0 and $bilA>0 and $bilA< $bilmp))//sekurangnya 1b
		if(($bilA>0 or $bilB>0) and $bilC==0 and $bilD==0 and $bilE==0 and $bilTH==0 and $bilA < $bilmp)
			$cntB++;
//		if($bilC>0 and $bilA==0 and $bilB==0)//sekurangnya 1c
		if($bilC>0 and $bilD==0 and $bilE==0 and $bilTH==0)
			$cntC++;
		if($bilD>0 and $bilE==0 and $bilTH==0)
//		if($bilD>0 and $bilA==0 and $bilB==0 and $bilC==0)//sekurangnya 1d
			$cntD++;
		if($bilE>0 and $bilTH==0 and $bilE < $bilmp)
//		if($bilE>0 and $bilA==0 and $bilB==0 and $bilC==0 and $bilD==0 and $bilE<$bilmp)//sekurangnya 1e
			$cntE++;
		if($bilTH>0)
//		if($bilTH>0 and $bilA==0 and $bilB==0 and $bilC==0 and $bilD==0 and $bilE==0)//sekurangnya 1th
			$cntTH++;
		if($bilE==$bilmp)//semua E
			$cntEALL++;
		if($bilTH==$bilmp)//tidak hadir semua
			$cntTHALL++;
		$bilabcd = (int) $cntA + (int) $cntB + (int) $cntC + (int) $cntD;//capai abcd
		$bileth = (int) $cntE + (int) $cntEALL;//tidak capai
		$peratuskuasai = peratus($bilabcd,$bilambil);
		$gpskelas = gpmpmrsr($cntA,$cntB,$cntC,$cntD,$cntE,$bilambil);
	}
	$analisis = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E,SUM(AMBIL) AS AMBIL FROM analisis_mpmr WHERE KODSEK='$kodsek' AND TAHUN='$tahun' AND JPEP='$jenis' AND TING='$ting' AND kelas='$kelas' AND KODMP NOT IN (SELECT KOD FROM SUB_MR_XAMBIL) GROUP BY KODSEK";
		$sr = oci_parse($conn_sispa,$analisis);
		oci_execute($sr);
		while($dataN=oci_fetch_array($sr)){
			$suma = $dataN["A"];	
			$sumb = $dataN["B"];
			$sumc = $dataN["C"];
			$sumd = $dataN["D"];
			$sume = $dataN["E"];
			$sumamb = $dataN["AMBIL"];
			$jumA+=$suma;
			$jumB+=$sumb;
			$jumC+=$sumc;
			$jumD+=$sumd;
			$jumE+=$sume;
			$jumAmbil+=$sumamb;
			$gpskelas = gpmpmrsr($suma,$sumb,$sumc,$sumd,$sume,$sumamb);
		}
	$bilcalondaftar+=$bilambil;
	$bilcalonA+=$cntA;
	$bilcalonB+=$cntB;
	$bilcalonC+=$cntC;
	$bilcalonD+=$cntD;
	$bilcalonE+=$cntE;
	$bilcalonTH+=$cntTH;
	$bilcalonEALL+=$cntEALL;
	$bilcalonTHALL+=$cntTHALL;
	$bilcalonabcd+=$bilabcd;
	$bilcaloneth+=$bileth;
	//echo $sql_nilai;
	echo "<tr><td>$kelas</td>";
	if($bilambil<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ALL&kelas=$kelas' target='_blank'>$bilambil</a></td>";
	else
		echo "<td align='center'>$bilambil</td>";
	if($cntA<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=A&kelas=$kelas' target='_blank'>$cntA</a></td>";
	else
		echo "<td align='center'>$cntA</td>";
	if($cntB<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=B&kelas=$kelas' target='_blank'>$cntB</a></td>";
	else
		echo "<td align='center'>$cntB</td>";
	if($cntC<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=C&kelas=$kelas' target='_blank'>$cntC</a></td>";
	else
		echo "<td align='center'>$cntC</td>";
	if($cntD<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=D&kelas=$kelas' target='_blank'>$cntD</a></td>";
	else
		echo "<td align='center'>$cntD</td>";
	if($cntE<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=E&kelas=$kelas' target='_blank'>$cntE</a></td>";
	else
		echo "<td align='center'>$cntE</td>";
	if($cntTH<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=TH&kelas=$kelas' target='_blank'>$cntTH</a></td>";
	else
		echo "<td align='center'>$cntTH</td>";
	if($cntEALL<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=EALL&kelas=$kelas' target='_blank'>$cntEALL</a></td>";
	else
		echo "<td align='center'>$cntEALL</td>";
	if($cntTHALL<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=THALL&kelas=$kelas' target='_blank'>$cntTHALL</a></td>";
	else
		echo "<td align='center'>$cntTHALL</td>";
	if($bilabcd<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ABCD&kelas=$kelas' target='_blank'>$bilabcd</a></td>";
	else
		echo "<td align='center'>$bilabcd</td>";
	if($bileth<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ETH&kelas=$kelas' target='_blank'>$bileth</a></td>";
	else
		echo "<td align='center'>$bileth</td>";
	echo "<td align='right'>$peratuskuasai %</td>";
	echo "<td align='center'>$gpskelas</td></tr>";
}
	/*$analisisall = "select sum(A) as A, SUM(B) as B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E,SUM(AMBIL) AS AMBIL FROM analisis_mpmr WHERE TAHUN='$tahun' AND JPEP='$jenis' AND KODSEK='$kodsek' AND TING='$ting' AND KODMP NOT IN (SELECT KOD FROM SUB_MR_XAMBIL) GROUP BY KODSEK";
	$sr2 = oci_parse($conn_sispa,$analisisall);
	oci_execute($sr2);
	while($dataN2=oci_fetch_array($sr2)){
		$juma = $dataN2["A"];	
		$jumb = $dataN2["B"];
		$jumc = $dataN2["C"];
		$jumd = $dataN2["D"];
		$jume = $dataN2["E"];
		$jumamb = $dataN2["AMBIL"];
	}*/
	echo "<tr bgcolor='#999999'><td>JUMLAH</td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ALL' target='_blank'>$bilcalondaftar</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=A' target='_blank'>$bilcalonA</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=B' target='_blank'>$bilcalonB</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=C' target='_blank'>$bilcalonC</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=D' target='_blank'>$bilcalonD</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=E' target='_blank'>$bilcalonE</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=TH' target='_blank'>$bilcalonTH</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=EALL' target='_blank'>$bilcalonEALL</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=THALL' target='_blank'>$bilcalonTHALL</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ABCD' target='_blank'>$bilcalonabcd</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ETH' target='_blank'>$bilcaloneth</a></td>";
	echo "<td align='right'>".peratus($bilcalonabcd,$bilcalondaftar)." %</td>";
	echo "<td align='center'>".gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil)."</td>";
	echo "</tr>";
}
if($paparlaporan==2){
	//SMA
	$cols='19';
echo "<table width=\"1100\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#ff9900\">";
echo "    <td rowspan=\"3\"><div align=\"center\">Kelas $kodmp</div></td>\n";
echo "    <td rowspan=\"3\"><div align=\"center\">Bil Calon Daftar</div></td>\n";
echo "    <td colspan=\"15\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";
echo "    <td rowspan=\"3\"><div align=\"center\">Peratus <br>Lulus</div></td>\n";
echo "    <td rowspan=\"3\"><div align=\"center\">GPS <br>Kelas</div></td>\n";
echo "  </tr>\n";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td rowspan=\"2\" align='center'>A+<br>Semua<br>MP</td>";
echo "<td colspan=\"10\" align='center'>Sekurangnya</td>";
echo "<td rowspan=\"2\" align='center'>G <br>Semua<br>MP</td>";
echo "<td rowspan=\"2\" align='center'>Tidak <br>Hadir <br>Semua</td>";
echo "<td colspan=\"2\" align='center'>Keputusan</td>";
echo "</tr>";
echo "<tr bgcolor=\"#ff9900\">";
echo "   <td> <div align=\"center\">1A</div></td>\n";
echo "   <td> <div align=\"center\">1A-</div></td>\n";
echo "   <td> <div align=\"center\">1B+</div></td>\n";
echo "   <td> <div align=\"center\">1B</div></td>\n";
echo "   <td> <div align=\"center\">1C+</div></td>\n";
echo "   <td> <div align=\"center\">1C</div></td>\n";
echo "   <td> <div align=\"center\">1D</div></td>\n";
echo "   <td> <div align=\"center\">1E</div></td>\n";
echo "   <td> <div align=\"center\">1G</div></td>\n";
echo "   <td> <div align=\"center\">1T</div></td>\n";
echo "   <td> <div align=\"center\">Lulus</div></td>\n";
echo "   <td> <div align=\"center\">Gagal</div></td>\n";
echo "</tr>";

$w_kelas="SELECT DISTINCT KELAS FROM sub_guru WHERE kodsek='$kodsek' AND tahun='$tahun' AND ting='$ting'";
$w_kelas=oci_parse($conn_sispa,$w_kelas);
oci_execute($w_kelas);
while($data=oci_fetch_array($w_kelas)){
	$kelas = $data["KELAS"];//TNILAI  - TAHUN JPEP KODSEK TING (INDEX)
	$bilambil = count_row("select * from $tnilai where kodsek='$kodsek' AND tahun='$tahun' AND jpep='$jenis' and $darjah='$ting' and kelas='$kelas'");
	$sql_nilai = "select * from $tnilai where tahun='$tahun' AND jpep='$jenis' AND kodsek='$kodsek' and $darjah='$ting' and kelas='$kelas'";
	//echo $sql_nilai."<br>";
	$res_nilai = oci_parse($conn_sispa,$sql_nilai);
	oci_execute($res_nilai);
	$cntAplus=0;$cntA=0;$cntAminus=0;$cntBplus=0;$cntB=0;$cntCplus=0;$cntC=0;$cntD=0;$cntE=0;$cntG=0;$cntTH=0;$cntEALL=0;$cntTHALL=0;$cntABC=0;$bilcapai=0;$biltakcapai=0;$gpskelas=0;$peratuskuasai=0;$bilmp=0;$bilAplus=0;$bilA=0;$bilAminus=0;$bilBplus=0;$bilB=0;$bilCplus=0;$bilC=0;$bilD=0;$bilE=0;$bilTH=0;$bilG=0;
	while($data_nilai = oci_fetch_array($res_nilai)){
		$bilmp = (int) $data_nilai["BILMP"];
		$bilAplus = (int) $data_nilai["BILAP"];
		$bilA = (int) $data_nilai["BILA"];
		$bilAminus = (int) $data_nilai["BILAM"];
		$bilBplus = (int) $data_nilai["BILBP"];
		$bilB = (int) $data_nilai["BILB"];
		$bilCplus = (int) $data_nilai["BILCP"];
		$bilC = (int) $data_nilai["BILC"];
		$bilD = (int) $data_nilai["BILD"];
		$bilE = (int) $data_nilai["BILE"];
		$bilTH = (int) $data_nilai["BILTH"];
		$bilG = (int) $data_nilai["BILG"];
		$bilde = (int) $bilD + (int) $bilE;
		if($bilAplus==$bilmp)
			$cntAplus++;
		if(($bilAplus>0 or $bilA>0) and $bilAminus==0 and $bilBplus==0 and $bilB==0 and $bilCplus==0 and $bilC==0 and $bilD==0 and $bilE==0 and $bilG==0 and $bilTH==0 and $bilAplus < $bilmp)//sekurangnya 1a
			$cntA++;
		if($bilAminus>0 and $bilBplus==0 and $bilB==0 and $bilCplus==0 and $bilC==0 and $bilD==0 and $bilE==0 and $bilG==0 and $bilTH==0)
			$cntAminus++;
		if($bilBplus>0 and $bilB==0 and $bilCplus==0 and $bilC==0 and $bilD==0 and $bilE==0 and $bilG==0 and $bilTH==0)//sekurangnya 1b+
			$cntBplus++;
		if($bilB>0 and $bilCplus==0 and $bilC==0 and $bilD==0 and $bilE==0 and $bilG==0 and $bilTH==0)//sekurangnya 1b
			$cntB++;
		if($bilCplus>0 and $bilC==0 and $bilD==0 and $bilE==0 and $bilG==0 and $bilTH==0)//sekurangnya 1c+
			$cntCplus++;
		if($bilC>0 and $bilD==0 and $bilE==0 and $bilG==0 and $bilTH==0)//sekurangnya 1c
			$cntC++;
		if($bilD>0 and $bilE==0 and $bilG==0 and $bilTH==0)//sekurangnya 1d
			$cntD++;
		if($bilE>0 and $bilG==0 and $bilTH==0 and $bilE<$bilmp)//sekurangnya 1e
			$cntE++;
		if($bilG>0 and $bilTH==0)//sekurangnya 1g
			$cntG++;
		if($bilTH>0)// and $bilAplus==0 and $bilA==0 and $bilAminus==0 and $bilBplus==0 and $bilB==0 and $bilCplus==0 and $bilC==0 and $bilD==0 and $bilE==0 and $bilG==0)//sekurangnya 1th
			$cntTH++;
		if($bilE==$bilmp)
			$cntEALL++;
		if($bilTH==$bilmp)
			$cntTHALL++;
		$bilcapai = (int) $cntAplus + (int) $cntA +(int) $cntAminus + (int) $cntBplus + (int) $cntB + (int) $cntCplus + (int) $cntC + (int) $cntD + (int) $cntE + (int) $cntEALL;
		$biltakcapai = (int) $cntG;
		$peratuskuasai = peratus($bilcapai,$bilambil);
		//$gpskelas = gpmpma($cntAplus,$cntA,$cntAminus,$cntBplus,$cntB,$cntCplus,$cntC,$cntD,$cntE,$cntG,$bilambil);
		//echo "$kelas $cntAplus,$cntA,$cntAminus,$cntBplus,$cntB,$cntCplus,$cntC,$cntD,$cntE,$bilG,$bilambil<br>";
	}
	$sqltnilai = "select sum(AP) as AP, SUM(A) AS A, SUM(AM) AS AM, SUM(BP) AS BP, SUM(B) as B, SUM(CP) AS CP,SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(G) AS G, SUM(AMBIL) AS AMBIL FROM analisis_mpma WHERE KODSEK='$kodsek' AND TAHUN='$tahun' AND JPEP='$jenis' AND  TING='$ting' AND kelas='$kelas' AND KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL) GROUP BY KODSEK";
	$sr3 = oci_parse($conn_sispa,$sqltnilai);
	oci_execute($sr3);
	while($dataN=oci_fetch_array($sr3)){
		$bilAP = (int) $dataN["AP"];
		$bilA = (int) $dataN["A"];
		$bilAM = (int) $dataN["AM"];
		$bilBP = (int) $dataN["BP"];
		$bilB = (int) $dataN["B"];
		$bilCP = (int) $dataN["CP"];
		$bilC = (int) $dataN["C"];
		$bilD = (int) $dataN["D"];
		$bilE = (int) $dataN["E"];
		$bilG = (int) $dataN["G"];
		$bilamb = (int) $dataN["AMBIL"];
		$jumAP+=$bilAP;
		$jumA+=$bilA;
		$jumAM+=$bilAM;
		$jumBP+=$bilBP;
		$jumB+=$bilB;
		$jumCP+=$bilCP;
		$jumC+=$bilC;
		$jumD+=$bilD;
		$jumE+=$bilE;
		$jumG+=$bilG;
		$jumAmbil+=$bilamb;	
//		gpmpma($jumAP,$jumA,$jumAM,$jumBP,$jumB,$jumCP,$jumC,$jumD,$jumE,$jumG,$jumAmbil);
		$gpskelas =  gpmpma($bilAP, $bilA, $bilAM, $bilBP, $bilB, $bilCP, $bilC, $bilD, $bilE, $bilG, $bilamb);
	}
		
	$bilcalondaftar+=$bilambil;
	$bilcalonAplus+=$cntAplus;
	$bilcalonA+=$cntA;
	$bilcalonAminus+=$cntAminus;
	$bilcalonBplus+=$cntBplus;
	$bilcalonB+=$cntB;
	$bilcalonC+=$cntC;
	$bilcalonCplus+=$cntCplus;
	$bilcalonD+=$cntD;
	$bilcalonE+=$cntE;
	$bilcalonG+=$cntG;
	$bilcalonTH+=$cntTH;
	$bilcalonEALL+=$cntEALL;
	$bilcalonTHALL+=$cntTHALL;
	$bilcaloncapai+=$bilcapai;
	$bilcalontakcapai+=$biltakcapai;
	echo "<tr><td>$kelas</td>";
	if($bilambil<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ALL&kelas=$kelas' target='_blank'>$bilambil</a></td>";
	else
		echo "<td align='center'>$bilambil</td>";
	if($cntAplus<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=AP&kelas=$kelas' target='_blank'>$cntAplus</a></td>";
	else
		echo "<td align='center'>$cntAplus</td>";
	if($cntA<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=A&kelas=$kelas' target='_blank'>$cntA</a></td>";
	else
		echo "<td align='center'>$cntA</td>";
	if($cntAminus<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=AM&kelas=$kelas' target='_blank'>$cntAminus</a></td>";
	else
		echo "<td align='center'>$cntAminus</td>";
	if($cntBplus<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=BP&kelas=$kelas' target='_blank'>$cntBplus</a></td>";
	else
		echo "<td align='center'>$cntBplus</td>";
	if($cntB<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=B&kelas=$kelas' target='_blank'>$cntB</a></td>";
	else
		echo "<td align='center'>$cntB</td>";
	if($cntCplus<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=CP&kelas=$kelas' target='_blank'>$cntCplus</a></td>";
	else
		echo "<td align='center'>$cntCplus</td>";
	if($cntC<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=C&kelas=$kelas' target='_blank'>$cntC</a></td>";
	else
		echo "<td align='center'>$cntC</td>";
	if($cntD<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=D&kelas=$kelas' target='_blank'>$cntD</a></td>";
	else
		echo "<td align='center'>$cntD</td>";
	if($cntE<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=E&kelas=$kelas' target='_blank'>$cntE</a></td>";
	else
		echo "<td align='center'>$cntE</td>";
	if($cntG<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=G&kelas=$kelas' target='_blank'>$cntG</a></td>";
	else
		echo "<td align='center'>$cntG</td>";
	if($cntTH<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=TH&kelas=$kelas' target='_blank'>$cntTH</a></td>";
	else
		echo "<td align='center'>$cntTH</td>";
	if($cntEALL<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=EALL&kelas=$kelas' target='_blank'>$cntEALL</a></td>";
	else
		echo "<td align='center'>$cntEALL</td>";
	if($cntTHALL<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=THALL&kelas=$kelas' target='_blank'>$cntTHALL</a></td>";
	else
		echo "<td align='center'>$cntTHALL</td>";
	if($bilcapai<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ABCDE&kelas=$kelas' target='_blank'>$bilcapai</a></td>";
	else
		echo "<td align='center'>$bilcapai</td>";
	if($biltakcapai<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=GTH&kelas=$kelas' target='_blank'>$biltakcapai</a></td>";
	else
		echo "<td align='center'>$biltakcapai</td>";
	echo "<td align='right'>$peratuskuasai %</td>";
	echo "<td align='center'>$gpskelas</td></tr>";
}	
	echo "<tr bgcolor='#999999'><td>JUMLAH</td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ALL' target='_blank'>$bilcalondaftar</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=AP' target='_blank'>$bilcalonAplus</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=A' target='_blank'>$bilcalonA</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=AM' target='_blank'>$bilcalonAminus</a></td>";	
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=BP' target='_blank'>$bilcalonBplus</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=B' target='_blank'>$bilcalonB</a></td>";	
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=CP' target='_blank'>$bilcalonCplus</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=C' target='_blank'>$bilcalonC</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=D' target='_blank'>$bilcalonD</a></td>";	
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=E' target='_blank'>$bilcalonE</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=G' target='_blank'>$bilcalonG</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=TH' target='_blank'>$bilcalonTH</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=EALL' target='_blank'>$bilcalonEALL</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=THALL' target='_blank'>$bilcalonTHALL</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=ABCDE' target='_blank'>$bilcaloncapai</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl-jpn.php?data=GTH' target='_blank'>$bilcalontakcapai</a></td>";
	echo "<td align='right'>".peratus($bilcaloncapai,$bilcalondaftar)." %</td>";
	echo "<td align='center'>".gpmpma($jumAP,$jumA,$jumAM,$jumBP,$jumB,$jumCP,$jumC,$jumD,$jumE,$jumG,$jumAmbil)."</td>";
	echo "</tr>";
}
//echo "<br>";
//echo "<table width=\"600\"  border=\"1\" align=\"left\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr><td colspan='$cols'>&nbsp;</td></tr>";
echo "<tr bgcolor=\"#ff9900\"><td colspan='$cols'><div align=\"Left\">Nota</div></td></tr>";
echo "<tr><td colspan='$cols'><div align=\"Left\">Sila pastikan SU Peperiksaan sekolah anda telah melakukan Proses Markah Peperiksaan untuk mendapatkan data yang terkini.</div></td></tr>";
echo "</table>";
echo "<br><br>";
?>