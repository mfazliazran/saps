<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Slip Keputusan Murid</p>

<?php

if (isset($_POST['pep']))
{		
	$ting = $_POST['ting'];
	$kelas = $_POST['kelas'];
	
	$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.kodsek='".$_SESSION['kodsek2']."' AND gk.tahun='".$_SESSION['tahun']."' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek");
	oci_execute($q_guru);
	$row=oci_fetch_array($q_guru);
	$namagu = $row["NAMA"]; 
	$namasek = $row["NAMASEK"];
	$tkt = array("P" => "PERALIHAN","T1" => "TINGKATAN SATU","T2" => "TINGKATAN DUA","T3" => "TINGKATAN TIGA","T4" => "TINGKATAN EMPAT","T5" => "TINGKATAN LIMA",
		   	     "D1" => "TAHUN SATU","D2" => "TAHUN DUA","D3" => "TAHUN TIGA","D4" => "TAHUN EMPAT","D5" => "TAHUN LIMA","D6" => "TAHUN ENAM");

	$tingkatan = $tkt["$ting"] ;

	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			location("slip_namadminsr-jpn.php?ting=$ting&kelas=".urlencode($kelas)."&&namaguru=$namagu&&tingkatan=$tingkatan&&namasekolah=$namasek");
			break;
		case "P": case "T1": case "T2": case "T3":
			location("slip_namadminmr-jpn.php?ting=$ting&kelas=".urlencode($kelas)."");//&&namaguru=$namagu&&tingkatan=$tingkatan&&namasekolah=$namasek");
			break;
		case "T4": case "T5":
			location("slip_namadminma-jpn.php?ting=$ting&kelas=".urlencode($kelas)."");//&&namaguru=$namagu&&tingkatan=$tingkatan&&namasekolah=$namasek");
			break;
	}
}
else{
?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='slip-namadmin-jpn.php?ting=' + val;
		}
		</script>
		<?php
		
		echo "<br><br><br><br><br>";
		echo " <center><h3>MENU<br>SLIP PEPERIKSAAN MENGIKUT KELAS</h3></center>";
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
		
		$ting=$_GET['ting'];
		$kelas=$_GET['kelas'];
	
		$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek2']."' ORDER BY ting";
		$stmt = oci_parse($conn_sispa,$SQL_tkelas);
		oci_execute($stmt);
		//$temprs_mp = mysql_query($SQL_tkelas);
		//$num = count_row($SQL_tkelas);
		if($ting<>""){
		$kelas_sql = oci_parse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek2']."' AND ting='$ting' ORDER BY kelas");
		oci_execute($kelas_sql);
		}
		echo "<form method=post name='f1' action='slip-namaadmin-jpn.php'>";
	
		echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih Tingkatan</option>";
		while($noticia2 = oci_fetch_array($stmt)) { 
		if($noticia2["TING"]==@$ting){echo "<option selected value='".$noticia2["TING"]."'>".$noticia2["TING"]."</option>"."<BR>";}
		else{echo  "<option value='".$noticia2["TING"]."'>".$noticia2["TING"]."</option>";}
		}
		echo "</select>";
		echo "</td>";
		echo "<td>";
		
		echo "<select name='kelas' ><option value=''>Pilih Kelas</option>";
		if($ting<>""){
		while($noticia3 = oci_fetch_array($kelas_sql)) { 
		if($noticia3["KELAS"]==@$kelas){echo "<option selected value='".$noticia2["KELAS"]."'>".$noticia2["KELAS"]."</option>"."<BR>";}
		else{echo  "<option value='".$noticia3["KELAS"]."'>".$noticia3["KELAS"]."</option>";}
		}	
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