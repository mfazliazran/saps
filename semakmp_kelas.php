<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Semak Key-In Markah Kelas</p>
<?php

	echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";

	echo "<br>";

	echo "<center><h3>SENARAI MATA PELAJARAN $gting $gkelas</h3></center>";

	echo "<br><br>";

	echo "  <tr>\n";

	echo "    <th scope=\"col\"><center>BIL</center></th>\n";

	echo "    <th scope=\"col\">MATA PELAJARAN</th>\n";

	echo "    <th scope=\"col\">NAMA GURU</th>\n";
	echo "    <th scope=\"col\">BIL<br>AMBIL</th>\n";
	echo "    <th scope=\"col\">BIL<br>MARKAH</th>\n";

	echo "    <th scope=\"col\">STATUS<br>MARKAH</th>\n";

	echo "  </tr>\n";

	$kodsek=$_SESSION['kodsek'];

	$query = "SELECT TING,KELAS FROM tkelassek WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$gting' AND kelas='$gkelas' ORDER BY ting"; 

	$result = OCIParse($conn_sispa,$query);
	OCIExecute($result);

	$bil=0;

	while (OCIFetch($result))

	{

	
		$bil=$bil+1;

		$ting = OCIResult($result,"TING");//$row["TING"];

		$kelas = OCIResult($result,"KELAS");//$row['kelas'];

		$querymp = "SELECT NAMA,KODMP,BILAMMP FROM sub_guru WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND ting='$gting' AND kelas='$gkelas' ORDER BY ting"; 

		$resultmp = OCIParse($conn_sispa,$querymp);
		OCIExecute($resultmp);

		$num = count_row($querymp);



		echo "  <tr>\n";


				if ($_SESSION['statussek']=="SM"){

					$qb_murid = "SELECT * FROM tmurid WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$gting' AND kelas$gting='$gkelas' and kodsek_semasa='$kodsek'";
					$qb = OCIParse($conn_sispa,$qb_murid);
					OCIExecute($qb);

					$bilm = count_row($qb_murid);

					}

				if ($_SESSION['statussek']=="SR"){

					$qb_murid = "SELECT * FROM tmuridsr WHERE kodsek$gting='$kodsek' AND tahun$gting='".$_SESSION['tahun']."' AND $gting='$gting' AND kelas$gting='$gkelas' and kodsek_semasa='$kodsek'";
					$qb = OCIParse($conn_sispa,$qb_murid);
					OCIExecute($qb);

					$bilm = count_row($qb_murid);

					}


		if($num != 0){

				
			$bilmp=0;

			while (OCIFetch($resultmp))

	

			{

	

				$kodmp=OCIResult($resultmp,"KODMP");//$row2['kodmp'];
				$nama=OCIResult($resultmp,"NAMA");//$row2['kodmp'];
				$bilammp=OCIResult($resultmp,"BILAMMP");//$row2['kodmp'];

				

				if ($_SESSION['statussek']=="SM"){

					$qrymp = "SELECT KOD, MP FROM mpsmkc WHERE kod='$kodmp'";

					$markah = "markah_pelajar";
					$tmurid = "tmurid";
					$tahap = "ting";

				}

				if ($_SESSION['statussek']=="SR"){

					$qrymp = "SELECT KOD, MP FROM mpsr WHERE kod='$kodmp'"; 

					$markah = "markah_pelajarsr";
					$tmurid = "tmuridsr";
					$tahap = "darjah";

				}

				

				$r_mp = OCIParse($conn_sispa,$qrymp);
				OCIExecute($r_mp);

	

				OCIFetch($r_mp);
				
				$bilmp=$bilmp+1;

				echo "	<td><center>$bilmp</center></td>\n";

				echo  "<td>" .OCIResult($r_mp,"KOD")."-".OCIResult($r_mp,"MP")."</td><td>$nama</td>";
				echo  "<td><center>$bilammp</center></td>";

				

				//$q_mark = "SELECT * FROM $markah WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND $tahap='$gting' AND kelas='$gkelas' AND $kodmp is not null and nokp in (select nokp from $tmurid where kodsek$ting='$kodsek' and tahun$ting='".$_SESSION['jpep']."' and $ting='$ting' and kelas$ting='$kelas' and kodsek_semasa='$kodsek')";
				//$q = OCIParse($conn_sispa,$q_mark);
				//OCIExecute($q);

				$bil_mark = count_row("SELECT * FROM $markah WHERE kodsek='$kodsek' AND tahun='".$_SESSION['tahun']."' AND jpep='".$_SESSION['jpep']."' AND $tahap='$gting' AND kelas='$gkelas' AND $kodmp is not null and nokp in (select nokp from $tmurid where kodsek$gting='$kodsek' and tahun$gting='".$_SESSION['tahun']."' and $gting='$gting' and kelas$gting='$kelas' and kodsek_semasa='$kodsek')");

				//if($bil_mark == 0){
				if($bil_mark <> $bilammp){
					//echo($bil_mark);

					echo  "<td><center>$bil_mark</center></td><td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></td>";

					}

					else{

					echo  "<td><center>$bil_mark</center></td><td><center><img src=\"images/ok.png\" width=\"20\" height=\"20\"></center></td>";

					}

	

				if ($num<>$bilrow)

	

					echo  "<tr></th></th>\n";

	

				$bilrow=$bilrow+1;

	

			}

	

		}

	

		else{

	

				echo  "<td>&nbsp<td>&nbsp<br>";

				echo  "<td><center>0</center></td><td><center><img src=\"images/ko.png\" width=\"20\" height=\"20\"></center></td>";

		}

		
		echo  "</tr>";

	}

	echo "</table>";

	echo "<br><br>";

	echo "</th>\n";

	echo "</tr>\n";

	echo "</table></body>\n";

?>
</td>
<?php include 'kaki.php';?>  