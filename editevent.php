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
	if(!(isset($_GET['id']))){
		header("LOCATION: zapisanewydarzenie.php");
	}
	$id = $_GET['id'];
	
	$conn = sqlConnect();
	$owner = isOwner($id,$conn);
	$per = getPermission($mh, $conn);
	if($per == 1 || $per == 2) {
		$admin = 1;
	}
	else $admin = 0;
	if($owner == 0 && $admin == 0)
	{
		header("LOCATION: zapisanewydarzenie.php");
	}
	
		
	$_SESSION["id"] = $id;
	$event = getEvent($conn, $id);
	$cat = categoriesAsSelectWithSelected($conn, $event['id_kat']);
	if($event['polecane'] == 1)
	{
		$rec = "checked";
	}
	else $rec = "";
	
	$h1 = $event['czas'][0];
	$h2 = $event['czas'][1];
	$m1 = $event['czas'][3];
	$m2 = $event['czas'][4];
	
	$h1e = $event['czas_end'][0];
	$h2e = $event['czas_end'][1];
	$m1e = $event['czas_end'][3];
	$m2e = $event['czas_end'][4];
	
	$startTime = $h1*600+$h2*60+$m1*10+$m2;
	$endTime = $h1e*600+$h2e*60+$m1e*10+$m2e;
	
	$places = placesAsSelectWithSelected($conn, $event['id_miejsce']);
	sqlClose($conn);
	
	if($event['cena'] != 0) $platne = 1;
	else $platne = 0;
	
	if(isset($_POST["submit"])){
		$conn = sqlConnect();
		
		editEvent($conn);
		sqlClose($conn);
		header("LOCATION: zapisanewydarzenie.php");
	}

