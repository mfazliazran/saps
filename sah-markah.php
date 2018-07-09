<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
//latest edited 23/6/2014 11.04am Naeim
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Pengesahan Kemasukan Markah Peperiksaan</p>

<?php
echo "<br><br>";
echo "<center><h3>PENGESAHAN KEMASUKKAN MARKAH PEPERIKSAAN<br>".jpep("".$_SESSION['jpep']."")."<br>".$_SESSION['tahun']."</h3><center>";
echo "<table align=\"center\" width=\"300\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr>\n";
echo "    <td width=\"146\"><div align=\"center\">Tingkatan / Tahun </div></td>\n";
echo "    <td width=\"144\"><div align=\"center\">Tarikh Sah</div></td>\n";
echo "    <td width=\"144\"><div align=\"center\">Status</div></td>\n";
echo "  </tr>\n";
//$q_ting2="SELECT count(DISTINCT ting) as BIL FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting not in ('D1','D2','D3','D4','P','T1','T2','T3') ORDER by ting";
$q_ting2="SELECT count(DISTINCT ting) as BIL FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting not in ('D1','D2','D3','D4','D5','P','T1','T2','T3') ORDER by ting";

//AND ting not in ('D1','D2','D3','D4','P','T1','T2','T3') 
//if ($kodsek=="AEE3045")
// echo "$q_ting2<br>";
$stmt2 = oci_parse($conn_sispa,$q_ting2);
oci_execute($stmt2);
$datating=oci_fetch_array($stmt2);
$numt=$datating["BIL"];
$bilsah=0;
$bolehsah=0;
$sahupsr=0;
$sahpmr=0;
$sahspm=0;

$q_ting="SELECT DISTINCT ting FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' ORDER by ting";
// and ting not in ('D1','D2','D3','D4','P','T1','T2','T3')
//if ($kodsek=="PBB3014")
  //echo "$q_ting<br>";
$stmt = oci_parse($conn_sispa,$q_ting);
oci_execute($stmt);
while($rowtg=oci_fetch_array($stmt)){
echo "  <tr>\n";
echo "    <td><a href=\"data-semakmp-ting.php?ting=".OCIResult($stmt,"TING")."\"><div align=\"center\">".OCIResult($stmt,"TING")."</div></a></td>\n";

	//$q_sah="SELECT * FROM tsah WHERE kodsek='$kodsek' AND ting='$rowtg[TING]' AND tahun='".$_SESSION['tahun']."' and jpep='".$_SESSION['jpep']."' and ting not in ('D1','D2','D3','D4','P','T1','T2','T3')";
	$q_sah="SELECT * FROM tsah WHERE kodsek='$kodsek' AND ting='$rowtg[TING]' AND tahun='".$_SESSION['tahun']."' and jpep='".$_SESSION['jpep']."' and ting not in ('D1','D2','D3','D4','D5','P','T1','T2','T3')";
	$sql = OCIParse($conn_sispa,$q_sah);
	OCIExecute($sql);
	
	$q_sah2="SELECT * FROM tsah WHERE kodsek='$kodsek' AND ting='$rowtg[TING]' AND tahun='".$_SESSION['tahun']."' and jpep='".$_SESSION['jpep']."'";
	$sql2 = OCIParse($conn_sispa,$q_sah2);
	OCIExecute($sql2);
	OCIFetch($sql2);
	$num2=count_row($q_sah2);
	//if ($kodsek=="BEA7615")
  		//echo "$q_sah2<br>";
	
	$num=count_row($q_sah);
	OCIFetch($sql);
	if($num >= 1){ 
		$bilsah=$bilsah+1; 
		if($_SESSION['jpep']=="UPSRC")
			$sahupsr=1;
		if($_SESSION['jpep']=="PMRC")
			$sahpmr=1;
		if($_SESSION['jpep']=="SPMC")
			$sahspm=1;
		$st = "<center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center>";
	}
	else{ 
		if($num2 >= 1){
			$st = "<center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center>";	
		}else{
			$st = "<center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center>";
		}
	} 
		echo "<td><div align=\"center\">&nbsp;".OCIResult($sql2,"TKSAH")."</div></td>\n";
		echo "<td><div align=\"center\">$st</div></td>\n";
		echo "</tr>\n";
	}
echo "</table><br><br>\n"; 
//echo "bilsah - $bilsah numt - $numt<br>";

$jpep = $_SESSION['jpep'];
if($jpep == "U1" or $jpep=="U2" or $jpep=="PPT"){// or $jpep=="PAT"){
	if($bilsah==$numt)
		$bolehsah=1;
}
else if($jpep=="UPSRC"){
	if($sahupsr==1)
		$bolehsah=1;
}
else if($jpep=="PMRC"){
	if($sahpmr==1)
		$bolehsah=1;
}
else if($jpep=="SPMC"){
	if($sahspm==1)
		$bolehsah=1;
}
else if($jpep=="PAT"){
	//echo $bilsah." - ".$numt."<br>";
	//echo $jsek."<br>";
	if($jsek=="SM"){
		if($bilsah==$numt-1)//tolak 1 sbb t5 x amik
			$bolehsah=1;
	}else if($jsek=="SR"){
		if($bilsah==$numt)
		$bolehsah=1;
	}
}
//if ($kodsek=="AEE3045")
  //echo "bilsah - $bolehsah jpep - $jpep<br>";
if($jpep<>'LNS01') {
	if($bolehsah==1){
		echo "<form name=\"form1\" method=\"post\" action=\"\">\n";
		echo "  <input type=\"submit\" name=\"cetak\" onClick=\"window.open('surat-sah.php?data=".$_SESSION['tahun']."/".$kodsek."/".$_SESSION['jpep']."','mywindow','toolbal=no,menubar=yes,resizable=yes,scrollbars=yes')\" value=\"Cetak Surat Pengesahan\">\n";
		echo "</form>\n";
	}else {
		echo "<form name=\"form1\" method=\"post\" onsubmit=\"javascript:alert('Sila Buat Pengesahan pada menu Semakan -> Semak Key-in Markah')\">\n";
		echo "<br><br><center><input type=\"submit\" name=\"cetak\" value=\"Cetak Surat Pengesahan\"></center>\n";
		echo "</form>\n";
	}
	}
?>
</td>
<?php include 'kaki.php';?> 