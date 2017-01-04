<?php
	include_once 'mysql.php';
	
	function saveIconCategory($nazwakat)
	{
		$target_file = 'upload/'.$nazwakat.'.png';
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ){
			return 0;
		}
		move_uploaded_file($_FILES["icon"]["tmp_name"], $target_file);
		return $target_file;
	}
	
	function addCategory($conn)
	{
		$nazwa = $_POST['name'];
		$plik = saveIconCategory($nazwa);
		addCategorytoDB($nazwa, $plik, $conn);
	}
	
	function editCategory($conn)
	{
		$id = $_POST['id'];
		$nazwa = $_POST['name'];
		$plik = "img/".$_POST['old_name'].".png";
		if($_FILES['icon']['error'] == 0){
			echo "jestem";
			unlink($plik);
			$plik = saveIconCategory($nazwa);
		}
		editCategoryinDB($id, $nazwa, $plik, $conn);
	}
	
	function saveMainImageEvent($id)
	{
		$target_file = 'upload/em_'.$id.'.png';
		/*$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ){
			return 0;
		}*/
		move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
		return $target_file;
	}	
	
	function savePhotoFromBase64($name, $data){
		$name = generatePhotoName($name);
		$target_file = 'upload/'.$name.'.png';
		$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));
		file_put_contents($target_file, $data);
		return $target_file;
	}	
	
	function savePhotoFromBlob($name, $file){
		$name = generatePhotoName($name);
		$target_file = 'upload/'.$name.'.png';
		move_uploaded_file($file["tmp_name"], $target_file);
		return $target_file;
	}
	
	function generatePhotoName($name){
		$slug = makeSlug($name);
		return uniqid().'-'.$slug;
	}
	
	function saveThumbPhoto($name, $image, $width){
		$targ_w = $targ_h = 400;
		$jpeg_quality = 9;
		$name = generatePhotoName($name);
		$target_file = 'upload/'.$name.'.png';
		$img_r = imagecreatefromstring(file_get_contents($image));
		$x = $_POST['X'];
		$y = $_POST['Y'];
		$w = $_POST['W'];
		$realW = imagesx($img_r);
		$realY = imagesy($img_r);
		$w_pom = 650;
		$h_pom = intval(($realW*$realY)/$realW);
		if(intval($w) > 651){
			if($realW >= $realY){
				$x = intval((650 - ((650*$realY)/$realW))/2);
				$y = 0;
				$w = intval(650 - $x*2);
			}else{
				$x = intval(($h_pom - ((650*$realY)/$realW))/2);
				$x = 0;
				$w = 650;
			}
		}
		$p = $realW/$width;
		$x *= $p;
		$y *= $p;
		$w *= $p;
		
		//die;
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,
			$targ_w,$targ_h,$w,$w);
		imagepng($dst_r, $target_file, $jpeg_quality);
		return $target_file;
	}
	
	function saveIconEvent($id, $image, $width)
	{
		$targ_w = $targ_h = 300;
		$jpeg_quality = 9;
		$target_file = 'upload/e_'.$id.'.png';
		
		$img_r = imagecreatefromstring(file_get_contents($image));
		
		$x = $_POST['X'];
		$y = $_POST['Y'];
		$w = $_POST['W'];
		$realW = imagesx($img_r);
		$realY = imagesy($img_r);
		
		$p = $realW/$width;
		$x *= $p;
		$y *= $p;
		$w *= $p;
		
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,
			$targ_w,$targ_h,$w,$w);

		imagepng($dst_r, $target_file, $jpeg_quality);
		
		return $target_file;
	}
	
	/*function addEvent($conn)
	{
		$name = $_SESSION["name"];
		$id_kat = $_SESSION["id_kat"];
		$rec = $_SESSION["recommend"];
		$thumb = $_SESSION["photo"];
		$data = $_SESSION["data"]; 
		$time = $_SESSION["time"];
		$data_end = $_SESSION["data_end"]; 
		$time_end = $_SESSION["time_end"];
		$desc = $_SESSION["desc"];
		$id_place = $_SESSION["id_place"];
		$views = 0;
		$like = 0;
		$comments = 0;
		$image = $_SESSION["mainphoto"];;
		$id_user = $_COOKIE['stmh'];
		$group = $_SESSION["group"];
		$www = $_SESSION['www'];
		$yt = $_SESSION['yt'];
		$price = $_SESSION["price"];
		$desc_img = $_SESSION["desc_img"];
		addEventDB($conn, $name, $id_kat, $thumb, $image, $rec, $data, $time, $data_end, $time_end, $desc, $id_place, $views, $comments, $like, $id_user, $group, $www, $yt, $price, $desc_img);
		sendAfterNewEventMailToOwner($_SESSION["id"], $name);
		doSiteMap();
	}*/
	
	function editEvent($conn)
	{
		$name = addslashes($_POST['name']); 
		$id_kat = addslashes($_POST['categorie_of_edit_event']); 
		
		if(isset($_POST['recomendate']))
		{
			$rec = 1;
		}
		else {
			$rec = 0;
		}
		
		$thumb = $_POST['old_name'];
		//if(($_FILES['image']['error'] == 0)){
		//	if(file_exists($thumb))	unlink($thumb);
		//	$thumb = saveIconEvent($_SESSION["id"]);
		//}
		
		$image = $_POST['old_nameMAIN'];
		if(($_FILES['image']['error'] == 0)){
			if(file_exists($image)) unlink($image);			
			$image = saveMainImageEvent($_SESSION["id"]);
			$thumb = saveIconEvent($_SESSION["id"], $image, 250);
		}
		
		$data = addslashes($_POST['date']); 
		$time = addslashes($_POST['time']); 
		$data_end = addslashes($_POST['date']); 
		$time_end = addslashes($_POST['time_end']); 
		$desc = addslashes($_POST['desc']); 
		$id_place = addslashes($_POST['place']);
		$www = $_POST['www'];
		$yt = $_POST['yt'];
		$price = $_POST["price"];
		if($price == "") $price = 0; 
		$desc_img = $_POST["desc_img"];
		
		editEventDB($_SESSION["id"], $conn, $name, $id_kat, $thumb, $image, $rec, $data, $time, $data_end, $time_end, $desc, $id_place, $www, $yt, $price, $desc_img);
	}
	
	function addPlace($conn)
	{
		$name = htmlspecialchars($_POST["name_of_new_place"]);
		$id_kat = htmlspecialchars($_POST['categorie_of_new_place']); 
		$opis = htmlspecialchars($_POST['desc']);
		$x = htmlspecialchars($_POST['x']);
		$y = htmlspecialchars($_POST['y']);
		$adress = htmlspecialchars($_POST['adress_of_new_event']); 

		addPlaceDB($conn, $name, $id_kat, $opis, $x, $y, $adress);
	}
	
	function getPolishDayName($a)
	{
		$names = ['Pon','Wt','Śr','Czw','Pt','So','N'];
		return $names[$a-1];
	}
	
	function getFullPolishDayName($a)
	{
		$names = ['Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota','Niedziela'];
		return $names[$a-1];
	}
	
	function getPolishMonthName($a)
	{
		$names = ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'];
		return $names[$a-1];
	}
	
	function sendAfterRegisterMail($user, $pass)
	{
		$naglowki = "Reply-to: rejestracja@co.wkaliszu.pl <rejestracja@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "From: rejestracja@co.wkaliszu.pl <rejestracja@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "MIME-Version: 1.0".PHP_EOL;
	    $naglowki .= "Content-type: text/html; charset=UTF-8".PHP_EOL; 
	 
		//Wiadomość najczęściej jest generowana przed wywołaniem funkcji
		$wiadomosc = '<html> 
		<head> 
			<meta charset="UTF-8" />
			<title>Potwierdzenie rejestracji</title> 
		</head>
		<body>
			<p><b>Witaj, '.$user['login'].'</b></p>
			<p>Dziękujemy za rejestracje na co.wkaliszu.pl<br>
			<br>
			Twoje dane:<br>
			login: '.$user['login'].'<br>
			hasło: '.$pass.'</p>
			<br><br>
			Pozdrawiamy<br>
			Ekipa co.wkaliszu.pl
		</body>
		</html>';
	  
		mail($user['mail'], 'Dziękujemy za rejestracje na co.wkaliszu.pl', $wiadomosc, $naglowki);
	}
	
	function zaloguj($l, $hp, $conn)
	{
		//ustawiamy cookie na mailhash
		//jeżeli dane są dobre to ustawiamy ciasteczko stmh-super tajny mail hash, które ma mail_hash
		$mh = checkLogin($l, $hp, $conn); //zwraca mail_hash jak dobre dane albo 'lipa' jak źle
		if($mh != 'lipa'){
			$active = isActiveLogin($mh, $conn);
			if($active == 1)
			{
				setcookie('stmh', $mh,  time() + (86400 * 30));
				$_SESSION["log"] = 1;
				return 1;
			}
			else return -1;
			
		}
		return 0;
	}
	
	function normalMenu()
	{	
		echo '<table>
					<tr>
						<td class="nav_panel_logo"><a href="index.php"><img src="img/p_logo.png" alt="wkaliszu" id="logo_fix"/></a></td>
						<td id="addEvent" onclick="window.location.href = \'addevent_1.php\';"><img src="img/p_plus.png" alt="Dodaj wydarzenie"/> <a href="addevent_1.php">Dodaj wydarzenie</a></td>
						<td id="events" onclick="window.location.href = \'zapisanewydarzenie.php\';"><img src="img/p_saved.png" alt="Kategorie"/> <a href="zapisanewydarzenie.php">Moje wydarzenia</a></td>
						<td id="liked" onclick="window.location.href = \'ulubione.php\';"><img src="img/p_liked.png" alt="Kategorie"/> <a href="ulubione.php">Ulubione</a></td>
						<td id="setting" onclick="window.location.href = \'setting.php\';"><img src="img/p_setting.png" alt="Kategorie"/> <a href="setting.php">Ustawienia konta</a></td>
						<td class="logout" onclick="window.location.href = \'logout.php\';"><img src="img/p_logout.png" alt="Kategorie"/></td>
					</tr>
				</table>';
	}

	function adminMenu()
	{
		echo '<table>
					<tr>
						<td class="nav_panel_logo"><a href="index.php"><img src="img/p_logo.png" alt="wkaliszu" id="logo_fix"/></a></td>
						<td id="addKat" onclick="window.location.href = \'dodajkategorie.php\';"><img src="img/p_plus.png" alt="Dodaj kategorie"/> <a href="dodajkategorie.php">Dodaj kategorię</a></td>
						<td id="addEvent" onclick="window.location.href = \'addevent_1.php\';"><img src="img/p_plus.png" alt="Dodaj wydarzenie"/> <a href="addevent_1.php">Dodaj wydarzenie</a></td>
						<td id="users" onclick="window.location.href = \'users.php\';"><img src="img/p_saved.png" alt="Użytkownicy"/> <a href="users.php">Użytkownicy</a></td>
						<td id="events" onclick="window.location.href = \'zapisanewydarzenie.php\';"><img src="img/p_saved.png" alt="Kategorie"/> <a href="zapisanewydarzenie.php">Zapisane wydarzenie</a></td>
						<td id="setting" onclick="window.location.href = \'setting.php\';"><img src="img/p_setting.png" alt="Kategorie"/> <a href="setting.php">Ustawienia konta</a></td>
						<td class="logout" onclick="window.location.href = \'logout.php\';"><img src="img/p_logout.png" alt="Kategorie"/></td>
					</tr>
				</table>';
	}
	
	function isOwner($id,$conn)
	{
		$event = getEvent($conn, $id);
		$mh = $_COOKIE['stmh'];
		if($event['id_user'] == $mh || getPermission($mh, $conn) == 1 || getPermission($mh, $conn) == 2)
			return 1;
		else return 0;
	}
	
	function convertToCoolDate($data){
		$day = substr($data, 8, 10);
		$month = substr($data, 5, 7);
		return $day." ".getPolishMonthName($month);
	}
	
	function isArchive($data, $czas){
		return (time() > strtotime($data.' '.$czas));	
	}
	
	function sendInfoEmailToOwner($id, $con){
		$event = getEvent($con, $id);
		$user = getUser($event['id_user'], $con);
		$link = linkToEvent($id);
		
		$naglowki = "Reply-to: info@co.wkaliszu.pl <info@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "From: info@co.wkaliszu.pl <info@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "MIME-Version: 1.0".PHP_EOL;
	    $naglowki .= "Content-type: text/html; charset=UTF-8".PHP_EOL; 
	 
		//Wiadomość najczęściej jest generowana przed wywołaniem funkcji
		$wiadomosc = '<html> 
		<head> 
			<meta charset="UTF-8" />
			<title>Akceptacja Twojego wydarzenia</title> 
		</head>
		<body>
			<p><b>Witaj, '.$user['login'].'</b></p>
			<p>Twoje wydarzenie "'.$event['nazwa'].'" właśnie zostało zaakceptowane przez administratora<br>
			Możesz je zobaczyć pod adresem:
			<a href="https://co.wkaliszu.pl/'.$link.'">https://co.wkaliszu.pl/'.$link.'</a></p>
			<br><br>
			Pozdrawiamy<br>
			Ekipa co.wkaliszu.pl
		</body>
		</html>';
	  
		mail($user['mail'], 'Akceptacja Twojego wydarzenia na co.wkaliszu.pl', $wiadomosc, $naglowki);
		//echo $wiadomosc;
	}
	
	function doSiteMap(){
		$f = fopen("sitemap.xml", "w");
		$smap = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
				<url>
				<loc>https://co.wkaliszu.pl/</loc>
				</url>
				<url>
				<loc>https://co.wkaliszu.pl/index.php</loc>
				</url>
				<url>
				<loc>https://co.wkaliszu.pl/mapa.php</loc>
				</url>
				<url>
				<loc>https://co.wkaliszu.pl/login.php</loc>
				</url>
				<url>
				<loc>https://co.wkaliszu.pl/zarejestruj.php</loc>
			</url>	';
		$smap .= linksToEventstoSiteMap();
		
		$smap .= "</urlset>";
		fputs($f, $smap);
		fclose($f);
	}
	
	function sendNewPassMail($mail){
		$user = getUserByMail($mail);
		
		$naglowki = "Reply-to: info@co.wkaliszu.pl <info@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "From: info@co.wkaliszu.pl <info@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "MIME-Version: 1.0".PHP_EOL;
	    $naglowki .= "Content-type: text/html; charset=UTF-8".PHP_EOL; 
	 
		//Wiadomość najczęściej jest generowana przed wywołaniem funkcji
		$wiadomosc = '<html> 
		<head> 
			<meta charset="UTF-8" />
			<title>Resetowanie hasła</title> 
		</head>
		<body>
			<p><b>Witaj, '.$user['login'].'</b></p>
			<p>Aby zresetowac swoje hasło wejdź na:</p>
			<a href="https://co.wkaliszu.pl/changePass.php?mh='.$event['mail_hash'].'">https://co.wkaliszu.pl/changePass.php?mh='.$event['mail_hash'].'</a></p>
			<br><br>
			Pozdrawiamy<br>
			Ekipa co.wkaliszu.pl
		</body>
		</html>';
	  
		mail($user['mail'], 'Resetowanie hasła na co.wkaliszu.pl', $wiadomosc, $naglowki);
		//echo $wiadomosc;
	}
	
	function sendAfterNewEventMailToOwner($id, $name){
		$con = sqlConnect();
		$event = getEvent($con, $id);
		$user = getUser($event['id_user'], $con);
		$link = linkToEvent($id);
		
		$naglowki = "Reply-to: info@co.wkaliszu.pl <info@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "From: info@co.wkaliszu.pl <info@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "MIME-Version: 1.0".PHP_EOL;
	    $naglowki .= "Content-type: text/html; charset=UTF-8".PHP_EOL; 
	 
		//Wiadomość najczęściej jest generowana przed wywołaniem funkcji
		$wiadomosc = '<html> 
		<head> 
			<meta charset="UTF-8" />
			<title>Potwierdzenie dodania nowego wydarzenia</title> 
		</head>
		<body>
			<p><b>Witaj, '.$user['login'].'</b></p>
			<p>Twoje wydarzenie "'.$event['nazwa'].'" właśnie zostało przekazane do administracji<br>
			Gdy zostanie zaakceptowane zostanie o tym poinformowany maile oraz będzie mógł je zobaczyć pod adresem:
			<a href="https://co.wkaliszu.pl/'.$link.'">https://co.wkaliszu.pl/'.$link.'</a></p>
			<br><br>
			Jeżeli to nie Ty dodałeś to wydarzenie, wejdź na <a href="https://co.wkaliszu.pl/login.php">https://co.wkaliszu.pl/login.php</a> aby zresetować swoje hasło!
			Pozdrawiamy<br>
			Ekipa co.wkaliszu.pl
		</body>
		</html>';
	  
		mail($user['mail'], 'Potwierdzenie dodania wydarzenia na co.wkaliszu.pl', $wiadomosc, $naglowki);		
		sqlClose($con);
	}
	
	function makeSlug($text){
		$text = notPolishLink($text);
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, '-');
		$text = preg_replace('~-+~', '-', $text);
		$text = strtolower($text);
		if (empty($text)) {
		return 'n-a';
		}
		return $text;
	}
	
	function makeSlugForFilterField($text){
		$text = notPolishLink($text);
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, '-');
		$text = preg_replace('~-+~', '-', $text);
		$text = strtolower($text);
		if (empty($text)) {
		return 'n-a';
		}
		$text = str_replace('-', '_', $text);
		return $text;
	}
?>