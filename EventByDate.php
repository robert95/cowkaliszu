<?php
	include_once 'mysql.php';
	include_once 'function.php';
	$conn = sqlConnect();
		
	$data = $_GET['data'];
	$eventList = getEventByData($conn, $data);
	$wyj = "";
	if($eventList=="")
	{
		$wyj = "<span class='noEvent'>Nie ma zaplanowanych wydarzeń na ten dzień</span>";
	}
	else{
		$i = 0;
		foreach ($eventList as $e)
		{
			$kat = getCategory($conn, $e["id_kat"]);
			$place = getPlace($conn, $e["id_miejsce"]);
			$fblink = "";
            $link = linkToEvent($e["id"]);
			
			$liked_icon = isLiked($e["id"], $conn) == 0?'img/add_to_fav.png':'img/del_to_fav.png';
			if($e['cena'] != ""){
				if($e['cena'] == "0") $price = "wstęp wolny";
				else $price = "Od ".$e['cena']." zł";
			}else $price = "";	
			
			$wyj .= '<div onmouseover="zaznaczNaMapie(this);" class="event" id="e_'.$i.'" data-x="'.$place['x'].'" data-y="'.$place['y'].'" data-title="'.$e['nazwa'].'" data-place="'.$place['nazwa'].'" data-id="'.$e['id'].'" data-date="'.$e['data'].'" data-time="'.$e['czas'].'" data-date_end="'.$e['data_end'].'" data-time_end="'.$e['czas_end'].'" data-idkat="'.$e['id_kat'].'">
						<div class="ev_th thumb_event" onclick="location.href=\''.$link.'\'"><img src="'.$e['miniatura'].'" alt="'.$e['nazwa'].'" onload="fitThumbSize();"/></div>
						<div class="event_desc" onclick="location.href=\''.$link.'\'">
							<div class="event-day-cat"><img src="'.$kat["obrazek"].'" alt="'.$kat["nazwa"].'"/><span> '.$kat["nazwa"].'</span></div>
							<h4><a href="'.$link.'">'.$e['nazwa'].'</a></h4>
							<h5>'.$place['nazwa'].'</h5>
							<h6>g. '.substr($e['czas'], 0, 5).' | '.$price.'</h6>
						</div>
						<p><img src="'.$liked_icon.'" alt="Dodaj do ulubionych" onClick="add_to_like('.$e["id"].', this)" data-id="'.$e["id"].'" class="liked_icon"/><!--<div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button"></div>--></p>
					</div>';
			$i++;
		}
		$ilNaStr = 10000;
		if($i>$ilNaStr)
		{
			$wyj .= '<div id="switcher_event"><a onclick="naStrone(1);">1</a> ';
			$j = intval($i/$ilNaStr);
			for($k = 1; $k <= $j; $k++)
			{
				$wyj .= '<a onclick="naStrone('.($k+1).');">'.($k+1).'</a> ';
			}
			$wyj .= '</div>';								
		}
	}
	echo $wyj;
	sqlClose($conn);
?>