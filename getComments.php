<?php
	include_once 'mysql.php';
	include_once 'function.php';

	if(isset($_GET['type'])){
		if($_GET['type'] == 2){
			echo getCommentForPlace($_GET['id']);
		}else{
			echo getCommentForEvent($_GET['id']);
		}
	}else{
		echo getCommentForEvent($_GET['id']);
	}
		
?>