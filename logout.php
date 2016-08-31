<?php
	if(!isset($_COOKIE['stmh']))
	{
		header("LOCATION: login.php");
	}
	
	unset($_COOKIE['stmh']);
	setcookie('stmh', '', time() - 3600);
	header("LOCATION: index.php");
?>