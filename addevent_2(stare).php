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
	$kom = "";
	$dalej = 1;
	
	if(isset($_POST["submit"])){
		if($_POST['description'] != ""){
			$_SESSION["desc"] = htmlspecialchars($_POST['description']);
		}else{
			$dalej = 0;
			$kom = "Podaj opis wydarzenia!<br>";
		}
		if(true){
			if($_FILES['image']["tmp_name"] != ""){
				$_SESSION["mainphoto"] = saveMainImageEvent($_SESSION["id"]);
				$_SESSION["photo"] = saveIconEvent($_SESSION["id"], $_SESSION["mainphoto"], 700);
			}else{
				if(!isset($_SESSION["photo"]) || $_SESSION["photo"] == ""){
					$dalej = 0;
					$kom .= "Brakuje miniatury wydarzenia!<br>";
				}
			}
			$_SESSION["photo"] = saveIconEvent($_SESSION["id"], $_SESSION["mainphoto"], 700);
			$_SESSION['thumbX'] = $_POST['X'];
			$_SESSION['thumbY']= $_POST['Y'];
			$_SESSION['thumbW'] = $_POST['W'];
		}
		
		/*if($_FILES['imageMAIN']["tmp_name"] != ""){
			$_SESSION["mainphoto"] = saveMainImageEvent($_SESSION["id"]);
		}else{
			$dalej = 0;
			$kom .= "Brakuje zdjęcia wydarzenia!";
		}	*/	
		/*$_SESSION["fblink"] = 'https://www.facebook.com/events/'.htmlspecialchars($_POST['fb']);*/
		if($dalej != 0) header("LOCATION: addevent_3.php");
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
			function readURLMain(input) {

				if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$('#previewMain').attr('src', e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
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
			<section id="panel_wydarzenia">
				<div id="menu_add_event">
					<table>
						<tr>
							<td>
								<a href="addevent_1.php">
									<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
									Co
								</a>
							</td>
							<td class="p_activ">
								<img src="img/p_add_next.png" alt="Dalej"/>
							</td>
							<td class="p_activ">
								<a href="addevent_2.php">
									<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
									Gdzie
								</a>								
							</td>
							<td class="p_activ">
								<img src="img/p_add_next.png" alt="Dalej"/>
							</td>
							<td>
								<a href="addevent_3.php">
									<img src="img/p_add_pointer.png" alt="Tu jesteś"/><br>
									Kiedy
								</a>
							</td>
						</tr>
					</table>
				</div>
				<div id="p_add_event_desc">
					<form action="" method="post"  enctype="multipart/form-data" runat="server">
						<div id="event_add_textarea_desc">
							<p>Opis wydarzenia</p>
							<textarea name="description"><?php if(isset($_SESSION["desc"])) echo $_SESSION["desc"];?></textarea>
						</div>
						<div id="event_add_image">
							Wybierz zdjęcie dla wydarzenia<br>
							<input type="file" name="image" id="imageTHUMB" onchange="readURL(this);"/><br>
							<!--<span> * wymagana rozdzielczość obrazka wynosi 150x150px</span>--><br>
							Wybierz miniaturę dla wydarzenia<br><br>
							<img src="<?php if(isset($_SESSION["mainphoto"])) echo $_SESSION["mainphoto"];?>" alt="Podgląd" id="preview" width="700px"/>						
						</div>
						<!--<div style="clear: both;"></div>
						<div id="event_add_main_image">
							Wybierz obraz dla wydarzenia<br>
							<input type="file" name="imageMAIN" id="imageMAIN" onchange="readURLMain(this);"/><br>
							<!--<span> * wymagana rozdzielczość obrazka wynosi 150x150px</span>--><br>
							<!--Podgląd<br>	
							<img src="img/p_preview.png" alt="Podgląd" id="previewMain"/>			
						</div>-->
						<!--<div id="event_add_fblink">
							ID wydarzenia na Facebook-u:<br>
							<input type="text" name="fb"/>
						</div>-->
				</div>
				<input type="hidden" name="X" id="X" value="0"/>
				<input type="hidden" name="Y" id="Y" value="0"/>
				<input type="hidden" name="W" id="W" value="100"/>
				<input type="hidden" name="orginalW" id="orginalW" value="0"/>
				<input type="hidden" name="orginalH" id="orginalH" value="0"/>
				<p class="btn_in_panel_add"><a class="btn" href="addevent_1.php">Poprzedni krok</a>&emsp;<input class="btn" type="submit" name="submit" value="Następny krok"/><br><?php echo $kom;?></p>
				</form>
			</section>
			<footer>
				
			</footer>
		</div>
		<script src="js/jquery.Jcrop.js"></script>
		<script type='text/javascript' src='js/skrypt_thumbMaker.js'></script>
	</body>
</html>