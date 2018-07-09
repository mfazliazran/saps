<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsikira.php';
include "input_validation.php";

$tahun = $_SESSION['tahun'];
$ting=validate($_POST["ting"]);
$kelas=validate($_POST["kelas"]);
$kodsek = $_SESSION['kodsek'];
$jpep = $_SESSION['jpep'];

$q_guru = OCIParse($conn_sispa,"SELECT * FROM tguru_kelas gk, tsekolah ts WHERE gk.tahun= :s_tahun AND gk.kodsek= :s_kodsek AND gk.ting= :ting AND gk.kelas= :kelas AND gk.kodsek=ts.kodsek");
oci_bind_by_name($q_guru, ':s_tahun', $_SESSION['tahun']);
oci_bind_by_name($q_guru, ':s_kodsek', $_SESSION['kodsek']);
oci_bind_by_name($q_guru, ':ting', $ting);
oci_bind_by_name($q_guru, ':kelas', $kelas);
OCIExecute($q_guru);
OCIFetch($q_guru);
$namagu = OCIResult($q_guru,"NAMA");
$namasek = OCIResult($q_guru,"NAMASEK");

$m="$ting&kelas=$kelas";
?>
<script language="javascript" type="text/javascript">
function open_window (fileName,windowName)
{
	mywindow=window.open(fileName,windowName,'width=800 height=600,directories=no,location=no,menubar=yes,scrollbars=yes,status=no,toolbar=no,resizable=no');
	mywindow.moveTo(screen.width/2-400,screen.height/2-300);
}
</script>


<td valign="top" class="rightColumn">
<p class="subHeader">Analisis Mata Pelajaran Ikut Kelas</p>
<form action="ctk_analisis-mpkelassr.php?ting=<?php echo $m;?>" method="POST" target="_blank">

<?php

//echo "<a href=../index.php> Kembali</a><br>";
echo "<h5><center>$namasek<br>ANALISA MATA PELAJARAN KELAS<br>".jpep("".$_SESSION['jpep']."")." TAHUN ".$_SESSION['tahun']."</center></h5>";
echo "<b>GURU KELAS : $namagu<br>KELAS : $ting $kelas </b><br><br>";
echo "<table width=\"98%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">";
echo "<tr bgcolor=\"#FFCC99\">";
echo "    <td rowspan=\"2\"><div align=\"center\">Bil </div></td>\n";
echo "    <td rowspan=\"2\"><div align=\"center\">Mata Pelajaran </div></td>\n";
echo "    <td colspan=\"3\"><div align=\"center\">Bil Calon </div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">A</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">B</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">C</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">D</div></td>\n";
echo "    <td colspan=\"2\"><div align=\"center\">E</div></td>\n";
if($tahun==2015){
	if($ting!='D6')
		echo "    <td colspan=\"2\"><div align=\"center\">F</div></td>\n";
}
echo "	<td colspan=\"2\"><div align=\"center\">Menguasai</div></td>\n";
echo "	<td colspan=\"2\"><div align=\"center\">Tidak <br>Menguasai</div></td>\n";
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
	if($ting!='D6'){
		echo "    <td><div align=\"center\">Bil</div></td>\n";
		echo "    <td><div align=\"center\">%</div></td>\n";
	}		
}
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">%</div></td>\n";
echo "  </tr>\n";
$bil=1;
$q_mp = "SELECT * FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.darjah= :ting AND amp.kelas= :kelas AND amp.kodmp=mp.kod ORDER BY mp.susun";
$qry = oci_parse($conn_sispa,$q_mp);
oci_bind_by_name($qry, ':ting', $ting);
oci_bind_by_name($qry, ':kelas', $kelas);
oci_execute($qry);
$parameter=array(":ting",":kelas");
$value=array($ting,$kelas);
$bilmp = kira_bil_rekod($q_mp,$parameter,$value);

if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($qry))
	{
		if($tahun<=2014){
			$lulus = $rowmp[A]+$rowmp[B]+$rowmp[C] ;
			$gagal = $rowmp[D]+$rowmp[E] ;
		}elseif($tahun==2015){
			if($ting=='D6'){
				$lulus = $rowmp[A]+$rowmp[B]+$rowmp[C] ;
				$gagal = $rowmp[D]+$rowmp[E] ;	
			}else{//D1-D5
				$lulus = $rowmp[A]+$rowmp[B]+$rowmp[C]+$rowmp[D]+$rowmp[E] ;
				$gagal = $rowmp[F];	
			}
		}else{//> 2016
			$lulus = $rowmp[A]+$rowmp[B]+$rowmp[C]+$rowmp[D] ;
			$gagal = $rowmp[E] ;
		}
		//$bil++;
		if($bil&1) {
			$bcol = "#CDCDCD";
		} else {
			$bcol = "";
		}
		echo "  <tr bgcolor='$bcol'>\n";
		echo "    <td>".$bil."</td>\n";
		echo "    <td>$rowmp[MP]</td>\n";
		echo "    <td><center>$rowmp[BCALON]</center></td>\n";
		echo "    <td><center>$rowmp[AMBIL]</center></td>\n";
		echo "    <td><center>$rowmp[TH]</center></td>\n";
		echo "    <td><center>$rowmp[A]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[A], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[B]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[B], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[C]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[C], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[D]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[D], $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$rowmp[E]</center></td>\n";
		echo "    <td><center>".peratus($rowmp[E], $rowmp[AMBIL])."</center></td>\n";
		if($tahun==2015){
			if($ting!='D6'){
				echo "    <td><center>$rowmp[F]</center></td>\n";
				echo "    <td><center>".peratus($rowmp[F], $rowmp[AMBIL])."</center></td>\n";
			}		
		}
		echo "    <td><center>$lulus</center></td>\n";
		echo "    <td><center>".peratus($lulus, $rowmp[AMBIL])."</center></td>\n";
		echo "    <td><center>$gagal</center></td>\n";
		echo "    <td><center>".peratus($gagal, $rowmp[AMBIL])."</center></td>\n";
		if($tahun<=2014){
			echo "<td><center>".gpmpmrsr($rowmp[A], $rowmp[B], $rowmp[C], $rowmp[D], $rowmp[E], $rowmp[AMBIL])."</center></td>\n";	
		}elseif($tahun==2015){
			if($ting=='D6'){
				echo "<td><center>".gpmpmrsr($rowmp[A], $rowmp[B], $rowmp[C], $rowmp[D], $rowmp[E], $rowmp[AMBIL])."</center></td>\n";	
			}else{//D1-D5
				echo "<td><center>".gpmpmrsr_baru($rowmp[A], $rowmp[B], $rowmp[C], $rowmp[D], $rowmp[E], $rowmp[F], $rowmp[AMBIL])."</center></td>\n";	
			}			
		}else{//2016
			echo "<td><center>".gpmpmrsr($rowmp[A], $rowmp[B], $rowmp[C], $rowmp[D], $rowmp[E], $rowmp[AMBIL])."</center></td>\n";		
		}
		$bil++;
		echo "  </tr>\n";
	}
}
else {
		echo "<tr>";
		echo "<br>";
		echo "<td colspan = \"20\"><center>MARKAH PEPERIKSAAN BELUM DIPROSES OLEH S/U<center></td>\n";
		echo "<br>";
		echo "</tr>";
	 }

echo "</table>\n";
?>

<br><br>
&nbsp;&nbsp;<input type="submit" name="submit" value="CETAK">
<input type="button" name="export" value="EXPORT KE EXCEL" onclick="open_window('data-analisis-mpkelassr-excel.php?ting=<?php echo $m;?>','win1');" />
</form>


<?php include 'kaki.php';
?>