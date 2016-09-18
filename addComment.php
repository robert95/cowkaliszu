<?php
	include_once 'mysql.php';
	include_once 'function.php';
	$data = (isset($_GET['data']) && $_GET['data'] != "") ? $_GET['data'] : date("Y-m-d");
	
	$conn = sqlConnect();
	
	if(!isset($_COOKIE['stmh'])){
		$userID = -1;
	}else{
		$user = getUser($_COOKIE['stmh'], $conn);
		$userID = $user['id'];
	}
	
	addComment(addslashes($_GET['type']), $userID, addslashes($_GET['id_item']), addslashes($_GET['content']));
	
	sqlClose($conn);
?>