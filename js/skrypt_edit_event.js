$( document ).ready(function() {
   mapaStart();
})
var mapa;
var marker;
var jest = true;
//var geocoder = new google.maps.Geocoder();
function dodajMarker(latlng)
{
	marker = new google.maps.Marker({  
				position: latlng,
					map: mapa,
				});
}
	
function mapaStart()  
{ 
	var x = $('[name="place"]').find(':selected').data('x');
	var y = $('[name="place"]').find(':selected').data('y');
	x = Number(x);
	y = Number(y);
	var wspolrzedne = new google.maps.LatLng(x,y);
	var opcjeMapy = {
		zoom: 15,
		center: wspolrzedne,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableDefaultUI: true,
	};
	mapa = new google.maps.Map(document.getElementById("eEv_map"), opcjeMapy); 

	dodajMarker(wspolrzedne);
	
	/*google.maps.event.addListener(mapa,'click',function(zdarzenie)
	{
		if(zdarzenie.latLng)	
		{
			if(jest) marker.setMap(null);
			dodajMarker(zdarzenie.latLng);
			$('[name="wX"]').val(marker.getPosition().lat());
			$('[name="wY"]').val(marker.getPosition().lng());
			geocodePosition(marker.getPosition());
		}
	});*/
}  

/*function geocodePosition(pos) {

  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
		$("#adress_place").val(responses[0].formatted_address);
    } else {
      alert('Przykro mi, nie wiem co to za adres:( Spróbuj wskazać miejsce w pobiżu');
    }
  });
}*/

function zaznacz(adres)
{
	marker.setMap(null);
	var x = $('[name="place"]').find(':selected').data('x');
	var y = $('[name="place"]').find(':selected').data('y');
	x = Number(x);
	y = Number(y);
	var wspolrzedne = new google.maps.LatLng(x,y);
	dodajMarker(wspolrzedne);
	mapa.setCenter(wspolrzedne);
	//geocoder.geocode({address: adres}, obslugaGeokodowania);
}
/*function obslugaGeokodowania(wyniki, status)
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
*/
function showChangeImage(){
	$("#oldImage").hide("slow");
	$("#changeImage").show("slow");
}
function checkTimeLastTime(){
	$("#time").val($( "#labelH" ).text());
	$("#time_end").val($( "#labelM" ).text());
}
function confirmEdition() {
	checkTimeLastTime();
	$("#confirm_delete").show();
	return 0;
};
$(".yes").click(function(){
	$("[name='submit']").click();
});
$("[name='submitEdition']").click(function (){
	confirmEdition();	
});
$(".no").click(function(){
	$("#confirm_delete").hide();	
});