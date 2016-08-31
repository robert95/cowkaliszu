$( document ).ready(function() {
    $( "#main_picture" ).draggable({ axis: "y" });	
});
$(".savePosition").click(function(){
	var top = parseInt($('#main_picture').css('top'));
	var id = parseInt($('#main_event').data('id'));
	//alert(top);
	if(top == 0) top = 1;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","saveTheTopPosition.php?top=" + top + "&id=" + id,true);
	xmlhttp.send();
	alert("Kadra został pomyślnie ustawiony!");
})