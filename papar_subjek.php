<?php 
//CA16111204
//session_start();
if(!isset($_SESSION)){session_start();}

//CA16111403
if(!isset($nummark)){$nummark="";}
if(!isset($bil_ambil)){$bil_ambil="";}
if(!isset($kodsekbuka)){$kodsekbuka="";}
//CA16111403

include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';

$tarikhsekarang = date("Y-m-d");
$sqlbukasekolah = "select * from bukasekolah where tarikh_tutup >= '$tarikhsekarang'";
$resque=oci_parse($conn_sispa,$sqlbukasekolah);
oci_execute($resque);
while($rowan = oci_fetch_array($resque)){
	if ($rowan["KODSEK"] == $_SESSION["kodsek"]){
	$kodsekbuka = "1";
	}
}

?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>

<td valign="top" class="rightColumn">
<p class="subHeader">Markah Peperiksaan <font color="#FFFFFF">(Tarikh Kemaskini Program : 4/8/2011 11:42AM)</font></p>

<?php
$tahun = $_SESSION['tahun'] ;
$jpep = $_SESSION['jpep'];
$kodsek = $_SESSION['kodsek'];
$nokp = $_SESSION['nokp'];
$statussek = $_SESSION['statussek'];

//echo "$nokp ";
	switch ($statussek)
	{
		case "SM" : $tmarkah = "markah_pelajar"; $tahap = "ting";  $tmurid = "tmurid";
		break;
		case "SR" : $tmarkah = "markah_pelajarsr"; $tahap = "darjah"; $tmurid = "tmuridsr";
		break;
    }
	
