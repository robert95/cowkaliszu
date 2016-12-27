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
		$per = getPermission($mh, $conn);
	}
	else{//nie zalogowany
		$zalogowany = 0;
		$per = -1;
	}	
	$admin = $per == 2;		
	sqlClose($conn);
	
	$catsList = '';
	$cats = getPlaceCats();
	foreach($cats as $c){
		$link = getLinkToPlaceCat($c['name']);
		$catsList .= '<div class="place-cat-cont col-md-3">
						<div class="place-cat">
							<div class="thumb">
								<a href="'.$link.'">
									<img src="'.$c['thumb'].'" alt="'.$c['name'].'">
								</a>
							</div>
							<p><a href="'.$link.'">'.$c['name'].'</a></p>';
		if($admin) $catsList .= '					<div class="admin-btns">
								<a class="btn" href="dodaj-kategorie-miejsca.php?id='.$c['id'].'">Edytuj</a>
								<a class="btn btn-delete" data-id="'.$c['id'].'">Usuń</a></div>';
		$catsList .= '	</div>
					</div>';
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
		<link rel="stylesheet" href="style/bootstrap.min.css" type="text/css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="style/style.css">
		<link rel="stylesheet" type="text/css" href="style/place-style.css">
		<link rel="stylesheet" type="text/css" href="style/place_css.css">
		<link rel="stylesheet" href="style/jquery.Jcrop.css" type="text/css" />
		<style>
			img[src="http://maps.gstatic.com/mapfiles/api-3/images/mapcnt6.png"] {
				display: none !important;
			}
		</style>
	</head>
	<body id="place-cat-list">
		<?php include 'menu.php'?>
		<div id="top">			
			<section id="container">
				<div class="row">
					<?php if($admin) echo '<a href="dodaj-kategorie-miejsca.php" class="btn">Dodaj nową kategorię miejsc</a>'; ?>
					<a href="edytuj-miejsce.php" class="btn">Dodaj nowe miejsce</a><br><br>
					<?php echo $catsList;?>
				</div>
			</section>
		</div>
		<footer>
			<div class="cont">
				<a href="regulamin.php">Regulamin i polityka prywatno&#347;ci</a>
				<a style="float: right; margin-right: 20px;" href="http:\\www.pinkelephant.pl"> Projekt www.pinkelephant.pl</a>
			</div>
		</footer>				
		<?php if($admin) echo '<div id="confirm_delete">
			<p>Czy na pewno chcesz usunąć tą kategorię?</p>
			<img src="img/confirm_yes.png" class="yes" alt="TAK" onclick="deletePlaceCatFromDB();"/>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<img src="img/confirm_no.png" class="no" alt="NIE" onclick="closeParentDelPlaceCat(this);"/>
		</div>';?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maps.google.com/maps/api/js?key=AIzaSyDa4nN-bDVonpOyK5S7HAx23krp3ZBRLhE&sensor=false" type="text/javascript"></script>
		<script type="text/javascript" src="js/skrypt_widget.js"></script>
		<script src="js/jquery.Jcrop.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<?php if($admin) echo '<script type="text/javascript" src="js/skrypt_editcatplace.js"></script>';?>
	</body>
</html>	