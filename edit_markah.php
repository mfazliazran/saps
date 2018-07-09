<?php
session_start();
include 'config.php';

//include 'menu.php';

//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";

$tahun = $_POST["tahun"];

$kodsek = $_POST["kodsek"];

$nokp = $_POST["nokp"];


$markah = $_POST["markah"];

$ting = $_POST["ting"];

$kelas = $_POST["kelas"];

$kod = $_POST["mp"];

$jpep = $_POST["jpep"];

$qtentu_hc = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='$tahun' AND jenpep='$jpep'");
oci_execute($qtentu_hc);

$jenisatr=oci_fetch_array($qtentu_hc);

$capai=strtolower($jenisatr['CAPAI']);

if ($_SESSION['statussek']=="SM"){

	$tmarkah="markah_pelajar";

	$theadcount="headcount";

	$tmurid="tmurid";

	//$tmp="mpsmkc";

	$tahap="ting";

}



if ($_SESSION['statussek']=="SR"){

	$tmarkah="markah_pelajarsr";

	$theadcount="headcountsr";

	$tmurid="tmuridsr";

	//$tmp="mpsr";

	$tahap="darjah";

}

$q_murid = oci_parse($conn_sispa,"SELECT namap,jantina,kaum,agama FROM $tmurid WHERE nokp='$nokp'
    AND tahun$ting='$tahun' AND kodsek$ting='$kodsek'");
oci_execute($q_murid);
 $data_m= oci_fetch_array($q_murid);
 $namapel=oci_escape_string($data_m["NAMAP"]);
 $jantina=$data_m["JANTINA"];
 $kaum=$data_m["KAUM"];
 $agama=$data_m["AGAMA"];

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

$gmp="G$kod";

		$item_qty = $nokp;


		$jan = $jantina;

		$ag = $agama;

		$bangsa = $kaum;

		$marks = $markah;

		if ($marks!=''){
			$gred= kira_gred($marks);

            $sql="SELECT nokp, nama, jpep, tahun, kodsek, $tahap, kelas, $kod, $gmp FROM $tmarkah WHERE nokp='$item_qty' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND jpep='$jpep'";
			$querypel=oci_parse($conn_sispa,$sql);
			oci_execute($querypel);


			if (count_row($sql)!=0){
			    //die("UPDATE $tmarkah SET $kod='$marks', $gmp='$gred' WHERE nokp='$item_qty' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep'<br>");
				$stmt = oci_parse($conn_sispa,"UPDATE $tmarkah SET $kod='$marks', $gmp='$gred' WHERE nokp='$item_qty' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep'");
				oci_execute($stmt);

			}else {
			//die("INSERT INTO $tmarkah(nokp, nama, tahun, kodsek, $tahap, kelas, jpep, jantina, agama, kaum, $kod, $gmp) VALUES ('$item_qty','$namapel','$tahun','$kodsek','$ting','$kelas','$jpep','$jan','$ag','$bangsa','$marks','$gred')");
			   $stmt = oci_parse($conn_sispa,"INSERT INTO $tmarkah(nokp, nama, tahun, kodsek, $tahap, kelas, jpep, jantina, agama, kaum, $kod, $gmp) VALUES ('$item_qty','$namapel','$tahun','$kodsek','$ting','$kelas','$jpep','$jan','$ag','$bangsa','$marks','$gred')"); }
			   oci_execute($stmt);
	        $qry="SELECT * FROM $theadcount WHERE  nokp='$item_qty' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND $tahap='$ting' AND kelas='$kelas' AND tov!='$marks' AND hmp='$kod'";
			$qtov = oci_parse($conn_sispa,$qry);
			oci_execute($qtov);

			if ((count_row($qry)!=0) AND ($capai=="TOV")){
						$stmt = oci_parse($conn_sispa,"UPDATE $theadcount SET tov='$marks', gtov='$gred', nt='', otr1='', gotr1='', otr2='', gotr2='', otr3='', gotr3='', etr='', getr='' WHERE  nokp='$item_qty' AND kodsek='$kodsek' AND tahun='$tahun' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kod'");
						oci_execute($stmt);

			}
		} else {
					$stmt = oci_parse($conn_sispa,"UPDATE $tmarkah SET $kod='', $gmp='' WHERE nokp='$item_qty' AND tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep'");
					oci_execute($stmt);
					$stmt = oci_parse($conn_sispa,"DELETE FROM $theadcount WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND nokp='$item_qty' AND hmp='$kod'");
					oci_execute($stmt);
  				}
//die("INSERT INTO $tmarkah(nokp, nama, tahun, kodsek, $tahap, kelas, jpep, jantina, agama, kaum, $kod, $gmp) VALUES //('$item_qty','$namapel','$tahun','$kodsek','$ting','$kelas','$jpep','$jan','$ag','$bangsa','$marks','$gred')");
	//echo "<th width=\"80%\" bgcolor=\"#FFFFFF\" valign=\"top\" scope=\"col\">";
    //require('papar_subjek.php');
	?> <script>
	location.href='b_edit_markah.php?<?php echo "data=$ting/$kelas/$kod/$tahun/$kodsek/$jpep"?>';
	</script> <?php




//Funsi mengira gred markah
////////////////////////////////////////////////////////
//function kira_gred($marks,  $level){
function kira_gred($marks){
global $conn_sispa;
global $level;

if (strtoupper($marks)=="TH") {
	$grednye="TH";
}
else {
    $markah=(int) $marks;
    $q_gred = oci_parse($conn_sispa,"SELECT * FROM gred WHERE tahap='$level' and min <= $markah and max >= $markah ");
    oci_execute($q_gred);
    $data_gred= oci_fetch_array($q_gred);
    $grednye=$data_gred["GRED"];
}

return $grednye;
}
?>