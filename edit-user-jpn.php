<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
include 'fungsi2.php';
include "input_validation.php";

if ($_POST["post"]=="1"){
  $nokp2=$_POST["nokp"];
  $nokp=validate($nokp2);
  $login=validate($_POST["login"]); 
  $level=validate($_POST["txtLevel"]); 
  $nama=oci_escape_string(validate($_POST["txtNama"]));
  $jawatan=oci_escape_string(validate($_POST["txtJawatan"]));
  $statussek=validate(validate($_POST["txtStatussek"]));

  $sttus_login=0;
  $query_sm=" update login set namasek= :jawatan1,nama= :nama1,level1=:level1, statuslogin= :statuslogin1,statussek=:statussek1
              where nokp= :nokp1 and user1= :user1";
  //echo " $query_sm<br>";
  $result_sm = oci_parse($conn_sispa,$query_sm);
	oci_bind_by_name($result_sm, ':jawatan1', $jawatan);
	oci_bind_by_name($result_sm, ':nama1', $nama);
	oci_bind_by_name($result_sm, ':level1', $level);
	oci_bind_by_name($result_sm, ':statuslogin1', $sttus_login);
	oci_bind_by_name($result_sm, ':statussek1', $statussek);
	oci_bind_by_name($result_sm, ':nokp1', $nokp);
	oci_bind_by_name($result_sm, ':user1', $login);

   $r=oci_execute($result_sm);
	 if (!$r) {
		echo "fatal<br>"; 
		$e = oci_error($result_sm);  // For oci_execute errors pass the statement handle
		print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%".($e['offset']+1)."s", "^");
		print  "\n</pre>\n";
	 } 
echo "selesai..";	 
  location("senarai-user.php");
 }
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Pengguna Level JPN</p>
<?php 
$nokp=validate($_GET["nokp"]);

  $query_sm="select Nokp,Login.KodSek,NamaSek,User1,pswd,nama,level1,statussek from login where nokp=:nokp ";
  $result_sm = oci_parse($conn_sispa,$query_sm);
  oci_bind_by_name($result_sm,":nokp",$nokp);

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
 ?>
<form name="frm1" method="post" action="">
<TABLE>
<tr><td>KOD SEKOLAH</td><td>:</td><td>
	<?php echo "<b>$kodsek</b>"; ?>	<input type="hidden" name="txtKodSek" size="10" maxlength="7" value="<?php echo $kodsek; ?>"/>
</td></tr>
<tr><td>NO. K/P</td><td>:</td><td><b><?php echo $nokp; ?></b></td></tr>
<tr><td>NAMA</td><td>:</td><td><input type="text" name="txtNama" size="50" maxlength="50" value="<?php echo $nama; ?>"/></td></tr>
<tr><td>NAMA SEK</td><td>:</td><td><input type="text" name="txtJawatan" id="txtJawatan" size="80" maxlength="80" value="<?php echo $jawatan; ?>"/></td></tr>
<tr><td>LEVEL</td><td>:</td>
<?php 	

	 echo "<td>";
     echo "<select name=\"txtLevel\">";
	 echo "<option value=\"\">-Pilih-</option>";
	 $res=oci_parse($conn_sispa,"select id,role from role where id in ('1','2','3','4','5','6','PK','P') order by role");
	 oci_execute($res);
	 while($dt=oci_fetch_array($res)){
		 
	   $idrole=$dt["ID"];
       $namarole=$dt["ROLE"];	   
	   if ($level==$idrole)
		 echo "<option selected value=\"".$idrole."\">$idrole -$namarole</option>";
	   else
		 echo "<option value=\"".$idrole."\">$idrole -$namarole</option>";
	 }
 ?>
</tr>
<tr><td>STATUS SEKOLAH</td>
        <td>:</td>
        <td><select name="txtStatussek" size="1">
				<option value=""> Sila Pilih Status Sekolah</option>
			  <option <?php if ($statussek=="SR") echo " SELECTED "; ?> value="SR">SEKOLAH RENDAH</option>
			  <option <?php if ($statussek=="SM") echo " SELECTED "; ?> value="SM">SEKOLAH MENENGAH</option>
			</select>          </select></td>
      </tr>

<tr><td>LOGIN</td><td>:</td><td><strong><?php echo $login; ?></strong></td></tr>
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