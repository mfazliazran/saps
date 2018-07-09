<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Mata Pelajaran</p>
<?php

$m=$_GET['data'];
list ($kod, $j_sek)=split('[/]', $m);
switch($j_sek){
	case "SR": $js = "SEKOLAH RENDAH"; break;
	case "SM": $js = "SEKOLAH MENENGAH"; break;
}
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
	 <!--<link rel="stylesheet" type="text/css" href="../tulisexam.css">-->
	<!--<div align="center"><strong>Kemaskini Mata Pelajaran </strong></div>-->
<?php
echo "<center><h3>KEMASKINI MATA PELAJARAN $js</center></h3><br>";
?>
    	<form name="form1" method="post" onsubmit='return formValidator()' action="data-edit-mp.php">
	<table width="450"  border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#CCCCCC">
	  <tr bgcolor="#999999">
	    <td><div align="center">Jenis Sekolah </div></td>
		<td><div align="center">Mata Pelajaran </div></td>
		<td><div align="center">Kod</div></td>
        <td><div align="center">Kod Lembaga</div></td>
        <td><div align="center">Status</div></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
	  <?php
		if($j_sek=="SM"){
		$query_sm = "SELECT * FROM mpsmkc WHERE kod='$kod'";
		$result_sm = oci_parse($conn_sispa,$query_sm);
		oci_execute($result_sm);
		while($sm = oci_fetch_array($result_sm)){
			$status_mp=$sm["STATUS_MP"];
	    echo "<td><input name=\"j_sek\" type=\"text\" id=\"j_sek\" readonly value=\"$j_sek\"></td>\n";
		echo "<td><input name=\"mp\" type=\"text\" id=\"mp\"  value=\"$sm[MP]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"50\"></td>\n";
		echo "<td><input name=\"kodmp\" type=\"text\" id=\"kodmp\" readonly  value=\"$sm[KOD]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"10\"></td>\n";
		echo "<td><input name=\"kodmplembaga\" type=\"text\" id=\"kodmplembaga\" value=\"$sm[KODLEMBAGA]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"10\"></td>\n";
		echo "<td><select name=\"cbostatus_mp\">";
		?>
		<option <?php if ($status_mp=="1") echo " selected "; ?> value="1">AKTIF</option>
		<option <?php if ($status_mp=="0") echo " selected "; ?> value="0">TIDAK AKTIF</option>
        <?php
		echo "</select>";
		echo "</td>\n";
		echo "<input name=\"mplama\" type=\"hidden\" id=\"mplama\"  value=\"$sm[MP]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"50\">\n";
		echo "<input name=\"kodmplama\" type=\"hidden\" id=\"kodmplama\"  value=\"$sm[KOD]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"10\">\n";
			}
		}
		if($j_sek=="SR"){
		$query_sr = "SELECT * FROM mpsr WHERE kod='$kod'";
		$result_sr = oci_parse($conn_sispa,$query_sr);
		oci_execute($result_sr);
		while($sr = oci_fetch_array($result_sr)){
		$status_mp=$sr["STATUS_MP"];
		echo "<td><input name=\"j_sek\" type=\"text\" id=\"j_sek\" readonly value=\"$j_sek\"></td>\n";
		echo "<td><input name=\"mp\" type=\"text\" id=\"mp\" value=\"$sr[MP]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"50\"></td>\n";
		echo "<td><input name=\"kodmp\" type=\"text\" id=\"kodmp\" readonly value=\"$sr[KOD]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"10\"></td>\n";
		echo "<td><input name=\"kodmplembaga\" type=\"text\" id=\"kodmplembaga\" value=\"$sr[KODLEMBAGA]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"10\"></td>\n";
		echo "<td><select name=\"cbostatus_mp\">";
		?>
		<option <?php if ($status_mp=="1") echo " selected "; ?> value="1">AKTIF</option>
		<option <?php if ($status_mp=="0") echo " selected "; ?> value="0">TIDAK AKTIF</option>
        <?php
		echo "</select>";
		echo "</td>\n";
		echo "<input name=\"mplama\" type=\"hidden\" id=\"mplama\" value=\"$sr[MP]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"50\">\n";
		echo "<input name=\"kodmplama\" type=\"hidden\" id=\"kodmplama\" value=\"$sr[KOD]\" onBlur=\"this.value=this.value.toUpperCase()\" size=\"10\">\n";
			}
		}
		?>
		<td><input type="submit" name="data" id="data" value="Hantar"></td>
	  </tr>
	</table>
    </form>
</td>
<?php include 'kaki.php';?>