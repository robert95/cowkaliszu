<?php
	include_once 'mysql.php';
	include_once 'function.php';
	cleanEventSession();
	if(isset($_COOKIE['stmh']))
	{
		$conn = sqlConnect();
		$mh = $_COOKIE['stmh'];
		$user = getUser($mh, $conn);
		$per = getPermission($mh, $conn);
		sqlClose($conn);
		if($per == 0 ) header("LOCATION: login.php");
	}
	else{
		header("LOCATION: login.php");
	}
	
	$conn = sqlConnect();

	$catTd = categoriesAsTd($conn);
	$kom = "";
	if(isset($_POST["submit"])){
		if($_POST["submit"] == "Dodaj") {
			addCategory($conn);
			$kom = "Nowa kategoria została dodana!";
		}
		if($_POST["submit"] == "Edycja") {
			editCategory($conn);
			$kom = "kategoria została zedytowana";
		}
		header("LOCATION: dodajkategorie.php");
	}
	
	sqlClose($conn);
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Panel użytkownika-wkaliszu.pl</title>
		<link rel="stylesheet" type="text/css" href="style/style_panel.css">
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
			<section id="panel_kategorie">
				<div id="new_category">
					<p><?php echo $kom;?></p>
					<form action="" method="post" enctype="multipart/form-data">
					Nazwa:<br>
					<input type="text" name="name"/><br>
					Wybierz ikonę dla nowej kategorii:<br>
					<input type="file" name="icon"/><br>
					<div><input id="confirm_button" class="btn" type="submit" name="submit" value="Dodaj"/></div>
					<input type="hidden" name="id" value="-1"/> 
					<input type="hidden" name="old_name" value="-1"/> 
					</form>
				</div>
				<div id="base_categories">
					<p>Baza kategorii:</p>
					<table class="p_table">
						<tr>
							<th style="width: 15%;">Nr</th>
							<th style="width: 15%;">Obrazek</th>
							<th style="width: 45%;">Nazwa</th>
							<th class="delete">Usuń</th>
						</tr>
						<?php echo $catTd;?>
						<tr>
							<td class="empty"></td>
							<td class="empty"></td>
							<td class="empty"></td>
							<td><input class="btn2 btn" type="submit" name="usun" value="usuń wybrane"/></td>
							<td class="empty"></td>
						</tr>
						
					</table>
				</div>
				<div style="clear:both"></div>
			</section>
			<footer>
				
			</footer>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript" src="js/scripts_cat.js"></script>
		<script type="text/javascript" src="js/skrypt_nav_panel.js"></script>		
	</body>
</html>