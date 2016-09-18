<?php
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	
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
							<!--<p class="cowkaliszu-nothingIsHere">NIC TU NIE MA:(</p>
							<p class="cowkaliszu-no_events_info">żadne wydarzenie nie zostało<br>
							dodane do ulubionych</p>
							<div id="image_of_nothing">
								<img src="img/widget_1.png" alt="Nic tu nie ma"/>
							</div>-->
							<p class="cowkaliszu-view_add_event"><strong>PRZEGLĄDAJ i DODAWAJ ULUBIONE WYDARZENIA</strong></p>
							<p class="cowkaszliu-cowkaliszu_logo"><strong> >> <a href="https://co.wkaliszu.pl" target="_parent" ><img src="img/logo.png" alt="Przejdź do logowania" /></a> << 
							<br><span>lub</span></strong></p>
							<p class="maybe_login"><strong> >> ZALOGUJ SIĘ << </strong></p>
							<img src="img/fb_login.png" id="zalogujFB">
						</div>
						<form action="" method="post" class="widget_panel_form_co" style="display:none;">
						Login lub email:<br>
						<input type="text" name="login" id="login"/><br>
						Hasło:<br>
						<input type="password" name="pass" id="pass"/><br>
						<div><input class="btn_panel" type="button" name="submit" id="tryLogin" value="Zaloguj"/></div>
						</form>
					   <p id="infoLogin" style="display:none;"></p>
					   <div id="remind">
							<p id="remPassShow" onclick="remPassShow();">Zapomniałeś hasła? Przypomnij hasło.</p>
							<div id="remaidForm">
								Twój e-mail:<br>
								<input type="text" name="my-mail" id="my-mail"/><br>
								<input type="button" class="btn_panel" value="Przypomnij hasło" onclick="remindPass();"><br>
								<p id="result-remaind">Nowe hasło zostało wysłane na Twój adres e-mail</p>
							</div>
						</div>
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
					   <!--<a class="cowkaliszu-to_pinki" href="http://www.pinkelephant.pl/" target="_parent"><img src="img/pink.png"/><br>reklama | zamów reklamę</a>-->
					   <a class="cowkaliszu-to_wkaliszu" href="http://wkaliszu.pl/" target="_parent">Przejdź do strony <img src="img/wkaliszu.png"/></a>
					   ';
	}
	
	
	
	if(isset($_POST["submit"])){
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
		$_SESSION['www']= addslashes(htmlspecialchars($_POST['www']));
		$_SESSION['yt'] = addslashes(htmlspecialchars($_POST['yt']));
		$_SESSION["price"] = addslashes(htmlspecialchars($_POST['price']));
		if($_SESSION["price"] == "") $_SESSION['price'] = 0; 
		$_SESSION["desc_img"] = addslashes(htmlspecialchars($_POST['desc_img']));
		
		$_SESSION["mainphoto"] = saveMainImageEvent($_SESSION["id"]);
		$_SESSION["photo"] = saveIconEvent($_SESSION["id"], $_SESSION["mainphoto"], 200);
		$_SESSION["group"] = -1;
		if(count($_POST["listTime"]) > 4){
			$_SESSION["group"] = $_SESSION["id"];
		}
		for($i = 0 ; $i < count($_POST["listTime"]); $i+=4){
			$_SESSION["data"] = htmlspecialchars($_POST['listTime'][$i]); 
			$_SESSION["time"] = htmlspecialchars($_POST['listTime'][$i+1]);
			$_SESSION["data_end"] = htmlspecialchars($_POST['listTime'][$i]); 
			$_SESSION["time_end"] = htmlspecialchars($_POST['listTime'][$i+2]);
			addEvent($conn);
		}
		header("LOCATION: beck_set_liked.php");
	}
	
	if(isset($_POST["submit-place"])){
		$conn = sqlConnect();
		addPlace($conn);
		sqlClose($conn);
		header("LOCATION: beck_set_liked.php");
	}
?>
<html style="height: 100%;">
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
						if (input.files[0].size > 2145728 ){
							alert("Ten plik jest za duży, musisz załadować plik do 2MB");
							$(input).val("");
						}
						else{
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
<body style="height: 100%;">
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
						<table class="priceForEvent">
							<tr>
								<td>Bezpłatne<br><input checked type="radio" name="price_type" value="0"></td>
								<td style="width: 30%;">Płatne<br><input checked type="radio" name="price_type" value="1"></td>
								<td>od<br><input type="text" name="price" id="price"></td>
							</tr>
						</table>
						<input type="text" name="www" id="www" placeholder="Strona www"><br><br>
						<span>https://www.youtube.com/</span><input type="text" name="yt" placeholder="Link do filmu z yt"><br><br>
						<input type="text" name="desc_img" id="desc_img" placeholder="Podpis zdjęcia(opcjonalnie)"><br>
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
						<div class="add_place_btn" onclick="showPanelAddPlace();"><img src="img/panel_add_newplace.png" alt="Dodaj nowe miejsce"><span> dodaj własne miejsce</span></div>
					</div>
					<div id="add_place_panel" style="display: none;">
						<div>
							<div id="event_add_new_place">
							<div>
								Nazwa<br>
								<input type="text" name="name_of_new_place"/><br>
								<select name="categorie_of_new_place" style="display:none;">
									<option value="1">klub</option> 
								</select>
								Adres<span></span><br>
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
							<p class="btn_in_panel_add"><input class="btn" type="submit" name="submit-place" value="Zapisz miejsce" /></p>
						</div>
						</div>
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
		<script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
		<script type="text/javascript" src="js/scripts_remPass.js"></script>
		<script type="text/javascript" src="js/skrypt_nav_panel.js"></script>
		<script type="text/javascript" src="js/skrypt_dodaj_miejsce.js"> </script> 
		<script type="text/javascript" src="js/skrypt_place_finder.js"> </script>
	</div>
	
		<script>
		  window.fbAsyncInit = function() {
			FB.init({
			  appId      : '1660825800824875',
			  xfbml      : true,
			  version    : 'v2.5'
			});
		  };

		  (function(d, s, id){
			 var js, fjs = d.getElementsByTagName(s)[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement(s); js.id = id;
			 js.src = "//connect.facebook.net/en_US/sdk.js";
			 fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
		</script>
		<script>
		document.getElementById('zalogujFB').onclick = function() {
			FB.login(function(response) {
				if(response.authResponse){
					getUserInfo(); // Get User Information.
				}else{
					console.log('Authorization failed.');
				}
			},{scope: 'email'});
		}
		
		function getUserInfo() {
			FB.api('/me',  { fields: 'name, email' }, function(response) {
				//console.log(response);	
				//console.log("zmpfbsynu.php?n=" + response.name + "&m=" + response.email);
				var data = {  
					n: response.name,
					m: response.email
				}  
		  
				$.get("zmpfbsynu.php", data, function(response){
					$("#k-s-m-g").html("<p>GÓRA:</p>" + response.substring(0, response.length - 1));
				});
				
				/*xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","zmpfbsynu.php?n=" + response.name + "&m=" + response.mail ,true);
				xmlhttp.send();*/
				setTimeout(function(){ location.reload(); }, 1000);
			});
		}
		</script>

</body>
</html>
