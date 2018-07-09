<script type="text/javascript">
function semak()
{
	if(document.frm1.kodsek.value==""){
		alert("Sila pilih sekolah pindah !");
		return false;
	}
	
return confirm("Pindahkan pengguna ini ?");	
}

function openWin (fileName,windowName) 
{ 
	mywindow = window.open(fileName,windowName,'width=800,height=800,directories=no,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,resizable=no'); 
	mywindow.moveTo(screen.width/2-400,screen.height/2-400);
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
<p class="subHeader">Pindah Pengguna Level JPN</p>

<?php
if(!($_SESSION["level"]=="6" and $_SESSION["pentadbir"]=="1")){
	die("Utiliti ini hanya untuk kegunaan JPN sahaja !");
}

if ($_POST["post"]=="1"){
  $level_asal=$_POST["level_asal"];
  $level_baru=$_POST["level"];
  
  if($level_baru=="1"){ //tukar ke sekolah
	  if($level_asal=="1" or $level_asal=="2" or $level_asal=="3" or $level_asal=="4" or $level_asal=="PK" or $level_asal=="P") { //1,2,3,4,PK,P
         $level_baru=$level_asal;
      }		  
  }	
  $nokp=$_POST["nokp"];
  $kodsekpindah=$_POST["kodsek"]; 
  $namasekpindah=oci_escape_string($_POST["sek"]); 

  if($level_baru=="5"){ //PPD
	 $kodnegeri=keterangan("TKPPD","KODNEGERI","KODPPD",$kodsekpindah);  
     $negeri=keterangan("TKNEGERI","NEGERI","KODNEGERI",$kodnegeri);  
	 $daerah="";
  }
  else if($level_baru=="5"){ //PPD
	 $kodnegeri=keterangan("TKPPD","KODNEGERI","KODPPD",$kodsekpindah);  
     $negeri=keterangan("TKNEGERI","NEGERI","KODNEGERI",$kodnegeri);  
	 $daerah="";
  }
  else {
	 $kodnegeri=keterangan("TSEKOLAH","KODNEGERIJPN","KODSEK",$kodsekpindah);  
     $negeri=keterangan("TSEKOLAH","NEGERI","KODSEK",$kodsekpindah);  
	 $daerah="";
  }
  $statussek=keterangan("TSEKOLAH","STATUS","KODSEK",$kodsekpindah);  
  //$query_sm=" update login set kodsek=:kodsekpindah,namasek=:namasekpindah,statussek=:statussek,negeri='$negeri'  where nokp=:nokp1)";
  $query_sm=" update login set kodsek='$kodsekpindah',namasek='$namasekpindah',statussek='$statussek',
    negeri='$negeri',daerah='$daerah',kodnegeri='$kodnegeri',level1='$level_baru'  where nokp='$nokp'";
  /*oci_bind_by_name($result_sm, ':kodsekpindah', $kodsekpindah);
  oci_bind_by_name($result_sm, ':namasekpindah', $namasekpindah);
  oci_bind_by_name($result_sm, ':statussek', $statussek);
  oci_bind_by_name($result_sm, ':nokp1', $nokp);*/
  
  $result_sm = oci_parse($conn_sispa,$query_sm);
  oci_execute($result_sm);
  echo "Pengguna telah dipindahkan.<br>";

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
<tr><td colspan="3"><hr></td></tr>	  
<tr><td>PINDAH KE SEKOLAH/PPD/JPN</td><td>:</td><td><input type="text" readonly="" name="kodsek" id="kodsek" size="10">&nbsp;
<input type="text" readonly="" name="sek" id="sek" size="50">
<input type="button" name="pilih" value="Pilih" onClick="openWin('pilih_lokasi_pindah.php','Lokasi_Pindah');"></td></tr>
<tr><td colspan="3"><hr></td></tr>	  

<tr><td colspan="3"><input type="submit" value="Pindah" name="pindah" onClick="return semak()"/>
<input type="hidden" name="post" value="1">
<input type="hidden" name="nokp" value="<?php echo $nokp; ?>">
<input type="hidden" name="login" value="<?php echo $login; ?>">
<input type="hidden" name="level" id="level" >
<input type="hidden" name="level_asal" id="level_asal" value="<?php echo $level;?>" >

<input type="button" value="Kembali" name="batal" onClick="location.href='senarai-user.php';"/>
</td></tr>
</TABLE>
</form>
<?php
}
?>
</td>
<?php include 'kaki.php';?>