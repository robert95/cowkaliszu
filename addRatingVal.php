<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$conn = sqlConnect();
	
	if(!isset($_COOKIE['stmh'])){
		$userID = -1;
	}else{
		$user = getUser($_COOKIE['stmh'], $conn);
		$userID = $user['id'];
	}
	
	addRatingVal($_GET['idR'], $_GET['val'], $_GET['com'], $userID);

	sqlClose($conn);
?>