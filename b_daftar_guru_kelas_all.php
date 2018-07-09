<?php
//die("Harap maaf. Utiliti semakan kelas dan guru kelas dari APDM dihentikan buat sementara waktu.");
include_once 'config.php';


$tahap="TAHUN";
$tingkat = "('D2','D3','D4','D5','D6','P','T2','T3','T4','T5')";
###################################TKELASSM########################
function semak_sekolah($kodsek){
	global $conn_sispa;
	
	$sql2 = "select status,namasek from tsekolah where kodsek='$kodsek'";
	$stmt2=oci_parse($conn_sispa,$sql2);
	oci_execute($stmt2);
	if($dt2=oci_fetch_array($stmt2)){
		$status = $dt2["STATUS"];
		$namasek = oci_escape_string($dt2["NAMASEK"]);
		return "$status|$namasek";
	}
	
}

	//DROP DATA TKELAS D2
	$sqlup=oci_parse($conn_sispa,"DELETE FROM TKELASSEK WHERE TAHUN='2012' AND TING='D2'");
	oci_execute($sqlup);
	
	//DROP DATA TGURU_KELAS D2
	$sqlup2=oci_parse($conn_sispa,"DELETE FROM TGURU_KELAS WHERE TAHUN='2012' AND TING='D2'");
	oci_execute($sqlup2);	
		
//kalu tak jalan tgk kat query ni...
$sql="SELECT DISTINCT KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH FROM DATA_SEMUA_MURID WHERE TRIM(KODTINGKATANTAHUN)='D2'";
$stmt=oci_parse($conn_sispa,$sql);
oci_execute($stmt);
	
