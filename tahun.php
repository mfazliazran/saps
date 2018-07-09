<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kawalan Peperiksaan</p>
<h3><center>Kemaskini Kawalan Kemasukan Markah Peperiksaan</center></h3>
<?php
$m=$_GET['data'];
list ($tahun, $npep, $status)=split('[/]', $m);
$rest=oci_parse($conn_sispa,"SELECT * FROM kawal_pep WHERE tahun='$tahun' AND  jpep='$npep'");
oci_execute($rest);
$row=oci_fetch_array($rest);
$tarikhmula = $row["MULA_PENGISIAN"];
$tarikhtamat = $row["TAMAT_PENGISIAN"];
	  
?>
<script type='text/javascript'>
function formValidator(){
	// Make quick references to our fields
	var tahun = document.getElementById('tahun');
	var status = document.getElementById('status');
	var tarikhbuka = document.getElementById('tarikhbuka');
	var tarikhtutup = document.getElementById('tarikhtamat');
	if(tahun_p(tahun, "Isikan Tahun Dengan Format 4 Digit")){
		if(notEmpty(tarikhbuka, "Isikan Tarikh Mula Pengisian")){
			//return true;
			if(notEmpty(tarikhtutup, "Isikan Tarikh Tamat Pengisian")){
				//return true;
				if(notEmpty(status, "Isikan Status")){
					return true;
				}
			}
		}
	}
	return false;
	
}

function tahun_p(elem, helperMsg){
	if(elem.value.length != 4){
		alert(helperMsg);
		elem.focus(); // set the focus to this input
		return false;
	}
	return true;
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
	
<!-- start page -->
<br>
<form name="form1" method="post" onsubmit='return formValidator()' action="data-tahun.php">
<table width="500" border="1" align="center" cellpadding="10" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
  <tr>
    <td>Tahun</td>
    <td>
      <input name="tahun" type="text" id="tahun" size="8" maxlength="4" value="<?php echo "$tahun"; ?>">
    </td>
  </tr>
  <tr>
    <td>Jenis Peperiksaan </td>
    <td><?php
		echo "<select name=\"jpep\" id=\"jpep\">";
		$mpSQL = "SELECT * FROM jpep ORDER BY jenis ASC";
		$rset = oci_parse($conn_sispa,$mpSQL);
		oci_execute($rset);
		$nrd = count_row("SELECT * FROM jpep ORDER BY jenis ASC");
		//echo "<OPTION VALUE=\"$npep\">$npep</OPTION>";
		while($row=oci_fetch_array($rset)){
			$kod = $row["KOD"];
			$jenis = $row["JENIS"];
			if($kod == $npep){
				echo "<option value='".$row["KOD"]."/".$row["RANK"]."' selected>".$row["JENIS"]."</option>";
			}else{
				echo "<option value='".$row["KOD"]."/".$row["RANK"]."'>".$row["JENIS"]."</option>";
			}
		}
		/*for ($m=0; $m<$nrd; $m++) {
			$rm = oci_fetch_array($rset);
			echo "<OPTION VALUE=\"".$rm["KOD"]."/".$rm["RANK"]."\">".$rm["JENIS"]."</OPTION>";
			}*/
		echo " </select>\n";
		?>
	</td>
  </tr>
  <tr>
    <td>Tarikh Mula Pengisian</td>
    <td><input readonly size="10" maxlength="10" type="text" name="tarikhbuka" id="tarikhbuka" value="<?php if($tarikhmula!=""){ echo $tarikhmula; } else {echo date("d/m/Y"); }?>">
      <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.form1.tarikhbuka);return false;" ><img class="PopcalTrigger" align="absmiddle" src="popupcal/calbtn.gif" width="22" height="22" border="0" alt=""></a></td>
  </tr>
  <tr>
    <td>Tarikh Tamat Pengisian</td>
    <td><input readonly size="10" maxlength="10" type="text" name="tarikhtamat" id="tarikhtamat" value="<?php echo $tarikhtamat; ?>">
      <a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.form1.tarikhtamat);return false;" ><img class="PopcalTrigger" align="absmiddle" src="popupcal/calbtn.gif" width="22" height="22" border="0" alt=""></a></td>
  </tr>
  <tr>
    <td>Status</td>
	<?php if($status=="0"){ $st = "Tutup"; } if($status=="1"){ $st = "Buka"; }?>
    <td><select name="status" id="status">
      <option value="<?php echo "$status"; ?>"><?php echo "$st"; ?></option>
      <option value="1">Buka</option>
      <option value="0">Tutup</option>
    </select></td>
  </tr>
</table>
<p>
  <center><input type="submit" name="Submit" value="Hantar">&nbsp;<input type="button" class="button" name="Kembali" value="Kembali" onclick="window.location.href='papar-tahun.php'" /></center>
</p></form>
</td>
<?php include 'kaki.php';?>
<!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="popupcal/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe> 