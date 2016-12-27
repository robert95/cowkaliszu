$(document).ready(function(){
	updateFileds();
	mapaStart();
});
$("#adress").change(function(){zaznacz($(this).val())});
$("#id_kat").change(updateFileds);
function updateFileds(){
	updateDescFileds();
	updateFilterFileds();
}
function updateDescFileds(){
	var idC = $("#id_kat").val();
	var idP = $("#place_id").val();
	var url = "place-ajax.php?action=getDescFieldsForCat&idP=" + idP + "&idC=" + idC;
	$.ajax({
		url: url
	}).done(function(data) {
		$("#place-desc-fileds").html(data);
		$(".hours-field").keyup(updateOpenHours);
		$(".hours-field").keyup(function(){
			validateHoursFiled($(this).data('index'));
		});
		showOpenHours();
		updateHoursDisabledFields();
	});
}
function updateFilterFileds(){
	var idC = $("#id_kat").val();
	var idP = $("#place_id").val();
	var url = "place-ajax.php?action=getFilterFieldsForCat&idP=" + idP + "&idC=" + idC;
	$.ajax({
		url: url
	}).done(function(data) {
		$("#place-filters").html(data);
	});
}

var mapa;
var marker;
var jest = false;
var geocoder = new google.maps.Geocoder();
var ikona;
function dodajMarker(latlng){
	jest = true;
	marker = new google.maps.Marker({  
					position: latlng,
					map: mapa,
					icon: ikona
				});
}
	
function mapaStart(){  
	var wspolrzedne = new google.maps.LatLng($("#ax").val(),$("#ay").val());
	var opcjeMapy = {
		zoom: 14,
		center: wspolrzedne,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableDefaultUI: true,
	};
	mapa = new google.maps.Map(document.getElementById("place_on_map"), opcjeMapy); 

	//cechy ikon markera
	var rozmiar = new google.maps.Size(37,49);
	var punkt_startowy = new google.maps.Point(0,0);
	var punkt_zaczepienia = new google.maps.Point(18,49);
	
	// ikonka makrera
	ikona = new google.maps.MarkerImage("img/place_marker.png", rozmiar, punkt_startowy, punkt_zaczepienia);
	
	dodajMarker(new google.maps.LatLng($("#ax").val(),$("#ay").val()));
	google.maps.event.addListener(mapa,'click',function(zdarzenie)
	{
		if(zdarzenie.latLng)	
		{
			if(jest) marker.setMap(null);
			dodajMarker(zdarzenie.latLng);
			$("#ax").val(marker.getPosition().lat());
			$("#ay").val(marker.getPosition().lng());
			geocodePosition(marker.getPosition());
		}
	});
}  

function geocodePosition(pos){
	geocoder.geocode({
		latLng: pos
	}, function(responses) {
			if (responses && responses.length > 0) {
			$("#adress").val(responses[0].formatted_address);
			validateAddress();
		} else {
			alert('Przykro mi, nie wiem co to za adres:( Spróbuj wskazać miejsce w pobiżu');
		}
	});
}

function zaznacz(adres){
	geocoder.geocode({address: adres}, obslugaGeokodowania);
}
function obslugaGeokodowania(wyniki, status){
	if(status == google.maps.GeocoderStatus.OK){
		if(jest) marker.setMap(null);
		mapa.setCenter(wyniki[0].geometry.location);
		dodajMarker(wyniki[0].geometry.location);
		$("#ax").val(wyniki[0].geometry.location.lat());
		$("#ay").val(wyniki[0].geometry.location.lng());
	}
	else{
		showAddressError();
		//alert("Przykro mi, nie wiem gdzie to:( Na pewno podałeś poprawny adres?")
	}
}