?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Panel użytkownika-wkaliszu.pl</title>
		<link rel="stylesheet" type="text/css" href="style/style_panel.css">
		<link rel="stylesheet" type="text/css" href="style/style_calendar.css">
		<link rel="stylesheet" href="style/jquery.Jcrop.css" type="text/css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script type='text/javascript' src='js/filereader.js'></script>
		<script>
		$(function() {
			$( "#date" ).datepicker({
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
			  values: [ <?php echo $startTime." , ".$endTime;?> ],
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
				$( "#labelH" ).text("<?php echo $h1.$h2.":".$m1.$m2; ?>");
				$("#time").val($( "#labelH" ).text());
				
				$( "#sliderTime span:last-of-type").html("<div id='labelM'></div>");
				$( "#labelM" ).text("<?php echo $h1e.$h2e.":".$m1e.$m2e; ?>");
				$("#time_end").val($( "#labelM" ).text());
			});		  
		</script>
		<script>
			var jcrop = null;
			function readURL(input) {

				if (input.files && input.files[0]) {
				var reader = new FileReader();
				
				
				reader.onload = function (e) {
					$('#preview').attr('src', e.target.result);
					if(jcrop) {
						jcrop.destroy();
						$('#preview').attr('style', '');
					}
					jcrop = miniatura();
				}

				reader.readAsDataURL(input.files[0]);
				}
			}
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
			<section id="panel_wydarzenia">
				<form action="" method="post" enctype="multipart/form-data" runat="server" onsubmit="return checkTimeLastTime();">
				<div id="edit_event">
						<div id="edit_event_info">
							Nazwa<br>
							<input type="text" name="name" value="<?php echo $event['nazwa'];?>"/><br>
							<select name="categorie_of_edit_event">
									<optgroup label = "Wybierz kategorię:">
									<?php echo $cat; ?>
									</optgroup>
							</select><br>
							<!--ID wydarzenia na facebook-u<br>
							<input type="text" name="fblink" value="<?php /*echo substr($event['fblink'], 32);*/?>"/><br>-->
							<?php if($admin != 0) echo 'Polecane <input type="checkbox" name="recomendate"'.$rec.'/><br><br>';?>
							<div id="oldImage">
								Miniatura: <br>
								<img src="<?php echo $event['miniatura'];?>" alt="podgląd" style="width: 150px;"/><br><br>
								Aktualne zdjęcie:<br>
								<img src="<?php echo $event['obraz'];?>" alt="podgląd" style="width: 150px;"/>
								<br>
								<input class="btn" type="button" name="show_changeImage" value="Zmień zdjęcie i miniature" onclick="showChangeImage();"/>
							</div>
							<div id="changeImage" style="display: none;">
								<input type="hidden" name="old_name" value="<?php echo $event['miniatura'];?>"/>
								<input type="hidden" name="old_nameMAIN" value="<?php echo $event['obraz'];?>"/>
								Wybierz nowe zdjęcie i zaznacz miniaturę:<br><br>
								<input type="file" name="image" onchange="readURL(this);"/>
								<img src="" alt="Podgląd" id="preview" width="250px"/>
								<input type="hidden" name="X" id="X" value="0"/>
								<input type="hidden" name="Y" id="Y" value="0"/>
								<input type="hidden" name="W" id="W" value="100"/>
								<input type="hidden" name="orginalW" id="orginalW" value="0"/>
								<input type="hidden" name="orginalH" id="orginalH" value="0"/>
							</div>
						</div>
						<div id="edit_event_time">
							<div id="edit_event_time_start">
							Wybierz datę dla wydarzenia<br>
							<input type="text" id="date" name="date" value="<?php echo $event['data'];?>">
							<div id="datepicker"></div><br><br>
							<span id="t_s">Wybierz godzinę dla wydarznia<br></span><br><br><br><br>
							<div id="sliderTime"></div>
								<input type="hidden" id="time" name="time" value="<?php echo $h1; ?><?php echo $h2; ?>:<?php echo $m1; ?><?php echo $m2; ?>">
								<input type="hidden" id="time_end" name="time_end" value="<?php echo $h1e; ?><?php echo $h2e; ?>:<?php echo $m1e; ?><?php echo $m2e; ?>">
							<br>
							<br>Cena:<br>
							<label><input <?php if(!$platne) echo 'checked';?> type="radio" name="price_type" value="0">Bezpłatne</label> <label><input type="radio" <?php if($platne) echo 'checked';?> name="price_type" value="1">Płatne</label> <br>od<br><input type="text" <?php if(!$platne) echo 'disabled';?> name="price" id="price" value="<?php echo $event['cena'];?>"/> PLN
							<br>Podpis zdjęcia:<br>
							<input type="text" name="desc_img" id="desc_img" value="<?php echo $event['opis_img'];?>"/>
							<br>Link do filmu:<br>
							<span>https://www.youtube.com/</span><input type="text" name="yt" value="<?php echo $event['yt'];?>" placeholder="watch?v=OTOa_Q0W-AI"/>
							<br>Strona wydarzenia:<br>
							<input type="text" name="www" value="<?php echo $event['www'];?>"/>
							</div>
						</div>
						<div id="edit_event_desc">
							Opis<br>
							<textarea name="desc"><?php echo $event['opis']?></textarea><br>
							Miejsce/adres wydarzenia<br>
							<select name="place" id="adress_place" onChange="zaznacz(this.value);"/>
								<?php echo $places; ?>
							</select><br>
							<div id="eEv_map"></div>
							<p class="btn_in_panel_add"><input style="display: none;" class="btn" type="submit" name="submit" value="Zapisz"/><input style="background: red;" class="btn" type="button" name="submitEdition" value="Zapisz"/></p>
						</div>
				</div>
				</form>
				<div style="clear:both;"></div>
			</section>
			<footer>
			</footer>
			<div id="confirm_delete">
				<p>Czy na pewno chcesz wprowadzić te zmiany?</p>
				<img src="img/confirm_yes.png" class="yes" alt="TAK"/>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<img src="img/confirm_no.png" class="no" alt="NIE"/>
			</div>
		</div>
		<script type="text/javascript" src="js/scripts_addevents.js"></script>
		<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
		<script type="text/javascript" src="js/skrypt_edit_event.js"> </script> 
		<script src="js/jquery.Jcrop.js"></script>
		<script type='text/javascript' src='js/skrypt_thumbMaker.js'></script>
	</body>
</html>