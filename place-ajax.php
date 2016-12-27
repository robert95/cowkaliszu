<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$action = $_GET['action'];
	if($action == "getDescFieldsForCat"){
		$idP = $_GET['idP'];
		$idC = $_GET['idC'];
		$descfields = getDescFieldForParent($idC , 1);
		$descStaticfields = getStaticFieldForParent($idC , 1);
		foreach($descStaticfields as $d){
			$val = getDescFieldVal($d['id'], 0, $idP, 1);
			$val = count($val) > 0 ? $val[0]['value'] : ""; 
			$name = getNameForStaticField($d['id_field']);
			if($name == "godziny otwarcia" || $name == "Godziny otwarcia"){
				echo '<div class="desc-field place-hours-edit">
									<label>
										Godziny otwarcia<br>
										<input type="hidden" id="hours-field-input" name="descStaticField['.$d['id'].']" class="cat-form-name" value=\''.$val.'\' placeholder="'.$name.'">
									</label>';
				echo '<div onclick="toogleHoursDescField();">';
				echo '<label class="rad"><input type="radio" checked name="active_open_hours" value="0"><i></i> brak godzin otwarcia</label><br>';
				echo '<label class="rad"><input type="radio" checked name="active_open_hours" value="1"><i></i> ustaw godziny otwarcia</label>';
				echo '</div><div id="hours_details">';
				echo '<input onclick="updateHoursDisabledFields()" type="checkbox" id="gh1" class="checkbox-square" name="hourOpen[0]" data-index="0" checked value="1"><label for="gh1"></label><span>Pon:</span> <input class="hours-field firstInput" type="text" data-index="0" id="godzPonOd" name="godzPonOd" value="07:00"> - <input class="hours-field" type="text" id="godzPonDo" name="godzPonDo" value="16:00"><br>';
				echo '<input onclick="updateHoursDisabledFields()" type="checkbox" id="gh2" class="checkbox-square" name="hourOpen[1]" data-index="1" checked value="1"><label for="gh2"></label><span>Wt:</span> <input class="hours-field firstInput" type="text" data-index="1" id="godzWtOd" name="godzWtOd" value="07:00"> - <input class="hours-field" type="text" id="godzWtDo" name="godzWtDo" value="16:00"><br>';
				echo '<input onclick="updateHoursDisabledFields()" type="checkbox" id="gh3" class="checkbox-square" name="hourOpen[2]" data-index="2" checked value="1"><label for="gh3"></label><span>Śr:</span> <input class="hours-field firstInput" type="text" data-index="2" id="godzSrOd" name="godzSrOd" value="07:00"> - <input class="hours-field" type="text" id="godzSrDo" name="godzSrDo" value="16:00"><br>';
				echo '<input onclick="updateHoursDisabledFields()" type="checkbox" id="gh4" class="checkbox-square" name="hourOpen[3]" data-index="3" checked value="1"><label for="gh4"></label><span>Cz:</span> <input class="hours-field firstInput" type="text" data-index="3" id="godzCzOd" name="godzCzOd" value="07:00"> - <input class="hours-field" type="text" id="godzCzDo" name="godzCzDo" value="16:00"><br>';
				echo '<input onclick="updateHoursDisabledFields()" type="checkbox" id="gh5" class="checkbox-square" name="hourOpen[4]" data-index="4" checked value="1"><label for="gh5"></label><span>Pt:</span> <input class="hours-field firstInput" type="text" data-index="4" id="godzPtOd" name="godzPtOd" value="07:00"> - <input class="hours-field" type="text" id="godzPtDo" name="godzPtDo" value="16:00"><br>';
				echo '<input onclick="updateHoursDisabledFields()" type="checkbox" id="gh6" class="checkbox-square" name="hourOpen[5]" data-index="5" checked value="1"><label for="gh6"></label><span>Sob:</span> <input class="hours-field firstInput" type="text" data-index="5" id="godzSobOd" name="godzSobOd" value="07:00"> - <input class="hours-field" type="text" id="godzSobDo" name="godzSobDo" value="16:00"><br>';
				echo '<input onclick="updateHoursDisabledFields()" type="checkbox" id="gh7" class="checkbox-square" name="hourOpen[6]" data-index="6" checked value="1"><label for="gh7"></label><span>Ndz:</span> <input class="hours-field firstInput" type="text" data-index="6" id="godzNdzOd" name="godzNdzOd" value="07:00"> - <input class="hours-field" type="text" id="godzNdzDo" name="godzNdzDo" value="16:00"><br>';
				echo '</div></div>';
			}else{
				echo '<p class="desc-field">
									<label>
										'.$name.'<br>
										<input type="text" name="descStaticField['.$d['id'].']" class="cat-form-name" value="'.$val.'" placeholder="'.$name.'">
									</label>
								</p>';
			}
		}
		foreach($descfields as $d){
			$val = getDescFieldVal($d['id'], 1, $idP, 1);
			$val = count($val) > 0 ? $val[0]['value'] : ""; 
			$name = $d['name'];
			echo '<p class="desc-field">
									<label>
										'.$name.'<br>
										<input type="text" name="descField['.$d['id'].']" class="cat-form-name" value="'.$val.'" placeholder="'.$name.'">
									</label>
								</p>';
		}
	}else if($action == "getFilterFieldsForCat"){
		$idP = $_GET['idP'];
		$idC = $_GET['idC'];
		
		$filters = getFiltersForParent($idC, 1);
		foreach($filters as $f){
			$type = $f['type'] == 2 ? 'radio': 'checkbox';
			$arr = $f['type'] == 2 ? '': '[]';
			echo '<div class="place-filter-elem">
				<p>'.$f['name'].'</p>';
			$fields = getFiltersForFilter($f['id']);
			$tmpFirstLoop = true;
			foreach($fields as $ff){
				$val = getFilterFieldVal($ff['id'], $idP, 1);
				$val = count($val) > 0 ? $val[0]['value'] : 0; 
				$checked = $val == 1 ? "checked" : "";
				if($type == "radio" && $tmpFirstLoop) $checked = "checked";
				$styleClass = $type == "radio" ? "rad" : "ckb";
				echo '<label class="'.$styleClass.'"><input type="'.$type.'" '.$checked.' name="filter['.$f['id'].']'.$arr.'" value="'.$ff['id'].'"><i></i> '.$ff['name'].'</label>';
				$tmpFirstLoop = false;
			}
			echo '</div>';
		}
	}else if($action == "checkPlace"){
		$slug = makeSlug($_GET['name']);
		$id = addslashes($_GET['id']);
		$place = getPlaceBySlug($slug);
		if(count($place) == 0){
			echo 0;
		}else{
			if($place['id'] == $id) echo 0;
			else echo 1;
		}
	}
?>