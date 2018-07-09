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
include 'config.php';
include 'fungsi.php';
include 'input_validation.php';

$tahun=validate($_GET['tahun']);
$ting=validate($_GET['ting']);
$jpep=validate($_GET['jpep']);
$status=validate($_GET['status']);
$kodppd=validate($_GET['kodppd']);
$namappd=validate($_GET['namappd']);

$sqlppd = oci_parse($conn_sispa,"select ppd from tkppd where kodppd= :kodppd");
oci_bind_by_name($sqlppd,":kodppd",$kodppd);
oci_execute($sqlppd);
$rowppd = oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];

switch ($ting)
		{
			case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
				$penilaian="tnilai_sr";
				//$bilgred= "(bilmp-(bilc+bild+bile+bilth))=1";
				$bilgred= "(TO_NUMBER(BILA)) > 5 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
				$bilgred2= "(TO_NUMBER(BILA)) > 3 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
				$tahap="darjah";
				$capai="(MINIMUM 4A)<br>(MINIMUM 6A - SJKC & SJKT)";
				break;
			case "P": case "T1": case "T2": case "T3":
				$penilaian="tnilai_smr";
				$bilgred2= "(TO_NUMBER(BILA)) > 5 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
				$tahap="ting";
				$capai="(BERDASARKAN MP DIAMBIL)";
				break;
			case "T4": case "T5":
				$penilaian="tnilai_sma";
				$bilgred2= "(TO_NUMBER(BILAP)+TO_NUMBER(BILA)+TO_NUMBER(BILAM)) > 5 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and TO_NUMBER(BILAP)+TO_NUMBER(BILA)+TO_NUMBER(BILAM)< TO_NUMBER(BILMP)";
				$tahap="ting";
				$capai="(BERDASARKAN MP DIAMBIL)";
				break;
		}
	
	echo " <div  align=\"center\"><span class=\"style3\">DATA NEAR MISS KECEMERLANGAN <br>$capai<br>".tahap($ting)."<br>".jpep($jpep)."<br>DAERAH $namappd($kodppd) TAHUN $tahun</span><br><br>\n";
	//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
	echo "   <table width=\"80%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"1\">\n";
	echo "<tr>\n";
	echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">BIL</span></td>\n";
	echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">NAMA SEKOLAH </span></td>\n";
	echo "<td align=\"center\" valign=\"top\"><span class=\"style3\">BILANGAN</span></td>\n";
	echo "</tr>\n";
	
	$qbm=oci_parse($conn_sispa,"SELECT kodsek,namasek,kodjenissekolah FROM tsekolah WHERE status= :status AND kodppd= :kodppd ORDER BY namasek");
    oci_bind_by_name($qbm,":status",$status);
	oci_bind_by_name($qbm,":kodppd",$kodppd);
	oci_execute($qbm);
	$i=0;
	$bil=0;
	while($rbm = oci_fetch_array($qbm)){
		$bil=$bil+1;
		$ksek = $rbm["KODSEK"];
		$kodjenissek = $rbm["KODJENISSEKOLAH"];
		echo " <tr>\n";
		echo " <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "<td valign=\"top\"><span class=\"style2\">".$rbm["NAMASEK"]."</span></td>\n";
		//$qnmiss="SELECT * FROM $penilaian WHERE tahun='$tahun' AND jpep='$jpep' and kodsek='$rbm[KODSEK]' AND $tahap='$ting' AND $bilgred";
		//$stmt = oci_parse($conn_sispa,$qnmiss);
		//oci_execute($stmt);
		if($kodjenissek=="103" or $kodjenissek=="104"){//SJKC & SJKT
			$sqlbil6a="SELECT * FROM $penilaian WHERE kodsek='$rbm[KODSEK]' AND tahun= :tahun AND jpep= :jpep AND $tahap= :ting AND $bilgred";
			$parameter=array(":tahun",":jpep",":ting");
            $value=array($tahun,$jpep,$ting);
            $bil6a = kira_bil_rekod($sqlbil6a,$parameter,$value);
		}
		else{
			$sqlbil6a="SELECT * FROM $penilaian WHERE kodsek='$rbm[KODSEK]' AND tahun= :tahun AND jpep= :jpep AND $tahap= :ting AND $bilgred2";
		    $parameter=array(":tahun",":jpep",":ting");
            $value=array($tahun,$jpep,$ting);
            $bil6a = kira_bil_rekod($sqlbil6a,$parameter,$value);
			}
		echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$bil6a</span></td>\n";
		echo "</tr>\n";
		$jumbil6a=$jumbil6a+$bil6a;

		}
		echo "<tr>\n";
		echo "<td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style2\">JUMLAH</span></td>\n";
		echo "<td align=\"center\" valign=\"top\"><span class=\"style2\">$jumbil6a</span></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	?>
    <?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>
