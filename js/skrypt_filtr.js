$( document ).ready(function() {
	
});
/********-- Filtrowanie po kategoriach tylko w kalendarzu --********/
$(".head-filter").click(function() {
	var obj = $(this);
	if(obj.parent('.filter-elem').hasClass('extendend-filter')){
		obj.parent('.filter-elem').removeClass('extendend-filter');
		obj.next(".extend-part-filter").slideUp();
	}else{
		obj.parent('.filter-elem').addClass('extendend-filter');
		obj.next(".extend-part-filter").slideDown();
	}
});

$(".cat-list li").click(function() {
	$(".cat-filter-btn").addClass('activ-btn');
	$(".cat-list").removeClass('active-all-options');
	var clickOpt = $(this);
	if($(".cat-list li").length == $(".cat-list .activ-option").length){
		$(".cat-list .activ-option").removeClass('activ-option');
		clickOpt.addClass('activ-option');
	}else{
		if(clickOpt.hasClass('activ-option')){
			clickOpt.removeClass('activ-option');
			if($(".cat-list .activ-option").length == 0){
				$(".cat-list").addClass('active-all-options');
				$(".cat-list li").addClass('activ-option');
			}
		}else{
			clickOpt.addClass('activ-option');
			/*if($(".cat-list li").length == $(".cat-list .activ-option").length){
				$(".cat-list").addClass('active-all-options');
			}else{
				$(".cat-list").removeClass('active-all-options');
			}*/
		}
	}
	setLink();
	runFilter();
});

$(".price-list li").click(function() {
	$(".price-filter-btn").addClass('activ-btn');
	$(".price-list").removeClass('active-all-options');
	var clickOpt = $(this);
	if($(".price-list li").length == $(".price-list .activ-option").length){
		$(".price-list .activ-option").removeClass('activ-option');
		clickOpt.addClass('activ-option');
	}else{
		if(clickOpt.hasClass('activ-option')){
			clickOpt.removeClass('activ-option');
			if($(".price-list .activ-option").length == 0){
				$(".price-list").addClass('active-all-options');
				$(".price-list li").addClass('activ-option');
			}
		}else{
			clickOpt.addClass('activ-option');
		}
	}
	setLink();
	runFilter();
});

$("#datepicker").on("change",function(){
	setLink();		
});

$( "#checklist_categorie, .b_k_s" ).hover(
  function() {
    if(!($("#checklist_categorie").is(":visible")))
		$("#checklist_categorie").show("slow");
  }, function() {
    //$("#checklist_categorie").hide("slow");
  }
);
$( "#checklist_categorie" ).hover(
  function() {
    if(!($("#checklist_categorie").is(":visible")))
		$("#checklist_categorie").show("slow");
  }, function() {
    $("#checklist_categorie").hide("slow");
  }
);
function pokazKat(){
	if(!($("#checklist_categorie").is(":visible")))
		$("#checklist_categorie").show("slow");
}
function schowajKat(){
	$("#checklist_categorie").hide("slow");
}
$(".cat_ch").click(function() {
	if($(this).attr('data-id') == -1){
		if($(this).attr('data-check') == '0'){
			checkAllcat();
			$(this).removeClass("nocheck_cat");
			$(this).attr('data-check', '1');
		}
		else{
			uncheckAllcat();
			$(this).addClass("nocheck_cat");
			$(this).attr('data-check', '0');
		}	
	}else{
		if($(this).attr('data-check') == '0'){
			 $(this).removeClass("nocheck_cat");
			 $(this).attr('data-check', '1');
			 filtruj();
		}
		else{
			 $(this).addClass("nocheck_cat");
			 $(this).attr('data-check', '0');
			 $("#k_all").addClass("nocheck_cat");
			 $("#k_all").attr('data-check', '0');
			 filtruj();
		}	
	}
});
function checkAllcat(){
	$.each($('.cat_ch'), function(index, value) { 
		$(this).removeClass("nocheck_cat");
		$(this).attr('data-check', '1');
		filtruj();
	});
}
function uncheckAllcat(){
	$.each($('.cat_ch'), function(index, value) { 
		$(this).addClass("nocheck_cat");
		$(this).attr('data-check', '0');
		filtruj();
	});
}
function filtruj(){
	console.log("Pokaż wydarzenia z kat nr: ");
	$(".event").hide();
	$.each($('.cat_ch'), function(index, value) { 
		if($(this).attr('data-check') == '1'){
			if($(this).data('id') != -1){
				var k = $(this).data('id');
				console.log("Wydarzenia z kat nr: " + k);
				$.each($('.event'), function(index, value) { 
					if($(this).data('idkat') == k){
						$(this).show();
						console.log($(this).data('id'));
					}
				});
			}
		}
	});
	ogarnijPeginator();
	odwiezMape();
}

