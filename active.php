<?php
	include_once 'mysql.php';
	include_once 'function.php';
	$conn = sqlConnect();
		
	$h = $_GET['h'];
	activeProfil($conn, $h);
	sqlClose($conn);
	header("LOCATION: setting.php");
?>