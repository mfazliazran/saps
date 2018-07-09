<link href="kpm.css" type="text/css" rel="stylesheet" />
<?php 

global $conn_smm;

set_time_limit(0);
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';

$database = "(DESCRIPTION =
                        (ADDRESS =
                                                (PROTOCOL = TCP)
                                                (HOST = prod-scan.moe.gov.my)
                                                (PORT = 1521)
                        )
                        (CONNECT_DATA = (SERVICE_NAME=KPMPROD))
                        )";
if ($conn_smm=oci_connect("smmsapd","S@PD02i8#",$database)){
  $success=1;
  //echo $success;
  // die($success);
 }
 else {
   $err=oci_error();
  echo "Fatal: ".$err["message"]."<br>";
   echo "<center>Harap Maaf !!!<br><br>
      Gangguan Dalam Laluan Ke Database EMIS<br><br>Pihak Kami Memohon Ribuan Kemaafan Di Atas Kesulitan Yang Dialami.<br><br>Terima Kasih !!</center>";
   die();
 }

 // echo $success;

?>
<script language="javascript" type="text/javascript">
function semak_inputs(){
	if(document.form1.txtKodSek.value==''){
		alert('Sila masukkan kod sekolah lama.');	
		document.form1.txtKodSek.focus();
		return false;
	}
	if(document.form1.txtKodSekBaru.value==""){
		alert('Sila masukkan kod sekolah baru.');	
		document.form1.txtKodSekBaru.focus();
		return false;
	}
	return true;
}
</script>
<td valign="top" class="rightColumn">
<p class="subHeader">Tukar Kod Sekolah<font color="#FFFFFF">(Tarikh Kemaskini Program : 24/8/2011 10:27AM)</font></p>
<form id="form1" name="form1" method="post" action="">
<table width="578" border="1" align="center">
  <tr bgcolor="#ff9900">
    <td colspan="2"><div align="center">TUKAR KOD SEKOLAH</div></td>
  </tr>
  <tr>
    <td colspan="2">SILA MASUKKAN KOD SEKOLAH</td>
  </tr>
  <tr>
    <td>Kod Sekolah Lama</td>
    <td><label>
      <input type="text" name="txtKodSek" id="txtKodSek" onkeypress="this.value=this.value.toUpperCase()" />
    </label></td>
    </tr>
  <tr>
    <td>Kod Sekolah Baru</td>
    <td><input type="text" name="txtKodSekBaru" id="txtKodSekBaru" onkeypress="this.value=this.value.toUpperCase()" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="button" id="button" value="Tukar" onclick="return semak_inputs();" />
      <input name="post" type="hidden" id="post" value="1" /></td>
    </tr>
</table>
</form>
<?php

// echo $conn_smm.":=conn_smm";

