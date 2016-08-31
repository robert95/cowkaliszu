function setPriorytets(){
	var prio = $('.ui-state-default').size();
	$.each($(".ui-state-default"), function(index, value) { 
		$(this).attr('data-pri',prio);
		prio--;
	});
}
$("#changePrio").click(function(){
	setPriorytets();
	
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","usunpriorytety.php", true);
	xmlhttp.send();
	xmlhttp.onreadystatechange=function()
	{	
		$.each($(".ui-state-default"), function(index, value) { 
		var id = $(this).data('id');
		var p = $(this).data('pri');
		xmlhttp2=new XMLHttpRequest();
		xmlhttp2.open("GET","ustawPrio.php?id=" + id + "&p=" + p);
		xmlhttp2.send();
		});
	}	
	
	alert("Pomyślnie zapisano zmiany!");
});

$("#showSortableRec").click(function(){
	$(this).hide("slow");
	$("#recommendate_events").show("slow");
});