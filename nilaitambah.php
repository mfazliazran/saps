<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Markah TOV/ETR <font color="#FFFFFF">(Tarikh Kemaskini Program : 4/8/2011 11:49AM)</font></p>

<?php
$nokp="".$_SESSION['nokp']."";
$kodsek="".$_SESSION['kodsek']."";

if ($_SESSION['statussek']=="SM"){
	$theadcount="headcount";
	$tmp="mpsmkc";
	$tahap="TING";
	$tmurid = "tmurid";
}

if ($_SESSION['statussek']=="SR"){
	$theadcount="headcountsr";
	$tmp="mpsr";
	$tahap="DARJAH";
	$tmurid = "tmuridsr";
}

$querysub = "SELECT ting,kelas,kodmp,bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' ORDER BY ting";
//$qry = OCIParse($conn_sispa,$querysub);
//OCIExecute($qry);
$num = count_row("SELECT * FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' ORDER BY ting");
if($num == 0){
	//echo "<br><br><br><br><br><br><br><br>";
	echo "<center><h3>KEMASKINI MATA PELAJARAN</h3></center>";
	echo "<br><br>";
	echo "<table align=\"center\" width=\"800\" border =\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "  <td colspan=\"4\"><center><font color =\"#ff0000\"><h3><br>Mata Pelajaran Belum Didaftarkan<br>Sila Daftar Mata Pelajaran </h3></font></center></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	} else {
			//echo "<br><br><br><br>";
			echo "<h3><center>Masuk TOV Dan ETR Mata Pelajaran</center></h3>";//<br><br>";
			echo "<table width=\"800\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
			echo "  <tr>\n";
			echo "    <th rowspan=\"2\" scope=\"col\">KELAS</th>\n";
			echo "    <th rowspan=\"2\" scope=\"col\">MASUK TARGET</th>\n";
			echo "    <th colspan=\"4\" scope=\"col\">STATUS TARGET</th>\n";
			echo "    <th rowspan=\"2\" scope=\"col\">CETAK<br>TARGET</th>\n";
			echo "    <th rowspan=\"2\" scope=\"col\">HAPUS<br>TOV/ETR<br>YANG BERMASALAH</th>\n";
			echo "	  <th rowspan=\"2\" scope=\"col\">ANALISIS<br>HC MP</th>\n";
			echo "  </tr>\n";
			echo "  <tr>\n";
			echo "  <td><center>Bil Pel</center></td>\n";
			echo "  <td><center>Bil Tov</center></td>\n";
			echo "  <td><center>Bil Tgt</center></td>\n";
			echo "  <td><center>Status</center></td>\n";
			echo "  </tr>\n";
			$qry = OCIParse($conn_sispa,$querysub);
			OCIExecute($qry);
			while(OCIFetch($qry)) {
				$ting = OCIResult($qry,"TING");//$row['ting'];
				$kelas = OCIResult($qry,"KELAS");//$row['kelas'];
				$kodmp = OCIResult($qry,"KODMP");//$row['kodmp'];
				$bil_am = OCIResult($qry,"BILAMMP");//$row['bilammp'];
				//$qbilmarktg = "SELECT * FROM $theadcount WHERE $tahap='$ting' AND kelas='$kelas' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND hmp='$kodmp' AND getr is not null";
				//$a = OCIParse($conn_sispa,$qbilmarktg);
				//OCIExecute($a);
				$numtarget=count_row("SELECT * FROM $theadcount WHERE $tahap='$ting' AND kelas='$kelas' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND hmp='$kodmp' AND getr is not null");// and nokp in (select nokp from $tmurid where kodsek$ting='$kodsek' and tahun$ting='".$_SESSION['tahun']."' and $ting='$ting' and kelas$ting='$kelas' and kodsek_semasa='$kodsek')");
				
				//$qbilmarktov = "SELECT * FROM $theadcount WHERE $tahap='$ting' AND kelas='$kelas' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND hmp='$kodmp' AND gtov  is not null";// AND tov != ''");
				//$b = OCIParse($conn_sispa,$qbilmarktov);
				//OCIExecute($b);
				$bilmarkhc=count_row("SELECT * FROM $theadcount WHERE $tahap='$ting' AND kelas='$kelas' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND hmp='$kodmp' AND gtov is not null");// and nokp in (select nokp from $tmurid where kodsek$ting='$kodsek' and tahun$ting='".$_SESSION['tahun']."' and $ting='$ting' and kelas$ting='$kelas' and kodsek_semasa='$kodsek')");
				
				$querykod = OCIParse($conn_sispa,"SELECT * FROM $tmp WHERE kod='$kodmp'");
				OCIExecute($querykod);
				OCIFetch($querykod);
				$namamp=OCIResult($querykod,"MP");//$resultkod['mp'];
				//echo "<form name=\"form1\" method=\"post\" action=\"b_markah.php\">";
				echo "  <tr>\n";
				//echo "  <td><a href=b_edit_markah.php?data=".$ting."/".$kelas."/".$kodmp."/".$year."/".$kodsek."/".$jpep." title=\"Masuk Markah\">".$ting." ".$kelas."</a></td>\n";
				//echo "  <td><a href=b_nt_markahc.php?data=".$ting."|".$kelas."|".$kodmp."|".$_SESSION['tahun']."|".$kodsek."|".$nokp.">".$ting." ".$kelas."</a></td>\n";
				//echo "  <td><a href=b_nt_markahc.php?data=".$ting."|".$kelas."|".$kodmp."|".$_SESSION['tahun']."|".$kodsek."|".$nokp.">".$namamp."</a></td>\n";
				echo "  <td>".$ting." ".$kelas."</td>\n";
				echo "  <td><a href=b_nt_markahc.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$_SESSION['tahun']."|".$kodsek."|".$jpep.">".$namamp."</a></td>\n";
				echo "  <td><center>$bil_am</center></td>\n";
				echo "  <td><center>$bilmarkhc</center></td>\n";
				echo "  <td><center>$numtarget</center></td>\n";
				if(($bil_am == $bilmarkhc) AND ($bilmarkhc == $numtarget ) AND ($bilmarkhc!=0)){ $smark = "<center><img src = images/ok.png width=20 height=20></center>";}
				if(($bilmarkhc != $numtarget) OR ($bilmarkhc==0) OR ($bilmarkhc != $bil_am)){ $smark = "<center><img src = images/ko.png width=20 height=20></center>";}
				echo "  <td>$smark</td>\n";
				//if($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1"){
				echo "  <td><a href=tengok_tov.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$_SESSION['tahun']."|".$kodsek."><center><img src = images/preview-icon.gif width=12 height=12 Alt=\"Lihat Markah\" border=0></center></a></td>\n";
				//} else {
				//echo "  <td><center><img src = images/preview-icon.gif width=12 height=12 Alt=\"Lihat Markah\" border=0></center></td>\n";
				//}
				
				//if($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1"){
				echo "  <td><a href=hapus_tov.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$_SESSION['tahun']."|".$kodsek."><center><img src = images/drop.png width=12 height=12 Alt=\"Hapus Data TOV/ETR\" border=0></center></a></td>\n";
				//} else {
				//echo "  <td><center><img src = images/drop.png width=12 height=12 Alt=\"Hapus Data TOV/ETR\" border=0></center></td>\n";
				//}
				
				//if($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1"){
				echo " <td><a href=analisis_hc_mp.php?datahc=".$kodsek."/".$_SESSION['tahun']."/".$ting."/".$kodmp."/".urlencode($kelas)."><center><img src = images/laporan.png width=12 height=12 Alt=\"ANALISIS HC MP\" border=0></center></a></td>\n";
				//} else {
				//echo " <td><center><img src = images/laporan.png width=12 height=12 Alt=\"ANALISIS HC MP\" border=0></center></td>\n";
				//}
				echo "  </tr>\n";
				//echo "</form>";
			}// tamat while
}	// tamat if($num == 0)
echo "</table>\n";
echo "<br><center>Sekiranya ETR dan TOV melebihi bilangan pelajar, sila gunakan kemudahan <b>HAPUS TOV/ETR YANG BERMASALAH.<b></center>";
echo "</div>"; 
echo "</td>"; 
echo"</table>";
echo "</th>\n";

?>
</td>
<?php include 'kaki.php';?> 