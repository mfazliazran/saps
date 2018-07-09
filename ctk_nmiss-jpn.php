<html>
<body>
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

	$tahun=$_GET['tahun'];
	$ting=$_GET['ting'];
	$jpep=$_GET['jpep'];


	echo " <div align=\"center\"><span class=\"style3\">DATA NEAR MISS KECEMERLANGAN<br>$capai<br>".tahap($ting)."<br>".jpep($jpep)."<br>TAHUN $tahun</span><br><br>\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
	echo "   <table width=\"40%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
	echo "     <tr>\n";
	echo "       <td align=\"center\" valign=\"top\"><span class=\"style3\">BIL</span></td>\n";
	echo "       <td align=\"center\" valign=\"top\"><span class=\"style3\">NAMA DAERAH</span></td>\n";
	echo "       <td align=\"center\" valign=\"top\"><span class=\"style3\">BILANGAN</span></td>\n";
	echo "     </tr>\n";
//*****************************************************************


$qppd=oci_parse($conn_sispa,"SELECT * FROM tkppd ORDER BY KodPPD");
oci_execute($qppd);
while($rppd = oci_fetch_array($qppd))
{
	$bilppd = $bilppd + 1;


	$qbm = oci_parse($conn_sispa,"SELECT *, sum(bcalon) as bilc FROM jpnrmiss WHERE tahun='$tahun' AND ting='$ting' AND jpep='$jpep' GROUP BY kodppd");
	oci_execute($qbm);

///****************************************************************
//	$qbm = mysql_query("SELECT *, sum(bcalon) as bilc FROM jpnrmiss WHERE tahun='$tahun' AND ting='$ting' AND jpep='$jpep' GROUP BY kodppd") OR die(mysql_query());

	$i = 0;
	while($rbm = oci_fetch_array($qbm)){
		$bil=$bil+1;
		echo "     <tr>\n";
		echo "       <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
		echo "       <td valign=\"top\"><span class=\"style2\">$rppd[PPD]</span></td>\n";
		$bilcer = $rbm['bilc'];
		echo "       <td align=\"center\" valign=\"top\"><span class=\"style2\">$bilcer</span></td>\n";
		echo "     </tr>\n";
		$jumcer = $jumcer + $bilcer;
	}
}	
echo "     <tr>\n";
echo "       <td colspan=\"2\" align=\"center\" valign=\"top\"><span class=\"style2\">JUMLAH</span></td>\n";
echo "       <td align=\"center\" valign=\"top\"><span class=\"style2\">$jumcer</span></td>\n";
echo "     </tr>\n";
echo "   </table>\n";
echo "<br><br><br>";
?>
<?php 	
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
?>

                       
