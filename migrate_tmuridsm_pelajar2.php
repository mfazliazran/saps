<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
if($_SESSION['level']<>'3' and $_SESSION['level']<>'4'){ 
	die("Anda bukan SUP");
}
set_time_limit(0);
$nokp = $_GET["nokp"];
//$nosijil = str_replace(" ","",strtoupper($_GET["sijil"]));
$nosijil = $_GET["sijil"];
$nopasport = $_GET["pasport"];
$tiadadoc = $_GET["tiadadoc"];
$idmurid = $_GET["idmurid"];

$darjah=$_GET["ting"];
$namkelas = $_GET["kelas"];
$namkelas = htmlentities($namkelas);
$nkelas = str_replace('_',' ',$namkelas);

if($jsek=='SM'){
	$table = "tmurid";	
	$tingkat = "('P','T1','T2',T3'','T4','T5')";
	$jenis = "TINGKATAN";
	$jen = "T";
}else{
	$table = "tmuridsr";	
	$tingkat = "('D1','D2','D3','D4','D5','D6')";
	$jenis = "DARJAH";
	$jen = "D";
}//class="rightColumn"
?>
<td valign="top" >

<?php
if($_SESSION["tahun"]<>date("Y"))
	die('Utiliti import data APDM hanya untuk tahun semasa sahaja.');
	
//if(date('Y')=='2017')
	//die("KEMUDAHAN INI DIBERHENTIKAN BUAT SEMENTARA WAKTU.");
	
