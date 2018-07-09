<?php 
session_start();
include 'config.php';
$d=$_GET['data'];
list($tahun, $kodsek, $jpep)=split('[/]', $d);

$q_p=oci_parse($conn_sispa,"SELECT * FROM login WHERE kodsek='$kodsek' AND level1='P'");
oci_execute($q_p);
$rowpg=oci_fetch_array($q_p);
$nama=$rowpg['NAMA'];
$nokp=$rowpg['NOKP'];

$qrynamalencana = oci_parse($conn_sispa,"SELECT * FROM tsekolah, tkppd WHERE tsekolah.kodsek='$kodsek' AND tsekolah.kodppd=tkppd.KodPPD");
oci_execute($qrynamalencana);
$row=oci_fetch_array($qrynamalencana);
$lencana=$row['LENCANA'];
$namasek=$row['NAMASEK'];
$status=$row['STATUS'];
$ppd=$row['PPD'];
$negeri=$row['NEGERI'];


echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">";
echo "<table width=\"90%\"  border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"2\"><table width=\"100%\"  border=\"0\">\n";
echo "      <tr>\n";
echo "        <td width=\"80\">";
	$qrynoimage = "SELECT * FROM tsekolah WHERE kodsek='$kodsek' AND lencana='' ";
	$stmt = oci_parse($conn_sispa,$qrynoimage);
	oci_execute($stmt);
	if(count_row($qrynoimage)==1){
		echo "<img src=\"images/lencana/noimage.gif\"  width=\"50\" height=\"53\" ></div></th>";
		}
		else{
		echo "<img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ></div></th>";
		}
echo " </td>\n";
echo "        <td valign=\"top\"><b>$namasek<br>$ppd<br>$negeri<br>$kodsek</b></td>\n";
echo "      </tr>\n";
echo "    </table></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td colspan=\"2\"><hr noshade></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td width=\"52%\"><em>&quot;1 MALAYSIA : RAKYAT DIDAHULUKAN, PENCAPAIAN DIUTAMAKAN&quot;</em></td>\n";
echo "    <td width=\"48%\"><div align=\"right\">Tarikh : ".date('d-m-Y')."</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td colspan=\"2\"><p>&nbsp;</p>\n";
echo "      <p>TUAN PEGAWAI PELAJARAN DAERAH<br>\n";
echo "        PEJABAT PELAJARAN DAERAH $ppd<br>\n";
echo "        (U.P : Unit Akademik)</p>      <p>Tuan,</p><p><strong>PENGESAHAN KEMASUKKAN MARKAH $jpep TAHUN ".date('Y')."</strong></p>\n";
echo "      <p>Dengan hormatnya mengenai perkara di atas adalah dirujuk. </p>\n";
echo "      <p>2. Sukacitanya saya $nama ($nokp) mengesahkan bahawa semua guru $namasek telah kemaskini markah $jpep dalam Sistem Analisis Peperiksaan seperti berikut : </p>\n";
echo "<table width=\"400\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo "    <td width=\"146\"><div align=\"center\">Tingkatan / Tahun </div></td>\n";
echo "    <td width=\"144\"><div align=\"center\">Tarikh Sah</div></td>\n";
echo "    <td width=\"144\"><div align=\"center\">Status</div></td>\n";
echo "  </tr>\n";
$q_ting="SELECT DISTINCT ting FROM tkelassek WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' ORDER by ting";
$qry_ting = oci_parse($conn_sispa,$q_ting);
oci_execute($qry_ting);
$numt=count_row($q_ting);
$bilsah=0;
while($rowtg=oci_fetch_array($qry_ting)){
echo "  <tr>\n";
echo "    <td><div align=\"center\">$rowtg[TING]</div></td>\n";
	$q_sah="SELECT * FROM tsah WHERE tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$rowtg[TING]' AND jpep='$jpep'";
	$qry_sah = oci_parse($conn_sispa,$q_sah);
	oci_execute($qry_sah);
	$num=count_row($q_sah);
	$rowsah=oci_fetch_array($qry_sah);
		if($num == 1){ $bilsah=$bilsah+1; $st = "<center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center>";}
		else{ $st = "<center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center>";}
	echo "    <td><div align=\"center\">&nbsp;$rowsah[TKSAH]</div></td>\n";
	echo "    <td><div align=\"center\">$st</div></td>\n";
	echo "  </tr>\n";
	}
if($status=="SM"){$pgb="PENGETUA";}else{$pgb="GURU BESAR";}
echo "</table>\n";
echo "	<p>Sekian, Terima Kasih.</p>\n";
echo "	<p>&quot;BERKHIDMAT UNTUK NEGARA&quot; </p><br><br><br><br>\n";
echo "    <p>Saya yang menurut perintah, </p>\n";
echo "	</td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br><br><br><br><table width=\"90%\"  border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
echo "	<td>$nama<br>$pgb<br>$namasek</td>\n";
echo "</table>\n";

?>