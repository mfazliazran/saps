<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Key-In Markah</p>

<?php
$ting = $_POST['ting'];

if($ting==""){
	$ting = $_GET['ting'];
}

$kelas = oci_escape_string($_POST['kelas']);

if($ting==""){
	$ting = $_GET["ting"];
	$kodsek = $_GET["kodsek"];
	$status =  $_GET["status"];
	$_SESSION["kodsek"] = $kodsek;
	$jenis = $_GET["jenis"];
	$_SESSION['jpep']=$jenis;
	$tahun = $_GET["tahun"];
	$_SESSION['tahun']=$tahun;
	$_SESSION['statussek']=$status;		
}

$gting = strtoupper($ting);
echo "<center><h3>SENARAI SEMAK KEMASUKAN DATA ".pep($_SESSION['jpep'])."<br>MENGIKUT MATA PELAJARAN KELAS <br>".ting($ting)." TAHUN ".$_SESSION['tahun']."</h3></center>";
//echo "<a href=cetak-mp-ting.php?data=".$ting." target=_blank><center><img src = images/printer.png width=15 height=15 border=\"0\"><br>CETAK</center></a>";
echo "<form action=\"ctk_data-semakmp-ting.php\" method=\"POST\" target=_blank>";
echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";

echo "  <tr>\n";
echo "    <th scope=\"col\"><center>BIL</center></th>\n";
echo "    <th scope=\"col\">KELAS</th>\n";
echo "    <th scope=\"col\">MATA PELAJARAN</th>\n";
echo "    <th scope=\"col\">NAMA GURU</th>\n";
echo "    <th scope=\"col\">BIL<br>AMBIL</th>\n";
echo "    <th scope=\"col\">BIL<br>MARKAH</th>\n";
echo "    <th scope=\"col\">STATUS<br>MARKAH</th>\n";
echo "  </tr>\n";
$kodsek=$_SESSION['kodsek'];

$q_jenis = "SELECT KODJENISSEKOLAH,STATUS FROM tsekolah WHERE kodsek='$kodsek'"; //AND kelas='$kelas'";
$qry = oci_parse($conn_sispa,$q_jenis);
oci_execute($qry); 
$datajenis=oci_fetch_array($qry);
$jenissekolah=$datajenis["KODJENISSEKOLAH"];
$status=$datajenis["STATUS"];//SM/SR


