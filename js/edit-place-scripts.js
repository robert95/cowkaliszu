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
		showOpenHours();
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
		alert("Przykro mi, nie wiem gdzie to:( Na pewno podałeś poprawny adres?")
	}
}

function updateOpenHours(){
	var hours = [];
	hours[0] = $("#godzPonOd").val();
	hours[1] = $("#godzPonDo").val();
	hours[2] = $("#godzWtOd").val();
	hours[3] = $("#godzWtDo").val();
	hours[4] = $("#godzSrOd").val();
	hours[5] = $("#godzSrDo").val();
	hours[6] = $("#godzCzOd").val();
	hours[7] = $("#godzCzDo").val();
	hours[8] = $("#godzPtOd").val();
	hours[9] = $("#godzPtDo").val();
	hours[10] = $("#godzSobOd").val();
	hours[11] = $("#godzSobDo").val();
	hours[12] = $("#godzNdzOd").val();
	hours[13] = $("#godzNdzDo").val();
	$("#hours-field-input").val(JSON.stringify(hours));
}

$(".hours-field").keyup(updateOpenHours);

function showOpenHours(){
	var hours = JSON.parse($("#hours-field-input").val());
	$("#godzPonOd").val(hours[0]);
	$("#godzPonDo").val(hours[1]);
	$("#godzWtOd").val(hours[2]);
	$("#godzWtDo").val(hours[3]);
	$("#godzSrOd").val(hours[4]);
	$("#godzSrDo").val(hours[5]);
	$("#godzCzOd").val(hours[6]);
	$("#godzCzDo").val(hours[7]);
	$("#godzPtOd").val(hours[8]);
	$("#godzPtDo").val(hours[9]);
	$("#godzSobOd").val(hours[10]);
	$("#godzSobDo").val(hours[11]);
	$("#godzNdzOd").val(hours[12]);
	$("#godzNdzDo").val(hours[13]);
}