<?php
//ibubapa2/index.php
//ajax_ibpelajar.js
include "../config.php";
session_start();
$tahun = $_GET["tahun"];
$nokp = $_SESSION["nokp"];
$kodsek = $_SESSION["kodsek_ib"];

function jpep2($kodpep)
{
	switch ($kodpep){
		case "U1":
		$npep="UJIAN 1";
		break;
		case "U2":
		$npep="UJIAN 2";
		break;
		case "PAT":
		$npep="PEPERIKSAAN AKHIR TAHUN";
		break;
		case "PPT":
		$npep="PEPERIKSAAN PERTENGAHAN TAHUN";
		break;
		case "PMRC":
		$npep="PEPERIKSAAN PERCUBAAN PMR";
		break;
		case "SPMC":
		$npep="PEPERIKSAAN PERCUBAAN SPM";
		break;
		case "UPSRC":
		$npep="PEPERIKSAAN PERCUBAAN UPSR";
		break;
	}
return $npep;
}

function GetDesc($tbl,$ktrgn,$kod,$v)
{
	global $conn_sispa;
	$query = "SELECT $ktrgn FROM $tbl where $kod='$v'";
	$res=oci_parse($conn_sispa,$query);
	oci_execute($res);
	$data=oci_fetch_array($res);
	$rekod=$data[$ktrgn];
	return $rekod;
}

$table1 = "tmuridsr";	
$table2 = "tmurid";	
$tahun_semasa1 = "(tahund1='$tahun' or tahund2='$tahun' or tahund3='$tahun' or tahund4='$tahun' or tahund5='$tahun' or tahund6='$tahun')";
$kodsek_semasa1 = "(kodsekd1='$kodsek' or kodsekd2='$kodsek' or kodsekd3='$kodsek' or kodsekd4='$kodsek' or kodsekd5='$kodsek' or kodsekd6='$kodsek') and kodsek_semasa='$kodsek'";
$tahun_semasa2 = "(tahunp='$tahun' or tahunt1='$tahun' or tahunt2='$tahun' or tahunt3='$tahun' or tahunt4='$tahun' or tahunt5='$tahun')";	
$kodsek_semasa2 = "(kodsekp='$kodsek' or kodsekt1='$kodsek' or kodsekt2='$kodsek' or kodsekt3='$kodsek' or kodsekt4='$kodsek' or kodsekt5='$kodsek') and kodsek_semasa='$kodsek'";


