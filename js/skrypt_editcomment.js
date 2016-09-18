function editCommentSend(data){
	$.ajax({
	   type: "GET",
	   url: "editComment.php",
	   data: data, 
	   success: function(data)
	   {
		   refresh_comment($("#id_item").val());
	   }
	});
}

function editComment(obj){
	$(obj).parent('.option-btns').siblings('.editComment-panel').show();
	$(obj).parent('.option-btns').siblings('p').hide();
}

function acceptEditComment(obj){
	var data = $(obj).siblings('.editComment').serialize();
	editCommentSend(data);
}