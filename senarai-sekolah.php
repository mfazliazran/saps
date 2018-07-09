<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
//include 'menu.php';
include 'fungsi2.php';
include('include/function.php');
?>
<td valign="top" class="rightColumn">
<p class="subHeader">RINGKASAN MAKLUMAT ASAS PENDIDIKAN SELURUH MALAYSIA<font color="#FFFFFF">(Tarikh Kemaskini 02/03/2012) </font></p>

<?php	
$bil=1;
echo "<h5><center>SENARAI SEKOLAH-SEKOLAH SELURUH MALAYSIA </center></h5>";
echo "<table width=\"45%\"  border=\"1\" align=\"center\" cellpadding=\"2\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr>";
echo "<td bgcolor=\"#FF9933\" colspan=\"1\"><div align=\"center\">BIL</div></td>\n";
echo "<td bgcolor=\"#FF9933\" colspan=\"1\"><div align=\"center\">KOD SEKOLAH</div></td>\n";
echo "<td bgcolor=\"#FF9933\" colspan=\"1\"><div align=\"center\">NAMA SEKOLAH</div></td>\n";
echo "<td bgcolor=\"#FF9933\" colspan=\"1\"><div align=\"center\">JENIS</div></td>\n";
echo "<td bgcolor=\"#FF9933\" colspan=\"1\"><div align=\"center\">GRED</div></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "</tr>\n";
echo "<tr>\n";

$pg=(int) $_GET["pg"];
if ($pg==0)
 $pg=1;

$recordpage=30;
$startrec=($pg-1)*$recordpage+1;
$endrec=($startrec+$recordpage)-1;  
$rowstart=($pg-1)*30;

	$jenis="SELECT tkjenissekolah.JENISSEKOLAH, tsekolah.GREDSEKOLAH, tsekolah.KODSEK, tsekolah.NAMASEK FROM tkjenissekolah LEFT JOIN tsekolah ON tsekolah.KODJENISSEKOLAH = tkjenissekolah.KODJENISSEKOLAH ORDER BY tsekolah.KODSEK ";
	//echo $jenis;
	$totalrecord=count_row($jenis);
  	$qrystr2="select * from ( select a.*,rownum rnum from ($jenis)a where rownum<=$endrec) where rnum>=$startrec";
  	$result_sm = oci_parse($conn_sispa,$qrystr2);
		$rowcnt=0;
		oci_execute($result_sm);
		$bil=$rowstart;
	while($res=oci_fetch_array($result_sm)){
		$bil=$bil+1;
		$rowcnt++;
	
		$sekolah=$res['NAMASEK'];
		$kodsekolah=$res['KODSEK'];
		$gred=$res['GREDSEKOLAH'];
		$jsekolah=$res['JENISSEKOLAH'];
			
echo "<td ><div align=\"center\">".$bil."</div></td>\n";
echo "<td ><div align=\"center\">$kodsekolah</div></td>\n";
echo "<td ><div align=\"center\">$sekolah</div></td>\n";	
echo "<td ><div align=\"center\">$jsekolah</div></td>\n";
echo "<td ><div align=\"center\">$gred</div></td>\n";	
echo "</tr>\n";
}	
if ($rowcnt==0){
  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"8\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";

}	
?>	
<tr bgcolor="#FFFFFF"><td colspan="15">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"senarai-sekolah.php?cari=1",$pg);

echo "</table>\n";
echo "<center><br><a href=cari-sekolah.php>Klik disini untuk carian sekolah</a></center>\n";
?>
<?php include 'kaki.php';?> 