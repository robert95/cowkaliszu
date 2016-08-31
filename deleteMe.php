<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(isset($_COOKIE['stmh']))
	{
		$conn = sqlConnect();
		deleteMe($conn);
		sqlClose($conn);
	}
	
	header("LOCATION: logout.php");
?>