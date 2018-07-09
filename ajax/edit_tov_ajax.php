<?php session_start(); ?>
<?php
include "../config.php";
$nokp=$_GET["nokp"];
$tov=$_GET["tov"];
$etr=$_GET["etr"];
$ting=$_GET["ting"];
$kelas=$_GET["kelas"];
$kod=$_GET["mp"];
$jpep=$_GET["jpep"];
$tahun=$_GET["tahun"];
$cnt=$_GET["cnt"];

if ($tov==""){	
	if ($ting=="D1" or $ting=="D2" or $ting=="D3" or $ting=="D4" or $ting=="D5" or $ting=="D6")
	   $table_markah="markah_pelajarsr";
	if ($ting=="P" or $ting=="T1"or $ting=="T2"or $ting=="T3"or $ting=="T4" or $ting=="T5")
	   $table_markah="markah_pelajar";
	   
	$res=oci_parse($conn_sispa,"select $kod from $table_markah where nokp='$nokp' and kodsek='".$_SESSION["kodsek"]."' and tahun='$tahun' and jpep='$jpep'");
	oci_execute($res);
	$m=oci_fetch_array($res);
	$tov=$m["$kod"];

}
?>
<script type="text/javascript" src="ajax/ajax_tov.js"></script>
<form name="form_edit" action="#" method="post">
<table>
<tr><td>
<input type="text" name="tov" id="tov" value="<?php echo $tov;?>" maxlength="3" size="5" onkeyup="this.value=this.value.toUpperCase();">
</td><td>
</td></tr></table>
</form>
|
<form name="form_edit2" action="#" method="post">
<table>
<tr><td>
<input type="text" name="etr" id="etr" value="<?php echo $etr;?>" maxlength="3" size="5" onkeyup="this.value=this.value.toUpperCase();">
</td><td>
<?php
 echo "<input type=\"button\" name=\"kemaskini\" id=\"kemaskini\" value=\"Kemaskini\" onClick=\"simpan_tov('$kodsek','$nokp','$tahun','$ting','$kelas','$kod','$jpep','$cnt',document.form_edit.tov.value,document.form_edit2.etr.value);\">";
?> 
</td></tr></table>
</form>