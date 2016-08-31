<?php
	include_once 'mysql.php';
	include_once 'function.php';
	$conn = sqlConnect();
	$events = getEventForOneDay($conn);
	cleanEventSession();
	if(isset($_COOKIE['stmh']))
	{//zalogowany
		$zalogowany = 1;
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		//var_dump($user);
	}
	else{//nie zalogowany
		$zalogowany = 0;
	}
	
	sqlClose($conn);
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="Znajdź interesujące Cię wydarzenia w Kaliszu i dodawaj do ulubionych. Sprawdź miejsca spotkań. Wiem co.wkaliszu.pl - rozrywka, film, turystyka, koncerty, muzyka, sztuka, dla dzieci, sport, teatr i inne.">
        <meta name="keywords" content="wydarzenia w Kaliszu, Kalisz, koncerty, filmy, kino, kino Helios, kino Cinema 3D, teatr, teatr im. Bogusławskiego, rozrywka">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Kalisz, wydarzenia i miejsca spotkań - wiem co.wkaliszu.pl</title>
		
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="stylesheet" type="text/css" href="style/style_mainmap.css">
	</head>
	<body>
		<div id="top">
			<nav id="MENU-FIX">
			<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo_fix"/></a>
			<div id="main_menu_fix">
				<ul id="u_menu_fix">
					<li><a href="index.php#event_calendar">KALENDARZ</a></li>
					<li><a href="mapa.php">MAPA</a></li>
				</ul>
			</div>
			<div id="panel_fix">
				<table>
					<tr>
						<td><img src="img/add.png" alt="Dodaj" id="add_img_fix"/></td>
						<td class="add" onclick="window.location.href = 'addevent_1.php';"><a href="addevent_1.php">DODAJ WYDARZENIE</a></td>
						<?php
							if($zalogowany == 1)
							{
								echo '<td><img src="img/avatar.png" alt="nick" id="avatar_fix"/></td>
									<td><a href="setting.php">'.$user["login"].'</a></td>';
							}
							else{
								echo '<td><img src="img/avatar.png" alt="nick" id="avatar_fix"/></td>
									<td><a href="login.php">Zaloguj</a></td>';
							}
						?>
						</tr>
				</table>
			</div>
			<div style="clear: both;"></div>
		</nav>
		<nav id="mobile_main_nav">
			<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo"/></a>
			<img src="img/menu_mobile.png" alt="Rozwiń menu" id="show_mobile_menu"/>
			<table>
				<tr><td><a href="index.php#event_calendar">KALENDARZ</a></td></tr>
				<tr><td><a href="mapa.php">MAPA</a></td></tr>
				<tr><td><a href="addevent_1.php">DODAJ WYDARZENIE</a></td></tr>
				<?php
					if($zalogowany == 1)
					{
						echo '<tr><td><a href="setting.php">'.$user["login"].'</a></td></tr>';
					}
					else{
						echo '<tr><td><a href="login.php">ZALOGUJ</a></td></tr>
							<tr><td><a href="zarejestruj.php">ZAREJESTRUJ</a></td></tr>';
					}
				?>
			</table>
		</nav>
			<section id="container">
				<section id="top_events">
					<!--<h1>MAPA - zobacz jak trafić</h1>-->
					<section id="big_map">
						
					</section>
					<div id="slider">
						<div id="slider-vertical" style="height:200px;"></div>
					</div> 
				</section>
			</section>
			<?php echo $events;?>
			<!--<footer>
				
			</footer>-->
		</div>
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
		<script src="js/scripts_mainmap.js"></script>
		<script type="text/javascript" src="js/scripts_mobile.js"></script>
	</body>
</html>