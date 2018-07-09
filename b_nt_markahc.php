<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include "input_validation.php";

function count_row_oci_by_name5($sql,$val1,$val2,$val3,$val4,$val5,$param1,$param2,$param3,$param4,$param5){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	//echo "POS $pos :- $sql";
	$newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

	$qic = oci_parse($conn_sispa,$newsql);
	oci_bind_by_name($qic, $param1, $val1);
	oci_bind_by_name($qic, $param2, $val2);
	oci_bind_by_name($qic, $param3, $val3);
	oci_bind_by_name($qic, $param4, $val4);
	oci_bind_by_name($qic, $param5, $val5);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

function count_row_oci_by_name2($sql,$val1,$val2,$param1,$param2){
	global $conn_sispa;

	$pos=strpos($sql,"FROM");
	if ($pos==0)
		$pos=strpos($sql,"from");
	if ($pos==0)
		$pos=strpos($sql,"From");
	//echo "POS $pos :- $sql";
	$newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 

	$qic = oci_parse($conn_sispa,$newsql);
	oci_bind_by_name($qic, $param1, $val1);
	oci_bind_by_name($qic, $param2, $val2);
	oci_execute($qic);
	if (OCIFetch($qic)){
		$bilrekod=OCIResult($qic,"BILREKOD");
	}
	return($bilrekod);  
}

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Markah TOV/ETR <font color="#FFFFFF">(Tarikh Kemaskini Program : 4/8/2011 11:17AM)</font></p>

<script language="JavaScript">
var bgcolor = "#FFCC66";
var change_color = "#FFFFFF"
function mover(aa) {
 aa.style.backgroundColor = change_color;
}
function mout(aa) {
 aa.style.backgroundColor = bgcolor;
}
</script>


<?php

$m=validate($_GET['data']);

list ($kelas, $ting, $kod, $tahun, $kodsek, $nokpguru)=explode("|", $m);

if ($_SESSION['statussek']=="SM"){
	$tmarkah="markah_pelajar";
	$theadcount="headcount";
	$tmurid="tmurid";
	$tmp="mpsmkc";
	$tahap="ting";
}

if ($_SESSION['statussek']=="SR"){
	$tmarkah="markah_pelajarsr";
	$theadcount="headcountsr";
	$tmurid="tmuridsr";
	$tmp="mpsr";
	$tahap="darjah";
}

$qrytentuhc = "SELECT * FROM tentu_hc WHERE tingpep= :ting AND tahunpep= :tahun AND capai='TOV'";
$penentuan = count_row_oci_by_name2($qrytentuhc, $ting, $_SESSION['tahun'], ":ting", ":tahun");

if ($penentuan!=0){
	$stmt = oci_parse($conn_sispa,$qrytentuhc);
	oci_bind_by_name($stmt, ':ting', $ting);
	oci_bind_by_name($stmt, ':tahun', $_SESSION['tahun']);
	oci_execute($stmt);
	$row = oci_fetch_array($stmt);
	$tentuhc=$row['JENPEP'];
	$tahuntov=$row['TAHUNTOV'];
	$tingtov=$row['TINGTOV'];
	$tg = strtolower($ting);
	switch ($kod)
	{
		case "PI":
		$querynama = "SELECT * FROM $tmurid WHERE kodsek$tg= :kodsek AND tahun$tg= :tahun AND $tg= :ting AND kelas$tg= :kelas AND agama='01' ORDER BY namap";
		break;	
		case "PIMA":
		$querynama = "SELECT * FROM $tmurid WHERE kodsek$tg= :kodsek AND tahun$tg= :tahun AND $tg= :ting AND kelas$tg= :kelas AND agama='01' ORDER BY namap";
		break;
		
		case "PM":
		$querynama = "SELECT * FROM $tmurid WHERE kodsek$tg= :kodsek AND tahun$tg= :tahun AND $tg= :ting AND kelas$tg= :kelas AND agama!='01' ORDER BY namap";
		break;
		case "PMMA":
		$querynama = "SELECT * FROM $tmurid WHERE kodsek$tg= :kodsek AND tahun$tg= :tahun AND $tg= :ting AND kelas$tg= :kelas AND agama!='01' ORDER BY namap";
		break;	
		
		default:
		$querynama = "SELECT * FROM $tmurid WHERE kodsek$tg= :kodsek AND tahun$tg= :tahun AND $tg= :ting AND kelas$tg= :kelas ORDER BY namap";
		break;
	}
	$resultpel = oci_parse($conn_sispa,$querynama);
	oci_bind_by_name($resultpel, ':kodsek', $kodsek);
	oci_bind_by_name($resultpel, ':tahun', $_SESSION['tahun']);
	oci_bind_by_name($resultpel, ':ting', $ting);
	oci_bind_by_name($resultpel, ':kelas', $kelas);
	oci_execute($resultpel);
	borang($resultpel, $ting, $kelas, $kod, $tahun, $tahuntov, $tingtov, $kodsek, $tentuhc, $nokpguru, $theadcount, $tmarkah, $tahap, $tmp);

} else {
?> <script>alert('Penentu TOV Belum Ditetapkan Lagi')</script> <?php
}

//fungsi borang markah
function borang($resultpel, $ting, $kelas, $kod, $tahun, $tahuntov, $tingtov, $kodsek, $jpep, $nokpguru, $theadcount, $tmarkah, $tahap, $tmp)
{
global $conn_sispa;
	$order_form = ""; 
    $stmt = oci_parse($conn_sispa,"SELECT * FROM $tmp WHERE kod= :kod");
    oci_bind_by_name($stmt, ':kod', $kod);
	oci_execute($stmt);
	$resultkod = oci_fetch_array($stmt);
	$namamp=$resultkod['MP'];
	$kod=$resultkod['KOD'];

	echo "<form name=\"form1\" method=\"post\" action=\"masuk_tovbaru.php\">\n";//?databutg=$butgtarget buang 18/3/2015
	print '<br><br>';
	echo "<center><h3>Masuk TOV Dan Sasaran Murid Tahun $tahun</h3></center><br>";
	echo "<input name=\"kodsek\" type=\"hidden\" id=\"kodsek\" value=\"$kodsek\">\n";
	echo "<input name=\"tahun\" type=\"hidden\" id=\"tahun\" value=\"$tahun\">\n";
	echo "<input name=\"ting\" type=\"hidden\" id=\"ting\" value=\"$ting\">\n";
	echo "<input name=\"kelas\" type=\"hidden\" id=\"kelas\" value=\"$kelas\">\n";
	echo "<input name=\"mp\" type=\"hidden\" id=\"mp\" value=\"$kod\">\n";
	echo "<input name=\"jpep\" type=\"hidden\" id=\"jpep\" value=\"$jpep\">\n";                                                        
	echo "<table width=\"70%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"#cccccc\">\n";
	echo "<tr height=\"25\" bgcolor=\"#FFCC66\">\n";
	echo "    <th width=\"33%\" scope=\"col\">KELAS</th>\n";
	echo "    <th width=\"33%\" scope=\"col\">MATAPELAJARAN</th>\n";
	echo "    <th width=\"33%\" scope=\"col\">JENIS PEPERIKSAAN</th>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "    <th scope=\"col\">$ting $kelas</th>\n";
	echo "    <th scope=\"col\">$namamp</th>\n";
	echo "    <th scope=\"col\">";
	echo " $jpep $tahuntov";
	echo " </th>\n";
	echo "  </tr>\n";
	echo " </table>\n";

	print '<br>';

	print '<table width="70%" border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#ffffff">';
	print '<tr height=\"25\" bgcolor="#FFCC66">';
	print '<td width="2%"><div align="left" ><strong><center>BIL</center></strong></div></td>';
	print '<td width="7%"><div align="left" ><strong><center>NO KP</center></strong></div></td>';
	print '<td width="35%"><div align="left" ><strong><center>NAMA</center></strong></div></td>';
	print '<td width="5%"><div align="left" ><strong><center>TOV</center></strong></div></td>';
	print '<td width="5%"><div align="left" ><strong><center>SASARAN</center></strong></div></td>';
	print '</tr>';

	$i=0; $bil=0; $tab=0;

	if($ting=='T5' and $tahun=='2012'){
		switch ($kod)
		{
			case "BMMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, BM FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'BM';
			break;
			case "BAMA":	
			$querymark =oci_parse($conn_sispa, "SELECT nokp, BA FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'BA';
			break;
			case "BCMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, BC FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'BC';
			break;
			case "BIMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, BI FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'BI';
			break;
			case "BTMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, BT FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'BT';
			break;
			case "GEOMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, GEO FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'GEO';
			break;
			case "M3MA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, M3 FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'M3';
			break;
			case "PIMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, PI FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'PI';
			break;
			case "PMMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, PM FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'PM';
			break;
			case "PSVMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, PSV FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'PSV';
			break;
			case "SEJMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, SEJ FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'SEJ';
			break;
			case "SNMA":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, SN FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'SN';
			break;

			default:	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, $kod FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = $kod;
			break;
		}
	}else if(($ting=='D3' or $ting=='D4' or $ting=='D5' or $ting=='D6') and $tahun=='2012'){
		switch ($kod)
		{
			case "BMKC": case "BMKT":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, BMK FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'BMK';
			break;
			case "BMPC": case "BMPT":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, BMP FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'BMP';
			break;
			case "M3C1": case "M3T1":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, M3 FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'M3';
			break;
			case "BIC1": case "BIT1":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, BI FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'BI';
			break;
			case "SNC": case "SNT":	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, SN FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = 'SN';
			break;
			default:	
			$querymark = oci_parse($conn_sispa,"SELECT nokp, $kod FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
			$kodlama = $kod;
			break;
		}
	}else{
		$querymark = oci_parse($conn_sispa,"SELECT nokp, $kod FROM $tmarkah WHERE kodsek= :kodsek AND tahun='$tahuntov' AND $tahap='$tingtov' AND  jpep= :jpep ORDER BY nokp");
		$kodlama = $kod;
	}
	oci_bind_by_name($querymark, ':kodsek', $kodsek);
	oci_bind_by_name($querymark, ':jpep', $jpep);
	oci_execute($querymark);
	$markpel = array();
	while($rowmark = oci_fetch_array($querymark))
	{
		$markpel[] = $rowmark;
	}

	while($rowpel = oci_fetch_array($resultpel)) 
	{
		$nokp = stripslashes($rowpel['NOKP']);
		$jantina = stripslashes($rowpel['JANTINA']);
		$agama = stripslashes($rowpel['AGAMA']);
		$kaum = stripslashes($rowpel['KAUM']);
		
		if($ting=='T5' and $tahun!='2011'){
			$gmp="G$kodlama";
		}else{
			$gmp="G$kod";
		}
	
	   $qtov = "SELECT * FROM $theadcount WHERE nokp='$nokp' AND tahun= :tahun AND kodsek= :kodsek AND $tahap= :ting AND kelas= :kelas AND hmp= :kod";
		$stmt = oci_parse($conn_sispa,$qtov);
		oci_bind_by_name($stmt, ':tahun', $_SESSION['tahun']);
		oci_bind_by_name($stmt, ':kodsek', $kodsek);
		oci_bind_by_name($stmt, ':ting', $ting);
		oci_bind_by_name($stmt, ':kelas', $kelas);
		oci_bind_by_name($stmt, ':kod', $kod);
		oci_execute($stmt);
		$rowrtov = oci_fetch_array($stmt);
		$nama = $rowpel['NAMAP'];	
		$markah = trim($rowrtov['TOV']) ;
		$bil_qtov = count_row_oci_by_name5($qtov, $_SESSION['tahun'], $kodsek, $ting, $kelas, $kod, ":tahun", ":kodsek", ":ting", ":kelas", ":kod");
        if(($rowrtov['TOV'] =="") OR ($bil_qtov==0)){
			foreach($markpel as $rowNum => $mark) {
				if ($nokp == $mark['NOKP']){ $markah = trim($mark["$kodlama"]); }
			}
         } 
		$sasaran = trim($rowrtov['ETR']);

		$bil=$bil+1;

		print "<tr onmouseover=\"mover(this);\"  onmouseout=\"mout(this);\" bgcolor=\"#FFCC66\">";

		print "<td><center><strong>$bil</strong></center></td>";

		print "<td>$nokp<input name=\"nokp[$i]\" type=\"hidden\" readonly value=\"$nokp\" size=\"15\">";
		echo "<input name=\"jantina[$i]\" type=\"hidden\" readonly value=\"$jantina\" size=\"15\">";
		echo "<input name=\"agama[$i]\" type=\"hidden\" readonly value=\"$agama\" size=\"15\">";
		echo "<input name=\"kaum[$i]\" type=\"hidden\" readonly value=\"$kaum\" size=\"15\">";
		echo "</td>";

		print "<td>$nama<input name=\"nama[$i]\" type=\"hidden\" readonly value=\"$nama\" size=\"35%\"></td>";
        
		print "<td><center><input name=\"markah[$i]\" type=\"text\" value=\"$markah\" tabindex=\"2+$tab\" size=\"2\"></center></td>";
        
		print "<td><center><input name=\"target[$i]\" type=\"text\" value=\"$sasaran\" tabindex=\"3+$tab\" size=\"2\"></center></td>";

		print "</tr>";

		$i++;  $tab=$tab+3;

	}
print "<input name=\"bilpel\" type=\"hidden\" readonly value=\"$bil\" size=\"3\">";

print "</table>";

print "<br><br>";

print "<center>";
print "<input type=\"submit\" name=\"add\" value=\"Simpan Target\">";

echo "</form>";

print "</center>";

}// end function borang

?>
</td>
<?php include 'kaki.php';?> 