$kodsekolahlama = strtoupper($_POST['txtKodSek']);
$kodsekolahbaru = strtoupper($_POST['txtKodSekBaru']);
if($_POST['post']=='1'){
	$cnt=0;
	$sql = "select KODSEK, NAMASEK from tsekolah where KODSEK='$kodsekolahlama'"; // check kod sekolah lama ada tak dalam saps
	$stmt=oci_parse($conn_sispa,$sql);
  	oci_execute($stmt);
	if($data=oci_fetch_array($stmt)){ // kalau ada, panggil utk check dalam emis
		// baca dari emis - conn_smm
	  	$kodsekolah=$data["KODSEK"];
	  	$sql2 = "select NamaSekolah from tssekolah where KodSekolah='$kodsekolahbaru'"; 
		$res=oci_parse($conn_smm,$sql2);
		oci_execute($res);
		$data=oci_fetch_array($res);
		$namasekolah=oci_escape_string($data["NAMASEKOLAH"]);
	
		if($namasekolah==""){
			die("Kodsekolah $kodsekolahbaru tidak wujud dalam EMIS");
		}

	  	$sql_check_lama = "select NamaSekolah from tssekolah where KodSekolah='$kodsekolahlama'"; // check kalau kod sekolah lama tu dah wujud, stop process
		$res_check_lama=oci_parse($conn_smm,$sql_check_lama);
		oci_execute($res_check_lama);
		$data_sek_lama=oci_fetch_array($res_check_lama);
		$namasekolah_lama=oci_escape_string($data_sek_lama["NAMASEKOLAH"]);

		if($namasekolah_lama<>""){ // kalau nama sekolah masih ada.. die()
			die("Kodsekolah $kodsekolahlama masih wujud dalam EMIS");
		}


	  
		$sql = "update tsekolah set kodsek='$kodsekolahbaru',namasek='$namasekolah' where kodsek='$kodsekolah'";
		//echo "$sql<br>";
		$stmt=oci_parse($conn_sispa,$sql);
	  	oci_execute($stmt);
		
		$sql2 = "update login set kodsek='$kodsekolahbaru',namasek='$namasekolah' where kodsek='$kodsekolah'";
		//echo "$sql2<br>";
		$stmt2=oci_parse($conn_sispa,$sql2);
	  	oci_execute($stmt2);
		
		////SEKOLAH MENENGAH///
		
		$sql3 = "update tmurid set kodsekp='$kodsekolahbaru' where kodsekp='$kodsekolah'";
		//echo "$sql3<br>";
		$stmt3=oci_parse($conn_sispa,$sql3);
	  	oci_execute($stmt3);
		
		$sql4 = "update tmurid set kodsekt1='$kodsekolahbaru' where kodsekt1='$kodsekolah'";
		//echo "$sql4<br>";
		$stmt4=oci_parse($conn_sispa,$sql4);
	  	oci_execute($stmt4);
		
		$sql5 = "update tmurid set kodsekt2='$kodsekolahbaru' where kodsekt2='$kodsekolah'";
		//echo "$sql5<br>";
		$stmt5=oci_parse($conn_sispa,$sql5);
	  	oci_execute($stmt5);
		
		$sql6 = "update tmurid set kodsekt3='$kodsekolahbaru' where kodsekt3='$kodsekolah'";
		//echo "$sql6<br>";
		$stmt6=oci_parse($conn_sispa,$sql6);
	  	oci_execute($stmt6);
		
		$sql7 = "update tmurid set kodsekt4='$kodsekolahbaru' where kodsekt4='$kodsekolah'";
		//echo "$sql7<br>";
		$stmt7=oci_parse($conn_sispa,$sql7);
	  	oci_execute($stmt7);
		
		$sql8 = "update tmurid set kodsekt5='$kodsekolahbaru' where kodsekt5='$kodsekolah'";
		//echo "$sql8<br>";
		$stmt8=oci_parse($conn_sispa,$sql8);
	  	oci_execute($stmt8);
		
		$sql9 = "update tmurid set kodsekt6='$kodsekolahbaru' where kodsekt6='$kodsekolah'";
		//echo "$sql9<br>";
		$stmt9=oci_parse($conn_sispa,$sql9);
	  	oci_execute($stmt9);
		
		$sql9a = "update tmurid set kodsek_semasa='$kodsekolahbaru' where kodsek_semasa='$kodsekolah'";
		//echo "$sql9a<br>";
		$stmt9a=oci_parse($conn_sispa,$sql9a);
	  	oci_execute($stmt9a);
		
		$sql10 = "update markah_pelajar set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql10<br>";
		$stmt10=oci_parse($conn_sispa,$sql10);
	  	oci_execute($stmt10);
		
		$sql11 = "update tguru_kelas set kodsek='$kodsekolahbaru',namasek='$namasekolah' where kodsek='$kodsekolah'";
		//echo "$sql11<br>";
		$stmt11=oci_parse($conn_sispa,$sql11);
	  	oci_execute($stmt11);
		
		$sql12 = "update tguru_kelas1 set kodsek='$kodsekolahbaru',namasek='$namasekolah' where kodsek='$kodsekolah'";
		//echo "$sql12<br>";
		$stmt12=oci_parse($conn_sispa,$sql12);
	  	oci_execute($stmt12);
		
		$sql13 = "update sub_guru set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql13<br>";
		$stmt13=oci_parse($conn_sispa,$sql13);
	  	oci_execute($stmt13);
		
		$sql14 = "update PENILAIAN_MURIDSMA set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql14<br>";
		$stmt14=oci_parse($conn_sispa,$sql14);
	  	oci_execute($stmt14);
		
		$sql14a = "update PENILAIAN_MURIDSMR set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql14a<br>";
		$stmt14a=oci_parse($conn_sispa,$sql14a);
	  	oci_execute($stmt14a);
		
		$sql15 = "update HEADCOUNT set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql15<br>";
		$stmt15=oci_parse($conn_sispa,$sql15);
	  	oci_execute($stmt15);
		
		$sql16 = "update ANALISIS_MPMA set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql16<br>";
		$stmt16=oci_parse($conn_sispa,$sql16);
	  	oci_execute($stmt16);
		
		$sql16a = "update ANALISIS_MPMR set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql16a<br>";
		$stmt16a=oci_parse($conn_sispa,$sql16a);
	  	oci_execute($stmt16a);
		
		$sql17 = "update TKELASSEK set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql17<br>";
		$stmt17=oci_parse($conn_sispa,$sql17);
	  	oci_execute($stmt17);
		
		$sql18 = "update TNILAI_SMA set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql18<br>";
		$stmt18=oci_parse($conn_sispa,$sql18);
	  	oci_execute($stmt18);
		
		$sql19 = "update TPINDAH set DR_KODSEK='$kodsekolahbaru',DR_NAMASEK='$namasekolah' where DR_KODSEK='$kodsekolah'";
		//echo "$sql19<br>";
		$stmt19=oci_parse($conn_sispa,$sql19);
	  	oci_execute($stmt19);
		
		$sql20 = "update TPINDAH set KE_KODSEK='$kodsekolahbaru',KE_NAMASEK='$namasekolah' where KE_KODSEK='$kodsekolah'";
		//echo "$sql20<br>";
		$stmt20=oci_parse($conn_sispa,$sql20);
	  	oci_execute($stmt20);
		
		$sql21 = "update TPROSES set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql21<br>";
		$stmt21=oci_parse($conn_sispa,$sql21);
	  	oci_execute($stmt21);
		
		$sql22 = "update TSAH set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql22<br>";
		$stmt22=oci_parse($conn_sispa,$sql22);
	  	oci_execute($stmt22);
		
		//SEKOLAH RENDAH//
		
		$sql23 = "update tmuridsr set kodsekd1='$kodsekolahbaru' where kodsekd1='$kodsekolah'";
		//echo "$sql23<br>";
		$stmt23=oci_parse($conn_sispa,$sql23);
	  	oci_execute($stmt23);
		
		$sql24 = "update tmuridsr set kodsekd2='$kodsekolahbaru' where kodsekd2='$kodsekolah'";
		//echo "$sql24<br>";
		$stmt24=oci_parse($conn_sispa,$sql24);
	  	oci_execute($stmt24);
		
		$sql25 = "update tmuridsr set kodsekd3='$kodsekolahbaru' where kodsekd3='$kodsekolah'";
		//echo "$sql25<br>";
		$stmt25=oci_parse($conn_sispa,$sql25);
	  	oci_execute($stmt25);
		
		$sql26 = "update tmuridsr set kodsekd4='$kodsekolahbaru' where kodsekd4='$kodsekolah'";
		//echo "$sql26<br>";
		$stmt26=oci_parse($conn_sispa,$sql26);
	  	oci_execute($stmt26);
		
		$sql27 = "update tmuridsr set kodsekd5='$kodsekolahbaru' where kodsekd5='$kodsekolah'";
		//echo "$sql27<br>";
		$stmt27=oci_parse($conn_sispa,$sql27);
	  	oci_execute($stmt27);
		
		$sql28 = "update tmuridsr set kodsekd6='$kodsekolahbaru' where kodsekd6='$kodsekolah'";
		//echo "$sql28<br>";
		$stmt28=oci_parse($conn_sispa,$sql28);
	  	oci_execute($stmt28);
		
		$sql28a = "update tmuridsr set kodsek_semasa='$kodsekolahbaru' where kodsek_semasa='$kodsekolah'";
		//echo "$sql28a<br>";
		$stmt28a=oci_parse($conn_sispa,$sql28a);
	  	oci_execute($stmt28a);
		
		$sql29 = "update markah_pelajarsr set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql29<br>";
		$stmt29=oci_parse($conn_sispa,$sql29);
	  	oci_execute($stmt29);
		
		$sql30 = "update PENILAIAN_MURIDSR set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql30<br>";
		$stmt30=oci_parse($conn_sispa,$sql30);
	  	oci_execute($stmt30);
		
		$sql31 = "update HEADCOUNTSR set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql31<br>";
		$stmt31=oci_parse($conn_sispa,$sql31);
	  	oci_execute($stmt31);
		
		$sql32 = "update ANALISIS_MPSR set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql32<br>";
		$stmt32=oci_parse($conn_sispa,$sql32);
	  	oci_execute($stmt32);
		
		$sql33 = "update TNILAI_SR set kodsek='$kodsekolahbaru' WHERE KODSEK='$kodsekolah'";
		//echo "$sql33<br>";
		$stmt33=oci_parse($conn_sispa,$sql33);
	  	oci_execute($stmt33);
	
	
		echo "Proses tukar kod sekolah selesai...";	  
	} else {
		echo "<br><br>Kod Sekolah $kodsekolah tidak wujud...";	
	}
	
	
} // post

?>
</td>
<?php include 'kaki.php';?> 