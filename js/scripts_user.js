$(".delete_user").click(function() {
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
		$.each($('.delete_user'), function(index, value) { 
			if($(this).data('check') == 1){
				id = $(this).data('id');
				xmlhttp=new XMLHttpRequest();
				xmlhttp.open("GET","usunUser.php?id=" + id,true);
				xmlhttp.send();
				setTimeout(function(){ location.reload(); }, 1000);
			}	
		});
	}
})
$("[name='usun']").click(function() {
	$("#confirm_delete").show();
	$(".yes").attr("data-target", "1");
});