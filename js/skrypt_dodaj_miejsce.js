function checkPlace(){
	if($( "[name='id_place']" ).val() == -1){
		alert("Podaj miejsce!");
		return false;
	}
	return true;
}

$(".set_place").click(function() {
		$( "[name='id_place']" ).val($(this).data('id'));
		$( "#next" ).click();
});
$(".checkPlaceByLetter").click(function() {
		
		var letter = $(this).text();
		$( ".set_place" ).each(function( index ) {
			if($( this ).text().charAt(0) == letter){
				$( this ).css("color" , "white");
			}
			else{
				$( this ).css("color" , "#3b3b3a");
			}
		});
});
$(".add_new_place").click(function() {
		$("#add_new_place").show( "slow" );
		$("#map").show( "slow" );
		$('html, body').animate({
			scrollTop: $("#add_new_place").offset().top - 20
		}, 1000);
		setTimeout(function(){
			mapaStart();
		}, 1000);
		
});
$("#close_add").click(function() {
		$("#add_new_place").hide( "slow" );
});
$("#zaznaczNaMapie").click(function() {
		 mapaStart();
});

var mapa;
var marker;
var jest = false;
var geocoder = new google.maps.Geocoder();
function dodajMarker(latlng)
{
	jest = true;
	marker = new google.maps.Marker({  
					position: latlng,
					map: mapa,
				});
}
	
function mapaStart()  
{  
	var wspolrzedne = new google.maps.LatLng(51.7587738,18.0871296);
	var opcjeMapy = {
		zoom: 10,
		center: wspolrzedne,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableDefaultUI: true,
	};
	mapa = new google.maps.Map(document.getElementById("map"), opcjeMapy); 

	//cechy ikon markera
	var rozmiar = new google.maps.Size(37,49);
	var punkt_startowy = new google.maps.Point(0,0);
	var punkt_zaczepienia = new google.maps.Point(18,49);
	
	// ikonka makrera
	ikona = new google.maps.MarkerImage("znak.png", rozmiar, punkt_startowy, punkt_zaczepienia);
	
	google.maps.event.addListener(mapa,'click',function(zdarzenie)
	{
		if(zdarzenie.latLng)	
		{
			if(jest) marker.setMap(null);
			dodajMarker(zdarzenie.latLng);
			$("#x").val(marker.getPosition().lat());
			$("#y").val(marker.getPosition().lng());
			geocodePosition(marker.getPosition());
		}
	});
}  

function geocodePosition(pos) {

  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
		$("#input_adress").val(responses[0].formatted_address);
    } else {
      alert('Przykro mi, nie wiem co to za adres:( Spróbuj wskazać miejsce w pobiżu');
    }
  });
}

function zaznacz(adres)
{
	geocoder.geocode({address: adres}, obslugaGeokodowania);
}
function obslugaGeokodowania(wyniki, status)
	{
		if(status == google.maps.GeocoderStatus.OK)
		{
			if(jest) marker.setMap(null);
			mapa.setCenter(wyniki[0].geometry.location);
			dodajMarker(wyniki[0].geometry.location);
			$("#x").val(wyniki[0].geometry.location.lat());
			$("#y").val(wyniki[0].geometry.location.lng());
		}
		else
		{
			alert("Przykro mi, nie wiem gdzie to:( Na pewno podałeś poprawny adres?")
		}
	}

$( document ).ready(function() {

});

function showPanelAddPlace(){
	$("#add_place_panel").show();
	setTimeout(function(){
		mapaStart();
	}, 3000);
}