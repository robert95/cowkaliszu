<?php
	function sqlConnect(){
		include_once 'config.php';
		$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($connection->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$connection->query("SET NAMES utf8");   
		return $connection;
	}
	
	function sqlClose($con){
		mysqli_close($con);
	}
	
	function categoriesAsSelect($conn){
		$query="SELECT * FROM `kategorie`";
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {
			$wyj .= '<option value="'.$row["id"].'">'.$row["nazwa"].'</option>';			
		}
		return $wyj;
	}
	
	function categoriesAsIcon(){
		$conn = sqlConnect();
		
		$query="SELECT * FROM `kategorie`";
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {
			$wyj .= '<div class="cat_icon cat_ch" data-check="1" data-id="'.$row["id"].'" id="sh_c_'.$row["id"].'">
						<img src="'.$row["obrazek"].'" alt="'.$row["nazwa"].'" />
						<p>'.$row["nazwa"].'</p>
					</div>';		
		}
		
		sqlClose($conn);
		return $wyj;
	}
	
	function categoriesAsSelectWithSelected($conn, $id){
		$query="SELECT * FROM `kategorie`";
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {
			if($row["id"] == $id) $s = "selected";
			else $s = "";
			$wyj .= '<option value="'.$row["id"].'" '.$s.'>'.$row["nazwa"].'</option>';			
		}
		return $wyj;
	}
	
	function categoriesAsLi($conn){
		$query="SELECT * FROM `kategorie`";
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {
			$wyj .= '<li data-id="'.$row["id"].'">'.$row["nazwa"].'</li>';			
		}
		return $wyj;
	}		
	
	function categoriesAsDivList($conn){
		$query="SELECT * FROM `kategorie`";
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {
			$wyj .= '<div class="cat_on_list">
						<img src="img/'.$row["nazwa"].'.png" alt="'.$row["nazwa"].'" />
						<span>'.$row["nazwa"].'</span>
						<img class="cat_ch" data-check="1" data-id="'.$row["id"].'" src="img/checked.png" alt="pokaż/ukryj" id="sh_c_'.$row["id"].'"/>
					</div>';		
		}
		return $wyj;
	}
	
	function categoriesAsTd($conn){
		$query="SELECT * FROM `kategorie`";
		$result=$conn->query($query);
		$wyj="";
		$i = 1;
		while($row = $result->fetch_assoc()) {
			$wyj .= '<tr><td>'.$i.'</td>
					<td><img src="'.$row["obrazek"].'" alt="'.$row["nazwa"].'"/></td>
					<td>'.$row["nazwa"].'</td>
					<td><img class="delete_cat" data-id="'.$row["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>
					<td><input class="btn" type="submit" name="edytuj" value="Edytuj" data-id="'.$row["id"].'" data-nazwa="'.$row["nazwa"].'"/></td></tr>';
			$i++;					
		}
		return $wyj;
	}
	
	function addCategorytoDB($name, $file, $con){
		$sql = "INSERT INTO kategorie (nazwa, obrazek) VALUES ('".$name."', '".$file."')";
		$con->query($sql);
	}
	
	function deleteCategory($id, $con){
		$sql = "DELETE FROM kategorie WHERE id=".$id."";
		$con->query($sql);
	}
	
	function deleteUser($id, $con){
		$sql = "DELETE FROM users WHERE id=".$id."";
		$con->query($sql);
	}
	
	function editCategoryinDB($id, $name, $file, $con)
	{
		$sql = "UPDATE kategorie SET nazwa = '".$name."', obrazek = '".$file."' WHERE id = ".$id."";
		$con->query($sql);
	}
	
	function addEventDB($con, $name, $id_kat, $thumb, $image, $recommend, $data, $time, $data_end, $time_end, $desc, $id_place, $views, $comments, $like, $id_user, $group, $www, $yt, $price, $desc_img){													
		$sql = "INSERT INTO `wydarzenia`(`nazwa`, `id_kat`, `miniatura`, `obraz`, `polecane`, `data`, `czas`, `data_end`, `czas_end`, `opis`, `id_miejsce`, `widzow`, `ulubione`, `komentarzy`, `id_user`, `grupa`, `www`, `yt`, `cena`, `opis_img`) VALUES ('".$name."',".$id_kat.",'".$thumb."','".$image."',".$recommend.",'".$data."','".$time."','".$data_end."','".$time_end."','".$desc."',".$id_place.",".$views.",".$like.",".$comments.",'".$id_user."',".$group.",'".$www."','".$yt."','".$price."','".$desc_img."')"; 
		$con->query($sql);
	}
	
	function getCategoryName($conn, $id){
		$query="SELECT * FROM `kategorie` WHERE `id`=".$id."";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row['nazwa'];
		}
		
	}
	
	function getCategoryIcon($conn, $id){
		$query="SELECT * FROM `kategorie` WHERE `id` = ".$id."";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row['obrazek'];
		}		
	}
	
	function eventsAsTd($conn){
		$mh = $_COOKIE['stmh'];
		if( getPermission($mh, $conn) == 1 || getPermission($mh, $conn) == 2){
			$query="SELECT * FROM `wydarzenia` WHERE `poczekalnia` = 0 AND CURRENT_DATE <= `data_end` ORDER BY `data` DESC";
		}
		else{
			$query="SELECT * FROM `wydarzenia` WHERE `id_user` = '".$mh."' AND CURRENT_DATE <= `data_end` ORDER BY `data` DESC";
		}
		
		$result=$conn->query($query);
		$wyj="";
		$i = 1;
		while($row = $result->fetch_assoc()) {
			$kat = getCategoryName($conn, $row["id_kat"]);
			$image_kat = getCategoryIcon($conn, $row["id_kat"]);
			$per = getPermission($_COOKIE['stmh'], $conn);
			if($row['polecane']) $im = 'img/p_checked.png';
			else $im = 'img/p_nochecked.png'; 
			if($row['glowne']) $imMain = 'img/p_main_checked.png';
			else $imMain = 'img/p_main_nochecked.png'; 
			$wyj .= '<tr><td>'.$i.'</td>
					<td>'.$row['data'].'</td>
					<td>'.$kat.'</td>
					<td><img src="'.$image_kat.'" alt="'.$kat.'"/></td>
					<td><a href="event.php?id='.$row["id"].'">'.$row["nazwa"].'</a></td>
					<td><img class="delete_cat" data-id="'.$row["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>';
			if($per == 1 || $per == 2) $wyj .= '<td><img class="recommend_ev" data-id="'.$row["id"].'" data-check="'.$row["polecane"].'" src="'.$im.'" alt="Usuń"/></td><td><img class="main_ev" data-id="'.$row["id"].'" data-check="'.$row["glowne"].'" src="'.$imMain.'" alt="Usuń"/></td>';
			$wyj .= '<td><input class="btn" type="submit" name="edytuj" value="Edytuj" data-id="'.$row["id"].'" data-nazwa="'.$row["nazwa"].'"/></td></tr>';
			$i++;					
		}
		return $wyj;		
	}
	
	function eventArchivAsTd($conn){
		$mh = $_COOKIE['stmh'];
		if( getPermission($mh, $conn) == 1 || getPermission($mh, $conn) == 2){
			$query="SELECT * FROM `wydarzenia` WHERE `poczekalnia` = 0 AND CURRENT_DATE > `data_end` ORDER BY `data` DESC";
		}
		else{
			$query="SELECT * FROM `wydarzenia` WHERE `id_user` = '".$mh."' AND CURRENT_DATE > `data_end` ORDER BY `data` DESC";
		}
		
		$result=$conn->query($query);
		$wyj="";
		$i = 1;
		while($row = $result->fetch_assoc()) {
			$kat = getCategoryName($conn, $row["id_kat"]);
			$image_kat = getCategoryIcon($conn, $row["id_kat"]);
			$per = getPermission($_COOKIE['stmh'], $conn);
			if($row['polecane']) $im = 'img/p_checked.png';
			else $im = 'img/p_nochecked.png'; 
			$wyj .= '<tr><td>'.$i.'</td>
					<td>'.$row['data'].'</td>
					<td>'.$kat.'</td>
					<td><img src="'.$image_kat.'" alt="'.$kat.'"/></td>
					<td><a href="event.php?id='.$row["id"].'">'.$row["nazwa"].'</a></td>
					<td><img class="delete_cat_archiv" data-id="'.$row["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>';
			if($per == 1 || $per == 2) $wyj .= '<td><img class="recommend_ev" data-id="'.$row["id"].'" data-check="'.$row["polecane"].'" src="'.$im.'" alt="Usuń"/></td>';
			$wyj .=	'<td><input class="btn" type="submit" name="edytuj" value="Edytuj" data-id="'.$row["id"].'" data-nazwa="'.$row["nazwa"].'"/></td></tr>';
			$i++;					
		}
		return $wyj;		
	}
	
	
	function waitingEventsAsTd($conn){
		$mh = $_COOKIE['stmh'];
		if( getPermission($mh, $conn) == 1 || getPermission($mh, $conn) == 2){
			$query="SELECT * FROM `wydarzenia` WHERE `poczekalnia` = 1"; 
		}
		else{
			$query="SELECT * FROM `wydarzenia` WHERE `id_user` = -10"; //zwróci pustą liste
		}
		
		$result=$conn->query($query);
		$wyj="";
		$i = 1;
		$alreadyShow = array(); //już wyświetlone wydarzenia
		while($row = $result->fetch_assoc()) {
			if(!(in_array( $row["id"] , $alreadyShow)))
			{
				array_push($alreadyShow,$row["id"]);
				$kat = getCategoryName($conn, $row["id_kat"]);
				$image_kat = getCategoryIcon($conn, $row["id_kat"]);
				if($row["grupa"] < 0){
					$wyj .= '<tr><td>'.$i.'</td>
						<td>'.$kat.'</td>
						<td><img src="'.$image_kat.'" alt="'.$kat.'"/></td>
						<td><a href="event.php?id='.$row["id"].'">'.$row["nazwa"].'</a></td>
						<td><input class="btn accept" type="submit" name="akceptuj" value="Akceptuj" data-id="'.$row["id"].'"/></td>
						<td><img class="delete_event_waiting" data-id="'.$row["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>
						<td><input class="btn" type="submit" name="edytuj" value="Edytuj" data-id="'.$row["id"].'" data-nazwa="'.$row["nazwa"].'"/></td></tr>';
				}else{
					$wyj .= '<tr><td>'.$i.'</td>
						<td>'.$kat.'</td>
						<td><img src="'.$image_kat.'" alt="'.$kat.'"/></td>
						<td><strong>Grupa wydarzeń -> '.$row["nazwa"].'</strong></td>
						<td><input class="btn acceptGroup" type="submit" name="akceptujGroup" value="Akceptuj" data-group="'.$row["grupa"].'"/></td>
						<td><input class="btn deleteGroup" type="submit" name="usunGroup" value="Usuń" data-group="'.$row["grupa"].'"/></td>
						<td><input class="btn showAllofGroup" type="submit" name="showAllofGroup" value="Rozwiń" data-group="'.$row["grupa"].'"/></td></tr>';
					
					$query2="SELECT * FROM `wydarzenia` WHERE `grupa` = ".$row["grupa"];
					$result2=$conn->query($query2);
					
					while($row2 = $result2->fetch_assoc()) {
						array_push($alreadyShow,$row2["id"]);
						$kat2 = getCategoryName($conn, $row2["id_kat"]);
						$image_kat2 = getCategoryIcon($conn, $row2["id_kat"]);
						
						$wyj .= '<tr style="display:none;" data-nrgrupa="'.$row2["grupa"].'"><td>'.$i.'</td>
						<td>'.$kat2.'</td>
						<td><img src="'.$image_kat2.'" alt="'.$kat2.'"/></td>
						<td><a href="event.php?id='.$row2["id"].'">'.$row2["nazwa"].'</a></td>
						<td><input class="btn accept" type="submit" name="akceptuj" value="Akceptuj" data-id="'.$row2["id"].'"/></td>
						<td><img class="delete_event_waiting" data-id="'.$row2["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>
						<td><input class="btn" type="submit" name="edytuj" value="Edytuj" data-id="'.$row2["id"].'" data-nazwa="'.$row2["nazwa"].'"/></td></tr>';
						
					}					
				}
				$i++;
			}
		}
		return $wyj;		
	}
	
	function deleteEvent($id, $con){
		$sql = "DELETE FROM wydarzenia WHERE id=".$id."";
		$con->query($sql);
	}	
	function deleteEventGroup($group, $con){
		$sql = "DELETE FROM wydarzenia WHERE grupa=".$group."";
		$con->query($sql);
	}	
	function activeEvent($id, $con){
		$sql = "UPDATE `wydarzenia` SET `poczekalnia`= 0 WHERE id=".$id."";
		$con->query($sql);
		sendInfoEmailToOwner($id, $con);
	}
	function activeEventGroung($group, $con){
		$sql = "UPDATE `wydarzenia` SET `poczekalnia`= 0 WHERE grupa=".$group."";
		$con->query($sql);
		//sendInfoEmailToOwnerActiveGroup($group, $con);
	}
	function getEvent($conn, $id){
		$query="SELECT * FROM `wydarzenia` WHERE `id` = ".$id."";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row;
		}		
	}
	
	function editEventDB($id, $con, $name, $id_kat, $thumb, $image, $recommend, $data, $time, $data_end, $time_end, $desc, $id_place, $www, $yt, $price, $desc_img){													
		$sql = "UPDATE `wydarzenia` SET `nazwa`='".$name."',`id_kat`=".$id_kat.",`miniatura`='".$thumb."',`obraz`='".$image."',`polecane`=".$recommend.",`data`='".$data."',`czas`='".$time."',`data_end`='".$data_end."',`czas_end`='".$time_end."',`opis`='".$desc."',`id_miejsce`=".$id_place.", `poczekalnia` = 1 ,`www`='".$www."',`yt`='".$yt."',`cena`='".$price."',`opis_img`='".$desc_img."' WHERE `id` = ".$id."";
		$con->query($sql);
	}
	
	function addPlaceDB($con, $name, $id_kat, $opis, $x, $y, $adress){
		$sql = "INSERT INTO `miejsce`(`nazwa`, `id_kat`, `opis`, `x`, `y`, `adres`) VALUES ('".$name."',".$id_kat.",'".$opis."',".$x.",".$y.",'".$adress."')"; 
		$con->query($sql);	
	}
	
	function getPlaceFirstLetter($con)
	{	
		$wyj = "";
		for($i = 65; $i < 91; $i++)
		{
			if($i%2 == 1) {$wyj.="<tr class='letterTR'>\n";}
			$wyj.='<td class="letter"><table>
							<tr><th>'.chr($i).'</th></tr>';
			$sql = "SELECT * FROM `miejsce` WHERE `nazwa` LIKE '".chr($i)."%'"; 
			$result=$con->query($sql);
			while($row = $result->fetch_assoc()) {				
				$wyj .= '<tr><td><a class="set_place" data-id="'.$row["id"].'">'.$row["nazwa"].'</a></td></tr>';					
			}
			$wyj .= '</table></td>';
			if($i%2 == 0) {$wyj.="</tr>";} 
		}	
		return $wyj;
	}
	
	function getPlaceFirstLetterForLittlePanel($con)
	{	
		$wyj = "";
		for($i = 65; $i < 91; $i++)
		{
			$wyj.='<tr><th>'.chr($i).'</th></tr>';
			$sql = "SELECT * FROM `miejsce` WHERE `nazwa` LIKE '".chr($i)."%'"; 
			$result=$con->query($sql);
			while($row = $result->fetch_assoc()) {				
				$wyj .= '<tr><td><a class="set_place" data-id="'.$row["id"].'">'.$row["nazwa"].'<a/></td></tr>';					
			}
		}	
		return $wyj;
	}
	
	function getPlace($conn, $id){
		$query="SELECT * FROM `miejsce` WHERE `id` = ".$id."";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row;
		}
	}
	
	function placesAsSelectWithSelected($conn, $id){
		$query="SELECT * FROM `miejsce`";
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {
			if($row["id"] == $id) $s = "selected";
			else $s = "";
			$wyj .= '<option value="'.$row["id"].'" '.$s.' data-x="'.$row["x"].'" data-y="'.$row["y"].'">'.$row["nazwa"].'</option>';			
		}
		return $wyj;
	}
	
	function getPopularEvent($conn, $cat = 0)
	{
		if($cat == 0 ){
			$query = "SELECT * FROM `wydarzenia` WHERE CURRENT_DATE < `data` AND `poczekalnia` = 0 ORDER BY `widzow` DESC, `data` ASC LIMIT 5";
		}
		else{
			$query = "SELECT * FROM `wydarzenia` WHERE `id_kat` = ".$cat." AND CURRENT_DATE < `data` AND `poczekalnia` = 0 ORDER BY `widzow` DESC, `data` ASC LIMIT 5";
		}
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {
			$place = getPlace($conn, $row["id_miejsce"]);
			$place = $place['nazwa'];
			$data = convertToCoolDate($row['data']);
			$wyj .= '<div class="popular" id="'.$row["id"].'" onclick="location.href=\''.linkToEvent($row["id"]).'\'">
						<div class="thumb_event"><img src="'.$row["miniatura"].'" alt="'.$row["nazwa"].'"/></div>
						<div class="pop_desc">
							<h4><a href="'.linkToEvent($row["id"]).'">'.$row["nazwa"].'</a></h4><br>
							<h5>'.$place.' - '.$data.' - '.substr($row['czas'], 0, 5).'</h5>
						</div>
					</div>';
		}
		return $wyj;
		
	}
	
	function getRecomendateEvent($conn, $cat = 0)
	{
		if($cat == 0 ){
			$query = "SELECT * FROM `wydarzenia` WHERE `polecane` = 1 AND CURRENT_DATE <= `data_end` AND `poczekalnia` = 0 ORDER BY `priorytet` DESC, `data` ASC, `czas`";
		}
		else{
			$query = "SELECT * FROM `wydarzenia` WHERE `id_kat` = ".$cat." AND `polecane` = 1 AND CURRENT_DATE <= `data_end` AND `poczekalnia` = 0 ORDER BY `priorytet` DESC, `data` ASC, `czas` ASC ";
		}
		$result=$conn->query($query);
		$i=0;
		$wyj="";
		$alreadyShow = array(); //już wyświetlone wydarzenia

		while($row = $result->fetch_assoc()) {
			if($i<8 && (!(in_array( $row['grupa'] , $alreadyShow))))
			{
				if($row['grupa'] > 0) array_push($alreadyShow,$row['grupa']);
				$place = getPlace($conn, $row["id_miejsce"]);
				$place = $place['nazwa'];
				$kat = getCategory($conn, $row["id_kat"]);
				$data = $row["data"];
					$wyj .= '<div class="recommend" onclick="location.href=\''.linkToEvent($row["id"]).'\'">
								<div class="thumb_event rec_img"><img src="'.$row["miniatura"].'" alt="'.$row["nazwa"].'"/></div>
								<div class="rec-desc-topbox">
									<img src="'.$kat["obrazek"].'" alt="'.$kat["nazwa"].'"/> <span>'.$kat["nazwa"].'</span>
									<p>'.$data.'</p>
								</div>
								<div class="rec_desc">
									<h4><a href="'.linkToEvent($row["id"]).'">'.$row["nazwa"].'</a></h4>
								</div>
							</div>';
				$i++;
			}			
		}
		return $wyj;
	}
	
	function getDatesOfEvent($id){
		$conn = sqlConnect();
		
		$event = getEvent($conn, $id);
		$id_group = $event['grupa'];
		$wyj = '';
		if($id_group > 0){
			$query="SELECT * FROM `wydarzenia` WHERE `grupa` = $id_group AND `id` <> $id AND CURRENT_DATE <= `data`";
			$result=$conn->query($query);
			$wyj = '<p class="show-other">Pokaż inne terminy tego wydarzenia</p>
					<table>';
			$isOtherDate = false;
			while($row = $result->fetch_assoc()) {
				$isOtherDate = true;
				$data = $row['data'];
				$d = strtotime($data);
				$readyData = getPolishDayName(date("N", $d)).'. '.date("d", $d).' '.getPolishMonthName(date("n", $d));
				$time = $row['czas'];
				$readyTime = substr($row['czas'], 0, 5);
				
				$wyj.= "<tr><td>".$readyData."</td><td>|</td><td>".$readyTime.'</td><td>|</td><td><img src="img/add_to_fav.png" alt="Dodaj do ulubionych" data-id="'.$row['id'].'" onClick="add_to_like('.$row['id'].', this);" class="liked_icon" style="cursor:pointer;"/></td></tr>';
			}
			$wyj .= "</table>";
		}			
		sqlClose($conn);
		if(!$isOtherDate) return "";
		return $wyj;
	}
	function getMainEvent($conn, $cat = 0)
	{
		if($cat == 0 ){
			//$query = "SELECT * FROM `wydarzenia` WHERE `polecane` = 1 AND CURRENT_DATE < `data` ORDER BY `data` ASC, `czas` ASC LIMIT 1";
			$query = "SELECT * FROM `wydarzenia` WHERE CURRENT_DATE <= `data_end` AND `poczekalnia` = 0 AND `glowne` <> 0 ORDER BY `glowne` DESC, `data` ASC, `czas` ASC, `polecane` LIMIT 1";
		}
		else{
			$query = "SELECT * FROM `wydarzenia` WHERE `id_kat` = ".$cat." AND CURRENT_DATE <= `data_end` AND `poczekalnia` = 0 AND `glowne` <> 0 ORDER BY `glowne` DESC, `data` ASC, `czas` ASC, `polecane` LIMIT 1";
		}	
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {
			$place = getPlace($conn, $row["id_miejsce"]);
			$kat = getCategory($conn, $row["id_kat"]);
			//$data = convertToCoolDate($row['data']);
			$data = convertToCoolDate($row['data']);
			$data_end = convertToCoolDate($row['data_end']);
			$countLiked = countOfLiked($row["id"], $conn);
			$link = linkToEvent($row["id"]);
			$fblink = "https://co.wkaliszu.pl/".$link;
			$wyj .= '<div id="main_event" data-id="'.$row['id'].'">
				<div class="mainevent_img" onclick="location.href=\''.linkToEvent($row["id"]).'\'"><img src="'.$row["obraz"].'" alt="'.$row["nazwa"].'" id="main_picture" style="top: '.$row['glowne'].'px;"/></div>
					<div class="mainevent_desc" onclick="location.href=\''.linkToEvent($row["id"]).'\'">
						<div class="mainevent-title-box">
							<img src="'.$kat["obrazek"].'" alt="'.$kat["nazwa"].'"/> <span>'.$kat["nazwa"].'</span>
							<p>'.$data.'</p>
						</div>
						<div class="mainevent-info-box">
							<h2><a href="'.linkToEvent($row["id"]).'">'.$row["nazwa"].'</a></h2>
							<p>'.$place['nazwa'].'</p>
						</div>
					</div>
						<table>
							<tr>
								<td><img src="img/views.png" alt="Widziało: "/>'.$row["widzow"].'</td>
								<td style="padding-right: 10px;"><img src="img/favourite.png" alt="Ulubione: "/>'.$countLiked.'</td>
								<!--<td style="border-left: 1px solid;"><img src="img/add_to_fav.png" alt="Dodaj do ulubionych" onClick="add_to_like('.$row["id"].', this);" data-id="'.$row["id"].'" class="liked_icon"/> <div class="fb-share-button" data-href="'.$fblink.'" data-layout="icon"></div></td>-->
							</tr>
						</table>
					
				</div>';						
		}
		return $wyj;
	}
	
	function getCategory($conn, $id){
		$query="SELECT * FROM `kategorie` WHERE `id` = ".$id."";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row;
		}		
	}
	
	function getEventByData($conn, $data){
		$query="SELECT * FROM `wydarzenia` WHERE `data` <= '".$data."' AND `data_end` >= '".$data."' AND `poczekalnia` = 0 ORDER BY `czas`";
		$result=$conn->query($query);
		$i = 0;
		$wyj = "";
		while($row = $result->fetch_assoc()) {		
			$wyj[$i] = $row;
			$i++;
		}
		return $wyj;
	}
	
	function getEventForOneDay($conn)
	{
		$query="SELECT * FROM `wydarzenia` WHERE `poczekalnia` = 0 AND (`data_end` > CURRENT_DATE OR (`data_end` = CURRENT_DATE AND `czas_end` >= CURRENT_TIME )) AND (`data` <= DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY)) ORDER BY `id` ASC";
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {	
			$p = getPlace($conn, $row['id_miejsce']);
			$wyj.='<div class="event" 
					data-id="'.$row['id'].'" 
					data-x="'.$p['x'].'" 
					data-y="'.$p['y'].'" 
					data-d="'.$row['data'].'" 
					data-t="'.$row['czas'].'" 
					data-tit="'.$row['nazwa'].'" 
					data-pla="'.$p['nazwa'].'"
					data-start="0">
					</div>';
		}	
		return $wyj;
	}
	
	function addNewUser($conn, $user)
	{
		$sql = "INSERT INTO `users`(`mail`, `haslo`, `data`, `login`, `nazwisko`, `tel`, `ilLog`, `ilMie`, `ilWyd`, `mail_hash`, `uprawnienia`, `aktywny`) VALUES ('".$user['mail']."','".$user['pass_hash']."',CURRENT_DATE,'".$user['login']."','".$user['surname']."','".$user['phone']."',0,0,0,'".$user['mail_hash']."',0,1)";
		$conn->query($sql);
	}
	
	function activeProfil($conn, $h)
	{
		$sql = "UPDATE `users` SET `aktywny` = 1  WHERE `mail_hash` = '".$h."'";
		$conn->query($sql);
	}
	
	function checkLogin($l, $hp, $conn)
	{
		$query="SELECT * FROM `users` WHERE (`login` = BINARY '".$l."' OR `mail` = '".$l."') AND `haslo` = '".$hp."'";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row['mail_hash'];
		}	
		return 'lipa';
	}
	
	function isActiveLogin($mh, $conn)
	{
		$query="SELECT * FROM `users` WHERE `mail_hash` = '".$mh."'";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row['aktywny'];
		}	
		return 0;
	}
	
	function getUser($mh, $conn)
	{
		$query="SELECT * FROM `users` WHERE `mail_hash` = '".$mh."'";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row;
		}
	}
	
	function getUserByID($id, $conn)
	{
		$query="SELECT * FROM `users` WHERE `id` = ".$id."";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row;
		}
	}
	
	function getUserLogin($mh){		
		$conn = sqlConnect();
		$user = getUser($mh, $conn);
		sqlClose($conn);
		return $user['login'];		
	}
	
	function getUserByMail($mail)
	{
		$conn = sqlConnect();
		$query="SELECT * FROM `users` WHERE `mail` = '".$mail."'";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row;
		}
		sqlClose($conn);
	}
	
	function getPermission($mh, $conn)
	{
		$query="SELECT * FROM `users` WHERE `mail_hash` = '".$mh."'";
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {		
			return $row['uprawnienia'];
		}
		return -1;
	}
	
	function editUser($conn, $eduser)
	{
		if(isset($eduser['new_pass'])){
			$sql = "UPDATE `users` SET `haslo`='".$eduser['new_pass_h']."',`tel`='".$eduser['phone']."' WHERE `id` = '".$eduser['id']."'";
		}
		else{
			$sql = "UPDATE `users` SET `tel`='".$eduser['phone']."' WHERE `id` = '".$eduser['id']."'";
		}
		$conn->query($sql);
		changeMailHash($conn, $eduser['mail_hash']);
	}
	
	function changeMailHash($conn, $new_mh){
		$mh = $_COOKIE['stmh'];
		$sql = "UPDATE `wydarzenia` SET `id_user`='".$new_mh."' WHERE `id_user`= '".$mh."'";
		$conn->query($sql);
	}
	
	function liked_panel($mh,$conn){
		$query = "SELECT u.* FROM `ulubione` AS u JOIN `wydarzenia` AS w on w.id = u.id_event WHERE u.id_user = '".$mh."' ORDER BY w.data, w.czas";
		$result=$conn->query($query);
		$data = "";
		$wyj = "";
		
		while($row = $result->fetch_assoc()) {
			
			$ev = getEvent($conn, $row['id_event']);
			$today = date("Y-m-d");
			//$temp_date = strtotime( $ev['data_end'] );
			//$date = date( 'N', $temp_date );
			//$dayWeek = getFullPolishDayName($date);
			$place = getPlace($conn, $ev['id_miejsce']);
			
			if($today <= $ev['data_end']){
				if($ev['data'] != $data){
					if($data != ""){
						$wyj .= '</div>
								</div>';
					}
					$data = $ev['data'];
					$link = linkToEvent($ev['id']);
					$wyj .= '<div data-data="'.$data.'" class="liked_data" >
							<div class="liked_data_switch">
							<img src="img/upArrow.png" alt="zamknij okno"/>
							<p>'.$data.'</p> 
							</div>
							<div class="liked_event_list" data-data="'.$data.'">';
				}
					$wyj .= '<div class="liked_event_desc">
								<a href="event.php?id='.$ev['id'].'">
								<table>
								<tr>
									<td class="thumb_of_widget_liked"><a href="'.$link.'"><img class="liked_thumb" src="'.$ev['miniatura'].'" alt="'.$ev['nazwa'].'"/></a></td>
									<td>
										<h3><a href="'.$link.'">'.$ev['nazwa'].'</a></h3>
										<h4>g. '.$ev['czas'].' / '.$ev['id_miejsce'].'</h4>
									</td>
								</tr>
							</table></a>
							<img class="delete_from_widget_liked" src="img/confirm_no.png" alt="usuń z ulubionych" data-id="'.$ev['id'].'"/>
						</div>';
			}
		}
		$wyj .= '</div>
								</div>';
		return $wyj;
	}
	
	function addToLiked($id, $conn){
		$mh = $_COOKIE['stmh'];
		$query = "SELECT * FROM `ulubione` WHERE `id_user` = '".$mh."' AND `id_event` = ".$id."";
		$results=$conn->query($query);
		if($results->num_rows < 1){
			$sql = "INSERT INTO `ulubione`(`id_user`, `id_event`) VALUES ('".$mh."','".$id."')";
			$kom = "Wydarzenie zostało pomyślnie dodane do ulubionych:)";
		}else{
			$sql = "DELETE FROM `ulubione` WHERE `id_user` = '".$mh."' AND `id_event` = ".$id."";
			$kom = "Wydarzenie zostało pomyślnie usunięte z ulubionych:<";
		}
		$conn->query($sql);
		return $kom;
	}
	
	function isLiked($id, $conn){
		if(!isset($_COOKIE['stmh']))
		{
			return 0;
		}
		$mh = $_COOKIE['stmh'];
		$query = "SELECT * FROM `ulubione` WHERE `id_user` = '".$mh."' AND `id_event` = ".$id."";
		$results=$conn->query($query);
		return ($results->num_rows);
	}
	
	function setRecOfEvent($id, $c, $conn){
		$query = 'UPDATE `wydarzenia` SET `polecane`='.$c.' WHERE `id` = '.$id; 
		$conn->query($query);
	}
	
	function recEventSortable($conn){
		$query="SELECT * FROM `wydarzenia` WHERE `polecane` = 1 AND CURRENT_DATE <= `data_end` AND `poczekalnia` = 0 ORDER BY `priorytet` DESC";
		$result=$conn->query($query);
		$wyj="";
		while($row = $result->fetch_assoc()) {	
			$p = getPlace($conn, $row['id_miejsce']);
			$wyj.='<li class="ui-state-default" data-id="'.$row['id'].'" data-pri="'.$row['priorytet'].'">'.$row['nazwa'].'
			       <br>'.$row['data'].' ('.substr($row['czas'], 0, 5).') -> '.$row['data_end'].' ('.substr($row['czas_end'], 0, 5).')</li>';
		}	
		return $wyj;
	}
	
	function deletePriorytets($conn){
		$query = 'UPDATE `wydarzenia` SET `priorytet`= 0';
		$conn->query($query);
	}
	
	function setPriorytet($id, $p, $conn){
		$query = 'UPDATE `wydarzenia` SET `priorytet`= '.$p.' WHERE `id` = '.$id; 
		$conn->query($query);
	}
	
	function setMainEvent($id, $con){
		$sql = "UPDATE `wydarzenia` SET `glowne`= 0";
		$con->query($sql);
		
		$sql = "UPDATE `wydarzenia` SET `glowne`= 1 WHERE id=".$id."";
		$con->query($sql);
	}
	
	function serachPlaceForKeyword($keyword, $conn){
		
		$keyword = $keyword . '%';
		$query = "SELECT * FROM `miejsce` WHERE `nazwa` LIKE '".$keyword."' ORDER BY `nazwa`";
		$result=$conn->query($query);
		$ret = '{ "lista" : [';
		
		while($row = $result->fetch_assoc()) {	
			$id = $row['id'];
			$name = $row['nazwa'];
			$ret .= '{ "id" : "'.$id.'" , "name" : "'.$name.'" },';
		}
		$ret = substr($ret, 0, strlen($ret) - 1);
		$ret .= "]}";
		
		return $ret;
	}
	
	function saveTheTopPositionOfMainImage($id, $top, $con){	
		$sql = "UPDATE `wydarzenia` SET `glowne`= 0";
		$con->query($sql);
		
		$sql = "UPDATE `wydarzenia` SET `glowne`= ".$top." WHERE id=".$id."";
		$con->query($sql);
	}
	
	function setRemaind($mh, $h, $id_event, $type, $con){
		deleteTheRemaind($mh, $id_event, $type, $con);
		$event = getEvent($con, $id_event);
		$data = $event['data'];
		$czas = $event['czas'];
		
		$remaind_time = date('G:i', strtotime($data." ".$czas) - 60 * 60 * $h);
		$remaind_date = date('Y-m-d', strtotime($data." ".$czas) - 60 * 60 * $h);
		
		$sql = "INSERT INTO `przypomnienia`(`id_user`, `id_event`, `data`, `czas`, `h`, `typ`) VALUES ('".$mh."',".$id_event.",'".$remaind_date."','".$remaind_time."',".$h.",".$type.")";
		$con->query($sql);
		echo $sql;
	}
	
	function deleteEndedRemaind($conn){
		$sql = "DELETE FROM `przypomnienia` WHERE `data` < CURRENT_DATE";
		$con->query($sql);
	}
	
	function deleteRemaind($conn, $id){
		$sql = "DELETE FROM `przypomnienia` WHERE `id` = ".$id;
		$conn->query($sql);
	}
	
	function deleteTheRemaind($mh, $id_event, $type, $con){
		$sql = "DELETE FROM `przypomnienia` WHERE `id_user` = '".$mh."' AND `id_event` = ".$id_event." AND `typ` = ".$type;
		echo $sql;
		$con->query($sql);
	}
	
	function executeRemaind($conn){
		//crop jest uruchamiany co 5 min, więc z dokładnością do 5 min wysyłamy maile i odrazu usuwamy
		$query="SELECT * FROM `przypomnienia` WHERE `data` < CURRENT_DATE OR (`data` = CURRENT_DATE AND `czas` < CURRENT_TIME)";
		$result=$conn->query($query);
		
		while($row = $result->fetch_assoc()) {	
			if($row['typ'] == 1) sendSMS($row['id_event'], $row['id_user'], $conn);
			else sendMAIL($row['id_event'], $row['id_user'], $row['h'], $conn);
			
			deleteRemaind($conn, $row['id']);
		}
	}
	
	function sendMAIL($event, $mh, $h, $conn){
		
		$user = getUser($mh, $conn);
		$e = getEvent($conn, $event);
		$place = getPlace($conn, $e['id_miejsce']);
		
		$naglowki = "Reply-to: moj@mail.pl <moj@mail.pl>".PHP_EOL;
	    $naglowki .= "From: moj@mail.pl <moj@mail.pl>".PHP_EOL;
	    $naglowki .= "MIME-Version: 1.0".PHP_EOL;
	    $naglowki .= "Content-type: text/html; charset=iso-UTF-8".PHP_EOL; 
	 
		//Wiadomość najczęściej jest generowana przed wywołaniem funkcji
		$wiadomosc = '<html> 
		<head> 
			<meta charset="UTF-8" />
			<title>Przypomnienie o wydarzeniu</title> 
		</head>
		<body>
			<p><b>Cześć, '.$user['imie'].'</b></p>
			Tak jak prosiłeś przypominamy o wydarzeniu "'.$e['nazwa'].'" które zaczyna się już za '.$h.' godzin w '.$place['nazwa'].'!
			<br>
			Nie może Cię tam zabraknąć! 
			<br><br>
			Pozdrawiamy<br>
			Ekipa co.wkaliszu.pl
		</body>
		</html>';
	  
		mail($user['mail'], 'Przypomnienie o nadchodzącym wydarzeniu!', $wiadomosc, $naglowki);			
	}
	
	function sendSMS($event, $user, $conn){
		echo "wysyłam smsa do ".$user." o wydarzeniu nr: ".$event;			
	}
	
	function getLikedEvents($conn){
		$mh = $_COOKIE['stmh'];
		$query = "SELECT u.* FROM `ulubione` AS u JOIN `wydarzenia` AS w on w.id = u.id_event WHERE u.id_user = '".$mh."' ORDER BY w.data, w.czas";
		$result=$conn->query($query);
		$data = "";
		$wyj = '';
		while($row = $result->fetch_assoc()) {
			$ev = getEvent($conn, $row['id_event']);
			$today = date("Y-m-d");
			$temp_date = strtotime( $ev['data_end'] );
			$date = date( 'N', $temp_date );
			$dayWeek = getFullPolishDayName($date);
			$place = getPlace($conn, $ev['id_miejsce']);
			
			if($today <= $ev['data_end']){
				if($ev['data'] != $data){
					if($data != ""){
						$wyj .= '</div>';
					}
					$data = $ev['data'];
					$wyj .= '<div class="oneDayDiv">
								<h2>'.$ev['data'].'</h2>';
				}
					$wyj .= '<div class="like_event">
								<div>
									<a href="event.php?id='.$ev['id'].'">
										<div class="thumb_event">
											<img src="'.$ev['miniatura'].'" alt="'.$ev['nazwa'].'"/>
										</div>
										<div class="desc_event">
											<h3>'.$ev['nazwa'].'</h3>
											<h4>'.$dayWeek.' - '.substr($ev['czas'], 0, 5).' - '.substr($ev['czas_end'], 0 ,5).'<br>
												'.$place['nazwa'].'
											</h4>
										</div>
									</a>
									<div class="unlike">
										<img src="img/unlikeInPanel.png" alt="Usuń z ulubionych" data-id="'.$ev['id'].'"/>
									</div>
									<div style="clear: both"></div>
								</div>	
							</div>';
			}		
		}	
		$wyj .= '</div>';
		return $wyj;				
	}
	
	function unlike($id, $con){
		$mh = $_COOKIE['stmh'];
		$sql = "DELETE FROM `ulubione` WHERE `id_user` = '".$mh."' AND `id_event` = ".$id;
		$con->query($sql);		
	}
	
	function addVisitorToEvent($id, $conn){
		$sql = "UPDATE `wydarzenia` SET `widzow`=`widzow` + 1 WHERE id=".$id."";
		$conn->query($sql);	
	}
	
	function countOfLiked($id, $conn){
		$query = "SELECT * FROM `ulubione` WHERE `id_event` = ".$id."";
		$results=$conn->query($query);
		return ($results->num_rows);
	}
	
	function isNotUniqeLogin($login, $conn){
		$query = "SELECT * FROM `users` WHERE `login` = '".$login."'";
		$results=$conn->query($query);
		return ($results->num_rows);
	}
	
	function isNotUniqeMain($mail, $conn){
		$query = "SELECT * FROM `users` WHERE `mail` = '".$mail."'";
		$results=$conn->query($query);
		return ($results->num_rows);
	}
	
	function deleteMe($conn){
		$mh = $_COOKIE['stmh'];
		$sql = "DELETE FROM `users` WHERE `mail_hash` = '".$mh."'";
		$conn->query($sql);		
	}
	
	function updatePass($mh, $haslo){
		$conn = sqlConnect();
		$sql = "UPDATE users SET haslo = '".$haslo."' WHERE mail_hash = ".$mh."";
		$conn->query($sql);
		sqlClose($conn);
	}
	
	function linksToEventstoSiteMap(){
		$conn = sqlConnect();
		
		$query="SELECT * FROM `wydarzenia`";
		$result=$conn->query($query);
		$wyj = "";
		while($row = $result->fetch_assoc()) {
			$id = $row['id'];
			$wyj .= "<url>
					<loc>https://co.wkaliszu.pl/event.php?id=$id</loc>
					</url>";
		}	
		
		sqlClose($conn);
		return $wyj;
	}
       function getRegulamin(){
		$conn = sqlConnect();
		
		$query="SELECT * FROM `regulamin`";
		$result=$conn->query($query);
		$wyj = "";
		while($row = $result->fetch_assoc()) {
			$wyj = $row['tresc'];
		}	
		
		sqlClose($conn);
		return $wyj;
	}
	
	function saveNewRegulamin($tresc){
		$conn = sqlConnect();
		
		$query="UPDATE `regulamin` SET tresc = '".$tresc."' ";
		$conn->query($query);
		
		sqlClose($conn);
	}
    
	function linkToEvent($id){
		$conn = sqlConnect();
		
		$event = getEvent($conn, $id);
		$nazwa = $event['nazwa'];
		$nazwa = notPolishLink($nazwa);
		$nazwa = preg_replace("![^a-z0-9]+!i", "-", $nazwa);
				
		sqlClose($conn);
		return "event-$id-$nazwa.html";
	}
	
	function notPolishLink($url){
		$url = substr($url, 0, 80);
		$aWhat = array('ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ż', 'ź', 'Ą', 'Ć', 'Ę', 'Ł', 'Ń', 'Ó', 'Ś', 'Ż', 'Ź');
		$aOn = array('a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z', 'A', 'C', 'E', 'L', 'N', 'O', 'S', 'Z', 'Z');
		return str_replace($aWhat, $aOn, $url);
	}
	
	function getEventInPlace($id_place, $id_event){
		$conn = sqlConnect();
		$wyj = "";
		
		$query="SELECT * FROM `wydarzenia` WHERE `id_miejsce` = $id_place AND CURRENT_DATE <= `data_end` AND `id` <> $id_event ORDER BY `data` ASC, `czas` ASC";
		
		$result=$conn->query($query);
		$wyj="";
		$i = 1;
		while($e = $result->fetch_assoc()) {
			$kat = getCategory($conn, $e["id_kat"]);
			$place = getPlace($conn, $e["id_miejsce"]);
            $link = linkToEvent($e["id"]);
			$liked_icon = isLiked($e["id"], $conn) == 0?'img/add_to_fav.png':'img/del_to_fav.png';
			$wyj .= '<div class="event" id="e_'.$i.'" data-id="'.$e['id'].'">
						<div class="ev_th thumb_event" onclick="location.href=\''.$link.'\'"><img src="'.$e['miniatura'].'" alt="'.$e['nazwa'].'" onload="fitThumbSize();"/></div>
						<div class="event_desc" onclick="location.href=\''.$link.'\'">
							<div class="event-day-cat"><img src="'.$kat["obrazek"].'" alt="'.$kat["nazwa"].'"/><span> '.$kat["nazwa"].'</span></div>
							<h4><a href="'.$link.'">'.$e['nazwa'].'</a></h4>
							<h5>'.$place['nazwa'].'</h5>
							<h6>g. '.substr($e['czas'], 0, 5).'</h6>
						</div>
					</div>';
			$i++;				
		}
		
		sqlClose($conn);
		return $wyj;
	}
	
	function cleanEventSession(){
		 /*unset ($_SESSION["id_kat"]);
		 unset ($_SESSION["name"]);
		 unset ($_SESSION["id"]);
		 unset ($_SESSION["recommend"]);
		 unset ($_SESSION["desc"]);		
		 unset ($_SESSION["mainphoto"]);		
		 unset ($_SESSION["photo"]);		
		 unset ($_SESSION['thumbX']);		
		 unset ($_SESSION['thumbY']);		
		 unset ($_SESSION['thumbW']);		
		 unset ($_SESSION['www']);		
		 unset ($_SESSION['yt']);		
		 unset ($_SESSION["price"]);		
		 unset ($_SESSION["desc_img"]);		
		 unset ($_SESSION["id_place"]);		
		 unset ($_SESSION["group"]);		
		 unset ($_SESSION["next_id"]);		
		 unset ($_SESSION["data"]);		
		 unset ($_SESSION["time"]);		
		 unset ($_SESSION["data_end"]);		
		 unset ($_SESSION["time_end"]);	*/
		session_start(); 
		session_destroy();
	}
	
	function usersTd(){
		$conn = sqlConnect();
		
		$mh = $_COOKIE['stmh'];
		if( getPermission($mh, $conn) == 2 ){
			$query="SELECT * FROM `users`";
		}
		
		$result=$conn->query($query);
		$wyj="";
		$i = 1;
		while($row = $result->fetch_assoc()) {
			$countOfEvents = countOfEvents($row['mail_hash']);
			$linkToEvent = $row['id'];
			$wyj .= '<tr><td>'.$i.'</td>
					<td>'.$row['login'].'</td>
					<td>'.$row['mail'].'</td>
					<td>'.$row['data'].'</td>
					<td class="moreEventsUserContainer">'.$countOfEvents.' <a href="userevent.php?id='.$linkToEvent.'" class="moreEventsUser"> Zobacz więcej </a></td>
					<td><img class="delete_user" data-id="'.$row["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>';
			$i++;					
		}
		return $wyj;
		sqlClose($conn);
	}
	
	function countOfEvents($mh){
		$conn = sqlConnect();
		$query="SELECT * FROM `wydarzenia` WHERE `id_user` = '".$mh."'";
		$result=$conn->query($query);
		$i = 0;
		while($row = $result->fetch_assoc()) {
			$i++;					
		}
		return $i;
		sqlClose($conn);
	}
	
	
	function eventsAsTdUser($mh, $conn){
		if( getPermission($mh, $conn) == 1 || getPermission($mh, $conn) == 2){
			$query="SELECT * FROM `wydarzenia` WHERE `poczekalnia` = 0 AND CURRENT_DATE <= `data_end` AND `id_user` = '".$mh."' ORDER BY `data` DESC";
		}
		else{
			$query="SELECT * FROM `wydarzenia` WHERE `id_user` = '".$mh."' AND CURRENT_DATE <= `data_end` ORDER BY `data` DESC";
		}
		
		$result=$conn->query($query);
		$wyj="";
		$i = 1;
		while($row = $result->fetch_assoc()) {
			$kat = getCategoryName($conn, $row["id_kat"]);
			$image_kat = getCategoryIcon($conn, $row["id_kat"]);
			$per = getPermission($_COOKIE['stmh'], $conn);
			if($row['polecane']) $im = 'img/p_checked.png';
			else $im = 'img/p_nochecked.png'; 
			if($row['glowne']) $imMain = 'img/p_main_checked.png';
			else $imMain = 'img/p_main_nochecked.png'; 
			$wyj .= '<tr><td>'.$i.'</td>
					<td>'.$row['data'].'</td>
					<td>'.$kat.'</td>
					<td><img src="'.$image_kat.'" alt="'.$kat.'"/></td>
					<td><a href="event.php?id='.$row["id"].'">'.$row["nazwa"].'</a></td>
					<td><img class="delete_cat" data-id="'.$row["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>';
			if($per == 1 || $per == 2) $wyj .= '<td><img class="recommend_ev" data-id="'.$row["id"].'" data-check="'.$row["polecane"].'" src="'.$im.'" alt="Usuń"/></td><td><img class="main_ev" data-id="'.$row["id"].'" data-check="'.$row["glowne"].'" src="'.$imMain.'" alt="Usuń"/></td>';
			$wyj .= '<td><input class="btn" type="submit" name="edytuj" value="Edytuj" data-id="'.$row["id"].'" data-nazwa="'.$row["nazwa"].'"/></td></tr>';
			$i++;					
		}
		return $wyj;		
	}
	
	function eventArchivAsTdUser($mh, $conn){
		echo $mh;
		$mhL = $_COOKIE['stmh'];
		if( getPermission($mhL, $conn) == 1 || getPermission($mhL, $conn) == 2){
			$query="SELECT * FROM `wydarzenia` WHERE `poczekalnia` = 0 AND CURRENT_DATE > `data_end` AND `id_user` = '".$mh."' ORDER BY `data` DESC";
		}
		else{
			$query="SELECT * FROM `wydarzenia` WHERE `id_user` = '".$mh."' AND CURRENT_DATE > `data_end` ORDER BY `data` DESC";
		}
		
		echo $query;
		$result=$conn->query($query);
		$wyj="";
		$i = 1;
		while($row = $result->fetch_assoc()) {
			$kat = getCategoryName($conn, $row["id_kat"]);
			$image_kat = getCategoryIcon($conn, $row["id_kat"]);
			$per = getPermission($_COOKIE['stmh'], $conn);
			if($row['polecane']) $im = 'img/p_checked.png';
			else $im = 'img/p_nochecked.png'; 
			$wyj .= '<tr><td>'.$i.'</td>
					<td>'.$row['data'].'</td>
					<td>'.$kat.'</td>
					<td><img src="'.$image_kat.'" alt="'.$kat.'"/></td>
					<td><a href="event.php?id='.$row["id"].'">'.$row["nazwa"].'</a></td>
					<td><img class="delete_cat_archiv" data-id="'.$row["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>';
			if($per == 1 || $per == 2) $wyj .= '<td><img class="recommend_ev" data-id="'.$row["id"].'" data-check="'.$row["polecane"].'" src="'.$im.'" alt="Usuń"/></td>';
			$wyj .=	'<td><input class="btn" type="submit" name="edytuj" value="Edytuj" data-id="'.$row["id"].'" data-nazwa="'.$row["nazwa"].'"/></td></tr>';
			$i++;					
		}
		return $wyj;		
	}
	
	
	function waitingEventsAsTdUser($mh, $conn){
		if( getPermission($mh, $conn) == 1 || getPermission($mh, $conn) == 2){
			$query="SELECT * FROM `wydarzenia` WHERE `id_user` = '".$mh."' AND `poczekalnia` = 1"; 
		}
		else{
			$query="SELECT * FROM `wydarzenia` WHERE `id_user` = -10"; //zwróci pustą liste
		}
		
		$result=$conn->query($query);
		$wyj="";
		$i = 1;
		$alreadyShow = array(); //już wyświetlone wydarzenia
		while($row = $result->fetch_assoc()) {
			if(!(in_array( $row["id"] , $alreadyShow)))
			{
				array_push($alreadyShow,$row["id"]);
				$kat = getCategoryName($conn, $row["id_kat"]);
				$image_kat = getCategoryIcon($conn, $row["id_kat"]);
				if($row["grupa"] < 0){
					$wyj .= '<tr><td>'.$i.'</td>
						<td>'.$kat.'</td>
						<td><img src="'.$image_kat.'" alt="'.$kat.'"/></td>
						<td><a href="event.php?id='.$row["id"].'">'.$row["nazwa"].'</a></td>
						<td><input class="btn accept" type="submit" name="akceptuj" value="Akceptuj" data-id="'.$row["id"].'"/></td>
						<td><img class="delete_event_waiting" data-id="'.$row["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>
						<td><input class="btn" type="submit" name="edytuj" value="Edytuj" data-id="'.$row["id"].'" data-nazwa="'.$row["nazwa"].'"/></td></tr>';
				}else{
					$wyj .= '<tr><td>'.$i.'</td>
						<td>'.$kat.'</td>
						<td><img src="'.$image_kat.'" alt="'.$kat.'"/></td>
						<td><strong>Grupa wydarzeń -> '.$row["nazwa"].'</strong></td>
						<td><input class="btn acceptGroup" type="submit" name="akceptujGroup" value="Akceptuj" data-group="'.$row["grupa"].'"/></td>
						<td><input class="btn deleteGroup" type="submit" name="usunGroup" value="Usuń" data-group="'.$row["grupa"].'"/></td>
						<td><input class="btn showAllofGroup" type="submit" name="showAllofGroup" value="Rozwiń" data-group="'.$row["grupa"].'"/></td></tr>';
					
					$query2="SELECT * FROM `wydarzenia` WHERE `id_user` = '".$mh."' AND `grupa` = ".$row["grupa"];
					$result2=$conn->query($query2);
					
					while($row2 = $result2->fetch_assoc()) {
						array_push($alreadyShow,$row2["id"]);
						$kat2 = getCategoryName($conn, $row2["id_kat"]);
						$image_kat2 = getCategoryIcon($conn, $row2["id_kat"]);
						
						$wyj .= '<tr style="display:none;" data-nrgrupa="'.$row2["grupa"].'"><td>'.$i.'</td>
						<td>'.$kat2.'</td>
						<td><img src="'.$image_kat2.'" alt="'.$kat2.'"/></td>
						<td><a href="event.php?id='.$row2["id"].'">'.$row2["nazwa"].'</a></td>
						<td><input class="btn accept" type="submit" name="akceptuj" value="Akceptuj" data-id="'.$row2["id"].'"/></td>
						<td><img class="delete_event_waiting" data-id="'.$row2["id"].'" data-check="0" src="img/p_nochecked.png" alt="Usuń"/></td>
						<td><input class="btn" type="submit" name="edytuj" value="Edytuj" data-id="'.$row2["id"].'" data-nazwa="'.$row2["nazwa"].'"/></td></tr>';
						
					}					
				}
				$i++;
			}
		}
		return $wyj;		
	}
	
	function getAllMailAdress(){
		$mailList = "";
		$conn = sqlConnect();
		
		$mh = $_COOKIE['stmh'];
		if( getPermission($mh, $conn) == 2 ){
			$query="SELECT * FROM `users`";
		}else die();
		
		$result=$conn->query($query);
		while($row = $result->fetch_assoc()) {
			$mailList .= $row['mail']."\n";
		}
		sqlClose($conn);
		
		$fp = fopen("tajnaListaMaili.txt", "w");
		fputs($fp, $mailList);
		fclose($fp);
		return "tajnaListaMaili.txt";
	}
	
	function remPass($mail, $con){
		$u = getUserByMail($mail);
		if($u != null){
			$pass = newPassForUser($u['mail_hash']);
			sendNewPass($pass, $mail);
		}
	}
	
	function newPassForUser($mh){
		$conn = sqlConnect();
		$newPass = generatePassword();
		$newPassHash = md5($newPass."@#!%!XXA@");
		
		$query="UPDATE `users` SET `haslo`='$newPassHash' WHERE `mail_hash` = '$mh'";
		$result=$conn->query($query);
		 
		sqlClose($conn);
		
		return $newPass;
	}
	
	function generatePassword($length = 8) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$count = mb_strlen($chars);

		for ($i = 0, $result = ''; $i < $length; $i++) {
			$index = rand(0, $count - 1);
			$result .= mb_substr($chars, $index, 1);
		}

		return $result;
	}
	
	function sendNewPass($pass, $mail){
		
		$naglowki = "Reply-to: info@co.wkaliszu.pl <info@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "From: info@co.wkaliszu.pl <info@co.wkaliszu.pl>".PHP_EOL;
	    $naglowki .= "MIME-Version: 1.0".PHP_EOL;
	    $naglowki .= "Content-type: text/html; charset=UTF-8".PHP_EOL; 
	 
		//Wiadomość najczęściej jest generowana przed wywołaniem funkcji
		$wiadomosc = '<html> 
		<head> 
			<meta charset="UTF-8" />
			<title>Przypomnienie Twojego hasła co.wkaliszu.pl</title> 
		</head>
		<body>
			<p><b>Witaj, </b></p>
			<p>Twoje nowe hasło w serwisie co.wkaliszu.pl to:<p>
			<p><strong>'.$pass.'</strong></p>
			Pozdrawiamy<br>
			Ekipa co.wkaliszu.pl
		</body>
		</html>';
	  
		mail($mail, 'Twoje nowe hasło na co.wkaliszu.pl', $wiadomosc, $naglowki);

	}
	
	function loginByFacebook($name, $m){
		$conn = sqlConnect();
		if(!isset($_COOKIE['stmh']))
		{}else{
			unset($_COOKIE['stmh']);
			setcookie('stmh', '', time() - 3600);
		}
		
		if(isNotUniqeMain($m, $conn)){
			/*LOGUJEMY*/
			$u = getUserByMail($m);
			setcookie('stmh', $u['mail_hash'],  time() + (86400 * 30));
			$_SESSION["log"] = 1;
		}else{
			$pass = generatePassword($length = 8);
			$user['pass_hash'] = md5($pass."@#!%!XXA@");
			$user['mail'] = $m;

			$explode = explode("@",$m);
			$user['login'] = $explode[0];
			
			$user['surname'] = "";
			$user['phone'] = "";
			
			$user['mail_hash'] = md5($user['mail']."!!@$%SACZ@!EDA%!%!@ZXC".$user['login']);
			
			addNewUser($conn, $user);
			zaloguj($user['login'], $user['pass_hash'], $conn);
			sqlClose($conn);
		}
	}
?>