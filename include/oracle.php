<?php
 if ($conn_smm=OCILogon("smmsapd","5mm54PdM0E","//10.251.27.28/moedb2")){
 // echo("Successfully connected to Oracle\n");
  //OCILogoff($conn_sispa);
  $success=1;
  //echo $success;
 }
 else {
   $err=oci_error();
   echo "<center>Harap Maaf !!<br><br>
      Gangguan Dalam Laluan Ke Database SMM<br><br>Pihak Kami Memohon Ribuan Kemaafan Di Atas Kesulitan Yang Dialami.<br><br>Terima Kasih !!</center>";
   die();
 }

 //if ($conn_saps=oci_connect("portal_sispa","portal_sispa","xe")){
  if ($conn_sispa=OCILogon("sapd","54PdM0E","//10.251.27.28/moedb2")){
//if ($conn_sispa=oci_connect("portal_sispa","portal_sispa","//testappserver:1521/xe")){
// echo("Successfully connected to Oracle\n");
  //OCILogoff($conn_sispa);
  $success=1;
  //echo $success;
 }
 else {
   $err=oci_error();
   echo "<center>Harap Maaf !!<br><br>
      Gangguan Dalam Laluan Ke Database<br><br>Pihak Kami Memohon Ribuan Kemaafan Di Atas Kesulitan Yang Dialami.<br><br>Terima Kasih !!</center>";
   die();
 }

function paging($totalrecord,$recordpage,$url,$pg)
{
 $maxpage=10;
 $b=($pg % $maxpage);
 $p=($pg-$b)/$maxpage;
 if (!$b)
    $p--;

  $startpage=($p * $maxpage)+1;

 $r=$totalrecord % $recordpage;
 $totalpage=(int) ($totalrecord-$r)/$recordpage;
 if ($r>0)
   $totalpage++;
 $c=0;
 $p=$startpage;
 if ($p>1){
  $prev=$p-1;
  echo "<a href=\"$url&pg=".$prev."\"><<</a>";
 }
 while($p<=$totalpage and $c<$maxpage){
   $c++;
   if ($pg==$p)
     echo "$p&nbsp;";
   else
     echo "<a href=\"$url&pg=$p\">$p&nbsp;</a>";
   $p++;
 } 
 $c++;
 if ($p<$totalpage)
  echo "<a href=\"$url&pg=$p\">>></a>";

}

  function oradate($tkh)
 {
  $namabulan[0]="JAN";
  $namabulan[1]="FEB";
  $namabulan[2]="MAR";
  $namabulan[3]="APR";
  $namabulan[4]="MAY";
  $namabulan[5]="JUN";
  $namabulan[6]="JUL";
  $namabulan[7]="AUG";
  $namabulan[8]="SEP";
  $namabulan[10]="NOV";
  $namabulan[11]="DEC";
 
  $oradt=$tkh;
    if ($oradt<>""){ 
       $mth=(int) substr($oradt,3,2); 
	   if ($mth>0)
	     $mth--;
       $oradt=substr($oradt,0,2)."-".$namabulan[$mth]."-".substr($oradt,8,2);
    } 
  return($oradt);	
 }
 
function jpep($kodpep){
switch ($kodpep){
	case "U1":
	$npep="UJIAN 1";
	break;
	case "U2":
	$npep="UJIAN 2";
	break;
	case "PAT":
	$npep="PEPERIKSAAN AKHIR TAHUN";
	break;
	case "PPT":
	$npep="PEPERIKSAAN PERTENGAHAN TAHUN";
	break;
	case "PMRC":
	$npep="PEPERIKSAAN PERCUBAAN PMR";
	break;
	case "SPMC":
	$npep="PEPERIKSAAN PERCUBAAN SPM";
	break;
    case "UPSRC":
	$npep="PEPERIKSAAN PERCUBAAN UPSR";
	break;
}
return $npep;
} 
 function count_rows($sql)//senarai_umum SAPS
 {
	global $conn_sispa;
 
   $pos=strpos($sql,"FROM");
   if ($pos==0)
     $pos=strpos($sql,"from");
   if ($pos==0)
     $pos=strpos($sql,"From");
//echo "POS $pos :- $sql";
  $newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 
  //echo "newsql:$newsql";
  //echo "conn_sispa : ".$conn_sispa;
  $stmt=oci_parse($conn_sispa,$newsql);
  oci_execute($stmt);
  if (OCIFetch($stmt)){
    $bilrekod=OCIResult($stmt,"BILREKOD");
  }
  return($bilrekod);  
}
 function count_row($sql,$conn)//modul unreached
 {
   $pos=strpos($sql,"FROM");
   if ($pos==0)
     $pos=strpos($sql,"from");
   if ($pos==0)
     $pos=strpos($sql,"From");

  $newsql="select count(*) as BILREKOD ".substr($sql,$pos);	 
  $stmt=OCIParse($conn,$newsql);
  OCIExecute($stmt);
  if (OCIFetch($stmt)){
    $bilrekod=OCIResult($stmt,"BILREKOD");
  }
  return($bilrekod);  
}

function oci_escape_string($str)
{
return str_replace("'", "''", $str);
}

 function keterangan($tbl,$ktrgn,$kod,$v)
 {
	 global $conn_smm;
  $rekod[]=NULL;
  $sql2="select " . $ktrgn . " from " . $tbl ." where " . $kod . "='" . $v . "'";
  $res=oci_parse($conn_smm,$sql2);
  oci_execute($res);
  $rekod=oci_fetch_array($res);		
  return $rekod[0];
 
 }

 
?>