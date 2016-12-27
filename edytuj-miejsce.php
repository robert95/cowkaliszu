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
	
	if(!($per == 2 || ($per >= 0 && $idP == -1) || ($per >= 0 && $idP > 0 && isPlaceOwner($idU, $idP) ))){
		//header("LOCATION: /");		
	}
	sqlClose($conn);
	
	if($_POST){
		$id = addslashes($_POST['id']);
		$name = addslashes($_POST['name']);
		$id_kat = addslashes($_POST['id_kat']);
		$opis = addslashes($_POST['opis']);
		$adress = addslashes($_POST['adress']);
		
		if($_POST['img'] && $_POST['img'] != ""){
			$img = savePhotoFromBase64($name, addslashes($_POST['img']));
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
		$x = addslashes($_POST['ax']);
		$y = addslashes($_POST['ay']);
		
		if($id == -1 ){
			$id = addNewPlace($name, $id_kat, $opis, $x, $y, $adress, $img, $thumb, $id_gallery, $idU);
		}else{
			editPlace($id, $name, $id_kat, $opis, $x, $y, $adress, $img, $thumb, $id_gallery);
		}
		
		//desc fileds static
		foreach($_POST['descStaticField'] as $idDs => $dS){
			$idDs = addslashes($idDs);
			$dS = addslashes($dS);
			$field = getDescFieldVal($idDs, 0, $id, 1);
			$idF = (count($field) > 0) ? $field[0]['id'] : -1; 
			if($idF > 0){
				editDescFieldVal($idF, $dS);
			}else{
				addDescFieldVal($idDs, 0, $dS, $id, 1);
			}
		}
		//desc filed non-static
		foreach($_POST['descField'] as $idDs => $dS){
			$idDs = addslashes($idDs);
			$dS = addslashes($dS);
			$field = getDescFieldVal($idDs, 1, $id, 1);
			$idF = (count($field) > 0) ? $field[0]['id'] : -1; 
			if($idF > 0){
				editDescFieldVal($idF, $dS);
			}else{
				addDescFieldVal($idDs, 1, $dS, $id, 1);
			}
		}
		//filters
		foreach($_POST['filter'] as $idF => $f){
			$idF = addslashes($idF);
			$filterType = getFilter($idF)['type'];
			if($filterType == 1){
				deleteAllFieldValForFilterWithThis($f[0], $id, 1);
				foreach($f as $fs){
					$fs = addslashes($fs);
					addFilterFieldVal($fs, 1, $id, 1);
				}
			}else{
				$f = addslashes($f);
				$field = getFilterFieldVal($f, $id, 1);
				$idFf = (count($field) > 0) ? $field[0]['id'] : -1; 
				if($idFf > 0){
					editFilterFieldVal($idFf, 1);
				}else{
					addFilterFieldVal($f, 1, $id, 1);
				}
				deleteAllFieldValForFilterWithoutThis($f, $id, 1);
			}
		}
	}
	
	if(!(isset($_GET['id']))){
		$id = -1;
		$id_kat = -1;
		$image = "";
		$thumb = "";
		$name = "";
		$adress = "";
		$desc = "";
		$x = "51.7587738";
		$y = "18.0871296";
	}else{
		$id = $_GET['id'];
		$place = getPlace($id);
		$image = $place['img'];
		$id_kat = $place['id_kat'];
		$thumb = $place['thumb'];
		$name = $place['nazwa'];
		$adress = $place['adres'];
		$desc = $place['opis'];
		$x = $place['x'];
		$y = $place['y'];
	}	
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
			<section id="container">
				<input type="file" name="file" id="image" class="btn hide">
				<form action="" id="place-add-form" method="post" onsubmit="return validateForm(event)">
				<section id="top_events">		
					<input type="hidden" name="id" id="place_id" value="<?php echo $id; ?>">
					<h1 id="event_id" data-id="<?php echo $id; ?>"><?php echo ($id>0) ? 'Edycja': 'Dodawanie';?> miejsca</h1>
					<div id="event_right">
						<div id="place_on_map" style="height: 500px;"></div>
					</div>
					<input type="hidden" name="img" id="img" value="">
					<input type="hidden" name="X" id="X" value="0"/>
					<input type="hidden" name="Y" id="Y" value="0"/>
					<input type="hidden" name="W" id="W" value="100"/>
					<input type="hidden" name="changeThumb" id="changeThumb" value="0"/>
					<input type="hidden" name="imgWidth" id="imgWidth" value="0"/>
					<input type="hidden" name="old_image" id="old_image" value="<?php echo $image; ?>"/>
					<input type="hidden" name="old_thumb" id="old_thumb" value="<?php echo $thumb; ?>"/>
					<div id="main_event" class="main_on_eventpage">
						<div class="mainevent_img_on_eventpage">
							<?php echo '<img src="'.$image.'" alt="Nazwa kategorii" id="main_picture"/>'; ?>
							<div class="add-image-cat-place-container">
								<img src="img/add_icon.png" alt="dodaj główne zdjęcie" id="add-main-imange-icon"/>
								<br><br>
								<button class="btn btn-add" id="add_main_image" type="button">Dodaj zdjęcie dla tego miejsca</button>
							</div>
							<button class="btn btn-add" id="add_main_thumb" onclick="startDoThumb(100,100,100)" type="button">Ustaw miniaturę</button>
							<button class="btn btn-cancel" id="stop_main_thumb" onclick="destroyJcrop();" type="button">Zapisz miniaturę</button>
						</div>
						<div class="mainevent_desc">
						    <div class="mainevent-title-box eventpage_desc">
								<div id="id_kat_wrapper">
									<select name="id_kat" id="id_kat">
										<?php
											foreach(getPlaceCats() as $c){
												$selected = $c['id'] == $id_kat ? 'selected' : '';
												echo '<option value="'.$c['id'].'" '.$selected.'>'.$c['name'].'</option>';
											}
										?>
									</select>
								</div>
							</div>
							<div class="mainevent-info-box">
								<input type="hidden" name="ax" id="ax" value="<?php echo $x; ?>"/>
								<input type="hidden" name="ay" id="ay" value="<?php echo $y; ?>"/>
								<h1 class="place-name">
									<p class="error" id="empty-place-name">Podaj nazwę miejsca</p>
									<p class="error" id="ununique-place-name">Miejsce o takiej nazwie już istnieje!</p>
									<input type="text" name="name" class="cat-form-name" value="<?php echo $name; ?>" placeholder="Wpisz nazwę miejsca">
								</h1>
								<h2 class="place-adress">
									<p class="error" id="empty-place-address">Podaj poprawny adres miejsca</p>
									<input type="text" name="adress" id="adress" class="cat-form-name" value="<?php echo $adress; ?>" placeholder="Wpisz adres miejsca">
								</h2>
								<div id="place-desc-fileds">
								</div>
							</div>
						</div>
					</div>
					<div id="main_event_desc">
						<p class="error" id="empty-place-desc">Podaj opis miejsca</p>
						<p><textarea name="opis" placeholder="Opis..." id="place_desc"><?php echo $desc;?></textarea></p>
					</div>
					<div id="place-gallery">
						<button class="btn" type="button" id="add_gallery">Dodaj galerię</button>
					</div>
					<div id="place-filters">
					</div>
				</section>
				<section class="center main-buttons-action" >
					<button class="btn btn-add" id="save-category" type="submit">Zapisz miejsce</button>
					<button class="btn btn-cancel" id="cancel-save-category" type="button">Anuluj</button>
				</section>
				</form>
			</section>
		</div>
		<footer>
			<div class="cont">
				<a href="regulamin.php">Regulamin i polityka prywatno&#347;ci</a>
				<a style="float: right; margin-right: 20px;" href="http:\\www.pinkelephant.pl"> Projekt www.pinkelephant.pl</a>
			</div>
		</footer>
		<div id="confirm-adding-without-image" class="cat-filter-container">
			<div class="vertical-center-wrap">
				<p class="center">
					Nie dodana zdjęcia dla tego miejsca<br>
					Zdjęcie zostanie zastąpione zdjęciem kategorii<br>
					Czy chcesz kontynuować?
					<br><br>
					<button class="btn btn-add" id="yes-add-no-image" onclick="acceptPhotoWarning()">Tak</button>
					<button class="btn btn-cancel" id="back-to-add-image" onclick="hidePhotoWarning()">Nie</button>
				</p>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maps.google.com/maps/api/js?key=AIzaSyDa4nN-bDVonpOyK5S7HAx23krp3ZBRLhE&sensor=false" type="text/javascript"></script>
		<script type="text/javascript" src="js/skrypt_widget.js"></script>
		<script src="js/jquery.Jcrop.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="js/place-scripts.js"></script>
		<script type="text/javascript" src="js/edit-place-scripts.js"></script>
	</body>
</html>	