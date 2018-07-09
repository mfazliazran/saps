<?php 
require_once ('auth.php');
include 'config.php';
$ting = $_GET['data'];
$gting = strtolower($ting);	
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">";
echo "<center><h3>SENARAI SEMAK KEMASUKAN MARKAH $ting<br>".pep($_SESSION['jpep'])."</h3></center>";
echo "<br>";
echo "<table width=\"98%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <th scope=\"col\"><center>BIL</center></th>\n";
echo "    <th scope=\"col\">KELAS</th>\n";
echo "    <th scope=\"col\">MATA PELAJARAN</th>\n";
echo "    <th scope=\"col\">NAMA GURU</th>\n";
echo "    <th scope=\"col\">BIL<br>MARKAH</th>\n";
echo "    <th scope=\"col\">STATUS<br>MARKAH</th>\n";
echo "  </tr>\n";

$kodsek=$_SESSION['kodsek'];
$query = "SELECT * FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting'"; 
$result = oci_parse($conn_sispa,$query);
oci_execute($result);
$bil=0;
while ($row = oci_fetch_array($result))
{
	$bil=$bil+1;
	$ting = $row['TING'];
	$kelas = $row['KELAS'];
	$querymp = "SELECT * FROM sub_guru WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kelas ASC"; 
	$resultmp = oci_parse($conn_sispa,$querymp);
	$num = count_row("SELECT * FROM sub_guru WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kelas ASC");
	echo "  <tr>\n";
	echo "		<td rowspan =\"$num\"><center>$bil</center></td>\n";
	if ($_SESSION['statussek']=="SM"){
		$qb_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas'");
		oci_execute($qb_murid);
		$bilm = count_row("SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas'");
	}

	if ($_SESSION['statussek']=="SR"){
		$qb_murid = oci_parse($conn_sispa,"SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas'");
		$bilm = count_row("SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas'");
	}
	echo "		<td rowspan =\"$num\"><center>$ting&nbsp;&nbsp;&nbsp; $kelas <br>[$bilm Murid]</center></td>";
	if($num != 0){
		$bilmp=0; $bilrow=0;
		while ($row2 = oci_fetch_array($resultmp))
		{
			$kodmp = $row2['KODMP'];
			if ($_SESSION['statussek']=="SM"){
				$qrymp = "SELECT * FROM mpsmkc WHERE kod='$kodmp'";
				$markah = "markah_pelajar";
				$tahap = "ting";
			}
			if ($_SESSION['statussek']=="SR"){
				$qrymp = "SELECT * FROM mpsr WHERE kod='$kodmp'"; 
				$markah = "markah_pelajarsr";
				$tahap = "darjah";
			}
			$r_mp = oci_parse($conn_sispa,$qrymp);
			oci_execute($r_mp);
			$tukarmp = oci_fetch_array($r_mp );
			$bilmp=$bilmp+1;
			echo  "<td>".$tukarmp['MP']." </td><td>".$row2['NAMA']."</td>";
			$q_mark = oci_parse($conn_sispa,"SELECT * FROM $markah WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND $tahap='$ting' AND kelas='$kelas' AND $kodmp !=''");
			oci_execute($q_mark);
			$bil_mark = count_row("SELECT * FROM $markah WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND $tahap='$ting' AND kelas='$kelas' AND $kodmp !=''");
			if($bil_mark == 0){
				echo  "<td><center>$bil_mark</center></td><td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></td>";
			} else {
					echo  "<td><center>$bil_mark</center></td><td><center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center></td>";
					}
			if ($num<>$bilrow)
				echo  "<tr></th></th>\n";
			$bilrow=$bilrow+1;
		}
	} else {
			echo  "<td>&nbsp<td>&nbsp<br>";
			echo  "<td><center>0</center></td><td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></td>";
			}
	echo  "</tr>";
}
echo "</table>";
////////////////////////
echo "<br><br><br>";
$querynama = "SELECT * FROM login WHERE kodsek='$kodsek' AND (level='P' OR level='3' OR level='4')";
$resultnama = oci_parse($conn_sispa,$querynama);
oci_execute($resultnama);

echo "<table width=\"98%\" border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "<tr>\n";
	while ($row = oci_fetch_array($resultnama)){
	$nama_p = $row['NAMA'];
	$namasek = $row['NAMASEK'];
	$level = $row['LEVEL'];
	
	if($level=="P"){
	echo "<td>Disahkan Oleh:<br><br><br><br><br>$nama_p<br>Pengetua<br>$namasek</td>";
	}
	if(($level=="3")OR($level=="4")){
	echo "<td width=60%>Disediakan Oleh:<br><br><br><br><br>$nama_p<br>Setiausaha Peperiksaan<br>$namasek</td>";
	}
}
echo "</tr>\n";
echo "</table>";
////////////////////////

echo "<br><br>";
echo "</th>\n";
echo "</tr>\n";
echo "</table></body>\n";

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
?>