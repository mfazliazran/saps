<?php
include("sambungan.php");
session_start();

//CA16111201
if(!isset($kodsek)){$kodsek="";}
if(!isset($ting)){$ting="";}
if(!isset($kelas)){$kelas="";}
if(!isset($nokp)){$nokp="";}
if(!isset($tahun)){$tahun="";}
if(!isset($kdk_u1)){$kdk_u1="";}
if(!isset($kdk_u2)){$kdk_u2="";}
if(!isset($kdk_pat)){$kdk_pat="";}
if(!isset($kdk_ppt)){$kdk_ppt="";}
if(!isset($kdk_pmrc)){$kdk_pmrc="";}
if(!isset($kdt_u1)){$kdt_u1="";}
if(!isset($kdt_u2)){$kdt_u2="";}
if(!isset($kdt_pat)){$kdt_pat="";}
if(!isset($kdt_ppt)){$kdt_ppt="";}
if(!isset($kdt_pmrc)){$kdt_pmrc="";}
if(!isset($bil_kdt_u1)){$bil_kdt_u1="";}
if(!isset($bil_kdt_u2)){$bil_kdt_u2="";}
if(!isset($bil_kdt_pat)){$bil_kdt_pat="";}
if(!isset($bil_kdt_ppt)){$bil_kdt_ppt="";}
if(!isset($bil_kdt_pmrc)){$bil_kdt_pmrc="";}
if(!isset($bil_kdk_u1)){$bil_kdk_u1="";}
if(!isset($bil_kdk_u2)){$bil_kdk_u2="";}
if(!isset($bil_kdk_pat)){$bil_kdk_pat="";}
if(!isset($bil_kdk_ppt)){$bil_kdk_ppt="";}
if(!isset($bil_kdk_pmrc)){$bil_kdk_pmrc="";}
if(!isset($peratus_u1)){$peratus_u1="";}
if(!isset($peratus_u2)){$peratus_u2="";}
if(!isset($peratus_pat)){$peratus_pat="";}
if(!isset($peratus_ppt)){$peratus_ppt="";}
if(!isset($peratus_pmrc)){$peratus_pmrc="";}
if(!isset($cboPep)){$cboPep="";}
//CA16111201

$jpep1[0]="U1";
$jpep1[1]="PPT";
$jpep1[2]="U2";
$jpep1[3]="PAT";
$jpep1[4]="PMRC";
//$c = $_GET['data'];
//list ($nokp, $kodsek, $ting, $kelas) = split('[|]', $c);
$nokp = $_REQUEST["nokp"];
$kodsek = $_REQUEST["kodsek"];
$ting = $_REQUEST["ting"];
$kelas = $_REQUEST["kelas"];
$jpep = $_REQUEST['cboPep'];
//$tahun = date("Y");
if($_SESSION["tahun_semasa"]<>"")
	$tahun = $_SESSION["tahun_semasa"];
else
	$tahun = date("Y");

include("../include/kirasemak_mark.php");

$q_sek = oci_parse($conn_sispa,"SELECT NAMASEK,LENCANA FROM tsekolah WHERE kodsek= :kodsek");
oci_bind_by_name($q_sek, ':kodsek', $kodsek);
oci_execute($q_sek);
$rowsek = oci_fetch_array($q_sek);
$namasek = $rowsek["NAMASEK"];
$lencana = $rowsek["LENCANA"];

$q_pel = oci_parse($conn_sispa,"SELECT JANTINA,NAMA,TING,KELAS,NOKP FROM markah_pelajar WHERE nokp= :nokp AND kodsek= :kodsek and ting= :ting and tahun= :tahun");//and kelas= :kelas 
oci_bind_by_name($q_pel, ':kodsek', $kodsek);
oci_bind_by_name($q_pel, ':ting', $ting);
//oci_bind_by_name($q_pel, ':kelas', $kelas);
oci_bind_by_name($q_pel, ':nokp', $nokp);
oci_bind_by_name($q_pel, ':tahun', $tahun);
oci_execute($q_pel);
$rowpel = oci_fetch_array($q_pel);
$jantina = $rowpel["JANTINA"];

