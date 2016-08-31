function validate(obj){
	$(obj).css("background", "white");
	if($(obj).val() == ""){
		$(obj).css("background", "rgb(247, 108, 108)");
		return false;
	}
	return true;
}

function validateALL(){
	var dalej = true;
	$(".toValidate").each(function( index ) {
		if(validate($(this)) == false) dalej = false;
	});
	if($(".toValidateCheckBox").length > 0 ) {
		if($(".toValidateCheckBox:checked").length < 1 ) {
			$("#accept_reg").css('color', 'red');
			dalej = false;
		}else{
			$("#accept_reg").css('color', 'white');
		}
	}
	
	return dalej;
}