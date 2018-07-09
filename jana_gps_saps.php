<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';
set_time_limit(0);
?>
<td valign="top" class="rightColumn">
<p class="subHeader">JANA GPS SAPS</p>
<?php
if ($_POST["post"]=="1"){
  $tahun=$_POST["txtTahun"];
  echo "Proses jana bermula..<br>";
  
  $resgpsppd=oci_parse($conn_sispa,"delete from gps_ppd_dashboard where TAHUN='$tahun' and JPEP in ('PPT-D6','PPT-T5','UPSRC','SPMC')");
  oci_execute($resgpsppd);
  $resgpsjpn=oci_parse($conn_sispa,"delete from gps_jpn_dashboard where TAHUN='$tahun' and JPEP in ('PPT-D6','PPT-T5','UPSRC','SPMC')");
  oci_execute($resgpsjpn);


//MULA JPN

  //MULA PROSES GPS SEKOLAH RENDAH (SR)
  $resnilai=oci_parse($conn_sispa,"select KODNEGERI,JPEP,sum(AMBIL) as BIL_AMBIL,sum(TH) as BIL_TH,sum(A) as BIL_A,sum(B) as BIL_B,sum(C) as BIL_C,sum(D) as BIL_D,sum(E) as BIL_E 
from analisis_mpsr INNER JOIN
tkppd on analisis_mpsr.kodppd=tkppd.kodppd
where TAHUN='$tahun' and JPEP in ('PPT','UPSRC') and DARJAH='D6' 
AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) group by JPEP,KODNEGERI");

  oci_execute($resnilai);
  $tarikh_rekod=date("d/m/Y");
  while($datanilai=oci_fetch_array($resnilai)){
    $kodjpn=$datanilai["KODNEGERI"];
    $jpep=$datanilai["JPEP"];
    $amb=$datanilai["BIL_AMBIL"];
    $bil_th=$datanilai["BIL_TH"];
    $bilA=$datanilai["BIL_A"];
    $bilB=$datanilai["BIL_B"];
    $bilC=$datanilai["BIL_C"];
    $bilD=$datanilai["BIL_D"];
    $bilE=$datanilai["BIL_E"];
	
	$gps=gpmpmrsr($bilA, $bilB, $bilC, $bilD, $bilE, $amb);
	
	if ($jpep=="PPT")
	  $jpep1="PPT-D6";
	else
      $jpep1=$jpep;
	//echo "$jpep1<br>";
	$ssql1="insert into gps_jpn_dashboard(KODJPN,JPEP,TAHUN,GPS,TARIKH_REKOD,KODJENIS_SEKOLAH) 
	                       values('$kodjpn','$jpep1','$tahun','$gps',to_date('$tarikh_rekod','DD/MM/YYYY'),'R')";
	$stmt=oci_parse($conn_sispa,$ssql1);
	oci_execute($stmt);					   
    //echo "$ssql1<br>";
  }  
  //TAMAT PROSES GPS SEKOLAH RENDAH (SR)
  
  //MULA PROSES GPS MENENGAH ATAS (MA)

					$sqltnilai = "select KODNEGERI,JPEP,sum(AP) as AP, SUM(A) AS A, SUM(AM) AS AM, SUM(BP) AS BP, SUM(B) as B, SUM(CP) AS CP,SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(G) AS G, SUM(AMBIL) AS AMBIL 
					FROM analisis_mpma   INNER JOIN
                   tkppd on analisis_mpma.kodppd=tkppd.kodppd
                   WHERE TAHUN='$tahun' AND JPEP in ('PPT','SPMC') and TING='T5' AND KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL) 
				   GROUP BY JPEP,KODNEGERI";
				$restnilai = oci_parse($conn_sispa,$sqltnilai);
					oci_execute($restnilai);
				while($datatnilai = oci_fetch_array($restnilai)){
					$kodjpn= $datatnilai["KODNEGERI"];
					$jpep = $datatnilai["JPEP"];
					$bilAP = (int) $datatnilai["AP"];
					$bilA = (int) $datatnilai["A"];
					$bilAM = (int) $datatnilai["AM"];
					$bilBP = (int) $datatnilai["BP"];
					$bilB = (int) $datatnilai["B"];
					$bilCP = (int) $datatnilai["CP"];
					$bilC = (int) $datatnilai["C"];
					$bilD = (int) $datatnilai["D"];
					$bilE = (int) $datatnilai["E"];
					$bilG = (int) $datatnilai["G"];
					$bilamb = (int) $datatnilai["AMBIL"];
						
				$gps = gpmpma($bilAP, $bilA, $bilAM, $bilBP, $bilB, $bilCP, $bilC, $bilD, $bilE, $bilG, $bilamb);
	
	if ($jpep=="PPT")
	  $jpep1="PPT-T5";
	else
      $jpep1=$jpep;	
	  
	$ssql="insert into gps_jpn_dashboard(KODJPN,JPEP,TAHUN,GPS,TARIKH_REKOD,KODJENIS_SEKOLAH) 
	                       values('$kodjpn','$jpep1','$tahun','$gps',to_date('$tarikh_rekod','DD/MM/YYYY'),'MA')";
						   
	//echo "<br>**$ssql<br>";
	$stmt=oci_parse($conn_sispa,$ssql);
	oci_execute($stmt);					   

  }
  //TAMAT PROSES GPS MENENGAH ATAS (MA)
