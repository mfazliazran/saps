<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Proses Analisis Peperiksaan</p>

<?php

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
//die($ting);
$gting = strtoupper($ting);
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];

$qppd = oci_parse($conn_sispa,"SELECT kodppd, negeri FROM tsekolah WHERE kodsek='$kodsek'");
oci_execute($qppd);
$rowppd = oci_fetch_array($qppd);
$kodppd = $rowppd['KODPPD'];
$negeri = $rowppd['NEGERI'];

/////////////////////////////////////////////////////////// koding baru //////////////////////////////////////////////////////////
//Senarai kelas yang ada dalam sekolah
$qrykelas = oci_parse($conn_sispa,"SELECT kelas FROM tkelassek WHERE kodsek='$kodsek' AND tahun='$tahun' AND ting='$ting' ORDER BY kelas");
oci_execute($qrykelas);
$bilkelas = 0;
while ($rowkelas = oci_fetch_array($qrykelas)) {
	$kelas11[] = $rowkelas;
	$bilkelas++;
}

$qrymp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru WHERE kodsek='$kodsek' AND tahun='$tahun' AND ting='$ting' 
 ORDER BY kodmp"); //sepatutnya ni amik semua subjek
oci_execute($qrymp);
//Subjek yang diambil dalam sekolah
$bilsub = 0;
while ($rowmp = oci_fetch_array($qrymp)) {
	$mpsek[] = $rowmp;
	$bilsub++;
	$qry_subj.=",".$rowmp["KODMP"];
    $qry_subj.=",G".$rowmp["KODMP"];
}

//Subjek yang dikira dalam penilian
$qrympkira = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru WHERE kodsek='$kodsek' AND tahun='$tahun' AND ting='$ting' 
 minus select kod from sub_ma_xambil					   
 ORDER BY kodmp");
oci_execute($qrympkira);
$i=0;
while ($rowmpkira = oci_fetch_array($qrympkira)) {
	$mpkira[] = $rowmpkira;
	$i++;
}

/*//Subjek yang tak dikira dalam penilian
$qrympxkira = oci_parse($conn_sispa,"SELECT * FROM sub_ma_xambil ORDER BY kod");
oci_execute($qrympxkira);
$i=0;
while ($rowmpxkira = oci_fetch_array($qrympxkira)) {
	$mpxkira[] = $rowmpxkira;
	$i++;
}
*/
if($tahun=='2011'){
$qrykeputusan = oci_parse($conn_sispa,"SELECT m.nokp,m.nama,m.kelas $qry_subj FROM markah_pelajar m, tmurid tm  WHERE m.kodsek='$kodsek' AND m.tahun='$tahun' AND m.jpep='$jpep' AND m.ting='$ting' AND tm.$gting='$ting' AND m.nokp=tm.nokp ORDER BY kelas");//tm.kelas$gting");	
}else{
$qrykeputusan = oci_parse($conn_sispa,"SELECT m.nokp,m.nama,m.kelas $qry_subj FROM markah_pelajar m, tmurid tm  WHERE m.kodsek='$kodsek' AND m.tahun='$tahun' AND m.jpep='$jpep' AND m.ting='$ting' AND tm.$gting='$ting' AND tm.tahun$gting='$tahun' AND m.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY kelas");//tm.kelas$gting");
}
//if($kodsek=='AEE1038')
	//die("SELECT m.nokp,m.nama,m.kelas $qry_subj FROM markah_pelajar m, tmurid tm  WHERE m.kodsek='$kodsek' AND m.tahun='$tahun' AND m.jpep='$jpep' AND m.ting='$ting' AND tm.$gting='$ting' AND tm.tahun$gting AND m.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY kelas");
oci_execute($qrykeputusan);
$i=0;
while ($rownilai = oci_fetch_array($qrykeputusan)) {
	$markah[] = $rownilai;
	$i++;
}

