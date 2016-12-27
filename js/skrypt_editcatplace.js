$( document ).ready(function(e) {
	startSortabaleField();
});
/**filters-edit**/
$("#add-new-filter-filed").click(function(){
	var newField = '<input type="text" class="cat-form-name" name="filter-field-name[]" data-id="-1" value="" placeholder="Dodaj opcję dla tego filtra">';
	$(".edit-filer-fields-list").append(newField);
});

$("#cancel-edit-filter").click(function(){
	$("#filter-id").val(-1);
	$("#filter-name").val("");
	$("#filter-checkbox").prop('checked', true);
	$("#filter-radio").prop('checked', false);
	$(".edit-filer-fields-list").html("");
	var newField = '<input type="text" class="cat-form-name" name="filter-field-name[]" data-id="-1" value="" placeholder="Dodaj opcję dla tego filtra">';
	$(".edit-filer-fields-list").append(newField);
});

var optionsToFilter = "";
function setOptionsBoxToFilter(filterFields, idP, idF, type){
	if(type == 2){
		optionsToFilter = '<select name="parentFilterField">';
		optionsToFilter += '<option value="-1">---------</option>';
		$( filterFields ).each(function( index ) {
			var idE = $(this).data('id');
			var optText = $(this).text();
			if(idE != idF){
				var selected = idP == idE ? 'selected' : '';
				optionsToFilter += '<option value="'+idE+'" '+ selected + '>'+optText+'</option>';
			}
		});
		optionsToFilter += '</select>';
	}else{
		optionsToFilter = "";
	}
}

function editFilter(obj){
	var id = $(obj).parent('.filter-elem').data('id');
	var type = $(obj).parent('.filter-elem').data('type');
	var name = $(obj).parent('.filter-elem').data('name');
	var filter_elem = $(obj).parent('.filter-elem').next('ul').children('li');
	$(".edit-filer-fields-list").html("");
	$( filter_elem ).each(function( index ) {
		var idE = $(this).data('id');
		var idP = $(this).data('idp');
		setOptionsBoxToFilter(filter_elem, idP, idE, type);
		var optText = $(this).text();
		selectbox = optionsToFilter;
		var newField = '<input type="text" class="cat-form-name" name="filter-field-name[]" data-id="' + idE + '" value="' + optText + '" placeholder="Dodaj opcję dla tego filtra">';
		$(".edit-filer-fields-list").append('<div>' + newField + selectbox + '</div>');
	});
	
	$("#filter-id").val(id);
	$("#filter-name").val(name);
	if(type == 1){
		$("#filter-checkbox").prop('checked', true);
		$("#filter-radio").prop('checked', false);
	}else{
		$("#filter-checkbox").prop('checked', false);
		$("#filter-radio").prop('checked', true);
	}
}
$(".edit-btn-filter").click(function(){
	editFilter($(this));
});

function startSortabaleRating(){
	$(".rating-field-list").sortable();
}

function endSortableRating(){
	if($(".rating-field-list div").hasClass('ui-state-default'))$(".rating-field-list").sortable("destroy"); 
    $(".rating-field-list div").removeClass('ui-state-default');
}

function startSortabaleDescFileds(){
	$(".desc-filed-sortable-container").sortable();
}

function endSortableDescFileds(){
	if($(".desc-filed-sortable-container label").hasClass('ui-state-default')) $(".desc-filed-sortable-container").sortable("destroy"); 
    $(".desc-filed-sortable-container label").removeClass('ui-state-default');
}

function startSortabaleField(){
	$( ".cat-filter-list-ul" ).sortable({
		stop: saveOrderFilter
	});
}

function endSortableField(){
	if($(".cat-filter-list-ul li").hasClass('ui-state-default')) $(".cat-filter-list-ul").sortable("destroy"); 
    $(".cat-filter-list-ul li").removeClass('ui-state-default');
}

var idFilterToDelete = -1;
var filterToDelete = null;
var filterlistToDelete = null;

$(".del-filter").click(function(){
	deleteFilter($(this));
});

function deleteFilter(obj){
	filterToDelete = $(obj).parent();
	filterlistToDelete = $(obj).parent().next('ul');
	idFilterToDelete = $(obj).parent().data('id');
	$("#confirm_delete").show();
}

