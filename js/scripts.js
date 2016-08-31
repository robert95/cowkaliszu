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

$("[name='usun']").click(function() {
	$.each($('.delete_cat'), function(index, value) { 
		if($(this).data('check') == 1){
			id = $(this).data('id');
			xmlhttp=new XMLHttpRequest();
			xmlhttp.open("GET","usunkat.php?id=" + id,true);
			xmlhttp.send();
			location.reload();
		}	
	});
});

$("[name='edytuj']").click(function() {
	var nazwa = $(this).data('nazwa');
	var id = $(this).data('id');
	$("[name='name']").attr('value', nazwa);
	$("#confirm_button").attr('value', "Edycja");
	$("input[name='id']").attr('value', id);
	$("input[name='old_name']").attr('value', nazwa);
});