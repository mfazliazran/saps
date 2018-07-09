<?php
session_start();
//include 'menu.php';
include 'config.php';
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
$tahuntov = $_POST["tahuntov"];
$tahun = $_SESSION['tahun'];
$kodsek = $_POST["kodsek"];
$nokp = $_POST["nokp"];
$nama = $_POST["nama"];
$markah = $_POST["markah"];
$ting = $_POST["ting"];
$tingtov = $_POST["tingtov"];
$kelas = $_POST["kelas"];
$kod = $_POST["mp"];
$jpeptov = $_POST["jpeptov"];
$jantina = $_POST["jantina"];
$kaum = $_POST["kaum"];
$agama = $_POST["agama"];
$bilpel= $_POST["bil"];

$rujuk_r=array("T2"=>"T3", "T3"=>"T4", "T4"=>"T5", "T5"=>"T5", "D2"=>"D3", "D3"=>"D4", "D4"=>"D5", "D5"=>"D6", "D6"=>"D6");
	$tingr=$rujuk_r[$ting];
	$tgr=strtolower($tingr);
	$tgu=strtoupper($tingr);

if ($_SESSION['statussek']=="SM"){
	$tmarkah="markah_pelajar";
	$tmurid="tmurid";
	$tahap="ting";
	$theadcount="headcount";
}

if ($_SESSION['statussek']=="SR"){
	$tmarkah="markah_pelajarsr";
	$tmurid="tmuridsr";
	$tahap="darjah";
	$theadcount="headcountsr";
}

$gmp="G$kod";

switch ($ting)
{
	case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
		$level="SR";
		break;
	case "P": case "T1": case "T2": case "T3":
		$level="MR";
		break;
	case "T4": case "T5":
		$level="MA";
		break;
}

$key=0;
if (is_array($nokp))
{
	while ($bilpel>0)
	{
		$item_qty = $nokp[$key];
		$namapel = $nama[$key];
		$jan = $jantina[$key];
		$ag = $agama[$key];
		$bangsa = $kaum[$key];
		$marks = $markah[$key];
		if ($marks!=''){
			$gred= kira_gred($marks, $level);
	
			$querypel = oci_parse($conn_sispa,"SELECT nokp, nama, jpep, tahun, kodsek, $tahap, kelas, $kod, $gmp FROM $tmarkah WHERE nokp='$item_qty' AND tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov'");
			oci_execute($querypel);
		
			if (count_row("SELECT nokp, nama, jpep, tahun, kodsek, $tahap, kelas, $kod, $gmp FROM $tmarkah WHERE nokp='$item_qty' AND tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov'")>0){
				$stmt = oci_parse($conn_sispa,"UPDATE $tmarkah SET $kod='$marks', $gmp='$gred' WHERE nokp='$item_qty' AND tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov'");
				oci_execute($stmt);
			}else { $stmt = oci_parse($conn_sispa,"INSERT INTO $tmarkah(nokp, nama, tahun, kodsek, $tahap, kelas, jpep, jantina, agama, kaum, $kod, $gmp) VALUES ('$item_qty','$namapel','$tahuntov','$kodsek','$tingtov','$kelas','$jpeptov','$jan','$ag','$bangsa','$marks','$gred')"); 
			oci_execute($stmt);
			}
	
			$qtov = oci_parse($conn_sispa,"SELECT * FROM $theadcount WHERE  nokp='$item_qty' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND $tahap='$tingr' AND kelas='$kelas' AND tov!='$marks' AND hmp='$kod'");
			oci_execute($qtov);
			if (count_row("SELECT * FROM $theadcount WHERE  nokp='$item_qty' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND $tahap='$tingr' AND kelas='$kelas' AND tov!='$marks' AND hmp='$kod'")>0){
				$stmt = oci_parse($conn_sispa,"UPDATE $theadcount SET tov='$marks', gtov='$gmarks', nt='', otr1='', gotr1='', otr2='', gotr2='', otr3='', gotr3='', etr='', getr='' WHERE  nokp='$item_qty' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND $tahap='$tingr' AND kelas='$kelas' AND hmp='$kod'");
				oci_execute($stmt);
			}
		} else {
					$stmt = oci_parse($conn_sispa,"UPDATE $tmarkah SET $kod='', $gmp='' WHERE nokp='$item_qty' AND tahun='$tahuntov' AND kodsek='$kodsek' AND $tahap='$tingtov' AND jpep='$jpeptov'");
					oci_execute($stmt);
					$stmt = oci_parse($conn_sispa,"DELETE FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$tingr' AND kelas='$kelas' AND nokp='$item_qty' AND hmp='$kod'");
					oci_execute($stmt);
				}
		$bilpel--; $key++;
	}
	//echo "<th width=\"80%\" bgcolor=\"#FFFFFF\" valign=\"top\" scope=\"col\">";
	require('papar_subjek_tov.php');
	?> <script>alert('MARKAH TELAH DIKEMASKINI')</script> <?php
}

//////////////////// FUNGSI Kira GRED
function kira_gred($marks, $level){
	$querygred=oci_parse($conn_sispa,"SELECT * FROM gred WHERE tahap='$level' AND min <= '$marks'  AND max >= '$marks'");
	oci_execute($querygred);
	if ((count_row("SELECT * FROM gred WHERE tahap='$level' AND min <= '$marks'  AND max >= '$marks'")!=0) AND ($marks!="")){
		$gred1 = oci_fetch_array($querygred);	
		$grednye = $gred1['GRED'];
	} else { $grednye = " "; }	
	//echo "$level  $marks   $grednye<br>";
	return $grednye;
}
?>