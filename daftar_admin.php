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

$nokp = validate($_POST['nokp']);
$nama = validate($_POST['nama']);
$jan = validate($_POST['jan']);
$negeri = validate($_POST['negeri']);
$daerah = validate($_POST['daerah']);
$kodsek = validate($_POST['kodsek']);
$user = validate($_POST['user']);
$password2 = validate($_POST['password']);
$password = encrypt($password2, "vtech%5%52018");
$cpassword = validate($_POST['cpassword']);
$tahun = validate($_POST['tahun']);
$level = validate($_POST['level']);
$keycode = validate($_POST['keycode']);

if($password2!=$cpassword){
message("Kata laluan dan Sahkan Kata Laluan tidak sama!",1);
location("b-daftar-sup.php");
}

$querynama = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek= :kodsek");
oci_bind_by_name($querynama, ':kodsek', $kodsek);
oci_execute($querynama);
//$bilsek = count_row("SELECT * FROM tsekolah WHERE kodsek='$kodsek'");
$bilsek=0;
while ($row = oci_fetch_array($querynama)){
	$qnamasek =  mysql_escape_string($row["NAMASEK"]);
	$qstatus = $row["STATUS"];
	$bilsek=1;
}

if($bilsek == 1){
	$biluser=0;
	$sqluser = "SELECT * FROM login WHERE user1=:user1";
	$paruser=array(":user1");
	$valuser=array($user);
	$biluser = kira_bil_rekod($sqluser,$paruser,$valuser);
	
	if($biluser == 1) {
		 message("Maaf!!! ID $user telah wujud, Sila pilih ID yang lain",1);
		 location("b-daftar-sup1.php");
		 } else {
			$biladm=0;
			$sqladm = "SELECT * FROM login WHERE kodsek=:kodsek AND (level1='3' OR level1='4')";
			$paradm=array(":kodsek");
			$valadm=array($kodsek);
			$biladm = kira_bil_rekod($sqladm,$paradm,$valadm); 
			if($biladm == 1) {
				message("Admin sekolah anda telah wujud.",1);
				location("b-daftar-sup1.php");
			} else {
				//$qkeycode = oci_parse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='$kodsek' AND keycode='$keycode'");
				//oci_execute($qkeycode);
				$bilsek=0;
				$sqlsek = "SELECT * FROM tsekolah WHERE kodsek=:kodsek AND keycode=:keycode";
				$parsek=array(":kodsek",":keycode");
				$valsek=array($kodsek,$keycode);
				$bilsek = kira_bil_rekod($sqlsek,$parsek,$valsek);
				if($num = $bilsek !=1)
				{
					message("Maaf!!! Kata Kunci Salah",1);
					location("b-daftar-sup1.php");
				}
				else {
					//Check for duplicate login ID
					$qry_su = "SELECT *  FROM login WHERE user1='$user'";
					if (count_row($qry_su)>0){
							 message("Username telah wujud..sila tukar yang lain",1);
							 location("b-daftar-sup1.php");
					}
					$q_nokp = oci_parse($conn_sispa,"SELECT nokp FROM login WHERE nokp='$nokp' AND kodsek='$kodsek'");
					oci_execute($q_nokp);
					$numnokp = count_row("SELECT nokp FROM login WHERE nokp='$nokp' AND kodsek='$kodsek'");
					if($numnokp==1){
						$level1=3;
						$mysql=oci_parse($conn_sispa,"UPDATE login SET level1= :level1, pswd= :password, user1= :user1, statussek= :qstatus WHERE nokp= :nokp");
						oci_bind_by_name($mysql, ':level1', $level1);
						oci_bind_by_name($mysql, ':password', $password);
						oci_bind_by_name($mysql, ':user1', $user);
						oci_bind_by_name($mysql, ':qstatus', $qstatus);
						oci_bind_by_name($mysql, ':nokp', $nokp);
						oci_execute($mysql);
					
						header("location: register-success.php");
					} else {
						$query=oci_parse($conn_sispa,"INSERT INTO login (tahun, nokp, nama, jan, user1, level1, pswd, negeri, daerah, namasek, kodsek, statussek) VALUES ('$tahun','$nokp','$nama','$jan','$user','$level','$password','$negeri','$daerah','".$qnamasek."','$kodsek','$qstatus')");
						oci_execute($query);
						
						header("location: register-success.php");
					}
				}
			}
		}
	}
else{
header("location: xbase.php");
exit();
}
?>