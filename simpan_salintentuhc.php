<?php
include_once ('config.php');
$cnt = $_POST["hdn_cnt"];
$capai = $_POST["hdn_capai"];
$tahunpep = $_POST["hdn_tahunpep"];

//echo "cnt ".$cnt."<br>";

function locationx($locate) {
 ?>
 <script language="JavaScript">
  var temp = "<?php print($locate)?>";
  window.location=temp;
 </script>
 <?php
}

for($i=1;$i<=$cnt;$i++){
	$tingpep = $_POST["txt_tingpep$i"];
	$tingtov = $_POST["txt_tingtov$i"];
	$tahuntov = $_POST["txt_tahuntov$i"];
	$jenpep = $_POST["txt_jenpep$i"];
	if($capai==""){
		$stmt = oci_parse($conn_sispa,"INSERT INTO tentu_hc (tahunpep, jenpep, tingpep, capai, tahuntov, tingtov) VALUES ('$tahunpep', '$jenpep', '$tingpep', '$capai', '$tahuntov', '$tingtov')");
	}else{
		$stmt = oci_parse($conn_sispa,"INSERT INTO tentu_hc (tahunpep, jenpep, tingpep, capai, tahuntov, tingtov) VALUES ('$tahunpep', '$jenpep', '$tingpep', '$capai', '$tahunpep', '$tingtov')");
	}
	oci_execute($stmt);
}
//message("DATA TELAH DIMASUKKAN", 1);
//locationx("penentu_hc.php?data=$capai");
?>
<script type="text/javascript">
alert('Rekod berjaya disimpan.');
location.href='<?php echo "penentu_hc.php?data=$capai";?>';
</script>