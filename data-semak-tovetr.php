<?php 
	session_start();
	include 'auth.php';
	include 'config.php';
	include 'kepala.php';
	include 'menu.php';
	?>
	<td valign="top" class="rightColumn">
	<p class="subHeader">Semak Key-In TOV-ETR</p>
	
	<?php
	$ting = $_POST['ting'];
	$kelas = oci_escape_string($_POST['kelas']);
	
	$gting = strtolower($ting);
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"tulisexam.css\">";
	echo "<br>";
	echo "<center><h3>SENARAI SEMAK KEMASUKAN DATA TOV/ETR SEKOLAH<br>MENGIKUT MATA PELAJARAN KELAS<br>".ting($ting)." TAHUN ".$_SESSION['tahun']."</h3></center>";
	//echo "<a href=cetak-semak-tovetr.php?data=".$ting." target=_blank><center><img src = images/printer.png width=15 height=15 border=\"0\"><br>CETAK</center></a>";
	echo "<table width=\"85%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
	echo "  <tr>\n";
	echo "    <td align=center>BIL</center></td>\n";
	echo "    <td align=center>KELAS</td>\n";
	echo "    <td align=center>MATA PELAJARAN</td>\n";
	echo "    <td align=center>NAMA GURU</td>\n";
	echo "    <td align=center>BIL<br>AMBIL</td>\n";
	echo "    <td align=center>BIL<br>TOV</td>\n";
	echo "    <td align=center>BIL<br>ETR</td>\n";
	echo "    <td align=center>STATUS</td>\n";
	echo "  </tr>\n";
	
	?>
