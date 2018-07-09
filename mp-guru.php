<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
//$_SESSION['tahun'];
$kodsek = $_GET["kodsek"];
$nama = $_POST['nama'];
$tahun = $_GET['tahun'];
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Key-In Markah Guru</p>

<?php



echo "<br><br><br><br>";
echo " <center><h3>SEMAKAN 'KEY-IN' MARKAH ".pep($_SESSION['jpep'])."</h3></center>";
echo " <center><b>SILA PILIH GURU</b></center>";
echo "<br><br>";
echo "<form method=\"post\" action=\"semak-mp-guru.php?kodsek=$kodsek\">";
echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "  <td>NAMA GURU</td>\n";
//echo "  <td>KELAS</td>\n";
echo "  <td>HANTAR</td>\n";
echo " </tr>";
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "  <td>\n";

$ting=$_GET['ting'];
$kelas=$_GET['kelas'];
$kodsek=$_GET['kodsek'];
	
		//$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY ting";
		$SQL_tkelas = "SELECT nokp,nama,jan FROM login WHERE kodsek='$kodsek' ORDER BY nama";
		//echo $SQL_tkelas;
		$stmt = OCIParse($conn_sispa,$SQL_tkelas);
		OCIExecute($stmt);
		$num = count_row($SQL_tkelas);
		
		//$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND ting='$ting' ORDER BY kelas");
		$kelas_sql = OCIParse($conn_sispa,"SELECT nokp,nama,jan FROM login WHERE kodsek='$kodsek' ORDER BY nama");
		OCIExecute($kelas_sql);
		echo "<select name='ting'><option value=''>Pilih Nama Guru</option>";// onchange=\"reload(this.form)\"
		while(OCIFetch($stmt)) {
			$nokp = OCIResult($stmt,"NOKP");
			$nama = OCIResult($stmt,"NAMA");
			echo  "<option value='$nokp'>$nama [$nokp]</option>";
		}
		echo "</select>";
		echo "</td>";
/*		echo "<td>";
		
		echo "<select name='kelas' ><option value=''>Pilih Kelas</option>";
		while(OCIFetch($kelas_sql)) { 
			if(OCIResult($kelas_sql,"KELAS")==@$kelas){echo "<option selected value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>"."<BR>";}
			else{echo  "<option value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>";}
		}

		echo "</td>";*/
echo "<td>";
echo "<input type='submit' name=\"pep\" value=\"Hantar\">";
echo "</td></tr></table>";
echo "</form>";
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
echo "<br><br><center><a href=cari-sekolah.php><< Kembali</a></center>";
?>
</td>
<?php include 'kaki.php';?> 