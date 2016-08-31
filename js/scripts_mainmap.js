var markers = new Array();
var mapa;
var ikona;
var dymek = new google.maps.InfoWindow();
$(function() {
    $( "#slider-vertical" ).slider({
      orientation: "vertical",
      range: "min",
      min: 0,
      max: 24,
	  step: 1,
      value: 18,
      slide: function( event, ui ) {
		$( "#label" ).text((24 - ui.value) + " h");
		zaznacz(24 - ui.value);
      }
    });
	$( "#slider span").html("<div id='label'></div>");
    $( "#label" ).text((24 - $( "#slider-vertical" ).slider( "value" )) +" h");
	//zaznacz(24 - $( "#slider-vertical" ).slider( "value" ));
});

$( document ).ready(function() {
	mapaStart();
	zaznacz(24 - $( "#slider-vertical" ).slider( "value" ));
});

function mapaStart()  
{ 
	var wspolrzedne = new google.maps.LatLng(51.76316992,18.08625057);
	var opcjeMapy = {
		zoom: 13,
		center: wspolrzedne,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
	};
	mapa = new google.maps.Map(document.getElementById("big_map"), opcjeMapy); 

	var rozmiar = new google.maps.Size(37,49);
	var punkt_startowy = new google.maps.Point(0,0);
	var punkt_zaczepienia = new google.maps.Point(18,49);
	
	// ikonki
	ikona = new google.maps.MarkerImage("znak.png", rozmiar, punkt_startowy, punkt_zaczepienia);
	
	//dodajMarker(wspolrzedne, 2, "tytuł", "miejsce");
}  

function zaznacz(x){
	usunMarkery();
	zaktualizujCzasy();
	$('.event').each(function(i, obj) {
		var s = $(this).data('start');
		if( s < x)
		{
			var wsp = new google.maps.LatLng($(this).data('x'),$(this).data('y'));
			var id = $(this).data('id');
			var tytul = $(this).data('tit');
			var miejsce = $(this).data('pla');
			var start = $(this).data('start');
			dodajMarker(wsp, id, tytul, miejsce, start);
		}
	});
	
	var bounds = new google.maps.LatLngBounds();

	for (var i = 0; i < markers.length; i++) {
		bounds.extend(markers[i].getPosition());
	}
	if(!bounds.isEmpty()){
		mapa.fitBounds(bounds);
		if(markers.length == 1){
			mapa.setZoom(18);
		}
	}
}

function dajIkone(r)
{
	if(r < 0){
		return 'img/m_trwa.gif';
	}
	else{
		if(r < 23){
			var x = r+1;
			return 'img/m_' + x + 'h.png';
		}
		else{
			r /= 24; //r-różnica w dniach
			r = Math.floor(r); //różnica w pełnych dniach
			if(r < 7)
			{
				var x = r+1;
				return 'img/m_' + x + 'd.png';
			}else{
				return 'img/m_df.png';
			}
		}
	}
}

function zaktualizujCzasy()
{
	$('.event').each(function(i, obj) {
		var d = $(this).data('d');
		var t = $(this).data('t');
		var d1 = new Date();
		d1 = d1.valueOf();
		var d2 = new Date(d + " " + t);
		d2 = d2.valueOf();
		var r = d2-d1;
		if(r < 0){
			$(this).attr('data-start', r);
		}
		else{
			r = r / 1000;
			r /= 60;
			r /= 60; //r-różnica w godzinach
			r = Math.floor(r);
			$(this).attr('data-start', r);
		}	 
	});
}

function usunMarkery()
{
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers.length = 0;
}

function dodajMarker(latlng, id, tytul, miejsce, start)
{
	var juzJest = false;
	for (var i = 0; i < markers.length; i++) {
		if(markers[i].getPosition().lat() == latlng.lat() && markers[i].getPosition().lng() == latlng.lng()){
			juzJest = true;
			var infoProgres = "- Za niecałe " + (start+1) + " godzin!";
			if(start < 0) infoProgres = "<span class='inProgresEvent'> - Właśnie trwa!</span>";
			markers[i].txt += "<hr><a class='marker' href='event.php?id=" + id + "'>" + tytul + "</a><br>" + miejsce + infoProgres;
		}
	}
	if(!juzJest){
		var ico = dajIkone(start);
		var marker = new google.maps.Marker({  
						position: latlng,
						map: mapa,
						icon: ico,
						optimized: false,
					});
		var infoProgres = "- Za niecałe " + (start+1) + " godzin!";
		if(start < 0) infoProgres = "<span class='inProgresEvent'> - Właśnie trwa!</span>";
		marker.txt = "<a class='marker' href='event.php?id=" + id + "'>" + tytul + "</a><br>" + miejsce + infoProgres;
		
		google.maps.event.addListener(marker,"mouseover",function()
		{
			dymek.setContent(marker.txt);
			dymek.open(mapa,marker);
		});
	
		markers.push(marker);
	}
}