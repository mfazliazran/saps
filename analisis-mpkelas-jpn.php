<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Mata Pelajaran Mengikut Kelas</p>

<?php

if (isset($_POST['pep']))
{		
	$ting = $_POST['ting'];
	$kelas = $_POST['kelas'];
	
	$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='".$_SESSION['tahun']."' AND gk.kodsek='".$_SESSION['kodsek2']."' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek");
	OCIExecute($q_guru);
	OCIFetch($q_guru);
	$namagu = OCIResult($q_guru,"NAMA");//$row[nama]; 
	$namasek = OCIResult($q_guru,"NAMASEK");//$row[namasek];

	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			location("analisis_mpkelassr-jpn.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
		case "P": case "T1": case "T2": case "T3":
			location("analisis_mpkelasmr-jpn.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
		case "T4": case "T5":
			location("analisis_mpkelasma-jpn.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
	}
	
}
else{
		?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='analisis-mpkelas-jpn.php?ting=' + val;
		}
		</script>
		<?php
		$ting=$_GET['ting'];
		$kelas=$_GET['kelas'];
		switch ($ting)
		{
			case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
				//location("analisis_mpkelassr.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
				$url = "analisis_mpkelassr-jpn.php";
				break;
			case "P": case "T1": case "T2": case "T3":
				//location("analisis_mpkelasmr.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
				$url="analisis_mpkelasmr-jpn.php";
				break;
			case "T4": case "T5":
				//location("analisis_mpkelasma.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
				$url="analisis_mpkelasma-jpn.php";
				break;
		}
		//echo "<br><br><br><br><br>";
		echo " <center><h3>MENU<br>ANALISIS MATA PELAJARAN MENGIKUT KELAS</h3></center>";
		echo " <center><b>SILA PILIH TINGKATAN/DARJAH DAN KELAS</b></center>";
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
			
		$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek2']."' ORDER BY ting";
		$stmt = OCIParse($conn_sispa,$SQL_tkelas);
		OCIExecute($stmt);
		$num = count_row($SQL_tkelas);
		
		$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek2']."' AND ting='$ting' ORDER BY kelas");
		OCIExecute($kelas_sql);
		
		echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih Tingkatan/Darjah</option>";
		while(OCIFetch($stmt)) 
		{ 
			if(OCIResult($stmt,"TING")/*$noticia2['ting']*/==@$ting){echo "<option selected value='".OCIResult($stmt,"TING")."'>".OCIResult($stmt,"TING")."</option>"."<BR>";}
			else{echo  "<option value='".OCIResult($stmt,"TING")."'>".OCIResult($stmt,"TING")."</option>";}
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

/////////////
?></tr></table>
</td>
<?php include 'kaki.php';?> 