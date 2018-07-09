<?php 
function tahap($ting)
{
	switch ($ting){
		case "P": $sting="KELAS PERALIHAN";
		break;
		case "T1":
		$sting="TINGKATAN 1";
		break;
		case "T2":
		$sting="TINGKATAN 2";
		break;
		case "T3":
		$sting="TINGKATAN 3";
		break;
		case "T4":
		$sting="TINGKATAN 4";
		break;
		case "T5":
		$sting="TINGKATAN 5";
		break;
		case "D1":
		$sting="TAHUN 1";
		break;
		case "D2":
		$sting="TAHUN 2";
		break;
		case "D3":
		$sting="TAHUN 3";
		break;
		case "D4":
		$sting="TAHUN 4";
		break;
		case "D5":
		$sting="TAHUN 5";
		break;
		case "D6":
		$sting="TAHUN 6";
		break;
	}
return $sting;
}

function message($ayat,$id) {
if($id==1):
  ?>
  <script language="javascript">
   var temp =  "<?php print($ayat)?>";
   alert(temp);
  </script>
  <?php
endif;
}

function location($locate) {
 ?>
 <script language="JavaScript">
  var temp = "<?php print($locate)?>";
  window.location=temp;
 </script>
 <?php
}


?>