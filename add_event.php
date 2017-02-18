<?php
	include_once 'mysql.php';
	include_once 'function.php';
	cleanEventSession();
	
	$event_group = true;
	$addComplete = false;
	$editComplete = false;
	$isValid = true;
	$linkToEvent = "#";
	$linkToEdition = "#";
	$conn = sqlConnect();
	if(isset($_COOKIE['stmh']))
	{//zalogowany
		$zalogowany = 1;
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		$idU = $user['id'];
		$per = getPermission($mh, $conn);
	}
	else{//nie zalogowany
		$zalogowany = 0;
		$per = -1;
		$idU = -1;
	}	
	
	if(!($per == 2 || ($per >= 0 && $idP == -1) || ($per >= 0 && $idP > 0 && isPlaceOwner($idU, $idP) ))){
		//header("LOCATION: /");		
	}
	
	if($_POST){
		if(validForm()){
			$id = addslashes($_POST['id']);
			$name = addslashes($_POST['name']);
			$id_kat = addslashes($_POST['id_kat']);
			$desc = addslashes($_POST['opis']);
			$yt = addslashes($_POST['yt']);
			$www = addslashes($_POST['www']);
			$price = addslashes($_POST['price']) == 1 ? addslashes($_POST['price-val']) : "0";
			$id_place = addslashes($_POST['placeId']);
			$hoursFromPlace = addslashes($_POST['time-from-place']) == 1;
			
			if(isset($_FILES['img']) && $_FILES['img']["tmp_name"] != ""){
				$img = savePhotoFromBlob($name, $_FILES['img']);
				$thumb = saveThumbPhoto($name, $img, addslashes($_POST['imgWidth']));
			}else{
				if(addslashes($_POST['changeThumb'])){
					$thumb = saveThumbPhoto($name, addslashes($_POST['old_image']), addslashes($_POST['imgWidth']));
				}else{
					$thumb = addslashes($_POST['old_thumb']);
				}
				$img = addslashes($_POST['old_image']);
			}
			
			$id_gallery = -1;
			
			if($id == -1 ){
				$eventsIds = array();
				foreach($_POST['date'] as $i => $date){
					$data = addslashes($date);
					for($j = 0; $j < count($_POST['time'][$i]); $j+=2){
						$time = addslashes($_POST['time'][$i][$j]);
						$time_end = addslashes($_POST['time'][$i][$j+1]);
						$eventsIds[] = addEvent($name, $id_kat, $img, $thumb, $data, $time, $time_end, $desc, $id_place, $idU, $www, $yt, $price);
					}
				}
				if(count($eventsIds) > 1){
					$i = 0;
					$id_group = -1;
					foreach($eventsIds as $id_event){
						if($i == 0){
							$id_group = $id_event;
						}
						updateGroupId($id_group, $id_event);
						$i++;
					}
				}
				//dodano poprawnie wydarzenia
				$addComplete = true;
			}else{
				//edycja wydarzenia
				if(!(isset($_GET['single']))){
					//grupa
					$id_group = getEvent($conn, $id)['grupa'];
					ungroupEventsInGroup($id_group);
					foreach($_POST['date'] as $i => $date){
						$data = htmlspecialchars(addslashes($date));
						for($j = 0; $j < count($_POST['time'][$i]); $j+=2){
							$time = htmlspecialchars(addslashes($_POST['time'][$i][$j]));
							$time_end = htmlspecialchars(addslashes($_POST['time'][$i][$j+1]));
							if(isset($_POST['time']['id'][$i][$j])){
								$id = $_POST['time']['id'][$i][$j];
								$eventsIds[] = newEditEvent($id, $name, $id_kat, $thumb, $img, $data, $time, $data, $time_end, $desc, $id_place, $www, $yt, $price);
							}else{
								$eventsIds[] = addEvent($name, $id_kat, $img, $thumb, $data, $time, $time_end, $desc, $id_place, $idU, $www, $yt, $price);
							}
						}
					}				
					if(count($eventsIds) > 1){
						$i = 0;
						$id_group = -1;
						foreach($eventsIds as $id_event){
							if($i == 0){
								$id_group = $id_event;
							}
							updateGroupId($id_group, $id_event);
							$i++;
						}
					}
				}else{
					//pojedynczy
					$data = htmlspecialchars(addslashes($_POST['date'][0]));	
					$time = htmlspecialchars(addslashes($_POST['time'][0][0]));	
					$time_end = htmlspecialchars(addslashes($_POST['time'][0][1]));	
					$eventsIds[0] = newEditEvent($id, $name, $id_kat, $thumb, $img, $data, $time, $data, $time_end, $desc, $id_place, $www, $yt, $price);
				}
				$editComplete = true;
			}
			$linkToEvent = linkToEvent($eventsIds[0]);
			$linkToEdition = linkToEventEdition($eventsIds[0]);
			$returnArr['linkToEvent'] = $linkToEvent;
			$returnArr['linkToEdition'] = $linkToEdition;
			echo json_encode($returnArr);die;
		}else{
			$isValid = false;
		}
	}
	
	if(!(isset($_GET['place']))){
		if(!(isset($_GET['id']))){
			header("LOCATION: set_place.php");
		}else{
			$id = intval($_GET['id']);
			$event = getEvent($conn, $id);
			$image = $event['obraz'];
			if($image != ''){
				$type = pathinfo($image, PATHINFO_EXTENSION);
				$data = file_get_contents($image);
				$imageBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			}
			$id_kat = $event['id_kat'];
			$thumb = $event['miniatura'];
			$name = $event['nazwa'];
			$desc = $event['opis'];
			$www = $event['www'];
			$yt = $event['yt'];
			$price = $event['cena'];
			$free = $price == 0;
			$place = getPlace($event['id_miejsce']);
			$x = $place['x'];
			$y = $place['y'];	
			$eventsInGroup = array();		
			if($event['grupa'] > 0 && !(isset($_GET['single']))){
				$eventsInGroup = getEventsFromGroup($event['grupa']);
				$event_group = true;
			}else{
				$event_group = false;
			}
		}	
	}else{
		if(!(isset($_GET['id']))){
			$id = -1;
			$image = '';
			$id_kat = '';
			$thumb = '';
			$name = '';
			$desc = '';
			$www = '';
			$yt = '';
			$price = 0;
			$free = $price == 0;
			$place = getPlace(addslashes($_GET['place']));
			$x = $place['x'];
			$y = $place['y'];
			$imageBase64 = '';
			$eventsInGroup = array();
		}else{
			$id = intval($_GET['id']);
			$event = getEvent($conn, $id);
			$image = $event['obraz'];
			if($image != ''){
				$type = pathinfo($image, PATHINFO_EXTENSION);
				$data = file_get_contents($image);
				$imageBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			}
			$id_kat = $event['id_kat'];
			$thumb = $event['miniatura'];
			$name = $event['nazwa'];
			$desc = $event['opis'];
			$www = $event['www'];
			$yt = $event['yt'];
			$price = $event['cena'];
			$free = $price == 0;
			$place = getPlace($event['id_miejsce']);
			$x = $place['x'];
			$y = $place['y'];
			$eventsInGroup = array();
			if($event['grupa'] > 0 && !(isset($_GET['single']))){
				$eventsInGroup = getEventsFromGroup($event['grupa']);
				$event_group = true;
			}else{
				$event_group = false;
			}
		}	
	}	
	
	if(!$isValid){
		$id = intval($_POST['id']);
		$name = addslashes($_POST['name']);
		$id_kat = addslashes($_POST['id_kat']);
		$image = $_POST['img'];
		$imageBase64 = '';
		if($image != ''){
			$type = pathinfo($image, PATHINFO_EXTENSION);
			$data = file_get_contents($image);
			$imageBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		}
		$desc = addslashes($_POST['opis']);
		$yt = addslashes($_POST['yt']);
		$www = addslashes($_POST['www']);
		$price = addslashes($_POST['price']) == 1 ? addslashes($_POST['price-val']) : "0";
		$free = $price == 0;
		$place = getPlace(addslashes($_POST['placeId']));
		$x = $place['x'];
		$y = $place['y'];
		$hoursFromPlace = addslashes($_POST['time-from-place']) == 1;
	}
	
	function validForm(){
		if(!isset($_POST["name"]) || $_POST["name"] == "") return false;
		if(!isset($_POST["opis"]) || $_POST["opis"] == "") return false;
		if(!isset($_POST['placeId']) || $_POST['placeId'] == "" || getPlace(addslashes($_POST['placeId'])) == 1) return false;
		if(!isset($_POST['date']) || count($_POST['date']) == 0) return false;
		
		return true;
	}
	
	sqlClose($conn);
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="co.wkaliszu.pl">
        <meta name="keywords" content="wydarzenia w Kaliszu, Kalisz, koncerty, filmy, kino, kino Helios, kino Cinema 3D, teatr, teatr im. Bogusławskiego, rozrywka">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Kalisz, wydarzenia i miejsca spotkań - wiem co.wkaliszu.pl</title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="stylesheet" type="text/css" href="style/place_css.css">
		<link rel="stylesheet" type="text/css" href="style/place-style.css">
		<link rel="stylesheet" type="text/css" href="style/style_calendar.css">
		<link rel="stylesheet" href="style/jquery.Jcrop.css" type="text/css" />
		<style>
			img[src="http://maps.gstatic.com/mapfiles/api-3/images/mapcnt6.png"] {
				display: none !important;
			}
		</style>
	</head>
	<body id="place-main-container">
		<?php include 'menu.php'?>
		<div id="top" class="place-page-container">			
			<section id="container" class="add_event_cont">
				<input type="file" name="file" id="image" class="btn hide">
				<input type="hidden" name="img" id="img" value="<?php echo $imageBase64; ?>">
				<form action="" id="place-add-form" method="post" enctype="multipart/form-data" onsubmit="return validateForm(event)">
				<section id="top_events">		
					<input type="hidden" name="id" id="place_id" value="<?php echo $id; ?>">
					<input type="hidden" name="placeId" id="placeId" value="<?php echo $place['id']; ?>">
					<h1 id="event_id" data-id="<?php echo $id; ?>"><?php echo ($id>0) ? 'Edycja': 'Dodawanie';?> wydarzenia</h1>
					<div id="event_right">
						<div id="place_on_map" style="height: 500px;"></div>
						<button class="btn" type="button" id="change_place">Zmień miejsce</button>
						<div class="search-cont" id="search-cont">
							Wyszukaj<br>
							<input type="text" placeholder="wyszukaj" class="fuzzy-search" name="place"/>
							<ul class="list">
								<?php 
									foreach(getAllPlaces() as $p){
										echo '<li><p class="name change-place-name" data-id="'.$p['id'].'" onclick="changePlace('.$p['id'].', '.$p['x'].', '.$p['y'].', \''.addslashes($p['nazwa']).'\');">'.$p['nazwa'].'</p></li>';
									}
								?>
							</ul>
						</div>
					</div>
					<input type="hidden" name="X" id="X" value="0"/>
					<input type="hidden" name="Y" id="Y" value="0"/>
					<input type="hidden" name="W" id="W" value="1000"/>
					<input type="hidden" name="changeThumb" id="changeThumb" value="0"/>
					<input type="hidden" name="imgWidth" id="imgWidth" value="0"/>
					<input type="hidden" name="old_image" id="old_image" value="<?php echo $image; ?>"/>
					<input type="hidden" name="old_thumb" id="old_thumb" value="<?php echo $thumb; ?>"/>
					<p class="error big-error" id="error-pic">Wybierz zdjęcie</p>
					<div id="main_event" class="main_on_eventpage">
						<div class="mainevent_img_on_eventpage">
							<?php echo '<img src="'.$image.'" alt="Zdjęcie wydarzenia" id="main_picture"/>'; ?>
							<div class="add-image-cat-place-container">
								<img src="img/add_icon.png" alt="dodaj główne zdjęcie" id="add-main-imange-icon"/>
								<br><br>
								<button class="btn btn-add" id="add_main_image" type="button">Dodaj zdjęcie dla tego wydarzenia</button>
							</div>
							<button class="btn btn-add" id="add_main_thumb" onclick="startDoThumb(100,100,100)" type="button">Ustaw miniaturę</button>
							<button class="btn btn-cancel" id="stop_main_thumb" onclick="destroyJcrop();" type="button">Zapisz miniaturę</button>
						</div>
						<div class="mainevent_desc">
						    <div class="mainevent-title-box eventpage_desc">
								<div id="id_kat_wrapper">
									<span class="select-cat-label">Wybierz kategorię</span>
									<select name="id_kat" id="id_kat">
										<?php
											foreach(getEventCats() as $c){
												$selected = $c['id'] == $id_kat ? 'selected' : '';
												echo '<option value="'.$c['id'].'" '.$selected.'>'.$c['nazwa'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
							<div class="categories-chooser filter-container">
								<ul>
								<?php
									foreach(getEventCats() as $c){
										$selected = $c['id'] == $id_kat ? 'activ-option' : '';
										echo '<li class="'.$selected.'" data-id="'.$c['id'].'">'.$c['nazwa'].'</li>';
									}
								?>
								</ul>
							</div>
							<div class="mainevent-info-box">
								<input type="hidden" name="ax" id="ax" value="<?php echo $x; ?>"/>
								<input type="hidden" name="ay" id="ay" value="<?php echo $y; ?>"/>
								<input type="hidden" name="placeName" id="placeName" value="<?php echo $place['nazwa']; ?>"/>
								<h1 class="place-name">
									<p class="error" id="empty-place-name">Podaj nazwę wydarzenia</p>
									<input type="text" name="name" class="cat-form-name" value="<?php echo $name; ?>" placeholder="Wpisz nazwę">
								</h1>
								<div class="date-container <?php if(!$event_group) echo 'edit-single-date';?>">
									<p class="error" id="empty-date">Podaj termin wydarzenia</p>
									<p>Kiedy:</p>
									<div class="datapicker-container">
										<img src="img/calendar.png" onclick="showCalendar();" alt="Wybierz termin">
										<p class="setDate" onclick="showCalendar();">Wybierz termin</p>
										<p class="moreInfo">*możesz zaznaczyć więcej niż jeden dzień</p>
										<div id="datepicker"></div>
										<div id="print-array"></div>
									</div>
									<div class="time-from-place-cont">
										<label class="rad"><input type="radio" name="time-from-place" value="1"> <i></i> Czas trwania wydarzenia zależny od godzin otwarcia miejsca</label>
										<label class="rad"><input type="radio" checked id="set-hours" name="time-from-place" value="2"> <i></i> Uzupełnij ramy czasowe dla tego wydarzenia</label>
									</div>
									<div class="time-conatiner">
									</div>
								</div>
								<div class="price-container">
									<p class="error" id="error-price">Błędna cena</p>
									Cena:<br>
									<label class="rad"><input type="radio" name="price" value="1" <?php echo $free ? '': 'checked'; ?>> <i></i> płatne</label>
									<div class="price-val-cont <?php echo $free ? 'no-active-price': ''; ?>">
										od <input type="text" <?php echo $free ? 'disabled': ''; ?> name="price-val" placeholder="00,00" value="<?php echo $price;?>" onchange="validatePrice();"> PLN
									</div>
									<label class="rad"><input type="radio" name="price" value="0" <?php echo $free ? 'checked': ''; ?> onchange="validatePrice();"> <i></i> bezpłatne</label>
								</div>
							</div>
						</div>
					</div>
					<div id="main_event_desc">
						<p class="error" id="empty-place-desc">Podaj opis wydarzenia</p>
						<p><textarea name="opis" placeholder="Opis..." id="place_desc"><?php echo $desc;?></textarea></p>
						<div class="addicinal-info">
							<p>Strona www wydarzenia</p>
							<p class="error" id="error-www">Błędny adress www</p>
							<input type="text" name="www" id="www-form-input" class="cat-form-name" value="<?php echo $www; ?>" placeholder="">
						</div>
						<div class="addicinal-info">
							<p>Link do filmu (opcjonalnie)</p>
							<p class="error" id="error-yt-link">Podaj poprawny link</p>
							<span>https://www.youtube.com/</span> <input type="text" name="yt" id="yt-link-form" class="cat-form-name" value="<?php echo $yt; ?>" placeholder="">
						</div>
					</div>	
					<div id="place-gallery">
						<button class="btn" type="button" id="add_gallery">Dodaj galerię</button>
						<button class="btn" type="button" id="add_files">Dodaj plik do pobrania</button>
					</div>
				</section>
				<section class="center main-buttons-action" >
					<p class="error big-error" id="correct-errors">Popraw błędy przed dodaniem wydarzenia:</p>
					<button class="btn btn-add" id="save-category" type="submit"><?php echo $id > 0 ? 'Edytuj' : 'Dodaj'; ?> wydarzenie</button>
					<button class="btn btn-cancel" id="cancel-save-category" type="button">Anuluj</button>
				</section>
				</form>
			</section>
			<div class="loading-panel full-loading-panel">
				<img src="img/loading.gif" alt="Ładowanie">
			</div>
		</div>
		<footer>
			<div class="cont">
				<a href="regulamin.php">Regulamin i polityka prywatno&#347;ci</a>
				<a style="float: right; margin-right: 20px;" href="http:\\www.pinkelephant.pl"> Projekt www.pinkelephant.pl</a>
			</div>
		</footer>
		<div id="confirm-adding-without-image-1" class="cat-filter-container">
			<div class="vertical-center-wrap">
				<p class="center">
					Dziękujemy<br>Twoje wydarzenie zostało poprawnie dodane
					<br><br>
					<a href="#" class="linkToEvent btn btn-red">Zobacz podgląd</a>
					<a href="#" class="linkToEdition btn btn-add">Wróc do edycji</a>
				</p>
			</div>
		</div>
		<div id="confirm-adding-without-image-2" class="cat-filter-container">
			<div class="vertical-center-wrap">
				<p class="center">
					Dziękujemy<br>Twoje wydarzenie zostało poprawnie zedytowane
					<br><br>
					<a href="#" class="linkToEvent btn btn-red">Zobacz podgląd</a>
					<a href="#" class="linkToEdition btn btn-add">Wróc do edycji</a>
				</p>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maps.google.com/maps/api/js?key=AIzaSyDa4nN-bDVonpOyK5S7HAx23krp3ZBRLhE&sensor=false" type="text/javascript"></script>
		<script type="text/javascript" src="js/skrypt_widget.js"></script>
		<script src="js/jquery.Jcrop.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="js/list.min.js"></script>
		<script type="text/javascript" src="js/fizzy.js"></script>
		<script type="text/javascript" src="js/place-scripts.js"></script>
		<script type="text/javascript" src="js/cat-chooser.js"></script>
		<?php 
			if($event_group){
				echo '<script>';
				if(count($eventsInGroup) > 0){
					echo 'var dates = '.json_encode(array_keys($eventsInGroup)).';';
					echo 'var multipleDates = '.json_encode($eventsInGroup).';';
				}else{
					echo 'var dates = [];';
					echo 'var multipleDates = [];';
				}
				echo '</script>';
				echo '<script type="text/javascript" src="js/multidate_script.js"></script>';
				if(isset($eventsInGroup)){
					echo '<script>addDatesToMultipleDates();</script>';
				}
			}				
			else{
				echo '<script>
					var dates = [\''.$event['data'].'\'];
				</script>';
				echo '<script type="text/javascript" src="js/singledate_script.js"></script>';
				echo '<script>
					addDateToSingleDate(dates[0],\''.substr($event['czas'], 0, 5).'\', \''.substr($event['czas_end'], 0, 5).'\');
				</script>';
			}
		?>
		<script>
			var changeImage = false;
			var mapa;
			var marker;
			var jest = false;
			var ikona;
			var dymek = new google.maps.InfoWindow();
			function dodajMarker(latlng){
				jest = true;
				marker = new google.maps.Marker({  
								position: latlng,
								map: mapa,
								icon: ikona
							});
			}
			function mapaStart(){  
				var wspolrzedne = new google.maps.LatLng($("#ax").val(),$("#ay").val());
				var opcjeMapy = {
					zoom: 14,
					center: wspolrzedne,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					disableDefaultUI: true,
				};
				mapa = new google.maps.Map(document.getElementById("place_on_map"), opcjeMapy); 

				//cechy ikon markera
				var rozmiar = new google.maps.Size(37,49);
				var punkt_startowy = new google.maps.Point(0,0);
				var punkt_zaczepienia = new google.maps.Point(18,49);
				
				// ikonka makrera
				ikona = new google.maps.MarkerImage("img/place_marker.png", rozmiar, punkt_startowy, punkt_zaczepienia);
				
				dodajMarker(new google.maps.LatLng($("#ax").val(),$("#ay").val()));
				
				google.maps.event.addListener(marker,"mouseover",function()
				{
					dymek.open(mapa,marker);
				});
				
				updateDymek($("#placeName").val());

			} 
			$(document).ready(function(){
				mapaStart();
				activateSearch();
				$("#change_place").click(function(){
					$("#search-cont").show();
				});
			});
			
			var isCorrectForm = false;
			$("input[name=name]").change(validEventName);
			function validEventName(){
				if($("input[name=name]").val() == ""){
					$("#empty-place-name").show();
					$("input[name=name]").addClass("error-input");
					isCorrectForm = false;
				}else{
					$("#empty-place-name").hide();
					$("input[name=name]").removeClass("error-input");
				}
			}

			$("#place_desc").change(validateDesc);
			function validateDesc(){
				if($("#place_desc").val() == ""){
					$("#empty-place-desc").show();
					$("#place_desc").addClass("error-input");
					isCorrectForm = false;
				}else{
					$("#empty-place-desc").hide();
					$("#place_desc").removeClass("error-input");
				}
			}
			
			function validateDate(){
				if(dates.length == 0){
					$("#empty-date").show();
					isCorrectForm = false;
				}else{
					$("#empty-date").hide();
				}
				validateHoursFiled();
			}

			function validateHoursFiled(){
				var patt = new RegExp("^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$");
				if($("input[name='time-from-place']:checked").val() == 2){
					$(".time-details input").each(function(){
						if(!($(this).hasClass('not-valid'))){
							if(!patt.test($(this).val())){
								$(this).addClass("error-input");
								console.log("tutaj");
								isCorrectForm = false;
							}else{
								$(this).removeClass("error-input");
							}
						}
					});
				}
			}
			
			function validatePrice(){
				if($("input[name='price']:checked").val() == 1){
					if(isNaN(parseFloat($("input[name='price-val']").val().replace(/,/g, '.')))){
						$("#error-price").show();
						$("input[name='price-val']").addClass("error-input");
						isCorrectForm = false;
					}else{
						var val = parseFloat($("input[name='price-val']").val().replace(/,/g, '.'));
						$("input[name='price-val']").val(val);
						$("#error-price").hide();
						$("input[name='price-val']").removeClass("error-input");
					}
				}else{
					$("#error-price").hide();
					$("input[name='price-val']").removeClass("error-input");
				}
			}

			function validatePic(){
				if($("#img").val() == "" && $("#main_picture").attr('src') == ""){
					isCorrectForm = false;
					$("#error-pic").show();
				}else{
					$("#error-pic").hide();
				}
			}

			function validateForm(event){
				//if(!isCorrectForm) event.preventDefault();
				event.preventDefault();
				isCorrectForm = true;
				validEventName();
				validateDesc();
				validateDate();
				validatePic();
				validatePrice();
				validateHoursFiled();
				setTimeout(function(){
					if(isCorrectForm){
						var formData = new FormData(document.forms[0]);
						if(changeImage){
							var base64ImageContent = $("#img").val().replace(/^data:image\/(png|jpg|jpeg);base64,/, "");
							var blob = base64ToBlob(base64ImageContent, 'image/png');  
							formData.append('img', blob);
						}
						var url = "add_event.php"; 
						$(".full-loading-panel").show();
						$.ajax({
							url: url, 
							type: "POST", 
							cache: false,
							contentType: false,
							processData: false,
							data: formData
						}).done(function(data){
							console.log(data);
							var links = JSON.parse(data);
							$(".linkToEvent").attr('href', links['linkToEvent']);
							$(".linkToEdition").attr('href', links['linkToEdition']);
							$(".full-loading-panel").hide();
							$("#confirm-adding-without-image-1").show();
						});
					}else{
						$("#correct-errors").show();
					}
				}, 200);
			}
			
			function showErrors(){
				validEventName();
				validateDesc();
				validateDate();
				validatePic();
				validatePrice();
				validateHoursFiled();
			}

			function getIsCorrectForm(){
				return isCorrectForm
			}
		</script>
		<script>
			var monkeyList;
			function activateSearch(){
				monkeyList = new List('search-cont', { 
					valueNames: ['name'], 
					plugins: [ ListFuzzySearch() ] 
				});
				monkeyList.on('searchComplete',updateCatList);	
			}
			
			function updateCatList(){
				//$("#set_place_list_place a").addClass("no-active");
				$("#set_place_list_place a").hide();
				$("#search-cont .name").each(function(index){
					var place = $(this).html();
					$("#set_place_list_place a").each(function(){
						if(place == $(this).html()){
							//$(this).removeClass("no-active");
							$(this).show();
						}
					});
					
					if(index + 1 == $("#search-cont .name").length){
						updateLetterCont();
					}
				});
				if($("#search-cont .name").length == 0){
					updateLetterCont();
				}
			}
			
			function updateLetterCont(){
				$(".placesLetter").each(function(index){
					if($(".fuzzy-search").val() == ""){
						$(this).show();
					}else{
						if($(this).find('a:visible').length == 0){
							$(this).hide();
						}else{
							$(this).show();
						}
					}
				});
			}
			
			function changePlace(id, x, y, name){
				$("#placeName").val(name);
				$("#placeId").val(id);
				$("#search-cont").hide();
				$("html, body").animate({ scrollTop: 0 }, 300);
				var newURL = updateURLParameter(window.location.href, 'place', id);
				window.history.pushState("", "", newURL);
				updateMapMarker(x, y);
				getOpenHours();
				updateDymek($("#placeName").val());
			}
			
			function updateMapMarker(x, y){
				$("#ax").val(x);
				$("#ay").val(y);
				marker.setMap(null);
				dodajMarker(new google.maps.LatLng($("#ax").val(),$("#ay").val()));
				mapa.setCenter(new google.maps.LatLng($("#ax").val(),$("#ay").val()));
			}
			
			function updateURLParameter(url, param, paramVal){
				var newAdditionalURL = "";
				var tempArray = url.split("?");
				var baseURL = tempArray[0];
				var additionalURL = tempArray[1];
				var temp = "";
				if (additionalURL) {
					tempArray = additionalURL.split("&");
					for (i=0; i<tempArray.length; i++){
						if(tempArray[i].split('=')[0] != param){
							newAdditionalURL += temp + tempArray[i];
							temp = "&";
						}
					}
				}

				var rows_txt = temp + "" + param + "=" + paramVal;
				return baseURL + "?" + newAdditionalURL + rows_txt;
			}
			
			function updateDymek(text){
				dymek.setContent(text);
				dymek.open(mapa,marker);
			}
		</script>
		<?php 
			if(!$isValid) 
				echo '<script>
					showErrors();
				</script>';
		?>
	</body>
</html>	