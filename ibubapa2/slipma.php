<?php
session_start();
include("sambungan.php");
include "config.php";
include("../input_validation.php");
include("../include/kirasemak_slip.php");

$nokp = validate($_REQUEST["nokp"]);
$kodsek = validate($_REQUEST["kodsek"]);
$ting = validate($_REQUEST["ting"]);
$kelas = validate($_REQUEST["kelas"]);
$jpep = validate($_REQUEST["cboPep"]);

if($_SESSION["tahun_semasa"]<>"")
	$tahun = validate($_SESSION["tahun_semasa"]);
else
	$tahun = date("Y");

//##########22/11/2012 BUANG FILTER BERDASARKAN KELAS KERANA ADA PELAJAR YG BERTUKAR KELAS TIDAK DAPAT MEMAPARKAN MARKAH UNTUK UJIAN TERDAHULU KERANA NAMA KELAS TELAH BERUBAH******###########
$wujud = 0;
$res2 = OCIParse($conn_sispa,"SELECT count(NOKP) as BILREKOD FROM tnilai_sma WHERE nokp=:nokp and kodsek=:kodsek and tahun=:tahun AND kelas= :kelas and ting=:ting AND jpep=:jpep");
oci_bind_by_name($res2, ':nokp', $nokp);
oci_bind_by_name($res2, ':kodsek', $kodsek);
oci_bind_by_name($res2, ':tahun', $tahun);
oci_bind_by_name($res2, ':kelas', $kelas);
oci_bind_by_name($res2, ':ting', $ting);
oci_bind_by_name($res2, ':jpep', $jpep);
OCIExecute($res2);
while($data=oci_fetch_array($res2)){
	$wujud = $data["BILREKOD"];
}
if($wujud=="0"){
	$msg = "Maklumat markah pelajar masih belum lagi dikemaskini.";
	message("$msg");
	echo "<script>window.close();</script>";
}
if($jpep=="" or $nokp==""){		
	if($nokp=="")
		$msg = "Sila masukkan No Kad Pengenalan / Sijil Lahir!";
	if($jpep=="")
		$msg = "Sila pilih jenis peperiksaan dahulu.";
	message("$msg");
	echo "<script>window.close();</script>";
}

$q_sek = oci_parse($conn_sispa,"SELECT NAMASEK,LENCANA FROM tsekolah WHERE kodsek=:kodsek");
oci_bind_by_name($q_sek, ':kodsek', $kodsek);
oci_execute($q_sek);
$rowsek = oci_fetch_array($q_sek);
$namasek = $rowsek["NAMASEK"];
$lencana = $rowsek["LENCANA"];

$gting = strtoupper($ting); 

if($wujud==0){
	$qrytingkatan = "SELECT * FROM tmurid WHERE kodsek$ting=:kodsek AND $ting=:ting and kelas$ting=:kelas and tahun$ting=:tahun and kodsek_semasa=:kodsek";	
}else{
	$qrytingkatan = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.jpep=:jpep";
}
$bilting=0;
$parlogin=array(":tahun",":ting",":kelas",":kodsek",":jpep");
$vallogin=array($tahun,$ting,$kelas,$kodsek,$jpep);
$bilting = kira_bil_rekod($qrytingkatan,$parlogin,$vallogin);

if($wujud==0){
	$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp=:nokp AND tahun=:tahun AND kodsek=:kodsek AND ting=:ting AND jpep=:jpep");
}else{
	$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=:nokp AND ma.nokp=:nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep and mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.jpep=:jpep");
}
oci_bind_by_name($q_slip, ":nokp", $nokp);
oci_bind_by_name($q_slip, ":tahun", $tahun);
oci_bind_by_name($q_slip, ":kodsek", $kodsek);
oci_bind_by_name($q_slip, ":ting", $ting);
oci_bind_by_name($q_slip, ":jpep", $jpep);
oci_execute($q_slip);
$rowmurid = oci_fetch_array($q_slip);
$jantina = $rowmurid["JANTINA"];
$kelas_lama = $rowmurid["KELAS"];
$ulas = $rowmurid["ULASAN"];

$bilmurid=0;
$qmurid = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.kelas=:kelas_lama AND mkh.jpep=:jpep";
$parmurid=array(":tahun",":ting",":kelas_lama",":kodsek",":jpep");
$valmurid=array($tahun,$ting,$kelas_lama,$kodsek,$jpep);
$bilmurid = kira_bil_rekod($qmurid,$parmurid,$valmurid);

