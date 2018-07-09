<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
include 'fungsi2.php';
include "input_validation.php";

if ($_POST["post"]=="1"){
  $nokp=validate($_POST["nokp"]);
  $nama=validate(oci_escape_string($_POST["txtNama"]));
  $kodsek=validate($_POST["txtKodSek"]);
  $level=validate($_POST["txtLevel"]);
  $login=validate($_POST["txtLogin"]);
  $statussek=validate($_POST["txtStatussek"]);
  
  $statuslogin=0;
  $query_sm=" update login set user1= :login1,kodsek= :kodsek1,nama= :nama1,level1= :level1,statussek= :statussek1, statuslogin= :statuslogin1 where nokp= :nokp1";
  //echo " $query_sm<br>";
  $result_sm = oci_parse($conn_sispa,$query_sm);
	oci_bind_by_name($result_sm, ':kodsek1', $kodsek);
	oci_bind_by_name($result_sm, ':nama1', $nama);
	oci_bind_by_name($result_sm, ':level1', $level);
	oci_bind_by_name($result_sm, ':statussek1', $statussek);
	oci_bind_by_name($result_sm, ':login1', $login);
	oci_bind_by_name($result_sm, ':statuslogin1', $statuslogin);
	oci_bind_by_name($result_sm, ':nokp1', $nokp);

  oci_execute($result_sm);
  location("senarai-user.php");
 }
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Pengguna</p>
<?php 
$nokp=$_GET["nokp"];
//if ($_SESSION["level"]=="5" or $_SESSION["level"]=="3" or $_SESSION["level"]=="4" or $_SESSION["level"]=="6" or $_SESSION["level"]=="7"){

if ($_SESSION["level"]=="3" or $_SESSION["level"]=="4"){ //SU PEPERIKSAAN
  $query_sm="select Nokp,Login.KodSek,User1,pswd,nama,level1,namasek from login,tsekolah where login.KodSek=tsekolah.KodSek and nokp='$nokp'";
  $query_sm.=" and login.KodSek='".$_SESSION["kodsek"]."' ";
}  
else if ($_SESSION["level"]=="5"){ //PPD
  $query_sm="select Nokp,Login.KodSek,User1,pswd,nama,level1,namasek from login where nokp='$nokp'";
  $query_sm.=" and KodPPD='".$_SESSION["kodsek"]."' ";
}  
else if ($_SESSION["level"]=="6"){ 
  $query_sm="select Nokp,Login.KodSek,User1,pswd,nama,level1,statussek,namasek from login where nokp='$nokp'";
  $query_sm.=" and KodJPN='".$_SESSION["kodsek"]."' ";
} else
  $query_sm="select Nokp,Login.KodSek,User1,pswd,nama,level1,statussek,namasek from login where nokp='$nokp'";
 
  $result_sm = oci_parse($conn_sispa,$query_sm);

    oci_execute($result_sm);
	$bil=$rowstart;
	if ($sm = oci_fetch_array($result_sm)){
	   $kodsek=$sm["KODSEK"];
	   $level=trim($sm["LEVEL1"]);
	   $pswd=$sm["PSWD"];
	   $nama=$sm["NAMA"];
	   $login=$sm["USER1"];
	   $statussek=$sm["STATUSSEK"];
	   $namasek=$sm["NAMASEK"];
	}
	//echo "level:".$_SESSION["level"];
 ?>
<form name="frm1" method="post" action="">
<TABLE>
<tr><td>KOD SEKOLAH</td><td>:</td><td>
	<?php echo "<b>$kodsek</b>"; ?>	<input type="hidden" name="txtKodSek" size="10" maxlength="7" value="<?php echo $kodsek; ?>"/>
</td></tr>
<tr><td>NAMA SEK</td><td>:</td><td>
	<?php echo "<b>$namasek</b>"; ?>
</td></tr>
<tr><td>NO. K/P</td><td>:</td><td><b><?php echo $nokp; ?></b></td></tr>
<tr><td>NAMA</td><td>:</td><td><input type="text" name="txtNama" size="50" maxlength="50" value="<?php echo $nama; ?>"/></td></tr>
<tr><td>LEVEL</td><td>:</td><td>
<select name="txtLevel">
<?php
$qrygrp="select rolegroup from role where id='".$_SESSION["level"]."'";
//echo "$qrygrp<br>";
$resgrp = oci_parse($conn_sispa,$qrygrp);
oci_execute($resgrp);
$datagrp=oci_fetch_array($resgrp);
$rolegroup=(int) $datagrp["ROLEGROUP"];

$qryrole="select id,role from role where rolegroup < $rolegroup order by role";
//echo "$qryrole<br>";
$resrole = oci_parse($conn_sispa,$qryrole);
oci_execute($resrole);
echo "<option value=\"\">-Pilih-</option>";
while($datarole=oci_fetch_array($resrole)){
  $roleid=$datarole["ID"];
  $role=$datarole["ROLE"];
   if ($level==$roleid)
	  echo "<option selected value=\"$roleid\">$roleid - $role</option>";
   else
	  echo "<option value=\"$roleid\">$roleid - $role</option>";
} 
?>
</select>
</td>
</tr>
<tr><td>LOGIN</td><td>:</td><td><input type="text" name="txtLogin" size="30" maxlength="30"  value="<?php echo $login; ?>"/></td></tr>
<tr><td>STATUS SEKOLAH</td>
        <td>:</td>
        <td><select name="txtStatussek" size="1">
				<option value=""> Sila Pilih Status Sekolah</option>
			  <option <?php if ($statussek=="SR") echo " SELECTED "; ?> value="SR">SEKOLAH RENDAH</option>
			  <option <?php if ($statussek=="SM") echo " SELECTED "; ?> value="SM">SEKOLAH MENENGAH</option>
			</select>          </select></td>
      </tr>

<tr><td colspan="3"><input type="submit" value="Simpan" name="simpan" />
<input type="hidden" name="post" value="1">
<input type="hidden" name="nokp" value="<?php echo $nokp; ?>">
<input type="button" value="Batal" name="batal" onClick="location.href='senarai-user.php';"/>
</td></tr>
</TABLE>
</form>
<?php
//}
?>
</td>
<?php include 'kaki.php';?>