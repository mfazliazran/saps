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
$ting = $_GET[ting];
$gting = strtolower($ting);
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
	//$kelas[] = $rowkelas;
	$bilkelas++;
}

$qrymp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru WHERE kodsek='$kodsek' AND tahun='$tahun' AND ting='$ting' ORDER BY kodmp");
oci_execute($qrymp);
//Subjek yang diambil dalam sekolah
$bilsub = 0;
$qry_subj="";
while ($rowmp = oci_fetch_array($qrymp)) {
	$mpsek[] = $rowmp;
	$bilsub++;
    $qry_subj.=",".$rowmp["KODMP"];
    $qry_subj.=",G".$rowmp["KODMP"];
}
//Subjek yang dikira dalam penilian
$qrympkira = oci_parse($conn_sispa,"SELECT * FROM sub_mr ORDER BY kod");
oci_execute($qrympkira);
$i=0;
while ($rowmpkira = oci_fetch_array($qrympkira)) {
	$mpkira[] = $rowmpkira;
	$i++;
}
//Subjek yang dikira dalam penilaian peperiksaan dalam sekolah
for ($k=0; $k<$bilsub; $k++){
	$sub = $mp[$k]['kodmp'];
	
	if (in_multiarray($sub, $mpkira)){
		$mpsekira[] = $sub;
	}
}

/*if($tahun=='2011'){
	$qrykeputusan = oci_parse($conn_sispa,"SELECT m.nokp,m.nama,m.kelas $qry_subj FROM markah_pelajar m, tmurid tm  WHERE m.kodsek='$kodsek' AND m.tahun='$tahun' AND m.jpep='$jpep' AND m.ting='$ting' AND tm.$gting='$ting' AND m.nokp=tm.nokp ORDER BY kelas");//tm.kelas$gting");	
}else{*/
	$qrykeputusan = oci_parse($conn_sispa,"SELECT m.nokp,m.nama,m.kelas $qry_subj FROM markah_pelajar m, tmurid tm  WHERE m.kodsek='$kodsek' AND m.tahun='$tahun' AND m.jpep='$jpep' AND m.ting='$ting' AND (tm.tahun$gting='$tahun' and tm.$gting='$ting' AND m.nokp=tm.nokp and kodsek_semasa='$kodsek') ORDER BY kelas");
	//echo "SELECT m.nokp,m.nama,m.kelas $qry_subj FROM markah_pelajar m, tmurid tm  WHERE m.kodsek='$kodsek' AND m.tahun='$tahun' AND m.jpep='$jpep' AND m.ting='$ting' AND (tm.tahun$gting='$tahun' and tm.$gting='$ting' AND m.nokp=tm.nokp and kodsek_semasa='$kodsek') ORDER BY kelas<br>";
	//tm.kelas$gting");
	//die("SELECT m.nokp,m.nama,m.kelas $qry_subj FROM markah_pelajar m, tmurid tm  WHERE m.kodsek='$kodsek' AND m.tahun='$tahun' AND m.jpep='$jpep' AND m.ting='$ting' AND tm.$gting='$ting' AND tm.tahun$gting='$tahun' AND tm.$gting='$ting' AND m.nokp=tm.nokp and kodsek_semasa='$kodsek' ORDER BY kelas");
	//AND tm.$gting='$ting' AND tm.tahun$gting='$tahun'
//}

