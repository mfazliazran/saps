<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Analisa Mata Pelajaran Kelas <?php echo "$gting $gkelas";?></p>
<?php

//$q_guru = oci_parse($conn_sispa,"SELECT NOKP,NAMA,NAMASEK,KODSEK,TING,KELAS FROM login WHERE nokp='".$_SESSION['nokp']."' AND kodsek='".$_SESSION['kodsek']."' AND user1='".$_SESSION['SESS_MEMBER_ID']."'");
$q_guru = oci_parse($conn_sispa,"SELECT NOKP,NAMA,NAMASEK,KODSEK,TING,KELAS,STATUSSEK FROM tguru_kelas WHERE tahun='".$_SESSION['tahun']."' AND kodsek='".$_SESSION['kodsek']."' AND nokp='".$_SESSION['nokp']."'");
oci_execute($q_guru);
$row=oci_fetch_array($q_guru);
$nokp=$row["NOKP"];
$namagu=$row["NAMA"]; 
$namasek=$row["NAMASEK"]; 
$kodsek=$row["KODSEK"];
$ting=$row["TING"]; 
$gting=strtoupper($row["TING"]); 
$kelas=$row["KELAS"];

		
	switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$level="SR";
			break;
		case "P": case "T1": case "T2": case "T3":
			$level="MR";
			break;
		case "T4": case "T5":
			$level="MA";
			break;
	}
