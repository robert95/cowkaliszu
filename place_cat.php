<?php
	include_once 'mysql.php';
	include_once 'function.php';
	cleanEventSession();
	
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
	
	if(!(isset($_GET['slug']))){
		header("LOCATION: /");
	}else{
		$arr = explode('/',$_GET['slug']);
		$slug = $arr[0];
		$placeCat = getPlaceCatBySlug($slug);
		if($placeCat == null){
			header("LOCATION: /");
		}
		$places = getPlacesFromCat($placeCat['id']);
	}	
?>
<!doctype html>
<html>
	<head>
		<base href="/cowkaliszu/" />
		<meta charset="UTF-8">
		<meta name="description" content="co.wkaliszu.pl">
        <meta name="keywords" content="wydarzenia w Kaliszu, Kalisz, koncerty, filmy, kino, kino Helios, kino Cinema 3D, teatr, teatr im. Bogusławskiego, rozrywka">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Kalisz, wydarzenia i miejsca spotkań - wiem co.wkaliszu.pl</title>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="stylesheet" type="text/css" href="style/place_css.css">
		<link rel="stylesheet" type="text/css" href="style/place-style.css">
		<link rel="stylesheet" href="style/jquery.Jcrop.css" type="text/css" />
		<style>
			img[src="http://maps.gstatic.com/mapfiles/api-3/images/mapcnt6.png"] {
				display: none !important;
			}
		</style>
	</head>
	<body id="place-cat-main-container">
		<?php include 'menu.php'?>
		<div id="top">
			<section id="container">
				<section id="event_calendar">
					<h3>MIEJSCA</h3>
					<input type="hidden" id="place-cat-slug" value="<?php echo $slug;?>">
					<div id="container_calendar">
						<div class="event-list-in-calendar">
							<div class="filter-in-main filter-container">
								<div class="open-filter-panel show-cat-filter-btn">
									<span>Filtruj miejsca</span> <img src="img/arrow-down.png" alt="filtruj wydarzenia">
								</div>
								<div class="open-filter-panel price-filtr-container">
									<label class="rad">
										<input type="radio" name="sorted" checked value="0"/>
										<i></i> ostatnio dodane
									</label>
									<label class="rad">
										<input type="radio" name="sorted" value="1"/>
										<i></i> najlepiej oceniane
									</label>
									<label class="rad">
										<input type="radio" name="sorted" value="2"/>
										<i></i> alfabetycznie
									</label>
								</div>
								<div class="list-of-activ-filter-fields">
								</div>
							</div>
							<div class="event-list-direct-cont">
							<?php
							$i = 1;
							foreach ($places as $p)
							{
								$link = getLinkToPlace($p["id"], $p['nazwa']);
								$thumb = $p['thumb'] == "" ? $placeCat['thumb']: $p['thumb'];
								$isOpen = "Otwarte";
								$isOpenClass= "open-place";
								$avgRating = number_format((float)getGlobalRatingValAvgForPlace($p["id"]), 2, ',', '');
								$hasOpenHours = false;
								$openHours = [];
								$idFieldOpenHours = -1;
								foreach(getStaticFieldForParent($placeCat['id'], 1) as $d){
									if($d['id_field'] == 4){
										$hasOpenHours = true;
										$idFieldOpenHours = $d['id'];
									}
								}
								if($hasOpenHours){
									$openHours = getDescFieldVal($idFieldOpenHours, 0, $p['id'], 1);
									if(!$openHours){
										$hasOpenHours = false;
									}else{
										$openHours = json_decode($openHours[0]['value']);
										$nowWeekDay = date('w');
										$nowWeekDay = $nowWeekDay != 0 ? $nowWeekDay-1 : $nowWeekDay = 6;
										$nowHour = date('H:i');
										if($openHours[$nowWeekDay*2] == "" || !($openHours[$nowWeekDay*2] <= $nowHour && $nowHour <= $openHours[$nowWeekDay*2+1])){
											$isOpen = "Nieczynne";
											$isOpenClass= "closed-place";
										}
									}
								}
								
								$filtersFieldsIds = "";
								$filters = getFiltersForParent($placeCat['id'], 1);
								foreach($filters as $f){
									$fields = getFiltersForFilter($f['id']);
									$parIds = '';
									foreach($fields as $ff){
										$val = getFilterFieldVal($ff['id'], $p['id'], 1);
										$val = count($val) > 0 ? $val[0]['value'] : 0; 
										$checked = $val == 1 ? "checked" : "";
										if($checked){
											$filtersFieldsIds .= $ff['id'].'-';
											$parIds .= getAllParentsFieldForFilterField($ff['id']);
										}
									}
									$filtersFieldsIds .= $parIds;
								}	
								$filtersFieldsIds = substr($filtersFieldsIds, 0, strlen($filtersFieldsIds)-1);
								//$liked_icon = isLiked($e["id"], $conn) == 0?'img/add_to_fav.png':'img/del_to_fav.png';
																
								echo '<div class="event-n event" onmouseover="zaznaczNaMapie(this);" id="e_'.$i.'" data-rating="'.$avgRating.'" data-link="'.$link.'" data-x="'.$p['x'].'" data-y="'.$p['y'].'" data-title="'.$p['nazwa'].'" data-address="'.$p['adres'].'" data-id="'.$p['id'].'" data-filters="'.$filtersFieldsIds.'">
											<div class="event-th-n">
												<a href="'.$link.'">
													<img src="'.$thumb.'" alt="'.$p['nazwa'].'"  class="eve-thumb">
												</a>
											</div>
											<div class="event-desc-cont-n">
												<div class="event-desc-n place-desc-thumb">
													<p class="place-cat-name">'.$placeCat['name'].'</p>
													<p class="place-title"><a href="'.$link.'">'.$p['nazwa'].'</a></p>
													<p class="place-address">'.$p['adres'].'</p>
													<p class="place-is-open '.$isOpenClass.'">Teraz: <span>'.$isOpen.'</span></p>
													';
													if($hasOpenHours) {	echo '<div class="place-open-hours">
														<p>Godziny otwarcia:</p>
														<table>
															<tr>
																<td>Pon</td>';
																if($openHours[0] != "") echo '<td>'.$openHours[0].' - '.$openHours[1].'</td>';
																else echo '<td class="closed-place"><span>Nieczynne</span></td>';
																echo '<td>Pt</td>';
																if($openHours[8] != "") echo '<td>'.$openHours[8].' - '.$openHours[9].'</td>';
																else echo '<td class="closed-place"><span>Nieczynne</span></td>';
														echo'</tr>
															<tr>
																<td>Wt</td>';
																if($openHours[2] != "") echo '<td>'.$openHours[2].' - '.$openHours[3].'</td>';
																else echo '<td class="closed-place"><span>Nieczynne</span></td>';
																echo '<td>Sob</td>';
																if($openHours[10] != "") echo '<td>'.$openHours[10].' - '.$openHours[11].'</td>';
																else echo '<td class="closed-place"><span>Nieczynne</span></td>';
														echo'</tr>
															<tr>
																<td>Śr</td>';
																if($openHours[4] != "") echo '<td>'.$openHours[4].' - '.$openHours[5].'</td>';
																else echo '<td class="closed-place"><span>Nieczynne</span></td>';
																echo '<td>Ndz</td>';
																if($openHours[12] != "") echo '<td>'.$openHours[12].' - '.$openHours[13].'</td>';
																else echo '<td class="closed-place"><span>Nieczynne</span></td>';
														echo'</tr>
															<tr>
																<td>Czw</td>';
																if($openHours[6] != "") echo '<td>'.$openHours[6].' - '.$openHours[7].'</td>';
																else echo '<td class="closed-place"><span>Nieczynne</span></td>';
																echo'<td></td>
																<td></td>
															</tr>
														</table>
													</div>';
													}
													echo '<p class="place-rating">Średnia ocen: <span>'.$avgRating.'</span></p>
												</div>
											</div>
										</div>';
								$i++;
							}
							?>
							</div>
							<p class="day-header no-events">Przykro nam, nie ma wydarzeń spełniających Twoje kryteria:(</p>
							<div class="loading-panel full-loading-panel">
								<img src="img/loading.gif" alt="Ładowanie">
							</div>
						</div>
						<div id="stick_map" class="">
							<div id="map">
							</div>
						</div>
						<div id="empty_stick_map">
						</div>
					</div>
				</section>
			</section>
		</div>
		<footer>
			<div class="cont">
				<a href="regulamin.php">Regulamin i polityka prywatno&#347;ci</a>
				<a style="float: right; margin-right: 20px;" href="http:\\www.pinkelephant.pl"> Projekt www.pinkelephant.pl</a>
			</div>
		</footer>
		<div class="cat-filter-container filter-container">
			<div class="vertical-center-wrap">
				<div class="filter-elem cat-filtr-container extendend-filter place-filters-panel">
					<?php echo getFiltersForParentToFilter($placeCat['id'], 1, []); ?>
					<div class="cat-filter-btns">
						<button class="back-filter cat-filter-btn">Anuluj</button>
						<button class="accept-filter cat-filter-btn">Potwierdź</button>
					</div>
				</div>
			</div>
		</div>
		<link rel="stylesheet" type="text/css" href="style/style_calendar.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="https://maps.google.com/maps/api/js?key=AIzaSyDa4nN-bDVonpOyK5S7HAx23krp3ZBRLhE&sensor=false" type="text/javascript"></script>
		<script type="text/javascript" src="js/scripts_place_filter.js"></script>
		<script type="text/javascript" src="js/skrypt_liked.js"></script>
		<script type="text/javascript" src="js/skrypt_widget.js"></script>
		<script type="text/javascript" src="js/scripts_mobile.js"></script>
		<script type="text/javascript" src="js/scripts_sorting.js"></script>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.4&appId=502492939917304";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	</body>
</html>	