/*$cnt = count_row($sql);
if($cnt > 0){
	//DROP DATA TKELAS
	$sqlup=oci_parse($conn_sispa,"DELETE FROM TKELASSEK WHERE KODSEK='$kodsek' AND TAHUN='2012' AND TING IN $tingkat");
	oci_execute($sqlup);
	
	//DROP DATA TGURU_KELAS
	$sqlup2=oci_parse($conn_sispa,"DELETE FROM TGURU_KELAS WHERE KODSEK='$kodsek' AND TAHUN='2012' AND TING IN $tingkat");
	oci_execute($sqlup2);	
	
	$sqlup3=oci_parse($conn_sispa,"UPDATE LOGIN SET LEVEL1='1' WHERE KODSEK='$kodsek' AND LEVEL1 NOT IN ('3','4','P')");
	oci_execute($sqlup3);
	
	$sqlup4=oci_parse($conn_sispa,"UPDATE LOGIN SET LEVEL1='3' WHERE KODSEK='$kodsek' AND LEVEL1='4'");
	oci_execute($sqlup4);
}*/
while($dt=oci_fetch_array($stmt)){
		
	$bil++;
	$KODSEKOLAH=$dt["KODSEKOLAH"];			
	$NOKPGURU=$dt["NOKPGURU"];
	$NAMAGURU=oci_escape_string($dt["NAMAGURU"]);
	$KODTINGKATANTAHUN=$dt["KODTINGKATANTAHUN"];//latest ting
	$NAMAKELAS=str_replace(' ','_',$dt["NAMAKELAS"]);//latest nama kelas\
	$NAMAKELAS=str_replace('\'','*',$NAMAKELAS);
	$data = semak_sekolah($KODSEKOLAH);
	list ($JENISSEK, $NAMASEK)=str_split('[|]', $data);
		
	################################## UPDATE/ADD TKELASSEK #########################
	$sql2 = "select KODSEK,TAHUN from TKELASSEK where KODSEK='$KODSEKOLAH' and TAHUN='2012' and TING='$KODTINGKATANTAHUN' and KELAS='".oci_escape_string($NAMAKELAS)."'";

	$stmt2=oci_parse($conn_sispa,$sql2);
  	oci_execute($stmt2);
	if($dt2=oci_fetch_array($stmt2)){
		//update
		$update = "update TKELASSEK set TAHUN='2012',KODSEK='$KODSEKOLAH',TING='$KODTINGKATANTAHUN',KELAS='$NAMAKELAS',LABEL_KELAS='KB' where ODSEK='$KODSEKOLAH' and TAHUN='2012'";
		$sql_upd=oci_parse($conn_sispa,$update);
		oci_execute($sql_upd);
		//echo "$update<br>";
	$upd++;
	}else{
		//add new
		$insert = "insert into TKELASSEK (TAHUN,KODSEK,TING,KELAS,LABEL_KELAS)values('2012','$KODSEKOLAH','$KODTINGKATANTAHUN','".oci_escape_string($NAMAKELAS)."','KB')";
		$sql_insrt=oci_parse($conn_sispa,$insert);
		oci_execute($sql_insrt);
		//echo "$insert<br>";
	}		
		
	################################## UPDATE/ADD TGURU_KELAS #########################
	$sql3 = "select * from TGURU_KELAS where KODSEK='$KODSEKOLAH' and TAHUN='2012' and TING='$KODTINGKATANTAHUN' AND KELAS='".oci_escape_string($NAMAKELAS)."'";
	$stmt3=oci_parse($conn_sispa,$sql3);
	oci_execute($stmt3);
	
	if($dt3=oci_fetch_array($stmt3)){
		//update
		$update2 = "update TGURU_KELAS set TAHUN='2012',NAMA='$NAMAGURU',LEVEL1='2',NOKP='$NOKPGURU',NAMASEK='$NAMASEK',KODSEK='$KODSEKOLAH',STATUSSEK='$JENISSEK',TING='$KODTINGKATANTAHUN' ,KELAS='".oci_escape_string($NAMAKELAS)."' where KODSEK='$KODSEKOLAH' and TAHUN='2012' and TING='$KODTINGKATANTAHUN' AND KELAS='$NAMAKELAS'";
		$sql_upd2=oci_parse($conn_sispa,$update2);
		oci_execute($sql_upd2);
		//echo "$update2<br>";
	
		$chklvl = oci_parse($conn_sispa,"select level1 from login where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
		oci_execute($chklvl);
		if($chk=oci_fetch_array($chklvl)){
			$levelguru = trim($chk["LEVEL1"]);
			if($levelguru == '3' or $levelguru == '4'){
				$sql_up=oci_parse($conn_sispa,"update login set level1='4' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
				oci_execute($sql_up);
				//echo "update login set level1='4' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'<br>";
			}else{
				$sql_up=oci_parse($conn_sispa,"update login set level1='2' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
				oci_execute($sql_up);
				//echo "update login set level1='2' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'<br>";
			}
		}
	}else{
			//add new
		$insert2 = "insert into TGURU_KELAS (TAHUN,NOKP,NAMA,LEVEL1,NAMASEK,KODSEK,STATUSSEK,TING,KELAS)values('2012','$NOKPGURU','$NAMAGURU','2','$NAMASEK','$KODSEKOLAH','$JENISSEK','$KODTINGKATANTAHUN','".oci_escape_string($NAMAKELAS)."')";
		$sql_insrt2=oci_parse($conn_sispa,$insert2);
		oci_execute($sql_insrt2);
		//echo "$insert2<br>";
			
		$chklvl = oci_parse($conn_sispa,"select level1 from login where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
		oci_execute($chklvl);
		if($chk=oci_fetch_array($chklvl)){
			$levelguru = trim($chk["LEVEL1"]);
			if($levelguru == '3' or $levelguru == '4'){
				$sql_up=oci_parse($conn_sispa,"update login set level1='4' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
				oci_execute($sql_up);
				//echo "update login set level1='4' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'<br>";
			}else{
				$sql_up=oci_parse($conn_sispa,"update login set level1='2' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
				oci_execute($sql_up);
				//echo "update login set level1='2' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'<br>";
			}
		}
	}			
}//while
####################END TKELASSM###################################		
echo "Proses selesai....";
?>