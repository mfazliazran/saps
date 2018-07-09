<?php
include "auth.php";
include "config.php";
include "input_validation.php";

$role=$_SESSION["level"];

set_time_limit(0);

	$tahun = validate($_POST['tahun']);
	$ting = validate($_POST['ting']);
	$ting2 = validate($_POST['ting']);
	$jpep = validate($_POST['pep']);
	$status = validate($_POST['status']);

	
		if($tahun==2015){
		    if ($ting=="D1" or $ting=="D2" or $ting=="D3" or $ting=="D4" or $ting=="D6")
			   $level='SR';
		    else if ($ting=="D5")
			   $level='SN';
			else if ($ting=="P" or $ting=="T1" or $ting=="T2" or $ting=="T3")
				$level='MR';
			else if ($ting=="T4" or $ting=="T5")
				$level='MA';
		}	
		else {
		    if ($ting=="D1" or $ting=="D2" or $ting=="D3" or $ting=="D4" or $ting=="D5" or $ting=="D6" )
			   $level='SR';
			else if ($ting=="P" or $ting=="T1" or $ting=="T2" or $ting=="T3")
				$level='MR';
			else if ($ting=="T4" or $ting=="T5")
				$level='MA';
        }

	$sql="select * from gred where TAHAP='$level' order by min desc";
	$res = oci_parse($conn_sispa,$sql);
	oci_execute($res);
	$idx=0;
	while ($datagred = oci_fetch_array($res)) {	
		$arrgred[$idx]=$datagred["GRED"];
		$idx++;
	}
	$arrgred[$idx]="TH";
	
	if ($status=="SR"){
	  $flag_export[0]="1";
	   $nama_table[0] = "markah_pelajarsr"; 	$ting[0]="DARJAH";	
	   $namafail[0] = "markah_pelajar_sr_".$jpep."_".$ting2."_".$nam."_".$tahun.".xls";
	   $namafailzip[0] = "markah_pelajar_sr_".$jpep."_".$ting2."_".$nam."_".$tahun;
	   $tingkatan="DARJAH";
	   $tablesubjek = "MPSR";

	}
	else if ($status=="MR"){
		$flag_export[0]="1";
		$nama_table[0] = "markah_pelajar"; 		
		$ting[0]="TING";	
		$namafail[0] = "markah_pelajar_".$jpep."_".$ting2."_".$nam."_".$tahun.".xls";
		$namafailzip[0] = "markah_pelajar_".$jpep."_".$ting2."_".$nam."_".$tahun;
		$tingkatan="TING";
		$tablesubjek = "MPSMKC";

	}
	else if ($status=="MA"){
		$flag_export[0]="1";
		$nama_table[0] = "markah_pelajar"; 		
		$ting[0]="TING";	
		$namafail[0] = "markah_pelajar_".$jpep."_".$ting2."_".$nam."_".$tahun.".xls";
		$namafailzip[0] = "markah_pelajar_".$jpep."_".$ting2."_".$nam."_".$tahun;
		$tingkatan="TING";
		$tablesubjek = "MPSMKC";

	}

