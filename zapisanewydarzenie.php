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

	$eventTd = eventsAsTd($conn);
	$eventArchivTd = eventArchivAsTd($conn);
	
	$waitingEventsTd = waitingEventsAsTd($conn);
	$recSortable = recEventSortable($conn);
	$kom = "";
	
	if(isset($_POST["submit"])){
		if($_POST["submit"] == "Dodaj") {
			addCategory($conn);
			$kom = "Nowa kategoria została dodana!";
		}
		if($_POST["submit"] == "Edycja") {
			editCategory($conn);
			$kom = "kategoria została zedytowana";
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
				<?php 
					$conn = sqlConnect();
					$per = getPermission($mh, $conn);
					sqlClose($conn);
					if($per == 1 || $per == 2) {
						$sortRec = '<input class="btn" type="button" id="showSortableRec" name="showSortableRec" value="Zmień polecane wydarzenie na stronie głównej"/>
								<div id="recommendate_events" style="display:none;">
									<h2>Polecane wydarzenia:</h2>
									<h6>*pierwsze cztery są wyświetlane na głownej w "POLECANE", jak jakieś z nich się zakończy to na jego miejsce wchodzi kolejne</h6>
									<ul id="sortable">
										'.$recSortable.'
									</ul>
									<input style="background: red;" class="btn" type="button" id="changePrio" name="changePrio" value="Zapisz zmiany"/>
								</div>';
					}
					else $sortRec = "";
					echo $sortRec;
				?>
				
				<div id="base_events">
				<?php 
					$conn = sqlConnect();
					$per = getPermission($mh, $conn);
					sqlClose($conn);
					if($per == 1 || $per == 2) {
						$pocz = '<h2>POCZEKALNIA:</h2>
						<div id="waiting_events">
							<table class="p_table" id="w_p_table">
								<tr class="must_visability">
									<th style="width: 5%;">Nr</th>
									<th style="width: 15%;">Kategorie</th>
									<th style="width: 10%;">Obrazek</th>
									<th style="width: 60%;">Nazwa</th>
									<th class="delete">Akceptuj</th>
									<th class="delete">Usuń</th>
				
								</tr>'.$waitingEventsTd.'
                                <tr class="must_visability">
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td><input class="btn2 btn" type="submit" name="usunZPoczekalni" value="usuń wybrane"/></td>
								    <td class="empty"></td>
								</tr>								
							</table>
						</div>';
					}
					else $pocz = "";
					echo $pocz;
				?>
				<?php 
					if($eventTd == ""){
						echo '<h2>AKTUALNE WYDARZENIA:</h2>
								Nie masz aktualnych wydarzeń, aby dodać <a class="add_new_ev_href" href="addevent_1.php">kliknij tutaj</a>';
					}
					else{
						echo '<div id="p_d_search">
								<img src="img/p_search.png" alt="Wyszukaj"/>
								<input type="text" name="search"  placeholder="szukaj" id="search"/>
							</div>
							<h2>AKTUALNE WYDARZENIA:</h2>
							<table class="p_table" id="p_table">
								<tr class="must_visability">
									<th style="width: 5%;">Nr</th>
									<th style="width: 15%;">Data</th>
									<th style="width: 15%;">Kategorie</th>
									<th style="width: 10%;">Obrazek</th>							
									<th style="width: 60%;">Nazwa</th>
									<th class="delete">Usuń</th>';
									
						if($per == 1 || $per == 2) echo '<th>Polecane</th><th>Główne:</th>';
						
						echo '</tr>
								'.$eventTd.'
								<tr class="must_visability">
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td><input class="btn2 btn" type="submit" name="usun" value="usuń wybrane"/></td>
								</tr>
							</table>';
								
					}
				
				?>
					<br><br>
					<h2>ARCHIWUM:</h2>
				<?php 
					if($eventArchivTd == ""){
						echo 'Nie masz archiwalnych wydarzeń';
					}else{
						echo '<table class="p_table" id="p_table">
							<tr class="must_visability">
								<th style="width: 5%;">Nr</th>
								<th style="width: 15%;">Data</th>
								<th style="width: 15%;">Kategorie</th>
								<th style="width: 10%;">Obrazek</th>							
								<th style="width: 60%;">Nazwa</th>
								<th class="delete">Usuń</th>
								<th>Polecane</th>
							</tr>
							'.$eventArchivTd.'
							<tr class="must_visability">
								<td class="empty"></td>
								<td class="empty"></td>
								<td class="empty"></td>
								<td class="empty"></td>
								<td class="empty"></td>
								<td><input class="btn2 btn" type="submit" name="usunZArchiwum" value="usuń wybrane"/></td>
							</tr>
						</table>';
						
					}
				?>
					
					
				</div>
				
			</section>
			<footer>
				
			</footer>
			<div id="confirm_delete">
				<p>Czy na pewno chcesz usunąć te wydarzenia?</p>
				<img src="img/confirm_yes.png" class="yes" alt="TAK"/>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<img src="img/confirm_no.png" class="no" alt="NIE"/>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript" src="js/scripts_eve.js"></script>
		<script type="text/javascript" src="js/scripts_sortRecEve.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script>
		  $(function() {
			$( "#sortable" ).sortable({
			  placeholder: "ui-state-highlight"
			});
			$( "#sortable" ).disableSelection();
		  });
		</script>
		<script type="text/javascript" src="js/skrypt_nav_panel.js"></script>
	</body>
</html>