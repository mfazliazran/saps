<?php 

include 'config.php';
include "input_validation.php";

function count_row_oci_by_name5($sql,$val1,$val2,$val3,$val4,$val5,$param1,$param2,$param3,$param4,$param5){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	//echo "POS $pos :- $sql";
	$newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

	$qic = oci_parse($conn_sispa,$newsql);
	oci_bind_by_name($qic, $param1, $val1);
	oci_bind_by_name($qic, $param2, $val2);
	oci_bind_by_name($qic, $param3, $val3);
	oci_bind_by_name($qic, $param4, $val4);
	oci_bind_by_name($qic, $param5, $val5);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

function count_row_oci_by_name($sql,$val1,$param1){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	//echo "POS $pos :- $sql";
	$newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

	$qic = oci_parse($conn_sispa,$newsql);
	oci_bind_by_name($qic, $param1, $val1);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

$kodsek = validate($_POST['kodsek']);
$tahun = validate($_POST['tahun']);
$nama = trim(oci_escape_string(validate($_POST['nama'])));
$nokp = validate($_POST['nokp']);
$ting = validate($_POST['ting']);
$kelas = validate($_POST['kelas']);
$mp = validate($_POST['mp']);
$bilammp = validate($_POST['bilammp']);


	$sql_subguru="SELECT * FROM sub_guru WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND kodmp= :mp";
	$bil_subguru = count_row_oci_by_name5($sql_subguru, $tahun, $kodsek, $ting, $kelas, $mp, ":tahun", ":kodsek", ":ting", ":kelas", ":mp");
	if ($bil_subguru==0){

		$stmt=oci_parse($conn_sispa,"INSERT INTO sub_guru (tahun, kodsek, nokp, nama, ting, kelas, kodmp, bilammp) VALUES ( :tahun, :kodsek, :nokp, :nama, :ting, :kelas, :mp, :bilammp)");
		oci_bind_by_name($stmt, ':tahun', $tahun);
		oci_bind_by_name($stmt, ':kodsek', $kodsek);
		oci_bind_by_name($stmt, ':nokp', $nokp);
		oci_bind_by_name($stmt, ':nama', $nama);
		oci_bind_by_name($stmt, ':ting', $ting);
		oci_bind_by_name($stmt, ':kelas', $kelas);
		oci_bind_by_name($stmt, ':mp', $mp);
		oci_bind_by_name($stmt, ':bilammp', $bilammp);
		oci_execute($stmt);
		require 'd_sub_guru.php';
	}

	else {
		$result_subguru = oci_parse($conn_sispa,$sql_subguru);
     	oci_execute($result_subguru);
		
		if($data=oci_fetch_array($result_subguru)){
			$nama_subguru = $data["NAMA"];	
			$kelas_subguru = $data["KELAS"];
			$nokp_subguru = $data["NOKP"];
			
		$sql_login = "SELECT * FROM login WHERE kodsek= :kodsek AND nokp='$nokp_subguru'";
		$bil_login = count_row_oci_by_name($sql_login, $kodsek, ":kodsek");
		if ($bil_login==0){
			
			$sql_update = oci_parse($conn_sispa,"UPDATE sub_guru SET nokp=: nokp, nama= :nama WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND kodmp= :mp");
			oci_bind_by_name($sql_update, ':nokp', $nokp);
			oci_bind_by_name($sql_update, ':nama', $nama);
			oci_bind_by_name($sql_update, ':tahun', $tahun);
			oci_bind_by_name($sql_update, ':kodsek', $kodsek);
			oci_bind_by_name($sql_update, ':ting', $ting);
			oci_bind_by_name($sql_update, ':kelas', $kelas);
			oci_bind_by_name($sql_update, ':mp', $mp);
			oci_execute($sql_update);
		}else{
			echo "<script>alert('Mata Pelajaran telah didaftarkan oleh $nama_subguru untuk $kelas_subguru.')</script>";
		}
			pageredirect("d_sub_guru.php");
		}
			
			?> <script>alert('Kelas Dan Mata Pelajaran Telah Ada Dalam DataBase')</script>
		<?php 
	}
			pageredirect("d_sub_guru.php");

echo "</table>\n";

echo "</th>\n";

echo "</tr>\n";

echo "</table>";

?>

