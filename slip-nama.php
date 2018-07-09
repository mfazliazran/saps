<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
//CA16111301
if(!isset($lencana)){$lencana="";}
if(!isset($jpep)){$jpep="";}
if(!isset($ting)){$ting="";}
if(!isset($kelas)){$kelas="";}
//CA16111301

?>



<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=1000height=800,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-500,screen.height/2-400);
}
</script>

<td valign="top" class="rightColumn">
<p class="subHeader">Slip Peperiksaan Kelas <?php echo "$gting $gkelas";?></p>
<?php
$count=count_row("select NOKP from tguru_kelas where NOKP='".$_SESSION["nokp"]."' and Tahun='".$_SESSION["tahun"]."'");
if ($count==0){
 die("Anda belum dilantik sebagai guru kelas pada tahun ".$_SESSION["tahun"]);
}

?>
<?php
$tkt=array("P" => "PERALIHAN",
		   "T1" => "TINGKATAN SATU",
		   "T2" => "TINGKATAN DUA",
		   "T3" => "TINGKATAN TIGA",
		   "T4" => "TINGKATAN EMPAT",
		   "T5" => "TINGKATAN LIMA",
		   "D1" => "TAHUN SATU",
		   "D2" => "TAHUN DUA",
		   "D3" => "TAHUN TIGA",
		   "D4" => "TAHUN EMPAT",
		   "D5" => "TAHUN LIMA",
		   "D6" => "TAHUN ENAM");
switch ($gting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$level="SR";
			$semakting = 1;
			break;
		case "P": case "T1": case "T2": case "T3":
			$level="MR";
			$tpenilaian = "penilaian_muridsmr";
			$semakting = 2;
			break;
		case "T4": case "T5":
			$level="MA";
			$tpenilaian = "penilaian_muridsmr";	
			$semakting = 3;
			break;
	}
//echo "<br>";
//echo "<center><h3>SLIP PEPERIKSAAN $tkt[$gting]<br>$gting $gkelas</h3></center>";
echo "<h3><center>$namasek<br>SLIP PEPERIKSAAN KELAS<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3><br>";
echo "<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"6\" cellspacing=\"0\">\n";
echo "  <tr>\n";
//echo "<th width=\"800\" scope=\"col\"><center><a href=slip-all.php?data=".$kodsek."|".$gting."|".$gkelas."|".$_SESSION['tahun']."|".$_SESSION['jpep']." target=_blank><img src = images/printer.png border=\"0\">&nbsp;Cetak Semua</a></center></th>\n";
if($semakting == 2)
	echo "<th width=\"800\" scope=\"col\"><center><a href=slip-allmr.php?data=".$gting."|".$gkelas." target=_blank><img src = images/printer.png border=\"0\">&nbsp;Cetak Semua</a></center></th>\n";
else
	echo "<th width=\"800\" scope=\"col\"><center><a href=slip-all.php?data=".$gting."|".$gkelas." target=_blank><img src = images/printer.png border=\"0\">&nbsp;Cetak Semua</a></center></th>\n";
echo "  <tr>\n";
echo "</table>";
echo "<table width=\"800\" border=\"1\" align=\"center\" cellpadding=\"6\" cellspacing=\"0\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <th scope=\"col\">BIL</th>\n";
echo "    <th scope=\"col\">NOKP</th>\n";
echo "    <th scope=\"col\">NAMA</th>\n";
echo "    <th scope=\"col\">JANTINA</th>\n";
echo "    <th scope=\"col\">CETAK <br>SLIP</th>\n";
echo "    <th scope=\"col\">EXPORT <br>KE EXCEL</th>\n";
echo "	<th scope=\"col\">KEHADIRAN</th>\n";
echo "	<th scope=\"col\">ULASAN</th>\n";
echo "  </tr>\n";

