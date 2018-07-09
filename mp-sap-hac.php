<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
$tahun = date("Y");
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Senarai Mata Pelajaran Peperiksaan</p>
<br><br>
 <div align="center"><h3>MATA PELAJARAN PEPERIKSAAN DAN HEADCOUNT</h3></div>
  <form name="form1" method="post" action="data-mp-saphc.php">
   <table width="70%"  border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#999999">
     <tr bgcolor="#CCCCCC">
       <td><div align="center">Jenis Sekolah</div></td>
       <td><div align="center">Mata Pelajaran </div></td>
       <td><div align="center">Tambah</div></td>
     </tr>
     <tr>
       <td height="31">
	     <div align="center">
	     <SCRIPT language=JavaScript>
		function reload(form)
		{
		var val=form.ting.options[form.ting.options.selectedIndex].value;
		self.location='mp-sap-hac.php?ting=' + val;
		}
		</script>
	     <?php
	    $ting=$_GET['ting'];
		$mp=$_GET['mp'];
		
		switch($ting){
		 case "SR": $js = "Sekolah Rendah"; break;
		 case "SM": $js = "Sekolah Menengah"; break;
		}
		
		echo "<form method=post name='f1' action='mp-sap-hac.php'>";		
	   	echo "<select name='ting' onchange=\"reload(this.form)\">";
		echo "<option value='$ting'>$js</option>";
		echo "<option value=\"SR\">Sekolah Rendah</option>";
		echo "<option value=\"SM\">Sekolah Menengah</option>";
		echo "</select>";
		echo "</td></center>";
	   ?>
	   </td>
       <td><center>
	   <?php
	   echo "<select name='mp'><option value=''>Pilih Mata Pelajaran</option>";
	   if($ting=="SM"){
	    $SQL_mp = oci_parse($conn_sispa,"SELECT * FROM mpsmkc ORDER BY mp");
		//die ("$SQL_mp");
		oci_execute($SQL_mp);
	   	while($rowmp = oci_fetch_array($SQL_mp)) { 
			echo "<option selected value='".$rowmp["KOD"]."/".$rowmp["MP"]."'>".$rowmp["MP"]."</option>"."<BR>";
			}
		}
		if($ting=="SR"){
		$SQL_mp_sr = oci_parse($conn_sispa,"SELECT * FROM mpsr ORDER BY mp");
		oci_execute($SQL_mp_sr);
	   	while($rowmpsr = oci_fetch_array($SQL_mp_sr)) { 
			echo "<option selected value='".$rowmpsr["KOD"]."/".$rowmpsr["MP"]."'>".$rowmpsr["MP"]."/".$rowmpsr["KODLEMBAGA"]."</option>"."<BR>";
			}
		}
		echo "</select>";
		 echo " <input type=\"hidden\" name=\"$ting\">";
		echo "</td></center>";
	   ?></center>
	   <div align="center"></div></td>
       <td><div align="center">
         <input type="submit" name="Submit" value="Hantar">
       </div></td>
     </tr>
   </table>
 </form>
 <br><br>
 <div align="center"><b>SENARAI MATA PELAJARAN PEPERIKSAAN DAN HEADCOUNT</b></div><br>
  <table width="70%"  border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#CCCCCC">
    <td>Bil</td>
    <td>Kod MP </td>
    <td>Mata Pelajaran </td>
    </tr>
  <tr>
  <?php
  	$bil=0;
	if($ting=="SM"){
  	    $SQL_mp2 = oci_parse($conn_sispa,"SELECT * FROM sub_mr ORDER BY mp");
		//echo "SELECT * FROM sub_mr ORDER BY mp";
		oci_execute($SQL_mp2);
	   	while($rowmpw = oci_fetch_array($SQL_mp2)) { 
	$bil=$bil+1;
    echo "<td><center>$bil</center></td>\n";
	echo "<td>".$rowmpw["KOD"]."</td>\n";
	echo "<td>".$rowmpw["MP"]."</td>\n";
	echo"</tr>";
		}
	echo"</table>";
	echo"<br><br>";
	}
	if($ting=="SR"){
	    $SQL_mp_sr2 = oci_parse($conn_sispa,"SELECT * FROM sub_sr ORDER BY mp");
		oci_execute($SQL_mp_sr2);
	   	while($rowmpsrw = oci_fetch_array($SQL_mp_sr2)) { 
	$bil=$bil+1;
	echo "<td><center>$bil</center></td>\n";
	echo "<td>".$rowmpsrw["KOD"]."</td>\n";
	echo "<td>".$rowmpsrw["MP"]."</td>\n";
	echo"</tr>";
		}
	echo"</table>";
	echo"<br><br>";
	}
   ?>
 
