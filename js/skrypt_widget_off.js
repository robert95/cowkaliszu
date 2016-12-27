$( document ).ready(function() {
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","liked_widget.php",false);
	xmlhttp.send();
	var res = xmlhttp.responseText;
	$( "body" ).append(res);
	if($( window.parent ).width() > 1450){
		$("#widget_co_w_kaliszu").addClass("widget_alway_show");
	}
	$("#widget_co_w_kaliszu").height($( window.parent ).height()-50);
});