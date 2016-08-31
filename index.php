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

?>
<!doctype html>
<html>
	<head>
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
		<nav id="MENU">
			<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo"/></a>
			<div id="main_menu">
				<ul id="u_menu">
					<li onclick="doKalendarza();"><a>KALENDARZ</a></li>
					<li><a href="mapa.php">MAPA</a></li>
					<!--<li><a href="miejsca.html">MIEJSCA</a></li>-->
				</ul>
			</div>
			<div id="panel">
				<table>
					<tr>
						<td><img src="img/add.png" alt="Dodaj" id="add_img"/></td>
						<td class="add" onclick="window.location.href = 'addevent_1.php';"><a href="addevent_1.php">DODAJ WYDARZENIE</a></td>
						<?php
							if($zalogowany == 1)
							{
								echo '<td><a href="setting.php"><img src="img/avatar.png" alt="nick" id="avatar"/></a></td>
									<td><a href="setting.php">'.$user["login"].'</a></td>';
							}
							else{
								echo '<td class="zaloguj"><a href="login.php">Zaloguj</a></td>
									<td class="zarejestruj"><a href="zarejestruj.php">Zarejestruj</a></td>';
							}
						?>
						</tr>
				</table>
			</div>
			<div style="clear: both;"></div>
			<!--<div id="categories_list">
				<ul>
				<li data-id="0">wszystkie wydarzenia</li>
				<?php //echo $catLi; ?>
				</ul>
			</div>-->
		</nav>
		<nav id="MENU-FIX">
			<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo_fix"/></a>
			<div id="main_menu_fix">
				<ul id="u_menu_fix">
					<li onclick="doKalendarza();"><a>KALENDARZ</a></li>
					<li><a href="mapa.php">MAPA</a></li>
					<!--<li><a href="miejsca.html">MIEJSCA</a></li>-->
				</ul>
			</div>
			<div id="panel_fix">
				<table>
					<tr>
						<td><img src="img/add.png" alt="Dodaj" id="add_img_fix"/></td>
						<td class="add" onclick="window.location.href = 'addevent_1.php';"><a href="addevent_1.php">DODAJ WYDARZENIE</a></td>
						<?php
							if($zalogowany == 1)
							{
								echo '<td><a href="setting.php"><img src="img/avatar.png" alt="nick" id="avatar_fix"/></a></td>
									<td><a href="setting.php">'.$user["login"].'</a></td>';
							}
							else{
								echo '<td class="zaloguj"><a href="login.php">Zaloguj</a></td>
									<td class="zarejestruj"><a href="zarejestruj.php">Zarejestruj</a></td>';
							}
						?>
						</tr>
				</table>
			</div>
			<div style="clear: both;"></div>
		</nav>
		<nav id="mobile_main_nav">
			<a href="index.php"><img src="img/logo.png" alt="wkaliszu" id="logo"/></a>
			<img src="img/menu_mobile.png" alt="Rozwiń menu" id="show_mobile_menu"/>
			<table>
				<tr><td onclick="doKalendarza();"><a>KALENDARZ</a></td></tr>
				<tr><td><a href="mapa.php">MAPA</a></td></tr>
				<tr><td><a href="addevent_1.php">DODAJ WYDARZENIE</a></td></tr>
				<?php
					if($zalogowany == 1)
					{
						echo '<tr><td><a href="setting.php">'.$user["login"].'</a></td></tr>';
					}
					else{
						echo '<tr><td><a href="login.php">ZALOGUJ</a></td></tr>
							<tr><td><a href="zarejestruj.php">ZAREJESTRUJ</a></td></tr>';
					}
				?>
			</table>
		</nav>
		<div id="top">
			<section id="container">
				<section id="top_events">
					<h1>WYDARZENIA - sprawdź co dzieje się na mieście</h1>
					<?php if(!$edycja && $admin!=0) echo '<a href="index.php?editMainImage=jestemsuperadminemimogetakieroznebajery" id="goToEditMainImage">Zmień kadr zdjęcia</a>'; ?>
					<?php if($edycja) echo '<p><input style="background: red " class="savePosition btn" type="button" name="submit" value="Zapisz pozycje zdjęcia"/></p>';?>
					<?php echo $mainEvent; ?>
					<section id="s_popular">
						<h3>popularne</h3>
						<?php echo $popularEvent; ?>
					</section>
					<div style="clear: both;"></div>
				</section>
				<section id="recommend_events">
					<h3>POLECANE WYDARZENIA</h3>
					<?php echo $recEvent; ?>
					<div style="clear: both;"></div>
				</section>
				<section id="event_calendar">
				<div id="datepicker"></div>
					<h3>KALENDARZ - zobacz, co dzieje się w wybranym dniu</h3>
					<div id="container_calendar">
						<div id="category-list">
						<div class="cat_icon cat_ch" data-check="1" data-id="-1" id="k_all">
							<img src="img/all_cat.png" alt="wszystkie" />
							<p>wszystkie</p>
						</div>
						<?php echo categoriesAsIcon(); ?>
						</div>
						<div id="calendar">
							<div id="calendar_menu">
								<!--<img class="b_k_s" src="img/show_cat.png" id="list_of_cat"/>-->
								<!--<span class="b_k_s">kategorie</span>-->
								<p><span id="mmYY">sierpien 2015</span> <img src="img/show_cal.png" onclick="pokaz();"/></p>
							</div>
							<div id="switcher_date">
								<a class="nextDay" onclick="odswiezSwitcher(false);"><</a>
								<a>23</a>
								<a>24</a>
								<a>25</a>
								<a>26</a>
								<a>27</a>
								<a>28</a>
								<a>29</a>
								<a class="prevDay" onclick="odswiezSwitcher(true);">></a>
							</div>
							<div id="checklist_categorie">
								<div style="display:none;" class="cat_on_list">
									<img src="img/pic_cat.png" alt="nazwa kategorii" />
									<span>wszystkie</span>
									<img class="cat_ch" data-check="1" data-id="-1" src="img/checked.png" alt="pokaż/ukryj" id="k_all"/>
								</div>
								<?php //echo $catDiv; ?>
							</div>
							<div id="events">		
								<div id="switcher_event">
									<a>1</a>
									<a>2</a>
									<a>3</a>
									<a>></a>
								</div>
							</div>
						</div>
						<div id="stick_map" class="">
							<div id="map">
							</div>
						</div>
					</div>
				</section>
			</section>
			<footer>
				<a href="regulamin.php">Regulamin i polityka prywatno&#347;ci</a>
				<a style="float: right; margin-right: 20px;" href="http:\\www.pinkelephant.pl"> Projekt www.pinkelephant.pl</a>
			</footer>
		</div>
		<link rel="stylesheet" type="text/css" href="style/style_calendar.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="https://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
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