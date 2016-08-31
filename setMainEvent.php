<?php
	include_once 'mysql.php';
	include_once 'function.php';

	if(!isset($_COOKIE['stmh']))
	{
		header("LOCATION: login.php");
	}
	$con = sqlConnect();
	$mh = $_COOKIE['stmh'];
	$id = $_GET['id'];
	$per = getPermission($mh, $con);
	if($per == 1 || $per == 2) {
		setMainEvent($id, $con);
	}	
	sqlClose($con);
?>