/*
if($level=="SR"){
	
	$q_murid = oci_parse($conn_sispa,"SELECT NOKP FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$gting' AND kelas$gting='$gkelas' ORDER BY namap");

	oci_execute($q_murid);
	
	while ($rowmurid=oci_fetch_array($q_murid)) {
		$bilmp=0;$bila=$bilb=$bilc=$bild=$bile=$bilth=$peratus=$jum_mark=$gpc=$keputusan=$kategori=$pencapaian="";
		$nokp=$rowmurid["NOKP"];
		$qrykeputusan = oci_parse($conn_sispa,"SELECT * FROM markah_pelajarsr WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND darjah='$gting' AND kelas='$gkelas'");
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
						case "TH": $bilth=$bilth+1; break;
					}//end switch $gred MR
				}
			}
		}
		if ($bilmp>=1){
			$peratus=number_format(($jum_mark/($bilmp*100))*100,'2','.',',');
			$gpc=number_format((($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilth*5))/$bilmp,2,'.',',');
		}
	
		if ($bile+$bilth+$bild>=1){
			$keputusan="GAGAL";
		} else { $keputusan="LULUS"; }

		if($bila != ''){ $pencapaian = "".$bila."A ";}
		if($bilb != ''){ $pencapaian = "".$pencapaian."".$bilb."B "; }
		if($bilc != ''){ $pencapaian = "".$pencapaian."".$bilc."C "; }
		if($bild != ''){ $pencapaian = "".$pencapaian."".$bild."D "; }
		if($bile != ''){ $pencapaian = "".$pencapaian."".$bile."E "; }
		if($bilth != ''){ $pencapaian = "".$pencapaian."".$bilth."TH "; }
			
		$qpenilaian=oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsr WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."'  AND jpep='".$_SESSION['jpep']."' AND darjah='$gting' AND kelas='$gkelas'");
		oci_execute($qpenilaian);
		if (count_row("SELECT * FROM penilaian_muridsr WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."'  AND jpep='".$_SESSION['jpep']."' AND darjah='$gting' AND kelas='$gkelas'")!=0){
	
			$stmt=oci_parse($conn_sispa,"UPDATE penilaian_muridsr SET bilmp='$bilmp', bila='$bila', bilb='$bilb', bilc='$bilc', bild='$bild', bile='$bile', bilth='$bilth', gpc='$gpc', jummark='$jum_mark', peratus='$peratus', keputusan='$keputusan', pencapaian='$pencapaian' WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND darjah='$gting' AND kelas='$gkelas'")or die("UPDATE penilaian_muridsr: ".oci_error());
oci_execute($stmt);			
	
		} else { $suhu=oci_parse($conn_sispa,"INSERT INTO penilaian_muridsr(nokp, tahun, kodsek, darjah, kelas, jpep, bilmp, bila, bilb, bilc, bild, bile, bilth, gpc, jummark, peratus, keputusan, pencapaian) VALUES ('$nokp','".$_SESSION['tahun']."','$kodsek','$gting', '$gkelas','".$_SESSION['jpep']."','$bilmp','$bila','$bilb','$bilc','$bild','$bile','$bilth','$gpc','$jum_mark','$peratus','$keputusan','$pencapaian')"); 
		
		oci_execute($suhu);}
	}
///////////////////// SUSUN KEDUDUKAN DALAM KELAS SR?//////////////////////////
	$i=0;
	$qrylulus = oci_parse($conn_sispa,"SELECT nokp, gpc, peratus, keputusan FROM penilaian_muridsr WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND darjah='$gting' AND kelas='$gkelas' ORDER BY keputusan DESC, gpc ASC, peratus DESC");
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
		$stm=oci_parse($conn_sispa,"UPDATE  penilaian_muridsr SET kdk='$kdk[$j]' WHERE nokp='$nokpmd[$j]' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND darjah='$ting' AND kelas='$kelas'");
		oci_execute($stm);
	}
///////////////////// Tamat SUSUN KEDUDUKAN DALAM KELAS SR?//////////////////////////	
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($level=="MR"){

	//$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$gting' AND kelas$gting='$gkelas' ORDER BY namap"); // TAK INDEX
	$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND $gting='$gting' AND kelas$gting='$gkelas' AND tahun$gting='".$_SESSION['tahun']."'  ORDER BY namap");

//echo "SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$gting' AND kelas$gting='$gkelas' ORDER BY namap";
	oci_execute($q_murid);
	$gpc = array('type' => 'float');
	$peratus = array('type' => 'float');
	while ($rowmurid=oci_fetch_array($q_murid)) {
		$bilmp=0; $bila=$bilb=$bilc=$bild=$bile=$bilth=$peratus=$jum_mark=$gpc=$keputusan=$kategori=$pencapaian="";
		$nokp=$rowmurid["NOKP"];
		//$qrykeputusan = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$gting' AND kelas='$gkelas'"); // TAK INDEX
		$qrykeputusan = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' 		AND ting='$gting' AND kelas='$gkelas' AND jpep='".$_SESSION['jpep']."'");

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
						case "TH": $bilth=$bilth+1; break;
					}//end switch $gred MR
				}
			}
		}
		if ($bilmp>=1){	
			$peratus=number_format(($jum_mark/($bilmp*100))*100,'2','.',',');
			$gpc=number_format((($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilth*5))/$bilmp,2,'.',',');	
		}
		if ($bile+$bilth>=1){	
			$keputusan="GAGAL";	
		} else { $keputusan="LULUS"; }
	
		if($bila != ''){ $pencapaian = "".$bila."A ";}
		if($bilb != ''){ $pencapaian = "".$pencapaian."".$bilb."B "; }
		if($bilc != ''){ $pencapaian = "".$pencapaian."".$bilc."C "; }
		if($bild != ''){ $pencapaian = "".$pencapaian."".$bild."D "; }
		if($bile != ''){ $pencapaian = "".$pencapaian."".$bile."E "; }
		if($bilth != ''){ $pencapaian = "".$pencapaian."".$bilth."TH "; }

		$qpenilaian=oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsmr WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas'");
		oci_execute($qpenilaian);
		if (count_row("SELECT * FROM penilaian_muridsmr WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas'")!=0){
	
			$syg=oci_parse($conn_sispa,"UPDATE penilaian_muridsmr SET bilmp='$bilmp', bila='$bila', bilb='$bilb', bilc='$bilc', bild='$bild', bile='$bile', bilth='$bilth', gpc='$gpc', jummark='$jum_mark', peratus='$peratus', keputusan='$keputusan', pencapaian='$pencapaian' WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas'");
			oci_execute($syg);
	
		} else { $bby=oci_parse($conn_sispa,"INSERT INTO penilaian_muridsmr(nokp, tahun, kodsek, ting, kelas, jpep, bilmp, bila, bilb, bilc, bild, bile, bilth, gpc, jummark, peratus, keputusan, pencapaian) VALUES ('$nokp','".$_SESSION['tahun']."','$kodsek','$gting', '$gkelas','".$_SESSION['jpep']."','$bilmp','$bila','$bilb','$bilc','$bild','$bile','$bilth','$gpc','$jum_mark','$peratus','$keputusan','$pencapaian')");
		oci_execute($bby);}
	}
///////////////////// SUSUN KEDUDUKAN DALAM KELAS MR?//////////////////////////
	$i=0;
	$qrylulus = oci_parse($conn_sispa,"SELECT nokp, gpc, peratus, keputusan FROM penilaian_muridsmr WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$gting' AND kelas='$gkelas' ORDER BY keputusan DESC, gpc ASC, peratus DESC");
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
		$cinta=oci_parse($conn_sispa,"UPDATE  penilaian_muridsmr SET kdk='$kdk[$j]' WHERE nokp='$nokpmd[$j]' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas'");
		oci_execute($cinta);
	}
///////////////////// TAMAT SUSUN KEDUDUKAN DALAM KELAS MR?//////////////////////////	
}
///////////////////////////////////////////////// BLOK MENENGAH ATAS /////////////////////////////////////////////
if($level=="MA"){
	$q_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND $gting='$gting' AND kelas$gting='$gkelas' AND tahun$gting='".$_SESSION['tahun']."'  ORDER BY namap");

	oci_execute($q_murid);
	while ($rowmurid=oci_fetch_array($q_murid)) {
		$bilmp=0;$bil1aa=$bil1a=$bil2a=$bil3b=$bil4b=$bil5c=$bil6c=$bil7d=$bil8e=$bil9g=$bilth=$peratus=$jum_mark=$gpc=$keputusan=$kategori=$pencapaian="";
		$nokp=$rowmurid["NOKP"];
		$qrykeputusan = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar WHERE nokp='$nokp' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas' AND jpep='".$_SESSION['jpep']."'");

oci_execute($qrykeputusan);
		while ($rowmarkpel=oci_fetch_array($qrykeputusan)) {
			$qrysubam = oci_parse($conn_sispa,"SELECT kod FROM mpsmkc ORDER BY kod");
			oci_execute($qrysubam);
	//		$qrysubam = mysql_query("SELECT DISTINCT kodmp FROM sub_guru WHERE kodsek='".$_SESSION['kodsek']."' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp");
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
						
						if (($rowmarkpel["GBM"]=="G") OR ($rowmarkpel["GBM"]=="TH") OR ($rowmarkpel["GBM"]=="")){
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

		$qpenilaian=oci_parse($conn_sispa,"SELECT * FROM penilaian_muridsma WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas'");
		//oci_execute($qpenilaian);
		if (count_row("SELECT * FROM penilaian_muridsma WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas'")!=0){
			$name=oci_parse($conn_sispa,"UPDATE penilaian_muridsma SET bilmp='$bilmp', bil1aa='$bil1aa', bil1a='$bil1a', bil2a='$bil2a', bil3b='$bil3b', bil4b='$bil4b', bil5c='$bil5c', bil6c='$bil6c', bil7d='$bil7d', bil8e='$bil8e', bil9g='$bil9g', bilth='$bilth', gpc='$gpc', jummark='$jum_mark', peratus='$peratus', keputusan='$keputusan', pencapaian='$pencapaian' WHERE nokp='$nokp' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas'");
			oci_execute($name);
		} else { $hp=oci_parse($conn_sispa,"INSERT INTO penilaian_muridsma(nokp, tahun, kodsek, ting, kelas, jpep, bilmp, bil1aa, bil1a, bil2a, bil3b, bil4b, bil5c, bil6c, bil7d, bil8e, bil9g, bilth, gpc, jummark, peratus, keputusan, pencapaian) VALUES ('$nokp','".$_SESSION['tahun']."','$kodsek','$gting','$gkelas','".$_SESSION['jpep']."','$bilmp', '$bil1aa','$bil1a','$bil2a','$bil3b','$bil4b','$bil5c','$bil6c','$bil7d','$bil8e','$bil9g','$bilth','$gpc','$jum_mark','$peratus','$keputusan','$pencapaian')");
		oci_execute($hp);}

}
///////////////////// SUSUN KEDUDUKAN DALAM KELAS MA?//////////////////////////
	$i=0;
	$qrylulus = oci_parse($conn_sispa,"SELECT nokp, gpc, peratus, keputusan FROM penilaian_muridsma WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND ting='$gting' AND kelas='$gkelas' ORDER BY keputusan DESC, gpc ASC, peratus DESC");
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
		$huhu=oci_parse($conn_sispa,"UPDATE  penilaian_muridsma SET kdk='$kdk[$i]' WHERE nokp='$nokpmd[$i]' AND jpep='".$_SESSION['jpep']."' AND tahun='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$gting' AND kelas='$gkelas'");
		oci_execute($huhu);
	}
///////////////////// TAMAT SUSUN KEDUDUKAN DALAM KELAS MA?//////////////////////////	
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//echo "level:$level";
*/
if(($level=="MR") OR ($level=="MA")){
	$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'"; 
	$query = "SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND $gting='$gting' AND kelas$gting='$gkelas' AND tahun$gting='".$_SESSION['tahun']."' and kodsek_semasa='$kodsek' ORDER BY namap"; 
	$table = "markah_pelajar";
	$da = "TING";
	//if($kodsek='BEB0110')
		//die($query);
	}
	
