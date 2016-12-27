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
	$kom = "";
	
	$query="SHOW TABLE STATUS WHERE `Name` = 'wydarzenia'";
	$result=$conn->query($query);
	while($row = $result->fetch_assoc()) {		
		$next_id = $row['Auto_increment'];
	}
	if(isset($_POST["submit"])){
		$kom = validateForm();
		if($kom == ""){
			$_SESSION["group"] = -1;
			if(count($_POST["listTime"]) > 4){
				$_SESSION["group"] = $next_id;								
			}
			$_SESSION["next_id"] = $next_id;
			for($i = 0 ; $i < count($_POST["listTime"]); $i+=4){
				$_SESSION["data"] = htmlspecialchars($_POST['listTime'][$i]); 
				$_SESSION["time"] = htmlspecialchars($_POST['listTime'][$i+1]);
				$_SESSION["data_end"] = htmlspecialchars($_POST['listTime'][$i]); 
				$_SESSION["time_end"] = htmlspecialchars($_POST['listTime'][$i+2]);
				addEvent($conn);
			}		
			sqlClose($conn);
			session_destroy();
			//header("LOCATION: index.php");
			$kom = "1";
		}		
	}
	
	//sqlClose($conn);
	
	function validateForm(){
		$kom = "";
		if(!isset($_SESSION["name"])) $kom .= "Brak nazwy<br>";
		if(!isset($_SESSION["id_kat"])) $kom .= "Wybierz kategorię<br>";
		if(!isset($_SESSION["desc"])) $kom .= "Brak opisu<br>";	
		if(!isset($_SESSION["photo"])) $kom .= "Brak miniatury<br>";
		if(!isset($_SESSION["mainphoto"])) $kom .= "Brak zdjęcia<br>";
		if(!isset($_SESSION["id_place"])) $kom .= "Brak miejsca<br>";
		return $kom;
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Panel użytkownika-wkaliszu.pl</title>
		<link rel="stylesheet" type="text/css" href="style/style_panel.css">
		<link rel="stylesheet" type="text/css" href="style/style_calendar.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script>
		$(function() {
			$( "#data" ).datepicker({
				nextText: "",
				prevText: "",
				dateFormat: 'yy-mm-dd',
				dayNamesMin: [ "Nie", "Pon", "Wt", "Śr", "Cz", "Pt", "So" ],
				monthNames: [ "Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień" ],
				minDate: 0
			});	

			$( "#sliderTime" ).slider({
			  range: true,
			  min: 0,
			  max: 1440,
			  step: 15,
			  values: [ 720, 900],
			  slide: function( event, ui ) {
					var x = ui.values[0];
					var h = Math.floor(x/60);
					var m = x-h*60;
					if(m == 0) m = "00";
					if(h == 24) {h = "23"; m = "59";}
					$( "#labelH" ).text(h + ":" + m);
					$("#time").val($( "#labelH" ).text());
					
					x = ui.values[1];
					h = Math.floor(x/60);
					m = x-h*60;
					if(m == 0) m = "00";
					if(h == 24) {h = "23"; m = "59";}
					$( "#labelM" ).text(h + ":" + m);
					$("#time_end").val($( "#labelM" ).text());
			  }
			});
			$( "#sliderTime span:first-of-type").html("<div id='labelH'></div>");
			$( "#labelH" ).text("12:00");
			$("#time").val($( "#labelH" ).text());
			
			$( "#sliderTime span:last-of-type").html("<div id='labelM'></div>");
			$( "#labelM" ).text("15:00");
			$("#time_end").val($( "#labelM" ).text());
		  });		  
		</script>
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
						<td>
							<a href="#">
								<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
								Gdzie
							</a>								
						</td>
						<td class="p_activ">
							<img src="img/p_add_next.png" alt="Dalej"/>
						</td>
						<td class="p_activ">
							<a href="#">
								<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
								Kiedy
							</a>
						</td>
					</tr>
				</table>
			</div>
			<section id="panel_wydarzenia">
				<form action="" method="post" id="form_add_ev">
					<div id="p_add_event_time">
						<div id="event_add_calendar">
							Data wydarzenia<br>
							<input type="text" id="data" name="date" value="<?php echo date("Y-m-d");?>">
							<p>Zakres godzin</p>
							<div id="sliderTime"></div>
							<p id="newTimesToEvent" onclick="addNewTime();"> + dodaj następny termin dla tego wydarzenia</p>
						</div>
						<div id="event_add_time">
							<div class="time_list">
								<table class="p_table" id="timeListTable">
									<tr>
										<th>Data wydarzenia</th>
										<th>Początek wydarzenia</th>
										<th>Koniec wydarzenia</th>
										
									</tr>
								</table>
							</div>
						</div>
					</div>
					<input type="hidden" id="time" name="time">
					<input type="hidden" id="time_end" name="time_end">
					<div id="listOfTimes">
					</div>
					<p class="btn_in_panel_add"><a class="btn" href="addevent_2.php">Cofnij</a>&emsp;&emsp;&emsp;<input class="btn" style="display: none;" id="send_form_add" type="submit" name="submit" value="Zapisz"/><input class="btn" type="button" onclick="checkTime();" name="submitButton" value="Zapisz"/></p>
					<p><?php echo $kom; ?></p>
				</form>
			</section>
			<footer>
				
			</footer>
			<div id="info_add">
			    <p id="isOk" data-info="<?php echo $kom; ?>"><?php if($kom == 1) echo "Wydarzenie czeka na akceptację administratora. O akceptacji powiadomimy mailem.<br>Zobacz jak wygląda Twoja wydarzenie...";
				else echo $kom; ?></p>
			</div>
		</div>
		<input type="hidden" id="id" value="<?php echo $next_id;?>">
		<script type="text/javascript" src="js/scripts_addevents.js"></script>
		<script type="text/javascript" src="js/skrypt_nav_panel.js"></script>
	</body>
</html>