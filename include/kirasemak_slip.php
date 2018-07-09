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

	$jenisujian = $jpep;

	$sqlhitslip = "SELECT SLIP_ID FROM HIT_SEMAK_SLIP WHERE SLIP_TARIKH = '$timestamp' AND SLIP_JENISSEK = '$jenissek' AND SLIP_JPEP = '$jenisujian'";
	$reshitslip = oci_parse($conn_sispa, $sqlhitslip);
	oci_execute($reshitslip);
	$numhitslip = count_row($sqlhitslip, $conn_sispa);
	$dathitslip = oci_fetch_array($reshitslip);

	if($numhitslip == 0){
		$chkid = oci_parse($conn_sispa, "SELECT MAX(SLIP_ID) AS HID FROM HIT_SEMAK_SLIP");
		oci_execute($chkid);
		$datid = oci_fetch_array($chkid);
		$hid = (int) $datid['HID'];
		$hid++;

		if($jenisujian != ""){
			$inshitslip = "INSERT INTO HIT_SEMAK_SLIP (SLIP_ID, SLIP_TARIKH, SLIP_JENISSEK, SLIP_JPEP, SLIP_BIL) VALUES ('$hid', '$timestamp', '$jenissek', '$jenisujian', '1')";
			$resinsslip = oci_parse($conn_sispa, $inshitslip);
			oci_execute($resinsslip);
		}
	}
	else{
		$hid = $dathitslip['SLIP_ID'];
		$gethcnt = oci_parse($conn_sispa, "SELECT SLIP_BIL FROM HIT_SEMAK_SLIP WHERE SLIP_ID = '$hid'");
		oci_execute($gethcnt);
		$dathcnt = oci_fetch_array($gethcnt);
		$hcnt = (int) $dathcnt["SLIP_BIL"];
		$hcnt++;

		$updhitslip = oci_parse($conn_sispa, "UPDATE HIT_SEMAK_SLIP SET SLIP_BIL = '$hcnt' WHERE SLIP_ID = '$hid'");
		oci_execute($updhitslip);
	}
?>