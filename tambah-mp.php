<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Tambah Mata Pelajaran Sekolah</p><br>
<font color="#FF0000" size="+1">Peringatan:<br>1. Kod MP TIDAK BOLEH nombor<br>2. Sila buat penambahan MP HANYA pada waktu malam SAHAJA. </font>
<?php
if(isset($_POST['data'])){
	$mp= $_POST['mp'];
	$kod= $_POST['kodmp'];
	$kodlembaga= $_POST['kodkplembaga'];
	$j_sek = $_POST['j_sek'];
	
	if($j_sek =="SM"){
		$query_mp = "SELECT * FROM mpsmkc WHERE kod='$kod' AND mp='$mp'";
		$result = oci_parse($conn_sispa,$query_mp);
		oci_execute($result);
		$num = count_row("SELECT * FROM mpsmkc WHERE kod='$kod' AND mp='$mp'");
		
		if($num == 0){
			$stmt=oci_parse($conn_sispa,"INSERT INTO mpsmkc (kod, mp,kodlembaga) VALUES ('$kod','$mp','$kodlembaga')");
			//die("INSERT INTO mpsmkc (kod, mp,kodlembaga) VALUES ('$kod','$mp','$kodlembaga')");
			oci_execute($stmt);
			?> <script>alert('Mata Pelajaran Telah Di Tambah')</script> <?php 
			$stmt=oci_parse($conn_sispa,"ALTER TABLE markah_pelajar ADD ($kod VARCHAR(5) NULL, G$kod VARCHAR2(2) NULL)");
			oci_execute($stmt);
			$stmt2=oci_parse($conn_sispa,"ALTER TABLE markah_pelajar_arkib ADD ($kod VARCHAR(5) NULL, G$kod VARCHAR2(2) NULL)");
			oci_execute($stmt2);
		}
		else{
			?> <script>alert('Mata Pelajaran Telah Ada..Maaf!')</script> <?php 
		}
	}else{
	////////sekrendah
		$query_mp = "SELECT * FROM mpsr WHERE kod='$kod' AND mp='$mp'";
		$result = oci_parse($conn_sispa,$query_mp);
		oci_execute($result);
		$num = count_row("SELECT * FROM mpsr WHERE kod='$kod' AND mp='$mp'");
		
		if($num == 0){
			$stmt=oci_parse($conn_sispa,"INSERT INTO mpsr (kod, mp,kodlembaga) VALUES ('$kod','$mp','$kodlembaga')");
			//die("INSERT INTO mpsr (kod, mp,kodlembaga) VALUES ('$kod','$mp','$kodlembaga')");
			oci_execute($stmt);
			?> <script>alert('Mata Pelajaran Telah Di Tambah')</script> <?php 
			$stmt=oci_parse($conn_sispa,"ALTER TABLE markah_pelajarsr ADD ($kod VARCHAR(4) NULL,G$kod VARCHAR(2) NULL)");
			oci_execute($stmt);
			$stmt2=oci_parse($conn_sispa,"ALTER TABLE markah_pelajarsr_arkib ADD ($kod VARCHAR(4) NULL,G$kod VARCHAR(2) NULL)");
			oci_execute($stmt2);
		}
		else{
			?> <script>alert('Mata Pelajaran Telah Ada..Maaf!')</script> <?php 
		}
	}//if j_sek
 //location("tambah-mp.php");
 pageredirect("tambah-mp.php");
}else{
	?>
	<script type='text/javascript'>
	function formValidator(){
		// Make quick references to our fields
		var mp = document.getElementById('mp');
		var kodmp = document.getElementById('kodmp');
		var j_sek = document.getElementById('j_sek');
		if(notEmpty(j_sek, "Pilih Jenis Sekolah")){
			if(notEmpty(mp, "Isikan Mata Pelajaran")){
				if(notEmpty(kodmp, "Isikan Kod Mata Pelajaran")){
				return true;
					}
				}
			}
		return false;
		}
	
	function notEmpty(elem, helperMsg){
		if(elem.value.length == 0){
			alert(helperMsg);
			elem.focus(); // set the focus to this input
			return false;
		}
		return true;
	}
	</script>
	<br><br><br>
	<div align="center"><h3>TAMBAH MATA PELAJARAN</h3></div>
	<form name="form1" method="post" onsubmit='return formValidator()' action="">
	<table width="450"  border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#CCCCCC">
	  <tr bgcolor="#999999">
	    <td><div align="center">Jenis Sekolah </div></td>
		<td><div align="center">Mata Pelajaran </div></td>
		<td><div align="center">Kod</div></td>
        <td><div align="center">Kod Lembaga</div></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td><select name="j_sek" id="j_sek">
		  <option value=""></option>
	      <option value="SM">Sekolah Menengah</option>
	      <option value="SR">Sekolah Rendah</option>
        </select></td>
		<td><input name="mp" type="text" id="mp" onBlur="this.value=this.value.toUpperCase()" size="50"></td>
		<td><input name="kodmp" type="text" id="kodmp" onBlur="this.value=this.value.toUpperCase()" size="10"></td>
		<td><input name="kodkplembaga" type="text" id="kodmplembaga" onBlur="this.value=this.value.toUpperCase()" size="10"></td>
		<td><input type="submit" name="data" id="data" value="Hantar"></td>
	  </tr>
	</table>
    </form>
	</td>
	<?php
	}
?>
</td>
<?php include 'kaki.php';?>