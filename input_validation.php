<?php

 function validate($var)
 {
  $var=trim($var);
  $var=strip_tags($var);
  $var=htmlspecialchars($var);
  return $var;
 }

 function kira_bil_rekod($sql,$prm,$val)
 {
 global $conn_sispa;	
 
 $sql2="select count(*) as bilrekod from ($sql)";
   if(count($prm)<>count($val))
	   return 0;
   
   $res=oci_parse($conn_sispa,$sql2);
   for($i=0;$i<count($prm);$i++){
	   //echo "bind $prm[$i] - $val[$i]<br>";
	   oci_bind_by_name($res,$prm[$i],$val[$i]);
   }
   oci_execute($res);
   $data=oci_fetch_array($res);
   $bilrekod=(int) $data["BILREKOD"];
   //echo "bilrekod:$bilrekod<br>";
   return $bilrekod;
 } 

?>
