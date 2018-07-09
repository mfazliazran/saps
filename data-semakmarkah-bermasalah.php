<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Markah Bermasalah</p>

<?php
//echo"post=".$_POST['post']."";
if ($_POST['post']==1){
$ting = $_POST['ting'];
$kelas = oci_escape_string($_POST['kelas']);
} else {

$m=$_GET['data'];
list ($ting,$kelas)=split('[|]', $m);
}

//$gting = strtoupper($ting);
//echo "<center><h3>SENARAI SEMAK KEMASUKAN DATA ".pep($_SESSION['jpep'])."<br>MENGIKUT KELAS <br>".ting($ting)." $kelas TAHUN ".$_SESSION['tahun']."</h3></center>";
//echo "<a href=cetak-mp-ting.php?data=".$ting." target=_blank><center><img src = images/printer.png width=15 height=15 border=\"0\"><br>CETAK</center></a>";
echo "<form action=\"ctk_data-semakmp-bermasalah.php\" method=\"POST\" target=_blank>";

$kodsek=$_SESSION['kodsek'];

echo"<h3>Tingkatan/Darjah = ".ting($ting)." | Kelas = $kelas | Tahun = ".$_SESSION['tahun']." | Peperiksaan = ".pep($_SESSION['jpep'])." </h3>";