function updateOpenHours(){
	var hours = [];
	if($(".place-hours-edit .checkbox-square").eq(0).is(":checked") && $("input[name=active_open_hours]:checked").val() == 1){
		hours[0] = $("#godzPonOd").val();
		hours[1] = $("#godzPonDo").val();
	}else{
		hours[0] = '';
		hours[1] = '';
	}
	if($(".place-hours-edit .checkbox-square").eq(1).is(":checked") && $("input[name=active_open_hours]:checked").val() == 1){
		hours[2] = $("#godzWtOd").val();
		hours[3] = $("#godzWtDo").val();
	}else{
		hours[2] = '';
		hours[3] = '';
	}
	if($(".place-hours-edit .checkbox-square").eq(2).is(":checked") && $("input[name=active_open_hours]:checked").val() == 1){
		hours[4] = $("#godzSrOd").val();
		hours[5] = $("#godzSrDo").val();
	}else{
		hours[4] = '';
		hours[5] = '';
	}
	if($(".place-hours-edit .checkbox-square").eq(3).is(":checked") && $("input[name=active_open_hours]:checked").val() == 1){
		hours[6] = $("#godzCzOd").val();
		hours[7] = $("#godzCzDo").val();
	}else{
		hours[6] = '';
		hours[7] = '';
	}
	if($(".place-hours-edit .checkbox-square").eq(4).is(":checked") && $("input[name=active_open_hours]:checked").val() == 1){
		hours[8] = $("#godzPtOd").val();
		hours[9] = $("#godzPtDo").val();
	}else{
		hours[8] = '';
		hours[9] = '';
	}
	if($(".place-hours-edit .checkbox-square").eq(5).is(":checked") && $("input[name=active_open_hours]:checked").val() == 1){
		hours[10] = $("#godzSobOd").val();
		hours[11] = $("#godzSobDo").val();
	}else{
		hours[10] = '';
		hours[11] = '';
	}
	if($(".place-hours-edit .checkbox-square").eq(6).is(":checked") && $("input[name=active_open_hours]:checked").val() == 1){
		hours[12] = $("#godzNdzOd").val();
		hours[13] = $("#godzNdzDo").val();
	}else{
		hours[12] = '';
		hours[13] = '';
	}
	$("#hours-field-input").val(JSON.stringify(hours));
}

$(".hours-field").keyup(updateOpenHours);

function showOpenHours(){
	var hours = JSON.parse($("#hours-field-input").val());
	var isOpenHours = false;
	if(hours[0] != "" && hours[1] != ""){
		$("#godzPonOd").val(hours[0]);
		$("#godzPonDo").val(hours[1]);
		$("#gh1").attr('checked', true);
		isOpenHours = true;
	}else{
		$("#godzPonOd").val("");
		$("#godzPonDo").val("");
		$("#gh1").attr('checked', false);
	}
	if(hours[2] != "" && hours[3] != ""){
		$("#godzWtOd").val(hours[2]);
		$("#godzWtDo").val(hours[3]);
		$("#gh2").attr('checked', true);
		isOpenHours = true;
	}else{
		$("#godzWtOd").val("");
		$("#godzWtDo").val("");
		$("#gh2").attr('checked', false);
	}
	if(hours[4] != "" && hours[5] != ""){
		$("#godzSrOd").val(hours[4]);
		$("#godzSrDo").val(hours[5]);
		$("#gh3").attr('checked', true);
		isOpenHours = true;
	}else{
		$("#godzSrOd").val("");
		$("#godzSrDo").val("");
		$("#gh3").attr('checked', false);
	}
	if(hours[6] != "" && hours[7] != ""){
		$("#godzCzOd").val(hours[6]);
		$("#godzCzDo").val(hours[7]);
		$("#gh4").attr('checked', true);
		isOpenHours = true;
	}else{
		$("#godzCzOd").val("");
		$("#godzCzDo").val("");
		$("#gh4").attr('checked', false);
	}
	if(hours[8] != "" && hours[9] != ""){
		$("#godzPtOd").val(hours[8]);
		$("#godzPtDo").val(hours[9]);
		$("#gh5").attr('checked', true);
		isOpenHours = true;
	}else{
		$("#godzPtOd").val("");
		$("#godzPtDo").val("");
		$("#gh5").attr('checked', false);
	}
	if(hours[10] != "" && hours[11] != ""){
		$("#godzSobOd").val(hours[10]);
		$("#godzSobDo").val(hours[11]);
		$("#gh6").attr('checked', true);
		isOpenHours = true;
	}else{
		$("#godzSobOd").val("");
		$("#godzSobDo").val("");
		$("#gh6").attr('checked', false);
	}
	if(hours[12] != "" && hours[13] != ""){
		$("#godzNdzOd").val(hours[12]);
		$("#godzNdzDo").val(hours[13]);
		$("#gh7").attr('checked', true);
		isOpenHours = true;
	}else{
		$("#godzNdzOd").val("");
		$("#godzNdzDo").val("");
		$("#gh7").attr('checked', false);
	}
	if(!isOpenHours){
		$("input[name=active_open_hours]").eq(0).attr('checked', true); 
		toogleHoursDescField();
	}
}

