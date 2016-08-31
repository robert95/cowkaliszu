<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(!isset($_COOKIE['stmh']))
	{
		header("LOCATION: login.php");
	}
	//!SPRAWDZAMY CZY JEST WŁAŚCICIELEM WYDARZENIA!!!!!!
	$con = sqlConnect();
		
	$group = $_GET['id'];
	//if(isOwner($id,$con) == 1) 
		deleteEventGroup($group, $con);
	
	sqlClose($con);
?>