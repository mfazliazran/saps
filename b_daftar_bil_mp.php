<?php
include 'auth.php'; 
include 'config.php';

$stmt=oci_parse($conn_sispa,"SELECT STATUSSEK FROM login WHERE nokp='".$_SESSION['nokp']."'");
oci_execute($stmt);

if (oci_fetch_array($stmt))
  $status=OCIResult($stmt,"STATUSSEK");

if ($status=="SM"){ $tmurid = "tmurid"; }
if ($status=="SR"){ $tmurid = "tmuridsr"; }

$num=count_row("SELECT * FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND nokp='".$_SESSION['nokp']."' AND bilammp=''");
if ($num!=0){
echo "<br><br><br>"; 
echo "<div align=\"center\"><h2>KEMASKINI BILANGAN MURID SETIAP MATA PELAJARAN</h2><br><b><font color =\"#ff0000\">$r_ng[nama]</font></b><br><br>Masukan bilangan murid mengikut mata pelajaran</div>\n";
echo "<form name=\"form1\" method=\"post\" action=\"daftar_bil_mp.php\">\n";
echo "  <table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#FFFFFF\">\n";
echo "    <tr bgcolor=\"#CCCCCC\">\n";
echo "      <td><div align=\"center\">Bil<input name=\"bil\" type=\"hidden\" id=\"bil\" readonly size=\"3\" value=\"$num\"></div></td>\n";
echo "      <td><div align=\"center\">Ting</div></td>\n";
echo "      <td><div align=\"center\">Kelas</div></td>\n";
echo "      <td><div align=\"center\">Mata Pelajaran </div></td>\n";
echo "      <td><div align=\"center\">Bil. Murid </div></td>\n";
echo "      <td><div align=\"center\">Bil. Ambil </div></td>\n";
echo "    </tr>\n";

$bil=0;
$q_bilam=oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND nokp='".$_SESSION['nokp']."' AND bilammp=''");
oci_execute($q_bilam);
while($r_bilam=mysql_fetch_array($q_bilam)){
$bil=$bil+1;
echo "    <tr bgcolor=\"#FFFF99\">\n";
echo "      <td><center>$bil</center></td>\n";
echo "      <td><input name=\"ting[$i]\" type=\"text\" id=\"ting\" size=\"3\" readonly value=\"$r_bilam[ting]\"></td>\n";
echo "      <td><input name=\"kelas[$i]\" type=\"text\" id=\"kelas\" size=\"15\" readonly value=\"$r_bilam[kelas]\"><input name=\"kodsek[$i]\" type=\"hidden\" id=\"kodsek\" size=\"15\" readonly value=\"$r_bilam[kodsek]\"></td>\n";
echo "      <td><input name=\"mp[$i]\" type=\"text\" id=\"mp\" size=\"30\" readonly value=\"$r_bilam[kodmp]\"></td>\n";
$q_tmurid=osi_parse($conn_sispa,"SELECT * FROM $tmurid WHERE kodsek$r_bilam[ting]='$r_bilam[kodsek]' AND $r_bilam[ting]='$r_bilam[ting]' AND kelas$r_bilam[ting]='$r_bilam[kelas]'");
osi_execute($q_tmurid);
$num_mu=count_row($q_tmurid);
echo "      <td><input name=\"bilmu[$i]\" type=\"text\" id=\"bilmu\" readonly size=\"3\" value=\"$num_mu\"></td>\n";
echo "      <td><input name=\"bilam[$i]\" type=\"text\" id=\"bilam\" size=\"3\"></td>\n";
echo "    </tr>\n";
	}
echo "  </table>\n";
echo "  <p><center><input type=\"submit\" name=\"Submit\" value=\"Hantar\"></center></p>\n";
echo "</form>\n";
}
else{
//header('Location: index.php');
if ($_SESSION['level']=="3" or $_SESSION['level']=="4"){
			//header('Location: index.php');
			header('Location: sah-markah.php');
}
else {
header('Location: papar_subjek.php');
}
}
?>