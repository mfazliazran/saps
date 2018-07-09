<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
$kodsek=$_SESSION["kodsek"];
$tahun=$_SESSION["tahun"];
$cari=$_GET["cari"];

$tagama=array("01" => "ISLAM", "02" => "BUKAN ISLAM");

$tjan=array("L" => "LELAKI", "P" => "PEREMPUAN");

$tkaum=array("01" => "MELAYU", "02" => "CINA", "03" => "INDIA", "04" => "ASLI");

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Carian Murid</p>
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
<form name="frm1" method="post" action="senarai-murid.php?cari=1">
<TABLE>
<tr><td width="120">NO K/P</td><td width="5">:</td><td><input type="text" name="txtNoKP" size="14" maxlength="12" /></td></tr>
<tr><td>NAMA</td><td>:</td><td><input type="text" name="txtNama" size="50" maxlength="50" /></td></tr>
<?php if ($_SESSION["level"]=="3" or $_SESSION["level"]=="4") { ?>
<tr><td valign="top">MURID</td><td valign="top">:</td><td>
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
	echo "<center><h3>SENARAI MURID ";
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
	echo "    <td width=\"50\"><div align=\"center\">Tahun</div></td>\n";
	echo "    <td width=\"50\"><div align=\"center\">Darjah</div></td>\n";
	echo "    <td width=\"110\"><div align=\"center\">Kelas</div></td>\n";
	echo "    <td width=\"110\"><div align=\"center\">Jantina</div></td>\n";
	echo "    <td width=\"110\"><div align=\"center\">Kaum</div></td>\n";
	echo "    <td width=\"110\"><div align=\"center\">Agama</div></td>\n";
	echo "    <td width=\"30\">Edit</td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	$pg=(int) $_GET["pg"];
	if ($pg==0)
	 $pg=1;
	 
	$recordpage=30;
	$startrec=($pg-1)*$recordpage+1;
	$endrec=($startrec+$recordpage)-1;  
	$rowstart=($pg-1)*30;
	if ($_SESSION['statussek']=="SM"){
		$sek="((kodsekp='$kodsek' and tahunp='$tahun') or (kodsekt1='$kodsek' and tahunt1='$tahun')
					 or (kodsekt2='$kodsek' and tahunt2='$tahun') or (kodsekt3='$kodsek' and tahunt3='$tahun')
					 or (kodsekt4='$kodsek' and tahunt4='$tahun') or (kodsekt5='$kodsek' and tahunt5='$tahun')
					 or (kodsekt6='$kodsek' and tahunt6='$tahun'))";
		$query_sm = "SELECT nokp,namap,kodsekp,tahunp,p,kelasp,kodsekt1,tahunt1,t1,kelast1,kodsekt2,tahunt2,t2,kelast2,
					 kodsekt3,tahunt3,t3,kelast3,kodsekt4,tahunt4,t4,kelast4,kodsekt5,tahunt5,t5,kelast5,
					 kodsekt6,tahunt6,t6,kelast6,jantina,kaum,agama FROM tmurid ";
	}
	else {
		$sek="((kodsekd1='$kodsek' and tahund1='$tahun')
					 or (kodsekd2='$kodsek' and tahund2='$tahun') or (kodsekd3='$kodsek' and tahund3='$tahun')
					 or (kodsekd4='$kodsek' and tahund4='$tahun') or (kodsekd5='$kodsek' and tahund5='$tahun')
					 or (kodsekd6='$kodsek' and tahund6='$tahun'))";
		$query_sm = "SELECT nokp,namap,kodsekd1,tahund1,d1,kelasd1,kodsekd2,tahund2,d2,kelasd2,kodsekd3,tahund3,d3,kelasd3,
					 kodsekd4,tahund4,d4,kelasd4,kodsekd5,tahund5,d5,kelasd5,kodsekd6,tahund6,d6,kelasd6,jantina,kaum,agama FROM tmuridsr ";

	}
	$c=" where ";
	if ($seksemasa_cari=="1"){
	  $query_sm.=" $c $sek ";
	  $c=" and ";
	}
	  
	if ($nokp_cari<>""){
	  $query_sm.=" $c nokp like '%$nokp_cari%' ";
	  $c=" and ";
	}  
	if ($nama_cari<>""){
	  $query_sm.=" $c Namap like '%$nama_cari%' ";
	  $c=" and ";
	}
	$query_sm.=" order by namap";
