<?php

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
	
	$conn = sqlConnect();

	$likedEvent = getLikedEvents($conn);
	
	
	sqlClose($conn);
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Panel użytkownika-wkaliszu.pl</title>
		<link rel="stylesheet" type="text/css" href="style/style_panel.css">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
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
			<section id="panel_wydarzenia">
				<?php echo $likedEvent; ?>
				
				<div style="clear: both"></div>
			</section>
			<footer>
				
			</footer>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript" src="js/scripts_eve.js"></script>
		<script type="text/javascript" src="js/skrypt_nav_panel.js"></script>
	</body>
</html>