<?php
//die("Harap maaf. Utiliti semakan kelas dan guru kelas dari APDM dihentikan buat sementara waktu.");
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once 'config.php';

?>
<td valign="top" class="rightColumn">
<p class="subHeader"><?php echo "DAFTAR GURU KELAS TAHUN ".$_SESSION['tahun'].""; ?></p>
<?php

if($_SESSION["tahun"]<>date("Y"))
	die('Utiliti import data APDM hanya untuk tahun semasa sahaja.');
	
if(date('Y')=='2015')
	die("KEMUDAHAN INI DIBERHENTIKAN BUAT SEMENTARA WAKTU.");
	
//die("KEMUDAHAN INI DIBERHENTIKAN BUAT SEMENTARA WAKTU.");
/*
DISABLE SBB TIDAK PERLU CHECK SBB IMPORT DIRECT DR APDM
$querykelas = "SELECT DISTINCT ting FROM tkelassek WHERE kodsek='$kodsek'"; 
$resultkelas = OCIParse($conn_sispa,$querykelas);
OCIExecute($resultkelas);
$bilkelas =count_row($querykelas);

	if($bilkelas ==0){
		echo "<br><br><br><br><br><br><br><br>";
		echo "<center><h3>DAFTAR GURU KELAS</h3></center>";
		echo "<br><br>";
		echo "<table width=\"500\" border =\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "  <td colspan=\"4\"><center><font color =\"#ff0000\"><h3><br>Senarai Kelas Belum Didaftarkan<br>Sila Hubungi SU Peperiksaan Sekolah Anda</h3></font></center></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		}
//////end semak subjek guru
	else{*/
		?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='b_daftar_guru_kelas.php?ting=' + val;
		}
		</script>
		<script type='text/javascript'>
		function formValidator(){
			var ting = document.getElementById('ting');
			var kelas = document.getElementById('kelas');
			var nokp = document.getElementById('nokp');

				if(notEmpty(ting, "Sila pilih tingkatan !!!!")){
					if(notEmpty(kelas, "Sila pilih kelas !!!!")){
						if(notEmpty(nokp, "Sila pilih nama guru !!!!")){
						return true;
						}
					}
				}
				return false;
			}
		function notEmpty(elem, helperMsg){
			if(elem.value.length == 0){
				alert(helperMsg);
				elem.focus(); // set the focus to this input
				return false;
			}
			return true;
		}
		</script>
		<?php
		if($_SESSION['statussek'] =="SM"){
			$tahap="TINGKATAN";
			$tingkat = "('P','T1','T2','T3','T4','T5','D5','D6')";
		}
		if($_SESSION['statussek']=="SR"){
			$tahap="TAHUN";
			$tingkat = "('D1','D2','D3','D4','D5','D6','T4','T5')";
		}
		?>
	<br>
	<div align="center">
	  <p>Senarai nama kelas dan guru kelas ini diambil secara terus dari <font color="#FF0000"> <b>APLIKASI PANGKALAN DATA MURID (APDM) ONLINE</b></font>.<br>
	  Oleh yang demikian, kemudahan <font color="#FF0000"><b>
	  <h> TAMBAH DAN KEMASKINI  KELAS akan ditamatkan dalam SAPS</h></b></font>. Sila kemaskini data didalam APDM ONLINE.<br>
	  </p>
