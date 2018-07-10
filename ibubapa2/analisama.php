<?php
include("sambungan.php");
include("config.php");
include "../input_validation.php";
include("../include/kirasemak_mark.php");
if(!isset($_SESSION)){session_start();}

//CA16111405
$gred_spmc="";
$kdt_u2="";
$bil_kdt_u2="";
$kdt_spmc="";
$bil_kdt_spmc="";
$kdk_u2="";
$bil_kdk_u2="";
$kdk_spmc="";
$bil_kdk_spmc="";
$peratus_u2="";
$peratus_spmc="";
//CA16111405

set_time_limit(0);
$jpep1[0]="U1";
$jpep1[1]="PPT";
$jpep1[2]="U2";
$jpep1[3]="PAT";
$jpep1[4]="SPMC";

$nokp = validate($_REQUEST["nokp"]);
$kodsek = validate($_REQUEST["kodsek"]);
$ting = validate($_REQUEST["ting"]);
$kelas = validate($_REQUEST["kelas"]);

if($_SESSION["tahun_semasa"]<>"")
	$tahun = validate($_SESSION["tahun_semasa"]);
else
	$tahun = date("Y");



$q_sek = oci_parse($conn_sispa,"SELECT NAMASEK,LENCANA FROM tsekolah WHERE kodsek= :kodsek");
oci_bind_by_name($q_sek, ':kodsek', $kodsek);
oci_execute($q_sek);
$rowsek = oci_fetch_array($q_sek);
$namasek = $rowsek["NAMASEK"];
$lencana = $rowsek["LENCANA"];

$q_pel = oci_parse($conn_sispa,"SELECT JANTINA,NAMA,TING,KELAS,NOKP FROM markah_pelajar WHERE kodsek= :kodsek and ting= :ting and nokp= :nokp and tahun= :tahun");
oci_bind_by_name($q_pel, ':kodsek', $kodsek);
oci_bind_by_name($q_pel, ':ting', $ting);
oci_bind_by_name($q_pel, ':nokp', $nokp);
oci_bind_by_name($q_pel, ':tahun', $tahun);
oci_execute($q_pel);
$rowpel = oci_fetch_array($q_pel);
$jantina = $rowpel["JANTINA"];

$jan = array("L" => "LELAKI","P" => "PEREMPUAN");
?>
<form>
&nbsp;&nbsp;<input type="button" name="mybutton" id="mybutton" value="Cetak" onClick="window.print();">
</form>
<title>Sistem Analisis Peperiksaan Sekolah</title>
<?php	   
//CA16110902
if (isset($lencana)){
	echo "<center><img src=\"../images/lencana/$lencana\"  width=\"50\" height=\"53\" ></center>";
}
//CA16110902
echo "<center><b>$namasek</b></center>";
echo "<br>\n";
echo "<table width=\"700\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
echo "  <tr style=\"background-color: rgb(204, 204, 204); height: 100%;\">\n";
echo "    <td><div align=\"center\"><strong>ANALISA KEPUTUSAN UJIAN & PEPERIKSAAN BAGI TAHUN $tahun</strong></div></td>\n";
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

$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc ORDER BY kod");
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$namamp[] = array("$rowsub[KOD]"=>$rowsub["MP"]);
}

