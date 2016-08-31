<?php
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	cleanEventSession();
	if(isset($_COOKIE['stmh']))
	{
		$conn = sqlConnect();
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		sqlClose($conn);
	}
	else{
		header("LOCATION: login.php");
	}
	$kom = "";
	$dalej = 1;
	if(isset($_POST["submit"])){
		//edycja uzytkownika
		$conn = sqlConnect();
		$eduser['id'] = $user['id'];
		$eduser['mail'] = "";
		$eduser['surname'] = "";
		$eduser['phone'] = $_POST["phone"];
		$eduser['mail_hash'] = "";
		if($_POST["old_pass"] != "")
		{
			$eduser['old_pass'] = $_POST["old_pass"];
			$eduser['old_pass_h'] = md5($eduser['old_pass']."@#!%!XXA@");
			if(checkLogin($user['mail'], $eduser['old_pass_h'], $conn) != "lipa"){
				if($_POST["new_pass"] != $_POST["re_new_pass"]){
					$dalej = 0;
					$kom = "Hasła nie zgadzają się!";
				}else{
					$eduser['new_pass'] = $_POST["new_pass"];
					$eduser['new_pass_h'] = md5($eduser['new_pass']."@#!%!XXA@");
				}				
			}
			else{
				$dalej = 0;
				$kom = "Błędne hasło!";
			}
		}
		if($dalej != 0) {
			editUser($conn, $eduser);
			header("LOCATION: back_set.php");
		}		
		//var_dump($eduser);
		sqlClose($conn);
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
		<div id="top">
			<nav id="menu">
				<?php 
					$conn = sqlConnect();
					$per = getPermission($mh, $conn);
					sqlClose($conn);
					if($per == 1 || $per == 2) adminMenu();
					else normalMenu();
				?>
			</nav>
			<section id="panel_ustawienia">
			<form action="" method="post" onsubmit="return validateALL();">
				<div id="p_settings">
					Login:<br>
					<input class="toValidate" type="text" name="name" disabled value="<?php echo $user['login']; ?>"/><br>
					E-mail:<br>
					<input class="toValidate" type="text" name="e-mail" disabled value="<?php echo $user['mail']; ?>"/><br>
					Nr telefonu:<br>
					<input class="toValidate" type="text" name="phone" value="<?php echo $user['tel']; ?>"/><br>
					<span class="activChangePass">zmień hasło</span><br><br>
					<div id="change_password">
					Stare hasło:<br>
					<input type="password" name="old_pass"/><br>
					Nowe hasło:<br>
					<input type="password" name="new_pass"/><br>
					Powtórz nowe hasło:<br>
					<input type="password" name="re_new_pass"/><br>
					</div>
					<input class="btn" type="submit" name="submit" value="Zapisz"/><br>
					<?php echo $kom; ?>					
				</div>
				</form>
				<div id="p_stats">
					Rejestracja: <?php echo $user['data']; ?><br>
					Ilość logowań: <?php echo $user['ilLog']; ?><br>
					Ilość dodanych miejsc: <?php echo $user['ilMie']; ?><br>
					Ilość dodanych wydarzeń: <?php echo $user['ilWyd']; ?><br>
					<!--Aktywność: 
					<img src="img/p_star_activ.png" alt="poziom aktywnośći"/>
					<img src="img/p_star_activ.png" alt="poziom aktywnośći"/>
					<img src="img/p_star_activ.png" alt="poziom aktywnośći"/>
					<img src="img/p_star_empty.png" alt="poziom aktywnośći"/>
					<img src="img/p_star_empty.png" alt="poziom aktywnośći"/>-->
				</div>
				<div class="delete_acount">
					<a>Usuń konto</a>
				</div>
			</section>
			<footer>
				
			</footer>
			<div id="confirm_delete">
				<p>Czy na pewno chcesz usunąć swoje konto?</p>
				<img src="img/confirm_yes.png" class="yes_acount" alt="TAK"/>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<img src="img/confirm_no.png" class="no" alt="NIE"/>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript" src="js/skrypt_nav_panel.js"></script>
		<script type="text/javascript" src="js/skrypt_settings.js"></script>
		<script type='text/javascript' src='js/scripts_eve.js'></script>
		<script type='text/javascript' src='js/skrypt_validator.js'></script>
	</body>
</html>