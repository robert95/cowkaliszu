$("#show_mobile_menu").click(function (){
	if($("#mobile_main_nav table").is(":visible") == true){
		$("#mobile_main_nav table").hide();
	}else{
		$("#mobile_main_nav table").show();
	}
	
});