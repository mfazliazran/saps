<?php
include 'auth.php';
include 'config.php';
include 'fungsi.php';
include 'fungsikira.php';
include "input_validation.php";
?>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<td valign="top" class="rightColumn">
<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>

<?php
$kodsek = $_SESSION['kodsek'];
$tahun = $_SESSION['tahun'];

$ting = validate($_POST['ting']);
$jenis = validate($_POST['jenis']);

if($ting=="" and $jenis==""){
	$ting = validate($_GET["ting"]);
	$kodsek = validate($_GET["kodsek"]);
	$jenis = validate($_GET["jenis"]);

	$_SESSION["kodseko"] = $kodsek;

	if($_SESSION["ses_tahun"]<>"")
		$tahun = $_SESSION["ses_tahun"];
	else
		$tahun = date("Y");
}

$_SESSION["pk_ting"] = $ting;
$_SESSION["pk_jenis"] = $jenis;

switch ($ting){
	case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
		$jensekolah = "SEKOLAH RENDAH";
		$tnilai = "tnilai_sr";
		$darjah = "darjah";
		$paparlaporan = 1;
		$tingdarjah = "DARJAH";
		break;
	case "P": case "T1": case "T2": case "T3":
		$jensekolah = "SEKOLAH MENENGAH";
		$tnilai = "tnilai_smr";
		$darjah = "ting";
		$paparlaporan = 0;
		$tingdarjah = "TINGKATAN";
		break;
	case "T4": case "T5":
		$jensekolah = "SEKOLAH MENENGAH";
		$tnilai = "tnilai_sma";
		$darjah = "ting";
		$paparlaporan = 2;
		$tingdarjah = "TINGKATAN";
		break;
}

$q_sql="SELECT namasek,lencana FROM tsekolah WHERE kodsek= :kodsek";
$q_sql=oci_parse ($conn_sispa,$q_sql);
oci_bind_by_name($q_sql, ':kodsek', $kodsek);
oci_execute($q_sql);
$row = oci_fetch_array($q_sql);
$namasek = $row["NAMASEK"];
$lencana = $row["LENCANA"];
$periksa = jpep ($jenis);

if (isset($lencana)){
	echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
}

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

