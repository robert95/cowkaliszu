<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(!isset($_COOKIE['stmh']))
	{
		echo 'img/add_to_fav.png';
	}else{
		$conn = sqlConnect();
		$id = $_GET['id'];
		if(isLiked($id, $conn) == 0){
			echo 'img/add_to_fav.png';
		}else{
			echo 'img/del_to_fav.png';
		}
		sqlClose($conn);
	}
	
	
?>