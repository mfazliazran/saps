<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Pencapaian Sekolah Mengikut Kelas</p>

<?php

/*if (isset($_POST['pep']))
{		
	$ting = $_POST['ting'];
	$jenis = $_POST['jenis'];
	location("pencapaian_kelas.php?ting=$ting&&jenis=$jenis");
	
	$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='".$_SESSION['tahun']."' AND gk.kodsek='".$_SESSION['kodsek']."' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek");
	OCIExecute($q_guru);
	OCIFetch($q_guru);
	$namagu = OCIResult($q_guru,"NAMA");//$row[nama]; 
	$namasek = OCIResult($q_guru,"NAMASEK");//$row[namasek];
	//location("laporan_kelas.php");

	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			location("pencapaian_kelas.php?ting=$ting&&jenis=$jenis");
			break;
		case "P": case "T1": case "T2": case "T3":
			location("analisis_mpkelasmr.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
		case "T4": case "T5":
			location("analisis_mpkelasmr.php?ting=$ting&&kelas=$kelas&&namaguru=$namagu&&namasekolah=$namasek");
			break;
	}
	
}
else{*/
		?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='pencapaian_sekolahmr.php?ting=' + val;
		}
		</script>
		<?php
		echo "<br><br><br><br><br>";
		echo " <center><h3>MENU<br>PENCAPAIAN PELAJAR MENGIKUT KELAS</h3></center>";
		echo " <center><b>SILA PILIH TINGKATAN/DARJAH DAN JENIS PEPERIKSAAN</b></center>";
		echo "<br><br>";
		echo "<form method=\"post\" target='_blank' action='pencapaian_kelasmr.php'>\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>TINGKATAN/DARJAH</td>\n";
		echo "  <td>JENIS PEPERIKSAAN</td>\n";
		echo "  <td>HANTAR</td>\n";
		echo " </tr>";
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		echo "  <td>\n";
			
		$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY ting";
		$stmt = OCIParse($conn_sispa,$SQL_tkelas);
		OCIExecute($stmt);
		$num = count_row($SQL_tkelas);
		$kelas_sql = OCIParse($conn_sispa,"SELECT jenis,kod FROM JPEP WHERE kod='U1' OR kod='U2' OR kod='PAT' OR kod='PPT' OR kod='PMRC'");
		OCIExecute($kelas_sql);
		echo "<select name='ting' ><option value=''>Pilih Tingkatan/Darjah</option>";
		while(OCIFetch($stmt)) 
		{ 
			if(OCIResult($stmt,"TING")/*$noticia2['ting']*/==@$ting){echo "<option selected value='".OCIResult($stmt,"TING")."'>".OCIResult($stmt,"TING")."</option>"."<BR>";}
			else{echo  "<option value='".OCIResult($stmt,"TING")."'>".OCIResult($stmt,"TING")."</option>";}
		}
		echo "</select>";
		echo "</td>";
		echo "<td>";
		
		echo "<select name='jenis' ><option value=''>Pilih Jenis Peperiksaan</option>";
		while(OCIFetch($kelas_sql)) { 
			if(OCIResult($kelas_sql,"JENIS")/*$noticia3['kelas']*/==@$ting){echo "<option selected value='".OCIResult($kelas_sql,"KOD")."'>".OCIResult($kelas_sql,"JENIS")."</option>"."<BR>";}
			else{echo  "<option value='".OCIResult($kelas_sql,"KOD")."'>".OCIResult($kelas_sql,"JENIS")."</option>";}
		}
		echo "</td>";
		echo "<td>";
		echo "<input type='submit' name=\"pep\" value=\"Hantar\">";
		echo "</td>";
		echo "</form>";
//}
?></tr></table>
</td>
<?php include 'kaki.php';?> 