<?php
	$chksession = $_SESSION['usertoken'];

	// date_default_timezone_set ("Asia/Kuala_Lumpur");
	$orasysdate = oci_parse($conn_sispa, "SELECT sysdate AS ODATE, TO_CHAR(sysdate, 'hh24') AS OHOUR FROM DUAL");
	oci_execute($orasysdate);
	$datsysdate = oci_fetch_array($orasysdate);
	$timestamp = $datsysdate["ODATE"];
	$hour = $datsysdate["OHOUR"];

	for($h=0; $h<24; $h++){
		if($hour == sprintf("%02d", $h))
			$field = "HIT_H".$hour;
	}

	if($chksession == ""){
		$sqlhithome = "SELECT HIT_ID FROM HIT_PELAWAT WHERE HIT_JENIS = '1' AND HIT_DATE = '$timestamp'";
		$reshithome = oci_parse($conn_sispa, $sqlhithome);
		oci_execute($reshithome);
		$numhithome = count_row($sqlhithome, $conn_sispa);
		$dathithome = oci_fetch_array($reshithome);

		if($numhithome == 0){
			$sqlmax = "SELECT MAX(HIT_ID) AS HID FROM HIT_PELAWAT";
			$chkid = oci_parse($conn_sispa, $sqlmax);
			$check = oci_execute($chkid);
			if (!$check) {
			        $e = oci_error($chkid);  // For oci_execute errors pass the statement handle
			        print htmlentities($e['message']);
			        print "\n<pre>\n";
			        print htmlentities($e['sqltext']);
			        printf("\n%".($e['offset']+1)."s", "^");
			        print  "\n</pre>\n";
			    }

			$datid = oci_fetch_array($chkid);
			$hid = (int) $datid['HID'];
			$hid = $hid + 1;

			$inshithome = "INSERT INTO HIT_PELAWAT (HIT_ID, HIT_JENIS, HIT_DATE, $field) VALUES ('$hid', '1', '$timestamp', '1')";

		}
		else{
			$hid = $dathithome['HIT_ID'];
			$gethcnt = oci_parse($conn_sispa, "SELECT $field FROM HIT_PELAWAT WHERE HIT_ID = '$hid'");
			oci_execute($gethcnt);
			$dathcnt = oci_fetch_array($gethcnt);
			$hcnt = (int) $dathcnt["$field"];
			$hcnt++;

			$inshithome = "UPDATE HIT_PELAWAT SET $field = '$hcnt' WHERE HIT_ID = '$hid'";
		}
		
		$reshithome = oci_parse($conn_sispa, $inshithome);
		$exec = oci_execute($reshithome);
		if (!$exec) {
		        $e = oci_error($reshithome);  // For oci_execute errors pass the statement handle
		        print htmlentities($e['message']);
		        print "\n<pre>\n";
		        print htmlentities($e['sqltext']);
		        printf("\n%".($e['offset']+1)."s", "^");
		        print  "\n</pre>\n";
		    }
	}

	$_SESSION['usertoken'] = session_id();
?>

<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

$day = date("w");
$current_hour = date("G");

if ($day >= 1 && $day < 6 && $current_hour >= 9 && $current_hour <= 17) {

echo "<script src='./include/jquery.ui.core.js'></script>";

}

?>