//echo $query_sm;
	  $totalrecord=count_row($query_sm);
	  $qrystr2="select * from ( select a.*,rownum rnum from ($query_sm) a where rownum<=$endrec) where rnum>=$startrec";
	  //echo "$qrystr2<br>";
	  $result_sm = oci_parse($conn_sispa,$qrystr2);
	$rowcnt=0;

	$arr_d[0]="D1";
	$arr_d[1]="D2";
	$arr_d[2]="D3";
	$arr_d[3]="D4";
	$arr_d[4]="D5";
	$arr_d[5]="D6";

	$arr_t[0]="P";
	$arr_t[1]="T1";
	$arr_t[2]="T2";
	$arr_t[3]="T3";
	$arr_t[4]="T4";
	$arr_t[5]="T5";
	$arr_t[6]="T6";

	$flg=0;
	oci_execute($result_sm);
		$bil=$rowstart;
		while($sm = oci_fetch_array($result_sm)){
			$bil=$bil+1;
			$rowcnt++;
			$nokp=$sm["NOKP"];
			if ($_SESSION["statussek"]=="SR"){
			  if (!$flg){
				 $bgcol="#FFFFCC";
				 $flg=1;
			  }	 
			  else {
				 $bgcol="#FFFFFF";	
				 $flg=0;
			  }  			 
			  $idx=0;
			  for($i=0;$i<6;$i++){
				 $d=$arr_d[$i];
				 if(strlen($sm["TAHUN$d"])==4){
				   $array_kodsek[$idx]=$sm["KODSEK$d"];
				   $array_tahun[$idx]=$sm["TAHUN$d"];
				   $array_darjah[$idx]=$sm["$d"];
				   $array_kelas[$idx]=$sm["KELAS$d"];
				   $idx++;
				 } //if
			} //for 
			} //SR
			if ($_SESSION["statussek"]=="SM"){
			  $idx=0;
			  for($i=0;$i<7;$i++){
				 $t=$arr_t[$i];
				 if(strlen($sm["TAHUN$t"])==4){
				   $array_kodsek[$idx]=$sm["KODSEK$t"];
				   $array_tahun[$idx]=$sm["TAHUN$t"];
				   $array_darjah[$idx]=$sm["$t"];
				   $array_kelas[$idx]=$sm["KELAS$t"];
				   $idx++;
				 } //if
			   } //for	 

			}//SM
			$jantina=$sm["JANTINA"];
			$kaum=$sm["KAUM"];
			$agama=$sm["AGAMA"];
			if ($idx>1)
			  $rowspan="rowspan=\"$idx\"";
			else
			  $rowspan="";		
			echo "  <tr bgcolor=\"$bgcol\"><td $rowspan><center>$bil</center></td>\n";
			echo "  <td $rowspan>".$sm["NOKP"]."</td>\n";
			echo "  <td $rowspan>".$sm["NAMAP"]."</td>\n";
			$namasek=cari_sek($array_kodsek[0]);
			echo "<td>$array_kodsek[0] - $namasek</td>\n";
			echo "  <td>$array_tahun[0]</td>\n";
			echo "  <td>$array_darjah[0]</td>\n";
			echo "  <td>$array_kelas[0]</td>\n";
			echo "  <td $rowspan>".$tjan[$jantina]."</td>\n";
			echo "  <td $rowspan>".$tkaum[$kaum]."</td>\n";
			echo "  <td $rowspan>".$tagama[$agama]."</td>\n";
			echo "  <td $rowspan>";
			//if ($array_kodsek[0]==$_SESSION["kodsek"])
			  // echo "<a href=\"b_kemaskini_pelajar_su.php?nokp=$nokp\"><center><img src = images/edit.png width=12 height=13 Alt=\"Sunting\" border=0></center></a>";
			//else
			   echo "&nbsp;";		
			echo "</td>\n";
			echo "  </tr>\n";
			for($row=1;$row<$idx;$row++){
				$namasek=cari_sek($array_kodsek[$row]);
				echo "<tr bgcolor=\"$bgcol\"><td>$array_kodsek[$row] - $namasek</td>\n";
				echo "  <td>$array_tahun[$row]</td>\n";
				echo "  <td>$array_darjah[$row]</td>\n";
				echo "  <td>$array_kelas[$row]</td></tr>\n";
			} //for
			
		}
	if ($rowcnt==0){
	  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"11\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";
 }//$_GET["cari"]=="1"
?> 
<tr bgcolor="#FFFFFF"><td colspan="11">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"senarai-murid.php?cari=1",$pg);
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