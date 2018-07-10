<?php
  /////////////SISPA IBUBAPA
function message($ayat) {
  ?>
  <script language="javascript">
   var temp =  "<?php print($ayat)?>";
   alert(temp);
  </script>
  <?php
}
function jpep2($kodpep)
{
	switch ($kodpep){
		case "U1":
		$npep="UJIAN 1";
		break;
		case "U2":
		$npep="UJIAN 2";
		break;
		case "PAT":
		$npep="PEPERIKSAAN AKHIR TAHUN";
		break;
		case "PPT":
		$npep="PEPERIKSAAN PERTENGAHAN TAHUN";
		break;
		case "PMRC":
		$npep="PEPERIKSAAN PERCUBAAN PMR";
		break;
		case "SPMC":
		$npep="PEPERIKSAAN PERCUBAAN SPM";
		break;
		case "UPSRC":
		$npep="PEPERIKSAAN PERCUBAAN UPSR";
		break;
	}
return $npep;
}
 ?>
