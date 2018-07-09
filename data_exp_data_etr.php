<?php
include 'auth.php';
include 'config.php';
include "input_validation.php";

set_time_limit(0);

	$tahun = validate($_POST['tahun']);
	$ting = validate($_POST['ting']);
	$ting2 = validate($_POST['ting']);
	$status = validate($_POST['status']);
	$gredmark = validate($_POST['gredmark']);

		
    set_time_limit(0);
    session_start();
	
	if($gredmark=='1'){
		$grdm = "KOD";//markah sahaja
		$nam = "mark";
	}else if($gredmark=='2'){
		$grdm = "'G'||KOD";	
		$nam = "gred";
	}else{
		$nam = "all";	
	}
    
	if ($status=="SR"){
		$flag_export[0]="1";
	   	$nama_table[0] = "headcountsr"; 	
	   	$ting[0]="DARJAH";	
	   	$namafail[0] = "headcountsr_".$ting2."_".$nam."_".$tahun.".xls";
	   	$namafailzip[0] = "headcountsr_".$ting2."_".$nam."_".$tahun;
	   	$tingkatan="DARJAH";
	   	$tablesubjek = "MPSR";
	}
	else if ($status=="SM"){
		$flag_export[0]="1";
		$nama_table[0] = "headcount"; 		
		$ting[0]="TING";	
		$namafail[0] = "headcount_".$ting2."_".$nam."_".$tahun.".xls";
		$namafailzip[0] = "headcount_".$ting2."_".$nam."_".$tahun;
		$tingkatan="TING";
		$tablesubjek = "MPSMKC";
	} 

header("Content-type: application/vnd.ms-excel ");
header("Content-Disposition: attachment; filename=$namafail[0]");
echo "<HTML xmlns=\"http://www.w3.org/1999/xhtml\"><HEAD><TITLE>DATA TOV/ETR</TITLE>";
echo "<META http-equiv=Content-Type content=\"text/html; charset=utf-8\">";
echo "<body>";


 for ($i=0;$i<1;$i++){ 
     echo "<table>";
	 $location_file=$path."export/".$_SESSION["kodsek"]."/".$namafail[$i];
	 //echo $location_file;

 //for ($i=0;$i<=1;$i++){
   if ($flag_export[$i]=="1"){
	  
	  if($gredmark==""){
		 $result = oci_parse($conn_sispa,"SELECT COLUMN_NAME,DATA_TYPE FROM ALL_TAB_COLS WHERE TABLE_NAME='".strtoupper($nama_table[$i])."' AND COLUMN_NAME IN ('NOKP','NAMA','KODSEK','TING','KELAS','TAHUN','HMP','TOV','GTOV','ETR','GETR') ORDER BY COLUMN_ID ");
	  }elseif($gredmark=="1"){
		 $result = oci_parse($conn_sispa,"SELECT COLUMN_NAME,DATA_TYPE FROM ALL_TAB_COLS WHERE TABLE_NAME='".strtoupper($nama_table[$i])."' AND COLUMN_NAME IN ('NOKP','NAMA','KODSEK','TING','KELAS','TAHUN','HMP','TOV','ETR') ORDER BY COLUMN_ID ");		  
	  }else{
		 $result = oci_parse($conn_sispa,"SELECT COLUMN_NAME,DATA_TYPE FROM ALL_TAB_COLS WHERE TABLE_NAME='".strtoupper($nama_table[$i])."' AND COLUMN_NAME IN ('NOKP','NAMA','KODSEK','TING','KELAS','TAHUN','HMP','GTOV','GETR') ORDER BY COLUMN_ID ");		  
	  }


      $col_list="";
	  oci_execute($result);
	  $cnt = 0;
	  $csv_output="<tr>";
	  while ($data = oci_fetch_array($result)) {
		$val="<td>".$data["COLUMN_NAME"]."</td>";
		if($cnt>0)
			$col_list.=",";
		$col_list.=$nama_table[$i].".".$data["COLUMN_NAME"];
		$type[$cnt]=$data["DATA_TYPE"];

		$csv_output .= $val;

		$cnt++;
	 } //while
	 $csv_output .= "</tr>";
	 echo $csv_output;
     $role=$_SESSION["level"];
	 
		 $sql="SELECT $col_list FROM ".$nama_table[$i].", TSEKOLAH where ".$nama_table[$i].".KODSEK=TSEKOLAH.KODSEK ";
           if ($role=="6") //JPN,
              $sql.=" AND TSEKOLAH.KODNEGERIJPN='".$_SESSION["kodsek"]."'";
           else if ($role=="5" ) //PPD
              $sql.=" AND TSEKOLAH.KODPPD='".$_SESSION["kodsek"]."'";
           else if ($role=="4" or $role=="3" or $role=="2" or $role=="1" or $role=="P") //SEKOLAH
              $sql.=" and TSEKOLAH.KODSEK='".$_SESSION["kodsek"]."'";
            else if ($role=="7") //pusat
			 $sql.="";
			else
			  $sql="ERROR"; 
			  
		 $sql.=" AND ".$nama_table[$i].".TAHUN=:tahun ";
		 $sql.=" AND ".$nama_table[$i].".$tingkatan=:ting";
		 
		 $res = oci_parse($conn_sispa,$sql);
		 oci_bind_by_name($res,":tahun",$tahun);
		 oci_bind_by_name($res,":ting",$ting2);
		 
		 oci_execute($res);
		 while ($data = oci_fetch_array($res)) {
			$csv_output="<tr>";
			for ($j=0;$j<$cnt;$j++) {
				 $val="<td>&nbsp;".$data[$j]."</td>";
		 
				 $csv_output .= $val;
			} //for(j==...
		  $csv_output .= "</tr>";
		  echo $csv_output;
		 } //while
		 
 } //if ($flag_export[$i]..  
 echo "</table>";
} //for

