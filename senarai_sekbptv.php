<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
//echo "..";

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
<td valign="top" class="rightColumn">
<p class="subHeader">Senarai Sekolah BPTV (SMT dan KV)</p>
<?php 
//echo "level:".$_SESSION["level"]." ppd:".$_SESSION["kodppd"];
//if ($_SESSION["level"]=="5" or $_SESSION["level"]=="3" or $_SESSION["level"]=="4" or $_SESSION["level"]=="6" or $_SESSION["level"]=="7" or $_SESSION["level"]=="2" or $_SESSION["level"]=="1"){
	//echo "hohohohoho";
	if ($_POST["post"]=="1"){
		$kodsek_cari=$_POST["txtKodSekolah"];
		$namasek_cari=$_POST["txtNamaSekolah"];
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
<tr><td colspan="3">Carian Senarai Sekolah.</td></tr>
<tr><td>KOD SEKOLAH</td><td>:</td><td><input type="text" name="txtKodSekolah" size="10" maxlength="7" value="<?php echo $kodsek_cari; ?>" onkeypress="return ucase(event,this);" /></td></tr>
<tr><td>NAMA SEKOLAH</td><td>:</td><td><input type="text" name="txtNamaSekolah" size="50" maxlength="50" value="<?php echo $namasek_cari; ?>" onkeypress="return ucase(event,this);"/></td></tr> 


  </select></td></tr>
<tr><td colspan="3">
<input type="submit" value="Cari" name="submit" />
<input type="hidden" value="1" name="post" />

</td></tr>
</TABLE>
<?php
echo "<br>";
echo "<center><h3>SENARAI SEKOLAH BPTV (SMT DAN KV)</center></h3><br>";
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
//$query_sm = " SELECT KodSek,NamaSek FROM tsekolah where (SBT='Y' or KLUSTER='Y')";
$query_sm = " SELECT KodSek,NamaSek FROM tsekolah where (kodjenissekolah='203' or kodjenissekolah='303') ";

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
paging($totalrecord,$recordpage,"senarai_sekbptv.php?cari=1",$pg);

	
echo "</table>\n";
echo "<br>";
//}

?>