function semak_sekolah($kodsek){
	global $conn_sispa;
	
	$sql2 = "select status from tsekolah where kodsek='$kodsek'";
	$stmt2=oci_parse($conn_sispa,$sql2);
	oci_execute($stmt2);
	if($dt2=oci_fetch_array($stmt2)){
		$status = $dt2["STATUS"];	
		return $status;
	}
}
	
	//if($nokp<>"" or $nosijil<>""){
		
		/*if($nokp=="-" and $nosijil==""){
			echo "No. Kad Pengenalan / No. Sijil Lahir tidak sah!. Proses import tidak dapat dilakukan";
			echo "<br><br><center><a href=papar_semak_pelajar_apdm.php?data=$darjah/".$_SESSION['kodsek']."/$namkelas><< Kembali</a></center>";
			die();
		}
		if($nokp=="" and $nosijil=="-")	{
			echo "No. Kad Pengenalan / No. Sijil Lahir tidak sah!. Proses import tidak dapat dilakukan";
			echo "<br><br><center><a href=papar_semak_pelajar_apdm.php?data=$darjah/".$_SESSION['kodsek']."/$namkelas><< Kembali</a></center>";
			die();
		}*/
	$kodsekolah = $_SESSION['kodsek'];
	$sql="SELECT IDMURID,NAMA,NOKP,NOSIJILLAHIR,NOPASPORT,MURIDTIADADOKUMEN,KODKAUM,KODAGAMA,KODJANTINA,KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH,IDKODJENISSEKOLAH,IDKODPPD,IDKODNEGERI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsekolah' AND (NOKP='$nokp' OR NOSIJILLAHIR='$nosijil' OR NOPASPORT='$nopasport' OR MURIDTIADADOKUMEN='$tiadadoc' OR IDMURID='$idmurid') AND TRIM(KODTINGKATANTAHUN)='$darjah' and NAMAKELAS='".oci_escape_string($namkelas)."' ORDER BY TRIM(KODTINGKATANTAHUN),NAMA";
	//if($kodsekolah=="ABD1089")
		//die($sql);
  	$stmt=oci_parse($conn_sispa,$sql);
  	oci_execute($stmt);
	$masa = date("d/m/Y H:i:s");

  	while($dt=oci_fetch_array($stmt)){
		$bil++;
		$IDMURID=$dt["IDMURID"];
		$NAMA=oci_escape_string($dt["NAMA"]);
    	$NOKP=str_replace(" ","",strtoupper($dt["NOKP"]));
		$NOSIJILLAHIR=str_replace(" ","",strtoupper($dt["NOSIJILLAHIR"]));
		$NOPASPORT=str_replace(" ","",strtoupper($dt["NOPASPORT"]));
		$MURIDTIADADOKUMEN=str_replace(" ","",strtoupper($dt["MURIDTIADADOKUMEN"]));
		if($NOKP==""){
			if($NOSIJILLAHIR<>"")
				$nokputama = $NOSIJILLAHIR;
			else if($NOPASPORT<>"")
				$nokputama = $NOPASPORT;
			else if($MURIDTIADADOKUMEN<>"")
				$nokputama = $MURIDTIADADOKUMEN;
			else
				$nokputama = $IDMURID;
		}else{
			$nokputama = $NOKP;
		}	
			
		$KODSEK=$dt["KODSEKOLAH"];
		$KODJANTINA=$dt["KODJANTINA"];
		$KODKAUM=$dt["KODKAUM"];
		$KODAGAMA=$dt["KODAGAMA"];
		if($KODAGAMA=='01')
			$KODAGAMA='01';
		else
			$KODAGAMA='02';
		$KODTINGKATANTAHUN=$dt["KODTINGKATANTAHUN"];//latest ting
		//$NAMAKELAS=str_replace(' ','_',oci_escape_string($dt["NAMAKELAS"]));//latest nama kelas
		$NAMAKELAS=str_replace(' ','_',$dt["NAMAKELAS"]);//latest nama kelas
		$NAMAKELAS=str_replace('\'','*',$NAMAKELAS);
		$NAMAKELAS=oci_escape_string($NAMAKELAS);

		//$sql2 = "select namap,$KODTINGKATANTAHUN,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,TO_CHAR(TKHMIGRATE,'YYYY/MM/DD HH:MI:SS') as TARIKH_MIG from $table where nokp='$NOKP' AND KODSEK$KODTINGKATANTAHUN='$KODSEK' and $KODTINGKATANTAHUN='$KODTINGKATANTAHUN' AND TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."' AND KELAS$KODTINGKATANTAHUN='$NAMAKELAS'";//untuk update maklumat pelajar berdasarkan no kp sbb kodsek semasa tiada sbb br buat pd 14/3/2012
		//if($KODSEK=='ABD1089'){
			//echo $sql2."<br>";
			//die();
		//}
		$sql2 = "select namap,$KODTINGKATANTAHUN,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,TO_CHAR(TKHMIGRATE,'YYYY/MM/DD HH:MI:SS') as TARIKH_MIG from $table where nokp='$NOKP' AND KODSEK$KODTINGKATANTAHUN='$KODSEK' and $KODTINGKATANTAHUN='$KODTINGKATANTAHUN' AND TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."' AND KELAS$KODTINGKATANTAHUN='$NAMAKELAS' AND KODSEK_SEMASA='$KODSEK'";
		/*if($KODSEK=='TKE3111'){
			echo $sql2."<br>";
			die();
		}*/
		$stmt2=oci_parse($conn_sispa,$sql2);
	  	oci_execute($stmt2);
		if($dt2=oci_fetch_array($stmt2)){
			//data wujud dlm SAPS
			$update = "update $table set KODSEK$KODTINGKATANTAHUN='$KODSEK',TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."',$KODTINGKATANTAHUN='$KODTINGKATANTAHUN',KELAS$KODTINGKATANTAHUN='$NAMAKELAS', NAMAP='$NAMA',JANTINA='$KODJANTINA',KAUM='$KODKAUM',AGAMA='$KODAGAMA',TKHMIGRATE=to_date('$masa','dd/mm/yyyy hh24:mi:ss'),KODSEK_SEMASA='$KODSEK' where nokp='$NOKP' and KODSEK$KODTINGKATANTAHUN='$KODSEK' and $KODTINGKATANTAHUN='$KODTINGKATANTAHUN' AND TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."' AND KELAS$KODTINGKATANTAHUN='$NAMAKELAS'";
			//if($KODSEK=='ABD1089'){
			//echo $update."<br>";
			//die();
			//}
			$sql_upd=oci_parse($conn_sispa,$update);
			oci_execute($sql_upd);
			
		}else{
			$insert = "insert into $table (NOKP,NAMAP,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,$KODTINGKATANTAHUN,KELAS$KODTINGKATANTAHUN,JANTINA,KAUM,AGAMA,TKHMIGRATE,KODSEK_SEMASA)values('$nokputama','$NAMA','$KODSEK','".$_SESSION['tahun']."','$KODTINGKATANTAHUN','$NAMAKELAS','$KODJANTINA','$KODKAUM','$KODAGAMA',to_date('$masa','dd/mm/yyyy hh24:mi:ss'),'$KODSEK')";
			//if($KODSEK=='ABD1089'){
			//echo $insert."<br>";
			//die();
			//}
			$sql_insrt=oci_parse($conn_sispa,$insert);
			oci_execute($sql_insrt);	
		}
		/*if($dt2=oci_fetch_array($stmt2)){
			$asal=trim($dt2[$KODTINGKATANTAHUN]);
			$kodsekasal=$dt2["KODSEK$KODTINGKATANTAHUN"];
			$namapelajar=$dt2["NAMAP"];
			$tahunpel=$dt2["TAHUN$KODTINGKATANTAHUN"];
			
			if($KODTINGKATANTAHUN<>"P"){
				$darjahsblm = substr($KODTINGKATANTAHUN,1,1);
				$darjahsblm = $darjahsblm -1;
				//semak kod sek darjah sebelumnya sekiranya sama update nama pelajar
				$chkkodsek = oci_parse($conn_sispa,"select kodsek$jen$darjahsblm from $table where nokp='$NOKP' and kodsek_semasa='$KODSEK'");
				oci_execute($chkkodsek);
				if($dtkodsek=oci_fetch_array($chkkodsek)){ 
					$kodseksblm = $dtkodsek["KODSEK$jen$darjahsblm"];
				}
			}
			if(($_SESSION['kodsek']==$kodseksblm) or $kodseksblm==""){
				$updnama = ",NAMAP='$NAMA'";	
			}else{
				$updnama = "";	
			}
			$update = "update $table set KODSEK$KODTINGKATANTAHUN='$KODSEK',TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."',$KODTINGKATANTAHUN='$KODTINGKATANTAHUN',KELAS$KODTINGKATANTAHUN='$NAMAKELAS', NAMAP='$NAMA',JANTINA='$KODJANTINA',KAUM='$KODKAUM',AGAMA='$KODAGAMA',TKHMIGRATE=to_date('$masa','dd/mm/yyyy hh24:mi:ss'),KODSEK_SEMASA='$KODSEK' where nokp='$NOKP' and KODSEK$KODTINGKATANTAHUN='$KODSEK' and $KODTINGKATANTAHUN='$KODTINGKATANTAHUN' AND TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."' AND KELAS$KODTINGKATANTAHUN='$NAMAKELAS'";
			$sql_upd=oci_parse($conn_sispa,$update);
			oci_execute($sql_upd);
		}
		else {//else dt2
			$sql3 = "select namap,$KODTINGKATANTAHUN,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,TO_CHAR(TKHMIGRATE,'YYYY/MM/DD HH:MI:SS') as TARIKH_MIG from $table where nokp='$NOSIJILLAHIR' AND KODSEK$KODTINGKATANTAHUN='$KODSEK' and $KODTINGKATANTAHUN='$KODTINGKATANTAHUN' AND TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."' AND KELAS$KODTINGKATANTAHUN='$NAMAKELAS'"; //untuk update maklumat pelajar berdasarkan no sijil lahir sbb kodsek semasa tiada sbb br buat pd 14/3/2012
			$stmt3=oci_parse($conn_sispa,$sql3);
			oci_execute($stmt3);
			if($dt3=oci_fetch_array($stmt3)){
				$asal=trim($dt3[$KODTINGKATANTAHUN]);
				$kodsekasal=$dt3["KODSEK$KODTINGKATANTAHUN"];
				$tkhmigrate=$dt3["TARIKH_MIG"];
				$namapelajar=$dt3["NAMAP"];
				$tahunpel=$dt3["TAHUN$KODTINGKATANTAHUN"];
				if($KODTINGKATANTAHUN<>"P"){
					$darjahsblm = substr($KODTINGKATANTAHUN,1,1);
					$darjahsblm = $darjahsblm -1;
					//semak kod sek darjah sebelumnya sekiranya sama update nama pelajar
					$chkkodsek = oci_parse($conn_sispa,"select kodsek$jen$darjahsblm from $table where nokp='$NOSIJILLAHIR' and kodsek_semasa='$KODSEK'");
					oci_execute($chkkodsek);
					if($dtkodsek=oci_fetch_array($chkkodsek)){ 
						$kodseksblm = $dtkodsek["KODSEK$jen$darjahsblm"];
					}
				}
				if(($_SESSION['kodsek']==$kodseksblm) or $kodseksblm==""){
					$updnama = ",NAMAP='$NAMA'";	
				}else{
					$updnama = "";	
				}
				$update3 = "update $table set KODSEK$KODTINGKATANTAHUN='$KODSEK',TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."',$KODTINGKATANTAHUN='$KODTINGKATANTAHUN',KELAS$KODTINGKATANTAHUN='$NAMAKELAS' ,NAMAP='$NAMA',JANTINA='$KODJANTINA',KAUM='$KODKAUM',AGAMA='$KODAGAMA',TKHMIGRATE=to_date('$masa','dd/mm/yyyy hh24:mi:ss'),KODSEK_SEMASA='$KODSEK' where nokp='$NOSIJILLAHIR' and KODSEK$KODTINGKATANTAHUN='$KODSEK' and $KODTINGKATANTAHUN='$KODTINGKATANTAHUN' AND TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."' AND KELAS$KODTINGKATANTAHUN='$NAMAKELAS'";
				$sql_upd3=oci_parse($conn_sispa,$update3);
				oci_execute($sql_upd3);
			}
			else {//else dt3
				//import pelajar IC double
				$sql4 = "select namap,$KODTINGKATANTAHUN,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,TO_CHAR(TKHMIGRATE,'YYYY/MM/DD HH:MI:SS') as TARIKH_MIG from $table where nokp='$NOKP' and KODSEK_SEMASA='$KODSEK'";
				$stmt4=oci_parse($conn_sispa,$sql4);
				oci_execute($stmt4);
				if($dt4=oci_fetch_array($stmt4)){
					if($KODTINGKATANTAHUN<>"P"){
						$darjahsblm = substr($KODTINGKATANTAHUN,1,1);
						$darjahsblm = $darjahsblm -1;
						
						$chkkodsek = oci_parse($conn_sispa,"select kodsek$jen$darjahsblm from $table where nokp='$NOKP' and kodsek_semasa='$KODSEK'");
						oci_execute($chkkodsek);
						if($dtkodsek=oci_fetch_array($chkkodsek)){ 
							$kodseksblm = $dtkodsek["KODSEK$jen$darjahsblm"];
						}
					}
					if(($_SESSION['kodsek']==$kodseksblm) or $kodseksblm==""){
						$updnama = ",NAMAP='$NAMA'";	
					}else{
						$updnama = "";	
					}
					$asal=trim($dt4[$KODTINGKATANTAHUN]);//chek sama ada data telah wujud/ belum dengan semak kodsek darjah sblm
					if ($asal=="" and ($_SESSION['kodsek']==$kodseksblm)){
						$update4 = "update $table set KODSEK$KODTINGKATANTAHUN='$KODSEK',TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."',$KODTINGKATANTAHUN='$KODTINGKATANTAHUN',KELAS$KODTINGKATANTAHUN='$NAMAKELAS' ,NAMAP='$NAMA',JANTINA='$KODJANTINA',KAUM='$KODKAUM',AGAMA='$KODAGAMA',TKHMIGRATE=to_date('$masa','dd/mm/yyyy hh24:mi:ss'),KODSEK_SEMASA='$KODSEK' where nokp='$NOKP' and KODSEK_SEMASA='$KODSEK'";
						$sql_upd4=oci_parse($conn_sispa,$update4);
						oci_execute($sql_upd4);
					}//asal
					else{//if($kodseksblm==""){//$asal==""){// and 
						$update6 = "update $table set KODSEK$KODTINGKATANTAHUN='$KODSEK',TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."',$KODTINGKATANTAHUN='$KODTINGKATANTAHUN',KELAS$KODTINGKATANTAHUN='$NAMAKELAS' ,NAMAP='$NAMA',JANTINA='$KODJANTINA',KAUM='$KODKAUM',AGAMA='$KODAGAMA',TKHMIGRATE=to_date('$masa','dd/mm/yyyy hh24:mi:ss'),KODSEK_SEMASA='$KODSEK' where nokp='$NOKP' and KODSEK_SEMASA='$KODSEK'";
						$sql_upd6=oci_parse($conn_sispa,$update6);
						oci_execute($sql_upd6);
					}
				}else{//else dt4 ... sekiranya no kp x wujud
					$sql5 = "select namap,$KODTINGKATANTAHUN,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,TO_CHAR(TKHMIGRATE,'YYYY/MM/DD HH:MI:SS') as TARIKH_MIG from $table where nokp='$NOSIJILLAHIR' and KODSEK_SEMASA='$KODSEK'";//untuk dptkan pelajar yg berkenaan
					$stmt5=oci_parse($conn_sispa,$sql5);
					oci_execute($stmt5);
					if($dt5=oci_fetch_array($stmt5)){
						if($KODTINGKATANTAHUN<>"P"){
							$darjahsblm = substr($KODTINGKATANTAHUN,1,1);
							$darjahsblm = $darjahsblm -1;
							
							$chkkodsek = oci_parse($conn_sispa,"select kodsek$jen$darjahsblm from $table where nokp='$NOSIJILLAHIR' and kodsek_semasa='$KODSEK'");
							oci_execute($chkkodsek);
							if($dtkodsek=oci_fetch_array($chkkodsek)){ 
								$kodseksblm = $dtkodsek["KODSEK$jen$darjahsblm"];
							}
						}
						if(($_SESSION['kodsek']==$kodseksblm) or $kodseksblm==""){
							$updnama = ",NAMAP='$NAMA'";	
						}else{
							$updnama = "";	
						}
						$asal=trim($dt5[$KODTINGKATANTAHUN]);//chek sama ada data telah wujud/ belum
						if ($asal=="" and ($_SESSION['kodsek']==$kodseksblm)){
							$update5 = "update $table set KODSEK$KODTINGKATANTAHUN='$KODSEK',TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."',$KODTINGKATANTAHUN='$KODTINGKATANTAHUN',KELAS$KODTINGKATANTAHUN='$NAMAKELAS' ,NAMAP='$NAMA',JANTINA='$KODJANTINA',KAUM='$KODKAUM',AGAMA='$KODAGAMA',TKHMIGRATE=to_date('$masa','dd/mm/yyyy hh24:mi:ss'),KODSEK_SEMASA='$KODSEK' where nokp='$NOSIJILLAHIR' and KODSEK_SEMASA='$KODSEK'";
							$sql_upd5=oci_parse($conn_sispa,$update5);
							oci_execute($sql_upd5);
						}
						else{//if($asal=="" and $kodseksblm==""){
							$update7 = "update $table set KODSEK$KODTINGKATANTAHUN='$KODSEK',TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."',$KODTINGKATANTAHUN='$KODTINGKATANTAHUN',KELAS$KODTINGKATANTAHUN='$NAMAKELAS' ,NAMAP='$NAMA',JANTINA='$KODJANTINA',KAUM='$KODKAUM',AGAMA='$KODAGAMA',TKHMIGRATE=to_date('$masa','dd/mm/yyyy hh24:mi:ss'),KODSEK_SEMASA='$KODSEK' where nokp='$NOKP' and KODSEK_SEMASA='$KODSEK'";
							$sql_upd7=oci_parse($conn_sispa,$update7);
							oci_execute($sql_upd7);
						}
					}else{//if data tidak wujud langsung dalam pangkalan data tmurid so insert baru
						if($nokputama<>"" and $nokputama<>"-" and $nokputama<>"TIADA"){
							$insert = "insert into $table (NOKP,NAMAP,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,$KODTINGKATANTAHUN,KELAS$KODTINGKATANTAHUN,JANTINA,KAUM,AGAMA,TKHMIGRATE,KODSEK_SEMASA)values('$nokputama','$NAMA','$KODSEK','".$_SESSION['tahun']."','$KODTINGKATANTAHUN','$NAMAKELAS','$KODJANTINA','$KODKAUM','$KODAGAMA',to_date('$masa','dd/mm/yyyy hh24:mi:ss'),'$KODSEK')";
							$sql_insrt=oci_parse($conn_sispa,$insert);
							oci_execute($sql_insrt);
							//echo "insert3 - ".$insert."<br><br>";
							$ins++;
						}else{
							echo "<br><b>$namapelajar [$KODTINGKATANTAHUN - $NAMAKELAS]</b> tidak mempunyai No. Kad Pengenalan / No. Sijil Lahir. Sistem SAPS tidak dapat menyimpan data tersebut.<br>";
							$berjaya=0;
						}
					}//else dt5
				}//else dt4
			}//ELSE dt3
		}//ELSE dt2*/ //tutup kejap
	}//WHILE
	
	//echo "Proses import data murid dari APDM selesai.....<br>";
	if($berjaya=="0"){
		?>
		<script language="javascript" type="text/javascript">
		alert('Rekod pelajar tidak berjaya di import.');
		</script>
		<?php
	}else{
		?>
		<script language="javascript" type="text/javascript">
		alert('Rekod pelajar berjaya di import.');
		</script>
		<?php
	}
	pageredirect("papar_semak_pelajar_apdm.php?data=$darjah|".$_SESSION['kodsek']."|".urlencode($namkelas)."");//
//}else{
	?>
    <!--<script language="javascript" type="text/javascript">
	alert('Rekod pelajar tidak berjaya di import kerana tiada No. Kad Pengenalan/ No. Sijil Lahir.');
	//history.go(-1);
	</script>-->
    <?php
	//pageredirect("papar_semak_pelajar_apdm.php?data=$darjah/".$_SESSION['kodsek']."/".urlencode($namkelas)."");
//}//if($nokp<>"" and $nosijil<>""){
	
//}post
//echo "<br><br><center><a href=papar_semak_pelajar_apdm.php?data=$darjah/".$_SESSION['kodsek']."/".urlencode($namkelas)."><< Kembali</a></center>";
?>
</td>
<?php //include 'kaki.php';?>