<?php
include 'config.php';
include 'fungsi.php';
include "input_validation.php";
function encrypt($string, $key) {
	$result = '';
	for($i=0; $i<strlen($string); $i++) {
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	}
	return base64_encode($result);
}

//Sanitize the POST values
$nokp = validate($_POST['nokp']);
$user = validate($_POST['user']);
$password = encrypt(validate($_POST['password']), "vtech%5%52018");
$cpassword = validate($_POST['cpassword']);

//////koding baru
$qstatus = "SELECT login.nokp, login.kodsek, tsekolah.status FROM login, tsekolah WHERE login.nokp=:nokp AND login.kodsek=tsekolah.kodsek";
$stmt=OCIParse($conn_sispa,$qstatus);
oci_bind_by_name($stmt, ':nokp', $nokp);
OCIExecute($stmt);
$row = oci_fetch_array($stmt);
$status = OCIResult($stmt,"STATUS");
$kodsek = OCIResult($stmt,"KODSEK");

/*if($password!=$cpassword){
message("Kata laluan dan Sahkan Kata Laluan tidak sama!",1);
location("b-daftar-id-guru.php");
}*/

$querynokp = "SELECT count(*) AS bilnokp FROM login WHERE nokp=:nokp";
$resultnokp = OCIParse($conn_sispa,$querynokp);
oci_bind_by_name($resultnokp, ':nokp', $nokp);
OCIExecute($resultnokp);
if($resultnokp) {
	$result_array2 = oci_fetch_array($resultnokp);
	if($result_array2['BILNOKP'] == 0) {
		 message("Maaf nama anda tiada dalam pangkalan data..sila hubungi s.u peperiksaan anda !",1);
		 location("b-daftar-id-guru.php");
	}
	else {
	//Check for duplicate login ID
	$qry = "SELECT count(*) AS biluser FROM login WHERE user1=:user1";
	$result = OCIParse($conn_sispa,$qry);
	oci_bind_by_name($result, ':user1', $user);
	OCIExecute($result);
	if($result) {
		$result_array = oci_fetch_array($result);
		if($result_array['BILUSER'] > 0) {
			message("Username telah wujud..sila tukar yang lain",1);
			location("b-daftar-id-guru.php");
		}
		else {
			$qrysemak = "SELECT * FROM login WHERE nokp=:nokp";
			$result3 = OCIParse($conn_sispa,$qrysemak);
			oci_bind_by_name($result3, ':nokp', $nokp);
			OCIExecute($result3);
			$result_array2 = oci_fetch_array($result3);
			if ($user<>$result_array2['USER1'] AND $result_array2['USER1']<>"")
			{
				message("Pendaftaran tidak diterima, kerana anda sudah mendaftar!!",1);
				location("b-daftar-id-guru.php");
			}
			else {
				$statuslogin = 0;
				$mysql="UPDATE login SET user1= :user1, pswd= :password, statussek= :status, statuslogin= :status_login WHERE nokp= :nokp";
				$stmt=OCIParse($conn_sispa,$mysql);
				oci_bind_by_name($stmt, ':user1', $user);
				oci_bind_by_name($stmt, ':password', $password);
				oci_bind_by_name($stmt, ':status', $status);
				oci_bind_by_name($stmt, ':status_login', $statuslogin);
				oci_bind_by_name($stmt, ':nokp', $nokp);
				
				OCIExecute($stmt);
				header("location: register-success.php");
			}
		}
	}
}
}
?>