//untuk sek men
if ($_SESSION['statussek']=="SM"){
echo"<h3><font color=\"#FF0000\"><center>Utiliti ini adalah untuk menghapuskan data markah murid yang bukan di dalam kelas $kelas.</center></font></h3>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td width=\"50%\" valign=\"top\">";
echo "<center><h5>SENARAI DATA MURID</h5></center>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td><center><b>BIL</center></b></td>";
echo"<td><center><b>NOKP/ NO. SIJIL LAHIR</center></b></td>";
echo"<td><center><b>NAMA</center></b></td>";
echo"<td><center><b>TINDAKAN</center></b></td>";
echo"</tr>";
$querytmurid = "SELECT nokp,namap FROM tmurid WHERE kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='".str_replace(' ','_',oci_escape_string($kelas))."' AND tahun$ting='".$_SESSION['tahun']."' ORDER BY namap"; 
	//if($kodsek='TEA5035')
		//echo $query;
	//echo $query;
	$resulttmurid = oci_parse($conn_sispa,$querytmurid);
	oci_execute($resulttmurid);
	$biltmurid=0;
	while ($rowtmurid = oci_fetch_array($resulttmurid))
	{
		$nokptmurid = $rowtmurid['NOKP'];
		$namatmurid = $rowtmurid['NAMAP'];
		$biltmurid=$biltmurid+1;
		
		$bil=$biltmurid+1;
			if($bil&1) {
				$bcol = "#CDCDCD";
			} else {
				$bcol = "";
			}	
echo"<tr bgcolor='$bcol'>";
echo"<td><center>$biltmurid</center></td>";
echo"<td>$nokptmurid</td>";
echo"<td>$namatmurid</td>";
echo"<td><center>&nbsp;</center></td>";
echo"</tr>";
	}
echo"</table>";
echo"</td>";
echo"<td width=\"50%\" valign=\"top\">";
echo "<center><h5>SENARAI DATA MARKAH BERMASALAH</h5></center>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td><center><b>BIL</center></b></td>";
echo"<td><center><b>NOKP/ NO. SIJIL LAHIR</center></b></td>";
echo"<td><center><b>NAMA</center></b></td>";
echo"<td><center><b>TINDAKAN</center></b></td>";
echo"</tr>";
$querytmarkah = "SELECT nokp,nama FROM markah_pelajar WHERE kodsek='$kodsek' AND ting='$ting' AND kelas='".str_replace(' ','_',oci_escape_string($kelas))."' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' and nokp not in (select nokp from tmurid where kodsek$ting='".$_SESSION['kodsek']."' and tahun$ting='".$_SESSION['tahun']."' and $ting='$ting' and kelas$ting='".str_replace(' ','_',oci_escape_string($kelas))."' and kodsek_semasa='".$_SESSION['kodsek']."') ORDER BY nama"; 

	//echo "$querytmarkah<br>";
	$resulttmarkah = oci_parse($conn_sispa,$querytmarkah);
	oci_execute($resulttmarkah);
	$biltmarkah=0;
	while ($rowtmarkah = oci_fetch_array($resulttmarkah))
	{
		$nokptmarkah = $rowtmarkah['NOKP'];
		$namatmarkah = $rowtmarkah['NAMA'];
		$biltmarkah=$biltmarkah+1;

		$bil=$biltmarkah+1;
			if($bil&1) {
				$bcol = "#CDCDCD";
			} else {
				$bcol = "";
			}	
echo"<tr bgcolor='$bcol'>";
echo"<td><center>$biltmarkah</center></td>";
echo"<td>$nokptmarkah</td>";
echo"<td>$namatmarkah</td>";
echo"<td><a href=hapus-markah-bermasalah.php?data=".$nokptmarkah."|".$ting."|".str_replace(' ','_',oci_escape_string($kelas))." onclick=\"return (confirm('Adakah anda pasti hapuskan data markah murid ".$nokptmarkah." kelas ".$kelas." ?'))\"><center><img src = images/drop.png width=11 height=13 Alt=\"Hapus Headcount\" border=0></center></td>";
echo"</tr>";
	}
echo"</table>";
echo"</td>";
echo"</tr>";
echo"</table>";
////data kelas lama///////
echo"<br>";
echo"<br>";
echo"<h3><font color=\"#FF0000\"><center>Utiliti ini adalah untuk menghapuskan data kelas lama murid yang menyebabkan data murid menjadi double di lembaran markah.</center></font></h3>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td width=\"50%\" valign=\"top\">";
echo "<center><h5>SENARAI DATA MARKAH KELAS BARU</h5></center>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td><center><b>BIL</center></b></td>";
echo"<td><center><b>NOKP/ NO. SIJIL LAHIR</center></b></td>";
echo"<td><center><b>NAMA</center></b></td>";
echo"<td><center><b>TINDAKAN</center></b></td>";
echo"</tr>";
$querytmurid = "SELECT nokp,nama FROM markah_pelajar WHERE kodsek='$kodsek' AND ting='$ting' AND kelas='".str_replace(' ','_',oci_escape_string($kelas))."' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' ORDER BY nama"; 
	//if($kodsek='BBA3031')
		//echo $querytmurid;
	//echo $query;
	$resulttmurid = oci_parse($conn_sispa,$querytmurid);
	oci_execute($resulttmurid);
	$biltmurid=0;
	while ($rowtmurid = oci_fetch_array($resulttmurid))
	{
		$nokptmurid = $rowtmurid['NOKP'];
		$namatmurid = $rowtmurid['NAMA'];
		$biltmurid=$biltmurid+1;
		
		$bil=$biltmurid+1;
			if($bil&1) {
				$bcol = "#CDCDCD";
			} else {
				$bcol = "";
			}	
echo"<tr bgcolor='$bcol'>";
echo"<td><center>$biltmurid</center></td>";
echo"<td>$nokptmurid</td>";
echo"<td>$namatmurid</td>";
echo"<td><center>$kelastmurid</center></td>";
echo"</tr>";
	}
echo"</table>";
echo"</td>";
echo"<td width=\"50%\" valign=\"top\">";
echo "<center><h5>SENARAI DATA MARKAH KELAS LAMA</h5></center>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td><center><b>BIL</center></b></td>";
echo"<td><center><b>NOKP/ NO. SIJIL LAHIR</center></b></td>";
echo"<td><center><b>NAMA</center></b></td>";
echo"<td><center><b>NAMA KELAS</center></b></td>";
echo"<td><center><b>TINDAKAN</center></b></td>";
echo"</tr>";
$querytmarkah = "SELECT nokp,nama,kelas FROM markah_pelajar WHERE kodsek='$kodsek' AND ting='$ting' AND kelas not in (SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND ting='$ting' ) AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' and nokp in (select nokp from tmurid where kodsek$ting='".$_SESSION['kodsek']."' and tahun$ting='".$_SESSION['tahun']."' and $ting='$ting' and kelas$ting='".str_replace(' ','_',oci_escape_string($kelas))."' and kodsek_semasa='".$_SESSION['kodsek']."') ORDER BY nama"; 

	//echo "$querytmarkah<br>";
	$resulttmarkah = oci_parse($conn_sispa,$querytmarkah);
	oci_execute($resulttmarkah);
	$biltmarkah=0;
	while ($rowtmarkah = oci_fetch_array($resulttmarkah))
	{
		$nokptmarkah = $rowtmarkah['NOKP'];
		$namatmarkah = $rowtmarkah['NAMA'];
		$kelastmarkah = $rowtmarkah['KELAS'];
		$biltmarkah=$biltmarkah+1;

		$bil=$biltmarkah+1;
			if($bil&1) {
				$bcol = "#CDCDCD";
			} else {
				$bcol = "";
			}	
echo"<tr bgcolor='$bcol'>";
echo"<td><center>$biltmarkah</center></td>";
echo"<td>$nokptmarkah</td>";
echo"<td>$namatmarkah</td>";
echo"<td>$kelastmarkah</td>";
echo"<td><a href=hapus-markah-bermasalah.php?data=".$nokptmarkah."|".$ting."|".str_replace(' ','_',oci_escape_string($kelas))."|".str_replace(' ','_',oci_escape_string($kelastmarkah))." onclick=\"return (confirm('Adakah anda pasti hapuskan data markah murid ".$nokptmarkah." kelas ".$kelastmarkah." ?'))\"><center><img src = images/drop.png width=11 height=13 Alt=\"Hapus Headcount\" border=0></center></td>";
echo"</tr>";
	}
echo"</table>";
echo"</td>";
echo"</tr>";
echo"</table>";
}