if($jpep=='LNS01') {
echo "<center><h3>HANYA UNTUK PEPERIKSAAN AM SAHAJA<br><font color =\"#ff0000\">".jpep($jpep)."</font><br>TIDAK DIBENARKAN</h3></center>";

} else {	
//echo "<br><br>";
echo "<center><h3>MASUK MARKAH PEPERIKSAAN TAHUN $tahun<br><font color =\"#ff0000\">".jpep($jpep)."</font></h3></center>";
echo "<table align=\"center\" width=\"800\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
echo "  <tr bgcolor=\"FFCC33\">\n";
echo "    <th rowspan=\"2\" scope=\"col\">KELAS</th>\n";
echo "    <th rowspan=\"2\" scope=\"col\">MASUK MARKAH</th>\n";
echo "    <th colspan=\"3\" scope=\"col\">STATUS MARKAH</th>\n";
echo "    <th rowspan=\"2\" scope=\"col\">PAPAR<br>MARKAH</th>\n";
echo "    <th rowspan=\"2\" scope=\"col\">EDIT<br>MARKAH</th>\n";
echo "    <th rowspan=\"2\" scope=\"col\">EXPORT<br>MARKAH</th>\n";

if($nummark > $bil_ambil)
	echo "    <th rowspan=\"2\" scope=\"col\">&nbsp;&nbsp;</th>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"FFCC33\">\n";
echo "  <td><center>Bil Pel</center></td>\n";
echo "  <td><center>Bil M</center></td>\n";
echo "  <td><center>Status</center></td>\n";
echo "  </tr>\n";
		
switch ($jpep)
{
	case "UPSRC" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='$year' AND ting='D6' ORDER BY ting";
		break;

	case "PAT" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='$year' AND ting!='T3' AND ting!='T5' ORDER BY ting";
		break;
		
	case "SPMC" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='$year' AND ting='T5' ORDER BY kodmp";
		break;
		
	case  "PMRC" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='$year' AND ting='T3' ORDER BY kodmp";
		break;
		
	default :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='$year' ORDER BY ting,kodmp";
		break;
}
//echo $querysub;
$num = count_row($querysub);
if($num == 0)
{
	echo "  <tr>\n";
	echo "  <td colspan=\"8\"><center><font color =\"#ff0000\"><h3><br>Mata pelajaran Belum Didaftarkan <br>Sila Daftar Mata Pelajaran</h3></font></center></td>\n";
	echo "  </tr>\n";
}
else{
		//echo $querysub;
		$stmt = oci_parse($conn_sispa,$querysub);
		oci_execute($stmt);
		while ($data=oci_fetch_array($stmt))
		{
			$ting = trim($data["TING"]);//$row['ting'];
			if($kodsek=='DFT1001' and ($ting=='T2' or $ting=='T3')){
				$wujud = 1;	
			}
			$kelas = $data["KELAS"];//$row['kelas'];
			$kodmp = $data["KODMP"];//$row['kodmp'];
			$bil_ambil = $data["BILAMMP"];//$row['bilammp'];
			
			//include 'config.php';
			//echo $kodmp;

			$qbilmark = "SELECT * FROM $tmarkah WHERE kodsek='$kodsek' and $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND tahun='$year' AND $kodmp is not null and nokp in (select nokp from $tmurid where kodsek$ting='$kodsek' and tahun$ting='$year' and $ting='$ting' and kelas$ting='$kelas' and kodsek_semasa='$kodsek')";//naim tambah and nokp in 2/4/2012
			
			
			//echo "$qbilmark<br>";
			//$stmt2 = oci_parse($conn_sispa,$qbilmark);
			//oci_execute($stmt2);
			$nummark = count_row($qbilmark);

            $mp=subjek_desc($kodmp);
			
			echo "  <tr>\n";
/*ASAL			echo "  <td><a href=b_markah.php?data=".$ting."/".$kelas."/".$kodmp."/".$year."/".$kodsek."/".$jpep." title=\"Masuk Markah\">".$ting." ".$kelas."</a></td>\n";
			echo "  <td><a href=b_markah.php?data=".$ting."/".$kelas."/".$kodmp."/".$year."/".$kodsek."/".$jpep." title=\"Masuk Markah\">".$mp."</a></td>\n";*/
			echo "  <td>".$ting." ".$kelas."</td>\n";// or $_SESSION['kodsek']=="XBA4384"
			if(($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1")){
			echo "  <td><a href=b_markah.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$year."|".$kodsek."|".$jpep." title=\"Masuk Markah\">".$mp."</a></td>\n";
			} else if ($kodsekbuka == "1"){
			echo "  <td><a href=b_markah.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$year."|".$kodsek."|".$jpep." title=\"Masuk Markah\">".$mp."</a></td>\n";
			} else {
			echo "  <td>$mp</td>\n";
			}
			
			echo "  <td><center>&nbsp;$bil_ambil</center></td>\n";
			echo "  <td><center>$nummark</center></td>\n";
			if($_SESSION['status_buka_tutup']=="1"){
			if($bil_ambil == $nummark){ $smark = "<center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center>";}
			if($bil_ambil != $nummark){ $smark = "<center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center>";}
			} else {$smark="<center>Tutup</center>";}
			echo "  <td>$smark</td>\n";
			//if($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup'] =="1"){
			echo "  <td><a href=tengok_markah.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$year."|".$kodsek."|".$jpep."><center><img src = images/preview-icon.gif width=12 height=12 Alt=\"Lihat Markah\" border=0></center></a></td>\n";
			//} 
			
			if(($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1")){
			echo "  <td><a href=b_markah.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$year."|".$kodsek."|".$jpep."><center><img src = images/edit.png width=12 height=13 Alt=\"Edit Markah\" border=0></center></a></td>\n";
			} else if ($kodsekbuka == "1"){
			echo "  <td><a href=b_markah.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$year."|".$kodsek."|".$jpep."><center><img src = images/edit.png width=12 height=13 Alt=\"Edit Markah\" border=0></center></a></td>\n";
			} else {
			echo "  <td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></a></td>\n";
			
			}
			echo "  <td><center><input type=\"button\" name=\"export\" value=\"EXPORT KE EXCEL\" onclick=\"open_window('data-markah-pelajar-excel.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$year."|".$kodsek."|".$jpep."','win1');\" /></center></td>\n";
			
			if($nummark > $bil_ambil)
				echo "  <td><a href=semak_markah_tiada_murid.php?data=".urlencode($kelas)."|".$ting."|".$kodmp."|".$year."|".$kodsek."|".$jpep." onclick=\"return (confirm('Adakah anda pasti Kelas ".$ting." ".$kelas." mata pelajaran ".$mp." mempunyai bilangan markah lebih dari bilangan murid ?'))\"><center><img src = images/editp.png width=20 height=20 Alt=\"Edit Pelaja\" border=0></center></a></td>\n";
				//echo "  <td><input type=\"button\" name=\"export\" value=\"EXPORT KE EXCEL\" onclick=\"open_window('data-markah-pelajar-excel.php?data=".$ting."|".urlencode($kelas)."|".$kodmp."|".$year."|".$kodsek."|".$jpep."','win1');\" /></td>\n";
			//else
			//	echo "<td>&nbsp;</td>\n";
			echo "  </tr>\n";
		}
	}
	
echo "</table>\n";
if($nummark > $bil_ambil) {
	echo "<center><br><br>|<img src = images/editp.png width=20 height=20 Alt=\"Edit Pelajar\" border=0>|  Klik ikon tersebut untuk menghapuskan markah pelajar yang bermasalah.";
}

//if($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1"){
if(($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1")){
echo "<center><br><br><a href=b_edit_daftar_bil_mp.php>Klik disini untuk edit bilangan murid setiap subjek</a> | Bil pel = bilangan murid | Bil M = bilangan markah murid</center>\n";
} else if ($kodsekbuka == "1"){
echo "<center><br><br><a href=b_edit_daftar_bil_mp.php>Klik disini untuk edit bilangan murid setiap subjek</a> | Bil pel = bilangan murid | Bil M = bilangan markah murid</center>\n";
} else {
echo "<center><br><br>Klik disini untuk edit bilangan murid setiap subjek | Bil pel = bilangan murid | Bil M = bilangan markah murid</center>\n";

}
//if($kodsek=='DFT1001' and $wujud==1){
//echo "<center><br><br><b>Subjek yang di paparkan sekiranya tidak betul sila kemaskini di menu Kemaskini MP. Markah yang dimasukkan sebelum ini akan dipaparkan semula setelah subjek yang betul dikemaskini. (Tidak Hilang). Sebarang bantuan boleh hubungi di saps.crew@gmail.com</b></center>\n";	
//}
echo "</div>"; 
echo "</td>"; 
echo"</table>";
}
function subjek_desc($kodmp)
{
	global $conn_sispa;

	$statussek = $_SESSION['statussek'];
	switch ($statussek)
	{
		case "SM" : 
			$sqlkod="SELECT mp FROM mpsmkc where KOD='$kodmp'";
			$querykod = oci_parse($conn_sispa,$sqlkod); 
			oci_execute($querykod);
		break;
		case "SR" : 
			$sqlkod="SELECT mp FROM mpsr where KOD='$kodmp'";
			$querykod = oci_parse($conn_sispa,$sqlkod);
			oci_execute($querykod);
		break;
	}
	
	$data=oci_fetch_array($querykod);
	$subjek=$data["MP"];
return($subjek);	
}

?>
</td>
<?php include 'kaki.php';?> 
