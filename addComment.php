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
		
	$idComment = addComment(addslashes($_GET['type']), $userID, addslashes($_GET['id_item']), addslashes($_GET['content']));
	if(isset($_GET['id_parent']) && $_GET['id_parent'] != -1){
		addParentToComment($idComment, addslashes($_GET['id_parent']));
	}
	if(isset($_GET['author']) && $_GET['author'] != '' && $userID == -1){
		addAuthorToComment($idComment, addslashes($_GET['author']));
	}
	echo $idComment;
	sqlClose($conn);
?>