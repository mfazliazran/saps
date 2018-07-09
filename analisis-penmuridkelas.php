<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include "input_validation.php";
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Pencapaian Murid Mengikut Kelas <font color="#FFFFFF">(Tarikh Kemaskini Program : 4/8/2011 4:57PM)</font></p>

<?php

if (isset($_POST['pep']))
{		
	$ting = validate($_POST['ting']);
	$kelas = validate($_POST['kelas']);
	
	$q_guru = "SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun= :s_tahun AND gk.kodsek= :s_kodsek AND gk.ting= :ting AND gk.kelas= :kelas AND gk.kodsek=ts.kodsek";
	$stmt = OCIParse($conn_sispa,$q_guru);
	oci_bind_by_name($stmt, ':ting', $ting);
	oci_bind_by_name($stmt, ':kelas', $kelas);
	oci_bind_by_name($stmt, ':s_tahun', $_SESSION['tahun']);
	oci_bind_by_name($stmt, ':s_kodsek', $_SESSION['kodsek']);
	OCIExecute($stmt);
	$parameter=array(":ting",":kelas",":s_tahun",":s_kodsek");
	$value=array($ting,$kelas,$_SESSION['tahun'],$_SESSION['kodsek']);
	$bilguru = kira_bil_rekod($q_guru,$parameter,$value);

	if ( $bilguru > 0 )
	{
		OCIFetch($stmt);
		$namagu = OCIResult($stmt,"NAMA"); 
		$namasek = OCIResult($stmt,"NAMASEK");
	}
	else {
			$q_nsek = OCIParse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='".$_SESSION['kodsek']."'");
			OCIExecute($q_nsek);
			OCIFetch($q_nsek);
			$namagu = "Tiada Guru Kelas"; 
			$namasek = OCIResult($q_nsek,"NAMASEK");
		 }

	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			location("analisis_penmuridkelassr.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
		case "P": case "T1": case "T2": case "T3":
			location("analisis_penmuridkelasmr.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
		case "T4": case "T5":
			location("analisis_penmuridkelasma.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
	}
	
}
else{
?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='analisis-penmuridkelas.php?ting=' + val;
		}
		</script>
		<?php
		$ting=validate($_GET['ting']);
		$kelas=validate($_GET['kelas']);
		switch ($ting)
		{
			case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
				$url = "analisis_penmuridkelassr.php";
				break;
			case "P": case "T1": case "T2": case "T3":
				$url="analisis_penmuridkelasmr.php";
				break;
			case "T4": case "T5":
				$url="analisis_penmuridkelasma.php";
				break;
		}
		
		echo " <center><h3>MENU<br>ANALISIS PENCAPAIAN MENGIKUT KELAS</h3></center>";
		echo " <center><b>SILA PILIH TINGKATAN/DARJAH</b></center>";
		echo "<br><br>";
		echo "<form method=\"post\" name='form' action='$url'>\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>TINGKATAN/DARJAH</td>\n";
		echo "  <td>KELAS</td>\n";
		echo "  <td>HANTAR</td>\n";
		echo " </tr>";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>\n";
		
		$ting=validate($_GET['ting']);
		$kelas=validate($_GET['kelas']);
	
		$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE kodsek='".$_SESSION['kodsek']."' AND tahun ='".$_SESSION['tahun']."' ORDER BY ting";
		$sql = OCIParse($conn_sispa,$SQL_tkelas);
		OCIExecute($sql);
		$num = count_row($SQL_tkelas);
			
		$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE kodsek= :s_kodsek AND tahun= :s_tahun AND ting= :ting ORDER BY kelas");
		oci_bind_by_name($kelas_sql, ':s_kodsek', $_SESSION["kodsek"]);
		oci_bind_by_name($kelas_sql, ':s_tahun', $_SESSION["tahun"]);
		oci_bind_by_name($kelas_sql, ':ting', $ting);
		OCIExecute($kelas_sql);
		
		echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih Tingkatan</option>";
		while(OCIFetch($sql)) { 
		if(OCIResult($sql,"TING")/*$noticia2['ting']*/==@$ting){echo "<option selected value='".OCIResult($sql,"TING")."'>".OCIResult($sql,"TING")."</option>"."<BR>";}
		else{echo  "<option value='".OCIResult($sql,"TING")."'>".OCIResult($sql,"TING")."</option>";}
		}
		echo "</select>";
		echo "</td>";
		echo "<td>";
		
		echo "<select name='kelas' ><option value=''>Pilih Kelas</option>";
		while(OCIFetch($kelas_sql)) { 
		if(OCIResult($kelas_sql,"KELAS")/*$noticia3['kelas']*/==@$kelas){echo "<option selected value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>"."<BR>";}
		else{echo  "<option value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>";}
		}
		
		
		echo "</td>";
		echo "<td>";
		echo "<input type='submit' name=\"pep\" value=\"Hantar\">";
		echo "</td>";
		echo "</form>";
}

?></tr></table>
</td>
<?php include 'kaki.php';?> 