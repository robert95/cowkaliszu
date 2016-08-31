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
	$usersTd = usersTd($conn);
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

				<a class="btn" href="<?php echo getAllMailAdress(); ?>">LISTA MAILI W TXT</a>			
				<div id="base_events">
				<?php 
					$conn = sqlConnect();
					$per = getPermission($mh, $conn);
					sqlClose($conn);
					if($per == 1 || $per == 2) {
						$pocz = '<h2>Lista użytkowników:</h2>
						<div id="waiting_events">
							<table class="p_table" id="w_p_table">
								<tr class="must_visability">
									<th style="width: 5%;">Nr</th>
									<th style="width: 15%;">Login</th>
									<th style="width: 20%;">E-mail</th>
									<th style="width: 20%;">Data rejestracji</th>
									<th style="width: 30%;" class="delete">Dodane wydarzenia</th>
									<th class="delete">Usuń</th>
				
								</tr>'.$usersTd.'
                                <tr class="must_visability">
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td><input class="btn2 btn" type="submit" name="usun" value="usuń wybrane"/></td>
								    <td class="empty"></td>
								</tr>								
							</table>
						</div>';
					}
					else $pocz = "";
					echo $pocz;
				?>
				</div>
				
			</section>
			<footer>
				
			</footer>
			<div id="confirm_delete" class="confirm-delete-users">
				<p>Czy na pewno chcesz usunąć tych użytkowników?</p>
				<img src="img/confirm_yes.png" class="yes" alt="TAK"/>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<img src="img/confirm_no.png" class="no" alt="NIE"/>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript" src="js/scripts_user.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script type="text/javascript" src="js/skrypt_nav_panel.js"></script>
	</body>
</html>