$q_kelas = "SELECT * FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' "; //AND kelas='$kelas'";
//echo "$q_kelas<br>";
$qry = oci_parse($conn_sispa,$q_kelas);
oci_execute($qry); 
$bilkelas = count_row($q_kelas);
$bil=0; $biltkt = 0;$i=0; $bilk = 0;
$bilteras=0;
$mpokteras=0;
while ($row = oci_fetch_array($qry))
{
	$bil=$bil+1;
	$ting = $row["TING"];
	$kelas = oci_escape_string($row["KELAS"]);

	$querymp = "SELECT * FROM sub_guru WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kelas ASC"; 
	//if($kodsek=='BEB8655')
		//echo $querymp.'<br>';
	$resultmp = oci_parse($conn_sispa,$querymp);
	oci_execute($resultmp);
	$num = count_row($querymp);
	$rowspan=$num;

   if ($rowspan==0)
	  $rowspan=1;
	  
	echo "  <tr>\n";
	echo "	<td rowspan =\"$rowspan\"><center>$bil</center></td>\n";
	
	if ($_SESSION['statussek']=="SM"){
		$qb_murid = "SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek'";
		//echo 
		$stmt = oci_parse($conn_sispa,$qb_murid);
		oci_execute($stmt);
		$bilm = count_row($qb_murid);
	}

	if ($_SESSION['statussek']=="SR"){
		$qb_murid = "SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek'";
		$stmt = oci_parse($conn_sispa,$qb_murid);
		oci_execute($stmt);
		$bilm = count_row($qb_murid);
	}
	echo "		<td rowspan =\"$rowspan\"><center>$ting&nbsp;&nbsp;&nbsp; $kelas <br>[$bilm Murid]</center></td>";
	if($num != 0){
		$bilmp=0; $bilrow=0; $mpok = 0;
		while ($row2 = oci_fetch_array($resultmp))
		{
			$kodmp = $row2["KODMP"];
			if ($_SESSION['statussek']=="SM"){
				$qrymp = "SELECT * FROM mpsmkc WHERE kod='$kodmp'";
				$markah = "markah_pelajar";
				$tahap = "ting";
				$tmurid = "tmurid";
			}//tutup sm
			if ($_SESSION['statussek']=="SR"){
				$qrymp = "SELECT * FROM mpsr WHERE kod='$kodmp'"; 
				$markah = "markah_pelajarsr";
				$tahap = "darjah";
				$tmurid = "tmuridsr";
			}//tutup sr
			$r_mp = oci_parse($conn_sispa,$qrymp);
			oci_execute($r_mp);
			$tukarmp = oci_fetch_array($r_mp);
			$bilmp=$bilmp+1;
			echo  "<td>($kodmp)".$tukarmp["MP"]."&nbsp;</td><td>&nbsp;".$row2["NAMA"]."</td><td><center>".$row2["BILAMMP"]."</center></td>";
			$q_mark = "SELECT * FROM $markah WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND $tahap='$ting' AND kelas='$kelas' AND $kodmp is not null and nokp in (select nokp from $tmurid where kodsek$ting='$kodsek' and tahun$ting='".$_SESSION['tahun']."' and $ting='$ting' and kelas$ting='$kelas' and kodsek_semasa='$kodsek')"; //naim tambah and nokp in 2/4/2012
			//if($kodsek=='BEB8655')
				//echo "***$q_mark<br>";
			$qrt = oci_parse($conn_sispa,$q_mark);
			oci_execute($qrt);
			$bil_mark = count_row($q_mark);
			
			if (subjek_teras($kodmp,$jenissekolah,$_SESSION['statussek'],$ting)==1){
			   $bilteras++;
			   $colo = "#FF0000";
			   //echo $color."<br>";
			   if ($bil_mark==$row2["BILAMMP"]){
				   $mpokteras++;
			   }
			}
			//if($kodsek=='YCC4104' and $kodmp=='M3C1' and $kelas=='4U')
			//echo "<br>bil mark - $bil_mark bilambl ".$row2["BILAMMP"];
				//echo $row2["NAMA"]." - ".$tukarmp["MP"]." bil mark - $bil_mark bilambl".$row2["BILAMMP"]."<br>";
			if ($bil_mark==$row2["BILAMMP"]){
				echo  "<td color=\"$colo\"><center>$bil_mark</center></td><td><center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center></td>";
				$mpok = $mpok + 1;
			}//tutup bilmark
			else
				echo  "<td><center>$bil_mark</center></td><td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></td>";
			
			if ($num<>$bilrow)
				echo  "<tr></th></th>\n";
			$bilrow=$bilrow+1;
//***********************************************************************************************

	$i=$i+1;
	
	print "<input name=\"bilrow\" type=\"hidden\" readonly value=\"$bilrow\">";
	print "<input name=\"tahun\" type=\"hidden\" readonly value=\"$_SESSION[tahun]\">";
	print "<input name=\"bil\" type=\"hidden\" readonly value=\"$bil\">";
	print "<input name=\"kodsek[$i]\" type=\"hidden\" readonly value=\"$kodsek\">";
	print "<input name=\"ting[$i]\" type=\"hidden\" readonly value=\"$ting\">";
	print "<input name=\"kelas[$i]\" type=\"hidden\" readonly value=\"$kelas\">";
	print "<input name=\"bilmp[$i]\" type=\"hidden\" readonly value=\"$bilmp\">";
	print "<input name=\"bilm[$i]\" type=\"hidden\" readonly value=\"$bilm\">";
	print "<input name=\"bilamb[$i]\" type=\"hidden\" readonly value=\"$bilamb\">";
	print "<input name=\"mp[$i]\" type=\"hidden\" readonly value=\"".$tukarmp["MP"]."\">";
	print "<input name=\"nama[$i]\" type=\"hidden\" readonly value=\"".$row2["NAMA"]."\">";
	print "<input name=\"bilammp[$i]\" type=\"hidden\" readonly value=\"".$row2["BILAMMP"]."\">";
	print "<input name=\"bil_mark[$i]\" type=\"hidden\" readonly value=\"$bil_mark\">";

//***********************************************************************************************

$bilk++;
print "<input name=\"num[$bil]\" type=\"hidden\" readonly value=\"$num\">";
		}// tutp while
	}
	 else { 
			echo  "<td>Tiada Mp</td><br>";
			echo  "<td>Tiada Guru MP</td>&nbsp<br>";
			echo  "<td><center>0</center><td><center>0</center></td><td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></td>";
			}//tutup num!=0
			
echo  "</tr>";	
print "<input name=\"bilk\" type=\"hidden\" readonly value=\"$bilk\">";
if (($mpok ==  $num) AND ( $num!=0 )) {$biltkt = $biltkt + 1; }
}
echo "</table><br>";

	//***************************END FORM PAPARAN CETAK********************************************************
