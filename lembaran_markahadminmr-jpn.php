<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';

$tahun = $_SESSION['tahun'];
$ting = $_GET['ting'];
$kelas = $_GET['kelas'];
//$tingkatan = $_GET['tingkatan'];
//$namagu = $_GET['namaguru'];
//$namasek = $_GET['namasekolah'];
$kodsek = $_SESSION['kodsek2'];
$jpep = $_SESSION['jpep'];
$tingkatan = tahap($ting);

if($kelas<>""){
	$q_sql=("SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.kodsek='$kodsek' and gk.tahun='$tahun' AND gk.ting='$ting' AND gk.kelas='$kelas' AND gk.kodsek=ts.kodsek");
	$kodkelas = "AND mkh.kelas='$kelas'";
	$kodkelas2 = "AND sg.kelas='$kelas'";
}else{
	$q_sql=("SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.kodsek='$kodsek' and gk.tahun='$tahun' AND gk.ting='$ting' AND gk.kodsek=ts.kodsek");
	$kodkelas= "";
	$kodkelas2 = "";
}
$q_sql=oci_parse ($conn_sispa,$q_sql);
oci_execute($q_sql);
$row = oci_fetch_array($q_sql);
$namasek = $row["NAMASEK"];
if($kelas<>"")
	$namagu = $row["NAMA"];
else
	$namagu = "KESELURUHAN $tingkatan";

$m="$ting&kelas=$kelas";//&&namaguru=$namagu&&tingkatan=$tingkatan";//&&namasekolah=$namasek";

?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>

<form action="ctk_lembaran-markahadminmr-jpn.php?ting=<?php echo $m;?>" method="POST" target="_blank">

<td valign="top" class="rightColumn">
<p class="subHeader">Lembaran Markah</p>

<?php



$q_mkdt = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE 
mkh.kodsek='$kodsek' and mkh.nokp=mr.nokp and
 mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep
