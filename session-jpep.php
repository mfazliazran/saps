<?php 
require('auth.php');
include 'config.php';
$m=$_GET['datajpep']; 

//CA16111204
//session_start();
if(!isset($_SESSION)){session_start();}

//$n=$_GET['datajpep']; 
list ($tahun, $jpep, $status_pep, $level)=split('[/]', $m);
//list ($tahun, $jpep, $status_pep, $level)=split('[/]', $n);
$tahun_server=date('Y');
$_SESSION['status_buka_tutup'] = $status_pep;
		  /*$sql="SELECT TAHUN,JPEP,STATUS FROM kawal_pep WHERE tahun='$tahun' and jpep='$jpep' ORDER BY rank";
		  $stmt=OCIParse($conn_sispa,$sql);
		  OCIExecute($stmt);
		  while(OCIFetch($stmt)){
			  	$status = OCIResult($stmt,"STATUS");
				$_SESSION['status_buka_tutup'] = $status;
				session_write_close();
		  }*/
//if(($tahun==$tahun_server) OR (($level==1) OR ($level==2))){

//if ($status_pep==1){
	//if(($tahun==$tahun_server)) { 		
		$_SESSION['tahun'] = $tahun; // store session data
		$_SESSION['jpep'] = $jpep;
		
		$resstart=oci_parse($conn_sispa,"select STARTUP from role where id='".$_SESSION["level"]."'");
		oci_execute($resstart);
		$datastart=oci_fetch_array($resstart);
		$startup=$datastart["STARTUP"];
		header("Location: $startup");
		
/*		if ($_SESSION['level']=="3" or $_SESSION['level']=="4" or $_SESSION['level']=="P"){
			//header('Location: index.php');
			header('Location: sah-markah.php');
		}else if ($_SESSION['level']=="12") // sbt
			header('Location: senarai_seksbt.php');
		else if ($_SESSION['level']=="11") // bpi
			header('Location: senarai_sekbpi.php');
		else if ($_SESSION['level']=="13") // skk - kluster kecemerlang
			header('Location: senarai_skk.php');
		else if ($_SESSION['level']=="15") // SBP - SEKOLAH BERASRAMA PENUH
			header('Location: senarai_sbp.php');
		else if ($_SESSION['level']=="6") // JPN
			header('Location: senarai_sekjpn.php');		
		else if ($_SESSION['level']=="5") // PPD
			header('Location: senarai_sekppd.php');		
		else if ($_SESSION['level']=="8") // BPTV
			header('Location: senarai_sekbptv.php');		
		else if ($_SESSION['level']=="14"){ // lp - lembaga peperiksaan
			//$_SESSION["kodsek3"]="LP";
			header('Location: senara_seklb.php');
		}
		else{
			//header('Location: index.php');
			header('Location: papar_subjek.php');
		}//header('Location: b_daftar_bil_mp.php');
	*/
	
	//} else {
			//$_SESSION['tahun'] = $tahun; // store session data
			//$_SESSION['jpep'] = $jpep;
			//header('Location: b_daftar_bil_mp.php');
			
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
	//}
	
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