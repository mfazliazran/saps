<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
//echo "..";
//echo "level:".$_SESSION['level']."<br>";
if ($_SESSION['level']<>"8" and $_SESSION['level']<>"10" and $_SESSION['level']<>"11" and $_SESSION['level']<>"12" and $_SESSION['level']<>"13" and $_SESSION['level']<>"14" and $_SESSION['level']<>"15")
	die('Anda tidak dibenarkan mengakses skrin ini');
?>
<script type="text/javascript" src="ajax/ajax_sekolah.js"></script>
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
<?php
if ($_SESSION["level"]=="8")
   $bhg="SMT dan KV";
 else if ($_SESSION["level"]=="10")
   $bhg="Bahagian Pendidikan Khas";
 else if ($_SESSION["level"]=="11")
   $bhg="Bahagian Pendidikan Islam";
 else if ($_SESSION["level"]=="12")
   $bhg="Sekolah Berprestasi Tinggi";
 else if ($_SESSION["level"]=="13")
   $bhg="Sekolah Kluster Kecemerlangan";
 else if ($_SESSION["level"]=="14")
   $bhg="Lembaga Peperiksaan";
 else if ($_SESSION["level"]=="15")
   $bhg="Sekolah Berasrama Penuh";

?>
<td valign="top" class="rightColumn">
<p class="subHeader"><?php echo "Senarai Sekolah [$bhg]";?></p>
<?php 
	if ($_POST["post"]=="1"){
		$kodsek_cari=$_POST["txtKodSekolah"];
		$namasek_cari=oci_escape_string($_POST["txtNamaSekolah"]);
		$jpn_cari=$_POST["txtKodJPN"];
		$ppd_cari=$_POST["txtKodPPD"];
		
		$_SESSION["kodsek_cari"]=$kodsek_cari;
		$_SESSION["namasek_cari"]=$namasek_cari;
		$_SESSION["jpn_cari"]=$jpn_cari;
		$_SESSION["ppd_cari"]=$ppd_cari;
	}
	else {
		$kodsek_cari=$_SESSION["kodsek_cari"];
		$namasek_cari=$_SESSION["namasek_cari"];
		$jpn_cari=$_SESSION["jpn_cari"];
		$ppd_cari=$_SESSION["ppd_cari"];
	}
 ?>
 <title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<form name="frm1" method="post" action="">
<TABLE>
<tr><td colspan="3">Carian Senarai Sekolah</td></tr>
<tr><td>KOD SEKOLAH</td><td>:</td><td><input type="text" name="txtKodSekolah" size="10" maxlength="7" value="<?php echo $kodsek_cari; ?>" onkeypress="return ucase(event,this);" /></td></tr>
<tr><td>NAMA SEKOLAH</td><td>:</td><td><input type="text" name="txtNamaSekolah" size="50" maxlength="50" value="<?php echo $namasek_cari; ?>" onkeypress="return ucase(event,this);"/></td></tr> 
<tr><td colspan="3">
<input type="submit" value="Cari" name="submit" />
<input type="hidden" value="1" name="post" />

</td></tr>
</TABLE>
<?php

if ($_SESSION["level"]=="8")//bptv
    $tajuk_senarai="SENARAI SEKOLAH BPTV (SMT DAN KV)";
else if ($_SESSION["level"]=="10")//bpkhas
    $tajuk_senarai="SENARAI SEKOLAH PENDIDIKAN KHAS";
else if ($_SESSION["level"]=="11")//bpi
    $tajuk_senarai="SENARAI SEKOLAH BPI (SR/SM Agama-SABK, SMK Agama)";
else if ($_SESSION["level"]=="12")//sbt
    $tajuk_senarai="SENARAI SEKOLAH BERPRESTASI TINGGI";
else if ($_SESSION["level"]=="13")//skk
    $tajuk_senarai="SENARAI SEKOLAH KLUSTER KECERMELANGAN";
