<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');

if($level<>'3' and $level<>'4'){ 
	die("Anda bukan SUP");
}
//die("KEMUDAHAN INI DIBERHENTIKAN BUAT SEMENTARA WAKTU.");
set_time_limit(0);
if($jsek=='SM'){
	$table = "tmurid";	
	$tingkat = "('P','T1','T2','T3','T4','T5')";
	$kods = "(KODSEKP='$kodsek' or KODSEKT1='$kodsek' or KODSEKT2='$kodsek' or KODSEKT3='$kodsek' or KODSEKT4='$kodsek' or KODSEKT5='$kodsek')";
	$kodss = "(TAHUNP='".$_SESSION['tahun']."' or TAHUNT1='".$_SESSION['tahun']."' or TAHUNT2='".$_SESSION['tahun']."' or TAHUNT3='".$_SESSION['tahun']."' or TAHUNT4='".$_SESSION['tahun']."' or TAHUNT5='".$_SESSION['tahun']."')";
}else{
	$table = "tmuridsr";	
	$tingkat = "('D1','D2','D3','D4','D5','D6')";
	$kods = "(KODSEKD1='$kodsek' or KODSEKD2='$kodsek' or KODSEKD3='$kodsek' or KODSEKD4='$kodsek' or KODSEKD5='$kodsek' or KODSEKD6='$kodsek')";
	$kodss = "(TAHUND1='".$_SESSION['tahun']."' or TAHUND2='".$_SESSION['tahun']."' or TAHUND3='".$_SESSION['tahun']."' or TAHUND4='".$_SESSION['tahun']."' or TAHUND5='".$_SESSION['tahun']."' or TAHUND6='".$_SESSION['tahun']."')";
}//class="rightColumn"
?>
<td valign="top" >
<p class="subHeader">Import Data Murid Dari APDM 2018</p><br>
<?php
if($_SESSION["tahun"]<>date("Y"))
	die('Utiliti import data APDM hanya untuk tahun semasa sahaja.');
	
if(date('Y')=='2019')
	die("KEMUDAHAN INI DIBERHENTIKAN BUAT SEMENTARA WAKTU.");
	
	$sk = "select TO_CHAR(tkhmigrate,'DD/MM/YYYY HH:MI:SS') as TARIKH_SYNC from $table where $kods and $kodss order by tkhmigrate asc";
	//echo $sk;
	$res = oci_parse($conn_sispa,$sk);
	oci_execute($res);
	if($dat=oci_fetch_array($res)){
		$tarikhkemas = $dat["TARIKH_SYNC"];	
	}
?>
<form id="form1" name="form1" method="post" action="">
<table width="578" border="1" align="center">
  <tr bgcolor="#ff9900">
    <td align="center">Proses import data murid terdahulu telah dilakukan pada <strong><?php echo $tarikhkemas; ?></strong></td>
  </tr>
  <tr bgcolor="#ff9900">
    <td align="center"><input type="submit" name="button2" id="button2" value="IMPORT DATA DARI APDM" onclick="return semak_input();"/>
      <input name="post" type="hidden" id="post" value="1" /></td>
    </tr>
  </table>
</form>
<?php

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

