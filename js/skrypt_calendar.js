var data = new Date();
var markers = new Array();
var mapa;
var ikona;
var dymek = new google.maps.InfoWindow();
var filterData = new Date();
var lastData;
$( document ).ready(function() {
	filterData = $("#filterData").val()!="" ? $("#filterData").val() : new Date();
	$("#datepicker").datepicker('setDate', filterData);
	/*data = wczoraj(data);
	odswiezSwitcher(true);*/
	//$("#datepicker").hide();
	mapaStart();
	
	var s = $("#stick_map");
    var pos = s.position();
	var off = s.offset();
	
    $(window).scroll(function() {
		var windowpos = $(window).scrollTop();
		var widthParent = s.width();
        if (windowpos+40 >= off.top ) {
            s.addClass("stick");
			$("#empty_stick_map").show();
			s.css("left", off.left);
			s.css("width", widthParent);
        } else {
            s.removeClass("stick"); 
			$("#empty_stick_map").hide();
			s.css("left", 0);
			s.attr('style', function(i, style){return style.replace(/width[^;]+;?/g,'');
			});
        }
		if($(window).scrollTop()+100 > $(document).height() - $(window).height()) {
			loadMoreEvents();
		}
    });
	
	$(document).mouseup(function (e){
		var container = $("#datepicker");

		if (!container.is(e.target) 
			&& container.has(e.target).length === 0)
		{
			container.hide();
		}
	});
	
	$('.fb-share-event').click(function() {
		var link = $(this).data('href');
		FB.ui({
			display: 'popup',
			method: 'share',
			href: link,
		}, function(response) {});
	});
});

Date.prototype.sameDay = function(d) {
  return this.getFullYear() === d.getFullYear()
    && this.getDate() === d.getDate()
    && this.getMonth() === d.getMonth();
}
$(function() {
	$( "#datepicker" ).datepicker({
		dateFormat: 'yy-mm-dd',
		dayNamesMin: [ "Nie", "Pon", "Wt", "Śr", "Cz", "Pt", "So" ],
		monthNames: [ "styczeń", "luty", "marzec", "kwiecień", "maj", "czerwiec", "lipiec", "sierpień", "wrzesień", "październik", "listopad", "grudzień" ],
		beforeShowDay: function(d) {
			var today = new Date(); 
			if(today < d || d.sameDay(today)){
				return [true,"",""]; 
			}else{
				return [true,"beforeToday",""]; 
			}
		},
		firstDay: 1,
		defaultDate: filterData
	});
	odwiedzWydarzenia();
});
$("#datepicker").on("change",function(){
	setLink();
	odwiedzWydarzenia();
	$("#datepicker").hide();	
});

function jutro(dzis){
	var jutro = new Date(dzis.valueOf() + 86400000); //nastepny dzien
	return jutro;
}

function wczoraj(dzis){
	var jutro = new Date(dzis.valueOf() - 86400000); //poprzedni dzien
	return jutro;
}

function odswiezSwitcher(bool){
	if(bool) data = jutro(data);
	else data = wczoraj(data);
	
	var p1 = wczoraj(data);
	var p2 = wczoraj(p1);
	var p3 = wczoraj(p2);
	
	var n1 = jutro(data);
	var n2 = jutro(n1);
	var n3 = jutro(n2);
	
	var wyj = '<a class="nextDay" onclick="odswiezSwitcher(false);"><</a> ';
	wyj += '<a onclick="zmienDate(-3)" >' + p3.getDate() + '</a> ';
	wyj += '<a onclick="zmienDate(-2)">' + p2.getDate() + '</a> ';
	wyj += '<a onclick="zmienDate(-1)">' + p1.getDate() + '</a> ';
	wyj += '<a class="today">' + data.getDate() + '</a> ';
	wyj += '<a onclick="zmienDate(1)">' + n1.getDate() + '</a> ';
	wyj += '<a onclick="zmienDate(2)">' + n2.getDate() + '</a> ';
	wyj += '<a onclick="zmienDate(3)">' + n3.getDate() + '</a> ';
	wyj += '<a class="prevDay" onclick="odswiezSwitcher(true);">></a>';
	
	var wyj2 = polskaNazwaMiesiaca(data.getMonth()) + ' ' + data.getFullYear();
	$("#switcher_date").html(wyj);
	$("#mmYY").text(wyj2);	
	/*odwiedzWydarzenia();
	odwiezMape();
	$("#datepicker").hide();*/
}

function zmienDate(x){
	if(x<0)
	{
		x = Math.abs(x);
		for(i = 0; i < x; i++)
			data = wczoraj(data);
	}
	else{
		for(i = 0; i < x; i++)
			data = jutro(data);
	}
	
	data = wczoraj(data);
	odswiezSwitcher(true);
}