$bil=0;
$q_subgu = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas ORDER BY kodmp");
oci_bind_by_name($q_subgu, ':tahun', $tahun);
oci_bind_by_name($q_subgu, ':kodsek', $kodsek);
oci_bind_by_name($q_subgu, ':ting', $ting);
oci_bind_by_name($q_subgu, ':kelas', $kelas);
oci_execute($q_subgu);
while($rowsubgu = oci_fetch_array($q_subgu))
{	
	$kodmp = $rowsubgu["KODMP"];
	$gmp = "G$kodmp";
	
	$ada=0;$markah_u1='';$gred_u1='';$markah_ppt='';$gred_ppt='';$markah_u2='';$gred_u2='';$markah_pat='';$gred_pat='';$markah_smpc='';$gred_smpc='';
	$q_slip = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_sma mr WHERE mkh.nokp=:nokp AND mr.nokp=:nokp AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting");
	oci_bind_by_name($q_slip, ':nokp', $nokp);
	oci_bind_by_name($q_slip, ':tahun', $tahun);
	oci_bind_by_name($q_slip, ':kodsek', $kodsek);
	oci_bind_by_name($q_slip, ':ting', $ting);
	oci_bind_by_name($q_slip, ':kelas', $kelas);
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
		 if ($jpep1=="SPMC"){
		   $markah_smpc=$rowmurid[$kodmp];
		   $gred_spmc=$rowmurid[$gmp];
		   $kelasspmc=$rowmurid["KELAS"];
		 }
	}   
	if($ada==1){
	echo "  <tr style=\"background-color: rgb(153, 153, 153); height: 25px;\">\n";
	$bil=$bil+1;
	echo "<td>&nbsp;&nbsp;&nbsp;$bil.</td>\n";
	echo "<td>";

	foreach ($namamp as $key => $mp){
		echo "$mp[$kodmp]";
	}

	echo "</td>\n";
	echo "    <td><center>$markah_u1</center></td>\n";
	echo "    <td><center>$gred_u1</center></td>\n";
	echo "    <td><center>$markah_ppt</center></td>\n";
	echo "    <td><center>$gred_ppt</center></td>\n";
	echo "    <td><center>$markah_u2</center></td>\n";
	echo "    <td><center>$gred_u2</center></td>\n";
	echo "    <td><center>$markah_pat</center></td>\n";
	echo "    <td><center>$gred_pat</center></td>\n";
	echo "    <td><center>$markah_smpc</center></td>\n";
	echo "    <td><center>$gred_spmc</center></td>\n";	
	echo "  </tr>\n";
	}
}//q subguru

$gting=strtoupper($ting);
$kodsekolah = "kodsekp=:kodsek OR kodsekt1=:kodsek OR kodsekt2=:kodsek OR kodsekt3=:kodsek OR kodsekt4=:kodsek OR kodsekt5=:kodsek"; 

