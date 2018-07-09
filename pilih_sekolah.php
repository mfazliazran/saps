<?php 
session_start();
include 'auth.php';
include_once ('config.php');
include('include/function.php');
 function count_row_oci_by_name($sql,$kodsek,$namasek)
 {
 global $conn_sispa;
 
   $pos=strpos($sql,"FROM");
   if ($pos==0)
     $pos=strpos($sql,"from");
   if ($pos==0)
     $pos=strpos($sql,"From");
//echo "POS $pos :- $sql";
  $newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

  $qic = oci_parse($conn_sispa,$newsql);
  oci_bind_by_name($qic, ':kodsek_cari', $kodsek);
  oci_bind_by_name($qic, ':namasek_cari', $namasek);
  oci_execute($qic);
  if (OCIFetch($qic)){
    $bilrekod=OCIResult($qic,"BILREKOD");
  }
  return($bilrekod);  
}
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
<tr><td colspan="3">Carian Senarai Sekolah</td></tr>
<tr><td>KOD SEKOLAH</td><td>:</td><td><input autocomplete="off" type="text" name="txtKodSekolah" size="10" maxlength="7" value="<?php echo $kodsek_cari; ?>" onkeypress="return ucase(event,this);" /></td></tr>
<tr><td>NAMA SEKOLAH</td><td>:</td><td><input autocomplete="off" type="text" name="txtNamaSekolah" size="50" maxlength="50" value="<?php echo $namasek_cari; ?>" onkeypress="return ucase(event,this);"/></td></tr> 


  </select></td></tr>
<tr><td colspan="3">
<input type="submit" value="Cari" name="submit" />
<input type="hidden" value="1" name="post" />

</td></tr>
</TABLE>
<?php
echo "<br>";
echo "<center><h3>SENARAI SEKOLAH</center></h3><br>";
echo "<table width=\"800\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
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
$query_sm = " SELECT KodSek,NamaSek FROM tsekolah ";
$c=" where ";
if ($kodsek_cari<>""){
  $query_sm.=" $c Kodsek= :kodsek_cari ";
  $c=" and ";
}
if ($namasek_cari<>""){
  $query_sm.=" $c NamaSek like '%'|| :namasek_cari ||'%' ";
  $c=" and ";
}

$query_sm.=" order by kodsek";
$totalrecord=count_row_oci_by_name($query_sm,$kodsek_cari,$namasek_cari);

$qrystr2="select * from ( select a.*,rownum rnum from ($query_sm)a where rownum<=$endrec) where rnum>=$startrec";
$qic = oci_parse($conn_sispa,$qrystr2);
oci_bind_by_name($qic, ':kodsek_cari', $kodsek_cari);
oci_bind_by_name($qic, ':namasek_cari', $namasek_cari);
oci_execute($qic);
$rowcnt=0;

$bil=$rowstart;
while($sm = oci_fetch_array($qic)){
	$bil=$bil+1;
	$rowcnt++;
	$kodsek2=$sm["KODSEK"];
	echo "  <tr><td><center>$bil</center></td>\n";
	echo "  <td>".$sm["KODSEK"]."</td>\n";
	echo "  <td>".$sm["NAMASEK"]."</td>\n";
	
	echo "  <td><a href=\"javascript:void(0);\" onclick=\"pilih_sekolah('$kodsek2');\"><center><img src = images/edit.png width=12 height=13 Alt=\"Sunting\" border=0></center></a></td>\n";
	echo "  </tr>\n";
}
if ($rowcnt==0){
  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"8\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";

}	
?>	
</form>
<tr bgcolor="#FFFFFF"><td colspan="8">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"pilih_sekolah.php?cari=1",$pg);

	
echo "</table>\n";
echo "<br>";
//}

?>