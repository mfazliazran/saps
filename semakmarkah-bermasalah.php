<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Markah Bermasalah</p>

<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='semakmarkah-bermasalah.php?ting=' + val;
		}
		</script>
<?php
$jpep = $_SESSION['jpep'];
if($jpep=='LNS01') {
echo "<center><h3>HANYA UNTUK PEPERIKSAAN AM SAHAJA<br><font color =\"#ff0000\">".jpep($jpep)."</font><br>TIDAK DIBENARKAN</h3></center>";

} else {
echo "<br><br><br><br>";
echo " <center><h3>SEMAKAN MARKAH BERMASALAH ".pep($_SESSION['jpep'])."</h3></center>";
echo " <center><b>SILA PILIH TINGKATAN/DARJAH DAN KELAS</b></center>";
echo "<br><br>";
echo "<form method=\"post\" action=\"data-semakmarkah-bermasalah.php\">";
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
	
		$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY ting";
		//echo $SQL_tkelas ;
		$stmt = OCIParse($conn_sispa,$SQL_tkelas);
		OCIExecute($stmt);
		$num = count_row($SQL_tkelas);
		
		$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND ting='$ting' ORDER BY kelas");
		OCIExecute($kelas_sql);
		
echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih Tingkatan/Darjah</option>";
		while(OCIFetch($stmt)) 
		{ 
			if(OCIResult($stmt,"TING")==@$ting){echo "<option selected value='".OCIResult($stmt,"TING")."'>".OCIResult($stmt,"TING")."</option>"."<BR>";}
			else{echo  "<option value='".OCIResult($stmt,"TING")."'>".OCIResult($stmt,"TING")."</option>";}
		}
		echo "</select>";
		echo "</td>";
	echo "<td>";
		
		echo "<select name='kelas' ><option value=''>Pilih Kelas</option>";
		while(OCIFetch($kelas_sql)) { 
			if(OCIResult($kelas_sql,"KELAS")==@$kelas){echo "<option selected value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>"."<BR>";}
			else{echo  "<option value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>";}
		}

		echo "</td>";
echo "<td>";
echo "<input name=\"post\" type=\"hidden\" readonly value=\"1\">";
echo "<input type='submit' name=\"pep\" value=\"Hantar\">";
echo "</td></tr></table>";
echo "</form>";
echo "<br><br>";
echo"<center><b>Utiliti ini dibangunakan bagi memudahkan guru menyemak dan menghapuskan data murid bermasalah didalam table data markah peperiksaan.<br>
Terdapat banyak kes guru kelas yang tersilap meletakkan murid ke dalam kelas yang betul dan juga masalah di no.k/p atau sijil murid.<br>
Utiliti ini bertujuan menghapuskan data markah yang bertambah didalam satu-satu kelas.<br>
Contohnya murid Kelas T4 MURNI mempunyai 30 orang murid, apabila di buat analisis murid Kelas T4 MURNI mempunyai 31 oarang murid.</b></center>";
echo"<br>";
echo"<center><b>SILA PROSES SEMULA MARKAH JIKA ANDA TELAH MENGHAPUSKAN DATA MARKAH MURID<b></center> ";
}
function pep($kodpep){
	switch ($kodpep){
		case "U1":
		$npep="UJIAN 1";
		break;
		case "U2":
		$npep="UJIAN 2";
		break;
		case "PAT":
		$npep="PEPERIKSAAN AKHIR TAHUN";
		break;
		case "PPT":
		$npep="PEPERIKSAAN PERTENGAHAN TAHUN";
		break;
		case "PMRC":
		$npep="PEPERIKSAAN PERCUBAAN PMR";
		break;
		case "SPMC":
		$npep="PEPERIKSAAN PERCUBAAN SPM";
		break;
	}
return $npep; 
}

?>
</td>
<?php include 'kaki.php';?> 