$jan = array("L" => "LELAKI","P" => "PEREMPUAN");
?>
<title>Sistem Analisis Peperiksaan Sekolah</title>
<?php	   
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">\n";

//CA16110902
if (isset($lencana)){
	echo "<center><img src=\"/images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
}
//CA16110902

echo "<center><b>$namasek</b></center>";
echo "<br>\n";
echo "<table width=\"700\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
echo "  <tr style=\"background-color: rgb(204, 204, 204); height: 100%;\">\n";
echo "    <td><div align=\"center\"><strong>ANALISIS KEPUTUSAN UJIAN & PEPERIKSAAN BAGI TAHUN $tahun</strong></div></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br>\n";
echo "<table width=\"700\"  border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr style=\"background-color: rgb(204, 204, 204); height: 100%;\">\n";
echo "    <td width=\"80\">&nbsp;Nama</font><br></td>\n";
echo "    <td width=\"1\">:</font><br></td>\n";
echo "    <td width=\"388\">&nbsp;".$rowpel["NAMA"]."</font><br></td>\n";
echo "    <td width=\"80\">&nbsp;Tingkatan</td>\n";
echo "    <td width=\"1\">:</font><br></td>\n";
echo "    <td width=\"150\">&nbsp;".$rowpel["TING"]." ".$rowpel["KELAS"]."</font><br></td>\n";
echo "  </tr>\n";
echo "  <tr style=\"background-color: rgb(204, 204, 204); height: 100%;\">\n";
echo "    <td>&nbsp;No. KP</td>\n";
echo "    <td>:</td>\n";
echo "    <td>&nbsp;".$rowpel["NOKP"]."</td>\n";
echo "    <td>&nbsp;Jantina</td>\n";
echo "    <td>:</td>\n";
echo "    <td>&nbsp;$jan[$jantina]</td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br>\n";
echo "<table width=\"700\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td colspan=\"4\"><div align=\"center\"></div><div align=\"center\">\n";
echo "      <hr align=\"center\" noshade>\n";
echo "    </div></td>\n";
echo "  </tr>\n";

echo "<table width=\"700\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">\n";
echo "  <tr bgcolor=\"#ff9900\">\n";
echo "    <td rowspan='2'>&nbsp;&nbsp;&nbsp;Bil</td>\n";
echo "    <td rowspan='2'>Mata Pelajaran </td>\n";

for($i=0;$i<=4;$i++){
echo "    <td colspan='2'><div align=\"center\">".jpep2($jpep1[$i])."</div></td>\n";
}
echo "  </tr>\n";
echo "  <tr bgcolor=\"#ff9900\">\n";
echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">Gred</div></td>\n";
echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">Gred</div></td>\n";
echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">Gred</div></td>\n";
echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">Gred</div></td>\n";
echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">Gred</div></td>\n";
echo "  </tr>\n";
//echo "  <tr>\n";
//echo "    <td colspan=\"4\"><hr align=\"center\" noshade></td>\n";
//echo "  </tr>\n";

$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc ORDER BY kod");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$namamp[] = array("$rowsub[KOD]"=>$rowsub["MP"]);
}

