<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include 'fungsi2.php';
include 'fungsikira.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Mata Pelajaran Guru Yang Diajar <font color="#FFFFFF">(Tarikh Kemaskini Program : 21/3/2012 16:08PM)</font></p>

<?php

$m = $_GET["data"];
list ($tahun, $jpep, $kodsek, $nokp, $statussek, $nama)=split('[|]', $m);
//echo $m;
	switch ($statussek)
	{
		case "SM" : 
		$tmarkah = "markah_pelajar"; 
		$tahap = "ting"; 
		break;
		
		case "SR" : 
		$tmarkah = "markah_pelajarsr";
		 $tahap = "darjah"; 
		break;
    }
		
{		
	$ting = $_GET['ting'];
	
	$q_sek = OCIParse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
	//echo "SELECT * FROM tsekolah WHERE kodsek='$kodsek'";
	OCIExecute($q_sek);
	OCIFetch($q_sek);
	$namasek = OCIResult($q_sek,"NAMASEK");//$row[namasek];
	
}
	
echo "<br><br>";
echo "<center><h3>SENARAI MATA PELAJARAN GURU $tahun<br><font color =\"#ff0000\">".jpep($jpep)."<br> NAMA GURU: $nama </font></h3></center>";


echo "<br>";

echo "<table align=\"center\" width=\"600\" border=\"1\" cellpadding=\"5\"  cellspacing=\"0\">\n";
echo "  <tr bgcolor=\"FFCC33\">\n";
echo "    <th rowspan=\"2\" scope=\"col\">KELAS</th>\n";
echo "    <th rowspan=\"2\" scope=\"col\">MATA PELAJARAN</th>\n";

if($nummark > $bil_ambil)
	echo "    <th rowspan=\"2\" scope=\"col\">&nbsp;&nbsp;</th>\n";
echo "  </tr>\n";
echo "  <tr bgcolor=\"FFCC33\">\n";
echo "  <td><center><b>Bil Pel</b></center></td>\n";
echo "  <td><center><b>Bil M</b></center></td>\n";
echo "  <td><center><b>GPMP Ting</b></center></td>\n";
echo "  <td><center><b>GPMP Kelas</b></center></td>\n";
echo "  </tr>\n";
		
switch ($jpep)
{
	case "UPSRC" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND ting='D6' AND nokp='$nokp' AND tahun='$year' ORDER BY ting";
		break;

	case "PAT" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND ting!='T3' AND ting!='T5' AND tahun='$year' ORDER BY ting";
		break;
		
	case "SPMC" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND ting='T5' AND tahun='$year' ORDER BY kodmp";
		break;
		
	case  "PMRC" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND ting='T3' AND tahun='$year' ORDER BY kodmp";
		break;
		
	default :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='$year' ORDER BY kodmp";
		//echo  "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='$kodsek' AND tahun='$year' ORDER BY kodmp";
		break;
}
//echo $querysub;
$num = count_row($querysub);
if($num == 0)
{
	echo "  <tr>\n";
	echo "  <td colspan=\"7\"><center><font color =\"#ff0000\"><h3><br>Tiada Mata Pelajaran Yang Diajar Pada Tahun Tersebut</h3></font></center></td>\n";
	echo "  </tr>\n";
}
else{
		//echo $querysub;
		$stmt = oci_parse($conn_sispa,$querysub);
		oci_execute($stmt);
		while ($data=oci_fetch_array($stmt))
		{
			$ting = trim($data["TING"]);//$row['ting'];
			$kelas = $data["KELAS"];//$row['kelas'];
			$kodmp = $data["KODMP"];//$row['kodmp'];
			$bil_ambil = $data["BILAMMP"];//$row['bilammp'];
			
			switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$analisis = "analisis_mpsr";
			$mpp = "mpsr";
			$gpmp = 1;
			$gp = 1;
			break;
			
		case "P": case "T1": case "T2": case "T3":
			$analisis = "analisis_mpmr";
			$mpp = "mpsmkc";
			$gpmp = 2;
			$gp = 2;
			break;
			
		case "T4": case "T5":
			$analisis = "analisis_mpma";
			$mpp = "mpsmkc";
			$gpmp = 3;
			$gp = 3;
			break;
	}
			

			$qbilmark = "SELECT * FROM $tmarkah WHERE $tahap='$ting' AND kelas='$kelas' AND tahun='$year' AND kodsek='$kodsek' AND jpep='$jpep' AND $kodmp is not null";
			
			
			//echo "$qbilmark<br>";
			//$stmt2 = oci_parse($conn_sispa,$qbilmark);
			//oci_execute($stmt2);
			$nummark = count_row($qbilmark);

            $mp=subjek_desc($kodmp);
			
			echo "  <tr>\n";
			echo "  <td>".$ting." ".$kelas."</td>\n";
			if($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1"){
			echo "  <td><a href=b_markah.php?data=".$ting."|".$kelas."|".$kodmp."|".$year."|".$kodsek."|".$jpep." title=\"Masuk Markah\">".$mp."</a></td>\n";
			} else {
			echo "  <td>$mp</td>\n";
			}
			
			echo "  <td><center>&nbsp;$bil_ambil</center></td>\n";
			echo "  <td><center>$nummark</center></td>\n";
			if($bil_ambil == $nummark){ $smark = "<center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center>";}
			if($bil_ambil != $nummark){ $smark = "<center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center>";}
			
///////////////////////////////////////////////////////////////////////////////////////////////gpmp ting////////////////////////////////////////////////////////////////////////////////////					
if ($gpmp == '1')	
{//////////////////////////////////////////////////////////////////////////////////sekolah rendah//////////////////////////////////////////////////////////////////////////////////
$q_mp ="SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kodmp='$kodmp' ";

					// echo("$q_mp");
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
					 
$bilmp = count_row("SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kodmp='$kodmp' ");
					 
if ($bilmp != 0)

	while($rowmp = oci_fetch_array($qry))
	{
//echo " <td>$gps</td>\n";
		//echo "    <td><center>haha".gpmpmrsr($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
}
echo "<td>".gpmpmrsr($rowmp[BILA], $rowmp[BILB], $rowmp[BILC], $rowmp[BILD], $rowmp[BILE], $rowmp[AMBIL])."</td>\n";
}

elseif  ($gpmp == '2')//////////////////////////////////////////////////////////////menegah rendah//////////////////////////////////////////////////////////////////////////////
{
$q_mp = "SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kodmp='$kodmp' ";

					 //echo("$q_mp");
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
					 
$bilmp = count_row("SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND amp.ting='$ting' AND amp.kodmp='$kodmp' ");
					 
if ($bilmp != 0)

	while($rowmp = oci_fetch_array($qry))
	{
//echo " <td>$gps</td>\n";
		//echo "    <td><center>haha".gpmpmrsr($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
}
echo " <td>".gpmpmrsr($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["AMBIL"])."</td>\n";
}
	
else
{////////////////////////////////////////////////////////////////////////////////// menengah atas //////////////////////////////////////////////////////////////////////////////////
$q_mp ="SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kodmp='$kodmp' ";

					 //echo("$q_mp");
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
					 
$bilmp = count_row("SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kodmp='$kodmp' ");
					 
if ($bilmp != 0)

	while($rowmp = oci_fetch_array($qry))
	{
//echo " <td>$gps</td>\n";
		//echo "    <td><center>haha".gpmpmrsr($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
}
echo "<td>".gpmpma($rowmp["BILAP"], $rowmp["BILA"], $rowmp["BILAM"], $rowmp["BILBP"], $rowmp["BILB"],$rowmp["BILCP"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["BILG"], $rowmp["AMBIL"])."</td>\n";
} 
//echo "  <td>gpmp ting</td>\n";
///////////////////////////////////////////////////////////////////////////////////////////gpmp kelas/////////////////////////////////////////////////////////////////////////////////////////
if ($gp == '1') 
{/////////////////////////////////////////////////////////////////////////sekolah rendah/////////////////////////////////////////////////////////////////////////
$q_mp = "SELECT * FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp'";
$qry = oci_parse($conn_sispa,$q_mp);
oci_execute($qry);
$bilmp = count_row("SELECT * FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp'");
//echo "$bilmp ";
//echo $q_mp;
if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($qry))
	{
	}
}
echo " <td>".gpmpmrsr($rowmp[A], $rowmp[B], $rowmp[C], $rowmp[D], $rowmp[E], $rowmp[AMBIL])."</td>\n";
}

