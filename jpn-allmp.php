
<?php

include 'config.php';
include 'fungsi.php';

$bilsek = $_POST['bilsek']; 
$kodppd =  $_POST['kodppd']; 
$tahun =  $_POST['tahun']; 
$status =  $_POST['status']; 
$kodmp =  $_POST['kodmp']; 
$ting =  $_POST['ting']; 
$bilmurid = $_POST['bilmurid']; 
$biltovL =  $_POST['biltovL']; 
$biltovC =  $_POST['biltovC']; 
$bilpptL =  $_POST['bilatr1L']; 
$bilpptC =  $_POST['bilatr1C']; 
$bilpatL =  $_POST['bilcubaL']; 
$bilpatC =  $_POST['bilcubaC']; 
$biletrL =  $_POST['biletrL']; 
$biletrC =  $_POST['biletrC']; 

for ( $i = 1; $i<= $bilsek; $i++){
//echo "$status  | $kodppd | $kodmp[$i] | $tahun | $bilsek | $ting | $bilmurid[$i]"; 

$sql = "select * from jpnhc_allmp where kodppd='$kodppd'  and kodmp='$kodmp[$i]' and status='$status' and tahun='$tahun' and ting='$ting' order by tahun";
$qry = oci_parse($conn_sispa,$sql);
oci_execute($qry);
$num_rows = count_row($sql);

if ($num_rows == 0):  
//echo "dah ada";

	$mysql = oci_parse($conn_sispa,"insert into jpnhc_allmp (kodppd, ting, status, tahun, kodmp, bcalon, tovlulus, tovcmlang, pptlulus, pptcmlang, patlulus,patcmlang, etrlulus, etrcmlang) VALUES ('$kodppd', '$ting', '$status','$tahun', '$kodmp[$i]','$bilmurid[$i]', '$biltovL[$i]', '$biltovC[$i]', '$bilpptL[$i]', '$bilpptC[$i]', '$bilpatL[$i]', '$bilpatC[$i]', '$biletrL[$i]', '$biletrC[$i]')");
	oci_execute($mysql);

else:
//echo "update";
	$query = oci_parse($conn_sispa,"update jpnhc_allmp set kodmp = '$kodmp[$i]',
                         bcalon = '$bilmurid[$i]',
						 tovlulus = '$biltovL[$i]',
                         tovcmlang = '$biltovC[$i]',
                         pptlulus = '$bilpptL[$i]',
						 pptcmlang = '$bilpptC[$i]',
						 patlulus = '$bilpatL[$i]',
						 patcmlang = '$bilpatC[$i]',
						 etrlulus = '$biletrL[$i]', 
                         etrcmlang = '$biletrC[$i]' where kodppd = '$kodppd' and status='$status' and tahun='$tahun' and ting='$ting'  and kodmp='$kodmp[$i]'");
	oci_execute($query);
endif;

}
message("Laporan PPD Telah Dihantar ke JPN",1);
     location("hcmpkes-daerah.php?tahun=$tahun&&ting=$ting&&kodppd=$kodppd&&status=$status");

?>