</div><br>

		<?php
		/*
		echo "<br><br>"; ?>
		<form name="form1" method="post" action="daftar_guru_kelas.php" onsubmit='return formValidator()'><?php
		echo "<input name=\"kodsek\" type=\"hidden\" value=\"$kodsek\" size=\"8\"><input name=\"namasek\" type=\"hidden\" value=\"$namasek\" size=\"8\"><input name=\"statussek\" type=\"hidden\" value=\"".$_SESSION['statussek']."\" size=\"8\">";
		echo "  <table width=\"600\" border=\"1\" bordercolor=\"#ccccccc\" align=\"center\" cellspacing=\"\" cellpadding=\"10\">\n";
		echo "    <tr>\n";
		echo "   <td bgcolor=\"#ff9900\"><center>$tahap</center></td>\n";
		echo "   <td bgcolor=\"#ff9900\"><center>KELAS</center></td>\n";
		echo "   <td bgcolor=\"#ff9900\"><center>GURU</center></td>\n";
		echo "   <td bgcolor=\"#ff9900\"><center>HANTAR</center></td>\n";
		echo "    </tr>\n";
		echo "    <tr>\n";
		echo "  <td><center>\n";
		
		$ting=$_GET['ting'];
		$kelas=$_GET['kelas'];
	
		$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='$kodsek' ORDER BY ting";
		$sql = OCIParse($conn_sispa,$SQL_tkelas);
		OCIExecute($sql);
		$num = count_row($SQL_tkelas);

		echo "<form method=post name='f1' action='d_sub_guru.php'>";
	
		echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''></option>";
		
		while(OCIFetch($sql)) { 
		if(OCIResult($sql,"TING")==$ting){
		     echo "<option selected value='".OCIResult($sql,"TING")."'>".OCIResult($sql,"TING")."</option>"."<BR>";
		  }
		else{
		   echo  "<option value='".OCIResult($sql,"TING")."'>".OCIResult($sql,"TING")."</option>";
		  }
		}//while
		echo "</select>";
		echo "</td></center>";
		echo "<td><center>";
		
		echo "<select name='kelas' ><option value=''></option>";

		$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' ORDER BY kelas");
		OCIExecute($kelas_sql);
		while(OCIFetch($kelas_sql)) { 
		if(OCIResult($kelas_sql,"KELAS")==$kelas){
		   echo "<option selected value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>"."<BR>";
		}
		else{
		  echo  "<option value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>";
		  }
		}
				
		echo "</td></center>";
		//////////        Starting of second drop downlist /////////
		echo "<td><center>";
		$strSQL = "SELECT * FROM login WHERE kodsek='$kodsek' ORDER BY nama";
		$rs = OCIParse($conn_sispa,$strSQL);
		OCIExecute($rs);
		echo "<select name=\"nokp\">";
		echo "<OPTION VALUE=\"\"></OPTION>";
		
		$nr = count_row($strSQL);
		while(OCIFetch($rs)) {
			echo "<OPTION VALUE=\"".OCIResult($rs,"NOKP")."|".OCIResult($rs,"NAMA")."|".OCIResult($rs,"LEVEL1")."\">".OCIResult($rs,"NOKP")."-".OCIResult($rs,"NAMA")."</OPTION>";
			}
		echo "</select>";
		echo "</td></center>";
		echo "<td><center>";
		echo "<center><input type=\"submit\" name=\"Submit\" value=\"Hantar\"></center>\n";
		echo "</td></center>";
		echo "</table>";
		echo "<br><br>";
		echo "</form>\n";
		//////papar subjek
		*/		
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
		//DROP DATA TKELAS
		//$sqlup=oci_parse($conn_sispa,"DELETE FROM TKELASSEK WHERE KODSEK='$kodsek' AND TAHUN='".$_SESSION['tahun']."' AND TING IN $tingkat");
		//oci_execute($sqlup);
		
		//DROP DATA TGURU_KELAS
		//$sqlup2=oci_parse($conn_sispa,"DELETE FROM TGURU_KELAS WHERE KODSEK='$kodsek' AND TAHUN='".$_SESSION['tahun']."' AND TING IN $tingkat");
		//oci_execute($sqlup2);
		
