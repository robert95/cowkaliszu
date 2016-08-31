<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(isset($_COOKIE['stmh']))
	{
		$conn = sqlConnect();
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		$per = getPermission($mh, $conn);
		sqlClose($conn);
		if($per == 0 ) header("LOCATION: login.php");
	}
	else{
		header("LOCATION: login.php");
	}
	
	
	$con = sqlConnect();
		
	deletePriorytets($con);
	
	sqlClose($con);
?>