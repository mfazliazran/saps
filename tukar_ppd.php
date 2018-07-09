<link href="kpm.css" type="text/css" rel="stylesheet" />
<?php 

global $conn_smm;

set_time_limit(0);
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'input_validation.php';

?>
<script language="javascript" type="text/javascript">
function semak_inputs(){
	if(document.form1.txtPPD.value==''){
		alert('Sila masukkan kod PPD lama.');	
		document.form1.txtPPD.focus();
		return false;
	}
	if(document.form1.txtKodPPD.value==""){
		alert('Sila masukkan kod PPD baru.');	
		document.form1.txtKodPPD.focus();
		return false;
	}
	return true;
}
</script>
<?php

$kodsekolah = validate($_POST['txtKodSekolah']);
$kodppd = validate($_POST['txtKodPPD']);
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Tukar Kod PPD<font color="#FFFFFF">(Tarikh Kemaskini Program : 5/7/2016 2:27PM)</font></p>
<form id="form1" name="form1" method="post" action="">
<table width="578" border="1" align="center">
  <tr bgcolor="#ff9900">
    <td colspan="2"><div align="center">TUKAR KOD PPD</div></td>
  </tr>
  <tr>
    <td>Kod Sekolah</td>
    <td><label>
      <input type="text" name="txtKodSekolah" id="txtKodSekolah" value="<?php echo $kodsekolah;?>" onkeypress="this.value=this.value.toUpperCase()" />
    </label></td>
    </tr>
  <tr>
    <td>Kod PPD Baru</td>
    <td><input type="text" name="txtKodPPD" id="txtKodPPD" value="<?php echo $kodppd;?>" onkeypress="this.value=this.value.toUpperCase()" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="button" id="button" value="Tukar" onclick="return semak_inputs();" />
      <input name="post" type="hidden" id="post" value="1" /></td>
    </tr>
</table>
</form>
<?php
if($_POST['post']=='1'){
	$cnt=0;
	$sql = "select KODSEK from tsekolah where KODSEK='$kodsekolah'"; 
	$stmt=oci_parse($conn_sispa,$sql);
  	oci_execute($stmt);
	if($data=oci_fetch_array($stmt)){ 

	    $kodsek=$data["KODSEK"];

	  	$sql_check = "select PPD from tkppd where KodPPD='$kodppd'"; 
		$res_check=oci_parse($conn_sispa,$sql_check);
		oci_execute($res_check);
		$data_sek=oci_fetch_array($res_check);
		$namappd=oci_escape_string($data_sek["PPD"]);

		if($namappd==""){ 
			die("Kod PPD $kodppd tidak wujud !");
		}

		$sql = "update ANALISIS_MPMA set kodppd='$kodppd' where kodsek='$kodsekolah'";
		//echo "$sql<br>";
		$stmt=oci_parse($conn_sispa,$sql);
	  	oci_execute($stmt);
		
		$sql2 = "update ANALISIS_MPMR set kodppd='$kodppd' where kodsek='$kodsekolah'";
		//echo "$sql2<br>";
		$stmt2=oci_parse($conn_sispa,$sql2);
	  	oci_execute($stmt2);

		$sql3 = "update ANALISIS_MPSR set kodppd='$kodppd' where kodsek='$kodsekolah'";
		//echo "$sql3<br>";
		$stmt3=oci_parse($conn_sispa,$sql3);
	  	oci_execute($stmt3);
		
		$sql4 = "update TNILAI_SMA set kodppd='$kodppd' where kodsek='$kodsekolah'";
		//echo "$sql4<br>";
		$stmt4=oci_parse($conn_sispa,$sql4);
	  	oci_execute($stmt4);
		
		$sql5 = "update TNILAI_SMR set kodppd='$kodppd' where kodsek='$kodsekolah'";
		//echo "$sql5<br>";
		$stmt5=oci_parse($conn_sispa,$sql5);
	  	oci_execute($stmt5);
		
		$sql6 = "update TNILAI_SR set kodppd='$kodppd' where kodsek='$kodsekolah'";
		//echo "$sql6<br>";
		$stmt6=oci_parse($conn_sispa,$sql6);
	  	oci_execute($stmt6);
		
		$sql7 = "update TSEKOLAH set kodppd='$kodppd' where kodsek='$kodsekolah'";
		//echo "$sql7<br>";
		$stmt7=oci_parse($conn_sispa,$sql7);
	  	oci_execute($stmt7);
		
		echo "Kod PPD untuk sekolah $kodsek telah berjaya ditukar kepada $kodppd ...";	  
	} else {
		echo "<br><br>Kod sekolah $kodsekolah tidak wujud...";	
	}

} // post

?>
</td>
<?php include 'kaki.php';?> 