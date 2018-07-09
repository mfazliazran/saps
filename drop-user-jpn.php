<script type="text/javascript">
function semak()
{
	if(document.frm1.txtSebabHapus.value==""){
		alert("Sila pilih sebab hapus !");
		return false;
	}
	
return confirm("Hapuskan rekod ini ?");	
}
</script>
<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
include 'fungsi2.php';

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Hapus Pengguna Level JPN</p>

<?php
if(!($_SESSION["level"]=="6" and $_SESSION["pentadbir"]=="1")){
	die("Utiliti ini hanya untuk kegunaan JPN sahaja !");
}
if ($_POST["post"]=="1"){
  $nokp=$_POST["nokp"];
  $kodsebabhapus=$_POST["txtSebabHapus"]; 

  
  $sttus_login=0;
  $query_sm=" insert into login_arkib (select TAHUN,NOKP,NAMA,JAN, USER1,LEVEL1,        
PSWD,NEGERI,DAERAH,NAMASEK,KODSEK,STATUSSEK,TING,KELAS,ONLINE1,TM,KODNEGERI,STATUSLOGIN,       
PSWD_LAMA,PENTADBIR,'$kodsebabhapus' from login where nokp=:nokp1)";
  oci_bind_by_name($result_sm, ':nokp1', $nokp);
  
  $result_sm = oci_parse($conn_sispa,$query_sm);
  oci_execute($result_sm);
  
  $query_sm=" delete from login where nokp=:nokp1)";
  oci_bind_by_name($result_sm, ':nokp1', $nokp);
  $result_sm = oci_parse($conn_sispa,$query_sm);
  oci_execute($result_sm);
  
  echo "<font size=\"+1\">Rekod telah dihapuskan.</font><br><br>";
/*	oci_bind_by_name($result_sm, ':katalaluan1', $katalaluan);
	oci_bind_by_name($result_sm, ':jawatan1', $jawatan);
	oci_bind_by_name($result_sm, ':nama1', $nama);
	oci_bind_by_name($result_sm, ':statuslogin1', $sttus_login);
	oci_bind_by_name($result_sm, ':nokp1', $nokp);
	oci_bind_by_name($result_sm, ':login1', $login);*/
  echo "<input type=\"button\" value=\"Kembali\" name=\"batal\" onClick=\"location.href='senarai-user.php';\"/>";

  //location("senarai-user.php");
 }
else {
$nokp=$_GET["nokp"];

  $query_sm="select Nokp,Login.KodSek,NamaSek,User1,pswd,nama,level1,statussek from login where nokp='$nokp' ";
 //echo $query_sm;
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
<tr><td>KOD SEKOLAH</td><td>:</td><td>
	<?php echo "<b>$kodsek</b>"; ?>	<input type="hidden" name="txtKodSek" size="10" maxlength="7" value="<?php echo $kodsek; ?>"/>
</td></tr>
<tr><td>NO. K/P</td><td>:</td><td><b><?php echo $nokp; ?></b></td></tr>
<tr><td>NAMA</td><td>:</td><td><b><?php echo $nama; ?></b></td></tr>
<tr><td>NAMA SEK</td><td>:</td><td><b><?php echo $jawatan; ?></b></td></tr>
<tr><td>LEVEL</td><td>:</td><td><b>
<?php 	
echo keterangan("ROLE","ROLE","ID",$level)
 ?>
</b></td></tr>
<tr><td>LOGIN</td><td>:</td><td><strong><?php echo $login; ?></strong></td></tr>
<tr><td>KATALALUAN</td><td>:</td><td><b><?php echo $pswd; ?></b></td></tr>
<tr><td>SEBAB HAPUS</td><td>:</td><td>
<select name="txtSebabHapus" id="txtSebabHapus" >
<?php
$qryrole="select KODSEBABHAPUS,SEBABHAPUS from TKSEBABHAPUS order by SEBABHAPUS";
//echo "$qryrole<br>";
$resrole = oci_parse($conn_sispa,$qryrole);
oci_execute($resrole);
echo "<option value=\"\">-Pilih-</option>";
while($datarole=oci_fetch_array($resrole)){
  $kodsebabhapus=$datarole["KODSEBABHAPUS"];
  $sebabhapus=$datarole["SEBABHAPUS"];
  echo "<option value=\"$kodsebabhapus\">$sebabhapus</option>";
} 
?>
</select>
</td></tr>
<tr><td colspan="3"><input type="submit" value="Hapus" name="hapus" onClick="return semak()"/>
<input type="hidden" name="post" value="1">
<input type="hidden" name="nokp" value="<?php echo $nokp; ?>">
<input type="hidden" name="login" value="<?php echo $login; ?>">
<input type="button" value="Kembali" name="batal" onClick="location.href='senarai-user.php';"/>
</td></tr>
</TABLE>
</form>
<?php
}
?>
</td>
<?php include 'kaki.php';?>