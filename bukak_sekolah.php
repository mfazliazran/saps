<script type="text/javascript" src="ajax/ajax_carikodsekolah.js"></script>
<script type="text/javascript" language="javascript">
function selectSekolah(kod,ktrgn){
	document.getElementById("kodsek").value = kod;
	document.getElementById("namasek").value = ktrgn;	
	document.getElementById("divCariSekolah").style.display = "none";
	document.getElementById("divSenaraiSekolah").style.display = "none";
}

function semak_input(){
	if(document.getElementById("kodsek").value==""){
		alert("Sila pilih sekolah.");
		document.getElementById("kodsek").focus();
		return false;
	}
	if(document.getElementById("tarikhbuka").value==""){
		alert("Sila pilih tarikh buka.");
		document.getElementById("tarikhbuka").focus();
		return false;
	}
	if(document.getElementById("tarikhtutup").value==""){
		alert("Sila pilih tarikh tutup.");
		document.getElementById("tarikhtutup").focus();
		return false;
	}
	return true;
}
</script>
<style type="text/css">
.readonly {
   background: #EEEEEE;
}
</style>
<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include 'fungsikira.php';
$statussek1 = $_SESSION['statussek'];
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Buka Sekolah</p>
<?php
if ($_SESSION["level"]<>7 and $_SESSION["level"]<>6){
die("Menu ini hanya untuk admin & JPN");
}

$tahun_sekarang = date("Y-m-d");    
?>
  <h3><center>Buka Pengisian Sekolah</center></h3>
  <form name="frm1" action="bukak_sekolah_save.php" method="post">
	<table width="991" border="0" align="center" cellpadding="0" cellspacing="5">
	  <tr>
	    <td width="111" valign="top">KOD SEKOLAH</td>
	    <td width="3" valign="top">:</td>
	    <td colspan="2"><input autocomplete="off" type="text" name="kodsek" id="kodsek" maxlength="7" size="10" readonly class="readonly">&nbsp;<input autocomplete="off" name="namasek" id="namasek" type="text" size="50" readonly class="readonly"><input name="CariSekolah" value="Cari Di Sini" id="CariSekolah" type="button" onClick="senarai_kodsekolah(document.getElementById('txtCariSekolah').value);" /><!--<a href="javascript:void(0)" onClick="senarai_kodsekolah(document.getElementById('txtCariSekolah').value);"><img src="images/arrow_down.png" height="15" width="15"></a>-->
                    <div id="divCariSekolah" style="display:none"><b>Carian&nbsp;</b><input style="text-transform:uppercase" type="textbox" name="txtCariSekolah" id="txtCariSekolah" size="30"  autocomplete="off" onkeyup="senarai_kodsekolah(this.value);">
	      <a href="javascript:void(0);" onClick="document.getElementById('divCariSekolah').style.display='none';document.getElementById('divSenaraiSekolah').style.display='none';"><b>X Tutup</b></a></div><div id="divSenaraiSekolah"></div></td>
      </tr>
      <tr>
        <td>TARIKH BUKA</td>
        <td>:</td>
        <td width="80"><input size="10" type="text" name="tarikhbuka" id="tarikhbuka" value="<?php echo date("Y-m-d")?>" readonly></td>
        <td width="772"><font color="#FF0004"><b>**YYYY-MM-DD</b></font></td>
      </tr>
      <tr>
	    <td>TARIKH TUTUP</td>
	    <td>:</td>
	    <td><input size="10" type="text" name="tarikhtutup" id="tarikhtutup" maxlength="10"></td>
	    <td><input type="submit" value="Hantar" name="hantar" onClick="return semak_input();"></td>
      </tr>
  </table>
  </form>
	<p>
<table width="898" border="1" align="center" cellpadding="0" cellspacing="0">
      <tr align="center" bgcolor="#CCCCCC">
        <td width="44" height="35">BIL</td>
        <td width="356">KOD SEKOLAH</td>
        <td width="200">TARIKH BUKA</td>
        <td width="200">TARIKH TUTUP</td>
        <td width="86">&nbsp;</td>
      </tr>
      <?php
	  $i=0;
	  $sqlbukatutup = "select * from bukasekolah where tarikh_tutup >= '$tahun_sekarang' ";
	  if($_SESSION["level"]==6){
		  $sqlbukatutup.=" and kodsek in (select kodsek from tsekolah where kodnegerijpn='".$_SESSION["kodnegeri"]."') ";
	  }
	  $sqlbukatutup .= " order by kodsek";
	  //echo $sqlbukatutup;
	  $resk = oci_parse($conn_sispa,$sqlbukatutup);
	  oci_execute($resk);
	  while($row=oci_fetch_array($resk)){
		  $i++;
      $html = '<tr align="center">
        <td height="35">'.$i.'</td>
        <td>'.$row["KODSEK"].'</td>
        <td>'.$row["TARIKH_BUKA"].'</td>
        <td>'.$row["TARIKH_TUTUP"].'</td>
		<td><a href="delete_sekolah_buka.php?kodsek='.$row["KODSEK"].'">Padam</a></td>
      </tr>';
	  echo $html;
	  }
	  ?>
  </table>
  <?php include 'kaki.php';?>