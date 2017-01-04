
function add_comment(){
	$('.loading-panel').show();
	$.ajax({
	   type: "GET",
	   url: "addComment.php",
	   data: $("#addNewComment").serialize(), // serializes the form's elements.
	   success: function(data)
	   {
			refresh_comment($("#id_item").val());
			$("#addNewComment textarea").val("");
			$("#addNewComment .author-comment").val("");
	   }
	});
}

var idComToDelete = -1;

function deleteComment(id){
	idComToDelete = id;
	$("#confirm_delete").show();
}

function deleteCom(){
	delete_comment();
	$("#confirm_delete").hide();
}

function closeParent(){
	$("#confirm_delete").hide();
	idComToDelete = -1;
}

function delete_comment(){
	$('.loading-panel').show();
	$.ajax({
	   type: "GET",
	   url: "deleteComment.php?id="+idComToDelete,
	   success: function(data)
	   {
		   console.log(data);
		   refresh_comment($("#id_item").val());
	   }
	});
}

function refresh_comment(id){
	$.ajax({
	   type: "GET",
	   url: "getComments.php?id="+id,
	   success: function(data)
	   {
			$(".comments-list").html(data);
			showMoreComments(0);
			$('.loading-panel').hide();
	   }
	});
}

$(document).ready(function(){
	showMoreComments();
});

function showReCommentForm(obj){
	$(obj).parent().next('.re-for-comment').show();
}

var scrollPosition = -1;
function add_re_comment(obj){
	scrollPosition = $(document).scrollTop();
	$('.loading-panel').show();
	var form = $(obj).prev('form');
	$.ajax({
	   type: "GET",
	   url: "addComment.php",
	   data: form.serialize(),
	   success: function(data)
	   {
		   commentId = data;
		   refresh_comment($("#id_item").val());
		   form.children("textarea").val("");
	   }
	});
}

var countOfShownComment = 0;
function showMoreComments(step){
	$('.loading-panel').show();
	if(!step || step != 0) step = 5;
	var shownComment = $('.comments-list > .ev-comment:visible').length;
	var countOfComment = $('.comments-list > .ev-comment').length;
	var tmp = 0;
	for(i = shownComment; i <= countOfShownComment+step && i <= countOfComment; i++){
		$('.comments-list > .ev-comment').eq(i).show();
		if(i > countOfShownComment)tmp++;
		if(i+1 == countOfShownComment+step){
			$('.loading-panel').hide();
			backToScrollPosition();
		}
	}
	countOfShownComment += tmp;
	if(shownComment+step > countOfComment){
		$('.comment-cont > .show-more').hide();
		$('.loading-panel').hide();
		backToScrollPosition();
	}
}

var scrollPosition = -1;
function backToScrollPosition(){
	if(scrollPosition != -1){
		$(document).scrollTop(scrollPosition);
		scrollPosition = -1;
	}
}