<?php 
include("config.php");
include 'fungsi.php';

$m = $_GET['data'];
list ($tahun, $kodsek, $ting, $kelas)=split('[/]', $m);

$tg = strtolower($ting);

switch ($ting)
{
	case "D1": case "D2": case "D3": case "D4": case "D5": case "D6": 
		$tmurid = "tmuridsr";
		break;
	
	case "P": case "T1": case "T2": case "T3": case "T4": case "T5": 
		$tmurid = "tmurid";
		break;
}

if (($ting == "") AND ($kelas == ""))
{
	$sqlgurukelas = "DELETE FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='' AND kelas=''";
	$result2 = oci_parse($conn_sispa,$sqlgurukelas); 
	oci_execute($result2);

	//require 'semak_guru_kelas.php';

}
else {
		$qsemak = oci_parse($conn_sispa,"SELECT * FROM $tmurid  WHERE tahun$tg='$tahun' AND kodsek$tg='$kodsek' AND $tg='$ting' AND kelas$tg='$kelas'");
		oci_execute($qsemak);
		$ada = count_row("SELECT * FROM $tmurid  WHERE tahun$tg='$tahun' AND kodsek$tg='$kodsek' AND $tg='$ting' AND kelas$tg='$kelas'"); 
		
		if ( $ada == 0 )
		{
			$sqlgurukelas = "DELETE FROM tguru_kelas WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'";
			$result2 = oci_parse($conn_sispa,$sqlgurukelas); 
			oci_execute($result2);
			
			$qsemaktk = oci_parse($conn_sispa,"SELECT * FROM tkelassek WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
			oci_execute($qsemaktk);
			$adaktk = count_row("SELECT * FROM tkelassek WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'"); 
			if ( $adaktk == 1 )
			{
				$sqlkelas="DELETE FROM tkelassek WHERE tahun='$tahun' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'";
				$result1 = oci_parse($conn_sispa,$sqlkelas);
				oci_execute($result1);
			}
			
			//require 'semak_guru_kelas.php';
		}
		//else {
		//		require 'semak_guru_kelas.php';
		//	 }
}
//mysql_close();

//require 'semak_guru_kelas.php';
location('semak_guru_kelas.php');
?>