$q_ma = oci_parse ($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_sma mr WHERE mkh.nokp=:nokp AND mr.nokp=:nokp AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting");
oci_bind_by_name($q_ma, ':nokp', $nokp);
oci_bind_by_name($q_ma, ':tahun', $tahun);
oci_bind_by_name($q_ma, ':kodsek', $kodsek);
oci_bind_by_name($q_ma, ':ting', $ting);
oci_bind_by_name($q_ma, ':kelas', $kelas);
oci_execute($q_ma);
while ($rowmurid = oci_fetch_array($q_ma)){;
 if ($rowmurid[$kodmp]<>"")
	   $ada=1;
	     $jpep3=$rowmurid["JPEP"];

		 if ($jpep3=="U1"){
			$peratus_u1=$rowmurid['PERATUS'];
			$kdt_u1=$rowmurid['KDT'];
			$kdk_u1=$rowmurid['KDK'];
			
			$bil_kdk_u1=0;
			$qrykdku1 = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.kelas=:kelas AND mkh.jpep=:jpep";
			$parkdku1=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdku1=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdk_u1 = kira_bil_rekod($qrykdku1,$parkdku1,$valkdku1);
			
			$bil_kdt_u1=0;
			$qrykdku1 = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.jpep=:jpep";
			$parkdku1=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdku1=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdt_u1 = kira_bil_rekod($qrykdku1,$parkdku1,$valkdku1);
		 }
		 if ($jpep3=="PPT"){
			$peratus_ppt=$rowmurid['PERATUS'];
			$kdt_ppt=$rowmurid['KDT'];
			$kdk_ppt=$rowmurid['KDK'];
			
		   	$bil_kdk_ppt=0;
			$qrykdkppt = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.kelas=:kelas AND mkh.jpep=:jpep";
			$parkdkppt=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdkppt=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdk_ppt = kira_bil_rekod($qrykdkppt,$parkdkppt,$valkdkppt);
		   	
			$bil_kdt_ppt=0;
			$qrykdtppt = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.jpep=:jpep";
			$parkdtppt=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdtppt=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdt_ppt = kira_bil_rekod($qrykdtppt,$parkdtppt,$valkdtppt);
		   
		 }
		 if ($jpep3=="U2"){
			$peratus_u2=$rowmurid['PERATUS'];
			$kdt_u2=$rowmurid['KDT'];
			$kdk_u2=$rowmurid['KDK'];
		   
		  	$bil_kdk_u2=0;
			$qrykdku2 = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.kelas=:kelas AND mkh.jpep=:jpep";
			$parkdku2=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdku2=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdk_u2 = kira_bil_rekod($qrykdku2,$parkdku2,$valkdku2);
			
			$bil_kdt_u2=0;
			$qrykdtu2 = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.jpep=:jpep";
			$parkdtu2=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdtu2=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdt_u2 = kira_bil_rekod($qrykdtu2,$parkdtu2,$valkdtu2);
		 }
		 if ($jpep3=="PAT"){
			$peratus_pat=$rowmurid['PERATUS'];
			$kdt_pat=$rowmurid['KDT'];
			$kdk_pat=$rowmurid['KDK'];
		   
		  	$bil_kdk_pat=0;		  
			$qrykdkpat = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.kelas=:kelas AND mkh.jpep=:jpep";
			$parkdkpat=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdkpat=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdk_pat = kira_bil_rekod($qrykdkpat,$parkdkpat,$valkdkpat);
			
			$bil_kdt_pat=0;
			$qrykdtpat = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.jpep=:jpep";
			$parkdtpat=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdtpat=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdt_pat = kira_bil_rekod($qrykdtpat,$parkdtpat,$valkdtpat);
		 }
		 if ($jpep3=="SPMC"){
			$peratus_spmc=$rowmurid['PERATUS'];
			$kdt_spmc=$rowmurid['KDT'];
			$kdk_spmc=$rowmurid['KDK'];
		   
		   	$bil_kdk_spmc=0;		  
			$qrykdkspmc = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.kelas=:kelas AND mkh.jpep=:jpep";
			$parkdkspmc=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdkspmc=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdk_spmc = kira_bil_rekod($qrykdkspmc,$parkdkspmc,$valkdkspmc);
			
			$bil_kdt_spmc=0;
			$qrykdtspmc = "SELECT * FROM markah_pelajar mkh , tnilai_sma ma WHERE mkh.nokp=ma.nokp AND mkh.tahun=ma.tahun and mkh.kodsek=ma.kodsek and mkh.ting=ma.ting and mkh.kelas=ma.kelas and mkh.jpep=ma.jpep AND mkh.tahun=:tahun AND mkh.kodsek=:kodsek AND mkh.ting=:ting AND mkh.jpep=:jpep";
			$parkdtspmc=array(":tahun",":ting",":kelas",":kodsek",":jpep");
			$valkdtspmc=array($tahun,$ting,$kelas,$kodsek,$jpep3);
			$bil_kdt_spmc = kira_bil_rekod($qrykdtspmc,$parkdtspmc,$valkdtspmc);
		 }
	}   

echo "  <tr bgcolor=\"#ff9900\">\n";
echo "   <td colspan='2'> KDT </td>";
echo "   <td colspan='2'><center>$kdt_u1 / $bil_kdt_u1</center></td>";
echo " 	 <td colspan='2'><center>$kdt_ppt / $bil_kdt_ppt</center></td>";
echo "   <td colspan='2'><center>$kdt_u2 / $bil_kdt_u2</center></td>";
echo "   <td colspan='2'><center>$kdt_pat / $bil_kdt_pat</center></td>";
echo "   <td colspan='2'><center>$kdt_spmc / $bil_kdt_spmc</center></td>";
echo "</tr>";
echo "  <tr bgcolor=\"#ff9900\">\n";
echo "   <td colspan='2'> KDK </td>";
echo "   <td colspan='2'><center>$kdk_u1 / $bil_kdk_u1</center></td>";
echo " 	 <td colspan='2'><center>$kdk_ppt / $bil_kdk_ppt</center></td>";
echo "   <td colspan='2'><center>$kdk_u2 / $bil_kdk_u2</center></td>";
echo "   <td colspan='2'><center>$kdk_pat / $bil_kdk_pat</center></td>";
echo "   <td colspan='2'><center>$kdk_spmc / $bil_kdk_spmc</center></td>";
echo "</tr>";

echo "  <tr bgcolor=\"#ff9900\">\n";
echo "   <td colspan='2'> PERATUS </td>";
echo "   <td colspan='2'><center>$peratus_u1</center></td>";
echo " 	 <td colspan='2'><center>$peratus_ppt</center></td>";
echo "   <td colspan='2'><center>$peratus_u2</center></td>";
echo "   <td colspan='2'><center>$peratus_pat</center></td>";
echo "   <td colspan='2'><center>$peratus_spmc</center></td>";
echo "</tr>";
  ?>

