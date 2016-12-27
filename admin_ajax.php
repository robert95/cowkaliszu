<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$conn = sqlConnect();

	if(!isset($_COOKIE['stmh'])){
		die;
	}else{
		$user = getUser($_COOKIE['stmh'], $conn);
		if($user['uprawnienia'] > 1){
			$action = $_GET['action'];
			if($action == "addrating"){
				if($_GET['id'] == -1 ) addRating($_GET['name'], $_GET['idP'], $_GET['dest']);
				else editRating($_GET['id'], $_GET['name'], $_GET['idP'], $_GET['dest']);
			}else if($action == "getratingstoeditplacecat"){
				$ratings = getRatingForParent($_GET['idP'], 1);
				foreach($ratings as $r){
					echo '<li data-id="'.$r['id'].'">'.$r['name'].'</li>';
				}
			}else if($action == "deleterating"){
				deleteRating($_GET['id']);
			}else if($action == "deleteFilter"){
				deleteFilter($_GET['id']);
				deleteFilterFieldByParent($_GET['id']);
			}else if($action == "addfilterfield"){
				if($_GET['id'] == -1 ) {
					if($_GET['name'] != "") addFilterField($_GET['name'], $_GET['idF']);
				}else{
					if($_GET['name'] != "") editFilterField($_GET['id'], $_GET['name'], $_GET['idP']);
					else deleteFilterField($_GET['id']);
				}
			}else if($action == "addfilter"){
				if($_GET['id'] == -1 ) {
					$filterID = addFilter($_GET['name'], $_GET['type'], $_GET['idP'], 1);
					addAllFilterFieldToFilter($filterID);
					$type = $_GET['type'] == 1 ? "checkbox" : "radio";
					echo '<li>
						<p class="filter-elem" data-id="'.$filterID.'" data-type="'.$_GET['type'].'" data-name="'.$_GET['name'].'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> '.$_GET['name'].' <span class="type-filter">('.$type.')</span> <span class="span-btn edit-btn-filter">edytuj</span><span class="span-btn span-btn-del del-filter" data-id="'.$filterID.'">usuń</span></p>
						<ul class="">';
					$filterFields = getFiltersForFilter($filterID);
					foreach($filterFields as $f){
						echo '<li data-idP="'.$f['id_parent_field'].'" data-id="'.$f['id'].'">'.$f['name'].'</li>';
					}
					echo '</ul>
					</li>';						
				}else{
					editFilter($_GET['id'], $_GET['name'], $_GET['type'], $_GET['idP'], 1);
					$filters = getFiltersForParent($_GET['idP'], 1);
					foreach($filters as $f){
						$type = $f['type'] == 1 ? "checkbox" : "radio";
						echo '<li>
							<p class="filter-elem" data-id="'.$f['id'].'" data-type="'.$f['type'].'" data-name="'.$f['name'].'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> '.$f['name'].' <span class="type-filter">('.$type.')</span> <span class="span-btn edit-btn-filter">edytuj</span><span class="span-btn span-btn-del del-filter" data-id="'.$f['id'].'">usuń</span></p>
							<ul class="">';
						$filterFields = getFiltersForFilter($f['id']);
						foreach($filterFields as $ff){
							echo '<li data-idP="'.$ff['id_parent_field'].'" data-id="'.$ff['id'].'">'.$ff['name'].'</li>';
						}
						echo '</ul>
						</li>';	
					}
				}
			}else if($action == "changeOrderFilter"){
				setOrder("filter", $_GET['idsArray']);
			}else if($action == "deletePlaceCat"){
				deletePlaceCat($_GET['id']);
			}
		}else{
			echo "Brak uprawnień do wykonania tej akcji";
			die;
		}
	}
	
	sqlClose($conn);
?>