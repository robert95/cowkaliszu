<?php
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	cleanEventSession();
	if(isset($_COOKIE['stmh']))
	{
		header("LOCATION: setting.php");
	}
	$kom = "";
	if(isset($_POST["submit"])){
		$conn = sqlConnect();

		$login = $_POST['login'];
		$haslo = $_POST['pass'];
		$haslo = addslashes($haslo);
		$login = addslashes($login);
		$login = htmlspecialchars($login);
		$haslo = md5($haslo."@#!%!XXA@");
		echo $login;
		$zalogowany = zaloguj($login, $haslo, $conn);
		sqlClose($conn);
		$kom = "";
		if($zalogowany == 1) header("LOCATION: index.php");
		if($zalogowany == -1) $kom = "Twoje konto jeszcze nie zostało aktywowane. Prosimy kliknąc w link podany w mailu. Prosimy sprawdzić czy wiadomość nie trafiła do SPAM.";
		else $kom = "Błędne hasło lub login!";
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Panel użytkownika-wkaliszu.pl</title>
		<link rel="stylesheet" type="text/css" href="style/style_panel.css">
	</head>
	<body>
		<div id="loguj">
			<p>Logowanie <span>  </span> <a href="../" ><img src="img/p_logo.png" alt="wkaliszu.pl"/></a></p>
			<form action="" method="post">
				Login lub e-mail:<br>
				<input type="text" name="login"/><br>
				Hasło:<br>
				<input type="password" name="pass"/><br>
				<div><input class="btn" type="submit" name="submit" value="Zaloguj"/></div>
			</form>
			<p class="comm_login"> <?php echo $kom; ?> </p>
		</div>
		<div id="remind">
			<p id="remPassShow" onclick="remPassShow();">Zapomniałeś hasła? Przypomnij hasło.</p>
			<div id="remaidForm">
				Twój e-mail:<br>
				<input type="text" name="my-mail" id="my-mail"/><br>
				<input type="button" class="btn" value="Przypomnij hasło" onclick="remindPass();"><br>
				<p id="result-remaind">Nowe hasło zostało wysłane na Twój adres e-mail</p>
			</div>
		</div>
		<a class="register" href="zarejestruj.php">Nie masz jeszcze konta? Zarejestruj się</a>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript" src="js/scripts_remPass.js"></script>
	</body>
</html>