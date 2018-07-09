<?php

include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';


if($_POST['simpan']){  
	$tarikhmula = substr($_POST['inpTarikhMula'],6,4)."-".substr($_POST['inpTarikhMula'],3,2)."-".substr($_POST['inpTarikhMula'],0,2);
	$tarikhakhir = substr($_POST['inpTarikhAkhir'],6,4)."-".substr($_POST['inpTarikhAkhir'],3,2)."-".substr($_POST['inpTarikhAkhir'],0,2);
	$perkara = 1; // kawal download data apdm
	$idkawalan = $_POST['hdnIDKawalan'];

	$check_table = "SELECT * FROM tskawal_apdm WHERE item='$perkara'";
	// die($check_table);
	$result_check = oci_parse($conn_sispa,$check_table);
	oci_execute($result_check);
	$count = count_row($check_table,$conn_sispa);
	if($count>0){
		$query = "UPDATE tskawal_apdm SET tarikh_mula='$tarikhmula', tarikh_akhir='$tarikhakhir' WHERE item='$perkara' and idkawalan='$idkawalan'";
	} else {
		$idtable = kira_max("tskawal_apdm");
		$query = "INSERT INTO tskawal_apdm (idkawalan, item, tarikh_mula, tarikh_akhir) VALUES ('$idtable','$perkara','$tarikhmula','$tarikhakhir')";
	}

	//die($query);
	$result_query = oci_parse($conn_sispa,$query);
	oci_execute($result_query);
	
}

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kawalan Tutup Muat Turun Data APDM</p>

<?php

$sql = "SELECT tarikh_mula, tarikh_akhir, item, idkawalan FROM tskawal_apdm WHERE item='1'";
$res = oci_parse($conn_sispa,$sql);
oci_execute($res);
if($data = oci_fetch_array($res)){
	// echo "masuk";
	$tarikhmula = fmtdate($data['TARIKH_MULA']);
	$tarikhakhir = fmtdate($data['TARIKH_AKHIR']);
	$idkawalan = $data['IDKAWALAN'];
}
// echo $tarikh_mula."haha";

?>

<form action="" method="POST">
<table width="100%" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td width="15%">Tarikh Mula</td>
		<td width="3">:</td>
		<td><input type="text" name="inpTarikhMula" placeholder="dd/mm/yyyy" value="<?php echo $tarikhmula;?>"></td>
	</tr>
	<tr>
		<td>Tarikh Akhir</td>
		<td>:</td>
		<td><input type="text" name="inpTarikhAkhir" placeholder="dd/mm/yyyy" value="<?php echo $tarikhakhir;?>"></td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="submit" class="button" name="simpan" value="Simpan" >
			<input type="hidden" name="hdnIDKawalan" value="<?php echo $idkawalan;?>"/>
		</td>
	</tr>
</table>
</form>

</td>
<?php

function kira_max($table){
	global $conn_sispa;
	$sql = "SELECT MAX(*) as total FROM $table";
	$res = oci_parse($conn_sispa,$sql);
	oci_execute($res);
	$data = oci_fetch_array($res);
	$total = $data['total'];
	return $total + 1;

}

function mysqldate($date_in)
{
if (strlen($date_in) > 0){
  $s = substr($date_in,6,4);
  $s .= "-".substr($date_in,3,2);
  $s .= "-".substr($date_in,0,2);
}
else
  $s="";  
return($s);
}

function fmtdate($date_in)
{
if (strlen($date_in) > 0){
  $s = substr($date_in,8,2);
  $s .= "/".substr($date_in,5,2);
  $s .= "/".substr($date_in,0,4);
}
else
  $s="";  
return($s);
}


?>