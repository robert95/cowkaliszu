$(".delete_cat").click(function() {
	if($(this).data('check') == 0){
		 $(this).attr("src", "img/p_checked.png");
		 $(this).data('check', 1);
	}
	else{
		 $(this).attr("src", "img/p_nochecked.png");
		 $(this).data('check', 0);
	}
});
$(".no").click(function(){
	$("#confirm_delete").hide();	
});
$(".yes").click(function(){
	if($(this).data("target") == "1"){
		$.each($('.delete_cat'), function(index, value) { 
			if($(this).data('check') == 1){
				id = $(this).data('id');
				xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","usuneve.php?id=" + id,true);
				xmlhttp.send();
				setTimeout(function(){ location.reload(); }, 1000);
			}	
		});
	}
	if($(this).data("target") == "-1"){
		$.each($('.delete_cat_archiv'), function(index, value) { 
			if($(this).data('check') == 1){
				id = $(this).data('id');
				xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","usuneve.php?id=" + id,true);
				xmlhttp.send();
				setTimeout(function(){ location.reload(); }, 1000);
			}	
		});
	}
	if($(this).data("target") == "2"){
		$.each($('.delete_event_waiting'), function(index, value) { 
			if($(this).data('check') == 1){
				id = $(this).data('id');
				xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","usuneve.php?id=" + id,true);
				xmlhttp.send();
				setTimeout(function(){ location.reload(); }, 1000);
			}	
		});
	}
	if($(this).data("target") == "3"){
		var id = $(this).attr('data-targetGroup');
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","usuneveGroup.php?id=" + id,true);
		xmlhttp.send();
		setTimeout(function(){ location.reload(); }, 1000);
	}
})
$("[name='usun']").click(function() {
	$("#confirm_delete").show();
	$(".yes").attr("data-target", "1");
});

$(".delete_cat_archiv").click(function() {
	if($(this).data('check') == 0){
		 $(this).attr("src", "img/p_checked.png");
		 $(this).data('check', 1);
	}
	else{
		 $(this).attr("src", "img/p_nochecked.png");
		 $(this).data('check', 0);
	}
});

$("[name='usunZArchiwum']").click(function() {
	$("#confirm_delete").show();
	$(".yes").attr("data-target", "-1");
});

$(".delete_event_waiting").click(function() {
	if($(this).data('check') == 0){
		 $(this).attr("src", "img/p_checked.png");
		 $(this).data('check', 1);
	}
	else{
		 $(this).attr("src", "img/p_nochecked.png");
		 $(this).data('check', 0);
	}
});

$("[name='usunZPoczekalni']").click(function() {
	$("#confirm_delete").show();
	$(".yes").attr("data-target", "2");
});

$(".main_ev").click(function() {
	$.each($(".main_ev"), function(index, value) { 
		$(this).attr("src", "img/p_main_nochecked.png");
		$(this).data('check', 0);
	});
	
	$(this).attr("src", "img/p_main_checked.png");
	$(this).data('check', 1);
	
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","setMainEvent.php?id=" + $(this).data('id'),true);
	xmlhttp.send();
});

$("[name='edytuj']").click(function() {
	var id = $(this).data('id');
	window.location.href = "editevent.php?id=" + id;
});

var $rows = $('#p_table tr');
$('#search').keyup(function() {
	    
	    var val = '^(?=.*\\b' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b') + ').*',
	        reg = RegExp(val, 'i'),
	        text;
	    
	    $rows.show().filter(function() {
	        text = $(this).text().replace(/\s+/g, ' ');
	        return !reg.test(text);
	    }).hide();
		
		$('.must_visability').show();
	});
	
$(".accept").click(function() {
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","akcepteve.php?id=" + $(this).data('id'),true);
	xmlhttp.send();
	setTimeout(function(){location.reload(); }, 500);
});

$(".recommend_ev").click(function() {
	if($(this).data('check') == 0){
		$(this).attr("src", "img/p_checked.png");
		$(this).data('check', 1);
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","recEve.php?id=" + $(this).data('id') + "&c=1",true);
		xmlhttp.send();
	}
	else{
		$(this).attr("src", "img/p_nochecked.png");
		$(this).data('check', 0);
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","recEve.php?id=" + $(this).data('id') + "&c=0",true);
		xmlhttp.send();
	}
});

$(".unlike img").click(function() {
	var id = $(this).data('id');
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","deleteLiked.php?id=" + id,true);
	xmlhttp.send();
	setTimeout(function(){ alert("Wydarzenie zostalo usunięte z ulubionych!");location.reload(); }, 1000);
});

$(".delete_acount a").click(function() {
	$("#confirm_delete").show();
});
$(".yes_acount").click(function(){
	location.replace("deleteMe.php");	
});

$(".acceptGroup").click(function() {
	var link = "akceptevegroup.php?id=" + $(this).data('group');
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET",link,true);
	xmlhttp.send();
	setTimeout(function(){location.reload(); }, 500);
});

$(".deleteGroup").click(function() {
	$("#confirm_delete").show();
	$(".yes").attr("data-target", "3");
	$(".yes").attr("data-targetGroup", $(this).data('group'));
});

$(".showAllofGroup").click(function() {
	if($(this).val() == "Zwiń") action = false;
	else action = true;
	var group = $(this).data('group');
	$.each($("#waiting_events tr"), function(index, value) { 
		if($(this).data('nrgrupa') == group){
			if(action) $(this).show("slow");
			else $(this).hide("slow");
		} 
	});
	
	if(action) $(this).val("Zwiń");
	else $(this).val("Rozwiń");
	
});