</p>
<form action="ctk_data-semak-tovetr.php" method="POST" target="_blank">
<?php
	
	$kodsek=$_SESSION['kodsek'];
	$querykelas = oci_parse($conn_sispa,"SELECT * FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting'"); //AND kelas='$kelas'"); 
	oci_execute($querykelas);
    $bilkelas = count_row("SELECT * FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting'"); //AND kelas='$kelas'");
    $bil=0; $biltkt = 0; $i=0; $bilk = 0;
	$bilteras=0;
	$mpokteras=0;
	while ($row = oci_fetch_array($querykelas))
	{
		$bil=$bil+1;
		$ting = $row['TING'];
		$kelas = oci_escape_string($row['KELAS']);
		
		$num = count_row("SELECT * FROM sub_guru WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kelas ASC");
		$qrytentuhc = oci_parse($conn_sispa,"SELECT * FROM tentu_hc WHERE tingpep='$ting' AND tahunpep='".$_SESSION['tahun']."' AND capai='TOV'");
		oci_execute($qrytentuhc);
		
		$row = oci_fetch_array($qrytentuhc);
		$jpeptov = $row['JENPEP']; $tahuntov = $row['TAHUNTOV']; $tingtov = $row['TINGTOV'];
		$rowspan=$num;
		if ($rowspan==0)
		   $rowspan=1;
		echo "  <tr>\n";
		echo "		<td rowspan =\"$rowspan\"><center>$bil</center></td>\n";
		if ($_SESSION['statussek']=="SM"){
			//$qb_murid = oci_parse($conn_sispa,"SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas'");
		    //oci_execute($qb_murid);
			$bilm = count_row("SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas'");
			
		}

		if ($_SESSION['statussek']=="SR"){
			//$qb_murid = oci_parse($conn_sispa,"SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas'");
            //oci_execute($qb_murid);
			$bilm = count_row("SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$ting' AND kelas$gting='$kelas'");
		}
		echo "		<td rowspan =\"$rowspan\"><center>$ting&nbsp;&nbsp;&nbsp; $kelas <br>[$bilm Murid]</center></td>";
		if($num != 0){
			$bilmp=0; $bilrow=0; $mpok = 0;
		    $querymp = oci_parse($conn_sispa,"SELECT * FROM sub_guru WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kelas ASC");
            oci_execute($querymp);
			while ($row2 = oci_fetch_array($querymp))
			{
				$kodmp = $row2['KODMP'];
				if ($_SESSION['statussek']=="SM"){
					$qrymp = "SELECT * FROM mpsmkc WHERE kod='$kodmp'";
					$markah = "markah_pelajar";
					$theadcount = "headcount";
					$tahap = "ting";
				}
				if ($_SESSION['statussek']=="SR"){
					$qrymp = "SELECT * FROM mpsr WHERE kod='$kodmp'"; 
					$markah = "markah_pelajarsr";
					$theadcount = "headcountsr";
					$tahap = "darjah";
				}
				$r_mp = oci_parse($conn_sispa,$qrymp);
				oci_execute($r_mp);
				$tukarmp = oci_fetch_array($r_mp );
				$bilmp=$bilmp+1;
				echo  "<td> ".$tukarmp['MP']." </td><td>".$row2['NAMA']."</td><td><center>".$row2['BILAMMP']."</center></td>";
				$q_mark = oci_parse($conn_sispa,"SELECT * FROM $theadcount WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kodmp' AND gtov is not null");
				oci_execute($q_mark);
				$bil_mark = count_row("SELECT * FROM $theadcount WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kodmp' AND gtov is not null");
				
				$q_etr = oci_parse($conn_sispa,"SELECT * FROM $theadcount WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kodmp' AND getr is not null");
                oci_execute($q_etr);				
				$bil_etr = count_row("SELECT * FROM $theadcount WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND $tahap='$ting' AND kelas='$kelas' AND hmp='$kodmp' AND getr is not null");

				if (subjek_teras($kodmp,$jenissekolah,$_SESSION['statussek'],$ting)==1){
			   $bilteras++;
			   $colo = "#FF0000";
			   //echo $color."<br>";
			   if ($bil_mark==$row2["BILAMMP"]){
				   $mpokteras++;
			   }
			}
				if(($bil_mark == $bil_etr) AND ($bil_mark !=0) AND ($bil_etr == $row2['BILAMMP'])){
					echo  "<td><center>$bil_mark</center></td><td><center>$bil_etr</center></td><td><center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center></td>";
					 $mpok = $mpok + 1;
				} else {
						echo  "<td><center>$bil_mark</center></td><td><center>$bil_etr</center></td><td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></td>";
						}
				if ($num<>$bilrow)
					echo  "<tr></th>\n";
				$bilrow=$bilrow+1;
				
				//********************** MULA HANTAR ARRAY*****************************************************************************************	
			
		$i=$i+1;

//echo "$bilrow | nama:$row2[nama] |ting: $gting |tahun:$tahun|jpep:$_SESSION[jpep] | markah:$bil_mark | bil_ambil:$row2['bilammp'] | kelas:$kelas | mp:$tukarmp[mp] "; 
//echo "$bilrow | nama:$row2[nama] |ting: $gting | bil_ambil:$row2[bilammp]| kelas:$kelas |tahun:$tahun |jpep:$_SESSION[jpep] | mp:$tukarmp[mp] "; 

//print "<input name=\"bilmp[$i]\" type=\"hidden\" readonly value=\"$bilmp\">";
print "<input name=\"bilmurid[$i]\" type=\"hidden\" readonly value=\"$bilm\">";
print "<input name=\"nama[$i]\" type=\"hidden\" readonly value=\"$row2[NAMA]\">";
print "<input name=\"bilrow\" type=\"hidden\" readonly value=\"$bilrow\">";
//echo $bilrow;
print "<input name=\"ting\" type=\"hidden\" readonly value=\"$gting\">";
print "<input name=\"tahun\" type=\"hidden\" readonly value=\"$_SESSION[tahun]\">";
print "<input name=\"jpep[$i]\" type=\"hidden\" readonly value=\"$_SESSION[jpep]\">";
print "<input name=\"bil_mark[$i]\" type=\"hidden\" readonly value=\"$bil_mark\">";
print "<input name=\"bil_etr[$i]\" type=\"hidden\" readonly value=\"$bil_etr\">";
print "<input name=\"bilammp[$i]\" type=\"hidden\" readonly value=\"$row2[BILAMMP]\">";
print "<input name=\"kelas[$i]\" type=\"hidden\" readonly value=\"$kelas\">";
print "<input name=\"mp[$i]\" type=\"hidden\" readonly value=\"$tukarmp[MP]\">";

	
	//*********************************************************************************************************************************			
		$bilk++;
	print "<input name=\"num[$bil]\" type=\"hidden\" readonly value=\"$num\">";	
				
				
			}
		} else {
				echo  "<td>Tiada MP</td>&nbsp<br>";
				echo  "<td>Tiada Guru</td>&nbsp<br>";
				echo  "<td><center>0</center></td>&nbsp<br>";
				echo  "<td><center>0</center></td><td><center>0</center></td><td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></td>";
				}
		echo  "</tr>";
		
		print "<input name=\"bilk\" type=\"hidden\" readonly value=\"$bilk\">";

		if (($mpok ==  $num) AND ( $num!=0 )) {$biltkt = $biltkt + 1; }
	}
	
				?>

