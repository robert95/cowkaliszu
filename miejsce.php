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
	
	if(!(isset($_GET['id']))){
		header("LOCATION: /");
	}else{
		$id = $_GET['id'];
		$place = getPlace($id);
		if($place == null){
			header("LOCATION: /");
		}
		$image = $place['img'];
		$id_kat = $place['id_kat'];
		$cat = getPlaceCat($id_kat);
		$thumb = $place['thumb'];
		$name = $place['nazwa'];
		$adress = $place['adres'];
		$desc = $place['opis'];
		$x = $place['x'];
		$y = $place['y'];
		$descfields = getDescFieldForParent($id_kat , 1);
		$descStaticfields = getStaticFieldForParent($id_kat , 1);
	}	
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<base href="/cowkaliszu/"/>
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
	<body id="place-main-container">
		<?php include 'menu.php'?>
		<div id="top" class="place-page-container show-place-page">			
			<section id="container">
				<section id="top_events">	
					<a class="btn" href="edytuj-miejsce.php?id=<?php echo $id;?>">Edytuj miejsce</a><br><br>
					<input type="hidden" name="id" id="place_id" value="<?php echo $id; ?>">
					<h1 id="event_id" data-id="<?php echo $id; ?>"><a href="/">co.wkaliszu.pl</a> > <a href="/miejsca.php">miejsca</a> > <a href="/miejsca.php"><?php echo $cat['name']; ?></a> > <?php echo $name; ?></h1>
					<div id="event_right">
						<div id="place_on_map" style="height: 500px;"></div>
					</div>
					<div id="main_event" class="main_on_eventpage">
						<div class="mainevent_img_on_eventpage">
							<?php echo '<img src="'.$image.'" alt="'.$name.'" id="main_picture"/>'; ?>
						</div>
						<div class="mainevent_desc">
						    <div class="mainevent-title-box eventpage_desc">
								<span><?php echo $cat['name']; ?> </span>
							    <img src="img/fb.png" alt="Udostępnij" id="fbshare"/>
								<p class="add-to-like-box"><span>Obserwuj<br>miejsce</span><img src="img/add_to_fav.png" alt="Dodaj do ulubionych" data-id="<?php echo $place['id']; ?>" id="main-like-icon" class="liked_icon" style="cursor:pointer;"/></p>
							</div>
							<div class="mainevent-info-box">
								<input type="hidden" name="ax" id="ax" value="<?php echo $x; ?>"/>
								<input type="hidden" name="ay" id="ay" value="<?php echo $y; ?>"/>
								<div>
									<h2 id="place_name"><?php echo $name; ?></h2>
									<p id="place_adress"><?php echo $adress; ?></p>
								</div>
								<div id="place-desc-fileds">
									<?php 
										foreach($descStaticfields as $d){
											$val = getDescFieldVal($d['id'], 0, $id, 1);
											$val = count($val) > 0 ? $val[0]['value'] : ""; 
											$name = getNameForStaticField($d['id_field']);
											if($name == "godziny otwarcia" || $name == "Godziny otwarcia"){
												if($val){
													$val = json_decode($val);
												}else{
													$val = ["","","","","","","","","","","","","",""];
												}												
												echo '<p class="desc-field">'.$name.':';
												echo '<table id="place-show-open-hours">';
												echo '<tr><td>Poniedziałek</td><td>'.$val[0].' - '.$val[1].'</td></tr>';
												echo '<tr><td>Wtorek</td><td>'.$val[2].' - '.$val[3].'</td></tr>';
												echo '<tr><td>Środa</td><td>'.$val[4].' - '.$val[5].'</td></tr>';
												echo '<tr><td>Czwartek</td><td>'.$val[6].' - '.$val[7].'</td></tr>';
												echo '<tr><td>Piątek</td><td>'.$val[8].' - '.$val[9].'</td></tr>';
												echo '<tr><td>Sobota</td><td>'.$val[10].' - '.$val[11].'</td></tr>';
												echo '<tr><td>Niedziela</td><td>'.$val[12].' - '.$val[13].'</td></tr>';
												echo '</table></p>';
											}else{
												echo '<p class="desc-field">'.$name.': <span>'.$val.'</span></p>';
											}
										}
									?>
									<?php 
									foreach($descfields as $d){
										$val = getDescFieldVal($d['id'], 1, $id, 1);
										$val = count($val) > 0 ? $val[0]['value'] : ""; 
										$name = $d['name'];
										echo '<p class="desc-field">'.$name.': <span>'.$val.'</span></p>';
									}
									?>
								</div>
							</div>
						</div>
					</div>
					<div id="rating-result-container">
						<p class="main-average">Średnia ocen: <span><?php echo number_format((float)getGlobalRatingValAvgForPlace($id), 2, ',', '');?></span></p>
						<?php 
							$ratingsAvg = getRatingValAvgForPlace($id);
							foreach( getRatingForParent( $id_kat , 1) as $rating){
								echo '<p>'.$rating['name'].' <span>';
								for($i = 0; $i < count($ratingsAvg); $i++){
									if($ratingsAvg[$i] == $rating['id']){
										echo '<span class="r-s-'.round($ratingsAvg[$i+1]).'">
											<img src="img/active_star.png" alt="1" class="a-s-1">
											<img src="img/star.png" alt="1" class="n-s-1">
											<img src="img/active_star.png" alt="1" class="a-s-2">
											<img src="img/star.png" alt="1" class="n-s-2">
											<img src="img/active_star.png" alt="1" class="a-s-3">
											<img src="img/star.png" alt="1" class="n-s-3">
											<img src="img/active_star.png" alt="1" class="a-s-4">
											<img src="img/star.png" alt="1" class="n-s-4">
											<img src="img/active_star.png" alt="1" class="a-s-5">
											<img src="img/star.png" alt="1" class="n-s-5">
										</span>';
										echo ' ('.number_format((float)$ratingsAvg[$i+1], 2, ',', '').')';
									}
								}
								echo '</span></p>';
							}
						?>
					</div>
					<div id="main_event_desc">
						<p><?php echo $desc;?></p>
					</div>
					<div id="place-filters">
						<?php
							$filters = getFiltersForParent($id_kat, 1);
							foreach($filters as $f){
								echo '<p>'.$f['name'].': <span>';
								$fields = getFiltersForFilter($f['id']);
								$namesFields = "";
								foreach($fields as $ff){
									$val = getFilterFieldVal($ff['id'], $id, 1);
									$val = count($val) > 0 ? $val[0]['value'] : 0; 
									$checked = $val == 1 ? "checked" : "";
									if($checked) $namesFields .= ' '.$ff['name'].',';
								}
								echo rtrim($namesFields, ',');
								echo '</span></p>';
							}
						?>
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
								<input type="hidden" name="type" value="2">
								<input type="hidden" name="id_item" id="id_item" value="<?php echo $id; ?>">
							</form>
							<div class="rating-panel">
								<p>Dodaj ocenę</p>
								<table>
								<?php 
									foreach( getRatingForParent( $id_kat , 1) as $rating){
										echo '<tr>
										<td>'.$rating['name'].'</td>
										<td class="rating-stars r-s-3" data-val="3" data-id="'.$rating['id'].'">
											<img src="img/active_star.png" alt="1" data-val="1" class="a-s-1">
											<img src="img/star.png" alt="1" data-val="1" class="n-s-1">
											<img src="img/active_star.png" alt="1" data-val="2" class="a-s-2">
											<img src="img/star.png" alt="1" data-val="2" class="n-s-2">
											<img src="img/active_star.png" alt="1" data-val="3" class="a-s-3">
											<img src="img/star.png" alt="1" data-val="3" class="n-s-3">
											<img src="img/active_star.png" alt="1" data-val="4" class="a-s-4">
											<img src="img/star.png" alt="1" data-val="4" class="n-s-4">
											<img src="img/active_star.png" alt="1" data-val="5" class="a-s-5">
											<img src="img/star.png" alt="1" data-val="5" class="n-s-5">
										</td>
									</tr>';
									}
								?>
								</table>
							</div>
							<button id="addComment" onclick="add_comment();">Dodaj</button>
						</div>
					</div>
					<div class="comments-list">
						<?php echo getCommentForPlace($id); ?>
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
		<div id="confirm_delete">
			<p>Czy na pewno chcesz usunąć ten komentarz?</p>
			<img src="img/confirm_yes.png" class="yes" alt="TAK" onclick="deleteCom();"/>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<img src="img/confirm_no.png" class="no" alt="NIE" onclick="closeParent();"/>
		</div>			
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maps.google.com/maps/api/js?key=AIzaSyDa4nN-bDVonpOyK5S7HAx23krp3ZBRLhE&sensor=false" type="text/javascript"></script>
		<script type="text/javascript" src="js/skrypt_widget.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="js/place-page-scripts.js"></script>
        <script type="text/javascript" src="js/skrypt_comment_place.js"></script>
		<?php if($per >=1 ) echo '<script type="text/javascript" src="js/skrypt_editcomment.js"></script>';?>
	</body>
</html>	