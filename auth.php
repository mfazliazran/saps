<?php
	//Start session
	//CA16111204
	//session_start();
	if(!isset($_SESSION)){session_start();}
	
	//Check whether the session variable
	//SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID'])=='')) {
		header("location: access-denied.php");
		exit();
		}
?>