function polskaNazwaMiesiaca(a){
	names = ['styczeń','luty','marzec','kwiecień','maj','czerwiec','lipiec','sierpień','wrzesień','październik','listopad','grudzień'];
	return names[a];
}

function pokaz(){
	$("#datepicker").show();
	/*odwiedzWydarzenia();*/
}

function getFormattedDate(d){
	var dzien = (d.getDate() < 10) ? "0" + (d.getDate()) : (d.getDate());
	var miesiac = (d.getMonth()+1 < 10) ? "0" + (d.getMonth()+1) : (d.getMonth()+1);
	var validFormatData = d.getFullYear() + "-" + miesiac + "-" +  dzien;
	return validFormatData;
}

var blockLoadNewEvents = false;
var newDayisLoaded = false;
var allDayisLoaded = false;
var lastDataWithEvent = false;
function odwiedzWydarzenia(d, a){
	if(lastDataWithEvent != d) allDayisLoaded = false;
	if(!blockLoadNewEvents){
		blockLoadNewEvents = true;
		if(!allDayisLoaded) $(".loading-panel").show();
		newDayisLoaded = false;
		if(!d) d = $("#datepicker").val();
		if(!a) a = false;
		var adresurl = "EventByDate.php?data=" + d;
		$.ajax({url: adresurl, success: function(result){
			blockLoadNewEvents = false;
			if(result == ""){
				odwiedzWydarzenia(getFormattedDate(jutro(new Date(d))), a);
				return;
			}else if(result != "false"){
				lastData = d;
				if(a) $(".event-list").append(result);
				else $(".event-list").html(result);
				newDayisLoaded = true;
			}else if(result == "false"){
				if(!a) $(".event-list").html("");
				allDayisLoaded = true;
				lastDataWithEvent = d;
			}
			if(newDayisLoaded){
				runFilter();
				checkAreVisableEvents();
				checkAreVisableEventsInDay();
				checkLastAddedVisibleEvents();
				odwiezMape();
			}
			$(".loading-panel").hide();
		}});
	}
}

function loadMoreEvents(){
	odwiedzWydarzenia(getFormattedDate(jutro(new Date(lastData))), true);
	blockLoadNewEvents = true;
}

function checkLastAddedVisibleEvents(){
	if($('.day-in-calendar').last().children().children(".event:visible").length == 0) loadMoreEvents();
}

function checkAreVisableEvents(){
	if($(".event:visible").length == 0) $(".no-events").show();
	else $(".no-events").hide();
}

function checkAreVisableEventsInDay(){
	$('.day-in-calendar').each(function(){
		$(this).show();
		if($(this).children().children(".event:visible").length == 0) $(this).hide();
	});
	checkAreVisableEvents();
}

function removeDuplicates(){
	$('.day-in-calendar').each(function(){
		$(this).show();
		if($(this).children().children(".event:visible").length == 0) $(this).hide();
	});
}

/*function naStrone(x){
	var tab = new Array();
	$.each($('.cat_ch'), function(index, value) { 
		if($(this).attr('data-check') == '1'){
			var idKat = $(this).data('id');
			kom = "[data-idkat = " + idKat + "]";
			tab = tab.concat($(kom).toArray());
		}
	});
	//console.log(tab);
	
	$(".event").hide();
	var ilNaStr = 10000;
	for(var i = (x-1)*ilNaStr; i < ((x-1)*ilNaStr+ilNaStr); i++)
	{
		$(tab[i]).show();
	}
	odwiezMape();
}*/

function dodajMarker(latlng, id, tytul, miejsce, data, czas, data_end, czas_end)
{
	var juzJest = false;
	for (var i = 0; i < markers.length; i++) {
		if(markers[i].getPosition().lat() == latlng.lat() && markers[i].getPosition().lng() == latlng.lng()){
			juzJest = true;
			var ico = dajIkone(data, czas, data_end, czas_end);
			if(ico == "img/m_trwa.gif"){
				markers[i].setIcon(ico);
				var temp = markers[i].txt;
				markers[i].txt = "<a class='marker' href='event-" + id + "-.html'>" + tytul + "</a><br>" + miejsce + inProgess(data, czas, data_end, czas_end) + "<hr>";
				markers[i].txt += temp;
			}else{
				markers[i].txt += "<hr><a class='marker' href='event-" + id + "-.html'>" + tytul + "</a><br>" + miejsce + inProgess(data, czas, data_end, czas_end);
			}
		}
	}
	if(!juzJest){
		var ico = dajIkone(data, czas, data_end, czas_end);
		var marker = new google.maps.Marker({  
						position: latlng,
						map: mapa,
						icon: ico,
						optimized: false,
					});
		marker.txt = "<a class='marker' href='event-" + id + "-.html'>" + tytul + "</a><br>" + miejsce + inProgess(data, czas, data_end, czas_end);
		
		google.maps.event.addListener(marker,"mouseover",function()
		{
			dymek.setContent(marker.txt);
			dymek.open(mapa,marker);
		});
		
		markers.push(marker);		
	}	
}
	
