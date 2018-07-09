<?php

	// date_default_timezone_set ("Asia/Kuala_Lumpur");
	$orasysdate = oci_parse($conn_sispa, "SELECT sysdate AS ODATE FROM DUAL");
	oci_execute($orasysdate);
	$datsysdate = oci_fetch_array($orasysdate);
	$timestamp = $datsysdate["ODATE"];

	// Get task
	$task = $_REQUEST['ting'];
	
	// Set jenis sekolah
	if(substr($task, 0, 1) == 'D')
		$jenissek = 'R';
	else
		$jenissek = 'M';

	$sqlhitsemak = "SELECT MARK_ID FROM HIT_SEMAK_MARK WHERE MARK_TARIKH = '$timestamp' AND MARK_JENISSEK = '$jenissek'";
	$reshitsemak = oci_parse($conn_sispa, $sqlhitsemak);
	oci_execute($reshitsemak);
	$numhitsemak = count_row($sqlhitsemak, $conn_sispa);
	$dathitsemak = oci_fetch_array($reshitsemak);

	if($numhitsemak == 0){
		$chkid = oci_parse($conn_sispa, "SELECT MAX(MARK_ID) AS HID FROM HIT_SEMAK_MARK");
		oci_execute($chkid);
		$datid = oci_fetch_array($chkid);
		$hid = (int) $datid['HID'];
		$hid++;

		$inshitsemak = "INSERT INTO HIT_SEMAK_MARK (MARK_ID, MARK_TARIKH, MARK_JENISSEK, MARK_BIL) VALUES ('$hid', '$timestamp', '$jenissek', '1')";
		$resinssemak = oci_parse($conn_sispa, $inshitsemak);
		oci_execute($resinssemak);
	}
	else{
		$hid = $dathitsemak['MARK_ID'];
		$gethcnt = oci_parse($conn_sispa, "SELECT MARK_BIL FROM HIT_SEMAK_MARK WHERE MARK_ID = '$hid'");
		oci_execute($gethcnt);
		$dathcnt = oci_fetch_array($gethcnt);
		$hcnt = (int) $dathcnt["MARK_BIL"];
		$hcnt++;

		$updhitsemak = oci_parse($conn_sispa, "UPDATE HIT_SEMAK_MARK SET MARK_BIL = '$hcnt' WHERE MARK_ID = '$hid'");
		oci_execute($updhitsemak);
	}
?>