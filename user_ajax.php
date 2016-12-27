<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$action = addslashes($_GET['action']);
	if($action == "getOpenHours"){
		$openHours = getOpenHoursForPlaceJSON(addslashes($_GET['place']));
		echo $openHours ? $openHours: "";
	}
?>