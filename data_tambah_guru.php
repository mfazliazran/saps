<?php 
session_start();
include 'config.php';
	$tahun = $_SESSION['tahun'];
	$nokp = $_POST['nokp'];
	$nama = oci_escape_string($_POST['nama']);
	$jantina = $_POST['jan'];
	$kodsek = $_POST['kodsek'];
	
	if ($nokp=="")
		die ("No KP Guru tidak diisi.");
	if ($nama=="")
		die ("Nama Guru tidak diisi.");
	if ($jantina=="")
		die ("Jantina Guru tidak diisi.");

	$qsek = "SELECT KODSEK FROM LOGIN WHERE nokp= :nokp";
	$qic = oci_parse($conn_sispa,$qsek);
	oci_bind_by_name($qic, ':nokp', $nokp);
	oci_execute($qic);
	$sek = oci_fetch_array($qic);
	$kodsek2=$sek["KODSEK"];

	$qnama = "SELECT NAMASEK,NOTELEFON FROM tsekolah WHERE kodsek= :kodsek2";
	$qseq = oci_parse($conn_sispa,$qnama);
	oci_bind_by_name($qseq, ':kodsek2', $kodsek2);
	oci_execute($qseq);
	$nme = oci_fetch_array($qseq);
	$namasek2=ereg_replace("'","\'",$nme["NAMASEK"]);
	$nofon=$nme["NOTELEFON"];

	$query = "SELECT NEGERI,DAERAH,NAMASEK,KODSEK,STATUSSEK FROM login WHERE kodsek= :kodsek"; 
	$qrow = oci_parse($conn_sispa,$query);
	oci_bind_by_name($qrow, ':kodsek', $kodsek);
	oci_execute($qrow);
	$row = oci_fetch_array($qrow);
	$negeri = $row['NEGERI'];
	$daerah = $row['DAERAH'];
	$namasek = oci_escape_string($row['NAMASEK']);
	$kodsek = $row['KODSEK'];
	$status = $row['STATUSSEK'];
	
	$q_guru="SELECT NOKP FROM login WHERE nokp= :nokp";
	$qguru = oci_parse($conn_sispa,$q_guru);
	oci_bind_by_name($qguru, ':nokp', $nokp);
	oci_execute($qguru);
	if (oci_fetch($qguru)==0){
		$mysql=oci_parse($conn_sispa,"INSERT INTO login (tahun, nokp, nama, jan, level1, negeri, daerah, namasek, kodsek, statussek ) VALUES (:tahun, :nokp, :nama, :jantina, '1', :negeri, :daerah, :namasek, :kodsek, :status)");
		oci_bind_by_name($mysql, ':tahun', $tahun);
		oci_bind_by_name($mysql, ':nokp', $nokp);
		oci_bind_by_name($mysql, ':nama', $nama);
		oci_bind_by_name($mysql, ':jantina', $jantina);
		oci_bind_by_name($mysql, ':negeri', $negeri);
		oci_bind_by_name($mysql, ':daerah', $daerah);
		oci_bind_by_name($mysql, ':namasek', $namasek);
		oci_bind_by_name($mysql, ':kodsek', $kodsek);
		oci_bind_by_name($mysql, ':status', $status);
		oci_execute($mysql);
		header('Location: edit_guru.php');
			}
		else {
		//mysql_query ("UPDATE login SET tahun='$tahun', nokp='$nokp', nama='$nama', jan='$jantina', level='1', negeri='$negeri', daerah='$daerah', namasek='$namasek', kodsek='$kodsek', statussek='$status'  WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."'")or die(mysql_error());

	?> <script>alert('Pendaftaran Tidak Berjaya<br>Nama guru telah ada dalam pangkalan data.\n Kod Sekolah: <?php echo "$kodsek2"; ?> \n Nama Sekolah: <?php echo "$namasek2"; ?> \n No Telefon: <?php echo "$nofon"; ?>')</script> <?php
		require('edit_guru.php');
	}
?>