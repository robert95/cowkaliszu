<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$con = sqlConnect();
		
	$mail = $_GET['mail'];
	remPass($mail, $con);
	
	sqlClose($con);
?>