echo "<table width=\"1000\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td rowspan=\"3\"><div align=\"center\">Kelas $kodmp</div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">Bil Calon <br>Daftar</div></td>\n";
if($tahun<=2014){
	$cols='14';
	echo "<td colspan=\"10\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";
}elseif($tahun==2015){
	if($ting=='D6'){
		$cols='14';
		echo "<td colspan=\"10\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";	
	}else{
		$cols='15';
		echo "<td colspan=\"11\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";	
	}
}else{
	$cols='14';
	echo "<td colspan=\"10\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";
}
echo "<td rowspan=\"3\"><div align=\"center\">Peratus <br>Menguasai</div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">GPS kelas</div></td>\n";
echo "</tr>\n";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td rowspan=\"2\" align='center'>Semua A</td>";
if($tahun<=2014){
	echo "<td colspan=\"5\" align='center'>Sekurangnya</td>";
	echo "<td rowspan=\"2\" align='center'>Semua D E</td>";
}elseif($tahun==2015){
	if($ting=='D6'){
		echo "<td colspan=\"5\" align='center'>Sekurangnya</td>";
		echo "<td rowspan=\"2\" align='center'>Semua D E</td>";
	}else{
		echo "<td colspan=\"6\" align='center'>Sekurangnya</td>";
		echo "<td rowspan=\"2\" align='center'>Semua F</td>";
	}
}else{
	echo "<td colspan=\"5\" align='center'>Sekurangnya</td>";
	echo "<td rowspan=\"2\" align='center'>Semua E</td>";
}
echo "<td rowspan=\"2\" align='center'>Tidak Hadir <br>Semua</td>";
echo "<td colspan=\"2\" align='center'>Bil Calon</td>";
echo "</tr>";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td align='center'>1B</td>";
echo "<td align='center'>1C</td>";
echo "<td align='center'>1D</td>";
echo "<td align='center'>1E</td>";
if($tahun==2015){
	if($ting!='D6'){
		echo "<td align='center'>1F</td>";
	}
}
echo "<td align='center'>1T</td>";
if($tahun<=2014){
	echo "<td align='center'>A B C</td>";
	echo "<td align='center'>D E</td>";
}elseif($tahun==2015){
	if($ting=='D6'){
		echo "<td align='center'>A B C</td>";
		echo "<td align='center'>D E</td>";	
	}else{
		echo "<td align='center'>A B C D E</td>";
		echo "<td align='center'>F</td>";	
	}
}else{
	echo "<td align='center'>A B C D</td>";
	echo "<td align='center'>E</td>";
}
echo "</tr>";
$w_kelas="SELECT DISTINCT KELAS FROM sub_guru WHERE kodsek= :kodsek and tahun='$tahun' AND ting= :ting";
$w_kelas=oci_parse($conn_sispa,$w_kelas);
oci_bind_by_name($w_kelas, ':kodsek', $kodsek);
oci_bind_by_name($w_kelas, ':ting', $ting);
oci_execute($w_kelas);
while($data=oci_fetch_array($w_kelas)){
	$kelas = $data["KELAS"];

	$sqlambil = "SELECT * FROM $tnilai WHERE kodsek= :kodsek AND tahun='$tahun' AND jpep= :jenis AND $darjah= :ting AND kelas='$kelas'";
	$parameter = array(":kodsek",":jenis",":ting");
	$value = array($kodsek,$jenis,$ting);
	$bilambil = kira_bil_rekod($sqlambil,$parameter,$value);

	$res_nilai = oci_parse($conn_sispa,$sqlambil);
	oci_bind_by_name($res_nilai, ':kodsek', $kodsek);
	oci_bind_by_name($res_nilai, ':jenis', $jenis);
	oci_bind_by_name($res_nilai, ':ting', $ting);
	oci_execute($res_nilai);
	
	$cntA=0;$cntB=0;$cntC=0;$cntD=0;$cntE=$bilex=0;
	$cntF=0;$cntABCDE=0;$bilabcde=0;$cntFALL=$bilabcd=$cntEALL=0;
	$cntTH=0;$cntDE=0;$cntTHALL=0;$cntABC=0;$bilabc=0;
	$bilde=$bile=0;$gpskelas=0;$peratuskuasai=0;$lulus=0;$gagal=0;
	$cuma=$sumb=$sumc=$sumd=$sume=$sumamb=0;$juma=$jumb=$jumc=$jumd=$jume=$jumf=$jumamb=0;
	while($data_nilai = oci_fetch_array($res_nilai)){
		$bilmp = $data_nilai["BILMP"];
		$bilA = $data_nilai["BILA"];
		$bilB = $data_nilai["BILB"];
		$bilC = $data_nilai["BILC"];
		$bilD = $data_nilai["BILD"];
		$bilE = $data_nilai["BILE"];
		$bilF = $data_nilai["BILF"];
		$bilTH = $data_nilai["BILTH"];
		$pk_kelas = $data_nilai["KELAS"];
		$keputusan = $data_nilai["KEPUTUSAN"];
		
		$bile = (int) $bilE;
		$bilde = (int) $bilD + (int) $bilE;
		
		if ($keputusan == "LULUS"){
			$lulus++;
		}
		else {
			$gagal++;
		}
		if($bilA==$bilmp)
			$cntA++;
		if(($bilA>0 or $bilB>0) and $bilC==0 and $bilD==0 and $bilE==0 and $bilF==0 and $bilTH==0 and $bilA < $bilmp)
			$cntB++;
		if($bilC>0 and $bilD==0 and $bilE==0 and $bilF==0 and $bilTH==0)
			$cntC++;
		if($bilD>0 and $bilE==0 and $bilF==0 and $bilTH==0)
			$cntD++;
		if($bilE>0 and $bilF==0 and $bilTH==0 and $bilE <= $bilmp)
			$cntE++;
		if($bilF>0 and $bilTH==0 and $bilF <= $bilmp)
			$cntF++;
		if($bilTH>0)
			$cntTH++;
		if($bilde==$bilmp)
			$cntDE++;
		if($bile==$bilmp)
			$cntEALL++;
		if($bilTH==$bilmp)
			$cntTHALL++;
		if($bilF==$bilmp)
			$cntFALL++;

		$bilabc = (int) $cntA + (int) $cntB + (int) $cntC;
		$bilabcd = (int) $cntA + (int) $cntB + (int) $cntC + (int) $cntD;
		$bilde = (int) $cntD + (int) $cntE;
		$bilabcde = (int) $cntA + (int) $cntB + (int) $cntC + (int) $cntD + (int) $cntE;
		$bilfx = (int) $cntF;
		$bilex = (int) $cntE;
		
		if($tahun<=2014){
			$peratuskuasai = peratus($bilabc,$bilambil);
		}elseif($tahun==2015){
			if($ting=='D6'){
				$peratuskuasai = peratus($bilabc,$bilambil);
			}else{
				$peratuskuasai = peratus($bilabcde,$bilambil);
			}
		}else{
			$peratuskuasai = peratus($bilabcd,$bilambil);
		}
	}
	
		$analisis = "SELECT sum(A) AS A, SUM(B) AS B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(F) AS F, SUM(AMBIL) AS AMBIL FROM analisis_mpsr WHERE KODSEK= :kodsek AND TAHUN='$tahun' AND JPEP= :jenis AND DARJAH= :ting AND kelas='$kelas' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) GROUP BY KODSEK";
		$sr = oci_parse($conn_sispa,$analisis);
		oci_bind_by_name($sr, ':kodsek', $kodsek);
		oci_bind_by_name($sr, ':jenis', $jenis);
		oci_bind_by_name($sr, ':ting', $ting);
		oci_execute($sr);
		while($dataN=oci_fetch_array($sr)){
			$suma = $dataN["A"];	
			$sumb = $dataN["B"];
			$sumc = $dataN["C"];
			$sumd = $dataN["D"];
			$sume = $dataN["E"];
			$sumf = $dataN["F"];
			$sumamb = $dataN["AMBIL"];
			
			$jumA+=$suma;
			$jumB+=$sumb;
			$jumC+=$sumc;
			$jumD+=$sumd;
			$jumE+=$sume;
			$jumF+=$sumf;
			$jumAmbil+=$sumamb;
			if($tahun<=2014){
				$gpskelas = gpmpmrsr($suma,$sumb,$sumc,$sumd,$sume,$sumamb);
			}elseif($tahun==2015){
				if($ting=='D6'){
					$gpskelas = gpmpmrsr($suma,$sumb,$sumc,$sumd,$sume,$sumamb);
				}else{
					$gpskelas = gpmpmrsr_baru($suma,$sumb,$sumc,$sumd,$sume,$sumf,$sumamb);
				}
			}else{
				$gpskelas = gpmpmrsr($suma,$sumb,$sumc,$sumd,$sume,$sumamb);
			}
		}
	$bilcalondaftar+=$bilambil;
	$bilcalonA+=$cntA;
	$bilcalonB+=$cntB;
	$bilcalonC+=$cntC;
	$bilcalonD+=$cntD;
	$bilcalonE+=$cntE;
	$bilcalonF+=$cntF;
	$bilcalonTH+=$cntTH;
	$bilcalonDE+=$cntDE;
	$bilcalonEALL+=$cntEALL;
	$bilcalonTHALL+=$cntTHALL;
	$bilcalonFALL+=$cntFALL;
	$bilcalonabc+=$bilabc;
	$bilcalonabcd+=$bilabcd;
	$bilcalonabcde+=$bilabcde;
	$bilcalonde+=$bilde;
	$bilcalonf+=$bilfx;
	$bilcalone+=$bilex;

	echo "<tr><td>$kelas</td>";
	if($bilambil<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ALL&kelas=$kelas' target='_blank'>$bilambil</a></td>";
	else
		echo "<td align='center'>$bilambil</td>";
	if($cntA<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=A&kelas=$kelas' target='_blank'>$cntA</a></td>";
	else
		echo "<td align='center'>$cntA</td>";
	if($cntB<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=B&kelas=$kelas' target='_blank'>$cntB</a></td>";
	else
		echo "<td align='center'>$cntB</td>";
	if($cntC<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=C&kelas=$kelas' target='_blank'>$cntC</a></td>";
	else
		echo "<td align='center'>$cntC</td>";
	if($cntD<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=D&kelas=$kelas' target='_blank'>$cntD</a></td>";
	else
		echo "<td align='center'>$cntD</td>";
	if($cntE<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=E&kelas=$kelas' target='_blank'>$cntE</a></td>";
	else
		echo "<td align='center'>$cntE</td>";

	if($tahun==2015){
		if($ting!='D6'){
			if($cntF<>0)
				echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=F&kelas=$kelas' target='_blank'>$cntF</a></td>";
			else
				echo "<td align='center'>$cntF</td>";
		}
	}
	
	if($cntTH<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=TH&kelas=$kelas' target='_blank'>$cntTH</a></td>";
	else
		echo "<td align='center'>$cntTH</td>";

	if($tahun<=2014){
		if($cntDE<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=DEALL&kelas=$kelas' target='_blank'>$cntDE</a></td>";
		else
			echo "<td align='center'>$cntDE</td>";	
	}elseif($tahun==2015){
		if($ting=='D6'){
			if($cntDE<>0)
				echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=DEALL&kelas=$kelas' target='_blank'>$cntDE</a></td>";
			else
				echo "<td align='center'>$cntDE</td>";	
		}
	}else{
		if($cntEALL<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=EALL&kelas=$kelas' target='_blank'>$cntEALL</a></td>";
		else
			echo "<td align='center'>$cntEALL</td>";
	}

	if($tahun==2015){
		if($ting!='D6'){
			if($cntFALL<>0)
				echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=FALL&kelas=$kelas' target='_blank'>$cntFALL</a></td>";
			else
				echo "<td align='center'>$cntFALL</td>";	
		}
	}

	if($cntTHALL<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=THALL&kelas=$kelas' target='_blank'>$cntTHALL</a></td>";
	else
		echo "<td align='center'>$cntTHALL</td>";

	if($tahun<=2014){
		if($bilabc<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABC&kelas=$kelas' target='_blank'>$bilabc</a></td>";
		else
			echo "<td align='center'>$bilabc</td>";
		if($bilde<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=DE&kelas=$kelas' target='_blank'>$bilde</a></td>";
		else
			echo "<td align='center'>$bilde</td>";
	}elseif($tahun==2015){
		if($ting=='D6'){
			if($bilabc<>0)
				echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABC&kelas=$kelas' target='_blank'>$bilabc</a></td>";
			else
				echo "<td align='center'>$bilabc</td>";
			if($bilde<>0)
				echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=DE&kelas=$kelas' target='_blank'>$bilde</a></td>";
			else
				echo "<td align='center'>$bilde</td>";
		}else{
			if($bilabcde<>0)
				echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCDE&kelas=$kelas' target='_blank'>$bilabcde</a></td>";
			else
				echo "<td align='center'>$bilabc</td>";
			if($bilfx<>0)
				echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=F&kelas=$kelas' target='_blank'>$bilfx</a></td>";
			else
				echo "<td align='center'>$bilfx</td>";
		}		
	}else{
		if($bilabcd<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCD&kelas=$kelas' target='_blank'>$bilabcd</a></td>";
		else
			echo "<td align='center'>$bilabc</td>";
		if($bilex<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=E&kelas=$kelas' target='_blank'>$bilex</a></td>";
		else
			echo "<td align='center'>$bilex</td>";
	}
	echo "<td align='right'>$peratuskuasai %</td>";
	echo "<td align='center'>$gpskelas</td></tr>";
}
	echo "<tr bgcolor='#999999'><td>JUMLAH</td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ALL' target='_blank'>$bilcalondaftar</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=A' target='_blank'>$bilcalonA</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=B' target='_blank'>$bilcalonB</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=C' target='_blank'>$bilcalonC</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=D' target='_blank'>$bilcalonD</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=E' target='_blank'>$bilcalonE</a></td>";
	if($tahun==2015){
		if($ting!='D6'){
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=F' target='_blank'>$bilcalonF</a></td>";
		}		
	}
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=TH' target='_blank'>$bilcalonTH</a></td>";

	if($tahun<=2014){
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=DEALL' target='_blank'>$bilcalonDE</a></td>";
	}elseif($tahun==2015){
		if($ting=='D6'){
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=DEALL' target='_blank'>$bilcalonDE</a></td>";	
		}else{
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=FALL' target='_blank'>$bilcalonFALL</a></td>";	
		}
	}else{
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=EALL' target='_blank'>$bilcalonEALL</a></td>";
	}
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=THALL' target='_blank'>$bilcalonTHALL</a></td>";
	if($tahun<=2014){
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABC' target='_blank'>$bilcalonabc</a></td>";
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=DE' target='_blank'>$bilcalonde</a></td>";
		echo "<td align='right'>".peratus($bilcalonabc,$bilcalondaftar)." %</td>";
		echo "<td align='center'>".gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil)."</td>";
	}elseif($tahun==2015){
		if($ting=='D6')	{
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABC' target='_blank'>$bilcalonabc</a></td>";
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=DE' target='_blank'>$bilcalonde</a></td>";	
			echo "<td align='right'>".peratus($bilcalonabc,$bilcalondaftar)." %</td>";
			echo "<td align='center'>".gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil)."</td>";
		}else{
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCDE' target='_blank'>$bilcalonabcde</a></td>";
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=F' target='_blank'>$bilcalonf</a></td>";
			echo "<td align='right'>".peratus($bilcalonabcde,$bilcalondaftar)." %</td>";
			echo "<td align='center'>".gpmpmrsr_baru($jumA,$jumB,$jumC,$jumD,$jumE,$jumF,$jumAmbil)."</td>";
		}
	}else{
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCD' target='_blank'>$bilcalonabcd</a></td>";
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=E' target='_blank'>$bilcalone</a></td>";
		echo "<td align='right'>".peratus($bilcalonabcd,$bilcalondaftar)." %</td>";
		echo "<td align='center'>".gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil)."</td>";
	}
	echo "</tr>";	
}
if($paparlaporan==0){

echo "<table width=\"1000\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td rowspan=\"3\"><div align=\"center\">Kelas</div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">Bil Calon <br>Daftar</div></td>\n";

if($tahun<=2014){
	$cols='14';
	echo "<td colspan=\"10\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";
}else{
	$cols='15';
	echo "<td colspan=\"11\"><div align=\"center\">Bilangan Calon Mengikut Kategori </div></td>\n";
}
echo "<td rowspan=\"3\"><div align=\"center\">Peratus <br>ABCD</div></td>\n";
echo "<td rowspan=\"3\"><div align=\"center\">GPS kelas</div></td>\n";
echo "</tr>\n";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td rowspan=\"2\" align='center'>A <br>Semua <br>MP</td>";
if($tahun<=2014){
	echo "<td colspan=\"5\" align='center'>Sekurangnya</td>";
	echo "<td rowspan=\"2\" align='center'>E <br>Semua<br>MP</td>";
}else{
	echo "<td colspan=\"6\" align='center'>Sekurangnya</td>";
	echo "<td rowspan=\"2\" align='center'>F <br>Semua<br>MP</td>";
}
echo "<td rowspan=\"2\" align='center'>Tidak <br>Hadir <br>Semua</td>";
echo "<td colspan=\"2\" align='center'>MENGUASAI/TIDAK MENGUASAI</td>";
echo "</tr>";
echo "<tr bgcolor=\"#ff9900\">";
echo "<td align='center'>1B</td>";
echo "<td align='center'>1C</td>";
echo "<td align='center'>1D</td>";
echo "<td align='center'>1E</td>";
if($tahun>=2015){
	echo "<td align='center'>1F</td>";
}
echo "<td align='center'>1T</td>";
echo "<td align='center'>Menguasai<br>Semua<br>MP</td>";
echo "<td align='center'>Tidak Menguasai<br>Semua<br>MP</td>";
echo "</tr>";

$w_kelas="SELECT DISTINCT KELAS FROM sub_guru WHERE kodsek= :kodsek and tahun='$tahun' AND ting= :ting";
$w_kelas=oci_parse($conn_sispa,$w_kelas);
oci_bind_by_name($w_kelas, ':kodsek', $kodsek);
oci_bind_by_name($w_kelas, ':ting', $ting);
oci_execute($w_kelas);
while($data=oci_fetch_array($w_kelas)){
	$kelas = $data["KELAS"];
	$sqlambil = "SELECT * FROM $tnilai WHERE kodsek= :kodsek AND tahun='$tahun' AND jpep= :jenis AND $darjah= :ting AND kelas='$kelas'";
	$parameter = array(":kodsek",":jenis",":ting");
	$value = array($kodsek,$jenis,$ting);
	$bilambil = kira_bil_rekod($sqlambil,$parameter,$value);

	$res_nilai = oci_parse($conn_sispa,$sqlambil);
	oci_bind_by_name($res_nilai, ':kodsek', $kodsek);
	oci_bind_by_name($res_nilai, ':jenis', $jenis);
	oci_bind_by_name($res_nilai, ':ting', $ting);
	oci_execute($res_nilai);

	$cntA=0;$cntB=0;$cntC=0;$cntD=0;$cntE=0;$cntTH=0;$cntF=0;
	$cntDE=0;$cntTHALL=0;$cntABC=0;$bilabc=0;$bilde=0;$gpskelas=0;$peratuskuasai=0;$cntEALL=0;$cntFALL=0;
	$bilabcd=0;$bilmp=0;$bilA=0;$bilB=0;$bilC=0;$bilD=0;$bilE=0;$bilTH=0;$bileth=0;$bilF=0;
	while($data_nilai = oci_fetch_array($res_nilai)){
		$bilmp = (int) $data_nilai["BILMP"];
		$bilA = (int) $data_nilai["BILA"];
		$bilB = (int) $data_nilai["BILB"];
		$bilC = (int) $data_nilai["BILC"];
		$bilD = (int) $data_nilai["BILD"];
		$bilE = (int) $data_nilai["BILE"];
		$bilF = (int) $data_nilai["BILF"];
		$bilTH = (int) $data_nilai["BILTH"];
		$pk_kelas = $data_nilai["KELAS"];
		if($bilA==$bilmp)//semua A
			$cntA++;
		//sekurangnya 1b
		if(($bilA>0 or $bilB>0) and $bilC==0 and $bilD==0 and $bilE==0 and $bilF==0 and $bilTH==0 and $bilA < $bilmp)
			$cntB++;
		//sekurangnya 1c
		if($bilC>0 and $bilD==0 and $bilE==0 and $bilF==0 and $bilTH==0)
			$cntC++;
		//sekurangnya 1d
		if($bilD>0 and $bilE==0 and $bilF==0 and $bilTH==0)
			$cntD++;
		//sekurangnya 1e
		if($bilE>0 and $bilF==0 and $bilTH==0 and $bilE < $bilmp)
			$cntE++;
		//sekurangnya 1f
		if($bilF>0 and $bilTH==0 and $bilF < $bilmp)
			$cntF++;
		//sekurangnya 1th
		if($bilTH>0)
			$cntTH++;
		//semua E
		if($bilE==$bilmp)
			$cntEALL++;
		//semua F
		if($bilF==$bilmp)
			$cntFALL++;
		//tidak hadir semua
		if($bilTH==$bilmp)
			$cntTHALL++;
		
		$bilabcd = (int) $cntA + (int) $cntB + (int) $cntC + (int) $cntD;//capai abcd
		$bilabcde = (int) $cntA + (int) $cntB + (int) $cntC + (int) $cntD + (int) $cntE;//capai abcde
		$bileth = (int) $cntE + (int) $cntEALL;//tidak capai
		$bilfth = (int) $cntF + (int) $cntFALL;//tidak capai
		if($tahun<=2014){
			$peratuskuasai = peratus($bilabcd,$bilambil);
			$gpskelas = gpmpmrsr($cntA,$cntB,$cntC,$cntD,$cntE,$bilambil);
		}else{
			$peratuskuasai = peratus($bilabcde,$bilambil);
			$gpskelas = gpmpmrsr_baru($cntA,$cntB,$cntC,$cntD,$cntE,$cntF,$bilambil);
		}
	}
	$analisis = "SELECT sum(A) AS A, SUM(B) AS B, SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(F) AS F, SUM(AMBIL) AS AMBIL FROM analisis_mpmr WHERE KODSEK= :kodsek AND TAHUN='$tahun' AND JPEP= :jenis AND TING= :ting AND kelas='$kelas' AND KODMP NOT IN (SELECT KOD FROM SUB_MR_XAMBIL) GROUP BY KODSEK";
		$sr = oci_parse($conn_sispa,$analisis);
		oci_bind_by_name($sr, ':kodsek', $kodsek);
		oci_bind_by_name($sr, ':jenis', $jenis);
		oci_bind_by_name($sr, ':ting', $ting);
		oci_execute($sr);
		while($dataN=oci_fetch_array($sr)){
			$suma = $dataN["A"];	
			$sumb = $dataN["B"];
			$sumc = $dataN["C"];
			$sumd = $dataN["D"];
			$sume = $dataN["E"];
			$sumf = $dataN["F"];
			$sumamb = $dataN["AMBIL"];
			$jumA+=$suma;
			$jumB+=$sumb;
			$jumC+=$sumc;
			$jumD+=$sumd;
			$jumE+=$sume;
			$jumF+=$sumf;
			$jumAmbil+=$sumamb;
			if($tahun<=2014){
				$gpskelas = gpmpmrsr($suma,$sumb,$sumc,$sumd,$sume,$sumamb);
			}else{
				$gpskelas = gpmpmrsr_baru($suma,$sumb,$sumc,$sumd,$sume,$sumf,$sumamb);
			}
		}
	$bilcalondaftar+=$bilambil;
	$bilcalonA+=$cntA;
	$bilcalonB+=$cntB;
	$bilcalonC+=$cntC;
	$bilcalonD+=$cntD;
	$bilcalonE+=$cntE;
	$bilcalonF+=$cntF;
	$bilcalonTH+=$cntTH;
	$bilcalonEALL+=$cntEALL;
	$bilcalonFALL+=$cntFALL;
	$bilcalonTHALL+=$cntTHALL;
	$bilcalonabcd+=$bilabcd;
	$bilcalonabcde+=$bilabcde;
	$bilcaloneth+=$bileth;
	$bilcalonfth+=$bilfth;

	echo "<tr><td>$kelas</td>";
	if($bilambil<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ALL&kelas=$kelas' target='_blank'>$bilambil</a></td>";
	else
		echo "<td align='center'>$bilambil</td>";
	if($cntA<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=A&kelas=$kelas' target='_blank'>$cntA</a></td>";
	else
		echo "<td align='center'>$cntA</td>";
	if($cntB<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=B&kelas=$kelas' target='_blank'>$cntB</a></td>";
	else
		echo "<td align='center'>$cntB</td>";
	if($cntC<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=C&kelas=$kelas' target='_blank'>$cntC</a></td>";
	else
		echo "<td align='center'>$cntC</td>";
	if($cntD<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=D&kelas=$kelas' target='_blank'>$cntD</a></td>";
	else
		echo "<td align='center'>$cntD</td>";
	if($cntE<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=E&kelas=$kelas' target='_blank'>$cntE</a></td>";
	else
		echo "<td align='center'>$cntE</td>";

	if($tahun>=2015){
		if($cntF<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=F&kelas=$kelas' target='_blank'>$cntF</a></td>";
		else
			echo "<td align='center'>$cntF</td>";
	}
	if($cntTH<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=TH&kelas=$kelas' target='_blank'>$cntTH</a></td>";
	else
		echo "<td align='center'>$cntTH</td>";
	if($tahun<=2014){
		if($cntEALL<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=EALL&kelas=$kelas' target='_blank'>$cntEALL</a></td>";
		else
			echo "<td align='center'>$cntEALL</td>";
	}else{
		if($cntFALL<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=FALL&kelas=$kelas' target='_blank'>$cntFALL</a></td>";
		else
			echo "<td align='center'>$cntFALL</td>";
	}
	if($cntTHALL<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=THALL&kelas=$kelas' target='_blank'>$cntTHALL</a></td>";
	else
		echo "<td align='center'>$cntTHALL</td>";
	if($tahun<=2014){
		if($bilabcd<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCD&kelas=$kelas' target='_blank'>$bilabcd</a></td>";
		else
			echo "<td align='center'>$bilabcd</td>";
		if($bileth<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ETH&kelas=$kelas' target='_blank'>$bileth</a></td>";
		else
			echo "<td align='center'>$bileth</td>";
	}else{
		if($bilabcde<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCDE&kelas=$kelas' target='_blank'>$bilabcde</a></td>";
		else
			echo "<td align='center'>$bilabcde</td>";
		if($bilfth<>0)
			echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=FTH&kelas=$kelas' target='_blank'>$bilfth</a></td>";
		else
			echo "<td align='center'>$bilfth</td>";
	}
	echo "<td align='right'>$peratuskuasai %</td>";
	echo "<td align='center'>$gpskelas</td></tr>";
}
	echo "<tr bgcolor='#999999'><td>JUMLAH</td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ALL' target='_blank'>$bilcalondaftar</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=A' target='_blank'>$bilcalonA</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=B' target='_blank'>$bilcalonB</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=C' target='_blank'>$bilcalonC</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=D' target='_blank'>$bilcalonD</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=E' target='_blank'>$bilcalonE</a></td>";
	if($tahun>=2015){
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=F' target='_blank'>$bilcalonF</a></td>";
	}
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=TH' target='_blank'>$bilcalonTH</a></td>";
	if($tahun<=2014){
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=EALL' target='_blank'>$bilcalonEALL</a></td>";
	}else{
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=FALL' target='_blank'>$bilcalonFALL</a></td>";
	}
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=THALL' target='_blank'>$bilcalonTHALL</a></td>";
	if($tahun<=2014){
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCD' target='_blank'>$bilcalonabcd</a></td>";
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ETH' target='_blank'>$bilcaloneth</a></td>";
		echo "<td align='right'>".peratus($bilcalonabcd,$bilcalondaftar)." %</td>";
		echo "<td align='center'>".gpmpmrsr($jumA,$jumB,$jumC,$jumD,$jumE,$jumAmbil)."</td>";
	}else{
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCDE' target='_blank'>$bilcalonabcde</a></td>";
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=FTH' target='_blank'>$bilcalonfth</a></td>";
		echo "<td align='right'>".peratus($bilcalonabcde,$bilcalondaftar)." %</td>";
		echo "<td align='center'>".gpmpmrsr_baru($jumA,$jumB,$jumC,$jumD,$jumE,$jumF,$jumAmbil)."</td>";
	}
	echo "</tr>";
}
if($paparlaporan==2){
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

$w_kelas="SELECT DISTINCT KELAS FROM sub_guru WHERE kodsek= :kodsek AND tahun='$tahun' AND ting= :ting";
$w_kelas=oci_parse($conn_sispa,$w_kelas);
oci_bind_by_name($w_kelas, ':kodsek', $kodsek);
oci_bind_by_name($w_kelas, ':ting', $ting);
oci_execute($w_kelas);
while($data=oci_fetch_array($w_kelas)){
	$kelas = $data["KELAS"];
	$sqlambil = "SELECT * FROM $tnilai WHERE kodsek= :kodsek AND tahun='$tahun' AND jpep= :jenis AND $darjah= :ting AND kelas='$kelas'";
	$parameter = array(":kodsek",":jenis",":ting");
	$value = array($kodsek,$jenis,$ting);
	$bilambil = kira_bil_rekod($sqlambil,$parameter,$value);

	$res_nilai = oci_parse($conn_sispa,$sqlambil);
	oci_bind_by_name($res_nilai, ':kodsek', $kodsek);
	oci_bind_by_name($res_nilai, ':jenis', $jenis);
	oci_bind_by_name($res_nilai, ':ting', $ting);
	oci_execute($res_nilai);
	$cntAplus=0;$cntA=0;$cntAminus=0;$cntBplus=0;$cntB=0;$cntCplus=0;$cntC=0;$cntD=0;$cntE=0;$cntG=0;$cntTH=0;$cntEALL=0;$cntTHALL=0;$cntABC=0;$bilcapai=0;$biltakcapai=0;$gpskelas=0;$peratuskuasai=0;$bilmp=0;$bilAplus=0;$bilA=0;$bilAminus=0;$bilBplus=0;$bilB=0;$bilCplus=0;$bilC=0;$bilD=0;$bilE=0;$bilTH=0;$bilG=0;$lulus=0;$gagal=0;
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
		$keputusan = $data_nilai["KEPUTUSAN"];
		
		$bilde = (int) $bilD + (int) $bilE;
		if ($keputusan == "LULUS"){
			$lulus++;
		}
		else {
			$gagal++;
		}

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

		$peratuskuasai = peratus($lulus,$bilambil);
	}
	$sqltnilai = "SELECT sum(AP) AS AP, SUM(A) AS A, SUM(AM) AS AM, SUM(BP) AS BP, SUM(B) AS B, SUM(CP) AS CP,SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(G) AS G, SUM(AMBIL) AS AMBIL FROM analisis_mpma WHERE KODSEK= :kodsek AND TAHUN='$tahun' AND JPEP= :jenis AND  TING= :ting AND kelas='$kelas' AND KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL) GROUP BY KODSEK";
	$sr3 = oci_parse($conn_sispa,$sqltnilai);
	oci_bind_by_name($sr3, ':kodsek', $kodsek);
	oci_bind_by_name($sr3, ':jenis', $jenis);
	oci_bind_by_name($sr3, ':ting', $ting);
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
	$bilcaloncapai+=$lulus;
	$bilcalontakcapai+=$gagal;
	echo "<tr><td>$kelas</td>";
	if($bilambil<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ALL&kelas=$kelas' target='_blank'>$bilambil</a></td>";
	else
		echo "<td align='center'>$bilambil</td>";
	if($cntAplus<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=AP&kelas=$kelas' target='_blank'>$cntAplus</a></td>";
	else
		echo "<td align='center'>$cntAplus</td>";
	if($cntA<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=A&kelas=$kelas' target='_blank'>$cntA</a></td>";
	else
		echo "<td align='center'>$cntA</td>";
	if($cntAminus<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=AM&kelas=$kelas' target='_blank'>$cntAminus</a></td>";
	else
		echo "<td align='center'>$cntAminus</td>";
	if($cntBplus<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=BP&kelas=$kelas' target='_blank'>$cntBplus</a></td>";
	else
		echo "<td align='center'>$cntBplus</td>";
	if($cntB<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=B&kelas=$kelas' target='_blank'>$cntB</a></td>";
	else
		echo "<td align='center'>$cntB</td>";
	if($cntCplus<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=CP&kelas=$kelas' target='_blank'>$cntCplus</a></td>";
	else
		echo "<td align='center'>$cntCplus</td>";
	if($cntC<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=C&kelas=$kelas' target='_blank'>$cntC</a></td>";
	else
		echo "<td align='center'>$cntC</td>";
	if($cntD<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=D&kelas=$kelas' target='_blank'>$cntD</a></td>";
	else
		echo "<td align='center'>$cntD</td>";
	if($cntE<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=E&kelas=$kelas' target='_blank'>$cntE</a></td>";
	else
		echo "<td align='center'>$cntE</td>";
	if($cntG<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=G&kelas=$kelas' target='_blank'>$cntG</a></td>";
	else
		echo "<td align='center'>$cntG</td>";
	if($cntTH<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=TH&kelas=$kelas' target='_blank'>$cntTH</a></td>";
	else
		echo "<td align='center'>$cntTH</td>";
	if($cntEALL<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=EALL&kelas=$kelas' target='_blank'>$cntEALL</a></td>";
	else
		echo "<td align='center'>$cntEALL</td>";
	if($cntTHALL<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=THALL&kelas=$kelas' target='_blank'>$cntTHALL</a></td>";
	else
		echo "<td align='center'>$cntTHALL</td>";
	if($lulus<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCDE&kelas=$kelas' target='_blank'>$lulus</a></td>";
	else
		echo "<td align='center'>$lulus</td>";
	if($gagal<>0)
		echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=GTH&kelas=$kelas' target='_blank'>$gagal</a></td>";
	else
		echo "<td align='center'>$gagal</td>";
	echo "<td align='right'>$peratuskuasai %</td>";
	echo "<td align='center'>$gpskelas</td></tr>";
}	
	echo "<tr bgcolor='#999999'><td>JUMLAH</td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ALL' target='_blank'>$bilcalondaftar</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=AP' target='_blank'>$bilcalonAplus</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=A' target='_blank'>$bilcalonA</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=AM' target='_blank'>$bilcalonAminus</a></td>";	
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=BP' target='_blank'>$bilcalonBplus</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=B' target='_blank'>$bilcalonB</a></td>";	
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=CP' target='_blank'>$bilcalonCplus</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=C' target='_blank'>$bilcalonC</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=D' target='_blank'>$bilcalonD</a></td>";	
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=E' target='_blank'>$bilcalonE</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=G' target='_blank'>$bilcalonG</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=TH' target='_blank'>$bilcalonTH</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=EALL' target='_blank'>$bilcalonEALL</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=THALL' target='_blank'>$bilcalonTHALL</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=ABCDE' target='_blank'>$bilcaloncapai</a></td>";
	echo "<td align='center'><a href='pencapaian_kelas_dtl.php?data=GTH' target='_blank'>$bilcalontakcapai</a></td>";
	echo "<td align='right'>".peratus($bilcaloncapai,$bilcalondaftar)." %</td>";
	echo "<td align='center'>".gpmpma($jumAP,$jumA,$jumAM,$jumBP,$jumB,$jumCP,$jumC,$jumD,$jumE,$jumG,$jumAmbil)."</td>";
	echo "</tr>";
}

echo "<tr><td colspan='$cols'>&nbsp;</td></tr>";
echo "<tr bgcolor=\"#ff9900\"><td colspan='$cols'><div align=\"Left\">Nota</div></td></tr>";
echo "<tr><td colspan='$cols'><div align=\"Left\">Sila pastikan SU Peperiksaan sekolah anda telah melakukan Proses Markah Peperiksaan untuk mendapatkan data yang terkini.</div></td></tr>";
echo "</table>";
echo "<br><br>";
?>