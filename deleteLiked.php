<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(!isset($_COOKIE['stmh']))
	{
		header("LOCATION: login.php");
	}
	
	$con = sqlConnect();
		
	$id = $_GET['id'];
	unlike($id, $con);
	
	sqlClose($con);
?>