if (!empty($markah))
{

	$databilgred = bilangred($markah,  $mpsek);	
	
	/////////////////////////////////////////// Proses penilaian murid //////////////////////////////////////
	$bil = 0;
	for ($k=0; $k<$i; $k++){
		$bilmp =0; $bilap = $bila = $bilam = $bilbp = $bilb = $bilcp = $bilc = $bild = $bile = $bilg = $bilth = "" ;
		$peratus = $jum_mark = $gpc = $keputusan = $kategori = $pencapaian = "";
	
		$bil++;
		$nokp = $markah[$k]['NOKP'];	
		$nama = $markah[$k]['NAMA'];
		$kelas = $markah[$k]['KELAS'];
	
		for ($j=0; $j<=$bilsub; $j++){
			$subjek = $mpsek[$j]['KODMP'];	
			//if (not_in_multiarray($subjek, $mpxkira)){
			if (subjek_dikira($subjek)){	
			   // die("subjek dikira:$subjek");
				if ($markah[$k]["$subjek"]!=""){
					$bilmp=$bilmp+1;
					$mark = $markah[$k]["$subjek"]; 
					$gred = trim($markah[$k]["G$subjek"]);
					$jum_mark = $jum_mark + $mark;
					//if ((trim($markah[$k]["GBM"])=="G") OR (trim($markah[$k]["GBM"])=="TH") OR (trim($markah[$k]["GBM"])==""))
						/*if ((trim($markah[$k]["GBMMA"])=="G") OR (trim($markah[$k]["GBMMA"])=="TH") OR (trim($markah[$k]["GBMMA"])==""))
							$keputusan="GAGAL";
						else  
							$keputusan="LULUS";*/
					if($ting=='T5'){
						if ((trim($markah[$k]["GBMMA"])=="G") OR (trim($markah[$k]["GBMMA"])=="TH") OR (trim($markah[$k]["GSEJMA"])=="G") OR (trim($markah[$k]["GSEJMA"])=="TH"))
							$keputusan="GAGAL";
						else  
							$keputusan="LULUS";
					}else{ //T4
						if ((trim($markah[$k]["GBMMA"])=="G") OR (trim($markah[$k]["GBMMA"])=="TH") OR (trim($markah[$k]["GSEJMA"])=="G") OR (trim($markah[$k]["GSEJMA"])=="TH"))
							$keputusan="GAGAL";
						else  
							$keputusan="LULUS";
					}
				
			//	echo "BM : ".trim($markah[$k]["GBMMA"])." - ".trim($markah[$k]["GBMMA"])."<br>";
//				echo "BM : ".trim($markah[$k]["GSEJMA"])." - ".trim($markah[$k]["GSEJMA"]);
	  //echo "nokp:$nokp gred:$gred<br>";echo $gred;
					switch ($gred){
					
						case "A+": $bilap=$bilap+1; break;
						case "A": $bila=$bila+1; break;
						case "A-": $bilam=$bilam+1; break;
						case "B+": $bilbp=$bilbp+1; break;
						case "B": $bilb=$bilb+1; break;
						case "C+": $bilcp=$bilcp+1; break;
						case "C": $bilc=$bilc+1; break;
						case "D": $bild=$bild+1; break;
						case "E": $bile=$bile+1; break;
						case "G": $bilg=$bilg+1; break;
						case "TH": $bilth=$bilth+1; break;
					} // end switch
				} // end if $markah
			} // end if in_multiarray
		} //end for $j
		if ($bilmp>=1){
			$peratus = number_format(($jum_mark/($bilmp*100))*100,'2','.',',');
			$gpc = number_format((($bilap*0)+($bila*1)+($bilam*2)+($bilbp*3)+($bilb*4)+($bilcp*5)+($bilc*6)+($bild*7)+($bile*8)+($bilg*9)+($bilth*9))/$bilmp,2,'.',',');
		}
		//die($bilg+$bilth);
		//if ($bilg+$bilth>=1){ $keputusan="GAGAL"; } else { $keputusan="LULUS"; }
		if($bilap != ''){ $pencapaian = "".$pencapaian."".$bilap."[A+] "; }
		if($bila != ''){ $pencapaian = "".$pencapaian."".$bila."[A] "; }
		if($bilam != ''){ $pencapaian = "".$pencapaian."".$bilam."[A-] "; }
		if($bilbp != ''){ $pencapaian = "".$pencapaian."".$bilbp."[B+] "; }
		if($bilb != ''){ $pencapaian = "".$pencapaian."".$bilb."[B] "; }
		if($bilcp != ''){ $pencapaian = "".$pencapaian."".$bilcp."[C+] "; }
		if($bilc != ''){ $pencapaian = "".$pencapaian."".$bilc."[C] "; }	
		if($bild != ''){ $pencapaian = "".$pencapaian."".$bild."[D] ";; }
		if($bile != ''){ $pencapaian = "".$pencapaian."".$bile."[E] "; }
		if($bilg != ''){ $pencapaian = "".$pencapaian."".$bilg."[G] "; }	
		if($bilth != ''){ $pencapaian = "".$pencapaian."".$bilth."[TH] "; }
	
//echo ("nokp:$nokp pencapaian:$pencapaian bilap:$bilap bila:$bila bilam:$bilam<BR>");
		$penilaian[] = array("NOKP" =>$nokp,"NAMA" =>$nama, "KELAS" =>$kelas, "AMBIL" =>$bilmp, "BILAp"=>$bilap, "BILA"=>$bila, "BILAm"=>$bilam,
							 "BILBp"=>$bilbp, "BILB"=>$bilb, "BILCp"=>$bilcp, "BILC"=>$bilc, "BILD"=>$bild, "BILE"=>$bile,
							 "BILG"=>$bilg, "BILTH"=>$bilth, "JUM" =>$jum_mark, "PERATUS" =>$peratus, "KEPUTUSAN" =>$keputusan, 
							 "GPC" =>$gpc, "KDK" =>$kdk, "KDT" =>$kdt, "CAPAI" =>$pencapaian );
	}
	/////////////////////////////////////////// Tamat Proses penilaian murid //////////////////////////////////////
	
	foreach ($penilaian as $key => $row) {
	   $result[$key]  = $row['KEPUTUSAN'];
	   $gpcalon[$key] = $row['GPC'];
	   $pmarkh[$key] = $row['PERATUS'];
	}
	
	array_multisort($result, SORT_DESC, $gpcalon, SORT_ASC, $pmarkh, SORT_DESC, $penilaian);
	
	$penilaian2 = susunkdt($penilaian);
	
	foreach ($penilaian2 as $key => $row) {
	   $kdting[$key]  = $row['KDT'];
	}
	
	array_multisort($kdting, SORT_ASC, $penilaian2);
	//*
	///////// Simpan data dalam data base //////////////
	$stmt = oci_parse($conn_sispa,"DELETE FROM analisis_mpma WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting'");
	oci_execute($stmt);

	foreach ($databilgred as $key => $row)
	{
		$qgredma = oci_parse($conn_sispa,"SELECT * FROM analisis_mpma WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='".$row["KELAS"]."' AND kodmp='".$row["MP"]."'");
		oci_execute($qgredma);
	
		if ( count_row("SELECT * FROM analisis_mpma WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='".$row["KELAS"]."' AND kodmp='".$row["MP"]."'") != 0 )
		{
			$stmt = oci_parse($conn_sispa,"UPDATE analisis_mpma SET bcalon='$row[CALON]', ambil='$row[AMBIL]', TH='$row[TH]', Ap='$row[Ap]',A='$row[A]',
				   Am='$row[Am]', cer='$row[CER]', Bp='$row[Bp]', B='$row[B]', pc='$row[PC]', Cp='$row[Cp]', C ='$row[C]', 
				   D='$row[D]', lus='$row[L]', E='$row[E]', pl='$row[PL]', G='$row[G]', ctk='$row[CTK]' 
				   WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$row[KELAS]' AND kodmp='$row[MP]'");
			oci_execute($stmt);
		}
		else {
				 $stmt = oci_parse($conn_sispa,"INSERT INTO analisis_mpma(tahun, jpep, negeri, kodppd, kodsek, ting, kelas, kodmp, bcalon, ambil, 
						TH, Ap, A, Am, cer, Bp, B, pc, Cp, C, D, lus,  E, pl, G, ctk) 
						VALUES ('$tahun','$jpep','$negeri','$kodppd','$kodsek','$ting','$row[KELAS]','$row[MP]','$row[CALON]','$row[AMBIL]',
								'$row[TH]','$row[Ap]','$row[A]','$row[Am]','$row[CER]','$row[Bp]','$row[B]','$row[PC]','$row[Cp]','$row[C]',
								'$row[D]','$row[L]','$row[E]','$row[PL]','$row[G]','$row[CTK]')"); 
				 oci_execute($stmt);
			 }
	}

	$stmt = oci_parse($conn_sispa,"DELETE FROM tnilai_sma WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting'");
	oci_execute($stmt);

	foreach ($penilaian2 as $key => $data)
	{	
		$qmark = oci_parse($conn_sispa,"SELECT * FROM tnilai_sma WHERE nokp='$data[NOKP]' AND tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$data[KELAS]'");
		oci_execute($qmark);
	
		if ( count_row("SELECT * FROM tnilai_sma WHERE nokp='$data[NOKP]' AND tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$data[KELAS]'") != 0 )
		{
			$stmt = oci_parse($conn_sispa,"UPDATE tnilai_sma SET bilmp='$data[AMBIL]', bilap='$data[BILAp]', bila='$data[BILA]', bilam='$data[BILAm]',
				   bilbp='$data[BILBp]', bilb='$data[BILB]', bilcp='$data[BILCp]', bilc='$data[BILC]', bild='$data[BILD]', 
				   bile='$data[BILE]', bilg='$data[BILG]', bilth='$data[BILTH]', gpc='$data[GPC]', jummark='$data[JUM]', 
				   peratus='$data[PERATUS]', keputusan='$data[KEPUTUSAN]', pencapaian='$data[CAPAI]', kdk='$data[KDK]', kdt='$data[KDT]'
				   WHERE nokp='$data[NOKP]' AND tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$data[KELAS]'");
			oci_execute($stmt);
		}
		else {
				$stmt = oci_parse($conn_sispa,"INSERT INTO tnilai_sma(nokp, tahun, jpep, negeri, kodppd, kodsek, ting, kelas, bilmp, bilap, bila, bilam,
				   bilbp, bilb, bilcp, bilc, bild, bile, bilg, bilth, gpc, jummark, peratus, keputusan, pencapaian, kdk, kdt)
				   VALUES ('$data[NOKP]','$tahun','$jpep','$negeri','$kodppd','$kodsek','$ting','$data[KELAS]','$data[AMBIL]','$data[BILAp]',
						   '$data[BILA]','$data[BILAm]','$data[BILBp]','$data[BILB]','$data[BILCp]','$data[BILC]','$data[BILD]', 
						   '$data[BILE]','$data[BILG]','$data[BILTH]','$data[GPC]','$data[JUM]','$data[PERATUS]','$data[KEPUTUSAN]',
						   '$data[CAPAI]','$data[KDK]','$data[KDT]')");
			      oci_execute($stmt);
				
			  }
	}

	echo "</table>\n";
	unset ($penilaian2);
	unset ($penilaian);
	
	?><script type='text/javascript'> alert("Proses Markah Selesai" )</script> <?php
	location("proses_peperiksaan.php");
}
else {
		?><script type='text/javascript'> alert("Tiada Data Markah Untuk Diproses" )</script> <?php
		location("proses_peperiksaan.php");
	 }

?></tr></table>
</td>
<?php

function susunkdt($result)
{
	$kdt = 1; $bil = 1; $i =1;
	foreach ($result as $key => $value)
	{	
		$tmp_gpc = $result[$i]["GPC"];
		$tmp_per = $result[$i]["PERATUS"];
		$result[$i-1]["KDT"] = $kdt ;
        //echo "i:$i tmp_gpc:$tmp_gpc tmp_per:$tmp_per kdt:$kdt<br>";
		if ( ($value['GPC'] == $tmp_gpc ) AND ($value['PERATUS'] == $tmp_per ))
		{ 
			$bil = $bil + 1;
		} 
		else {$kdt = $kdt + $bil ; $bil = 1;  }
		$i++;
	}
	 
	usort($result, 'susunkdk');

	$kdk = 1; $bil = 1; $i =1;
	foreach ($result as $key => $value)
	{	
		$tmp_kdt = $result[$i]["KDT"];
		$tmp_kel = $result[$i]["KELAS"];
		$result[$i-1]["KDK"] = $kdk ;
        //echo "i:$i kelas:".$value['KELAS']." kdt:".$value['KDT']." tmp_kdt:$tmp_kdt tmp_kel:$tmp_kel kdk:$kdk bil:$bil<br>";
		if  ($value['KELAS'] == $tmp_kel )
		{  
			if ($value['KDT'] == $tmp_kdt )
			{
				$bil = $bil + 1;
			} 
			else 
			{ 
			   $kdk = $kdk + $bil ; 
			   $bil = 1;  
			 }
		} else 
		{ 
		  $kdk = 1;  
		  $bil=1; 
		}
		$i++;
	}
	return $result ;
}

function susunkdk($a, $b)
{
  $retval3 = strnatcmp($a['KELAS'], $b['KELAS']);
  if(!$retval3){ return strnatcmp($a['KDT'], $b['KDT']);}
  return $retval3;
}

function bilangred($arraymarkah, $arraympsek) 
{ 
	while (list($key, $mps) = each($arraympsek))
	{ 
		$bilAp = $bilA = $bilAm = $bilBp = $bilB = $bilCp = $bilC = $bilD = $bilE = $bilG = $bilTH = $bcalon = $ambil = 0;
		$cer = $pc = $lulus = $pl = $ctk = 0;
		
		$subjek = $mps['KODMP'];
		$gsubjek = trim("G$subjek");
		$i=0;
		while (list($key1, $gred) = each($arraymarkah)) 
		{ 
			$markmp = $gred["$subjek"];
			$gredmp = trim($gred["$gsubjek"]);
			$skelas = $arraymarkah[$i]['KELAS'];
			$bkelas = $arraymarkah[$i+1]['KELAS'];
//			echo "$skelas";
			
			if ($markmp >=80 ) { $cer++; }
			if (($markmp >=65) AND ($gmarkmp <=79)) { $pc++; }
			if (($markmp >=30) AND ($markmp <=64)) 	{ $lulus++; }
			if (($markmp >=21) AND ($markmp <=29)) 	{ $pl++; }
			if (($markmp >=1) AND ($markmp <=20)) 	{ $ctk++; }
			
			switch ($gredmp)
			{
				case "A+": $bilAp++; break;
				case "A": $bilA++; break;
				case "A-": $bilAm++; break;
				case "B+": $bilBp++; break;
				case "B": $bilB++; break;
				case "C+": $bilCp++; break;
				case "C": $bilC++; break;
				case "D": $bilD++; break;
				case "E": $bilE++; break;
				case "G": $bilG++; break;
				case "TH": $bilTH++; break;
			}
			if ($skelas != $bkelas){
				$bcalon = $bilAp+$bilA+$bilAm+$bilBp+$bilB+$bilCp+$bilC+$bilD+$bilE+$bilG+$bilTH;
				$ambil = $bilAp+$bilA+$bilAm+$bilBp+$bilB+$bilCp+$bilC+$bilD+$bilE+$bilG;
				$bilgred[] = array("KELAS" =>$skelas, "MP" =>$subjek, "CALON" =>$bcalon, "AMBIL" =>$ambil, "TH"=>$bilTH, 
								   "Ap" =>$bilAp, "A" =>$bilA, "Am" =>$bilAm, "CER" =>$cer, "Bp" =>$bilBp, "B"=>$bilB, "PC" =>$pc,
								   "Cp" =>$bilCp, "C"=>$bilC, "D"=>$bilD, "L" =>$lulus, "E"=>$bilE, "PL" =>$pl, "G"=>$bilG, "CTK" =>$ctk);
				$bilAp = $bilA = $bilAm = $bilBp = $bilB = $bilCp = $bilC = $bilD = $bilE = $bilG = $bilTH = $bcalon = $ambil = 0;
				$cer = $pc = $lulus = $pl = $ctk = 0;
			}
			$i++;
		}
		reset($arraymarkah); unset($key); unset($key1); unset($gred); 
	}
	return $bilgred ; 
} 

function in_multiarray($elem, $array)
{
	$top = sizeof($array) - 1;
	$bottom = 0;
	while($bottom <= $top)
	{
		if($array[$bottom] == $elem)
			return true;
		else 
			if(is_array($array[$bottom]))
				if(in_multiarray($elem, ($array[$bottom])))
					return true;
				
		$bottom++;
	}        
	return false;
}

function subjek_dikira($subjek)
{
global $conn_sispa;	
//Subjek yang tak dikira dalam penilian
$qrympxkira = oci_parse($conn_sispa,"SELECT kod FROM sub_ma_xambil where kod='$subjek'");
oci_execute($qrympxkira);
if ($rowmpxkira = oci_fetch_array($qrympxkira)) 
   return false;
else
   return true;
}

function not_in_multiarray($elem, $array)
{
	$top = sizeof($array) - 1;
	$bottom = 0;
	while($bottom <= $top)
	{
		if($array[$bottom] == $elem){
			//die("1. elem:$elem bottom:$bottom ".$array[$bottom]);
			return false;
		}
		else 
			if(is_array($array[$bottom]))
				if(not_in_multiarray($elem, ($array[$bottom]))){
					return false;
					
				}
				
		$bottom++;
	}        
	return true;
}
//////////////////////////////////////utk tarikh poses/////////////////////////////////////////
$tarikh=date('d-m-Y');
$masa=date('h:i:s');
$q_tp=oci_parse($conn_sispa,"SELECT * FROM tproses WHERE tahun='$tahun' AND kodsek='$kodsek' AND jpep='$jpep' AND ting='$ting'");
oci_execute($q_tp);
	if ( count_row("SELECT * FROM tproses WHERE tahun='$tahun' AND kodsek='$kodsek' AND jpep='$jpep' AND ting='$ting'") != 0 )
	{
		$stmt=oci_parse($conn_sispa,"UPDATE tproses SET tarikh='$tarikh', masa=to_date('$masa','hh24:mi:ss') WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting'");
		oci_execute($stmt);
	}
	else {
		$stmt=oci_parse($conn_sispa,"INSERT INTO tproses (tahun, jpep, kodsek, ting, masa, tarikh) VALUES ('$tahun','$jpep','$kodsek','$ting',to_date('$masa','hh24:mi:ss'),'$tarikh')");
		oci_execute($stmt);
	}
/////////////////////////////////////////////////////////////////////////////////////////////

?> 

<?php include 'kaki.php';?> 