elseif  ($gp == '2'){///////////////////////////////////////////////////////////////////////// menengah rendah/////////////////////////////////////////////////////////////////////////
$q_mp = "SELECT * FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp' ";
$qry = oci_parse($conn_sispa,$q_mp);
oci_execute($qry);
$bilmp = count_row("SELECT * FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp' ");
//echo "$bilmp ";
//echo $q_mp;
if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($qry))
	{
	}
}
echo "  <td>".gpmpmrsr($rowmp["A"], $rowmp["B"], $rowmp["C"], $rowmp["D"], $rowmp["E"], $rowmp["AMBIL"])."</td>\n";
}
else
{///////////////////////////////////////////////////////////////////////// menengah atas/////////////////////////////////////////////////////////////////////////
$q_mp = "SELECT * FROM analisis_mpma amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp' ";
$qry = oci_parse($conn_sispa,$q_mp);
oci_execute($qry);
$bilmp = count_row("SELECT * FROM analisis_mpma amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='$kodsek' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp' ");
//echo "$bilmp ";
//echo $q_mp;
if ($bilmp != 0)
{
	while($rowmp = oci_fetch_array($qry))
	{
	}
}
echo "  <td>".gpmpma($rowmp["AP"], $rowmp["A"], $rowmp["AM"], $rowmp["BP"], $rowmp["B"],$rowmp["CP"], $rowmp["C"], $rowmp["D"], $rowmp["E"], $rowmp["G"], $rowmp["AMBIL"])."</td>\n";
}
			echo "  </tr>\n";
		}
	}
	
echo "</table>\n";
if($nummark > $bil_ambil) {
	//echo "<center><br><br>|<img src = images/editp.png width=20 height=20 Alt=\"Edit Pelaja\" border=0>|  Klik ikon tersebut untuk menghapuskan markah pelajar yang bermasalah.";
}
echo "</div>"; 
echo "</td>"; 
echo"</table>";

function subjek_desc($kodmp)
{
	global $conn_sispa;

	$statussek = $_SESSION['statussek'];
	switch ($statussek)
	{
		case "SM" : 
			$sqlkod="SELECT mp FROM mpsmkc where KOD='$kodmp'";
			$querykod = oci_parse($conn_sispa,$sqlkod); 
			oci_execute($querykod);
		break;
		case "SR" : 
			$sqlkod="SELECT mp FROM mpsr where KOD='$kodmp'";
			$querykod = oci_parse($conn_sispa,$sqlkod);
			oci_execute($querykod);
		break;
	}
	
	$data=oci_fetch_array($querykod);
	$subjek=$data["MP"];
return($subjek);	
}

?>
</td>
<?php include 'kaki.php';?> 
