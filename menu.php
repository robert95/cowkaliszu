<?php
	include_once 'mysql.php';
	include_once 'function.php';
	$conn = sqlConnect();
	
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
<nav id="MENU">
	<div class="cont">
		<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo"/></a>
		<div id="main_menu">
			<ul id="u_menu">
				<li><a href="index.php#event_calendar">KALENDARZ</a></li>
				<li><a href="mapa.php">MAPA</a></li>
				<li><a href="#">MIEJSCA</a></li>
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
								<td><a href="setting.php" class="nickname">'.$user["login"].'</a></td>';
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
	</div>
</nav>
<nav id="MENU-FIX">
	<div class="cont">
		<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo_fix"/></a>
		<div id="main_menu_fix">
			<ul id="u_menu_fix">
				<li onclick="index.php#event_calendar"><a>KALENDARZ</a></li>
				<li><a href="mapa.php">MAPA</a></li>
				<li><a href="#">MIEJSCA</a></li>
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
								<td><a href="setting.php" class="nickname">'.$user["login"].'</a></td>';
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
	</div>
</nav>
<nav id="mobile_main_nav">
	<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo"/></a>
	<img src="img/menu_mobile.png" alt="Rozwiń menu" id="show_mobile_menu"/>
	<table>
		<tr><td onclick="doKalendarza();"><a>KALENDARZ</a></td></tr>
		<tr><td><a href="mapa.php">MAPA</a></td></tr>
		<tr><td><a href="#">MIEJSCA</a></td></tr>
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