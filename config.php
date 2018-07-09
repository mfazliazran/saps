<?php
error_reporting(E_ALL  & ~E_WARNING & ~E_STRICT & ~E_DEPRECATED);

//semak time
date_default_timezone_set("Asia/Kuala_Lumpur");

/*$pukul12 = "00:00:01";
$pukul5 = "05:00:00";
$h =  strtotime($pukul12);
$h2 = strtotime($pukul5);
$strto = strtotime(date("H:i:s"));
echo "12 - $h<br>5 - $h2<br>$strto<br>";
//die(date("H:i:s"));
if(($strto > $h) and ($strto < $h2)){
echo "hahaha ".date("H:i:s");
die('redirect ke page maintenance');
}*/

$database = "(DESCRIPTION =
                        (ADDRESS =
                                                (PROTOCOL = TCP)
                                                (HOST = nprod-scan.moe.gov.my)
                                                (PORT = 1521)
                        )
                        (CONNECT_DATA = (SERVICE_NAME=KPMSTG))
                        )";


if ($conn_sispa=oci_connect("sapd","sapddev123",$database)){
  //echo("Successfully connected to Oracle\n");
  //OCILogoff($conn_sispa);
  $success=1;
  //echo "DEDICATED<br>";
 }
 else {
   //$err=oci_error();
    $err = OCIError();
    $e=split(":",$err["message"]);
    if ($e[0]=="ORA-01017")
       $errmsg="<br><font color='#FF0000'><strong>Id Pengguna dan Katalaluan tidak sah !<strong></font><br><br>";
    else
      $errmsg=$e[0]."<br><font color='#FF0000'><strong>Harap maaf! Pangkalan data SAPS sedang diselenggara - Sila cuba lagi<strong></font><br><br>";
   echo "$errmsg";
   //echo "<center>Harap Maaf !!<br><br>
   //   Gangguan Dalam Laluan Ke Database<br><br>Pihak Kami Memohon Ribuan Kemaafan Di Atas Kesulitan Yang Dialami.<br><br>Terima Kasih !!</center>";
   //die();
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
  $namabulan[9]="OCT";
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
 
 
 function count_row($sql)
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
  $stmt=oci_parse($conn_sispa,$newsql);
  oci_execute($stmt);
  if (OCIFetch($stmt)){
    $bilrekod=OCIResult($stmt,"BILREKOD");
  }
  return($bilrekod);  
}

function oci_escape_string($str)
{
	if (get_magic_quotes_gpc()) {
		$strtmp = stripslashes($str);
	}
	else
		$strtmp=$str;
	
return str_replace("'", "''", $strtmp);
}

 function keterangan($tbl,$ktrgn,$kod,$v)
 {
	 global $conn_sispa;
	 
  $rekod[]=NULL;
  $sql2="select " . $ktrgn . " from " . $tbl ." where " . $kod . "='" . $v . "'";

  $res=oci_parse($conn_sispa,$sql2);
  oci_execute($res);
  $rekod=oci_fetch_array($res);		
  return $rekod[0];
 
 }
 
 function pageredirect($url){
	echo "<script type=\"text/javascript\">location.href='$url'</script>";	
}

 function download_apdm(){
   $tarikh_semasa = date("Y-m-d");

   global $conn_sispa;

   $sql = "SELECT * FROM tskawal_apdm WHERE tarikh_mula<='$tarikh_semasa' and tarikh_akhir>='$tarikh_semasa' and item='1'";
   $res = oci_parse($conn_sispa,$sql);
   oci_execute($res);
   $data = oci_fetch_array($res);
   if($data){
     return 1;
   } else {
     return 0;
   }

 }

?>
