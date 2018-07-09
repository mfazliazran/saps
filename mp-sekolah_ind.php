<form>
<input type=button name=mybutton id=mybutton value="Cetak" onClick="window.print();">
</form>

<?php
include 'config.php';
include 'fungsi.php';
include 'fungsikira.php';
include "input_validation.php";
?>
<title>Sistem Analisis Peperiksaan Sekolah</title>
<?php

	$tahun_semasa=validate($_POST['tahun_semasa']);
	$kodmp=validate($_POST['kodmp']);
	$ting=validate($_POST['ting']);
	$jpep=validate($_POST['pep']);
	$status1=validate($_POST['statussek']);
	$kodsek=validate($_POST['kodsek']);
  
  
  $data = "kodsek-$kodsek|ting-$ting|kodmp-$kodmp|tahun-$tahun_semasa|jpep-$jpep|status-$status1";
  
  if($status1=="SM"){ 
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod= :kodmp"); 
  		oci_bind_by_name($qmp, ':kodmp', $kodmp);
		oci_execute($qmp);
		}
  if($status1=="SR"){ 
  		$qmp=oci_parse($conn_sispa,"SELECT * FROM mpsr WHERE kod= :kodmp"); 
  		oci_bind_by_name($qmp, ':kodmp', $kodmp);
		oci_execute($qmp);
		}
$rmp=oci_fetch_array($qmp);
   
   $qstatus=oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek= :kodsek AND status= :status"); 
   oci_bind_by_name($qstatus, ':kodsek', $kodsek);
   oci_bind_by_name($qstatus, ':status', $status1);
   oci_execute($qstatus);
   $rstatus=oci_fetch_array($qstatus);
	?>
   <?php
if (($ting=="P") OR ($ting=="T1") OR ($ting=="T2") OR ($ting=="T3") OR ($ting=="T4") OR ($ting=="T5")){		
echo "<h3><center> ".jpep($jpep)." <br>ANALISIS MATA PELAJARAN <br>TAHUN ".$tahun_semasa."<br><br>".$rmp["MP"]." </center></h3>";
echo "<table width=\"70%\"  border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr><td><b>SEKOLAH : </b>".$rstatus["NAMASEK"]."</td></tr>";
echo "<tr><td><b>TAHUN / TINGKATAN : </b>".tahap($ting)."</td></tr>";
echo "</table>";
echo "<br>";
echo "<table width=\"70%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr>";
echo "    <td><div align=\"center\">Bil </div></td>\n";
echo "    <td><div align=\"center\">Kelas </div></td>\n";
echo "    <td><div align=\"center\">Nama Calon </div></td>\n";
echo "    <td><div align=\"center\">No. KP</div></td>\n";
echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">Gred</div></td>\n";
echo "  </tr>\n";

	$bil=0;
	
	$q_mp = "SELECT  * FROM markah_pelajar WHERE kodsek= :kodsek AND tahun= :tahun_s AND jpep= :jpep AND ting= :ting AND :kodmp IS NOT NULL ORDER BY decode(trim($kodmp),'TH',-1,to_number($kodmp)) desc";
	$qry = oci_parse($conn_sispa,$q_mp);
	oci_bind_by_name($qry, ':kodsek', $kodsek);
	oci_bind_by_name($qry, ':tahun_s', $tahun_semasa);
	oci_bind_by_name($qry, ':jpep', $jpep);
	oci_bind_by_name($qry, ':ting', $ting);
	oci_bind_by_name($qry, ':kodmp', $kodmp);
	oci_execute($qry);
	while($rbmurid=oci_fetch_array($qry)){
	$bil=$bil+1;
		
	echo "  <tr>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["KELAS"]."</center></span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\">".$rbmurid["NAMA"]."</span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\">".$rbmurid["NOKP"]."</span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid[$kodmp]."</center></span></td>\n";
	echo "    <td><center>".$rbmurid['G'.$kodmp]."</center></td>\n";
	echo "  </tr>\n";
	}
}
	
if (($ting=="D1") OR ($ting=="D2") OR ($ting=="D3") OR ($ting=="D4") OR ($ting=="D5") OR ($ting=="D6")){		
echo "<h3><center> ".jpep($jpep)." <br>ANALISIS MATA PELAJARAN <br>TAHUN ".$tahun_semasa."<br><br>".$rmp["MP"]." </center></h3>";
echo "<table width=\"70%\"  border=\"0\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr><td><b>SEKOLAH : </b>".$rstatus["NAMASEK"]."</td></tr>";
echo "<tr><td><b>TAHUN / TINGKATAN : </b>".tahap($ting)."</td></tr>";
echo "</table>";
echo "<br>";
echo "<table width=\"70%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr>";
echo "    <td><div align=\"center\">Bil </div></td>\n";
echo "    <td><div align=\"center\">Kelas </div></td>\n";
echo "    <td><div align=\"center\">Nama Calon </div></td>\n";
echo "    <td><div align=\"center\">No. KP</div></td>\n";
echo "    <td><div align=\"center\">Markah</div></td>\n";
echo "    <td><div align=\"center\">Gred</div></td>\n";
echo "  </tr>\n";

	$bil=0;
	
	$q_mp = "SELECT  * FROM markah_pelajarsr WHERE kodsek= :kodsek AND tahun= :tahun_s AND jpep= :jpep AND darjah= :ting AND :kodmp IS NOT NULL ORDER BY decode($kodmp,'TH',-1,to_number($kodmp)) desc";
	$qry = oci_parse($conn_sispa,$q_mp);
	oci_bind_by_name($qry, ':kodsek', $kodsek);
	oci_bind_by_name($qry, ':tahun_s', $tahun_semasa);
	oci_bind_by_name($qry, ':jpep', $jpep);
	oci_bind_by_name($qry, ':ting', $ting);
	oci_bind_by_name($qry, ':kodmp', $kodmp);
	oci_execute($qry);
	while($rbmurid=oci_fetch_array($qry)){
	$bil=$bil+1;
		
	echo "  <tr>\n";
	echo "    <td align=\"center\" valign=\"top\"><span class=\"style2\">$bil</span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid["KELAS"]."</center></span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\">".$rbmurid["NAMA"]."</span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\">".$rbmurid["NOKP"]."</span></td>\n";
	echo "    <td valign=\"top\"><span class=\"style2\"><center>".$rbmurid[$kodmp]."</center></span></td>\n";
	echo "    <td><center>".$rbmurid['G'.$kodmp]."</center></td>\n";
	echo "  </tr>\n";
	}
}
echo "</table>";
echo "<br><br>";
if ($conn_sispa) 
  OCILogoff($conn_sispa); 
  	
?>