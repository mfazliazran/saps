<?php

include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include "input_validation.php";

function count_row_oci_by_name5($sql,$val1,$val2,$val3,$val4,$val5,$param1,$param2,$param3,$param4,$param5){
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
	oci_bind_by_name($qic, $param3, $val3);
	oci_bind_by_name($qic, $param4, $val4);
	oci_bind_by_name($qic, $param5, $val5);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

function count_row_oci_by_name3($sql,$val1,$val2,$val3,$param1,$param2,$param3){
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
	oci_bind_by_name($qic, $param3, $val3);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Mata Pelajaran <font color="#FFFFFF">(Tarikh Kemaskini Program : 10/8/2011)</font></p>

<?php

if (isset($_POST["edit"]))
{	
	$t=validate($_POST['ting']);
	$k=validate($_POST['kelas']);
	$mp=validate($_POST['kodmp']);
	$lama=validate($_GET['data2']);
	list ($tahun, $nokp, $kodsek, $tingl, $kelasl, $kodmpl)=explode("/", $lama);
	//echo "$lama";
	$sql_subguru = "SELECT * FROM sub_guru WHERE tahun= :tahun AND kodsek= :kodsek AND ting= :ting AND kelas= :kelas AND kodmp= :kodmp";
	$stmt = oci_parse($conn_sispa,$sql_subguru);
	oci_bind_by_name($stmt, ':tahun', $tahun);
	oci_bind_by_name($stmt, ':kodsek', $kodsek);
	oci_bind_by_name($stmt, ':ting', $t);
	oci_bind_by_name($stmt, ':kelas', $k);
	oci_bind_by_name($stmt, ':kodmp', $mp);
	oci_execute($stmt);

	$bilsubguru = count_row_oci_by_name5($sql_subguru, $tahun, $kodsek, $t, $k, $mp, ":tahun", ":kodsek", ":ting", ":kelas", ":kodmp");
	if ($bilsubguru==0){
		$a = oci_parse($conn_sispa,"UPDATE sub_guru SET ting= :ting, kelas= :kelas, kodmp= :kodmp WHERE tahun= :tahun AND kodsek= :kodsek AND nokp= :nokp AND ting= :tingl AND kelas= :kelasl AND kodmp= :kodmpl");
		oci_bind_by_name($a, ':ting', $t);
		oci_bind_by_name($a, ':kelas', $k);
		oci_bind_by_name($a, ':kodmp', $mp);
		oci_bind_by_name($a, ':tahun', $tahun);
		oci_bind_by_name($a, ':kodsek', $kodsek);
		oci_bind_by_name($a, ':nokp', $nokp);
		oci_bind_by_name($a, ':tingl', $tingl);
		oci_bind_by_name($a, ':kelasl', $kelasl);
		oci_bind_by_name($a, ':kodmpl', $kodmpl);
		oci_execute($a);

		?> 
		<script>alert('DATA TELAH DIKEMASKINI')</script> <?php
		pageredirect("b_edit_sub_guru.php");
	} else {
				//echo "<th width=\"79%\" bgcolor=\"#FFFFFF\" align=\"center\" valign=\"top\" scope=\"col\">";
				?> <script>alert('Kelas Dan Mata Pelajaran Telah Ada Dalam DataBase')</script> <?php 
				pageredirect("b_edit_sub_guru.php");
			} 
		//////papar subjek
		$querysub = "SELECT ting,kelas,kodmp FROM sub_guru WHERE nokp= :nokp AND tahun= :year AND kodsek= :s_kodsek ORDER BY ting";
		$resultmp=oci_parse($conn_sispa,$querysub);
		oci_bind_by_name($resultmp, ':nokp', $nokp);
		oci_bind_by_name($resultmp, ':year', $year);
		oci_bind_by_name($resultmp, ':s_kodsek', $_SESSION["kodsek"]);
		oci_execute($resultmp);
		//$num=count_row($querysub);
		echo "<br><br><br><br>";
		echo "<center><h3>SENARAI KELAS DAN MATAPELAJARAN YANG DIAJAR</h3></center><br><br>";
		echo "<table align=\"center\" width=\"600\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
		echo "  <tr>\n";
		echo "    <th bgcolor=\"#ffcc66\" width=\"10%\"scope=\"col\">TINGKATAN</th>\n";
		echo "    <th bgcolor=\"#ffcc66\" width=\"20%\"scope=\"col\">KELAS</th>\n";
		echo "    <th bgcolor=\"#ffcc66\" width=\"50%\" scope=\"col\">MATA PELAJARAN / KOD LEMBAGA</th>\n";
		echo "  </tr>\n";
		
		while ($data=oci_fetch_array($resultmp)) {
		$ting = $data["TING"];//$row['ting'];
		$kelas = $data["KELAS"];//$row['kelas'];
		$kodmp = $data["KODMP"];//$row['kodmp'];
        $namamp="";
		if ($_SESSION['statussek']=="SR"){
			$querykod = "SELECT mp,kodlembaga FROM mpsr WHERE kod= :kodmp";
			$stmt = oci_parse($conn_sispa,$querykod);
			oci_bind_by_name($stmt, ':kodmp', $kodmp);
			oci_execute($stmt);
			if (oci_fetch($stmt)){
			  $namamp=oci_result($stmt,"MP");
			  $kodlem=oci_result($stmt,"KODLEMBAGA");
			}
		}
		
		if ($_SESSION['statussek']=="SM"){
			$querykod = "SELECT mp,kodlembaga FROM mpsmkc WHERE kod= :kodmp";
			$stmt = oci_parse($conn_sispa,$querykod);
			oci_bind_by_name($stmt, ':kodmp', $kodmp);
			oci_execute($stmt);
			if (oci_fetch($stmt)){
			  $namamp=oci_result($stmt,"MP");
			  $kodlem=oci_result($stmt,"KODLEMBAGA");
			}
		}
		
			
		echo "  <tr>\n";
		echo "  <td><center>".$ting."</center></td>";
		echo "  <td>".$kelas."</td>\n";
		echo "  <td>".$namamp." - ".$kodlem."</td>\n";
		echo "  </tr>\n";
		}		
}

else
{
	$qsubguru = "SELECT * FROM sub_guru WHERE nokp= :nokp AND tahun= :tahun AND kodsek= :s_kodsek ORDER BY ting";
	$rmpguru = oci_parse($conn_sispa,$qsubguru);
	oci_bind_by_name($rmpguru, ':nokp', $nokp);
	oci_bind_by_name($rmpguru, ':tahun', $_SESSION['tahun']);
	oci_bind_by_name($rmpguru, ':s_kodsek', $_SESSION["kodsek"]);
	oci_execute($rmpguru);
	$num = count_row_oci_by_name3($qsubguru, $nokp, $_SESSION['tahun'], $_SESSION["kodsek"], ":nokp", ":tahun", ":s_kodsek");
	
	if($num == 0){
			//echo "<br><br><br><br><br><br><br><br>";
			echo "<center><h3>KEMASKINI MATA PELAJARAN</h3></center>";
			echo "<br><br>";
			echo "<table width=\"500\" border =\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
			echo "  <tr>\n";
			echo "  <td colspan=\"4\"><center><font color =\"#ff0000\"><h3><br>Mata Pelajaran Belum Didaftarkan<br>Sila Daftar Mata Pelajaran</h3></font></center></td>\n";
			echo "  </tr>\n";
			echo "</table>\n";
			}
		
		else{
		//echo "<br><br><br><br>";
		echo "<center><h3>KEMASKINI MATA PELAJARAN GURU</h3></center>";
		echo "<br>";
		echo "<table align=\"center\" width=\"500\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"cccccc\">\n";
		echo "    <th scope=\"col\">TING</th>\n";
		echo "    <th scope=\"col\">KELAS</th>\n";
		echo "    <th scope=\"col\">MATA PELAJARAN / KOD LEMBAGA</th>\n";
		echo "    <th scope=\"col\">UBAHSUAI</th>\n";
		echo "    <th scope=\"col\">HAPUS</th>\n";
		echo "  </tr>\n";
		$i=0;
		while ($data=oci_fetch_array($rmpguru))
		{
			$ting = $data ["TING"];//$mpguru['ting'];
			$kelas = $data["KELAS"];//$mpguru['kelas'];
			$kodmp = $data["KODMP"];//$mpguru['kodmp'];
			$tingl = $data["TING1"];
			$kelasl = $data["KELAS1"];
			$kodmpl = $data["KODMP1"];
									
			echo "<form name=\"form_edit\" method=\"post\" action=\"b_edit_sub_guru.php?data2=".$_SESSION['tahun']."/".$nokp."/".$kodsek."/".$ting."/".$kelas."/".$kodmp."\">";
			echo "  <tr>\n";
			echo "  <td>\n";
			
			$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun = :tahun AND kodsek= :kodsek ORDER BY ting";
			$rs_tkelas = oci_parse($conn_sispa,$SQL_tkelas);
			oci_bind_by_name($rs_tkelas, ':tahun', $_SESSION['tahun']);
			oci_bind_by_name($rs_tkelas, ':kodsek', $kodsek);
			oci_execute($rs_tkelas);
			
			echo "<select name=\"ting\">";
			while($data=oci_fetch_array($rs_tkelas))
			{		
			    if ($ting==$data["TING"])
				  echo "<OPTION SELECTED VALUE=\"".$data["TING"]."\">".$data["TING"]."</OPTION>";
				else
				  echo "<OPTION VALUE=\"".$data["TING"]."\">".$data["TING"]."</OPTION>";
			}
			echo " </select></th>\n";
			echo "  <td>";
			
			$SQL_kelas = "SELECT DISTINCT kelas FROM tkelassek WHERE tahun= :tahun AND kodsek= :kodsek ORDER BY kelas";
			$rsetkelas = oci_parse($conn_sispa,$SQL_kelas);
			oci_bind_by_name($rsetkelas, ':tahun', $_SESSION['tahun']);
			oci_bind_by_name($rsetkelas, ':kodsek', $kodsek);
			oci_execute($rsetkelas);
					
			echo "<select name=\"kelas\">";
			while ($data=oci_fetch_array($rsetkelas))
			{	
			   if ($kelas==$data["KELAS"])
				echo "<OPTION SELECTED VALUE=\"".$data["KELAS"]."\">".$data["KELAS"]."</OPTION>";
			    else
				echo "<OPTION VALUE=\"".$data["KELAS"]."\">".$data["KELAS"]."</OPTION>";
			}
			echo " </select></th>\n";
			echo "  <td>";
			
			if ($_SESSION['statussek']=="SM"){
				//$mpSQL = "SELECT * FROM mpsmkc ORDER BY mp";
			if($ting == "P" or $ting == "T1" or $ting == "T2" or $ting == "T3"){
				$mpSQL = "SELECT KOD,MP,KODLEMBAGA FROM mpsmkc WHERE status_mp='1' AND kod IN (SELECT KODMP FROM sub_guru WHERE KELAS= :kelas AND TING= :ting) OR BARU IS NULL OR BARU='MR' ORDER BY mp";
			} else if($ting == "T4" or $ting == "T5"){
				$mpSQL = "SELECT KOD,MP,KODLEMBAGA FROM mpsmkc WHERE status_mp='1' AND kod IN (SELECT KODMP FROM sub_guru WHERE KELAS= :kelas AND TING= :ting) OR BARU IS NULL OR BARU='MA' ORDER BY mp";
			}
			}
			
			if ($_SESSION['statussek']=="SR"){
				$mpSQL = "SELECT KOD,MP,KODLEMBAGA FROM mpsr WHERE status_mp='1' ORDER BY mp";
			}

			$rs_mp = oci_parse($conn_sispa,$mpSQL);
			oci_bind_by_name($rs_mp, ':kelas', $kelas);
			oci_bind_by_name($rs_mp, ':ting', $ting);
			oci_execute($rs_mp);
			
			echo "<select name=\"kodmp\">";
			while($data=oci_fetch_array($rs_mp))
			{
			   if ($kodmp==$data["KOD"])
				echo "<OPTION SELECTED VALUE=\"".$data["KOD"]."\">".$data["MP"]." / ".$data["KODLEMBAGA"]."</OPTION>";
			   else	
				echo "<OPTION VALUE=\"".$data["KOD"]."\">".$data["MP"]." / ".$data["KODLEMBAGA"]."</OPTION>";
			}
	
			echo " </select>";
			if(($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1")){
			echo "  <td><center><input type=\"submit\" name=\"edit\" value=\"simpan\" width=12 height=13 Alt=\"Edit Markah\" border=0></center></a></td>\n";
			} else {
			echo "  <td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></a></td>\n";
			}
			
			if(($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1")) {
			echo "  <td><a href=hapus_sub_guru.php?data=".$_SESSION['tahun']."|".$nokp."|".$kodsek."|".$ting."|".urlencode($kelas)."|".$kodmp." onclick=\"return (confirm('Adakah anda pasti hapuskan kelas $ting $kelas subjek $kodmp ?.'))\"><center><img src = images/drop.png width=11 height=13 Alt=\"Hapus Subjek Guru\" border=0></center></a></td>\n";
			} else {
			echo "  <td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></a></td>\n";
			}
			
			echo "  </tr>\n";
			echo "</form>";
			$i++;
		}
	}
}



echo "</table>\n";
//footer//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
</td>
<?php include 'kaki.php';?> 