//kalu tak jalan tgk kat query ni...
$sql="SELECT DISTINCT KODTINGKATANTAHUN,NAMAKELAS,NOKPGURU,NAMAGURU,KODSEKOLAH,NAMASEKOLAH FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsek' AND TRIM(KODTINGKATANTAHUN) IN $tingkat";
	//echo "kodsekolah: $kodsekolah<br>";
	//if ($kodsekolah=='NBA1014')
		//echo $sql."<br>";
  	$stmt=oci_parse($conn_sispa,$sql);
  	oci_execute($stmt);
	$cnt = count_row($sql);
	if($cnt > 0){
		//DROP DATA TKELAS
		$sqlup=oci_parse($conn_sispa,"DELETE FROM TKELASSEK WHERE KODSEK='$kodsek' AND TAHUN='".$_SESSION['tahun']."' AND TING IN $tingkat");
		oci_execute($sqlup);
		
		//DROP DATA TGURU_KELAS
		$sqlup2=oci_parse($conn_sispa,"DELETE FROM TGURU_KELAS WHERE KODSEK='$kodsek' AND TAHUN='".$_SESSION['tahun']."' AND TING IN $tingkat");
		oci_execute($sqlup2);	
		
		$sqlup3=oci_parse($conn_sispa,"UPDATE LOGIN SET LEVEL1='1' WHERE KODSEK='$kodsek' AND LEVEL1 NOT IN ('3','4','P','PK')");
		oci_execute($sqlup3);
		
		$sqlup4=oci_parse($conn_sispa,"UPDATE LOGIN SET LEVEL1='3' WHERE KODSEK='$kodsek' AND LEVEL1='4'");
		oci_execute($sqlup4);
	}
	while($dt=oci_fetch_array($stmt)){
		
		$bil++;
		$KODSEKOLAH=$dt["KODSEKOLAH"];			
		//$KODTINGDARIASAL=$dt["KODTINGDARIASAL"];
		//$NAMAKELASDARIASAL=$dt["NAMAKELASDARIASAL"];
		$NOKPGURU=$dt["NOKPGURU"];
		$NAMAGURU=oci_escape_string($dt["NAMAGURU"]);
		$KODTINGKATANTAHUN=$dt["KODTINGKATANTAHUN"];//latest ting
		$NAMAKELAS=str_replace(' ','_',$dt["NAMAKELAS"]);//latest nama kelas\
		$NAMAKELAS=str_replace('\'','*',$NAMAKELAS);
		$data = semak_sekolah($KODSEKOLAH);
		list ($JENISSEK, $NAMASEK)=explode("|", $data);
			
		################################## UPDATE/ADD TKELASSEK #########################
		$sql2 = "select KODSEK,TAHUN from TKELASSEK where KODSEK='$KODSEKOLAH' and TAHUN='".$_SESSION['tahun']."' and TING='$KODTINGKATANTAHUN' and KELAS='".oci_escape_string($NAMAKELAS)."'";
		//if($kodsek=='TEA5035')
			//echo "$sql2 <br>";
		$stmt2=oci_parse($conn_sispa,$sql2);
	  	oci_execute($stmt2);
		if($dt2=oci_fetch_array($stmt2)){
			//update
			/*$update = "update TKELASSEK set TAHUN='".$_SESSION['tahun']."',KODSEK='$KODSEKOLAH',TING='$KODTINGKATANTAHUN',KELAS='$NAMAKELAS',LABEL_KELAS='KB' where KODSEK='$KODSEKOLAH' and TAHUN='".$_SESSION['tahun']."'";
			$sql_upd=oci_parse($conn_sispa,$update);
			oci_execute($sql_upd);
			*/
			$upd++;
		}else{
			//add new
			$insert = "insert into TKELASSEK (TAHUN,KODSEK,TING,KELAS,LABEL_KELAS)values('".$_SESSION['tahun']."','$KODSEKOLAH','$KODTINGKATANTAHUN','".oci_escape_string($NAMAKELAS)."','KB')";
			$sql_insrt=oci_parse($conn_sispa,$insert);
			oci_execute($sql_insrt);
			$xupd++;
		}
		
		
		################################## UPDATE/ADD TGURU_KELAS #########################
		$sql3 = "select * from TGURU_KELAS where KODSEK='$KODSEKOLAH' and TAHUN='".$_SESSION['tahun']."' and TING='$KODTINGKATANTAHUN' AND KELAS='".oci_escape_string($NAMAKELAS)."'";
		$stmt3=oci_parse($conn_sispa,$sql3);
	  	oci_execute($stmt3);
		//if($kodsek=='TEA5035')
			//echo "$sql3 <br>";
		if($dt3=oci_fetch_array($stmt3)){
			//update
			$update2 = "update TGURU_KELAS set TAHUN='".$_SESSION['tahun']."',NAMA='$NAMAGURU',LEVEL1='2',NOKP='$NOKPGURU',NAMASEK='$NAMASEK',KODSEK='$KODSEKOLAH',STATUSSEK='$JENISSEK',TING='$KODTINGKATANTAHUN' ,KELAS='".oci_escape_string($NAMAKELAS)."' where KODSEK='$KODSEKOLAH' and TAHUN='".$_SESSION['tahun']."' and TING='$KODTINGKATANTAHUN' AND KELAS='$NAMAKELAS'";
			$sql_upd2=oci_parse($conn_sispa,$update2);
			oci_execute($sql_upd2);
			//if($KODSEKOLAH=='TEA5035')
				//echo "$update2 <br>";
			$chklvl = oci_parse($conn_sispa,"select level1 from login where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
			//if($KODSEKOLAH=='TEA5035')
				//echo "select level1 from login where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU' <br>";
			oci_execute($chklvl);
			if($chk=oci_fetch_array($chklvl)){
				$levelguru = trim($chk["LEVEL1"]);
				if($levelguru == '3' or $levelguru == '4'){
					$sql_up=oci_parse($conn_sispa,"update login set level1='4' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
					oci_execute($sql_up);
				}else{
					$sql_up=oci_parse($conn_sispa,"update login set level1='2' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
					oci_execute($sql_up);
					//if($KODSEKOLAH=='TEA5035')
						//echo "update login set level1='2' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU' <br>";
				}
			}
			//echo $update2.'<br>';
			$upd2++;
			$up="";
		}else{
			//add new
			$insert2 = "insert into TGURU_KELAS (TAHUN,NOKP,NAMA,LEVEL1,NAMASEK,KODSEK,STATUSSEK,TING,KELAS)values('".$_SESSION['tahun']."','$NOKPGURU','$NAMAGURU','2','$NAMASEK','$KODSEKOLAH','$JENISSEK','$KODTINGKATANTAHUN','".oci_escape_string($NAMAKELAS)."')";
			//die($insert2);
			//if($KODSEKOLAH=='TEA5035')
				//echo "$insert2 <br>";
			$sql_insrt2=oci_parse($conn_sispa,$insert2);
			oci_execute($sql_insrt2);
			
			$chklvl = oci_parse($conn_sispa,"select level1 from login where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
			oci_execute($chklvl);
			//if($KODSEKOLAH=='TEA5035')
				//echo "select level1 from login where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU' <br>";
			if($chk=oci_fetch_array($chklvl)){
				$levelguru = trim($chk["LEVEL1"]);
				if($levelguru == '3' or $levelguru == '4'){
					$sql_up=oci_parse($conn_sispa,"update login set level1='4' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
					oci_execute($sql_up);
				}else{
					$sql_up=oci_parse($conn_sispa,"update login set level1='2' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
					oci_execute($sql_up);
					//if($KODSEKOLAH=='TEA5035')
						//echo "update login set level1='2' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU' <br>";
				}
			}
			//$sql_up=oci_parse($conn_sispa,"update login set level1='2' where kodsek='$KODSEKOLAH' and nokp='$NOKPGURU'");
			//oci_execute($sql_up);
			//echo $insert2.'<br>';
			$xupd2++;
			$up="";
		}			
	}//while
####################END TKELASSM###################################		
		echo "<center><b>SENARAI NAMA KELAS DAN GURU KELAS</b></center><br><br>";
		echo "<table width=\"600\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <th bgcolor=\"#ff9900\" width=\"10%\"scope=\"col\">BIL</th>\n";
		echo "   <td bgcolor=\"#ff9900\"><center><b>$tahap</b></center></td>\n";
		echo "    <th bgcolor=\"#ff9900\" width=\"20%\"scope=\"col\">KELAS</th>\n";
		echo "    <th bgcolor=\"#ff9900\" width=\"50%\" scope=\"col\">NAMA GURU</th>\n";
		echo "    <th bgcolor=\"#ff9900\" width=\"50%\" scope=\"col\">NOKP</th>\n";
		//echo "    <th bgcolor=\"#ff9900\" width=\"10%\"scope=\"col\">HAPUS</th>\n";
		echo "  </tr>\n";
		
		$q_kelas = OCIParse($conn_sispa,"SELECT ting,kelas FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' ORDER BY ting");
		//echo "SELECT ting,kelas FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' ORDER BY ting";
		OCIExecute($q_kelas);
		$bil=0;
		while (OCIFetch($q_kelas)) {
		$ting = OCIResult($q_kelas,"TING");//$rowk['ting'];
		$kelas = oci_escape_string(OCIResult($q_kelas,"KELAS"));//$rowk['kelas'];
		$kelas = htmlentities($kelas);
		$bil=$bil+1;
		echo "  <tr>\n";
		echo "  <td><center>$bil</center></td>";
		echo "  <td><center>".OCIResult($q_kelas,"TING")."</center></td>";
		echo "  <td>".str_replace('*','\'',OCIResult($q_kelas,"KELAS"))."</td>\n";
		
		$q_guru = "SELECT * FROM tguru_kelas WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas'";
		$stmt = OCIParse($conn_sispa,$q_guru);
		OCIExecute($stmt);
		$rowg=oci_fetch_array($stmt);
				
		if ($numg = count_row($q_guru)==0){
			echo "<td><img src=\"images/ko.png\" width=\"20\" height=\"20\"></td>\n";
			}
		else{
			$nama=$rowg[NAMA];
			$nokp=$rowg[NOKP];
			echo "<td>$nama</td>\n";
			echo "<td>$nokp</td>\n";
			}
		//echo "<td><a href=hapus_guru_kelas.php?data=".$rowg["NOKP"]."|".$kodsek."|".$_SESSION['tahun']."|".$ting."|".urlencode($kelas)."|".$rowg["LEVEL1"]."><center><img src = images/drop.png width=12 height=13 Alt=\"Hapus\" border=0></center></a></td>\n";
		echo "  </tr>\n";
		}
	//}///IF ATAS SKALI
echo "</th>\n";
echo "</tr>\n";
echo "</table></body>\n";
echo "<br><br><br><br>";
?>
</td>
<?php include 'kaki.php';?> 