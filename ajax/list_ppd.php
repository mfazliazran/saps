<select name="txtKodPPD" id="txtKodPPD">
<option value="">-Pilih-</option>
<?php
include "../config.php";
  $kodjpn=$_GET["kodjpn"];
  //echo "kodjpn:$kodjpn";
  $query=" select KodPPD,PPD from tkppd where KodNegeri='$kodjpn'";
  $result = oci_parse($conn_sispa,$query);
  oci_execute($result);
  while($data=oci_fetch_array($result)){
    $kod=$data["KODPPD"];
	$ppd=$data["PPD"];
	echo "<option value=\"$kod\">$kod - $ppd</option>";
  }

?>

</select>
