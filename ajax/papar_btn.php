<?php
session_start();
$nokp = $_GET["nokp"];
$kodsek = $_GET["kodsek"];
$ting = $_GET["ting"];
$kelas = $_GET["kelas"];
$tahun = $_GET["tahun"];
$_SESSION["tahun_semasa"] = $tahun;
$jpep = $_GET["jpep"];
switch ($ting)
{
	case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
		$url3= "slipsr.php";
		$url4= "analisasr.php";
		break;
	case "P": case "T1": case "T2":
		$url3= "slipmr.php";
		$url4= "analisamr.php";
		break;
	case "T3":
		$url3= "slipmr.php";
		$url4= "analisamr.php";
		break;
	case "T4":
		$url3= "slipma.php";
		$url4= "analisama.php";
		break;
	case "T5":
		$url3= "slipma.php";
		$url4= "analisama.php";
		break;
}
?>

<form id="form7" name="form7" method="post" action="" target="_blank">
<table width="200" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><input type="button" name="jpep2" id="jpep2" value="Papar Slip Keputusan" onclick="return submitform('jpep','<?php echo $url3;?>');"/></td>
  </tr>
  <tr>
    <td><input type="button" name="analisis2" id="analisis2" value="Papar markah Peperiksaan" onclick="return submitform('analisis','<?php echo $url4;?>');"/></td>
  </tr>
</table>
<input type="hidden" name="nokp" id="nokp" value="<?php echo $nokp;?>">
<input type="hidden" name="kodsek" id="kodsek" value="<?php echo $kodsek;?>">
<input type="hidden" name="ting" id="ting" value="<?php echo $ting;?>">
<input type="hidden" name="kelas" id="kelas" value="<?php echo $kelas;?>">
<input type="hidden" name="cboPep" id="cboPep" value="<?php echo $jpep;?>">
</form>