header("Content-type: application/vnd.ms-excel ");
header("Content-Disposition: attachment; filename=$namafail[0]");
echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>STATISTIK LINUS IKUT PPD</TITLE>";
echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
echo "<body>";

	
 for ($i=0;$i<1;$i++){
   echo "<table>";

   $csv_output .= "<tr><td>KODSEKOLAH</td><td>NAMASEKOLAH</td><td>PPD</td><td>JPN</td><td>JENIS SEKOLAH</td><td>LOKASI</td><td>MATAPELAJARAN</td>";
	 for($i=0;$i<count($arrgred);$i++){
		 $csv_output .="<td>".$arrgred[$i]."</td>"; 
	 }	 
	 $csv_output .=  "</tr>";
	 echo $csv_output;

//**** SQL UNTUK PILIH SEKOLAH 	 
	$sql="select distinct a.kodsek,namasek,kodppd,kodnegerijpn,kodjenissekolah,kodlokasisemasa,kod,kod||'-'||mp as subj from tsekolah a,$tablesubjek b,sub_guru c
		  where a.kodsek=c.kodsek and b.kod=c.kodmp and c.tahun=:tahun and ting=:ting ";
   if ($role=="6") //JPN,
	  $sql.=" AND a.KODNEGERIJPN='".$_SESSION["kodsek"]."'";
   else if ($role=="5" ) //PPD
	  $sql.=" AND a.KODPPD='".$_SESSION["kodsek"]."'";
   else if ($role=="4" or $role=="3" or $role=="2" or $role=="1" or $role=="P") //SEKOLAH
	  $sql.=" and a.KODSEK='".$_SESSION["kodsek"]."'";
   else if ($role=="8") //bptv	  
      $sql .= " and (kodjenissekolah='203' or kodjenissekolah='303') ";
   else if ($role=="12")//sbt
    $sql .= " and SBT='Y' ";
   else if ($role=="13")//skk
    $sql .= " and Kluster='Y'";
   else if ($role=="15")//sbp
    $sql .= " and kodjenissekolah='206'";
	  
     $sql.=" order by 1,3";
	 //echo "$sql";
		 $res = oci_parse($conn_sispa,$sql);
		 oci_bind_by_name($res,":tahun",$tahun);
		 oci_bind_by_name($res,":ting",$ting);
		 oci_execute($res);
		 $cnt=0;
		 while ($data = oci_fetch_array($res)) {
			$cnt++; 
			$kodsek=$data["KODSEK"];
			$namasek=$data["NAMASEK"];
			$kodppd=$data["KODPPD"];
			$kodjenissekolah=$data["KODJENISSEKOLAH"];
			
			$resjenis=oci_parse($conn_sispa,"select JENISSEKOLAH from TKJENISSEKOLAH where KODJENISSEKOLAH='".$kodjenissekolah."'");
			oci_execute($resjenis);
			$datajenis=oci_fetch_array($resjenis);
			$jenissekolah=$datajenis["JENISSEKOLAH"];

			$resppd=oci_parse($conn_sispa,"select PPD from TKPPD where KODPPD='".$kodppd."'");
			oci_execute($resppd);
			$datappd=oci_fetch_array($resppd);
			$ppd=$datappd["PPD"];
			
			$kodjpn=$data["KODNEGERIJPN"];
			$resjpn=oci_parse($conn_sispa,"select NEGERI from TKNEGERI where KODNEGERI='".$kodjpn."'");
			oci_execute($resjpn);
			$datajpn=oci_fetch_array($resjpn);
			$jpn=$datajpn["NEGERI"];

			$kodlokasisemasa=$data["KODLOKASISEMASA"];
			if($kodlokasisemasa=="1")
				$lokasisemasa="BANDAR";
			else if($kodlokasisemasa=="2")
				$lokasisemasa="LUAR BANDAR";
			else
				$lokasisemasa="-";
				
			$kodmp=$data["KOD"];
			$matapelajaran=$data["SUBJ"];

		   $csv_output="<tr><td>$kodsek</td><td>$namasek</td><td>$ppd</td><td>$jpn</td><td>$jenissekolah</td><td>$lokasisemasa</td><td>$matapelajaran</td>";
	       $sqlmarkah="select G$kodmp,count(*) as bilmurid from $nama_table[0] where KODSEK='$kodsek' and JPEP='$jpep' and TAHUN='$tahun' and $tingkatan='$ting' group by  G$kodmp";
		   //echo "$cnt. $sqlmarkah<br>"; 
		   $resmarkah = oci_parse($conn_sispa,$sqlmarkah);
		   oci_execute($resmarkah);
		   for($i=0;$i<count($arrgred);$i++)
			   $arrcountgred[$i]=0;
           		   
		   while($datamarkah=oci_fetch_array($resmarkah)){
			   $gred=$datamarkah["G$kodmp"];
			   $bilmurid=$datamarkah["BILMURID"];
			   for($i=0;$i<count($arrgred);$i++){
				   if($gred==$arrgred[$i])
				     $arrcountgred[$i]=$bilmurid;
			   }   
		   }	
		  for($i=0;$i<count($arrgred);$i++){
		    $csv_output .= "<td>".$arrcountgred[$i]."</td>";
		  } 
		  $csv_output .= "</tr>";
		  //echo "$csv_output<br>";
		  
		  echo $csv_output;
		 } //while
		 
 echo "</table>";
} //for



