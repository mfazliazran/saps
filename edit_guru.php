<?php 
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';
include('include/function.php');
echo "KELAS - ".$gkelas;
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Guru</p>
<?php
echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "<br>";
echo "<center><h3>KEMASKINI - PINDAH - HAPUS GURU</h3></center>";
echo "";
echo "  <tr bgcolor=\"CCCCCC\">\n";
echo "    <th scope=\"col\">BIL</th>\n";
echo "    <th scope=\"col\">NOKP</th>\n";
echo "    <th scope=\"col\">NAMA</th>\n";
echo "    <th scope=\"col\">JANTINA</th>\n";
echo "    <th scope=\"col\">SUNTING</th>\n";
echo "    <th scope=\"col\">PINDAH<br>SEKOLAH</th>\n";
echo "    <th scope=\"col\">HAPUS</th>\n";
echo "  </tr>\n";
//echo "$kodsek";


$pg=(int) $_GET["pg"];
if ($pg==0)
 $pg=1;

$recordpage=30;
$startrec=($pg-1)*$recordpage+1;
$endrec=($startrec+$recordpage)-1;  
$rowstart=($pg-1)*30;

$query = "SELECT nokp,nama,jan FROM login WHERE kodsek='$kodsek' ORDER BY nama"; 
//$result = oci_parse($conn_sispa,$query);
//oci_execute($result);
$totalrecord=count_row($query);
	$qrystr2="select * from ( select a.*,rownum rnum from ($query)a where rownum<=$endrec) where rnum>=$startrec";
 
 $result_sm = oci_parse($conn_sispa,$qrystr2);
 oci_execute($result_sm);
$rowcnt=0;
$bil=$rowstart;
while ($row=oci_fetch_array($result_sm))
{
	$nokp = $row["NOKP"];
	$nama = $row["NAMA"];
	$jantina = $row["JAN"];
	$bil=$bil+1;
	$rowcnt++;
	


	echo "  <tr>\n";
	echo "    <td><center>$bil</center></td>\n";
	echo "    <td>$nokp</td>\n";
	echo "    <td>$nama</td>\n";
	echo "    <td><center>$jantina</center></td>\n";
	echo "  <td><a href=b_edit_guru.php?data=".$nokp."|".$kodsek."><center><img src = images/edit.png width=12 height=13 Alt=\"Sunting\" border=0></center></a></td>\n";
	echo "  <td><a href=b_guru_pindah.php?data=".$nokp."|".$kodsek."><center><img src = images/pindah.png width=12 height=13 Alt=\"Pindah\" border=0></center></a></td>\n";
	echo "  <td><a href=hapus_guru.php?data=".$nokp."|".$kodsek." onclick=\"return (confirm('Adakah anda pasti hapus $nama ?'))\"><center><img src = images/drop.png width=12 height=13 Alt=\"Hapus\" border=0></center></a></td>\n";
}

if ($rowcnt==0){
  //echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"8\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";

}	
?>	

<tr bgcolor="#FFFFFF"><td colspan="15">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"edit_guru.php?cari=1",$pg);

echo "</table>\n";
echo "<br>";


?>


</td>
<?php include 'kaki.php';?> 