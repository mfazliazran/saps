<?php
session_start();
include "../config.php";
$nokp=$_GET["nokp"];
$ting=$_GET["ting"];
$kelas=$_GET["kelas"];
$kod=$_GET["mp"];
$mp=$_GET["mp"];
$jpep=$_GET["jpep"];
$tov=$_GET["tov"];
$etr=$_GET["etr"];
$tahun=$_GET["tahun"];
$kodsek=$_SESSION["kodsek"];


if ($_SESSION['statussek']=="SM"){
	$tmarkah="markah_pelajar";
	$theadcount="headcount";
	$tmurid="tmurid";
	//$tmp="mpsmkc";
	$tahap="TING";
}

if ($_SESSION['statussek']=="SR"){
	$tmarkah="markah_pelajarsr";
	$theadcount="headcountsr";
	$tmurid="tmuridsr";
	//$tmp="mpsr";
	$tahap="DARJAH";
}

$q_murid = oci_parse($conn_sispa,"SELECT namap,jantina,kaum,agama FROM $tmurid WHERE nokp='$nokp'
    AND tahun$ting='$tahun' AND kodsek$ting='$kodsek'");

oci_execute($q_murid);
 $data_m= oci_fetch_array($q_murid);
 $namapel=$data_m["NAMAP"];
 $jantina=$data_m["JANTINA"];
 $kaum=$data_m["KAUM"];
 $agama=$data_m["AGAMA"];

switch ($ting)
{
	case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
		$level="SR";
		break;
	case "P" : case "T1": case "T2": case "T3":
		$level="MR";
		break;
	case "T4": case "T5":
		$level="MA";
		break;
}

/////////////////////////////////////// Masuk Koding Baru /////////////////////////////////////
$q_gred = oci_parse($conn_sispa,"SELECT * FROM gred WHERE tahap='$level' ORDER BY max");
oci_execute($q_gred);
//////////////////////////////////////////////////////////////////////////////////////////////
$carigred = array();
while($row = oci_fetch_array($q_gred))
{
	$carigred[] = $row;
}


		$item_qty = $nokp;
		$namapel = oci_escape_string($namapel);
		$jan = $jantina;
		$ag = $agama;
		$kaum = $kaum;

			$gtov = kira_gred($tov,$carigred);
			$getr = kira_gred($etr, $carigred);
			$nt = $etr - $marks;
			$otr1 = (round($nt*0.25) + $marks);
			$gotr1 = kira_gred($otr1, $carigred);
			$otr2 = (round($nt*0.5) + $marks);
			$gotr2 = kira_gred($otr2, $carigred);
			$otr3 = (round($nt*0.75) + $marks);
			$gotr3 = kira_gred($otr3, $carigred);
			$q_tovpel = oci_parse($conn_sispa,"SELECT * FROM $theadcount WHERE nokp='$item_qty' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND 			kelas='$kelas' AND hmp='$mp'");
			oci_execute($q_tovpel);
			$q_tov = oci_fetch_array($q_tovpel);
			
			if (($item_qty==$q_tov['NOKP']) AND ($kodsek==$q_tov['KODSEK']) AND ($tahun==$q_tov['TAHUN']) AND ($ting==$q_tov["$tahap"]) AND ($kelas==$q_tov['KELAS']) AND ($mp==$q_tov['HMP'])){
				$sql="UPDATE  $theadcount SET tov='$tov', gtov='$gtov', nt='$nt', otr1='$otr1', gotr1='$gotr1', otr2='$otr2', gotr2='$gotr2', otr3='$otr3', gotr3='$gotr3', etr='$etr', getr='$getr' WHERE  tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND nokp='$item_qty' AND hmp='$mp'";
					$stmt = oci_parse($conn_sispa,$sql);
					oci_execute($stmt);
			} else {
				$sql="INSERT INTO $theadcount(nokp, nama, tahun, kodsek, $tahap, kelas, hmp, tov, gtov, nt, otr1, gotr1, otr2, gotr2, otr3, gotr3, etr, getr, jantina, agama, kaum) VALUES ('$item_qty','$namapel','$tahun','$kodsek','$ting','$kelas','$mp','$tov','$gtov','$nt','$otr1','$gotr1','$otr2','$gotr2','$otr3','$gotr3','$etr','$getr','$jan','$ag','$kaum')";
			        $stmt = oci_parse($conn_sispa,$sql);
					oci_execute($stmt);
				
            }
			//echo $stmt;
			echo "$tov|$etr";



function kira_gred($marks,  $carigred){
	foreach($carigred as $rowNum => $row) {
	//$row would be same as the original $row inside the while loop
		if (($marks == 'TH') OR ($marks == 'th'))
		{
			$grednye = 'TH';
			break;
		}
		if (($marks >= $row['MIN']) AND ($marks <= $row['MAX']))
		{
			$grednye = $row['GRED'];
			break;
		}
	}
return $grednye;
}


//die ("stmt: $sql<br>");	
//header('Location: nilaitambah.php');
?>