$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc ORDER BY kod");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$namamp[] = array("".$rowsub["KOD"].""=>$rowsub["MP"]);
}

$jan = array("L" => "LELAKI","P" => "PEREMPUAN");
	   
$html= "<br><br>";
if(file_exists("../images/lencana/$lencana")){
$html.= "<div align=\"center\"><img src=\"../images/lencana/$lencana\" width=\"50\" height=\"53\" ></div>";
}
$html.= "<div align=\"center\"><b>$namasek</b></div>";
$html.= "<br>";
$html.= "<table width=\"700\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">";
$html.= "  <tr>";
$html.= "    <td align=\"center\"><font size=\"3\"><strong>SLIP KEPUTUSAN - ".jpep($jpep)." - $tahun</strong></font></td>";
$html.= "  </tr>";
$html.= "</table>";
$html.= "<br>";

$html.= "<table width=\"700\"  border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
$html.= "  <tr>";
$html.= "    <td width=\"80\">&nbsp;Nama</font><br></td>";
$html.= "    <td width=\"1\">:</font><br></td>";
$html.= "    <td width=\"388\">&nbsp;".$rowmurid["NAMA"]."</font><br></td>";
$html.= "    <td width=\"80\">&nbsp;Tingkatan</td>";
$html.= "    <td width=\"1\">:</font><br></td>";
$html.= "    <td width=\"150\">&nbsp;".$rowmurid["TING"]." ".$rowmurid["KELAS"]."</font><br></td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td>&nbsp;No. KP</td>";
$html.= "    <td>:</td>";
$html.= "    <td>&nbsp;".$rowmurid["NOKP"]."</td>";
$html.= "    <td>&nbsp;Jantina</td>";
$html.= "    <td>:</td>";
$html.= "    <td>&nbsp;$jan[$jantina]</td>";
$html.= "  </tr>";
$html.= "</table>";
$html.= "<br>";
$html.= "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
$html.= "  <tr>";
$html.= "   <td colspan=\"4\"><hr align=\"center\" noshade></td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td>&nbsp;&nbsp;&nbsp;Bil</td>";
$html.= "    <td>Mata Pelajaran </td>";
$html.= "    <td><center>Markah</center></td>";
$html.= "    <td><center>Gred</center></td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td colspan=\"4\"><hr align=\"center\" noshade></td>";
$html.= "  </tr>";

$bil=0;
$q_subgu = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun=:tahun AND kodsek=:kodsek AND ting=:ting AND kelas=:kelas ORDER BY kodmp");
oci_bind_by_name($q_subgu, ':tahun', $tahun);
oci_bind_by_name($q_subgu, ':kodsek', $kodsek);
oci_bind_by_name($q_subgu, ':ting', $ting);
oci_bind_by_name($q_subgu, ':kelas', $kelas_lama);
oci_execute($q_subgu);
while($rowsubgu = oci_fetch_array($q_subgu))
{
	$html.= "  <tr>";
	$kodmp = $rowsubgu["KODMP"];
	$gmp = "G$kodmp";
	if($rowmurid["$kodmp"] != ''){
		$bil=$bil+1;
		$html.= "<td>&nbsp;&nbsp;&nbsp;$bil.</td>";
		$html.= "<td>";

		foreach ($namamp as $key => $mp)
		{
			$html.= "$mp[$kodmp]";
		}

		$html.= "</td>";
		$html.= "<td><center>$rowmurid[$kodmp]</center></td>";
		$html.= "<td><center>$rowmurid[$gmp]</center></td>";
	}
	$html.= "  </tr>";
}
$html.= "</table>";
$html.= "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
$html.= "  <tr>";
$html.= "    <td colspan=\"6\"><hr align=\"center\" noshade></td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td width=\"220\">&nbsp;Bilangan Mata Pelajaran Daftar </td>";
$html.= "    <td width=\"1\">:</td>";
$html.= "    <td width=\"179\">".$rowmurid["BILMP"]."</td>";
$html.= "    <td width=\"214\">Jumlah Markah </td>";
$html.= "    <td width=\"1\">:</td>";
$html.= "    <td width=\"85\">".$rowmurid["JUMMARK"]."</td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td>&nbsp;Kedudukan Dalam Kelas </td>";
$html.= "    <td width=\"1\">:</td>";
$html.= "    <td>".$rowmurid["KDK"]." / $bilmurid</td>";
$html.= "    <td>&nbsp; </td>";
$html.= "    <td>&nbsp; </td>";
$html.= "    <td>&nbsp; </td>";
$html.= "  <tr>";
$html.= "	<td>&nbsp;Kedudukan Dalam Tingkatan </td>";
$html.= "    <td width=\"1\">:</td>";
$html.= "	<td>".$rowmurid["KDT"]." / $bilting</td>";
$html.= "    <td>Peratus</td>";
$html.= "    <td width=\"1\">:</td>";
$html.= "    <td>".$rowmurid["PERATUS"]."</td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td>&nbsp;Kehadiran</td>";
$html.= "    <td width=\"1\">:</td>";
$html.= "    <td>".$rowmurid["KEHADIRAN"]." / ".$rowmurid["KEHADIRANPENUH"]." Hari</td>";
$html.= "    <td>Gred Purata Pelajar </td>";
$html.= "    <td width=\"1\">:</td>";
$html.= "    <td>".$rowmurid["GPC"]."</td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td>&nbsp;Pencapaian Gred Keseluruhan </td>";
$html.= "    <td width=\"1\">:</td>";
$html.= "    <td>".$rowmurid["PENCAPAIAN"]."</td>";
$html.= "    <td>Keputusan</td>";
$html.= "    <td width=\"1\">:</td>";
$html.= "    <td>".$rowmurid["KEPUTUSAN"]."</td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td colspan=\"6\"><hr align=\"center\" noshade></td>";
$html.= "  </tr>";
$html.= "</table>";
	
