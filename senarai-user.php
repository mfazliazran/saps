<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');

?>
<script language="javascript" type="text/javascript">
function ucase(e,obj) {
tecla = (document.all) ? e.keyCode : e.which;
//alert(tecla);
if (tecla!="8" && tecla!="0"){
obj.value += String.fromCharCode(tecla).toUpperCase();
return false;
}else{
return true;
}
}
</script>
<td valign="top" class="rightColumn">
<p class="subHeader">CARIAN SENARAI PENGGUNA</p>
<?php 
/*if($_SESSION["level"]<>"7"){
	die("Utiliti ini diberhentikan buat sementara waktu.");	
}*/

if($_SESSION["level"]<>"7" and $_SESSION["level"]<>"6" and $_SESSION["level"]<>"5" and $_SESSION["level"]<>"4" and $_SESSION["level"]<>"3"){
//if($_SESSION["kodsek"]<>"B010"){
//if($_SESSION["kodsek"]<>"AEE3045"){
	//echo "level:".$_SESSION["level"]." ppd:".$_SESSION["kodppd"]." negeri:".$_SESSION["kodnegeri"];
	die("Tiada kebenaran untuk akses...");	
}
//echo "level:".$_SESSION["level"]." ppd:".$_SESSION["kodsek"]." negeri:".$_SESSION["kodnegeri"];
if ($_SESSION["level"]=="5" or $_SESSION["level"]=="3" or $_SESSION["level"]=="4" or $_SESSION["level"]=="6" or $_SESSION["level"]=="7"){
	if ($_POST["post"]=="1"){
		$kodsek_cari=$_POST["txtKodSekolah"];
		$namasek_cari=oci_escape_string($_POST["txtNamaSekolah"]);
		$level_cari=$_POST["txtLevel"];
		$login_cari=$_POST["txtLogin"];
		$nokp_cari=$_POST["txtNoKP"];
		$nama_cari=$_POST["txtNama"];
		
		$_SESSION["kodsek_cari"]=$kodsek_cari;
		$_SESSION["namasek_cari"]=$namasek_cari;
		$_SESSION["level_cari"]=$level_cari;
		$_SESSION["login_cari"]=$login_cari;
		$_SESSION["nokp_cari"]=$nokp_cari;
		$_SESSION["nama_cari"]=$nama_cari;
	}
	else {
		$kodsek_cari=$_SESSION["kodsek_cari"];
		$namasek_cari=$_SESSION["namasek_cari"];
		$level_cari=$_SESSION["level_cari"];
		$login_cari=$_SESSION["login_cari"];
		$nokp_cari=$_SESSION["nokp_cari"];
		$nama_cari=$_SESSION["nama_cari"];
	}
 ?>
<form name="frm1" method="post" action="">
<TABLE>
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td><strong>KOD SEKOLAH</strong></td><td>:</td><td><input type="text" name="txtKodSekolah" size="10" maxlength="7" value="<?php echo $kodsek_cari; ?>" onkeypress="return ucase(event,this);" /></td></tr>
<tr><td><strong>NAMA SEKOLAH</strong></td><td>:</td><td><input type="text" name="txtNamaSekolah" size="50" maxlength="50" value="<?php echo $namasek_cari; ?>" onkeypress="return ucase(event,this);"/></td></tr>
<tr><td><strong>LEVEL</strong></td><td>:</td><td>
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
echo "<select name=\"txtLevel\">";
echo "<option value=\"\">-Pilih-</option>";
while($datarole=oci_fetch_array($resrole)){
  $roleid=$datarole["ID"];
  $role=$datarole["ROLE"];
	// for($i=0;$i<=$cnt_level;$i++){
	   if ($level_cari==$roleid)
		  echo "<option selected value=\"$roleid\">$role</option>";
	   else
		  echo "<option value=\"$roleid\">$role</option>";
	// }
} 
?>
</select>
</td>
</tr>
<tr><td><strong>LOGIN</strong></td><td>:</td><td><input type="text" name="txtLogin" size="30" maxlength="30"  value="<?php echo $login_cari; ?>"/></td></tr>
<tr><td><strong>NO K/P</strong></td><td>:</td><td><input type="text" name="txtNoKP" size="14" maxlength="12"  value="<?php echo $nokp_cari; ?>"/></td></tr>
<tr><td><strong>NAMA</strong></td><td>:</td><td><input type="text" name="txtNama" size="50" maxlength="50"  value="<?php echo $nama_cari; ?>"/></td></tr>
<tr align="center"><td colspan="3">
<input type="submit" value="Cari" name="submit" />
<input type="hidden" value="1" name="post" />
</td></tr>
</TABLE>
</form>
<?php
$cari=$_GET["cari"];
$post=$_POST["post"];
//echo "cari: $cari post: $post<br>";
//if ($_GET["cari"]=="1" or $_POST["post"]=="1"){
	echo "<center><h3>SENARAI PENGGUNA</center></h3><br>";
	echo "<table width=\"850\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
	echo "<tr><td colspan=\"4\"><strong>FUNGSI RESET :</strong></td><td colspan=\"15\"><strong><font size=\"2\" color=\"#FF0000\">1. TUKAR KATALALUAN kepada 12345678 dan <br>2. Tukar level kepada 1 (Guru Mata pelajaran)</font></strong></td></tr>";
	echo "<tr><td colspan=\"4\"><strong>USERID PPD dan JPN :</strong></td><td colspan=\"15\"><strong><font size=\"2\" color=\"#FF0000\">1. Create userID JPN<br>2. Tukar level Guru</font></strong></td></tr>";
	//echo $_SESSION["level"]." - ".$_SESSION["pentadbir"];
	if ($_SESSION["level"]=="7"){
		echo "  <tr><td colspan=\"15\"><img src=\"images/add.gif\">&nbsp;<a href=\"tambah-user.php\"><b>Tambah Pengguna</b></a></td>\n";
	} 
	else if ($_SESSION["level"]=="6" and $_SESSION["pentadbir"]=="1"){ // jpn
		echo "  <tr><td colspan=\"15\"><img src=\"images/add.gif\">&nbsp;<a href=\"tambah-user-jpn.php\"><b>Tambah Pengguna (level JPN SAHAJA)</b></a></td>\n";
	} else if ($_SESSION["level"]=="5" and $_SESSION["pentadbir"]=="1"){ // ppd
		echo "  <tr><td colspan=\"15\"><img src=\"images/add.gif\">&nbsp;<a href=\"tambah-user-ppd.php\"><b>Tambah Pengguna (level PPD SAHAJA)</b></a></td>\n";
	} 
	 
	echo "  <tr bgcolor=\"#CCCCCC\">\n";
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">Status</div></td>\n";
	if ($_SESSION["level"]=="5" or $_SESSION["level"]=="6" or $_SESSION["level"]=="7"){
	echo "    <td><div align=\"center\">Kata Kunci</div></td>\n";
	}
	echo "    <td><div align=\"center\">Nama Sekolah / Jawatan</div></td>\n";
	echo "    <td><div align=\"center\">No KP</div></td>\n";
	echo "    <td><div align=\"center\">Nama</div></td>\n";
	echo "    <td><div align=\"center\">Kelas</div></td>\n";
	echo "    <td><div align=\"center\">MP</div></td>\n";
	echo "    <td><div align=\"center\">LoginID</div></td>\n";
	
	if ($_SESSION["level"]=="7"){
	  echo "    <td><div align=\"center\">Password</div></td>\n";
	}  
	echo "    <td><div align=\"center\">Level</div></td>\n";
	if ($_SESSION["level"]=="7" or $_SESSION["level"]=="6" or $_SESSION["level"]=="5"){
	  echo "    <td>Edit</td>\n";
	  echo "    <td>Hapus</td>\n";
	  echo "    <td>Pindah</td>\n";
	}
	echo "    <td>Reset</td>\n";
	echo "  </tr>\n";
	$pg=(int) $_GET["pg"];
	if ($pg==0 or $post<>"")
		$pg=1;
	
	$recordpage=30;
	$startrec=($pg-1)*$recordpage+1;
	$endrec=($startrec+$recordpage)-1;  
	$rowstart=($pg-1)*30;
	$query_sm = "SELECT Nokp,Login.KodSek,login.NamaSek,User1,pswd,nama,keycode,level1,Statussek FROM login,tsekolah 
				 where login.kodsek=tsekolah.kodsek";
				 
	if ($_SESSION["level"]=="3" or $_SESSION["level"]=="4" or $_SESSION["level"]=="P") //SU PEPERIKSAAN dan Pengetua
	  $query_sm.=" and login.KodSek='".$_SESSION["kodsek"]."' ";
	else if ($_SESSION["level"]=="5") //PPD
	  $query_sm.=" and KodPPD='".$_SESSION["kodsek"]."' ";
	else if ($_SESSION["level"]=="6") {//jpn
	  $query_sm.=" and tsekolah.KodNegeriJPN='".$_SESSION["kodsek"]."' ";
	}
	
	if ($level_cari=="3/4" ){ //SU PEPERIKSAAN	
		$query_sm.=" and Level1 in ('3','4') ";
	}else if ($level_cari=="" and ($_SESSION["level"]=="7" or $_SESSION["level"]=="6" or $_SESSION["level"]=="5")) {
		$query_sm = "SELECT Nokp,Login.KodSek,login.NamaSek,User1,pswd,nama,level1 FROM login";
		$query_sm.=" where nokp is not null";
		if($_SESSION["level"]=="5"){//PPD
			$query_sm = "SELECT Nokp,Login.KodSek,login.NamaSek,User1,pswd,nama,level1 FROM login,tsekolah ";
			$query_sm.=" where login.kodsek=tsekolah.kodsek ";
			$query_sm.=" and kodppd='".$_SESSION["kodsek"]."' ";
		}elseif ($_SESSION["level"]=="6"){//JPN
			$query_sm.=" and KodNegeri='".$_SESSION["kodnegeri"]."' ";
		}
	}else if ($level_cari=="5") {//PPD
		$query_sm = "SELECT Nokp,Login.KodSek,login.NamaSek,User1,pswd,nama,level1 FROM login ";
		$query_sm.=" where  Level1='5'  ";
		if ($_SESSION["level"]=="5")			 
			$query_sm.=" and kodsek='".$_SESSION["kodsek"]."' ";
		else if ($_SESSION["level"]=="6") // JPN
			$query_sm.=" and login.KodNegeri='".$_SESSION["kodnegeri"]."' ";
	}else if ($level_cari=="6") {//JPN
		$query_sm = "SELECT Nokp,Login.KodSek,login.NamaSek,User1,pswd,nama,level1 FROM login";
		$query_sm.=" where  Level1='6'";
		
		if ($_SESSION["level"]<>"7")			 
			$query_sm.=" and KodNegeri='".$_SESSION["kodsek"]."' ";
	}else if ($level_cari<>""){
		$query_sm.=" and Level1='$level_cari' ";
	}
	
	if ($kodsek_cari<>"") {
		if ($_SESSION["level"]=="7")
			$query_sm.=" and login.Kodsek like '$kodsek_cari%' ";
		else
			$query_sm.=" and login.Kodsek='$kodsek_cari' ";
	}
	if ($namasek_cari<>"")
	  $query_sm.=" and login.NamaSek like '%$namasek_cari%' ";
	
	if ($login_cari<>"")
	  $query_sm.=" and User1 like '%$login_cari%' ";
	if ($nokp_cari<>"")
	  $query_sm.=" and nokp like '%$nokp_cari%' ";
	if ($nama_cari<>"")
	  $query_sm.=" and Nama like '%$nama_cari%' ";
	$query_sm.=" order by Nama";
	//if ($_SESSION["level"]=="7")
		//echo "$query_sm<br>";
	//if($_SESSION["kodsek"]=="A090")
	//	echo $query_sm;
	
	  $totalrecord=count_row($query_sm);
	  $qrystr2="select * from ( select a.*,rownum rnum from ($query_sm)a where rownum<=$endrec) where rnum>=$startrec";
	  $result_sm = oci_parse($conn_sispa,$qrystr2);
	$rowcnt=0;
	//	echo "$qrystr2";
	oci_execute($result_sm);
		$bil=$rowstart;
		while($sm = oci_fetch_array($result_sm)){
			$bil=$bil+1;
			$rowcnt++;
			$nokp=$sm["NOKP"];
			$kodsek=$sm["KODSEK"];
			echo "  <tr><td><center>$bil</center></td>\n";
			echo "  <td>".$sm["STATUSSEK"]."</td>\n";
			if ($_SESSION["level"]=="5" or $_SESSION["level"]=="6" or $_SESSION["level"]=="7"){
			echo "  <td>".$sm["KEYCODE"]."</td>\n";
			}
			echo "  <td>(".$sm["KODSEK"].") ".$sm["NAMASEK"]."</td>\n";
			echo "  <td>$nokp</td>\n";
			echo "  <td>".$sm["NAMA"]."</td>";
			if($sm["LEVEL1"]=="2" or $sm["LEVEL1"]=="4"){
		   $result_kelas = oci_parse($conn_sispa,"select ting,kelas from tguru_kelas where nokp='$nokp' and tahun='".date("Y")."'");
		   oci_execute($result_kelas);
			   if($rowkelas = oci_fetch_array($result_kelas)){
				 $ting1=$rowkelas["TING"];
				 $namakelas1=$rowkelas["KELAS"];
			   } else {
				 $ting1='';
				 $namakelas1='';
			   }
			  echo "<td>$ting1 $namakelas1</td>";
			}
			else echo "<td></td>";
		   $result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp from sub_guru where nokp='$nokp' and tahun='".date("Y")."'");
		   oci_execute($result_mp);
		   echo "<td>";
			while($rowmp = oci_fetch_array($result_mp)){
				 $mp1=$rowmp["KODMP"];
				 $ting1=$rowmp["TING"];
				 $kelas1=$rowmp["KELAS"];
			  echo "$ting1/$kelas1/$mp1<br>";
			}  
			echo "</td>";  
			echo "</td>\n";
			echo "  <td>".$sm["USER1"]."</td>\n";
			
	        if ($_SESSION["level"]=="7")
			  echo "  <td>".$sm["PSWD"]."</td>\n";
			echo "  <td>".$sm["LEVEL1"]."</td>\n";
			if($_SESSION["level"]=="7"){
				echo "  <td><a href=\"edit-user.php?nokp=$nokp\"><center><strong>Edit</strong></center></a></td>\n";
				echo "<td><a href=\"drop-user.php?nokp=$nokp&kodsek=$kodsek\"><center><strong>Hapus</strong></center></a></td>";
				echo "<td><a href=\"pindah-user.php?nokp=$nokp&kodsek=$kodsek\"><center><strong>Pindah</strong></center></a></td>";
			}	
			else if ($_SESSION["level"]=="6") { // JPN
				echo "<td><a href=\"edit-user-jpn.php?nokp=$nokp&kodsek=$kodsek\"><center><strong>Edit</strong></center></a></td>";
				echo "<td><a href=\"drop-user-jpn.php?nokp=$nokp&kodsek=$kodsek\"><center><strong>Hapus</strong></center></a></td>";
				echo "<td><a href=\"pindah-user-jpn.php?nokp=$nokp&kodsek=$kodsek\"><center><strong>Pindah</strong></center></a></td>";
			} else if ($_SESSION["level"]=="5") { // PPD
				echo "<td><a href=\"edit-user-ppd.php?nokp=$nokp&kodsek=$kodsek\"><center><strong>Edit</strong></center></a></td>";
				echo "<td><a href=\"drop-user-ppd.php?nokp=$nokp&kodsek=$kodsek\"><center><strong>Hapus</strong></center></a></td>";
				echo "<td><a href=\"pindah-user-ppd.php?nokp=$nokp&kodsek=$kodsek\"><center><strong>Pindah</strong></center></a></td>";
			}
			//Reset password
			echo "  <td><a href=\"hapus-user.php?nokp=$nokp\" onclick=\"return (confirm('Adakah anda pasti reset ".$sm["MP"]." ?'))\"><center><strong>Reset</strong></center></a></td>\n";
			echo "  </tr>\n";
		}
	if ($rowcnt==0){
	  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"15\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";
	
	}	
	?>	
	<tr bgcolor="#FFFFFF"><td colspan="15">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
	<?php
	paging($totalrecord,$recordpage,"senarai-user.php?cari=1",$pg);
	
		
	echo "</table>\n";
	//echo "<br>";
//}else {
//	echo "<font size=\"3\" color=\"#fe0000\"><center><strong>TIADA REKOD</strong></center></font>";
//}
} 
?>

</td>
<?php include 'kaki.php';?>