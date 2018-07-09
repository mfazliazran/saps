<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
include "input_validation.php";

$tahun = $_SESSION['tahun'];
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];

$ting = validate($_GET['ting']);
$kelas = validate($_GET['kelas']);

$tkt = array("P" => "PERALIHAN","T1" => "TINGKATAN SATU","T2" => "TINGKATAN DUA","T3" => "TINGKATAN TIGA","T4" => "TINGKATAN EMPAT","T5" => "TINGKATAN LIMA","D1" => "TAHUN SATU","D2" => "TAHUN DUA","D3" => "TAHUN TIGA","D4" => "TAHUN EMPAT","D5" => "TAHUN LIMA","D6" => "TAHUN ENAM");
$tingkatan = $tkt["$ting"] ;

$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun='$tahun' AND gk.kodsek='$kodsek' AND gk.ting= :ting AND gk.kelas= :kelas AND gk.kodsek=ts.kodsek");
oci_bind_by_name($q_guru, ':ting', $ting);
oci_bind_by_name($q_guru, ':kelas', $kelas);
OCIExecute($q_guru);
OCIFetch($q_guru);
$namagu = OCIResult($q_guru,"NAMA"); 
$namasek = OCIResult($q_guru,"NAMASEK");

$m="$ting&&kelas=$kelas&&namaguru=$namagu&&tingkatan=$tingkatan&&namasekolah=$namasek";

?>

<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>

<form action="ctk_missmr.php?ting=<?php echo $m;?>" method="POST" target="_blank">

<td valign="top" class="rightColumn">
<p class="subHeader">Senarai Pencapaian Kecemerlangan Calon Mengikut Kategori</p>
<?php

$q_murid = oci_parse($conn_sispa,"SELECT * FROM markah_pelajar mkh, tnilai_smr mr WHERE mkh.nokp=mr.nokp 
 AND mkh.tahun=mr.tahun and mkh.kodsek=mr.kodsek AND mkh.ting=mr.ting AND mkh.kelas=mr.kelas AND mkh.jpep=mr.jpep AND mkh.tahun='$tahun' AND mkh.kodsek='$kodsek' AND mkh.ting= :ting AND mkh.kelas= :kelas AND mkh.jpep='$jpep' AND (BILB > 0) AND NVL(BILC,0)=0 AND NVL(BILD,0)=0 AND NVL(BILE,0)=0 AND NVL(BILTH,0)=0 ORDER BY mr.keputusan DESC, mr.gpc ASC, mr.peratus DESC");
oci_bind_by_name($q_murid, ':ting', $ting);
oci_bind_by_name($q_murid, ':kelas', $kelas);
oci_execute($q_murid);

while ( $rowmurid = oci_fetch_array($q_murid))
{
	$rpel[] = $rowmurid;
}

$q_sub = oci_parse($conn_sispa,"SELECT * FROM mpsmkc mp, sub_guru sg WHERE sg.tahun='$tahun' AND sg.kodsek='$kodsek' AND sg.ting= :ting AND sg.kelas= :kelas AND mp.kod=sg.kodmp ORDER BY mp.susun ");
oci_bind_by_name($q_sub, ':ting', $ting);
oci_bind_by_name($q_sub, ':kelas', $kelas);
oci_execute($q_sub);
while ( $rowsub = oci_fetch_array($q_sub))
{
	$mpkelas[] = array("KODMP"=>$rowsub["KODMP"]);
}

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
echo "<h5><center>$namasek<br>SENARAI PENCAPAIAN KECEMERLANGAN CALON MENGIKUT KATEGORI $tingkatan<br>".jpep($jpep)." TAHUN ".$tahun."</center></h5>";
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n";
echo "<h5><center>SEKURANG-KURANGNYA SATU B</center></h5>";
echo "<tr bgcolor=\"#FFCC99\">";
echo "GURU KELAS : $namagu<br>TINGKATAN : $ting  $kelas";
echo "<td rowspan = \"2\"><center>Bil</center></td>";
echo "<td rowspan = \"2\">NAMA MURID</td>";
echo "<td rowspan = \"2\"><center>BIL MP</center></td>";
echo "<td rowspan = \"2\"><center>PENCAPAIAN</center></td>";

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
echo "<td rowspan = \"2\"><center>GPS</center></td>";	  

echo "</tr>";
echo "<tr bgcolor=\"#FFCC99\">";
for ($i = 0; $i <= $key; $i++)
{
	echo "<td><center>M</center></td>";
	echo "<td><center>G</center></td>";
}
echo "</tr>";

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
		echo "    <td>".$murid["NAMA"]."</td>\n";
		echo "    <td><center>".$murid['BILMP']."</center></td>\n";
		echo "    <td align=\"center\">".$murid["PENCAPAIAN"]."</td>\n";
		$bilA=0;$bilB=0;$bilC=0;$bilD=0;$bilE=0;$gps=0;
		foreach( $mpkelas as $key2 => $sub )
		{		
			$mkh = $sub["KODMP"];
			//die ("$mkh");
			$gmkh = "G$mkh";
			$gred = trim($murid["$gmkh"]);
			if($gred=='A')
				$bilA++;
			if($gred=='B')
				$bilB++;
			if($gred=='C')
				$bilC++;
			if($gred=='D')
				$bilD++;
			if($gred=='E')
				$bilE++;

			echo "    <td><center>&nbsp;".$murid["$mkh"]."</center></td>\n";
			echo "    <td><center>&nbsp;".$murid["$gmkh"]."</center></td>\n";
		}

		$gps = gpmpmrsr($bilA,$bilB,$bilC,$bilD,$bilE,$murid['BILMP']);
		echo "    <td><center>$gps</center></td>\n";
	}
}
else {
		$bilcol = 9 + ($key+1)*2;
		echo "<tr>";
		echo "<br>";
		echo "<td colspan = \"$bilcol\"><center><font color=\"#FF0000\"><strong>1. MARKAH AKAN DIPAPARKAN JIKA KELAS TERSEBUT ADA A DAN B SAHAJA <br> 2. KEPADA SUP, SILA KLIK BUTANG 'PROSES MARKAH PEPERIKSAAN' UNTUK MENDAPAT MARKAH TERKINI.</strong></font><center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
?>
<br><br>
  <center><input type="submit"  name="submit" value="CETAK">
  </center>
</p>
</form>
<?php 
include 'kaki.php';
?> 