if ($_SESSION['statussek']=="SR"){
//echo"masuk";
echo"<h3><font color=\"#FF0000\"><center>Utiliti ini adalah untuk menghapuskan data markah murid yang bukan di dalam kelas $kelas.</center></font></h3>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td width=\"50%\" valign=\"top\">";
echo "<center><h5>SENARAI DATA MURID</h5></center>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td><center><b>BIL</center></b></td>";
echo"<td><center><b>NOKP/ NO. SIJIL LAHIR</center></b></td>";
echo"<td><center><b>NAMA</center></b></td>";
echo"<td><center><b>TINDAKAN</center></b></td>";
echo"</tr>";
$querytmurid = "SELECT nokp,namap FROM tmuridsr WHERE kodsek$ting='$kodsek' AND $ting='$ting' AND kelas$ting='".str_replace(' ','_',oci_escape_string($kelas))."' AND tahun$ting='".$_SESSION['tahun']."' ORDER BY namap"; 
	//if($kodsek='TEA5035')
		//echo $query;
	//echo $query;
	$resulttmurid = oci_parse($conn_sispa,$querytmurid);
	oci_execute($resulttmurid);
	$biltmurid=0;
	while ($rowtmurid = oci_fetch_array($resulttmurid))
	{
		$nokptmurid = $rowtmurid['NOKP'];
		$namatmurid = $rowtmurid['NAMAP'];
		$biltmurid=$biltmurid+1;
		
		$bil=$biltmurid+1;
			if($bil&1) {
				$bcol = "#CDCDCD";
			} else {
				$bcol = "";
			}	
echo"<tr bgcolor='$bcol'>";
echo"<td><center>$biltmurid</center></td>";
echo"<td>$nokptmurid</td>";
echo"<td>$namatmurid</td>";
echo"<td><center>&nbsp;</center></td>";
echo"</tr>";
	}
echo"</table>";
echo"</td>";
echo"<td width=\"50%\" valign=\"top\">";
echo "<center><h5>SENARAI DATA MARKAH BERMASALAH</h5></center>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td><center><b>BIL</center></b></td>";
echo"<td><center><b>NOKP/ NO. SIJIL LAHIR</center></b></td>";
echo"<td><center><b>NAMA</center></b></td>";
echo"<td><center><b>TINDAKAN</center></b></td>";
echo"</tr>";
$querytmarkah = "SELECT nokp,nama FROM markah_pelajarsr WHERE kodsek='$kodsek' AND darjah='$ting' AND kelas='".str_replace(' ','_',oci_escape_string($kelas))."' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' and nokp not in (select nokp from tmuridsr where kodsek$ting='".$_SESSION['kodsek']."' and tahun$ting='".$_SESSION['tahun']."' and $ting='$ting' and kelas$ting='".str_replace(' ','_',oci_escape_string($kelas))."' and kodsek_semasa='".$_SESSION['kodsek']."') ORDER BY nama"; 

	//echo "$querytmarkah<br>";
	$resulttmarkah = oci_parse($conn_sispa,$querytmarkah);
	oci_execute($resulttmarkah);
	$biltmarkah=0;
	while ($rowtmarkah = oci_fetch_array($resulttmarkah))
	{
		$nokptmarkah = $rowtmarkah['NOKP'];
		$namatmarkah = $rowtmarkah['NAMA'];
		$biltmarkah=$biltmarkah+1;

		$bil=$biltmarkah+1;
			if($bil&1) {
				$bcol = "#CDCDCD";
			} else {
				$bcol = "";
			}	
echo"<tr bgcolor='$bcol'>";
echo"<td><center>$biltmarkah</center></td>";
echo"<td>$nokptmarkah</td>";
echo"<td>$namatmarkah</td>";
echo"<td><a href=hapus-markah-bermasalah.php?data=".$nokptmarkah."|".$ting."|".str_replace(' ','_',oci_escape_string($kelas))." onclick=\"return (confirm('Adakah anda pasti hapuskan data markah murid ".$nokptmarkah." kelas ".$kelas." ?'))\"><center><img src = images/drop.png width=11 height=13 Alt=\"Hapus Headcount\" border=0></center></td>";
echo"</tr>";
	}
echo"</table>";
echo"</td>";
echo"</tr>";
echo"</table>";
//////data kelas lama/////
echo"<br>";
echo"<br>";
echo"<h3><font color=\"#FF0000\"><center>Utiliti ini adalah untuk menghapuskan data kelas lama murid yang menyebabkan data murid menjadi double di lembaran markah .</center></font></h3>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td width=\"50%\" valign=\"top\">";
echo "<center><h5>SENARAI DATA MARKAH KELAS BARU</h5></center>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td><center><b>BIL</center></b></td>";
echo"<td><center><b>NOKP/ NO. SIJIL LAHIR</center></b></td>";
echo"<td><center><b>NAMA</center></b></td>";
echo"<td><center><b>TINDAKAN</center></b></td>";
echo"</tr>";
$querytmurid = "SELECT nokp,nama FROM markah_pelajarsr WHERE kodsek='$kodsek' AND darjah='$ting' AND kelas='".str_replace(' ','_',oci_escape_string($kelas))."' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' ORDER BY nama"; 
	//if($kodsek='WEB0209')
		//echo $querytmurid;
	//echo $query;
	$resulttmurid = oci_parse($conn_sispa,$querytmurid);
	oci_execute($resulttmurid);
	$biltmurid=0;
	while ($rowtmurid = oci_fetch_array($resulttmurid))
	{
		$nokptmurid = $rowtmurid['NOKP'];
		$namatmurid = $rowtmurid['NAMA'];
		$biltmurid=$biltmurid+1;
		
		$bil=$biltmurid+1;
			if($bil&1) {
				$bcol = "#CDCDCD";
			} else {
				$bcol = "";
			}	
echo"<tr bgcolor='$bcol'>";
echo"<td><center>$biltmurid</center></td>";
echo"<td>$nokptmurid</td>";
echo"<td>$namatmurid</td>";
echo"<td><center>$kelastmurid</center></td>";
echo"</tr>";
	}
echo"</table>";
echo"</td>";
echo"<td width=\"50%\" valign=\"top\">";
echo "<center><h5>SENARAI DATA MARKAH KELAS LAMA</h5></center>";
echo"<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo"<tr>";
echo"<td><center><b>BIL</center></b></td>";
echo"<td><center><b>NOKP/ NO. SIJIL LAHIR</center></b></td>";
echo"<td><center><b>NAMA</center></b></td>";
echo"<td><center><b>NAMA KELAS</center></b></td>";
echo"<td><center><b>TINDAKAN</center></b></td>";
echo"</tr>";
$querytmarkah = "SELECT nokp,nama,kelas FROM markah_pelajarsr WHERE kodsek='$kodsek' AND darjah='$ting' AND kelas not in (SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND ting='$ting' ) AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' and nokp in (select nokp from tmuridsr where kodsek$ting='".$_SESSION['kodsek']."' and tahun$ting='".$_SESSION['tahun']."' and $ting='$ting' and kelas$ting='".str_replace(' ','_',oci_escape_string($kelas))."' and kodsek_semasa='".$_SESSION['kodsek']."') ORDER BY nama"; 

	//echo "$querytmarkah<br>";
	$resulttmarkah = oci_parse($conn_sispa,$querytmarkah);
	oci_execute($resulttmarkah);
	$biltmarkah=0;
	while ($rowtmarkah = oci_fetch_array($resulttmarkah))
	{
		$nokptmarkah = $rowtmarkah['NOKP'];
		$namatmarkah = $rowtmarkah['NAMA'];
		$kelastmarkah = $rowtmarkah['KELAS'];
		$biltmarkah=$biltmarkah+1;

		$bil=$biltmarkah+1;
			if($bil&1) {
				$bcol = "#CDCDCD";
			} else {
				$bcol = "";
			}	
echo"<tr bgcolor='$bcol'>";
echo"<td><center>$biltmarkah</center></td>";
echo"<td>$nokptmarkah</td>";
echo"<td>$namatmarkah</td>";
echo"<td>$kelastmarkah</td>";
echo"<td><a href=hapus-markah-bermasalah.php?data=".$nokptmarkah."|".$ting."|".str_replace(' ','_',oci_escape_string($kelastmarkah))." onclick=\"return (confirm('Adakah anda pasti hapuskan data markah murid ".$nokptmarkah." kelas ".$kelastmarkah." ?'))\"><center><img src = images/drop.png width=11 height=13 Alt=\"Hapus Headcount\" border=0></center></td>";
echo"</tr>";
	}
echo"</table>";
echo"</td>";
echo"</tr>";
echo"</table>";
}
echo"<br>";
echo"<center><b>SILA PROSES SEMULA MARKAH JIKA ANDA TELAH MENGHAPUSKAN DATA MARKAH MURID<b></center> ";
echo"<br><br><center><a href=semakmarkah-bermasalah.php?ting=".$ting."><< Kembali</a></center>";


