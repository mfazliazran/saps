<?php 
//set_time_limit();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Pencapaian Kelas <?php echo "$gting $gkelas";?></p>
<?php
if($_SESSION['tahun']<>date("Y")){
	echo "Analisis Pencapaian Kelas ini hanya boleh melihat untuk tahun semasa sahaja.";
	die();
}
$q_guru = oci_parse($conn_sispa,"SELECT NOKP,NAMA,NAMASEK,KODSEK,TING,KELAS,NAMA FROM tguru_kelas WHERE tahun='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND nokp='".$_SESSION['nokp']."'");
oci_execute($q_guru);
$num=count_row("SELECT NOKP,NAMA,NAMASEK,KODSEK,TING,KELAS,NAMA FROM tguru_kelas WHERE tahun='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND nokp='".$_SESSION['nokp']."'");
while($row=oci_fetch_array($q_guru)){
$nokpg=$row["NOKP"];
$nama=$row["NAMA"]; 
$namasek=$row["NAMASEK"]; 
$kodsek=$row["KODSEK"];
$ting=$row["TING"]; 
$gting=strtoupper($row["TING"]); 
$kelas=$row["KELAS"];
$namagu=$row["NAMA"];
}
switch ($ting)
{
	case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
		$level="SR";
		break;
	case "P": case "T1": case "T2": case "T3":
		$level="MR";
		break;
	case "T4": case "T5":
		$level="MA";
		break;
}
$tahun = $_SESSION["tahun"];
////////////////////////////////mula level
if($level=="MA"){
$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h3><center>$namasek<br>ANALISA KEPUTUSAN MURID<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3><br>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "Guru Kelas : $namagu<br>Kelas : $ting $kelas ";
echo "<br><br>";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil MP<br>Ambil</td>\n";
echo "    <td colspan=\"11\"><div align=\"center\">Bilangan Gred </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Keputusan</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">GPC</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A+&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A-&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;B+&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;B&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;C+&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;C&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;D&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;E&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;G&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";
//echo "  <tr>\n";

$q_murid = oci_parse($conn_sispa,"SELECT NAMAP, NOKP,KELAS$gting FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' ORDER BY namap");
oci_execute($q_murid);
$bil_pel = count_row("SELECT NAMAP, NOKP,KELAS$gting FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' ORDER BY namap");
if($bil_pel>0){
	$stmt=oci_parse($conn_sispa,"DELETE FROM penilaian_muridsma WHERE tahun='$tahun' AND jpep='".$_SESSION['jpep']."' AND kodsek='$kodsek' AND ting='$ting' and kelas='$kelas'");
	oci_execute($stmt);	
}
while ($rowmurid=oci_fetch_array($q_murid)) {
	$bilmp=0;$bil1aa=$bil1a=$bil2a=$bil3b=$bil4b=$bil5c=$bil6c=$bil7d=$bil8e=$bil9g=$bilth=$peratus=$jum_mark=$gpc=$keputusan=$kategori=$pencapaian="";
	$nokp=$rowmurid["NOKP"];
	$qrykeputusan = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$ting' AND kelas='$kelas'");
	oci_execute($qrykeputusan);
	while ($rowmarkpel=oci_fetch_array($qrykeputusan)) {
		$qrysubam = oci_parse($conn_sispa,"SELECT kod FROM mpsmkc ORDER BY kod");
		oci_execute($qrysubam);
		while ($rowsubam=oci_fetch_array($qrysubam)){
			$kodmpam=$rowsubam["KOD"];
			$qrysub = oci_parse($conn_sispa,"SELECT kod FROM sub_ma_xambil WHERE kod='$kodmpam'");
			oci_execute($qrysub);
			$mpxambil=count_row("SELECT kod FROM sub_ma_xambil WHERE kod='$kodmpam'");

			if ($mpxambil==0){
				if ($rowmarkpel["$kodmpam"]!=""){
					$bilmp=$bilmp+1;
					$markah=$rowmarkpel["$kodmpam"]; $gred=trim($rowmarkpel["G$kodmpam"]);
					$jum_mark=$jum_mark+$markah;
					
					//if ((trim($rowmarkpel["GBM"])=="G") OR ($rowmarkpel["GBM"]=="TH") OR (trim($rowmarkpel["GBM"])=="")){
					
					if ((trim($rowmarkpel["GBMMA"])=="G") OR ($rowmarkpel["GBMMA"]=="TH") OR (trim($rowmarkpel["GSEJMA"])=="G") OR (trim($rowmarkpel["GSEJMA"])=="TH") OR (trim($rowmarkpel["GBIMA"])=="G") OR (trim($rowmarkpel["GBIMA"])=="TH")){//OR (trim($rowmarkpel["GSEJMA"])=="")OR (trim($rowmarkpel["GBMMA"])=="")
						$keputusan="GAGAL";
					} else { $keputusan="LULUS"; }
					
					switch ($gred){
						case "A+":$bil1aa=$bil1aa+1; break;
						case "A":$bil1a=$bil1a+1; break;
						case "A-":$bil2a=$bil2a+1; break;
						case "B+":$bil3b=$bil3b+1; break;
						case "B":$bil4b=$bil4b+1; break;
						case "C+":$bil5c=$bil5c+1; break;
						case "C":$bil6c=$bil6c+1; break;
						case "D":$bil7d=$bil7d+1; break;
						case "E":$bil8e=$bil8e+1; break;
						case "G":$bil9g=$bil9g+1; break;
						case "TH":$bilth=$bilth+1; break;
							
					}//end switch $gred MA
				}
			}
		}
	}
	if ($bilmp>=1){
		$peratus=number_format(($jum_mark/($bilmp*100))*100,'2','.',',');
		$gpc=number_format((($bil1aa*0)+($bil1a*1)+($bil2a*2)+($bil3b*3)+($bil4b*4)+($bil5c*5)+($bil6c*6)+($bil7d*7)+($bil8e*8)+($bil9g*9)+($bilth*9))/$bilmp,2,'.',',');
	}

	if($bil1aa != ''){$pencapaian = "".$bil1aa."[A+] ";}	
	if($bil1a != ''){$pencapaian = "".$pencapaian."".$bil1a."[A] ";}
	if($bil2a != ''){$pencapaian = "".$pencapaian."".$bil2a."[A-] ";}
	if($bil3b != ''){$pencapaian = "".$pencapaian."".$bil3b."[B+] ";}
	if($bil4b != ''){$pencapaian = "".$pencapaian."".$bil4b."[B] ";}
	if($bil5c != ''){$pencapaian = "".$pencapaian."".$bil5c."[C+] ";}
	if($bil6c != ''){$pencapaian = "".$pencapaian."".$bil6c."[C] ";}
	if($bil7d != ''){$pencapaian = "".$pencapaian."".$bil7d."[D] ";}
	if($bil8e != ''){$pencapaian = "".$pencapaian."".$bil8e."[E] ";}
	if($bil9g != ''){$pencapaian = "".$pencapaian."".$bil9g."[G] ";}
	if($bilth != ''){$pencapaian = "".$pencapaian."".$bilth."[TH] ";}
		
	$qpenilaian=oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsma WHERE nokp='$nokp' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='".$_SESSION['jpep']."'");
	oci_execute($qpenilaian);

	if (count_row("SELECT * FROM penilaian_muridsma WHERE nokp='$nokp' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='".$_SESSION['jpep']."'")!=0){
		$stmt=oci_parse($conn_sispa,"UPDATE penilaian_muridsma SET bilmp='$bilmp', bil1aa='$bil1aa', bil1a='$bil1a', bil2a='$bil2a', bil3b='$bil3b', bil4b='$bil4b', bil5c='$bil5c', bil6c='$bil6c', bil7d='$bil7d', bil8e='$bil8e', bil9g='$bil9g', bilth='$bilth', gpc='$gpc', jummark='$jum_mark', peratus='$peratus', keputusan='$keputusan', pencapaian='$pencapaian' WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
		oci_execute($stmt);
	} else { $stmt=oci_parse($conn_sispa,"INSERT INTO penilaian_muridsma(nokp, tahun, kodsek, ting, kelas, jpep, bilmp, bil1aa, bil1a, bil2a, bil3b, bil4b, bil5c, bil6c, bil7d, bil8e, bil9g, bilth, gpc, jummark, peratus, keputusan, pencapaian) VALUES ('$nokp','".$_SESSION['tahun']."','$kodsek','$ting','$kelas','".$_SESSION['jpep']."','$bilmp','$bil1aa','$bil1a','$bil2a','$bil3b','$bil4b','$bil5c','$bil6c','$bil7d','$bil8e','$bil9g','$bilth','$gpc','$jum_mark','$peratus','$keputusan','$pencapaian')");
	oci_execute($stmt);}
}
///////////////////// SUSUN KEDUDUKAN DALAM KELAS MA?//////////////////////////
$i=0;
$qrylulus = oci_parse($conn_sispa,"SELECT nokp, gpc, peratus, keputusan FROM penilaian_muridsma WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$ting' AND kelas='$kelas' ORDER BY keputusan Desc, gpc Asc, peratus Desc");
oci_execute($qrylulus);
$gpc = array('type' => 'float');
$peratus = array('type' => 'float');
while($rowlulus=oci_fetch_array($qrylulus))
{	
	$nokpmd[$i]=$rowlulus["NOKP"]; $gpc[$i]=$rowlulus["GPC"]; $peratus[$i]=$rowlulus["PERATUS"]; $keputusan[$i]=$rowlulus["KEPUTUSAN"];
	$i=$i+1;
}
$num=1; $bil=1;
for ($j=0; $j<$i; $j++){
	$kdk[$j]=$num;
	if (($gpc[$j]==$gpc[$j+1]) AND ($peratus[$j]==$peratus[$j+1])){
		$bil=$bil+1;
	} else { $num=$num+$bil; $bil=1; }
	$stmt=oci_parse($conn_sispa,"UPDATE  penilaian_muridsma SET kdk='$kdk[$j]' WHERE nokp='$nokpmd[$j]' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$ting' AND kelas='$kelas'");
	oci_execute($stmt);
}
///////////////////// TAMAT SUSUN KEDUDUKAN DALAM KELAS MA?//////////////////////////	
$bil=0; 
$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsma WHERE tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' ORDER BY keputusan Desc, gpc Asc, peratus Desc");
oci_execute($q_nilai);
while($rownilai = oci_fetch_array($q_nilai)){
	$q_murid = oci_parse($conn_sispa,"SELECT NAMAP, NOKP,KELAS$gting FROM tmurid WHERE nokp='$rownilai[NOKP]' AND kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek'");
	oci_execute($q_murid);
	$rowmurid=oci_fetch_array($q_murid);
	$namap = $rowmurid["NAMAP"];
	$kelas = $rowmurid["KELAS$gting"];
	
	if ($rownilai["GPC"] != 0){
		$bil=$bil+1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "  <tr bgcolor=\"$bcol\">\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$namap</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BILMP]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL1AA]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL1A]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL2A]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL3B]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL4B]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL5C]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL6C]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL7D]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL8E]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BIL9G]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BILTH]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[JUMMARK]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[PERATUS]</td>\n";
		echo "    <td><center>$rownilai[KEPUTUSAN]</td>\n";
		echo "    <td><center>&nbsp;&nbsp;&nbsp;$rownilai[GPC]&nbsp;&nbsp;&nbsp;</td>\n";
		echo "    <td><center>&nbsp;&nbsp;$rownilai[KDK]/$bil_pel&nbsp;&nbsp;</td>\n";
		echo "    <td>$rownilai[PENCAPAIAN]</td>\n";	
		echo "</tr>";
	}
}
echo "</table>\n";
}
////////////////////////////////////////////habis level
////////////////////////////////mula level
if($level=="MR"){
$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h3><center>$namasek<br>ANALISA KEPUTUSAN MURID<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3><br>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "Guru Kelas : $namagu<br>Kelas : $ting $kelas ";
echo "<br><br>";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\">Bil MP<br>Ambil</td>\n";
if($tahun<=2014){
	echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
}else{
	echo "    <td colspan=\"7\"><div align=\"center\">Bilangan Gred </div></td>\n";
}
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Keputusan</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">GPC</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;B&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;C&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;D&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;E&nbsp;&nbsp;</div></td>\n";
if($tahun>=2015){
	echo "    <td><div align=\"center\">&nbsp;&nbsp;F&nbsp;&nbsp;</div></td>\n";
}
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";
//echo "  <tr>\n";
///////////////////////////////////////////////////////////////
$q_murid = oci_parse($conn_sispa,"SELECT NAMAP, NOKP,KELAS$gting FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' ORDER BY namap");
oci_execute($q_murid);
$bil_pel = count_row("SELECT NAMAP, NOKP,KELAS$gting FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' ORDER BY namap");
if($bil_pel>0){
	$stmt=oci_parse($conn_sispa,"DELETE FROM penilaian_muridsmr WHERE tahun='$tahun' AND jpep='".$_SESSION['jpep']."' AND kodsek='$kodsek' AND ting='$ting' and kelas='$kelas'");
	oci_execute($stmt);	
}
while ($rowmurid=oci_fetch_array($q_murid)) {
$bilmp=0;$bila=$bilb=$bilc=$bild=$bile=$bilf=$bilth=$peratus=$jum_mark=$gpc=$keputusan=$kategori=$pencapaian="";
	$nokp=$rowmurid["NOKP"];
	$qrykeputusan = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$ting' AND kelas='$kelas'");
	oci_execute($qrykeputusan);
	while ($rowmarkpel=oci_fetch_array($qrykeputusan)) {
		$qrysub = oci_parse($conn_sispa,"SELECT kod FROM sub_mr");
		oci_execute($qrysub);
		while ($rowsub=oci_fetch_array($qrysub)) {
			$kodmp=$rowsub["KOD"];
			if ($rowmarkpel["$kodmp"]!=""){
				$bilmp=$bilmp+1;
				$markah=$rowmarkpel["$kodmp"]; $gred=trim($rowmarkpel["G$kodmp"]);
				$jum_mark=$jum_mark+$markah;
				
				switch ($gred){
					case "A": $bila=$bila+1; break;
					case "B": $bilb=$bilb+1; break;
					case "C": $bilc=$bilc+1; break;
					case "D": $bild=$bild+1; break;
					case "E": $bile=$bile+1; break;
					case "F": $bilf=$bilf+1; break;
					case "TH": $bilth=$bilth+1; break;
				}//end switch $gred MR
			}
		}
	}
	if ($bilmp>=1){
		$peratus=number_format(($jum_mark/($bilmp*100))*100,'2','.',',');
		if($tahun<=2014){
			$gpc=number_format((($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilth*5))/$bilmp,2,'.',',');
		}else{
			$gpc=number_format((($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilf*6)+($bilth*6))/$bilmp,2,'.',',');
		}
	}
	
	if($tahun<=2014){
		if ($bile+$bilth>=1){
			$keputusan="TIDAK MELEPASI TAHAP PENGUASAAN MINIMUM";//GAGAL
		} else { $keputusan="LULUS"; }
	}else{
		if ($bilf+$bilth>=1){
			$keputusan="TIDAK MELEPASI TAHAP PENGUASAAN MINIMUM";//GAGAL
		} else { $keputusan="LULUS"; }		
	}
	
	if($bila != ''){
		$pencapaian = "".$bila."A ";
		}
	if($bilb != ''){
		$pencapaian = "".$pencapaian."".$bilb."B ";
		}
	if($bilc != ''){
		$pencapaian = "".$pencapaian."".$bilc."C ";
		}
	if($bild != ''){
		$pencapaian = "".$pencapaian."".$bild."D ";
		}
	if($bile != ''){
		$pencapaian = "".$pencapaian."".$bile."E ";
		}
	if($bilf != ''){
		$pencapaian = "".$pencapaian."".$bilf."F ";
		}
	if($bilth != ''){
		$pencapaian = "".$pencapaian."".$bilth."TH ";
		}
		
	$qpenilaian=oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsmr WHERE nokp='$nokp' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='".$_SESSION['jpep']."'");
	oci_execute($qpenilaian);
	
    
	if (count_row("SELECT * FROM penilaian_muridsmr WHERE nokp='$nokp' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas' AND jpep='".$_SESSION['jpep']."'")!=0){
		$comp=oci_parse($conn_sispa,"UPDATE penilaian_muridsmr SET bilmp='$bilmp', bila='$bila', bilb='$bilb', bilc='$bilc', bild='$bild', bile='$bile', bilf='$bilf', bilth='$bilth', gpc='$gpc', jummark='$jum_mark', peratus='$peratus', keputusan='$keputusan', pencapaian='$pencapaian' WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$kelas'");
		oci_execute($comp);
		
	} else { $comp2=oci_parse($conn_sispa,"INSERT INTO penilaian_muridsmr(nokp, tahun, kodsek, ting, kelas, jpep, bilmp, bila, bilb, bilc, bild, bile, bilf, bilth, gpc, jummark, peratus, keputusan, pencapaian) VALUES ('$nokp','".$_SESSION['tahun']."','$kodsek','$ting', '$kelas','".$_SESSION['jpep']."','$bilmp','$bila','$bilb','$bilc','$bild','$bile','$bilf','$bilth','$gpc','$jum_mark','$peratus','$keputusan','$pencapaian')");
	oci_execute($comp2);
	}
}

///////////////////// SUSUN KEDUDUKAN DALAM KELAS MR?//////////////////////////
$i=0;
$qrylulus = oci_parse($conn_sispa,"SELECT nokp, gpc, peratus, keputusan FROM penilaian_muridsmr WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$gting' AND kelas='$gkelas' ORDER BY keputusan Asc, gpc ASC, peratus DESC");
oci_execute($qrylulus);
$gpc = array('type' => 'float');
$peratus = array('type' => 'float');
while($rowlulus=oci_fetch_array($qrylulus))
{	
	$nokpmd[$i]=$rowlulus["NOKP"]; $gpc[$i]=$rowlulus["GPC"]; $peratus[$i]=$rowlulus["PERATUS"]; $keputusan[$i]=$rowlulus["KEPUTUSAN"];
	$i=$i+1;
}
$num=1; $bil=1;
for ($j=0; $j<$i; $j++){
	$kdk[$j]=$num;
	if (($gpc[$j]==$gpc[$j+1]) AND ($peratus[$j]==$peratus[$j+1])){
		$bil=$bil+1;
	} else { $num=$num+$bil; $bil=1; }
$updte = oci_parse($conn_sispa,"UPDATE  penilaian_muridsmr SET kdk='$kdk[$j]' WHERE nokp='$nokpmd[$j]' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$ting' AND kelas='$kelas'");
oci_execute($updte);
}
///////////////////// TAMAT SUSUN KEDUDUKAN DALAM KELAS MR?//////////////////////////	

$bil=0; 
$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsmr WHERE tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' ORDER BY keputusan Asc, gpc Asc, peratus Desc");
oci_execute($q_nilai);

while($rownilai = oci_fetch_array($q_nilai)){
	$q_murid = oci_parse($conn_sispa,"SELECT NAMAP, NOKP,KELAS$gting FROM tmurid WHERE nokp='$rownilai[NOKP]' AND kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek'");
	oci_execute($q_murid);
	$rowmurid=oci_fetch_array($q_murid);
	$namap = $rowmurid["NAMAP"];
	$kelas = $rowmurid["KELAS$gting"];
	if ($rownilai["GPC"] != 0){
		$bil=$bil+1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "  <tr bgcolor=\"$bcol\">\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$namap</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BILMP]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BILA]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BILB]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BILC]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BILD]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[BILE]</td>\n";
		if($tahun>=2015){
			echo "    <td><center>&nbsp;$rownilai[BILF]</td>\n";
		}
		echo "    <td><center>&nbsp;$rownilai[BILTH]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[JUMMARK]</td>\n";
		echo "    <td><center>&nbsp;$rownilai[PERATUS]</td>\n";
		echo "    <td><center>$rownilai[KEPUTUSAN]</td>\n";
		echo "    <td><center>&nbsp;&nbsp;$rownilai[KDK]/$bil_pel&nbsp;&nbsp;</td>\n";
		echo "    <td><center>&nbsp;&nbsp;&nbsp;$rownilai[GPC]&nbsp;&nbsp;&nbsp;</td>\n";
		echo "    <td>$rownilai[PENCAPAIAN]</td>\n";
		echo "</tr>";
	}
}
echo "</table>\n";
}
////////////////////////////////////////////habis level
////////////////////////////////////////////mula level
if($level=="SR"){
$kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
echo "<h3><center>$namasek<br>ANALISA KEPUTUSAN MURID<br>".jpep("".$_SESSION['jpep']."")."<br>TAHUN ".$_SESSION['tahun']."</center></h3><br>";
echo "<table width=\"100%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "<b>Guru Kelas : $namagu<br>Kelas : $ting $kelas</b>";
echo "<br><br>";
echo "    <td rowspan=\"2\"><center>Bil</center></td>\n";
echo "    <td rowspan=\"2\">Nama Murid</td>\n";
echo "    <td rowspan=\"2\">Bil MP<br>Ambil</td>\n";
if($tahun!=2015){
	echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";
}else{//if($tahun==2015){
	if($ting=='D6'){
		echo "    <td colspan=\"6\"><div align=\"center\">Bilangan Gred </div></td>\n";	
	}else{
		echo "    <td colspan=\"7\"><div align=\"center\">Bilangan Gred </div></td>\n";
	}
}
echo "    <td rowspan=\"2\"><div align=\"center\">Jumlah<br>Markah </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Peratus</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Keputusan</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">KDK</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">GPC</div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Pencapaian</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;A&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;B&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;C&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;D&nbsp;&nbsp;</div></td>\n";
echo "    <td><div align=\"center\">&nbsp;&nbsp;E&nbsp;&nbsp;</div></td>\n";
if($tahun==2015){
	if($ting!='D6'){
		echo "    <td><div align=\"center\">&nbsp;&nbsp;F&nbsp;&nbsp;</div></td>\n";
	}
}
echo "    <td><div align=\"center\">&nbsp;&nbsp;TH&nbsp;&nbsp;</div></td>\n";
echo "  </tr>\n";
//echo "  <tr>\n";
///////////////////////////////////////////////////////
$q_murid = oci_parse($conn_sispa,"SELECT NAMAP, NOKP,KELAS$gting FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' ORDER BY namap");
oci_execute($q_murid);
$bil_pel = count_row("SELECT NAMAP, NOKP,KELAS$gting FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek' ORDER BY namap");
if($bil_pel>0){
	$stmt=oci_parse($conn_sispa,"DELETE FROM penilaian_muridsr WHERE tahun='$tahun' AND jpep='".$_SESSION['jpep']."' AND kodsek='$kodsek' AND darjah='$ting' and kelas='$kelas'");
	oci_execute($stmt);	
}
while ($rowmurid=oci_fetch_array($q_murid)) {
	$bilmp=0;$bila=$bilb=$bilc=$bild=$bile=$bilf=$bilth=$peratus=$jum_mark=$gpc=$keputusan=$kategori=$pencapaian="";
	$nokp=$rowmurid["NOKP"];
	$qrykeputusan = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND darjah='$ting' AND kelas='$kelas'");
	oci_execute($qrykeputusan);
	while ($rowmarkpel=oci_fetch_array($qrykeputusan)) {
		$qrysub = oci_parse($conn_sispa,"SELECT kod FROM sub_sr");
		oci_execute($qrysub);
		while ($rowsub=oci_fetch_array($qrysub)) {
			$kodmp=$rowsub["KOD"];
			if ($rowmarkpel["$kodmp"]!=""){
				$bilmp=$bilmp+1;
				$markah=$rowmarkpel["$kodmp"]; $gred=trim($rowmarkpel["G$kodmp"]);
				$jum_mark=$jum_mark+$markah;

				switch ($gred){
					case "A": $bila=$bila+1; break;
					case "B": $bilb=$bilb+1; break;
					case "C": $bilc=$bilc+1; break;
					case "D": $bild=$bild+1; break;
					case "E": $bile=$bile+1; break;
					case "F": $bilf=$bilf+1; break;
					case "TH": $bilth=$bilth+1; break;
				}//end switch $gred MR
			}
		}
	}
	if ($bilmp>=1){
		$peratus=number_format(($jum_mark/($bilmp*100))*100,'2','.',',');
		if($tahun<=2014){
			$jumgpc=($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilth*5);
			$gpc=(($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilth*5))/$bilmp;
		}elseif($tahun==2015){
			if($ting=='D6'){
				$jumgpc=($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilth*5);
				$gpc=(($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilth*5))/$bilmp;
			}else{
				$jumgpc=($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilf*6)+($bilth*6);
				$gpc=(($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilf*6)+($bilth*6))/$bilmp;	
			}
		}else{//2016 D1-D6
			$jumgpc=($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilf*6)+($bilth*6);
			$gpc=(($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilf*6)+($bilth*6))/$bilmp;	
		}
	}
	
	if($tahun<=2014){//D1-D6
			if ($bild+$bile+$bilth>=1){ 
				$keputusan="GAGAL"; 
				$susunakLG = "B";
			} else { 
				$keputusan="LULUS"; 
				$susunakLG = "A";
			}
		}else if($tahun==2015){//2015
			if($ting=='D6'){
				if ($bild+$bile+$bilth>=1){ 
					$keputusan="GAGAL"; 
					$susunakLG = "B";
				} else { 
					$keputusan="LULUS"; 
					$susunakLG = "A";
				}
			}else{//D1-D5
				if ($bilf+$bilth>=1){ 
					$keputusan="GAGAL"; 
					$susunakLG = "B";
				} else { 
					$keputusan="LULUS";
					$susunakLG = "A"; 
				}
			}
		}else{//2016 D1-D6
			//if ($bilf+$bilth>=1){ $keputusan="GAGAL"; } else { $keputusan="LULUS"; }
			if ($bile>0 or $bilth>0){ 
				$keputusan="BELUM MENCAPAI TAHAP MINIMUM";
				$susunakLG = "E";
			}else if ($bild>0){
				$keputusan="MENCAPAI TAHAP MINIMUM";
				$susunakLG = "D";
			}else if ($bilc>0){ 
				$keputusan="MEMUASKAN";
				$susunakLG = "C";
			}else if ($bilb>0){
				$keputusan="BAIK";
				$susunakLG = "B";
			}else{
				$keputusan="CEMERLANG";
				$susunakLG = "A";
			}
		}	

	if($bila != ''){
		$pencapaian = "".$bila."A ";
		}
	if($bilb != ''){
		$pencapaian = "".$pencapaian."".$bilb."B ";
		}
	if($bilc != ''){
		$pencapaian = "".$pencapaian."".$bilc."C ";
		}
	if($bild != ''){
		$pencapaian = "".$pencapaian."".$bild."D ";
		}
	if($bile != ''){
		$pencapaian = "".$pencapaian."".$bile."E ";
		}
	if($bilf != ''){
		$pencapaian = "".$pencapaian."".$bilf."F ";
		}
	if($bilth != ''){
		$pencapaian = "".$pencapaian."".$bilth."TH ";
		}
	$qpenilaian=oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsr WHERE nokp='$nokp' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='".$_SESSION['jpep']."'");
	oci_execute($qpenilaian);

	if (count_row("SELECT * FROM penilaian_muridsr WHERE nokp='$nokp' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas' AND jpep='".$_SESSION['jpep']."'")!=0){
			$insrt2 = oci_parse($conn_sispa,"UPDATE penilaian_muridsr SET bilmp='$bilmp', bila='$bila', bilb='$bilb', bilc='$bilc', bild='$bild', bile='$bile', bilf='$bilf', bilth='$bilth', gpc='$gpc', jummark='$jum_mark', peratus='$peratus', keputusan='$keputusan', pencapaian='$pencapaian' WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND darjah='$ting' AND kelas='$kelas'");
		    oci_execute($insrt2);
	} else { $insrt = oci_parse($conn_sispa,"INSERT INTO penilaian_muridsr (nokp, tahun, kodsek, darjah, kelas, jpep, bilmp, bila, bilb, bilc, bild, bile, bilf, bilth, gpc, jummark, peratus, keputusan, pencapaian) VALUES ('$nokp','".$_SESSION['tahun']."','$kodsek','$ting', '$kelas','".$_SESSION['jpep']."','$bilmp','$bila','$bilb','$bilc','$bild','$bile','$bilf','$bilth','$gpc','$jum_mark','$peratus','$keputusan','$pencapaian')"); 
	          oci_execute($insrt);
      }
}

///////////////////// SUSUN KEDUDUKAN DALAM KELAS SR?//////////////////////////
$i=0;
$qrylulus = oci_parse($conn_sispa,"SELECT nokp, gpc, peratus, keputusan FROM penilaian_muridsr WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND darjah='$ting' AND kelas='$kelas' Order By Case keputusan When 'CEMERLANG' Then 1
    When 'BAIK' Then 2
    When 'MEMUASKAN' Then 3
	When 'MENCAPAI TAHAP MINIMUM' Then 4
	When 'BELUM MENCAPAI TAHAP MINIMUM' Then 5
	When 'Lulus' Then 6
    Else 7 End, gpc ASC, peratus DESC");//ORDER BY gpc ASC, peratus DESC, keputusan DESC");

oci_execute($qrylulus);
$gpc = array('type' => 'float');
$peratus = array('type' => 'float');
$i=0;
while($rowlulus=oci_fetch_array($qrylulus))
{	
	$nokpmd[$i]=$rowlulus["NOKP"]; $gpc[$i]=$rowlulus["GPC"]; $peratus[$i]=$rowlulus["PERATUS"]; $keputusan[$i]=$rowlulus["KEPUTUSAN"];
	$i=$i+1;
}
$num=1; $bil=1;
for ($j=0; $j<$i; $j++){
	$kdk[$j]=$num;
	if (($gpc[$j]==$gpc[$j+1]) AND ($peratus[$j]==$peratus[$j+1])){
		$bil=$bil+1;
	} else { $num=$num+$bil; $bil=1; }	
	$sis=oci_parse($conn_sispa,"UPDATE  penilaian_muridsr SET kdk='$kdk[$j]' WHERE nokp='$nokpmd[$j]' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND darjah='$ting' AND kelas='$kelas'");
	oci_execute($sis);
}

///////////////////// Tamat SUSUN KEDUDUKAN DALAM KELAS SR?//////////////////////////	
$bil=0; 

$q_nilai = oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsr WHERE tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' Order By Case keputusan When 'CEMERLANG' Then 1
    When 'BAIK' Then 2
    When 'MEMUASKAN' Then 3
	When 'MENCAPAI TAHAP MINIMUM' Then 4
	When 'BELUM MENCAPAI TAHAP MINIMUM' Then 5
	When 'Lulus' Then 6
    Else 7 End, gpc ASC, peratus DESC");//ORDER BY gpc Asc, peratus Desc, keputusan Desc");
oci_execute($q_nilai);

while($rownilai = oci_fetch_array($q_nilai)){
	$q_murid = oci_parse($conn_sispa,"SELECT NAMAP, NOKP,KELAS$gting FROM tmuridsr WHERE nokp='$rownilai[NOKP]' AND kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas' and kodsek_semasa='$kodsek'");
    oci_execute($q_murid);
	$rowmurid=OCI_fetch_array($q_murid);
	$namap = $rowmurid["NAMAP"];
	$kelas = $rowmurid["KELAS$gting"];

	if ($rownilai["GPC"] != 0){
		$bil=$bil+1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "  <tr bgcolor=\"$bcol\">\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$namap</td>\n";
		echo "    <td><center>&nbsp;".$rownilai["BILMP"]."</td>\n";
		echo "    <td><center>&nbsp;".$rownilai["BILA"]."</td>\n";
		echo "    <td><center>&nbsp;".$rownilai["BILB"]."</td>\n";
		echo "    <td><center>&nbsp;".$rownilai["BILC"]."</td>\n";
		echo "    <td><center>&nbsp;".$rownilai["BILD"]."</td>\n";
		echo "    <td><center>&nbsp;".$rownilai["BILE"]."</td>\n";
		if($tahun==2015){
			if($ting!='D6'){
				echo "    <td><center>&nbsp;".$rownilai["BILF"]."</td>\n";
			}				
		}
		echo "    <td><center>&nbsp;".$rownilai["BILTH"]."</td>\n";
		echo "    <td><center>&nbsp;".$rownilai["JUMMARK"]."</td>\n";
		echo "    <td><center>&nbsp;".$rownilai["PERATUS"]."</td>\n";
		echo "    <td><center>".$rownilai["KEPUTUSAN"]."</td>\n";
		echo "    <td><center>&nbsp;&nbsp;".$rownilai["KDK"]."/$bil_pel &nbsp;&nbsp;</td>\n";
		echo "    <td><center>&nbsp;&nbsp;&nbsp;".$rownilai["GPC"]."&nbsp;&nbsp;&nbsp;</td>\n";
		echo "    <td>".$rownilai["PENCAPAIAN"]."</td>\n";
		echo "</tr>";
	}
}
echo "</table>\n";
}
////////////////////////////////////////////habis 
?>
</td>
<?php include 'kaki.php';?> 