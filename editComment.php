<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$conn = sqlConnect();
	
	if(!isset($_COOKIE['stmh'])){
	}else{
		$user = getUser($_COOKIE['stmh'], $conn);
		if($user['uprawnienia'] > 0){
			editCommentWithAuthor($_GET['id'], addslashes($_GET['content']), addslashes($_GET['author']));
		}
	}
	
	sqlClose($conn);
?>