<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$conn = sqlConnect();
	cleanEventSession();
	if(isset($_POST["submit"])){
		saveNewRegulamin($_POST["tresc"]);
	}
	
	$regulamin = getRegulamin();
	
	if(isset($_COOKIE['stmh']))
	{//zalogowany
		$zalogowany = 1;
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		$per = getPermission($mh, $conn);
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
		<title>Regulamin co.wkaliszu.pl</title>
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<style>
			img[src="http://maps.gstatic.com/mapfiles/api-3/images/mapcnt6.png"] {
				display: none !important;
			}
		</style>
	</head>
	<body>
		<nav id="MENU">
			<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo"/></a>
			<div id="main_menu">
				<ul id="u_menu">
					<li><a href="index.php#event_calendar">KALENDARZ</a></li>
					<li><a href="mapa.php">MAPA</a></li>
					<!--<li><a href="miejsca.html">MIEJSCA</a></li>-->
				</ul>
			</div>
			<div id="panel">
				<table>
					<tr>
						<td><img src="img/add.png" alt="Dodaj" id="add_img"/></td>
						<td class="add" onclick="window.location.href = 'addevent_1.php';"><a href="addevent_1.php">DODAJ WYDARZENIE</a></td>
						<?php
							if($zalogowany == 1)
							{
								echo '<td><img src="img/avatar.png" alt="nick" id="avatar"/></td>
									<td><a href="setting.php">'.$user["login"].'</a></td>';
							}
							else{
								echo '<td><img src="img/avatar.png" alt="nick" id="avatar"/></td>
									<td><a href="login.php">Zaloguj</a></td>';
							}
						?>
					</tr>
				</table>
			</div>
			<div style="clear: both;"></div>
		</nav>
		<nav id="MENU-FIX">
			<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo_fix"/></a>
			<div id="main_menu_fix">
				<ul id="u_menu_fix">
					<li onclick="index.php#event_calendar"><a>KALENDARZ</a></li>
					<li><a href="mapa.php">MAPA</a></li>
					<!--<li><a href="miejsca.html">MIEJSCA</a></li>-->
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
		<div id="top">			
			<section id="container">
				<section id="top_events">
					<h1 id="event_id">Regulamin i polityka prywatności</h1>
					<?php if($per == 2){
						echo '<form action="" method="post">
								<textarea name="tresc" id="reg_tresc_area">'.$regulamin.'</textarea>
								<input type="submit" value="zapisz" name="submit" class="btn saveNewRegulamin"/>
						</form>';
					}else echo "<p>".nl2br($regulamin)."</p>"; ?>
				</section>
				
			</section>
			<footer>
				<a href="regulamin.php">Regulamin i polityka prywatno&#347;ci</a>
			</footer>
		</div>
	</body>
</html>