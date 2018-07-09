<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Skala Gred</p>
<?php

$m=$_GET['data'];
list ($tahap, $min, $max, $gred)=str_split('[/]', $m);

$querydata="SELECT * FROM gred WHERE tahap = '$tahap' ORDER BY max DESC";
$resultdata= oci_parse($conn_sispa,$querydata);
oci_execute($resultdata);

	if($tahap=="MA"){
	$status="Sunting Skala Gred Menengah Atas <br> T4 DAN T5";
	}
	if($tahap=="MR"){
	$status="Sunting Skala Gred Menengah Rendah <br> T1, T2, DAN T3";
	}
	if($tahap=="SR"){
	$status="Sunting Skala Gred Sekolah Rendah";
	}
?>
<br><br>
<h3><center><?php echo "$status"; ?></h3>
<body>
<form name="form1" method="post" action="data-gred.php">
  <table width="350"  border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <tr>
      <td colspan="3"><div align="center">Markah</div></td>
      <td rowspan="3"><center>
        <input name="tahap" type="hidden" id="tahap" value="<?php echo "$tahap"; ?>"> 
        <input name="gred" type="hidden" id="gred" value="<?php echo "$gred"; ?>">
        <input name="hantar" type="submit" id="hantar3" value="Hantar">
      </center></td>
    </tr>
    <tr>
      <td>Gred</td>
      <td><div align="center">Minima</div></td>
      <td><div align="center">Maksima</div></td>
    </tr>
    <tr>
      <td><?php echo "$gred"; ?></td>
      <td height="36"><center><input name="min" type="text" id="min" size="8" maxlength="3" value="<?php echo "$min"; ?>"></center></td>
      <td><center><input name="max" type="text" id="max" size="8" maxlength="3" value="<?php echo "$max"; ?>" ></center></td>
    </tr>
  </table>
</form>
</body>
<?php
echo "</td>";
include 'kaki.php'; ?>