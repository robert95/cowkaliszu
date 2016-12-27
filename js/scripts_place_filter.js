$( document ).ready(function() {
	updateActiveCats();
	setActivCatList();
});
/********-- Filtrowanie --********/
$(".cat-list li").click(function() {
	var filter = $(this).parent();
	var clickOpt = $(this);
	if(filter.find("li").length == filter.find(".activ-option").length){
		filter.find(".activ-option").removeClass('activ-option');
		clickOpt.addClass('activ-option');
	}else{
		if(filter.data('type') == 1){
			if(clickOpt.hasClass('activ-option')){
				clickOpt.removeClass('activ-option');
				if(filter.find(".activ-option").length == 0){
					filter.find("li").addClass('activ-option');
				}
			}else{
				clickOpt.addClass('activ-option');
			}
		}else if(filter.data('type') == 2){
			if(clickOpt.hasClass('activ-option')){
				clickOpt.removeClass('activ-option');
				if(filter.find(".activ-option").length == 0){
					filter.find("li").addClass('activ-option');
				}
			}else{
				filter.find(".activ-option").removeClass('activ-option');
				clickOpt.addClass('activ-option');
			}
		}
	}
});

$('.accept-filter').click(function() {
	setLink();
	runFilter();
	setActivCatList();
	$(".cat-filter-container").hide('fade');
});

$('.back-filter').click(function() {
	$(".cat-filter-container").hide('fade');
	updateActiveCats();
});

$('.show-cat-filter-btn').click(function() {
	$(".cat-filter-container").show('fade');
});

function runFilter(){
	var filters = [];
	$('.cat-list').each(function(){
		if($(this).find(".activ-option").length > 0 && $(this).find(".activ-option").length < $(this).find("li").length){
			filters.push($(this).data('type'));
			var fields = [];
			$(this).find(".activ-option").each(function(){
				fields.push($(this).data('id'));
			});
			filters.push(fields);
		}
	});
	$('.event').show();
	for(var i = 0; i < filters.length; i+=2) {
		filtersIds = filters[i+1];
		$('.event').each(function(){
			var fIds = $(this).data('filters') + '';
			console.log(fIds);
			fIds = fIds.split('-');
			var notInFilter = true;
			for(var i = 0; i < filtersIds.length; i++){
				if(fIds.indexOf(filtersIds[i].toString()) > -1)
					notInFilter = false;
			}
			if(notInFilter) $(this).hide();
		});
	}
	
	odwiezMape();
}

function setLink(){
	var link = "/miejsca/" + $("#place-cat-slug").val();
	$(".cat-list").each(function(){
		var parent = $(this);
		var name = $(this).data('slug');
		if(parent.find("li").length != parent.find(".activ-option").length && parent.find(".activ-option").length > 0){
			link += "/" + name + "/";
			parent.find( ".activ-option" ).each(function() {
				link += $(this).data('slu') + "-";
			});
			link = link.slice(0, -1);
		}
	});
	link += ".html";
	//ZMIANA po przeniesieniu na serwer!!! usunąć /cowkaliszu
	window.history.pushState("", "", '/cowkaliszu'+link);
}

function updateActiveCats(){
	var arr = window.location.pathname.split('/');
	var filters = [];
	$( ".cat-list").each(function(){
		filters.push($(this).data('slug'));
	});
	for (i=0; i < arr.length; i+=2){
		k = arr[i];
		v = arr[i+1];
		if(filters.indexOf(k) > -1){
			var parent = false;
			$( ".cat-list").each(function(){
				if($(this).data('slug') == k){
					parent = $(this);
				}
			});
			var filterFields = v.split('-');
			filterFields[filterFields.length-1] = filterFields[filterFields.length-1].replace(".html", "");
			parent.find( "li" ).each(function( index ) {
				var slu = $(this).data('slu');
				if(filterFields.indexOf(slu) > -1) $(this).addClass('activ-option');
				else $(this).removeClass('activ-option');
				if(index+1 == parent.find( "li" ).length){
					if(parent.find( ".activ-option" ).length == 0){
						parent.find("li").addClass('activ-option');
					}
				}
			});
		}
	}
}

function setActivCatList(){
	$(".list-of-activ-filter-fields").html("");
	$(".cat-list").each(function(){
		if(!($(this).find("li").length == $(this).find(".activ-option").length)){
			$(this).find('.activ-option').each(function(){
				var name = $(this).text();
				var id = $(this).data('id');
				var html = '<div>' + name + '<img src="img/confirmW_no.png" alt="Usun" onclick="uncheckThisCat(' + id + ');"></div>';
				$(".list-of-activ-filter-fields").append(html);
			});
		}
	});	
}

function uncheckThisCat(id){
	$('.cat-list .activ-option').each(function(){
		if(id == $(this).data('id')){
			$(this).click();
			$('.accept-filter').click();
		}
	});
}
/********-- KONIEC Filtrowania KONIEC--********/

/********-- MAPA --********/

var markers = new Array();
var mapa;
var ikona;
var dymek = new google.maps.InfoWindow();
$( document ).ready(function() {
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
    });
	
	runFilter();
});

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
	// ikona
	ikona = new google.maps.MarkerImage("img/place_marker.png", rozmiar, punkt_startowy, punkt_zaczepienia);
}  

function dodajMarker(latlng, tytul, link, adres)
{
	var juzJest = false;
	for (var i = 0; i < markers.length; i++) {
		if(markers[i].getPosition().lat() == latlng.lat() && markers[i].getPosition().lng() == latlng.lng()){
			juzJest = true;
			markers[i].txt += "<hr><a class='marker' href='" + link + "'>" + tytul + "</a><br>" + adres;
		}
	}
	if(!juzJest){
		var marker = new google.maps.Marker({  
						position: latlng,
						map: mapa,
						optimized: false,
						icon: ikona
					});
		marker.txt = "<a class='marker' href='" + link + "'>" + tytul + "</a><br>" + adres;
		
		google.maps.event.addListener(marker,"mouseover",function()
		{
			dymek.setContent(marker.txt);
			dymek.open(mapa,marker);
		});
		
		markers.push(marker);		
	}	
}

function odwiezMape()
{
	usunMarkery();
	$('.event').each(function(i, obj) {
		if($(this).is(":visible")){
			var wsp = new google.maps.LatLng($(this).data('x'),$(this).data('y'));
			var tit = $(this).data('title');
			var link = $(this).data('link');
			var adres = $(this).data('address');

			dodajMarker(wsp, tit, link, adres);
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

function usunMarkery()
{
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers = [];
}

function zaznaczNaMapie(a){
	var stop = false;
	var x = $(a).data('x');
	x = parseFloat(x).toFixed(4);
	var y = $(a).data('y');
	y = parseFloat(y).toFixed(4);
	
	var tit = $(a).data('title');
	var link = $(a).data('link');
	var adres = $(a).data('address');
	
	var text = "<a class='marker' href='" + link + "'>" + tit + "</a><br>" + adres;
	for (var i = 0; i < markers.length && !stop; i++) {
		if(parseFloat(markers[i].getPosition().lat()).toFixed(4) == x && parseFloat(markers[i].getPosition().lng()).toFixed(4) == y){
			zaznaczNaMapiePom(markers[i], text);
			stop = true;
		}
	}	
}

function zaznaczNaMapiePom(marker, text){
	dymek.setContent(text);
	dymek.open(mapa,marker);
}
/********-- END MAPA --********/