function ogarnijPeginator()
{
	var i = $('.event:visible').length;
	var wyj = "";
	if(i>5)
	{
		wyj += '<a onclick="naStrone(1);">1</a> ';
		var j = Math.floor(i/5);
		for(z = 1; z <= j; z++)
		{
			wyj += '<a onclick="naStrone(' + (z+1) + ');">' + (z+1) + '</a> ';
		}							
	}
	$('#switcher_event').html(wyj);
	
	i = 0;
	$.each($('.event'), function(index, value) { 
		if(i>5){
			$(this).hide();
		}
		i++;
	});
	
}

function runFilter(){
	var cats = [];
	var prices = [];
	$('.cat-list .activ-option').each(function(){
		cats.push($(this).data('id'));
	});
	$('.price-list .activ-option').each(function(){
		prices.push($(this).data('id'));
	});
	
	$('.event').each(function(){
		var idCat = $(this).data('idkat');
		var idPrice = $(this).data('free') == true ? 1 : 0;
		if(cats.indexOf(idCat) >= 0 && prices.indexOf(idPrice) >= 0){
			$(this).show();
		}else{
			$(this).hide();
		}
	});
	
	checkAreVisableEvents();
	checkAreVisableEventsInDay();
	odwiezMape();
}

function setLink(){
	var link = "";
	if($(".cat-list li").length != $(".cat-list .activ-option").length && $(".cat-list .activ-option").length > 0){
		link = "/kategoria/";
		$( ".cat-list .activ-option" ).each(function( index ) {
			link += $(this).data('slu') + "-";
		});
		link = link.slice(0, -1);
	}
	
	var data = $("#datepicker").val();
	if(data!="") link += "/data/"+data;
	
	if($(".price-list li").length != $(".price-list .activ-option").length && $(".price-list .activ-option").length > 0){
		link += "/cena/";
		$( ".price-list .activ-option" ).each(function( index ) {
			link += $(this).data('slu') + "-";
		});
		link = link.slice(0, -1);
	}
	
	link += ".html";
	
	//ZMIANA po przeniesieniu na serwer!!! usunąć /cowkaliszu
	window.history.pushState("", "", '/cowkaliszu'+link);
}
/********-- KONIEC Filtrowanie po kategoriach tylko w kalendarzu KONIEC--********/


/********-- Filtrowanie po kategoriach tylko na całej stronie --********/
$("#categories_list li").click(function() {
	//aktualizacja głównego wydarzenia 
	var adresurl = "mainEventForCat.php?kat=" + $(this).data('id');
	$.ajax({url: adresurl, success: function(result){
            $("#main_event").html(result);
    }});
	//aktualizacja popularnych wydarzeń
	adresurl = "popularEventForCat.php?kat=" + $(this).data('id');
	$.ajax({url: adresurl, success: function(result){
            $("#s_popular").html(result);
			$("#s_popular").prepend( "<h3>popularne</h3>" );
    }});
	//aktualizacja polecanych wydarzeń
	adresurl = "recomendateEventForCat.php?kat=" + $(this).data('id');
	$.ajax({url: adresurl, success: function(result){
            $("#recommend_events").html(result);
			$("#recommend_events").prepend( "<h3>POLECANE WYDARZENIA</h3>" );
			$("#recommend_events").append( '<div style="clear: both;"></div>' );
    }});
	var id = $(this).data('id');
	//zaznaczenie w kalendarzu wybranej kategorii
	uncheckAllcat();
	$.each($('.cat_ch'), function(index, value) {
		if($(this).data('id') == id){
			$(this).attr("src", "img/checked.png");
			$(this).attr('data-check', '1');
		}
	});
	filtruj();	
});
/********-- KONIEC Filtrowanie po kategoriach tylko na całej stronie KONIEC --********/
