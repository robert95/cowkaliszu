$(document).ready(function(){
	mapaStart();
});
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

	new google.maps.Marker({  
			position: wspolrzedne,
			map: mapa,
			icon: ikona
		});
}  