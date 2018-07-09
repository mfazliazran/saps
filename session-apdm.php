<?php 
require('auth.php');
$m=$_GET['datajpep']; 
//$n=$_GET['datajpep']; 
list ($tahun, $jpep, $status_pep, $level)=split('[/]', $m);
//list ($tahun, $jpep, $status_pep, $level)=split('[/]', $n);
$tahun_server=date('Y');

//if(($tahun==$tahun_server) OR (($level==1) OR ($level==2))){

//if ($status_pep==1){
	if(($tahun==$tahun_server)) { 		
		$_SESSION['tahun'] = $tahun; // store session data
		$_SESSION['jpep'] = $jpep;
		if ($_SESSION['level']=="3" or $_SESSION['level']=="4")
			header('Location: semak_pelajar_apdm.php');
		else
			header('Location: papar_subjek.php');
		//header('Location: b_daftar_bil_mp.php');
	} else {
			$_SESSION['tahun'] = $tahun; // store session data
			$_SESSION['jpep'] = $jpep;
			header('Location: b_daftar_bil_mp.php');
			
			//DI EDIT UNTUK DI BERI KEBENARAN KEPADA SEMUA USER 19/1/2012
			/*if (($level==3) OR ($level==4)){
				$_SESSION['tahun'] = $tahun; // store session data
				$_SESSION['jpep'] = $jpep;
				//header('Location: index.php');
				header('Location: b_daftar_bil_mp.php');
			} else {
				echo "<br><br><br><br><br><br><br>";
				echo "<table width=\"450\"  border=\"1\" align=\"center\" cellpadding=\"30\" cellspacing=\"0\" bordercolor=\"#0000FF\">\n";
				echo "  <tr>\n";
				echo "    <td bgcolor=\"#FFFF99\"><div align=\"center\" class=\"style1\">Maaf ! <br>Hanya Su saja<br>\n";
				echo "      Ditutup</div>\n";
				echo "      <br>\n";
				echo "      << <a href=\"pilih-jpep.php\">Kembali</a></td>\n";
				echo "  </tr>\n";
				echo "</table>\n";
			}	*/		
	}
	
/*	}else{
		echo "<br><br><br><br><br><br><br>";
		echo "<table width=\"450\"  border=\"1\" align=\"center\" cellpadding=\"30\" cellspacing=\"0\" bordercolor=\"#0000FF\">\n";
		echo "  <tr>\n";
		echo "    <td bgcolor=\"#FFFF99\"><div align=\"center\" class=\"style1\">Maaf ! <br>Kemasukan Data<br>\n";
		echo "      Ditutup</div>\n";
		echo "      <br>\n";
		echo "      << <a href=\"pilih-jpep.php\">Kembali</a></td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
	 	}
*/
?>