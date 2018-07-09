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
list ($negeri, $tahun, $ting, $jpep,$status) =split('[/]', $d); 
//echo $d;

$sqlppd = oci_parse($conn_sispa,"select negeri,kodnegeri from tknegeri where negeri='$negeri'");
oci_execute($sqlppd);
$rowppd = oci_fetch_array($sqlppd);
$namanegeri = $rowppd["NEGERI"];
$kodnegeri = $rowppd["KODNEGERI"];
?>

<td valign="top" class="rightColumn">

<p class="subHeader">NEAR MISS CEMERLANG (BPTV)</p>

<?php

switch ($ting)
		{

			case "T4": case "T5":
				$penilaian="tnilai_sma";
				$bilgred= "(TO_NUMBER(BILAP)+TO_NUMBER(BILA)+TO_NUMBER(BILAM)) > 5 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and TO_NUMBER(BILAP)+TO_NUMBER(BILA)+TO_NUMBER(BILAM)< TO_NUMBER(BILMP)";
				$tahap="ting";
				$capai="(BERDASARKAN MP DIAMBIL)";
				break;
		}
	?>


<?php
// <form action="jpn-nmiss.php" method="POST" target=_self>
echo " <div align=\"center\"><p><h3>".tahap($ting)."<br>NEGERI $namanegeri TAHUN $tahun</h3></p></center>\n";
	echo "   <table width=\"80%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
	echo "     <tr>\n";
	echo "       <td align=\"center\"><b>BIL</b></td>\n";
	echo "       <td><b>NAMA PEJABAT PELAJARAN DAERAH</b></td>\n";
	echo "       <td><div align=\"center\"><b>BILANGAN</b></div></td>\n";
	echo "     </tr>\n";	
	
$qppd=oci_parse($conn_sispa,"SELECT * FROM tkppd where kodnegeri='$kodnegeri'");
//echo "SELECT * FROM tkppd WHERE kodnegeri='$kodnegerijpn' ORDER BY kodppd";
//die ("$qppd");
oci_execute($qppd);
$bil=0; $jumbilnmiss=0;
while($rppd = oci_fetch_array($qppd))
{
	$bilppd = $bilppd + 1;
	$kodppd=$rppd["KODPPD"];

	$qbm2 = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodppd='$kodppd' and kodjenissekolah in ('203','303') ");
	oci_execute($qbm2);
	$jumbilnmiss=0;
	while($rppd2 = oci_fetch_array($qbm2)){
		$kodsekolah = $rppd2["KODSEK"];	
		$bilnmiss=count_row("SELECT * FROM $penilaian WHERE tahun='$tahun' AND jpep='$jpep' and kodsek='$kodsekolah' AND $tahap='$ting' AND $bilgred");
		$jumbilnmiss=$jumbilnmiss+$bilnmiss;	
	}
	$jumall+=$jumbilnmiss;
	$i = 0;
	//$rbm = oci_fetch_array($qbm2);
		$bil=$bil+1;
	
		echo "     <tr>\n";
		echo "       <td><center>$bil</center></td>\n";
		echo "       <td align=left><a href=n-mis-bptv.php?data=".$rppd["KODPPD"]."/".$tahun."/".$ting."/".$jpep." target=_blank>".$rppd["PPD"]."</a></td>\n";
		echo "       <td><center>$jumbilnmiss</center></td>\n";
		echo "     </tr>\n";

		//$jumbilnmiss=$jumbilnmiss+$bilnmiss;
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
		echo "       <td><center>$jumall</center></td>\n";
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