function closeParent(obj){
	$(obj).parent().hide();
	idFilterToDelete = -1;
	filterToDelete = null;
	filterlistToDelete = null;
}

function deleteFiltr(){
	var url = "admin_ajax.php?action=deleteFilter&id=" + idFilterToDelete;
	$.ajax({
		url: url,
		async: false
	}).done(function(data) {
		endSortableField();
		filterToDelete.parent().remove();
		filterToDelete.remove();
		filterlistToDelete.remove();
		$("#confirm_delete").hide();
		startSortabaleField();
	});	
}

$("#add-rating-field").click(function(){
	$("#edit-rating-fields-panel").show();
	$("#rating-fields-ul li").each(function( index ) {
		var idE = $(this).data('id');
		var optText = $(this).text();
		var newField = '<div><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> <input type="text" class="cat-form-name" data-id="' + idE + '"value="' + optText + '" placeholder="Wprowadź nową nazwę pola"></div>';
		if(index == 0) $(".rating-field-list").html(newField);
		else $(".rating-field-list").append(newField);
		if(index + 1 == $("#rating-fields-ul li").length){
			startSortabaleRating();
		}
	});
});

$("#close-panel-rating-edit").click(function(){
	$(this).parent().hide();
});

$("#close-panel-desc-edit").click(function(){
	$(this).parent().hide();
});

$("#add-new-rating-field-btn").click(function(){
	endSortableRating();
	var text = $("#new-rating-field").val();
	var newField = '<div><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> <input type="text" class="cat-form-name" data-id="-1" value="' + text + '" placeholder="Wprowadź nową nazwę pola"></div>';
	$(".rating-field-list").append(newField);
	$("#new-rating-field").val("");
	startSortabaleRating();
});

$("#save-ratings-fields").click(function(){
	var id = $("#event_id").data('id');
	$("#rating-hidden-fields").html("");
	$("#rating-fields-ul").html("");
	$( ".rating-field-list input" ).each(function( index ) {
		var idR = $(this).data('id');
		var name = $(this).val();
		if(name != ""){
			var inputfield = '<input type="hidden" name="rating[]" value="' + idR + '">';
			inputfield += '<input type="hidden" name="rating[]" value="' + name + '">';
			$("#rating-hidden-fields").append(inputfield);
			
			var inputfield2 = '<li data-id="' + idR + '">' + name + '</li>';
			$("#rating-fields-ul").append(inputfield2);
		}		
	});
	$("#close-panel-rating-edit").click();
});

$("#add-desc-field").click(function(){
	$( ".static-desc-field-list label" ).each(function( index ) {
		if($(this).hasClass('canDelete')) $(this).remove();
	});
	setTimeout(function(){
		$("#desc-fields-ul li").each(function( index ) {
			var idE = $(this).data('id');
			var optText = $(this).text();
			if(optText != "static"){
				console.log(idE + " - " + optText);
				var newField = '<label class="canDelete"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> <input type="checkbox" checked class="checkbox" name="desc-field-check[]" value="' + idE + '"> <input type="text" class="text" placeholder="Wpisz nową nazwę pola" value="' + optText + '"></label>';
				$(".desc-filed-sortable-container").append(newField);
			}
			if(index+1 == $("#desc-fields-ul li").length){
				startSortabaleDescFileds();
			}
		});
		$("#edit-desc-fields-panel").show();
	}, 200);
});

$("#save-new-field-btn").click(function(){
	startSortabaleDescFileds();
	var text = $("#new-field").val();
	var newField = '<label class="canDelete"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span> <input type="checkbox" checked class="checkbox" name="desc-field-check[]" value="-1"> <input type="text" class="text" placeholder="Wpisz nową nazwę pola" value="' + text + '"></label>';
	$(".desc-filed-sortable-container").append(newField);
	$("#new-field").val("");
	endSortabaleDescFileds();
});

$("#save-fields").click(function(){
	$("#desc-hidden-fields").html("");
	$("#desc-fields-ul").html("");
	$( ".static-desc-field-list label" ).each(function( index ) {
		var checkbox = $(this).children('.checkbox');
		var text = $(this).children('.text');
		var idD = checkbox.val();
		var name = text.length > 0 ? text.val() : "static";
		if(checkbox.is(":checked")){
			var inputfield = '<input type="hidden" name="field[]" value="' + idD + '">';
			inputfield += '<input type="hidden" name="field[]" value="' + name + '">';
			$("#desc-hidden-fields").append(inputfield);
			
			var inputfield2 = '<li data-id="' + idD + '">' + name + '</li>';
			$("#desc-fields-ul").append(inputfield2);
		}
	});
	$("#close-panel-desc-edit").click();
});

