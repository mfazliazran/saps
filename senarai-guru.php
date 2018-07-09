<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');

$kodsek=$_SESSION["kodsek"];
$tahun=$_SESSION["tahun"];
$cari=$_GET["cari"];

$tjan=array("L" => "LELAKI", "P" => "PEREMPUAN");
 function count_row_oci_by_name($sql,$kodsek,$nokp,$nama)
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
  oci_bind_by_name($qic, ':kodsek', $kodsek);
	oci_bind_by_name($qic, ':nokp_cari', $nokp);
	oci_bind_by_name($qic, ':nama_cari', $nama);
  oci_execute($qic);
  if (OCIFetch($qic)){
    $bilrekod=OCIResult($qic,"BILREKOD");
  }
  return($bilrekod);  
}
?>
<td valign="top" class="rightColumn">
<p class="subHeader">***Carian Guru</p>
<?php 
if ($_SESSION["level"]=="5" or $_SESSION["level"]=="3" or $_SESSION["level"]=="4" or $_SESSION["level"]=="6" or $_SESSION["level"]=="7"){
	if ($_POST["post"]=="1"){
		$nokp_cari=$_POST["txtNoKP"];
		$nama_cari=oci_escape_string($_POST["txtNama"]);
		$seksemasa_cari=$_POST["txtSekSemasa"];
		
		$_SESSION["nokp_cari"]=$nokp_cari;
		$_SESSION["nama_cari"]=$nama_cari;
		$_SESSION["seksemasa_cari"]=$seksemasa_cari;
	}
	else {
		$nokp_cari=$_SESSION["nokp_cari"];
		$nama_cari=$_SESSION["nama_cari"];
		$seksemasa_cari=$_SESSION["seksemasa_cari"];
	}
 if ($seksemasa_cari==""){
   if ($_SESSION["level"]=="3" or $_SESSION["level"]=="4")
     $seksemasa_cari="1";
   else
     $seksemasa_cari="0";   
	 

} 
 ?>
<form name="frm1" method="post" action="senarai-guru.php?cari=1">
<TABLE>
<tr><td width="120">NO K/P</td><td width="5">:</td><td><input type="text" name="txtNoKP" size="14" maxlength="12" /></td></tr>
<tr><td>NAMA</td><td>:</td><td><input type="text" name="txtNama" size="50" maxlength="50" /></td></tr>
<?php if ($_SESSION["level"]=="3" or $_SESSION["level"]=="4" or $_SESSION["level"]=="7") { ?>
<tr><td valign="top">GURU</td><td valign="top">:</td><td>
<input <?php if ($seksemasa_cari=="1") echo " checked "; ?> type="radio" name="txtSekSemasa" id="txtSekSemasa1" value="1"/>
<label for="txtSekSemasa1"><?php echo $namasek; ?></label>
<br><input <?php if ($seksemasa_cari=="0") echo " checked "; ?> type="radio" name="txtSekSemasa" id="txtSekSemasa2" value="0"/>
<label for="txtSekSemasa2"><?php echo "SEMUA SEKOLAH";?></label>
</td></tr>
<?php } //level 3/4 ?>
<tr><td colspan="3">
<input type="submit" value="Cari" name="submit" />
<input type="hidden" value="1" name="post" />

</td></tr>
</TABLE>
</form>
<?php
if ($_GET["cari"]=="1"){ 
	echo "<br>";
	echo "<center><h3>SENARAI GURU ";
	if ($seksemasa_cari=="1")
	  echo "$namasek"; 
	else
	  echo " SEMUA SEKOLAH ";
	echo "</center></h3><br>";
	echo "<table width=\"850\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
	echo "  <tr bgcolor=\"#CCCCCC\">\n";
	echo "    <td width=\"30\"><div align=\"center\">Bil</div></td>\n";
	echo "    <td width=\"110\"><div align=\"center\">No KP</div></td>\n";
	echo "    <td width=\"300\"><div align=\"center\">Nama</div></td>\n";
	echo "    <td width=\"300\"><div align=\"center\">Sekolah</div></td>\n";
	echo "    <td width=\"110\"><div align=\"center\">Jantina</div></td>\n";
	echo "    <td width=\"30\">Edit</td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	$pg=(int) $_GET["pg"];
	if ($pg==0)
	 $pg=1;
	$query_sm="select * from login "; 
	$recordpage=30;
	$startrec=($pg-1)*$recordpage+1;
	$endrec=($startrec+$recordpage)-1;  
	$rowstart=($pg-1)*30;
	$c=" where ";
	if ($seksemasa_cari=="1"){
	  $query_sm.=" $c KodSek= :kodsek ";
	  $c=" and ";
	}
	  
	if ($nokp_cari<>""){
	  $query_sm.=" $c nokp like '%'|| :nokp_cari ||'%' ";
	  $c=" and ";
	}  
	if ($nama_cari<>""){
	  $query_sm.=" $c Nama like '%'|| :nama_cari ||'%' ";
	  $c=" and ";
	}
	$query_sm.=" order by nama";
//echo $query_sm;
	$totalrecord=count_row_oci_by_name($query_sm,$_SESSION["kodsek"],$nokp_cari,$nama_cari);
	$qrystr2="select * from ( select a.*,rownum rnum from ($query_sm) a where rownum<=$endrec) where rnum>=$startrec";
	$qic = oci_parse($conn_sispa,$qrystr2);
	oci_bind_by_name($qic, ':kodsek', $_SESSION["kodsek"]);
	oci_bind_by_name($qic, ':nokp_cari', $nokp_cari);
	oci_bind_by_name($qic, ':nama_cari', $nama_cari);
	$rowcnt=0;


	$flg=0;
	oci_execute($qic);
		$bil=$rowstart;
		while($sm = oci_fetch_array($qic)){
			$bil=$bil+1;
			$rowcnt++;
			$nokp=$sm["NOKP"];
			  if (!$flg){
				 $bgcol="#FFFFCC";
				 $flg=1;
			  }	 
			  else {
				 $bgcol="#FFFFFF";	
				 $flg=0;
			  }  			 
			$jantina=$sm["JAN"];
			if ($idx>1)
			  $rowspan="rowspan=\"$idx\"";
			else
			  $rowspan="";		
			echo "  <tr bgcolor=\"$bgcol\"><td><center>$bil</center></td>\n";
			echo "  <td>".$sm["NOKP"]."</td>\n";
			echo "  <td>".$sm["NAMA"]."</td>\n";
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
			echo "  <td>".$tjan[$jantina]."</td>\n";
			echo "  <td>";
			if ($_SESSION["level"]=="7")
			   echo "<a href=\"edit_guru.php?nokp=$nokp\"><center>Edit</center></a>";
			else
			   echo "&nbsp;";		
			echo "</td>\n";
			echo "  </tr>\n";
			
		}
	if ($rowcnt==0){
	  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"11\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";
 }//$_GET["cari"]=="1"
?> 
<tr bgcolor="#FFFFFF"><td colspan="11">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"senarai-guru.php?cari=1",$pg);
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