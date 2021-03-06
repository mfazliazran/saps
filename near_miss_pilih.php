<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include "input_validation.php";
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Near Miss</p>

<?php

if (isset($_POST['tgkls']))
{		
	$ting = validate($_POST['ting']);
	$kelas = validate($_POST['kelas']);
	$tahun = $_SESSION['tahun'];
	$kodsek = $_SESSION['kodsek'];
	
	$q_guru = "SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='$tahun' AND gk.kodsek='$kodsek' AND gk.ting= :ting AND gk.kelas= :kelas AND gk.kodsek=ts.kodsek";
	$stmt = OCIParse($conn_sispa,$q_guru);
	oci_bind_by_name($stmt, ':ting', $ting);
	oci_bind_by_name($stmt, ':kelas', $kelas);
	OCIExecute($stmt);
	$parameter = array(":ting",":kelas");
	$value = array($ting,$kelas);
	$bilguru = kira_bil_rekod($q_guru,$parameter,$value);
	OCIFetch($stmt);
	if ( $bilguru == 0 )
	{			
		$namagu = "Tiada Guru Kelas"; 
		$namasek = OCIResult($stmt,"NAMASEK");
	}
	else {
			$namagu = OCIResult($stmt,"NAMA"); 
			$namasek = OCIResult($stmt,"NAMASEK");
		 }
		 
	$tkt = array("P" => "PERALIHAN","T1" => "TINGKATAN SATU","T2" => "TINGKATAN DUA","T3" => "TINGKATAN TIGA","T4" => "TINGKATAN EMPAT","T5" => "TINGKATAN LIMA",
		   	     "D1" => "TAHUN SATU","D2" => "TAHUN DUA","D3" => "TAHUN TIGA","D4" => "TAHUN EMPAT","D5" => "TAHUN LIMA","D6" => "TAHUN ENAM");

	$tingkatan = $tkt["$ting"] ;

	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			location("near_misssr.php?ting=$ting&&kelas=$kelas");
			break;
		case "P": case "T1": case "T2": case "T3":
			location("near_missmr.php?ting=$ting&&kelas=$kelas");
			break;
		case "T4": case "T5":
			location("near_missma.php?ting=$ting&&kelas=$kelas");
			break;
	}
} 
else {
		?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='near_miss_pilih.php?ting=' + val;
		}
		</script>
		<?php
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
		echo " <center><h3>NEAR MISS CEMERLANG<br></h3></center>";
		echo " <center><b>SILA PILIH TINGKATAN/DARJAH DAN KELAS</b></center>";
		echo "<br><br>";
		echo "<form method=\"post\">\n";
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
	
		$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY ting";
		$sql = OCIParse($conn_sispa,$SQL_tkelas);
		OCIExecute($sql);
		$num = count_row($SQL_tkelas);
		$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun= :s_tahun AND kodsek= :s_kodsek AND ting= :ting ORDER BY kelas");
		oci_bind_by_name($kelas_sql, ':s_tahun', $_SESSION['tahun']);
		oci_bind_by_name($kelas_sql, ':s_kodsek', $_SESSION['kodsek']);
		oci_bind_by_name($kelas_sql, ':ting', $ting);
		OCIExecute($kelas_sql);
		echo "<form method=post name='f1' action='near_miss_pilih.php'>";
		echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih Tingkatan/Darjah</option>";
		while(OCIFetch($sql)) { 
			if(OCIResult($sql,"TING")==@$ting){echo "<option selected value='".OCIResult($sql,"TING")."'>".OCIResult($sql,"TING")."</option>"."<BR>";}
			else{echo  "<option value='".OCIResult($sql,"TING")."'>".OCIResult($sql,"TING")."</option>";}
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
		echo "<input type='submit' name=\"tgkls\" value=\"Hantar\">";
		echo "</td>";
		echo "</form>";
}

?> </tr></table>
</td>
<?php include 'kaki.php';?> 