$("#save-edit-filter").click(function(){
	var idP = $("#event_id").data('id');
	var id = $("#filter-id").val();
	var filterName = $("#filter-name").val();
	filterName = encodeURIComponent(filterName);
	var type = $(".filter-type:checked").val();
	if(id == -1){
		$(".edit-filer-fields-list input").each(function(index){
			var idF = $(this).data('id');
			var nameF = $(this).val();
			nameF = encodeURIComponent(nameF);
			var url = "admin_ajax.php?action=addfilterfield&idF=" + id + "&name=" + nameF + "&id=" +idF;
			$.ajax({
				url: url,
				async: false
			}).done(function(data) {
				if(index + 1 == $( ".edit-filer-fields-list input" ).length){
					var url = "admin_ajax.php?action=addfilter&id=" + id + "&idP=" + idP + "&type=" + type + "&name=" + filterName;
					$.ajax({
						url: url
					}).done(function(data) {
						$(".cat-filter-list-ul").append(data);
						$(".edit-btn-filter").click(function(){	editFilter($(this)); });
						$(".del-filter").click(function(){deleteFilter($(this));});
						$("#cancel-edit-filter").click();
					});
				}
			});	
		});
	}else{
		$(".edit-filer-fields-list input").each(function(index){
			var idF = $(this).data('id');
			var idPF = $(this).next('select').val();
			var nameF = $(this).val();
			nameF = encodeURIComponent(nameF);
			var url = "admin_ajax.php?action=addfilterfield&idF=" + id + "&name=" + nameF + "&id=" +idF + "&idP=" +idPF;
			console.log(url);
			$.ajax({
				url: url,
				async: false
			}).done(function(data) {
				if(index + 1 == $( ".edit-filer-fields-list input" ).length){
					var url = "admin_ajax.php?action=addfilter&id=" + id + "&idP=" + idP + "&type=" + type + "&name=" + filterName;
					$.ajax({
						url: url
					}).done(function(data) {
						$(".cat-filter-list-ul").html(data);
						$(".edit-btn-filter").click(function(){	editFilter($(this)); });
						$(".del-filter").click(function(){deleteFilter($(this));});
						$("#cancel-edit-filter").click();
					});
				}
			});	
		});
	}
});

$(".comments-swicher").click(function(){
	if($("#input_comments").val() == 1){
		$("#input_comments").val(0);
		$("#comment-swicher-container").addClass('comment-on');
		$("#comment-swicher-container").removeClass('comment-off');
	}else{
		$("#input_comments").val(1);
		$("#comment-swicher-container").addClass('comment-off');
		$("#comment-swicher-container").removeClass('comment-on');
	}
});

function saveOrderFilter(){
	var idsArray = [];
	var type = "filters";
	$(".cat-filter-list-ul li p").each(function(index){
		idsArray.push($(this).data("id"));
		if(index + 1 == $(".cat-filter-list-ul li p").length){
			var url = "admin_ajax.php?action=changeOrderFilter";
			$.ajax({
				url: url,
				data: {'idsArray': idsArray},
				method: "GET",
				async: false
			}).done(function(data) {
			});
		}
	});
}
/**END filters-edit**/
/**PLACE CAT LIST**/
	
var idPlaceCatToDelete = -1;

$(".admin-btns .btn-delete").click(function(){
	deletePlaceCat($(this));
});

function deletePlaceCat(obj){
	idPlaceCatToDelete = $(obj).data('id');
	$("#confirm_delete").show();
}

function closeParentDelPlaceCat(obj){
	$(obj).parent().hide();
	idPlaceCatToDelete = -1;
}

function deletePlaceCatFromDB(){
	var url = "admin_ajax.php?action=deletePlaceCat&id=" + idPlaceCatToDelete;
	$.ajax({
		url: url,
		async: false
	}).done(function(data) {
		location.reload();
	});	
}
/**END PLACE CAT LIST**/