AND mkh.tahun='$tahun' AND mkh.ting='$ting' AND mkh.jpep='$jpep' ORDER BY mr.keputusan Asc, mr.gpc ASC, mr.peratus DESC");
oci_execute($q_mkdt);
$bilmkdt = count_row("SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.kodsek='$kodsek' and mkh.nokp=mr.nokp AND 
 mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep and
mkh.tahun='$tahun' AND mkh.ting='$ting' AND mkh.jpep='$jpep' ORDER BY mr.keputusan Asc, mr.gpc ASC, mr.peratus DESC");
$q_murid = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.kodsek='$kodsek' and mkh.nokp=mr.nokp 
 AND 
 mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep 
AND mkh.tahun='$tahun' AND mkh.ting='$ting' $kodkelas AND mkh.jpep='$jpep' ORDER BY mr.keputusan Asc, mr.gpc ASC, mr.peratus DESC");
oci_execute($q_murid);
$bilmurid = count_row("SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.kodsek='$kodsek' and mkh.nokp=mr.nokp AND 
 mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek
and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep and 
mkh.tahun='$tahun' AND mkh.ting='$ting' $kodkelas AND mkh.jpep='$jpep' ORDER BY mr.keputusan Asc, mr.gpc ASC, mr.peratus DESC");
while ( $rowmurid = oci_fetch_array($q_murid))
{
	$rpel[] = $rowmurid;
}

if($kelas<>"")
	$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc mp, sub_guru sg WHERE sg.kodsek='$kodsek' and sg.tahun='$tahun' AND sg.ting='$ting' $kodkelas2 AND mp.kod=sg.kodmp ORDER BY mp.kod");
else
	$q_sub = oci_parse($conn_sispa,"SELECT DISTINCT KODMP,KOD FROM mpsmkc mp, sub_guru sg WHERE sg.kodsek='$kodsek' and sg.tahun='$tahun' AND sg.ting='$ting' AND mp.kod=sg.kodmp ORDER BY mp.kod");
	
//if($kodsek=='BEA8626')
	//echo "SELECT * FROM mpsmkc mp, sub_guru sg WHERE sg.kodsek='$kodsek' and sg.tahun='$tahun' AND sg.ting='$ting' $kodkelas2 AND mp.kod=sg.kodmp ORDER BY mp.kod";
	//echo "SELECT DISTINCT KODMP,KOD FROM mpsmkc mp, sub_guru sg WHERE sg.kodsek='$kodsek' and sg.tahun='$tahun' AND sg.ting='$ting' AND mp.kod=sg.kodmp ORDER BY mp.kod";
//echo "SELECT DISTINCT KODMP FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting='$ting' AND mp.kod=sg.kodmp ORDER BY mp.kod";
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$mpkelas[] = array("KODMP"=>$rowsub["KODMP"]);
	//echo $rowsub["KODMP"]."<br>";
}

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h5><center>$namasek<br>LEMBARAN MARKAH MURID $tingkatan<br>".jpep($jpep)." TAHUN ".$tahun."</center></h5>";
//echo "GURU KELAS : $namagu<br>TINGKATAN : $ting  $kelas";
echo "<table align=\"center\" width=\"98%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "<tr><td width='10%'><b>GURU KELAS</b></td><td width='1%'><b>:</b></td><td width='87%'><b>$namagu</b></td></tr>";
echo "<tr><td><b width='10%'>TINGKATAN</b><td width='1%'><b>:</b></td><td width='87%'><b>$ting  $kelas</b></td></td></tr>";
echo "</table>";
echo "<br>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<tr bgcolor=\"#FFCC99\">";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";

if (!empty($mpkelas))
{
	foreach($mpkelas as $key => $subjek)
	{
		echo "<td colspan = \"2\"><center>".$subjek["KODMP"]."</center></td>";
	}
}
else {
		echo "<td colspan = \"2\"><center>SUBJEK KELAS</center></td>";
     }
	  
echo "<td rowspan = \"2\"><center>JUM MARKAH</center></td>";
echo "<td rowspan = \"2\"><center>PERATUS</center></td>";
echo "<td rowspan = \"2\"><center>GPC</center></td>";
echo "<td rowspan = \"2\"><center>MENGUASAI / <br>TIDAK MENGUASAI</center></td>";
echo "<td rowspan = \"2\"><center>KDK</center></td>";
echo "<td rowspan = \"2\"><center>KDT</center></td>";
echo "<td rowspan = \"2\"><center>PENCAPAIAN</center></td>";
echo "</tr>";
echo "<tr bgcolor=\"#FFCC99\">";
for ($i = 0; $i <= $key; $i++)
{
	echo "<td><center>M</center></td>";
	echo "<td><center>G</center></td>";
}
echo "</tr>";
//////habis kepala

if (!empty($rpel))
{
	foreach( $rpel as $key1 => $murid )
	{
		$bil = $key1 + 1;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "    <tr bgcolor='$bcol'>\n";
		echo "    <td><center>$bil</center></td>\n";
		echo "    <td>".$murid["NAMA"]."<br>[".$murid["KELAS"]."]</td>\n";
		foreach( $mpkelas as $key2 => $sub )
		{		
			$mkh = $sub["KODMP"];
			//die ("$mkh");
			$gmkh = "G$mkh";
			//die ("$gmkh");
			echo "    <td><center>&nbsp;".$murid["$mkh"]."</center></td>\n";
			echo "    <td><center>&nbsp;".$murid["$gmkh"]."</center></td>\n";
		}
		if($kelas=="")
			$bilmurid = count_row("SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.kodsek='$kodsek' and mkh.nokp=mr.nokp AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek and mkh.ting=mr.ting and mkh.kelas=mr.kelas and mkh.jpep=mr.jpep and mkh.tahun='$tahun' AND mkh.ting='$ting' and mkh.kelas='".$murid["KELAS"]."' AND mkh.jpep='$jpep' ORDER BY mr.keputusan DESC, mr.gpc ASC, mr.peratus DESC");
		echo "    <td><center>".$murid["JUMMARK"]."</center></td>\n";
		echo "    <td><center>".$murid["PERATUS"]."</center></td>\n";
		echo "    <td><center>".$murid["GPC"]."</center></td>\n";
		echo "    <td><center>".$murid["KEPUTUSAN"]."</center></td>\n";
		echo "    <td><center>".$murid["KDK"]."/$bilmurid</center></td>\n";
		echo "    <td><center>".$murid["KDT"]."/$bilmkdt</center></td>\n";
		echo "    <td>".$murid["PENCAPAIAN"]."</td>\n";
	}
}
else {
		$bilcol = 9 + ($key+1)*2;
		echo "<tr>";
		echo "<br>";
		echo "<td colspan = \"$bilcol\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
?>
<br><br>
&nbsp;&nbsp;<input type="submit" name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('data-markah-adminmr-excel-jpn.php?ting=<?php echo $m;?>','win1');" />
</form>
<?php 
include 'kaki.php';
function tahap($ting)
{
	switch ($ting){
		case "P": $sting="KELAS PERALIHAN";
		break;
		case "T1":
		$sting="TINGKATAN 1";
		break;
		case "T2":
		$sting="TINGKATAN 2";
		break;
		case "T3":
		$sting="TINGKATAN 3";
		break;
		case "T4":
		$sting="TINGKATAN 4";
		break;
		case "T5":
		$sting="TINGKATAN 5";
		break;
		case "D1":
		$sting="TAHUN 1";
		break;
		case "D2":
		$sting="TAHUN 2";
		break;
		case "D3":
		$sting="TAHUN 3";
		break;
		case "D4":
		$sting="TAHUN 4";
		break;
		case "D5":
		$sting="TAHUN 5";
		break;
		case "D6":
		$sting="TAHUN 6";
		break;
	}
return $sting;
}
?> 