<?php
include 'fungsi.php';	
	session_start();

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
   
	include 'config.php'; 
	$user= oci_escape_string($_POST['user']);
	$password= oci_escape_string($_POST['password']);	
	$encrypted = encrypt("$password", "vtech%5%52018");
	$user_verify=$_REQUEST["user_verify"]; //number verification

	
	$qry="SELECT USER1,PSWD,NOKP,LEVEL1,TING,KELAS,KODNEGERI, KODSEK, STATUSLOGIN, LASTCHANGEPASSWORD FROM login WHERE USER1= :user_lama AND PSWD= :pass_lama1";	
    $stmt = OCIParse($conn_sispa, $qry);
	
	oci_bind_by_name($stmt, ':user_lama', $user);
	oci_bind_by_name($stmt, ':pass_lama1', $encrypted);
    OCIExecute($stmt);
	
    if (OCIFetch($stmt)){
		$lastchangepwd = OCIResult($stmt,"LASTCHANGEPASSWORD");
		$pwdlama = OCIResult($stmt, "PSWD");
		$date1 = substr($lastchangepwd,0,10);
		$date2 = date("Y-m-d");
		$diff = abs(strtotime($date2) - strtotime($date1));

		echo "diff:$diff<br>";
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		//check user dah 3 bulan tak tukar pwd
		if($months>3 or ($months==3 and $days>0)){
			if($_SESSION["verification"]==$user_verify){//($lastchangepwd=="01-JAN-18") and (
				$_SESSION['SESS_MEMBER_ID']= OCIResult($stmt, "USER1");
				$_SESSION['SESS_PASSWORD']= OCIResult($stmt, "PSWD");
				$_SESSION['SESS_KODSEK']= OCIResult($stmt, "KODSEK");
				$_SESSION['level']=trim(OCIResult($stmt, "LEVEL1"));
				session_write_close();	   
				header("location: login_kedua.php");
				exit();	
			}else{

				header("location: login-failed.php");
				exit();
			}
		}
		
		if((OCIResult($stmt,"STATUSLOGIN") == "0") and ($lastchangepwd<>"01-JAN-18") and $_SESSION["verification"]==$user_verify){
			//
			$_SESSION['tahun']=date("Y");
			
			$qry1="SELECT USER1,PSWD,NOKP,LEVEL1,TING,KELAS,KODNEGERI, STATUSLOGIN,PENTADBIR FROM login WHERE USER1= :user_lama1 AND PSWD= :pass_lama1";
			//die("$qry1");
			$stmt1 = OCIParse($conn_sispa, $qry1);
			oci_bind_by_name($stmt1, ':user_lama1', $user);
			oci_bind_by_name($stmt1, ':pass_lama1', $encrypted);
			OCIExecute($stmt1);
			 if (OCIFetch($stmt1)){
				$_SESSION['SESS_MEMBER_ID']= OCIResult($stmt1, "USER1");
				$_SESSION['SESS_PASSWORD']= OCIResult($stmt1, "PSWD");
				$_SESSION['nokp']= OCIResult($stmt1, "NOKP");
				$_SESSION['level']=trim(OCIResult($stmt1, "LEVEL1"));
				$_SESSION['ting']=OCIResult($stmt1, "TING");
				$_SESSION['kelas']=OCIResult($stmt1, "KELAS");
				$_SESSION["negeri"]=OCIResult($stmt1, "KODNEGERI");
				$_SESSION["kodnegeri"]=OCIResult($stmt1, "KODNEGERI");
				$_SESSION["pentadbir"]=OCIResult($stmt1, "PENTADBIR");
				$levelpengguna = OCIResult($stmt1, "LEVEL1");
				session_write_close();
				
				if(($_SESSION['level']=="7")  or ($_SESSION['level']=="9") OR ($_SESSION['level']=="10")){
					header("Location: index.php");
					exit();
				} else {
					header("location: pilih-jpep.php");
					exit();
				}
			 } else { 
				header("location: login-failed.php");
				exit();
			 }
		} else {//if((OCIResult($stmt,"STATUSLOGIN") == "0")
			header("location: login-failed.php");
			exit();
		}
	} else {//if (OCIFetch($stmt)){

			header("location: login-failed.php");
			exit();
	}
	
?>