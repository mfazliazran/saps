<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Lembaran Markah</p>

<?php

if (isset($_POST['tgkls']))
{		
	$ting = $_POST['ting'];
	$kelas = $_POST['kelas'];
	$q_guru = oci_parse($conn_sispa,"SELECT * FROM tguru_kelas WHERE tahun='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND ting='$ting' AND kelas='$kelas'");
	oci_execute($q_guru);
	
	$row = oci_fetch_array($q_guru);
	$nokp = $row["NOKP"];
	$nama = $row["NAMA"]; 
	$namasek = $row["NAMASEK"];
	$kodsek = $row["KODSEK"];
	$gting = strtoupper($ting); 
	$gkelas = $kelas;
	
	if ($_SESSION['statussek']=="SM"){
		$nting="TINGKATAN";
		$tmarkah="markah_pelajar";
		$tmurid="tmurid";
		$tmp="mpsmkc";
		$tahap="ting";
//		$kodsekolah = "kodsekp='$kodsek' OR kodsekt1='$kodsek' OR kodsekt2='$kodsek' OR kodsekt3='$kodsek' OR kodsekt4='$kodsek' OR kodsekt5='$kodsek'";
	}
	
	if ($_SESSION['statussek']=="SR"){
		$nting="TAHUN";
		$tmarkah="markah_pelajarsr";
		$tmurid="tmuridsr";
		$tmp="mpsr";
		$tahap="darjah";
//		$kodsekolah = "kodsekd1='$kodsek' OR kodsekd2='$kodsek' OR kodsekd3='$kodsek' OR kodsekd4='$kodsek' OR kodsekd5='$kodsek' OR kodsekd6='$kodsek'";
	}
	
	
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
	echo "<h3><center>$namasek<br>LEMBARAN MARKAH MURID<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3><br>";
	echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
	echo "<tr>";
	echo "GURU KELAS : $nama<br>$nting : $ting  $gkelas";
	echo "<br><br>";
	echo "<td rowspan = \"2\"><center>Bil</center></td>";
	echo "<td rowspan = \"2\">NAMA MURID</td>";
	$i = 0 ;
	$q_mp = "SELECT * FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND ting='$gting' AND kelas='$gkelas' AND kodsek='$kodsek' ORDER BY kodmp";
	$stmt = oci_parse($conn_sispa,$q_mp);
	oci_execute($stmt);
	$num = count_row("SELECT * FROM sub_guru WHERE tahun='".$_SESSION['tahun']."' AND ting='$gting' AND kelas='$gkelas' AND kodsek='$kodsek' ORDER BY kodmp");
	while($rowmp = oci_fetch_array($stmt)){
		$kodmp[$i] = $rowmp["KODMP"];
		$gmp[$i] = "G$rowmp[KODMP]";
		echo "<td colspan = \"2\"><center>$kodmp[$i]</center></td>";
		$i++;
	}
	echo "</tr>";
	echo "<tr>";
	for ($i=0; $i<$num; $i++)
	{
		echo "<td><center>M</center></td>";
		echo "<td><center>G</center></td>";
	}
	echo "</tr>";
	//////habis kepala
	
	$bil=0;
	$q_murid = "SELECT * FROM $tmurid WHERE tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$gkelas' AND kodsek$gting='$kodsek' ORDER BY namap";
	$stmt1 = oci_parse($conn_sispa,$q_murid);
	oci_execute($stmt1);
	while($rowmurid = oci_fetch_array($stmt1))
	{
		$namap = $rowmurid["NAMAP"];
		$nokpm = $rowmurid["NOKP"];
		$bil=$bil + 1;
		echo "    <tr>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>$namap</td>\n";
		$q_mark = oci_parse($conn_sispa,"SELECT * FROM $tmarkah WHERE nokp='$nokpm' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$gkelas' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' ORDER BY nama");
		oci_execute($q_mark);
		$row = oci_fetch_array($q_mark);
		for ($k = 0; $k < $i; $k++)
		{		
				echo "    <td><center>&nbsp;".$row["$kodmp[$k]"]."</center></td>\n";
				echo "    <td><center>&nbsp;".$row["$gmp[$k]"]."</center></td>\n";
		}
	}
	echo "</table>\n";
} 
else {
		?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='lembaran-markah-admin.php?ting=' + val;
		}
		</script>
		<?php
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
		echo "<br><br><br><br><br>";
		echo " <center><h3>SILA PILIH TINGKATAN/DARJAH DAN KELAS</h3></center>";
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
	
		$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY ting";
		$stmt2 = oci_parse($conn_sispa,$SQL_tkelas);
		oci_execute($stmt2);
		$num = count_row("SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY ting");
		echo "<form method=post name='f1' action='lembaran-markah-admin.php'>";
		echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih Tingkatan/Darjah</option>";
		while($noticia2 = oci_fetch_array($stmt2)) { 
			if($noticia2["TING"]==@$ting){echo "<option selected value='".$noticia2["TING"]."'>".$noticia2["TING"]."</option>"."<BR>";}
			else{echo  "<option value='".$noticia2["TING"]."'>".$noticia2["TING"]."</option>";}
		}
		echo "</select>";
		echo "</td>";
		echo "<td>";
		$kelas_sql = "SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND ting='$ting' ORDER BY kelas";
		$stmt3 = oci_parse($conn_sispa,$kelas_sql);
		oci_execute($stmt3);
		echo "<select name='kelas' ><option value=''>Pilih Kelas</option>";
		while($noticia3 = oci_fetch_array($stmt3)) { 
			if($noticia3["KELAS"]==@$kelas){echo "<option selected value='".$noticia2["KELAS"]."'>".$noticia2["KELAS"]."</option>"."<BR>";}
			else{echo  "<option value='".$noticia3["KELAS"]."'>".$noticia3["KELAS"]."</option>";}
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