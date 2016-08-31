$(".liked_data_switch").click(function() {
	if($(this).next().is(':visible'))
	{
		$(this).next().hide("slow");
		$(this).children().first().attr('src', 'img/downArrow.png');
	}else{
		$(this).next().show("slow");
		$(this).children().first().attr('src', 'img/upArrow.png');
	}
})
function add_to_like(id, img){
	var adresurl = "addToLiked.php?id=" + id;
	$.ajax({url: adresurl, success: function(result){
		console.log(result);
		if(result == -1) alert("Zaloguj się aby dodać do ulubionych:)");
		get_like_icon(id, img);
		document.getElementById("cowkaliszu_widget").contentDocument.location.reload(true);
		showAndHidePanel();
    }});
	
}
function get_like_icon(id, img){
	var adresurl = "isLiked.php?id=" + id;
	$.ajax({url: adresurl, success: function(result){
		$(img).attr('src', result);
    }});
}
$( document ).ready(function() {
    checkLikedIcon();
});
function checkLikedIcon(){
	$('.liked_icon').each(function(i, obj) {
		var id = $(this).data('id');
		get_like_icon(id, $(this));			
	});
}

function showAndHidePanel(){
	$("#widget_co_w_kaliszu").css("right", "0");
	setTimeout(function(){
		$("#widget_co_w_kaliszu").css("right", "-250px");
		$('#widget_co_w_kaliszu').attr("style", "")
	}, 1000);
	
}