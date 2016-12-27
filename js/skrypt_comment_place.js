var commentId = -1;
function add_comment(){
	$('.loading-panel').show();
	$.ajax({
	   type: "GET",
	   url: "addComment.php",
	   data: $("#addNewComment").serialize(),
	   success: function(data)
	   {
			commentId = data;
			add_ratings_vals();
			$("#addNewComment textarea").val("");
	   }
	});
}

function add_ratings_vals(){
	$(".rating-stars").each(function(index){
		var idR = $(this).attr('data-id');
		var val = $(this).attr('data-val');
		console.log(index + ": " + idR + " - " + val);
		add_rating_val(idR, val);
		if(index+1 == $(".rating-stars").length){
			for(var i = 0; i < 6; i++){
				if(3 == i){
					$(".rating-stars").addClass('r-s-' + 3);
					$(".rating-stars").attr('data-val', 3);
				}else{
					$(".rating-stars").removeClass('r-s-'+i);
				}
			}
			refresh_comment($("#id_item").val());
		}
	});
}

function add_rating_val(idR, val){
	$.ajax({
	   type: "GET",
	   url: "addRatingVal.php?com="+commentId+"&val="+val+"&idR="+idR,
	   success: function(data)
	   {
		
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
		   refresh_comment($("#id_item").val());
	   }
	});
}

function refresh_comment(id){
	$.ajax({
	   type: "GET",
	   url: "getComments.php?type=2&id="+id,
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
	$(".rating-stars img").click(function(){
		var val = $(this).attr('data-val');
		for(var i = 0; i < 6; i++){
			if(val == i){
				$(this).parent().addClass('r-s-'+val);
				$(this).parent().attr('data-val', val);
			}else{
				$(this).parent().removeClass('r-s-'+i);
			}
		}
	});
	$(".rating-stars img").hover(function(){
		var val = $(this).attr('data-val');
		for(var i = 0; i < 6; i++){
			if(val == i){
				$(this).parent().addClass('r-s-'+val);
			}else{
				$(this).parent().removeClass('r-s-'+i);
			}
		}
	},function(){
		var val = $(this).parent().attr('data-val');
		for(var i = 0; i < 6; i++){
			if(val == i){
				$(this).parent().addClass('r-s-'+val);
			}else{
				$(this).parent().removeClass('r-s-'+i);
			}
		}
	});
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
		   form.children(" textarea").val("");
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
	console.log(scrollPosition);
	if(scrollPosition != -1){
		$(document).scrollTop(scrollPosition);
		scrollPosition = -1;
	}
}