<?php
include 'auth.php';
include_once( 'config.php');
include 'kepala.php';
include 'menu.php';
include "input_validation.php";

function count_row_oci_by_name($sql,$val1,$val2,$param1,$param2){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	//echo "POS $pos :- $sql";
	$newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

	$qic = oci_parse($conn_sispa,$newsql);
	oci_bind_by_name($qic, $param1, $val1);
	oci_bind_by_name($qic, $param2, $val2);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

$tarikhsekarang = date("Y-m-d");
$sqlbukasekolah = "SELECT * FROM bukasekolah WHERE tarikh_tutup >= $tarikhsekarang";
$resque=oci_parse($conn_sispa,$sqlbukasekolah);
oci_execute($resque);
while($rowan = oci_fetch_array($resque)){
	if ($rowan["KODSEK"] == $_SESSION["kodsek"]){
	$kodsekbuka = "1";
	}
}

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Daftar Mata Pelajaran</p>
<script type='text/javascript'>

function formValidator(){
	// Make quick references to our fields
	var ting = document.getElementById('ting');
	var kelas = document.getElementById('kelas');
	var mp = document.getElementById('mp');
	var bilammp = document.getElementById('bilammp');
	if(notEmpty(ting, "Pilih Tingkatan")){
		if(notEmpty(kelas, "Pilih Kelas")){
			if(notEmpty(mp, "Pilih Mata Pelajaran")){
				if(notEmpty(bilammp, "Isikan Bilangan Murid")){
					return true;
					}
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

//////semak subjek guru
$querykelas = "SELECT DISTINCT ting FROM tkelassek WHERE kodsek= :kodsek AND tahun= :tahun"; 
$bilkelas = count_row_oci_by_name($querykelas, $kodsek, $_SESSION["tahun"], ":kodsek", ":tahun");

if($bilkelas ==0){
		echo "<br><br><br><br><br><br><br><br>";
		echo "<center><h3>DAFTAR MATA PELAJARAN</h3></center>";
		echo "<br><br>";
		echo "<table width=\"500\" border =\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "  <td colspan=\"4\"><center><font color =\"#ff0000\"><h3><br>Senarai Kelas Belum Didaftarkan<br>Sila Hubungi SU Peperiksaan Sekolah Anda<br> untuk mendaftarkan kelas di menu Senarai Kelas dan Guru Kelas.</h3></font></center></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		}
//////end semak subjek guru
else{
		?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='d_sub_guru.php?ting=' + val;
		}
		</script>
		<?php
		echo "<center><h3>DAFTAR KELAS DAN MATAPELAJARAN YANG DIAJAR</h3></center>";
		echo "<br>";
		echo "<form name=\"form1\" method=\"post\" onsubmit=\"return formValidator()\" action=\"masuk_sub_guru.php\">\n";
		echo "<input name=\"tahun\" type=\"hidden\" value=\"".$_SESSION['tahun']."\" size=\"4\">";
		echo "<input name=\"kodsek\" type=\"hidden\" value=\"$kodsek\" size=\"8\">";
		print "<input name=\"nama\" type=\"hidden\" readonly value=\"$nama\" size=\"40\">";
		print "<input name=\"nokp\" type=\"hidden\" readonly value=\"$nokp\" size=\"15\">";
				
		echo "  <table width=\"500\" border=\"1\" bordercolor=\"#ccccccc\" align=\"center\" cellspacing=\"\" cellpadding=\"10\">\n";
		echo "    <tr>\n";
		echo "   <td bgcolor=\"#ffcc66\"><center>Tingkatan</center></td>\n";
		echo "   <td bgcolor=\"#ffcc66\"><center>Kelas</center></td>\n";
		echo "   <td bgcolor=\"#ffcc66\"><center>Mata Pelajaran / Kod Lembaga</center></td>\n";
		echo "   <td bgcolor=\"#ffcc66\"><center>Bil Murid Ambil</center></td>\n";
		echo "   <td bgcolor=\"#ffcc66\"><center>Hantar</center></td>\n";
		echo "    </tr>\n";
		echo "    <tr>\n";
		echo "  <td><center>\n";
		
		$ting=validate($_GET['ting']);
		$kelas=validate($_GET['kelas']);
	
		$SQL_tkelas = oci_parse($conn_sispa,"SELECT DISTINCT ting FROM tkelassek WHERE tahun= :tahun AND kodsek= :kodsek ORDER BY ting");
		oci_bind_by_name($SQL_tkelas, ':tahun', $_SESSION['tahun']);
		oci_bind_by_name($SQL_tkelas, ':kodsek', $kodsek);
		oci_execute($SQL_tkelas);

		echo "<form method=post name='f1' action='d_sub_guru.php'>";
	
		echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''></option>";
		while($noticia2 = oci_fetch_array($SQL_tkelas)) { 
		if($noticia2['TING']==$ting){echo "<option selected value='$noticia2[TING]'>$noticia2[TING]</option>"."<BR>";}
		else{echo  "<option value='$noticia2[TING]'>$noticia2[TING]</option>";}
		}
		echo "</select>";
		echo "</td></center>";
		echo "<td><center>";
		
		$kelas_sql = oci_parse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting ORDER BY kelas");
		oci_bind_by_name($kelas_sql, ':tahun', $_SESSION['tahun']);
		oci_bind_by_name($kelas_sql, ':kodsek', $kodsek);
		oci_bind_by_name($kelas_sql, ':ting', $ting);
		oci_execute($kelas_sql);
		echo "<select name='kelas' ><option value=''></option>";
		while($noticia3 = oci_fetch_array($kelas_sql)) { 
		if($noticia3['KELAS']==$kelas){echo "<option selected value='$noticia2[KELAS]'>$noticia2[KELAS]</option>"."<BR>";}
		else{echo  "<option value='$noticia3[KELAS]'>$noticia3[KELAS]</option>";}
		}
		echo "</td></center>";
		//////////        Starting of second drop downlist /////////
		echo "<td><center>";
		echo "<select name=\"mp\">";
		echo "<OPTION VALUE=\"\"></OPTION>";
		
		if ($_SESSION['statussek']=="SM"){
			if($ting == "P" or $ting == "T1" or $ting == "T2" or $ting == "T3"){
				$strSQL = "SELECT KOD,MP,KODLEMBAGA FROM mpsmkc WHERE status_mp='1' AND kod IN (SELECT KODMP FROM sub_guru WHERE KELAS= :kelas AND TING= :ting) OR BARU IS NULL OR BARU='MR' ORDER BY mp";
			} else if($ting == "T4" or $ting == "T5"){
				$strSQL = "SELECT KOD,MP,KODLEMBAGA FROM mpsmkc WHERE status_mp='1' AND kod IN (SELECT KODMP FROM sub_guru WHERE KELAS= :kelas AND TING=:ting) OR BARU IS NULL OR BARU='MA' ORDER BY mp";
			}
		}
		if ($_SESSION['statussek']=="SR"){
				$strSQL = "SELECT KOD,MP,KODLEMBAGA FROM mpsr where status_mp='1' ORDER BY mp";
		}
		
		$rs = oci_parse($conn_sispa,$strSQL);
		oci_bind_by_name($rs, ':kelas', $kelas);
		oci_bind_by_name($rs, ':ting', $ting);
		oci_execute($rs);
		$nr = count_row_oci_by_name($strSQL, $kelas, $ting, ":kelas", ":ting");
		for ($k=0; $k<$nr; $k++) {
			$r = oci_fetch_array($rs);
			echo "<OPTION VALUE=\"".$r["KOD"]."\">".$r["MP"].' / '.$r["KODLEMBAGA"]."</OPTION>";
			}

		echo "</select>";
		echo "</td></center>";
		echo "<td><center><input name=\"bilammp\" type=\"text\" id=\"bilammp\" size=\"2\" maxlength=\"3\"></center></td>";
		echo "<td><center>";
		if(($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup'] == "1")){
		echo "<center><input type=\"submit\" name=\"Submit\" value=\"Hantar\"></center>\n";
		} else if ($kodsekbuka == "1"){
		echo "<center><input type=\"submit\" name=\"Submit\" value=\"Hantar\"></center>\n";
		} else {
		echo "<center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center>\n";
		}
		echo "</td></center>";
		echo "</table>";
		echo "<br><br>";
		echo "</form>\n";
		//////papar subjek		
		echo "<b><center>SENARAI KELAS DAN MATAPELAJARAN YANG DIAJAR</center></b><br><br>";
		echo "<table width=\"660\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <th bgcolor=\"#ffcc66\" width=\"10%\"scope=\"col\">TINGKATAN</th>\n";
		echo "    <th bgcolor=\"#ffcc66\" width=\"20%\"scope=\"col\">KELAS</th>\n";
		echo "    <th bgcolor=\"#ffcc66\" width=\"50%\" scope=\"col\">MATA PELAJARAN / KOD LEMBAGA</th>\n";
		echo "    <th bgcolor=\"#ffcc66\" width=\"50%\" scope=\"col\">BIL MURID AMBIL</th>\n";
		echo "  </tr>\n";
		
		$querysub = "SELECT TING,KELAS,KODMP,BILAMMP FROM sub_guru WHERE tahun= :year AND nokp= :nokp and kodsek= :s_kodsek ORDER BY ting";
		$resultmp=oci_parse($conn_sispa,$querysub);
		oci_bind_by_name($resultmp, ':year', $year);
		oci_bind_by_name($resultmp, ':nokp', $nokp);
		oci_bind_by_name($resultmp, ':s_kodsek', $_SESSION["kodsek"]);
		oci_execute($resultmp);
		while ($data = oci_fetch_array($resultmp)) {
		$ting = $data['TING'];
		$kelas = $data['KELAS'];
		$kodmp = $data['KODMP'];
		$bilamp = $data['BILAMMP'];

		if ($_SESSION['statussek']=="SR"){
			$querykod = oci_parse($conn_sispa,"SELECT mp,kodlembaga FROM mpsr WHERE kod= :kodmp");
			oci_bind_by_name($querykod, ':kodmp', $kodmp);
			oci_execute($querykod);
		    $resultkod = oci_fetch_array($querykod);
		    $namamp=$resultkod['MP'];
			$kodlem=$resultkod['KODLEMBAGA'];
		}
		
		if ($_SESSION['statussek']=="SM"){
			$querykod = oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod= :kodmp ");
			oci_bind_by_name($querykod, ':kodmp', $kodmp);
			oci_execute($querykod);
		    $resultkod = oci_fetch_array($querykod);
		    $namamp=$resultkod['MP'];
			$kodlem=$resultkod['KODLEMBAGA'];
		}
		
		echo "  <tr>\n";
		echo "  <td><center>$ting</center></td>";
		echo "  <td>$kelas</td>\n";
		echo "  <td>$namamp / $kodlem</td>\n";
		echo "  <td><center>&nbsp;$bilamp</center></td>";
		echo "  </tr>\n";
		}
	}
echo "</th>\n";
echo "</tr>\n";
echo "</table>\n";


if(($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup'] == "1")) {
echo "<center><br><br><a href=b_edit_daftar_bil_mp.php>Klik disini untuk edit bilangan murid setiap subjek</a></center>\n";
} else if ($kodsekbuka == "1"){
echo "<center><br><br><a href=b_edit_daftar_bil_mp.php>Klik disini untuk edit bilangan murid setiap subjek</a></center>\n";
} else {
echo "<center><br><br>Klik disini untuk edit bilangan murid setiap subjek</center>\n";
}
?>
</td>
<?php include 'kaki.php';?> 