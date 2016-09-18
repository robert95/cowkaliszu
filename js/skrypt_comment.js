function add_comment(){
	$.ajax({
	   type: "GET",
	   url: "addComment.php",
	   data: $("#addNewComment").serialize(), // serializes the form's elements.
	   success: function(data)
	   {
		   refresh_comment($("#id_item").val());
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
	   }
	});
}