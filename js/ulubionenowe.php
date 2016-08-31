<?php
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	if(isset($_GET['cowkaliszu'])) $cowkalisz = '<a class="cowkaliszu-to_wkaliszu" href="http://co.wkaliszu.pl/" target="_parent">Przejdź do strony <img src="img/logo.png"/></a>';
	else $cowkalisz = '<a class="cowkaliszu-to_wkaliszu" href="http://wkaliszu.pl/" target="_parent">Przejdź do strony <img src="img/wkaliszu.png"/></a>';
	$conn = sqlConnect();
	$cat = categoriesAsSelect($conn);
	$place = getPlaceFirstLetterForLittlePanel($conn);
	sqlClose($conn);
	if(isset($_COOKIE['stmh']))
	{
		$conn = sqlConnect();
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		$like = liked_panel($mh,$conn);
		sqlClose($conn);
		$zalogowany = true;
	}
	else{
		$zalogowany = false;
		$form_login = '<div id="cowkaliszu_login">
						<h2>Zaloguj</h2>
						<div class="cowkaliszu-nothing">
							<p class="cowkaliszu-nothingIsHere">NIC TU NIE MA:(</p>
							<p class="cowkaliszu-no_events_info">żadne wydarzenie nie zostało<br>
							dodane do ulubionych</p>
							<div id="image_of_nothing">
								<img src="img/widget_1.png" alt="Nic tu nie ma"/>
							</div>
							<p class="cowkaliszu-view_add_event"><strong>PRZEGLĄDAJ i DODAWAJ ULUBIONE WYDARZENIA</strong></p>
							<p class="cowkaszliu-cowkaliszu_logo"><strong> >> <a href="login.php" target="_parent" ><img src="img/logo_cowkaliszu.png" alt="Przejdź do logowania" /></a> << 
							<br><span>lub</span></strong></p>
							<p class="maybe_login"><strong> >> ZALOGUJ SIĘ << </strong></p>
						</div>
						<form action="" method="post" class="widget_panel_form_co" style="display:none;">
						Login lub adres e-mail:<br>
						<input type="text" name="login" id="login"/><br>
						Hasło:<br>
						<input type="password" name="pass" id="pass"/><br>
						<div><input class="btn_panel" type="button" name="submit" id="tryLogin" value="Zaloguj"/></div>
						</form>
					   <p id="infoLogin" style="display:none;"></p>
					   <p class="cowkaliszu-maybe_register">Jeśli nie masz konta - <span>zarejestruj się!</span></p>
					   </div>
					   <div id="cowkaliszu_register" style="display: none;">
						<h2>Zarejestruj</h2>
						<form action="" method="post" onsubmit="return validateALL();">
							Adres e-mail:<br>
							<input class="toValidate" type="text" name="email" id="r_email"/><br>
							Login:<br>
							<input class="toValidate" type="text" name="login" id="r_login"/><br>
							Telefon (opcjonalnie):<br>
							<input type="text" name="phone" id="r_phone"/><br>
							Hasło:<br>
							<input class="toValidate" type="password" name="pass" id="r_pass"/><br>
							Powtórz hasło:<br>				
							<input class="toValidate" type="password" name="re_pass" id="r_re_pass"/><br>
							Przepisz kod:<br>	
							<input class="toValidate" type="text" name="cap" id="r_cap_kod" style="width: 50%"/> <img id="cap" src="cap.php" alt="Captach"/><br>
							<label id="accept_reg"><input class="toValidateCheckBox" type="checkbox" name="accept"/> Zgadzam się z <a href="regulamin.php" target="_blank">regulaminem</a> portalu.</label>
							<div><input class="btn_panel" id="tryRegister" type="button" name="submit" value="Zarejestruj"/></div>
						</form>
						<p id="infoRegister" style="display:none;"></p>
					   </div>
					   <a class="cowkaliszu-to_pinki" href="http://www.pinkelephant.pl/" target="_parent"><img src="img/pink.png"/><br>reklama | zamów reklamę</a>
					   '.$cowkalisz;
	}
	
	
	
	if(isset($_POST["name"])){
		$conn = sqlConnect();
		$query="SELECT MAX(id) FROM wydarzenia";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			$_SESSION["id"] = ++$row['MAX(id)'];
		}		
		sqlClose($conn);
		$conn = sqlConnect();
		$_SESSION["name"] = $_POST["name"];
		$_SESSION["id_kat"] = $_POST["id_kat"];
		$_SESSION["recommend"] = 0;
		$_SESSION["desc"] = $_POST["desc"];
		$_SESSION["id_place"] = $_POST["id_place"];
		$views = 0;
		$like = 0;
		$comments = 0;
		$id_user = $_COOKIE['stmh'];
		
		$_SESSION["mainphoto"] = saveMainImageEvent($_SESSION["id"]);
		$_SESSION["photo"] = saveIconEvent($_SESSION["id"], $_SESSION["mainphoto"], 200);
		
		for($i = 0 ; $i < count($_POST["listTime"]); $i+=4){
			$_SESSION["data"] = htmlspecialchars($_POST['listTime'][$i]); 
			$_SESSION["time"] = htmlspecialchars($_POST['listTime'][$i+1]);
			$_SESSION["data_end"] = htmlspecialchars($_POST['listTime'][$i]); 
			$_SESSION["time_end"] = htmlspecialchars($_POST['listTime'][$i+2]);
			addEvent($conn);
		}
		header("LOCATION: beck_set_liked.php");
	}
