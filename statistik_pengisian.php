<?php
set_time_limit(0);
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
	
$kodsek="".$_SESSION['kodsek']."";
$tahun=date("Y");
$jenissek = $_GET["jenissek"];
$jenispep = $_GET["jenispep"];
if($jenissek=='SR'){
	$namasek="SEKOLAH RENDAH";
	$sqlpep = "JPEP!='PMRC' AND JPEP!='SPMC'";
}
else if($jenissek=='SM'){
	$namasek="SEKOLAH MENENGAH";	
	$sqlpep = "JPEP!='UPSRC'";
}
?>
<script language="javascript" type="text/javascript" src="ajax/ajax_murid.js"></script>
<SCRIPT language=JavaScript>
function reload(form,key)
{
	if(key==1){
		var val=form.cboJenis.options[form.cboJenis.options.selectedIndex].value;
		self.location='statistik_pengisian.php?jenissek=' + val;
	}
	if(key==2){
		var val=form.cboJenis.options[form.cboJenis.options.selectedIndex].value;
		var val2=form.cboPep.options[form.cboPep.options.selectedIndex].value;
		self.location='statistik_pengisian.php?jenissek=' + val +'&jenispep=' + val2;
	}
}
function location_negeri(location){
	window.location=location;	
}
</script>
<td valign="top" class="rightColumn">
<p class="subHeader">Statistik Pengisian<font color="#FFFFFF">(Tarikh Kemaskini Program : 06/9/2011 10.12AM)</font></p>
<?php

echo "<form method=post name='f1' action=''>";
			echo "<table width=\"300\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
			echo "  <tr>\n";
			echo "    <th bgcolor=\"#ff9900\" width=\"50%\"><center><b>Jenis Sekolah</b></center></th>\n";
			echo "    <th bgcolor=\"#ff9900\" width=\"35%\"><center><b>Peperiksaan</b></center></th>\n";
			//echo "    <th bgcolor=\"#ff9900\" width=\"15%\"><center><b>&nbsp;</b></center></th>\n";
			echo "  </tr>\n";
			echo "  <tr>\n";
			echo "<td align='center'><select name='cboJenis' id='cboJenis' onchange=\"reload(this.form,'1')\"><option value=''>-PILIH-</option>";
		    echo "<option value='SR' ";if($jenissek=='SR'){ echo "SELECTED";} echo " >SR</option>";
			echo "<option value='SM' ";if($jenissek=='SM'){ echo "SELECTED";} echo " >SM</option>";
			echo "</select></td>";
			echo "<td><select name='cboPep' id='cboPep' onchange=\"reload(this.form,'2')\"><option value=''>-PILIH-</option>";
			$kelas_sql = OCIParse($conn_sispa,"SELECT jpep FROM kawal_pep WHERE tahun ='$tahun' AND $sqlpep ORDER BY RANK");
			OCIExecute($kelas_sql);
			while(OCIFetch($kelas_sql)) { 
			if(OCIResult($kelas_sql,"JPEP")==$jenispep){
			   echo "<option selected value='".OCIResult($kelas_sql,"JPEP")."'>".OCIResult($kelas_sql,"JPEP")."</option>"."<BR>";
			}
			else{
			  echo  "<option value='".OCIResult($kelas_sql,"JPEP")."'>".OCIResult($kelas_sql,"JPEP")."</option>";
			  }
			}
			echo "</select></td>";
			//echo "<td><center><input type=\"submit\" name=\"Submit\" value=\"Hantar\"></center></td>";
			echo "</tr>";
			echo " </table>\n";
			echo "</form>\n";
			//echo "<br><br>";
if($jenissek<>"" and $jenispep<>""){			
			echo "<h3><center>STATISTIK PENGISIAN $namasek BAGI TAHUN $tahun</center></h3><br>";
			//echo "<br><br>";
$Q = "select KODNEGERI,NEGERI from tknegeri where kodnegeri!='98'";
$Q = OCIParse($conn_sispa,$Q);
OCIExecute($Q);
	echo "<table width=\"600\" align=\"center\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
			echo "  <tr bgcolor=\"#ff9900\">\n";
			echo "    <td rowspan=\"2\" scope=\"col\">Bil.</td>\n";
			echo "    <td rowspan=\"2\" scope=\"col\">Negeri</td>\n";
			echo "    <td rowspan=\"2\" scope=\"col\">Jumlah Sekolah</td>\n";
			echo "    <td colspan=\"4\" scope=\"col\" align=\"center\">Pengisian Markah</td>\n";
			echo "  </tr>\n";
			echo "<tr bgcolor=\"#ff9900\">\n";
			echo "  <td><center>Belum</center></td>\n";
			echo "  <td><center>Sedang</center></td>\n";
			echo "  <td><center>Siap</center></td>\n";
			echo "  <td><center>Sah</center></td>\n";
			echo "</tr>\n";
	$jumsek=0;
	$belum=0;
	$siap=0;
	$sedang=0;
	$sah=0;
	while(OCIFetch($Q)) {
		$bil++;
		$kodnegeri = OCIResult($Q,"KODNEGERI");
		$negeri = OCIResult($Q,"NEGERI");
		$querysub = "SELECT jumsek,belum,siap,sedang,sah,to_char(TARIKH_KEMASKINI, 'dd-mm-yyyy') as tarikh,to_char(MASA_KEMASKINI, 'HH24:MI:SS') as masa FROM statistik_pengisian WHERE negeri='$kodnegeri' and STATUS='$jenissek' and jenispep='$jenispep'";//DONE INDEXING
		//echo $querysub;
		$qry = OCIParse($conn_sispa,$querysub);
		OCIExecute($qry);
		while(OCIFetch($qry)) {
			$jumsek = OCIResult($qry,"JUMSEK");
			$belum = OCIResult($qry,"BELUM");
			$siap = OCIResult($qry,"SIAP");
			$sedang = OCIResult($qry,"SEDANG");
			$sah = OCIResult($qry,"SAH");
			$tarikh_kemaskini = OCIResult($qry,"TARIKH");
			$masa_kemaskini = OCIResult($qry,"MASA");
		}
		echo "<tr>\n";
		echo "<td>$bil</td>";
		echo "<td>$negeri</td>";
		echo "<td>$jumsek</td>";
		echo "<td>$belum</td>";
		echo "<td>$sedang</td>";
		echo "<td>$siap</td>";	
		echo "<td>$sah</td>";	
		echo "</tr>\n";
		//$tarikh_kemaskini = "";
		//$masa_kemaskini = "";
	}//while Q
	echo "</table>\n";
echo "<h3><center>Statistik Pengisian adalah berdasarkan data yang telah diproses pada $tarikh_kemaskini </center></h3><br><br>";
}//jenissek<>""

?>
</td>
<?php include 'kaki.php';?> 