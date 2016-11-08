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
				echo '<p class="desc-field place-hours-edit">
									<label>
										'.$name.'<br>
										<input type="hidden" id="hours-field-input" name="descStaticField['.$d['id'].']" class="cat-form-name" value=\''.$val.'\' placeholder="'.$name.'">
									</label>';
				echo '<span>Pon:</span> <input class="hours-field" type="text" id="godzPonOd" name="godzPonOd"> - <input class="hours-field" type="text" id="godzPonDo" name="godzPonDo"><br>';
				echo '<span>Wt:</span> <input class="hours-field" type="text" id="godzWtOd" name="godzWtOd"> - <input class="hours-field" type="text" id="godzWtDo" name="godzWtDo"><br>';
				echo '<span>Śr:</span> <input class="hours-field" type="text" id="godzSrOd" name="godzSrOd"> - <input class="hours-field" type="text" id="godzSrDo" name="godzSrDo"><br>';
				echo '<span>Cz:</span> <input class="hours-field" type="text" id="godzCzOd" name="godzCzOd"> - <input class="hours-field" type="text" id="godzCzDo" name="godzCzDo"><br>';
				echo '<span>Pt:</span> <input class="hours-field" type="text" id="godzPtOd" name="godzPtOd"> - <input class="hours-field" type="text" id="godzPtDo" name="godzPtDo"><br>';
				echo '<span>Sob:</span> <input class="hours-field" type="text" id="godzSobOd" name="godzSobOd"> - <input class="hours-field" type="text" id="godzSobDo" name="godzSobDo"><br>';
				echo '<span>Ndz:</span> <input class="hours-field" type="text" id="godzNdzOd" name="godzNdzOd"> - <input class="hours-field" type="text" id="godzNdzDo" name="godzNdzDo"><br>';
				echo '</p>';
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
	}
?>