/////////////////////////
$q_namasek = oci_parse($conn_sispa,"SELECT namasek FROM login WHERE kodsek='".$_SESSION['kodsek']."'");
oci_execute($q_namasek);
$row_sek=oci_fetch_array($q_namasek);
$namasek=$row_sek["NAMASEK"];
$tahun = $_SESSION['tahun'];
////////////////////////////////
if($level=="MR"){
echo "<h3><center>$namasek<br>ANALISA MATA PELAJARAN KELAS<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3><br>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
//echo "Guru Kelas : $namagu<br>Kelas : $ting $kelas ";
//echo "<br><br>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Mata Pelajaran </div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">E</div></td>\n";
if($tahun>=2015){
	echo "    <td colspan=\"2\"><div align=\"center\">F</div></td>\n";
}
echo "	<td colspan=\"2\"><div align=\"center\">Lulus</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Gagal</div></td>\n";
echo "	<td rowspan=\"2\"><div align=\"center\">GPMP</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td ><div align=\"center\">Daftar</div></td>\n";
echo "    <td ><div align=\"center\">Ambil</div></td>\n";
echo "    <td ><div align=\"center\">TH</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td ><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td ><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
if($tahun>=2015){
	echo "    <td><div align=\"center\">Bil</div></td>\n";
	echo "    <td><div align=\"center\">%</div></td>\n";
}
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
$BIL=1;

$q_mp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru WHERE kodsek='".$_SESSION['kodsek']."' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp");
oci_execute($q_mp);
while($row_mp=oci_fetch_array($q_mp)){
$kodmp=$row_mp["KODMP"];
	$t_mp = oci_parse($conn_sispa,"SELECT MP FROM mpsmkc WHERE kod='$kodmp'");
	oci_execute($t_mp);
	while($row_tmp=oci_fetch_array($t_mp)){
	$BIL++;
	if($BIL&1) {
		$bcol = "#CDCDCD";
	} else {
		$bcol = "";
	}
	echo "  <tr bgcolor='$bcol'>\n";
	echo "    <td><center>".$BIL."</td>\n";
	echo "    <td>".$row_tmp["MP"]."</td>\n";
	$q_bildaftar = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bildaftar FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='".$_SESSION['kodsek']."' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp) is not null");
	oci_execute($q_bildaftar);
	$row_bildaftar=oci_fetch_array($q_bildaftar);
	echo "    <td><center>".$row_bildaftar["BILDAFTAR"]."</center></td>\n";
	$q_bilambil = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilambil FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp) != 'TH' AND trim(G$kodmp) is not null");
	oci_execute($q_bilambil);
	$row_bilambil=oci_fetch_array($q_bilambil);
	echo "    <td><center>".$row_bilambil["BILAMBIL"]."</center></td>\n";
	$q_bilth = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilth FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND  kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'TH'");
	oci_execute($q_bilth);
	$row_bilth=oci_fetch_array($q_bilth);
	echo "    <td><center>".$row_bilth["BILTH"]."</center></td>\n";
	
	$q_bila = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilA FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'A'");
	//echo("SELECT COUNT(G$kodmp) AS bilA FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'A'");
	oci_execute($q_bila);
	$row_bila=oci_fetch_array($q_bila);
	echo "    <td><center>".$row_bila["BILA"]."</center></td>\n";
	if($row_bila["BILA"] != 0){	echo "    <td><center>".number_format(($row_bila["BILA"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}

	$q_bilb = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilB FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'B'");
	oci_execute($q_bilb);
	$row_bilb=oci_fetch_array($q_bilb);
	echo "    <td><center>".$row_bilb["BILB"]."</center></td>\n";
	if($row_bilb["BILB"] != 0){	echo "    <td><center>".number_format(($row_bilb["BILB"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	$q_bilc = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilC FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'C'");
	oci_execute($q_bilc);
	$row_bilc=oci_fetch_array($q_bilc);
	echo "    <td><center>".$row_bilc["BILC"]."</center></td>\n";
	if($row_bilc["BILC"] != 0){	echo "    <td><center>".number_format(($row_bilc["BILC"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	$q_bild = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilD FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'D'");
	oci_execute($q_bild);
	$row_bild=oci_fetch_array($q_bild);
	echo "    <td><center>".$row_bild["BILD"]."</center></td>\n";
	if($row_bild["BILD"] != 0){	echo "    <td><center>".number_format(($row_bild["BILD"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	$q_bile = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilE FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'E'");
	oci_execute($q_bile);
	$row_bile=oci_fetch_array($q_bile);
	echo "    <td><center>".$row_bile["BILE"]."</center></td>\n";
	if($row_bile["BILE"] != 0){	echo "    <td><center>".number_format(($row_bile["BILE"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	if($tahun>=2015){
	$q_bilf = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilF FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'F'");
	oci_execute($q_bilf);
	$row_bilf=oci_fetch_array($q_bilf);
	echo "    <td><center>".$row_bilf["BILF"]."</center></td>\n";
	if($row_bilf["BILF"] != 0){	echo "    <td><center>".number_format(($row_bilf["BILF"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}		
	}
	
	$q_billulus = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS billulus FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND (G$kodmp = 'A' OR G$kodmp= 'B' OR G$kodmp= 'C' OR G$kodmp= 'D')");
	oci_execute($q_billulus);
	$row_billulus = oci_fetch_array($q_billulus);
	echo "    <td><center>".$row_billulus["BILLULUS"]."</center></td>\n";
	if($row_billulus["BILLULUS"] != 0){  echo "    <td><center>".number_format(($row_billulus["BILLULUS"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	if($tahun<=2014){
		$qrygagal = "AND trim(G$kodmp) = 'E'";	
	}else{
		$qrygagal = "AND trim(G$kodmp) = 'F'";
	}
	
	$q_bilgagal = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilgagal FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' $qrygagal");
	oci_execute($q_bilgagal);
	$row_bilgagal=oci_fetch_array($q_bilgagal);
	echo "    <td><center>".$row_bilgagal["BILGAGAL"]."</center></td>\n";
	if($row_bilgagal["BILGAGAL"] != 0){ echo "    <td><center>".number_format(($row_bilgagal["BILGAGAL"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	if(($row_bila["BILA"] + $row_bilb["BILB"] + $row_bilc["BILC"] + $row_bild["BILD"] + $row_bile["BELE"] + $row_bilf["BELF"] + $row_bilth["BILTH"]) != 0){
		if($tahun<=2014){
			echo "    <td><center>".number_format((($row_bila["BILA"]*1) + ($row_bilb["BILB"]*2) + ($row_bilc["BILC"]*3) + ($row_bild["BILD"]*4) + ($row_bile["BILE"]*5))/$row_bilambil["BILAMBIL"],2,'.',',') ."</center></td>\n";
		}else{
			echo "    <td><center>".number_format((($row_bila["BILA"]*1) + ($row_bilb["BILB"]*2) + ($row_bilc["BILC"]*3) + ($row_bild["BILD"]*4) + ($row_bile["BILE"]*5) + ($row_bilf["BILF"]*6))/$row_bilambil["BILAMBIL"],2,'.',',') ."</center></td>\n";
		}
	}else {
		echo "    <td><center>0.00</center></td>\n";
	}
	
	
	echo "  </tr>\n";
	}
}
echo "</table>\n";
}
////////////////////////////////////end if mr
if($level=="MA"){
//echo "<a href=../index.php> Kembali</a><br>";
echo "<h3><center>$namasek<br>ANALISA MATA PELAJARAN KELAS<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3><br>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
//echo "Guru Kelas : $namagu<br>Kelas : $ting $kelas ";
//echo "<br><br>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Mata Pelajaran </div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A+</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A-</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">B+</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">C+</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">E</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">G</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Lulus</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Gagal</div></td>\n";
echo "	<td rowspan=\"2\"><div align=\"center\">GPMP</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td ><div align=\"center\">Daftar</div></td>\n";
echo "    <td ><div align=\"center\">Ambil</div></td>\n";
echo "    <td ><div align=\"center\">TH</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td ><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td ><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";

echo "  </tr>\n";
//echo "  <tr>\n";
$BIL=1;
//echo "SELECT DISTINCT kodmp FROM sub_guru WHERE kodsek='".$_SESSION['kodsek']."' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp<br>";
$q_mp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru WHERE kodsek='".$_SESSION['kodsek']."' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp");
oci_execute($q_mp);
while($rowmp = oci_fetch_array($q_mp))
{
	$kod = "$rowmp[KODMP]";
	$gmp = "G$kod";
	//echo "SELECT $gmp FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='".$_SESSION['kodsek']."' AND jpep='".$_SESSION['jpep']."' AND $gmp is not null<br>";
	$q_gred = oci_parse($conn_sispa,"SELECT $gmp FROM markah_pelajar WHERE tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' AND kodsek='".$_SESSION['kodsek']."' AND jpep='".$_SESSION['jpep']."' AND $gmp is not null");
	oci_execute($q_gred);
	$bildaf = $bilambil = $bilL = $bil1AA = $bil1A = $bil2A = $bil3B = $bil4B = $bil5C = $bil6C = $bil7D = $bil8E = $bil9G = $bilTH = 0 ;
	while($rowgred = oci_fetch_array($q_gred))
	{
		switch (trim($rowgred["$gmp"]))
		{
			case 'A+' : $bil1AA = $bil1AA + 1; break;
			case 'A' : $bil1A = $bil1A + 1; break;
			case 'A-' : $bil2A = $bil2A + 1; break;
			case 'B+' : $bil3B = $bil3B + 1; break;
			case 'B' : $bil4B = $bil4B + 1; break;
			case 'C+' : $bil5C = $bil5C + 1; break;
			case 'C' : $bil6C = $bil6C + 1; break;
			case 'D' : $bil7D = $bil7D + 1; break;
			case 'E' : $bil8E = $bil8E + 1; break;
			case 'G' : $bil9G = $bil9G + 1; break;
			case 'TH' : $bilTH = $bilTH + 1; break;
		}
	}
	$bilL = $bil1AA + $bil1A + $bil2A + $bil3B + $bil4B + $bil5C + $bil6C + $bil7D + $bil8E ;
	$bildaf = $bilL + $bil9G + $bilTH ;
	$bilambil = $bilL + $bil9G ;
	$t_mp = oci_parse($conn_sispa,"SELECT * FROM mpsmkc WHERE kod='$kod'");
	oci_execute($t_mp);
	$namamp = oci_fetch_array($t_mp);
	$BIL++;
	if($BIL&1) {
		$bcol = "#CDCDCD";
	} else {
		$bcol = "";
	}
	echo "  <tr bgcolor='$bcol'>\n";
	echo "    <td><center>".$BIL."</td>\n";
	echo "    <td>".$namamp["MP"]."</td>\n";
	echo "    <td><center>$bildaf</center></td>\n";
	echo "    <td><center>$bilambil</center></td>\n";
	echo "    <td><center>$bilTH</center></td>\n";
	
	echo "    <td><center>$bil1AA</center></td>\n";
	if($bil1AA != 0){	echo "    <td><center>".number_format(($bil1AA/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil1A</center></td>\n";
	if($bil1A != 0){	echo "    <td><center>".number_format(($bil1A/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil2A</center></td>\n";
	if($bil2A != 0){	echo "    <td><center>".number_format(($bil2A/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil3B</center></td>\n";
	if($bil3B != 0){	echo "    <td><center>".number_format(($bil3B/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil4B</center></td>\n";
	if($bil4B != 0){	echo "    <td><center>".number_format(($bil4B/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil5C</center></td>\n";
	if($bil5C != 0){	echo "    <td><center>".number_format(($bil5C/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil6C</center></td>\n";
	if($bil6C != 0){	echo "    <td><center>".number_format(($bil6C/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil7D</center></td>\n";
	if($bil7D != 0){	echo "    <td><center>".number_format(($bil7D/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil8E</center></td>\n";
	if($bil8E != 0){	echo "    <td><center>".number_format(($bil8E/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil9G</center></td>\n";
	if($bil9G != 0){	echo "    <td><center>".number_format(($bil9G/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bilL</center></td>\n";
	if($bilL != 0){  echo "    <td><center>".number_format(($bilL/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	echo "    <td><center>$bil9G</center></td>\n";
	if($bil9G != 0){ echo "    <td><center>".number_format(($bil9G/$bilambil)*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	if(($bil1AA + $bil1A + $bil2A + $bil3B + $bil4B + $bil5C + $bil6C + $bil7D + $bil8E + $bil9G) != 0){
		echo "    <td><center>".number_format((($bil1AA*0) + ($bil1A*1) + ($bil2A*2) + ($bil3B*3) + ($bil4B*4) + ($bil5C*5) + ($bil6C*6) + ($bil7D*7) + ($bil8E*8) + ($bil9G*9))/$bilambil,2,'.',',') ."</center></td>\n";
	} else { echo "    <td><center>0.00</center></td>\n"; }
	echo "  </tr>\n";
	}
echo "</table>\n";
}
//////////////////////////////////////////////////end ma
/////////////start sr
if($level=="SR"){
echo "<h3><center>$namasek<br>ANALISA MATA PELAJARAN KELAS<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h3><br>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
//echo "Guru Kelas : $namagu<br>Kelas : $ting $kelas ";
//echo "<br><br>";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Mata Pelajaran </div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">E</div></td>\n";
if($tahun==2015){
	if($ting!='D6'){
		echo "    <td colspan=\"2\"><div align=\"center\">F</div></td>\n";
	}
}
echo "	<td colspan=\"2\"><div align=\"center\">Lulus</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Gagal</div></td>\n";
echo "	<td rowspan=\"2\"><div align=\"center\">GPMP</div></td>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"#FFCC99\">\n";
echo "    <td ><div align=\"center\">Daftar</div></td>\n";
echo "    <td ><div align=\"center\">Ambil</div></td>\n";
echo "    <td ><div align=\"center\">TH</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td ><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td ><div align=\"center\">Bil</div></td>\n";
echo "    <td ><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
if($tahun==2015){
	if($ting!='D6')	{//D1-D5
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
	}
}
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "  </tr>\n";
//echo "  <tr>\n";
$BIL=0;
$q_mp = oci_parse($conn_sispa,"SELECT DISTINCT kodmp FROM sub_guru WHERE kodsek='".$_SESSION['kodsek']."' AND tahun='".$_SESSION['tahun']."' AND ting='$ting' AND kelas='$kelas' ORDER BY kodmp");
oci_execute($q_mp);
while($row_mp=oci_fetch_array($q_mp)){
$kodmp=$row_mp["KODMP"];
	$t_mp = oci_parse($conn_sispa,"SELECT * FROM mpsr WHERE kod='$kodmp'");
	oci_execute($t_mp);
	while($row_tmp=oci_fetch_array($t_mp)){
	$BIL++;
	if($BIL&1) {
		$bcol = "#CDCDCD";
	} else {
		$bcol = "";
	}
	echo "  <tr bgcolor='$bcol'>\n";
	echo "    <td><center>".$BIL."</td>\n";
	echo "    <td>".$row_tmp["MP"]."</td>\n";
	$q_bildaftar = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bildaftar FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='".$_SESSION['kodsek']."' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp) is not null");
	oci_execute($q_bildaftar);
	$row_bildaftar=oci_fetch_array($q_bildaftar);
	echo "    <td><center>".$row_bildaftar["BILDAFTAR"]."</center></td>\n";
	$q_bilambil = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilambil FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp) != 'TH' AND trim(G$kodmp) is not null");
	oci_execute($q_bilambil);
	$row_bilambil=oci_fetch_array($q_bilambil);
	echo "    <td><center>".$row_bilambil["BILAMBIL"]."</center></td>\n";
	$q_bilth = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilth FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND  kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'TH'");
	oci_execute($q_bilth);
	$row_bilth=oci_fetch_array($q_bilth);
	echo "    <td><center>".$row_bilth["BILTH"]."</center></td>\n";
	
	$q_bila = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilA FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'A'");
	oci_execute($q_bila);
	$row_bila=oci_fetch_array($q_bila);
	echo "    <td><center>".$row_bila["BILA"]."</center></td>\n";
	if($row_bila["BILA"] != 0){	echo "    <td><center>".number_format(($row_bila["BILA"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}

	$q_bilb = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilB FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'B'");
	oci_execute($q_bilb);
	$row_bilb=oci_fetch_array($q_bilb);
	echo "    <td><center>".$row_bilb["BILB"]."</center></td>\n";
	if($row_bilb["BILB"] != 0){	echo "    <td><center>".number_format(($row_bilb["BILB"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	$q_bilc = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilC FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'C'");
	oci_execute($q_bilc);
	$row_bilc=oci_fetch_array($q_bilc);
	echo "    <td><center>".$row_bilc["BILC"]."</center></td>\n";
	if($row_bilc["BILC"] != 0){	echo "    <td><center>".number_format(($row_bilc["BILC"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	$q_bild = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilD FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'D'");
	oci_execute($q_bild);
	$row_bild=oci_fetch_array($q_bild);
	echo "    <td><center>".$row_bild["BILD"]."</center></td>\n";
	if($row_bild["BILD"] != 0){	echo "    <td><center>".number_format(($row_bild["BILD"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	$q_bile = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilE FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'E'");
	oci_execute($q_bile);
	$row_bile=oci_fetch_array($q_bile);
	echo "    <td><center>".$row_bile["BILE"]."</center></td>\n";
	if($row_bile["BILE"] != 0){	echo "    <td><center>".number_format(($row_bile["BILE"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	if($tahun==2015){
		if($ting!='D6'){
			$q_bilf = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilF FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND trim(G$kodmp)= 'F'");
			oci_execute($q_bilf);
			$row_bilf=oci_fetch_array($q_bilf);
			echo "    <td><center>".$row_bilf["BILF"]."</center></td>\n";
			if($row_bilf["BILF"] != 0){	echo "    <td><center>".number_format(($row_bilf["BILF"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}		
		}
	}
	if($tahun<=2014){//D1-D6
		$q_billulus = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS billulus FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND (G$kodmp = 'A' OR G$kodmp= 'B' OR G$kodmp= 'C')");
	}elseif($tahun==2015){
		if($ting=='D6'){//D6
			$q_billulus = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS billulus FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND (G$kodmp = 'A' OR G$kodmp= 'B' OR G$kodmp= 'C')");
		}else{//D1-D5
			$q_billulus = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS billulus FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND (G$kodmp = 'A' OR G$kodmp= 'B' OR G$kodmp= 'C' OR G$kodmp= 'D' OR G$kodmp= 'E')");
		}
	}else{//2016 ke atas
		$q_billulus = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS billulus FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' AND (G$kodmp = 'A' OR G$kodmp= 'B' OR G$kodmp= 'C' OR G$kodmp= 'D' )");
	}
	oci_execute($q_billulus);
	$row_billulus = oci_fetch_array($q_billulus);
	echo "    <td><center>".$row_billulus["BILLULUS"]."</center></td>\n";
	if($row_billulus["BILLULUS"] != 0){  echo "    <td><center>".number_format(($row_billulus["BILLULUS"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	if($tahun<=2014){
		$qrygagal = "AND (G$kodmp = 'D' OR G$kodmp = 'E')";	
	}elseif($tahun==2015){
		if($ting=='D6'){
			$qrygagal = "AND (G$kodmp = 'D' OR G$kodmp = 'E')";	
		}else{
			$qrygagal = "AND trim(G$kodmp) = 'F'";
		}
	}else{
		$qrygagal = "AND trim(G$kodmp) = 'E'";
	}
	$q_bilgagal = oci_parse($conn_sispa,"SELECT COUNT(G$kodmp) AS bilgagal FROM markah_pelajarsr WHERE tahun='".$_SESSION['tahun']."' AND darjah='$ting' AND kelas='$kelas' AND kodsek='$kodsek' AND jpep='".$_SESSION['jpep']."' $qrygagal");
	oci_execute($q_bilgagal);
	$row_bilgagal=oci_fetch_array($q_bilgagal);
	echo "    <td><center>".$row_bilgagal["BILGAGAL"]."</center></td>\n";
	if($row_bilgagal["BILGAGAL"] != 0){ echo "    <td><center>".number_format(($row_bilgagal["BILGAGAL"]/$row_bilambil["BILAMBIL"])*100,2,'.',',') ."</center></td>\n";} else { echo "<td><center>0.00</center></td>";}
	
	if(($row_bila["BILA"] + $row_bilb["BILB"] + $row_bilc["BILC"] + $row_bild["BILD"] + $row_bile["BILE"] + $row_bilf["BILF"] + $row_bilth["BILTH"]) != 0){
		if($tahun<=2014){
			echo "    <td><center>".number_format((($row_bila["BILA"]*1) + ($row_bilb["BILB"]*2) + ($row_bilc["BILC"]*3) + ($row_bild["BILD"]*4) + ($row_bile["BILE"]*5))/$row_bilambil["BILAMBIL"],2,'.',',') ."</center></td>\n";
		}elseif($tahun==2015){
			if($ting!='D6'){
				echo "    <td><center>".number_format((($row_bila["BILA"]*1) + ($row_bilb["BILB"]*2) + ($row_bilc["BILC"]*3) + ($row_bild["BILD"]*4) + ($row_bile["BILE"]*5) + ($row_bilf["BILF"]*6))/$row_bilambil["BILAMBIL"],2,'.',',') ."</center></td>\n";	
			}else{
				echo "    <td><center>".number_format((($row_bila["BILA"]*1) + ($row_bilb["BILB"]*2) + ($row_bilc["BILC"]*3) + ($row_bild["BILD"]*4) + ($row_bile["BILE"]*5))/$row_bilambil["BILAMBIL"],2,'.',',') ."</center></td>\n";	
			}
		}else{
			echo "    <td><center>".number_format((($row_bila["BILA"]*1) + ($row_bilb["BILB"]*2) + ($row_bilc["BILC"]*3) + ($row_bild["BILD"]*4) + ($row_bile["BILE"]*5) )/$row_bilambil["BILAMBIL"],2,'.',',') ."</center></td>\n";	
		}
	}
	else {
	echo "    <td><center>0.00</center></td>\n";
	}
	echo "  </tr>\n";
	}
}
echo "</table>\n";
}

/////////////
?>
</td>
<?php include 'kaki.php';?> 