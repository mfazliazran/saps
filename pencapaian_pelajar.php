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

<SCRIPT language=JavaScript>
	function reload(form){
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='pencapaian_pelajar.php?ting=' + val;
	}
</script>

<?php
	echo " <center><h3>MENU<br>PENCAPAIAN PELAJAR MENGIKUT KELAS</h3></center>";
	echo " <center><b>SILA PILIH TINGKATAN/DARJAH DAN JENIS PEPERIKSAAN</b></center>";
	echo "<br><br>";
	echo "<form method=\"post\" target='_blank' action='pencapaian_kelas.php'>\n";
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
	echo "<select name='ting' ><option value=''>Pilih Tingkatan/Darjah</option>";
	while(OCIFetch($stmt)) { 
		if(OCIResult($stmt,"TING")==@$ting){
			echo "<option selected value='".OCIResult($stmt,"TING")."'>".OCIResult($stmt,"TING")."</option>"."<BR>";
		}
		else{
			echo  "<option value='".OCIResult($stmt,"TING")."'>".OCIResult($stmt,"TING")."</option>";
		}
	}
	echo "</select>";
	echo "</td>";
	echo "<td>";
	if($jsek=="SR")
		$kod = "OR kod='UPSRC'";
	elseif($jsek=="SM")
		$kod = "OR kod='PMRC' OR kod='SPMC'";

	$kelas_sql = OCIParse($conn_sispa,"SELECT jenis,kod FROM JPEP WHERE kod='U1' OR kod='U2' OR kod='PAT' OR kod='PPT' $kod ORDER BY rank");
	OCIExecute($kelas_sql);
	echo "<select name='jenis' ><option value=''>Pilih Jenis Peperiksaan</option>";
	while(OCIFetch($kelas_sql)) { 
		if(OCIResult($kelas_sql,"JENIS")==@$ting){
			echo "<option selected value='".OCIResult($kelas_sql,"KOD")."'>".OCIResult($kelas_sql,"JENIS")."</option>"."<BR>";
		}
		else{
			echo  "<option value='".OCIResult($kelas_sql,"KOD")."'>".OCIResult($kelas_sql,"JENIS")."</option>";
		}
	}
	echo "</td>";
	echo "<td>";
	echo "<input type='submit' name=\"pep\" value=\"Hantar\">";
	echo "</td>";
	echo "</form>";
?>
		</tr>
	</table>
</td>

<?php include 'kaki.php';?> 