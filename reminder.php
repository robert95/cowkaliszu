<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$conn = sqlConnect();
	executeRemaind($conn);	
	sqlClose($conn);
?>