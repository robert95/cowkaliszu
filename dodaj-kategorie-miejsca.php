<?php
	include_once 'mysql.php';
	include_once 'function.php';
	cleanEventSession();
	if($_POST){
		$id = $_POST['id'];
		$name = $_POST['name'];
		if($_POST['img'] && $_POST['img'] != ""){
			$image = savePhotoFromBase64($name, $_POST['img']);
			$thumb = saveThumbPhoto($name, $image, $_POST['imgWidth']);
		}else{
			if($_POST['changeThumb']){
				$thumb = saveThumbPhoto($name, $_POST['old_image'], $_POST['imgWidth']);
			}else{
				$thumb = $_POST['old_thumb'];
			}
			$image = $_POST['old_image'];
		}
		$id_gallery = -1;
		$comments = $_POST['commments'];
		
		if($id == -1 )$id = addPlaceCat($name, $image, $thumb, $id_gallery, $comments);
		else editPlaceCat($id, $name, $image, $thumb, $id_gallery, $comments);
		
		//ratings fileds	
		$ratIds = [];
		for($i = 0 ; $i < count($_POST['rating']); $i+=2){
			$idR = $_POST['rating'][$i];
			$nameR = $_POST['rating'][$i+1];
			if($idR == -1) $ratIds[] = addRating($nameR, $id, 1);
			else{
				$ratIds[] = $idR;
				editRating($idR, $nameR, $id, 1);
			}
		}
		deleteAllRatingFieldNotYetForParent($ratIds, $id);
		setOrder("rating", $ratIds);
		//end ratings
		//desc fileds	
		$descIds = [];
		$descStaticIds = [];
		$staticFields = getStaticFieldForParent($id, 1);
		$staticFieldsIds = [];
		foreach($staticFields as $sf) $staticFieldsIds[] = $sf['id_field'];
		for($i = 0 ; $i < count($_POST['field']); $i+=2){
			$idD = $_POST['field'][$i];
			$nameD = $_POST['field'][$i+1];
			if($nameD == "static"){
				if(!in_array($idD, $staticFieldsIds))addStaticField($idD, $id, 1);
				$descStaticIds[] = $idD;
			}else{
				if($idD == -1) $descIds[] = addDescField($nameD, $id, 1);
				else{
					$descIds[] = $idD;
					editDescField($idD, $nameD, $id, 1);
				}
			}
		}
		deleteAllDescFieldNotYetForParent($descIds, $id);
		deleteAllStaticDescFieldNotYetForParent($descStaticIds, $id);
		setOrder("desc_field", $descIds);
		//end descfileds
		//filters
		setUnsignedFilterToParent($id);
		//end filters
		header("LOCATION: /miejsca.php");	
	}else{
		deleteAllEmptyFilter();
		deleteAllEmptyFilterField();
		deleteAllEmptyDescField();
		deleteAllEmptyStaticField();
		deleteAllEmptyRating();
	}
	
	$conn = sqlConnect();
	
	$image = "";
	if(!(isset($_GET['id']))){
		$id = -1;
		$image = "";
		$thumb = "";
		$name = "";
		$comment = 0;
		$commentClass = "on";
	}else{
		$id = $_GET['id'];
		$catPlace = getPlaceCat($id);
		$image = $catPlace['image'];
		$thumb = $catPlace['thumb'];
		$name = $catPlace['name'];
		$comment = $catPlace['comments'];
		$commentClass = $comment==0 ? 'on': 'off';
	}	
	
	$ratingsField = "";
	$ratingsFieldInput = "";
	$ratings = getRatingForParent($id , 1);
	foreach($ratings as $r){
		$ratingsField .= '<li data-id="'.$r['id'].'">'.$r['name'].'</li>';
		$ratingsFieldInput .= '<input type="hidden" name="rating[]" value="'.$r['id'].'">';
		$ratingsFieldInput .= '<input type="hidden" name="rating[]" value="'.$r['name'].'">';
	}
	
	$filter = "";
	$filters = getFiltersForParent($id , 1);
	foreach($filters as $f){
		$type = $f['type'] == 1 ? "checkbox" : "radio";
		$filter .= '<li>
			<p class="filter-elem" data-id="'.$f['id'].'" data-type="'.$f['type'].'" data-name="'.$f['name'].'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> '.$f['name'].' <span class="type-filter">('.$type.')</span> <span class="span-btn edit-btn-filter">edytuj</span><span class="span-btn span-btn-del del-filter" data-id="'.$f['id'].'">usuń</span></p>
			<ul class="">';
		$filterFields = getFiltersForFilter($f['id']);
		foreach($filterFields as $ff){
			$filter .= '<li data-idP="'.$ff['id_parent_field'].'" data-id="'.$ff['id'].'">'.$ff['name'].'</li>';
		}
		$filter .= '</ul>
		</li>';	
	}
	
	$descField = "";
	$descFieldInput = "";
	$descfields = getDescFieldForParent($id , 1);
	$descStaticfields = getStaticFieldForParent($id , 1);
	$staticFieldCheckedIDS = [];
	foreach($descStaticfields as $r){
		$staticFieldCheckedIDS[] = $r['id_field'];
		$descField .= '<li data-id="'.$r['id_field'].'">static</li>';
		$descFieldInput .= '<input type="hidden" name="field[]" value="'.$r['id_field'].'">';
		$descFieldInput .= '<input type="hidden" name="field[]" value="static">';
	}
	foreach($descfields as $r){
		$descField .= '<li data-id="'.$r['id'].'">'.$r['name'].'</li>';
		$descFieldInput .= '<input type="hidden" name="field[]" value="'.$r['id'].'">';
		$descFieldInput .= '<input type="hidden" name="field[]" value="'.$r['name'].'">';
	}
	
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
	
	if($per < 2){
		header("LOCATION: /");		
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
		<link rel="stylesheet" href="style/jquery.Jcrop.css" type="text/css" />
		<style>
			img[src="http://maps.gstatic.com/mapfiles/api-3/images/mapcnt6.png"] {
				display: none !important;
			}
		</style>
	</head>
	<body id="place-main-container">
		<?php include 'menu.php'?>
		<div id="top">			
			<section id="container">
				<input type="file" name="file" id="image" class="btn hide">
				<form action="" method="post">
				<section id="top_events">
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<h1 id="event_id" data-id="<?php echo $id; ?>">Dodawanie kategorii miejsca</h1>
					<input type="hidden" name="img" id="img" value="">
					<input type="hidden" name="X" id="X" value="0"/>
					<input type="hidden" name="Y" id="Y" value="0"/>
					<input type="hidden" name="W" id="W" value="1000"/>
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
								<button class="btn btn-add" id="add_main_image" type="button">Dodaj zdjęcie dla tej kategorii</button>
							</div>
							<button class="btn btn-add" id="add_main_thumb" onclick="startDoThumb(100,100,100)" type="button">Ustaw miniaturę</button>
							<button class="btn btn-cancel" id="stop_main_thumb" onclick="destroyJcrop();" type="button">Zapisz miniaturę</button>
						</div>
						<div class="mainevent_desc place_cat_desc">
						    <div class="mainevent-title-box eventpage_desc">
								<input type="text" name="name" class="cat-form-name" value="<?php echo $name; ?>" placeholder="Wpisz nazwę kategorii">
							</div>
							<div class="mainevent-info-box">
								<h1>Nazwa miejsca</h1>
								<h2>adres miejsca</h2>
								<div id="desc-fileds" class="btn-opts-in-desc">
									<p id="add-desc-field"><img src="img/add_icon.png" alt="Dodaj pole"> dodaj pola</p>
									<ul id="desc-fields-ul" class="hide">
										<?php echo $descField; ?>
									</ul>
									<div id="desc-hidden-fields" class="hide">
										<?php echo $descFieldInput; ?>
									</div>
								</div>
								<div id="ratings-fileds" class="btn-opts-in-desc">
									<p id="add-rating-field"><img src="img/add_icon.png" alt="Dodaj pole"> dodaj możliwość oceny</p>
									<ul id="rating-fields-ul">
										<?php echo $ratingsField; ?>
									</ul>
									<div id="rating-hidden-fields">
										<?php echo $ratingsFieldInput; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="event_right"></div>
					<div id="main_event_desc">
						<p>Opis</p>
						<div class="add-filters-container">
							<div class="cat-filter">
								<div class="cat-filter-list">
									<ul class="cat-filter-list-ul">
										<?php echo $filter; ?>
									</ul>
								</div>
							</div>
							<div class="add-edit-filter">
								<h5>Dodaj/edytuj filtr</h5>
								<input type="hidden" name="filter-id" id="filter-id" value="-1">
								<div class="main-settings-filter">
									<input type="text" class="cat-form-name" name="filter-name" id="filter-name" value="" placeholder="wpisz nazwę filtra">
									<label class="rad">
										<input type="radio" name="type" class="filter-type" id="filter-checkbox" value="1"/>
										<i></i> checkbox
									</label>
									<label class="rad">
										<input type="radio" name="type" class="filter-type" id="filter-radio" value="2"/>
										<i></i> radio
									</label>
								</div>
								<div class="edit-filer-fields-list">
									<input type="text" class="cat-form-name" name="filter-field-name[]" data-id="-1" value="" placeholder="Dodaj opcję dla tego filtra">
								</div>	
								<p id="add-new-filter-filed">dodaj następną pozycję</p>
								<button class="btn btn-add" type="button" id="save-edit-filter">Zapisz</button>
								<button class="btn btn-cancel" type="button" id="cancel-edit-filter">Anuluj</button>	
							</div>
						</div>
						<input type="hidden" id="input_comments" name="commments" value="<?php echo $comment; ?>">
						<div id="comment-swicher-container" class="comment-<?php echo $commentClass; ?>">
							<button class="btn btn-add comments-swicher comment-on-btn" type="button">Włącz komentarze</button>
							<button class="btn btn-add comments-swicher comment-off-btn" type="button">Wyłącz komentarze</button>
						</div>
					</div>
				</section>
				<section class="center main-buttons-action" >
					<button class="btn btn-add" id="save-category" type="submit">Zapisz kategorię</button>
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
		<div id="confirm_delete">
			<p>Czy na pewno chcesz usunąć ten filtr?</p>
			<img src="img/confirm_yes.png" class="yes" alt="TAK" onclick="deleteFiltr();"/>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<img src="img/confirm_no.png" class="no" alt="NIE" onclick="closeParent(this);"/>
		</div>
		<div id="edit-desc-fields-panel" class="fixed-center-panel">
			<img src="img/confirm_no.png" alt="Zamknij" class="close-panel" id="close-panel-desc-edit">
			<h1>Zaznacz pola które chcesz dodać</h1>
			<div class="static-desc-field-list">
				<label><input type="checkbox" <?php if(in_array(1, $staticFieldCheckedIDS)) echo "checked";?> class="checkbox" name="desc-field-check[]" value="1"> numer telefonu</label>
				<label><input type="checkbox" <?php if(in_array(2, $staticFieldCheckedIDS)) echo "checked";?> class="checkbox" name="desc-field-check[]" value="2"> adres e-mail</label>
				<label><input type="checkbox" <?php if(in_array(3, $staticFieldCheckedIDS)) echo "checked";?> class="checkbox" name="desc-field-check[]" value="3"> strona www</label>
				<label><input type="checkbox" <?php if(in_array(4, $staticFieldCheckedIDS)) echo "checked";?> class="checkbox" name="desc-field-check[]" value="4"> godziny otwarcia</label>
				<div class="desc-filed-sortable-container">
				</div>
			</div>
			<div class="add-new-desc-field">
				<h1>Dodaj nowe pole</h1>
				<input type="text" class="cat-form-name" id="new-field" value="" placeholder="Dodaj nowe pole"> <button type="button" class="btn save-new-field-btn" id="save-new-field-btn">Dodaj</button>
			</div>	
			<button id="save-fields" class="btn btn-save-panel">Zapisz</button>
		</div>
		<div id="edit-rating-fields-panel" class="fixed-center-panel">
			<img src="img/confirm_no.png" alt="Zamknij" class="close-panel" id="close-panel-rating-edit">
			<h1>Wprowadź kryteria oceniania</h1>
			<div class="rating-field-list">
			</div>
			<input type="text" class="cat-form-name" id="new-rating-field" value="" placeholder="Dodaj nowe pole"> 
			<button type="button" class="btn save-new-field-btn" id="add-new-rating-field-btn">Dodaj następne kryterium</button>
			<button id="save-ratings-fields" class="btn btn-save-panel">Zapisz</button>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maps.google.com/maps/api/js?key=AIzaSyDa4nN-bDVonpOyK5S7HAx23krp3ZBRLhE&sensor=false" type="text/javascript"></script>
		<script type="text/javascript" src="js/skrypt_widget.js"></script>
		<script src="js/jquery.Jcrop.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="js/place-scripts.js"></script>
		<?php if($per >=1 ) echo '<script type="text/javascript" src="js/skrypt_editcatplace.js"></script>';?>
	</body>
</html>	