$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun=:tahun AND kodsek=:kodsek AND ting=:ting AND kelas=:kelas");
oci_bind_by_name($q_guru, ':tahun', $tahun);
oci_bind_by_name($q_guru, ':kodsek', $kodsek);
oci_bind_by_name($q_guru, ':ting', $ting);
oci_bind_by_name($q_guru, ':kelas', $kelas_lama);
oci_execute($q_guru);
$row_guru = oci_fetch_array($q_guru);

$html.= "<br>";
$html.= "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
$html.= "  <tr>";
$html.= "    <td width='120'>Nama Guru Kelas</td>";
$html.= "    <td width='1'>:</td>";
$html.= "    <td width='579'>&nbsp;".$row_guru["NAMA"]."</td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td colspan='3'>&nbsp;</td>";
$html.= "  </tr>";
$html.= "  <tr>";
$html.= "    <td>Ulasan Guru Kelas</td>";
$html.= "    <td>:</td>";
$html.= "    <td>&nbsp;$ulas</td>";
$html.= "  </tr>";

if($wujud==0){
	$html.= "<tr><td colspan='3'>&nbsp;</td></tr>";
	$html.= "<tr><td colspan='3'>&nbsp;</td></tr>";
	$html.= "<tr><td colspan='3'>&nbsp;</td></tr>";
	$html.= "<tr><td colspan='3'><b>NOTIS MAKLUMAN:</b></td></tr>";	
	$html.= "<tr><td colspan='3'>Markah Peperiksaan Masih Dalam Proses Pengisian dan Analisa. Sila Hubungi Pihak Sekolah Untuk Mengetahui Tarikh Keputusan Akhir Yang Akan Dikeluarkan.</td></tr>";
}

$html.= "</table>";

function jpep($kodpep){
	switch ($kodpep)
	{
		case "U1": $npep="UJIAN 1"; break;
		case "U2": $npep="UJIAN 2"; break;
		case "PAT": $npep="PEPERIKSAAN AKHIR TAHUN"; break;
		case "PPT": $npep="PEPERIKSAAN PERTENGAHAN TAHUN"; break;
		case "PMRC": $npep="PEPERIKSAAN PERCUBAAN PMR"; break;
		case "SPMC": $npep="PEPERIKSAAN PERCUBAAN SPM"; break;
	}
	return $npep;
}
include("../MPDF54/mpdf.php");
$mpdf=new mPDF('c','A4','','',10,10,10,10,16,13);
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
$mpdf->SetProtection(array('print'), '', 'vtech123');
$mpdf->WriteHTML($html,2);
$mpdf->Output('mpdf.pdf','I');
exit;

?>