$bil=0;
/*$q_subgu = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas ORDER BY kodmp");
oci_bind_by_name($q_subgu, ':tahun', $tahun);
oci_bind_by_name($q_subgu, ':kodsek', $kodsek);
oci_bind_by_name($q_subgu, ':ting', $kelas);
oci_bind_by_name($q_subgu, ':kelas', $kelas);
oci_execute($q_subgu);*/
$q_subgu = oci_parse($conn_sispa,"SELECT KODMP FROM sub_guru WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas ORDER BY kodmp");
oci_bind_by_name($q_subgu, ':tahun', $tahun);
oci_bind_by_name($q_subgu, ':kodsek', $kodsek);
oci_bind_by_name($q_subgu, ':ting', $ting);
oci_bind_by_name($q_subgu, ':kelas', $kelas);
oci_execute($q_subgu);
while($rowsubgu = oci_fetch_array($q_subgu))
{	
	$kodmp = $rowsubgu["KODMP"];
	$gmp = "G$kodmp";
	$ada=0;$markah_u1='';$gred_u1='';$markah_ppt='';$gred_ppt='';$markah_u2='';$gred_u2='';$markah_pat='';$gred_pat='';$markah_pmrc='';$gred_pmrc='';
	$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp='$nokp' AND mr.nokp='$nokp' 
AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting'");// and mkh.jpep='$jpep1[$i]'"); // AND mkh.kelas='$kelas'
//if($nokp=='980909106031')
	//echo "<br>SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp='$nokp' AND mr.nokp='$nokp' AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting'";
	oci_execute($q_slip);
	while($rowmurid = oci_fetch_array($q_slip)){
	 if ($rowmurid[$kodmp]<>"")
	   $ada=1;
	     $jpep1=$rowmurid["JPEP"];
		 $mark[$i]=$rowmurid[$kodmp];
		 $gred[$i]=$rowmurid[$gmp];
		 if ($jpep1=="U1"){
		   $markah_u1=$rowmurid[$kodmp];
		   $gred_u1=$rowmurid[$gmp];
		   $kelasu1=$rowmurid["KELAS"];
		 }
		 if ($jpep1=="PPT"){
		   $markah_ppt=$rowmurid[$kodmp];
		   $gred_ppt=$rowmurid[$gmp];
		   $kelasppt=$rowmurid["KELAS"];
		 }
		 if ($jpep1=="U2"){
		   $markah_u2=$rowmurid[$kodmp];
		   $gred_u2=$rowmurid[$gmp];
		   $kelasu2=$rowmurid["KELAS"];
		 }
		 if ($jpep1=="PAT"){
		   $markah_pat=$rowmurid[$kodmp];
		   $gred_pat=$rowmurid[$gmp];
		   $kelaspat=$rowmurid["KELAS"];
		 }
		 if ($jpep1=="PMRC"){
		   $markah_pmrc=$rowmurid[$kodmp];
		   $gred_pmrc=$rowmurid[$gmp];
		   $kelaspmrc=$rowmurid["KELAS"];
		 }
	}   
	//}//for
	if($ada==1){
	echo "  <tr style=\"background-color: rgb(153, 153, 153); height: 25px;\">\n";
	$bil=$bil+1;
	echo "<td>&nbsp;&nbsp;&nbsp;$bil.</td>\n";
	echo "<td>";

	//CA16111101
	//error_reporting(E_ALL & ~E_NOTICE);

	foreach ($namamp as $key => $mp){
		echo "$mp[$kodmp]";
	}

	//CA16111101
	//error_reporting(E_ALL);

	echo "</td>\n";
	echo "    <td><center>$markah_u1</center></td>\n";
	echo "    <td><center>$gred_u1</center></td>\n";
	echo "    <td><center>$markah_ppt</center></td>\n";
	echo "    <td><center>$gred_ppt</center></td>\n";
	echo "    <td><center>$markah_u2</center></td>\n";
	echo "    <td><center>$gred_u2</center></td>\n";
	echo "    <td><center>$markah_pat</center></td>\n";
	echo "    <td><center>$gred_pat</center></td>\n";
	echo "    <td><center>$markah_pmrc</center></td>\n";
	echo "    <td><center>$gred_pmrc</center></td>\n";	
	echo "  </tr>\n";
	}
}//q subguru

