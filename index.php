<?php
	include_once 'mysql.php';
	include_once 'function.php';
	$conn = sqlConnect();
	
	//$catLi = categoriesAsLi($conn);
	$catDiv = categoriesAsDivList($conn);
	$popularEvent = getPopularEvent($conn);
	$recEvent = getRecomendateEvent($conn);
	$mainEvent = getMainEvent($conn);
	$admin = 0;
	if(isset($_COOKIE['stmh']))
	{//zalogowany
		$zalogowany = 1;
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		//var_dump($user);
		$admin = getPermission($mh, $conn);
	}
	else{//nie zalogowany
		$zalogowany = 0;
	}
	
	sqlClose($conn);
	$edycja = 0;
	if ( isset( $_GET['editMainImage'] ) && !empty( $_GET['editMainImage'] ) ){
		if($_GET['editMainImage'] == "jestemsuperadminemimogetakieroznebajery")
			$edycja = 1;
		else $edycja = 0;
	}
/*filtrowanie*/	
	$filterCats = array();
	$filterData = "";
	$filterPrice = array();
	if(isset($_GET['url']))	{
		$arr = explode('/',$_GET['url']);
		for ($i=0; $i < count($arr); $i+=2){
			$k = $arr[$i]; //nazwa parametru
			$v = isset($arr[$i+1]) ? $arr[$i+1] : ''; //wartość parametru
			if($k == "kategoria"){
				$filterCats = explode('-',$v);
			}else if($k == "data"){
				$filterData = $v;
			}else if($k == "cena"){
				$filterPrice = explode('-',$v);
			}
		}
	}
/*filtrowanie*/	
?>
<!doctype html>
<html>
	<head>
		<base href="/cowkaliszu/"/>
		<meta charset="UTF-8">
		<meta name="description" content="Znajdź interesujące Cię wydarzenia w Kaliszu i dodawaj do ulubionych. Sprawdź miejsca spotkań. Wiem co.wkaliszu.pl - rozrywka, film, turystyka, koncerty, muzyka, sztuka, dla dzieci, sport, teatr i inne.">
        <meta name="keywords" content="wydarzenia w Kaliszu, Kalisz, koncerty, filmy, kino, kino Helios, kino Cinema 3D, teatr, teatr im. Bogusławskiego, rozrywka">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Kalisz, wydarzenia i miejsca spotkań - wiem co.wkaliszu.pl</title>
		
		<link rel="stylesheet" type="text/css" href="style/style.css">
                <script>
                  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                 ga('create', 'UA-45313537-1', 'auto', {'allowLinker': true});
                 ga('require', 'linker');
                 ga('linker:autoLink', ['wkaliszu.pl'] );
                 ga('send', 'pageview');

                </script>
	</head>

	<body>
		<?php include 'menu.php'?>
		<div id="top">
			<section id="container">
				<section id="top_events">
					<h1>WYDARZENIA - sprawdź co dzieje się na mieście</h1>
					<?php if(!$edycja && $admin!=0) echo '<a href="index.php?editMainImage=jestemsuperadminemimogetakieroznebajery" id="goToEditMainImage">Zmień kadr zdjęcia</a>'; ?>
					<?php if($edycja) echo '<p><input style="background: red " class="savePosition btn" type="button" name="submit" value="Zapisz pozycje zdjęcia"/></p>';?>
					<div class="flex-box">
						<?php echo $mainEvent; ?>
						<section id="s_popular">
							<h3>popularne</h3>
							<?php echo $popularEvent; ?>
						</section>
					</div>
					<div style="clear: both;"></div>
				</section>
				<section id="recommend_events">
					<h3>POLECANE WYDARZENIA</h3>
					<?php echo $recEvent; ?>
					<div style="clear: both;"></div>
				</section>
				<section id="event_calendar">
					<h3>KALENDARZ - zobacz, co dzieje się w wybranym dniu</h3>
					<div id="container_calendar">
						<div class="event-list-in-calendar">
							<div class="filter-in-main filter-container">
								<div class="open-filter-panel show-cat-filter-btn">
									<span>Filtruj wydarzenia</span> <img src="img/arrow-down.png" alt="filtruj wydarzenia">
								</div>
								<div class="open-filter-panel data-filtr-container data-filter-btn">
									<span onclick="pokaz();">Wybierz dzień</span> <img src="img/show-cal.png" alt="Wybierz dzień" onclick="pokaz();">
									<div id="datepicker"></div>
									<input type="hidden" id="filterData" value="<?php echo $filterData; ?>">
								</div>
								<div class="open-filter-panel price-filtr-container">
									<?php echo pricesAsListToFilter($filterPrice); ?>
								</div>
								<div class="list-of-activ-kategory">
								</div>
							</div>
							<div class="event-list">
							</div>
							<p class="day-header no-events">Przykro nam, nie ma wydarzeń spełniających Twoje kryteria:(</p>
							<div class="loading-panel">
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
				<div class="filter-elem cat-filtr-container extendend-filter">
					<p>Co cię interesuje?</p>
					<?php echo categoriesAsListToFilter($filterCats); ?>
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
		<script type="text/javascript" src="js/skrypt_thumb.js"></script>
		<script type="text/javascript" src="js/skrypt_calendar.js"> </script>
		<script type="text/javascript" src="js/skrypt_filtr.js"></script>
		<script type="text/javascript" src="js/skrypt_liked.js"></script>
		<script type="text/javascript" src="js/skrypt_widget.js"></script>
		<script type="text/javascript" src="js/scripts_mobile.js"></script>
		<?php if($edycja) echo'<script type="text/javascript" src="js/superTajnySkryptTylkoDlaAdmina.js"></script>';?>
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