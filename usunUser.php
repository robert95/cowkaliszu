<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(!isset($_COOKIE['stmh']))
	{
		die();
	}
	
	$con = sqlConnect();
		
	$id = $_GET['id'];
	deleteUser($id, $con);
	
	sqlClose($con);
?>