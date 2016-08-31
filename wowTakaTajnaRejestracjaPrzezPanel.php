<?php
//var_dump($_POST);
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(isset($_POST["submit"])){
		$conn = sqlConnect();		
		$kom = "";
		$dalej = 1;
		
		if(isNotUniqeLogin($_POST['login'], $conn)){
			$dalej = 0;
			$kom .= "Login zajęty!<br>";
		}
		if(isNotUniqeLogin($_POST['mail'], $conn)){
			$dalej = 0;
			$kom = "Ktoś już zarejestrował się na ten e-mail! Przesłaliśmy na adres email link do resetowania hasła.";
			sendNewPassMail($user['mail']);
		}
		if($_POST["email"] == ""){
			$kom .= "Wpisz e-mail!<br>";
			$dalej = 0;
		}
		if($_POST["login"] == ""){
			$kom .= "Wpisz login!<br>";
			$dalej = 0;
		}
		if($_POST["pass"] != $_POST["re_pass"]){
			$dalej = 0;
			$kom .= "Hasła się nie zgadzają!<br>";
		}else{
			if(strlen($_POST["pass"]) < 6){
				$dalej = 0;
				$kom .= "Hasło za krótkie, minimum 6 znaków!<br>";
			}
		}
		if($_SESSION['captcha'] != $_POST["cap"]){
			$dalej = 0;
			$kom .= "Błędnie przepisany kod!<br>";
		}
		if(!(isset($_POST['accept']))){
			$dalej = 0;
			$kom .= "Zaakceptuj regulamin<br>";
		}
		if($dalej != 0){
			$user['mail'] = addslashes($_POST["email"]);
			$user['login'] = addslashes($_POST["login"]);
			$user['phone'] = addslashes($_POST["phone"]);
			$user['surname'] = "";
			$user['pass'] = addslashes($_POST["pass"]);
			$user['pass_hash'] = md5($user['pass']."@#!%!XXA@");
			$user['mail_hash'] = md5($user['mail']."!!@$%SACZ@!EDA%!%!@ZXC".$user['login']);
			
			addNewUser($conn, $user);
			sendAfterRegisterMail($user, $_POST["pass"]);
			$kom .= "Dziękujemy za rejestrację, na Twój e-mail zostały wysłane Twoje dane!";
		}
		
		sqlClose($conn);	
		echo $kom;
	}
?>