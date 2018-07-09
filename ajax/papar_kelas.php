<?php session_start();
$kodsek=$_SESSION["kodsek"];
include "../config.php";
$ting=$_GET["ting"];
								
echo "<select name='kelas' id='kelas'><option value=''>-PILIH-</option>";

$kelas_sql = OCIParse($conn_sispa,"SELECT DISTINCT kelas FROM tkelassek WHERE tahun ='".$_SESSION['tahun']."' AND kodsek='$kodsek' AND ting='$ting' ORDER BY kelas");
OCIExecute($kelas_sql);
while(OCIFetch($kelas_sql)) { 
if(OCIResult($kelas_sql,"KELAS")==$kelas){
   echo "<option selected value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>"."<BR>";
}
else{
  echo  "<option value='".OCIResult($kelas_sql,"KELAS")."'>".OCIResult($kelas_sql,"KELAS")."</option>";
  }
}
echo "</select>";
?>		
