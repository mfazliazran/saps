<?php
//include 'kepala.php';
session_start();
include 'config.php';
$tahuntov = $_POST['tahuntov'];
$tahun = $_POST['tahun'];
$kodsek = trim($_POST["kodsek"]);
$nokp = $_POST["nokp"];
$nama = $_POST["nama"];
$markah = $_POST["markah"];
$ting = $_POST["ting"];
$tg = strtolower($_POST["ting"]);
$kelas = $_POST["kelas"];
$mp = $_POST["mp"];
$jantina = $_POST["jantina"];
$kaum = $_POST["kaum"];
$agama = $_POST["agama"];
$target = $_POST["target"];
$bil = $_POST["bilpel"];

if ($_SESSION['statussek']=="SM"){
	$tmarkah="markah_pelajar";
	$theadcount="headcount";
	$theadcount2="headcount_temp";
	$tmurid="tmurid";
	//$tmp="mpsmkc";
	$tahap="TING";
}

if ($_SESSION['statussek']=="SR"){
	$tmarkah="markah_pelajarsr";
	$theadcount="headcountsr";
	$theadcount2="headcountsr_temp";
	$tmurid="tmuridsr";
	//$tmp="mpsr";
	$tahap="DARJAH";
}
	
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

$key=0;
if (is_array($nokp))
{
	while ($bil>0)
	{
		$item_qty = $nokp[$key];
		$namapel = oci_escape_string($nama[$key]);
		$jan = $jantina[$key];
		$ag = $agama[$key];
		$kaum = $kaum[$key];
		$marks = $markah[$key];
		$gmarks= kira_gred($marks, $carigred);
		$sasaran = $target[$key];
		//if (($marks!='') AND ($sasaran!='')){
			$gsasaran = kira_gred($sasaran, $carigred);
			$nt = $sasaran - $marks;
			$otr1 = (round($nt*0.25) + $marks);
			$gotr1 = kira_gred($otr1, $carigred);
			$otr2 = (round($nt*0.5) + $marks);
			$gotr2 = kira_gred($otr2, $carigred);
			$otr3 = (round($nt*0.75) + $marks);
			$gotr3 = kira_gred($otr3, $carigred);
			$q_tovpel = oci_parse($conn_sispa,"SELECT * FROM $theadcount WHERE nokp='$item_qty' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$mp'");//dah index INDEX_HEADCOUNT2
			oci_execute($q_tovpel);
			$q_tov = oci_fetch_array($q_tovpel);
			//$r_hc_pel= mysql_query("SELECT * FROM $theadcount WHERE nokp='$item_qty' AND kodsek='$kodsek' AND tahun='$tahun' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$mp'") or die('Error, query failed bilang pelajar dalam tmurid ikut mp kelas');
			//echo "1. item_qty:$item_qty kodsek:$kodsek tahun:$tahun ting:$ting kelas:$kelas hmp:$mp<br>";
			//echo "2. item_qty:".$q_tov['NOKP']." kodsek:".$q_tov['KODSEK']." tahun:".$q_tov['TAHUN']." ting:".$q_tov["$tahap"]." kelas:$kelas hmp:".$q_tov['HMP']."<br>";
			//echo $_SESSION['kodsek']."=='$kodsek'--- ".$_SESSION['nokp']."<br>";

$sql="SELECT KODSEK FROM login WHERE user1='".$_SESSION['SESS_MEMBER_ID']."' and pswd='".$_SESSION['SESS_PASSWORD']."'";
$stmt=OCIParse($conn_sispa,$sql);
OCIExecute($stmt);
//$bil=count_row($sql);

if(OCIFetch($stmt)){
	$kodsekguru = OCIResult($stmt,"KODSEK");
}
OCIFreeStatement($stmt);

//echo $kodsekguru."=='$kodsek'--- ".$_SESSION['nokp']."<br>";
		if ($kodsekguru==$kodsek){
			if (($item_qty==$q_tov['NOKP']) AND ($kodsek==$q_tov['KODSEK']) AND ($tahun==$q_tov['TAHUN']) AND ($ting==$q_tov["$tahap"]) AND ($kelas==$q_tov['KELAS']) AND ($mp==$q_tov['HMP'])){
				//die ("masuk update<br>");	
					//echo "UPDATE  $theadcount SET nama='$namapel', tov='$marks', gtov='$gmarks', nt='$nt', otr1='$otr1', gotr1='$gotr1', otr2='$otr2', gotr2='$gotr2', otr3='$otr3', gotr3='$gotr3', etr='$sasaran', getr='$gsasaran' WHERE nokp='$item_qty' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$mp'<br>";
					$stmt = oci_parse($conn_sispa,"UPDATE  $theadcount SET nama='$namapel', tov='$marks', gtov='$gmarks', nt='$nt', otr1='$otr1', gotr1='$gotr1', otr2='$otr2', gotr2='$gotr2', otr3='$otr3', gotr3='$gotr3', etr='$sasaran', getr='$gsasaran' WHERE nokp='$item_qty' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$mp'");
					oci_execute($stmt);
			} else {
				//die ("masuk insert<br>");	
					//echo "INSERT INTO $theadcount(nokp, nama, tahun, kodsek, $tahap, kelas, hmp, tov, gtov, nt, otr1, gotr1, otr2, gotr2, otr3, gotr3, etr, getr, jantina, agama, kaum) VALUES ('$item_qty','$namapel','$tahun','$kodsek','$ting','$kelas','$mp','$marks','$gmarks','$nt','$otr1','$gotr1','$otr2','$gotr2','$otr3','$gotr3','$sasaran','$gsasaran','$jan','$ag','$kaum')<br>";
			        $stmt = oci_parse($conn_sispa,"INSERT INTO $theadcount(nokp, nama, tahun, kodsek, $tahap, kelas, hmp, tov, gtov, nt, otr1, gotr1, otr2, gotr2, otr3, gotr3, etr, getr, jantina, agama, kaum) VALUES ('$item_qty','$namapel','$tahun','$kodsek','$ting','$kelas','$mp','$marks','$gmarks','$nt','$otr1','$gotr1','$otr2','$gotr2','$otr3','$gotr3','$sasaran','$gsasaran','$jan','$ag','$kaum')");
					oci_execute($stmt);
					//if($kodsek=='TBA6092')
						//echo "INSERT INTO $theadcount(nokp, nama, tahun, kodsek, $tahap, kelas, hmp, tov, gtov, nt, otr1, gotr1, otr2, gotr2, otr3, gotr3, etr, getr, jantina, agama, kaum) VALUES ('$item_qty','$namapel','$tahun','$kodsek','$ting','$kelas','$mp','$marks','$gmarks','$nt','$otr1','$gotr1','$otr2','$gotr2','$otr3','$gotr3','$sasaran','$gsasaran','$jan','$ag','$kaum')<br>";
				
            }//end $q_tovpel
		}//if session
		else{
				$tarikh_temp= date ("d/m/Y H:i:s");
				//if($kodsek=='TBA6092')
					//die('Masuk');
				 //echo "fff";
				 $stmt = oci_parse($conn_sispa,"INSERT INTO $theadcount2(nokp, nama, tahun, kodsek, $tahap, kelas, hmp, tov, gtov, nt, otr1, gotr1, otr2, gotr2, otr3, gotr3, etr, getr, jantina, agama, kaum, idd_temp, nokp_temp, tarikh_temp) VALUES ('$item_qty','$namapel','$tahun','$kodsek','$ting','$kelas','$mp','$marks','$gmarks','$nt','$otr1','$gotr1','$otr2','$gotr2','$otr3','$gotr3','$sasaran','$gsasaran','$jan','$ag','$kaum','".$_SESSION['SESS_MEMBER_ID']."','".$_SESSION['nokp']."',to_date('$tarikh_temp','DD/MM/YYYY hh24:mi:ss'))");
				oci_execute($stmt);
					
			//die ( "INSERT INTO $theadcount2(nokp, nama, tahun, kodsek, $tahap, kelas, hmp, tov, gtov, nt, otr1, gotr1, otr2, gotr2, otr3, gotr3, etr, getr, jantina, agama, kaum, idd_temp, nokp_temp, tarikh_temp) VALUES ('$item_qty','$namapel','$tahun','$kodsek','$ting','$kelas','$mp','$marks','$gmarks','$nt','$otr1','$gotr1','$otr2','$gotr2','$otr3','$gotr3','$sasaran','$gsasaran','$jan','$ag','$kaum','".$_SESSION['SESS_MEMBER_ID']."','".$_SESSION['nokp']."',to_date('$tarikh_temp','DD/MM/YYYY hh24:mi:ss'))");
		} 
        //}
		$bil--; $key++;
	}//end while bil >0
}


//header('Location: nilaitambah.php');
?>
<script>alert('MARKAH TOV TELAH DISIMPAN !')
location.href='nilaitambah.php';
</script> 
<?php 
//Funsi mengira gred markah
////////////////////////////////////////////////////////
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
?>
