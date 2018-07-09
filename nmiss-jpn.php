<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
$status=$_GET['status'] ;
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Peperiksaan Negeri</p>

<?php
if (isset($_POST['nmiss']))
{
	$tahun=$_POST['tahun'];
	$ting=$_POST['ting'];
	$jpep=$_POST['jpep'];
	
	switch ($ting)
		{
			case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
				$penilaian="tnilai_sr";
				//$bilgred= "(bilmp-(bilc+bild+bile+bilth))=1";
				$bilgred= "(TO_NUMBER(BILA)) > 5 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";

				$bilgred2= "(TO_NUMBER(BILA)) > 3 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
				$tahap="darjah";
				$capai="(MINIMUM 4A - SK)<br>(MINIMUM 6A - SJKC & SJKT)";
				break;
			case "P": case "T1": case "T2": case "T3":
				$penilaian="tnilai_smr";
				$bilgred2= "(TO_NUMBER(BILA)) > 5 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
				$tahap="ting";
				$capai="(BERDASARKAN MP DIAMBIL)";
				break;
			case "T4": case "T5":
				$penilaian="tnilai_sma";
				$bilgred2=  "(TO_NUMBER(BILAP) + TO_NUMBER(BILA) + TO_NUMBER(BILAM)) > 5 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and TO_NUMBER(BILAP) + TO_NUMBER(BILA) + TO_NUMBER(BILAM)< TO_NUMBER(BILMP)";
				$tahap="ting";
				$capai="(BERDASARKAN MP DIAMBIL)";
				break;
		}

?>
<div align=right>
</div><br>
<?php
	echo " <div align=\"center\"><p><strong>DATA NEAR MISS KECEMERLANGAN<br>$capai<br>".tahap($ting)."<br>".jpep($jpep)."<br>TAHUN $tahun</strong></p>\n";
	//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
	echo "   <table width=\"40%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
	echo "     <tr>\n";
	echo "       <td><div align=\"center\">BIL</div></td>\n";
	echo "       <td>NAMA DAERAH</td>\n";
	echo "       <td><div align=\"center\">BILANGAN</div></td>\n";
	echo "     </tr>\n";
//*****************************************************************
$qppd=oci_parse($conn_sispa,"SELECT * FROM tkppd WHERE kodnegeri='$kodnegerijpn' ORDER BY kodppd");
//echo "SELECT * FROM tkppd WHERE kodnegeri='$kodnegerijpn' ORDER BY kodppd";
oci_execute($qppd);
$bil=0; $jumbilnmiss=0;
while($rppd = oci_fetch_array($qppd))
{
	$bilppd = $bilppd + 1;

	//$qbm = oci_parse($conn_sispa,"SELECT sum(bcalon) as bilc FROM jpnrmiss WHERE kodppd='$rppd[KODPPD]' and tahun='$tahun' AND ting='$ting' AND jpep='$jpep'");
	//oci_execute($qbm);

///****************************************************************
//	$qbm = mysql_query("SELECT *, sum(bcalon) as bilc FROM jpnrmiss WHERE tahun='$tahun' AND ting='$ting' AND jpep='$jpep' GROUP BY kodppd") OR die(mysql_query());
	$qbm2 = oci_parse($conn_sispa,"SELECT kodsek,KodJenisSekolah FROM tsekolah WHERE kodppd='$rppd[KODPPD]' ");
	oci_execute($qbm2);
	$jumbilnmiss=0;
	while($rppd2 = oci_fetch_array($qbm2)){
		$kodsekolah = $rppd2["KODSEK"];	
		$kodjenissek = $rppd2["KODJENISSEKOLAH"];
		//echo $kodsekolah."<br>";
		if($kodjenissek=="103" or $kodjenissek=="104")
			$bilnmiss=count_row("SELECT kodsek FROM $penilaian WHERE kodsek='$kodsekolah' and tahun='$tahun' AND jpep='$jpep' and $tahap='$ting' AND $bilgred");

		else
			$bilnmiss=count_row("SELECT kodsek FROM $penilaian WHERE kodsek='$kodsekolah' and tahun='$tahun' AND jpep='$jpep' and $tahap='$ting' AND $bilgred2");
		$jumbilnmiss+=$bilnmiss;
	}
	$jumall+=$jumbilnmiss;
	$i = 0;
	//$rbm = oci_fetch_array($qbm);
	$bil=$bil+1;
		echo "     <tr>\n";
		echo "       <td><center>$bil</center></td>\n";
		echo "       <td align=left><a href=nmiss-jpn-dae.php?data=".$rppd["KODPPD"]."/".$tahun."/".$ting."/".$jpep."/$status target='_blank'>".$rppd["PPD"]."</a></td>\n";
		//$bilcer = $rbm["BILC"]+0;
		echo "       <td><center>$jumbilnmiss</center></td>\n";
		echo "     </tr>\n";
		//$jumcer = $jumcer + $bilcer;
	
}	
echo "     <tr>\n";
echo "       <td colspan=\"2\"><center>JUMLAH</center></td>\n";
echo "       <td><center>$jumall</center></td>\n";
echo "     </tr>\n";
echo "   </table>\n";
echo "<br><br><br>";
} 
else { ?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.status.options[form.status.options.selectedIndex].value;
	self.location='nmiss-jpn.php?status='+val;
}
</script>

