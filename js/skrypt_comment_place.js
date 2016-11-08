var commentId = -1;
function add_comment(){
	$.ajax({
	   type: "GET",
	   url: "addComment.php",
	   data: $("#addNewComment").serialize(), // serializes the form's elements.
	   success: function(data)
	   {
		   commentId = data;
		   refresh_comment($("#id_item").val());
		   add_ratings_vals();
		   $("#addNewComment textarea").val("");
	   }
	});
}

function add_ratings_vals(){
	$(".rating-stars").each(function(index){
		var idR = $(this).data('id');
		var val = $(this).data('val');
		add_rating_val(idR, val);
		if(index+1 == $(".rating-stars").length){
			for(var i = 0; i < 6; i++){
				if(3 == i){
					$(".rating-stars").addClass('r-s-'+3);
					$(".rating-stars").attr('data-val', 3);
				}else{
					$(".rating-stars").removeClass('r-s-'+i);
				}
			}
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
	   }
	});
}

$(document).ready(function(){
	$(".rating-stars img").click(function(){
		var val = $(this).data('val');
		for(var i = 0; i < 6; i++){
			if(val == i){
				$(this).parent().addClass('r-s-'+val);
				$(this).parent().attr('data-val', val);
			}else{
				$(this).parent().removeClass('r-s-'+i);
			}
		}
	});
});