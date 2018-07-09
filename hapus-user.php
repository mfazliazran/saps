<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
include 'fungsi2.php';

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

if ($_SESSION["level"]=="5" or $_SESSION["level"]=="3" or $_SESSION["level"]=="4" or $_SESSION["level"]=="6" or $_SESSION["level"]=="7"){

    $nokp=$_GET["nokp"];
	$lvl=trim(semak_user($nokp));
	if ($lvl<>""){
	  if($lvl=="1" or $lvl=="2" or $lvl=="3" or $lvl=="4" or $lvl=="P" or $lvl=="PK"){
		    echo "reset level<br>";
            $lvl="1"; //User sekolah reset level jadi 1		
	  }		

      $encrypted = encrypt("12345678", "vtech%5%52018");
	  $query_sm=" update login set pswd=:encrypted,level1=:lvl,lastchangepassword=to_date('01-JAN-18') where nokp=:nokp ";

	  $result_sm = oci_parse($conn_sispa,$query_sm);
 	  oci_bind_by_name($result_sm, ':encrypted', $encrypted);
 	  oci_bind_by_name($result_sm, ':lvl', $lvl);
 	  oci_bind_by_name($result_sm, ':nokp', $nokp);

	}
  oci_execute($result_sm);
  location("senarai-user.php");
}

  function semak_user($nokp)
  
  {
  global $conn_sispa;
  if($_SESSION["level"]=="7"){
	  $query_sm=" select level1 from login ";
	  $c=" where ";
  }	  
  else {
	  $query_sm=" select level1 from login,tsekolah where login.kodsek=tsekolah.kodsek";
	  $c=" and ";
  }	  
	if ($_SESSION["level"]=="3" or $_SESSION["level"]=="4") //SU PEPERIKSAAN
	  $query_sm.=" and login.KodSek='".$_SESSION["kodsek"]."' ";
	else if ($_SESSION["level"]=="5") //PPD
	  $query_sm.=" and KodPPD='".$_SESSION["kodsek"]."' ";
	else if ($_SESSION["level"]=="6") //jpn
	  $query_sm.=" and KodNegeriJPN='".$_SESSION["kodsek"]."' ";
	  
  
  $query_sm.=" $c nokp='$nokp' ";
  $result_sm = oci_parse($conn_sispa,$query_sm);

  oci_execute($result_sm);
	if($sm = oci_fetch_array($result_sm)){
       $ada=$sm["LEVEL1"];
	}   
	else
       $ada="";	
 return ($ada); 
  }
?>