<?php
//echo "<br><br>";
echo " <center></b>NEAR MISS CEMERLANG</b></center>";
echo "<br>";
echo "<form method=\"post\">\n";
echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";

echo "  <tr bgcolor=\"#CCCCCC\">\n";
 
//if($status == "")
//	$status = "SM";
//$ting=$_GET['ting'];

switch ($status)
{
	case "SM" : $statussek = "SEKOLAH MENENGAH"; $tmp = "mpsmkc"; break;
	case "SR" : $statussek = "SEKOLAH RENDAH"; $tmp = "mpsr"; break;
	default : $statussek = "Pilih Jenis Sekolah"; break;
}

/*$SQLting = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting");
//echo "SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting";
oci_execute($SQLting);
$SQLmp = oci_parse($conn_sispa,"SELECT * FROM $tmp ORDER BY mp");
//echo "SELECT DISTINCT * FROM $tmp ORDER BY mp";
oci_execute($SQLmp);*/

echo "<td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
echo "<form method=post name='f1' action='nmiss-jpn.php'>";
echo "<tr bgcolor=\"#CCCCCC\"><td>JENIS SEKOLAH</td><td><select name=\"status\"  onchange=\"reload(this.form)\"><option value=''>Pilih Jenis Sekolah</option>";
echo "<option value=\"SR\"";if($status=='SR'){echo "SELECTED";} echo ">SEKOLAH RENDAH</option>";
echo "<option value=\"SM\"";if($status=='SM'){echo "SELECTED";} echo ">SEKOLAH MENENGAH</option>";
echo "</select>";
echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";

echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
echo "<select name='ting'><option value=''>Ting/Darjah</option>";
switch ($status)
{
	case "SM" :	echo "<option value=\"P\">P</option>";
				echo "<option value=\"T1\">T1</option>";
				echo "<option value=\"T2\">T2</option>";
				echo "<option value=\"T3\">T3</option>";
				echo "<option value=\"T4\">T4</option>";
				echo "<option value=\"T5\">T5</option>";
				break;
				
	case "SR" : 
				echo "<option value=\"D2\">D2</option>";
				echo "<option value=\"D3\">D3</option>";
				echo "<option value=\"D4\">D4</option>";
				echo "<option value=\"D5\">D5</option>";
				echo "<option value=\"D6\">D6</option>";
				break;
}
echo "</select>";
echo "</td></tr>";

$SQLpep = oci_parse($conn_sispa,"SELECT DISTINCT kod,jenis,rank FROM jpep ORDER BY rank");
oci_execute($SQLpep);
echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td><td>";
echo "<select name='jpep'><option value=''>Pilih Peperiksaan</option>";
while($rowpep = oci_fetch_array($SQLpep)) { 
	echo  "<option value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
}
echo "</select>";
echo "</td></tr>";


//////////////////  This will end the second drop down list ///////////
//// Add your other form fields as needed here/////
echo "</tr></table><br><br>";
 print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";
echo "<center><input type='submit' name=\"nmiss\" value=\"Hantar\"></center>";

echo "</form>";
} ?> 
</td>
<?php include 'kaki.php';?> 