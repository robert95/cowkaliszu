$( document ).ready(function() {
   mapaStart();
   updateShowEvent();
   updateShowEventinCat();
   checkOtherEventsShouldShown();
   
   $(".more-event").click(function(){
		showOtherEvent += 3;
		updateShowEvent();	   
   });
   
   $(".more-event-incat").click(function(){
		showOtherEventinCat += 3;
		updateShowEventinCat();	   
   });
})

var showOtherEvent = 3;
var showOtherEventinCat = 3;

var mapa;
var ikona;
var dymek = new google.maps.InfoWindow();
function dodajMarker(latlng)
{
	var marker = new google.maps.Marker({  
				position: latlng,
					map: mapa,
					icon: ikona,
				});
	var tytul = $('#event_info').data('title');
	var miejsce = $('#event_info').data('place');;
	dymek.setContent("<swap style='font-size:20px'>" + tytul + "<br></swap>" + miejsce);
	dymek.open(mapa,marker);
}
	
function mapaStart()  
{ 
	var x = $('#x').data('x');
	var y = $('#y').data('y');
	x = Number(x);
	y = Number(y);
	var wspolrzedne = new google.maps.LatLng(x,y);
	var opcjeMapy = {
		zoom: 15,
		center: wspolrzedne,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableDefaultUI: true,
	};
	mapa = new google.maps.Map(document.getElementById("event_on_map"), opcjeMapy); 

	var rozmiar = new google.maps.Size(37,49);
	var punkt_startowy = new google.maps.Point(0,0);
	var punkt_zaczepienia = new google.maps.Point(18,49);
	
	// ikonki
	ikona = new google.maps.MarkerImage("img/m_df.png", rozmiar, punkt_startowy, punkt_zaczepienia);
	
	
	dodajMarker(wspolrzedne);
}  

function updateShowEvent(){
	$('.other-ev-in-place .event').show();
	$.each($('.other-ev-in-place .event'), function(index, value) { 
		if($(this).index() > showOtherEvent){
			$(this).hide();
		}
	});
	if(showOtherEvent >= $('.other-ev-in-place .event').length){
		$(".more-event").hide();
	}
}

function updateShowEventinCat(){
	$('.other-ev-in-cat .event-in-same-cat').show();
	$.each($('.other-ev-in-cat .event-in-same-cat'), function(index, value) { 
		if($(this).index() > showOtherEventinCat){
			$(this).hide();
		}
	});
	if(showOtherEventinCat >= $('.other-ev-in-cat .event-in-same-cat').length){
		$(".more-event-incat").hide();
	}
}

function checkOtherEventsShouldShown(){
	if($('.other-ev-in-cat .event-in-same-cat').length == 0){
		$(".other-ev-in-cat").hide();
	}
	if($('.other-ev-in-place .event').length == 0){
		$(".other-ev-in-place").hide();
	}
}
