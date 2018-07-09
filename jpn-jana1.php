<?php

include 'config.php';
include 'fungsi.php';

$bilsek = $_POST['bilsek']; 
$status = $_POST['status']; 
$kodsek =  $_POST['kodsek']; 
$ksek =$_POST['ksek']; 
$tahun =  $_POST['tahun']; 
$kodmp =  $_POST['kodmp']; 
$ting =  $_POST['ting']; 
$labsek =  $_POST['labsek']; 
$jumbil = $_POST['bilmurid']; 
$jumbiltovL =  $_POST['biltovL']; 
$jumbiltovC =  $_POST['biltovC']; 
$jumbilatr1L =  $_POST['bilatr1L']; 
$jumbilatr1C =  $_POST['bilatr1C']; 
$jumbilatr2L =  $_POST['bilatr2L']; 
$jumbilatr2C =  $_POST['bilatr2C']; 
$jumbiletrL =  $_POST['biletrL']; 
$jumbiletrC =  $_POST['biletrC']; 

for ( $i = 1; $i<= $bilsek; $i++){
//echo "$status  | $kodsek | $ksek[$i] | $tahun | $kodmp | $ting | $jumbil[$i] | $jumbiltovL[$i] | $jumbiltovC[$i] | $jumbilatr1L[$i] | $jumbilatr1C[$i] | $jumbilatr2L[$i] | $jumbilatr2C[$i] | $jumbiletrL[$i] | $jumbiletrC[$i] | $jumbiletrC[$i] | $labsek[$i]"; 

$sql = "select * from jpnhc where kodppd='$kodsek' and kodsek='$ksek[$i]' and tahun='$tahun' and ting='$ting' and kodmp='$kodmp' order by tahun";
$qry = oci_parse($conn_sispa,$sql);
oci_execute($qry);
$num_rows = count_row("select * from jpnhc where kodppd='$kodsek' and kodsek='$ksek[$i]' and tahun='$tahun' and ting='$ting' and kodmp='$kodmp' order by tahun");
if ($num_rows == 0):
	$sql1 = oci_parse($conn_sispa,"insert into jpnhc (kodppd, ting, tahun, kodmp, status, kodsek, labsek, bcalon, tovlulus, tovcmlang, pptlulus, pptcmlang, patlulus,patcmlang, etrlulus, etrcmlang) VALUES ('$kodsek', '$ting', '$tahun', '$kodmp', '$status', '$ksek[$i]', '$labsek[$i]','$jumbil[$i]', '$jumbiltovL[$i]', '$jumbiltovC[$i]', '$jumbilatr1L[$i]', '$jumbilatr1C[$i]', '$jumbilatr2L[$i]', '$jumbilatr2C[$i]', '$jumbiletrL[$i]', '$jumbiletrC[$i]')");
	oci_execute($sql1);

else:
	$sql2 = oci_parse($conn_sispa,"update jpnhc set status = '$status',
                         labsek = '$labsek[$i]',
						 bcalon = '$jumbil[$i]',
						 tovlulus = '$jumbiltovL[$i]',
                         tovcmlang = '$jumbiltovC[$i]',
                         pptlulus = '$jumbilatr1L[$i]',
						 pptcmlang = '$jumbilatr1C[$i]',
						 patlulus = '$jumbilatr2L[$i]',
						 patcmlang = '$jumbilatr2C[$i]',
						 etrlulus = '".(int) $jumbiletrL[$i]."',
                         etrcmlang = '$jumbiletrC[$i]' where kodppd = '$kodsek' and tahun='$tahun' and kodmp='$kodmp' and kodsek='$ksek[$i]'");
	oci_execute($sql2);
endif;

}
message("Laporan PPD Telah Dihantar ke JPN",1);
     location("hcmp-daerah.php?tahun=$tahun&&status=$status&&kodppd=kodppd&&ting=$ting&&jpep=$jpep");

?>