function toogleHoursFields(obj, i){
	if(obj.is(":checked")){
		$(".hours-field").eq(i).attr('disabled', false);
		$(".hours-field").eq(i+1).attr('disabled', false);
	}else{
		$(".hours-field").eq(i).attr('disabled', true);
		$(".hours-field").eq(i+1).attr('disabled', true);
		if($(".hours-field").eq(i).val() == ""){
			$(".hours-field").eq(i).val("07:00");
			$(".hours-field").eq(i+1).val("16:00");
		}
	}
}

function updateHoursDisabledFields(){
	$(".place-hours-edit .checkbox-square").each(function(index){
		toogleHoursFields($(this), index*2);
		if(index+1 == $(".place-hours-edit .checkbox-square").length){
			updateOpenHours();
		}
	});
}

function toogleHoursDescField(){
	if($("input[name=active_open_hours]:checked").val() == 0){
		$("#hours_details").hide();
		updateOpenHours();
	}else{
		$("#hours_details").show();
		updateOpenHours();
	}
}

var isCorrectForm = false;
$("input[name=name]").change(validPlaceName);
function validPlaceName(){
	if($("input[name=name]").val() == ""){
		$("#empty-place-name").show();
		$("input[name=name]").addClass("error-input");
		isCorrectForm = false;
	}else{
		$("#empty-place-name").hide();
		$("input[name=name]").removeClass("error-input");
		var idP = $("#place_id").val();
		var url = "place-ajax.php?action=checkPlace&id=" + idP + "&name="+encodeURIComponent($("input[name=name]").val());
		$.ajax({
			url: url
		}).done(function(data) {
			if(parseInt(data)>0){
				$("#ununique-place-name").show();
				$("input[name=name]").addClass("error-input");
				isCorrectForm = false;
			}else{
				$("#ununique-place-name").hide();
				$("input[name=name]").removeClass("error-input");
			}
		});
	}
}

$("#adress").change(validateAddress);
function validateAddress(){
	if($("#adress").val() == ""){
		showAddressError();
		isCorrectForm = false;
	}else{
		$("#empty-place-address").hide();
		$("#adress").removeClass("error-input");
	}
}

function showAddressError(){
	$("#empty-place-address").show();
	$("#adress").addClass("error-input");
}


$("#place_desc").change(validateDesc);
function validateDesc(){
	if($("#place_desc").val() == ""){
		$("#empty-place-desc").show();
		$("#place_desc").addClass("error-input");
		isCorrectForm = false;
	}else{
		$("#empty-place-desc").hide();
		$("#place_desc").removeClass("error-input");
	}
}

function validateHoursFiled(i){
	var patt = new RegExp("^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$");
	if($("#gh"+parseInt(i+1)).is(":checked")){
		if(!patt.test($(".hours-field").eq(i*2).val())){
			$(".hours-field").eq(i*2).addClass("error-input");
			isCorrectForm = false;
		}else{
			$(".hours-field").eq(i*2).removeClass("error-input");
		}
		if(!patt.test($(".hours-field").eq(i*2+1).val())){
			$(".hours-field").eq(i*2+1).addClass("error-input");
			isCorrectForm = false;
		}else{
			$(".hours-field").eq(i*2+1).removeClass("error-input");
		}
	}else{
		$(".hours-field").eq(i*2).removeClass("error-input");
		$(".hours-field").eq(i*2+1).removeClass("error-input");
	}
}

function validateAllHoursFileds(){
	for(var i = 0; i < 7; i++){
		validateHoursFiled(i);
	}
}

var continueWithOutPhoto = false;
function validatePic(){
	if($("#img").val() == "" && $("#main_picture").attr('src') == ""){
		if(!continueWithOutPhoto){
			$("#confirm-adding-without-image").show();
			isCorrectForm = false;
		} 
	}
}

function acceptPhotoWarning(){
	continueWithOutPhoto = true;
	$("#confirm-adding-without-image").hide();
	$("#place-add-form").submit();
	hidePhotoWarning();
}

function hidePhotoWarning(){
	$("#confirm-adding-without-image").hide();
}

function validateForm(event){
	if(!isCorrectForm) event.preventDefault();
	isCorrectForm = true;
	validPlaceName();
	validateAddress();
	validateDesc();
	if($("input[name=active_open_hours]:checked").val() == 1){
		validateAllHoursFileds();
	}
	if(isCorrectForm){
		validatePic();
	}
	setTimeout(function(){
		if(isCorrectForm){
			$("#place-add-form").submit();
		}
	}, 200);
}

function getIsCorrectForm(){
	return isCorrectForm
}