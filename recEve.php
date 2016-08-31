<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$conn = sqlConnect();
	$mh = $_COOKIE['stmh'];
	$per = getPermission($mh, $conn);
	if($per == 1 || $per == 2){
		$c = $_GET['c'];
		$id = $_GET['id'];
		setRecOfEvent($id, $c, $conn);
	}
	sqlClose($conn);
?>