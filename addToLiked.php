<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(!isset($_COOKIE['stmh']))
	{
		echo "-1";
		die();
	}
	
	$conn = sqlConnect();
		
	$id = $_GET['id'];
	echo addToLiked($id, $conn);
	sqlClose($conn);
?>