<br><br>
&nbsp;&nbsp;<input type="submit" name="submit" value="CETAK">
</form>

<?php
	
	echo "</table><br>";
	
	
	echo "<table width=\"85%\" border=\"1\" bgcolor=\"ffffcc\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\"><td valign=\"middle\">";
		if ($bilteras == $mpokteras and $bilteras>0){ 
	//	echo "<center>Sila Klik Untuk Pengesahan<br>TOV/ETR ".ting($ting)."</center>";
	echo "<tr bgcolor=\"#00FF00\"><td valign=\"middle\">";
	echo "<center><strong><font color=\"#000000\">Sila Klik Untuk Pengesahan<br>TOV ".ting($ting)."</font></strong></center>";
		echo "<form name=\"form1\" method=\"post\" action=\"data-sah.php?data=".$_SESSION['tahun']."/".$kodsek."/".$ting."/TOV/".date("d-m-Y")."\">\n";
		echo " <center><br><input type=\"submit\" name=\"cetak\" value=\"SILA KLIK UNTUK PENGESAHAN ".ting($ting)."\"></center>\n";
		echo "</form>\n";
		 }
		else { echo "<tr bgcolor=\"#FF0000\"><td><center><strong>Pengesahan TOV/ETR ".ting($ting)." Belum Selesai. Pengesahan TOV/ETR hanya boleh di buat selepas semua mata pelajaran teras diisi.</strong></center></td></tr>"; 
	}
	echo "</td></table>";
	echo "<br><br>";
	/*echo "</th>\n";
	echo "</tr>\n";
	echo "</table></body>\n";
	*/
	function ting($ting){
	switch ($ting){
		case "T1":
		$npep="TINGKATAN SATU";
		break;
		case "T2":
		$npep="TINGKATAN DUA";
		break;
		case "T3":
		$npep="TINGKATAN TIGA";
		break;
		case "T4":
		$npep="TINGKATAN EMPAT";
		break;
		case "T5":
		$npep="TINGKATAN LIMA";
		break;
		case "P":
		$npep="PERALIHAN";
		break;
		case "D1":
		$npep="TAHUN SATU";
		break;
		case "D2":
		$npep="TAHUN DUA";
		break;
		case "D3":
		$npep="TAHUN TIGA";
		break;
		case "D4":
		$npep="TAHUN EMPAT";
		break;
		case "D5":
		$npep="TAHUN LIMA";
		break;
		case "D6":
		$npep="TAHUN ENAM";
		break;
	}
return $npep;
}

function subjek_teras($kodmp,$jenissekolah,$status,$ting)
{
	if($ting=='T4' or $ting=='T5'){
		$sql = "SELECT * FROM sub_ma where kod='$kodmp' ORDER BY mp";
	}else{
		if($status=="SM"){
			$sql = "SELECT * FROM sub_mr where kod='$kodmp' ORDER BY mp";	
		}else{
			$sql = "SELECT * FROM sub_sr where kod='$kodmp' ORDER BY mp";	
		}
	}
	//$num=count_row("SELECT * FROM subjekteras where jenissekolah='".$jenissekolah."' and kodmp='$kodmp' ");
	$num=count_row($sql);
// echo "SELECT * FROM subjekteras where jenis='".$jenissekolah."' and kodmp='$kodmp' <br>";
 return($num);
}
?>
</td>
<?php include 'kaki.php';?> 