oci_execute($qrykeputusan);
$i = 0;
while ($rownilai = oci_fetch_array($qrykeputusan)) {
	$namapelajar = $rownilai['NAMA'];
	//echo $namapelajar."$i<br>";
	$markah[] = $rownilai;
	$i++;
}
if (!empty($markah))
{
	$databilgred = bilangred($markah, $mpsekira, $mpsek);	
	//////////// Papar data Mata pelajaran yang diproses /////////////
	
	/*foreach ($databilgred as $key => $row) {
		echo "".$row['KELAS']." | ".$row['MP']." | ".$row['A']." | ".$row['B']." | ".$row['C']." | ".$row['D']." |".$row['E']." | ".$row['TH']." | ".$row['GPMP']."<br>";
	}*/
	
	//////////// Tamat papar data Mata pelajaran yang diproses /////////////
	
	/////////////////////////////////////////// Proses penilaian murid //////////////////////////////////////
	$bil = 0;
	for ($k=0; $k<$i; $k++){
		$bilmp =0; $bila = $bilb = $bilc = $bild = $bile = $bilf = $bilth = $peratus = $jum_mark = $gpc = $keputusan = $kategori = $pencapaian = "";
	
		$bil++;
		$nokp = $markah[$k]['NOKP'];	
		$nama = $markah[$k]['NAMA'];
		$kelas = $markah[$k]['KELAS'];
		//echo $nokp." - ".$nama." - ".$kelas."<br>";
	
		for ($j=0; $j<=$bilsub; $j++){
			$subjek = $mpsek[$j]['KODMP'];	
			//if (subjek_dikira($subjek)){
			if (in_multiarray($subjek, $mpkira)){
				if ($markah[$k]["$subjek"]!=""){
					$bilmp=$bilmp+1;
					$mark = $markah[$k]["$subjek"]; 
					$gred = trim($markah[$k]["G$subjek"]);
					$jum_mark = $jum_mark + $mark;
	
					switch ($gred){
						case "A": $bila=$bila+1; break;
						case "B": $bilb=$bilb+1; break;
						case "C": $bilc=$bilc+1; break;
						case "D": $bild=$bild+1; break;
						case "E": $bile=$bile+1; break;
						case "F": $bilf=$bilf+1; break;
						case "TH": $bilth=$bilth+1; break;
					} // end switch
				} // end if $markah
			} // end if in_multiarray
		} //end for $j
		if ($bilmp>=1){
			$peratus = number_format(($jum_mark/($bilmp*100))*100,'2','.',',');
			$gpc = number_format((($bila*1)+($bilb*2)+($bilc*3)+($bild*4)+($bile*5)+($bilf*6)+($bilth*6))/$bilmp,2,'.',',');
		}
		
		//if ($bile+$bilth>=1){ $keputusan="GAGAL"; } else { $keputusan="LULUS"; } 
		if ($bilf+$bilth>=1){ $keputusan="TIDAK MELEPASI TAHAP PENGUASAAN MINIMUM"; } else { $keputusan="LULUS"; }
		if($bila != ''){ $pencapaian = "".$bila."[A] "; }
		if($bilb != ''){ $pencapaian = "".$pencapaian."".$bilb."[B] "; }
		if($bilc != ''){ $pencapaian = "".$pencapaian."".$bilc."[C] "; }
		if($bild != ''){ $pencapaian = "".$pencapaian."".$bild."[D] "; }
		if($bile != ''){ $pencapaian = "".$pencapaian."".$bile."[E] "; }
		if($bilf != ''){ $pencapaian = "".$pencapaian."".$bilf."[F] "; }
		if($bilth != ''){ $pencapaian = "".$pencapaian."".$bilth."[TH] "; }
	
		$penilaian[] = array("NOKP" =>$nokp,"NAMA" =>$nama, "KELAS" =>$kelas, "AMBIL" =>$bilmp, "BILA"=>$bila, "BILB"=>$bilb, "BILC"=>$bilc,
							 "BILD"=>$bild, "BILE"=>$bile, "BILF"=>$bilf, "BILTH"=>$bilth, "JUM" =>$jum_mark, "PERATUS" =>$peratus,
							 "KEPUTUSAN" =>$keputusan, "GPC" =>$gpc, "KDK" =>$kdk, "KDT" =>$kdt, "CAPAI" =>$pencapaian );
		//echo "".$penilaian[]."<br>";
	}
	/////////////////////////////////////////// Tamat Proses penilaian murid //////////////////////////////////////
	
	foreach ($penilaian as $key => $row) {
	   $result[$key]  = $row['KEPUTUSAN'];
	   $gpcalon[$key] = $row['GPC'];
	   $pmarkh[$key] = $row['PERATUS'];
	}
	
	//array_multisort($result, SORT_DESC, $gpcalon, SORT_ASC, $pmarkh, SORT_DESC, $penilaian);
	array_multisort($result, SORT_ASC, $gpcalon, SORT_ASC, $pmarkh, SORT_DESC, $penilaian);
	
	$penilaian2 = susunkdt($penilaian, $bmt);
	
	foreach ($penilaian2 as $key => $row) {
	   $kdting[$key]  = $row['KDT'];
	}
	
	array_multisort($kdting, SORT_ASC, $penilaian2);
	//*
	///////// Simpan data dalam data base //////////////
	$stmt=oci_parse($conn_sispa,"DELETE FROM analisis_mpmr WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting'");
	oci_execute($stmt);
	
	foreach ($databilgred as $key => $row)
	{
		$qgredmr = oci_parse($conn_sispa,"SELECT * FROM analisis_mpmr WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$row[KELAS]' AND kodmp='$row[MP]'");
		oci_execute($qgredmr);
	
		if ( count_row("SELECT * FROM analisis_mpmr WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$row[KELAS]' AND kodmp='$row[MP]'") != 0 )
		{
			$query=oci_parse($conn_sispa,"UPDATE analisis_mpmr SET bcalon='$row[CALON]', ambil='$row[AMBIL]', TH='$row[TH]', A='$row[A]', cer='$row[CER]', 
				   B='$row[B]', pc='$row[PC]', C='$row[C]', lus='$row[L]', D='$row[D]', pl='$row[PL]', E='$row[E]', ctk='$row[CTK]', F='$row[F]'
				   WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$row[KELAS]' 
				   AND kodmp='$row[MP]'");
			oci_execute($query);
		}
		else {
				 $query=oci_parse($conn_sispa,"INSERT INTO analisis_mpmr(tahun, jpep, negeri, kodppd, kodsek, ting, kelas, kodmp, bcalon, ambil, TH, A, cer, B, pc, C, lus, D, pl, E, ctk, F) 
						VALUES ('$tahun','$jpep','$negeri','$kodppd','$kodsek','$ting','$row[KELAS]','$row[MP]','$row[CALON]','$row[AMBIL]',
								'$row[TH]','$row[A]','$row[CER]','$row[B]','$row[PC]','$row[C]','$row[L]','$row[D]','$row[PL]','$row[E]','$row[CTK]','$row[F]')");
				 oci_execute($query);
			 }
	}
	
	$query2=oci_parse($conn_sispa,"DELETE FROM tnilai_smr WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting'");
	oci_execute($query2);
	
	foreach ($penilaian2 as $key => $data)
	{	
		$qmark = oci_parse($conn_sispa,"SELECT * FROM tnilai_smr WHERE nokp='$data[NOKP]' AND tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$data[KELAS]'");
		oci_execute($qmark);
	
		if ( count_row("SELECT * FROM tnilai_smr WHERE nokp='$data[NOKP]' AND tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' AND kelas='$data[KELAS]'") != 0 )
		{
			$mysql_query=oci_parse($conn_sispa,"UPDATE tnilai_smr SET bilmp='$data[AMBIL]', bila='$data[BILA]', bilb='$data[BILB]', bilc='$data[BILC]',
				   bild='$data[BILD]', bile='$data[BILE]', bilf='$data[BILF]', bilth='$data[BILTH]', gpc='$data[GPC]', jummark='$data[JUM]', 
				   peratus='$data[PERATUS]', keputusan='$data[KEPUTUSAN]', pencapaian='$data[CAPAI]', kdk='$data[KDK]', kdt='$data[KDT]'
				   WHERE nokp='$data[NOKP]' AND tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting' 
				   AND kelas='$data[KELAS]'");
			oci_execute($mysql_query);
		}
		else {
				$mysql_query=oci_parse($conn_sispa,"INSERT INTO tnilai_smr(nokp, tahun, jpep, negeri, kodppd, kodsek, ting, kelas, bilmp, bila, bilb, bilc, bild, bile, bilf, bilth, gpc, jummark, peratus, keputusan, pencapaian, kdk, kdt)
				   VALUES ('$data[NOKP]','$tahun','$jpep','$negeri','$kodppd','$kodsek','$ting','$data[KELAS]','$data[AMBIL]','$data[BILA]',
						   '$data[BILB]','$data[BILC]','$data[BILD]', '$data[BILE]', '$data[BILF]', '$data[BILTH]','$data[GPC]','$data[JUM]',
						   '$data[PERATUS]','$data[KEPUTUSAN]','$data[CAPAI]','$data[KDK]','$data[KDT]')");
				oci_execute($mysql_query);
	
			  }
	}
	//*/
	///////////// Tamat simpan data ////////////////////
	//////////// Papar data penilaian pelajar yang diproses /////////////
	/*
	$bil = 1;
	echo "<table border='1'><tr>";
	foreach ($penilaian2 as $key => $data)
	{	
		echo "    <tr><td>$bil</td>\n";
		echo "    <td>".$data['NOKP']."</td>\n";
		echo "    <td>".$data['NAMA']."</td>\n";
		echo "    <td>".$data['KELAS']."</td>\n";
		echo "    <td><center>".$data['AMBIL']."</center></td>\n";
		echo "    <td><center>".$data['BILA']."</center></td>\n";
		echo "    <td><center>".$data['BILB']."</center></td>\n";
		echo "    <td><center>".$data['BILC']."</center></td>\n";
		echo "    <td><center>".$data['BILD']."</center></td>\n";
		echo "    <td><center>".$data['BILE']."</center></td>\n";
		echo "    <td><center>".$data['BILTH']."</center></td>\n";
		echo "    <td><center>".$data['JUM']."</center></td>\n";
		echo "    <td><center>".$data['PERATUS']."</center></td>\n";
		echo "    <td><center>".$data['KEPUTUSAN']."</center></td>\n";
		echo "    <td><center>".$data['GPC']."</center></td>\n";
		echo "    <td><center>".$data['KDK']."</center></td>\n";
		echo "    <td><center>".$data['KDT']."</center></td>\n";
		echo "    <td>".$data['CAPAI']."</td>\n";
		echo "</tr>";
		$bil++;
	}*/
	
	//////////// Tamat papar data yang diproses /////////////
	
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

		if  ($value['KELAS'] == $tmp_kel )
		{  
			if ($value['KDT'] == $tmp_kdt )
			{
				$bil = $bil + 1;
			} else { $kdk = $kdk + $bil ; $bil = 1;  }
		} else { $kdk = 1; $bil=1; }
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

function bilangred($arraymarkah, $arraympsekira, $arraympsek) 
{ 
	while (list($key, $mps) = each($arraympsek))
	{ 
		$bilA = $bilB = $bilC = $bilD = $bilE = $bilF = $bilTH = $bcalon = $ambil = $cer = $pc = $lulus = $pl = $ctk = 0; 
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
			if (($markmp >=40) AND ($markmp <=64)) 	{ $lulus++; }
			if (($markmp >=21) AND ($markmp <=39)) 	{ $pl++; }
			if (($markmp >=1) AND ($markmp <=20)) 	{ $ctk++; }
			
			switch ($gredmp)
			{
				case "A": $bilA++; break;
				case "B": $bilB++; break;
				case "C": $bilC++; break;
				case "D": $bilD++; break;
				case "E": $bilE++; break;
				case "F": $bilF++; break;
				case "TH": $bilTH++; break;
			}

			if ($skelas != $bkelas ){
				$bcalon = $bilA+$bilB+$bilC+$bilD+$bilE+$bilF+$bilTH;
				$ambil = $bilA+$bilB+$bilC+$bilD+$bilE+$bilF;
				$bilgred[] = array("KELAS" =>$skelas, "MP" =>$subjek, "CALON" =>$bcalon, "AMBIL" =>$ambil, "TH"=>$bilTH, 
				     			    "A" =>$bilA, "CER" =>$cer, "B" =>$bilB, "PC" =>$pc, "C"=>$bilC, "L" =>$lulus, "D"=>$bilD, 
									"PL" =>$pl, "E"=>$bilE, "CTK" =>$ctk, "F"=>$bilF);
				$bilA = $bilB = $bilC = $bilD = $bilE = $bilF = $bilTH = $bcalon = $ambil = $cer = $pc = $lulus = $pl = $ctk = 0; 
			}
			$i++;
		}
		reset($arraymarkah); unset($key); unset($key1); unset($gred); 
	}
	return $bilgred ; 
} 

function subjek_dikira($subjek)
{
global $conn_sispa;	
//Subjek yang dikira dalam penilian
$qrympkira = oci_parse($conn_sispa,"SELECT * FROM sub_mr where kod='$subjek'");
oci_execute($qrympkira);
if ($rowmpkira = oci_fetch_array($qrympkira)) 
   return true;
else
   return false;
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

//////////////////////////////////////utk tarikh poses/////////////////////////////////////////
$tarikh=date('d-m-Y');
$masa=date('h:i:s');
$q_tp=oci_parse($conn_sispa,"SELECT * FROM tproses WHERE tahun='$tahun' AND kodsek='$kodsek' AND jpep='$jpep' AND ting='$ting'");
oci_execute($q_tp);
	if ( count_row("SELECT * FROM tproses WHERE tahun='$tahun' AND kodsek='$kodsek' AND jpep='$jpep' AND ting='$ting'") != 0 )
	{
		$query3=oci_parse($conn_sispa,"UPDATE tproses SET tarikh='$tarikh', masa=to_date('$masa','hh24:mi:ss') WHERE tahun='$tahun' AND jpep='$jpep' AND kodsek='$kodsek' AND ting='$ting'");
		oci_execute($query3);
	}
	else {
	    $query3=oci_parse($conn_sispa,"INSERT INTO tproses (tahun, jpep, kodsek, ting, masa, tarikh) VALUES ('$tahun','$jpep','$kodsek','$ting',to_date('$masa','hh:mi:ss'),'$tarikh')");
		oci_execute($query3);
		//die($sql);
	}
    /////////////////////////////////////////////////////////////////////////////////////////////

?> 

<?php include 'kaki.php';?> 