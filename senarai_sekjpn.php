<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
include "input_validation.php";

function count_row_oci_by_name($sql,$val1,$val2,$val3,$val4,$val5,$param1,$param2,$param3,$param4,$param5){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	//echo "POS $pos :- $sql";
	$newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

	$qic = oci_parse($conn_sispa,$newsql);
	oci_bind_by_name($qic, $param1, $val1);
	oci_bind_by_name($qic, $param2, $val2);
	oci_bind_by_name($qic, $param3, $val3);
	oci_bind_by_name($qic, $param4, $val4);
	oci_bind_by_name($qic, $param5, $val5);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

if ($_SESSION['level']<>"6")
	die('Anda bukan level JPN');
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
<p class="subHeader">Senarai Sekolah [Bahagian Jabatan Pelajaran Negeri]</p>
<?php 


	if (validate($_POST["post"])=="1"){
		$kodsek_cari=validate($_POST["txtKodSekolah"]);
		$namasek_cari=oci_escape_string(validate($_POST["txtNamaSekolah"]));
		$jpn_cari=validate($_POST["txtKodJPN"]);
		$ppd_cari=validate($_POST["txtKodPPD"]);
		
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


  </select></td></tr>
<tr><td colspan="3">
<input type="submit" value="Cari" name="submit" />
<input type="hidden" value="1" name="post" />

</td></tr>
</TABLE>
<?php
echo "<br>";
echo "<center><h3>SENARAI SEKOLAH</center></h3><br>";

//echo($_SESSION['kodsek']);

echo "<table width=\"800\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
//echo "  <tr><td colspan=\"9\"><img src=\"images/add.gif\">&nbsp;<a href=\"tambah-user.php\"><b>Tambah Pengguna</b></a></td>\n";
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">Kod Sekolah</div></td>\n";
echo "    <td><div align=\"center\">Nama Sekolah</div></td>\n";
echo "    <td>Pilih</td>\n";
echo "  </tr>\n";
$pg=(int) validate($_GET["pg"]);
if ($pg==0)
 $pg=1;

$recordpage=30;
$startrec=($pg-1)*$recordpage+1;
$endrec=($startrec+$recordpage)-1;  
$rowstart=($pg-1)*30;

$query_sm = " SELECT KodSek,NamaSek FROM tsekolah WHERE kodnegerijpn= :kodsek_s";

$c=" AND";
if ($kodsek_cari<>""){
  $query_sm.=" $c Kodsek= :kodsek_c ";
  $c=" AND ";
}
if ($namasek_cari<>""){
  $query_sm.=" $c NamaSek like '%'|| :namasek_c ||'%' ";
  $c=" AND ";
}
if ($ppd_cari<>""){
  $query_sm.=" $c kodppd like '%'|| :ppd_c ||'%' ";
  $c=" AND ";
}
if ($jpn_cari<>""){
  $query_sm.=" $c kodnegerijpn like '%'|| :jpn_c ||'%' ";
  $c=" AND ";
}

$query_sm.=" ORDER BY kodsek";
$totalrecord = count_row_oci_by_name($query_sm, $_SESSION["kodsek"], $kodsek_cari, $namasek_cari, $ppd_cari, $jpn_cari, ":kodsek_s", ":kodsek_c", ":namasek_c", ":ppd_c", ":jpn_c");
$qrystr2="select * from ( select a.*,rownum rnum from ($query_sm)a where rownum<=$endrec) where rnum>=$startrec";
$result_sm = oci_parse($conn_sispa,$qrystr2);
oci_bind_by_name($result_sm, ':kodsek_s', $_SESSION["kodsek"]);
oci_bind_by_name($result_sm, ':kodsek_c', $kodsek_cari);
oci_bind_by_name($result_sm, ':namasek_c', $namasek_cari);
oci_bind_by_name($result_sm, ':ppd_c', $ppd_cari);
oci_bind_by_name($result_sm, ':jpn_c', $jpn_cari);
$rowcnt=0;


oci_execute($result_sm);
	$bil=$rowstart;
	while($sm = oci_fetch_array($result_sm)){
		$bil=$bil+1;
		$rowcnt++;
		echo "  <tr><td><center>$bil</center></td>\n";
		echo "  <td>".$sm["KODSEK"]."</td>\n";
		echo "  <td>".$sm["NAMASEK"]."</td>\n";
		
		echo "  <td><a href=ses-jpn.php?kodsek=".$sm["KODSEK"].">Pilih</a></td>\n";
		echo "  </tr>\n";
	}
if ($rowcnt==0){
  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"8\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";

}	
?>	
</form>
<tr bgcolor="#FFFFFF"><td colspan="8">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"senarai_sekjpn.php?cari=1",$pg);

	
echo "</table>\n";
echo "<br>";
//}

?>