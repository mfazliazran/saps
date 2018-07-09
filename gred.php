<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Skala Gred</p>
<?php

$m=$_GET['data'];
list ($tahap)=split('[/]', $m);

$querydata="SELECT * FROM gred WHERE tahap = '$tahap' ORDER BY max DESC";
$resultdata= oci_parse($conn_sispa,$querydata);
oci_execute($resultdata);

	if($tahap=="MA"){
	$status="Skala Gred Menengah Atas <br> T4 DAN T5";
	}
	if($tahap=="MR"){
	$status="Skala Gred Menengah Rendah <br> T1, T2, DAN T3";
	}
	if($tahap=="SR"){
	$status="Skala Gred Sekolah Rendah";
	}
?>
<h2><center><?php echo "$status"; ?></h2>
<table width="400" border="1" align="center" cellpadding="10" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
  <tr bgcolor="#FF9933">
   	<td colspan="2"width="100"><center>Markah</center></td>
    <td  width="100"><center>Gred</center></td>
	 <td  width="100"><center>Edit</center></td>
  </tr>
  <tr>
  <?php
  
  while($row = oci_fetch_array($resultdata))
	{
  	$min = $row['MIN'];
	$max = $row['MAX'];
	$gred = $row['GRED'];
			
	echo " <td><center>$min</center></td>\n";
	echo " <td><center>$max</center></td>\n";
	echo " <td><center>$gred</center></td>\n";
	echo " <td><center><a href=b-edit-gred.php?data=".$tahap."/".$min."/".$max."/".$gred.">Edit</a></center></td>\n";
	echo " </tr>\n";
	}
		
?>
</table><br><br><br>
<?php
echo "</td>";
include 'kaki.php'; ?>