function ting($ting){
	switch ($ting){
		case "T1":
		$tkt="TINGKATAN SATU";
		break;
		case "T2":
		$tkt="TINGKATAN DUA";
		break;
		case "T3":
		$tkt="TINGKATAN TIGA";
		break;
		case "T4":
		$tkt="TINGKATAN EMPAT";
		break;
		case "T5":
		$tkt="TINGKATAN LIMA";
		break;
		case "P":
		$tkt="PERALIHAN";
		break;
		case "D1":
		$npep="TAHUN SATU";
		break;
		case "D2":
		$tkt="TAHUN DUA";
		break;
		case "D3":
		$tkt="TAHUN TIGA";
		break;
		case "D4":
		$tkt="TAHUN EMPAT";
		break;
		case "D5":
		$tkt="TAHUN LIMA";
		break;
		case "D6":
		$tkt="TAHUN ENAM";
		break;
	}
return $tkt;
}

function pep($kodpep){
	switch ($kodpep){
		case "U1":
		$npep="UJIAN 1";
		break;
		case "U2":
		$npep="UJIAN 2";
		break;
		case "PAT":
		$npep="PEPERIKSAAN AKHIR TAHUN";
		break;
		case "PPT":
		$npep="PEPERIKSAAN PERTENGAHAN TAHUN";
		break;
		case "PMRC":
		$npep="PEPERIKSAAN PERCUBAAN PMR";
		break;
		case "SPMC":
		$npep="PEPERIKSAAN PERCUBAAN SPM";
		break;
	}
return $npep;
}

function subjek_teras($kodmp,$jenissekolah,$status,$ting)
{
	if($ting=='T4' or $ting=='T5'){
		$sql = "SELECT * FROM sub_ma where kod='$kodmp' ORDER BY mp";
	}else{
		if($status=="SM"){
			$sql = "SELECT * FROM sub_mr where kod='$kodmp' ORDER BY mp";	
		}else{
			$sql = "SELECT * FROM sub_sr where kod='$kodmp' ORDER BY mp";	
		}
	}
	//$num=count_row("SELECT * FROM subjekteras where jenissekolah='".$jenissekolah."' and kodmp='$kodmp' ");
	$num=count_row($sql);
// echo "SELECT * FROM subjekteras where jenis='".$jenissekolah."' and kodmp='$kodmp' <br>";
 return($num);
}

?>
</td>
<?php include 'kaki.php';?> 