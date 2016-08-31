<?php
session_start();
	include_once 'mysql.php';
	include_once 'function.php';
	
	if(isset($_COOKIE['stmh']))
	{
		$conn = sqlConnect();
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		sqlClose($conn);
	}
	else{
		header("LOCATION: login.php");
	}
	
	$conn = sqlConnect();
	$cat = (isset($_SESSION["id_kat"])) ? categoriesAsSelectWithSelected($conn, $_SESSION["id_kat"]) : categoriesAsSelect($conn);
	$kom = "";
	$dalej = 1;
	
	if(isset($_POST["submit"])){
		if($_POST['name'] != ""){
			$_SESSION["name"] = addslashes(htmlspecialchars($_POST['name']));
		}else{
			$dalej = 0;
			$kom = "Podaj nazwę wydarzenia!<br>";
		}
		$_SESSION["id_kat"] = addslashes(htmlspecialchars($_POST['id_kat']));
		
		$query="SELECT MAX(id) FROM wydarzenia";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			$_SESSION["id"] = ++$row['MAX(id)'];
		}		
		
		if(isset($_POST['recomendate']))
		{
			$_SESSION["recommend"] = 1;
		}
		else {
			$_SESSION["recommend"] = 0;
		}
		
		if($_POST['description'] != ""){
			$_SESSION["desc"] = addslashes(htmlspecialchars($_POST['description']));
		}else{
			$dalej = 0;
			$kom .= "Podaj opis wydarzenia!<br>";
		}
		
		if($_FILES['image']["tmp_name"] != ""){
			$_SESSION["mainphoto"] = saveMainImageEvent($_SESSION["id"]);
			$_SESSION["photo"] = saveIconEvent($_SESSION["id"], $_SESSION["mainphoto"], 300);
		}else{
			if(!isset($_SESSION["photo"]) || $_SESSION["photo"] == ""){
				$dalej = 0;
				$kom .= "Brakuje miniatury!<br>";
			}
		}
		if(!isset($_SESSION["mainphoto"]) || $_SESSION["mainphoto"] == ""){
				$dalej = 0;
				$kom .= "Brakuje obrazka!<br>";
		}else{
			$_SESSION["photo"] = saveIconEvent($_SESSION["id"], $_SESSION["mainphoto"], 300);
		}		
		$_SESSION['thumbX'] = $_POST['X'];
		$_SESSION['thumbY']= $_POST['Y'];
		$_SESSION['thumbW'] = $_POST['W'];
		
		$_SESSION['www']= addslashes(htmlspecialchars($_POST['www']));
		$_SESSION['yt'] = addslashes(htmlspecialchars($_POST['yt']));
		$_SESSION["price"] = addslashes(htmlspecialchars($_POST['price']));
		if($_SESSION["price"] == "") $_SESSION['price'] = 0; 
		$_SESSION["desc_img"] = addslashes(htmlspecialchars($_POST['desc_img']));
		if($dalej != 0) header("LOCATION: addevent_2.php");
	}
	
	sqlClose($conn);
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Panel użytkownika-wkaliszu.pl</title>
		<link rel="stylesheet" type="text/css" href="style/style_panel.css">
		<link rel="stylesheet" href="style/jquery.Jcrop.css" type="text/css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type='text/javascript' src='js/filereader.js'></script>
		<script>
			var jcrop = null;
			
			$( document ).ready(function() {
				if($("#preview").attr("src") != ""){
					var x = <?php if(isset($_SESSION["thumbX"])) echo $_SESSION['thumbX'].";"; else echo "0;";?>
					var y = <?php if(isset($_SESSION["thumbY"])) echo $_SESSION['thumbY'].";"; else echo "0;";?>
					var w = <?php if(isset($_SESSION["thumbW"])) echo $_SESSION['thumbW'].";"; else echo "150;";?>
					if(jcrop) {
						jcrop.destroy();
						$('#preview').attr('style', '');
					}
					jcrop = miniatura(x, y, w);
				}
			});
			function readURL(input) {

				if (input.files && input.files[0]) {
					var reader = new FileReader();
					if (input.files[0].size > 2145728 ){
						alert("Ten plik jest za duży, musisz załadować plik do 2MB");
						$(input).val("");
					}
					else{
						reader.onload = function (e) {
							$('#preview').attr('src', e.target.result);
							if(jcrop) {
								jcrop.destroy();
								$('#preview').attr('style', '');
							}
							jcrop = miniatura(0, 0, 150);
						}

						reader.readAsDataURL(input.files[0]);
					}
				}
			}
		</script>
	</head>
	<body>
		<div id="top">
			<nav id="menu">
				<?php 
					$conn = sqlConnect();
					$per = getPermission($mh, $conn);
					sqlClose($conn);
					if($per == 1 || $per == 2) adminMenu();
					else normalMenu();
				?>
			</nav>
				<div id="menu_add_event">
					<table>
						<tr>
							<td class="p_activ">
								<a href="#">
									<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
									Co
								</a>
							</td>
							<td class="p_activ">
								<img src="img/p_add_next.png" alt="Dalej"/>
							</td>
							<td>
								<a href="#">
									<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
									Gdzie
								</a>								
							</td>
							<td class="p_activ">
								<img src="img/p_add_next.png" alt="Dalej"/>
							</td>
							<td>
								<a href="#">
									<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
									Kiedy
								</a>
							</td>
						</tr>
					</table>
				</div>
				<section id="panel_wydarzenia">
				<form action="" method="post" enctype="multipart/form-data" runat="server" onsubmit="return validateALL();">
				<div id="p_add_event_name">
						Nazwa:<br>
						<input class="toValidate" type="text" name="name" value="<?php if(isset($_SESSION["name"]))echo $_SESSION["name"];?>"/>
						<br>
						<select name="id_kat">
							<?php echo $cat; ?>
						</select><br>
						<?php 
							$conn = sqlConnect();
							$per = getPermission($mh, $conn);
							sqlClose($conn);
							if($per == 1 || $per == 2){
								echo 'Polecane: <input type="checkbox" name="recomendate" value="1"/><br>';
							}
						?>	
						<div id="event_add_image">
						
							Wybierz zdjęcie dla wydarzenia<br>
							<input type="file" name="image" id="imageTHUMB" onchange="readURL(this);"/><br>
							<!--<span> * wymagana rozdzielczość obrazka wynosi 150x150px</span>--><br>
							Wybierz miniaturę dla wydarzenia<br><br>
							<img src="<?php if(isset($_SESSION["mainphoto"])) echo $_SESSION["mainphoto"];?>" alt="Podgląd" id="preview" width="300px"/>						
							<input type="hidden" name="X" id="X" value="0"/>
							<input type="hidden" name="Y" id="Y" value="0"/>
							<input type="hidden" name="W" id="W" value="100"/>
							<input type="hidden" name="orginalW" id="orginalW" value="0"/>
							<input type="hidden" name="orginalH" id="orginalH" value="0"/>
						</div>
				</div>
				<div id="event_add_textarea_desc">
					<p>Opis wydarzenia</p>
					<textarea class="toValidate" name="description"><?php if(isset($_SESSION["desc"])) echo $_SESSION["desc"];?></textarea>
				</div>
				<div>
					<table class="optional-option">
						<tr>
							<td>Podpis zdjęcia<span> (opcjonalnie)</span></td>
							<td>Strona www<span> (opcjonalnie)</span></td>
						</tr>
						<tr>
							<td><input type="text" name="desc_img" id="desc_img" value="<?php if(isset($_SESSION["desc_img"]))echo $_SESSION["desc_img"];?>"/></td>
							<td><input type="text" name="www" value="<?php if(isset($_SESSION["www"]))echo $_SESSION["www"];?>"/></td>
						</tr>
						<tr>
							<td>Cena</td>
							<td>Link do filmu<span> (opcjonalnie)</span></td>
						</tr>
						<tr>
							<td><label><input checked type="radio" name="price_type" value="0">Bezpłatne</label> <label><input type="radio" name="price_type" value="1">Płatne</label> od <input type="text" disabled name="price" id="price" value="<?php if(isset($_SESSION["price"])){echo $_SESSION["price"];} else echo "0";?>"/> PLN</td>
							<td class="yt-input"><span>https://www.youtube.com/</span><input type="text" name="yt" value="<?php if(isset($_SESSION["yt"]))echo $_SESSION["yt"];?>" placeholder="watch?v=OTOa_Q0W-AI"/></td>
						</tr>
					</table>
				</div>
				<div style="clear:both;">
					<br><br>
					<p><input class="btn" type="submit" name="submit" value="Następny krok"/><br>
						<?php echo $kom; ?></p>
				</div>
				</form>
			</section>
			<footer>
				
			</footer>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript" src="js/scripts_addevents.js"></script>
		<script src="js/jquery.Jcrop.js"></script>
		<script type='text/javascript' src='js/skrypt_thumbMaker.js'></script>
		<script type='text/javascript' src='js/skrypt_validator.js'></script>
		<script type="text/javascript" src="js/skrypt_nav_panel.js"></script>
	</body>
</html>