<?php
	include_once 'mysql.php';
	include_once 'function.php';
	cleanEventSession();
	$conn = sqlConnect();
	if(!(isset($_GET['id']))){
		header("LOCATION: index.php");
	}
	if(!(isset($_GET['preview']))){
		$preview = 0;
	}else $preview = 1;
	
	$id = $_GET['id'];
	addVisitorToEvent($id, $conn);
	
	$event = getEvent($conn, $id);
	$place = getPlace($event['id_miejsce']);
	$kat = getCategoryName($conn, $event['id_kat']);
	$kat_icon = getCategoryIcon($conn, $event['id_kat']);
	
	$data = $event['data'];
	$d = strtotime($data);
	$readyData = getPolishDayName(date("N", $d)).'. '.date("d", $d).' '.getPolishMonthName(date("n", $d));
	$time = $event['czas'];
	$readyTime = substr($event['czas'], 0, 5);
	
	$data_end = $event['data_end'];
	$d_end = strtotime($data_end);
	$readyDataEnd = getPolishDayName(date("N", $d_end)).'. '.date("d", $d_end).' '.getPolishMonthName(date("n", $d_end));
	$time_end = $event['czas_end'];
	$readyTimeEnd = substr($event['czas_end'], 0, 5);
	
	$is_archive = isArchive($event['data_end'], $event['czas_end']); 
	$is_waiting = $event['poczekalnia'];
	$link = linkToEvent($event["id"]);
	$fblink = "https://co.wkaliszu.pl/".$link;
	$per = 0;
	
	if($event['cena'] != ""){
		if($event['cena'] == "0") $price = "wstęp wolny";
		else $price = "Od ".$event['cena']." zł";
	}else $price = "";		
	
	$yt = "";
	if($event['yt'] != "") $yt = substr($event['yt'], 8);
		
	if(isset($_COOKIE['stmh']))
	{//zalogowany
		$zalogowany = 1;
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		$per = getPermission($mh, $conn);
	}
	else{//nie zalogowany
		$zalogowany = 0;
		$per = -1;
	}	
	sqlClose($conn);
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta property="og:title" content="<?php echo $event['nazwa']; ?> " />
		<meta property="og:site_name" content="Nadchodzące imprezy z Kalisza"/>
		<meta property="og:description" content="<?php echo str_replace("<br />"," ", $event['opis']); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:image" content="https://co.wkaliszu.pl/<?php echo $event['obraz']; ?>" />
		
		<meta name="description" content="Kalisz: <?php echo $event['nazwa']; ?> - <?php echo $kat; ?> - wiem co.wkaliszu.pl">
        <meta name="keywords" content="wydarzenia w Kaliszu, Kalisz, koncerty, filmy, kino, kino Helios, kino Cinema 3D, teatr, teatr im. Bogusławskiego, rozrywka">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Kalisz, wydarzenia i miejsca spotkań - wiem co.wkaliszu.pl</title>
		
		<script type="application/ld+json">
			[{
			  "@context" : "http://schema.org",
			  "@type" : "<?php echo $kat; ?>",
			  "name" : "<?php echo $event['nazwa']; ?>",
			  "image" : "https://co.wkaliszu.pl/<?php echo $event['obraz']; ?>",
			  "url" : "<?php echo "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>",
 			  "startDate" : "<?php echo $event['data'].' '.$event['czas']; ?>",
			  "location" : {
				"@type" : "Place",
				"name" : "<?php echo $place['nazwa']; ?>",
				"address" : "<?php echo $place['adres']; ?>"
			  },
			}]
			</script>
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<style>
			img[src="http://maps.gstatic.com/mapfiles/api-3/images/mapcnt6.png"] {
				display: none !important;
			}
		</style>
	</head>
	<body>
		<?php include 'menu.php'?>
		<div id="top">			
			<section id="container">
				<?php if($preview == 1 && $per > 0){
						echo '<section id="preview-options">
								<button class="btn btn-p-o b-accept" onclick="accept();">AKCEPTUJ</button><br>
								<button class="btn btn-p-o b-edit" onclick="edit();">EDYTUJ</button><br>
								<button class="btn btn-p-o b-delete" onclick="del();">ANULUJ</button>
							</section>';
				}?>
				<section id="top_events">
					<br><br><a class="btn" href="add_event.php?id=<?php echo $id;?>">Edytuj wydarzenie</a><br><br>
					<h1 id="event_id" data-id="<?php echo $event['id']; ?>"><a href="/">co.wkaliszu.pl</a> > <a href="/#kalendarz">wydarzenia</a> > <?php echo $event['nazwa']; ?> <?php if($is_archive) echo '<span class="s_archive"> - UWAGA! To wydarzenie już się zakończyło!</span>';?></h1>
					<div id="main_event" class="main_on_eventpage">
						<input type="hidden" id="event_info" data-title="<?php echo $event['nazwa']; ?>" data-place="<?php echo $place['nazwa']; ?>"/>
						<input type="hidden" id="x" data-x="<?php echo $place['x']; ?>" />
						<input type="hidden" id="y" data-y="<?php echo $place['y']; ?>" />
						<div class="mainevent_img_on_eventpage">
							<img src="<?php echo $event['obraz']; ?>" alt="Tytuł wydarzenia" id="main_picture"/>
							<?php if($is_archive) echo '<img src="img/archiwum.png" class="archive" alt="Wydarzenie archiwalne"/>';?>
						</div>
						<div class="mainevent_desc">
						    <div class="mainevent-title-box eventpage_desc">
								<img src="<?php echo $kat_icon; ?>" alt="<?php echo $kat; ?>"/> <span><?php echo $kat; ?></span>
							    <img src="img/fb.png" alt="Udostępnij" id="fbshare"/>
								<p class="add-to-like-box"><span>Dodaj<br>do schowka</span><img src="img/add_to_fav.png" alt="Dodaj do ulubionych" data-id="<?php echo $event['id']; ?>" id="main-like-icon" class="liked_icon" style="cursor:pointer;"/></p>
							</div>
							<div class="mainevent-info-box">
								<div>
									<h2><?php echo $event['nazwa']; ?></h2>
									<p class="place-name"><?php echo $place['nazwa']; ?><br><?php echo $place['adres']; ?></p>
									<h4 class="event-price"><?php echo $price; ?></h4>
								</div>
								<div class="mainevent-date-box">
									<p><?php echo $readyData ?> | <?php echo $readyTime; ?></p>
									<?php echo getDatesOfEvent($event['id']); ?>
								</div>
							</div>
							<!--<p style="float:right;">
								<?php echo $place['nazwa']; ?><br>
								<?php echo $readyData ?> | <?php echo $readyTime; ?><br>
							</p>
							<img src="<?php echo $kat_icon; ?>" alt="<?php echo $kat; ?>"/> <span><?php echo $kat; ?></span>
							<h2><?php echo $event['nazwa']; ?></h2>-->
							<!--<table>
								<tr>
									<td><img src="img/views.png" alt="Widziało: "/><?php echo $event['widzow']; ?></td>
									<td><img src="img/comments.png" alt="Komentarzy: "/><?php echo $event['komentarzy']; ?></td>
									<td style="padding-right: 10px;"><img src="img/favourite.png" alt="Ulubione: "/><?php echo $event['ulubione']; ?></td>
									<td><img src="img/add_to_fav.png" alt="Dodaj do ulubionych" data-id="<?php echo $event['id']; ?>" onClick="add_to_like(<?php echo $event['id']; ?> , this);" class="liked_icon" style="cursor:pointer;"/> <div class="fb-share-button" data-href="<?php echo $fblink; ?>" data-layout="icon"></div></td>
								</tr>
							</table>-->
						</div>
					</div>
					<div id="event_right">
						<img src="img/goToMap.png" onclick="location.href='mapa.php'" alt="Otwórz mapę" id="goToBigMap">
						<?php /*<div id="reminder">
							<p><img src="img/remind.png" alt="Ustaw przypomnienie"/> Ustaw przypomnienie</p>
							*/?><?php /*if(!$zalogowany) echo '<div id="login_panel_rem">
								<p>Aby ustawić przypomnienie zaloguj się: </p>
								<table>
								<form action="" method="post">
								Login:<br>
								<input type="text" name="login" id="login"/><br>
								Hasło:<br>
								<input type="password" name="pass" id="pass"/><br>
								<div><input class="btn" type="button" name="submit" id="tryLogin" value="Zaloguj"/></div>
								</form>
							</table>
							<p id="infoLogin" style="display:none;"></p></div>';
							*/?>
							<?php /*<div id="remineder_panel">
								<table>
									<tr>
										<td><div id="div_rem1" class="rem"><img src="img/reminder.png" alt="Ustaw godzinę" class="temp" id="rem1" data-time="2"/></div></td>
										<td><div id="div_rem2" class="rem"><img src="img/reminder.png" alt="Ustaw godzinę" class="temp" id="rem2" data-time="2"/></div></td>
									</tr>
									<tr>
										<td><img src="img/nochecked.png" data-check="0" id="SMSRemaind" alt="Przypomnienie sms" class="check_img"/> sms</td>
										<td><img src="img/nochecked.png" data-check="0" id="MAILRemaind" alt="Przypomnienie e-mail" class="check_img"/> e-mail</td>
									</tr>
								</table>
								<p id="saveRemaind">zapisz</p>
							</div>
						</div>*/?>
						<div id="event_on_map"  style="height: 500px;"></div>
						<div id="place-more-info">
							<div class="place-header">
								<img src="img/markerE.png" alt="<?php echo $place['nazwa']; ?>">
								<p class="place-name"><?php echo $place['nazwa']; ?></p>
								<p class="place-adress"><?php echo $place['adres']; ?></p>
							</div>
							<p>
								tel.: 62-759-75-57<br>
								strona.: <a href="www.teatr.pl">www.teatr.pl</a><br><br>
								Godziny otwarcia:<br>
								pon. - ndz. 7:30-22:00
							</p>
							<img src="img/place_main.png" alt="Teatr" class="image">
						</div>
					</div>
					<div id="main_event_desc">
						<p><?php echo nl2br($event['opis']); ?></p>
						<?php if($event['www'] != "") echo '<h6>Strona wydarzenia: <a href='.$event['www'].'>'.$event['www'].'</a></h6>'; ?>
						<?php if($yt != "") echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$yt.'" frameborder="0" allowfullscreen></iframe>'; ?>
						<h6 class="event-author">Dodane przez: <?php echo getUserLogin($event['id_user']);?></h6>
					</div>
				</section>
				<section class="event-comments">
					<div class="add-comment">
						<div class="avatar-comment">
							<img src="img/bigavatar.png" alt="nick">
						</div>
						<div class="add-comment-form">
							<form id="addNewComment">
								<textarea name="content" placeholder="Napisz komentarz..."></textarea>
								<input type="hidden" name="type" value="1">
								<input type="hidden" name="id_item" id="id_item" value="<?php echo $event['id']; ?>">
							</form>
							<button id="addComment" onclick="add_comment();">Dodaj</button>
						</div>
					</div>
					<div class="comments-list comment-cont">
						<?php echo getCommentForEvent($event['id']); ?>
						<p class="show-more" onclick="showMoreComments();">pokaż więcej komentarzy</p>
						<div class="loading-panel full-loading-panel">
							<img src="img/loading.gif" alt="Ładowanie">
						</div>
					</div>
				</section>
				<?php if($is_waiting && ($per == 1 || $per == 2)) echo '<div class="acceptEvent accept" data-id="'.$event['id'].'">AKCEPTUJ</div>';?>
				<section class="other-event-in-place other-ev-in-place">
					<h3>Wydarzenia w tym samym miejscu</h3>
					<?php echo getEventInPlace($event['id_miejsce'], $event['id']); ?>
					<p class="more-event">Wczytaj więcej >> </p>
				</section>
				<section class="other-event-in-place other-ev-in-cat">
					<h3>Wydarzenia z tej samej kategorii</h3>
					<?php echo getEventInSameCategory($event['id_kat'], $event['id']); ?>
					<p class="more-event-incat">Wczytaj więcej >> </p>
				</section>
				<section class="other-event-in-place near-restaurant">
					<h3>Gdzie zjeść w pobliżu wydarzenia</h3>
					<?php echo getNearRestuarant($event['id_miejsce']); ?>
				</section>
			</section>
		</div>
		<footer>
			<div class="cont">
				<a href="regulamin.php">Regulamin i polityka prywatno&#347;ci</a>
				<a style="float: right; margin-right: 20px;" href="http:\\www.pinkelephant.pl"> Projekt www.pinkelephant.pl</a>
			</div>
		</footer>
		<div id="confirm_delete">
			<p>Czy na pewno chcesz usunąć ten komentarz?</p>
			<img src="img/confirm_yes.png" class="yes" alt="TAK" onclick="deleteCom();"/>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<img src="img/confirm_no.png" class="no" alt="NIE" onclick="closeParent();"/>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maps.google.com/maps/api/js?key=AIzaSyDa4nN-bDVonpOyK5S7HAx23krp3ZBRLhE&sensor=false" type="text/javascript"></script>
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script type="text/javascript" src="js/skrypt_eventpage.js"> </script> 
		<script type="text/javascript" src="js/skrypt_liked.js"></script>
		<script type="text/javascript" src="js/scripts_eve.js"></script>
		<script type="text/javascript" src="js/skrypt_remider.js"></script>
        <script type="text/javascript" src="js/skrypt_widget.js"></script>
        <script type="text/javascript" src="js/skrypt_comment.js"></script>
		<?php if($per >=1 ) echo '<script type="text/javascript" src="js/skrypt_editcomment.js"></script>';?>
		<div id="fb-root"></div>
		<script>/*(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.4&appId=502492939917304";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));*/</script>
		<script>
			$(".add-to-like-box").click(function(){
			//	$(".add-to-like-box .liked_icon").click();
				add_to_like(<?php echo $event['id']; ?> , $("#main-like-icon"));
			});
			$(".show-other").click(function(){
				$(".mainevent-date-box table").toggle("slow");
				if($(this).text()[0] != "P"){
					$(this).text("Pokaż inne terminy tego wydarzenia");
				}else $(this).text("Schowaj inne terminy");
			});
		</script>
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
		document.getElementById('fbshare').onclick = function() {
		  FB.ui({
			display: 'popup',
			method: 'share',
			href: '<?php echo $fblink; ?>',
		  }, function(response){});
		}
		</script>
		<script>
		document.getElementById('zaloguj').onclick = function() {
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
				console.log(response);	
				console.log("zmpfbsynu.php?n=" + response.name + "&m=" + response.email);
				xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","zmpfbsynu.php?n=" + response.name + "&m=" + response.mail ,true);
				xmlhttp.send();
				setTimeout(function(){ location.reload(); }, 1000);
			});
		}
		</script>
		<script>
		
		function accept(){
			window.location.href = 'index.php';
		}
		function edit(){
			window.location.href = 'editevent.php?id=<?php echo $id;?>';
		}
		function del(){
			var id = <?php echo $id;?>;
			xmlhttp=new XMLHttpRequest();
			xmlhttp.open("GET","usuneve.php?id=" + id,true);
			xmlhttp.send();
			window.location.href = 'index.php';
		}
		</script>
	</body>
</html>	