$sql = "SELECT * FROM $table2 where nokp='$nokp' and $tahun_semasa2 order by nokp asc";
//echo $sql;
$qic = oci_parse($conn_sispa,$sql);
oci_execute($qic);
if($row = oci_fetch_array($qic)){
	//SEKOLAH MENENGAH
	$tahunt[0] = $row["TAHUNP"];
	$tahunt[1] = $row["TAHUNT1"];
	$tahunt[2] = $row["TAHUNT2"];
	$tahunt[3] = $row["TAHUNT3"];
	$tahunt[4] = $row["TAHUNT4"];
	$tahunt[5] = $row["TAHUNT5"];
	for($i=0;$i<6;$i++){
		if($tahunt[$i]==$tahun){
			//echo "$tahunt[$i]==$tahun";
			//if($row["P"]=="P"){
			if($tahunt[0]==$tahun){
				$ting = "P";
				$tingkatan = "PERALIHAN";
				$kelas = $row["KELASP"];
				$kodseksemasa = $row["KODSEKP"];
				//echo "xxx";
			}else{
				$ting = "T$i";
				$tingkatan = "TINGKATAN $i";
				$kelas = $row["KELAST$i"];
				$kodseksemasa = $row["KODSEKT$i"];
				//echo "$i $ting $tingkatan $kelas $kodseksemasa";
				//echo "ttt";
			}			
			
			switch ($i)
			{
				case "0": case "1": case "2": 
					$jpeps = "JPEP!='UPSRC' and JPEP!='PMRC' and JPEP!='SPMC'";
					break;
				case "3":
					$jpeps = "JPEP!='UPSRC' and JPEP!='SPMC'";
					break;
				case "4":
					$jpeps = "JPEP!='UPSRC' and JPEP!='PMRC' and JPEP!='SPMC'";
					break;
				case "5":
					$jpeps = "JPEP!='UPSRC' and JPEP!='PMRC'";
					break;
			}
		}
	}
	$query = "SELECT NAMASEK, NOTELEFON FROM TSEKOLAH WHERE KODSEK='$kodseksemasa'";
	//echo $query;
	$result = oci_parse($conn_sispa,$query);
	oci_execute($result);
	if($data=oci_fetch_array($result)){ 
		$name = $data["NAMASEK"];
		$notelefon = $data["NOTELEFON"];
	}
	//$kelas_sql = "SELECT jpep FROM kawal_pep WHERE tahun ='$tahun' AND $jpeps ORDER BY RANK";
	//echo $kelas_sql;
	echo "Wujud|$tingkatan|$kelas|($kodseksemasa) $name ($notelefon)|";
	echo "<select name='cboPep' id='cboPep' onChange=\"papar_btn(this.value,'$nokp','$kodseksemasa','$ting','$kelas','$tahun');\"><option value=''>-PILIH JENIS PEPERIKSAAN-</option>";
	$kelas_sql = "SELECT jpep FROM kawal_pep WHERE tahun ='$tahun' AND $jpeps ORDER BY RANK";
	$qic3 = oci_parse($conn_sispa,$kelas_sql);
	oci_execute($qic3);
	while($row = oci_fetch_array($qic3)){
	if($row["JPEP"]==$jenispep){
	   echo "<option selected value='".$row["JPEP"]."'>".jpep2($row["JPEP"])."</option>"."<BR>";
	}
	else{
	  echo  "<option value='".$row["JPEP"]."'>".jpep2($row["JPEP"])."</option>";
	  }
	}
	echo "</select>|";
	echo "<table width=\"100%\" border=\"1\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size: small\">";
	echo "<tr align=\"center\"><td>NAMA MATAPELAJARAN</td><td>GURU MATAPELAJARAN</td></tr>";
	$qry = "SELECT KODMP, NAMA FROM SUB_GURU WHERE KODSEK='$kodseksemasa' and TAHUN='$tahun' and ting='$ting' and kelas='$kelas'";
	$res = oci_parse($conn_sispa,$qry);
	oci_execute($res);
	while($row_mp = oci_fetch_array($res)){
		$kodmp = $row_mp["KODMP"];
		$namagmp = $row_mp["NAMA"];	
		echo "<tr><td>&nbsp;".GetDesc("MPSMKC","MP","KOD",$kodmp)."</td><td>&nbsp;$namagmp</td></tr>";
	}
}else{
	//SEKOLAH RENDAH
	$sql2 = "SELECT * FROM $table1 where nokp='$nokp' and $tahun_semasa1 order by nokp asc";
	$qic2 = oci_parse($conn_sispa,$sql2);
	oci_execute($qic2);
	if($row = oci_fetch_array($qic2)){
		$tahund[1] = $row["TAHUND1"];
		$tahund[2] = $row["TAHUND2"];
		$tahund[3] = $row["TAHUND3"];
		$tahund[4] = $row["TAHUND4"];
		$tahund[5] = $row["TAHUND5"];
		$tahund[6] = $row["TAHUND6"];
		for($i=1;$i<=6;$i++){
			if($tahund[$i]==$tahun){
				$darj = "D$i";
				$darjah = "DARJAH $i";
				$kelas = $row["KELASD$i"];
				$kodseksemasa = $row["KODSEKD$i"];
				switch ($i)
				{
					case "1": case "2" : case "3": case "4" :case "5" :
						$jpeps = "JPEP!='PMRC' AND JPEP!='SPMC' and JPEP!='UPSRC'";
						break;
					case "6" :
						$jpeps = "JPEP!='PMRC' AND JPEP!='SPMC'";
						break;
				}
			}
		}
		$query = "SELECT NAMASEK, NOTELEFON FROM TSEKOLAH WHERE KODSEK='$kodseksemasa'";
		$result = oci_parse($conn_sispa,$query);
		oci_execute($result);
		if($data=oci_fetch_array($result)){ 
			$name = $data["NAMASEK"];
			$notelefon = $data["NOTELEFON"];
		}
		echo "Wujud|$darjah|$kelas|($kodseksemasa) $name ($notelefon)|";
		echo "<select name='cboPep' id='cboPep' onChange=\"papar_btn(this.value,'$nokp','$kodseksemasa','$darj','$kelas','$tahun');\"><option value=''>-PILIH JENIS PEPERIKSAAN-</option>";
		$kelas_sql = "SELECT jpep FROM kawal_pep WHERE tahun ='$tahun' AND $jpeps ORDER BY RANK";
		$qic3 = oci_parse($conn_sispa,$kelas_sql);
		oci_execute($qic3);
		while($row = oci_fetch_array($qic3)){
		if($row["JPEP"]==$jenispep){
		   echo "<option selected value='".$row["JPEP"]."'>".jpep2($row["JPEP"])."</option>"."<BR>";
		}
		else{
		  echo  "<option value='".$row["JPEP"]."'>".jpep2($row["JPEP"])."</option>";
		  }
		}
		echo "</select>|";
		echo "<table width=\"100%\" border=\"1\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\">";
		echo "<tr align=\"center\"><td>NAMA MATAPELAJARAN</td><td>GURU MATAPELAJARAN</td></tr>";
		$qry = "SELECT KODMP, NAMA FROM SUB_GURU WHERE KODSEK='$kodseksemasa' and TAHUN='$tahun' and ting='$darj' and kelas='$kelas'";
		$res = oci_parse($conn_sispa,$qry);
		oci_execute($res);
		while($row_mp = oci_fetch_array($res)){
			$kodmp = $row_mp["KODMP"];
			$namagmp = $row_mp["NAMA"];	
			echo "<tr><td>&nbsp;".GetDesc("MPSR","MP","KOD",$kodmp)."</td><td>&nbsp;$namagmp</td></tr>";
		}
	}else{
		echo "Tidak Wujud";	
	}
}
?>