if($level=="SR"){
	//$kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	$query = "SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND $gting='$gting' AND kelas$gting='$gkelas' AND tahun$gting='".$_SESSION['tahun']."' and kodsek_semasa='$kodsek' ORDER BY namap"; 
	$table = "markah_pelajarsr";
	$da = "DARJAH";
	//die($query);
	}
		
$result = oci_parse($conn_sispa,$query);
oci_execute($result);
$bil=0;
while ($row = oci_fetch_array($result))
{
	$nokp = $row["NOKP"];
	$nama = $row["NAMAP"];
	$jantina = $row["JANTINA"];
	$q_slip = oci_parse($conn_sispa,"SELECT KEHADIRAN,KEHADIRANPENUH FROM $table WHERE nokp='$nokp' and kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND $da='$gting' and jpep='".$_SESSION['jpep']."' AND kelas='$gkelas' ORDER BY nama");
	oci_execute($q_slip);
	$rows = oci_fetch_array($q_slip);
	$kehadiran = $rows["KEHADIRAN"];
	$kehadiranpenuh = $rows["KEHADIRANPENUH"];
	
	$m="$nokp|$kodsek|$gting|$gkelas|$tahun|$jpep";
	$bil=$bil+1;
	if($bil&1) {
		$bcol = "#CDCDCD";
	} else {
		$bcol = "";
	}
	echo "<tr bgcolor='$bcol'>\n";
	echo "<td><center>$bil</center></td>\n";
	echo "<td>$nokp</a></td>\n";
	echo "<td>$nama</a></td>\n";
	echo "<td><center>$jantina</center></td>\n";
	//echo "<td><a href=slip.php?data=".urlencode($nokp)."|".$kodsek."|".$gting."|".urlencode($gkelas)."|".$_SESSION['tahun']."|".$_SESSION['jpep']." target=_blank><center><img src = images/printer.png width=13 height=13 Alt=\"Cetak\" border=0></center></a></td>\n";
	if($semakting==2)
		echo "<td><a href=slipmr.php?data=".urlencode($nokp)."|".$gting."|".urlencode($gkelas)." target=_blank><center><img src = images/printer.png width=13 height=13 Alt=\"Cetak\" border=0></center></a></td>\n";
	else
		echo "<td><a href=slip.php?data=".urlencode($nokp)."|".$gting."|".urlencode($gkelas)." target=_blank><center><img src = images/printer.png width=13 height=13 Alt=\"Cetak\" border=0></center></a></td>\n";
	echo "<td><a href=slipmuridmr_excel.php?data=".urlencode($nokp)."|".$kodsek."|".$ting."|".urlencode($kelas)."|".$tahun."|".$jpep."|".$lencana." target=_blank><center><img src = images/printer.png width=13 height=13 Alt=\"Cetak\" border=0></center></a></td>\n";
	echo "<td align='center'>$kehadiran / $kehadiranpenuh</td>\n";
	echo "    <td><a href=edit_ulasan.php?data=".urlencode($nokp)."|".$kodsek."|".$gting."|".urlencode($gkelas)."|".$_SESSION['tahun']."|".$_SESSION['jpep']."><center><img src = images/edit.png width=12 height=13 Alt=\"Sunting\" border=0></center></a></td>\n";
		
}
echo "</table>\n";
?>
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('slipallmurid_excel.php?data=<?php echo $m;?>','win1');" /></center>
<?php
echo "<br><br><br>";
?>
</td>
<?php include 'kaki.php';?> 