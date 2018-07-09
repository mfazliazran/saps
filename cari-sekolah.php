<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
$kodsek=$_SESSION["kodsek"];
//$tahun_semasa=$_SESSION["tahun"];
$cari=$_GET["cari"];


?>

<script type="text/javascript">

function pilih_tahun(tahun_semasa)
{
//alert(nama_bulan)
//alert(tahun)

//location.href="mpep-daerah.php?tahun=" + tahun_semasa
location.href="cari-sekolah.php?tahun=" + tahun_semasa


}

</script>
<?php
$tahun_semasa = $_GET['tahun'];

if($tahun_semasa <> "") {
	$tahun = $tahun_semasa;
} else {
	$tahun = $_SESSION["tahun"];//date("Y");
}

//$_SESSION['tahun'] = $tahun;

$tahun_sekarang = date("Y");

?>

<td valign="top" class="rightColumn">
<p class="subHeader">Carian Sekolah</p>
<?php 
if ($_SESSION["level"]=="7"){
	if ($_POST["post"]=="1"){
		$sekolah_cari=$_POST["txtKodSekolah"];
		$nama_sekolah=oci_escape_string($_POST["txtNamaSekolah"]);
		$seksemasa_cari=$_POST["txtSekSemasa"];
		
		$_SESSION["sekolah_cari"]=$sekolah_cari;
		$_SESSION["nama_sekolah"]=$nama_sekolah;
		$_SESSION["seksemasa_cari"]=$seksemasa_cari;
		$tahun = $_POST["tahun_semasa"];
	}
	else {
		$sekolah_cari=$_SESSION["sekolah_cari"];
		$nama_sekolah=$_SESSION["nama_sekolah"];
		$seksemasa_cari=$_SESSION["seksemasa_cari"];
	}
 if ($seksemasa_cari==""){
   if ($_SESSION["level"]=="3" or $_SESSION["level"]=="4")
     $seksemasa_cari="1";
   else
     $seksemasa_cari="0";   
	 

} 
 ?>
<form name="frm1" method="post" action="cari-sekolah.php?cari=1">
<TABLE border="0">
<tr><td>TAHUN</td><td>:</td><td>
<select name="tahun_semasa" id="tahun_semasa">
<option value="">-- Pilih Tahun --</option>
<?php
	for($thn = 2011; $thn <= 2012; $thn++ ){
		if($tahun == $thn){
			echo "<option value='$thn' selected>$thn</option>";
		} else {
			echo "<option value='$thn'>$thn</option>";
		}
	}			
?>
</select></td></tr>
<tr><td>KOD SEKOLAH</td><td>:</td><td><input type="text" name="txtKodSekolah" size="14" maxlength="12" /></td></tr>
<tr><td>NAMA SEKOLAH</td><td>:</td><td><input type="text" name="txtNamaSekolah" size="50" maxlength="50" /></td></tr>
<tr><td colspan="3"><input style="width:100px" type="submit" value="Cari" name="submit" /><input type="hidden" value="1" name="post" /></td></tr>
</TABLE>
</form>
<?php
if ($_GET["cari"]=="1"){ 
	echo "<br>";
	echo "<center><h3>SENARAI SEKOLAH ";
	if ($seksemasa_cari=="1")
	  echo "$namasek"; 
	else
	  echo " SEMUA SEKOLAH ";
	echo "</center></h3><br>";
	echo "<table width=\"850\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
	echo "  <tr bgcolor=\"#CCCCCC\">\n";
	echo "    <td width=\"30\"><div align=\"center\">Bil</div></td>\n";
	echo "    <td width=\"110\"><div align=\"center\">Kod Sekolah</div></td>\n";
	echo "    <td width=\"300\"><div align=\"center\">Nama Sekolah</div></td>\n";
	echo "    <td width=\"300\"><div align=\"center\">Maklumat Sekolah</div></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	$pg=(int) $_GET["pg"];
	if ($pg==0)
	 $pg=1;
	$query_sm="select * from tsekolah "; 
	$recordpage=30;
	$startrec=($pg-1)*$recordpage+1;
	$endrec=($startrec+$recordpage)-1;  
	$rowstart=($pg-1)*30;
	$c=" where ";
	if ($seksemasa_cari=="1"){
	  $query_sm.=" $c kodsek='".$_SESSION["kodsek"]."' ";
	  $c=" and ";
	}
	  
	if ($sekolah_cari<>""){
	  $query_sm.=" $c kodsek like '$sekolah_cari%' ";
	  $c=" and ";
	}  
	if ($nama_sekolah<>""){
	  $query_sm.=" $c namasek like '%$nama_sekolah%' ";
	  $c=" and ";
	}
	$query_sm.=" order by kodsek";
	//echo $query_sm;
	  $totalrecord=count_row($query_sm);
	  $qrystr2="select * from ( select a.*,rownum rnum from ($query_sm) a where rownum<=$endrec) where rnum>=$startrec";
	  //echo "$qrystr2<br>";
	  $result_sm = oci_parse($conn_sispa,$qrystr2);
	$rowcnt=0;


	$flg=0;
	oci_execute($result_sm);
		$bil=$rowstart;
		while($sm = oci_fetch_array($result_sm)){

			$bil=$bil+1;
			$rowcnt++;
			$kodsek=$sm["KODSEK"];
			  if (!$flg){
				 $bgcol="#F0E68C";
				 $flg=1;
			  }	 
			  else {
				 $bgcol="#D1D1D1";	
				 $flg=0;
			  }  			 
			//$jantina=$sm["JAN"];
			if ($idx>1)
			  $rowspan="rowspan=\"$idx\"";
			else
			  $rowspan="";		
			echo "  <tr bgcolor=\"$bgcol\"><td><center>$bil</center></td>\n";
			//echo "  <td><center>".$sm["KODSEK"]."</center></td>\n";
		    echo "  <td><center><a href=\"mp-guru.php?kodsek=$kodsek&tahun=$tahun\"><center><strong>".$sm["KODSEK"]."</strong></center></a></center></td>\n";
			//echo haha;
			//<a href='pencapaian_kelas.php?ting=$ting&kodsek=$kodsek1&jenis=$jpep' target='_blank'>$kodsek1</a>
			echo "  <td><center>".$sm["NAMASEK"]."<center></td>\n";
			$kodsek2=$sm["KODSEK"];
			$namasek2=$sm["NAMASEK"];
			
  	        $result_sek = oci_parse($conn_sispa,"select NOTELEFON from TSEKOLAH where KODSEK='$kodsek2'");
	        oci_execute($result_sek);
			$data_sek=oci_fetch_array($result_sek);

			echo "<td>$kodsek2 - $namasek2 ";
			if ($data_sek["NOTELEFON"]<>""){
			  echo "(TEL:".$data_sek["NOTELEFON"].")";
			}
			echo "</td>\n";
			//echo $tahun;
			//echo "  <td>".$tjan[$jantina]."</td>\n";
			//echo "  <td>";
			//if ($array_kodsek[0]==$_SESSION["kodsek"])
			//   echo "<a href=\"b_kemaskini_guru_su.php?nokp=$nokp\"><center><img src = images/edit.png width=12 height=13 Alt=\"Sunting\" border=0></center></a>";
			//else
			//   echo "&nbsp;";		
			//echo "</td>\n";
			echo "  </tr>\n";
			
		}
	if ($rowcnt==0){
	  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"11\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";
 }//$_GET["cari"]=="1"
?> 
<tr bgcolor="#FFFFFF"><td colspan="11">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"cari-sekolah.php?cari=1",$pg);
}	

	
echo "</table>\n";
echo "<br>";
}

function cari_sek($kodsek)
{
 global $conn_sispa;

 $qry = "SELECT NAMASEK,NOTELEFON from TSEKOLAH where KODSEK='$kodsek'";
	$res = oci_parse($conn_sispa,$qry);
	oci_execute($res);
	$data=oci_fetch_array($res);
	$namasek=$data["NAMASEK"]." (Tel:".$data["NOTELEFON"].")";
 return($namasek);

}
?>

</td>
<?php include 'kaki.php';?>