<?php
include "input_validation.php";
//include 'kepala.php';
session_start();
include 'config.php';
$tahuntov = validate($_POST['tahuntov']);
$tahun = validate($_POST['tahun']);
$kodsek = trim(validate($_POST["kodsek"]));
$nokp = validate($_POST["nokp"]);
$nama = validate($_POST["nama"]);
$markah = validate($_POST["markah"]);
$ting = validate($_POST["ting"]);
$tg = strtolower(validate($_POST["ting"]));
$kelas = validate($_POST["kelas"]);
$mp = validate($_POST["mp"]);
$jantina = validate($_POST["jantina"]);
$kaum = validate($_POST["kaum"]);
$agama = validate($_POST["agama"]);
$target = validate($_POST["target"]);
$bil = validate($_POST["bilpel"]);

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

$q_gred = oci_parse($conn_sispa,"SELECT * FROM gred WHERE tahap='$level' ORDER BY max");
oci_execute($q_gred);
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
		$gsasaran = kira_gred($sasaran, $carigred);
		$nt = $sasaran - $marks;
		$otr1 = (round($nt*0.25) + $marks);
		$gotr1 = kira_gred($otr1, $carigred);
		$otr2 = (round($nt*0.5) + $marks);
		$gotr2 = kira_gred($otr2, $carigred);
		$otr3 = (round($nt*0.75) + $marks);
		$gotr3 = kira_gred($otr3, $carigred);
		$q_tovpel = oci_parse($conn_sispa,"SELECT * FROM $theadcount WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND $tahap= :ting AND kelas= :kelas AND hmp= :mp");
		oci_bind_by_name($q_tovpel, ':nokp', $item_qty);
		oci_bind_by_name($q_tovpel, ':tahun', $tahun);
		oci_bind_by_name($q_tovpel, ':kodsek', $kodsek);
		oci_bind_by_name($q_tovpel, ':ting', $ting);
		oci_bind_by_name($q_tovpel, ':kelas', $kelas);
		oci_bind_by_name($q_tovpel, ':mp', $mp);
		oci_execute($q_tovpel);
		$q_tov = oci_fetch_array($q_tovpel);

$sql="SELECT KODSEK FROM login WHERE user1='".$_SESSION['SESS_MEMBER_ID']."' and pswd='".$_SESSION['SESS_PASSWORD']."'";
$stmt=OCIParse($conn_sispa,$sql);
OCIExecute($stmt);

if(OCIFetch($stmt)){
	$kodsekguru = OCIResult($stmt,"KODSEK");
}

OCIFreeStatement($stmt);

		if ($kodsekguru==$kodsek){
			if (($item_qty==$q_tov['NOKP']) AND ($kodsek==$q_tov['KODSEK']) AND ($tahun==$q_tov['TAHUN']) AND ($ting==$q_tov["$tahap"]) AND ($kelas==$q_tov['KELAS']) AND ($mp==$q_tov['HMP'])){
					$stmt = oci_parse($conn_sispa,"UPDATE  $theadcount SET nama= :namapel, tov= :marks, gtov='$gmarks', nt='$nt', otr1='$otr1', gotr1='$gotr1', otr2='$otr2', gotr2='$gotr2', otr3='$otr3', gotr3='$gotr3', etr= :sasaran, getr='$gsasaran' WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :kodsek AND $tahap= :ting AND kelas= :kelas AND hmp= :mp");
					oci_bind_by_name($stmt, ':namapel', $namapel);
					oci_bind_by_name($stmt, ':marks', $marks);
					oci_bind_by_name($stmt, ':sasaran', $sasaran);
					oci_bind_by_name($stmt, ':nokp', $item_qty);
					oci_bind_by_name($stmt, ':tahun', $tahun);
					oci_bind_by_name($stmt, ':kodsek', $kodsek);
					oci_bind_by_name($stmt, ':ting', $ting);
					oci_bind_by_name($stmt, ':kelas', $kelas);
					oci_bind_by_name($stmt, ':mp', $mp);
					oci_execute($stmt);
			} else {
			        $stmt = oci_parse($conn_sispa,"INSERT INTO $theadcount(nokp, nama, tahun, kodsek, $tahap, kelas, hmp, tov, gtov, nt, otr1, gotr1, otr2, gotr2, otr3, gotr3, etr, getr, jantina, agama, kaum) VALUES ( :nokp, :namapel, :tahun, :kodsek, :ting, :kelas, :mp, :marks','$gmarks','$nt','$otr1','$gotr1','$otr2','$gotr2','$otr3','$gotr3', :sasaran,'$gsasaran','$jan','$ag','$kaum')");
			        oci_bind_by_name($stmt, ':nokp', $item_qty);
					oci_bind_by_name($stmt, ':namapel', $namapel);
					oci_bind_by_name($stmt, ':tahun', $tahun);
					oci_bind_by_name($stmt, ':kodsek', $kodsek);
					oci_bind_by_name($stmt, ':ting', $ting);
					oci_bind_by_name($stmt, ':kelas', $kelas);
					oci_bind_by_name($stmt, ':mp', $mp);
					oci_bind_by_name($stmt, ':marks', $marks);
					oci_bind_by_name($stmt, ':sasaran', $sasaran);
					oci_execute($stmt);
            }
		}
		else{
				$tarikh_temp= date ("d/m/Y H:i:s");
			
				 $stmt = oci_parse($conn_sispa,"INSERT INTO $theadcount2(nokp, nama, tahun, kodsek, $tahap, kelas, hmp, tov, gtov, nt, otr1, gotr1, otr2, gotr2, otr3, gotr3, etr, getr, jantina, agama, kaum, idd_temp, nokp_temp, tarikh_temp) VALUES ( :nokp, :namapel, :tahun, :kodsek, :ting, :kelas, :mp, :marks,'$gmarks','$nt','$otr1','$gotr1','$otr2','$gotr2','$otr3','$gotr3', :sasaran,'$gsasaran', :jan, :ag, :kaum, :member_s, :nokp_s, to_date('$tarikh_temp','DD/MM/YYYY hh24:mi:ss'))");
				oci_bind_by_name($stmt, ':nokp', $item_qty);
				oci_bind_by_name($stmt, ':namapel', $namapel);
				oci_bind_by_name($stmt, ':tahun', $tahun);
				oci_bind_by_name($stmt, ':kodsek', $kodsek);
				oci_bind_by_name($stmt, ':ting', $ting);
				oci_bind_by_name($stmt, ':kelas', $kelas);
				oci_bind_by_name($stmt, ':mp', $mp);
				oci_bind_by_name($stmt, ':marks', $marks);
				oci_bind_by_name($stmt, ':sasaran', $sasaran);
				oci_bind_by_name($stmt, ':jan', $jan);
				oci_bind_by_name($stmt, ':ag', $ag);
				oci_bind_by_name($stmt, ':kaum', $kaum);
				oci_bind_by_name($stmt, ':member_s', $_SESSION['SESS_MEMBER_ID']);
				oci_bind_by_name($stmt, ':nokp_s', $_SESSION['nokp']);
				oci_execute($stmt);
					
		} 
		$bil--; $key++;
	}
}

?>
<script>alert('MARKAH TOV TELAH DISIMPAN !')
location.href='nilaitambah.php';
</script> 
<?php 
//Funsi mengira gred markah
function kira_gred($marks,  $carigred){
	foreach($carigred as $rowNum => $row) {
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