$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 
$q_mr = oci_parse ($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp='$nokp' AND mr.nokp='$nokp' 
AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting='$ting'");// AND mkh.kelas='$kelas'
$gting=strtoupper($ting);

oci_execute($q_mr);
while ($rowmurid = oci_fetch_array($q_mr)){;
 if ($rowmurid[$kodmp]<>"")
	   $ada=1;
	     $jpep3=$rowmurid["JPEP"];
		 if ($jpep3=="U1"){
		   $peratus_u1=$rowmurid['PERATUS'];
		   $kdt_u1=$rowmurid['KDT'];
		   $kdk_u1=$rowmurid['KDK'];
		   $bil_kdk_u1=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.kelas='$kelasu1'  AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		   $bil_kdt_u1=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		 }
		 if ($jpep3=="PPT"){
		   $peratus_ppt=$rowmurid['PERATUS'];
		   $kdt_ppt=$rowmurid['KDT'];
		   $kdk_ppt=$rowmurid['KDK'];
		   $bil_kdk_ppt=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.kelas='$kelasppt'  AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		   $bil_kdt_ppt=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		 }
		 if ($jpep3=="U2"){
		   $peratus_u2=$rowmurid['PERATUS'];
		   $kdt_u2=$rowmurid['KDT'];
		   $kdk_u2=$rowmurid['KDK'];
		   $bil_kdk_u2=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.kelas='$kelasu2'  AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		   $bil_kdt_u2=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		 }
		 if ($jpep3=="PAT"){
		   $peratus_pat=$rowmurid['PERATUS'];
		   $kdt_pat=$rowmurid['KDT'];
		   $kdk_pat=$rowmurid['KDK'];
		   $bil_kdk_pat=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.kelas='$kelaspat'  AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		   $bil_kdt_pat=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		 }
		 if ($jpep3=="PMRC"){
		   $peratus_pmrc=$rowmurid['PERATUS'];
		   $kdt_pmrc=$rowmurid['KDT'];
		   $kdk_pmrc=$rowmurid['KDK'];
		   $bil_kdk_pmrc=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.kelas='$kelaspmrc' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		   $bil_kdt_pmrc=count_row("SELECT *  FROM tnilai_smr mr, tmurid tm WHERE mr.tahun='$tahun' AND mr.jpep='$jpep3' AND mr.ting='$ting' AND mr.kodsek='$kodsek' AND mr.nokp=tm.nokp ORDER BY mr.keputusan Desc, mr.gpc Asc, mr.peratus Desc");
		 }
	}   



echo "  <tr bgcolor=\"#ff9900\">\n";
echo "   <td colspan='2'> KDT </td>";
echo "   <td colspan='2'><center>$kdt_u1 / $bil_kdt_u1</center> </td>";
echo " 	 <td colspan='2'><center>$kdt_ppt / $bil_kdt_ppt</center></td>";
echo "   <td colspan='2'><center>$kdt_u2 / $bil_kdt_u2</center></td>";
echo "   <td colspan='2'><center>$kdt_pat / $bil_kdt_pat</center></td>";
echo "   <td colspan='2'><center>$kdt_pmrc / $bil_kdt_pmrc</center></td>";
echo "</tr>";
echo "  <tr bgcolor=\"#ff9900\">\n";
echo "   <td colspan='2'> KDK </td>";
echo "   <td colspan='2'><center>$kdk_u1 / $bil_kdk_u1</center></td>";
echo " 	 <td colspan='2'><center>$kdk_ppt / $bil_kdk_ppt</center></td>";
echo "   <td colspan='2'><center>$kdk_u2 / $bil_kdk_u2</center></td>";
echo "   <td colspan='2'><center>$kdk_pat / $bil_kdk_pat</center></td>";
echo "   <td colspan='2'><center>$kdk_pmrc / $bil_kdk_pmrc</center></td>";
echo "</tr>";

echo "  <tr bgcolor=\"#ff9900\">\n";
echo "   <td colspan='2'> PERATUS </td>";
echo "   <td colspan='2'><center>$peratus_u1</center></td>";
echo " 	 <td colspan='2'><center>$peratus_ppt</center></td>";
echo "   <td colspan='2'><center>$peratus_u2</center></td>";
echo "   <td colspan='2'><center>$peratus_pat</center></td>";
echo "   <td colspan='2'><center>$peratus_pmrc</center></td>";
echo "</tr>";
  ?>






