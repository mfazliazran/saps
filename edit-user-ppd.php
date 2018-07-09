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
  $login=validate($_POST["login"]);
  $level=validate($_POST["txtLevel"]);   
  $nama=validate(oci_escape_string($_POST["txtNama"]));
  $jawatan=validate(oci_escape_string($_POST["txtJawatan"]));
  
  $query_sm=" update login set namasek=:jawatan,nama=:nama,level1=:level1 where nokp=:nokp and user1=:login";
  $result_sm = oci_parse($conn_sispa,$query_sm);
  oci_bind_by_name($result_sm,":jawatan",$jawatan);
  oci_bind_by_name($result_sm,":nama",$nama);
  oci_bind_by_name($result_sm,":level1",$level);
  oci_bind_by_name($result_sm,":nokp",$nokp);
  oci_bind_by_name($result_sm,":login",$login);
  
  oci_execute($result_sm);
  location("senarai-user.php");
 }
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Pengguna</p>
<?php 
$nokp=$_GET["nokp"];
$kodsek=$_GET["kodsek"];
if ($kodsek=="") { // untuk ppd
	$query_sm="select Nokp,Login.KodSek,NamaSek,User1,pswd,nama,level1,statussek from login where nokp='$nokp' and user1='$nokp'";
	$ppd=1;
} else {
	$query_sm="select Nokp,Login.KodSek,NamaSek,User1,pswd,nama,level1,statussek from login where nokp='$nokp' and kodsek='$kodsek'";
	$ppd=0;
}
 
  $result_sm = oci_parse($conn_sispa,$query_sm);

    oci_execute($result_sm);
	$bil=$rowstart;
	if ($sm = oci_fetch_array($result_sm)){
	   $kodsek=$sm["KODSEK"];
	   $level=$sm["LEVEL1"];
	   $pswd=$sm["PSWD"];
	   $nama=$sm["NAMA"];
	   $jawatan=$sm["NAMASEK"];
	   $login=$sm["USER1"];
	   $statussek=$sm["STATUSSEK"];
	}
	//echo "level:".$_SESSION["level"];
 ?>
<form name="frm1" method="post" action="">
<TABLE>
<?php if ($ppd==1) { //untuk edit userid jpn ?> 
	<tr><td>KOD PPD</td><td>:</td><td><?php echo "<b>$kodsek</b>"; ?><input type="hidden" name="txtKodSek" size="10" maxlength="7" value="<?php echo $kodsek; ?>"/></td></tr>
	<tr><td>NO. K/P</td><td>:</td><td><b><?php echo $nokp; ?></b></td></tr>
	<tr><td>NAMA</td><td>:</td><td><input type="text" name="txtNama" size="50" maxlength="50" value="<?php echo $nama; ?>"/></td></tr>
	<tr><td>JAWATAN</td><td>:</td><td><input type="text" name="txtJawatan" id="txtJawatan" size="80" maxlength="80" value="<?php echo $jawatan; ?>"/></td></tr>
	<tr><td>LEVEL</td><td>:</td><td><strong>PPD</strong><input type="hidden" name="txtLevel" id="txtLevel" value="5"></td></tr>
	<tr><td>LOGIN</td><td>:</td><td><strong><?php echo $login; ?></strong></td></tr>
<?php } else { // untuk edit userid sekolah ?>
	<tr><td>KOD SEKOLAH</td><td>:</td><td><?php echo "<b>$kodsek</b>"; ?><input type="hidden" name="txtKodSek" size="10" maxlength="7" value="<?php echo $kodsek; ?>"/></td></tr>
	<tr><td>NAMA SEKOLAH</td><td>:</td><td><strong><?php echo $jawatan; ?></strong><input type="hidden" name="txtJawatan" id="txtJawatan" value="<?php echo $jawatan; ?>"/></td></tr>
	<tr><td>NO. K/P</td><td>:</td><td><b><?php echo $nokp; ?></b></td></tr>
	<tr><td>NAMA</td><td>:</td><td><b><?php echo $nama; ?></b><input type="hidden" name="txtNama" size="50" maxlength="50" value="<?php echo $nama; ?>"/></td></tr>
	<tr><td>LOGIN</td><td>:</td><td><strong><?php echo $login; ?></strong></td></tr>
	<tr><td>LEVEL</td><td>:</td>
<?php
	 echo "<td>";
     echo "<select name=\"txtLevel\">";
	 echo "<option value=\"\">-Pilih-</option>";
	 $res=oci_parse($conn_sispa,"select id,role from role where id in ('1','2','3','4','PK','P') order by role");
	 oci_execute($res);
	 while($dt=oci_fetch_array($res)){
		 
	   $idrole=$dt["ID"];
       $namarole=$dt["ROLE"];	   
	   if ($level==$idrole)
		 echo "<option selected value=\"".$idrole."\">$idrole -$namarole</option>";
	   else
		 echo "<option value=\"".$idrole."\">$idrole -$namarole</option>";
	 }
 
 } ?>
<tr><td colspan="3"><input type="submit" value="Simpan" name="simpan" />
<input type="hidden" name="post" value="1">
<input type="hidden" name="nokp" value="<?php echo $nokp; ?>">
<input type="hidden" name="login" value="<?php echo $login; ?>">
<input type="button" value="Kembali" name="batal" onClick="location.href='senarai-user.php';"/>
</td></tr>
</TABLE>
</form>
</td>
<?php include 'kaki.php';?>