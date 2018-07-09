<?php 
include 'config.php';
include 'auth.php';
$nokpadmin = $_POST['nokpadmin'];
$leveladmin = $_POST['leveladmin'];
$kodsek = $_POST['kodsek'];
$m = $_POST['nokp'];
list($nokp, $levelsupbaru, $nama)=split('[/]', $m);
/*echo $nokp."<br>";
echo $level."<br>";
echo $nama."<br>";
die($m);*/
//echo $nokpadmin;
//$q_guru = oci_parse($conn_sispa,"SELECT * FROM login WHERE kodsek='$kodsek' AND nokp='$nokp' AND user1!=''");
//oci_execute($q_guru);
//die("SELECT * FROM login WHERE kodsek='$kodsek' AND nokp='$nokp' AND user1!=' '");
$xuser = count_row("SELECT * FROM login WHERE kodsek='$kodsek' AND nokp='$nokp' AND user1!=' '");
if ($xuser == "1" )
{
	//echo "level:$levelsupbaru leveladmin:$leveladmin<br>";
	//die('masuk');
	switch ($levelsupbaru)
	{
		case 1 : $stmt = oci_parse($conn_sispa,"UPDATE login SET level1='3' WHERE nokp='$nokp'"); 
		oci_execute($stmt);
		break;
		
		case 2 : $stmt = oci_parse($conn_sispa,"UPDATE login SET level1='4' WHERE nokp='$nokp'"); 
		oci_execute($stmt);
		break;
	}
	
	switch ($leveladmin)
	{
		case "4" : $stmt = oci_parse($conn_sispa,"UPDATE login SET level1='2' WHERE nokp='$nokpadmin'"); 
		oci_execute($stmt);
		break;

		case "3" : $stmt = oci_parse($conn_sispa,"UPDATE login SET level1='1' WHERE nokp='$nokpadmin'"); 
		oci_execute($stmt);
		break;
	}
}
else {	require 'tukar_admin.php';
		?><script type='text/javascript'> alert("nama Belum Daftar Id Buat Pendaftaran Id Dahulu" )</script> <?php break;
	 }

require 'index.php';
?>