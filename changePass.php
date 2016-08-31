<?php
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	
	$kom = "";
	if(isset($_POST["submit"])){
		$conn = sqlConnect();

		$re_haslo = $_POST['repass'];
		$haslo = $_POST['pass'];
		$mh = addslashes($_POST['mh']);
		$haslo = addslashes($haslo);
		$re_haslo = addslashes($re_haslo);
		
		if($haslo != $re_haslo){
			$dalej = 0;
			$kom = "Hasła się nie zgadzają!";
		}else{
			if(strlen($haslo) < 6){
				$dalej = 0;
				$kom = "Hasło za krótkie, minimum 6 znaków!";
			}
		}
		
		if($dalej != 0){
			$haslo = md5($haslo."@#!%!XXA@");
			updatePass($mh, $haslo);
		}
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
			<p>Zmiana hasła <span>  </span> <a href="../" ><img src="img/p_logo.png" alt="wkaliszu.pl"/></a></p>
			<form action="" method="post">
				Nowe hasło:<br>
				<input type="password" name="pass"/><br>
				Powtórz:<br>
				<input type="password" name="repass"/><br>
				<input type="hidden" name="mh" value="<?php echo $_GET['mh'];?>"/>
				<div><input class="btn" type="submit" name="submit" value="Zmień hasło"/></div>
			</form>
			<p class="comm_login"> <?php echo $kom; ?> </p>
		</div>
	</body>
</html>