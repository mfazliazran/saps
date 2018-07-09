<?php
session_start();
if(!isset($_SESSION['kod']) || (trim($_SESSION['kod'])=='')) {
	header("location: logout.php");
	exit();
	}
?>