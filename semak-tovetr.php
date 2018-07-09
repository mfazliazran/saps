<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='semak-tovetr.php?ting=' + val;
		}
		</script>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Key-In TOV-ETR</p>

<?php
echo "<br><br><br>";
echo " <center><h3>SEMAK 'KEY-IN' TOV/ETR</h3></center>";
echo " <center><b>SILA PILIH TINGKATAN/DARJAH</b></center>";
echo "<br><br>";
echo "<form method=\"post\" action=\"data-semak-tovetr.php\">";//data-semak-tovetr.php
echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "  <td>TINGKATAN/DARJAH</td>\n";
echo "	<td>HANTAR</td>\n";
echo " </tr>";
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "<td>";

$ting=$_GET['ting'];
$kelas=$_GET['kelas'];

$SQL_tkelas = "SELECT DISTINCT ting FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' ORDER BY ting";
$sql = OCIParse($conn_sispa,$SQL_tkelas);
OCIExecute($sql);
		//$temprs_mp = mysql_query($SQL_tkelas);
$num = count_row($SQL_tkelas);
			
$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND ting='$ting' ORDER BY kelas");
OCIExecute($kelas_sql);


/*echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Sila Pilih Tingkatan/Darjah</option>";
$tingSQL = "SELECT DISTINCT ting FROM tkelassek WHERE tahun='".$_SESSION['tahun']."' AND kodsek ='".$_SESSION['kodsek']."' ORDER BY ting";
$rting = OCIParse($conn_sispa,$tingSQL);
oci_execute($rting);
while(OCIFetch($rting)) { 

	echo  "<option value='".OCIResult($rting,"TING")."'>".OCIResult($rting,"TING")."</option>";
	}
			echo "</select>";
		echo "</td>";*/
		echo "<select name='ting' onchange=\"reload(this.form)\"><option value=''>Pilih Tingkatan</option>";
		while(OCIFetch($sql)) { 
		if(OCIResult($sql,"TING")/*$noticia2['ting']*/==@$ting){echo "<option selected value='".OCIResult($sql,"TING")."'>".OCIResult($sql,"TING")."</option>"."<BR>";}
		else{echo  "<option value='".OCIResult($sql,"TING")."'>".OCIResult($sql,"TING")."</option>";}
		}
		echo "</select>";
		echo "</td>";
		
		/*echo "<td>";
		echo "<select name='kelas' ><option value=''>Pilih Kelas</option>";
		while(OCIFetch($kelas_sql)) { 
		if(OCIResult($kelas_sql,"KELAS")/*$noticia3['kelas']==@$kelas){echo "<option selected value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>"."<BR>";}
		else{echo  "<option value=\"".OCIResult($kelas_sql,"KELAS")."\">".OCIResult($kelas_sql,"KELAS")."</option>";}
		}
		
	echo "</select>";
	echo "</td>";*/
	echo "<td>";
	echo "<input type='submit' name=\"pep\" value=\"Hantar\">";
	echo "</td></tr></table>";
	echo "</form>";
?>
</td>
<?php include 'kaki.php';?> 