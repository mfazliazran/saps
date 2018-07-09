<?php 
include 'config.php';
include 'fungsi.php';

$b=$_GET['data'];
list ($jenpep, $tahuntov, $tingtov, $tingpep, $capai)=split('[/]', $b);

$h_tentu_hc=oci_parse($conn_sispa,"DELETE FROM tentu_hc WHERE jenpep='$jenpep' AND tahuntov='$tahuntov' AND tingtov='$tingtov' AND tingpep='$tingpep' AND capai='$capai'");
//die("DELETE FROM tentu_hc WHERE jenpep='$jenpep' AND tahuntov='$tahuntov' AND tingtov='$tingtov' AND tingpep='$tingpep' AND capai='$capai'");
oci_execute($h_tentu_hc);

message("Data telah dihapuskan",1);
location("penentu_hc.php?data=$capai");
?>