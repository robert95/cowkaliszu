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
	$conn = sqlConnect();
	$place = getPlaceFirstLetter($conn);
	sqlClose($conn);
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
				<h3>Dodaj nowe lub wybierz istniejące już miejsce dla twojego wydarzenia</h1>
				<div class="set_place_containter">
					<div class="search-cont" id="search-cont">
						Wyszukaj<br>
						<input type="text" placeholder="wyszukaj" class="fuzzy-search" name="place"/>
						<ul class="list hidden-list">
							<?php 
								foreach(getAllPlaces() as $p){
									echo '<li><p class="name">'.$p['nazwa'].'</p></li>';
								}
							?>
						</ul>
					</div>
					<p class="add_new_place_link">
						<a href="edytuj-miejsce.php"><img src="img/add_icon.png" alt="Dodaj miejsce"/>  jeśli na liście nie ma miejsca, dla Twojego wydarzenia - możesz dodać własne</a>
					</p>
					<section id="set-place-list">
						<div id="letterFiltr">
							<span class="checkPlaceByLetter" data-check='0'>A</span>
							<span class="checkPlaceByLetter" data-check='0'>B</span>
							<span class="checkPlaceByLetter" data-check='0'>C</span>
							<span class="checkPlaceByLetter" data-check='0'>D</span>
							<span class="checkPlaceByLetter" data-check='0'>E</span>
							<span class="checkPlaceByLetter" data-check='0'>F</span>
							<span class="checkPlaceByLetter" data-check='0'>G</span>
							<span class="checkPlaceByLetter" data-check='0'>H</span>
							<span class="checkPlaceByLetter" data-check='0'>I</span>
							<span class="checkPlaceByLetter" data-check='0'>J</span>
							<span class="checkPlaceByLetter" data-check='0'>K</span>
							<span class="checkPlaceByLetter" data-check='0'>L</span>
							<span class="checkPlaceByLetter" data-check='0'>M</span>
							<span class="checkPlaceByLetter" data-check='0'>N</span>
							<span class="checkPlaceByLetter" data-check='0'>O</span>
							<span class="checkPlaceByLetter" data-check='0'>P</span>
							<span class="checkPlaceByLetter" data-check='0'>R</span>
							<span class="checkPlaceByLetter" data-check='0'>S</span>
							<span class="checkPlaceByLetter" data-check='0'>T</span>
							<span class="checkPlaceByLetter" data-check='0'>U</span>
							<span class="checkPlaceByLetter" data-check='0'>V</span>
							<span class="checkPlaceByLetter" data-check='0'>W</span>
							<span class="checkPlaceByLetter" data-check='0'>X</span>
							<span class="checkPlaceByLetter" data-check='0'>Y</span>
							<span class="checkPlaceByLetter" data-check='0'>Z</span>
							<span class="checkPlaceByLetter" data-check='0'>Ż</span>
							<span class="checkPlaceByLetter" data-check='0'>Ź</span>
						</div>
						<div id="set_place_list_place">
							<?php echo $place;?>
						</div>
					</section>
				</div>
			</section>
		</div>
		<footer>
			<div class="cont">
				<a href="regulamin.php">Regulamin i polityka prywatno&#347;ci</a>
				<a style="float: right; margin-right: 20px;" href="http:\\www.pinkelephant.pl"> Projekt www.pinkelephant.pl</a>
			</div>
		</footer>
		<link rel="stylesheet" type="text/css" href="style/style_calendar.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script type="text/javascript" src="js/skrypt_widget.js"></script>
		<script type="text/javascript" src="js/list.min.js"></script>
		<script type="text/javascript" src="js/fizzy.js"></script>
		<script>
			$( document ).ready(function() {
				activateSearch();
				$(".checkPlaceByLetter").click(function(){
					if($(this).attr('data-check') == "1"){
						$(".checkPlaceByLetter").removeClass('no-active');
						$(".placesLetter a").removeClass('no-active');
						$(this).attr('data-check', 0);
					}else{
						$(".fuzzy-search").val("");
						monkeyList.search();
						$(".checkPlaceByLetter").addClass('no-active');
						$('.checkPlaceByLetter').attr('data-check', 0);
						$(".placesLetter a").addClass('no-active');
						$(this).removeClass('no-active');
						$(this).attr('data-check', 1);
						var letter = $(this).html();
						$(".bold-letter").each(function(){
							if($(this).html() == letter){
								$('html, body').animate({
									scrollTop: $(this).offset().top - 100
								}, 500);
								$(this).siblings("a").removeClass('no-active');
							}
						});
					}
				});
			});
			
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
				resetCheckedLetter();
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
			
			function resetCheckedLetter(){
				$(".checkPlaceByLetter").removeClass('no-active');
				$('.checkPlaceByLetter').attr('data-check', 0);
				$("#set_place_list_place a").removeClass("no-active");
			}
		</script>
	</body>
</html>	