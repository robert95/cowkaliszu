<?php
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(isset($_COOKIE['stmh']))
	{
		echo "2";
		die();
	}
	if(isset($_POST["submit"])){
		$conn = sqlConnect();

		$login = $_POST['login'];
		$haslo = $_POST['pass'];
		$haslo = addslashes($haslo);
		$login = addslashes($login);
		$login = htmlspecialchars($login);
		$haslo = md5($haslo."@#!%!XXA@");
		
		echo zaloguj($login, $haslo, $conn);
		sqlClose($conn);		
	}
?>