else if ($_SESSION["level"]=="14")//lp
    $tajuk_senarai="SENARAI SEKOLAH";
else if ($_SESSION["level"]=="15")//sbp
    $tajuk_senarai="SENARAI SEKOLAH BERASRAMA PENUH";
	
echo "<br>";
echo "<center><h3>$tajuk_senarai</center></h3><br>";
echo "<table width=\"800\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
//echo "  <tr><td colspan=\"9\"><img src=\"images/add.gif\">&nbsp;<a href=\"tambah-user.php\"><b>Tambah Pengguna</b></a></td>\n";
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">Kod Sekolah</div></td>\n";
echo "    <td><div align=\"center\">Nama Sekolah</div></td>\n";
echo "    <td>Pilih</td>\n";
echo "  </tr>\n";
$pg=(int) $_GET["pg"];
if ($pg==0)
 $pg=1;

$recordpage=30;
$startrec=($pg-1)*$recordpage+1;
$endrec=($startrec+$recordpage)-1;  
$rowstart=($pg-1)*30;

if ($_SESSION["level"]=="8")//bptv
    $query_sm = " SELECT KodSek,NamaSek FROM tsekolah where (kodjenissekolah='203' or kodjenissekolah='303') ";
else if ($_SESSION["level"]=="10")//bpkhas
    $query_sm = " SELECT KodSek,NamaSek FROM tsekolah where kodjenissekolah in ('KHAS')";
else if ($_SESSION["level"]=="11")//bpi
    $query_sm = " SELECT KodSek,NamaSek FROM tsekolah where kodjenissekolah in ('107','204','209')";
else if ($_SESSION["level"]=="12")//sbt
    $query_sm = " SELECT KodSek,NamaSek FROM tsekolah where SBT='Y' ";
else if ($_SESSION["level"]=="13")//skk
    $query_sm = " SELECT KodSek,NamaSek FROM tsekolah where Kluster='Y'";
else if ($_SESSION["level"]=="14")//lp
    $query_sm = " SELECT KodSek,NamaSek FROM tsekolah where kodsek is not null ";
else if ($_SESSION["level"]=="15")//sbp
    $query_sm = " SELECT KodSek,NamaSek FROM tsekolah where kodjenissekolah='206'";

$c=" and";
if ($kodsek_cari<>""){
  $query_sm.=" $c Kodsek='$kodsek_cari' ";
  $c=" and ";
}
if ($namasek_cari<>""){
  $query_sm.=" $c NamaSek like '%$namasek_cari%' ";
  $c=" and ";
}
if ($ppd_cari<>""){
  $query_sm.=" $c kodppd like '%$ppd_cari%' ";
  $c=" and ";
}
if ($jpn_cari<>""){
  $query_sm.=" $c kodnegerijpn like '%$jpn_cari%' ";
  $c=" and ";
}



$query_sm.=" order by kodsek";
  $totalrecord=count_row($query_sm);
  $qrystr2="select * from ( select a.*,rownum rnum from ($query_sm)a where rownum<=$endrec) where rnum>=$startrec";
  $result_sm = oci_parse($conn_sispa,$qrystr2);
$rowcnt=0;


oci_execute($result_sm);
	$bil=$rowstart;
	while($sm = oci_fetch_array($result_sm)){
		$bil=$bil+1;
		$rowcnt++;
		$kodsek2=$sm["KODSEK"];
		echo "  <tr><td><center>$bil</center></td>\n";
		echo "  <td>".$sm["KODSEK"]."</td>\n";
		echo "  <td>".$sm["NAMASEK"]."</td>\n";
		
		echo "  <td><a href=ses.php?kodsek=".$sm["KODSEK"].">Pilih</a></td>\n";
		echo "  </tr>\n";
	}
if ($rowcnt==0){
  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"8\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";

}	
?>	
</form>
<tr bgcolor="#FFFFFF"><td colspan="8">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"senarai_sek_bhg.php?cari=1",$pg);

	
echo "</table>\n";
echo "<br>";
//}

?>