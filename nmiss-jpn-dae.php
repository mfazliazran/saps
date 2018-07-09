<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
?>
<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>

<?php
$d = $_GET['data'];
list ($kodppd, $tahun, $ting, $jpep,$status) =split('[/]', $d); 

$sqlppd = oci_parse($conn_sispa,"select ppd from tkppd where kodppd='$kodppd'");
oci_execute($sqlppd);
$rowppd = oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];
?>

<td valign="top" class="rightColumn">

<p class="subHeader">Analisis Peperiksaan</p>

<?php

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
	?>
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('nmiss-date-excell.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&jpep=<?php echo $jpep;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>','win1');" />


<div align=right><a href="ctk_nmiss-dae.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&jpep=<?php echo $jpep;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>" target=_blank ><img src=printi.jpg border=0></a></div>

<form action="jpn-nmiss.php" method="POST" target=_self>
<?php

echo " <div align=\"center\"><p><h3>$capai<br>".jpep($jpep)." ".tahap($ting)."<br>DAERAH $namappd($kodppd) TAHUN $tahun</h3></p></center>\n";
	echo "   <table width=\"80%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
	echo "     <tr>\n";
	echo "       <td align=\"center\"><b>BIL</b></td>\n";
	echo "       <td><b>NAMA SEKOLAH</b></td>\n";
	echo "       <td><div align=\"center\"><b>BILANGAN</b></div></td>\n";
	echo "     </tr>\n";

	$qbm=oci_parse($conn_sispa,"SELECT kodsek,namasek,KodJenisSekolah FROM tsekolah WHERE status='$status' AND kodppd='$kodppd' ORDER BY namasek");
	oci_execute($qbm);
	$i=0;
	$bil=0;
	while($rbm = oci_fetch_array($qbm)){
	$bil=$bil+1;
	$ksek = $rbm["KODSEK"];
	$kodjenissek = $rbm["KODJENISSEKOLAH"];
		echo "     <tr>\n";
		echo "       <td><center>$bil</center></td>\n";
		echo "       <td align=left><a href=nmiss-dae-senarai.php?data=".$rbm["KODSEK"]."/".$tahun."/".$ting."/".$jpep." target=_blank>".$rbm["NAMASEK"]."</a></td>\n";
		/*if($kodjenissek=="103" or $kodjenissek=="104")//SJKC & SJKT
			$qnmiss="SELECT * FROM $penilaian WHERE tahun='$tahun' AND jpep='$jpep' and kodsek='$rbm[KODSEK]' AND $tahap='$ting' AND $bilgred";
		else
			$qnmiss="SELECT * FROM $penilaian WHERE tahun='$tahun' AND jpep='$jpep' and kodsek='$rbm[KODSEK]' AND $tahap='$ting' AND $bilgred2";
		$stmt = oci_parse($conn_sispa,$qnmiss);
		oci_execute($stmt);*/
		
		if($kodjenissek=="103" or $kodjenissek=="104")//SJKC & SJKT
			$bilnmiss=count_row("SELECT * FROM $penilaian WHERE kodsek='$rbm[KODSEK]' AND tahun='$tahun' AND jpep='$jpep' AND $tahap='$ting' AND $bilgred");
		else
			$bilnmiss=count_row("SELECT * FROM $penilaian WHERE kodsek='$rbm[KODSEK]' AND tahun='$tahun' AND jpep='$jpep' AND $tahap='$ting' AND $bilgred2");
		echo "       <td><center>$bilnmiss</center></td>\n";
		echo "     </tr>\n";
		$jumbilnmiss=$jumbilnmiss+$bilnmiss;
		/////////////////////////////////////////////////////////////////////////////////////////
		/*$i=$i+1;
		print "<input name=\"bil\" type=\"hidden\" readonly value=\"$bil\">";
		print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		print "<input name=\"jpep\" type=\"hidden\" readonly value=\"$jpep\">";
		print "<input name=\"kodsek[$i]\" type=\"hidden\" readonly value=\"$ksek\">";
		print "<input name=\"ting\" type=\"hidden\" readonly value=\"$ting\">";
		print "<input name=\"tahun\" type=\"hidden\" readonly value=\"$tahun\">";
		print "<input name=\"bilnmiss[$i]\" type=\"hidden\" readonly value=\"$bilnmiss\">";
		*/
		}
		echo "     <tr>\n";
		echo "       <td colspan=\"2\"><center>JUMLAH</center></td>\n";
		echo "       <td><center>$jumbilnmiss</center></td>\n";
		echo "     </tr>\n";
	echo "   </table>\n";

?>

<br>
</form>

<?php

		
		//echo "$kodsek";
		//echo "<br><br>";
		//echo " <center></b>NEAR MISS CEMERLANG</b></center>";
		echo "<br>";
		echo "<form method=\"post\">\n";
		//echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";

		echo "  <tr bgcolor=\"#CCCCCC\">\n";

		$status=$_GET['status'] ;
		if ($status=="")
		  $status="SR";
		$ting=$_GET['ting'];

		switch ($status)
		{
			case "SM" : $statussek = "SEKOLAH MENENGAH"; $tmp = "mpsmkc"; break;
			case "SR" : $statussek = "SEKOLAH RENDAH"; $tmp = "mpsr"; break;
			default : $statussek = "Pilih Jenis Sekolah"; break;
		}

		$SQLting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting");
		oci_execute($SQLting);
		$SQLmp = oci_parse($conn_sispa,"SELECT DISTINCT * FROM $tmp ORDER BY mp");
		oci_execute($SQLmp);


		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</tr></table><br><br>";
		//echo "<center><input type='submit' name=\"nmiss\" value=\"Hantar\"></center>";

		echo "</form>";
?>
</td>



<?php include 'kaki.php';?>


