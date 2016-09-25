<?php
	include_once 'mysql.php';
	include_once 'function.php';
	$conn = sqlConnect();
		
	$data = (isset($_GET['data']) && $_GET['data'] != "") ? $_GET['data'] : date("Y-m-d");
	//var_dump($data);
	$eventList = getEventByData($conn, $data);
	$temp_date = strtotime( $data );
	$dayInWeek = date( 'N', $temp_date );
	$weekDay = getFullPolishDayName($dayInWeek);
	$polishDate = convertToCoolDate($data).' '.substr($data, 0, 4);
	$wyj = "";
	if($eventList=="")
	{
		//$wyj = "<span class='noEvent'>Nie ma zaplanowanych wydarzeń na ten dzień</span>";
		$wyj = "";
	}
	else{
		$wyj = '<div class="day-in-calendar">
					<p class="day-header"><span>'.$weekDay.', </span> '.$polishDate.'</p>
					<div class="events-in-day-container">';
		$i = 0;
		foreach ($eventList as $e)
		{
			$kat = getCategory($conn, $e["id_kat"]);
			$place = getPlace($conn, $e["id_miejsce"]);
            $link = linkToEvent($e["id"]);
			$fblink = "";
			
			$liked_icon = isLiked($e["id"], $conn) == 0?'img/add_to_fav.png':'img/del_to_fav.png';
			if($e['cena'] != ""){
				if($e['cena'] == "0") $price = "wstęp wolny";
				else $price = "Od ".$e['cena']." zł";
			}else $price = "";	
			
			$free = $price != "wstęp wolny" ? "false" : "true";
			
			$wyj .= '<div class="event-day event"  onmouseover="zaznaczNaMapie(this);" id="e_'.$i.'" data-x="'.$place['x'].'" data-y="'.$place['y'].'" data-title="'.$e['nazwa'].'" data-place="'.$place['nazwa'].'" data-id="'.$e['id'].'" data-date="'.$e['data'].'" data-time="'.$e['czas'].'" data-date_end="'.$e['data_end'].'" data-time_end="'.$e['czas_end'].'" data-idkat="'.$e['id_kat'].'" data-free="'.$free.'">
						<a href="'.$link.'">
							<img src="'.$e['miniatura'].'" alt="'.$e['nazwa'].'"  class="eve-thumb">
						</a>
						<div class="event-day-desc">
							<p class="cat-name"><img src="'.$kat["obrazek"].'" alt="'.$kat["nazwa"].'"> '.$kat["nazwa"].'</p>
							<p class="event-title"><a href="'.$link.'">'.$e['nazwa'].'</a></p>
							<div class="external-info">
								<a href="#" class="place-link">'.$place['nazwa'].'</a>
								<p>g. '.substr($e['czas'], 0, 5).' | '.$price.'</p>
							</div>
							<div class="share-like-btns">
								<p><img src="'.$liked_icon.'" alt="Dodaj do ulubionych" onClick="add_to_like('.$e["id"].', this)" data-id="'.$e["id"].'" class="liked_icon"/><img src="img/facebook-icon.png" class="fb-share-event" data-href="'.$link.'"></p>
							</div>
						</div>
					</div>';
			$i++;
		}
		$wyj .= '</div>
			</div>';
	}
	
	if($wyj == "" && isLastDayWithEvents($conn, $data)) $wyj = "false";
	echo $wyj;
	sqlClose($conn);
?>