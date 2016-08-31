<?php
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	cleanEventSession();
	if(isset($_COOKIE['stmh']))
	{
		header("LOCATION: setting.php");
	}
	$dalej = 1;
	$kom = "";
	$user['mail'] = "";
	$user['login'] = "";
	$user['surname'] = "";
	$user['phone'] = "";
	if(isset($_POST["submit"])){
		$conn = sqlConnect();
		
		$user['mail'] = $_POST["email"];
		$user['login'] = $_POST["login"];
		$user['surname'] = "";
		if(isset($_POST["phone"])) $user['phone'] = $_POST["phone"];
		$user['pass'] = $_POST["pass"];
		$user['pass_hash'] = md5($user['pass']."@#!%!XXA@");
		$user['mail_hash'] = md5($user['mail']."!!@$%SACZ@!EDA%!%!@ZXC".$user['login']);
		
		if(isNotUniqeLogin($user['login'], $conn)){
			$dalej = 0;
			$kom = "Login zajęty!";
		}
		if(isNotUniqeMain($user['mail'], $conn)){
			$dalej = 0;
			$kom = "Ktoś już zarejestrował się na ten e-mail! Przesłaliśmy na adres email link do resetowania hasła.";
			sendNewPassMail($user['mail']);
		}
		if($_POST["pass"] != $_POST["re_pass"]){
			$dalej = 0;
			$kom = "Hasła się nie zgadzają!";
		}else{
			if(strlen($_POST["pass"]) < 6){
				$dalej = 0;
				$kom = "Hasło za krótkie, minimum 6 znaków!";
			}
		}
		if($_SESSION['captcha'] != $_POST["cap"]){
			$dalej = 0;
			$kom = "Błędnie przepisany kod";
		}
		if($dalej != 0){
			addNewUser($conn, $user);
			sendAfterRegisterMail($user, $_POST["pass"]);
			zaloguj($user['login'], $user['pass_hash'], $conn);
			sqlClose($conn);
			header("LOCATION: index.php");
		}		
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Rejestracja użytkownika-wkaliszu.pl</title>
		<link rel="stylesheet" type="text/css" href="style/style_panel.css">
	</head>
	<body>
		<div id="loguj">
			<p>Rejestracja <span>  </span> <a href="../"><img src="img/p_logo.png" alt="wkaliszu.pl"/></a></p>
			<form action="" method="post" onsubmit="return validateALL();">
				Adres e-mail:<br>
				<input class="toValidate" type="text" name="email" value="<?php echo $user['mail'];?>"/><br>
				Login:<br>
				<input class="toValidate" type="text" name="login" value="<?php echo $user['login'];?>"/><br>
				Telefon (opcjonalnie):<br>
				<input type="text" name="phone" value="<?php echo $user['phone'];?>"/><br>
				Hasło:<br>
				<input class="toValidate" type="password" name="pass"/><br>
				Powtórz hasło:<br>				
				<input class="toValidate" type="password" name="re_pass"/><br>
				Przepisz kod:<br>	
				<input class="toValidate" type="text" name="cap" style="width: 50%"/> <img id="cap" src="cap.php" alt="Captach"/><br>
				<label id="accept_reg"><input class="toValidateCheckBox" type="checkbox" name="accept"/> Zgadzam się z <a href="regulamin.php" target="_blank">regulaminem</a> portalu.</label>
				<div><input class="btn" type="submit" name="submit" value="Zarejestruj"/><br><?php echo $kom;?></div>
			</form>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type='text/javascript' src='js/skrypt_validator.js'></script>
	</body>
</html>