//TAMAT JPN
//die("tamat jpn");
//MULA PPD

  //MULA PROSES GPS SEKOLAH RENDAH (SR)
  $resnilai=oci_parse($conn_sispa,"select KODPPD,JPEP,sum(AMBIL) as BIL_AMBIL,sum(TH) as BIL_TH,sum(A) as BIL_A,sum(B) as BIL_B,sum(C) as BIL_C,sum(D) as BIL_D,sum(E) as BIL_E 
      from analisis_mpsr where TAHUN='$tahun' and JPEP in ('PPT','UPSRC') and DARJAH='D6' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) group by JPEP,KODPPD");
  oci_execute($resnilai);
  $tarikh_rekod=date("d/m/Y");
  while($datanilai=oci_fetch_array($resnilai)){
    $kodppd=$datanilai["KODPPD"];
    $jpep=$datanilai["JPEP"];
    $amb=$datanilai["BIL_AMBIL"];
    $bil_th=$datanilai["BIL_TH"];
    $bilA=$datanilai["BIL_A"];
    $bilB=$datanilai["BIL_B"];
    $bilC=$datanilai["BIL_C"];
    $bilD=$datanilai["BIL_D"];
    $bilE=$datanilai["BIL_E"];
	
	$gps=gpmpmrsr($bilA, $bilB, $bilC, $bilD, $bilE, $amb);
	
	if ($jpep=="PPT")
	  $jpep1="PPT-D6";
	else
      $jpep1=$jpep;
	//echo "$jpep1<br>";
	$stmt=oci_parse($conn_sispa,"insert into gps_ppd_dashboard(KODPPD,JPEP,TAHUN,GPS,TARIKH_REKOD,KODJENIS_SEKOLAH) 
	                       values('$kodppd','$jpep1','$tahun','$gps',to_date('$tarikh_rekod','DD/MM/YYYY'),'R')");
	oci_execute($stmt);					   

  }  
  //TAMAT PROSES GPS SEKOLAH RENDAH (SR)
  
  //MULA PROSES GPS MENENGAH ATAS (MA)
					$sqltnilai = "select KODPPD,JPEP,sum(AP) as AP, SUM(A) AS A, SUM(AM) AS AM, SUM(BP) AS BP, SUM(B) as B, SUM(CP) AS CP,SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(G) AS G, SUM(AMBIL) AS AMBIL FROM analisis_mpma WHERE TAHUN='$tahun' AND JPEP in ('PPT','SPMC') and TING='T5' AND KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL) GROUP BY JPEP,KODPPD";
						//echo $sqltnilai."<br>";
				$restnilai = oci_parse($conn_sispa,$sqltnilai);
					oci_execute($restnilai);
				while($datatnilai = oci_fetch_array($restnilai)){
					$kodppd = $datatnilai["KODPPD"];
					$jpep = $datatnilai["JPEP"];
					$bilAP = (int) $datatnilai["AP"];
					$bilA = (int) $datatnilai["A"];
					$bilAM = (int) $datatnilai["AM"];
					$bilBP = (int) $datatnilai["BP"];
					$bilB = (int) $datatnilai["B"];
					$bilCP = (int) $datatnilai["CP"];
					$bilC = (int) $datatnilai["C"];
					$bilD = (int) $datatnilai["D"];
					$bilE = (int) $datatnilai["E"];
					$bilG = (int) $datatnilai["G"];
					$bilamb = (int) $datatnilai["AMBIL"];
						
				$gps = gpmpma($bilAP, $bilA, $bilAM, $bilBP, $bilB, $bilCP, $bilC, $bilD, $bilE, $bilG, $bilamb);
	
	if ($jpep=="PPT")
	  $jpep1="PPT-T5";
	else
      $jpep1=$jpep;	
	
	$ssql="insert into gps_ppd_dashboard(KODPPD,JPEP,TAHUN,GPS,TARIKH_REKOD,KODJENIS_SEKOLAH) 
	                       values('$kodppd','$jpep1','$tahun','$gps',to_date('$tarikh_rekod','DD/MM/YYYY'),'MA')";
						   
	//echo "$ssql<br>";
	$stmt=oci_parse($conn_sispa,$ssql);
	oci_execute($stmt);					   

  }
  //TAMAT PROSES GPS MENENGAH ATAS (MA)
//TAMAT PPD


 //MULA SEKOLAH
  $resgpsppd=oci_parse($conn_sispa,"delete from gps_sekolah_dashboard where TAHUN='$tahun' and JPEP in ('PPT-D6','PPT-T5','UPSRC','SPMC')");
  oci_execute($resgpsppd);

  //MULA PROSES GPS SEKOLAH RENDAH (SR)
  //echo "select KODSEK,JPEP,sum(AMBIL) as BIL_AMBIL,sum(TH) as BIL_TH,sum(A) as BIL_A,sum(B) as BIL_B,sum(C) as BIL_C,sum(D) as BIL_D,sum(E) as BIL_E from analisis_mpsr where TAHUN='$tahun' and JPEP in ('PPT','UPSRC') and DARJAH='D6' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) group by JPEP,KODSEK<br>";
	  
  $resnilai=oci_parse($conn_sispa,"select KODSEK,JPEP,sum(AMBIL) as BIL_AMBIL,sum(TH) as BIL_TH,sum(A) as BIL_A,sum(B) as BIL_B,sum(C) as BIL_C,sum(D) as BIL_D,sum(E) as BIL_E 
      from analisis_mpsr where TAHUN='$tahun' and JPEP in ('PPT','UPSRC') and DARJAH='D6' AND KODMP NOT IN (SELECT KOD FROM SUB_SR_XAMBIL) group by JPEP,KODSEK");
  oci_execute($resnilai);
  $tarikh_rekod=date("d/m/Y");
  while($datanilai=oci_fetch_array($resnilai)){
    $kodsekolah=$datanilai["KODSEK"];
    $jpep=$datanilai["JPEP"];
    $amb=$datanilai["BIL_AMBIL"];
    $bil_th=$datanilai["BIL_TH"];
    $bilA=$datanilai["BIL_A"];
    $bilB=$datanilai["BIL_B"];
    $bilC=$datanilai["BIL_C"];
    $bilD=$datanilai["BIL_D"];
    $bilE=$datanilai["BIL_E"];
	
	/** DALAM DASHBOARD PPPM */
	/*mysql_select_db("my_data_emisportal");
	$resppd=sql_query("select KodNegeriJPN,KodPPD from tssekolah where kodsekolah='$kodsekolah'",$dbi);
	$datappd=mysql_fetch_array($resppd);
	$kodjpn=$datappd["KodNegeriJPN"];
	$kodppd=$datappd["KodPPD"];*/
	
	$resppd=oci_parse($conn_sispa,"select KodNegeriJPN,KodPPD from tsekolah where kodsek='$kodsekolah'");
	oci_execute($resppd);
	$datappd=oci_fetch_array($resppd);
	$kodjpn=$datappd["KODNEGERIJPN"];
	$kodppd=$datappd["KODPPD"];
	$gps=gpmpmrsr($bilA, $bilB, $bilC, $bilD, $bilE, $amb);

	//die("select KodNegeriJPN,KodPPD from tsekolah where kodsek='$kodsekolah' kodjpn:$kodjpn kodppd:$kodppd gps:$gps");
	
	if ($jpep=="PPT")
	  $jpep1="PPT-D6";
	else
      $jpep1=$jpep;
     
	 
	 //echo "insert into gps_sekolah_dashboard(KODJPN,KODPPD,KODSEKOLAH,JPEP,TAHUN,GPS,TARIKH_REKOD,KODJENIS_SEKOLAH) 
	 //                      values('$kodjpn','$kodppd','$kodsekolah','$jpep1','$tahun','$gps',to_date('$tarikh_rekod','DD/MM/YYYY'),'R')<br>";

	 $stmt=oci_parse($conn_sispa,"insert into gps_sekolah_dashboard(KODJPN,KODPPD,KODSEKOLAH,JPEP,TAHUN,GPS,TARIKH_REKOD,KODJENIS_SEKOLAH) 
	                       values('$kodjpn','$kodppd','$kodsekolah','$jpep1','$tahun','$gps',to_date('$tarikh_rekod','DD/MM/YYYY'),'R')");
	oci_execute($stmt);					   

  }  
  //TAMAT PROSES GPS SEKOLAH RENDAH (SR)

  //MULA PROSES GPS MENENGAH ATAS (MA)
					$sqltnilai = "select KODSEK,JPEP,sum(AP) as AP, SUM(A) AS A, SUM(AM) AS AM, SUM(BP) AS BP, SUM(B) as B, SUM(CP) AS CP,SUM(C) AS C, SUM(D) AS D, SUM(E) AS E, SUM(G) AS G, SUM(AMBIL) AS AMBIL FROM analisis_mpma WHERE TAHUN='$tahun' AND JPEP in ('PPT','SPMC') and TING='T5' AND KODMP NOT IN (SELECT KOD FROM SUB_MA_XAMBIL) GROUP BY JPEP,KODSEK";
						//echo $sqltnilai."<br>";
					$restnilai = oci_parse($conn_sispa,$sqltnilai);
					oci_execute($restnilai);
					while($datatnilai = oci_fetch_array($restnilai)){
					$kodsekolah = $datatnilai["KODSEK"];
					$jpep = $datatnilai["JPEP"];
					$bilAP = (int) $datatnilai["AP"];
					$bilA = (int) $datatnilai["A"];
					$bilAM = (int) $datatnilai["AM"];
					$bilBP = (int) $datatnilai["BP"];
					$bilB = (int) $datatnilai["B"];
					$bilCP = (int) $datatnilai["CP"];
					$bilC = (int) $datatnilai["C"];
					$bilD = (int) $datatnilai["D"];
					$bilE = (int) $datatnilai["E"];
					$bilG = (int) $datatnilai["G"];
					$bilamb = (int) $datatnilai["AMBIL"];
				$gps = gpmpma($bilAP, $bilA, $bilAM, $bilBP, $bilB, $bilCP, $bilC, $bilD, $bilE, $bilG, $bilamb);
	
	/** DALAM DASHBOARD PPPM */
	/*mysql_select_db("my_data_emisportal");
	$resppd=sql_query("select KodNegeriJPN,KodPPD from tssekolah where kodsekolah='$kodsekolah'",$dbi);
	$datappd=mysql_fetch_array($resppd);
	$kodjpn=$datappd["KodNegeriJPN"];
	$kodppd=$datappd["KodPPD"];*/
	
	$resppd=oci_parse($conn_sispa,"select KodNegeriJPN,KodPPD from tsekolah where kodsek='$kodsekolah'");
	oci_execute($resppd);
	$datappd=oci_fetch_array($resppd);
	$kodjpn=$datappd["KODNEGERIJPN"];
	$kodppd=$datappd["KODPPD"];

	
	if ($jpep=="PPT")
	  $jpep1="PPT-T5";
	else
      $jpep1=$jpep;	
	
	$stmt=oci_parse($conn_sispa,"insert into gps_sekolah_dashboard(KODJPN,KODPPD,KODSEKOLAH,JPEP,TAHUN,GPS,TARIKH_REKOD,KODJENIS_SEKOLAH) 
	                       values('$kodjpn','$kodppd','$kodsekolah','$jpep1','$tahun','$gps',to_date('$tarikh_rekod','DD/MM/YYYY'),'MA')");
	oci_execute($stmt);					   

  }  
  //TAMAT PROSES GPS MENENGAH ATAS (MA)
//TAMAT SEKOLAH

  echo "Proses data GPS SAPS selesai...<br>";
} else {
?>
<form name="frm1" method="post" action="">
<input type="text" name="txtTahun" size="4" value="<?php echo date("Y");?>">
<input type="submit" name="submit" value="Mulakan">
<input type="hidden" name="post" value="1">
</form>
<?php
}
 
function gpmpmrsr($bilA, $bilB, $bilC, $bilD, $bilE, $amb)
{//echo "$bilA, $bilB, $bilC, $bilD, $bilE, $amb <br>";
	if ($amb >= 1)
	{
		$gpmp = number_format((($bilA*1) + ($bilB*2) + ($bilC*3) + ($bilD*4) + ($bilE*5))/$amb,2,'.',',');
	}
	else {
			$gpmp = 0.00 ;
		 }
	return $gpmp ;
}

function gpmpma($bilAp, $bilA, $bilAm, $bilBp, $bilB, $bilCp, $bilC, $bilD, $bilE, $bilG, $amb)
{
	
	if ($amb >= 1)
	{
		$gpmp = number_format((($bilAp*0) + ($bilA*1) + ($bilAm*2) + ($bilBp*3) + ($bilB*4) + ($bilCp*5) 
								+ ($bilC*6) + ($bilD*7) + ($bilE*8) + ($bilG*9))/$amb,2,'.',',');
	}
	else {
			$gpmp = 0.00 ;
		 }
	return $gpmp ;
}
?>
</td>
<?php include 'kaki.php';?> 