if($_POST['post']=='1'){	
	$kodsekolah = $kodsek;//strtoupper($_POST['txtKodSek']);
	
	$sql="SELECT NAMA,NOKP,NOSIJILLAHIR,KODKAUM,KODAGAMA,KODJANTINA,KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH,IDKODJENISSEKOLAH,IDKODPPD,IDKODNEGERI FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsekolah' AND TRIM(KODTINGKATANTAHUN) IN $tingkat ORDER BY TRIM(KODTINGKATANTAHUN),NAMA";
//die($sql);
  	$stmt=oci_parse($conn_sispa,$sql);
  	oci_execute($stmt);
	$bil=0;
	$upd=0;
	$xupd=0;
	$wud=0;
	$ins=0;
	$cnt=0;

	$masa = date("d/m/Y H:i:s");
  	while($dt=oci_fetch_array($stmt)){
		$bil++;
		$NAMA=oci_escape_string($dt["NAMA"]);
    	$NOKP=str_replace(" ","",strtoupper($dt["NOKP"]));
		$NOSIJILLAHIR=str_replace(" ","",strtoupper($dt["NOSIJILLAHIR"]));
		if($NOKP=="")
			$nokputama = $NOSIJILLAHIR;
		else
			$nokputama = $NOKP;
			
		$KODSEK=$dt["KODSEKOLAH"];
		$KODJANTINA=$dt["KODJANTINA"];
		$KODKAUM=$dt["KODKAUM"];
		$KODAGAMA=$dt["KODAGAMA"];
		if($KODAGAMA=='01')
			$KODAGAMA='01';
		else
			$KODAGAMA='02';
		$KODTINGKATANTAHUN=$dt["KODTINGKATANTAHUN"];//latest ting
		$NAMAKELAS=str_replace(' ','_',$dt["NAMAKELAS"]);//latest nama kelas
		$NAMAKELAS=str_replace('\'','*',$NAMAKELAS);
		$NAMAKELAS=oci_escape_string($NAMAKELAS);
		$TARIKH_SYNC=$dt["TARIKH_SYNC"];
		
		if($bil==1 or ($semakting <> $KODTINGKATANTAHUN)){
			//null kan column
			$semakting = $KODTINGKATANTAHUN;
			$sqlup=oci_parse($conn_sispa,"update $table set KODSEK$KODTINGKATANTAHUN=null,TAHUN$KODTINGKATANTAHUN=null,$KODTINGKATANTAHUN=null,KELAS$KODTINGKATANTAHUN=null where KODSEK$KODTINGKATANTAHUN='$KODSEK' AND TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."' AND $KODTINGKATANTAHUN='$KODTINGKATANTAHUN'");
			oci_execute($sqlup);
		}
		
		//semak table berdasarkan nokp
		$sql2 = "select namap,$KODTINGKATANTAHUN,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,TO_CHAR(TKHMIGRATE,'YYYY/MM/DD HH:MI:SS') as TARIKH_MIG from $table where nokp='$NOKP' and KODSEK_SEMASA='$KODSEK'";
		//die($sql2);
		$stmt2=oci_parse($conn_sispa,$sql2);
	  	oci_execute($stmt2);
		if($dt2=oci_fetch_array($stmt2)){
			//update data telah wujud
			$asal=trim($dt2[$KODTINGKATANTAHUN]);
			$kodsekasal=$dt2["KODSEK$KODTINGKATANTAHUN"];
			$tkhmigrate=$dt2["TARIKH_MIG"];
			$namapelajar=$dt2["NAMAP"];
			$tahunpel=$dt2["TAHUN$KODTINGKATANTAHUN"];
			if ($asal==""){
				//check samada IC pelajar telah digunakan atau belum
				//cth T2 is null so br update column yang terlibat			
				$cnt++;
				$update = "update $table set KODSEK$KODTINGKATANTAHUN='$KODSEK',TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."',$KODTINGKATANTAHUN='$KODTINGKATANTAHUN',KELAS$KODTINGKATANTAHUN='$NAMAKELAS',JANTINA='$KODJANTINA',KAUM='$KODKAUM',AGAMA='$KODAGAMA',TKHMIGRATE=to_date('$masa','dd/mm/yyyy hh24:mi:ss'),KODSEK_SEMASA='$KODSEK' where nokp='$NOKP' and KODSEK_SEMASA='$KODSEK'";
				$sql_upd=oci_parse($conn_sispa,$update);
				oci_execute($sql_upd);
				$upd++;
			}else{
				echo "Pelajar No K.P : $NOKP sudah didaftarkan untuk tahap $KODTINGKATANTAHUN.<br>";
				echo "Nama : $namapelajar<br>";
				echo "Kod Sekolah : $kodsekasal<br>";
				echo "Tahun : $tahunpel<br><br>";
			}
		}
		else 
		{
			//semak table berdasarkan sijil lahir
			$sql3 = "select namap,$KODTINGKATANTAHUN,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,TO_CHAR(TKHMIGRATE,'YYYY/MM/DD HH:MI:SS') as TARIKH_MIG from $table where nokp='$NOSIJILLAHIR' and KODSEK_SEMASA='$KODSEK'";
			$stmt3=oci_parse($conn_sispa,$sql3);
			oci_execute($stmt3);
			if($dt3=oci_fetch_array($stmt3)){
				//update data wujud
				$asal=trim($dt3[$KODTINGKATANTAHUN]);
				$kodsekasal=$dt3["KODSEK$KODTINGKATANTAHUN"];
				$tkhmigrate=$dt3["TARIKH_MIG"];
				$namapelajar=$dt3["NAMAP"];
				$tahunpel=$dt3["TAHUN$KODTINGKATANTAHUN"];
					if ($asal==""){
						//check samada sijil lahir pelajar telah digunakan atau belum
						//cth T2 is null so br update column yang terlibat
						$cnt++;
						$update3 = "update $table set KODSEK$KODTINGKATANTAHUN='$KODSEK',TAHUN$KODTINGKATANTAHUN='".$_SESSION['tahun']."',$KODTINGKATANTAHUN='$KODTINGKATANTAHUN',KELAS$KODTINGKATANTAHUN='$NAMAKELAS',JANTINA='$KODJANTINA',KAUM='$KODKAUM',AGAMA='$KODAGAMA',TKHMIGRATE=to_date('$masa','dd/mm/yyyy hh24:mi:ss'),KODSEK_SEMASA='$KODSEK' where nokp='$NOSIJILLAHIR' and KODSEK_SEMASA='$KODSEK'";
						$sql_upd3=oci_parse($conn_sispa,$update3);
						oci_execute($sql_upd3);
						$upd++;
					}else{
						echo "Pelajar No Sijil Lahir : $NOSIJILLAHIR sudah didaftarkan untuk tahap $KODTINGKATANTAHUN.<br>";
						echo "Nama : $namapelajar<br>";
						echo "Kod Sekolah : $kodsekasal<br>";
						echo "Tahun : $tahunpel<br><br>";
					}
			}
			else {
				//kedua2 sijil lahir dan ic tidak wujud lagi baru insert new
				if($nokputama<>"" and $nokputama<>"-"){
				$insert = "insert into $table (NOKP,NAMAP,KODSEK$KODTINGKATANTAHUN,TAHUN$KODTINGKATANTAHUN,$KODTINGKATANTAHUN,KELAS$KODTINGKATANTAHUN,JANTINA,KAUM,AGAMA,TKHMIGRATE,KODSEK_SEMASA)values('$nokputama','$NAMA','$KODSEK','".$_SESSION['tahun']."','$KODTINGKATANTAHUN','$NAMAKELAS','$KODJANTINA','$KODKAUM','$KODAGAMA',to_date('$masa','dd/mm/yyyy hh24:mi:ss'),'$KODSEK')";
				$sql_insrt=oci_parse($conn_sispa,$insert);
				oci_execute($sql_insrt);
				//echo $insert."<br>";
				$ins++;
				}else{
					echo "<br><b>$namapelajar [$KODTINGKATANTAHUN - $NAMAKELAS]</b> tidak mempunyai No. Kad Pengenalan / No. Sijil Lahir. Sistem SAPS tidak dapat menyimpan data tersebut.<br>";
				}
			}
		}
	}
	
	echo "Proses import data murid dari APDM selesai.....<br>";
}
?>
</td>
<?php //include 'kaki.php';?>