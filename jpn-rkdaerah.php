
<?php

include 'config.php';
include 'fungsi.php';

$bil = $_POST['bil']; 
$kodppd =  $_POST['kodppd']; 
$kodsek =  $_POST['kodsek']; 
$tahun =  $_POST['tahun']; 
$status =  $_POST['status']; 
$kelas =  $_POST['kelas']; 
$ting =  $_POST['ting']; 
$bcalon = $_POST['bilmp']; 
$Atov =  $_POST['biltovSA']; 
$Aar1 =  $_POST['bilatrSA']; 
$Aar2 =  $_POST['bilatr2SA']; 
$Aetr =  $_POST['biletrSA']; 
$Dtov =  $_POST['biltovSD']; 
$Dar1 =  $_POST['bilatrSD']; 
$Dar2 =  $_POST['bilatr2SD']; 
$Detr =  $_POST['biletrSD']; 

for ( $i = 1; $i<= $bil; $i++){
//echo "$kodppd | $kodsek[$i] | $tahun | $kelas[$i] | $ting | $bcalon[$i] | $Atov[$i] | $Aar1[$i] | $Aar2[$i] | $Aetr[$i] | $Dtov[$i] | $Dar1[$i] | $Dar2[$i] | $Detr[$i]<br>"; 

$sql = "select * from jpnhc_analisark where kodppd='$kodppd' and status='$status' and tahun='$tahun' and ting='$ting' order by tahun";
$qry = oci_parse($conn_sispa,$sql);
oci_execute($qry);
$num_rows = count_row($sql);

if ($num_rows == 0){
	$mysql = oci_parse($conn_sispa,"insert  into jpnhc_analisark (kodppd, kodsek,ting, status, tahun, kelas, bcalon, Atov, Aar1, Aar2, Aetr, Dtov,Dar1, Dar2, Detr) VALUES ('$kodppd', '$kodsek[$i]','$ting', '$status[$i]','$tahun', '$kelas[$i]','$bcalon[$i]', '$Atov[$i]', '$Aar1[$i]', '$Aar2[$i]', '$Aetr[$i]', '$Dtov[$i]', '$Dar1[$i]', '$Dar2[$i]', '$Detr[$i]')");
	oci_execute($mysql);

} else {
		$query = oci_parse($conn_sispa,"update jpnhc_analisark set kelas = '$kelas[$i]',
                         bcalon = '$bcalon[$i]',
						 Atov = '$Atov[$i]',
                         Aar1 = '$Aar1[$i]',
                         Aar2 = '$Aar2[$i]',
						 Aetr = '$Aetr[$i]',
						 Dtov = '$Dtov[$i]',
						 Dar1 = '$Dar1[$i]',
						 Dar2 = '$Dar2[$i]', 
                         Detr = '$Detr[$i]' where kodppd = '$kodppd' and status='$status' and tahun='$tahun' and ting='$ting'");
		oci_execute($query);
		}
}
message("Laporan PPD Telah Dihantar ke JPN",1);
location("analisa-rkdaerah.php?tahun=$tahun&&ting=$ting&&kodppd=$kodppd&&status=$status");


?>