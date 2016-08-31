<?php
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	
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
	$place = getPlaceFirstLetter($conn);
	if(isset($_POST["submit"])){
		if($_POST["submit"] == "Dalej") {
			$_SESSION["id_place"] = $_POST['id_place'];
			header("LOCATION: addevent_3.php");
		}
		if($_POST["submit"] == "Zapisz miejsce") {
			addPlace($conn);
			sleep(1);
			header("LOCATION: addevent_2.php");
		}
	}
	
	sqlClose($conn);
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Panel użytkownika-wkaliszu.pl</title>
		<link rel="stylesheet" type="text/css" href="style/style_panel.css">
		<link rel="stylesheet" type="text/css" href="style/styles_autoComplete.css">
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
			<div id="menu_add_event">
				<table>
					<tr>
						<td>
							<a href="#">
								<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
								Co
							</a>
						</td>
						<td class="p_activ">
							<img src="img/p_add_next.png" alt="Dalej"/>
						</td>
						<td class="p_activ">
							<a href="#">
								<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
								Gdzie
							</a>								
						</td>
						<td class="p_activ">
							<img src="img/p_add_next.png" alt="Dalej"/>
						</td>
						<td>
							<a href="#">
								<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
								Kiedy
							</a>
						</td>
					</tr>
				</table>
			</div>
			<section id="panel_wydarzenia">
				<div id="letterFiltr">
					<span class="checkPlaceByLetter">A</span><br>
					<span class="checkPlaceByLetter">B</span><br>
					<span class="checkPlaceByLetter">C</span><br>
					<span class="checkPlaceByLetter">D</span><br>
					<span class="checkPlaceByLetter">E</span><br>
					<span class="checkPlaceByLetter">F</span><br>
					<span class="checkPlaceByLetter">G</span><br>
					<span class="checkPlaceByLetter">H</span><br>
					<span class="checkPlaceByLetter">I</span><br>
					<span class="checkPlaceByLetter">J</span><br>
					<span class="checkPlaceByLetter">K</span><br>
					<span class="checkPlaceByLetter">L</span><br>
					<span class="checkPlaceByLetter">M</span><br>
					<span class="checkPlaceByLetter">N</span><br>
					<span class="checkPlaceByLetter">O</span><br>
					<span class="checkPlaceByLetter">P</span><br>
					<span class="checkPlaceByLetter">R</span><br>
					<span class="checkPlaceByLetter">S</span><br>
					<span class="checkPlaceByLetter">T</span><br>
					<span class="checkPlaceByLetter">U</span><br>
					<span class="checkPlaceByLetter">V</span><br>
					<span class="checkPlaceByLetter">W</span><br>
					<span class="checkPlaceByLetter">X</span><br>
					<span class="checkPlaceByLetter">Y</span><br>
					<span class="checkPlaceByLetter">Z</span><br>
				</div>
				<form action="" method="post" id="#form" onsubmit="return checkPlace();">
					<div id="p_add_event_place">
						Wyszukaj<br>
						<input type="text" placeholder="wyszukaj" name="place" id="search"/><br>
						<p class="add_new_place"><img src="img/add_place_plus.png" alt="Dodaj miejsce"/>  jeśli na liście nie ma miejsca, dla Twojego wydarzenia - możesz dodać własne</p>
						<table id="place_list">
							<?php echo $place;?>
						</table>
					</div>
					<input type="hidden" name="id_place" value="<?php if(isset($_SESSION["id_place"]))echo $_SESSION["id_place"]; else echo "-1";?>"/>
					<p class="btn_in_panel_add"><a class="btn" href="addevent_1.php">Cofnij</a>&emsp;&emsp;&emsp;<input class="btn" type="submit" name="submit" value="Dalej" id="next"/></p>
					<p class="btn_in_panel_add" style="display: none;"></p>
				</form>
				<form action="" method="post">
					<div id="add_new_place" style="display:none;">
					
						<img src="img/exit.png" alt="zamknij" id="close_add"/>
						<p>Dodaj nowe miejsce</p>
						<div id="event_add_new_place">
							<div>
								Nazwa<br>
								<input type="text" name="name_of_new_place"/><br><br>
								<select name="categorie_of_new_place" style="display:none;">
									<option value="1">klub</option> 
								</select><br><br>
								Adres<span>(miasto|ulica|nr)</span><br>
								<input type="text" id="input_adress" name="adress_of_new_event" onChange="zaznacz(this.value);"/>
								<input type="hidden" id="x" name="x"/>
								<input type="hidden" id="y" name="y"/>
								<br>
								<!--<a id="zaznaczNaMapie">Zaznacz na mapie</a>-->
							</div>
						</div>
						<div id="event_add_new_place_desc">
							Opis<br>
							<textarea name="desc"></textarea>						
						</div>
						<div id="map" style="width: 80%; height: 300px; /*display:none;*/ clear:both; margin: 10px auto;" />
						</div>
						<div>
							<p class="btn_in_panel_add"><input class="btn" type="submit" name="submit" value="Zapisz miejsce" /></p>
						</div>
					</div>
				</form>
			</section>
			<footer>
				
			</footer>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
		<script type="text/javascript" src="js/skrypt_dodaj_miejsce.js"> </script> 
		<script type="text/javascript" src="js/skrypt_place_finder.js"> </script>
		<script type="text/javascript" src="js/skrypt_nav_panel.js"></script>
	</body>
</html>