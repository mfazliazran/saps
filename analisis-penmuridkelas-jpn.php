<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Pencapaian Murid Mengikut Kelas <font color="#FFFFFF">(Tarikh Kemaskini Program : 4/8/2011 4:57PM)</font></p>

<?php

if (isset($_POST['pep']))
{		
	$ting = $_POST['ting'];
	$kelas = $_POST['kelas'];
	
	$q_guru = "SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='".$_SESSION['tahun']."' AND gk.kodsek='".$_SESSION['kodsek2']."' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek";
	//die($q_guru);
	$stmt = OCIParse($conn_sispa,$q_guru);
	OCIExecute($stmt);
	$bilguru = count_row($q_guru);
	if ( $bilguru > 0 )
	{
		OCIFetch($stmt);
		$namagu = OCIResult($stmt,"NAMA");//$row[nama]; 
		$namasek = OCIResult($stmt,"NAMASEK");//$row[namasek];
	}
	else {
			$q_nsek = OCIParse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='".$_SESSION['kodsek2']."'");
			OCIExecute($q_nsek);
			OCIFetch($q_nsek);
			$namagu = "Tiada Guru Kelas"; 
			$namasek = OCIResult($q_nsek,"NAMASEK");//$row[namasek];
		 }

	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			location("analisis_penmuridkelassr-jpn.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
		case "P": case "T1": case "T2": case "T3":
			location("analisis_penmuridkelasmr-jpn.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
		case "T4": case "T5":
			location("analisis_penmuridkelasma-jpn.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
	}
	
}
else{
?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='analisis-penmuridkelas-jpn.php?ting=' + val;
		}
		</script>
		<?php
		$ting=$_GET['ting'];
		$kelas=$_GET['kelas'];
		switch ($ting)
		{
			case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
				//location("analisis_mpkelassr.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
				$url = "analisis_penmuridkelassr-jpn.php";
				break;
			case "P": case "T1": case "T2": case "T3":
				//location("analisis_mpkelasmr.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
				$url="analisis_penmuridkelasmr-jpn.php";
				break;
			case "T4": case "T5":
				//location("analisis_mpkelasma.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
				$url="analisis_penmuridkelasma-jpn.php";
				break;
		}
		
		//echo "<br><br><br><br><br>";
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
		
		$ting=$_GET['ting'];
		$kelas=$_GET['kelas'];
	
		$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE kodsek='".$_SESSION['kodsek2']."' AND tahun ='".$_SESSION['tahun']."' ORDER BY ting";
		$sql = OCIParse($conn_sispa,$SQL_tkelas);
		OCIExecute($sql);
		//$temprs_mp = mysql_query($SQL_tkelas);
		$num = count_row($SQL_tkelas);
			
		$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE kodsek='".$_SESSION['kodsek2']."' AND tahun ='".$_SESSION['tahun']."' AND ting='$ting' ORDER BY kelas");
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