echo "<br><center>\n";
echo "<input type=\"submit\" name=\"submit\" value=\"PAPARAN CETAK\">";
echo "<br><br><br>";
echo "</form></center>\n";
//***************************END FORM PAPARAN CETAK********************************************************


//echo "bilteras:$bilteras mpokteras:$mpokteras<br>";

	echo "<table width=\"85%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">";
	if ($bilteras == $mpokteras and $bilteras>0){ 
		echo "<tr bgcolor=\"#00FF00\"><td valign=\"middle\">";
		echo "<center><strong><font color=\"#000000\">Sila Klik Untuk Pengesahan<br>MARKAH ".ting($ting)."</font></strong></center>";
		echo "<form name=\"form1\" method=\"post\" action=\"data-sah.php?data=".$_SESSION['tahun']."/".$kodsek."/".$ting."/".$_SESSION['jpep']."/".date("d-m-Y")."\">\n";
		echo " <center><br><input type=\"submit\" name=\"cetak\" value=\"SILA KLIK UNTUK PENGESAHAN ".ting($ting)."\"></center>\n";
		echo "</form>\n";
		echo "</td></tr>";
	}
	else if ($_SESSION['jpep']=='PAT' and $ting=='D6'){
		echo "<tr bgcolor=\"#00FF00\"><td valign=\"middle\">";
		echo "<center><strong><font color=\"#000000\">Sila Klik Untuk Pengesahan<br>MARKAH ".ting($ting)."</font></strong></center>";
		echo "<form name=\"form1\" method=\"post\" action=\"data-sah.php?data=".$_SESSION['tahun']."/".$kodsek."/".$ting."/".$_SESSION['jpep']."/".date("d-m-Y")."\">\n";
		echo " <center><br><input type=\"submit\" name=\"cetak\" value=\"SILA KLIK UNTUK PENGESAHAN ".ting($ting)."\"></center>\n";
		echo "</form>\n";
		echo "</td></tr>";		
	 } else { 
	 	echo "<tr bgcolor=\"#FF0000\"><td><center><strong>Pengesahan MARKAH ".ting($ting)." Belum Selesai. Pengesahan MARKAH hanya boleh di buat selepas semua mata pelajaran teras diisi.</strong></center></td></tr>"; 
	}
	echo "</table>";


/*echo "</th>\n";
echo "</tr>\n";
echo "</table></body>\n";*/
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