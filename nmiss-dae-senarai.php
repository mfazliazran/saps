<html>
<body>
<title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<STYLE type="text/css">
@media print {
  #mybutton { display:none; visibility:hidden; }

  #mybutton2 { display:none; visibility:hidden; }
 
}
</STYLE>
<style type="text/css">
.style1 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 16px;
	color: #000000;
 	font-weight: bold; 
}

.style2 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;

}

.style3 {
	font-family: verdana,Arial, Helvetica, sans-serif;
   	font-size: 12px;
	color: #000000;
 	font-weight: bold; 
}

</style>
<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>
<?php
//include 'auth.php';
include 'config.php';
//include 'kepala.php';
//include 'menu.php';
include 'fungsi.php';
include 'input_validation.php';


$m=validate($_GET['data']);
list($kodsek, $tahun, $ting, $jpep)=explode("/", $m);

if (isset($_GET['data']))
{


switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$penilaian="tnilai_sr";
			$markah="markah_pelajarsr";
			$bilgred= "(TO_NUMBER(BILA)) > 5 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
			$bilgred2= "(TO_NUMBER(BILA)) > 3 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";

			$tahap="darjah";
			$capai="(MINIMUM 4A)<br>(MINIMUM 6A - SJKC & SJKT)";
			break;
		case "P": case "T1": case "T2": case "T3":
			$penilaian="tnilai_smr";
			$markah="markah_pelajar";
			$bilgred2="(TO_NUMBER(BILA)) > 5 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
			$capai="(MINIMUM 6A)";
			$tahap="ting";
			break;
		case "T4": case "T5":
			$penilaian="tnilai_sma";
			$bilgred2= "(TO_NUMBER(BILAP) + TO_NUMBER(BILA) + TO_NUMBER(BILAM)) > 5 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and TO_NUMBER(BILAP) + TO_NUMBER(BILA) + TO_NUMBER(BILAM)< TO_NUMBER(BILMP)";
			$markah="markah_pelajar";
			$tahap="ting";
			$capai="(MINIMUM 6A)";
			break;
	}
$qs=oci_parse($conn_sispa,"SELECT namasek,kodppd,KodJenisSekolah FROM tsekolah WHERE kodsek= :kodsek");	
oci_bind_by_name($qs, ':kodsek', $kodsek);
oci_execute($qs);
while($rs=oci_fetch_array($qs)){
	$namasek=$rs["NAMASEK"];
	$kodppd=$rs["KODPPD"];
	$kodjenissek = $rs["KODJENISSEKOLAH"];
	
	$sqlppd = oci_parse($conn_sispa,"select ppd from tkppd where kodppd='$kodppd'");
	oci_execute($sqlppd);
	$rowppd = oci_fetch_array($sqlppd);
	$namappd = $rowppd["PPD"];
}

echo "<span class=\"style1\">ANALISIS PEPERIKSAAN</span><br><br>" ; 
echo "<div align=\"center\"><span class=\"style3\">DATA NEAR MISS KECEMERLANGAN $tahun<br>$capai<br>".tahap($ting)."<br>".jpep($jpep)."<br>DAERAH $namappd($kodppd)</div><br>\n";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NAMA SEKOLAH : $kodsek - $namasek</span><br><br>\n";
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<div align=\"center\"><table width=\"90%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo " <tr>\n";
echo " <td align=\"center\" valign=\"top\"><span class=\"style3\">BIL</span></td>\n";
echo " <td align=\"center\" valign=\"top\"><span class=\"style3\">NAMA MURID</span></td>\n";
$i=0;
$k=0;

$q_mp = oci_parse($conn_sispa,"SELECT DISTINCT ting, tahun, kodmp, kodsek FROM sub_guru WHERE kodsek= :kodsek and tahun= :tahun AND ting= :ting  ORDER BY kodmp");
oci_bind_by_name($q_mp, ':kodsek', $kodsek);
oci_bind_by_name($q_mp, ':tahun', $tahun);
oci_bind_by_name($q_mp, ':ting', $ting);
//echo "SELECT DISTINCT ting, tahun, kodmp, kodsek FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' and kodmp not in (select kod from sub_mr_xambil) ORDER BY kodmp";
//echo "SELECT DISTINCT ting, tahun, kodmp, kodsek FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' ORDER BY kodmp";
oci_execute($q_mp);
//$num = count_row("SELECT DISTINCT ting, tahun, kodmp, kodsek FROM sub_guru WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' and kodmp not in (select kod from sub_sr_xambil) ORDER BY kodmp");
	while($rowmp=oci_fetch_array($q_mp)){
	$mp_kodmp[$i] = $rowmp["KODMP"];
	echo "<td><center>$mp_kodmp[$i]</center></td>";
	$i++;
	$k++;
	}
echo "<td><center>PENCAPAIAN</center></td>";
echo "</tr>\n";
//////////////////////////////////////////////////////////////////////////
if($kodjenissek=="103" or $kodjenissek=="104")//SJKC & SJKT
	$qnm=oci_parse($conn_sispa,"SELECT * FROM $penilaian WHERE kodsek= :kodsek AND tahun= :tahun AND $tahap= :ting AND jpep= :jpep AND $bilgred");	
else
	$qnm=oci_parse($conn_sispa,"SELECT * FROM $penilaian WHERE kodsek= :kodsek AND tahun= :tahun AND $tahap= :ting AND jpep= :jpep AND $bilgred2");
    oci_bind_by_name($qnm, ':kodsek', $kodsek);
    oci_bind_by_name($qnm, ':tahun', $tahun);
    oci_bind_by_name($qnm, ':ting', $ting);
	oci_bind_by_name($qnm, ':jpep', $jpep);
oci_execute($qnm);
//echo "SELECT * FROM $penilaian WHERE kodsek='$kodsek' AND tahun='$tahun' AND $tahap='$ting' AND jpep='$jpep' AND $bilgred2";
while($rnm=oci_fetch_array($qnm)){
$bil=$bil+1;
	//echo " <tr>\n";
	//echo " <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
	$qnamap=oci_parse($conn_sispa,"SELECT * FROM $markah WHERE nokp='$rnm[NOKP]' AND kodsek= :kodsek AND tahun= :tahun AND $tahap= :ting AND jpep= :jpep");
	oci_bind_by_name($qnamap, ':kodsek', $kodsek);
    oci_bind_by_name($qnamap, ':tahun', $tahun);
    oci_bind_by_name($qnamap, ':ting', $ting);
	oci_bind_by_name($qnamap, ':jpep', $jpep);
	oci_execute($qnamap);
	$rnamap=oci_fetch_array($qnamap);
	echo " <tr>\n";
	echo " <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
	echo "<td valign=\"top\"><span class=\"style2\">".$rnamap["NAMA"]."</span></td>\n";
	for ($i=0; $i<$k; $i++){
		$kod = "G$mp_kodmp[$i]";
		echo " <td align=\"center\" valign=\"top\"><span class=\"style2\">$rnamap[$kod]&nbsp;</span></td>\n";
	}
	echo "<td>".$rnm["PENCAPAIAN"]."</td>";
	echo "</tr>\n";
	}
echo "   </table></div>\n";
} ?> 
              
