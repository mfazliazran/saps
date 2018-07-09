<?php 
include 'config.php';
include 'fungsi.php';
$kodseklama = $_POST['kodseklama'];
$nokp = $_POST['nokp'];
$level = $_POST['level'];
$sek = $_POST['kodsek'];
//die();
//$daerah_b = $_POST['daerah_b'];
//list($kodsek, $namasek, $status, $kodppd)=split('[|]', $sek);
$tahun_h = date("Y");

//$namasek = oci_escape_string($namasek[$qppd]);
//die("kodsek:$kodsek Namasek:$namasek, Status:$status, Kodppd:$kodppd");

//$qppd=oci_parse($conn_sispa,"SELECT * FROM tkppd WHERE KodPPD='$kodppd'");
$qppd=oci_parse($conn_sispa,"SELECT PPD,NAMASEK,STATUS,NEGERI FROM tsekolah,tkppd WHERE kodsek='$sek' and TSEKOLAH.KODPPD=TKPPD.KODPPD");
oci_execute($qppd);
$row=oci_fetch_array($qppd);
$ppd=$row["PPD"];
$namasek=oci_escape_string($row["NAMASEK"]);
$status=$row["STATUS"];
$negeri=$row["NEGERI"];

//echo "$tahun_h , $nokp , $kodsek , ";

	if($sek==''){
	message("Nama Sekolah Tidak Dipilih",1);
	location("b_guru_pindah.php?data=$nokp");} 

	else {
	$stmt=oci_parse($conn_sispa,"UPDATE login SET kodsek='$sek',negeri='$negeri', daerah='$ppd', namasek='$namasek', statussek='$status', ting=null, kelas=null, level1='1' WHERE nokp='$nokp'");
	
	//die("UPDATE login SET kodsek='$sek',negeri='$negeri', daerah='$ppd', namasek='$namasek', statussek='$status', ting=null, kelas=null, level1='1' WHERE nokp='$nokp'");
	oci_execute($stmt);
	
	
	//$stmk=oci_parse($conn_sispa,"DELETE FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodseklama' AND tahun='$tahun_h'");
	//oci_execute($stmk);
	
	$stmy=oci_parse($conn_sispa,"DELETE FROM tguru_kelas WHERE nokp='$nokp' AND kodsek='$kodseklama' AND tahun='$tahun_h'");
	oci_execute($stmy);
	
	message("Guru Telah Dipindahkan",1);
	location("edit_guru.php");
	} 
?>