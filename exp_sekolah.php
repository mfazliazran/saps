<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';

	die ("<center><h3>UTILITI EXPORT DATA MARKAH PELAJAR INI DIHENTIKAN KERANA MASALAH TEKNIKAL.
		 SEGALA KESULITAN AMAT DIKESALI.</h3></center>");

  ?>
		<SCRIPT language=JavaScript>
		function reload(form)
		{
			var val=form.status.options[form.status.options.selectedIndex].value;
			self.location='exp_sekolah.php?status=' + val;
		}
		</script>
<td valign="top" class="rightColumn">
<p class="subHeader">Eksport Data SAPS</p>
<?php
if (isset($_POST['pep']))
{

	$tahun = $_POST['tahun'];
	$kodmp = $_POST['kodmp'];
	$ting = $_POST['ting'];
	$jpep=$_POST['pep'];
	$status = $_POST['status'];

    set_time_limit(0);
    session_start();

    $nama_table[0] = "markah_pelajarsr"; 	$ting[0]="DARJAH";	$flag_export[0]="0";
    $nama_table[1] = "markah_pelajar"; 		$ting[1]="TING";	$flag_export[1]="0";
    $nama_table[2] = "tnilai_sr"; 			$ting[2]="DARJAH";	$flag_export[2]="0";
    $nama_table[3] = "tnilai_smr"; 			$ting[3]="TING";	$flag_export[3]="0";
    $nama_table[4] = "tnilai_sma"; 			$ting[4]="TING";	$flag_export[4]="0";
    $nama_table[5] = "headcount";			$ting[5]="TING";	$flag_export[5]="0";
    $nama_table[6] = "headcountsr";   		$ting[6]="DARJAH";	$flag_export[6]="0";
    $nama_table[7] = "penilaian_hcsr";		$ting[7]="DARJAH";	$flag_export[7]="0";
    $nama_table[8] = "penilaian_hcsmr";		$ting[8]="TING";	$flag_export[8]="0";
    $nama_table[9] = "penilaian_hcsma";		$ting[9]="TING";	$flag_export[9]="0";
    $nama_table[10] = "penilaian_muridsr";	$ting[10]="DARJAH";	$flag_export[10]="0";
    $nama_table[11] = "penilaian_muridsmr";	$ting[11]="TING";	$flag_export[11]="0";
    $nama_table[12] = "penilaian_muridsma";	$ting[12]="TING";	$flag_export[12]="0";
    
	if ($status=="SR"){
	  $flag_export[0]="1";
	  $flag_export[2]="1";
	  $flag_export[6]="1";
	  $flag_export[7]="1";
	  $flag_export[10]="1";
	}
	else if ($status=="MR"){
	  $flag_export[1]="1";
	  $flag_export[3]="1";
	  $flag_export[5]="1";
	  $flag_export[8]="1";
	  $flag_export[11]="1";
	}
	else if ($status=="MA"){
	  $flag_export[1]="1";
	  $flag_export[4]="1";
	  $flag_export[5]="1";
	  $flag_export[9]="1";
	  $flag_export[12]="1";
	}

    $namafail[0] = "markah_pelajar_sr.csv";
    $namafail[1] = "markah_pelajar.csv";
    $namafail[2] = "tnilai_sr.csv";
    $namafail[3] = "tnilai_smr.csv";
    $namafail[4] = "tnilai_sma.csv";
    $namafail[5] = "headcount.csv";
    $namafail[6] = "headcountsr.csv";
    $namafail[7] = "penilaian_hcsr.csv";
    $namafail[8] = "penilaian_hcsmr.csv";
    $namafail[9] = "penilaian_hcsma.csv";
    $namafail[10] = "penilaian_muridsr.csv";
    $namafail[11] = "penilaian_muridsmr.csv";
    $namafail[12] = "penilaian_muridsma.csv";
    

$export_path=$path."export/".$_SESSION["kodsek"]."/";

if (!file_exists($export_path))
     mkdir($export_path,0777);

 for ($i=0;$i<=12;$i++){
   if ($flag_export[$i]=="1"){
	  $f1=fopen($export_path.$namafail[$i],"w");

	  $result = oci_parse($conn_sispa,"SELECT COLUMN_NAME,DATA_TYPE FROM ALL_TAB_COLS WHERE TABLE_NAME='".strtoupper($nama_table[$i])."' ORDER BY COLUMN_ID ");
	  oci_execute($result);
	  $cnt = 0;
	  $csv_output="";
	  while ($data = oci_fetch_array($result)) {
		$val="\"".$data["COLUMN_NAME"]."\" ";
		$type[$cnt]=$data["DATA_TYPE"];
		//if (ereg("id",$val)){
		//    $pos_id=$cnt;
		//    $ada_id=1;
		//}
		//else {
		  if ($cnt>0)
			$csv_output.=",";
		  $csv_output .= $val;
		//} //field id dikecualikan
		$cnt++;
	 } //while
	 $csv_output .= "\r\n";
	 fwrite($f1,$csv_output);
     $role=$_SESSION["level"];
	 
		 $sql="SELECT ".$nama_table[$i].".* FROM ".$nama_table[$i].", TSEKOLAH where ".$nama_table[$i].".KODSEK=TSEKOLAH.KODSEK ";

           if ($role=="6") //JPN,
              $sql.=" AND TSEKOLAH.KODNEGERIJPN='".$_SESSION["kodsek"]."'";
           else if ($role=="5" ) //PPD
              $sql.=" AND TSEKOLAH.KODPPD='".$_SESSION["kodsek"]."'";
           else if ($role=="4" or $role=="3" or $role=="2" or $role=="1" or $role=="P") //SEKOLAH
              $sql.=" and TSEKOLAH.KODSEK='".$_SESSION["kodsek"]."'";
            else 
			  $sql="ERROR"; 
			  
		 $sql.=" AND ".$nama_table[$i].".TAHUN='$tahun' ";
		 
		 if ($nama_table[$i]<>"headcount" and $nama_table[$i]<>"headcountsr")
		 $sql.=" AND ".$nama_table[$i].".JPEP='$jpep' ";
		 	  
         echo "$sql<br>";
		 $res = oci_parse($conn_sispa,$sql);
		 oci_execute($res);
		 while ($data = oci_fetch_array($res)) {
			$csv_output="";
			for ($j=0;$j<$cnt;$j++) {
			  if (ereg("CHAR",$type[$j]))
				$val="\"".$data[$j]."\" ";
			  elseif (ereg("DATE",$type[$j]))
				$val="\"".$data[$j]."\" ";
			  else
				 $val=$data[$j];
		 
			  //if ($ada_id==1 and $j==$pos_id)
			  //    $fld11=0;
			  //else {
				 if ($j>0)
				   $csv_output.=",";
				 $csv_output .= $val;
			  //}
			} //for(j==...
		  $csv_output .= "\r\n";
		  //echo "$csv_output<br>";  
		  fwrite($f1,$csv_output);
		
		 } //while
   //} //while($data1==..
   fclose($f1);
 } //if ($flag_export[$i]..  
} //for
//zip file

  require_once('include/pclzip.lib.php');
  $archive = new PclZip($export_path."saps.zip");
  if (file_exists($export_path."saps.zip"))
     unlink($export_path."saps.zip");
  for($i=0;$i<=12;$i++){
    if ($flag_export[$i]=="1"){
		$v_list = $archive->add($export_path.$namafail[$i],
							  PCLZIP_OPT_REMOVE_PATH, $export_path);
		unlink($export_path.$namafail[$i]);
	}	
  }
  if ($v_list == 0) {
    die("Error : ".$archive->errorInfo(true));
  }
$file=$export_path."saps.zip";
echo "Selesai...<a href=\"$file\">Download file</a>";
} //isset($_POST['pep']
else {

		//echo "$kodsek";
		//echo "<br><br>";
		echo " <center></b>EKSPORT DATA SAPS</b></center>";
		echo "<br>";
		echo "<form method=\"post\">\n";
		echo "<table  border=\"1\" bordercolor=\"#FFFFFF\" width=\"300\"  border=\"0\" align=\"center\" cellpadding=\"10\" cellspacing=\"0\">\n";
		
		echo "  <tr bgcolor=\"#CCCCCC\">\n";
		
		//$SQLting = mysql_query("SELECT DISTINCT ting FROM markah_pelajar ORDER BY ting");
		
		$status = $_GET['status'];
		if($status == "")
		 $status = "MR";
		switch ($status)
		{
			case "MR" : $tahap = "MENENGAH RENDAH"; ; $tmp = "sub_mr"; $mr=" SELECTED "; break;
			case "MA" : $tahap = "MENENGAH ATAS"; $tmp = "mpsmkc"; $ma=" SELECTED ";  break;
			case "SR" : $tahap = "SEKOLAH RENDAH"; $tmp = "sub_sr"; $sr=" SELECTED ";  break;
			default : $tahap = "Pilih Tahap"; break;
		}	
		
     	echo "<td>TAHUN</td><td><input name=\"tahun\" type=\"text\" id=\"tahun\" value=\"".date('Y')."\" size=\"3\" maxlength=\"4\"></td></tr>";
		echo "<form method=post name='f1' action='exp_sekolah.php'>";
		echo "<tr bgcolor=\"#CCCCCC\"><td>TAHAP</td><td><select name=\"status\" onchange=\"reload(this.form)\">";
		echo "<option $mr value=\"MR\">MENENGAH RENDAH</option>";
		echo "<option $ma value=\"MA\">MENENGAH ATAS</option>";
		echo "<option $sr value=\"SR\">SEKOLAH RENDAH</option>";
		echo "</select>";
		echo "<input name=\"statush\" value=\"$status\" type=\"hidden\" size=\"6\" maxlength=\"4\"></td></tr>";

		$qrypep = "SELECT * FROM jpep ORDER BY rank";
		//echo $qrypep;
		$SQLpep = oci_parse($conn_sispa,$qrypep);
		oci_execute($SQLpep);
		echo "<tr bgcolor=\"#CCCCCC\"><td>PEPERIKSAAN</td><td>";
		echo "<select name='pep'><option value=''>Pilih Peperiksaan</option>";
		while($rowpep = oci_fetch_array($SQLpep)) { 
			echo  "<option value='$rowpep[KOD]'>$rowpep[JENIS]</option>";
		}
		echo "</select>";
		echo "</td></tr>";

		echo "<tr bgcolor=\"#CCCCCC\"><td>TINGKATAN/DARJAH</td><td>";
		echo "<select name='ting'><option value=''>Ting/Darjah</option>";
		switch ($status)
		{
			case "MR" :	echo "<option value=\"P\">P</option>";
						echo "<option value=\"T1\">T1</option>";
						echo "<option value=\"T2\">T2</option>";
						echo "<option value=\"T3\">T3</option>";
						break;
						
			case "MA" : echo "<option value=\"T4\">T4</option>";
						echo "<option value=\"T5\">T5</option>";
						break;
						
			case "SR" :	echo "<option value=\"D1\">D1</option>";
						echo "<option value=\"D2\">D2</option>";
						echo "<option value=\"D3\">D3</option>";
						echo "<option value=\"D4\">D4</option>";
						echo "<option value=\"D5\">D5</option>";
						echo "<option value=\"D6\">D6</option>";
						break;
		}
		echo "</select>";
		echo "</td></tr>";

		//////////        Starting of second drop downlist /////////
		
		$qrymp = "SELECT DISTINCT * FROM $tmp ORDER BY mp";
		//echo $qrymp;
		$SQLmp = oci_parse($conn_sispa,$qrymp);	
		oci_execute($SQLmp);
		echo "<tr bgcolor=\"#CCCCCC\"><td>MATA PELAJARAN</td><td>";
		echo "<select name='kodmp'><option value=''>Sila Pilih Mata Pelajaran</option>";
		while($rowmp = oci_fetch_array($SQLmp)) { 
			echo  "<option value='$rowmp[KOD]'>$rowmp[MP]</option>";
		}
		echo "</select>";
		echo "</td>";
		
		//////////////////  This will end the second drop down list ///////////
		//// Add your other form fields as needed here/////
		echo "</tr></table><br><br>";
		 print "<input name=\"kodppd\" type=\"hidden\" readonly value=\"$kodsek\">";
		 print "<input name=\"namappd\" type=\"hidden\" readonly value=\"$namappd\">";
		echo "<center><input type='submit' name=\"mpep\" value=\"Hantar\"></center>";
		echo "</form>";
} 
include 'kaki.php';		

?> 
</td>

<br>
<br>
