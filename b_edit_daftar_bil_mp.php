<?php

//CA16111404
$i="";
//CA16111404

include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Bilangan Ambil Mata Pelajaran</p>
<?php

//$q_ng=oci_parse($conn_sispa,"SELECT * FROM login WHERE nokp='".$_SESSION['nokp']."'");
//oci_execute($q_ng);
//$r_ng=oci_fetch_array($q_ng);
//<b><font color =\"#ff0000\">sss$r_ng[nama]</font></b>

//if ($status=="SM"){ $tmurid = "tmurid"; }
//if ($status=="SR"){ $tmurid = "tmuridsr"; }
$q_bilam=oci_parse($conn_sispa,"SELECT KODMP,TING,KELAS,KODSEK,BILAMMP FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND nokp='".$_SESSION['nokp']."'");
oci_execute($q_bilam);
$num=count_row("SELECT * FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND nokp='".$_SESSION['nokp']."'");
echo "<br>";
echo "<div align=\"center\"><h3>KEMASKINI BILANGAN MURID SETIAP MATA PELAJARAN</h3><br><br><br>Masukan bilangan murid mengikut mata pelajaran<br><br></div>\n";
echo "<form name=\"form1\" method=\"post\" action=\"daftar_bil_mp.php\">\n";
echo "  <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#FFFFFF\">\n";
echo "    <tr bgcolor=\"#CCCCCC\">\n";
echo "      <td><div align=\"center\">Bil<input name=\"bil\" type=\"hidden\" id=\"bil\" readonly size=\"3\" value=\"$num\"></div></td>\n";
echo "      <td><div align=\"center\">Ting</div></td>\n";
echo "      <td><div align=\"center\">Kelas</div></td>\n";
echo "      <td><div align=\"center\">Mata Pelajaran </div></td>\n";
echo "      <td><div align=\"center\">Bil. Ambil </div></td>\n";
echo "    </tr>\n";

$bil=0;
while($r_bilam=oci_fetch_array($q_bilam)){
$bil=$bil+1;
$mp=subjek_desc($r_bilam["KODMP"]);
echo "    <tr bgcolor=\"#FFFF99\">\n";
echo "      <td><center>$bil</center></td>\n";
echo "      <td>$r_bilam[TING]<input name=\"ting[$i]\" type=\"hidden\" id=\"ting\" size=\"3\" readonly value=\"$r_bilam[TING]\"></td>\n";
echo "      <td>$r_bilam[KELAS]<input name=\"kelas[$i]\" type=\"hidden\" id=\"kelas\" size=\"15\" readonly value=\"$r_bilam[KELAS]\"><input name=\"kodsek[$i]\" type=\"hidden\" id=\"kodsek\" size=\"15\" readonly value=\"$r_bilam[KODSEK]\"></td>\n";
echo "      <td>$mp<input name=\"mp[$i]\" type=\"hidden\" id=\"mp\" size=\"30\" readonly value=\"$r_bilam[KODMP]\"></td>\n";
echo "      <td><input name=\"bilam[$i]\" type=\"text\" id=\"bilam\" size=\"3\" value=\"$r_bilam[BILAMMP]\"></td>\n";
echo "    </tr>\n";
	}
echo "  </table>\n";
echo "  <p><center><input type=\"submit\" name=\"Submit\" value=\"Hantar\"></center></p>\n";
echo "</form>\n";

function subjek_desc($kodmp)
{
	global $conn_sispa;

	$statussek = $_SESSION['statussek'];
	switch ($statussek)
	{
		case "SM" : $tmarkah = "markah_pelajar"; $tahap = "ting"; 
			$sqlkod="SELECT mp FROM mpsmkc where KOD='$kodmp'";
			$querykod = oci_parse($conn_sispa,$sqlkod); 
			oci_execute($querykod);
		break;
		case "SR" : $tmarkah = "markah_pelajarsr"; $tahap = "darjah"; 
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