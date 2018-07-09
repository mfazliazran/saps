<?php session_start(); ?>
<script type="text/javascript" src="ajax/ajax.js"></script>
<?php
include "../config.php";
$nokp=$_GET["nokp"];
$markah=$_GET["markah"];
$ting=$_GET["ting"];
$kelas=$_GET["kelas"];
$kod=$_GET["mp"];
$jpep=$_GET["jpep"];
$tahun=$_GET["tahun"];
$cnt=$_GET["cnt"];
?>
<form name="form_edit" action="#" method="post">
<table>
<tr><td>
<input type="text" name="markah" id="markah" value="<?php echo $markah;?>" maxlength="3" size="5" onkeyup="this.value=this.value.toUpperCase();">
<?php
	echo "<input name=\"kodsek\" type=\"hidden\" id=\"kodsek\" value=\"".$_SESSION["kodsek"]."\">\n";
	echo "<input name=\"nokp\" type=\"hidden\" id=\"nokp\" value=\"$nokp\">\n";
	echo "<input name=\"tahun\" type=\"hidden\" id=\"tahun\" value=\"$tahun\">\n";
	echo "<input name=\"ting\" type=\"hidden\" id=\"ting\" value=\"$ting\">\n";
	echo "<input name=\"kelas\" type=\"hidden\" id=\"kelas\" value=\"$kelas\">\n";
	echo "<input name=\"mp\" type=\"hidden\" id=\"mp\" value=\"$kod\">\n";
	echo "<input name=\"jpep\" type=\"hidden\" id=\"jpep\" value=\"$jpep\">\n";
	echo "<input name=\"cnt\" type=\"hidden\" id=\"cnt\" value=\"$cnt\">\n";
?>
</td><td>
<?php
 echo "<input type=\"button\" name=\"kemaskini\" id=\"kemaskini\" value=\"Kemaskini\" onClick=\"simpan_markah('$kodsek','$nokp','$tahun','$ting','$kelas','$kod','$jpep','$cnt',document.form_edit.markah.value);\">";
?> 
</td></tr></table>
 
</form>