?>
<html>
	<head>
		<base target="_parent" />
		<meta charset="UTF-8">
		<title>Panel użytkownika-wkaliszu.pl</title>
			<link rel="stylesheet" type="text/css" href="style/style_liked_panel.css">
			<link rel="stylesheet" href="style/jquery.Jcrop.css" type="text/css" />
			<link rel="stylesheet" type="text/css" href="style/style_calendar.css">
			<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
			<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
			<script type='text/javascript' src='js/filereader.js'></script>
			<script src="js/jquery.Jcrop.js"></script>
			<script type='text/javascript' src='js/skrypt_thumbMaker.js'></script>
			<script>
				var jcrop = null;
				
				$( document ).ready(function() {
					if($("#preview").attr("src") != ""){
						var x = 0;
						var y = 0;
						var w = 150;
						if(jcrop) {
							jcrop.destroy();
							$('#preview').attr('style', '');
						}
						jcrop = miniatura(x, y, w);
					}
				});
				function readURL(input) {

					if (input.files && input.files[0]) {
					var reader = new FileReader();

					reader.onload = function (e) {
						$('#preview').show();
						$('#preview').attr('src', e.target.result);
						if(jcrop) {
							jcrop.destroy();
							$('#preview').attr('style', '');
						}
						jcrop = miniatura(0, 0, 150);
					}

					reader.readAsDataURL(input.files[0]);
					}
				}
			</script>
			<script>
				$(function() {
					$( "#data" ).datepicker({
						nextText: "",
						prevText: "",
						dateFormat: 'yy-mm-dd',
						dayNamesMin: [ "Nie", "Pon", "Wt", "Śr", "Cz", "Pt", "So" ],
						monthNames: [ "Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień" ],
						minDate: 0,
						defaultDate: new Date(),
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
<section id="ulubione">
	<?php 
	if(!$zalogowany) echo $form_login;
	else{
		echo '<div id="add_event_panel_mini" style="display: none;">
				<h2>dodaj wydarzenie</h2>
				<table id="nav_panel">
					<tr>
						<td class="active" id="nav_pan1">Co</td>
						<td id="nav_pan2">Gdzie</td>
						<td id="nav_pan3">Kiedy</td>
					</tr>
				</table>
				<form id="form_add_event_panel" action="" method="post" enctype="multipart/form-data" runat="server" target="_self">
					<div id="first_panel">
						<p>Nazwa</p>
						<input type="text" name="name" id="name" value=""/>
						<p>Kategoria</p>
						<select name="id_kat">
							'.$cat.'
						</select>
						<p>Opis</p>
						<textarea name="desc" id="desc"></textarea>
						<input type="file" name="image" id="imageTHUMB" onchange="readURL(this);"/>
						<img src="" alt="Podgląd" id="preview" width="200px" style="display:none"/>						
						<input type="hidden" name="X" id="X" value="0"/>
						<input type="hidden" name="Y" id="Y" value="0"/>
						<input type="hidden" name="W" id="W" value="100"/>
						<input type="hidden" name="orginalW" id="orginalW" value="0"/>
						<input type="hidden" name="orginalH" id="orginalH" value="0"/>
					</div>
					<div id="second_panel"  style="display:none;">
						<p>Wyszukaj</p>
						<input type="text" name="place" value=""/> <p class="ABC_panel">ABC</p>
						<div id="tabel">
							<p class="noPlaceinfo" style="display:none;">Niestety, nie znaleziono żadnych<br>miejsc spełniających Twoje kryteria.</p>
								<table>
									'.$place.'
								</table>
							<input type="hidden" name="id_place" id="id_place" value=""/>
						</div>
						<div id="placeByLetter_panel" style="display:none;">
							<table>
								<tr>
									<td>A</td>
									<td>B</td>
									<td>C</td>
									<td>D</td>
									<td>E</td>
								</tr>
								<tr>
									<td>F</td>
									<td>G</td>
									<td>H</td>
									<td>I</td>
									<td>J</td>
								</tr>
								<tr>
									<td>K</td>
									<td>L</td>
									<td>M</td>
									<td>N</td>
									<td>O</td>
								</tr>
								<tr>
									<td>P</td>
									<td>R</td>
									<td>S</td>
									<td>T</td>
									<td>U</td>
								</tr>
								<tr>
									<td>W</td>
									<td>X</td>
									<td>Y</td>
									<td>Z</td>
								</tr>
							</table>
						</div>
					</div>
					<div id="add_place_panel" style="display:none;">
						<!--<img src="img/panel_add_newplace.png" alt="Dodaj nowe miejsce"><span> dodaj własne miejsce</span>-->
					</div>
					<div id="third_panel" style="display:none;">
						<p>Data wydarzenia</p>
						<input type="text" id="data" name="date" value="">
						<p class="timer_panel">Zakres godzin</p>
						<div id="sliderTime"></div>
						<input type="hidden" id="time" name="time">
						<input type="hidden" id="time_end" name="time_end">
						<p id="newTimesToEvent" onclick="addNewTime();"> + dodaj następny termin dla tego wydarzenia</p>
						<div class="time_list">
							<table class="p_table" id="timeListTable">
								<tr>
									<th>Data</th>
									<th>Start</th>
									<th>Koniec</th>
								</tr>
							</table>
						</div>
						<div id="listOfTimes">
						</div>
					</div>
					<div id="add_new_place_panel" style="display:none;">
						<p>Nazwa</p>
						<input type="text" name="name_new_place" value=""/>
						<p>Opis</p>
						<textarea name="desc_new_place"></textarea>
						<p>Adres</p>
						<input type="text" name="adres_new_place" value=""/>
						<div id="mini_map_panel">
						</div>
					</div>
					<div id="next_prev_btn">
						<table>
							<tr>
								<td><p class="prev btn_panel" onclick="prev();">Cofnij</p></td>
								<td></td>
								<td><p class="next btn_panel" onclick="next();">Dalej</p>
						<p class="add btn_panel" onclick="add_event();">Dodaj</p></td>
							</tr>
						</table>
						<!--&emsp;&emsp;-->
						
					</div>
				</form>
				<p id="currentPanel" style="display: none;">first_panel</p>
			</div>
			<div id="my_liked_panel">
				<h2>ulubione</h2>
				'.$like.'
			</div>';
		
		
		
	}


	?> 
</section>	
	<div class="liked_panel">
		<a class="lps" id="show_liked_event"><img src="img/panel_liked_1.png" id="panel_manu_1" alt="panel ulubionych" ></a>
		<a class="lps"><img src="img/panel_liked_2.png" alt="panel ulubionych" id="add_new_button_event" onclick="showAddPanel()"></a>
		<a href="setting.php" class="lps"><img src="img/panel_liked_3.png" id="panel_manu_3" alt="panel ulubionych"></a>
		<script type="text/javascript" src="js/skrypt_liked.js"></script>
		<script type="text/javascript" src="js/skrypt_for_widget.js"></script>
	</div>
	

</body>
</html>
