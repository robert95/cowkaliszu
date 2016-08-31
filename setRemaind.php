<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(isset($_COOKIE['stmh']))
	{
		$conn = sqlConnect();
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		
		$id_event = $_GET['idevent'];
		$h = $_GET['h'];
		$type = $_GET['type'];
		setRemaind($mh, $h, $id_event, $type, $conn);
		
		sqlClose($conn);
	}
	else echo "0";
?>