$( document ).ready(function() {
	
});
/********-- Filtrowanie po kategoriach tylko w kalendarzu --********/
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
