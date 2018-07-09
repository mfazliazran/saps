<?php 
$stmt=OCIParse($conn_sispa,"SELECT * FROM umum ORDER BY id DESC");
OCIExecute($stmt);
while(OCIFetch($stmt)){
	$id=OCIResult($stmt,"ID");
	$tarikh=OCIResult($stmt,"TARIKH");
	$umum=OCIResult($stmt,"UMUM");
	echo "$umum<br>Tarikh : $tarikh<br><br>";
}
?>