function mapaStart()  
{ 
	var wspolrzedne = new google.maps.LatLng(51.76316992,18.08625057);
	var opcjeMapy = {
		zoom: 14,
		center: wspolrzedne,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableDefaultUI: true,
	};
	mapa = new google.maps.Map(document.getElementById("map"), opcjeMapy); 

	var rozmiar = new google.maps.Size(37,49);
	var punkt_startowy = new google.maps.Point(0,0);
	var punkt_zaczepienia = new google.maps.Point(18,49);
	
	// ikonki
	ikona = new google.maps.MarkerImage("znak.png", rozmiar, punkt_startowy, punkt_zaczepienia);
	
	//dodajMarker(wspolrzedne, 2, "tytuł", "miejsce");
}  

function odwiezMape()
{
	usunMarkery();
	$('.event').each(function(i, obj) {
		if($(this).is(":visible")){
			var wsp = new google.maps.LatLng($(this).data('x'),$(this).data('y'));
			var tit = $(this).data('title');
			var id = $(this).data('id');
			var place = $(this).data('place');
			var d = $(this).data('date');
			var t = $(this).data('time');
			var dE = $(this).data('date_end');
			var tE = $(this).data('time_end');

			dodajMarker(wsp, id, tit, place, d, t, dE, tE);
		} 
	});
	
	fitThumbSize();
	
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

function usunMarkery()
{
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers = [];
}

/*function inProgess(d, t, dE, tE){
	var d1 = new Date();
	d1 = d1.valueOf();
	var d2 = new Date(d + " " + t);
	d2 = d2.valueOf();
	var d2e = new Date(dE + " " + tE);
	d2e = d2e.valueOf();
	
	var r2 = d2e-d1; //różnica czasu zakończenia od obecnego
	if(r2 < 0){
		return false //zakończone
	}
	else{
		var r = d2-d1;
		if(r < 0){
			return true; //trwające 
		}
		else return false; //jeszcze się nie zaczęło
	}
}*/
function dajIkone(d, t, dE, tE)
{
	var d1 = new Date();
	d1 = d1.valueOf();
	var d2 = new Date(d + " " + t);
	d2 = d2.valueOf();
	var d2e = new Date(dE + " " + tE);
	d2e = d2e.valueOf();
	
	
	var r2 = d2e-d1; //różnica czasu zakończenia od obecnego
	if(r2 < 0){
		return 'img/m_df.png'; //zakończone
	}
	else{
		var r = d2-d1;
		if(r < 0){
			return 'img/m_trwa.gif'; //trwające 
		}
		else{
			r = r / 1000;
			r /= 60;
			r /= 60; //r-różnica w godzinach
			r = Math.floor(r); //różnica w pełnych godzinach
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
}

function inProgess(d, t, dE, tE){
	var d1 = new Date();
	d1 = d1.valueOf();
	var d2 = new Date(d + " " + t);
	d2 = d2.valueOf();
	var d2e = new Date(dE + " " + tE);
	d2e = d2e.valueOf();
	
	
	var r2 = d2e-d1; //różnica czasu zakończenia od obecnego
	if(r2 < 0){
		return " - ZAKOŃCZONE!"; //zakończone
	}
	else{
		var r = d2-d1;
		if(r < 0){
			return "<span class='inProgresEvent'> - Właśnie trwa!</span>"; //trwające 
		}
		else{
			r = r / 1000;
			r /= 60;
			r /= 60; //r-różnica w godzinach
			r = Math.floor(r); //różnica w pełnych godzinach
			if(r < 23){
				var x = r+1;
				return " - Za niecałe " + (x) + " godzin!";
			}
			else{
				r /= 24; //r-różnica w dniach
				r = Math.floor(r); //różnica w pełnych dniach
				if(r < 7)
				{
					var x = r+1;
					return "- Za niecałe " + (x) + " dni!";
				}else{
					return -2;
				}
			}
		}		
	}
}
function doKalendarza(){
	 $('html, body').animate({
        scrollTop: $("#event_calendar").offset().top
    }, 1000);
	$("#checklist_categorie").show("slow");
}

function zaznaczNaMapie(a){
	var stop = false;
	var x = $(a).data('x');
	x = parseFloat(x).toFixed(4);
	var y = $(a).data('y');
	y = parseFloat(y).toFixed(4);
	
	var place = $(a).data('place');
	
	for (var i = 0; i < markers.length && !stop; i++) {
		if(parseFloat(markers[i].getPosition().lat()).toFixed(4) == x && parseFloat(markers[i].getPosition().lng()).toFixed(4) == y){
			 zaznaczNaMapiePom(markers[i], place);
			stop = true;
		}
	}	
}

function zaznaczNaMapiePom(marker, place){
	dymek.setContent(place);
	dymek.open(mapa,marker);
}