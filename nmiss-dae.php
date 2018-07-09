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

$tahun=$_POST['tahun'];
$ting=$_POST['ting'];
$jpep=$_POST['jpep'];
$status=$_POST['status'];
$kodppd=$_POST['kodppd'];
$namappd=$_POST['namappd'];

$sqlppd = oci_parse($conn_sispa,"select ppd from tkppd where kodppd= :kodppd");
oci_bind_by_name($sqlppd, ':kodppd', $kodppd);
oci_execute($sqlppd);
$rowppd = oci_fetch_array($sqlppd);
$namappd = $rowppd["PPD"];
?>

<td valign="top" class="rightColumn">

<p class="subHeader">Analisis Peperiksaan</p>

<?php

if (isset($_POST['nmiss']))
{

	switch ($ting)
		{
			case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
				$penilaian="tnilai_sr";
				
				$bilgred= "(TO_NUMBER(BILA)) > 3 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
				$bilgred2= "(TO_NUMBER(BILA)) > 5 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and  TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";//SJKC & SJKT
				$tahap="darjah";
				$capai="(MINIMUM 4A)<br>(MINIMUM 6A - SJKC & SJKT)";
				break;
			case "P": case "T1": case "T2": case "T3":
				$penilaian="tnilai_smr";
				$bilgred= "(TO_NUMBER(BILA)) > 5 and NVL(BILC,0)=0 and NVL(BILD,0)=0 and NVL(BILE,0)=0 and NVL(BILTH,0)=0 and TO_NUMBER(BILA) < TO_NUMBER(BILMP) ";
				$tahap="ting";
				$capai="(BERDASARKAN MP DIAMBIL)";
				break;
			case "T4": case "T5":
				$penilaian="tnilai_sma";
				$bilgred= "(TO_NUMBER(BILAP)+TO_NUMBER(BILA)+TO_NUMBER(BILAM)) > 5 AND NVL(BILCP,0)=0 AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILG,0)=0 AND NVL(BILTH,0)=0 and TO_NUMBER(BILAP)+TO_NUMBER(BILA)+TO_NUMBER(BILAM)< TO_NUMBER(BILMP)";
				$tahap="ting";
				$capai="(BERDASARKAN MP DIAMBIL)";
				break;
		}
	?>
<input type="button" name="export" value="EXPORT KE EXCELL" onclick="open_window('nmiss-date-excell.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&jpep=<?php echo $jpep;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>','win1');" />


<div align=right><a href="ctk_nmiss-dae.php?tahun=<?php echo $tahun;?>&&ting=<?php echo $ting;?>&&jpep=<?php echo $jpep;?>&&status=<?php echo $status;?>&&kodppd=<?php echo $kodppd;?>" target=_blank ><img src=printi.jpg border=0></a></div>

<form action="jpn-nmiss.php" method="POST" target=_self>
<?php

echo " <div align=\"center\"><p><h3>DATA NEAR MISS KECEMERLANGAN $capai<br>".jpep($jpep)." ".tahap($ting)."<br>DAERAH $namappd($kodppd) TAHUN $tahun</h3></p></center>\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
	echo "   <table width=\"80%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
	echo "     <tr>\n";
	echo "       <td align=\"center\"><b>BIL</b></td>\n";
	echo "       <td><b>NAMA SEKOLAH</b></td>\n";
	echo "       <td><div align=\"center\"><b>BILANGAN</b></div></td>\n";
	echo "     </tr>\n";

	$qbm=oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE status= :status AND kodppd= :kodppd ORDER BY namasek");
	oci_bind_by_name($qbm, ':status', $status);
	oci_bind_by_name($qbm, ':kodppd', $kodppd);
	oci_execute($qbm);
	$i=0;
	$bil=0;
	while($rbm = oci_fetch_array($qbm)){
	$bil=$bil+1;
	$ksek = $rbm["KODSEK"];
	$kodjenissek = $rbm["KODJENISSEKOLAH"];
	if($kodjenissek=="103" or $kodjenissek=="104")//SJKC & SJKT
		$bilgred = $bilgred2;
		
		echo "     <tr>\n";
		echo "       <td><center>$bil</center></td>\n";
		echo "       <td align=left><a href=nmiss-dae-senarai.php?data=".$rbm["KODSEK"]."/".$tahun."/".$ting."/".$jpep." target=_blank>".$rbm["NAMASEK"]."</a></td>\n";
		
		$qnmiss="SELECT * FROM $penilaian WHERE kodsek= :kodsek AND tahun= :tahun AND $tahap= :ting AND jpep= :jpep AND $bilgred";
		
		$stmt = oci_parse($conn_sispa,$qnmiss);
		oci_bind_by_name($stmt, ':kodsek', $rbm[KODSEK]);
		oci_bind_by_name($stmt, ':tahun', $tahun);
		oci_bind_by_name($stmt, ':ting', $ting);
		oci_bind_by_name($stmt, ':jpep', $jpep);		
		oci_execute($stmt);
		$bilnmiss=count_row("SELECT * FROM $penilaian WHERE kodsek= :kodsek AND tahun= :tahun AND $tahap= :ting AND jpep= :jpep AND $bilgred");

		echo "       <td><center>$bilnmiss</center></td>\n";
		echo "     </tr>\n";
		$jumbilnmiss=$jumbilnmiss+$bilnmiss;
		
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

}
else { ?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='nmiss-dae.php?status='+val;
		}
		</script>

		<?php
		
		echo " <center></b>NEAR MISS CEMERLANG</b></center>";
		echo "<br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";

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

     	echo "<td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
		echo "<form method=post name='f1' action='nmiss-dae.php'>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>JENIS SEKOLAH</td><td><select name=\"status\" onchange=\"reload(this.form)\">";
		?>
		
			
		<option <?php if ($status=="SR") echo " selected "; ?> value="SR">SEKOLAH RENDAH</option>
		<option <?php if ($status=="SM") echo " selected "; ?> value="SM">SEKOLAH MENENGAH</option>
		
        <?php echo "</select>";
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

			case "SR" : echo "<option value=\"D1\">D1</option>";
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
		while($row = oci_fetch_array($SQLpep)) {
			echo  "<option value='".$row["KOD"]."'>".$row["JENIS"]."</option>";
		}
		echo "</select>";
		echo "</td></tr>";


		
		echo "</tr></table><br><br>";
		 print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";
		echo "<center><input type='submit' name=\"nmiss\" value=\"Hantar\"></center>";

		echo "</form>";
} ?>
</td>



<?php include 'kaki.php';?>


